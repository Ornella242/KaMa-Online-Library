<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Subcategory;
use App\Models\Category;

class Book extends Model
{
        protected $fillable = [
        'user_id',
        'category_id',
        'subcategory_id',
        'title',
        'short_description',
        'long_description',
        'type',
        'price',
        'pages',
        'duration',
        'language',
        'publication_year',
        'cover_image',
        'file_path',
        'file_type',
        'original_file_name',
        'file_size',
    ];

    public function author()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function subcategory()
    {
        return $this->belongsTo(Subcategory::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function activities()
    {
        return $this->hasMany(Activity::class);
    }
}
