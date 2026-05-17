<div class="pb-24">
    <x-admin-splash target="save, executeDelete" />
    <div class="mb-6 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h1 class="text-3xl font-bold text-white mb-2">Class Schedule</h1>
            <p class="text-gray-300">Manage weekly recurring classes and their schedules</p>
        </div>
        <div class="flex flex-wrap items-center gap-3">
            <style>
                .glass-scrollbar::-webkit-scrollbar { width: 6px; }
                .glass-scrollbar::-webkit-scrollbar-track { background: transparent; }
                .glass-scrollbar::-webkit-scrollbar-thumb { background: rgba(255, 255, 255, 0.2); border-radius: 10px; }
                .glass-scrollbar::-webkit-scrollbar-thumb:hover { background: rgba(255, 255, 255, 0.3); }
            </style>

            <div class="relative group">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none z-10">
                    <svg class="h-4 w-4 text-gray-500 group-hover:text-amber-500/50 transition-colors duration-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
                <input type="text" wire:model.live.debounce.300ms="search" placeholder="Search classes..." 
                    class="bg-white/5 border border-white/10 hover:border-amber-500/40 rounded-xl pl-9 pr-4 py-2 text-sm text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-amber-500/50 backdrop-blur-md transition-all duration-300 w-48 shadow-inner">
            </div>

            <div x-data="{ open: false }" @click.outside="open = false" class="relative w-40 z-40" wire:ignore.self>
                <button @click="open = !open" type="button" 
                    class="w-full bg-white/5 hover:bg-white/10 border border-white/10 rounded-xl px-4 py-2 text-sm text-gray-200 flex justify-between items-center focus:outline-none focus:ring-2 focus:ring-amber-500/50 backdrop-blur-md transition-all shadow-inner"
                    :class="{ 'ring-2 ring-amber-500/50 bg-white/10 border-amber-500/50': open }">
                    <div class="flex items-center gap-2.5 font-medium truncate">
                        @if($sort === 'name_asc')
                            <span>Name (A-Z)</span>
                        @elseif($sort === 'name_desc')
                            <span>Name (Z-A)</span>
                        @elseif($sort === 'capacity_asc')
                            <span>Capacity (Asc)</span>
                        @elseif($sort === 'capacity_desc')
                            <span>Capacity (Desc)</span>
                        @endif
                    </div>
                    <svg class="w-4 h-4 text-gray-500 transition-transform duration-300 shrink-0 ml-2" :class="{'rotate-180 text-amber-400': open}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                </button>
                <div x-show="open" 
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="transform opacity-0 -translate-y-2"
                     x-transition:enter-end="transform opacity-100 translate-y-0"
                     x-transition:leave="transition ease-in duration-150"
                     x-transition:leave-start="transform opacity-100 translate-y-0"
                     x-transition:leave-end="transform opacity-0 -translate-y-2"
                     style="display: none;"
                     class="absolute right-0 mt-2 w-full bg-[#000009]/95 backdrop-blur-xl border border-white/10 rounded-xl shadow-[0_15px_40px_rgba(0,0,0,0.8)] overflow-hidden">
                    <div class="p-1 flex flex-col">
                        <button wire:click="$set('sort', 'name_asc')" @click="open = false" type="button" class="flex items-center gap-3 w-full text-left px-3 py-2.5 text-sm font-medium text-gray-300 hover:text-white hover:bg-white/10 rounded-lg transition-colors {{ $sort === 'name_asc' ? 'bg-white/10 text-white' : '' }}">
                            Name (A-Z)
                        </button>
                        <button wire:click="$set('sort', 'name_desc')" @click="open = false" type="button" class="flex items-center gap-3 w-full text-left px-3 py-2.5 text-sm font-medium text-gray-300 hover:text-white hover:bg-white/10 rounded-lg transition-colors {{ $sort === 'name_desc' ? 'bg-white/10 text-white' : '' }}">
                            Name (Z-A)
                        </button>
                        <button wire:click="$set('sort', 'capacity_asc')" @click="open = false" type="button" class="flex items-center gap-3 w-full text-left px-3 py-2.5 text-sm font-medium text-gray-300 hover:text-white hover:bg-white/10 rounded-lg transition-colors {{ $sort === 'capacity_asc' ? 'bg-white/10 text-white' : '' }}">
                            Capacity (Asc)
                        </button>
                        <button wire:click="$set('sort', 'capacity_desc')" @click="open = false" type="button" class="flex items-center gap-3 w-full text-left px-3 py-2.5 text-sm font-medium text-gray-300 hover:text-white hover:bg-white/10 rounded-lg transition-colors {{ $sort === 'capacity_desc' ? 'bg-white/10 text-white' : '' }}">
                            Capacity (Desc)
                        </button>
                    </div>
                </div>
            </div>
            
            <div x-data="{ open: false }" @click.outside="open = false" class="relative w-48 z-40" wire:ignore.self>
                <button @click="open = !open" type="button" 
                    class="w-full bg-white/5 hover:bg-white/10 border border-white/10 rounded-xl px-4 py-2 text-sm text-gray-200 flex justify-between items-center focus:outline-none focus:ring-2 focus:ring-amber-500/50 backdrop-blur-md transition-all shadow-inner"
                    :class="{ 'ring-2 ring-amber-500/50 bg-white/10 border-amber-500/50': open }">
                    <div class="flex items-center gap-2.5 font-medium truncate">
                        @if($coachFilter === '')
                            <span>All Coaches</span>
                        @else
                            <span>{{ $coaches->firstWhere('id', $coachFilter)?->name ?? 'All Coaches' }}</span>
                        @endif
                    </div>
                    <svg class="w-4 h-4 text-gray-500 transition-transform duration-300 shrink-0 ml-2" :class="{'rotate-180 text-amber-400': open}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                </button>
                <div x-show="open" 
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="transform opacity-0 -translate-y-2"
                     x-transition:enter-end="transform opacity-100 translate-y-0"
                     x-transition:leave="transition ease-in duration-150"
                     x-transition:leave-start="transform opacity-100 translate-y-0"
                     x-transition:leave-end="transform opacity-0 -translate-y-2"
                     style="display: none;"
                     class="absolute right-0 mt-2 w-full max-h-64 overflow-y-auto glass-scrollbar bg-[#000009]/95 backdrop-blur-xl border border-white/10 rounded-xl shadow-[0_15px_40px_rgba(0,0,0,0.8)]">
                    <div class="p-1 flex flex-col">
                        <button wire:click="$set('coachFilter', '')" @click="open = false" type="button" class="flex items-center gap-3 w-full text-left px-3 py-2.5 text-sm font-medium text-gray-300 hover:text-white hover:bg-white/10 rounded-lg transition-colors {{ $coachFilter === '' ? 'bg-white/10 text-white' : '' }}">
                            All Coaches
                        </button>
                        @foreach($coaches as $coach)
                        <button wire:click="$set('coachFilter', '{{ $coach->id }}')" @click="open = false" type="button" class="flex items-center gap-3 w-full text-left px-3 py-2.5 text-sm font-medium text-gray-300 hover:text-white hover:bg-white/10 rounded-lg transition-colors {{ $coachFilter == $coach->id ? 'bg-white/10 text-white' : '' }}">
                            {{ $coach->name }}
                        </button>
                        @endforeach
                    </div>
                </div>
            </div>

            <div x-data="{ open: false }" @click.outside="open = false" class="relative w-32 z-40" wire:ignore.self>
                <button @click="open = !open" type="button" 
                    class="w-full bg-white/5 hover:bg-white/10 border border-white/10 rounded-xl px-4 py-2 text-sm text-gray-200 flex justify-between items-center focus:outline-none focus:ring-2 focus:ring-amber-500/50 backdrop-blur-md transition-all shadow-inner"
                    :class="{ 'ring-2 ring-amber-500/50 bg-white/10 border-amber-500/50': open }">
                    <div class="flex items-center gap-2.5 font-medium">
                        @if($dayFilter === '')
                            <span>All Days</span>
                        @else
                            <span>{{ $this->dayOptions()[$dayFilter] ?? 'All Days' }}</span>
                        @endif
                    </div>
                    <svg class="w-4 h-4 text-gray-500 transition-transform duration-300 shrink-0 ml-2" :class="{'rotate-180 text-amber-400': open}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                </button>
                <div x-show="open" 
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="transform opacity-0 -translate-y-2"
                     x-transition:enter-end="transform opacity-100 translate-y-0"
                     x-transition:leave="transition ease-in duration-150"
                     x-transition:leave-start="transform opacity-100 translate-y-0"
                     x-transition:leave-end="transform opacity-0 -translate-y-2"
                     style="display: none;"
                     class="absolute right-0 mt-2 w-full max-h-64 overflow-y-auto glass-scrollbar bg-[#000009]/95 backdrop-blur-xl border border-white/10 rounded-xl shadow-[0_15px_40px_rgba(0,0,0,0.8)]">
                    <div class="p-1 flex flex-col">
                        <button wire:click="$set('dayFilter', '')" @click="open = false" type="button" class="flex items-center gap-3 w-full text-left px-3 py-2.5 text-sm font-medium text-gray-300 hover:text-white hover:bg-white/10 rounded-lg transition-colors {{ $dayFilter === '' ? 'bg-white/10 text-white' : '' }}">
                            All Days
                        </button>
                        @foreach($this->dayOptions() as $val => $label)
                        <button wire:click="$set('dayFilter', '{{ $val }}')" @click="open = false" type="button" class="flex items-center gap-3 w-full text-left px-3 py-2.5 text-sm font-medium text-gray-300 hover:text-white hover:bg-white/10 rounded-lg transition-colors {{ $dayFilter === (string)$val ? 'bg-white/10 text-white' : '' }}">
                            {{ $label }}
                        </button>
                        @endforeach
                    </div>
                </div>
            </div>

            <button wire:click="openCreate" title="Add Class" class="bg-gradient-to-r from-amber-400 to-yellow-500 hover:from-amber-500 hover:to-yellow-600 transition-all duration-300 text-black font-bold p-2 rounded-xl shadow-[0_0_15px_rgba(251,191,36,0.2)] hover:shadow-[0_0_20px_rgba(251,191,36,0.4)] flex items-center justify-center transform hover:-translate-y-0.5 aspect-square">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" /></svg>
            </button>
        </div>
    </div>

    @if($showForm)
    <style>
        .gold-gradient-bg {
            background-size: 200% 200%;
            animation: pan-gradient 4s ease infinite;
        }
        @keyframes pan-gradient {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }
    </style>
    
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/70 backdrop-blur-sm p-4">
        <div class="relative w-full max-w-md mx-auto group">
            <div class="absolute -inset-[1.5px] bg-gradient-to-r from-orange-300 via-orange-600 to-orange-400 rounded-2xl gold-gradient-bg opacity-80 blur-[2px] transition-opacity duration-500"></div>
            
            <div class="relative bg-[#000000] rounded-2xl shadow-[0_0_40px_rgba(0,0,0,0.5)] p-8 w-full">
                
                <div class="flex flex-col items-center justify-center mb-8 text-center">
                    <div class="bg-gradient-to-br from-orange-400/20 to-orange-600/20 border border-orange-500/30 text-orange-400 p-3.5 rounded-full mb-4 shadow-[0_0_20px_rgba(234,88,12,0.15)]">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C6.596 6.253 2 10.849 2 16.5S6.596 26.747 12 26.747s10-4.596 10-10.247S17.404 6.253 12 6.253z" />
                        </svg>
                    </div>
                    <h2 class="text-2xl font-extrabold text-white tracking-wide">{{ $editingId ? 'Edit Class' : 'Add Class' }}</h2>
                    <p class="text-sm text-gray-400 mt-1.5">{{ $editingId ? 'Update class schedule' : 'Create a new recurring class' }}</p>
                </div>
                
                <form wire:submit="save" class="space-y-5">
                    
                    <div>
                        <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1.5 ml-1">Class Name</label>
                        <input type="text" wire:model="name" placeholder="e.g. Morning Yoga"
                            class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-orange-500/50 focus:border-orange-500/50 focus:bg-white/10 backdrop-blur-md transition-all shadow-inner" required>
                        @error('name') <span class="text-xs text-red-400 mt-1 ml-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1.5 ml-1">Day</label>
                            <select wire:model="dayOfWeek" class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white focus:outline-none focus:ring-2 focus:ring-orange-500/50 focus:border-orange-500/50 focus:bg-white/10 backdrop-blur-md transition-all shadow-inner">
                                @foreach($this->dayOptions() as $dayValue => $dayLabel)
                                    <option value="{{ $dayValue }}" class="bg-dark-page text-white">{{ $dayLabel }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1.5 ml-1">Time</label>
                            <input type="time" wire:model="time" style="color-scheme: dark;"
                                class="w-full bg-[#0a0a0a] hover:bg-[#111111] border border-white/10 hover:border-amber-500/50 rounded-xl px-4 py-3 text-white focus:outline-none focus:ring-2 focus:ring-amber-500/50 focus:border-amber-500/50 transition-all shadow-inner cursor-pointer" required>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1.5 ml-1">Coach (Optional)</label>
                            <select wire:model="coachId" class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white focus:outline-none focus:ring-2 focus:ring-orange-500/50 focus:border-orange-500/50 focus:bg-white/10 backdrop-blur-md transition-all shadow-inner">
                                <option value="" class="bg-dark-page text-white">No coach</option>
                                @foreach($coaches as $coach)<option value="{{ $coach->id }}" class="bg-dark-page text-white">{{ $coach->name }}</option>@endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1.5 ml-1">Capacity</label>
                            <input type="number" wire:model="capacity" min="1" placeholder="20"
                                class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-orange-500/50 focus:border-orange-500/50 focus:bg-white/10 backdrop-blur-md transition-all shadow-inner" required>
                        </div>
                    </div>

                    <div class="flex items-center gap-3 bg-white/5 border border-white/10 rounded-xl px-4 py-3">
                        <input type="checkbox" wire:model="isRecurring" id="recur" class="rounded border-white/20">
                        <label for="recur" class="text-sm text-gray-300 cursor-pointer flex-1">Recurring weekly</label>
                    </div>
                    
                    <div class="flex gap-3 mt-8 pt-6 border-t border-white/10">
                        <button type="button" wire:click="$set('showForm', false)" class="flex-1 px-4 py-3 text-sm font-semibold text-gray-300 bg-white/5 hover:bg-white/10 border border-white/10 rounded-xl transition-all">Cancel</button>
                        <button type="submit" class="flex-[2] bg-gradient-to-r from-orange-400 to-orange-600 hover:from-orange-500 hover:to-orange-700 text-white font-bold px-4 py-3 rounded-xl shadow-[0_0_20px_rgba(234,88,12,0.3)] hover:shadow-[0_0_25px_rgba(234,88,12,0.5)] transition-all transform hover:-translate-y-0.5">{{ $editingId ? 'Update Class' : 'Add Class' }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif

<div class="bg-[#1a1a1a]/95 backdrop-blur-md border border-white/10 rounded-2xl shadow-2xl overflow-visible">
    
    <table class="w-full text-sm border-separate border-spacing-0">

        {{-- TABLE HEADER --}}
        <thead class="border-b border-white/10 bg-white/[0.04]">
            <tr>

                <th class="text-left text-xs font-bold text-gray-200 uppercase tracking-[0.15em] py-5 px-6">
                    Class
                </th>

                <th class="text-center text-xs font-bold text-gray-200 uppercase tracking-[0.15em] py-5 px-4">
                    Day
                </th>

                <th class="text-center text-xs font-bold text-gray-200 uppercase tracking-[0.15em] py-5 px-4">
                    Time
                </th>

                <th class="text-center text-xs font-bold text-gray-200 uppercase tracking-[0.15em] py-5 px-4">
                    Coach
                </th>

                <th class="text-center text-xs font-bold text-gray-200 uppercase tracking-[0.15em] py-5 px-4">
                    Capacity
                </th>

                <th class="text-center text-xs font-bold text-gray-200 uppercase tracking-[0.15em] py-5 px-6">
                    Actions
                </th>

            </tr>
        </thead>

        {{-- TABLE BODY --}}
        <tbody class="divide-y divide-white/10">

            @forelse($schedules as $s)

            <tr class="hover:bg-white/[0.03] transition-all duration-200 group">

                {{-- CLASS --}}
                <td class="py-5 px-6">
                    <div class="font-bold text-white tracking-wide">
                        {{ $s->name }}
                    </div>
                </td>

                {{-- DAY --}}
                <td class="py-5 px-4 text-center">
                    <span class="font-semibold text-gray-300">
                        {{ $this->dayLabel((int) $s->day_of_week) }}
                    </span>
                </td>

                {{-- TIME --}}
                <td class="py-5 px-4 text-center">
                    <span class="font-semibold text-gray-300">
                        {{ $s->time }}
                    </span>
                </td>

                {{-- COACH --}}
                <td class="py-5 px-4 text-center">
                    <span class="font-bold text-gray-200">
                        {{ $s->coach?->name ?? '—' }}
                    </span>
                </td>

                {{-- CAPACITY --}}
                <td class="py-5 px-4 text-center">
                    <span class="font-bold text-gray-200">
                        {{ $s->capacity }}
                    </span>
                </td>

                {{-- ACTIONS --}}
                <td class="py-5 px-6">

                    <div class="flex items-center justify-center">

                        <div class="relative group/action">

                            {{-- ACTION BUTTON --}}
                            <button
                                class="flex items-center justify-center w-10 h-10 text-gray-400 hover:text-white transition-all duration-200 rounded-xl border border-transparent hover:border-white/20 hover:bg-white/10"
                                title="More actions"
                            >
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                    <circle cx="3" cy="10" r="1.5" fill="currentColor"/>
                                    <circle cx="10" cy="10" r="1.5" fill="currentColor"/>
                                    <circle cx="17" cy="10" r="1.5" fill="currentColor"/>
                                </svg>
                            </button>

                            {{-- DROPDOWN --}}
                            <div class="absolute right-0 top-12 w-44 bg-[#111111]/95 backdrop-blur-xl border border-white/10 rounded-2xl shadow-[0_15px_40px_rgba(0,0,0,0.45)] opacity-0 invisible group-hover/action:opacity-100 group-hover/action:visible transition-all duration-200 z-[9999] overflow-visible">

                                <div class="p-2">

                                    {{-- EDIT --}}
                                    <button
                                        wire:click="openEdit({{ $s->id }})"
                                        class="w-full text-left px-4 py-3 text-sm text-gray-300 hover:bg-white/10 hover:text-amber-400 transition-all rounded-xl flex items-center gap-3 font-semibold"
                                    >
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                            <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"/>
                                        </svg>

                                        <span class="font-bold">
                                            Edit
                                        </span>
                                    </button>

                                    {{-- DELETE --}}
                                    <button
                                        wire:click="confirmDelete({{ $s->id }})"
                                        class="w-full text-left px-4 py-3 text-sm text-red-400 hover:bg-red-500/20 hover:text-red-300 transition-all rounded-xl flex items-center gap-3 font-semibold"
                                    >
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                        </svg>

                                        <span class="font-bold">
                                            Delete
                                        </span>
                                    </button>

                                </div>

                            </div>

                        </div>

                    </div>

                </td>

            </tr>

            @empty

            <tr>
                <td colspan="6" class="py-12 text-center text-gray-400">

                    <p class="font-bold text-gray-300">
                        No classes yet
                    </p>

                    <p class="text-xs mt-1 text-gray-500">
                        Add your first class to build the weekly schedule
                    </p>

                </td>
            </tr>

            @endforelse

        </tbody>

    </table>

</div>

@if($schedules instanceof \Illuminate\Contracts\Pagination\Paginator && $schedules->hasPages())
<div class="fixed bottom-0 right-0 z-50 w-full md:w-[calc(100%-16rem)] py-3 px-4 bg-[#0a0a0a]/90 backdrop-blur-xl border-t border-white/10 shadow-[0_-10px_30px_rgba(0,0,0,0.5)] flex justify-center items-center">
    {{ $schedules->links('components.custom-pagination') }}
</div>
@endif

@if($showDeleteModal && $selectedSchedule)
    <div class="fixed inset-0 z-[60] flex items-center justify-center p-4 bg-black/60 backdrop-blur-md">
        <div class="w-full max-w-sm p-6 mx-4 shadow-2xl bg-[#1a1a1a] border border-red-500/50 rounded-2xl relative overflow-hidden">
            <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-red-600 to-red-400"></div>
            <div class="flex items-center gap-4 mb-4">
                <div class="p-3 rounded-full bg-red-900/30 text-red-400 border border-red-500/20">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                </div>
                <h2 class="text-xl font-bold text-white">Delete Class</h2>
            </div>
            <p class="mb-8 text-sm text-[#a0a0a0]">Are you sure you want to permanently delete <strong class="text-white">{{ $selectedSchedule->name }}</strong>? This action cannot be undone.</p>
            <div class="flex justify-end gap-3">
                <button wire:click="$set('showDeleteModal', false)" class="px-5 py-2.5 text-sm font-semibold text-[#a0a0a0] transition-colors border border-[#404040] rounded-lg hover:bg-[#252525] hover:text-white">Cancel</button>
                <button wire:click="executeDelete" class="px-5 py-2.5 text-sm font-bold text-white transition-colors bg-red-600 rounded-lg hover:bg-red-700 shadow-lg shadow-red-600/20">Delete Class</button>
            </div>
        </div>
    </div>
@endif
</div>
