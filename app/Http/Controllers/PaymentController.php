<?php

namespace App\Http\Controllers;
use App\Models\Payment;
use App\Models\Book;
use App\Models\User;
use App\Notifications\NewBookSubmittedNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    public function payPublication(Book $book)
    {

        if($book->user_id !== Auth::id()){
            abort(403);
        }

        $payment = Payment::create([
            'user_id' => Auth::id(),
            'book_id' => $book->id,
            'reference' => 'KAMA-'.time(),
            'amount' => 10,
            'currency' => 'USD',
            'status' => 'success',
            'type' => 'publication',
            'payment_method' => 'manual',
            'transaction_id' => null,
        ]);

        $book->update([
            'status' => 'waiting_review'
        ]);

        // Notification
        $admins = User::whereHas('role', function($query){
            $query->where('name','Admin');
        })->get();

        foreach($admins as $admin){

            $admin->notify(
                new NewBookSubmittedNotification($book)
            );

        }



        return redirect()
            ->back()
            ->with(
                'success',
                'Paiement du dépôt effectué. Votre livre sera vérifié prochainement.'
            );

    }
}
