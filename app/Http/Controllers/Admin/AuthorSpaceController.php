<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\BookSponsorship;
use App\Models\Payment;
use App\Models\Review;
use App\Models\User;
use App\Models\Withdrawal;
use App\Services\WalletService;
use App\Services\WithdrawalService;
use Illuminate\Http\Request;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Support\Facades\Auth;

class AuthorSpaceController extends Controller
{
    public function overview(WalletService $walletService)
    {
        $author = $this->authorAccount();
        $userId = $author->id;

        $publishedBooks = Book::query()
            ->where('user_id', $userId)
            ->where('status', 'published')
            ->count();

        $booksGrowth = $this->growth(
            Book::query()
                ->where('user_id', $userId)
                ->where('status', 'published')
                ->whereBetween('created_at', [now()->startOfMonth(), now()->endOfMonth()])
                ->count(),
            Book::query()
                ->where('user_id', $userId)
                ->where('status', 'published')
                ->whereBetween('created_at', [now()->subMonth()->startOfMonth(), now()->subMonth()->endOfMonth()])
                ->count()
        );

        $purchases = Payment::query()
            ->whereHas('book', fn ($query) => $query->where('user_id', $userId))
            ->where('status', 'success')
            ->where('type', 'purchase');

        $totalRevenue = (float) (clone $purchases)->sum('amount');
        $revenueGrowth = $this->growth(
            (float) (clone $purchases)->whereBetween('created_at', [now()->startOfMonth(), now()->endOfMonth()])->sum('amount'),
            (float) (clone $purchases)->whereBetween('created_at', [now()->subMonth()->startOfMonth(), now()->subMonth()->endOfMonth()])->sum('amount')
        );

        $totalReaders = (int) (clone $purchases)->distinct('user_id')->count('user_id');
        $readersGrowth = $this->growth(
            (int) (clone $purchases)->whereBetween('created_at', [now()->startOfMonth(), now()->endOfMonth()])->distinct('user_id')->count('user_id'),
            (int) (clone $purchases)->whereBetween('created_at', [now()->subMonth()->startOfMonth(), now()->subMonth()->endOfMonth()])->distinct('user_id')->count('user_id')
        );

        $reviewBase = Review::query()->whereHas('book', fn ($query) => $query->where('user_id', $userId));
        $averageRating = (clone $reviewBase)->avg('rating');
        $totalReviews = (clone $reviewBase)->count();

        $bestBooks = Book::query()
            ->where('user_id', $userId)
            ->where('status', 'published')
            ->withCount([
                'payments as sales_count' => function ($query) {
                    $query->where('status', 'success')->where('type', 'purchase');
                },
            ])
            ->withAvg('reviews', 'rating')
            ->having('sales_count', '>', 0)
            ->orderByDesc('sales_count')
            ->take(5)
            ->get();

        $bookPerformance = Book::query()
            ->where('user_id', $userId)
            ->where('status', 'published')
            ->get()
            ->map(function (Book $book) {
                $publicationFee = (float) Payment::query()
                    ->where('book_id', $book->id)
                    ->where('status', 'success')
                    ->where('type', 'publication')
                    ->sum('amount');

                $sponsorshipAmount = (float) BookSponsorship::query()
                    ->where('book_id', $book->id)
                    ->where('status', 'success')
                    ->sum('amount');

                $salesAmount = (float) Payment::query()
                    ->where('book_id', $book->id)
                    ->where('status', 'success')
                    ->where('type', 'purchase')
                    ->sum('amount');

                return [
                    'label' => $book->title,
                    'investment' => round($publicationFee + $sponsorshipAmount, 2),
                    'sales' => round($salesAmount, 2),
                ];
            })
            ->values();

        $wallet = $walletService->ensureWallet($author);

        return view('admin.author.overview', compact(
            'publishedBooks',
            'booksGrowth',
            'totalRevenue',
            'revenueGrowth',
            'totalReaders',
            'readersGrowth',
            'averageRating',
            'totalReviews',
            'bestBooks',
            'bookPerformance',
            'wallet',
        ));
    }

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
            ->where('status', Withdrawal::STATUS_COMPLETED)
            ->sum('amount');

        $pendingWithdrawals = Withdrawal::query()
            ->where('user_id', $userId)
            ->whereIn('status', [Withdrawal::STATUS_INITIATED, Withdrawal::STATUS_PROCESSING])
            ->sum('amount');

        $wallet = app(WalletService::class)->ensureWallet(Auth::user());
        $availableBalance = (float) $wallet->balance;

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

    public function wallet(WalletService $walletService)
    {
        $user = Auth::user();
        $wallet = $walletService->ensureWallet($user);

        $transactions = $wallet->transactions()
            ->with(['payment.book', 'payment.user'])
            ->latest()
            ->paginate(15);

        $totalCredited = (float) $wallet->transactions()
            ->where('type', 'sale')
            ->sum('amount');

        $totalWithdrawn = (float) $wallet->transactions()
            ->where('type', 'withdrawal')
            ->sum('amount');

        $salesThisMonth = (float) $wallet->transactions()
            ->where('type', 'sale')
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('amount');

        return view('admin.author.wallet', compact(
            'wallet',
            'transactions',
            'totalCredited',
            'totalWithdrawn',
            'salesThisMonth',
        ));
    }

    public function withdrawals()
    {
        $withdrawals = Withdrawal::query()
            ->where('user_id', Auth::id())
            ->latest()
            ->paginate(15);

        return view('admin.author.withdrawals-index', compact('withdrawals'));
    }

    public function createWithdrawal(WalletService $walletService, WithdrawalService $withdrawalService)
    {
        $wallet = $walletService->ensureWallet(Auth::user());
        $methods = Withdrawal::paymentMethods();
        $commissionPercent = $withdrawalService->commissionPercent();
        $minimumAmount = $withdrawalService->minimumAmount();

        $openWithdrawal = Withdrawal::query()
            ->where('user_id', Auth::id())
            ->whereIn('status', [Withdrawal::STATUS_INITIATED, Withdrawal::STATUS_PROCESSING])
            ->latest()
            ->first();

        return view('admin.author.withdrawals-create', compact(
            'wallet',
            'methods',
            'commissionPercent',
            'minimumAmount',
            'openWithdrawal',
        ));
    }

    public function storeWithdrawal(Request $request, WithdrawalService $withdrawalService)
    {
        $validated = $request->validate([
            'amount' => ['required', 'numeric', 'min:0.01'],
            'payment_method' => ['required', 'string', 'in:'.implode(',', array_keys(Withdrawal::paymentMethods()))],
            'payout_details' => ['required', 'array'],
        ]);

        $details = collect($validated['payout_details'])
            ->map(fn ($value) => is_string($value) ? trim($value) : $value)
            ->all();

        $withdrawal = $withdrawalService->request(
            Auth::user(),
            (float) $validated['amount'],
            $validated['payment_method'],
            $details
        );

        return redirect()
            ->route('admin.author.withdrawals.index')
            ->with('success', 'Demande de retrait #'.$withdrawal->id.' initiée. Net à recevoir : '
                .number_format((float) $withdrawal->net_amount, 2, ',', ' ').' €');
    }

    private function authorAccount(): User
    {
        $user = Auth::user();
        $authorId = $user->authorAccountUserId();

        if ((int) $authorId === (int) $user->id) {
            return $user;
        }

        return User::query()->findOrFail($authorId);
    }

    private function growth(float|int $current, float|int $previous): int
    {
        if ((float) $previous == 0.0) {
            return $current > 0 ? 100 : 0;
        }

        return (int) round((((float) $current - (float) $previous) / (float) $previous) * 100);
    }
}
