<div>
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-xl font-semibold text-gray-900">Membership Plans</h1>
            <p class="text-sm text-gray-500 mt-0.5">Manage plans shown on the public site</p>
        </div>
        <button wire:click="openCreate" class="bg-gray-900 text-white text-sm px-4 py-2 rounded-md">+ New Plan</button>
    </div>

    @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-700 text-sm px-4 py-3 rounded-md mb-4">{{ session('success') }}</div>
    @endif

    @if($showForm)
        <div class="bg-white border border-gray-200 rounded-md p-5 mb-4">
            <h2 class="text-sm font-semibold text-gray-700 mb-4">{{ $editingId ? 'Edit Plan' : 'New Plan' }}</h2>
            <div class="grid grid-cols-2 gap-4">
                <div class="flex flex-col gap-1">
                    <label class="text-sm font-medium text-gray-700">Plan Name</label>
                    <input type="text" wire:model="name" class="border border-gray-300 rounded-md px-3 py-2 text-sm w-full">
                    @error('name')<p class="text-xs text-red-500">{{ $message }}</p>@enderror
                </div>
                <div class="flex flex-col gap-1">
                    <label class="text-sm font-medium text-gray-700">Duration (days)</label>
                    <input type="number" wire:model="durationDays" class="border border-gray-300 rounded-md px-3 py-2 text-sm w-full">
                    @error('durationDays')<p class="text-xs text-red-500">{{ $message }}</p>@enderror
                </div>
                <div class="flex flex-col gap-1">
                    <label class="text-sm font-medium text-gray-700">Price (₱)</label>
                    <input type="number" wire:model="price" step="0.01" class="border border-gray-300 rounded-md px-3 py-2 text-sm w-full">
                    @error('price')<p class="text-xs text-red-500">{{ $message }}</p>@enderror
                </div>
                <div class="flex flex-col gap-1">
                    <label class="text-sm font-medium text-gray-700">Active</label>
                    <div class="flex items-center gap-2 mt-1">
                        <input type="checkbox" wire:model="isActive" id="isActive">
                        <label for="isActive" class="text-sm text-gray-600">Show on public site</label>
                    </div>
                </div>
                <div class="col-span-2 flex flex-col gap-1">
                    <label class="text-sm font-medium text-gray-700">Benefits (one per line)</label>
                    <textarea wire:model="benefitsRaw" rows="4" class="border border-gray-300 rounded-md px-3 py-2 text-sm w-full"></textarea>
                    @error('benefitsRaw')<p class="text-xs text-red-500">{{ $message }}</p>@enderror
                </div>
            </div>
            <div class="flex gap-3 mt-4">
                <button wire:click="save" class="bg-gray-900 text-white text-sm px-4 py-2 rounded-md">{{ $editingId ? 'Update' : 'Create' }}</button>
                <button wire:click="cancel" class="border border-gray-300 text-gray-700 text-sm px-4 py-2 rounded-md">Cancel</button>
            </div>
        </div>
    @endif

    <div class="bg-white border border-gray-200 rounded-md overflow-hidden">
        <table class="w-full text-sm">
            <thead class="border-b border-gray-200 bg-gray-50">
                <tr>
                    <th class="text-left text-xs font-medium text-gray-500 uppercase tracking-wide py-3 px-4">Name</th>
                    <th class="text-left text-xs font-medium text-gray-500 uppercase tracking-wide py-3 px-4">Duration</th>
                    <th class="text-left text-xs font-medium text-gray-500 uppercase tracking-wide py-3 px-4">Price</th>
                    <th class="text-left text-xs font-medium text-gray-500 uppercase tracking-wide py-3 px-4">Status</th>
                    <th class="text-left text-xs font-medium text-gray-500 uppercase tracking-wide py-3 px-4">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($plans as $plan)
                    <tr class="border-b border-gray-100">
                        <td class="py-3 px-4 font-medium text-gray-900">{{ $plan->name }}</td>
                        <td class="py-3 px-4 text-gray-600">{{ $plan->duration_days }} days</td>
                        <td class="py-3 px-4 text-gray-600">₱{{ number_format($plan->price, 2) }}</td>
                        <td class="py-3 px-4">
                            @if($plan->is_active)
                                <span class="inline-flex px-2 py-0.5 rounded text-xs font-medium bg-green-50 text-green-700 border border-green-200">Active</span>
                            @else
                                <span class="inline-flex px-2 py-0.5 rounded text-xs font-medium bg-red-50 text-red-700 border border-red-200">Inactive</span>
                            @endif
                        </td>
                        <td class="py-3 px-4">
                            <div class="flex gap-2">
                                <button wire:click="openEdit({{ $plan->id }})" class="text-sm text-gray-600 underline">Edit</button>
                                <button wire:click="toggleActive({{ $plan->id }})" class="text-sm text-gray-600 underline">
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
