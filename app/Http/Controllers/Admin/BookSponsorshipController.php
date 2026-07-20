<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\BookSponsorship;
use Illuminate\Http\Request;

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

    public function sponsor(Book $book)
    {
        BookSponsorship::create([
            'book_id' => $book->id,
            'writer_id' => $book->user_id,
            'sponsorship_plan_id' => null,
            'amount' => 0,
            'transaction_reference' => 'ADMIN_SPONSOR',
            'status' => 'paid',
            'starts_at' => now(),
            'ends_at' => now()->addDays(30),
            'paid_at' => now(),
            'source' => 'admin',
        ]);

        return back()->with(
            'success',
            'Le livre est maintenant mis en avant sur KaMa.'
        );
    }
}
