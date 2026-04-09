<div>
    <div>
        <h1>Live Chat</h1>
        <p>Respond to member support conversations in real time</p>
    </div>

    <div>
        {{-- Conversation List --}}
        <div>
            <p>Open Conversations</p>
            @forelse($conversations as $conv)
            <div wire:click="selectConversation({{ $conv->id }})">
                <div>
                    <p>{{ $conv->member?->name ?? 'Unknown' }}</p>
                    @if($conv->status === 'escalated')
                        <span>Escalated</span>
                    @else
                        <span>Open</span>
                    @endif
                </div>
                @if($conv->messages->first())
                    <p>{{ $conv->messages->first()->body }}</p>
                @endif
            </div>
            @empty
            <div>
                <p>No open conversations</p>
                <p>New member chats will appear here</p>
            </div>
            @endforelse
        </div>

        {{-- Message Thread --}}
        <div>
            @if($activeConversation)
                <div>
                    <div>
                        <p>{{ $activeConversation->member?->name }}</p>
                        <p>{{ ucfirst($activeConversation->status) }}</p>
                    </div>
                    <button wire:click="closeConversation({{ $activeConversation->id }})" wire:confirm="Close this conversation?">Close</button>
                </div>

                <div>
                    @foreach($activeMessages as $msg)
                    <div>
                        <div>
                            @if($msg->sender_type === 'admin')<p>Staff</p>@endif
                            @if($msg->sender_type === 'bot')<p>Bot</p>@endif
                            {{ $msg->body }}
                        </div>
                    </div>
                    @endforeach
                </div>

                @if($activeConversation->status !== 'closed')
                <div>
                    <div>
                        <input type="text" wire:model="reply" wire:keydown.enter="sendReply"
                            placeholder="Type a reply…">
                        <button wire:click="sendReply">Send</button>
                    </div>
                </div>
                @endif
            @else
                <div>
                    <div>
                        <p>Select a conversation to view messages</p>
                        <p>Choose from the list on the left</p>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
