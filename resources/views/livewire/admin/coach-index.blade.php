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
                    <div class="relative group">
                        <button class="text-gray-400 hover:text-gray-200 transition-all p-1.5 hover:bg-gray-700/50 rounded-lg border border-transparent hover:border-gray-600" title="More actions">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                <circle cx="3" cy="10" r="1.5" fill="currentColor"/>
                                <circle cx="10" cy="10" r="1.5" fill="currentColor"/>
                                <circle cx="17" cy="10" r="1.5" fill="currentColor"/>
                            </svg>
                        </button>
                        <div class="absolute right-0 mt-2 w-40 bg-dark-card border border-gray-600 rounded-lg shadow-xl opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 z-50">
                            <button wire:click="openEdit({{ $coach->id }})" class="w-full text-left px-4 py-2 text-sm text-gray-300 hover:bg-gray-700/50 hover:text-blue-400 transition-colors rounded-t-lg flex items-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor"><path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"/></svg>
                                Edit
                            </button>
                            <button wire:click="confirmDelete({{ $coach->id }})" class="w-full text-left px-4 py-2 text-sm text-red-400 hover:bg-red-900/20 hover:text-red-300 transition-colors rounded-b-lg flex items-center gap-2 border-t border-gray-700">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                                Delete
                            </button>
                        </div>
                    </div>
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

    @if($showDeleteModal && $selectedCoach)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-md">
            <div class="bg-dark-card border border-red-500/50 rounded-xl shadow-2xl p-6 w-full max-w-sm mx-4">
                <div class="flex items-center gap-4 mb-4">
                    <div class="bg-red-900/30 text-red-400 p-3 rounded-full">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                    </div>
                    <h2 class="text-xl font-bold text-white">Delete Coach</h2>
                </div>
                <p class="text-gray-300 text-sm mb-6">Are you sure you want to permanently delete <strong class="text-white">{{ $selectedCoach->name }}</strong>? This action cannot be undone.</p>
                <div class="flex justify-end gap-3">
                    <button wire:click="$set('showDeleteModal', false)" class="px-4 py-2 text-sm text-gray-300 hover:text-white transition-colors">Cancel</button>
                    <button wire:click="executeDelete" class="bg-red-600 hover:bg-red-700 text-white font-bold px-4 py-2 rounded-md transition-colors">Delete</button>
                </div>
            </div>
        </div>
    @endif
</div>
