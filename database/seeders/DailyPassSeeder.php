<?php

namespace Database\Seeders;

use App\Models\MembershipPlan;
use Illuminate\Database\Seeder;

class DailyPassSeeder extends Seeder
{
    public function run(): void
    {
        MembershipPlan::firstOrCreate(
            ['name' => 'Daily Pass'],
            [
                'duration_days' => 1,
                'price' => env('DAILY_PASS_PRICE', 150),
                'is_daily' => true,
                'benefits' => ['One-day gym access', 'Locker room access'],
                'is_active' => true,
            ]
        );
    }
}
