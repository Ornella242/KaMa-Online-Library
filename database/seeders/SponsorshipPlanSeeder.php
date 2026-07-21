<?php

namespace Database\Seeders;

use App\Models\SponsorshipPlan;
use Illuminate\Database\Seeder;

class SponsorshipPlanSeeder extends Seeder
{
    public function run(): void
    {
        $plans = [
            [
                'name' => '1 semaine',
                'duration_days' => 7,
                'price' => 5000,
                'active' => true,
            ],
            [
                'name' => '2 semaines',
                'duration_days' => 14,
                'price' => 9000,
                'active' => true,
            ],
            [
                'name' => '1 mois',
                'duration_days' => 30,
                'price' => 15000,
                'active' => true,
            ],
            [
                'name' => '2 mois',
                'duration_days' => 60,
                'price' => 25000,
                'active' => true,
            ],
            [
                'name' => '3 mois',
                'duration_days' => 90,
                'price' => 35000,
                'active' => true,
            ],
        ];

        foreach ($plans as $plan) {
            SponsorshipPlan::query()->updateOrCreate(
                ['name' => $plan['name']],
                $plan
            );
        }
    }
}
