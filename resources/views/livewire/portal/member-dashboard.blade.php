{{-- Member Dashboard — matches Admin Panel design --}}
<div>
    {{-- Page Header --}}
    <div class="mb-8 flex justify-between items-start">
        <div>
            <h1 class="text-3xl font-bold text-white mb-2">Dashboard</h1>
            <p class="text-gray-300">Welcome back, {{ $user->name }}</p>
        </div>
        @if($membership)
        <div class="text-right">
            <p class="text-xs text-zinc-500 font-medium tracking-wide uppercase">Current Plan</p>
            <p class="text-sm text-zinc-300 font-semibold mt-0.5">{{ $membership->plan?->name ?? 'N/A' }}</p>
        </div>
        @endif
    </div>

    {{-- Membership Status Banner --}}
    @if($membershipStatus === 'expiring_soon')
        <div class="mb-6 flex items-center gap-3 rounded-xl border border-amber-500/30 bg-amber-500/10 px-5 py-4 shadow-[0_0_15px_rgba(245,158,11,0.1)]">
            <svg class="h-5 w-5 shrink-0 text-amber-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
            <p class="text-sm text-amber-300">Your membership expires in <strong>{{ $daysRemaining }} day{{ $daysRemaining === 1 ? '' : 's' }}</strong>. Renew now to keep your access.</p>
            <a href="/register" class="ml-auto shrink-0 bg-gradient-to-r from-amber-400 to-yellow-500 hover:from-amber-500 hover:to-yellow-600 text-black font-bold text-sm px-4 py-1.5 rounded-lg transition-all">Renew</a>
        </div>
    @elseif($membershipStatus === 'expired')
        <div class="mb-6 flex items-center gap-3 rounded-xl border border-red-500/30 bg-red-500/10 px-5 py-4">
            <svg class="h-5 w-5 shrink-0 text-red-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <p class="text-sm text-red-300">Your membership has expired. Renew to regain full access.</p>
            <a href="/register" class="ml-auto shrink-0 bg-gradient-to-r from-amber-400 to-yellow-500 hover:from-amber-500 hover:to-yellow-600 text-black font-bold text-sm px-4 py-1.5 rounded-lg transition-all">Renew Now</a>
        </div>
    @elseif($membershipStatus === 'no_membership')
        <div class="mb-6 flex items-center gap-3 rounded-xl border border-white/10 bg-white/5 px-5 py-4">
            <svg class="h-5 w-5 shrink-0 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <p class="text-sm text-gray-300">You don't have an active membership yet.</p>
            <a href="/register" class="ml-auto shrink-0 bg-gradient-to-r from-amber-400 to-yellow-500 text-black font-bold text-sm px-4 py-1.5 rounded-lg transition-all">Get Started</a>
        </div>
    @endif

    {{-- Stat Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-8">
        {{-- Membership Status --}}
        @php
            $statusColor = match($membershipStatus) {
                'active'        => ['bg' => 'bg-green-500/10', 'border' => 'border-green-500/30', 'text' => 'text-green-400', 'dot' => 'bg-green-400', 'label' => 'Active'],
                'expiring_soon' => ['bg' => 'bg-amber-500/10', 'border' => 'border-amber-500/30', 'text' => 'text-amber-400', 'dot' => 'bg-amber-400', 'label' => 'Expiring Soon'],
                'expired'       => ['bg' => 'bg-red-500/10',   'border' => 'border-red-500/30',   'text' => 'text-red-400',   'dot' => 'bg-red-400',   'label' => 'Expired'],
                default         => ['bg' => 'bg-white/5',      'border' => 'border-white/10',     'text' => 'text-gray-400',  'dot' => 'bg-gray-400',  'label' => 'No Membership'],
            };
        @endphp
        <div class="bg-white/5 backdrop-blur-md border border-white/10 rounded-xl p-5 shadow-xl">
            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-3">Membership</p>
            <div class="flex items-center gap-2">
                <span class="inline-flex items-center gap-1.5 px-2 py-1 rounded-md text-xs font-semibold {{ $statusColor['bg'] }} {{ $statusColor['text'] }} border {{ $statusColor['border'] }}">
                    <span class="w-1.5 h-1.5 rounded-full {{ $statusColor['dot'] }}"></span>
                    {{ $statusColor['label'] }}
                </span>
            </div>
            <div class="mt-4 grid grid-cols-2 gap-3">
                <div>
                    <p class="text-xs text-gray-500">Expires</p>
                    <p class="text-sm font-semibold text-white mt-0.5">{{ $membership?->expires_at?->format('M j, Y') ?? '—' }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-500">Days Left</p>
                    <p class="text-sm font-semibold {{ ($daysRemaining !== null && $daysRemaining <= 7 && $daysRemaining >= 0) ? 'text-amber-400' : (($daysRemaining !== null && $daysRemaining < 0) ? 'text-red-400' : 'text-white') }} mt-0.5">
                        @if($daysRemaining !== null)
                            {{ $daysRemaining >= 0 ? $daysRemaining : abs($daysRemaining).'d over' }}
                        @else
                            —
                        @endif
                    </p>
                </div>
            </div>
        </div>

        {{-- Bookings --}}
        <div class="bg-white/5 backdrop-blur-md border border-white/10 rounded-xl p-5 shadow-xl">
            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-3">Upcoming Sessions</p>
            <p class="text-4xl font-bold text-white">{{ $upcomingBookings->count() }}</p>
            <p class="mt-2 text-xs text-gray-500">{{ $upcomingBookings->count() === 1 ? 'coaching session' : 'coaching sessions' }} scheduled</p>
        </div>

        {{-- Access --}}
        <div class="bg-white/5 backdrop-blur-md border border-white/10 rounded-xl p-5 shadow-xl">
            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-3">Portal Access</p>
            <span class="inline-flex items-center gap-1.5 px-2 py-1 rounded-md text-xs font-semibold bg-green-500/10 text-green-400 border border-green-500/30">
                <span class="w-1.5 h-1.5 rounded-full bg-green-400 shadow-[0_0_6px_rgba(34,197,94,0.8)]"></span>
                Active
            </span>
            <p class="mt-3 text-xs text-gray-500">Card, classes, events, and chat support are available.</p>
        </div>
    </div>

    {{-- Quick Links + Upcoming Bookings --}}
    <div class="grid gap-6 lg:grid-cols-2">
        {{-- Quick Links --}}
        <div class="backdrop-blur-md border border-white/10 rounded-xl shadow-xl overflow-hidden">
            <div class="px-5 py-4 border-b border-white/10 bg-white/5">
                <h2 class="text-sm font-semibold text-white">Quick Links</h2>
            </div>
            <div class="p-4 grid grid-cols-2 gap-3">
                @php
                    $quickLinks = [
                        ['route' => 'portal.card',          'label' => 'My Card',       'icon' => 'M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z'],
                        ['route' => 'portal.coaches',        'label' => 'Book a Coach',  'icon' => 'M8 7V3m8 4V3M4 11h16M5 5h14a1 1 0 011 1v13a2 2 0 01-2 2H6a2 2 0 01-2-2V6a1 1 0 011-1z'],
                        ['route' => 'portal.schedule',       'label' => 'Class Schedule','icon' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z'],
                        ['route' => 'portal.events',         'label' => 'Events',        'icon' => 'm12 3 2.3 5.4 5.7.5-4.3 3.8 1.3 5.6-5-3-5 3 1.3-5.6L4 8.9l5.7-.5L12 3z'],
                        ['route' => 'portal.my-membership',  'label' => 'My Membership', 'icon' => 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z'],
                        ['route' => 'portal.support',        'label' => 'Support',       'icon' => 'M8 10h8M8 14h5m8-2a9 9 0 11-4.2-7.6L21 4l-1.1 4.1A8.9 8.9 0 0121 12z'],
                    ];
                @endphp
                @foreach($quickLinks as $link)
                    <a href="{{ route($link['route']) }}" class="group flex items-center gap-3 rounded-lg bg-white/5 hover:bg-white/10 border border-white/10 hover:border-amber-500/30 p-3.5 transition-all duration-200 hover:-translate-y-0.5">
                        <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-amber-400/10 text-amber-400 transition group-hover:bg-amber-400/20">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75"><path stroke-linecap="round" stroke-linejoin="round" d="{{ $link['icon'] }}"/></svg>
                        </span>
                        <span class="text-sm font-medium text-gray-300 group-hover:text-white transition-colors">{{ $link['label'] }}</span>
                    </a>
                @endforeach
            </div>
        </div>

        {{-- Upcoming Bookings --}}
        <div class="backdrop-blur-md border border-white/10 rounded-xl shadow-xl overflow-hidden">
            <div class="px-5 py-4 border-b border-white/10 bg-white/5 flex items-center justify-between">
                <h2 class="text-sm font-semibold text-white">Upcoming Sessions</h2>
                <a href="{{ route('portal.coaches') }}" class="bg-gradient-to-r from-amber-400 to-yellow-500 hover:from-amber-500 hover:to-yellow-600 transition-all text-black font-bold text-xs px-3 py-1.5 rounded-lg shadow-[0_0_10px_rgba(251,191,36,0.2)] hover:shadow-[0_0_15px_rgba(251,191,36,0.35)] flex items-center gap-1.5 transform hover:-translate-y-0.5">
                    <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                    Book
                </a>
            </div>
            <div class="divide-y divide-white/5">
                @forelse($upcomingBookings as $booking)
                    <div class="flex items-center gap-4 px-5 py-4 hover:bg-white/5 transition-colors">
                        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-amber-400/10 text-amber-400 border border-amber-500/20">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                        </div>
                        <div class="min-w-0 flex-1">
                            <p class="truncate text-sm font-medium text-white">{{ $booking->coach?->name ?? 'Coach Session' }}</p>
                            <p class="mt-0.5 text-xs text-gray-400">{{ $booking->scheduled_at->format('M j, Y') }} · {{ $booking->scheduled_at->format('g:i A') }}</p>
                        </div>
                        <span class="shrink-0 inline-flex items-center gap-1.5 px-2 py-1 rounded-md text-xs font-semibold bg-green-500/10 text-green-400 border border-green-500/30">
                            <span class="w-1.5 h-1.5 rounded-full bg-green-400"></span>
                            {{ ucfirst($booking->status) }}
                        </span>
                    </div>
                @empty
                    <div class="py-16 text-center">
                        <svg class="h-10 w-10 text-white/20 mb-3 mx-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3M4 11h16M5 5h14a1 1 0 011 1v13a2 2 0 01-2 2H6a2 2 0 01-2-2V6a1 1 0 011-1z"/></svg>
                        <p class="text-sm font-medium text-gray-300">No upcoming bookings</p>
                        <p class="text-xs text-gray-500 mt-1">Book a coaching session to get started.</p>
                        <a href="{{ route('portal.coaches') }}" class="mt-4 inline-flex bg-gradient-to-r from-amber-400 to-yellow-500 hover:from-amber-500 hover:to-yellow-600 text-black font-bold text-sm px-5 py-2 rounded-xl transition-all transform hover:-translate-y-0.5 shadow-[0_0_15px_rgba(251,191,36,0.2)]">
                            Browse Coaches
                        </a>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>