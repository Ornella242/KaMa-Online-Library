<?php

namespace App\Services;

use App\Models\Setting;
use App\Models\User;
use App\Models\Withdrawal;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class WithdrawalService
{
    public function __construct(private WalletService $walletService)
    {
    }

    public function commissionPercent(): float
    {
        return round((float) Setting::getValue('withdrawal_commission_percent', 5), 2);
    }

    public function minimumAmount(): float
    {
        return round((float) Setting::getValue('withdrawal_minimum_amount', 10), 2);
    }

    /**
     * @param  array<string, mixed>  $payoutDetails
     */
    public function request(User $author, float $amount, string $method, array $payoutDetails): Withdrawal
    {
        $amount = round($amount, 2);
        $methods = Withdrawal::paymentMethods();

        if (! isset($methods[$method])) {
            throw ValidationException::withMessages([
                'payment_method' => 'Mode de paiement invalide.',
            ]);
        }

        $min = $this->minimumAmount();
        if ($amount < $min) {
            throw ValidationException::withMessages([
                'amount' => "Le montant minimum de retrait est de {$min} $.",
            ]);
        }

        $this->assertPayoutDetails($method, $payoutDetails);

        $commissionPercent = $this->commissionPercent();
        $commissionAmount = round($amount * ($commissionPercent / 100), 2);
        $netAmount = round($amount - $commissionAmount, 2);

        if ($netAmount <= 0) {
            throw ValidationException::withMessages([
                'amount' => 'Après commission, le montant net doit être supérieur à 0.',
            ]);
        }

        $accountNumber = $this->primaryAccountIdentifier($method, $payoutDetails);

        return DB::transaction(function () use ($author, $amount, $method, $payoutDetails, $commissionPercent, $commissionAmount, $netAmount, $accountNumber) {
            $wallet = $this->walletService->ensureWallet($author);
            $wallet = \App\Models\Wallet::query()->lockForUpdate()->findOrFail($wallet->id);

            if ((float) $wallet->balance + 0.0001 < $amount) {
                throw ValidationException::withMessages([
                    'amount' => 'Solde insuffisant. Disponible : $'.number_format((float) $wallet->balance, 2, '.', ','),
                ]);
            }

            $openExists = Withdrawal::query()
                ->where('user_id', $author->id)
                ->whereIn('status', [Withdrawal::STATUS_INITIATED, Withdrawal::STATUS_PROCESSING])
                ->exists();

            if ($openExists) {
                throw ValidationException::withMessages([
                    'amount' => 'Vous avez déjà une demande de retrait en cours.',
                ]);
            }

            $withdrawal = Withdrawal::query()->create([
                'user_id' => $author->id,
                'amount' => $amount,
                'commission_percent' => $commissionPercent,
                'commission_amount' => $commissionAmount,
                'net_amount' => $netAmount,
                'status' => Withdrawal::STATUS_INITIATED,
                'payment_method' => $method,
                'account_number' => $accountNumber,
                'payout_details' => $payoutDetails,
            ]);

            $this->walletService->debitWithdrawal(
                $author,
                $amount,
                $withdrawal->id,
                'Retrait #'.$withdrawal->id.' — montant demandé'
            );

            return $withdrawal->fresh();
        });
    }

    public function markProcessing(Withdrawal $withdrawal, ?string $note = null): Withdrawal
    {
        abort_unless($withdrawal->status === Withdrawal::STATUS_INITIATED, 409, 'Seule une demande initiée peut passer en cours.');

        $withdrawal->update([
            'status' => Withdrawal::STATUS_PROCESSING,
            'processing_at' => now(),
            'processed_by' => Auth::id(),
            'admin_note' => $note ?: $withdrawal->admin_note,
        ]);

        return $withdrawal->fresh();
    }

    public function markCompleted(Withdrawal $withdrawal, ?string $note = null): Withdrawal
    {
        abort_unless(
            in_array($withdrawal->status, [Withdrawal::STATUS_INITIATED, Withdrawal::STATUS_PROCESSING], true),
            409,
            'Cette demande ne peut plus être terminée.'
        );

        $withdrawal->update([
            'status' => Withdrawal::STATUS_COMPLETED,
            'processing_at' => $withdrawal->processing_at ?: now(),
            'completed_at' => now(),
            'processed_by' => Auth::id(),
            'admin_note' => $note ?: $withdrawal->admin_note,
        ]);

        return $withdrawal->fresh();
    }

    public function reject(Withdrawal $withdrawal, ?string $note = null): Withdrawal
    {
        abort_unless($withdrawal->isOpen(), 409, 'Cette demande ne peut plus être refusée.');

        return DB::transaction(function () use ($withdrawal, $note) {
            $locked = Withdrawal::query()->lockForUpdate()->findOrFail($withdrawal->id);
            abort_unless($locked->isOpen(), 409);

            $locked->update([
                'status' => Withdrawal::STATUS_REJECTED,
                'rejected_at' => now(),
                'processed_by' => Auth::id(),
                'admin_note' => $note ?: $locked->admin_note,
            ]);

            $this->walletService->refundWithdrawal(
                $locked->user,
                (float) $locked->amount,
                $locked->id
            );

            return $locked->fresh();
        });
    }

    /**
     * @param  array<string, mixed>  $details
     */
    private function assertPayoutDetails(string $method, array $details): void
    {
        $fields = Withdrawal::paymentMethods()[$method]['fields'] ?? [];
        $errors = [];

        foreach ($fields as $key => $field) {
            $required = $field['required'] ?? true;
            $value = trim((string) ($details[$key] ?? ''));

            if ($required && $value === '') {
                $errors["payout_details.$key"] = 'Le champ « '.$field['label'].' » est obligatoire.';
            }
        }

        if ($errors !== []) {
            throw ValidationException::withMessages($errors);
        }
    }

    /**
     * @param  array<string, mixed>  $details
     */
    private function primaryAccountIdentifier(string $method, array $details): string
    {
                return match ($method) {
            Withdrawal::METHOD_MOBILE_MONEY => (string) ($details['phone'] ?? ''),
            Withdrawal::METHOD_BANK => (string) ($details['iban_or_account'] ?? ''),
            Withdrawal::METHOD_PAYPAL => (string) ($details['email'] ?? ''),
            default => '',
        };
    }
}
