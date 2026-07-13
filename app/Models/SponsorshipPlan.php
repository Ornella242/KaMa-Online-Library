<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SponsorshipPlan extends Model
{
    protected $fillable = [
        'name',
        'duration_days',
        'price',
        'active'
    ];

    public function sponsorships()
    {
        return $this->hasMany(BookSponsorship::class);
    }
}
