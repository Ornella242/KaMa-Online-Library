<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

class AdminAccountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = User::whereHas('role', function ($query) {
            $query->where('name', 'admin');
        })
        ->where('is_main_admin', false)
        ->first();

        if (!$admin && !User::query()->where('is_main_admin', true)->exists()) {
            return;
        }

        if (User::query()->where('is_main_admin', true)->exists()) {
            return;
        }

        $admin->update([
            'is_main_admin' => true,
        ]);
    }
}
