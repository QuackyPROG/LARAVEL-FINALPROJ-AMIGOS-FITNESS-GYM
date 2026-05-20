<div>
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-white mb-2">Events</h1>
        <p class="text-gray-300">Upcoming gym events, challenges, and community activities</p>
    </div>

    <div class="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-3">
        @forelse($events as $event)
        <article class="bg-white/5 backdrop-blur-md border border-white/10 rounded-xl shadow-xl overflow-hidden transition-all duration-300 hover:border-amber-500/30 hover:-translate-y-0.5 hover:shadow-[0_8px_30px_rgba(0,0,0,0.4)] group">
            @if($event->cover_image)
                <div class="relative overflow-hidden h-44">
                    <img src="{{ Storage::url($event->cover_image) }}" alt="{{ $event->title }}" class="h-full w-full object-cover opacity-90 transition group-hover:scale-105 group-hover:opacity-100">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>
                </div>
            @else
                <div class="h-44 flex items-center justify-center bg-white/5 border-b border-white/10 overflow-hidden relative">
                    <span class="text-6xl font-black text-white/10 group-hover:text-white/15 transition">{{ strtoupper(substr($event->title,0,1)) }}</span>
                </div>
            @endif
            <div class="p-5">
                <p class="font-semibold text-white">{{ $event->title }}</p>
                <span class="mt-2 inline-flex items-center gap-1.5 px-2 py-1 rounded-md text-xs font-semibold bg-amber-500/10 text-amber-400 border border-amber-500/20">
                    <svg class="h-3 w-3 opacity-70" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3M4 11h16M5 5h14a1 1 0 011 1v13a2 2 0 01-2 2H6a2 2 0 01-2-2V6a1 1 0 011-1z"/></svg>
                    {{ $event->date->format('F j, Y \a\t g:i A') }}
                </span>
                @if($event->description)
                    <p class="mt-3 text-sm leading-6 text-gray-400">{{ $event->description }}</p>
                @endif
            </div>
        </article>
        @empty
        <div class="md:col-span-2 lg:col-span-3 py-16 text-center">
            <svg class="h-12 w-12 text-white/20 mb-4 mx-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 9v7.5"/></svg>
            <p class="font-medium text-gray-300">No upcoming events</p>
            <p class="text-xs mt-1 text-gray-500">Check back soon for upcoming gym events and activities.</p>
        </div>
        @endforelse
    </div>
</div>
