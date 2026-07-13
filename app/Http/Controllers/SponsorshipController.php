<?php

namespace App\Http\Controllers;
use App\Models\SponsorshipPlan;
use App\Models\BookSponsorship;
use App\Models\Book;
use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;

class SponsorshipController extends Controller
{
    public function create(Book $book)
    {

        $plans = SponsorshipPlan::query()->where('active',true)
                ->get();


        return view(
            'writer.books.sponsor',
            compact(
                'book',
                'plans'
            )
        );

    }

    public function store(Book $book, SponsorshipPlan $plan)
    {

        $sponsorship = BookSponsorship::create([

            'book_id' => $book->id,

            'writer_id' => Auth::id(),

            'sponsorship_plan_id' => $plan->id,

            'amount' => $plan->price,

            'status' => 'pending',

        ]);


        return redirect()->route(
            'writer.sponsorship.payment',
            $sponsorship
        );

    }

    public function payment(BookSponsorship $sponsorship)
    {
        return view(
            'writer.sponsorship.payment',
            compact('sponsorship')
        );
    }
}
