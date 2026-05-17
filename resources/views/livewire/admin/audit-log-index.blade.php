<div class="pb-24">
    <div class="mb-6 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h1 class="text-3xl font-bold text-white mb-2">Audit Log</h1>
            <p class="text-gray-300">All admin actions, read-only</p>
        </div>

        <div class="flex flex-wrap items-center gap-3">
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none z-10">
                    <svg class="h-4 w-4 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
                <input type="text" wire:model.live.debounce.300ms="search"
                    placeholder="Search logs…"
                    class="bg-white/5 border border-white/10 text-white placeholder-gray-500 pl-10 pr-4 py-2 text-sm rounded-xl focus:outline-none focus:ring-2 focus:ring-amber-500/50 backdrop-blur-md transition-all w-64 shadow-inner">
            </div>

            <div x-data="{ open: false }" @click.outside="open = false" class="relative w-48 z-40" wire:ignore.self>
                <button @click="open = !open" type="button" 
                    class="w-full bg-white/5 hover:bg-white/10 border border-white/10 rounded-xl px-4 py-2 text-sm text-gray-200 flex justify-between items-center focus:outline-none focus:ring-2 focus:ring-amber-500/50 backdrop-blur-md transition-all shadow-inner"
                    :class="{ 'ring-2 ring-amber-500/50 bg-white/10 border-amber-500/50': open }">
                    <div class="flex items-center gap-2.5 font-medium">
                        @if($modelFilter === '')
                            <span>All actions</span>
                        @elseif($modelFilter === 'member')
                            <span>Member actions</span>
                        @elseif($modelFilter === 'membership')
                            <span>Membership actions</span>
                        @elseif($modelFilter === 'plan')
                            <span>Plan actions</span>
                        @endif
                    </div>
                    <svg class="w-4 h-4 text-gray-500 transition-transform duration-300 shrink-0 ml-2" :class="{'rotate-180 text-amber-400': open}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                </button>
                <div x-show="open" 
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="transform opacity-0 -translate-y-2"
                     x-transition:enter-end="transform opacity-100 translate-y-0"
                     x-transition:leave="transition ease-in duration-150"
                     x-transition:leave-start="transform opacity-100 translate-y-0"
                     x-transition:leave-end="transform opacity-0 -translate-y-2"
                     style="display: none;"
                     class="absolute right-0 mt-2 w-full bg-[#000009]/95 backdrop-blur-xl border border-white/10 rounded-xl shadow-[0_15px_40px_rgba(0,0,0,0.8)] overflow-hidden">
                    <div class="p-1 flex flex-col">
                        <button wire:click="$set('modelFilter', '')" @click="open = false" type="button" class="flex items-center gap-3 w-full text-left px-3 py-2.5 text-sm font-medium text-gray-300 hover:text-white hover:bg-white/10 rounded-lg transition-colors {{ $modelFilter === '' ? 'bg-white/10 text-white' : '' }}">
                            All actions
                        </button>
                        <button wire:click="$set('modelFilter', 'member')" @click="open = false" type="button" class="flex items-center gap-3 w-full text-left px-3 py-2.5 text-sm font-medium text-gray-300 hover:text-white hover:bg-white/10 rounded-lg transition-colors {{ $modelFilter === 'member' ? 'bg-white/10 text-white' : '' }}">
                            Member actions
                        </button>
                        <button wire:click="$set('modelFilter', 'membership')" @click="open = false" type="button" class="flex items-center gap-3 w-full text-left px-3 py-2.5 text-sm font-medium text-gray-300 hover:text-white hover:bg-white/10 rounded-lg transition-colors {{ $modelFilter === 'membership' ? 'bg-white/10 text-white' : '' }}">
                            Membership actions
                        </button>
                        <button wire:click="$set('modelFilter', 'plan')" @click="open = false" type="button" class="flex items-center gap-3 w-full text-left px-3 py-2.5 text-sm font-medium text-gray-300 hover:text-white hover:bg-white/10 rounded-lg transition-colors {{ $modelFilter === 'plan' ? 'bg-white/10 text-white' : '' }}">
                            Plan actions
                        </button>
                    </div>
                </div>
            </div>

            <div class="flex items-center gap-2">
                <input type="date" wire:model.live="dateFrom" title="From Date" style="color-scheme: dark;"
                    class="bg-[#0a0a0a] hover:bg-[#111111] border border-white/10 hover:border-amber-500/50 text-white px-3 py-2 text-sm rounded-xl focus:outline-none focus:ring-2 focus:ring-amber-500/50 transition-all shadow-inner cursor-pointer">
                <span class="text-gray-500">—</span>
                <input type="date" wire:model.live="dateTo" title="To Date" style="color-scheme: dark;"
                    class="bg-[#0a0a0a] hover:bg-[#111111] border border-white/10 hover:border-amber-500/50 text-white px-3 py-2 text-sm rounded-xl focus:outline-none focus:ring-2 focus:ring-amber-500/50 transition-all shadow-inner cursor-pointer">
            </div>
        </div>
    </div>

    <div class="bg-[#1a1a1a]/95 backdrop-blur-md border border-white/10 rounded-2xl shadow-2xl overflow-visible">
        <table class="w-full text-sm border-separate border-spacing-0">
            <thead class="border-b border-white/10 bg-white/[0.04]">
                <tr>
                    <th class="text-left text-xs font-bold text-gray-200 uppercase tracking-[0.15em] py-5 px-6">Time</th>
                    <th class="text-left text-xs font-bold text-gray-200 uppercase tracking-[0.15em] py-5 px-4">Actor</th>
                    <th class="text-left text-xs font-bold text-gray-200 uppercase tracking-[0.15em] py-5 px-4">Action</th>
                    <th class="text-left text-xs font-bold text-gray-200 uppercase tracking-[0.15em] py-5 px-4">Model</th>
                    <th class="text-left text-xs font-bold text-gray-200 uppercase tracking-[0.15em] py-5 px-6">Details</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-white/10">
                @forelse($logs as $log)
                    <tr class="hover:bg-white/[0.03] transition-all duration-200 group">
                        <td class="py-5 px-6 text-gray-300 font-semibold whitespace-nowrap">{{ $log->created_at->format('M j H:i') }}</td>
                        <td class="py-5 px-4 font-bold text-white">{{ $log->actor?->name ?? 'System' }}</td>
                        <td class="py-5 px-4 text-gray-300">
                            <span class="inline-flex px-2 py-1 rounded-md text-[10px] font-bold tracking-wide uppercase bg-white/5 border border-white/10">
                                {{ $log->action }}
                            </span>
                        </td>
                        <td class="py-5 px-4 text-gray-400">{{ class_basename($log->model_type) }} #{{ $log->model_id }}</td>
                        <td class="py-5 px-6 text-gray-500 font-mono text-[10px] break-all max-w-xs">{{ json_encode($log->payload) }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="py-12 text-center text-gray-400">
                            <div class="flex justify-center mb-3">
                                <svg class="w-10 h-10 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                            </div>
                            <p class="font-bold text-gray-300">No audit entries found</p>
                            <p class="text-xs mt-1 text-gray-500">Admin actions will be logged here automatically</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($logs instanceof \Illuminate\Contracts\Pagination\Paginator && $logs->hasPages())
    <div class="fixed bottom-0 right-0 z-50 w-full md:w-[calc(100%-16rem)] py-3 px-4 bg-[#0a0a0a]/90 backdrop-blur-xl border-t border-white/10 shadow-[0_-10px_30px_rgba(0,0,0,0.5)] flex justify-center items-center">
        {{ $logs->links('components.custom-pagination') }}
    </div>
    @endif
</div>