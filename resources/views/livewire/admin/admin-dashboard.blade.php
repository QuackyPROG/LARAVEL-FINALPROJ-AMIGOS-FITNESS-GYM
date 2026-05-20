<div>
    <div class="mb-8 flex justify-between items-start">
        <div>
            <h1 class="text-3xl font-bold text-white mb-2">Dashboard</h1>
            <p class="text-gray-300">Overview of your gym's performance and activity</p>
        </div>

<div wire:ignore class="text-right" x-data="{
    date: '',
    time: '',
    init() {
        this.updateClock();
        setInterval(() => this.updateClock(), 1000);
    },
    updateClock() {
        const now = new Date();
        this.date = now.toLocaleDateString('en-US', { weekday: 'long', month: 'long', day: 'numeric', year: 'numeric' });
        this.time = now.toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit' });
    }
}">
    <div class="text-xs text-zinc-500 font-medium tracking-wide" x-text="date"></div>
    <div class="text-sm text-zinc-400 font-semibold mt-0.5" x-text="time"></div>
</div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
        <x-stat-card
            title="Total Members"
            value="{{ $totalMembers }}"
            icon="people"
            color="gold"
            percentage="{{ $totalMembersChange['value'] }}"
            trend="{{ $totalMembersChange['trend'] }}"
        />
        <x-stat-card
            title="Active Members"
            value="{{ $activeMembers }}"
            icon="activity"
            color="gold"
            percentage="{{ $activeMembersChange['value'] }}"
            trend="{{ $activeMembersChange['trend'] }}"
        />
        <x-stat-card
            title="Expiring Soon"
            value="{{ $expiringSoon }}"
            icon="alert"
            color="gold"
            percentage="{{ $expiringChange['value'] }}"
            trend="{{ $expiringChange['trend'] }}"
        />
        <x-stat-card
            title="New This Month"
            value="{{ $newThisMonth }}"
            icon="calendar"
            color="gold"
            percentage="{{ $newMembersChange['value'] }}"
            trend="{{ $newMembersChange['trend'] }}"
        />
    </div>

    <div class="backdrop-blur-md border border-white/10 rounded-xl shadow-xl overflow-hidden">
        <div class="px-5 py-4 border-b border-white/10 flex flex-wrap justify-between items-center gap-4">
            <h2 class="text-sm font-semibold text-white">Recent Sign-ups</h2>
            <div class="flex items-center gap-3">
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none z-10">
                        <svg class="h-4 w-4 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                    <input type="text" wire:model.live.debounce.300ms="search" placeholder="Search members…"
                        class="bg-white/5 border border-white/10 text-white placeholder-gray-500 pl-10 pr-4 py-2 text-sm rounded-xl focus:outline-none focus:ring-2 focus:ring-amber-500/50 backdrop-blur-md transition-all w-64 shadow-inner">
                </div>
                <input type="date" wire:model.live="dateFilter"
                    style="color-scheme: dark;" class="bg-[#0a0a0a] hover:bg-[#111111] border border-white/10 hover:border-amber-500/50 text-white px-3 py-2 text-sm rounded-xl focus:outline-none focus:ring-2 focus:ring-amber-500/50 transition-all shadow-inner cursor-pointer">
            </div>
        </div>
        <div>
            <table class="w-full text-sm">
                <thead class="border-b border-white/10">
                    <tr>
                        <th class="text-left text-xs font-medium text-gray-300 uppercase tracking-wide py-3 px-4">Name</th>
                        <th class="text-left text-xs font-medium text-gray-300 uppercase tracking-wide py-3 px-4">Email</th>
                        <th class="text-left text-xs font-medium text-gray-300 uppercase tracking-wide py-3 px-4">Plan</th>
                        <th class="text-left text-xs font-medium text-gray-300 uppercase tracking-wide py-3 px-4">Joined</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/10">
                    @forelse($recentSignups as $member)
                        <tr class="hover:bg-white/5 transition-colors">
                            <td class="py-3 px-4 font-medium text-dark">{{ $member->name }}</td>
                            <td class="py-3 px-4 text-gray-300">{{ $member->email }}</td>
                            <td class="py-3 px-4 text-gray-300">{{ $member->activeMembership?->plan?->name ?? '—' }}</td>
                            <td class="py-3 px-4 text-gray-400">{{ $member->created_at->format('M j, Y') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="py-8 text-center text-gray-400">
                                <p>No members yet</p>
                                <p class="text-xs mt-0.5">New sign-ups will appear here</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
