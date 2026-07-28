<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use App\Models\Book;
use Illuminate\Notifications\Notification;

class BookUnderReviewNotification extends Notification
{
    use Queueable;

    public $book;
    /**
     * Create a new notification instance.
     */
      public function __construct(Book $book)
    {
        $this->book = $book;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail','database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)

            ->subject(
                'Votre livre est en cours de vérification - KaMa'
            )

            ->view(
                'emails.books.under-review',
                [
                    'user' => $notifiable,
                    'book' => $this->book
                ]
            );
    }

    public function toDatabase($notifiable)
    {

        return [

            'title' => 'Livre en vérification',
            'message' => 
            'Votre livre "'.$this->book->title.'" est maintenant sous vérification éditoriale.',
            'book_id' => $this->book->id,
            'url' => route(
                'writer.books.show',
                $this->book
            )

        ];

    }


    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
