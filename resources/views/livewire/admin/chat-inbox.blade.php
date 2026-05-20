<div>
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-white mb-1">Live Chat</h1>
        <p class="text-sm text-gray-500">Respond to member support conversations in real time</p>
    </div>

    <div class="flex gap-4" style="height: calc(100vh - 200px); min-height: 400px;">
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

            <div class="flex-1 overflow-y-auto px-4 py-4 space-y-3" id="adminChatMessages">
                @foreach($activeMessages as $msg)
                @php
                    $isAdmin  = $msg->sender_type === 'admin';
                    $isBot    = $msg->sender_type === 'bot';
                    $isMember = !$isAdmin && !$isBot;
                @endphp

                @if($isBot)
                    {{-- Bot: centered pill --}}
                    <div class="flex justify-center">
                        <div class="inline-flex items-center gap-1.5 bg-zinc-900 border border-zinc-800 rounded-full px-3 py-1.5 max-w-xs">
                            <svg class="w-3 h-3 text-zinc-500 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                            <span class="text-xs text-zinc-500 italic">{{ $msg->body }}</span>
                        </div>
                    </div>

                @elseif($isAdmin)
                    {{-- Staff: right side, gold-tinted --}}
                    <div class="flex flex-col items-end gap-1">
                        <span class="text-[10px] font-bold uppercase tracking-widest text-amber-500/70 pr-1">You (Staff)</span>
                        <div class="bg-amber-400/10 border border-amber-400/20 text-amber-100 rounded-2xl rounded-tr-sm px-3.5 py-2.5 text-sm max-w-[75%] break-words">
                            {{ $msg->body }}
                        </div>
                    </div>

                @else
                    {{-- Member: left side, zinc --}}
                    <div class="flex flex-col items-start gap-1">
                        <span class="text-[10px] font-bold uppercase tracking-widest text-zinc-500 pl-1">{{ $activeConversation->member?->name ?? 'Member' }}</span>
                        <div class="bg-zinc-800 border border-zinc-700 text-zinc-100 rounded-2xl rounded-tl-sm px-3.5 py-2.5 text-sm max-w-[75%] break-words">
                            {{ $msg->body }}
                        </div>
                    </div>
                @endif

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

        let activeChannelName = null;

        // Always subscribe to the global admin channel for sidebar updates
        window.Echo.private('support.admin')
            .listen('.chat.message.created', () => {
                $wire.$refresh();
            });

        // Subscribe to a specific conversation channel when admin selects one
        const subscribeToConversation = (conversationId) => {
            const nextChannelName = conversationId
                ? `support.conversation.${conversationId}`
                : null;

            // Clean up old conversation subscription
            if (activeChannelName && activeChannelName !== nextChannelName) {
                window.Echo.leave(activeChannelName);
                activeChannelName = null;
            }

            // Subscribe to the new conversation channel
            if (nextChannelName && activeChannelName !== nextChannelName) {
                window.Echo.private(nextChannelName)
                    .listen('.chat.message.created', () => {
                        $wire.$refresh();
                    });

                activeChannelName = nextChannelName;
            }
        };

        // React to admin clicking on a conversation
        $wire.on('admin-conversation-selected', ({ conversationId }) => {
            subscribeToConversation(conversationId);
        });

        // If a conversation is already active at render time, subscribe immediately
        const initialId = @js($activeConversationId);
        if (initialId) {
            subscribeToConversation(initialId);
        }

        // Cleanup on navigation / page unload
        const cleanup = () => {
            if (activeChannelName) {
                window.Echo.leave(activeChannelName);
                activeChannelName = null;
            }
            window.Echo.leave('support.admin');
        };

        document.addEventListener('livewire:navigating', cleanup);
        window.addEventListener('beforeunload', cleanup);
    })();
</script>
@endscript
