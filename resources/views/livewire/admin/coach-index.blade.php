<div>
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-xl font-semibold text-gray-900">Coaches</h1>
            <p class="text-sm text-gray-500 mt-0.5">Manage coaching staff and their specializations</p>
        </div>
        <button wire:click="openCreate" class="bg-gray-900 text-white text-sm px-4 py-2 rounded-md">+ Add Coach</button>
    </div>

    @if(session('success'))<div class="bg-green-50 border border-green-200 text-green-700 text-sm px-4 py-3 rounded-md mb-4">{{ session('success') }}</div>@endif

    @if($showForm)
    <div class="bg-white border border-gray-200 rounded-md p-5 mb-4">
        <h2 class="text-sm font-semibold text-gray-700 mb-4">{{ $editingId ? 'Edit' : 'New' }} Coach</h2>
        <div class="space-y-4">
            <div class="flex flex-col gap-1">
                <label class="text-sm font-medium text-gray-700">Name</label>
                <input type="text" wire:model="name" class="border border-gray-300 rounded-md px-3 py-2 text-sm w-full">
                @error('name')<p class="text-xs text-red-500">{{ $message }}</p>@enderror
            </div>
            <div class="flex flex-col gap-1">
                <label class="text-sm font-medium text-gray-700">Photo (optional)</label>
                <input type="file" wire:model="photo" class="border border-gray-300 rounded-md px-3 py-2 text-sm w-full">
            </div>
            <div class="flex flex-col gap-1">
                <label class="text-sm font-medium text-gray-700">Bio</label>
                <textarea wire:model="bio" rows="3" class="border border-gray-300 rounded-md px-3 py-2 text-sm w-full"></textarea>
            </div>
            <div class="flex flex-col gap-1">
                <label class="text-sm font-medium text-gray-700">Specializations (one per line)</label>
                <textarea wire:model="specializationsRaw" rows="3" class="border border-gray-300 rounded-md px-3 py-2 text-sm w-full"></textarea>
            </div>
        </div>
        <div class="flex gap-3 mt-4">
            <button wire:click="save" class="bg-gray-900 text-white text-sm px-4 py-2 rounded-md">Save</button>
            <button wire:click="$set('showForm', false)" class="border border-gray-300 text-gray-700 text-sm px-4 py-2 rounded-md">Cancel</button>
        </div>
    </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        @forelse($coaches as $coach)
        <div class="bg-white border border-gray-200 rounded-md p-4">
            <div class="flex items-start justify-between mb-2">
                <div>
                    <p class="font-medium text-gray-900">{{ $coach->name }}</p>
                    <p class="text-xs text-gray-400">{{ $coach->bookings_count }} bookings</p>
                    @if($coach->specializations)
                        <div class="flex flex-wrap gap-1 mt-2">
                            @foreach($coach->specializations as $s)
                                <span class="inline-flex px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-600 border border-gray-200">{{ $s }}</span>
                            @endforeach
                        </div>
                    @endif
                </div>
                <div class="flex gap-2 flex-shrink-0">
                    <button wire:click="openEdit({{ $coach->id }})" class="text-sm text-gray-600 underline">Edit</button>
                    <button wire:click="delete({{ $coach->id }})" wire:confirm="Delete this coach?" class="border border-red-200 text-red-600 text-xs px-2 py-1 rounded">Delete</button>
                </div>
            </div>
        </div>
        @empty
        <div class="col-span-3 bg-white border border-gray-200 rounded-md p-8 text-center">
            <p class="text-sm text-gray-400">No coaches yet</p>
            <p class="text-xs text-gray-300 mt-0.5">Add your first coach to enable session bookings</p>
        </div>
        @endforelse
    </div>
</div>
