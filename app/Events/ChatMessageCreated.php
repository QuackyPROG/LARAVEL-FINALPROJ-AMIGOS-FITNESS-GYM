<?php

namespace App\Events;

use App\Models\ChatMessage;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ChatMessageCreated implements ShouldBroadcastNow
{
    use Dispatchable;
    use SerializesModels;

    public ChatMessage $chatMessage;

    public function __construct(ChatMessage $chatMessage)
    {
        $this->chatMessage = $chatMessage->loadMissing('conversation.member');
    }

    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('support.conversation.'.$this->chatMessage->conversation_id),
            new PrivateChannel('support.admin'),
        ];
    }

    public function broadcastAs(): string
    {
        return 'chat.message.created';
    }

    public function broadcastWith(): array
    {
        $member = $this->chatMessage->conversation?->member;

        return [
            'id' => $this->chatMessage->id,
            'conversation_id' => $this->chatMessage->conversation_id,
            'sender_type' => $this->chatMessage->sender_type,
            'body' => $this->chatMessage->body,
            'created_at' => optional($this->chatMessage->created_at)?->toIso8601String(),
            'member' => [
                'id' => $member?->id,
                'name' => $member?->name,
            ],
        ];
    }
}
