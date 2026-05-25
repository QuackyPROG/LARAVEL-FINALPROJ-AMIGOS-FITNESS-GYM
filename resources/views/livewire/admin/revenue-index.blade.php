<div class="pb-24">
    {{-- Header --}}
    <div class="mb-6 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h1 class="text-3xl font-bold text-amber-400 mb-2">Revenue Management</h1>
            <p class="text-gray-300">Income overview across payments, memberships, and bookings</p>
        </div>

        {{-- Period filter tabs --}}
        <div class="flex items-center gap-1 bg-white/5 border border-white/10 rounded-xl p-1">
            <button
                wire:click="$set('period', 'week')"
                type="button"
                class="px-4 py-1.5 text-sm font-medium rounded-lg transition-all duration-200
                    {{ $period === 'week' ? 'bg-amber-500 text-black shadow' : 'text-gray-400 hover:text-white hover:bg-white/10' }}">
                Week
            </button>
            <button
                wire:click="$set('period', 'month')"
                type="button"
                class="px-4 py-1.5 text-sm font-medium rounded-lg transition-all duration-200
                    {{ $period === 'month' ? 'bg-amber-500 text-black shadow' : 'text-gray-400 hover:text-white hover:bg-white/10' }}">
                Month
            </button>
            <button
                wire:click="$set('period', 'year')"
                type="button"
                class="px-4 py-1.5 text-sm font-medium rounded-lg transition-all duration-200
                    {{ $period === 'year' ? 'bg-amber-500 text-black shadow' : 'text-gray-400 hover:text-white hover:bg-white/10' }}">
                Year
            </button>
        </div>
    </div>

    {{-- Plan filter dropdown --}}
    <div class="mb-6 flex items-center gap-3">
        <div x-data="{ open: false }" @click.outside="open = false" class="relative w-56 z-40" wire:ignore.self>
            <button @click="open = !open" type="button"
                class="w-full bg-white/5 hover:bg-white/10 border border-white/10 rounded-xl px-4 py-2 text-sm text-gray-200 flex justify-between items-center focus:outline-none focus:ring-2 focus:ring-amber-500/50 backdrop-blur-md transition-all shadow-inner"
                :class="{ 'ring-2 ring-amber-500/50 bg-white/10 border-amber-500/50': open }">
                <span class="font-medium">
                    @if($planFilter === '')
                        All Plans
                    @else
                        {{ $plans->firstWhere('id', $planFilter)?->name ?? 'All Plans' }}
                    @endif
                </span>
                <svg class="w-4 h-4 text-gray-500 transition-transform duration-300 shrink-0 ml-2"
                    :class="{'rotate-180 text-amber-400': open}"
                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                </svg>
            </button>
            <div x-show="open"
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="transform opacity-0 -translate-y-2"
                 x-transition:enter-end="transform opacity-100 translate-y-0"
                 x-transition:leave="transition ease-in duration-150"
                 x-transition:leave-start="transform opacity-100 translate-y-0"
                 x-transition:leave-end="transform opacity-0 -translate-y-2"
                 style="display: none;"
                 class="absolute left-0 mt-2 w-full bg-[#000009]/95 backdrop-blur-xl border border-white/10 rounded-xl shadow-[0_15px_40px_rgba(0,0,0,0.8)] overflow-hidden">
                <div class="p-1 flex flex-col">
                    <button wire:click="$set('planFilter', '')" @click="open = false" type="button"
                        class="flex items-center gap-3 w-full text-left px-3 py-2.5 text-sm font-medium text-gray-300 hover:text-white hover:bg-white/10 rounded-lg transition-colors {{ $planFilter === '' ? 'bg-white/10 text-white' : '' }}">
                        All Plans
                    </button>
                    @foreach($plans as $plan)
                        <button wire:click="$set('planFilter', '{{ $plan->id }}')" @click="open = false" type="button"
                            class="flex items-center gap-3 w-full text-left px-3 py-2.5 text-sm font-medium text-gray-300 hover:text-white hover:bg-white/10 rounded-lg transition-colors {{ $planFilter == $plan->id ? 'bg-white/10 text-white' : '' }}">
                            {{ $plan->name }}
                        </button>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    {{-- Top stat cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4 mb-8">
        {{-- Total Revenue --}}
        <div class="bg-[#1a1a1a]/95 backdrop-blur-md border border-white/10 rounded-2xl p-5 shadow-2xl">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-9 h-9 rounded-xl bg-amber-500/10 border border-amber-500/20 flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <span class="text-xs font-semibold text-gray-400 uppercase tracking-widest">Total Revenue</span>
            </div>
            <p class="text-2xl font-bold text-white">₱{{ number_format($totalRevenue, 2) }}</p>
            <p class="text-xs text-gray-500 mt-1 capitalize">This {{ $period }}</p>
        </div>

        {{-- Transactions --}}
        <div class="bg-[#1a1a1a]/95 backdrop-blur-md border border-white/10 rounded-2xl p-5 shadow-2xl">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-9 h-9 rounded-xl bg-blue-500/10 border border-blue-500/20 flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                </div>
                <span class="text-xs font-semibold text-gray-400 uppercase tracking-widest">Transactions</span>
            </div>
            <p class="text-2xl font-bold text-white">{{ number_format($transactionCount) }}</p>
            <p class="text-xs text-gray-500 mt-1 capitalize">Paid this {{ $period }}</p>
        </div>

        {{-- Total Bookings --}}
        <div class="bg-[#1a1a1a]/95 backdrop-blur-md border border-white/10 rounded-2xl p-5 shadow-2xl">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-9 h-9 rounded-xl bg-emerald-500/10 border border-emerald-500/20 flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                </div>
                <span class="text-xs font-semibold text-gray-400 uppercase tracking-widest">Total Bookings</span>
            </div>
            <p class="text-2xl font-bold text-white">{{ number_format($totalBookings) }}</p>
            <p class="text-xs text-gray-500 mt-1 capitalize">This {{ $period }}</p>
        </div>

        {{-- Confirmed Bookings --}}
        <div class="bg-[#1a1a1a]/95 backdrop-blur-md border border-white/10 rounded-2xl p-5 shadow-2xl">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-9 h-9 rounded-xl bg-violet-500/10 border border-violet-500/20 flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5 text-violet-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <span class="text-xs font-semibold text-gray-400 uppercase tracking-widest">Confirmed Bookings</span>
            </div>
            <p class="text-2xl font-bold text-white">{{ number_format($confirmedBookings) }}</p>
            <p class="text-xs text-gray-500 mt-1 capitalize">This {{ $period }}</p>
        </div>
    </div>

    {{-- Membership Income by Plan --}}
    <div>
        <h2 class="text-lg font-bold text-amber-400 mb-4">Membership Income by Plan</h2>

        <div class="bg-[#1a1a1a]/95 backdrop-blur-md border border-white/10 rounded-2xl shadow-2xl overflow-visible">
            <table class="w-full text-sm border-separate border-spacing-0">
                <thead class="border-b border-white/10 bg-white/[0.04]">
                    <tr>
                        <th class="text-left text-xs font-bold text-gray-300 uppercase tracking-[0.15em] py-4 px-6">Plan Name</th>
                        <th class="text-right text-xs font-bold text-gray-300 uppercase tracking-[0.15em] py-4 px-6">Memberships Sold</th>
                        <th class="text-right text-xs font-bold text-gray-300 uppercase tracking-[0.15em] py-4 px-6">Revenue</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/10">
                    @forelse($membershipsByPlan as $planName => $stats)
                        <tr class="hover:bg-white/[0.03] transition-all duration-200">
                            <td class="py-4 px-6 font-semibold text-white">{{ $planName }}</td>
                            <td class="py-4 px-6 text-right text-gray-300">{{ number_format($stats['count']) }}</td>
                            <td class="py-4 px-6 text-right font-semibold text-amber-400">₱{{ number_format($stats['revenue'], 2) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="py-12 text-center text-gray-400">
                                <div class="flex justify-center mb-3">
                                    <svg class="w-10 h-10 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                </div>
                                <p class="font-bold text-gray-300">No data for this period</p>
                                <p class="text-xs mt-1 text-gray-500">Membership data will appear here once memberships are sold</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
                @if($membershipsByPlan->isNotEmpty())
                    <tfoot class="border-t border-white/10 bg-white/[0.02]">
                        <tr>
                            <td class="py-4 px-6 text-xs font-bold text-gray-400 uppercase tracking-wider">Total</td>
                            <td class="py-4 px-6 text-right font-bold text-white">{{ number_format($membershipsByPlan->sum('count')) }}</td>
                            <td class="py-4 px-6 text-right font-bold text-amber-400">₱{{ number_format($membershipsByPlan->sum('revenue'), 2) }}</td>
                        </tr>
                    </tfoot>
                @endif
            </table>
        </div>
    </div>
</div>
