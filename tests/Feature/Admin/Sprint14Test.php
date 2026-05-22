<?php

declare(strict_types=1);

use App\Livewire\Admin\MemberIndex;
use App\Models\Membership;
use App\Models\MembershipPlan;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

uses(RefreshDatabase::class);

it('walk-in modal plan cards show daily pass first', function (): void {
    $admin = User::factory()->admin()->create(['must_change_password' => false]);

    MembershipPlan::create(['name' => 'Monthly', 'duration_days' => 30, 'price' => 999, 'benefits' => [], 'is_active' => true, 'is_daily' => false]);
    MembershipPlan::create(['name' => 'Daily Pass', 'duration_days' => 1, 'price' => 150, 'benefits' => [], 'is_active' => true, 'is_daily' => true]);

    $component = Livewire::actingAs($admin)->test(MemberIndex::class);
    $plans = $component->viewData('plans');

    expect($plans->first()->is_daily)->toBeTrue();
    expect($plans->first()->name)->toBe('Daily Pass');
});

it('saveMember creates member with daily pass and expires_at tomorrow', function (): void {
    $admin = User::factory()->admin()->create(['must_change_password' => false]);
    $plan = MembershipPlan::create(['name' => 'Daily Pass', 'duration_days' => 1, 'price' => 150, 'benefits' => [], 'is_active' => true, 'is_daily' => true]);

    Livewire::actingAs($admin)
        ->test(MemberIndex::class)
        ->set('addName', 'Drop In Member')
        ->set('addEmail', 'dropin@example.com')
        ->set('addPlanId', $plan->id)
        ->call('saveMember')
        ->assertHasNoErrors();

    $membership = Membership::whereHas('user', fn ($q) => $q->where('email', 'dropin@example.com'))->first();

    expect($membership)->not->toBeNull();
    expect($membership->expires_at->toDateString())->toBe(Carbon::today()->addDay()->toDateString());
});

it('saveMember still works with a non-daily plan', function (): void {
    $admin = User::factory()->admin()->create(['must_change_password' => false]);
    $plan = MembershipPlan::create(['name' => 'Monthly', 'duration_days' => 30, 'price' => 999, 'benefits' => [], 'is_active' => true, 'is_daily' => false]);

    Livewire::actingAs($admin)
        ->test(MemberIndex::class)
        ->set('addName', 'Monthly Member')
        ->set('addEmail', 'monthly@example.com')
        ->set('addPlanId', $plan->id)
        ->call('saveMember')
        ->assertHasNoErrors();

    $membership = Membership::whereHas('user', fn ($q) => $q->where('email', 'monthly@example.com'))->first();

    expect($membership)->not->toBeNull();
    expect($membership->expires_at->toDateString())->toBe(Carbon::today()->addDays(30)->toDateString());
});
