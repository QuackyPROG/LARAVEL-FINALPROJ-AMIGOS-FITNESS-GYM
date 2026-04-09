<?php

use App\Mail\ExpiryWarningEmail;
use App\Models\Membership;
use App\Models\MembershipPlan;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;

uses(RefreshDatabase::class);

it('queues expiry warning email for members expiring within 7 days', function () {
    Mail::fake();

    $member = User::factory()->create(['role' => 'member', 'status' => 'active']);
    $plan = MembershipPlan::factory()->create();
    $membership = Membership::factory()->create([
        'user_id' => $member->id,
        'plan_id' => $plan->id,
        'status' => 'active',
        'expires_at' => now()->addDays(5),
        'expiry_warned_at' => null,
    ]);

    $this->artisan('memberships:send-expiry-warnings')->assertSuccessful();

    Mail::assertQueued(ExpiryWarningEmail::class, fn ($mail) => $mail->hasTo($member->email));
});

it('does not re-send warning if expiry_warned_at is already set', function () {
    Mail::fake();

    $member = User::factory()->create(['role' => 'member', 'status' => 'active']);
    $plan = MembershipPlan::factory()->create();
    Membership::factory()->create([
        'user_id' => $member->id,
        'plan_id' => $plan->id,
        'status' => 'active',
        'expires_at' => now()->addDays(3),
        'expiry_warned_at' => now()->subDay(),
    ]);

    $this->artisan('memberships:send-expiry-warnings')->assertSuccessful();

    Mail::assertNotQueued(ExpiryWarningEmail::class);
});

it('does not email members expiring beyond 7 days', function () {
    Mail::fake();

    $member = User::factory()->create(['role' => 'member', 'status' => 'active']);
    $plan = MembershipPlan::factory()->create();
    Membership::factory()->create([
        'user_id' => $member->id,
        'plan_id' => $plan->id,
        'status' => 'active',
        'expires_at' => now()->addDays(30),
        'expiry_warned_at' => null,
    ]);

    $this->artisan('memberships:send-expiry-warnings')->assertSuccessful();

    Mail::assertNotQueued(ExpiryWarningEmail::class);
});

it('sets expiry_warned_at after sending warning', function () {
    Mail::fake();

    $member = User::factory()->create(['role' => 'member', 'status' => 'active']);
    $plan = MembershipPlan::factory()->create();
    $membership = Membership::factory()->create([
        'user_id' => $member->id,
        'plan_id' => $plan->id,
        'status' => 'active',
        'expires_at' => now()->addDays(2),
        'expiry_warned_at' => null,
    ]);

    $this->artisan('memberships:send-expiry-warnings');

    expect($membership->fresh()->expiry_warned_at)->not->toBeNull();
});

it('expiry warning email has correct subject', function () {
    $member = User::factory()->create();
    $plan = MembershipPlan::factory()->create();
    $membership = Membership::factory()->create([
        'user_id' => $member->id,
        'plan_id' => $plan->id,
        'expires_at' => now()->addDays(3),
    ]);

    $mailable = new ExpiryWarningEmail($member, $membership);

    expect($mailable->envelope()->subject)->toBe('Your membership is expiring soon');
});

it('gov id route requires admin authentication', function () {
    $member = User::factory()->create(['role' => 'member']);

    $response = $this->get(route('admin.members.gov-id', $member));
    $response->assertRedirect('/login');
});

it('gov id route returns 403 without valid signature for admin', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $member = User::factory()->create(['role' => 'member']);

    $response = $this->actingAs($admin)
        ->get(route('admin.members.gov-id', $member));

    $response->assertStatus(403);
});
