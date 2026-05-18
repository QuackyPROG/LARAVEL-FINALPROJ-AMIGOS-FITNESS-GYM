<div class="mx-auto max-w-7xl space-y-6">
    <section class="relative overflow-hidden rounded-lg bg-[#080808] px-5 py-6 ring-1 ring-amber-400/10 sm:px-7">
        <div class="absolute inset-y-0 right-0 hidden w-1/2 bg-[url('/images/gym-bg.png')] bg-cover bg-center opacity-15 mix-blend-luminosity lg:block"></div>
        <div class="absolute inset-0 bg-[radial-gradient(circle_at_85%_15%,rgba(251,191,36,0.14),transparent_34%)]"></div>
        <div class="relative">
            <span class="inline-flex rounded-full border border-amber-400/25 px-3 py-1 text-[11px] font-bold uppercase tracking-[0.16em] text-amber-400">
                Gym Community
            </span>
            <h1 class="mt-4 text-4xl font-black uppercase leading-none text-white sm:text-5xl">Events</h1>
            <p class="mt-3 max-w-2xl text-sm leading-6 text-zinc-400">Upcoming gym events, challenges, and member activities.</p>
        </div>
    </section>

    <section class="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-3">
        @forelse($events as $event)
        <article class="overflow-hidden rounded-lg bg-[#0b0b0b] ring-1 ring-white/10 transition hover:ring-amber-400/25">
            @if($event->cover_image)
                <img src="{{ Storage::url($event->cover_image) }}" alt="{{ $event->title }}" class="h-40 w-full object-cover opacity-90">
            @else
                <div class="flex h-40 w-full items-center justify-center bg-[linear-gradient(135deg,#111_0%,#050505_100%)]">
                    <span class="text-5xl font-black text-amber-400/25">{{ strtoupper(substr($event->title,0,1)) }}</span>
                </div>
            @endif
            <div class="p-5">
                <p class="text-lg font-black uppercase text-white">{{ $event->title }}</p>
                <p class="mt-2 inline-flex rounded-full border border-amber-400/20 bg-amber-400/10 px-3 py-1 text-xs font-bold text-amber-300">{{ $event->date->format('F j, Y \a\t g:i A') }}</p>
                @if($event->description)<p class="mt-3 text-sm leading-6 text-zinc-400">{{ $event->description }}</p>@endif
            </div>
        </article>
        @empty
        <div class="rounded-lg border border-dashed border-zinc-800 bg-zinc-950/50 p-8 text-center md:col-span-2 lg:col-span-3">
            <p class="text-sm font-semibold text-zinc-300">No upcoming events</p>
            <p class="mt-1 text-xs text-zinc-600">Check back soon for upcoming gym events and activities.</p>
        </div>
        @endforelse
    </section>
</div>
