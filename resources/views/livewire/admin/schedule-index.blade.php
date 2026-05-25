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

            <button wire:click="$toggle('showAllMembers')"
                class="px-4 py-2 text-sm font-semibold rounded-xl border transition-all duration-200 {{ $showAllMembers ? 'bg-amber-500/20 border-amber-500/50 text-amber-300 hover:bg-amber-500/30' : 'bg-white/5 border-white/10 text-gray-300 hover:bg-white/10 hover:text-white' }}">
                {{ $showAllMembers ? 'Hide Members' : 'All Members' }}
            </button>

            <button wire:click="openCreate" title="Add Class" class="bg-gradient-to-r from-amber-400 to-yellow-500 hover:from-amber-500 hover:to-yellow-600 transition-all duration-300 text-black font-bold p-2 rounded-xl shadow-[0_0_15px_rgba(251,191,36,0.2)] hover:shadow-[0_0_20px_rgba(251,191,36,0.4)] flex items-center justify-center transform hover:-translate-y-0.5 aspect-square">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" /></svg>
            </button>
        </div>
    </div>

    @if($showForm)
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 p-4">
        <div class="w-full max-w-2xl bg-[#111111] rounded-2xl border border-white/10 overflow-hidden shadow-[0_24px_64px_rgba(0,0,0,0.8)]">
            <div class="h-[2px] bg-gradient-to-r from-transparent via-amber-500 to-transparent"></div>
            <div class="p-8 max-h-[90vh] overflow-y-auto">
                <div class="flex items-start justify-between mb-8">
                    <div>
                        <h2 class="text-lg font-semibold text-white tracking-tight">{{ $editingId ? 'Edit Class' : 'Add Class' }}</h2>
                        <p class="text-xs text-gray-500 mt-1">{{ $editingId ? 'Update class schedule' : 'Create a new recurring class' }}</p>
                    </div>
                    <button wire:click="$set('showForm', false)" class="text-gray-600 hover:text-gray-300 transition-colors p-1.5 rounded-lg hover:bg-white/5 ml-4 shrink-0">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <form wire:submit="save">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                        {{-- Left column --}}
                        <div class="space-y-5">
                            <div>
                                <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1.5">Event Name</label>
                                <input type="text" wire:model="name" placeholder="e.g. Morning Yoga"
                                    class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-amber-500/50 focus:border-amber-500/50 focus:bg-white/10 backdrop-blur-md transition-all shadow-inner" required>
                                @error('name') <span class="text-xs text-red-400 mt-1 block">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Day of Week</label>
                                <div class="flex flex-wrap gap-1.5">
                                    @foreach([1 => 'Mon', 2 => 'Tue', 3 => 'Wed', 4 => 'Thu', 5 => 'Fri', 6 => 'Sat', 0 => 'Sun'] as $dayVal => $dayShort)
                                        <button type="button"
                                            wire:click="$set('dayOfWeek', {{ $dayVal }})"
                                            class="px-3 py-1.5 text-xs font-bold rounded-lg transition-all
                                                {{ $dayOfWeek == $dayVal
                                                    ? 'bg-amber-500 text-black'
                                                    : 'bg-white/5 border border-white/10 text-gray-300 hover:bg-white/10' }}">
                                            {{ $dayShort }}
                                        </button>
                                    @endforeach
                                </div>
                                @error('dayOfWeek') <span class="text-xs text-red-400 mt-1 block">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1.5">Start Time</label>
                                <input type="time" wire:model="time" style="color-scheme: dark;"
                                    class="w-full bg-white/5 border border-white/10 text-white rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-amber-500/50 focus:border-amber-500/50 transition-all shadow-inner cursor-pointer" required>
                                @error('time') <span class="text-xs text-red-400 mt-1 block">{{ $message }}</span> @enderror
                            </div>

                            <div class="flex items-center gap-3 bg-white/5 border border-white/10 rounded-xl px-4 py-3">
                                <input type="checkbox" wire:model="isRecurring" id="recur" class="rounded border-white/20">
                                <label for="recur" class="text-sm text-gray-300 cursor-pointer flex-1">Recurring weekly</label>
                            </div>
                        </div>

                        {{-- Right column --}}
                        <div class="space-y-5">
                            <div x-data="{
                                    open: false,
                                    selectedName: '{{ $coaches->firstWhere('id', $coachId)?->name ?? 'No coach' }}',
                                    selectCoach(id, name) {
                                        $wire.set('coachId', id);
                                        this.selectedName = name;
                                        this.open = false;
                                    }
                                 }"
                                 @click.outside="open = false"
                                 class="relative" wire:ignore.self>
                                <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1.5">Coach (Optional)</label>
                                <button @click="open = !open" type="button"
                                    class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white flex justify-between items-center focus:outline-none focus:ring-2 focus:ring-amber-500/50 backdrop-blur-md transition-all shadow-inner"
                                    :class="{'ring-2 ring-amber-500/50 border-amber-500/50': open}">
                                    <span x-text="selectedName" class="text-sm font-medium text-gray-300"></span>
                                    <svg class="w-4 h-4 text-gray-400 transition-transform duration-300" :class="{'rotate-180 text-amber-400': open}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                </button>
                                <div x-show="open"
                                     x-transition:enter="transition ease-out duration-200"
                                     x-transition:enter-start="opacity-0 -translate-y-2"
                                     x-transition:enter-end="opacity-100 translate-y-0"
                                     x-transition:leave="transition ease-in duration-150"
                                     x-transition:leave-start="opacity-100 translate-y-0"
                                     x-transition:leave-end="opacity-0 -translate-y-2"
                                     style="display:none;"
                                     class="absolute z-50 w-full mt-2 bg-[#000009]/95 backdrop-blur-xl border border-white/10 rounded-xl shadow-[0_15px_50px_rgba(0,0,0,0.7)] overflow-hidden">
                                    <div class="p-1 max-h-48 overflow-y-auto">
                                        <button type="button" @click="selectCoach(null, 'No coach')" class="w-full text-left px-4 py-2.5 text-sm text-gray-300 hover:bg-white/10 hover:text-white rounded-lg transition-colors">No coach</button>
                                        @foreach($coaches as $coach)
                                            <button type="button" @click="selectCoach({{ $coach->id }}, '{{ addslashes($coach->name) }}')" class="w-full text-left px-4 py-2.5 text-sm text-gray-300 hover:bg-white/10 hover:text-white rounded-lg transition-colors">{{ $coach->name }}</button>
                                        @endforeach
                                    </div>
                                </div>
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1.5">Capacity</label>
                                <div class="flex items-center gap-0 bg-white/5 border border-white/10 rounded-xl overflow-hidden">
                                    <button type="button"
                                        wire:click="$set('capacity', {{ max(1, $capacity - 1) }})"
                                        class="px-4 py-3 text-lg font-bold text-gray-300 bg-white/5 border-r border-white/10 hover:bg-white/10 hover:text-amber-400 transition-colors">−</button>
                                    <span class="flex-1 text-center text-white font-bold text-lg py-3">{{ $capacity }}</span>
                                    <button type="button"
                                        wire:click="$set('capacity', {{ $capacity + 1 }})"
                                        class="px-4 py-3 text-lg font-bold text-gray-300 bg-white/5 border-l border-white/10 hover:bg-white/10 hover:text-amber-400 transition-colors">+</button>
                                </div>
                                @error('capacity') <span class="text-xs text-red-400 mt-1 block">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>

                    <div class="flex gap-3 mt-8 pt-6 border-t border-white/10">
                        <button type="button" wire:click="$set('showForm', false)" class="flex-1 px-4 py-2.5 text-sm font-medium text-gray-400 hover:text-white bg-white/5 hover:bg-white/10 border border-white/10 rounded-xl transition-colors">Cancel</button>
                        <button type="submit" class="flex-[2] px-4 py-2.5 text-sm font-semibold bg-amber-500 hover:bg-amber-400 text-black rounded-xl transition-colors">{{ $editingId ? 'Update Class' : 'Add Class' }}</button>
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

                <th class="text-center text-xs font-bold text-gray-200 uppercase tracking-[0.15em] py-5 px-4">
                    Enrolled
                </th>

                <th class="text-center text-xs font-bold text-gray-200 uppercase tracking-[0.15em] py-5 px-4">
                    Available
                </th>

                <th class="text-center text-xs font-bold text-gray-200 uppercase tracking-[0.15em] py-5 px-4">
                    Revenue
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

                {{-- ENROLLED --}}
                <td class="py-5 px-4 text-center">
                    <span class="font-bold text-amber-300">
                        {{ $s->enrolled_count }}
                    </span>
                </td>

                {{-- AVAILABLE --}}
                <td class="py-5 px-4 text-center">
                    @php $available = max(0, $s->capacity - $s->enrolled_count); @endphp
                    <span class="font-bold {{ $available === 0 ? 'text-red-400' : 'text-green-400' }}">
                        {{ $available }}
                    </span>
                </td>

                {{-- REVENUE --}}
                <td class="py-5 px-4 text-center">
                    <span class="text-xs text-gray-500 font-medium">Included</span>
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
                <td colspan="9" class="py-12 text-center text-gray-400">

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

@if($showAllMembers && $activeMembers)
<div class="mt-6 bg-white/5 border border-white/10 rounded-xl p-4">
    <h2 class="text-gray-300 font-semibold mb-3 flex items-center gap-2">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-amber-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
        </svg>
        All Active Members
        <span class="ml-1 text-xs font-normal text-gray-500">({{ $activeMembers->count() }})</span>
    </h2>
    <table class="w-full text-sm text-gray-300">
        <thead>
            <tr class="border-b border-white/10 text-xs text-gray-500 uppercase tracking-wider">
                <th class="text-left py-2 px-3">Name</th>
                <th class="text-left py-2 px-3">Plan</th>
                <th class="text-left py-2 px-3">Expires</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-white/5">
            @forelse($activeMembers as $member)
            <tr class="hover:bg-white/5 transition-colors">
                <td class="py-2.5 px-3 font-medium text-gray-200">{{ $member->name }}</td>
                <td class="py-2.5 px-3 text-gray-400">{{ $member->activeMembership?->plan?->name ?? '—' }}</td>
                <td class="py-2.5 px-3 text-gray-400">
                    @if($member->activeMembership?->expires_at)
                        {{ $member->activeMembership->expires_at->format('M d, Y') }}
                    @else
                        —
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="3" class="py-6 text-center text-gray-500">No active members found.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endif

@if($schedules instanceof \Illuminate\Contracts\Pagination\Paginator && $schedules->hasPages())
<div class="fixed bottom-0 right-0 z-50 w-full md:w-[calc(100%-16rem)] py-3 px-4 bg-[#0a0a0a]/90 backdrop-blur-xl border-t border-white/10 shadow-[0_-10px_30px_rgba(0,0,0,0.5)] flex justify-center items-center">
    {{ $schedules->links('components.custom-pagination') }}
</div>
@endif

@if($showDeleteModal && $selectedSchedule)
    <div class="fixed inset-0 z-[60] flex items-center justify-center bg-black/60 p-4">
        <div class="w-full max-w-sm bg-[#111111] rounded-2xl border border-white/10 overflow-hidden shadow-[0_24px_48px_rgba(0,0,0,0.7)]">
            <div class="h-[2px] bg-red-500/80"></div>
            <div class="p-6">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-9 h-9 rounded-xl bg-red-500/10 border border-red-500/20 flex items-center justify-center shrink-0">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-red-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                    </div>
                    <h2 class="text-base font-semibold text-white">Delete Class</h2>
                </div>
                <p class="text-sm text-gray-400 mb-6 leading-relaxed">Permanently delete <strong class="text-gray-200">{{ $selectedSchedule->name }}</strong>? This action cannot be undone.</p>
                <div class="flex gap-3">
                    <button wire:click="$set('showDeleteModal', false)" class="flex-1 px-4 py-2.5 text-sm font-medium text-gray-400 hover:text-white bg-white/5 hover:bg-white/10 border border-white/10 rounded-xl transition-colors">Cancel</button>
                    <button wire:click="executeDelete" class="flex-1 px-4 py-2.5 text-sm font-semibold bg-red-600 hover:bg-red-500 text-white rounded-xl transition-colors">Delete</button>
                </div>
            </div>
        </div>
    </div>
@endif
</div>
