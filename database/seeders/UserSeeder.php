<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'firstname' => 'Leopold-Auguste',
            'lastname' => 'Ngomo',
            'email' => 'ngomoauguste@gmail.com',
            'country_id' => 1,
            'role_id' => 1,
            'password' => Hash::make('Admin@1234'),
        ]);
    }
}
