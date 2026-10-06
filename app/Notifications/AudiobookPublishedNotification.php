<?php

namespace App\Notifications;

use App\Models\AudiobookRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AudiobookPublishedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public AudiobookRequest $audiobookRequest
    ) {
        $this->audiobookRequest->loadMissing([
            'book',
            'publishedBook',
        ]);
    }

    public function via(object $notifiable): array
    {
        return [
            'database',
            'mail',
        ];
    }

    public function toArray(object $notifiable): array
    {
        $book = $this->audiobookRequest->publishedBook;

        return [
            'type' => 'audiobook_published',

            'title' => 'Votre audiobook est disponible',

            'message' => sprintf(
                'Votre demande de génération d’audiobook pour « %s » a été traitée. Le livre audio est maintenant disponible sur KaMa.',
                $this->audiobookRequest->book->title
            ),

            'book_id' => $book?->id,

            'audiobook_request_id' =>
                $this->audiobookRequest->id,

            'url' => $book
                ? route('writer.books.show', $book)
                : route('writer.books'),

            'status' =>
                $this->audiobookRequest->status,
        ];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $book = $this->audiobookRequest->publishedBook;

        return (new MailMessage)
            ->subject(
                'Votre audiobook est maintenant disponible sur KaMa'
            )
            ->view(
                'emails.audiobooks/published',
                [
                    'user' => $notifiable,
                    'audiobookRequest' => $this->audiobookRequest,
                    'book' => $book,
                    'url' => $book
                        ? route('writer.books.show', $book)
                        : route('writer.books'),
                ]
            );
    }
}