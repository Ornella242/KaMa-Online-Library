<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class BookPublishedNotification extends Notification
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

            ->subject('Votre livre est publié sur KaMa')
            ->greeting('Bonjour '.$notifiable->firstname)
            ->line(
                'Votre livre "'.$this->book->title.'" a été validé et publié sur KaMa.'
            )
            ->action(
                'Voir mon livre',
                route('books.show',$this->book)
            )
            ->line(
                'Merci de contribuer à la bibliothèque KaMa.'
            );
    }

    public function toDatabase($notifiable)
    {

        return [

            'title'=>'Livre publié',

            'message'=>
                'Votre livre "'.$this->book->title.'" est maintenant disponible sur KaMa.',

            'book_id'=>$this->book->id,

            'type'=>'book_published'

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
