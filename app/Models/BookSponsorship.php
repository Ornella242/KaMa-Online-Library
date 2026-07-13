<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BookSponsorship extends Model
{
     protected $fillable = [
        'book_id',
        'writer_id',
        'sponsorship_plan_id',
        'amount',
        'transaction_reference',
        'status',
        'starts_at',
        'ends_at',
        'paid_at',
    ];

    protected $casts = [
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
        'paid_at' => 'datetime',
    ];

    /* Relations */

    public function book()
    {
        return $this->belongsTo(Book::class);
    }

    public function writer()
    {
        return $this->belongsTo(User::class, 'writer_id');
    }

    public function plan()
    {
        return $this->belongsTo(SponsorshipPlan::class, 'sponsorship_plan_id');
    }
}
