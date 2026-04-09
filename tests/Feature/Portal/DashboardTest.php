<?php

use App\Models\Membership;
use App\Models\MembershipPlan;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

function memberWithMembership(int $daysUntilExpiry, string $membershipStatus = 'active'): User
{
    $plan = MembershipPlan::create([
        'name' => 'Monthly',
        'duration_days' => 30,
        'price' => 999.00,
        'benefits' => ['Gym access'],
        'is_active' => true,
    ]);

    $user = User::factory()->create([
        'role' => 'member',
        'status' => 'active',
        'must_change_password' => false,
    ]);

    Membership::create([
        'user_id' => $user->id,
        'plan_id' => $plan->id,
        'starts_at' => now()->subDays(5)->toDateString(),
        'expires_at' => now()->addDays($daysUntilExpiry)->toDateString(),
        'status' => $membershipStatus,
    ]);

    return $user;
}

it('dashboard returns 200 for authenticated member', function (): void {
    $user = memberWithMembership(30);

    $response = $this->actingAs($user)->get('/portal/dashboard');

    $response->assertStatus(200);
});

it('dashboard shows member name', function (): void {
    $user = memberWithMembership(30);

    $response = $this->actingAs($user)->get('/portal/dashboard');

    $response->assertSee($user->name);
});

it('dashboard shows Active badge for active membership', function (): void {
    $user = memberWithMembership(30);

    $response = $this->actingAs($user)->get('/portal/dashboard');

    $response->assertSee('Active');
});

it('dashboard shows Expiring Soon badge when expires within 7 days', function (): void {
    $user = memberWithMembership(5);

    $response = $this->actingAs($user)->get('/portal/dashboard');

    $response->assertSee('Expiring Soon');
});

it('dashboard shows Expired badge when expires in the past', function (): void {
    $plan = MembershipPlan::create([
        'name' => 'Monthly',
        'duration_days' => 30,
        'price' => 999.00,
        'benefits' => ['Gym access'],
        'is_active' => true,
    ]);

    $user = User::factory()->create([
        'role' => 'member',
        'status' => 'active',
        'must_change_password' => false,
    ]);

    Membership::create([
        'user_id' => $user->id,
        'plan_id' => $plan->id,
        'starts_at' => now()->subDays(35)->toDateString(),
        'expires_at' => now()->subDays(5)->toDateString(),
        'status' => 'active',
    ]);

    $response = $this->actingAs($user)->get('/portal/dashboard');

    $response->assertSee('Expired');
});

it('renew button visible when Expiring Soon', function (): void {
    $user = memberWithMembership(5);

    $response = $this->actingAs($user)->get('/portal/dashboard');

    $response->assertSee('Renew Membership');
});

it('renew button visible when Expired', function (): void {
    $user = memberWithMembership(30, 'expired');

    $response = $this->actingAs($user)->get('/portal/dashboard');

    $response->assertSee('Renew Membership');
});

it('renew button NOT visible when Active with more than 7 days', function (): void {
    $user = memberWithMembership(30);

    $response = $this->actingAs($user)->get('/portal/dashboard');

    $response->assertDontSee('Renew Membership');
});

it('dashboard redirects unauthenticated to login', function (): void {
    $response = $this->get('/portal/dashboard');

    $response->assertRedirect('/login');
});
