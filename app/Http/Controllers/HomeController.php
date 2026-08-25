<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Book;
use App\Models\Advertisement;
use App\Models\BookSponsorship;
use App\Models\User;
use App\Models\Review;
use App\Models\Category;
use App\Models\FaqCategory;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
      public function index()
    {
        $totalBooks = Book::published()->count();
        $authors = User::whereHas('books', function($query){
            $query->where('status', 'published');
        })->get();

        $books = Book::published()->with(['author', 'category'])
            ->withAvg('reviews', 'rating')
            ->withCount('reviews')
            ->latest()
            ->take(6)
            ->get();

        $categories = Category::withCount('books')
        ->orderBy('books_count', 'desc')
        ->take(6)
        ->get();


        $sponsoredBooks = BookSponsorship::with([
            'book.author'
        ])
        ->where('status','paid')
        ->where('ends_at','>',now())
        ->get();

        $sponsoredPrestigeBooks = BookSponsorship::with('book.author')
        ->where('sponsorship_plan_id', 7)
        ->where('status', 'paid')
        ->where('ends_at','>',now())
        ->get();

       // Livre le mieux noté (grand affichage)
        $bestRatedBook = Book::published()->with(['author', 'category'])
            ->withAvg('reviews', 'rating')
            ->withCount('reviews')
            ->has('reviews', '>=', 1)
            // ->has('reviews', '>=', 3)
            ->orderByDesc('reviews_avg_rating')
            ->first();

        // dd($bestRatedBook);

        // Autres livres les mieux notés (mini cartes)
        $topRatedBooks = Book::published()->with(['author', 'category'])
            ->withAvg('reviews', 'rating')
            ->withCount('reviews')
            // ->has('reviews', '>=', 3)
            ->has('reviews', '>=', 1)
            ->orderByDesc('reviews_avg_rating')
            ->skip(1)
            ->take(6)
            ->get();

        $highestRatedBooks = Book::published()->with(['author', 'category'])
            ->withAvg('reviews', 'rating')
            ->withCount('reviews')
            ->has('reviews', '>=', 3)
            ->orderByDesc('reviews_avg_rating')
            ->take(6)
            ->get();

            $bestSellingBooks = Book::published()->with(['author', 'category'])
            ->withCount([
                'payments as sales_count' => function ($query) {
                    $query->where('type', 'purchase')
                        ->where('status', 'success');
                }
            ])
            ->orderByDesc('sales_count')
            ->take(6)
            ->get();

            $latestReviews = Review::with([
                'user',
                'book'
            ])
            ->latest()
            ->take(10)
            ->get();

        $topAuthors = User::select([
            'users.id',
            'users.firstname',
            'users.lastname',
            'users.avatar',
            'users.country_id',
        ])
        ->addSelect(DB::raw('COUNT(payments.id) as sales_count'))
        ->join('books', 'books.user_id', '=', 'users.id')
        ->join('payments', 'payments.book_id', '=', 'books.id')
        ->where('payments.type', 'purchase')
        ->where('payments.status', 'success')
        ->groupBy(
            'users.id',
            'users.firstname',
            'users.lastname',
            'users.avatar',
            'users.country_id'
        )
        ->orderByDesc('sales_count')
        ->take(3)
        ->get();

        return view('home.index', compact('totalBooks',
            'books',
            'categories',
            'bestRatedBook',
            'topRatedBooks','highestRatedBooks',
            'bestSellingBooks','latestReviews','topAuthors','sponsoredBooks', 'authors','sponsoredPrestigeBooks'
        ));

    }

    public function about()
    {
         return view('home.about');
    }

    public function faq()
    {
         $categories = FaqCategory::with([
            'faqs'=>function($q){
                $q->where('status',true)
                ->orderBy('order');
            }
        ])
        ->orderBy('order')
        ->get();

        return view('home.faq',compact('categories'));
    }
}
