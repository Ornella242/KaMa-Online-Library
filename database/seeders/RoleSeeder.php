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
         Role::updateOrCreate(
            ['name' => 'admin'],
            [
                'label' => 'Administrateur',
                'description' => 'Administrateur de la plateforme KaMa.',
            ]
        );
        
        Role::updateOrCreate(
            ['name' => 'reader'],
            [
                'label' => 'Lecteur',
                'description' => 'Utilisateur qui lit et achète des livres.',
            ]
        );

        Role::updateOrCreate(
            ['name' => 'writer'],
            [
                'label' => 'Auteur',
                'description' => 'Utilisateur qui publie des livres.',
            ]
        );

       
    }
}
