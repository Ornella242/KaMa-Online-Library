<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Advertisement;
use App\Models\Book;
use App\Models\BookSponsorship;
use App\Models\Category;
use App\Models\Order;
use App\Models\Payment;
use App\Models\SiteVisit;
use App\Models\User;
use App\Models\Withdrawal;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $successfulPayments = Payment::query()->where('status', 'success');
        $purchaseRevenue = $this->amountsByCurrency(
            (clone $successfulPayments)->where('type', 'purchase')
        );
        $publicationRevenue = $this->amountsByCurrency(
            (clone $successfulPayments)->where('type', 'publication')
        );

        $sponsorshipRevenue = (float) BookSponsorship::query()
            ->where('status', 'paid')
            ->sum('amount');
        $advertisementRevenue = (float) Advertisement::query()
            ->whereIn('status', ['active', 'expired'])
            ->sum('amount');
        $promotionRevenue = ['EUR' => $sponsorshipRevenue + $advertisementRevenue];

        $globalRevenue = $this->mergeAmounts($purchaseRevenue, $publicationRevenue, $promotionRevenue);

        $sessions = $this->countSessions();
        $paidOrders = Order::query()->where('status', Order::STATUS_PAID)->count();

        $metrics = [
            'sales' => (clone $successfulPayments)->where('type', 'purchase')->count(),
            'paid_orders' => $paidOrders,
            'global_revenue' => $globalRevenue,
            'publication_revenue' => $publicationRevenue,
            'advertising_revenue' => $promotionRevenue,
            'pending_payout' => (float) Withdrawal::query()
                ->whereIn('status', [Withdrawal::STATUS_INITIATED, Withdrawal::STATUS_PROCESSING])
                ->sum('net_amount'),
            'sessions' => $sessions,
            'conversion_rate' => $sessions > 0 ? ($paidOrders / $sessions) * 100 : 0,
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
                    'visits' => $this->countSessions($start, $end),
                ];
            })
            ->values();

        $countryName = "COALESCE(countries.name, 'Non renseigné')";

        $salesByCountry = Order::query()
            ->leftJoin('countries', 'countries.id', '=', 'orders.country_id')
            ->where('orders.status', Order::STATUS_PAID)
            ->groupBy(DB::raw($countryName), 'orders.currency')
            ->orderByDesc(DB::raw('COUNT(orders.id)'))
            ->get([
                DB::raw($countryName.' as country'),
                'orders.currency',
                DB::raw('COUNT(orders.id) as sales_count'),
                DB::raw('SUM(orders.amount) as total_amount'),
            ])
            ->groupBy('country')
            ->map(function (Collection $rows, string $country) {
                return [
                    'country' => $country,
                    'sales_count' => (int) $rows->sum('sales_count'),
                    'amounts' => $rows
                        ->mapWithKeys(fn ($row) => [
                            strtoupper($row->currency ?: 'EUR') => (float) $row->total_amount,
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
            ->selectRaw("UPPER(COALESCE(NULLIF(currency, ''), 'EUR')) as currency_code, SUM(amount) as total")
            ->groupBy('currency_code')
            ->pluck('total', 'currency_code')
            ->mapWithKeys(fn ($amount, $currency) => [
                strtoupper((string) $currency) => (float) $amount,
            ])
            ->all();
    }

    /**
     * @param  array<string, float>  ...$groups
     * @return array<string, float>
     */
    private function mergeAmounts(array ...$groups): array
    {
        $merged = [];

        foreach ($groups as $group) {
            foreach ($group as $currency => $amount) {
                if ((float) $amount == 0.0) {
                    continue;
                }
                $code = strtoupper((string) $currency);
                $merged[$code] = ($merged[$code] ?? 0) + (float) $amount;
            }
        }

        return $merged;
    }

    private function countSessions(?Carbon $start = null, ?Carbon $end = null): int
    {
        $query = SiteVisit::query();

        if ($start && $end) {
            $query->whereBetween('created_at', [$start, $end]);
        }

        return (int) $query->distinct()->count('session_id');
    }
}
