<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class BookResubmittedNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
     public $book;

    public function __construct($book)
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
        return [
            'database',
            'mail'
        ];
    }

    /**
     * Get the mail representation of the notification.
     */
     /**
     * Notification dans la cloche
     */
    public function toDatabase(object $notifiable): array
    {
        return [

            'title' => 'Livre resoumis',

            'message' =>
                'L\'auteur '
                .$this->book->author->firstname
                .' a resoumis le livre "'
                .$this->book->title
                .'" après correction.',


            'url' => route(
                'admin.books.show',
                $this->book
            ),

            'icon' => 'bi bi-arrow-repeat'

        ];
    }



    /**
     * Email administrateur
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)

            ->subject(
                'Votre livre nécessite des modifications - KaMa'
            )

            ->view(
                'emails.books.needs-modifications',
                [
                    'user' => $notifiable,
                    'book' => $this->book
                ]
            );
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
