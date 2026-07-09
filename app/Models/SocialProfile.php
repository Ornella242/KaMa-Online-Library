<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SocialProfile extends Model
{
    protected $fillable = [
        'user_id',
        'facebook_url',
        'x_url',
        'instagram_url',
        'linkedin_url',
        'tiktok_url',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
