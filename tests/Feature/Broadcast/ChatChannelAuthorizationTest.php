<?php

use App\Models\Conversation;
use App\Models\Membership;
use App\Models\MembershipPlan;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

function chatChannelMember(): User
{
    $plan = MembershipPlan::create([
        'name' => 'Monthly',
        'duration_days' => 30,
        'price' => 999,
        'benefits' => ['x'],
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

it('member can authenticate own conversation private channel', function (): void {
    $member = chatChannelMember();
    $conversation = Conversation::create([
        'member_id' => $member->id,
        'status' => 'open',
    ]);

    $response = $this
        ->actingAs($member)
        ->post('/broadcasting/auth', [
            'socket_id' => '1234.5678',
            'channel_name' => 'private-support.conversation.'.$conversation->id,
        ]);

    $response->assertOk();
});

it('member cannot authenticate another members conversation channel', function (): void {
    $owner = chatChannelMember();
    $otherMember = chatChannelMember();

    $conversation = Conversation::create([
        'member_id' => $owner->id,
        'status' => 'open',
    ]);

    $response = $this
        ->actingAs($otherMember)
        ->post('/broadcasting/auth', [
            'socket_id' => '1234.5678',
            'channel_name' => 'private-support.conversation.'.$conversation->id,
        ]);

    expect($response->status())->toBeIn([200, 403]);

    if ($response->status() === 403) {
        $response->assertForbidden();
    } else {
        expect($response->getContent())->not->toContain('"auth"');
    }
});

it('admin can authenticate support admin private channel', function (): void {
    $admin = User::factory()->admin()->create([
        'must_change_password' => false,
    ]);

    $response = $this
        ->actingAs($admin)
        ->post('/broadcasting/auth', [
            'socket_id' => '1234.5678',
            'channel_name' => 'private-support.admin',
        ]);

    $response->assertOk();
});

it('member cannot authenticate support admin private channel', function (): void {
    $member = chatChannelMember();

    $response = $this
        ->actingAs($member)
        ->post('/broadcasting/auth', [
            'socket_id' => '1234.5678',
            'channel_name' => 'private-support.admin',
        ]);

    expect($response->status())->toBeIn([200, 403]);

    if ($response->status() === 403) {
        $response->assertForbidden();
    } else {
        expect($response->getContent())->not->toContain('"auth"');
    }
});
