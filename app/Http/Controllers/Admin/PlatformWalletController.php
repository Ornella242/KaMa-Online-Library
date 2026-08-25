<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Advertisement;
use App\Models\BookSponsorship;
use App\Models\Payment;
use App\Models\Wallet;
use App\Models\Withdrawal;
use Illuminate\Pagination\LengthAwarePaginator;

class PlatformWalletController extends Controller
{
    public function index()
    {
        $salesTotal = (float) Payment::query()
            ->where('type', 'purchase')
            ->where('status', 'success')
            ->sum('amount');

        $publicationTotal = (float) Payment::query()
            ->where('type', 'publication')
            ->where('status', 'success')
            ->sum('amount');

        $sponsorshipTotal = (float) BookSponsorship::query()
            ->where('status', 'paid')
            ->where('amount', '>', 0)
            ->sum('amount');

        $advertisementTotal = (float) Advertisement::query()
            ->whereIn('status', ['active', 'expired'])
            ->sum('amount');

        $platformBalance = $salesTotal + $publicationTotal + $sponsorshipTotal + $advertisementTotal;

        $withdrawalCommissionTotal = (float) Withdrawal::query()
            ->where('status', Withdrawal::STATUS_COMPLETED)
            ->sum('commission_amount');

        $authorsOwed = (float) Wallet::query()->sum('balance');
        $platformNet = max(0, $platformBalance - $authorsOwed);

        $monthStart = now()->copy()->startOfMonth();
        $monthEnd = now()->copy()->endOfMonth();

        $salesThisMonth = (float) Payment::query()
            ->where('type', 'purchase')
            ->where('status', 'success')
            ->whereBetween('created_at', [$monthStart, $monthEnd])
            ->sum('amount');

        $publicationThisMonth = (float) Payment::query()
            ->where('type', 'publication')
            ->where('status', 'success')
            ->whereBetween('created_at', [$monthStart, $monthEnd])
            ->sum('amount');

        $sponsorshipThisMonth = (float) BookSponsorship::query()
            ->where('status', 'paid')
            ->where('amount', '>', 0)
            ->whereBetween('paid_at', [$monthStart, $monthEnd])
            ->sum('amount');

        $thisMonth = $salesThisMonth + $publicationThisMonth + $sponsorshipThisMonth;

        $transactions = $this->buildTransactionFeed();

        return view('admin.platform-wallet', compact(
            'platformBalance',
            'platformNet',
            'authorsOwed',
            'salesTotal',
            'publicationTotal',
            'sponsorshipTotal',
            'advertisementTotal',
            'withdrawalCommissionTotal',
            'thisMonth',
            'transactions',
        ));
    }

    /**
     * @return LengthAwarePaginator<int, object>
     */
    private function buildTransactionFeed(): LengthAwarePaginator
    {
        $page = max(1, (int) request('page', 1));
        $perPage = 20;

        $sales = Payment::query()
            ->with(['book.author', 'user'])
            ->where('type', 'purchase')
            ->where('status', 'success')
            ->latest('id')
            ->get()
            ->map(fn (Payment $payment) => (object) [
                'kind' => 'sale',
                'label' => 'Vente livre',
                'description' => $payment->book?->title
                    ? 'Achat — '.$payment->book->title
                    : 'Achat livre',
                'party' => $this->buyerLabel($payment),
                'author' => $this->authorLabel($payment->book?->author),
                'amount' => (float) $payment->amount,
                'currency' => strtoupper($payment->currency ?: 'EUR'),
                'reference' => $payment->reference,
                'occurred_at' => $payment->created_at,
            ]);

        $publications = Payment::query()
            ->with(['book.author', 'user'])
            ->where('type', 'publication')
            ->where('status', 'success')
            ->latest('id')
            ->get()
            ->map(fn (Payment $payment) => (object) [
                'kind' => 'publication',
                'label' => 'Frais de publication',
                'description' => $payment->book?->title
                    ? 'Dépôt — '.$payment->book->title
                    : 'Frais de publication',
                'party' => $this->authorLabel($payment->user) ?: $this->authorLabel($payment->book?->author),
                'author' => $this->authorLabel($payment->book?->author),
                'amount' => (float) $payment->amount,
                'currency' => strtoupper($payment->currency ?: 'EUR'),
                'reference' => $payment->reference,
                'occurred_at' => $payment->created_at,
            ]);

        $sponsorships = BookSponsorship::query()
            ->with(['book.author', 'writer', 'plan'])
            ->where('status', 'paid')
            ->where('amount', '>', 0)
            ->latest('id')
            ->get()
            ->map(fn (BookSponsorship $sponsorship) => (object) [
                'kind' => 'sponsorship',
                'label' => 'Sponsoring',
                'description' => ($sponsorship->plan?->name ?: 'Sponsoring')
                    .' — '.($sponsorship->book?->title ?: 'Livre'),
                'party' => $this->authorLabel($sponsorship->writer),
                'author' => $this->authorLabel($sponsorship->book?->author),
                'amount' => (float) $sponsorship->amount,
                'currency' => 'EUR',
                'reference' => $sponsorship->transaction_reference,
                'occurred_at' => $sponsorship->paid_at ?: $sponsorship->updated_at,
            ]);

        $ads = Advertisement::query()
            ->with(['book', 'user'])
            ->whereIn('status', ['active', 'expired'])
            ->where('amount', '>', 0)
            ->latest('id')
            ->get()
            ->map(fn (Advertisement $ad) => (object) [
                'kind' => 'advertisement',
                'label' => 'Publicité',
                'description' => $ad->book?->title
                    ? 'Campagne — '.$ad->book->title
                    : 'Campagne publicitaire',
                'party' => $this->authorLabel($ad->user),
                'author' => $this->authorLabel($ad->book?->author ?? $ad->user),
                'amount' => (float) $ad->amount,
                'currency' => 'EUR',
                'reference' => 'ad-'.$ad->id,
                'occurred_at' => $ad->updated_at ?: $ad->created_at,
            ]);

        $sorted = $sales
            ->concat($publications)
            ->concat($sponsorships)
            ->concat($ads)
            ->sortByDesc(fn ($row) => optional($row->occurred_at)->timestamp ?? 0)
            ->values();

        return new LengthAwarePaginator(
            $sorted->forPage($page, $perPage)->values(),
            $sorted->count(),
            $perPage,
            $page,
            [
                'path' => request()->url(),
                'query' => request()->query(),
            ]
        );
    }

    private function buyerLabel(?Payment $payment = null): string
    {
        if (! $payment) {
            return 'Lecteur';
        }

        $name = trim(($payment->user?->firstname ?? '').' '.($payment->user?->lastname ?? ''));

        return $name !== ''
            ? $name
            : ($payment->user?->email ?: ($payment->guest_email ?: 'Lecteur invité'));
    }

    private function authorLabel($user): string
    {
        if (! $user) {
            return '—';
        }

        $name = trim(($user->firstname ?? '').' '.($user->lastname ?? ''));

        return $name !== '' ? $name : ($user->email ?? '—');
    }
}
