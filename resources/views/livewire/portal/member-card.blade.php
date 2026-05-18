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
</style>

<div class="mx-auto max-w-5xl space-y-6">
    <section class="relative overflow-hidden rounded-lg bg-[#080808] px-5 py-6 ring-1 ring-amber-400/10 sm:px-7">
        <div class="absolute inset-0 bg-[radial-gradient(circle_at_85%_15%,rgba(251,191,36,0.14),transparent_34%)]"></div>
        <div class="relative">
            <span class="inline-flex rounded-full border border-amber-400/25 px-3 py-1 text-[11px] font-bold uppercase tracking-[0.16em] text-amber-400">
                Digital Access
            </span>
            <h1 class="mt-4 text-4xl font-black uppercase leading-none text-white sm:text-5xl">My Membership Card</h1>
            <p class="mt-3 max-w-2xl text-sm leading-6 text-zinc-400">Your digital gym pass for quick membership verification.</p>
        </div>
    </section>

    <section class="flex justify-center">
        <div class="relative w-full max-w-md overflow-hidden rounded-lg bg-[#080808] p-6 shadow-2xl shadow-black/50 ring-1 ring-amber-400/20">
            <div class="absolute inset-x-0 top-0 h-1 bg-amber-400"></div>
            <div class="absolute inset-0 bg-[radial-gradient(circle_at_90%_0%,rgba(251,191,36,0.18),transparent_35%)]"></div>

            <div class="relative">
                <div class="mb-5 flex items-start justify-between gap-4">
                    <div>
                        <p class="text-[11px] font-black uppercase tracking-[0.18em] text-amber-400">Amigos Fitness Gym</p>
                        <p class="mt-2 text-2xl font-black uppercase leading-tight text-white">{{ $user->name }}</p>
                        <p class="mt-1 font-mono text-xs text-zinc-500">{{ $memberId }}</p>
                    </div>
                    <div class="shrink-0">
                        @if($membership)
                            @php
                                $now = \Illuminate\Support\Carbon::today();
                                $daysLeft = $membership->expires_at ? (int) $now->diffInDays($membership->expires_at, false) : null;
                                $isExpiring = $daysLeft !== null && $daysLeft <= 7 && $daysLeft >= 0;
                                $isExpired = $daysLeft !== null && $daysLeft < 0;
                            @endphp
                            @if($isExpired)
                                <span class="inline-flex rounded-full border border-red-400/30 bg-red-400/10 px-3 py-1 text-xs font-bold text-red-300">Expired</span>
                            @elseif($isExpiring)
                                <span class="inline-flex rounded-full border border-amber-400/35 bg-amber-400/10 px-3 py-1 text-xs font-bold text-amber-300">Expiring Soon</span>
                            @else
                                <span class="inline-flex rounded-full border border-emerald-400/50 bg-emerald-400/20 px-3 py-1 text-xs font-bold text-emerald-300">Active</span>
                            @endif
                        @else
                            <span class="inline-flex rounded-full border border-zinc-700 bg-zinc-900 px-3 py-1 text-xs font-bold text-zinc-300">No Membership</span>
                        @endif
                    </div>
                </div>

                @if($membership)
                    <div class="mb-5 grid grid-cols-2 gap-3 border-y border-white/10 py-4">
                        <div>
                            <p class="text-[11px] font-bold uppercase tracking-[0.14em] text-zinc-500">Plan</p>
                            <p class="mt-2 text-sm font-semibold text-white">{{ $membership->plan?->name ?? '-' }}</p>
                        </div>
                        <div>
                            <p class="text-[11px] font-bold uppercase tracking-[0.14em] text-zinc-500">Expires</p>
                            <p class="mt-2 text-sm font-semibold text-white">{{ $membership->expires_at?->format('M j, Y') ?? '-' }}</p>
                        </div>
                    </div>
                @endif

                <div class="mb-3 flex justify-center">
                    <div class="inline-block rounded-md border border-amber-400/20 p-3" style="background: #fff;">
                        {!! $qrSvg !!}
                    </div>
                </div>

                <p class="text-center text-xs font-medium text-zinc-500">Scan to verify membership</p>

                <a href="{{ route('portal.card.pdf') }}" class="pub-btn-primary mt-5 w-full justify-center">Download PDF</a>
            </div>
        </div>
    </section>
</div>