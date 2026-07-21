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
    public function toMail(object $notifiable): MailMessage
    {

        return (new MailMessage)

            ->subject(
                'Nouveau livre resoumis - KaMa'
            )
            ->greeting(
                'Bonjour '.$notifiable->firstname.','
            )
            ->line(
                'Un auteur a effectué des corrections et a resoumis son livre pour validation.'
            )
            ->line(
                'Livre : '.$this->book->title
            )
            ->line(
                'Auteur : '
                .$this->book->author->firstname
                .' '
                .$this->book->author->lastname
            )
            ->action(
                'Voir le livre',
                route(
                    'admin.books.show',
                    $this->book
                )
            )
            ->line(
                'Merci de procéder à une nouvelle vérification éditoriale.'
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
