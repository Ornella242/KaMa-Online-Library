<?php

namespace App\Services;

use App\Mail\OrderPurchaseMail;
use App\Models\Book;
use App\Models\BookSponsorship;
use App\Models\Order;
use App\Models\Payment;
use App\Models\User;
use App\Notifications\NewBookSubmittedNotification;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Throwable;

class PaymentFulfillmentService
{
    /**
     * @param  array<string, mixed>  $custom
     */
    public function handlePaidCheckout(array $custom, string $transactionId, ?string $paymentMethod = 'stripe'): array
    {
        $type = (string) ($custom['type'] ?? '');

        return match ($type) {
            'order' => $this->fulfillOrder($custom, $transactionId, $paymentMethod),
            'publication' => $this->fulfillPublication($custom, $transactionId, $paymentMethod),
            'sponsorship' => $this->fulfillSponsorship($custom, $transactionId, $paymentMethod),
            default => ['ok' => false, 'message' => 'Type de paiement inconnu.'],
        };
    }

    /**
     * @param  array<string, mixed>  $custom
     */
    public function fulfillOrder(array $custom, string $transactionId, ?string $paymentMethod = 'stripe'): array
    {
        $order = Order::query()
            ->when(
                filled($custom['order_reference'] ?? null),
                fn ($q) => $q->where('reference', $custom['order_reference'])
            )
            ->when(
                filled($custom['order_id'] ?? null) && empty($custom['order_reference']),
                fn ($q) => $q->where('id', $custom['order_id'])
            )
            ->first();

        if (! $order) {
            return ['ok' => false, 'message' => 'Commande introuvable.'];
        }

        if ($order->status === Order::STATUS_PAID) {
            return [
                'ok' => true,
                'already' => true,
                'redirect' => route('checkout.success', $order),
                'message' => 'Paiement déjà confirmé.',
            ];
        }

        DB::transaction(function () use ($order, $transactionId, $paymentMethod) {
            $lockedOrder = Order::query()->lockForUpdate()->findOrFail($order->id);
            abort_unless($lockedOrder->status === Order::STATUS_PENDING, 409, 'Cette commande a déjà été traitée.');

            $lockedOrder->update([
                'status' => Order::STATUS_PAID,
                'payment_method' => $paymentMethod ?: 'stripe',
                'transaction_id' => $transactionId,
            ]);

            $lockedOrder->load('items.book');

            foreach ($lockedOrder->items as $item) {
                $payment = Payment::query()->updateOrCreate(
                    [
                        'order_id' => $lockedOrder->id,
                        'book_id' => $item->book_id,
                        'type' => 'purchase',
                    ],
                    [
                        'user_id' => $lockedOrder->user_id,
                        'guest_email' => $lockedOrder->user_id ? null : $lockedOrder->email,
                        'reference' => $lockedOrder->reference.'-'.$item->book_id,
                        'amount' => $item->unit_price,
                        'currency' => $lockedOrder->currency ?: 'EUR',
                        'status' => 'success',
                        'payment_method' => $paymentMethod ?: 'stripe',
                        'transaction_id' => $transactionId.'-'.$item->book_id,
                    ]
                );

                app(WalletService::class)->creditSaleFromPayment($payment->fresh(['book']));
            }
        });

        $order->refresh()->load('items.book');
        $this->sendPurchaseEmail($order);

        return [
            'ok' => true,
            'redirect' => route('checkout.success', $order),
            'message' => 'Paiement confirmé. Vos livres ont été envoyés par email.',
        ];
    }

    /**
     * @param  array<string, mixed>  $custom
     */
    public function fulfillPublication(array $custom, string $transactionId, ?string $paymentMethod = 'stripe'): array
    {
        $payment = Payment::query()
            ->when(
                filled($custom['payment_id'] ?? null),
                fn ($q) => $q->where('id', $custom['payment_id'])
            )
            ->when(
                filled($custom['payment_reference'] ?? null),
                fn ($q) => $q->where('reference', $custom['payment_reference'])
            )
            ->where('type', 'publication')
            ->latest('id')
            ->first();

        if (! $payment) {
            return ['ok' => false, 'message' => 'Paiement de publication introuvable.'];
        }

        if ($payment->status === 'success') {
            return [
                'ok' => true,
                'already' => true,
                'redirect' => $this->publicationSuccessRedirect($payment),
                'message' => 'Paiement déjà confirmé.',
            ];
        }

        $book = DB::transaction(function () use ($payment, $transactionId, $paymentMethod) {
            $lockedPayment = Payment::query()->lockForUpdate()->findOrFail($payment->id);
            $lockedBook = Book::query()->lockForUpdate()->findOrFail($lockedPayment->book_id);

            abort_unless($lockedPayment->status === 'pending', 409, 'Ce paiement a déjà été traité.');
            abort_unless($lockedBook->status === 'draft', 409, 'Le livre n’est plus à l’état brouillon.');

            $lockedPayment->update([
                'status' => 'success',
                'payment_method' => $paymentMethod ?: 'stripe',
                'transaction_id' => $transactionId,
            ]);
            $lockedBook->update(['status' => 'waiting_review']);

            return $lockedBook;
        });

        $this->notifyAdmins($book);

        return [
            'ok' => true,
            'redirect' => $this->publicationSuccessRedirect($payment->fresh()),
            'message' => 'Paiement confirmé. Votre livre est maintenant en attente de vérification.',
        ];
    }

    /**
     * @param  array<string, mixed>  $custom
     */
    public function fulfillSponsorship(array $custom, string $transactionId, ?string $paymentMethod = 'stripe'): array
    {
        $sponsorship = BookSponsorship::query()
            ->when(
                filled($custom['sponsorship_id'] ?? null),
                fn ($q) => $q->where('id', $custom['sponsorship_id'])
            )
            ->when(
                filled($custom['sponsorship_reference'] ?? null),
                fn ($q) => $q->where('transaction_reference', $custom['sponsorship_reference'])
            )
            ->latest('id')
            ->first();

        if (! $sponsorship) {
            return ['ok' => false, 'message' => 'Sponsoring introuvable.'];
        }

        if ($sponsorship->status === 'paid') {
            return [
                'ok' => true,
                'already' => true,
                'redirect' => route('writer.books'),
                'message' => 'Paiement déjà confirmé. Votre demande est en attente de validation admin.',
            ];
        }

        DB::transaction(function () use ($sponsorship, $transactionId) {
            $locked = BookSponsorship::query()->lockForUpdate()->findOrFail($sponsorship->id);
            abort_unless($locked->status === 'pending', 409);

            $locked->update([
                'status' => 'paid',
                'paid_at' => now(),
                'transaction_reference' => $transactionId,
                'starts_at' => null,
                'ends_at' => null,
            ]);
        });

        return [
            'ok' => true,
            'redirect' => route('writer.books'),
            'message' => 'Paiement confirmé. Votre sponsoring sera activé après validation par l’équipe KaMa.',
        ];
    }

    private function sendPurchaseEmail(Order $order): void
    {
        try {
            Mail::to($order->email)->send(new OrderPurchaseMail($order));
        } catch (Throwable $exception) {
            report($exception);
        }
    }

    private function notifyAdmins(Book $book): void
    {
        User::query()
            ->whereHas('role', fn ($q) => $q->where('name', 'admin'))
            ->get()
            ->each(fn (User $admin) => $admin->notify(new NewBookSubmittedNotification($book)));
    }

    private function publicationSuccessRedirect(Payment $payment): string
    {
        $user = $payment->user;

        if ($user && method_exists($user, 'isAdmin') && $user->isAdmin()) {
            return route('admin.books.index');
        }

        return route('writer.books');
    }
}
