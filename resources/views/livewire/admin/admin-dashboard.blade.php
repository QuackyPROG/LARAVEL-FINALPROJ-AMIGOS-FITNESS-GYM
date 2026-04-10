<div>
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-xl font-semibold text-gray-900">Dashboard</h1>
            <p class="text-sm text-gray-500 mt-0.5">Overview of your gym's performance and activity</p>
        </div>
    </div>

    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
        <div class="bg-white border border-gray-200 rounded-md p-4">
            <p class="text-xs text-gray-500 mb-1">Total Members</p>
            <p class="text-2xl font-semibold text-gray-900">{{ $totalMembers }}</p>
            <p class="text-xs text-gray-400 mt-0.5">All registered accounts</p>
        </div>
        <div class="bg-white border border-gray-200 rounded-md p-4">
            <p class="text-xs text-gray-500 mb-1">Active Members</p>
            <p class="text-2xl font-semibold text-gray-900">{{ $activeMembers }}</p>
            <p class="text-xs text-gray-400 mt-0.5">With active memberships</p>
        </div>
        <div class="bg-white border border-gray-200 rounded-md p-4">
            <p class="text-xs text-gray-500 mb-1">Expiring Soon</p>
            <p class="text-2xl font-semibold text-gray-900">{{ $expiringSoon }}</p>
            <p class="text-xs text-gray-400 mt-0.5">Within 7 days</p>
        </div>
        <div class="bg-white border border-gray-200 rounded-md p-4">
            <p class="text-xs text-gray-500 mb-1">New This Month</p>
            <p class="text-2xl font-semibold text-gray-900">{{ $newThisMonth }}</p>
            <p class="text-xs text-gray-400 mt-0.5">New sign-ups</p>
        </div>
    </div>

    <div class="bg-white border border-gray-200 rounded-md overflow-hidden">
        <div class="px-5 py-4 border-b border-gray-100">
            <h2 class="text-sm font-semibold text-gray-700">Recent Sign-ups</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="border-b border-gray-100 bg-gray-50">
                    <tr>
                        <th class="text-left text-xs font-medium text-gray-500 uppercase tracking-wide py-3 px-4">Name</th>
                        <th class="text-left text-xs font-medium text-gray-500 uppercase tracking-wide py-3 px-4">Email</th>
                        <th class="text-left text-xs font-medium text-gray-500 uppercase tracking-wide py-3 px-4">Plan</th>
                        <th class="text-left text-xs font-medium text-gray-500 uppercase tracking-wide py-3 px-4">Joined</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentSignups as $member)
                        <tr class="border-b border-gray-100">
                            <td class="py-3 px-4 font-medium text-gray-900">{{ $member->name }}</td>
                            <td class="py-3 px-4 text-gray-600">{{ $member->email }}</td>
                            <td class="py-3 px-4 text-gray-600">{{ $member->activeMembership?->plan?->name ?? '—' }}</td>
                            <td class="py-3 px-4 text-gray-500">{{ $member->created_at->format('M j, Y') }}</td>
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
