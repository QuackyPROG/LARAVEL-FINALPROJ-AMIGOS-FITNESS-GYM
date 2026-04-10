<div>
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-xl font-semibold text-gray-900">Class Schedule</h1>
            <p class="text-sm text-gray-500 mt-0.5">Manage weekly recurring classes and their schedules</p>
        </div>
        <button wire:click="openCreate" class="bg-gray-900 text-white text-sm px-4 py-2 rounded-md">+ Add Class</button>
    </div>

    @if(session('success'))<div class="bg-green-50 border border-green-200 text-green-700 text-sm px-4 py-3 rounded-md mb-4">{{ session('success') }}</div>@endif

    @if($showForm)
    <div class="bg-white border border-gray-200 rounded-md p-5 mb-4">
        <h2 class="text-sm font-semibold text-gray-700 mb-4">{{ $editingId ? 'Edit' : 'New' }} Class</h2>
        <div class="grid grid-cols-2 gap-4">
            <div class="flex flex-col gap-1">
                <label class="text-sm font-medium text-gray-700">Class Name</label>
                <input type="text" wire:model="name" class="border border-gray-300 rounded-md px-3 py-2 text-sm w-full">
                @error('name')<p class="text-xs text-red-500">{{ $message }}</p>@enderror
            </div>
            <div class="flex flex-col gap-1">
                <label class="text-sm font-medium text-gray-700">Coach</label>
                <select wire:model="coachId" class="border border-gray-300 rounded-md px-3 py-2 text-sm w-full">
                    <option value="">No coach</option>
                    @foreach($coaches as $coach)<option value="{{ $coach->id }}">{{ $coach->name }}</option>@endforeach
                </select>
            </div>
            <div class="flex flex-col gap-1">
                <label class="text-sm font-medium text-gray-700">Day</label>
                <select wire:model="dayOfWeek" class="border border-gray-300 rounded-md px-3 py-2 text-sm w-full">
                    @foreach(['Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday'] as $day)
                        <option>{{ $day }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex flex-col gap-1">
                <label class="text-sm font-medium text-gray-700">Time</label>
                <input type="time" wire:model="time" class="border border-gray-300 rounded-md px-3 py-2 text-sm w-full">
            </div>
            <div class="flex flex-col gap-1">
                <label class="text-sm font-medium text-gray-700">Capacity</label>
                <input type="number" wire:model="capacity" min="1" class="border border-gray-300 rounded-md px-3 py-2 text-sm w-full">
            </div>
            <div class="flex items-center gap-2 mt-5">
                <input type="checkbox" wire:model="isRecurring" id="recur">
                <label for="recur" class="text-sm text-gray-600">Recurring weekly</label>
            </div>
        </div>
        <div class="flex gap-3 mt-4">
            <button wire:click="save" class="bg-gray-900 text-white text-sm px-4 py-2 rounded-md">Save</button>
            <button wire:click="$set('showForm', false)" class="border border-gray-300 text-gray-700 text-sm px-4 py-2 rounded-md">Cancel</button>
        </div>
    </div>
    @endif

    <div class="bg-white border border-gray-200 rounded-md overflow-hidden">
        <table class="w-full text-sm">
            <thead class="border-b border-gray-200 bg-gray-50">
                <tr>
                    <th class="text-left text-xs font-medium text-gray-500 uppercase tracking-wide py-3 px-4">Class</th>
                    <th class="text-left text-xs font-medium text-gray-500 uppercase tracking-wide py-3 px-4">Day</th>
                    <th class="text-left text-xs font-medium text-gray-500 uppercase tracking-wide py-3 px-4">Time</th>
                    <th class="text-left text-xs font-medium text-gray-500 uppercase tracking-wide py-3 px-4">Coach</th>
                    <th class="text-left text-xs font-medium text-gray-500 uppercase tracking-wide py-3 px-4">Cap</th>
                    <th class="text-left text-xs font-medium text-gray-500 uppercase tracking-wide py-3 px-4">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($schedules as $s)
                <tr class="border-b border-gray-100">
                    <td class="py-3 px-4 font-medium text-gray-900">{{ $s->name }}</td>
                    <td class="py-3 px-4 text-gray-600">{{ $s->day_of_week }}</td>
                    <td class="py-3 px-4 text-gray-600">{{ $s->time }}</td>
                    <td class="py-3 px-4 text-gray-500">{{ $s->coach?->name ?? '—' }}</td>
                    <td class="py-3 px-4 text-gray-500">{{ $s->capacity }}</td>
                    <td class="py-3 px-4"><div class="flex gap-2">
                        <button wire:click="openEdit({{ $s->id }})" class="text-sm text-gray-600 underline">Edit</button>
                        <button wire:click="delete({{ $s->id }})" wire:confirm="Delete?" class="border border-red-200 text-red-600 text-xs px-2 py-1 rounded">Delete</button>
                    </div></td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="py-8 text-center text-gray-400">
                        <p>No classes yet</p>
                        <p class="text-xs mt-0.5">Add your first class to build the weekly schedule</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
