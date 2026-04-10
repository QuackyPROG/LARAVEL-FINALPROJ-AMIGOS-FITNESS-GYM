<div>
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-xl font-semibold text-gray-900">Events</h1>
            <p class="text-sm text-gray-500 mt-0.5">Manage gym events visible to members</p>
        </div>
        <button wire:click="openCreate" class="bg-gray-900 text-white text-sm px-4 py-2 rounded-md">+ New Event</button>
    </div>

    @if(session('success'))<div class="bg-green-50 border border-green-200 text-green-700 text-sm px-4 py-3 rounded-md mb-4">{{ session('success') }}</div>@endif

    @if($showForm)
    <div class="bg-white border border-gray-200 rounded-md p-5 mb-4">
        <h2 class="text-sm font-semibold text-gray-700 mb-4">{{ $editingId ? 'Edit' : 'New' }} Event</h2>
        <div class="grid grid-cols-2 gap-4">
            <div class="flex flex-col gap-1">
                <label class="text-sm font-medium text-gray-700">Title</label>
                <input type="text" wire:model="title" class="border border-gray-300 rounded-md px-3 py-2 text-sm w-full">
                @error('title')<p class="text-xs text-red-500">{{ $message }}</p>@enderror
            </div>
            <div class="flex flex-col gap-1">
                <label class="text-sm font-medium text-gray-700">Date &amp; Time</label>
                <input type="datetime-local" wire:model="date" class="border border-gray-300 rounded-md px-3 py-2 text-sm w-full">
                @error('date')<p class="text-xs text-red-500">{{ $message }}</p>@enderror
            </div>
            <div class="col-span-2 flex flex-col gap-1">
                <label class="text-sm font-medium text-gray-700">Description</label>
                <textarea wire:model="description" rows="3" class="border border-gray-300 rounded-md px-3 py-2 text-sm w-full"></textarea>
            </div>
            <div class="flex flex-col gap-1">
                <label class="text-sm font-medium text-gray-700">Cover Image</label>
                <input type="file" wire:model="coverImage" class="border border-gray-300 rounded-md px-3 py-2 text-sm w-full">
            </div>
            <div class="flex items-center gap-2 mt-5">
                <input type="checkbox" wire:model="isVisible" id="vis">
                <label for="vis" class="text-sm text-gray-600">Visible to members</label>
            </div>
        </div>
        <div class="flex gap-3 mt-4">
            <button wire:click="save" class="bg-gray-900 text-white text-sm px-4 py-2 rounded-md">Save</button>
            <button wire:click="$set('showForm', false)" class="border border-gray-300 text-gray-700 text-sm px-4 py-2 rounded-md">Cancel</button>
        </div>
    </div>
    @endif

    <div class="space-y-3">
        @forelse($events as $event)
        <div class="bg-white border border-gray-200 rounded-md p-4">
            <div class="flex items-start justify-between">
                <div>
                    <p class="font-medium text-gray-900">{{ $event->title }}</p>
                    <p class="text-sm text-gray-500 mt-0.5">{{ $event->date->format('M j, Y \a\t g:i A') }}</p>
                    @if($event->description)
                        <p class="text-sm text-gray-400 mt-1">{{ $event->description }}</p>
                    @endif
                </div>
                <div class="flex items-center gap-2 flex-shrink-0">
                    @if($event->is_visible)
                        <span class="inline-flex px-2 py-0.5 rounded text-xs font-medium bg-green-50 text-green-700 border border-green-200">Visible</span>
                    @else
                        <span class="inline-flex px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-500 border border-gray-200">Hidden</span>
                    @endif
                    <button wire:click="openEdit({{ $event->id }})" class="text-sm text-gray-600 underline">Edit</button>
                    <button wire:click="toggleVisible({{ $event->id }})" class="text-sm text-gray-600 underline">Toggle</button>
                </div>
            </div>
        </div>
        @empty
        <div class="bg-white border border-gray-200 rounded-md p-8 text-center">
            <p class="text-sm text-gray-400">No events yet</p>
            <p class="text-xs text-gray-300 mt-0.5">Create your first event to share with members</p>
        </div>
        @endforelse
    </div>
</div>
