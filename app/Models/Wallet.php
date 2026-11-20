<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Wallet extends Model
{
    protected $fillable = [
        'user_id',
        'balance',
        'currency',
    ];

    protected $casts = [
        'balance' => 'float',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(WalletTransaction::class);
    }

    /**
     * @deprecated Prefer WalletService::creditSaleFromPayment for sales.
     */
    public function addBalance(float $amount, $description = null): void
    {
        $this->balance = round((float) $this->balance + $amount, 2);
        $this->save();

        $this->transactions()->create([
            'amount' => $amount,
            'type' => 'sale',
            'description' => $description,
        ]);
    }
}
