<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\BookSponsorship;
use App\Models\SponsorshipPlan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class BookSponsorshipController extends Controller
{
    public function index(Request $request)
    {
        $tab = $request->get('tab', 'pending');

        $base = BookSponsorship::query()
            ->with(['book.author', 'writer', 'plan'])
            ->where('source', 'author')
            ->latest();

        $pending = (clone $base)
            ->where('status', 'paid')
            ->whereNull('starts_at')
            ->paginate(10, ['*'], 'pending_page')
            ->withQueryString();

        $active = BookSponsorship::query()
            ->with(['book.author', 'writer', 'plan'])
            ->where('status', 'paid')
            ->whereNotNull('starts_at')
            ->where('ends_at', '>', now())
            ->latest('starts_at')
            ->paginate(10, ['*'], 'active_page')
            ->withQueryString();

        $history = BookSponsorship::query()
            ->with(['book.author', 'writer', 'plan'])
            ->where(function ($query) {
                $query->whereIn('status', ['cancelled', 'expired', 'failed'])
                    ->orWhere(function ($inner) {
                        $inner->where('status', 'paid')
                            ->whereNotNull('ends_at')
                            ->where('ends_at', '<=', now());
                    });
            })
            ->latest()
            ->paginate(10, ['*'], 'history_page')
            ->withQueryString();

        $stats = [
            'pending' => BookSponsorship::query()
                ->where('source', 'author')
                ->where('status', 'paid')
                ->whereNull('starts_at')
                ->count(),
            'active' => BookSponsorship::query()
                ->where('status', 'paid')
                ->whereNotNull('starts_at')
                ->where('ends_at', '>', now())
                ->count(),
            'revenue' => (float) BookSponsorship::query()
                ->where('status', 'paid')
                ->where('source', 'author')
                ->sum('amount'),
        ];

        return view('admin.sponsorships.index', compact(
            'tab',
            'pending',
            'active',
            'history',
            'stats'
        ));
    }

    public function approve(BookSponsorship $sponsorship)
    {
        abort_unless(
            $sponsorship->status === 'paid'
                && $sponsorship->starts_at === null
                && $sponsorship->source === 'author',
            409,
            'Cette demande ne peut plus être validée.'
        );

        $days = (int) ($sponsorship->plan?->duration_days ?: 30);

        $sponsorship->update([
            'starts_at' => now(),
            'ends_at' => now()->addDays(max($days, 1)),
        ]);

        return back()->with(
            'success',
            'Sponsoring validé. Le livre est maintenant mis en avant.'
        );
    }

    public function reject(BookSponsorship $sponsorship)
    {
        abort_unless(
            $sponsorship->status === 'paid'
                && $sponsorship->starts_at === null
                && $sponsorship->source === 'author',
            409,
            'Cette demande ne peut plus être refusée.'
        );

        $sponsorship->update([
            'status' => 'cancelled',
        ]);

        return back()->with(
            'success',
            'Demande de sponsoring refusée.'
        );
    }

    /**
     * Page de choix de formule (sponsoring gratuit pour l'admin auteur).
     */
    public function create(Book $book)
    {
        abort_unless($book->user_id === Auth::id(), 403);
        abort_unless($book->status === Book::STATUS_PUBLISHED, 403, 'Seuls les livres publiés peuvent être sponsorisés.');

        $plans = SponsorshipPlan::query()
            ->orderBy('duration_days')
            ->get();

        $activeSponsorship = $book->activeSponsorship()->first();

        return view('admin.books.sponsor', compact(
            'book',
            'plans',
            'activeSponsorship'
        ));
    }

    /**
     * Active immédiatement un sponsoring gratuit pour le livre de l'admin.
     */
    public function sponsor(Book $book, SponsorshipPlan $plan)
    {
        abort_unless($book->user_id === Auth::id(), 403);
        abort_unless($book->status === Book::STATUS_PUBLISHED, 403, 'Seuls les livres publiés peuvent être sponsorisés.');
        abort_if($book->activeSponsorship()->exists(), 409, 'Ce livre a déjà un sponsoring actif.');

        $days = max((int) $plan->duration_days, 1);

        BookSponsorship::create([
            'book_id' => $book->id,
            'writer_id' => $book->user_id,
            'sponsorship_plan_id' => $plan->id,
            'amount' => 0,
            'transaction_reference' => 'ADMIN-FREE-' . strtoupper(Str::random(8)),
            'status' => 'paid',
            'starts_at' => now(),
            'ends_at' => now()->addDays($days),
            'paid_at' => now(),
            'source' => 'admin',
        ]);

        return redirect()
            ->route('admin.books.index')
            ->with(
                'success',
                "Sponsoring activé gratuitement pour « {$book->title} » ({$days} jours)."
            );
    }
}
