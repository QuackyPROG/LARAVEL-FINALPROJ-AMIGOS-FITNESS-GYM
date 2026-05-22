<div>
    @if(!$isOpen)
        <button wire:click="openChat" class="fixed bottom-6 right-6 z-50 flex shrink-0 items-center justify-center bg-amber-400 text-black shadow-[0_14px_38px_rgba(251,191,36,0.35)] ring-2 ring-amber-400/30 transition hover:bg-amber-300 active:scale-95" style="border-radius:9999px;height:3.5rem;width:3.5rem;min-height:3.5rem;min-width:3.5rem;" aria-label="Open chat support">
            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2h-5l-5 5v-5z"/>
            </svg>
        </button>
    @else
        <div class="fixed bottom-6 right-6 z-50 flex w-[calc(100vw-2rem)] max-w-sm flex-col overflow-hidden rounded-lg bg-[#080808] shadow-2xl shadow-black/70 ring-1 ring-amber-400/20 sm:w-96" style="height: min(560px, calc(100vh - 3rem));">

            {{-- Header --}}
            <div class="relative overflow-hidden border-b border-amber-400/10 bg-[#080808] px-4 py-4">
                <div class="absolute inset-x-0 top-0 h-[3px] bg-amber-400"></div>
                <div class="absolute inset-0 bg-[radial-gradient(circle_at_90%_0%,rgba(251,191,36,0.12),transparent_50%)]"></div>
                <div class="relative flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-md bg-amber-400/10 text-amber-400 ring-1 ring-amber-400/20">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h8M8 14h5m8-2a9 9 0 1 1-4.2-7.6L21 4l-1.1 4.1A8.9 8.9 0 0 1 21 12Z"/>
                            </svg>
                        </span>
                        <div>
                            <p class="text-xs font-black uppercase tracking-[0.16em] text-white">Chat Support</p>
                            @if($conversation?->isEscalated())
                                <p class="mt-0.5 text-[11px] font-semibold text-amber-400">Staff connected</p>
                            @else
                                <p class="mt-0.5 text-[11px] text-zinc-500">AI Assistant</p>
                            @endif
                        </div>
                    </div>
                    <button wire:click="closeChat" class="rounded-md p-1.5 text-zinc-600 transition hover:bg-zinc-900 hover:text-amber-400" aria-label="Close chat support">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            </div>

            {{-- Messages --}}
            <div class="flex flex-1 flex-col gap-2.5 overflow-y-auto px-4 py-4" id="userChatMessages"
                 x-on:scroll-chat-bottom.window="$el.scrollTo({top: $el.scrollHeight, behavior: 'smooth'})">
                @forelse($messages as $msg)
                    @php
                        $isMember = $msg->sender_type === 'member';
                        $isBot    = $msg->sender_type === 'bot';
                        $isAdmin  = !$isMember && !$isBot;
                    @endphp

                    @if($isBot)
                        {{-- Bot: centered status pill --}}
                        <div class="flex justify-center my-1">
                            <div class="inline-flex items-center gap-1.5 bg-zinc-900/80 border border-zinc-800 rounded-full px-3 py-1.5">
                                <svg class="w-3 h-3 text-zinc-500 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                </svg>
                                <span class="text-[11px] text-zinc-500 italic">{{ $msg->body }}</span>
                            </div>
                        </div>

                    @elseif($isMember)
                        {{-- Member: right side, gold bubble --}}
                        <div class="flex flex-col items-end gap-0.5">
                            <span class="text-[10px] font-semibold text-amber-500/60 tracking-wide pr-1">You</span>
                            <div style="max-width:72%;word-break:break-word;padding:9px 14px;font-size:14px;line-height:1.45;border-radius:18px 18px 4px 18px;background:#e8a020;color:#000;font-weight:600;">
                                {{ $msg->body }}
                            </div>
                        </div>

                    @else
                        {{-- Staff: left side, dark bubble with icon --}}
                        <div class="flex items-end gap-2" style="max-width:85%;">
                            <span class="mb-0.5 flex h-6 w-6 shrink-0 items-center justify-center rounded-md bg-amber-400/10 text-amber-400 ring-1 ring-amber-400/20">
                                <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                            </span>
                            <div class="flex flex-col gap-0.5" style="width:fit-content;max-width:100%;">
                                <span class="text-[10px] font-semibold text-zinc-500 tracking-wide pl-1">Staff</span>
                                <div style="word-break:break-word;padding:9px 14px;font-size:14px;line-height:1.45;border-radius:18px 18px 18px 4px;background:#27272a;color:#f4f4f5;display:inline-block;">
                                    {{ $msg->body }}
                                </div>
                            </div>
                        </div>
                    @endif

                @empty
                    <div class="flex flex-1 items-center justify-center">
                        <div class="rounded-lg border border-dashed border-zinc-800 bg-zinc-950/60 px-5 py-8 text-center">
                            <p class="text-sm font-black uppercase tracking-wide text-zinc-300">We're here to help.</p>
                            <p class="mt-1 text-xs text-zinc-600">Ask about memberships, bookings, billing, or gym access.</p>
                        </div>
                    </div>
                @endforelse
            </div>

            {{-- Input --}}
            @if($conversation?->status !== 'closed')
                <div class="border-t border-amber-400/10 bg-[#080808] px-3 py-3">
                    <div class="flex gap-2">
                        <input type="text" wire:model="message" wire:keydown.enter="sendMessage"
                            placeholder="Type a message..."
                            class="min-w-0 flex-1 rounded-full border border-zinc-800 bg-zinc-950 px-4 py-2 text-sm text-white outline-none transition placeholder:text-zinc-600 focus:border-amber-400 focus:ring-2 focus:ring-amber-400/15">
                        <button wire:click="sendMessage" class="inline-flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-amber-400 text-black transition hover:bg-amber-300 active:scale-95" aria-label="Send message">
                            <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M2.01 21L23 12 2.01 3 2 10l15 2-15 2z"/>
                            </svg>
                        </button>
                    </div>
                    @error('message')<p class="mt-2 text-xs text-red-400">{{ $message }}</p>@enderror
                </div>
            @else
                <div class="border-t border-amber-400/10 bg-[#080808] px-3 py-3 text-center text-xs font-bold uppercase tracking-[0.14em] text-zinc-600">
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

        let currentChannelName = null;

        const subscribe = (conversationId) => {
            if (!conversationId) return;

            const channelName = `support.conversation.${conversationId}`;

            // Already subscribed to this channel
            if (currentChannelName === channelName) return;

            // Clean up old subscription
            if (currentChannelName) {
                window.Echo.leave(currentChannelName);
            }

            window.Echo.private(channelName)
                .listen('.chat.message.created', () => {
                    $wire.$refresh();
                });

            currentChannelName = channelName;
        };

        // Listen for Livewire dispatch event (fires when conversationId becomes available)
        $wire.on('chat-channel-subscribe', ({ conversationId }) => {
            subscribe(conversationId);
        });

        // If we already have a conversationId at render time, subscribe immediately
        const initialId = @js($conversationId);
        if (initialId) {
            subscribe(initialId);
        }

        // Cleanup on navigation / page unload
        const cleanup = () => {
            if (currentChannelName) {
                window.Echo.leave(currentChannelName);
                currentChannelName = null;
            }
        };

        document.addEventListener('livewire:navigating', cleanup);
        window.addEventListener('beforeunload', cleanup);
    })();
</script>
@endscript