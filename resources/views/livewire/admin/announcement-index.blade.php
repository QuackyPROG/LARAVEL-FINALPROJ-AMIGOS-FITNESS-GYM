<div>
    <div>
        <div>
            <h1>Announcements</h1>
            <p>Broadcast messages to all active members</p>
        </div>
        <button wire:click="openCreate">+ Compose</button>
    </div>

    @if(session('success'))<div>{{ session('success') }}</div>@endif

    @if($showForm)
    <div>
        <h2>Compose Announcement</h2>
        <div>
            <div><label>Subject</label>
                <input type="text" wire:model="subject">
                @error('subject')<p>{{ $message }}</p>@enderror
            </div>
            <div><label>Body</label>
                <textarea wire:model="body" rows="5"></textarea>
                @error('body')<p>{{ $message }}</p>@enderror
            </div>
            <div>
                <div>
                    <label>Recipients</label>
                    <select wire:model.live="recipientFilter">
                        <option value="all">All Active Members</option>
                        <option value="plan">By Plan</option>
                    </select>
                </div>
                @if($recipientFilter === 'plan')
                <div>
                    <label>Plan</label>
                    <select wire:model.live="planId">
                        <option value="">Select…</option>
                        @foreach($plans as $plan)<option value="{{ $plan->id }}">{{ $plan->name }}</option>@endforeach
                    </select>
                </div>
                @endif
                <div>
                    <span><span>{{ $recipientCount }}</span> recipients</span>
                </div>
            </div>
        </div>
        <div>
            <button wire:click="send">Send</button>
            <button wire:click="$set('showForm', false)">Cancel</button>
        </div>
    </div>
    @endif

    <div>
        <table>
            <thead>
                <tr>
                    <th>Subject</th>
                    <th>Sent By</th>
                    <th>Sent At</th>
                </tr>
            </thead>
            <tbody>
                @forelse($announcements as $a)
                <tr>
                    <td>{{ $a->subject }}</td>
                    <td>{{ $a->admin?->name ?? 'System' }}</td>
                    <td>{{ $a->sent_at?->format('M j, Y H:i') ?? '—' }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="3">
                        <p>No announcements sent</p>
                        <p>Compose a message to broadcast to all active members</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
