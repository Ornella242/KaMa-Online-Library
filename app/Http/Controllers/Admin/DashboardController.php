<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Advertisement;
use App\Models\Book;
use App\Models\BookSponsorship;
use App\Models\Category;
use App\Models\Payment;
use App\Models\SiteVisit;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $successfulPayments = Payment::query()->where('status', 'success');
        $paymentRevenue = $this->amountsByCurrency(clone $successfulPayments);
        $subscriptionRevenue = $this->amountsByCurrency(
            (clone $successfulPayments)->where('type', 'subscription')
        );

        $advertisingRevenueUsd = (float) BookSponsorship::query()
            ->where('status', 'paid')
            ->sum('amount')
            + (float) Advertisement::query()
                ->whereIn('status', ['active', 'expired'])
                ->sum('amount');

        $globalRevenue = collect($paymentRevenue);
        if ($advertisingRevenueUsd > 0) {
            $globalRevenue['USD'] = (float) $globalRevenue->get('USD', 0) + $advertisingRevenueUsd;
        }

        $metrics = [
            'sales' => (clone $successfulPayments)->where('type', 'purchase')->count(),
            'global_revenue' => $globalRevenue->all(),
            'subscription_revenue' => $subscriptionRevenue,
            'advertising_revenue' => ['USD' => $advertisingRevenueUsd],
            'visits' => SiteVisit::query()->count(),
            'converted_visits' => SiteVisit::query()->whereNotNull('converted_at')->count(),
            'users' => User::query()->count(),
            'writers' => User::query()
                ->whereHas('role', fn ($query) => $query->where('name', 'writer'))
                ->count(),
            'books' => Book::query()->count(),
            'published' => Book::query()->where('status', Book::STATUS_PUBLISHED)->count(),
            'waiting' => Book::query()->where('status', Book::STATUS_WAITING_REVIEW)->count(),
            'under_review' => Book::query()->where('status', Book::STATUS_UNDER_REVIEW)->count(),
            'revision_required' => Book::query()->where('status', Book::STATUS_REVISION_REQUIRED)->count(),
            'categories' => Category::query()->count(),
        ];

        $recentBooks = Book::query()
            ->with(['author', 'category'])
            ->whereIn('status', [
                Book::STATUS_WAITING_REVIEW,
                Book::STATUS_UNDER_REVIEW,
                Book::STATUS_REVISION_REQUIRED,
            ])
            ->latest('updated_at')
            ->take(6)
            ->get();

        $monthlyPerformance = collect(range(5, 0))
            ->map(function (int $monthsAgo) {
                $month = Carbon::now()->subMonths($monthsAgo);
                $start = $month->copy()->startOfMonth();
                $end = $month->copy()->endOfMonth();

                return [
                    'label' => $month->translatedFormat('M'),
                    'sales' => Payment::query()
                        ->where('type', 'purchase')
                        ->where('status', 'success')
                        ->whereBetween('created_at', [$start, $end])
                        ->count(),
                    'visits' => SiteVisit::query()
                        ->whereBetween('created_at', [$start, $end])
                        ->count(),
                ];
            })
            ->values();

        $salesByCountry = Payment::query()
            ->join('users', 'users.id', '=', 'payments.user_id')
            ->leftJoin('countries', 'countries.id', '=', 'users.country_id')
            ->where('payments.type', 'purchase')
            ->where('payments.status', 'success')
            ->groupBy('countries.name', 'payments.currency')
            ->orderByDesc(DB::raw('COUNT(payments.id)'))
            ->get([
                DB::raw("COALESCE(countries.name, 'Non renseigné') as country"),
                'payments.currency',
                DB::raw('COUNT(payments.id) as sales_count'),
                DB::raw('SUM(payments.amount) as total_amount'),
            ])
            ->groupBy('country')
            ->map(function (Collection $rows, string $country) {
                return [
                    'country' => $country,
                    'sales_count' => (int) $rows->sum('sales_count'),
                    'amounts' => $rows
                        ->mapWithKeys(fn ($row) => [
                            strtoupper($row->currency ?: 'USD') => (float) $row->total_amount,
                        ])
                        ->all(),
                ];
            })
            ->sortByDesc('sales_count')
            ->values()
            ->take(8);

        return view('admin.dashboard', compact(
            'metrics',
            'recentBooks',
            'monthlyPerformance',
            'salesByCountry'
        ));
    }

    private function amountsByCurrency($query): array
    {
        return $query
            ->selectRaw("COALESCE(currency, 'USD') as currency_code, SUM(amount) as total")
            ->groupBy('currency')
            ->pluck('total', 'currency_code')
            ->mapWithKeys(fn ($amount, $currency) => [
                strtoupper((string) $currency) => (float) $amount,
            ])
            ->all();
    }
}
