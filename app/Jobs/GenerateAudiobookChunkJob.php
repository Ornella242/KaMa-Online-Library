<?php

namespace App\Jobs;

use App\Models\AudiobookChunk;
use App\Services\Audiobook\ElevenLabsService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use RuntimeException;
use Throwable;

class GenerateAudiobookChunkJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    /**
     * Important :
     * on évite les retries automatiques aveugles.
     *
     * Une requête ElevenLabs peut avoir consommé
     * des caractères même si notre application rencontre
     * ensuite une erreur.
     */
    public int $tries = 1;

    public function __construct(
        public int $chunkId
    ) {
    }

    public function handle(
        ElevenLabsService $elevenLabs
    ): void {

        /*
        |--------------------------------------------------------------------------
        | 1. Récupérer le chunk
        |--------------------------------------------------------------------------
        */

        $chunk = AudiobookChunk::with([
            'section.audiobook',
        ])->find($this->chunkId);

        if (!$chunk) {
            return;
        }

        /*
        |--------------------------------------------------------------------------
        | 2. Vérifier si déjà terminé
        |--------------------------------------------------------------------------
        |
        | Protection contre une double génération.
        |
        */

        if ($chunk->status === 'completed') {
            return;
        }

        $section = $chunk->section;

        if (!$section) {
            $this->failChunk(
                $chunk,
                'La section de ce chunk est introuvable.'
            );

            return;
        }

        $audiobook = $section->audiobook;

        if (!$audiobook) {
            $this->failChunk(
                $chunk,
                'L’audiobook de ce chunk est introuvable.'
            );

            return;
        }

        /*
        |--------------------------------------------------------------------------
        | 3. Vérifier que l'audiobook peut encore être généré
        |--------------------------------------------------------------------------
        */

        if (
            in_array(
                $audiobook->status,
                ['cancelled', 'completed'],
                true
            )
        ) {
            return;
        }

        /*
        |--------------------------------------------------------------------------
        | 4. Vérifier que le texte existe
        |--------------------------------------------------------------------------
        */

        if ($chunk->text === '') {
            $this->failChunk(
                $chunk,
                'Le texte du chunk est vide.'
            );

            return;
        }

        /*
        |--------------------------------------------------------------------------
        | 5. Marquer comme "generating"
        |--------------------------------------------------------------------------
        */

        $chunk->update([
            'status' => 'generating',
            'started_at' => now(),
            'error_message' => null,
        ]);

        try {

            /*
            |--------------------------------------------------------------------------
            | 6. Appel ElevenLabs
            |--------------------------------------------------------------------------
            */

            $result =
                $elevenLabs->generateSpeechWithMetadata(
                    $chunk->text,
                    $audiobook->voice_id
                );

            /*
            |--------------------------------------------------------------------------
            | 7. Déterminer le chemin du fichier
            |--------------------------------------------------------------------------
            */

            $path = sprintf(
                'audiobooks/%d/sections/%d/chunks/%d.mp3',
                $audiobook->id,
                $section->position,
                $chunk->position
            );

            /*
            |--------------------------------------------------------------------------
            | 8. Sauvegarder le MP3
            |--------------------------------------------------------------------------
            */

            Storage::disk('local')->put(
                $path,
                $result['audio']
            );

            /*
            |--------------------------------------------------------------------------
            | 9. Mettre à jour le chunk
            |--------------------------------------------------------------------------
            */

            $characterCost =
                (int) (
                    $result['character_cost']
                    ?? mb_strlen($chunk->text)
                );

            DB::transaction(function () use (
                $chunk,
                $section,
                $audiobook,
                $path,
                $characterCost,
                $result
            ) {

                $chunk->update([
                    'status' => 'completed',

                    'character_cost' =>
                        $characterCost,

                    'audio_path' =>
                        $path,

                    'request_id' =>
                        $result['request_id'] ?? null,

                    'trace_id' =>
                        $result['trace_id'] ?? null,

                    'completed_at' =>
                        now(),

                    'error_message' =>
                        null,
                ]);

                /*
                |--------------------------------------------------------------------------
                | Compteurs audiobook
                |--------------------------------------------------------------------------
                */

                $audiobook->increment(
                    'generated_characters',
                    $characterCost
                );

                $audiobook->increment(
                    'completed_chunks'
                );
            });

            /*
            |--------------------------------------------------------------------------
            | 10. Vérifier si la section est terminée
            |--------------------------------------------------------------------------
            */

            $this->updateSectionStatus(
                $section->fresh()
            );

            /*
            |--------------------------------------------------------------------------
            | 11. Vérifier si tout l'audiobook est terminé
            |--------------------------------------------------------------------------
            */

            $this->updateAudiobookStatus(
                $audiobook->fresh()
            );

        } catch (Throwable $e) {

            /*
            |--------------------------------------------------------------------------
            | Erreur
            |--------------------------------------------------------------------------
            */

            $this->failChunk(
                $chunk,
                $e->getMessage()
            );

            throw $e;
        }
    }

    /**
     * Met à jour le statut de la section.
     */
    private function updateSectionStatus(
        $section
    ): void {

        $totalChunks =
            $section->chunks()->count();

        $completedChunks =
            $section->chunks()
                ->where('status', 'completed')
                ->count();

        $failedChunks =
            $section->chunks()
                ->where('status', 'failed')
                ->count();

        if (
            $totalChunks > 0 &&
            $completedChunks === $totalChunks
        ) {

            $section->update([
                'status' => 'completed',
            ]);

            return;
        }

        if ($failedChunks > 0) {

            $section->update([
                'status' => 'failed',
            ]);

            return;
        }

        $section->update([
            'status' => 'generating',
        ]);
    }

    /**
     * Met à jour le statut global de l'audiobook.
     */
    private function updateAudiobookStatus(
        $audiobook
    ): void {

        $totalChunks =
            $audiobook->chunks()->count();

        $completedChunks =
            $audiobook->chunks()
                ->where('status', 'completed')
                ->count();

        $failedChunks =
            $audiobook->chunks()
                ->where('status', 'failed')
                ->count();

        if (
            $totalChunks > 0 &&
            $completedChunks === $totalChunks
        ) {

            $audiobook->update([
                'status' => 'completed',
                'actual_cost' =>
                    $audiobook->generated_characters / 1000 * 0.10,
                'completed_at' => now(),
            ]);

            return;
        }

        if ($failedChunks > 0) {

            $audiobook->update([
                'status' => 'failed',
                'error_message' =>
                    'Un ou plusieurs chunks n’ont pas pu être générés.',
            ]);

            return;
        }

        $audiobook->update([
            'status' => 'generating',
        ]);
    }

    /**
     * Marque un chunk comme échoué.
     */
    private function failChunk(
        AudiobookChunk $chunk,
        string $message
    ): void {

        $chunk->update([
            'status' => 'failed',
            'error_message' => $message,
        ]);

        if ($chunk->section) {

            $chunk->section->update([
                'status' => 'failed',
                'error_message' => $message,
            ]);
        }

        if (
            $chunk->section &&
            $chunk->section->audiobook
        ) {

            $chunk->section->audiobook->update([
                'status' => 'failed',
                'error_message' => $message,
            ]);
        }
    }

    /**
     * Gestion finale d'une exception du Job.
     */
    public function failed(
        ?Throwable $exception
    ): void {

        $chunk = AudiobookChunk::with(
            'section.audiobook'
        )->find($this->chunkId);

        if (!$chunk) {
            return;
        }

        $message =
            $exception?->getMessage()
            ?? 'Erreur inconnue lors de la génération.';

        $this->failChunk(
            $chunk,
            $message
        );
    }
}