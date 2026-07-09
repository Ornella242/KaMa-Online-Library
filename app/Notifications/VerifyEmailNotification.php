<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\URL;
use Illuminate\Auth\Notifications\VerifyEmail;

class VerifyEmailNotification extends VerifyEmail
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    // public function via(object $notifiable): array
    // {
    //     return ['mail'];
    // }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail($notifiable)
    {
        $verificationUrl = $this->verificationUrl($notifiable);

        return (new MailMessage)
            ->mailer('smtp')
            ->subject('Bienvenue sur KaMa Online Library')
            ->greeting('Bonjour '.$notifiable->firstname.' 👋')
            ->line('Bienvenue sur KaMa Online Library !')
            ->line('Merci de nous avoir rejoints.')
            ->line('Veuillez confirmer votre adresse email afin d\'activer votre compte.')
            ->action('Vérifier mon adresse email', $verificationUrl)
            ->line('Ce lien expirera dans 60 minutes.')
            ->salutation('L\'équipe KaMa Online Library');
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
