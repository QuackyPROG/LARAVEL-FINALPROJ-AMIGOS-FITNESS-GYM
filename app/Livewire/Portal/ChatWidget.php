<?php

namespace App\Livewire\Portal;

use App\Events\ChatMessageCreated;
use App\Models\ChatMessage;
use App\Models\Conversation;
use App\Services\ChatbotService;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Rule;
use Livewire\Component;

class ChatWidget extends Component
{
    public bool $isOpen = false;

    #[Rule('required|string|max:1000')]
    public string $message = '';

    public ?int $conversationId = null;

    public function openChat(): void
    {
        $this->isOpen = true;

        if (! $this->conversationId) {
            $existing = Conversation::where('member_id', auth()->id())
                ->whereIn('status', ['open', 'escalated'])
                ->latest()
                ->first();

            if ($existing) {
                $this->conversationId = $existing->id;
                $this->dispatch('chat-channel-subscribe', conversationId: $existing->id);
            }
        }
    }

    public function closeChat(): void
    {
        $this->isOpen = false;
    }

    public function sendMessage(): void
    {
        $this->validate();

        if (! $this->conversationId) {
            $conversation = Conversation::create([
                'member_id' => auth()->id(),
                'status' => 'open',
            ]);
            $this->conversationId = $conversation->id;
            $this->dispatch('chat-channel-subscribe', conversationId: $conversation->id);
        } else {
            $conversation = Conversation::where('id', $this->conversationId)
                ->where('member_id', auth()->id())
                ->firstOrFail();
        }

        $memberMessage = ChatMessage::create([
            'conversation_id' => $conversation->id,
            'sender_id' => auth()->id(),
            'sender_type' => 'member',
            'body' => $this->message,
        ]);
        event(new ChatMessageCreated($memberMessage));

        $userMessage = $this->message;
        $this->message = '';

        if (! $conversation->isEscalated()) {
            try {
                $result = app(ChatbotService::class)->respond($userMessage, auth()->user()->name);

                $botMessage = ChatMessage::create([
                    'conversation_id' => $conversation->id,
                    'sender_id' => null,
                    'sender_type' => 'bot',
                    'body' => $result->reply,
                ]);
                event(new ChatMessageCreated($botMessage));

                if ($result->shouldEscalate) {
                    $conversation->status = 'escalated';
                    $conversation->save();
                }
            } catch (\Throwable) {
                $botFallbackMessage = ChatMessage::create([
                    'conversation_id' => $conversation->id,
                    'sender_id' => null,
                    'sender_type' => 'bot',
                    'body' => 'Sorry, I\'m having trouble responding right now. A staff member will follow up with you shortly.',
                ]);
                event(new ChatMessageCreated($botFallbackMessage));

                $conversation->status = 'escalated';
                $conversation->save();
            }
        }
    }

    public function render(): View
    {
        $messages = $this->conversationId
            ? ChatMessage::where('conversation_id', $this->conversationId)->oldest()->get()
            : collect();

        $conversation = $this->conversationId
            ? Conversation::find($this->conversationId)
            : null;

        return view('livewire.portal.chat-widget', compact('messages', 'conversation'));
    }
}
