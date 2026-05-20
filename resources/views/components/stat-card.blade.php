@props([
    'title' => '',
    'value' => 0,
    'icon' => 'squares-2x2',
    'color' => 'gold',
    'percentage' => null,
    'trend' => 'neutral', // 'up', 'down', 'neutral'
])

@php
$colorClasses = match($color) {
    'gold'   => 'border-amber-400/20 text-amber-400',
    'green'  => 'border-emerald-400/20 text-emerald-400',
    'red'    => 'border-red-500/20 text-red-400',
    'cyan'   => 'border-cyan-400/20 text-cyan-400',
    'purple' => 'border-purple-400/20 text-purple-400',
    default  => 'border-zinc-700 text-zinc-400'
};

$percentageColor = match($trend) {
    'up'      => 'text-emerald-400',
    'down'    => 'text-red-400',
    'neutral' => 'text-zinc-500',
    default   => 'text-zinc-500'
};

$arrow = match($trend) {
    'up'    => '↑',
    'down'  => '↓',
    default => '—'
};
@endphp

<div class="relative rounded-xl p-5 border backdrop-blur-sm flex items-stretch justify-between gap-5 bg-[#0a0a0a] transition-all duration-200 hover:bg-[#0e0e0e] {{ $colorClasses }}">
    {{-- Left: Title + Value --}}
    <div class="flex-1">
        <p class="text-[10px] font-bold uppercase tracking-widest text-zinc-500 mb-2">{{ $title }}</p>
        <p class="text-3xl font-black tracking-tight text-white leading-none">{{ $value }}</p>
    </div>

    {{-- Right: Icon + Trend --}}
    <div class="flex flex-col items-center justify-center gap-1.5 min-w-[48px]">
        <svg class="w-7 h-7 opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            @switch($icon)
                @case('people')
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                    @break
                @case('activity')
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                    @break
                @case('alert')
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    @break
                @case('calendar')
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    @break
                @default
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            @endswitch
        </svg>

        @if($percentage !== null)
            <p class="text-xs font-semibold {{ $percentageColor }}">
                {{ $arrow }} {{ abs($percentage) }}%
            </p>
        @endif
    </div>
</div>
