<div>
    <div>
        <div>
            <h1>Events</h1>
            <p>Manage gym events visible to members</p>
        </div>
        <button wire:click="openCreate">+ New Event</button>
    </div>

    @if(session('success'))<div>{{ session('success') }}</div>@endif

    @if($showForm)
    <div>
        <h2>{{ $editingId ? 'Edit' : 'New' }} Event</h2>
        <div>
            <div><label>Title</label>
                <input type="text" wire:model="title">
                @error('title')<p>{{ $message }}</p>@enderror
            </div>
            <div><label>Date &amp; Time</label>
                <input type="datetime-local" wire:model="date">
                @error('date')<p>{{ $message }}</p>@enderror
            </div>
            <div><label>Description</label>
                <textarea wire:model="description" rows="3"></textarea>
            </div>
            <div><label>Cover Image</label>
                <input type="file" wire:model="coverImage">
            </div>
            <div>
                <input type="checkbox" wire:model="isVisible" id="vis">
                <label for="vis">Visible to members</label>
            </div>
        </div>
        <div>
            <button wire:click="save">Save</button>
            <button wire:click="$set('showForm', false)">Cancel</button>
        </div>
    </div>
    @endif

    <div>
        @forelse($events as $event)
        <div>
            <div>
                <div>
                    <p>{{ $event->title }}</p>
                    <p>{{ $event->date->format('M j, Y \a\t g:i A') }}</p>
                    @if($event->description)
                        <p>{{ $event->description }}</p>
                    @endif
                </div>
                <div>
                    @if($event->is_visible)
                        <span>Visible</span>
                    @else
                        <span>Hidden</span>
                    @endif
                    <button wire:click="openEdit({{ $event->id }})">Edit</button>
                    <button wire:click="toggleVisible({{ $event->id }})">Toggle</button>
                </div>
            </div>
        </div>
        @empty
        <div>
            <p>No events yet</p>
            <p>Create your first event to share with members</p>
        </div>
        @endforelse
    </div>
</div>
