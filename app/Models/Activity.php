<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
     protected $fillable = [

        'user_id',
        'book_id',
        'type',
        'title',
        'description',
        'icon',
        'color'

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
