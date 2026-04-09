<div>
    <div>
        <h1>Audit Log</h1>
        <p>All admin actions, read-only</p>
    </div>

    <div>
        <select wire:model.live="modelFilter">
            <option value="">All actions</option>
            <option value="member">Member actions</option>
            <option value="membership">Membership actions</option>
            <option value="plan">Plan actions</option>
        </select>
        <input type="date" wire:model.live="dateFrom">
        <input type="date" wire:model.live="dateTo">
    </div>

    <div>
        <table>
            <thead>
                <tr>
                    <th>Time</th>
                    <th>Actor</th>
                    <th>Action</th>
                    <th>Model</th>
                    <th>Details</th>
                </tr>
            </thead>
            <tbody>
                @forelse($logs as $log)
                    <tr>
                        <td>{{ $log->created_at->format('M j H:i') }}</td>
                        <td>{{ $log->actor?->name ?? 'System' }}</td>
                        <td>{{ $log->action }}</td>
                        <td>{{ class_basename($log->model_type) }} #{{ $log->model_id }}</td>
                        <td>{{ json_encode($log->payload) }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5">
                            <p>No audit entries found</p>
                            <p>Admin actions will be logged here automatically</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div>{{ $logs->links() }}</div>
</div>
