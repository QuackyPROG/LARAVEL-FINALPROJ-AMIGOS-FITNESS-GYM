<div>
    @if(!$isOpen)
        <button wire:click="openChat" class="fixed bottom-6 right-6 bg-gray-900 text-white rounded-full w-12 h-12 flex items-center justify-center shadow-md z-50">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/>
            </svg>
        </button>
    @else
        <div class="fixed bottom-6 right-6 w-80 border border-gray-200 rounded-lg bg-white shadow-md flex flex-col overflow-hidden z-50" style="height: 480px;">
            {{-- Header --}}
            <div class="px-4 py-3 border-b border-gray-100 bg-gray-50 flex items-center justify-between">
                <div>
                    <p class="text-sm font-semibold text-gray-900">Chat Support</p>
                    @if($conversation?->isEscalated())
                        <p class="text-xs text-gray-400">Staff connected</p>
                    @else
                        <p class="text-xs text-gray-400">AI Assistant</p>
                    @endif
                </div>
                <button wire:click="closeChat" class="text-gray-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            {{-- Messages --}}
            <div class="flex-1 overflow-y-auto px-3 py-3 space-y-2">
                @forelse($messages as $msg)
                    <div class="flex">
                        <div class="bg-gray-100 rounded-md px-3 py-2 text-sm text-gray-700 max-w-[80%]">
                            {{ $msg->body }}
                        </div>
                    </div>
                @empty
                    <p class="py-6 text-center text-sm text-gray-400">Say hello! We're here to help.</p>
                @endforelse
            </div>

            {{-- Input --}}
            @if($conversation?->status !== 'closed')
                <div class="px-3 py-2 border-t border-gray-100">
                    <div class="flex gap-2">
                        <input type="text" wire:model="message" wire:keydown.enter="sendMessage"
                            placeholder="Type a message…"
                            class="flex-1 border border-gray-300 rounded-md px-3 py-1.5 text-sm">
                        <button wire:click="sendMessage" class="bg-gray-900 text-white rounded-md px-3 py-1.5">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                            </svg>
                        </button>
                    </div>
                    @error('message')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                </div>
            @else
                <div class="px-3 py-2 border-t border-gray-100 text-center text-sm text-gray-400">
                    Conversation closed
                </div>
            @endif
        </div>
    @endif
</div>

@script
<script>
    (() => {
        if (!window.Echo) {
            return;
        }

        const componentId = @js($this->getId());
        const conversationId = @js($conversationId);
        const bucket = window.__chatRealtimeMember ?? new Map();
        window.__chatRealtimeMember = bucket;

        const cleanup = () => {
            const state = bucket.get(componentId);

            if (!state) {
                return;
            }

            window.Echo.leave(state.channelName);
            bucket.delete(componentId);
        };

        const current = bucket.get(componentId);
        const targetChannelName = conversationId ? `support.conversation.${conversationId}` : null;

        if (!targetChannelName) {
            if (current) {
                cleanup();
            }

            return;
        }

        if (current && current.channelName !== targetChannelName) {
            cleanup();
        }

        if (bucket.has(componentId)) {
            return;
        }

        window.Echo.private(targetChannelName)
            .listen('.chat.message.created', (event) => {
                if (Number(event.conversation_id) !== Number(conversationId)) {
                    return;
                }

                $wire.$refresh();
            });

        bucket.set(componentId, {
            channelName: targetChannelName,
        });

        if (!window.__chatRealtimeMemberCleanupBound) {
            window.__chatRealtimeMemberCleanupBound = true;

            document.addEventListener('livewire:navigating', () => {
                for (const entry of bucket.values()) {
                    window.Echo.leave(entry.channelName);
                }

                bucket.clear();
            });

            window.addEventListener('beforeunload', () => {
                for (const entry of bucket.values()) {
                    window.Echo.leave(entry.channelName);
                }

                bucket.clear();
            });
        }
    })();
</script>
@endscript
