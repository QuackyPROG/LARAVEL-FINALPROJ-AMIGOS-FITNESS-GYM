<?php

declare(strict_types=1);

use App\Livewire\Admin\ChatInbox;
use App\Livewire\Portal\ChatWidget;
use App\Models\Conversation;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

uses(RefreshDatabase::class);

it('admin selectConversation dispatches scroll-chat-bottom', function (): void {
    $admin = User::factory()->admin()->create(['must_change_password' => false]);
    $member = User::factory()->create(['must_change_password' => false]);
    $conversation = Conversation::create(['member_id' => $member->id, 'status' => 'open']);

    Livewire::actingAs($admin)
        ->test(ChatInbox::class)
        ->call('selectConversation', $conversation->id)
        ->assertDispatched('scroll-chat-bottom');
});

it('admin sendReply dispatches scroll-chat-bottom', function (): void {
    $admin = User::factory()->admin()->create(['must_change_password' => false]);
    $member = User::factory()->create(['must_change_password' => false]);
    $conversation = Conversation::create(['member_id' => $member->id, 'status' => 'open']);

    Livewire::actingAs($admin)
        ->test(ChatInbox::class)
        ->set('activeConversationId', $conversation->id)
        ->set('reply', 'Hello there')
        ->call('sendReply')
        ->assertDispatched('scroll-chat-bottom');
});

it('member openChat dispatches scroll-chat-bottom', function (): void {
    $member = User::factory()->create(['must_change_password' => false]);

    Livewire::actingAs($member)
        ->test(ChatWidget::class)
        ->call('openChat')
        ->assertDispatched('scroll-chat-bottom');
});

it('member sendMessage dispatches scroll-chat-bottom', function (): void {
    $member = User::factory()->create(['must_change_password' => false]);

    Livewire::actingAs($member)
        ->test(ChatWidget::class)
        ->set('isOpen', true)
        ->set('message', 'Hello')
        ->call('sendMessage')
        ->assertDispatched('scroll-chat-bottom');
});
