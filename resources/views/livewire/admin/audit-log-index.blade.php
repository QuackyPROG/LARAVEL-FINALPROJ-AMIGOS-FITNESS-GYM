<div>
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-xl font-semibold text-gray-900">Audit Log</h1>
            <p class="text-sm text-gray-500 mt-0.5">All admin actions, read-only</p>
        </div>
    </div>

    <div class="flex gap-3 mb-4">
        <select wire:model.live="modelFilter" class="border border-gray-300 rounded-md px-3 py-2 text-sm">
            <option value="">All actions</option>
            <option value="member">Member actions</option>
            <option value="membership">Membership actions</option>
            <option value="plan">Plan actions</option>
        </select>
        <input type="date" wire:model.live="dateFrom" class="border border-gray-300 rounded-md px-3 py-2 text-sm">
        <input type="date" wire:model.live="dateTo" class="border border-gray-300 rounded-md px-3 py-2 text-sm">
    </div>

    <div class="bg-white border border-gray-200 rounded-md overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="border-b border-gray-200 bg-gray-50">
                    <tr>
                        <th class="text-left text-xs font-medium text-gray-500 uppercase tracking-wide py-3 px-4">Time</th>
                        <th class="text-left text-xs font-medium text-gray-500 uppercase tracking-wide py-3 px-4">Actor</th>
                        <th class="text-left text-xs font-medium text-gray-500 uppercase tracking-wide py-3 px-4">Action</th>
                        <th class="text-left text-xs font-medium text-gray-500 uppercase tracking-wide py-3 px-4">Model</th>
                        <th class="text-left text-xs font-medium text-gray-500 uppercase tracking-wide py-3 px-4">Details</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($logs as $log)
                        <tr class="border-b border-gray-100">
                            <td class="py-3 px-4 text-gray-500 whitespace-nowrap">{{ $log->created_at->format('M j H:i') }}</td>
                            <td class="py-3 px-4 font-medium text-gray-900">{{ $log->actor?->name ?? 'System' }}</td>
                            <td class="py-3 px-4 text-gray-700">{{ $log->action }}</td>
                            <td class="py-3 px-4 text-gray-500">{{ class_basename($log->model_type) }} #{{ $log->model_id }}</td>
                            <td class="py-3 px-4 text-gray-400 font-mono text-xs">{{ json_encode($log->payload) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-8 text-center text-gray-400">
                                <p>No audit entries found</p>
                                <p class="text-xs mt-0.5">Admin actions will be logged here automatically</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-4">{{ $logs->links() }}</div>
</div>
