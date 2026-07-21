<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Subcategory;
use App\Models\Category;

class Book extends Model
{
    public const STATUS_DRAFT = 'draft';
    public const STATUS_WAITING_REVIEW = 'waiting_review';
    public const STATUS_UNDER_REVIEW = 'under_review';
    public const STATUS_PUBLISHED = 'published';
    public const STATUS_REJECTED = 'rejected';
    public const STATUS_REVISION_REQUIRED = 'revision_required';

    public const STATUSES = [
        self::STATUS_DRAFT,
        self::STATUS_WAITING_REVIEW,
        self::STATUS_UNDER_REVIEW,
        self::STATUS_PUBLISHED,
        self::STATUS_REJECTED,
        self::STATUS_REVISION_REQUIRED,
    ];

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
        'preview_type',
        'preview_start_page',
        'preview_end_page',
        'status',
        'rejection_reason',
        'copyright_accepted',
        'copyright_accepted_at'
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

    public function publicationPayment()
    {
        return $this->hasOne(Payment::class)
            ->where('type', 'publication')
            ->latestOfMany();
    }

    public function scopePublished(\Illuminate\Database\Eloquent\Builder $query)
    {
        return $query->where('status', self::STATUS_PUBLISHED);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function activities()
    {
        return $this->hasMany(Activity::class);
    }

    public function sponsorships()
    {
        return $this->hasMany(BookSponsorship::class);
    }

    public function activeSponsorship()
    {
        return $this->hasOne(BookSponsorship::class)
            ->where('status','paid')
            ->where('ends_at','>',now());
    }
}
