<?php

declare(strict_types=1);

use App\Models\MembershipPlan;
use Database\Seeders\DailyPassSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;

uses(RefreshDatabase::class);

it('DailyPassSeeder inserts the daily pass plan', function (): void {
    $this->seed(DailyPassSeeder::class);

    $this->assertDatabaseHas('membership_plans', [
        'name' => 'Daily Pass',
        'duration_days' => 1,
        'is_daily' => true,
        'is_active' => true,
    ]);
});

it('DailyPassSeeder is idempotent and does not create duplicates', function (): void {
    $this->seed(DailyPassSeeder::class);
    $this->seed(DailyPassSeeder::class);

    expect(MembershipPlan::where('name', 'Daily Pass')->count())->toBe(1);
});

it('daily pass plan has duration_days of 1', function (): void {
    $this->seed(DailyPassSeeder::class);

    $plan = MembershipPlan::where('name', 'Daily Pass')->first();

    expect($plan->duration_days)->toBe(1);
    expect($plan->is_daily)->toBeTrue();
});

it('membership_plans table has is_daily column', function (): void {
    expect(Schema::hasColumn('membership_plans', 'is_daily'))->toBeTrue();
});
