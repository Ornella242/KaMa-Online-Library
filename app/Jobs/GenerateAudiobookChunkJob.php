<?php

namespace App\Jobs;

use App\Jobs\AssembleAudiobookJob;
use App\Models\AudiobookChunk;
use App\Services\Audiobook\ElevenLabsService;
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

class GenerateAudiobookChunkJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public int $tries = 1;

    public int $timeout = 180;

    public function __construct(
        public int $chunkId
    ) {
    }

    public function handle(ElevenLabsService $elevenLabs): void
    {
        /*
        |--------------------------------------------------------------------------
        | 1. Réserver le chunk pour éviter les doubles traitements
        |--------------------------------------------------------------------------
        */

        $claimed = DB::transaction(function (): bool {
            $chunk = AudiobookChunk::whereKey($this->chunkId)
                ->lockForUpdate()
                ->first();

            if (!$chunk) {
                return false;
            }

            if (in_array($chunk->status, [
                'completed',
                'generating',
                'failed',
            ], true)) {
                return false;
            }

            $chunk->update([
                'status' => 'generating',
                'started_at' => now(),
                'error_message' => null,
            ]);

            return true;
        });

        if (!$claimed) {
            return;
        }

        $chunk = AudiobookChunk::with([
            'section.audiobook.book',
        ])->find($this->chunkId);

        if (!$chunk) {
            return;
        }

        $section = $chunk->section;
        $audiobook = $section?->audiobook;

        if (!$section || !$audiobook || !$audiobook->book) {
            $this->failChunk(
                $chunk,
                'La section, l’audiobook ou le livre associé est introuvable.'
            );

            return;
        }

        /*
        |--------------------------------------------------------------------------
        | 2. Vérifier l'état de l'audiobook et le texte
        |--------------------------------------------------------------------------
        */

        if (in_array($audiobook->status, [
            'cancelled',
            'completed',
            'failed',
        ], true)) {
            return;
        }

        $text = (string) $chunk->text;

        if (trim($text) === '') {
            $this->failChunk(
                $chunk,
                'Le texte du chunk est vide.'
            );

            return;
        }

        try {
            /*
            |--------------------------------------------------------------------------
            | 3. Générer le MP3 avec ElevenLabs
            |--------------------------------------------------------------------------
            */

            $result = $elevenLabs->generateSpeechWithMetadata(
                $text,
                $audiobook->voice_id
            );

            if (
                !isset($result['audio']) ||
                !is_string($result['audio']) ||
                $result['audio'] === ''
            ) {
                throw new RuntimeException(
                    'ElevenLabs n’a retourné aucun contenu audio exploitable.'
                );
            }

            /*
            |--------------------------------------------------------------------------
            | 4. Préparer le chemin du fichier
            |--------------------------------------------------------------------------
            */

            $bookSlug = Str::limit(
                Str::slug($audiobook->book->title),
                80,
                ''
            );

            $path = sprintf(
                'elevenlabs-audiobooks/%s/sections/chapitre-%d/chunks/morceau-%d.mp3',
                $bookSlug,
                $section->position,
                $chunk->position
            );

            $disk = Storage::disk('local');

            /*
            |--------------------------------------------------------------------------
            | 5. Enregistrer le MP3 et mettre à jour les statuts
            |--------------------------------------------------------------------------
            |
            | Le verrou sur l'audiobook protège les compteurs et le déclenchement
            | de l'assemblage. Le fichier est vérifié avant de terminer le chunk.
            |--------------------------------------------------------------------------
            */

            $shouldAssemble = DB::transaction(function () use (
                $disk,
                $path,
                $result,
                $text
            ): bool {
                $chunk = AudiobookChunk::whereKey($this->chunkId)
                    ->lockForUpdate()
                    ->first();

                if (!$chunk || $chunk->status !== 'generating') {
                    return false;
                }

                $section = $chunk->section;

                if (!$section) {
                    throw new RuntimeException(
                        'La section du chunk est introuvable.'
                    );
                }

                $audiobook = DB::table('audiobooks')
                    ->where('id', $section->audiobook_id)
                    ->lockForUpdate()
                    ->first();

                if (!$audiobook) {
                    throw new RuntimeException(
                        'L’audiobook associé est introuvable.'
                    );
                }

                /*
                 * Ne jamais finaliser un résultat tardif après une annulation,
                 * un échec ou la fin de l'audiobook.
                 */
                if (in_array($audiobook->status, [
                    'cancelled',
                    'completed',
                    'failed',
                ], true)) {
                    return false;
                }

                $saved = $disk->put($path, $result['audio']);

                if (
                    !$saved ||
                    !$disk->exists($path) ||
                    $disk->size($path) <= 0
                ) {
                    throw new RuntimeException(
                        'Le fichier MP3 du morceau n’a pas été enregistré correctement.'
                    );
                }

                $characterCost = (int) (
                    $result['character_cost'] ?? mb_strlen($text)
                );

                $chunk->update([
                    'status' => 'completed',
                    'character_cost' => $characterCost,
                    'audio_path' => $path,
                    'request_id' => $result['request_id'] ?? null,
                    'trace_id' => $result['trace_id'] ?? null,
                    'completed_at' => now(),
                    'error_message' => null,
                ]);

                /*
                 * Mise à jour atomique des compteurs sous le verrou
                 * de l'audiobook.
                 */
                DB::table('audiobooks')
                    ->where('id', $audiobook->id)
                    ->update([
                        'generated_characters' =>
                            ((int) ($audiobook->generated_characters ?? 0))
                            + $characterCost,

                        'completed_chunks' =>
                            ((int) ($audiobook->completed_chunks ?? 0)) + 1,

                        'updated_at' => now(),
                    ]);

                /*
                 * Mettre à jour le statut de la section.
                 */
                $totalSectionChunks = AudiobookChunk::where(
                    'audiobook_section_id',
                    $section->id
                )->count();

                $completedSectionChunks = AudiobookChunk::where(
                    'audiobook_section_id',
                    $section->id
                )->where('status', 'completed')->count();

                if (
                    $totalSectionChunks > 0 &&
                    $completedSectionChunks === $totalSectionChunks
                ) {
                    $section->update([
                        'status' => 'completed',
                        'error_message' => null,
                    ]);
                } else {
                    $section->update([
                        'status' => 'generating',
                    ]);
                }

                /*
                 * Ne passer à assembling qu'une seule fois.
                 * Le verrou empêche deux chunks de déclencher
                 * simultanément deux assemblages.
                 */
                $totalChunks = AudiobookChunk::whereHas(
                    'section',
                    fn ($query) => $query->where(
                        'audiobook_id',
                        $audiobook->id
                    )
                )->count();

                $completedChunks = AudiobookChunk::whereHas(
                    'section',
                    fn ($query) => $query->where(
                        'audiobook_id',
                        $audiobook->id
                    )
                )->where('status', 'completed')->count();

                if (
                    $totalChunks > 0 &&
                    $completedChunks === $totalChunks &&
                    $audiobook->status !== 'assembling'
                ) {
                    DB::table('audiobooks')
                        ->where('id', $audiobook->id)
                        ->update([
                            'status' => 'assembling',
                            'error_message' => null,
                            'updated_at' => now(),
                        ]);

                    return true;
                }

                return false;
            });

            /*
            |--------------------------------------------------------------------------
            | 6. Déclencher l'assemblage après validation de la transaction
            |--------------------------------------------------------------------------
            */

            if ($shouldAssemble) {
                AssembleAudiobookJob::dispatch(
                    $audiobook->id
                )->afterCommit();
            }
        } catch (Throwable $e) {
            $this->failChunk(
                $chunk,
                $e->getMessage()
            );

            throw $e;
        }
    }

    /**
     * Marque un chunk en échec sans remplacer un statut terminal
     * de l'audiobook par un autre statut.
     */
    private function failChunk(
        AudiobookChunk $chunk,
        string $message
    ): void {
        DB::transaction(function () use ($chunk, $message): void {
            $lockedChunk = AudiobookChunk::whereKey($chunk->id)
                ->lockForUpdate()
                ->first();

            if (!$lockedChunk || $lockedChunk->status === 'completed') {
                return;
            }

            $lockedChunk->update([
                'status' => 'failed',
                'error_message' => $message,
            ]);

            $section = $lockedChunk->section;

            if (!$section) {
                return;
            }

            $section->update([
                'status' => 'failed',
                'error_message' => $message,
            ]);

            $audiobookId = $section->audiobook_id;

            $audiobook = DB::table('audiobooks')
                ->where('id', $audiobookId)
                ->lockForUpdate()
                ->first();

            if (
                !$audiobook ||
                in_array($audiobook->status, [
                    'cancelled',
                    'completed',
                ], true)
            ) {
                return;
            }

            DB::table('audiobooks')
                ->where('id', $audiobookId)
                ->update([
                    'status' => 'failed',
                    'error_message' => $message,
                    'updated_at' => now(),
                ]);
        });
    }

    /**
     * Dernier filet de sécurité lorsque le job échoue définitivement.
     */
    public function failed(?Throwable $exception): void
    {
        $chunk = AudiobookChunk::find($this->chunkId);

        if (!$chunk) {
            return;
        }

        $this->failChunk(
            $chunk,
            $exception?->getMessage()
                ?? 'Erreur inconnue lors de la génération.'
        );
    }
}
