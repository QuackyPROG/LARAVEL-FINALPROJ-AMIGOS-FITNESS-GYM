<div>
    <div class="mb-8 flex justify-between items-start">
        <div>
            <h1 class="text-3xl font-bold text-white mb-2">Live Chat</h1>
            <p class="text-gray-300">Respond to member support conversations in real time</p>
        </div>

<div wire:ignore class="text-right" x-data="{
    date: '',
    time: '',
    init() {
        this.updateClock();
        setInterval(() => this.updateClock(), 1000);
    },
    updateClock() {
        const now = new Date();
        this.date = now.toLocaleDateString('en-US', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' });
        this.time = now.toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit', second: '2-digit' });
    }
}">
    <div class="text-amber-400 text-sm font-medium tracking-wide uppercase" x-text="date"></div>
    <div class="text-white text-4xl font-extrabold tracking-tight mt-0.5" x-text="time"></div>
</div>
    </div>

    <div class="flex gap-4" style="height: calc(100vh - 240px); min-height: 400px;">
        {{-- Conversation List --}}
        <div class="w-64 border border-white/10 rounded-xl shadow-xl overflow-y-auto bg-white/5 backdrop-blur-md flex-shrink-0 flex flex-col custom-scrollbar">
            <p class="px-3 py-3 text-xs font-medium text-gray-400 uppercase tracking-wide border-b border-white/10 bg-white/5">Open Conversations</p>
            @forelse($conversations as $conv)
            <div
                wire:click="selectConversation({{ $conv->id }})"
                class="py-3 pr-3 pl-2 border-b border-white/10 cursor-pointer transition-all duration-200 border-l-4 @if($activeConversation?->id === $conv->id) border-amber-400 bg-white/10 @else border-transparent hover:bg-white/5 @endif"
            >
                <div class="flex items-center justify-between">
                    <p class="font-medium text-sm text-white">{{ $conv->member?->name ?? 'Unknown' }}</p>
                    @if($conv->status === 'escalated')
                        <span class="inline-flex px-1.5 py-0.5 rounded text-xs font-medium bg-yellow-900/20 text-yellow-300 border border-yellow-700">Escalated</span>
                    @else
                        <span class="inline-flex px-1.5 py-0.5 rounded text-xs font-medium bg-green-900/20 text-green-300 border border-green-700">Open</span>
                    @endif
                </div>
                @if($conv->messages->first())
                    <p class="text-xs text-gray-400 mt-0.5 truncate">{{ $conv->messages->first()->body }}</p>
                @endif
            </div>
            @empty
            <div class="p-4 text-center">
                <p class="text-sm text-gray-400">No open conversations</p>
                <p class="text-xs text-gray-400 mt-0.5">New member chats will appear here</p>
            </div>
            @endforelse
        </div>

        {{-- Message Thread --}}
        @if($activeConversation)
        <div class="flex-1 border border-white/10 rounded-xl shadow-xl overflow-hidden bg-white/5 backdrop-blur-md flex flex-col">
            <div class="px-4 py-3 border-b border-white/10 bg-white/5 flex items-center justify-between">
                <div>
                    <p class="font-medium text-white">{{ $activeConversation->member?->name }}</p>
                    <p class="text-xs text-gray-400">{{ ucfirst($activeConversation->status) }}</p>
                </div>
                <button wire:click="closeConversation({{ $activeConversation->id }})" wire:confirm="Close this conversation?" class="border border-red-700 text-red-400 text-sm px-3 py-1 rounded hover:bg-red-900/20 transition-colors">Close</button>
            </div>

            <div class="flex-1 overflow-y-auto px-4 py-3 space-y-3">
                @foreach($activeMessages as $msg)
                <div class="flex">
                    <div class="bg-white/10 border border-white/10 rounded-xl px-3 py-2 text-sm text-gray-300 max-w-xs backdrop-blur-sm">
                        @if($msg->sender_type === 'admin')<p class="text-xs font-medium text-gray-400 mb-0.5">Staff</p>@endif
                        @if($msg->sender_type === 'bot')<p class="text-xs font-medium text-gray-400 mb-0.5">Bot</p>@endif
                        {{ $msg->body }}
                    </div>
                </div>
                @endforeach
            </div>

            @if($activeConversation->status !== 'closed')
            <div class="px-3 py-3 border-t border-white/10 bg-white/5">
                <div class="flex gap-2">
                    <input type="text" wire:model="reply" wire:keydown.enter="sendReply"
                        placeholder="Type a reply…"
                        class="flex-1 border border-white/10 rounded-xl px-4 py-2 text-sm bg-white/5 backdrop-blur-md text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-amber-500/50">
                    <button wire:click="sendReply" class="bg-black hover:bg-gray-800 transition-colors text-white text-sm px-3 py-1.5 rounded-md">Send</button>
                </div>
            </div>
            @endif
        </div>
        @else
        <div class="flex-1 border border-white/10 rounded-xl shadow-inner bg-white/5 backdrop-blur-md flex items-center justify-center">
            <div class="text-center">
                <p class="text-sm text-gray-400">Select a conversation to view messages</p>
                <p class="text-xs text-gray-400 mt-1">Choose from the list on the left</p>
            </div>
        </div>
        @endif
    </div>
</div>

@script
<script>
    (() => {
        if (!window.Echo) {
            return;
        }

        const componentId = @js($this->getId());
        const activeConversationId = @js($activeConversationId);
        const stateByComponent = window.__chatRealtimeAdmin ?? new Map();
        window.__chatRealtimeAdmin = stateByComponent;

        const fullCleanup = (id) => {
            const state = stateByComponent.get(id);

            if (!state) {
                return;
            }

            if (state.activeConversationChannelName) {
                window.Echo.leave(state.activeConversationChannelName);
            }

            window.Echo.leave(state.adminChannelName);
            stateByComponent.delete(id);
        };

        let state = stateByComponent.get(componentId);

        if (!state) {
            const adminChannelName = 'support.admin';

            window.Echo.private(adminChannelName)
                .listen('.chat.message.created', () => {
                    $wire.$refresh();
                });

            state = {
                adminChannelName,
                activeConversationChannelName: null,
            };

            stateByComponent.set(componentId, state);
        }

        const nextActiveChannelName = activeConversationId
            ? `support.conversation.${activeConversationId}`
            : null;

        if (state.activeConversationChannelName && state.activeConversationChannelName !== nextActiveChannelName) {
            window.Echo.leave(state.activeConversationChannelName);
            state.activeConversationChannelName = null;
        }

        if (nextActiveChannelName && state.activeConversationChannelName !== nextActiveChannelName) {
            window.Echo.private(nextActiveChannelName)
                .listen('.chat.message.created', (event) => {
                    if (Number(event.conversation_id) !== Number(activeConversationId)) {
                        return;
                    }

                    $wire.$refresh();
                });

            state.activeConversationChannelName = nextActiveChannelName;
        }

        if (!window.__chatRealtimeAdminCleanupBound) {
            window.__chatRealtimeAdminCleanupBound = true;

            document.addEventListener('livewire:navigating', () => {
                for (const id of stateByComponent.keys()) {
                    fullCleanup(id);
                }
            });

            window.addEventListener('beforeunload', () => {
                for (const id of stateByComponent.keys()) {
                    fullCleanup(id);
                }
            });
        }
    })();
</script>
@endscript
