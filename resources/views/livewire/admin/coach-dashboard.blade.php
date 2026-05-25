<div>
    @php
        $dayLabels = [
            0 => 'Sunday',
            1 => 'Monday',
            2 => 'Tuesday',
            3 => 'Wednesday',
            4 => 'Thursday',
            5 => 'Friday',
            6 => 'Saturday',
        ];

        $badgeStyles = [
            'text-purple-400 border-purple-400/50 bg-purple-400/10',
            'text-blue-400 border-blue-400/50 bg-blue-400/10',
            'text-green-400 border-green-400/50 bg-green-400/10',
            'text-orange-400 border-orange-400/50 bg-orange-400/10',
            'text-pink-400 border-pink-400/50 bg-pink-400/10',
            'text-teal-400 border-teal-400/50 bg-teal-400/10',
            'text-red-400 border-red-400/50 bg-red-400/10',
            'text-amber-400 border-amber-400/50 bg-amber-400/10',
            'text-cyan-400 border-cyan-400/50 bg-cyan-400/10',
            'text-lime-400 border-lime-400/50 bg-lime-400/10',
        ];
    @endphp

    {{-- Back link --}}
    <div class="mb-6">
        <a href="{{ route('admin.coaches.index') }}"
           class="inline-flex items-center gap-2 text-sm text-zinc-400 hover:text-amber-400 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Back to Coaches
        </a>
    </div>

    {{-- ── Header Card ── --}}
    <div class="relative mb-8 overflow-hidden rounded-2xl border border-white/10 bg-white/5 backdrop-blur-md p-6 shadow-xl">
        <div class="absolute inset-0 bg-gradient-to-br from-amber-500/5 via-transparent to-transparent pointer-events-none"></div>
        <div class="relative flex flex-col sm:flex-row items-center sm:items-start gap-6">

            {{-- Avatar --}}
            <div class="flex-shrink-0">
                <div class="w-24 h-24 rounded-full overflow-hidden border-2 border-amber-500/40 bg-amber-500/20 flex items-center justify-center shadow-[0_0_20px_rgba(251,191,36,0.15)]">
                    @if($coach->photo)
                        <img src="{{ asset('storage/'.$coach->photo) }}" alt="{{ $coach->name }}" class="w-full h-full object-cover">
                    @else
                        <span class="text-amber-400 font-bold text-3xl">{{ strtoupper(substr($coach->name, 0, 1)) }}</span>
                    @endif
                </div>
            </div>

            {{-- Info --}}
            <div class="flex-1 min-w-0 text-center sm:text-left">
                <h1 class="text-3xl font-extrabold text-white mb-1">{{ $coach->name }}</h1>

                @if($coach->bio)
                    <p class="text-sm text-zinc-400 leading-relaxed mb-3 max-w-2xl">{{ $coach->bio }}</p>
                @endif

                @if($coach->specializations && count($coach->specializations) > 0)
                    <div class="flex flex-wrap gap-1.5 justify-center sm:justify-start">
                        @foreach($coach->specializations as $s)
                            @php
                                $colorIndex = abs(crc32(strtolower(trim($s)))) % count($badgeStyles);
                                $styleClass = $badgeStyles[$colorIndex];
                            @endphp
                            <span class="inline-flex items-center px-2.5 py-1 text-[11px] font-bold tracking-wide uppercase border rounded-md {{ $styleClass }}">
                                {{ $s }}
                            </span>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- ── Stats Row ── --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
        @php
            $statCards = [
                [
                    'label' => 'Total Bookings',
                    'value' => $stats['total_bookings'],
                    'icon'  => 'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z',
                    'color' => 'text-amber-400 bg-amber-400/10 border-amber-400/20',
                ],
                [
                    'label' => 'Confirmed',
                    'value' => $stats['confirmed_bookings'],
                    'icon'  => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z',
                    'color' => 'text-green-400 bg-green-400/10 border-green-400/20',
                ],
                [
                    'label' => 'Cancelled',
                    'value' => $stats['cancelled_bookings'],
                    'icon'  => 'M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z',
                    'color' => 'text-red-400 bg-red-400/10 border-red-400/20',
                ],
                [
                    'label' => 'Unique Members',
                    'value' => $stats['unique_members'],
                    'icon'  => 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z',
                    'color' => 'text-blue-400 bg-blue-400/10 border-blue-400/20',
                ],
            ];
        @endphp

        @foreach($statCards as $card)
            <div class="rounded-2xl border bg-white/5 backdrop-blur-md p-5 shadow-lg {{ $card['color'] }} border-opacity-30">
                <div class="flex items-center gap-3 mb-3">
                    <div class="p-2.5 rounded-xl border {{ $card['color'] }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="{{ $card['icon'] }}"/>
                        </svg>
                    </div>
                    <span class="text-xs font-semibold text-zinc-400 uppercase tracking-wider">{{ $card['label'] }}</span>
                </div>
                <div class="text-4xl font-extrabold text-white">{{ number_format($card['value']) }}</div>
            </div>
        @endforeach
    </div>

    {{-- ── Class Schedules ── --}}
    <div class="mb-8">
        <h2 class="text-xl font-bold text-white mb-4 flex items-center gap-2">
            <svg class="w-5 h-5 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>
            Class Schedules
        </h2>

        @if($schedules->isEmpty())
            <div class="rounded-xl border border-white/10 bg-white/5 p-8 text-center text-zinc-500">
                <svg class="w-10 h-10 mx-auto mb-3 opacity-40" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                <p class="text-sm">No class schedules assigned to this coach.</p>
            </div>
        @else
            <div>
                <table class="w-full text-sm">
                    <thead>
                        <tr class="text-left">
                            <th class="px-4 py-3 text-xs font-semibold text-zinc-400 uppercase tracking-wider">Day</th>
                            <th class="px-4 py-3 text-xs font-semibold text-zinc-400 uppercase tracking-wider">Time</th>
                            <th class="px-4 py-3 text-xs font-semibold text-zinc-400 uppercase tracking-wider">Class Name</th>
                            <th class="px-4 py-3 text-xs font-semibold text-zinc-400 uppercase tracking-wider">Capacity</th>
                            <th class="px-4 py-3 text-xs font-semibold text-zinc-400 uppercase tracking-wider">Recurring</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($schedules as $schedule)
                            <tr>
                                <td class="px-4 py-3 text-white font-medium">
                                    {{ $dayLabels[$schedule->day_of_week] ?? 'Unknown' }}
                                </td>
                                <td class="px-4 py-3 text-zinc-300">
                                    {{ \Carbon\Carbon::parse($schedule->time)->format('g:i A') }}
                                </td>
                                <td class="px-4 py-3 text-zinc-300">{{ $schedule->name }}</td>
                                <td class="px-4 py-3 text-zinc-300">{{ $schedule->capacity }}</td>
                                <td class="px-4 py-3">
                                    @if($schedule->is_recurring)
                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 text-[11px] font-bold rounded-md text-green-400 bg-green-400/10 border border-green-400/20">
                                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                            </svg>
                                            Yes
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-0.5 text-[11px] font-bold rounded-md text-zinc-500 bg-white/5 border border-white/10">
                                            No
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

    {{-- ── Bookings / Attendance ── --}}
    <div>
        <h2 class="text-xl font-bold text-white mb-4 flex items-center gap-2">
            <svg class="w-5 h-5 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
            </svg>
            Bookings &amp; Attendance
        </h2>

        @if($bookings->isEmpty())
            <div class="rounded-xl border border-white/10 bg-white/5 p-8 text-center text-zinc-500">
                <svg class="w-10 h-10 mx-auto mb-3 opacity-40" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
                <p class="text-sm">No bookings recorded for this coach yet.</p>
            </div>
        @else
            <div>
                <table class="w-full text-sm">
                    <thead>
                        <tr class="text-left">
                            <th class="px-4 py-3 text-xs font-semibold text-zinc-400 uppercase tracking-wider">Member</th>
                            <th class="px-4 py-3 text-xs font-semibold text-zinc-400 uppercase tracking-wider">Scheduled At</th>
                            <th class="px-4 py-3 text-xs font-semibold text-zinc-400 uppercase tracking-wider">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($bookings as $booking)
                            <tr>
                                <td class="px-4 py-3">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-full bg-amber-500/20 border border-amber-500/30 flex items-center justify-center flex-shrink-0">
                                            <span class="text-amber-400 font-bold text-xs">
                                                {{ strtoupper(substr($booking->member?->name ?? '?', 0, 1)) }}
                                            </span>
                                        </div>
                                        <span class="text-white font-medium">{{ $booking->member?->name ?? '—' }}</span>
                                    </div>
                                </td>
                                <td class="px-4 py-3 text-zinc-300">
                                    {{ $booking->scheduled_at->format('M d, Y g:i A') }}
                                </td>
                                <td class="px-4 py-3">
                                    @if($booking->status === 'confirmed')
                                        <span class="inline-flex items-center px-2.5 py-1 text-[11px] font-bold rounded-md text-green-400 bg-green-400/10 border border-green-400/20 uppercase tracking-wide">
                                            Confirmed
                                        </span>
                                    @elseif($booking->status === 'cancelled')
                                        <span class="inline-flex items-center px-2.5 py-1 text-[11px] font-bold rounded-md text-red-400 bg-red-400/10 border border-red-400/20 uppercase tracking-wide">
                                            Cancelled
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-1 text-[11px] font-bold rounded-md text-zinc-400 bg-white/5 border border-white/10 uppercase tracking-wide">
                                            {{ $booking->status }}
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            @if($bookings->hasPages())
                <div class="mt-4 px-4">
                    {{ $bookings->links() }}
                </div>
            @endif
        @endif
    </div>
</div>
