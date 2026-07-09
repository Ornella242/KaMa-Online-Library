<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Withdrawal extends Model
{
     protected $fillable = [

        'user_id',
        'amount',
        'status',
        'payment_method',
        'account_number'

    ];


    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
