<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Role::create([
        'name' => 'reader',
        'description' => 'Utilisateur qui lit et achète des livres.',
        ]);

        Role::create([
            'name' => 'writer',
            'description' => 'Auteur pouvant publier ses ouvrages.',
        ]);

        Role::create([
            'name' => 'admin',
            'description' => 'Administrateur de la plateforme.',
        ]);
    }
}
