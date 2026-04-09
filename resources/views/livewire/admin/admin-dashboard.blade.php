<div>
    <div>
        <h1>Dashboard</h1>
        <p>Overview of your gym's performance and activity</p>
    </div>

    <div>
        <div>
            <p>Total Members</p>
            <p>{{ $totalMembers }}</p>
            <p>All registered accounts</p>
        </div>
        <div>
            <p>Active Members</p>
            <p>{{ $activeMembers }}</p>
            <p>With active memberships</p>
        </div>
        <div>
            <p>Expiring Soon</p>
            <p>{{ $expiringSoon }}</p>
            <p>Within 7 days</p>
        </div>
        <div>
            <p>New This Month</p>
            <p>{{ $newThisMonth }}</p>
            <p>New sign-ups</p>
        </div>
    </div>

    <div>
        <h2>Recent Sign-ups</h2>
        <div>
            <table>
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Plan</th>
                        <th>Joined</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentSignups as $member)
                        <tr>
                            <td>{{ $member->name }}</td>
                            <td>{{ $member->email }}</td>
                            <td>{{ $member->activeMembership?->plan?->name ?? '—' }}</td>
                            <td>{{ $member->created_at->format('M j, Y') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4">
                                <p>No members yet</p>
                                <p>New sign-ups will appear here</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
