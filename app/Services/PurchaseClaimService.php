<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class PurchaseClaimService
{
    /**
     * Link guest purchases (same email) to a registered user account.
     */
    public function claimFor(User $user): int
    {
        $email = strtolower(trim((string) $user->email));

        if ($email === '') {
            return 0;
        }

        return (int) DB::transaction(function () use ($user, $email) {
            Order::query()
                ->whereRaw('LOWER(email) = ?', [$email])
                ->where('status', Order::STATUS_PAID)
                ->whereNull('user_id')
                ->update(['user_id' => $user->id]);

            $orderIds = Order::query()
                ->where('user_id', $user->id)
                ->pluck('id');

            return Payment::query()
                ->where('type', 'purchase')
                ->where('status', 'success')
                ->whereNull('user_id')
                ->where(function ($query) use ($email, $orderIds) {
                    $query->whereRaw('LOWER(guest_email) = ?', [$email]);

                    if ($orderIds->isNotEmpty()) {
                        $query->orWhereIn('order_id', $orderIds);
                    }
                })
                ->update([
                    'user_id' => $user->id,
                    'guest_email' => null,
                ]);
        });
    }

    public function userOwnsBook(User $user, int $bookId): bool
    {
        $this->claimFor($user);

        return Payment::query()
            ->where('user_id', $user->id)
            ->where('book_id', $bookId)
            ->where('type', 'purchase')
            ->where('status', 'success')
            ->exists();
    }
}
