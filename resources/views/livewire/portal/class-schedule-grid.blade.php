<div>
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-white mb-2">Class Schedule</h1>
        <p class="text-gray-300">Weekly recurring group classes and coach-led sessions</p>
    </div>

    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">
        @foreach($days as $dayValue => $dayLabel)
        @php $dayClasses = $schedules->get($dayValue, collect()); @endphp
        <div class="bg-white/5 backdrop-blur-md border border-white/10 rounded-xl shadow-xl overflow-hidden">
            <div class="px-4 py-3 border-b border-white/10 bg-white/5 flex items-center justify-between">
                <p class="text-xs font-bold text-amber-400 uppercase tracking-wider">{{ $dayLabel }}</p>
                @if($dayClasses->count())
                    <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-bold bg-amber-400/10 text-amber-400 border border-amber-500/20">{{ $dayClasses->count() }}</span>
                @endif
            </div>
            <div class="divide-y divide-white/5">
                @forelse($dayClasses as $class)
                <div class="px-4 py-4 hover:bg-white/5 transition-colors">
                    <p class="text-sm font-semibold text-white">{{ $class->name }}</p>
                    <div class="mt-2 flex flex-wrap gap-2">
                        @php
                            $timeFormatted = $class->time ? date('g:i A', strtotime($class->time)) : '—';
                        @endphp
                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-xs font-semibold bg-amber-500/10 text-amber-400 border border-amber-500/20">
                            <svg class="h-3 w-3 opacity-70" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                            {{ $timeFormatted }}
                        </span>
                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-xs font-semibold bg-white/5 text-gray-400 border border-white/10">
                            <svg class="h-3 w-3 opacity-60" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0"/></svg>
                            {{ $class->capacity }}
                        </span>
                    </div>
                    @if($class->coach)
                        <p class="mt-2 flex items-center gap-1 text-xs text-gray-400">
                            <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                            {{ $class->coach->name }}
                        </p>
                    @endif
                </div>
                @empty
                <div class="px-4 py-10 text-center">
                    <svg class="h-8 w-8 text-white/20 mb-2 mx-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3M4 11h16M5 5h14a1 1 0 011 1v13a2 2 0 01-2 2H6a2 2 0 01-2-2V6a1 1 0 011-1z"/></svg>
                    <p class="text-xs font-medium text-gray-500">Rest day</p>
                </div>
                @endforelse
            </div>
        </div>
        @endforeach
    </div>
</div>