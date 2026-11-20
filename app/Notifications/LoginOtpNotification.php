<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class LoginOtpNotification extends Notification
{
    use Queueable;

    public function __construct(public string $code)
    {
    }

    /**
     * Canaux de diffusion : email direct.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Représentation email du code de connexion OTP.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject($this->code . ' est votre code de connexion KaMa')
            ->view('emails.auth.login-otp', [
                'user' => $notifiable,
                'code' => $this->code,
            ]);
    }
}
