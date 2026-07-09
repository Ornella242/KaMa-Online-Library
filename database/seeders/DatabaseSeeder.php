<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */

        public function run(): void
        {
            // User::factory(10)->create();
              $this->call([
                RoleSeeder::class,
                CategorySeeder::class,
                WalletSeeder::class,
            ]);

            User::create([
            'firstname' => 'Leopold-Auguste',
            'lastname' => 'Ngomo',
            'email' => 'ngomo@gmail.com',
            'country' => 'Ghana',
            'role_id' => 3,
            'password' => Hash::make('Admin@1234'),
        ]);
    }
}
