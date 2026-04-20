<div>
    <div class="mb-6 flex justify-between items-center gap-4">
        <div>
            <h1 class="text-3xl font-bold text-amber-400 mb-2">Members</h1>
            <p class="text-gray-300">All registered members</p>
        </div>
        <div class="flex items-center gap-3">
            <input type="text" wire:model.live.debounce.300ms="search"
                placeholder="Search name or email…"
                class="bg-dark-card text-white placeholder-gray-500 px-3 py-2 text-sm rounded-md focus:outline-none focus:ring-1 focus:ring-amber-400 w-64">
            <select wire:model.live="statusFilter" class="bg-dark-card text-white px-3 py-2 text-sm rounded-md focus:outline-none focus:ring-1 focus:ring-amber-400 w-40">
                <option value="" class="bg-dark-page text-white">All statuses</option>
                <option value="active" class="bg-dark-page text-white">Active</option>
                <option value="inactive" class="bg-dark-page text-white">Inactive</option>
                <option value="pending" class="bg-dark-page text-white">Pending</option>
            </select>
            <a href="{{ route('admin.members.create') }}" class="bg-gradient-to-r from-amber-400 to-yellow-500 hover:from-amber-500 hover:to-yellow-600 transition-colors text-black font-bold text-xs px-2.5 py-1 rounded shadow-sm">+ Add Walk-in Member</a>
        </div>
    </div>

    @if(session('success'))
        <div class="bg-green-900/20 border border-green-700 text-green-300 text-sm px-4 py-3 rounded-md mb-4">{{ session('success') }}</div>
    @endif

    <div class="bg-dark-card border border-gray-600 rounded-md overflow-hidden">
        <table class="w-full text-sm">
                <thead class="border-b border-gray-600 bg-dark-card">
                    <tr>
                        <th class="text-left text-xs font-medium text-gray-300 uppercase tracking-wide py-3 px-4">Name</th>
                        <th class="text-left text-xs font-medium text-gray-300 uppercase tracking-wide py-3 px-4">Email</th>
                        <th class="text-left text-xs font-medium text-gray-300 uppercase tracking-wide py-3 px-4">Status</th>
                        <th class="text-left text-xs font-medium text-gray-300 uppercase tracking-wide py-3 px-4">Plan</th>
                        <th class="text-left text-xs font-medium text-gray-300 uppercase tracking-wide py-3 px-4">Expires</th>
                        <th class="text-left text-xs font-medium text-gray-300 uppercase tracking-wide py-3 px-4">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-dark-card">
                    @forelse($members as $member)
                        <tr class="border-b border-gray-600 hover:bg-gray-700 transition-colors">
                            <td class="py-3 px-4">
                                <a href="{{ route('admin.members.show', $member) }}" class="font-medium text-white underline">{{ $member->name }}</a>
                            </td>
                            <td class="py-3 px-4 text-gray-300">{{ $member->email }}</td>
                            <td class="py-3 px-4">
                                @if($member->status === 'active')
                                    <span class="inline-flex px-2 py-0.5 rounded text-xs font-medium bg-green-900/20 text-green-300 border border-green-700">Active</span>
                                @elseif($member->status === 'inactive')
                                    <span class="inline-flex px-2 py-0.5 rounded text-xs font-medium bg-red-900/20 text-red-300 border border-red-700">Inactive</span>
                                @else
                                    <span class="inline-flex px-2 py-0.5 rounded text-xs font-medium bg-gray-700 text-gray-300 border border-gray-600">{{ ucfirst($member->status) }}</span>
                                @endif
                            </td>
                            <td class="py-3 px-4 text-gray-300">{{ $member->activeMembership?->plan?->name ?? '—' }}</td>
                            <td class="py-3 px-4 text-gray-400">{{ $member->activeMembership?->expires_at?->format('M j, Y') ?? '—' }}</td>
                            <td class="py-3 px-4">
                                <div class="flex items-center gap-2">
                                    <a href="{{ route('admin.members.show', $member) }}" class="text-sm text-gray-300 underline hover:text-white transition-colors">View</a>
                                    <button wire:click="deactivate({{ $member->id }})" wire:confirm="Deactivate this member?" class="border border-red-700 text-red-400 text-xs px-2 py-1 rounded hover:bg-red-900/20 transition-colors">Deactivate</button>
                                    <button wire:click="softDelete({{ $member->id }})" wire:confirm="Permanently delete this member?" class="border border-red-700 text-red-400 text-xs px-2 py-1 rounded hover:bg-red-900/20 transition-colors">Delete</button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-8 text-center text-gray-400">
                                <p>No members found</p>
                                <p class="text-xs mt-0.5">Try adjusting your search or status filter</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
    </div>

    <div class="mt-4">{{ $members->links() }}</div>
</div>
