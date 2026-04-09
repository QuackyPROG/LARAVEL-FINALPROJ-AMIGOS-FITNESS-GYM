<?php

use App\Jobs\SendWelcomeEmail;
use App\Models\Membership;
use App\Models\MembershipPlan;
use App\Models\User;
use App\Models\WebhookLog;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Queue;

uses(RefreshDatabase::class);

/**
 * Build a PayMongo-signed webhook payload and headers.
 *
 * @param  array<string, mixed>  $payload
 * @return array{body: string, headers: array<string, string>}
 */
function makeWebhookRequest(array $payload, string $secret = 'test_webhook_secret'): array
{
    $body = json_encode($payload);
    $timestamp = (string) time();
    $hash = hash_hmac('sha256', $timestamp.'.'.$body, $secret);
    $signature = "t={$timestamp},te={$hash},li={$hash}";

    return [
        'body' => $body,
        'headers' => [
            'Paymongo-Signature' => $signature,
            'Content-Type' => 'application/json',
        ],
    ];
}

function makePaidEvent(string $checkoutSessionId, string $paymongoId): array
{
    return [
        'data' => [
            'id' => $checkoutSessionId,
            'attributes' => [
                'type' => 'checkout_session.payment.paid',
                'data' => [
                    'id' => $paymongoId,
                    'attributes' => [
                        'amount' => 99900,
                        'currency' => 'PHP',
                        'status' => 'paid',
                    ],
                ],
            ],
        ],
    ];
}

function makeFailedEvent(string $checkoutSessionId): array
{
    return [
        'data' => [
            'id' => $checkoutSessionId,
            'attributes' => [
                'type' => 'checkout_session.payment.failed',
                'data' => [
                    'id' => 'pi_test_failed',
                    'attributes' => [
                        'amount' => 99900,
                        'currency' => 'PHP',
                        'status' => 'failed',
                        'last_payment_error' => [
                            'code' => 'card_declined',
                            'message' => 'Card was declined.',
                        ],
                    ],
                ],
            ],
        ],
    ];
}

function createPendingMembership(): Membership
{
    $plan = MembershipPlan::create([
        'name' => 'Monthly',
        'duration_days' => 30,
        'price' => 999.00,
        'benefits' => ['Gym access'],
        'is_active' => true,
    ]);

    $user = User::create([
        'name' => 'Test User',
        'email' => 'testuser@example.com',
        'password' => bcrypt('placeholder'),
        'role' => 'member',
        'status' => 'pending',
        'must_change_password' => true,
    ]);

    return Membership::create([
        'user_id' => $user->id,
        'plan_id' => $plan->id,
        'starts_at' => now()->toDateString(),
        'expires_at' => now()->addDays(30)->toDateString(),
        'status' => 'pending_payment',
        'checkout_session_id' => 'cs_test_123',
    ]);
}

it('activates a membership on payment.paid webhook', function (): void {
    Queue::fake();
    config(['paymongo.webhook_secret' => 'test_webhook_secret']);

    $membership = createPendingMembership();
    $payload = makePaidEvent('cs_test_123', 'pi_test_paid_001');
    $req = makeWebhookRequest($payload);

    $response = $this->postJson('/api/webhook/paymongo', $payload, $req['headers']);

    $response->assertStatus(200)->assertJson(['status' => 'ok']);
    $membership->refresh();
    expect($membership->status)->toBe('active');
    expect($membership->payment_ref)->toBe('cs_test_123');
});

it('sets must_change_password and activates user on payment.paid', function (): void {
    Queue::fake();
    config(['paymongo.webhook_secret' => 'test_webhook_secret']);

    $membership = createPendingMembership();
    $payload = makePaidEvent('cs_test_123', 'pi_test_paid_002');
    $req = makeWebhookRequest($payload);

    $this->postJson('/api/webhook/paymongo', $payload, $req['headers']);

    $membership->refresh();
    $user = $membership->user;
    expect($user->must_change_password)->toBeTrue();
    expect($user->status)->toBe('active');
    expect($user->email_verified_at)->not->toBeNull();
});

it('dispatches SendWelcomeEmail job on payment.paid', function (): void {
    Queue::fake();
    config(['paymongo.webhook_secret' => 'test_webhook_secret']);

    $membership = createPendingMembership();
    $payload = makePaidEvent('cs_test_123', 'pi_test_paid_003');
    $req = makeWebhookRequest($payload);

    $this->postJson('/api/webhook/paymongo', $payload, $req['headers']);

    Queue::assertPushed(SendWelcomeEmail::class, function ($job) use ($membership): bool {
        return $job->user->id === $membership->user_id;
    });
});

it('logs every webhook event to webhook_logs table', function (): void {
    Queue::fake();
    config(['paymongo.webhook_secret' => 'test_webhook_secret']);

    $membership = createPendingMembership();
    $payload = makePaidEvent('cs_test_123', 'pi_test_paid_004');
    $req = makeWebhookRequest($payload);

    $this->postJson('/api/webhook/paymongo', $payload, $req['headers']);

    expect(WebhookLog::where('event_type', 'checkout_session.payment.paid')->count())->toBe(1);
});

it('is idempotent — replaying the same event returns already_processed', function (): void {
    Queue::fake();
    config(['paymongo.webhook_secret' => 'test_webhook_secret']);

    $membership = createPendingMembership();
    $payload = makePaidEvent('cs_test_123', 'pi_test_paid_005');
    $req = makeWebhookRequest($payload);

    $this->postJson('/api/webhook/paymongo', $payload, $req['headers']);

    // Replay
    $req2 = makeWebhookRequest($payload);
    $second = $this->postJson('/api/webhook/paymongo', $payload, $req2['headers']);

    $second->assertStatus(200)->assertJson(['status' => 'already_processed']);
    expect(Membership::where('status', 'active')->count())->toBe(1);
});

it('sets membership to failed on payment.failed event', function (): void {
    Queue::fake();
    config(['paymongo.webhook_secret' => 'test_webhook_secret']);

    $membership = createPendingMembership();
    $payload = makeFailedEvent('cs_test_123');
    $req = makeWebhookRequest($payload);

    $response = $this->postJson('/api/webhook/paymongo', $payload, $req['headers']);

    $response->assertStatus(200)->assertJson(['status' => 'ok']);
    $membership->refresh();
    expect($membership->status)->toBe('failed');
});

it('does not activate user on payment.failed event', function (): void {
    Queue::fake();
    config(['paymongo.webhook_secret' => 'test_webhook_secret']);

    $membership = createPendingMembership();
    $user = $membership->user;
    $payload = makeFailedEvent('cs_test_123');
    $req = makeWebhookRequest($payload);

    $this->postJson('/api/webhook/paymongo', $payload, $req['headers']);

    $user->refresh();
    expect($user->status)->toBe('pending');
    expect($user->email_verified_at)->toBeNull();
});

it('returns 400 for an invalid webhook signature', function (): void {
    config(['paymongo.webhook_secret' => 'test_webhook_secret']);

    $payload = makePaidEvent('cs_test_bad', 'pi_bad');

    $response = $this->postJson('/api/webhook/paymongo', $payload, [
        'Paymongo-Signature' => 't=9999,te=invalidsig,li=invalidsig',
        'Content-Type' => 'application/json',
    ]);

    $response->assertStatus(400);
});
