<div>
    <div class="mb-6 flex justify-between items-start">
        <div>
            <h1 class="text-3xl font-bold text-white mb-2">Events</h1>
            <p class="text-gray-300">Manage gym events visible to members</p>
        </div>
        <button wire:click="openCreate" class="bg-gradient-to-r from-amber-400 to-yellow-500 hover:from-amber-500 hover:to-yellow-600 transition-colors text-black font-bold text-xs px-2.5 py-1 rounded shadow-sm mt-2">+ New Event</button>
    </div>

    @if(session('success'))<div class="bg-green-900/20 border border-green-700 text-green-300 text-sm px-4 py-3 rounded-md mb-4">{{ session('success') }}</div>@endif

    @if($showForm)
    <div class="bg-dark-card border border-gray-600 rounded-md p-5 mb-4">
        <h2 class="text-sm font-semibold text-white mb-4">{{ $editingId ? 'Edit' : 'New' }} Event</h2>
        <div class="grid grid-cols-2 gap-4">
            <div class="flex flex-col gap-1">
                <label class="text-sm font-medium text-gray-300">Title</label>
                <input type="text" wire:model="title" class="border border-gray-600 rounded-md px-3 py-2 text-sm w-full bg-dark-page text-white placeholder-gray-400">
                @error('title')<p class="text-xs text-red-400">{{ $message }}</p>@enderror
            </div>
            <div class="flex flex-col gap-1">
                <label class="text-sm font-medium text-gray-300">Date &amp; Time</label>
                <input type="datetime-local" wire:model="date" class="border border-gray-600 rounded-md px-3 py-2 text-sm w-full bg-dark-page text-white">
                @error('date')<p class="text-xs text-red-400">{{ $message }}</p>@enderror
            </div>
            <div class="col-span-2 flex flex-col gap-1">
                <label class="text-sm font-medium text-gray-300">Description</label>
                <textarea wire:model="description" rows="3" class="border border-gray-600 rounded-md px-3 py-2 text-sm w-full bg-dark-page text-white placeholder-gray-400"></textarea>
            </div>
            <div class="flex flex-col gap-1">
                <label class="text-sm font-medium text-gray-300">Cover Image</label>
                <input type="file" wire:model="coverImage" class="border border-gray-600 rounded-md px-3 py-2 text-sm w-full bg-dark-page text-white">
            </div>
            <div class="flex items-center gap-2 mt-5">
                <input type="checkbox" wire:model="isVisible" id="vis" class="rounded">
                <label for="vis" class="text-sm text-gray-300">Visible to members</label>
            </div>
        </div>
        <div class="flex gap-3 mt-4">
            <button wire:click="save" class="bg-black hover:bg-gray-800 transition-colors text-white text-sm px-4 py-2 rounded-md">Save</button>
            <button wire:click="$set('showForm', false)" class="border border-gray-600 text-gray-300 hover:bg-gray-700 transition-colors text-sm px-4 py-2 rounded-md">Cancel</button>
        </div>
    </div>
    @endif

    <div class="space-y-3">
        @forelse($events as $event)
        <div class="bg-dark-card border border-gray-600 rounded-md p-4">
            <div class="flex items-start justify-between">
                <div>
                    <p class="font-medium text-white">{{ $event->title }}</p>
                    <p class="text-sm text-gray-400 mt-0.5">{{ $event->date->format('M j, Y \a\t g:i A') }}</p>
                    @if($event->description)
                        <p class="text-sm text-gray-400 mt-1">{{ $event->description }}</p>
                    @endif
                </div>
                <div class="flex items-center gap-2 flex-shrink-0">
                    @if($event->is_visible)
                        <span class="inline-flex px-2 py-0.5 rounded text-xs font-medium bg-green-900/20 text-green-300 border border-green-700">Visible</span>
                    @else
                        <span class="inline-flex px-2 py-0.5 rounded text-xs font-medium bg-gray-700 text-gray-300 border border-gray-600">Hidden</span>
                    @endif
                    <button wire:click="openEdit({{ $event->id }})" class="text-sm text-gray-300 underline hover:text-white transition-colors">Edit</button>
                    <button wire:click="toggleVisible({{ $event->id }})" class="text-sm text-gray-300 underline hover:text-white transition-colors">Toggle</button>
                </div>
            </div>
        </div>
        @empty
        <div class="bg-dark-card border border-gray-600 rounded-md p-8 text-center">
            <p class="text-sm text-gray-400">No events yet</p>
            <p class="text-xs text-gray-400 mt-0.5">Create your first event to share with members</p>
        </div>
        @endforelse
    </div>
</div>
