<div>
    <div class="mb-4">
        <h1 class="text-xl font-semibold text-gray-900">Live Chat</h1>
        <p class="text-sm text-gray-500">Respond to member support conversations in real time</p>
    </div>

    <div class="flex gap-4" style="height: 600px;">
        {{-- Conversation List --}}
        <div class="w-64 border border-gray-200 rounded-md overflow-y-auto bg-white flex-shrink-0 flex flex-col">
            <p class="px-3 py-2 text-xs font-medium text-gray-500 uppercase tracking-wide border-b border-gray-100">Open Conversations</p>
            @forelse($conversations as $conv)
            <div wire:click="selectConversation({{ $conv->id }})" class="px-3 py-3 border-b border-gray-100 cursor-pointer hover:bg-gray-50">
                <div class="flex items-center justify-between">
                    <p class="font-medium text-sm text-gray-900">{{ $conv->member?->name ?? 'Unknown' }}</p>
                    @if($conv->status === 'escalated')
                        <span class="inline-flex px-1.5 py-0.5 rounded text-xs font-medium bg-yellow-50 text-yellow-700 border border-yellow-200">Escalated</span>
                    @else
                        <span class="inline-flex px-1.5 py-0.5 rounded text-xs font-medium bg-green-50 text-green-700 border border-green-200">Open</span>
                    @endif
                </div>
                @if($conv->messages->first())
                    <p class="text-xs text-gray-400 mt-0.5 truncate">{{ $conv->messages->first()->body }}</p>
                @endif
            </div>
            @empty
            <div class="p-4 text-center">
                <p class="text-sm text-gray-400">No open conversations</p>
                <p class="text-xs text-gray-300 mt-0.5">New member chats will appear here</p>
            </div>
            @endforelse
        </div>

        {{-- Message Thread --}}
        @if($activeConversation)
        <div class="flex-1 border border-gray-200 rounded-md overflow-hidden bg-white flex flex-col">
            <div class="px-4 py-3 border-b border-gray-100 flex items-center justify-between">
                <div>
                    <p class="font-medium text-gray-900">{{ $activeConversation->member?->name }}</p>
                    <p class="text-xs text-gray-400">{{ ucfirst($activeConversation->status) }}</p>
                </div>
                <button wire:click="closeConversation({{ $activeConversation->id }})" wire:confirm="Close this conversation?" class="border border-red-200 text-red-600 text-sm px-3 py-1 rounded">Close</button>
            </div>

            <div class="flex-1 overflow-y-auto px-4 py-3 space-y-3">
                @foreach($activeMessages as $msg)
                <div class="flex">
                    <div class="bg-gray-50 border border-gray-100 rounded-md px-3 py-2 text-sm text-gray-700 max-w-xs">
                        @if($msg->sender_type === 'admin')<p class="text-xs font-medium text-gray-400 mb-0.5">Staff</p>@endif
                        @if($msg->sender_type === 'bot')<p class="text-xs font-medium text-gray-400 mb-0.5">Bot</p>@endif
                        {{ $msg->body }}
                    </div>
                </div>
                @endforeach
            </div>

            @if($activeConversation->status !== 'closed')
            <div class="px-3 py-2 border-t border-gray-100">
                <div class="flex gap-2">
                    <input type="text" wire:model="reply" wire:keydown.enter="sendReply"
                        placeholder="Type a reply…"
                        class="flex-1 border border-gray-300 rounded-md px-3 py-1.5 text-sm">
                    <button wire:click="sendReply" class="bg-gray-900 text-white text-sm px-3 py-1.5 rounded-md">Send</button>
                </div>
            </div>
            @endif
        </div>
        @else
        <div class="flex-1 border border-gray-200 rounded-md bg-white flex items-center justify-center">
            <div class="text-center">
                <p class="text-sm text-gray-400">Select a conversation to view messages</p>
                <p class="text-xs text-gray-300 mt-1">Choose from the list on the left</p>
            </div>
        </div>
        @endif
    </div>
</div>
