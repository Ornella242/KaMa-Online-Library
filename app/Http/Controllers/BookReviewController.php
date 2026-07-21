<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BookReviewController extends Controller
{
    public function store(Request $request, Book $book)
    {
        abort_unless($book->status === Book::STATUS_PUBLISHED, 404);

        if (! Auth::check()) {
            return redirect()
                ->route('login')
                ->with('error', 'Connectez-vous pour laisser un avis.');
        }

        abort_if(Auth::id() === $book->user_id, 403, 'Vous ne pouvez pas noter votre propre livre.');

        $data = $request->validate([
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'comment' => ['required', 'string', 'min:10', 'max:1000'],
        ], [
            'comment.required' => 'Merci de rédiger un commentaire.',
            'comment.min' => 'Le commentaire doit contenir au moins 10 caractères.',
            'rating.required' => 'Veuillez choisir une note.',
        ]);

        Review::updateOrCreate(
            [
                'book_id' => $book->id,
                'user_id' => Auth::id(),
            ],
            [
                'rating' => $data['rating'],
                'comment' => $data['comment'],
            ]
        );

        return redirect()
            ->to(route('books.show', $book) . '#avis')
            ->with('success', 'Votre avis a été publié. Merci !');
    }
}
