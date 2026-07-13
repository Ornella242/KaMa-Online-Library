<?php

namespace Database\Seeders;
use App\Models\SponsorshipPlan;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SponsorshipPlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $plans = [

        [
            'name' => '1 semaine',
            'duration_days' => 7,
            'price' => 10,
        ],

        [
            'name' => '2 semaines',
            'duration_days' => 14,
            'price' => 20,
        ],

        [
            'name' => '1 mois',
            'duration_days' => 30,
            'price' => 40,
        ],

        [
            'name' => '2 mois',
            'duration_days' => 60,
            'price' => 80,
        ],

        [
            'name' => '3 mois',
            'duration_days' => 90,
            'price' => 120,
        ],

    ];


    foreach($plans as $plan){

        SponsorshipPlan::create($plan);

    }
    }
}
