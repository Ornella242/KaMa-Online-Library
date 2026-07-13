<?php

namespace App\Http\Controllers;
use App\Models\Book;
use App\Models\Advertisement;
use Illuminate\Http\Request;

class DetailsLivreController extends Controller
{
    
    public function index(Book $book)
    {
        $book->load([
            'author',
            'category',
            'subcategory',
            'reviews'
        ]);

        $book->loadAvg('reviews', 'rating');
        $book->loadCount('reviews');


        // Livres du même auteur
        $sameAuthorBooks = Book::query()
            ->where('user_id', $book->user_id)
            ->where('id', '!=', $book->id)
            ->withAvg('reviews', 'rating')
            ->take(10)
            ->get();


        // Livres sponsorisés
        $sponsoredBooks = Advertisement::with([
                'book.author'
            ])
            ->where('status', 'active')
            ->whereDate('start_date', '<=', now())
            ->whereDate('end_date', '>=', now())
            ->take(10)
            ->get();


        $previewStart = $book->preview_start_page;

        $previewEnd = $book->preview_end_page;


        return view('books.detaillivre', compact(
            'book',
            'sameAuthorBooks',
            'sponsoredBooks','previewStart','previewEnd'
        ));
    }

    
}
