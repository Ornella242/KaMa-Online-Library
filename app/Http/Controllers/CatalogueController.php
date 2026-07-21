<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Book;
use App\Models\BookSponsorship;
use App\Models\User;
use App\Models\Category;
use Illuminate\Support\Facades\Auth;

class CatalogueController extends Controller
{
    
    public function index(Request $request)
    {
        $categories = Category::with('subcategories')->get();

        $query = Book::with(['author', 'category', 'subcategory'])
        ->where('status', 'published')
        ->withAvg('reviews', 'rating');

        
        if ($request->filled('search')) {

            $search = $request->search;

            $query->where(function ($q) use ($search) {

                $q->where('title', 'like', "%{$search}%")

                    ->orWhereHas('author', function ($author) use ($search) {
                        $author->where('firstname', 'like', "%{$search}%")
                            ->orWhere('lastname', 'like', "%{$search}%");
                    })

                    ->orWhereHas('category', function ($category) use ($search) {
                        $category->where('name', 'like', "%{$search}%");
                    })

                    // CHANGÉ : recherche également dans les sous-catégories
                    ->orWhereHas('subcategory', function ($subcategory) use ($search) {
                        $subcategory->where('name', 'like', "%{$search}%");
                    });

            });
        }

        
        if ($request->filled('title')) {
            $query->where('title', 'like', '%' . $request->title . '%');
        }

        
        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        
        if ($request->filled('subcategory')) {
            $query->where('subcategory_id', $request->subcategory);
        }

        
        if ($request->filled('price')) {

            switch ($request->price) {

                case '10-20':
                    $query->whereBetween('price', [10, 20]);
                    break;

                case '30-40':
                    $query->whereBetween('price', [30, 40]);
                    break;

                case '40-50':
                    $query->whereBetween('price', [40, 50]);
                    break;

                case '50+':
                    $query->where('price', '>=', 50);
                    break;
            }
        }

      
        if ($request->filled('author')) {
            $query->where('user_id', $request->author);
        }

        if ($request->filled('rating')) {

            $query->whereRaw(
                '(SELECT AVG(rating) FROM reviews WHERE reviews.book_id = books.id) >= ?',
                [$request->rating]
            );

        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        $books = $query
            ->latest()
            ->paginate(6)
            ->withQueryString();

        $authors = User::whereHas('books', function($query){
            $query->where('status', 'published');
        })->get();

        $sponsoredBooks = BookSponsorship::with([
            'book.author'
        ])
        ->where('status','paid')
        ->where('ends_at','>',now())
        ->whereHas('book', function($query){
            $query->where('status','published');
        })
        ->get();

        $representedCountries = User::whereHas('books', function($query){
                $query->where('status', 'published');

            })
            ->whereNotNull('country_id')
            ->distinct('country_id')
            ->count('country_id');

        $wishlistIds = auth()->check()
            ? \App\Models\Wishlist::query()
                ->where('user_id', Auth::id())
                ->pluck('book_id')
                ->all()
            : [];

        return view('books.catalogue', compact(
            'books',
            'categories',
            'authors',
            'sponsoredBooks',
            'wishlistIds','representedCountries'
        ));
    }


}
