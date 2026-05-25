<div class="space-y-6">

    {{-- Page Header --}}
    <div class="flex items-center justify-between">
        <h1 class="text-3xl font-bold text-amber-400">Sales Summary</h1>
        <p class="text-sm text-zinc-500">
            {{ $start->format('M d, Y') }} — {{ $end->format('M d, Y') }}
        </p>
    </div>

    {{-- Period Selector --}}
    <div class="flex gap-2">
        @foreach(['week' => 'Week', 'month' => 'Month', 'year' => 'Year'] as $value => $label)
            <button
                wire:click="$set('period', '{{ $value }}')"
                class="px-4 py-1.5 rounded-lg text-sm font-medium transition-colors
                    {{ $period === $value
                        ? 'bg-amber-400/20 text-amber-400 border border-amber-400/40'
                        : 'bg-white/5 text-zinc-400 border border-white/10 hover:bg-white/10' }}"
            >
                {{ $label }}
            </button>
        @endforeach
    </div>

    {{-- Summary Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        {{-- Total Sales --}}
        <div class="rounded-2xl border border-white/10 bg-white/[0.04] backdrop-blur-sm p-5">
            <p class="text-xs font-medium text-zinc-500 uppercase tracking-widest mb-1">Total Sales</p>
            <p class="text-3xl font-bold text-amber-400">
                ₱{{ number_format((float) $totalSales, 2) }}
            </p>
            <p class="text-xs text-zinc-600 mt-1">From membership plan prices in period</p>
        </div>

        {{-- Total Transactions --}}
        <div class="rounded-2xl border border-white/10 bg-white/[0.04] backdrop-blur-sm p-5">
            <p class="text-xs font-medium text-zinc-500 uppercase tracking-widest mb-1">Total Transactions</p>
            <p class="text-3xl font-bold text-white">
                {{ number_format($totalTransactions) }}
            </p>
            <p class="text-xs text-zinc-600 mt-1">Memberships activated in period</p>
        </div>
    </div>

    {{-- Search Bar --}}
    <div class="relative max-w-sm">
        <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-zinc-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"/>
        </svg>
        <input
            type="text"
            wire:model.live.debounce.300ms="search"
            placeholder="Search by name or email…"
            class="w-full pl-9 pr-4 py-2 rounded-xl bg-white/5 border border-white/10 text-sm text-white placeholder-zinc-600 focus:outline-none focus:border-amber-400/40 focus:bg-white/[0.07] transition"
        >
    </div>

    {{-- Members Table --}}
    <div class="rounded-2xl border border-white/10 bg-white/[0.04] backdrop-blur-sm overflow-hidden">
        @if($members->isEmpty())
            {{-- Empty State --}}
            <div class="flex flex-col items-center justify-center py-16 text-center">
                <svg class="w-12 h-12 text-zinc-700 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0115.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 013 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 00-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 01-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 003 15h-.75"/>
                </svg>
                <p class="text-zinc-500 font-medium">No members found for this period</p>
                <p class="text-xs text-zinc-600 mt-1">Try a different time period or search term.</p>
            </div>
        @else
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-left text-xs font-semibold text-zinc-500 uppercase tracking-wider">
                        <th class="px-5 py-3">Member</th>
                        <th class="px-5 py-3">Email</th>
                        <th class="px-5 py-3">Plan</th>
                        <th class="px-5 py-3 text-right">Total Sales</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($members as $member)
                        <tr class="group">
                            <td class="px-5 py-3.5">
                                <div class="flex items-center gap-2.5">
                                    <div class="w-7 h-7 rounded-full bg-amber-400/10 border border-amber-400/20 flex items-center justify-center flex-shrink-0">
                                        <span class="text-[11px] font-bold text-amber-400">
                                            {{ strtoupper(substr($member->name, 0, 1)) }}
                                        </span>
                                    </div>
                                    <span class="font-medium text-white/90">{{ $member->name }}</span>
                                </div>
                            </td>
                            <td class="px-5 py-3.5 text-zinc-400">{{ $member->email }}</td>
                            <td class="px-5 py-3.5">
                                @php $planName = $member->memberships->last()?->plan?->name ?? '—'; @endphp
                                @if($planName !== '—')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-amber-400/10 text-amber-400 border border-amber-400/20">
                                        {{ $planName }}
                                    </span>
                                @else
                                    <span class="text-zinc-600">—</span>
                                @endif
                            </td>
                            <td class="px-5 py-3.5 text-right font-semibold text-emerald-400">
                                ₱{{ number_format($member->memberships->sum(fn($m) => $m->plan?->price ?? 0), 2) }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            {{-- Pagination --}}
            @if($members->hasPages())
                <div class="px-5 py-3 border-t border-white/5">
                    {{ $members->links() }}
                </div>
            @endif
        @endif
    </div>

</div>
