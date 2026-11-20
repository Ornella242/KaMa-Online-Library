<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Payment;
use App\Services\CartService;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function __construct(private CartService $cart)
    {
    }

    public function index()
    {
        $items = $this->cart->items();
        $books = $this->cart->books();
        $total = $this->cart->total();

        return view('cart.index', compact('items', 'books', 'total'));
    }

    public function store(Book $book)
    {
        abort_unless($book->status === Book::STATUS_PUBLISHED, 404);

        if (Auth::check() && Auth::id() === $book->user_id) {
            return back()->with('error', 'Vous ne pouvez pas acheter votre propre livre.');
        }

        if (
            Auth::check() &&
            Payment::query()
                ->where('user_id', Auth::id())
                ->where('book_id', $book->id)
                ->where('type', 'purchase')
                ->where('status', 'success')
                ->exists()
        ) {
            return back()->with('error', 'Vous possédez déjà ce livre.');
        }

        $this->cart->add($book->loadMissing('author'));

        return back()->with('success', 'Livre ajouté avec succès.');
    }

    public function destroy(Book $book)
    {
        $this->cart->remove($book->id);

        return back()->with('success', 'Livre retiré du panier.');
    }

    public function clear()
    {
        $this->cart->clear();

        return redirect()
            ->route('cart.index')
            ->with('success', 'Panier vidé.');
    }

}
