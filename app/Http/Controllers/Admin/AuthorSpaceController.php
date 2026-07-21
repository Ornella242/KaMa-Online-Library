<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Review;
use App\Models\Withdrawal;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Support\Facades\Auth;

class AuthorSpaceController extends Controller
{
    public function reviews()
    {
        $userId = Auth::id();

        $reviews = Review::with(['book', 'user'])
            ->whereHas('book', fn ($query) => $query->where('user_id', $userId))
            ->latest()
            ->paginate(10);

        $base = Review::whereHas('book', fn ($query) => $query->where('user_id', $userId));

        $totalReviews = (clone $base)->count();
        $averageRating = (clone $base)->avg('rating') ?? 0;
        $fiveStars = (clone $base)->where('rating', 5)->count();
        $satisfiedReviews = (clone $base)->whereIn('rating', [4, 5])->count();
        $satisfaction = $totalReviews > 0
            ? round(($satisfiedReviews / $totalReviews) * 100)
            : 0;

        $ratingCounts = [];
        $ratingPercentages = [];

        for ($i = 1; $i <= 5; $i++) {
            $count = (clone $base)->where('rating', $i)->count();
            $ratingCounts[$i] = $count;
            $ratingPercentages[$i] = $totalReviews > 0
                ? round(($count / $totalReviews) * 100)
                : 0;
        }

        return view('admin.author.reviews', compact(
            'reviews',
            'totalReviews',
            'averageRating',
            'fiveStars',
            'satisfaction',
            'ratingCounts',
            'ratingPercentages'
        ));
    }

    public function activities()
    {
        $user = Auth::user();

        $notifications = $user->notifications()
            ->latest()
            ->paginate(15);

        $user->unreadNotifications->markAsRead();

        return view('admin.author.activities', compact('notifications'));
    }

    public function destroyNotification(DatabaseNotification $notification)
    {
        abort_unless($notification->notifiable_id === Auth::id(), 403);

        $notification->delete();

        return back()->with('success', 'Notification supprimée.');
    }

    public function revenues()
    {
        $userId = Auth::id();

        $purchaseBase = Payment::whereHas('book', fn ($q) => $q->where('user_id', $userId))
            ->where('status', 'success')
            ->where('type', 'purchase');

        $totalRevenue = (clone $purchaseBase)->sum('amount');
        $monthlyRevenue = (clone $purchaseBase)
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('amount');
        $totalSales = (clone $purchaseBase)->count();
        $totalReaders = (clone $purchaseBase)->distinct('user_id')->count('user_id');

        $totalWithdrawn = Withdrawal::query()
            ->where('user_id', $userId)
            ->where('status', 'approved')
            ->sum('amount');

        $pendingWithdrawals = Withdrawal::query()
            ->where('user_id', $userId)
            ->where('status', 'pending')
            ->sum('amount');

        $availableBalance = max(0, $totalRevenue - $totalWithdrawn - $pendingWithdrawals);

        $pendingAmount = Payment::whereHas('book', fn ($q) => $q->where('user_id', $userId))
            ->where('status', 'pending')
            ->where('type', 'purchase')
            ->sum('amount');

        $purchasePayments = Payment::with(['book', 'user'])
            ->whereHas('book', fn ($query) => $query->where('user_id', $userId))
            ->where('type', 'purchase')
            ->where('status', 'success')
            ->latest()
            ->paginate(10, ['*'], 'sales_page');

        $publicationPayments = Payment::with('book')
            ->whereHas('book', fn ($query) => $query->where('user_id', $userId))
            ->where('type', 'publication')
            ->latest()
            ->paginate(10, ['*'], 'publication_page');

        return view('admin.author.revenues', compact(
            'totalRevenue',
            'monthlyRevenue',
            'totalSales',
            'totalReaders',
            'pendingAmount',
            'purchasePayments',
            'publicationPayments',
            'availableBalance',
            'totalWithdrawn',
            'pendingWithdrawals',
        ));
    }
}
