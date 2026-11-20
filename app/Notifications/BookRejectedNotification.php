<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class BookRejectedNotification extends Notification
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
    public function toMail(object $notifiable): MailMessage
    {

        return (new MailMessage)

            ->subject(
                'Votre livre nécessite des modifications - KaMa'
            )

            ->view(
                'emails.books.rejected',
                [
                    'user' => $notifiable,
                    'book' => $this->book
                ]
            );

    }

     public function toDatabase($notifiable)
    {

        return [

            'title'=>'Livre rejeté',

            'message'=>
            'Votre livre "'.$this->book->title.'" a été rejeté.',

            'reason'=>$this->book->rejection_reason,

            'book_id'=>$this->book->id,

            'type'=>'book_rejected'

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
