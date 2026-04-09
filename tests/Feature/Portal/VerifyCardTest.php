<?php

use App\Models\Membership;
use App\Models\MembershipPlan;
use App\Models\User;
use App\Services\MemberCardService;
use Firebase\JWT\JWT;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

function memberForVerify(): User
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
        'starts_at' => now()->toDateString(),
        'expires_at' => now()->addDays(30)->toDateString(),
        'status' => 'active',
    ]);

    return $user;
}

it('valid token returns 200 and shows member name', function (): void {
    $user = memberForVerify();
    $cardService = app(MemberCardService::class);
    $token = $cardService->generateToken($user);

    $response = $this->get('/verify/'.$token);

    $response->assertStatus(200);
    $response->assertSee($user->name);
});

it('expired token shows expired message (not 500)', function (): void {
    $user = memberForVerify();

    $payload = [
        'member_id' => $user->id,
        'expires_at' => null,
        'iat' => time() - 90000,
        'exp' => time() - 86400,
    ];
    $token = JWT::encode($payload, config('app.key'), 'HS256');

    $response = $this->get('/verify/'.$token);

    $response->assertStatus(200);
    $response->assertSee('Token Expired');
});

it('invalid token returns 404', function (): void {
    $response = $this->get('/verify/this.is.not.a.valid.token.at.all');

    $response->assertStatus(404);
});
