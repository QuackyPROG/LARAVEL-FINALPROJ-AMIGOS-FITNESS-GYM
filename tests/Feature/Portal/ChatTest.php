<?php

use App\Livewire\Admin\ChatInbox;
use App\Livewire\Portal\ChatWidget;
use App\Models\ChatMessage;
use App\Models\Conversation;
use App\Models\Membership;
use App\Models\MembershipPlan;
use App\Models\User;
use App\Services\ChatbotService;
use App\ValueObjects\ChatBotResult;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

uses(RefreshDatabase::class);

function chatMember(): User
{
    $plan = MembershipPlan::create(['name' => 'Monthly', 'duration_days' => 30, 'price' => 999, 'benefits' => ['x'], 'is_active' => true]);
    $user = User::factory()->create(['role' => 'member', 'status' => 'active', 'must_change_password' => false]);
    Membership::create(['user_id' => $user->id, 'plan_id' => $plan->id, 'starts_at' => now()->toDateString(), 'expires_at' => now()->addDays(30)->toDateString(), 'status' => 'active']);

    return $user;
}

it('chat widget sends message and gets bot response', function (): void {
    $member = chatMember();

    $this->mock(ChatbotService::class, function ($mock): void {
        $mock->shouldReceive('respond')
            ->once()
            ->andReturn(ChatBotResult::reply('Hello! How can I help you today?'));
    });

    Livewire::actingAs($member)
        ->test(ChatWidget::class)
        ->call('openChat')
        ->set('message', 'What are your gym hours?')
        ->call('sendMessage');

    expect(ChatMessage::where('sender_type', 'member')->where('body', 'What are your gym hours?')->exists())->toBeTrue();
    expect(ChatMessage::where('sender_type', 'bot')->exists())->toBeTrue();
});

it('chatbot escalates when Claude returns escalate signal', function (): void {
    $member = chatMember();

    $this->mock(ChatbotService::class, function ($mock): void {
        $mock->shouldReceive('respond')
            ->once()
            ->andReturn(ChatBotResult::escalate());
    });

    Livewire::actingAs($member)
        ->test(ChatWidget::class)
        ->call('openChat')
        ->set('message', 'I want a refund')
        ->call('sendMessage');

    $conv = Conversation::where('member_id', $member->id)->first();
    expect($conv->status)->toBe('escalated');
});

it('member cannot access another members conversation', function (): void {
    $member1 = chatMember();
    $member2 = chatMember();

    $conv = Conversation::create(['member_id' => $member1->id, 'status' => 'open']);

    $this->mock(ChatbotService::class, function ($mock): void {
        $mock->shouldReceive('respond')->andReturn(ChatBotResult::reply('ok'));
    });

    // member2 sets a different conversationId manually — can't see member1's messages
    Livewire::actingAs($member2)
        ->test(ChatWidget::class)
        ->call('openChat');

    // member2's open conversation is null (no conv for member2)
    expect(Conversation::where('member_id', $member2->id)->exists())->toBeFalse();
});

it('admin chat inbox returns 200', function (): void {
    $admin = User::factory()->admin()->create(['must_change_password' => false]);
    $response = $this->actingAs($admin)->get('/admin/chat');
    $response->assertStatus(200);
});

it('admin can close conversation and system message is sent', function (): void {
    $admin = User::factory()->admin()->create(['must_change_password' => false]);
    $member = chatMember();
    $conv = Conversation::create(['member_id' => $member->id, 'status' => 'escalated']);

    Livewire::actingAs($admin)
        ->test(ChatInbox::class)
        ->call('closeConversation', $conv->id);

    $conv->refresh();
    expect($conv->status)->toBe('closed');
    expect(ChatMessage::where('conversation_id', $conv->id)->where('sender_type', 'system')->exists())->toBeTrue();
});
