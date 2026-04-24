<div>
    <div class="mb-6 flex justify-between items-start">
        <div>
            <h1 class="text-3xl font-bold text-white mb-2">Class Schedule</h1>
            <p class="text-gray-300">Manage weekly recurring classes and their schedules</p>
        </div>
        <button wire:click="openCreate" class="bg-gradient-to-r from-amber-400 to-yellow-500 hover:from-amber-500 hover:to-yellow-600 transition-colors text-black font-bold text-xs px-2.5 py-1 rounded shadow-sm mt-2">+ Add Class</button>
    </div>

    @if(session('success'))<div class="bg-green-900/20 border border-green-700 text-green-300 text-sm px-4 py-3 rounded-md mb-4">{{ session('success') }}</div>@endif

    @if($showForm)
    <div class="bg-dark-card border border-gray-600 rounded-md p-5 mb-4">
        <h2 class="text-sm font-semibold text-white mb-4">{{ $editingId ? 'Edit' : 'New' }} Class</h2>
        <div class="grid grid-cols-2 gap-4">
            <div class="flex flex-col gap-1">
                <label class="text-sm font-medium text-gray-300">Class Name</label>
                <input type="text" wire:model="name" class="border border-gray-600 rounded-md px-3 py-2 text-sm w-full bg-dark-page text-white placeholder-gray-400">
                @error('name')<p class="text-xs text-red-400">{{ $message }}</p>@enderror
            </div>
            <div class="flex flex-col gap-1">
                <label class="text-sm font-medium text-gray-300">Coach</label>
                <select wire:model="coachId" class="border border-gray-600 rounded-md px-3 py-2 text-sm w-full bg-dark-page text-white">
                    <option value="" class="bg-dark-page text-white">No coach</option>
                    @foreach($coaches as $coach)<option value="{{ $coach->id }}" class="bg-dark-page text-white">{{ $coach->name }}</option>@endforeach
                </select>
            </div>
            <div class="flex flex-col gap-1">
                <label class="text-sm font-medium text-gray-300">Day</label>
                <select wire:model="dayOfWeek" class="border border-gray-600 rounded-md px-3 py-2 text-sm w-full bg-dark-page text-white">
                    @foreach($this->dayOptions() as $dayValue => $dayLabel)
                        <option value="{{ $dayValue }}" class="bg-dark-page text-white">{{ $dayLabel }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex flex-col gap-1">
                <label class="text-sm font-medium text-gray-300">Time</label>
                <input type="time" wire:model="time" class="border border-gray-600 rounded-md px-3 py-2 text-sm w-full bg-dark-page text-white">
            </div>
            <div class="flex flex-col gap-1">
                <label class="text-sm font-medium text-gray-300">Capacity</label>
                <input type="number" wire:model="capacity" min="1" class="border border-gray-600 rounded-md px-3 py-2 text-sm w-full bg-dark-page text-white">
            </div>
            <div class="flex items-center gap-2 mt-5">
                <input type="checkbox" wire:model="isRecurring" id="recur" class="rounded">
                <label for="recur" class="text-sm text-gray-300">Recurring weekly</label>
            </div>
        </div>
        <div class="flex gap-3 mt-4">
            <button wire:click="save" class="bg-black hover:bg-gray-800 transition-colors text-white text-sm px-4 py-2 rounded-md">Save</button>
            <button wire:click="$set('showForm', false)" class="border border-gray-600 text-gray-300 hover:bg-gray-700 transition-colors text-sm px-4 py-2 rounded-md">Cancel</button>
        </div>
    </div>
    @endif

    <div class="bg-dark-card border border-gray-600 rounded-md overflow-hidden">
        <table class="w-full text-sm">
            <thead class="border-b border-gray-600 bg-dark-card">
                <tr>
                    <th class="text-left text-xs font-medium text-gray-300 uppercase tracking-wide py-3 px-4">Class</th>
                    <th class="text-left text-xs font-medium text-gray-300 uppercase tracking-wide py-3 px-4">Day</th>
                    <th class="text-left text-xs font-medium text-gray-300 uppercase tracking-wide py-3 px-4">Time</th>
                    <th class="text-left text-xs font-medium text-gray-300 uppercase tracking-wide py-3 px-4">Coach</th>
                    <th class="text-left text-xs font-medium text-gray-300 uppercase tracking-wide py-3 px-4">Cap</th>
                    <th class="text-left text-xs font-medium text-gray-300 uppercase tracking-wide py-3 px-4">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-dark-card">
                @forelse($schedules as $s)
                <tr class="border-b border-gray-600 hover:bg-gray-700 transition-colors">
                    <td class="py-3 px-4 font-medium text-white">{{ $s->name }}</td>
                    <td class="py-3 px-4 text-gray-300">{{ $this->dayLabel((int) $s->day_of_week) }}</td>
                    <td class="py-3 px-4 text-gray-300">{{ $s->time }}</td>
                    <td class="py-3 px-4 text-gray-400">{{ $s->coach?->name ?? '—' }}</td>
                    <td class="py-3 px-4 text-gray-400">{{ $s->capacity }}</td>
                    <td class="py-3 px-4"><div class="flex gap-2">
                        <button wire:click="openEdit({{ $s->id }})" class="text-sm text-gray-300 underline hover:text-white transition-colors">Edit</button>
                        <button wire:click="delete({{ $s->id }})" wire:confirm="Delete?" class="border border-red-700 text-red-400 text-xs px-2 py-1 rounded hover:bg-red-900/20 transition-colors">Delete</button>
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
