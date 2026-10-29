<?php

namespace App\Jobs;

use App\Models\Audiobook;
use App\Models\AudiobookRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use RuntimeException;
use Throwable;
use ZipArchive;

class AssembleAudiobookJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public int $tries = 1;

    public int $timeout = 300;

    public function __construct(
        public int $audiobookId
    ) {
    }

    public function handle(): void
    {
        $audiobook = Audiobook::with([
            'book',
            'sections.chunks',
        ])->find($this->audiobookId);

        if (!$audiobook) {
            return;
        }

        if (in_array($audiobook->status, [
            'cancelled',
            'completed',
        ], true)) {
            return;
        }

        if ($audiobook->status !== 'assembling') {
            return;
        }

        if (!$audiobook->book) {
            throw new RuntimeException(
                'Le livre associé à cet audiobook est introuvable.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | 1. Récupérer les morceaux dans l'ordre des chapitres
        |--------------------------------------------------------------------------
        */

        $sections = $audiobook->sections->sortBy('position');

        $chunks = $sections
            ->flatMap(function ($section) {
                return $section->chunks->sortBy('position');
            })
            ->values();

        if ($chunks->isEmpty()) {
            throw new RuntimeException(
                'Aucun morceau audio à archiver.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | 2. Vérifier que tous les fichiers MP3 sont disponibles
        |--------------------------------------------------------------------------
        */

        $disk = Storage::disk('local');

        foreach ($chunks as $chunk) {
            if (
                $chunk->status !== 'completed' ||
                blank($chunk->audio_path)
            ) {
                throw new RuntimeException(
                    'Tous les morceaux audio ne sont pas prêts.'
                );
            }

            if (!$disk->exists($chunk->audio_path)) {
                throw new RuntimeException(
                    'Fichier audio introuvable pour le morceau '
                    . $chunk->id
                    . '.'
                );
            }

            if ($disk->size($chunk->audio_path) <= 0) {
                throw new RuntimeException(
                    'Le fichier audio du morceau '
                    . $chunk->id
                    . ' est vide.'
                );
            }
        }

        /*
        |--------------------------------------------------------------------------
        | 3. Préparer le chemin de l'archive ZIP
        |--------------------------------------------------------------------------
        */

        $bookSlug = Str::limit(
            Str::slug($audiobook->book->title),
            80,
            ''
        );

        if ($bookSlug === '') {
            $bookSlug = 'livre';
        }

        $directory = 'elevenlabs-audiobooks/' . $bookSlug;

        $disk->makeDirectory($directory);

        /*
         * L'identifiant de l'audiobook évite que deux archives
         * du même livre utilisent le même nom.
         */
        $zipRelativePath = sprintf(
            '%s/audiobook-%d.zip',
            $directory,
            $audiobook->id
        );

        $zipAbsolutePath = $disk->path($zipRelativePath);

        /*
        |--------------------------------------------------------------------------
        | 4. Créer le ZIP et classer les morceaux par chapitre
        |--------------------------------------------------------------------------
        */

        if (!class_exists(ZipArchive::class)) {
            throw new RuntimeException(
                'L’extension PHP ZipArchive n’est pas disponible.'
            );
        }

        $zip = new ZipArchive();

        $openResult = $zip->open(
            $zipAbsolutePath,
            ZipArchive::CREATE | ZipArchive::OVERWRITE
        );

        if ($openResult !== true) {
            throw new RuntimeException(
                'Impossible de créer l’archive ZIP. Code : '
                . $openResult
            );
        }

        $zipClosed = false;

        try {
            foreach ($sections as $section) {
                $sectionChunks = $section->chunks
                    ->sortBy('position');

                foreach ($sectionChunks as $chunk) {
                    $chapterNumber = (int) $section->position;
                    $chunkNumber = (int) $chunk->position;

                    $archivePath = sprintf(
                        'chapitre-%02d/morceau-%03d.mp3',
                        $chapterNumber,
                        $chunkNumber
                    );

                    $absoluteChunkPath = $disk->path(
                        $chunk->audio_path
                    );

                    if (!$zip->addFile(
                        $absoluteChunkPath,
                        $archivePath
                    )) {
                        throw new RuntimeException(
                            'Impossible d’ajouter le morceau '
                            . $chunk->id
                            . ' à l’archive ZIP.'
                        );
                    }
                }
            }

            if (!$zip->close()) {
                throw new RuntimeException(
                    'Impossible de finaliser l’archive ZIP.'
                );
            }

            $zipClosed = true;
        } finally {
            if (!$zipClosed) {
                $zip->close();
            }
        }

        /*
        |--------------------------------------------------------------------------
        | 5. Vérifier l'archive avant de marquer l'audiobook terminé
        |--------------------------------------------------------------------------
        */

        if (
            !$disk->exists($zipRelativePath) ||
            $disk->size($zipRelativePath) <= 0
        ) {
            throw new RuntimeException(
                'L’archive ZIP est absente ou vide.'
            );
        }

        $verificationZip = new ZipArchive();

        $verificationResult = $verificationZip->open(
            $zipAbsolutePath,
            ZipArchive::CHECKCONS
        );

        if ($verificationResult !== true) {
            throw new RuntimeException(
                'L’archive ZIP créée est invalide.'
            );
        }

        try {
            if ($verificationZip->numFiles !== $chunks->count()) {
                throw new RuntimeException(
                    'Le nombre de fichiers dans le ZIP ne correspond '
                    . 'pas au nombre de morceaux audio attendus.'
                );
            }
        } finally {
            $verificationZip->close();
        }

        /*
        |--------------------------------------------------------------------------
        | 6. Enregistrer le résultat en base de données
        |--------------------------------------------------------------------------
        */

        DB::transaction(function () use (
            $audiobook,
            $zipRelativePath
        ) {
            $lockedAudiobook = Audiobook::query()
                ->lockForUpdate()
                ->findOrFail($audiobook->id);

            if (
                $lockedAudiobook->status === 'cancelled' ||
                $lockedAudiobook->status === 'completed'
            ) {
                return;
            }

            if ($lockedAudiobook->status !== 'assembling') {
                return;
            }

            $lockedAudiobook->update([
                'status' => 'completed',
                'final_audio_path' => $zipRelativePath,
                'actual_cost' =>
                    $lockedAudiobook->generated_characters
                    / 1000
                    * 0.10,
                'completed_at' => now(),
                'error_message' => null,
            ]);

            AudiobookRequest::where(
                'audiobook_id',
                $lockedAudiobook->id
            )->update([
                'status' => AudiobookRequest::STATUS_COMPLETED,
            ]);
        });
    }

    public function failed(Throwable $exception): void
    {
        $audiobook = Audiobook::find($this->audiobookId);

        if (!$audiobook) {
            return;
        }

        if (in_array($audiobook->status, [
            'cancelled',
            'completed',
        ], true)) {
            return;
        }

        $audiobook->update([
            'status' => 'failed',
            'error_message' => $exception->getMessage(),
        ]);

        AudiobookRequest::where(
            'audiobook_id',
            $audiobook->id
        )->update([
            'status' => AudiobookRequest::STATUS_FAILED,
        ]);
    }
}
