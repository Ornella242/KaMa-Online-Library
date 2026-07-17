<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Category;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Support\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $metrics = [
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
            'revenue' => Payment::query()->where('status', 'success')->sum('amount'),
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

        $recentUsers = User::query()
            ->with('role')
            ->latest()
            ->take(5)
            ->get();

        $monthlyActivity = collect(range(5, 0))
            ->map(function (int $monthsAgo) {
                $month = Carbon::now()->subMonths($monthsAgo);
                $start = $month->copy()->startOfMonth();
                $end = $month->copy()->endOfMonth();

                return [
                    'label' => $month->translatedFormat('M'),
                    'books' => Book::query()->whereBetween('created_at', [$start, $end])->count(),
                    'users' => User::query()->whereBetween('created_at', [$start, $end])->count(),
                ];
            })
            ->values();

        return view('admin.dashboard', compact(
            'metrics',
            'recentBooks',
            'recentUsers',
            'monthlyActivity'
        ));
    }
}
