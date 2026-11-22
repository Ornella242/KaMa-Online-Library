<?php

namespace App\Notifications;

use App\Models\Withdrawal;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class WithdrawalCompletedNotification extends Notification
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
        $netFormatted = number_format((float) $this->withdrawal->net_amount, 2, ',', ' ');

        return (new MailMessage)
            ->subject('Votre retrait KaMa a été effectué (' . $netFormatted . ' €)')
            ->view('emails.withdrawals.completed', [
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
            'title' => 'Retrait terminé',
            'message' => 'Votre demande de retrait #'.$this->withdrawal->id.' a été effectuée. '
                . 'Montant net versé : '
                . number_format((float) $this->withdrawal->net_amount, 2, ',', ' ')
                . ' €.',
            'withdrawal_id' => $this->withdrawal->id,
            'amount' => $this->withdrawal->amount,
            'net_amount' => $this->withdrawal->net_amount,
            'payout_reference' => $this->withdrawal->payout_reference,
            'type' => 'withdrawal_completed',
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
