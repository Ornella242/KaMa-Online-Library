<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PublicationFee extends Model
{
    protected $fillable = [
        'book_type',
        'amount',
        'currency',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
        ];
    }

    public static function forType(string $bookType): ?self
    {
        return static::query()
            ->where('book_type', $bookType)
            ->first();
    }
}
