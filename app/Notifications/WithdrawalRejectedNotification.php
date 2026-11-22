<?php

namespace App\Notifications;

use App\Models\Withdrawal;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class WithdrawalRejectedNotification extends Notification
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
            ->subject('Votre demande de retrait KaMa a été refusée (' . $amountFormatted . ' €)')
            ->view('emails.withdrawals.rejected', [
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
            'title' => 'Demande de retrait refusée',
            'message' => 'Votre demande de retrait #'.$this->withdrawal->id.' a été refusée. '
                . 'Le montant de '
                . number_format((float) $this->withdrawal->amount, 2, ',', ' ')
                . ' € a été recrédité sur votre portefeuille.',
            'withdrawal_id' => $this->withdrawal->id,
            'amount' => $this->withdrawal->amount,
            'net_amount' => $this->withdrawal->net_amount,
            'type' => 'withdrawal_rejected',
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
