<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Setting;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
      
        Setting::setValue('free_submission_limit', 1000);
        Setting::setValue('withdrawal_commission_percent', 5);
        Setting::setValue('withdrawal_minimum_amount', 10);
    
    }
}
