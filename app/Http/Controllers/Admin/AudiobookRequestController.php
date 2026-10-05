<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Audiobook;
use App\Models\AudiobookRequest;
use Illuminate\Http\Request;
use App\Services\Audiobook\ElevenLabsService;
use App\Models\Book;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\Process\Process;
use App\Services\Audiobook\AudiobookGenerationService;

class AudiobookRequestController extends Controller
{
    public function index()
    {
        abort_unless(
            auth()->user()->hasAdminPermission('books.audio.generate'),
            403
        );

        $requests = AudiobookRequest::query()
            ->with([
                'book',
                'author',
                'payment',
                'audiobook',
            ])
            ->latest()
            ->paginate(20);

        /*
        |--------------------------------------------------------------------------
        | Voices ElevenLabs
        |--------------------------------------------------------------------------
        */

        $voicesResponse = app(ElevenLabsService::class)
            ->getVoices();

        $voiceNames = collect($voicesResponse['voices'] ?? [])
            ->mapWithKeys(function ($voice) {
                return [
                    $voice['voice_id'] => $voice['name'] ?? $voice['voice_id'],
                ];
            });

        return view(
            'admin.audiobooks.requests.index',
            compact(
                'requests',
                'voiceNames'
            )
        );
    }


    public function show(AudiobookRequest $audiobookRequest)
    {
        abort_unless(
            auth()->user()->hasAdminPermission('books.audio.generate'),
            403
        );

        $audiobookRequest->load([
            'book.category',
            'author',
            'payment',
            'audiobook.sections.chunks',
        ]);


        $voicesResponse = app(ElevenLabsService::class)->getVoices();

        $voiceNames = collect($voicesResponse['voices'] ?? [])
            ->mapWithKeys(function ($voice) {
                return [
                    $voice['voice_id'] => $voice['name'] ?? $voice['voice_id'],
                ];
            });


        return view(
            'admin.audiobooks.requests.show',
            compact(
                'audiobookRequest',
                'voiceNames'
            )
        );
    }

    public function generate(
        AudiobookRequest $audiobookRequest
        ) {
        abort_unless(
            auth()->user()->hasAdminPermission('books.audio.generate'),
            403
        );

        abort_unless(
            $audiobookRequest->status === AudiobookRequest::STATUS_PAID,
            409,
            'Cette demande d’audiobook ne peut pas encore être générée.'
        );

        abort_unless(
            !empty($audiobookRequest->voice_id),
            422,
            'Aucune voix n’est associée à cette demande.'
        );

        try {

            /*
            |--------------------------------------------------------------------------
            | 1. Récupérer ou créer l'audiobook
            |--------------------------------------------------------------------------
            */

            $audiobook = Audiobook::firstOrCreate(
                [
                    'book_id' => $audiobookRequest->book_id,
                ],
                [
                    'voice_id' => $audiobookRequest->voice_id,
                ]
            );

            /*
            |--------------------------------------------------------------------------
            | 2. Synchroniser la voix
            |--------------------------------------------------------------------------
            */

            if (
                $audiobook->voice_id !==
                $audiobookRequest->voice_id
            ) {
                $audiobook->update([
                    'voice_id' =>
                        $audiobookRequest->voice_id,
                ]);
            }

            /*
            |--------------------------------------------------------------------------
            | 3. Préparer les chunks si nécessaire
            |--------------------------------------------------------------------------
            */

            $service = app(
                \App\Services\Audiobook\AudiobookGenerationService::class
            );

            if (!$audiobook->chunks()->exists()) {

                $audiobook = $service->prepare(
                    $audiobook->book,
                    $audiobook->voice_id
                );
            }

            /*
            |--------------------------------------------------------------------------
            | 4. Lier la demande à l'audiobook
            |--------------------------------------------------------------------------
            */

            $audiobookRequest->update([
                'status' =>
                    AudiobookRequest::STATUS_QUEUED,

                'audiobook_id' =>
                    $audiobook->id,
            ]);

            /*
            |--------------------------------------------------------------------------
            | 5. Lancer les jobs ElevenLabs
            |--------------------------------------------------------------------------
            */

            dd([
                'request_id' => $audiobookRequest->id,
                'book_id' => $audiobookRequest->book_id,
                'audiobook_id' => $audiobook->id,
                'audiobook_status' => $audiobook->status,
                'voice_id' => $audiobook->voice_id,
                'chunks_count' => $audiobook->chunks()->count(),
                'total_chunks' => $audiobook->total_chunks,
                'total_characters' => $audiobook->total_characters,
            ]);

            $service->generateAudiobook(
                $audiobook
            );

            /*
            |--------------------------------------------------------------------------
            | 6. Recharger l'audiobook
            |--------------------------------------------------------------------------
            */

            $audiobook->refresh();

            /*
            |--------------------------------------------------------------------------
            | 7. Synchroniser immédiatement le statut
            |--------------------------------------------------------------------------
            */

            if ($audiobook->status === 'generating') {

                $audiobookRequest->update([
                    'status' =>
                        AudiobookRequest::STATUS_GENERATING,
                ]);
            }

            return redirect()
                ->route(
                    'admin.audiobooks.requests.show',
                    $audiobookRequest
                )
                ->with(
                    'success',
                    'La génération de l’audiobook a été lancée.'
                );

        } catch (\Throwable $e) {

            report($e);

            return redirect()
                ->back()
                ->with(
                    'error',
                    'Impossible de lancer la génération : '
                    . $e->getMessage()
                );
        }
    }

    public function publish(
        AudiobookRequest $audiobookRequest
        ) {
        abort_unless(
            auth()->user()->hasAdminPermission('books.audio.generate'),
            403
        );

        $audiobookRequest->load([
            'book',
            'audiobook',
            'publishedBook',
        ]);

        abort_unless(
            $audiobookRequest->status === AudiobookRequest::STATUS_COMPLETED,
            409,
            'Cet audiobook n’est pas encore terminé.'
        );

        abort_unless(
            $audiobookRequest->audiobook,
            404,
            'Aucun audiobook généré n’est associé à cette demande.'
        );

        $audiobook = $audiobookRequest->audiobook;

        abort_unless(
            $audiobook->status === 'completed',
            409,
            'La génération de l’audiobook n’est pas terminée.'
        );

        abort_unless(
            !empty($audiobook->final_audio_path),
            404,
            'Le fichier audio final est introuvable.'
        );


        if ($audiobookRequest->published_book_id) {
            return redirect()
                ->route(
                    'admin.audiobooks.requests.show',
                    $audiobookRequest
                )
                ->with(
                    'info',
                    'Cet audiobook est déjà publié sur KaMa.'
                );
        }

        $disk = Storage::disk('local');

        abort_unless(
            $disk->exists($audiobook->final_audio_path),
            404,
            'Le fichier audio final n’existe plus sur le serveur.'
        );

        $audioAbsolutePath = $disk->path(
            $audiobook->final_audio_path
        );

        $duration = $this->getAudioDuration(
            $audioAbsolutePath
        );

        try {

            $publishedBook = DB::transaction(function () use (
                $audiobookRequest,
                $audiobook,
                $duration,
                $disk
            ) {

                $lockedRequest = AudiobookRequest::query()
                    ->lockForUpdate()
                    ->findOrFail($audiobookRequest->id);

                if ($lockedRequest->published_book_id) {
                    return Book::findOrFail(
                        $lockedRequest->published_book_id
                    );
                }

                $originalBook = $audiobookRequest->book;


                $publishedBook = Book::create([
                    'user_id' => $originalBook->user_id,

                    'category_id' => $originalBook->category_id,
                    'subcategory_id' => $originalBook->subcategory_id,

                    'title' => $originalBook->title,

                    'short_description' =>
                        $originalBook->short_description,

                    'long_description' =>
                        $originalBook->long_description,

                    'preview_type' => 'text',

                    'preview_start_page' => null,
                    'preview_end_page' => null,

                    'type' => 'audio',

                    'price' => $originalBook->price,

                    'pages' => null,

                    'duration' => $duration,

                    'language' => $originalBook->language,

                    'publication_year' =>
                        $originalBook->publication_year,

                
                    'cover_image' =>
                        $originalBook->cover_image,

                
                    'file_path' =>
                        $audiobook->final_audio_path,

                    'file_type' => 'mp3',

                    'original_file_name' =>
                        basename(
                            $audiobook->final_audio_path
                        ),

                    'file_size' =>
                        $disk->size(
                            $audiobook->final_audio_path
                        ),

                    'status' => Book::STATUS_PUBLISHED,

                    'copyright_accepted' => true,

                    'copyright_accepted_at' => now(),
                ]);


                $lockedRequest->update([
                    'published_book_id' =>
                        $publishedBook->id,
                ]);

                return $publishedBook;
            });

        } catch (\Throwable $e) {

            report($e);

            return redirect()
                ->back()
                ->with(
                    'error',
                    'Impossible de publier l’audiobook : '
                    . $e->getMessage()
                );
        }

        $audiobookRequest->refresh();

        $audiobookRequest->author->notify(
            new AudiobookPublishedNotification(
                $audiobookRequest
            )
        );

        return redirect()
            ->route(
                'admin.audiobooks.requests.show',
                $audiobookRequest
            )
            ->with(
                'success',
                'L’audiobook a été publié avec succès sur KaMa.'
            );
    }

    private function getAudioDuration( string $audioPath
        ): string 
    {

        $process = new Process([
            'ffprobe',
            '-v',
            'error',
            '-show_entries',
            'format=duration',
            '-of',
            'default=noprint_wrappers=1:nokey=1',
            $audioPath,
        ]);

        $process->setTimeout(60);

        $process->run();

        if (!$process->isSuccessful()) {
            throw new \RuntimeException(
                'Impossible de déterminer la durée du fichier audio.'
            );
        }

        $seconds = (float) trim(
            $process->getOutput()
        );

        if ($seconds <= 0) {
            throw new \RuntimeException(
                'La durée du fichier audio est invalide.'
            );
        }

        $totalSeconds = (int) round($seconds);

        $hours = intdiv(
            $totalSeconds,
            3600
        );

        $minutes = intdiv(
            $totalSeconds % 3600,
            60
        );

        $remainingSeconds =
            $totalSeconds % 60;

        return sprintf(
            '%02d:%02d:%02d',
            $hours,
            $minutes,
            $remainingSeconds
        );
    }
}