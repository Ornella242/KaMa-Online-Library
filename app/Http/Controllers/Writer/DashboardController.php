<?php

namespace App\Http\Controllers\Writer;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\BookSponsorship;
use App\Models\Review;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{

    private function calculateGrowth($current, $previous)
    {
        if($previous == 0){
            return $current > 0 ? 100 : 0;
        }
        return round((($current - $previous) / $previous) * 100);
    }


    public function index()
    {
        $userId = Auth::id();
        // Total livres publiés
        $publishedBooks = Book::query()
                    ->where('user_id', Auth::id())
                    ->where('status', 'published')
                    ->count();

        $currentPublishedBooks = Book::query() -> where('user_id',$userId)
            ->where('status','published')
            ->whereBetween('created_at', [now()->startOfMonth(), now()->endOfMonth()])
            ->count();

        $previousPublishedBooks = Book::query()->where('user_id',$userId)
            ->where('status','published')
            ->whereBetween('created_at', [
                now()->subMonth()->startOfMonth(),
                now()->subMonth()->endOfMonth(),
            ])
            ->count();

        $booksGrowth = $this->calculateGrowth(
            $currentPublishedBooks,
            $previousPublishedBooks
        );

        // Total livres
        $totalBooks = Book::query()
                    ->where('user_id', $userId)
                    ->count();

        // Livres les plus vendus

        $bestBooks = Book::query()->where('user_id', $userId)
            ->where('status','published')
            ->withCount([
                'payments as sales_count' => function($query){
                    $query->where('status','success')
                        ->where('type','purchase');
                }
            ])
            ->withAvg('reviews','rating')
            ->having('sales_count', '>', 0)
            ->orderByDesc('sales_count')
            ->take(5)
            ->get();


        $totalRevenue = Payment::whereHas('book', function($query) use ($userId){

             $query->where('user_id',$userId);
        })
            ->where('status','success')
            ->where('type','purchase')
            ->sum('amount');

        $currentRevenue = Payment::whereHas('book',function($q) use($userId){

            $q->where('user_id',$userId);

        })
        ->where('status','success')
        ->where('type','purchase')
        ->whereBetween('created_at', [now()->startOfMonth(), now()->endOfMonth()])
        ->sum('amount');

        $previousRevenue = Payment::whereHas('book',function($q) use($userId){
            $q->where('user_id',$userId);
        })
        ->where('status','success')
        ->where('type','purchase')
        ->whereBetween('created_at', [
            now()->subMonth()->startOfMonth(),
            now()->subMonth()->endOfMonth(),
        ])
        ->sum('amount');

        $revenueGrowth = $this->calculateGrowth(
            $currentRevenue,
            $previousRevenue
        );

        $totalReaders = Payment::whereHas('book', function($query) use ($userId){

             $query->where('user_id', $userId);

        })
            ->where('status', 'success')
            ->where('type', 'purchase')
            ->distinct('user_id')
            ->count('user_id');

        $currentReaders = Payment::whereHas('book',function($q) use($userId){
            $q->where('user_id',$userId);
        })
        ->where('status','success')
        ->where('type','purchase')
        ->whereBetween('created_at', [now()->startOfMonth(), now()->endOfMonth()])
        ->distinct('user_id')
        ->count('user_id');

        $previousReaders = Payment::whereHas('book',function($q) use($userId){

            $q->where('user_id',$userId);

        })
        ->where('status','success')
        ->where('type','purchase')
        ->whereBetween('created_at', [
            now()->subMonth()->startOfMonth(),
            now()->subMonth()->endOfMonth(),
        ])
        ->distinct('user_id')
        ->count('user_id');

        $readersGrowth = $this->calculateGrowth(
            $currentReaders,
            $previousReaders
        );

        $averageRating = Review::whereHas('book', function($query) use ($userId){
            $query->where('user_id', $userId);

        })
        ->avg('rating');

        $totalReviews = Review::whereHas('book', function($query) use ($userId){
            $query->where('user_id',$userId);

        })
        ->count();

        // ==========================================
// STATISTIQUES PAR LIVRE
// ==========================================

$bookPerformance = Book::query()
    ->where('user_id', $userId)
    ->where('status', 'published')
    ->get()
    ->map(function ($book) {

        // Frais de dépôt/publication
        $publicationFee = Payment::where('book_id', $book->id)
            ->where('status', 'success')
            ->where('type', 'publication')
            ->sum('amount');

        // Total des sponsoring payés
        $sponsorshipAmount = BookSponsorship::where('book_id', $book->id)
            ->where('status', 'success')
            ->sum('amount');

        // Total des ventes du livre
        $salesAmount = Payment::where('book_id', $book->id)
            ->where('status', 'success')
            ->where('type', 'purchase')
            ->sum('amount');

        // Budget total investi dans le livre
        $investment = $publicationFee + $sponsorshipAmount;

        return [
            'label' => $book->title,
            'investment' => $investment,
            'sales' => $salesAmount,
        ];
    });

        return view(
            'writer.dashboard',
            compact(
                'publishedBooks',
                'totalBooks',
                'bestBooks', 'totalRevenue','totalReaders','averageRating', 'totalReviews',
                'readersGrowth','booksGrowth','revenueGrowth','bookPerformance'
            )
        );


    }
}
