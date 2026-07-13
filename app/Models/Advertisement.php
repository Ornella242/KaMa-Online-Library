<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Advertisement extends Model
{
    protected $fillable = [
        'book_id',
        'user_id',
        'start_date',
        'end_date',
        'status',
        'amount'
    ];


    public function book()
    {
        return $this->belongsTo(Book::class);
    }


    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
