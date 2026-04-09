<?php

use App\Models\Membership;
use App\Models\MembershipPlan;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

function memberWithActiveMembership(): User
{
    $plan = MembershipPlan::create([
        'name' => 'Quarterly',
        'duration_days' => 90,
        'price' => 2500.00,
        'benefits' => ['Full access'],
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
        'starts_at' => now()->toDateString(),
        'expires_at' => now()->addDays(90)->toDateString(),
        'status' => 'active',
    ]);

    return $user;
}

it('card page returns 200 for authenticated member', function (): void {
    $user = memberWithActiveMembership();

    $response = $this->actingAs($user)->get('/portal/card');

    $response->assertStatus(200);
});

it('card shows member name and plan name', function (): void {
    $user = memberWithActiveMembership();

    $response = $this->actingAs($user)->get('/portal/card');

    $response->assertSee($user->name);
    $response->assertSee('Quarterly');
});

it('card page redirects unauthenticated to login', function (): void {
    $response = $this->get('/portal/card');

    $response->assertRedirect('/login');
});

it('pdf download returns 200 with application/pdf content type', function (): void {
    $user = memberWithActiveMembership();

    $response = $this->actingAs($user)->get('/portal/card/pdf');

    $response->assertStatus(200);
    expect($response->headers->get('Content-Type'))->toContain('application/pdf');
});

it('pdf download redirects unauthenticated to login', function (): void {
    $response = $this->get('/portal/card/pdf');

    $response->assertRedirect('/login');
});
