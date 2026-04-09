<div>
    <div>
        <div>
            <h1>Coaches</h1>
            <p>Manage coaching staff and their specializations</p>
        </div>
        <button wire:click="openCreate">+ Add Coach</button>
    </div>

    @if(session('success'))<div>{{ session('success') }}</div>@endif

    @if($showForm)
    <div>
        <h2>{{ $editingId ? 'Edit' : 'New' }} Coach</h2>
        <div>
            <div>
                <label>Name</label>
                <input type="text" wire:model="name">
                @error('name')<p>{{ $message }}</p>@enderror
            </div>
            <div>
                <label>Photo (optional)</label>
                <input type="file" wire:model="photo">
            </div>
            <div>
                <label>Bio</label>
                <textarea wire:model="bio" rows="3"></textarea>
            </div>
            <div>
                <label>Specializations (one per line)</label>
                <textarea wire:model="specializationsRaw" rows="3"></textarea>
            </div>
        </div>
        <div>
            <button wire:click="save">Save</button>
            <button wire:click="$set('showForm', false)">Cancel</button>
        </div>
    </div>
    @endif

    <div>
        @forelse($coaches as $coach)
        <div>
            <div>
                <div>
                    <p>{{ $coach->name }}</p>
                    <p>{{ $coach->bookings_count }} bookings</p>
                    @if($coach->specializations)
                        <div>
                            @foreach($coach->specializations as $s)
                                <span>{{ $s }}</span>
                            @endforeach
                        </div>
                    @endif
                </div>
                <div>
                    <button wire:click="openEdit({{ $coach->id }})">Edit</button>
                    <button wire:click="delete({{ $coach->id }})" wire:confirm="Delete this coach?">Delete</button>
                </div>
            </div>
        </div>
        @empty
        <div>
            <p>No coaches yet</p>
            <p>Add your first coach to enable session bookings</p>
        </div>
        @endforelse
    </div>
</div>
