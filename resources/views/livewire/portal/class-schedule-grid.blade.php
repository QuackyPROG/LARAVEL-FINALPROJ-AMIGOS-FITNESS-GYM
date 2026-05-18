<div class="mx-auto max-w-7xl space-y-6">
    <section class="relative overflow-hidden rounded-lg bg-[#080808] px-5 py-6 ring-1 ring-amber-400/10 sm:px-7">
        <div class="absolute inset-0 bg-[radial-gradient(circle_at_85%_15%,rgba(251,191,36,0.14),transparent_34%)]"></div>
        <div class="relative">
            <span class="inline-flex rounded-full border border-amber-400/25 px-3 py-1 text-[11px] font-bold uppercase tracking-[0.16em] text-amber-400">
                Weekly Training
            </span>
            <h1 class="mt-4 text-4xl font-black uppercase leading-none text-white sm:text-5xl">Class Schedule</h1>
            <p class="mt-3 max-w-2xl text-sm leading-6 text-zinc-400">Plan your week around recurring group classes, coach-led sessions, and capacity limits.</p>
        </div>
    </section>

    <div class="grid grid-cols-1 gap-4 md:grid-cols-2 xl:grid-cols-4">
        @foreach($days as $dayValue => $dayLabel)
        <section class="overflow-hidden rounded-lg bg-[#0b0b0b] ring-1 ring-white/10">
            <div class="border-b border-amber-400/10 bg-zinc-950/80 px-4 py-3">
                <p class="text-[11px] font-black uppercase tracking-[0.16em] text-amber-400">{{ $dayLabel }}</p>
            </div>
            <div class="divide-y divide-zinc-900">
                @forelse($schedules->get($dayValue, collect()) as $class)
                <div class="px-4 py-4 transition hover:bg-amber-400/5">
                    <p class="text-sm font-bold text-white">{{ $class->name }}</p>
                    <div class="mt-2 flex flex-wrap gap-2">
                        <span class="inline-flex rounded-full border border-amber-400/20 bg-amber-400/10 px-2.5 py-1 text-xs font-bold text-amber-300">{{ $class->time }}</span>
                        <span class="inline-flex rounded-full border border-zinc-800 bg-zinc-950 px-2.5 py-1 text-xs font-semibold text-zinc-400">Cap: {{ $class->capacity }}</span>
                    </div>
                    @if($class->coach)<p class="mt-2 text-xs font-medium text-zinc-500">{{ $class->coach->name }}</p>@endif
                </div>
                @empty
                <div class="px-4 py-8 text-center">
                    <p class="text-xs font-semibold uppercase tracking-[0.14em] text-zinc-600">No classes</p>
                </div>
                @endforelse
            </div>
        </section>
        @endforeach
    </div>
</div>