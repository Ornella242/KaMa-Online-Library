<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Wishlist;
use App\Services\CartService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WishlistController extends Controller
{
    public function index()
    {
        $items = Wishlist::query()
            ->with(['book.author', 'book.category', 'book.reviews'])
            ->where('user_id', Auth::id())
            ->latest()
            ->get()
            ->filter(fn (Wishlist $item) => $item->book !== null)
            ->values();

        return view('reader.wishlist', compact('items'));
    }

    public function store(Book $book)
    {
        abort_unless($book->status === Book::STATUS_PUBLISHED, 404);

        Wishlist::query()->firstOrCreate([
            'user_id' => Auth::id(),
            'book_id' => $book->id,
        ]);

        return back()->with('success', 'Livre ajouté à votre liste de souhaits.');
    }

    public function destroy(Book $book)
    {
        Wishlist::query()
            ->where('user_id', Auth::id())
            ->where('book_id', $book->id)
            ->delete();

        return back()->with('success', 'Livre retiré de votre liste de souhaits.');
    }

    public function clear()
    {
        Wishlist::query()
            ->where('user_id', Auth::id())
            ->delete();

        return redirect()
            ->route('reader.wishlist')
            ->with('success', 'Liste de souhaits vidée.');
    }

    public function moveToCart(Book $book, CartService $cart)
    {
        abort_unless($book->status === Book::STATUS_PUBLISHED, 404);

        $cart->add($book->loadMissing('author'));

        Wishlist::query()
            ->where('user_id', Auth::id())
            ->where('book_id', $book->id)
            ->delete();

        return redirect()
            ->route('cart.index')
            ->with('success', 'Livre ajouté au panier.');
    }
}
