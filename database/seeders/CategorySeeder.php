<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         $categories = [

            'Roman' => [
                'Roman africain',
                'Roman historique',
                'Roman romantique',
                'Roman policier',
                'Roman fantastique',
            ],

            'Développement personnel' => [
                'Motivation',
                'Leadership',
                'Confiance en soi',
                'Gestion du temps',
                'Habitudes et réussite',
            ],

            'Business et entrepreneuriat' => [
                'Création d’entreprise',
                'Marketing',
                'Finance',
                'Management',
                'Innovation',
            ],

            'Education' => [
                'Méthodes d’apprentissage',
                'Enseignement',
                'Formation professionnelle',
                'Petite enfance',
                'Sciences de l’éducation',
            ],

            'Histoire et culture' => [
                'Histoire africaine',
                'Civilisations',
                'Patrimoine culturel',
                'Biographies historiques',
                'Traditions',
            ],

            'Sciences et technologie' => [
                'Informatique',
                'Intelligence artificielle',
                'Sciences naturelles',
                'Innovation technologique',
                'Recherche scientifique',
            ],

            'Poésie et théâtre' => [
                'Poésie africaine',
                'Poésie contemporaine',
                'Théâtre classique',
                'Théâtre moderne',
                'Textes littéraires',
            ],

            'Religion et spiritualité' => [
                'Spiritualité',
                'Philosophie religieuse',
                'Textes sacrés',
                'Méditation',
                'Développement spirituel',
            ],

            'Enfants et jeunesse' => [
                'Contes pour enfants',
                'Romans jeunesse',
                'Livres éducatifs',
                'Histoires illustrées',
                'Aventures jeunesse',
            ],

            'Cuisine et art de vivre' => [
                'Cuisine africaine',
                'Cuisine internationale',
                'Nutrition',
                'Santé et bien-être',
                'Mode de vie',
            ],

        ];


        foreach ($categories as $categoryName => $subcategories) {


            $category = Category::create([
                'name' => $categoryName,
                'slug' => Str::slug($categoryName),
            ]);


            foreach ($subcategories as $subcategoryName) {


                $category->subcategories()->create([
                    'name' => $subcategoryName,
                    'slug' => Str::slug($subcategoryName),
                ]);


            }

        }
    
    }
}
