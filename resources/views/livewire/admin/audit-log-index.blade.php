<div>
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-white mb-2">Audit Log</h1>
        <p class="text-gray-300">All admin actions, read-only</p>
    </div>

    <div class="flex gap-3 mb-6">
        <select wire:model.live="modelFilter" class="border border-gray-600 rounded-md px-3 py-2 text-sm bg-dark-card text-white">
            <option value="" class="bg-dark-page text-white">All actions</option>
            <option value="member" class="bg-dark-page text-white">Member actions</option>
            <option value="membership" class="bg-dark-page text-white">Membership actions</option>
            <option value="plan" class="bg-dark-page text-white">Plan actions</option>
        </select>
        <input type="date" wire:model.live="dateFrom" class="border border-gray-600 rounded-md px-3 py-2 text-sm bg-dark-card text-white">
        <input type="date" wire:model.live="dateTo" class="border border-gray-600 rounded-md px-3 py-2 text-sm bg-dark-card text-white">
    </div>

    <div class="bg-dark-card border border-gray-600 rounded-md overflow-hidden">
        <table class="w-full text-sm">
                <thead class="border-b border-gray-600 bg-dark-card">
                    <tr>
                        <th class="text-left text-xs font-medium text-gray-300 uppercase tracking-wide py-3 px-4">Time</th>
                        <th class="text-left text-xs font-medium text-gray-300 uppercase tracking-wide py-3 px-4">Actor</th>
                        <th class="text-left text-xs font-medium text-gray-300 uppercase tracking-wide py-3 px-4">Action</th>
                        <th class="text-left text-xs font-medium text-gray-300 uppercase tracking-wide py-3 px-4">Model</th>
                        <th class="text-left text-xs font-medium text-gray-300 uppercase tracking-wide py-3 px-4">Details</th>
                    </tr>
                </thead>
                <tbody class="bg-dark-card">
                    @forelse($logs as $log)
                        <tr class="border-b border-gray-600 hover:bg-gray-700 transition-colors">
                            <td class="py-3 px-4 text-gray-400 whitespace-nowrap">{{ $log->created_at->format('M j H:i') }}</td>
                            <td class="py-3 px-4 font-medium text-white">{{ $log->actor?->name ?? 'System' }}</td>
                            <td class="py-3 px-4 text-gray-300">{{ $log->action }}</td>
                            <td class="py-3 px-4 text-gray-400">{{ class_basename($log->model_type) }} #{{ $log->model_id }}</td>
                            <td class="py-3 px-4 text-gray-500 font-mono text-xs">{{ json_encode($log->payload) }}</td>
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

    <div class="mt-4">{{ $logs->links() }}</div>
</div>
