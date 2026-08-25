<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Payment;
use App\Models\PublicationFee;
use App\Models\User;
use App\Notifications\NewBookSubmittedNotification;
use App\Services\PublicationService;
use App\Services\StripeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Throwable;

class PaymentController extends Controller
{
    public function payPublication(Book $book, PublicationService $publicationService, StripeService $stripe)
    {
        abort_unless($book->user_id === Auth::id(), 403);
        abort_unless($book->status === 'draft', 409, 'Ce livre a déjà été soumis ou payé.');
        abort_unless($stripe->isConfigured(), 503, 'Stripe n’est pas encore configuré.');
        abort_unless($publicationService->shouldRequirePayment(), 403, 'Les frais de dépôt ne sont pas requis actuellement.');

        $payment = DB::transaction(function () use ($book) {
            $lockedBook = Book::query()
                ->lockForUpdate()
                ->findOrFail($book->id);
            abort_unless($lockedBook->status === 'draft', 409, 'Ce livre a déjà été soumis.');

            $fee = PublicationFee::query()
                ->where('book_type', $lockedBook->type)
                ->lockForUpdate()
                ->first();

            abort_unless(
                $fee && (float) $fee->amount > 0,
                503,
                'Les frais de publication ne sont pas encore configurés pour ce format.'
            );

            $payment = Payment::query()->firstOrCreate(
                [
                    'book_id' => $lockedBook->id,
                    'type' => 'publication',
                    'status' => 'pending',
                ],
                [
                    'user_id' => Auth::id(),
                    'reference' => 'KAMA-'.str()->uuid(),
                    'amount' => $fee->amount,
                    'currency' => 'USD',
                    'payment_method' => 'stripe',
                    'transaction_id' => null,
                ]
            );

            if ($payment->transaction_id === null) {
                $payment->update([
                    'amount' => $fee->amount,
                    'currency' => 'USD',
                    'payment_method' => 'stripe',
                ]);
            }

            return $payment->fresh();
        });

        $successUrl = Auth::user()?->isAdmin()
            ? route('admin.books.index')
            : route('writer.books');

        try {
            $checkout = $stripe->createCheckout(
                (float) $payment->amount,
                [
                    'name' => trim(Auth::user()->firstname.' '.Auth::user()->lastname),
                    'email' => Auth::user()->email,
                    'custom' => [
                        'type' => 'publication',
                        'payment_id' => (string) $payment->id,
                        'payment_reference' => $payment->reference,
                        'book_id' => (string) $book->id,
                    ],
                ],
                $successUrl.'?paid=1',
                Auth::user()?->isAdmin()
                    ? route('admin.books.deposit', $book)
                    : route('writer.books.deposit', $book),
                'Frais de publication — '.$book->title
            );
        } catch (Throwable $exception) {
            report($exception);

            return response()->json([
                'message' => $exception->getMessage(),
            ], 502);
        }

        return response()->json([
            'checkout_url' => $checkout['url'],
            'checkout_id' => $checkout['id'],
            'payment' => [
                'reference' => $payment->reference,
                'amount' => (float) $payment->amount,
                'currency' => 'USD',
            ],
        ]);
    }

    public function submitWithoutPayment(Book $book, PublicationService $publicationService)
    {
        abort_unless($book->user_id === Auth::id(), 403);
        abort_unless($book->status === 'draft', 409, 'Ce livre a déjà été soumis.');
        abort_if(
            $publicationService->shouldRequirePayment(),
            403,
            'Le paiement est désormais obligatoire.'
        );

        DB::transaction(function () use ($book) {
            $lockedBook = Book::query()->lockForUpdate()->findOrFail($book->id);
            abort_unless($lockedBook->status === 'draft', 409, 'Ce livre a déjà été soumis.');
            $lockedBook->update(['status' => 'waiting_review']);
        });

        $this->notifyAdmins($book);

        return redirect()
            ->to($this->publicationSuccessRedirect())
            ->with('success', 'Votre livre a été soumis pour vérification.');
    }

    public function verifyPublication(Request $request, Book $book)
    {
        abort_unless($book->user_id === Auth::id(), 403);

        $payment = Payment::query()
            ->where('book_id', $book->id)
            ->where('user_id', Auth::id())
            ->where('type', 'publication')
            ->latest()
            ->firstOrFail();

        if ($payment->status === 'success') {
            return response()->json([
                'redirect' => $this->publicationSuccessRedirect(),
                'message' => 'Paiement confirmé.',
            ]);
        }

        return response()->json([
            'message' => 'Paiement en cours de confirmation…',
            'pending' => true,
        ], 202);
    }

    public function confirmPublication(Payment $payment)
    {
        abort_unless(
            $payment->type === 'publication'
                && $payment->status === 'pending'
                && $payment->payment_method === 'manual',
            409,
            'Seuls les anciens paiements manuels peuvent être confirmés par l’administration.'
        );

        $book = DB::transaction(function () use ($payment) {
            $lockedPayment = Payment::query()->lockForUpdate()->findOrFail($payment->id);
            $lockedBook = Book::query()->lockForUpdate()->findOrFail($lockedPayment->book_id);

            abort_unless($lockedPayment->status === 'pending', 409, 'Ce paiement a déjà été traité.');
            abort_unless($lockedBook->status === 'draft', 409, 'Le livre n’est plus à l’état brouillon.');

            $lockedPayment->update(['status' => 'success']);
            $lockedBook->update(['status' => 'waiting_review']);

            return $lockedBook;
        });

        $this->notifyAdmins($book);

        return redirect()
            ->back()
            ->with('success', 'Paiement confirmé. Le livre est maintenant en attente de vérification.');
    }

    private function publicationSuccessRedirect(): string
    {
        return Auth::user()?->isAdmin()
            ? route('admin.books.index')
            : route('writer.books');
    }

    private function notifyAdmins(Book $book): void
    {
        User::query()
            ->whereHas('role', fn ($q) => $q->where('name', 'admin'))
            ->get()
            ->each(fn (User $admin) => $admin->notify(new NewBookSubmittedNotification($book)));
    }
}
