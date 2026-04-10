<div>
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-xl font-semibold text-gray-900">Announcements</h1>
            <p class="text-sm text-gray-500 mt-0.5">Broadcast messages to all active members</p>
        </div>
        <button wire:click="openCreate" class="bg-gray-900 text-white text-sm px-4 py-2 rounded-md">+ Compose</button>
    </div>

    @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-700 text-sm px-4 py-3 rounded-md mb-4">{{ session('success') }}</div>
    @endif

    @if($showForm)
    <div class="bg-white border border-gray-200 rounded-md p-5 mb-4">
        <h2 class="text-sm font-semibold text-gray-700 mb-4">Compose Announcement</h2>
        <div class="space-y-4">
            <div class="flex flex-col gap-1">
                <label class="text-sm font-medium text-gray-700">Subject</label>
                <input type="text" wire:model="subject" class="border border-gray-300 rounded-md px-3 py-2 text-sm w-full">
                @error('subject')<p class="text-xs text-red-500">{{ $message }}</p>@enderror
            </div>
            <div class="flex flex-col gap-1">
                <label class="text-sm font-medium text-gray-700">Body</label>
                <textarea wire:model="body" rows="5" class="border border-gray-300 rounded-md px-3 py-2 text-sm w-full"></textarea>
                @error('body')<p class="text-xs text-red-500">{{ $message }}</p>@enderror
            </div>
            <div class="flex items-end gap-4">
                <div class="flex flex-col gap-1">
                    <label class="text-sm font-medium text-gray-700">Recipients</label>
                    <select wire:model.live="recipientFilter" class="border border-gray-300 rounded-md px-3 py-2 text-sm">
                        <option value="all">All Active Members</option>
                        <option value="plan">By Plan</option>
                    </select>
                </div>
                @if($recipientFilter === 'plan')
                <div class="flex flex-col gap-1">
                    <label class="text-sm font-medium text-gray-700">Plan</label>
                    <select wire:model.live="planId" class="border border-gray-300 rounded-md px-3 py-2 text-sm">
                        <option value="">Select…</option>
                        @foreach($plans as $plan)<option value="{{ $plan->id }}">{{ $plan->name }}</option>@endforeach
                    </select>
                </div>
                @endif
                <div class="border border-gray-200 rounded-md px-3 py-2 text-sm bg-gray-50">
                    <span class="font-semibold text-gray-900">{{ $recipientCount }}</span>
                    <span class="text-gray-500"> recipients</span>
                </div>
            </div>
        </div>
        <div class="flex gap-3 mt-4">
            <button wire:click="send" class="bg-gray-900 text-white text-sm px-4 py-2 rounded-md">Send</button>
            <button wire:click="$set('showForm', false)" class="border border-gray-300 text-gray-700 text-sm px-4 py-2 rounded-md">Cancel</button>
        </div>
    </div>
    @endif

    <div class="bg-white border border-gray-200 rounded-md overflow-hidden">
        <table class="w-full text-sm">
            <thead class="border-b border-gray-200 bg-gray-50">
                <tr>
                    <th class="text-left text-xs font-medium text-gray-500 uppercase tracking-wide py-3 px-4">Subject</th>
                    <th class="text-left text-xs font-medium text-gray-500 uppercase tracking-wide py-3 px-4">Sent By</th>
                    <th class="text-left text-xs font-medium text-gray-500 uppercase tracking-wide py-3 px-4">Sent At</th>
                </tr>
            </thead>
            <tbody>
                @forelse($announcements as $a)
                <tr class="border-b border-gray-100">
                    <td class="py-3 px-4 font-medium text-gray-900">{{ $a->subject }}</td>
                    <td class="py-3 px-4 text-gray-600">{{ $a->admin?->name ?? 'System' }}</td>
                    <td class="py-3 px-4 text-gray-500">{{ $a->sent_at?->format('M j, Y H:i') ?? '—' }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="3" class="py-8 text-center text-gray-400">
                        <p>No announcements sent</p>
                        <p class="text-xs mt-0.5">Compose a message to broadcast to all active members</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
