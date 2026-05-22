<?php

namespace App\Livewire\Admin;

use App\Events\ChatMessageCreated;
use App\Models\ChatMessage;
use App\Models\Conversation;
use Illuminate\Broadcasting\BroadcastException;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class ChatInbox extends Component
{
    public ?int $activeConversationId = null;

    public string $reply = '';

    public function selectConversation(int $id): void
    {
        $this->activeConversationId = $id;
        $this->reply = '';

        ChatMessage::where('conversation_id', $id)
            ->whereNull('read_at')
            ->where('sender_type', 'member')
            ->update(['read_at' => now()]);

        $this->dispatch('admin-conversation-selected', conversationId: $id);
        $this->dispatch('scroll-chat-bottom');
    }

    public function sendReply(): void
    {
        $this->validate(['reply' => 'required|string|max:2000']);

        $message = ChatMessage::create([
            'conversation_id' => $this->activeConversationId,
            'sender_id' => auth()->id(),
            'sender_type' => 'admin',
            'body' => $this->reply,
        ]);

        try {
            event(new ChatMessageCreated($message));
        } catch (BroadcastException) {
            // Reverb server not running — message is saved, real-time push skipped
        }

        $this->reply = '';
        $this->dispatch('scroll-chat-bottom');
    }

    public function closeConversation(int $id): void
    {
        $conversation = Conversation::findOrFail($id);
        $conversation->status = 'closed';
        $conversation->save();

        $systemMessage = ChatMessage::create([
            'conversation_id' => $id,
            'sender_id' => null,
            'sender_type' => 'system',
            'body' => 'Conversation closed.',
        ]);
        try {
            event(new ChatMessageCreated($systemMessage));
        } catch (BroadcastException) {
            // Reverb server not running — message saved, push skipped
        }

        if ($this->activeConversationId === $id) {
            $this->activeConversationId = null;
            $this->dispatch('admin-conversation-selected', conversationId: null);
        }
    }

    public function render(): View
    {
        $conversations = Conversation::with(['member', 'messages' => fn ($q) => $q->latest()->limit(1)])
            ->whereIn('status', ['open', 'escalated'])
            ->latest()
            ->get();

        $activeMessages = $this->activeConversationId
            ? ChatMessage::where('conversation_id', $this->activeConversationId)->oldest()->get()
            : collect();

        $activeConversation = $this->activeConversationId
            ? Conversation::find($this->activeConversationId)
            : null;

        return view('livewire.admin.chat-inbox', compact('conversations', 'activeMessages', 'activeConversation'));
    }
}
