<style>
.pub-btn-primary {
    display: inline-flex; align-items: center; justify-content: center; gap: 6px;
    background: #fbbf24; color: #000;
    font-size: 13px; font-weight: 700; letter-spacing: 0.02em;
    padding: 8px 18px; border-radius: 6px; border: none;
    text-decoration: none;
    transition: background 0.2s, transform 0.2s, box-shadow 0.2s;
    cursor: pointer;
}
.pub-btn-primary:hover {
    background: #f59e0b; color: #000;
    transform: translateY(-1px);
    box-shadow: 0 6px 20px rgba(251,191,36,0.3);
}
.pub-btn-primary:active { transform: translateY(0); }

.pub-btn-outline {
    display: inline-flex; align-items: center; justify-content: center; gap: 6px;
    background: transparent; color: #fff;
    font-size: 13px; font-weight: 700; letter-spacing: 0.02em;
    padding: 8px 18px; border-radius: 6px; border: 2px solid #3f3f46;
    text-decoration: none;
    transition: border-color 0.2s, color 0.2s;
    cursor: pointer;
}
.pub-btn-outline:hover { border-color: #fbbf24; color: #fbbf24; }
</style>

<div class="mx-auto max-w-7xl space-y-6">
    @php
        $statusStyles = [
            'active' => 'border-emerald-400/25 bg-emerald-400/10 text-emerald-300',
            'expiring_soon' => 'border-amber-400/35 bg-amber-400/10 text-amber-300',
            'expired' => 'border-red-400/30 bg-red-400/10 text-red-300',
            'no_membership' => 'border-zinc-700 bg-zinc-900 text-zinc-300',
        ];
        $statusLabels = [
            'active' => 'Active',
            'expiring_soon' => 'Expiring Soon',
            'expired' => 'Expired',
            'no_membership' => 'No Membership',
        ];
        $statusClass = $statusStyles[$membershipStatus] ?? $statusStyles['no_membership'];
        $statusLabel = $statusLabels[$membershipStatus] ?? $statusLabels['no_membership'];
        $planName = $membership?->plan?->name ?? 'No Active Membership';
    @endphp

    <section class="relative overflow-hidden rounded-lg bg-[#080808] px-5 py-6 shadow-2xl shadow-black/40 ring-1 ring-amber-400/10 sm:px-7 lg:px-8">
        <div class="absolute inset-y-0 right-0 hidden w-1/2 bg-[url('/images/hero-gym.jpg')] bg-cover bg-center opacity-20 mix-blend-luminosity lg:block"></div>
        <div class="absolute inset-0 bg-[radial-gradient(circle_at_85%_15%,rgba(251,191,36,0.16),transparent_34%)]"></div>
        <div class="relative grid gap-6 lg:grid-cols-[1.25fr_0.75fr] lg:items-end">
            <div>
                <span class="inline-flex rounded-full border border-amber-400/25 px-3 py-1 text-[11px] font-bold uppercase tracking-[0.16em] text-amber-400">
                    Member Dashboard
                </span>
                <h1 class="mt-4 max-w-3xl text-4xl font-black uppercase leading-none text-white sm:text-5xl lg:text-6xl">
                    Welcome back, {{ $user->name }}
                </h1>
                <p class="mt-4 max-w-xl text-sm leading-6 text-zinc-400 sm:text-base">
                    Your membership, coaching sessions, class access, and support are ready when you are.
                </p>
                <div class="mt-6 flex flex-wrap gap-3">
                    <a href="{{ route('portal.coaches') }}" class="pub-btn-primary">
                        <svg aria-hidden="true" style="width:16px;height:16px;flex-shrink:0;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3M4 11h16M5 5h14a1 1 0 0 1 1 1v13a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a1 1 0 0 1 1-1Z" />
                        </svg>
                        Book a Coach
                    </a>
                    <a href="{{ route('portal.card') }}" class="pub-btn-outline">
                        <svg aria-hidden="true" style="width:16px;height:16px;flex-shrink:0;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 8h18M5 5h14a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V7a2 2 0 0 1 2-2Zm2 10h4" />
                        </svg>
                        View My Card
                    </a>
                </div>
            </div>

            <div class="rounded-lg bg-black/65 p-5 ring-1 ring-white/10 backdrop-blur">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <p class="text-[11px] font-bold uppercase tracking-[0.16em] text-zinc-500">Current Plan</p>
                        <p class="mt-2 text-2xl font-black uppercase text-white">{{ $planName }}</p>
                    </div>
                    <span class="inline-flex shrink-0 rounded-full border px-3 py-1 text-xs font-bold {{ $statusClass }}">
                        {{ $statusLabel }}
                    </span>
                </div>

                <div class="mt-5 grid grid-cols-2 gap-3">
                    <div class="rounded-md bg-zinc-950/80 p-4 ring-1 ring-white/5">
                        <p class="text-[11px] font-bold uppercase tracking-[0.14em] text-zinc-500">Expires</p>
                        <p class="mt-2 text-sm font-semibold text-zinc-200">{{ $membership?->expires_at?->format('M j, Y') ?? 'Not set' }}</p>
                    </div>
                    <div class="rounded-md bg-zinc-950/80 p-4 ring-1 ring-white/5">
                        <p class="text-[11px] font-bold uppercase tracking-[0.14em] text-zinc-500">Remaining</p>
                        <p class="mt-2 text-sm font-semibold text-zinc-200">
                            @if($daysRemaining !== null)
                                @if($daysRemaining >= 0)
                                    {{ $daysRemaining }} day{{ $daysRemaining === 1 ? '' : 's' }}
                                @else
                                    {{ abs($daysRemaining) }} day{{ abs($daysRemaining) === 1 ? '' : 's' }} overdue
                                @endif
                            @else
                                Not active
                            @endif
                        </p>
                    </div>
                </div>

                @if($showRenew)
                    <a href="/register{{ $membership?->plan_id ? '?plan='.$membership->plan_id : '' }}" class="pub-btn-primary mt-4 w-full justify-center">
                        Renew Membership
                    </a>
                @endif
            </div>
        </div>
    </section>

    <section class="grid gap-4 md:grid-cols-3">
        <div class="rounded-lg bg-[#0b0b0b] p-5 ring-1 ring-white/10">
            <p class="text-[11px] font-bold uppercase tracking-[0.16em] text-zinc-500">Membership</p>
            <p class="mt-3 text-3xl font-black text-white">{{ $membership ? $statusLabel : 'Start' }}</p>
            <p class="mt-2 text-sm text-zinc-500">{{ $membership ? 'Plan access is synced to your member profile.' : 'Choose a plan to unlock member access.' }}</p>
        </div>
        <div class="rounded-lg bg-[#0b0b0b] p-5 ring-1 ring-white/10">
            <p class="text-[11px] font-bold uppercase tracking-[0.16em] text-zinc-500">Bookings</p>
            <p class="mt-3 text-3xl font-black text-white">{{ $upcomingBookings->count() }}</p>
            <p class="mt-2 text-sm text-zinc-500">Upcoming coach session{{ $upcomingBookings->count() === 1 ? '' : 's' }} on your calendar.</p>
        </div>
        <div class="rounded-lg bg-[#0b0b0b] p-5 ring-1 ring-white/10">
            <p class="text-[11px] font-bold uppercase tracking-[0.16em] text-zinc-500">Member Access</p>
            <p class="mt-3 text-3xl font-black text-white">Ready</p>
            <p class="mt-2 text-sm text-zinc-500">Your card, classes, events, and support live here.</p>
        </div>
    </section>

    <section class="grid gap-6 lg:grid-cols-[0.95fr_1.05fr]">
        <div class="rounded-lg bg-[#0b0b0b] p-5 ring-1 ring-white/10 sm:p-6">
            <div class="flex items-center justify-between gap-4">
                <div>
                    <p class="text-[11px] font-bold uppercase tracking-[0.16em] text-amber-400">Quick Links</p>
                    <h2 class="mt-2 text-xl font-black uppercase text-white">Move Faster</h2>
                </div>
            </div>

            <div class="mt-5 grid gap-3 sm:grid-cols-2">
                <a href="{{ route('portal.card') }}" class="group rounded-md bg-zinc-950/70 p-4 ring-1 ring-white/5 transition hover:bg-amber-400 hover:text-black">
                    <span class="flex h-10 w-10 items-center justify-center rounded-md bg-amber-400/10 text-amber-400 ring-1 ring-amber-400/15 transition group-hover:bg-black/10 group-hover:text-black group-hover:ring-black/10">
                        <svg aria-hidden="true" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 8h18M5 5h14a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V7a2 2 0 0 1 2-2Z" />
                        </svg>
                    </span>
                    <span class="mt-4 block text-sm font-bold text-white transition group-hover:text-black">View My Card</span>
                </a>

                <a href="{{ route('portal.schedule') }}" class="group rounded-md bg-zinc-950/70 p-4 ring-1 ring-white/5 transition hover:bg-amber-400 hover:text-black">
                    <span class="flex h-10 w-10 items-center justify-center rounded-md bg-amber-400/10 text-amber-400 ring-1 ring-amber-400/15 transition group-hover:bg-black/10 group-hover:text-black group-hover:ring-black/10">
                        <svg aria-hidden="true" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6l4 2m5-2a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                        </svg>
                    </span>
                    <span class="mt-4 block text-sm font-bold text-white transition group-hover:text-black">Class Schedule</span>
                </a>

                <a href="{{ route('portal.events') }}" class="group rounded-md bg-zinc-950/70 p-4 ring-1 ring-white/5 transition hover:bg-amber-400 hover:text-black">
                    <span class="flex h-10 w-10 items-center justify-center rounded-md bg-amber-400/10 text-amber-400 ring-1 ring-amber-400/15 transition group-hover:bg-black/10 group-hover:text-black group-hover:ring-black/10">
                        <svg aria-hidden="true" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m12 3 2.3 5.4 5.7.5-4.3 3.8 1.3 5.6-5-3-5 3 1.3-5.6L4 8.9l5.7-.5L12 3Z" />
                        </svg>
                    </span>
                    <span class="mt-4 block text-sm font-bold text-white transition group-hover:text-black">Events</span>
                </a>

                <a href="{{ route('portal.support') }}" class="group rounded-md bg-zinc-950/70 p-4 ring-1 ring-white/5 transition hover:bg-amber-400 hover:text-black">
                    <span class="flex h-10 w-10 items-center justify-center rounded-md bg-amber-400/10 text-amber-400 ring-1 ring-amber-400/15 transition group-hover:bg-black/10 group-hover:text-black group-hover:ring-black/10">
                        <svg aria-hidden="true" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 10h8M8 14h5m8-2a9 9 0 1 1-4.2-7.6L21 4l-1.1 4.1A8.9 8.9 0 0 1 21 12Z" />
                        </svg>
                    </span>
                    <span class="mt-4 block text-sm font-bold text-white transition group-hover:text-black">Chat Support</span>
                </a>
            </div>
        </div>

        <div class="rounded-lg bg-[#0b0b0b] p-5 ring-1 ring-white/10 sm:p-6">
            <div class="flex items-center justify-between gap-4">
                <div>
                    <p class="text-[11px] font-bold uppercase tracking-[0.16em] text-amber-400">My Bookings</p>
                    <h2 class="mt-2 text-xl font-black uppercase text-white">Upcoming Sessions</h2>
                </div>
                <a href="{{ route('portal.coaches') }}" class="pub-btn-primary">Book</a>
            </div>

            <div class="mt-5 space-y-3">
                @forelse($upcomingBookings as $booking)
                    <div class="flex items-center justify-between gap-4 rounded-md bg-zinc-950/70 p-4 ring-1 ring-white/5">
                        <div>
                            <p class="font-semibold text-white">{{ $booking->coach?->name ?? 'Coach Session' }}</p>
                            <p class="mt-1 text-sm text-zinc-500">{{ $booking->scheduled_at->format('M j, Y') }} at {{ $booking->scheduled_at->format('g:i A') }}</p>
                        </div>
                        <span class="rounded-full border border-amber-400/20 bg-amber-400/10 px-3 py-1 text-xs font-bold capitalize text-amber-300">{{ $booking->status }}</span>
                    </div>
                @empty
                    <div class="rounded-md border border-dashed border-zinc-800 bg-zinc-950/50 px-5 py-8 text-center">
                        <p class="text-sm font-semibold text-zinc-300">No upcoming bookings</p>
                        <p class="mt-1 text-xs text-zinc-600">Coach booking sessions will appear here.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </section>
</div>