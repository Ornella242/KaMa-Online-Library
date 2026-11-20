<?php

namespace App\Notifications;

use App\Models\AudiobookRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewAudiobookRequestNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        private AudiobookRequest $audiobookRequest
    ) {
    }

    /**
     * Canaux utilisés :
     * - database : notification dans KaMa
     * - mail     : email à l'administrateur
     */
    public function via(object $notifiable): array
    {
        return [
            'database',
            'mail',
        ];
    }

    /**
     * Notification enregistrée dans la base de données.
     */
    public function toDatabase(object $notifiable): array
    {
        $book = $this->audiobookRequest->book;
        $author = $this->audiobookRequest->author;

        return [
            'type' => 'audiobook_request',

            'title' => 'Nouvelle demande d’audiobook',

            'message' => sprintf(
                '%s a effectué une demande d’audiobook pour « %s ». Le paiement a été confirmé.',
                trim($author->firstname . ' ' . $author->lastname),
                $book->title
            ),

            'book_id' => $book->id,

            'audiobook_request_id' => $this->audiobookRequest->id,

            'author_id' => $author->id,

            'amount' => (float) $this->audiobookRequest->total_amount,

            'status' => $this->audiobookRequest->status,

            'url' => route(
                'admin.audiobooks.requests.show',
                $this->audiobookRequest
            ),
        ];
    }

    /**
     * Email envoyé à l'administrateur.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $book = $this->audiobookRequest->book;
        $author = $this->audiobookRequest->author;

        return (new MailMessage)
            ->subject(
                'Nouvelle demande d’audiobook — ' . $book->title
            )
            ->view(
                'emails.audiobooks.new-request',
                [
                    'book' => $book,
                    'author' => $author,
                    'audiobookRequest' => $this->audiobookRequest,
                ]
            );
    }
}