<?php

use App\Jobs\SendWelcomeEmail;
use App\Models\Membership;
use App\Models\MembershipPlan;
use App\Models\User;
use App\Services\PayMongoService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Queue;

uses(RefreshDatabase::class);

function membershipWithPlan(string $status = 'active'): Membership
{
    $plan = MembershipPlan::create([
        'name' => 'Monthly Plan',
        'duration_days' => 30,
        'price' => 999.00,
        'benefits' => ['Gym access'],
        'is_active' => true,
    ]);

    $user = User::factory()->create([
        'role' => 'member',
        'status' => $status === 'active' ? 'active' : 'pending',
    ]);

    return Membership::create([
        'user_id' => $user->id,
        'plan_id' => $plan->id,
        'starts_at' => now()->toDateString(),
        'expires_at' => now()->addDays(30)->toDateString(),
        'status' => $status,
        'payment_ref' => $status === 'active' ? 'pi_test_paid' : null,
        'checkout_session_id' => 'cs_test_result',
    ]);
}

it('returns 200 on the payment success page for an active membership', function (): void {
    $membership = membershipWithPlan('active');

    $response = $this->get('/payment/success?ref='.$membership->id);

    $response->assertStatus(200);
});

it('shows plan name and expiry on the payment success page', function (): void {
    $membership = membershipWithPlan('active');

    $response = $this->get('/payment/success?ref='.$membership->id);

    $response->assertSee('Monthly Plan');
    $response->assertSee($membership->expires_at->format('F j, Y'));
});

it('shows a pending message when webhook has not fired yet', function (): void {
    $membership = membershipWithPlan('pending_payment');

    $response = $this->get('/payment/success?ref='.$membership->id);

    $response->assertStatus(200);
    $response->assertSee('Processing');
});

it('activates a pending membership from a paid checkout session on success redirect', function (): void {
    Queue::fake();
    config(['paymongo.secret_key' => 'sk_test_dummy']);

    $membership = membershipWithPlan('pending_payment');

    $this->mock(PayMongoService::class, function ($mock): void {
        $mock->shouldReceive('getCheckoutSession')
            ->once()
            ->with('cs_test_result')
            ->andReturn([
                'id' => 'cs_test_result',
                'attributes' => [
                    'status' => 'paid',
                ],
            ]);
    });

    $response = $this->get('/payment/success?ref='.$membership->id);

    $response->assertStatus(200);
    $response->assertSee('Payment Confirmed!');

    $membership->refresh();
    expect($membership->status)->toBe('active');
    expect($membership->payment_ref)->toBe('cs_test_result');
    expect($membership->user->fresh()->status)->toBe('active');

    Queue::assertPushed(SendWelcomeEmail::class);
});

it('keeps a membership pending when the checkout session is not paid yet', function (): void {
    config(['paymongo.secret_key' => 'sk_test_dummy']);

    $membership = membershipWithPlan('pending_payment');

    $this->mock(PayMongoService::class, function ($mock): void {
        $mock->shouldReceive('getCheckoutSession')
            ->once()
            ->with('cs_test_result')
            ->andReturn([
                'id' => 'cs_test_result',
                'attributes' => [
                    'status' => 'active',
                    'payment_status' => 'unpaid',
                ],
            ]);
    });

    $response = $this->get('/payment/success?ref='.$membership->id);

    $response->assertStatus(200);
    $response->assertSee('Processing');

    expect($membership->fresh()->status)->toBe('pending_payment');
});

it('returns 200 on the payment failed page', function (): void {
    $membership = membershipWithPlan('failed');

    $response = $this->get('/payment/failed?ref='.$membership->id);

    $response->assertStatus(200);
});

it('shows a try again link on the payment failed page', function (): void {
    $membership = membershipWithPlan('failed');

    $response = $this->get('/payment/failed?ref='.$membership->id);

    $response->assertSee('Try Again');
    $response->assertSee('/register?plan='.$membership->plan_id);
});

it('payment failed page renders without a ref param', function (): void {
    $response = $this->get('/payment/failed');

    $response->assertStatus(200);
    $response->assertSee('Payment Did Not Go Through');
});
