<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\BookSponsorship;
use Illuminate\Http\Request;

class BookSponsorshipController extends Controller
{
    public function sponsor(Book $book)
    {
      
        BookSponsorship::create([
            'book_id' => $book->id,
            'writer_id' => $book->user_id,
            'sponsorship_plan_id' => null,
            'amount' => 0,
            'transaction_reference' => 'ADMIN_SPONSOR',
            'status' => 'paid',
            'starts_at' => now(),
            'ends_at' => now()->addDays(30),
            'paid_at' => null,
            'source' => 'admin',
        ]);


        return back()->with(
            'success',
            'Le livre est maintenant mis en avant sur KaMa.'
        );

    }
}
