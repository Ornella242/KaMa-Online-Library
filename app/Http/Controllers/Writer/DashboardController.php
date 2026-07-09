<?php

namespace App\Http\Controllers\Writer;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Review;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{

    private function calculateGrowth($current, $previous)
    {
        if($previous == 0){
            return 0;
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
            ->whereMonth(
                'created_at',
                now()->month
            )
            ->count();

        $previousPublishedBooks = Book::query()->where('user_id',$userId)
            ->where('status','published')
            ->whereMonth(
                'created_at',
                now()->subMonth()->month
            )
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
        ->whereMonth('created_at',now()->month)
        ->sum('amount');

        $previousRevenue = Payment::whereHas('book',function($q) use($userId){
            $q->where('user_id',$userId);
        })
        ->where('status','success')
        ->where('type','purchase')
        ->whereMonth(
            'created_at',
            now()->subMonth()->month
        )
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
        ->whereMonth('created_at',now()->month)
        ->distinct('user_id')
        ->count('user_id');

        $previousReaders = Payment::whereHas('book',function($q) use($userId){

            $q->where('user_id',$userId);

        })
        ->where('status','success')
        ->where('type','purchase')
        ->whereMonth(
            'created_at',
            now()->subMonth()->month
        )
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

        return view(
            'writer.dashboard',
            compact(
                'publishedBooks',
                'totalBooks',
                'bestBooks', 'totalRevenue','totalReaders','averageRating', 'totalReviews',
                'readersGrowth','booksGrowth','revenueGrowth'
            )
        );


    }
}
