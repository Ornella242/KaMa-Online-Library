<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Book;
use App\Models\PublicationFee;
use App\Models\User;
use App\Notifications\NewBookSubmittedNotification;
use App\Services\PublicationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Kkiapay\Kkiapay;
use Throwable;

class PaymentController extends Controller
{
    public function payPublication(Book $book, PublicationService $publicationService)
    {
        abort_unless($book->user_id === Auth::id(), 403);
        abort_unless($book->status === 'draft', 409, 'Ce livre a déjà été soumis ou payé.');
        abort_unless($this->kkiapayIsConfigured(), 503, 'KKiaPay n’est pas encore configuré.');
        abort_unless($publicationService->shouldRequirePayment(),403,'Les frais de dépôt ne sont pas requis actuellement.');

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
            abort_unless(
                strtoupper($fee->currency) === 'XOF',
                409,
                'KKiaPay exige un tarif configuré en XOF.'
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
                    'currency' => $fee->currency,
                    'payment_method' => 'kkiapay',
                    'transaction_id' => null,
                ]
            );

            if (! $payment->wasRecentlyCreated && $payment->transaction_id === null) {
                $payment->update([
                    'amount' => $fee->amount,
                    'currency' => $fee->currency,
                    'payment_method' => 'kkiapay',
                ]);
            }
            return $payment;
        });

        return response()->json([
            'payment' => [
                'reference' => $payment->reference,
                'amount' => (float) $payment->amount,
                'currency' => $payment->currency,
            ],
        ]);
    }

    public function verifyKkiapayPublication(Request $request, Book $book)
    {
        abort_unless($book->user_id === Auth::id(), 403);
        abort_unless($this->kkiapayIsConfigured(), 503, 'KKiaPay n’est pas encore configuré.');

        $validated = $request->validate([
            'transaction_id' => ['required', 'string', 'max:255'],
        ]);

        $payment = Payment::query()
            ->where('book_id', $book->id)
            ->where('user_id', Auth::id())
            ->where('type', 'publication')
            ->latest()
            ->firstOrFail();

        if ($payment->status === 'success' && $payment->transaction_id === $validated['transaction_id']) {
            return response()->json([
                'redirect' => $this->publicationSuccessRedirect(),
                'message' => 'Paiement déjà confirmé.',
            ]);
        }

        abort_unless($payment->status === 'pending', 409, 'Cette demande de paiement ne peut plus être validée.');
        abort_if(
            Payment::query()
                ->where('transaction_id', $validated['transaction_id'])
                ->where('id', '!=', $payment->id)
                ->exists(),
            409,
            'Cette transaction a déjà été utilisée.'
        );

        try {
            $kkiapay = new Kkiapay(
                config('services.kkiapay.public_key'),
                config('services.kkiapay.private_key'),
                config('services.kkiapay.secret'),
                (bool) config('services.kkiapay.sandbox', true)
            );
            $verification = $kkiapay->verifyTransaction($validated['transaction_id']);
        } catch (Throwable $exception) {
            report($exception);

            return response()->json([
                'message' => 'La vérification KKiaPay est momentanément indisponible. Réessayez sans effectuer un nouveau paiement.',
            ], 502);
        }

        abort_unless(
            is_object($verification)
                && strtoupper((string) ($verification->status ?? '')) === 'SUCCESS',
            422,
            'KKiaPay n’a pas confirmé cette transaction.'
        );
        abort_unless(
            abs((float) ($verification->amount ?? -1) - (float) $payment->amount) < 0.01,
            422,
            'Le montant confirmé par KKiaPay ne correspond pas aux frais attendus.'
        );

        $partnerId = trim((string) ($verification->partnerId ?? ''));
        abort_unless(
            $partnerId === '' || hash_equals($payment->reference, $partnerId),
            422,
            'La référence KKiaPay ne correspond pas à cette demande.'
        );

        $book = DB::transaction(function () use ($payment, $validated, $verification) {
            $lockedPayment = Payment::query()->lockForUpdate()->findOrFail($payment->id);
            $lockedBook = Book::query()->lockForUpdate()->findOrFail($lockedPayment->book_id);

            abort_unless($lockedPayment->status === 'pending', 409, 'Ce paiement a déjà été traité.');
            abort_unless($lockedBook->status === 'draft', 409, 'Le livre n’est plus à l’état brouillon.');
            abort_if(
                Payment::query()
                    ->where('transaction_id', $validated['transaction_id'])
                    ->where('id', '!=', $lockedPayment->id)
                    ->exists(),
                409,
                'Cette transaction a déjà été utilisée.'
            );

            $lockedPayment->update([
                'status' => 'success',
                'payment_method' => strtolower((string) ($verification->source ?? 'kkiapay')),
                'transaction_id' => $validated['transaction_id'],
            ]);
            $lockedBook->update(['status' => 'waiting_review']);

            return $lockedBook;
        });

        $this->notifyAdmins($book);

        return response()->json([
            'redirect' => $this->publicationSuccessRedirect(),
            'message' => 'Paiement confirmé. Votre livre est maintenant en attente de vérification.',
        ]);
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

    private function kkiapayIsConfigured(): bool
    {
        return filled(config('services.kkiapay.public_key'))
            && filled(config('services.kkiapay.private_key'))
            && filled(config('services.kkiapay.secret'));
    }

    private function notifyAdmins(Book $book): void
    {
        User::query()
            ->whereHas('role', fn ($query) => $query->where('name', 'admin'))
            ->where('id', '!=', $book->user_id)
            ->each(fn (User $admin) => $admin->notify(new NewBookSubmittedNotification($book)));
    }

    public function submitWithoutPayment(Book $book, PublicationService $publicationService)
    {
        abort_unless($book->user_id === Auth::id(), 403);
        abort_unless($book->status === 'draft', 409);
        abort_if(
            $publicationService->shouldRequirePayment(),
            403,
            'Le paiement est désormais obligatoire.'
        );

        DB::transaction(function () use ($book) {
            $lockedBook = Book::lockForUpdate()->findOrFail($book->id);
            $lockedBook->update([
                'status' => 'waiting_review',
            ]);
        });

        $this->notifyAdmins($book);
        return redirect()
            ->route('writer.books')
            ->with(
                'success',
                'Votre livre a été soumis pour vérification.'
            );
    }
}
