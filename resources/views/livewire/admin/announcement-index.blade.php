<div>
    <div class="mb-6 flex justify-between items-center gap-4">
        <div>
            <h1 class="text-3xl font-bold text-white mb-2">Announcements</h1>
            <p class="text-gray-300">Broadcast messages to all active members</p>
        </div>
        <div class="flex items-center gap-3">
            <input 
                type="text" 
                wire:model.live.debounce.300ms="search"
                placeholder="Search by subject…"
                class="bg-dark-card text-white placeholder-gray-500 px-3 py-2 text-sm rounded-md focus:outline-none focus:ring-1 focus:ring-amber-400">
            <select wire:model.live="filterStatus" class="bg-dark-card text-white px-3 py-2 text-sm rounded-md focus:outline-none focus:ring-1 focus:ring-amber-400">
                <option value="" class="bg-dark-page text-white">All Status</option>
                <option value="sent" class="bg-dark-page text-white">Sent</option>
                <option value="draft" class="bg-dark-page text-white">Draft</option>
            </select>
            <button wire:click="openCreate" class="bg-gradient-to-r from-amber-400 to-yellow-500 hover:from-amber-500 hover:to-yellow-600 transition-colors text-black font-bold text-xs px-2.5 py-1 rounded shadow-sm">+ Compose</button>
        </div>
    </div>

    @if(session('success'))
        <div class="bg-green-900/20 border border-green-700 text-green-300 text-sm px-4 py-3 rounded-md mb-4">{{ session('success') }}</div>
    @endif

    @if($showForm)
    <div class="bg-dark-card border border-gray-600 rounded-md p-5 mb-4">
        <h2 class="text-sm font-semibold text-white mb-4">Compose Announcement</h2>
        <div class="space-y-4">
            <div class="flex flex-col gap-1">
                <label class="text-sm font-medium text-gray-300">Subject</label>
                <input type="text" wire:model="subject" class="border border-gray-600 rounded-md px-3 py-2 text-sm w-full bg-dark-page text-white placeholder-gray-400">
                @error('subject')<p class="text-xs text-red-400">{{ $message }}</p>@enderror
            </div>
            <div class="flex flex-col gap-1">
                <label class="text-sm font-medium text-gray-300">Body</label>
                <textarea wire:model="body" rows="5" class="border border-gray-600 rounded-md px-3 py-2 text-sm w-full bg-dark-page text-white placeholder-gray-400"></textarea>
                @error('body')<p class="text-xs text-red-400">{{ $message }}</p>@enderror
            </div>
            <div class="flex items-end gap-4">
                <div class="flex flex-col gap-1">
                    <label class="text-sm font-medium text-gray-300">Recipients</label>
                    <select wire:model.live="recipientFilter" class="border border-gray-600 rounded-md px-3 py-2 text-sm bg-dark-page text-white">
                        <option value="all" class="bg-dark-page text-white">All Active Members</option>
                        <option value="plan" class="bg-dark-page text-white">By Plan</option>
                    </select>
                </div>
                @if($recipientFilter === 'plan')
                <div class="flex flex-col gap-1">
                    <label class="text-sm font-medium text-gray-300">Plan</label>
                    <select wire:model.live="planId" class="border border-gray-600 rounded-md px-3 py-2 text-sm bg-dark-page text-white">
                        <option value="" class="bg-dark-page text-white">Select…</option>
                        @foreach($plans as $plan)<option value="{{ $plan->id }}" class="bg-dark-page text-white">{{ $plan->name }}</option>@endforeach
                    </select>
                </div>
                @endif
                <div class="border border-gray-600 rounded-md px-3 py-2 text-sm bg-dark-page">
                    <span class="font-semibold text-white">{{ $recipientCount }}</span>
                    <span class="text-gray-400"> recipients</span>
                </div>
            </div>
        </div>
        <div class="flex gap-3 mt-4">
            <button wire:click="send" class="bg-black hover:bg-gray-800 transition-colors text-white text-sm px-4 py-2 rounded-md">Send</button>
            <button wire:click="$set('showForm', false)" class="border border-gray-600 text-gray-300 hover:bg-gray-700 transition-colors text-sm px-4 py-2 rounded-md">Cancel</button>
        </div>
    </div>
    @endif

    <div class="bg-dark-card border border-gray-600 rounded-md overflow-hidden">
        <table class="w-full text-sm">
            <thead class="border-b border-gray-600 bg-dark-card">
                <tr>
                    <th class="text-left text-xs font-medium text-gray-300 uppercase tracking-wide py-3 px-4">Subject</th>
                    <th class="text-left text-xs font-medium text-gray-300 uppercase tracking-wide py-3 px-4">Sent By</th>
                    <th class="text-left text-xs font-medium text-gray-300 uppercase tracking-wide py-3 px-4">Sent At</th>
                </tr>
            </thead>
            <tbody class="bg-dark-card">
                @forelse($announcements as $a)
                <tr class="border-b border-gray-600 hover:bg-gray-700 transition-colors">
                    <td class="py-3 px-4 font-medium text-white">{{ $a->subject }}</td>
                    <td class="py-3 px-4 text-gray-300">{{ $a->admin?->name ?? 'System' }}</td>
                    <td class="py-3 px-4 text-gray-400">{{ $a->sent_at?->format('M j, Y H:i') ?? '—' }}</td>
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
