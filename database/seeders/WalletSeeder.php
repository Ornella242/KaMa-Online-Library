<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\User;
use App\Models\Wallet;
use Illuminate\Database\Seeder;

class WalletSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::whereHas('role', function ($query) {

            $query->whereIn('name', [
                'writer',
                'admin'
            ]);

        })
        ->each(function ($user) {

            Wallet::firstOrCreate(
                [
                    'user_id' => $user->id
                ],
                [
                    'balance' => 0,
                    'currency' => 'USD'
                ]
            );

        });
    }
}
