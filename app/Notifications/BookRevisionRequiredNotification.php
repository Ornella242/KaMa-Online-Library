<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class BookRevisionRequiredNotification extends Notification
{
    use Queueable;
    public $book;

    /**
     * Create a new notification instance.
     */
     public function __construct($book)
    {
        $this->book = $book;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via($notifiable)
    {
        return [
            'database',
            'mail'
        ];
    }



    public function toDatabase($notifiable)
    {

        return [

            'title'=>'Modifications requises',

            'message'=>
            'Votre livre "'.$this->book->title.
            '" nécessite des corrections.',


            'url'=>route(
                'writer.books.edit',
                $this->book
            )

        ];

    }



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
