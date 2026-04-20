<div>
    <div class="mb-6 flex justify-between items-start">
        <div>
            <h1 class="text-3xl font-bold text-white mb-2">Coaches</h1>
            <p class="text-gray-300">Manage coaching staff and their specializations</p>
        </div>
        <button wire:click="openCreate" class="bg-gradient-to-r from-amber-400 to-yellow-500 hover:from-amber-500 hover:to-yellow-600 transition-colors text-black font-bold text-xs px-2.5 py-1 rounded shadow-sm mt-2">+ Add Coach</button>
    </div>

    @if(session('success'))<div class="bg-green-900/20 border border-green-700 text-green-300 text-sm px-4 py-3 rounded-md mb-4">{{ session('success') }}</div>@endif

    @if($showForm)
    <div class="bg-dark-card border border-gray-600 rounded-md p-5 mb-4">
        <h2 class="text-sm font-semibold text-white mb-4">{{ $editingId ? 'Edit' : 'New' }} Coach</h2>
        <div class="space-y-4">
            <div class="flex flex-col gap-1">
                <label class="text-sm font-medium text-gray-300">Name</label>
                <input type="text" wire:model="name" class="border border-gray-600 rounded-md px-3 py-2 text-sm w-full bg-dark-page text-white placeholder-gray-400">
                @error('name')<p class="text-xs text-red-400">{{ $message }}</p>@enderror
            </div>
            <div class="flex flex-col gap-1">
                <label class="text-sm font-medium text-gray-300">Photo (optional)</label>
                <input type="file" wire:model="photo" class="border border-gray-600 rounded-md px-3 py-2 text-sm w-full bg-dark-page text-white">
            </div>
            <div class="flex flex-col gap-1">
                <label class="text-sm font-medium text-gray-300">Bio</label>
                <textarea wire:model="bio" rows="3" class="border border-gray-600 rounded-md px-3 py-2 text-sm w-full bg-dark-page text-white placeholder-gray-400"></textarea>
            </div>
            <div class="flex flex-col gap-1">
                <label class="text-sm font-medium text-gray-300">Specializations (one per line)</label>
                <textarea wire:model="specializationsRaw" rows="3" class="border border-gray-600 rounded-md px-3 py-2 text-sm w-full bg-dark-page text-white placeholder-gray-400"></textarea>
            </div>
        </div>
        <div class="flex gap-3 mt-4">
            <button wire:click="save" class="bg-black hover:bg-gray-800 transition-colors text-white text-sm px-4 py-2 rounded-md">Save</button>
            <button wire:click="$set('showForm', false)" class="border border-gray-600 text-gray-300 hover:bg-gray-700 transition-colors text-sm px-4 py-2 rounded-md">Cancel</button>
        </div>
    </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        @forelse($coaches as $coach)
        <div class="bg-dark-card border border-gray-600 rounded-md p-4">
            <div class="flex items-start justify-between mb-2">
                <div>
                    <p class="font-medium text-white">{{ $coach->name }}</p>
                    <p class="text-xs text-gray-400">{{ $coach->bookings_count }} bookings</p>
                    @if($coach->specializations)
                        <div class="flex flex-wrap gap-1 mt-2">
                            @foreach($coach->specializations as $s)
                                <span class="inline-flex px-2 py-0.5 rounded text-xs font-medium bg-gray-700 text-gray-300 border border-gray-600">{{ $s }}</span>
                            @endforeach
                        </div>
                    @endif
                </div>
                <div class="flex gap-2 flex-shrink-0">
                    <button wire:click="openEdit({{ $coach->id }})" class="text-sm text-gray-300 underline hover:text-white transition-colors">Edit</button>
                    <button wire:click="delete({{ $coach->id }})" wire:confirm="Delete this coach?" class="border border-red-700 text-red-400 text-xs px-2 py-1 rounded hover:bg-red-900/20 transition-colors">Delete</button>
                </div>
            </div>
        </div>
        @empty
        <div class="col-span-3 bg-dark-card border border-gray-600 rounded-md p-8 text-center">
            <p class="text-sm text-gray-400">No coaches yet</p>
            <p class="text-xs text-gray-400 mt-0.5">Add your first coach to enable session bookings</p>
        </div>
        @endforelse
    </div>
</div>
