<div>
    <div class="mb-6 flex justify-between items-start">
        <div>
            <h1 class="text-3xl font-bold text-white mb-2">Membership Plans</h1>
            <p class="text-gray-300">Manage plans shown on the public site</p>
        </div>
        <button wire:click="openCreate" class="bg-gradient-to-r from-amber-400 to-yellow-500 hover:from-amber-500 hover:to-yellow-600 transition-colors text-black font-bold text-xs px-2.5 py-1 rounded shadow-sm mt-2">+ New Plan</button>
    </div>

    @if(session('success'))
        <div class="bg-green-900/20 border border-green-700 text-green-300 text-sm px-4 py-3 rounded-md mb-4">{{ session('success') }}</div>
    @endif

    @if($showForm)
        <div class="bg-dark-card border border-gray-600 rounded-md p-5 mb-4">
            <h2 class="text-sm font-semibold text-white mb-4">{{ $editingId ? 'Edit Plan' : 'New Plan' }}</h2>
            <div class="grid grid-cols-2 gap-4">
                <div class="flex flex-col gap-1">
                    <label class="text-sm font-medium text-gray-300">Plan Name</label>
                    <input type="text" wire:model="name" class="border border-gray-600 rounded-md px-3 py-2 text-sm w-full bg-dark-page text-white placeholder-gray-400">
                    @error('name')<p class="text-xs text-red-400">{{ $message }}</p>@enderror
                </div>
                <div class="flex flex-col gap-1">
                    <label class="text-sm font-medium text-gray-300">Duration (days)</label>
                    <input type="number" wire:model="durationDays" class="border border-gray-600 rounded-md px-3 py-2 text-sm w-full bg-dark-page text-white">
                    @error('durationDays')<p class="text-xs text-red-400">{{ $message }}</p>@enderror
                </div>
                <div class="flex flex-col gap-1">
                    <label class="text-sm font-medium text-gray-300">Price (₱)</label>
                    <input type="number" wire:model="price" step="0.01" class="border border-gray-600 rounded-md px-3 py-2 text-sm w-full bg-dark-page text-white">
                    @error('price')<p class="text-xs text-red-400">{{ $message }}</p>@enderror
                </div>
                <div class="flex flex-col gap-1">
                    <label class="text-sm font-medium text-gray-300">Active</label>
                    <div class="flex items-center gap-2 mt-1">
                        <input type="checkbox" wire:model="isActive" id="isActive" class="rounded">
                        <label for="isActive" class="text-sm text-gray-300">Show on public site</label>
                    </div>
                </div>
                <div class="col-span-2 flex flex-col gap-1">
                    <label class="text-sm font-medium text-gray-300">Benefits (one per line)</label>
                    <textarea wire:model="benefitsRaw" rows="4" class="border border-gray-600 rounded-md px-3 py-2 text-sm w-full bg-dark-page text-white placeholder-gray-400"></textarea>
                    @error('benefitsRaw')<p class="text-xs text-red-400">{{ $message }}</p>@enderror
                </div>
            </div>
            <div class="flex gap-3 mt-4">
                <button wire:click="save" class="bg-black hover:bg-gray-800 transition-colors text-white text-sm px-4 py-2 rounded-md">{{ $editingId ? 'Update' : 'Create' }}</button>
                <button wire:click="cancel" class="border border-gray-600 text-gray-300 hover:bg-gray-700 transition-colors text-sm px-4 py-2 rounded-md">Cancel</button>
            </div>
        </div>
    @endif

    <div class="bg-dark-card border border-gray-600 rounded-md overflow-hidden">
        <table class="w-full text-sm">
            <thead class="border-b border-gray-600 bg-dark-card">
                <tr>
                    <th class="text-left text-xs font-medium text-gray-300 uppercase tracking-wide py-3 px-4">Name</th>
                    <th class="text-left text-xs font-medium text-gray-300 uppercase tracking-wide py-3 px-4">Duration</th>
                    <th class="text-left text-xs font-medium text-gray-300 uppercase tracking-wide py-3 px-4">Price</th>
                    <th class="text-left text-xs font-medium text-gray-300 uppercase tracking-wide py-3 px-4">Status</th>
                    <th class="text-left text-xs font-medium text-gray-300 uppercase tracking-wide py-3 px-4">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-dark-card">
                @forelse($plans as $plan)
                    <tr class="border-b border-gray-600 hover:bg-gray-700 transition-colors">
                        <td class="py-3 px-4 font-medium text-white">{{ $plan->name }}</td>
                        <td class="py-3 px-4 text-gray-300">{{ $plan->duration_days }} days</td>
                        <td class="py-3 px-4 text-gray-300">₱{{ number_format($plan->price, 2) }}</td>
                        <td class="py-3 px-4">
                            @if($plan->is_active)
                                <span class="inline-flex px-2 py-0.5 rounded text-xs font-medium bg-green-900/20 text-green-300 border border-green-700">Active</span>
                            @else
                                <span class="inline-flex px-2 py-0.5 rounded text-xs font-medium bg-red-900/20 text-red-300 border border-red-700">Inactive</span>
                            @endif
                        </td>
                        <td class="py-3 px-4">
                            <div class="flex gap-2">
                                <button wire:click="openEdit({{ $plan->id }})" class="text-sm text-gray-300 underline hover:text-white transition-colors">Edit</button>
                                <button wire:click="toggleActive({{ $plan->id }})" class="text-sm text-gray-300 underline hover:text-white transition-colors">
                                    {{ $plan->is_active ? 'Deactivate' : 'Activate' }}
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="py-8 text-center text-gray-400">
                            <p>No plans yet</p>
                            <p class="text-xs mt-0.5">Create your first membership plan to get started</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
