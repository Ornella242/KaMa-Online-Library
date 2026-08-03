<?php

namespace App\Services;

use App\Models\Payment;
use App\Models\User;
use App\Models\Wallet;
use App\Models\WalletTransaction;
use Illuminate\Support\Facades\DB;

class WalletService
{
    public function ensureWallet(User $user): Wallet
    {
        return Wallet::query()->firstOrCreate(
            ['user_id' => $user->id],
            [
                'balance' => 0,
                'currency' => 'USD',
            ]
        );
    }

    /**
     * Crédite le portefeuille de l’auteur du livre après une vente confirmée.
     * Idempotent grâce à la référence unique liée au paiement.
     */
    public function creditSaleFromPayment(Payment $payment): bool
    {
        if ($payment->type !== 'purchase' || $payment->status !== 'success') {
            return false;
        }

        $payment->loadMissing('book');

        $authorId = $payment->book?->user_id;
        if (! $authorId) {
            return false;
        }

        $reference = 'sale-payment-'.$payment->id;
        $amount = round((float) $payment->amount, 2);

        if ($amount <= 0) {
            return false;
        }

        return DB::transaction(function () use ($authorId, $payment, $reference, $amount) {
            if (WalletTransaction::query()->where('reference', $reference)->exists()) {
                return false;
            }

            $wallet = Wallet::query()->firstOrCreate(
                ['user_id' => $authorId],
                [
                    'balance' => 0,
                    'currency' => $payment->currency ?: 'USD',
                ]
            );

            $wallet = Wallet::query()->lockForUpdate()->findOrFail($wallet->id);

            if (WalletTransaction::query()->where('reference', $reference)->exists()) {
                return false;
            }

            $bookTitle = $payment->book?->title ?: 'Livre #'.$payment->book_id;

            $wallet->balance = round((float) $wallet->balance + $amount, 2);
            $wallet->currency = $payment->currency ?: ($wallet->currency ?: 'USD');
            $wallet->save();

            $wallet->transactions()->create([
                'amount' => $amount,
                'type' => 'sale',
                'description' => 'Vente — '.$bookTitle,
                'reference' => $reference,
                'payment_id' => $payment->id,
            ]);

            return true;
        });
    }

    /**
     * Recrédite les ventes déjà confirmées qui n’ont pas encore alimenté un portefeuille.
     */
    public function backfillSales(?int $authorId = null): int
    {
        $credited = 0;

        Payment::query()
            ->with('book')
            ->where('type', 'purchase')
            ->where('status', 'success')
            ->when($authorId, function ($query) use ($authorId) {
                $query->whereHas('book', fn ($q) => $q->where('user_id', $authorId));
            })
            ->orderBy('id')
            ->chunkById(100, function ($payments) use (&$credited) {
                foreach ($payments as $payment) {
                    if ($this->creditSaleFromPayment($payment)) {
                        $credited++;
                    }
                }
            });

        return $credited;
    }

    /**
     * Débite le portefeuille pour une demande de retrait (montant brut demandé).
     */
    public function debitWithdrawal(User $user, float $amount, int $withdrawalId, string $description): void
    {
        $amount = round($amount, 2);
        abort_if($amount <= 0, 422, 'Montant invalide.');

        $reference = 'withdrawal-'.$withdrawalId;

        DB::transaction(function () use ($user, $amount, $reference, $description) {
            if (WalletTransaction::query()->where('reference', $reference)->exists()) {
                return;
            }

            $wallet = $this->ensureWallet($user);
            $wallet = Wallet::query()->lockForUpdate()->findOrFail($wallet->id);

            abort_if(
                (float) $wallet->balance + 0.0001 < $amount,
                422,
                'Solde insuffisant pour ce retrait.'
            );

            $wallet->balance = round((float) $wallet->balance - $amount, 2);
            $wallet->save();

            $wallet->transactions()->create([
                'amount' => $amount,
                'type' => 'withdrawal',
                'description' => $description,
                'reference' => $reference,
            ]);
        });
    }

    /**
     * Recrédite le portefeuille si un retrait est refusé.
     */
    public function refundWithdrawal(User $user, float $amount, int $withdrawalId): void
    {
        $amount = round($amount, 2);
        if ($amount <= 0) {
            return;
        }

        $debitReference = 'withdrawal-'.$withdrawalId;
        $refundReference = 'withdrawal-refund-'.$withdrawalId;

        DB::transaction(function () use ($user, $amount, $debitReference, $refundReference, $withdrawalId) {
            if (! WalletTransaction::query()->where('reference', $debitReference)->exists()) {
                return;
            }

            if (WalletTransaction::query()->where('reference', $refundReference)->exists()) {
                return;
            }

            $wallet = $this->ensureWallet($user);
            $wallet = Wallet::query()->lockForUpdate()->findOrFail($wallet->id);

            $wallet->balance = round((float) $wallet->balance + $amount, 2);
            $wallet->save();

            $wallet->transactions()->create([
                'amount' => $amount,
                'type' => 'sale',
                'description' => 'Remboursement — retrait #'.$withdrawalId,
                'reference' => $refundReference,
            ]);
        });
    }
}
