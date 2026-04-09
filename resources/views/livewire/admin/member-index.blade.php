<div>
    <div>
        <div>
            <h1>Members</h1>
            <p>All registered members</p>
        </div>
        <a href="{{ route('admin.members.create') }}">+ Add Walk-in Member</a>
    </div>

    @if(session('success'))
        <div>{{ session('success') }}</div>
    @endif

    <div>
        <input type="text" wire:model.live.debounce.300ms="search"
            placeholder="Search name or email…">
        <select wire:model.live="statusFilter">
            <option value="">All statuses</option>
            <option value="active">Active</option>
            <option value="inactive">Inactive</option>
            <option value="pending">Pending</option>
        </select>
    </div>

    <div>
        <table>
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Status</th>
                    <th>Plan</th>
                    <th>Expires</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($members as $member)
                    <tr>
                        <td>
                            <a href="{{ route('admin.members.show', $member) }}">{{ $member->name }}</a>
                        </td>
                        <td>{{ $member->email }}</td>
                        <td>
                            @if($member->status === 'active')
                                <span>Active</span>
                            @elseif($member->status === 'inactive')
                                <span>Inactive</span>
                            @else
                                <span>{{ ucfirst($member->status) }}</span>
                            @endif
                        </td>
                        <td>{{ $member->activeMembership?->plan?->name ?? '—' }}</td>
                        <td>{{ $member->activeMembership?->expires_at?->format('M j, Y') ?? '—' }}</td>
                        <td>
                            <div>
                                <a href="{{ route('admin.members.show', $member) }}">View</a>
                                <button wire:click="deactivate({{ $member->id }})" wire:confirm="Deactivate this member?">Deactivate</button>
                                <button wire:click="softDelete({{ $member->id }})" wire:confirm="Permanently delete this member?">Delete</button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6">
                            <p>No members found</p>
                            <p>Try adjusting your search or status filter</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div>{{ $members->links() }}</div>
</div>
