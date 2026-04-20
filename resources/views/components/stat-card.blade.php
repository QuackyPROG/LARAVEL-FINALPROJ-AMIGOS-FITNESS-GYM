@props([
    'title' => '',
    'value' => 0,
    'icon' => 'squares-2x2',
    'color' => 'green',
    'percentage' => null,
    'trend' => 'neutral', // 'up', 'down', 'neutral'
])

@php
$colorClasses = match($color) {
    'green' => 'border-emerald-400 text-emerald-400 shadow-emerald-500/20',
    'red' => 'border-red-500 text-red-500 shadow-red-500/20',
    'cyan' => 'border-cyan-400 text-cyan-400 shadow-cyan-500/20',
    'purple' => 'border-purple-400 text-purple-400 shadow-purple-500/20',
    default => 'border-gray-500 text-gray-400 shadow-gray-500/20'
};

$percentageColor = match($trend) {
    'up' => 'text-emerald-400',
    'down' => 'text-red-500',
    'neutral' => 'text-gray-400',
    default => 'text-gray-400'
};

$arrow = match($trend) {
    'up' => '↑',
    'down' => '↓',
    'neutral' => '—',
    default => '—'
};
@endphp

<div class="stat-card {{ $colorClasses }}">
    <!-- Left Section: Title and Value -->
    <div class="flex-1">
        <p class="stat-card-title">{{ $title }}</p>
        <p class="stat-card-value">{{ $value }}</p>
    </div>

    <!-- Right Section: Icon and Percentage -->
    <div class="stat-card-right">
        <svg class="stat-card-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            @switch($icon)
                @case('people')
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.856-1.487M15 10a3 3 0 11-6 0 3 3 0 016 0zM16 20a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    @break
                @case('activity')
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                    @break
                @case('alert')
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4v2m0-14a9 9 0 110 18 9 9 0 010-18z"></path>
                    @break
                @case('calendar')
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    @break
                @default
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            @endswitch
        </svg>

        @if($percentage !== null)
            <p class="stat-card-percentage {{ $percentageColor }}">
                {{ $arrow }} {{ abs($percentage) }}%
            </p>
        @endif
    </div>
</div>
