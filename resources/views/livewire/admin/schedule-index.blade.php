<div>
    <div>
        <div>
            <h1>Class Schedule</h1>
            <p>Manage weekly recurring classes and their schedules</p>
        </div>
        <button wire:click="openCreate">+ Add Class</button>
    </div>

    @if(session('success'))<div>{{ session('success') }}</div>@endif

    @if($showForm)
    <div>
        <h2>{{ $editingId ? 'Edit' : 'New' }} Class</h2>
        <div>
            <div><label>Class Name</label>
                <input type="text" wire:model="name">
                @error('name')<p>{{ $message }}</p>@enderror
            </div>
            <div><label>Coach</label>
                <select wire:model="coachId">
                    <option value="">No coach</option>
                    @foreach($coaches as $coach)<option value="{{ $coach->id }}">{{ $coach->name }}</option>@endforeach
                </select>
            </div>
            <div><label>Day</label>
                <select wire:model="dayOfWeek">
                    @foreach(['Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday'] as $day)
                        <option>{{ $day }}</option>
                    @endforeach
                </select>
            </div>
            <div><label>Time</label>
                <input type="time" wire:model="time">
            </div>
            <div><label>Capacity</label>
                <input type="number" wire:model="capacity" min="1">
            </div>
            <div>
                <input type="checkbox" wire:model="isRecurring" id="recur">
                <label for="recur">Recurring weekly</label>
            </div>
        </div>
        <div>
            <button wire:click="save">Save</button>
            <button wire:click="$set('showForm', false)">Cancel</button>
        </div>
    </div>
    @endif

    <div>
        <table>
            <thead>
                <tr>
                    <th>Class</th>
                    <th>Day</th>
                    <th>Time</th>
                    <th>Coach</th>
                    <th>Cap</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($schedules as $s)
                <tr>
                    <td>{{ $s->name }}</td>
                    <td>{{ $s->day_of_week }}</td>
                    <td>{{ $s->time }}</td>
                    <td>{{ $s->coach?->name ?? '—' }}</td>
                    <td>{{ $s->capacity }}</td>
                    <td><div>
                        <button wire:click="openEdit({{ $s->id }})">Edit</button>
                        <button wire:click="delete({{ $s->id }})" wire:confirm="Delete?">Delete</button>
                    </div></td>
                </tr>
                @empty
                <tr>
                    <td colspan="6">
                        <p>No classes yet</p>
                        <p>Add your first class to build the weekly schedule</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
