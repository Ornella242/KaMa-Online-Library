<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\FaqCategory;
use Illuminate\Database\Seeder;

class FaqCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        FaqCategory::insert([

            [
                'name'=>'Lecteurs',
                'slug'=>'lecteurs',
                'icon'=>'bi-book',
                'order'=>1
            ],


            [
                'name'=>'Écrivains',
                'slug'=>'ecrivains',
                'icon'=>'bi-pen',
                'order'=>2
            ]

        ]);
    }
}
