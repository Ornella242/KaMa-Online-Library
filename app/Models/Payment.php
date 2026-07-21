<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
     protected $fillable = [
        'user_id',
        'guest_email',
        'book_id',
        'order_id',
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

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    protected static function booted(): void
    {
        static::saved(function (Payment $payment): void {
            if ($payment->type !== 'purchase' || $payment->status !== 'success' || ! $payment->user_id) {
                return;
            }

            SiteVisit::query()
                ->where('user_id', $payment->user_id)
                ->whereNull('converted_at')
                ->where('created_at', '<=', $payment->created_at ?? now())
                ->where('created_at', '>=', ($payment->created_at ?? now())->copy()->subDays(30))
                ->latest()
                ->first()
                ?->update(['converted_at' => $payment->created_at ?? now()]);
        });
    }
}
