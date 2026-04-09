<?php

use App\Models\Membership;
use App\Models\MembershipPlan;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('authenticated member can view my-membership page', function (): void {
    $plan = MembershipPlan::create([
        'name' => 'Monthly',
        'duration_days' => 30,
        'price' => 999.00,
        'benefits' => [],
        'is_active' => true,
    ]);

    $member = User::factory()->create([
        'role' => 'member',
        'status' => 'active',
        'must_change_password' => false,
    ]);

    Membership::create([
        'user_id' => $member->id,
        'plan_id' => $plan->id,
        'starts_at' => now()->subDays(5)->toDateString(),
        'expires_at' => now()->addDays(25)->toDateString(),
        'status' => 'active',
    ]);

    $this->actingAs($member)->get('/portal/my-membership')->assertOk();
});

it('unauthenticated user is redirected from my-membership', function (): void {
    $this->get('/portal/my-membership')->assertRedirect('/login');
});
