<div>
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-xl font-semibold text-gray-900">Members</h1>
            <p class="text-sm text-gray-500 mt-0.5">All registered members</p>
        </div>
        <a href="{{ route('admin.members.create') }}" class="bg-gray-900 text-white text-sm px-4 py-2 rounded-md">+ Add Walk-in Member</a>
    </div>

    @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-700 text-sm px-4 py-3 rounded-md mb-4">{{ session('success') }}</div>
    @endif

    <div class="flex gap-3 mb-4">
        <input type="text" wire:model.live.debounce.300ms="search"
            placeholder="Search name or email…"
            class="border border-gray-300 rounded-md px-3 py-2 text-sm flex-1">
        <select wire:model.live="statusFilter" class="border border-gray-300 rounded-md px-3 py-2 text-sm">
            <option value="">All statuses</option>
            <option value="active">Active</option>
            <option value="inactive">Inactive</option>
            <option value="pending">Pending</option>
        </select>
    </div>

    <div class="bg-white border border-gray-200 rounded-md overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="border-b border-gray-200 bg-gray-50">
                    <tr>
                        <th class="text-left text-xs font-medium text-gray-500 uppercase tracking-wide py-3 px-4">Name</th>
                        <th class="text-left text-xs font-medium text-gray-500 uppercase tracking-wide py-3 px-4">Email</th>
                        <th class="text-left text-xs font-medium text-gray-500 uppercase tracking-wide py-3 px-4">Status</th>
                        <th class="text-left text-xs font-medium text-gray-500 uppercase tracking-wide py-3 px-4">Plan</th>
                        <th class="text-left text-xs font-medium text-gray-500 uppercase tracking-wide py-3 px-4">Expires</th>
                        <th class="text-left text-xs font-medium text-gray-500 uppercase tracking-wide py-3 px-4">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($members as $member)
                        <tr class="border-b border-gray-100">
                            <td class="py-3 px-4">
                                <a href="{{ route('admin.members.show', $member) }}" class="font-medium text-gray-900 underline">{{ $member->name }}</a>
                            </td>
                            <td class="py-3 px-4 text-gray-600">{{ $member->email }}</td>
                            <td class="py-3 px-4">
                                @if($member->status === 'active')
                                    <span class="inline-flex px-2 py-0.5 rounded text-xs font-medium bg-green-50 text-green-700 border border-green-200">Active</span>
                                @elseif($member->status === 'inactive')
                                    <span class="inline-flex px-2 py-0.5 rounded text-xs font-medium bg-red-50 text-red-700 border border-red-200">Inactive</span>
                                @else
                                    <span class="inline-flex px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-600 border border-gray-200">{{ ucfirst($member->status) }}</span>
                                @endif
                            </td>
                            <td class="py-3 px-4 text-gray-600">{{ $member->activeMembership?->plan?->name ?? '—' }}</td>
                            <td class="py-3 px-4 text-gray-500">{{ $member->activeMembership?->expires_at?->format('M j, Y') ?? '—' }}</td>
                            <td class="py-3 px-4">
                                <div class="flex items-center gap-2">
                                    <a href="{{ route('admin.members.show', $member) }}" class="text-sm text-gray-600 underline">View</a>
                                    <button wire:click="deactivate({{ $member->id }})" wire:confirm="Deactivate this member?" class="border border-red-200 text-red-600 text-xs px-2 py-1 rounded">Deactivate</button>
                                    <button wire:click="softDelete({{ $member->id }})" wire:confirm="Permanently delete this member?" class="border border-red-200 text-red-600 text-xs px-2 py-1 rounded">Delete</button>
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
    </div>

    <div class="mt-4">{{ $members->links() }}</div>
</div>
