<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\BookSponsorship;
use App\Models\SponsorshipPlan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Kkiapay\Kkiapay;
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

    public function payment(BookSponsorship $sponsorship)
    {
        abort_unless($sponsorship->writer_id === Auth::id(), 403);
        abort_unless($sponsorship->status === 'pending', 409, 'Cette demande n’est plus en attente de paiement.');

        $sponsorship->load(['book', 'plan']);

        $kkiapayConfigured = $this->kkiapayIsConfigured();
        $kkiapayPublicKey = config('services.kkiapay.public_key');
        $kkiapaySandbox = (bool) config('services.kkiapay.sandbox', true);

        return view('writer.sponsorship.payment', compact(
            'sponsorship',
            'kkiapayConfigured',
            'kkiapayPublicKey',
            'kkiapaySandbox'
        ));
    }

    public function preparePayment(BookSponsorship $sponsorship)
    {
        abort_unless($sponsorship->writer_id === Auth::id(), 403);
        abort_unless($sponsorship->status === 'pending', 409);
        abort_unless($this->kkiapayIsConfigured(), 503, 'KKiaPay n’est pas encore configuré.');

        return response()->json([
            'payment' => [
                'reference' => $sponsorship->transaction_reference,
                'amount' => (float) $sponsorship->amount,
                'currency' => 'XOF',
            ],
            'customer' => [
                'name' => trim(Auth::user()->firstname . ' ' . Auth::user()->lastname),
                'email' => Auth::user()->email,
                'phone' => Auth::user()->phone,
            ],
        ]);
    }

    public function verify(Request $request, BookSponsorship $sponsorship)
    {
        abort_unless($sponsorship->writer_id === Auth::id(), 403);
        abort_unless($this->kkiapayIsConfigured(), 503, 'KKiaPay n’est pas encore configuré.');

        $validated = $request->validate([
            'transaction_id' => ['required', 'string', 'max:255'],
        ]);

        if ($sponsorship->status === 'paid') {
            return response()->json([
                'redirect' => route('writer.books'),
                'message' => 'Paiement déjà confirmé. Votre demande est en attente de validation admin.',
            ]);
        }

        abort_unless($sponsorship->status === 'pending', 409, 'Cette demande ne peut plus être payée.');
        abort_if(
            BookSponsorship::query()
                ->where('transaction_reference', $validated['transaction_id'])
                ->where('id', '!=', $sponsorship->id)
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
            abs((float) ($verification->amount ?? -1) - (float) $sponsorship->amount) < 0.01,
            422,
            'Le montant confirmé par KKiaPay ne correspond pas à la formule.'
        );

        $partnerId = trim((string) ($verification->partnerId ?? ''));
        abort_unless(
            $partnerId === '' || hash_equals((string) $sponsorship->transaction_reference, $partnerId),
            422,
            'La référence KKiaPay ne correspond pas à cette demande.'
        );

        DB::transaction(function () use ($sponsorship, $validated) {
            $locked = BookSponsorship::query()->lockForUpdate()->findOrFail($sponsorship->id);
            abort_unless($locked->status === 'pending', 409);

            $locked->update([
                'status' => 'paid',
                'paid_at' => now(),
                'transaction_reference' => $validated['transaction_id'],
                // Live only after admin approval
                'starts_at' => null,
                'ends_at' => null,
            ]);
        });

        return response()->json([
            'redirect' => route('writer.books'),
            'message' => 'Paiement confirmé. Votre sponsoring sera activé après validation par l’équipe KaMa.',
        ]);
    }

    private function authorizeBook(Book $book): void
    {
        abort_unless($book->user_id === Auth::id(), 403);
    }

    private function kkiapayIsConfigured(): bool
    {
        return filled(config('services.kkiapay.public_key'))
            && filled(config('services.kkiapay.private_key'))
            && filled(config('services.kkiapay.secret'));
    }
}
