<?php

namespace App\Notifications;

use App\Models\Withdrawal;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class WithdrawalInitiatedNotification extends Notification
{
    use Queueable;

    public function __construct(public Withdrawal $withdrawal)
    {
    }

    /**
     * Canaux de diffusion.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    /**
     * Représentation email de la notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $amountFormatted = number_format((float) $this->withdrawal->amount, 2, ',', ' ');

        return (new MailMessage)
            ->subject('Demande de retrait initiée sur votre compte KaMa (' . $amountFormatted . ' €)')
            ->view('emails.withdrawals.initiated', [
                'user' => $notifiable,
                'withdrawal' => $this->withdrawal,
            ]);
    }

    /**
     * Enregistrement dans la table des notifications (dashboard / barre latérale).
     *
     * @return array<string, mixed>
     */
    public function toDatabase(object $notifiable): array
    {
        return [
            'title' => 'Demande de retrait initiée',
            'message' => 'Une demande de retrait de '
                . number_format((float) $this->withdrawal->amount, 2, ',', ' ')
                . ' € a été initiée sur votre compte KaMa (#'.$this->withdrawal->id.').',
            'withdrawal_id' => $this->withdrawal->id,
            'amount' => $this->withdrawal->amount,
            'net_amount' => $this->withdrawal->net_amount,
            'url' => route('writer.wallet'),
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return $this->toDatabase($notifiable);
    }
}
