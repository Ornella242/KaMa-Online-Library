<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
     protected $fillable = [

        'user_id',
        'book_id',
        'reference',
        'amount',
        'currency',
        'status',
        'type',
        'payment_method',
        'transaction_id'

    ];


    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function book()
    {
        return $this->belongsTo(Book::class);
    }
}
