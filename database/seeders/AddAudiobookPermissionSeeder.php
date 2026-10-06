<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Permission;

class AddAudiobookPermissionSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            [
                'name' => 'books.audio.generate',
                'label' => 'Générer l’audiobook',
                'description' => 'Permet de générer un audiobook à partir du contenu d’un livre.',
            ],
            [
                'name' => 'books.audio.download',
                'label' => 'Télécharger l’audiobook',
                'description' => 'Permet de télécharger le fichier audio d’un audiobook.',
            ],
            [
                'name' => 'books.audio.delete',
                'label' => 'Supprimer l’audiobook',
                'description' => 'Permet de supprimer un audiobook généré.',
            ],
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(
                ['name' => $permission['name']],
                [
                    'label' => $permission['label'],
                    'description' => $permission['description'],
                ]
            );
        }
    }
}