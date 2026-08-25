<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\BookSponsorship;
use App\Models\SponsorshipPlan;
use App\Services\StripeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Throwable;

class SponsorshipController extends Controller
{
    public function create(Book $book)
    {
        $this->authorizeBook($book);
        abort_unless($book->status === Book::STATUS_PUBLISHED, 403, 'Seuls les livres publiés peuvent être sponsorisés.');

        $plans = SponsorshipPlan::query()
            ->where('active', true)
            ->orderBy('duration_days')
            ->get();

        $activeSponsorship = $book->activeSponsorship()->first();
        $pendingValidation = BookSponsorship::query()
            ->where('book_id', $book->id)
            ->where('writer_id', Auth::id())
            ->where('status', 'paid')
            ->whereNull('starts_at')
            ->latest()
            ->first();

        return view('writer.books.sponsor', compact(
            'book',
            'plans',
            'activeSponsorship',
            'pendingValidation'
        ));
    }

    public function store(Book $book, SponsorshipPlan $plan)
    {
        $this->authorizeBook($book);
        abort_unless($book->status === Book::STATUS_PUBLISHED, 403);
        abort_unless($plan->active, 404);
        abort_if($book->activeSponsorship()->exists(), 409, 'Ce livre a déjà un sponsoring actif.');

        $awaiting = BookSponsorship::query()
            ->where('book_id', $book->id)
            ->where('writer_id', Auth::id())
            ->where('status', 'paid')
            ->whereNull('starts_at')
            ->exists();

        abort_if($awaiting, 409, 'Une demande payée est déjà en attente de validation.');

        $sponsorship = BookSponsorship::query()->create([
            'book_id' => $book->id,
            'writer_id' => Auth::id(),
            'sponsorship_plan_id' => $plan->id,
            'amount' => $plan->price,
            'transaction_reference' => 'KAMA-SPN-' . strtoupper(Str::random(10)),
            'status' => 'pending',
            'source' => 'author',
        ]);

        return redirect()->route('writer.sponsorship.payment', $sponsorship);
    }

    public function payment(BookSponsorship $sponsorship, StripeService $stripe)
    {
        abort_unless($sponsorship->writer_id === Auth::id(), 403);
        abort_unless($sponsorship->status === 'pending', 409, 'Cette demande n’est plus en attente de paiement.');

        $sponsorship->load(['book', 'plan']);

        return view('writer.sponsorship.payment', [
            'sponsorship' => $sponsorship,
            'stripeConfigured' => $stripe->isConfigured(),
            'stripeTestMode' => $stripe->isTestMode(),
        ]);
    }

    public function preparePayment(BookSponsorship $sponsorship, StripeService $stripe)
    {
        abort_unless($sponsorship->writer_id === Auth::id(), 403);
        abort_unless($sponsorship->status === 'pending', 409);
        abort_unless($stripe->isConfigured(), 503, 'Stripe n’est pas encore configuré.');

        try {
            $checkout = $stripe->createCheckout(
                (float) $sponsorship->amount,
                [
                    'name' => trim(Auth::user()->firstname.' '.Auth::user()->lastname),
                    'email' => Auth::user()->email,
                    'custom' => [
                        'type' => 'sponsorship',
                        'sponsorship_id' => (string) $sponsorship->id,
                        'sponsorship_reference' => $sponsorship->transaction_reference,
                        'book_id' => (string) $sponsorship->book_id,
                    ],
                ],
                route('writer.books').'?paid=1',
                route('writer.sponsorship.payment', $sponsorship),
                'Sponsoring KaMa — '.$sponsorship->book->title
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
                'reference' => $sponsorship->transaction_reference,
                'amount' => (float) $sponsorship->amount,
                'currency' => 'EUR',
            ],
        ]);
    }

    public function verify(Request $request, BookSponsorship $sponsorship)
    {
        abort_unless($sponsorship->writer_id === Auth::id(), 403);

        if ($sponsorship->status === 'paid') {
            return response()->json([
                'redirect' => route('writer.books'),
                'message' => 'Paiement confirmé. Votre demande est en attente de validation admin.',
            ]);
        }

        abort_unless($sponsorship->status === 'pending', 409, 'Cette demande ne peut plus être payée.');

        $sponsorship->refresh();
        if ($sponsorship->status === 'paid') {
            return response()->json([
                'redirect' => route('writer.books'),
                'message' => 'Paiement confirmé.',
            ]);
        }

        return response()->json([
            'message' => 'Paiement en cours de confirmation…',
            'pending' => true,
        ], 202);
    }

    private function authorizeBook(Book $book): void
    {
        abort_unless($book->user_id === Auth::id(), 403);
    }
}
