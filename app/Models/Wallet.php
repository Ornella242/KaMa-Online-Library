<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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

    public function user()
    {
        return $this->belongsTo(User::class);
    }


    public function addBalance(float $amount, $description = null): void
    {
        $this->balance += $amount;
        $this->save();
        $this->transactions()->create([
            'amount' => $amount,
            'type' => 'sale',
            'description' => $description
        ]);

    }

    public function transactions()
    {
        return $this->hasMany(WalletTransaction::class);
    }
}
