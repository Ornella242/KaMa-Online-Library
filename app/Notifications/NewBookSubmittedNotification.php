<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Book;

class NewBookSubmittedNotification extends Notification
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
        return ['database','mail'];
    }


     /**
     * Notification dans le dashboard
     */
    public function toDatabase(object $notifiable): array
    {
        return [

            'book_id' => $this->book->id,
            'title' => $this->book->title,
            'author' => $this->book->author->firstname
                    .' '.
                    $this->book->author->lastname,
            'message' => 'Un nouveau livre est disponible pour vérification éditoriale.',
            'url' => route('admin.books.show',$this->book->id)
        ];
    }


    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {

        return (new MailMessage)

            ->subject(
                'Nouveau livre soumis sur KaMa'
            )

            ->view(
                'emails.books.book-submitted',
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
