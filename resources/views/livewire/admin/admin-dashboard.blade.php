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
                this.date = now.toLocaleDateString('en-US', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' });
                this.time = now.toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit', second: '2-digit' });
            }
        }">
            <div class="text-amber-400 text-sm font-medium tracking-wide uppercase" x-text="date"></div>
            <div class="text-white text-2xl font-bold tracking-tight mt-0.5" x-text="time"></div>
        </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-8">
        <x-stat-card
            title="Total Members"
            value="{{ $totalMembers }}"
            icon="people"
            color="green"
            percentage="12"
            trend="up"
        />
        <x-stat-card
            title="Active Members"
            value="{{ $activeMembers }}"
            icon="activity"
            color="red"
            percentage="8"
            trend="up"
        />
        <x-stat-card
            title="Expiring Soon"
            value="{{ $expiringSoon }}"
            icon="alert"
            color="cyan"
            percentage="3"
            trend="down"
        />
        <x-stat-card
            title="New This Month"
            value="{{ $newThisMonth }}"
            icon="calendar"
            color="purple"
            percentage="15"
            trend="up"
        />
    </div>

    <div class="bg-dark-card border border-gray-600 rounded-md overflow-hidden">
        <div class="px-5 py-4 border-b border-gray-600 flex flex-wrap justify-between items-center gap-4">
            <h2 class="text-sm font-semibold text-white">Recent Sign-ups</h2>
            <div class="flex items-center gap-3">
                <input type="text" wire:model.live.debounce.300ms="search" placeholder="Search members…"
                    class="bg-dark-page border border-gray-600 text-white placeholder-gray-500 px-3 py-1.5 text-sm rounded-md focus:outline-none focus:ring-1 focus:ring-amber-400 w-48">
                <input type="date" wire:model.live="dateFilter"
                    class="bg-dark-page border border-gray-600 text-white px-3 py-1.5 text-sm rounded-md focus:outline-none focus:ring-1 focus:ring-amber-400">
            </div>
        </div>
        <div>
            <table class="w-full text-sm">
                <thead class="border-b border-gray-600 bg-dark-card">
                    <tr>
                        <th class="text-left text-xs font-medium text-gray-300 uppercase tracking-wide py-3 px-4">Name</th>
                        <th class="text-left text-xs font-medium text-gray-300 uppercase tracking-wide py-3 px-4">Email</th>
                        <th class="text-left text-xs font-medium text-gray-300 uppercase tracking-wide py-3 px-4">Plan</th>
                        <th class="text-left text-xs font-medium text-gray-300 uppercase tracking-wide py-3 px-4">Joined</th>
                    </tr>
                </thead>
                <tbody class="bg-dark-card">
                    @forelse($recentSignups as $member)
                        <tr class="border-b border-gray-600 hover:bg-gray-700 transition-colors">
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
