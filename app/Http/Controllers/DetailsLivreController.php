<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Advertisement;
use App\Models\Payment;
use App\Models\Review;
use App\Models\Wishlist;
use App\Services\CartService;
use App\Services\PurchaseClaimService;
use Illuminate\Support\Facades\Auth;

class DetailsLivreController extends Controller
{
    public function index(Book $book, CartService $cart, PurchaseClaimService $claims)
    {
        abort_unless($book->status === Book::STATUS_PUBLISHED, 404);

        $book->load([
            'author',
            'category',
            'subcategory',
            'reviews' => fn ($q) => $q->with('user')->latest(),
        ]);

        $book->loadAvg('reviews', 'rating');
        $book->loadCount('reviews');

        $sameAuthorBooks = Book::query()
            ->published()
            ->where('user_id', $book->user_id)
            ->where('id', '!=', $book->id)
            ->withAvg('reviews', 'rating')
            ->take(8)
            ->get();

        $sponsoredBooks = Advertisement::with(['book.author'])
            ->where('status', 'active')
            ->whereDate('start_date', '<=', now())
            ->whereDate('end_date', '>=', now())
            ->take(6)
            ->get();

        $previewStart = $book->preview_start_page;
        $previewEnd = $book->preview_end_page;

        $userReview = null;
        $alreadyOwned = false;
        $inWishlist = false;
        $inCart = $cart->has($book->id);
        $isOwner = Auth::check() && Auth::id() === $book->user_id;

        if (Auth::check()) {
            $claims->claimFor(Auth::user());

            $userReview = Review::query()
                ->where('book_id', $book->id)
                ->where('user_id', Auth::id())
                ->first();

            $alreadyOwned = Payment::query()
                ->where('user_id', Auth::id())
                ->where('book_id', $book->id)
                ->where('type', 'purchase')
                ->where('status', 'success')
                ->exists();

            $inWishlist = Wishlist::query()
                ->where('user_id', Auth::id())
                ->where('book_id', $book->id)
                ->exists();
        }

        return view('books.detaillivre', compact(
            'book',
            'sameAuthorBooks',
            'sponsoredBooks',
            'previewStart',
            'previewEnd',
            'userReview',
            'alreadyOwned',
            'inCart',
            'inWishlist',
            'isOwner'
        ));
    }
}
