<?php

namespace App\Jobs;

use App\Models\Audiobook;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Models\AudiobookRequest;
use RuntimeException;
use Symfony\Component\Process\Process;

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

        if (
            $audiobook->status === 'cancelled' ||
            $audiobook->status === 'completed'
        ) {
            return;
        }

        if ($audiobook->status !== 'assembling') {
            return;
        }

        $chunks = $audiobook->sections
            ->sortBy('position')
            ->flatMap(function ($section) {
                return $section->chunks
                    ->sortBy('position');
            })
            ->values();

        if ($chunks->isEmpty()) {
            throw new RuntimeException(
                'Aucun chunk audio à assembler.'
            );
        }

        foreach ($chunks as $chunk) {

            if (
                $chunk->status !== 'completed' ||
                !$chunk->audio_path
            ) {
                throw new RuntimeException(
                    'Tous les chunks audio ne sont pas prêts.'
                );
            }

            if (
                !Storage::disk('local')
                    ->exists($chunk->audio_path)
            ) {
                throw new RuntimeException(
                    'Fichier audio introuvable pour le chunk '
                    . $chunk->id
                    . '.'
                );
            }
        }

        $bookSlug = Str::limit(
            Str::slug($audiobook->book->title),
            80,
            ''
        );

        $directory =
            'elevenlabs-audiobooks/' . $bookSlug;

        Storage::disk('local')->makeDirectory(
            $directory
        );

        $concatRelativePath =
            $directory . '/ffmpeg-input.txt';

        $concatFile =
            Storage::disk('local')
                ->path($concatRelativePath);

        $finalRelativePath =
           $directory . '/' . $bookSlug . '.mp3';

        $finalAbsolutePath =
            Storage::disk('local')
                ->path($finalRelativePath);

        $lines = [];

        foreach ($chunks as $chunk) {

            $absolutePath =
                Storage::disk('local')
                    ->path($chunk->audio_path);

            $escapedPath =
                str_replace(
                    [
                        '\\',
                        "'",
                    ],
                    [
                        '\\\\',
                        "'\\''",
                    ],
                    $absolutePath
                );

            $lines[] =
                "file '{$escapedPath}'";
        }

        file_put_contents(
            $concatFile,
            implode(PHP_EOL, $lines) . PHP_EOL
        );

        $process = new Process([
            'ffmpeg',
            '-y',
            '-f',
            'concat',
            '-safe',
            '0',
            '-i',
            $concatFile,
            '-c',
            'copy',
            $finalAbsolutePath,
        ]);

        $process->setTimeout(300);

       try {

            $process->run();

            if (!$process->isSuccessful()) {

                throw new RuntimeException(
                    'FFmpeg a échoué lors de l’assemblage : '
                    . $process->getErrorOutput()
                );
            }

        } finally {

            if (is_file($concatFile)) {
                unlink($concatFile);
            }
        }

        if (
            !Storage::disk('local')
                ->exists($finalRelativePath)
        ) {
            throw new RuntimeException(
                'Le fichier audio final n’a pas été créé.'
            );
        }

       $audiobook->update([
            'status' => 'completed',
            'final_audio_path' => $finalRelativePath,
            'actual_cost' =>
                $audiobook->generated_characters / 1000 * 0.10,
            'completed_at' => now(),
            'error_message' => null,
        ]);

        AudiobookRequest::where(
            'audiobook_id',
            $audiobook->id
        )->update([
            'status' => AudiobookRequest::STATUS_COMPLETED,
        ]);

        
    }

    public function failed(\Throwable $exception): void
    {
        $audiobook = Audiobook::find(
            $this->audiobookId
        );

        if (!$audiobook) {
            return;
        }

        $audiobook->update([
            'status' => 'failed',
            'error_message' =>
                $exception->getMessage(),
        ]);

        AudiobookRequest::where(
            'audiobook_id',
            $audiobook->id
        )->update([
            'status' => AudiobookRequest::STATUS_FAILED,
        ]);
    }
}