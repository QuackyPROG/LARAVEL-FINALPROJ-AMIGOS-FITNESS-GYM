<div>
    <div class="mb-8 flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4">
        
        <div>
            <h1 class="text-3xl font-bold text-amber-400 mb-2">Members</h1>
            <p class="text-gray-300">All registered members</p>
        </div>

        <div class="flex flex-wrap items-center gap-3">
            
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                    <svg class="h-4 w-4 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
                <input type="text" wire:model.live.debounce.300ms="search"
                    placeholder="Search name or email…"
                    class="bg-white/5 border border-white/10 text-white placeholder-gray-500 pl-10 pr-4 py-2 text-sm rounded-xl focus:outline-none focus:ring-2 focus:ring-amber-500/50 backdrop-blur-md transition-all w-64 shadow-inner">
            </div>

            <div x-data="{ open: false }" @click.outside="open = false" class="relative w-48 z-40" wire:ignore.self>
                
                <button @click="open = !open" type="button" 
                    class="w-full bg-white/5 hover:bg-white/10 border border-white/10 rounded-xl px-4 py-2 text-sm text-gray-200 flex justify-between items-center focus:outline-none focus:ring-2 focus:ring-amber-500/50 backdrop-blur-md transition-all shadow-inner"
                    :class="{ 'ring-2 ring-amber-500/50 bg-white/10 border-amber-500/50': open }">
                    
                    <div class="flex items-center gap-2.5 font-medium">
                        @if($statusFilter === 'active')
                            <svg class="w-4 h-4 text-green-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                            <span>Active</span>
                        @elseif($statusFilter === 'inactive')
                            <svg class="w-4 h-4 text-red-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                            <span>Inactive</span>
                        @elseif($statusFilter === 'pending')
                            <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                            <span>Pending</span>
                        @elseif($statusFilter === 'expiring')
                            <svg class="w-4 h-4 text-amber-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                            <span>Expiring Soon</span>
                        @else
                            <svg class="w-4 h-4 text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" /></svg>
                            <span>All Statuses</span>
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
                        <button wire:click="$set('statusFilter', '')" @click="open = false" type="button" class="flex items-center gap-3 w-full text-left px-3 py-2.5 text-sm font-medium text-gray-300 hover:text-white hover:bg-white/10 rounded-lg transition-colors {{ $statusFilter === '' ? 'bg-white/10 text-white' : '' }}">
                            <svg class="w-4 h-4 text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" /></svg>
                            All Statuses
                        </button>

                        <button wire:click="$set('statusFilter', 'active')" @click="open = false" type="button" class="flex items-center gap-3 w-full text-left px-3 py-2.5 text-sm font-medium text-gray-300 hover:text-white hover:bg-white/10 rounded-lg transition-colors {{ $statusFilter === 'active' ? 'bg-white/10 text-white' : '' }}">
                            <svg class="w-4 h-4 text-green-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                            Active
                        </button>

                        <button wire:click="$set('statusFilter', 'inactive')" @click="open = false" type="button" class="flex items-center gap-3 w-full text-left px-3 py-2.5 text-sm font-medium text-gray-300 hover:text-white hover:bg-white/10 rounded-lg transition-colors {{ $statusFilter === 'inactive' ? 'bg-white/10 text-white' : '' }}">
                            <svg class="w-4 h-4 text-red-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                            Inactive
                        </button>

                        <button wire:click="$set('statusFilter', 'pending')" @click="open = false" type="button" class="flex items-center gap-3 w-full text-left px-3 py-2.5 text-sm font-medium text-gray-300 hover:text-white hover:bg-white/10 rounded-lg transition-colors {{ $statusFilter === 'pending' ? 'bg-white/10 text-white' : '' }}">
                            <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                            Pending
                        </button>

                        <div class="border-t border-white/10 my-1 mx-2"></div>

                        <button wire:click="$set('statusFilter', 'expiring')" @click="open = false" type="button" class="flex items-center gap-3 w-full text-left px-3 py-2.5 text-sm font-medium text-amber-400 hover:text-amber-300 hover:bg-amber-500/10 rounded-lg transition-colors {{ $statusFilter === 'expiring' ? 'bg-amber-500/10 text-amber-300' : '' }}">
                            <svg class="w-4 h-4 text-amber-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                            Expiring Soon
                        </button>
                    </div>
                </div>
            </div>

            <button wire:click="openAddModal" class="bg-gradient-to-r from-amber-400 to-yellow-500 hover:from-amber-500 hover:to-yellow-600 transition-all text-black font-bold text-sm px-5 py-2 rounded-xl shadow-[0_0_15px_rgba(251,191,36,0.2)] hover:shadow-[0_0_20px_rgba(251,191,36,0.4)] flex items-center gap-2 transform hover:-translate-y-0.5">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" /></svg>
                Walk-in Member
            </button>
        </div>
    </div> @if(session('success'))
        <div class="bg-green-900/20 border border-green-700 text-green-300 text-sm px-4 py-3 rounded-md mb-4">{{ session('success') }}</div>
    @endif

    <div class="bg-white/[0.02] backdrop-blur-2xl border border-white/10 rounded-2xl shadow-[0_8px_30px_rgb(0,0,0,0.5)]">
        <table class="w-full text-sm">
            <thead class="border-b border-white/10 bg-white/[0.03]">
                <tr>
                    <th class="text-left text-xs font-semibold text-gray-400 uppercase tracking-wider py-4 px-5">Name</th>
                    <th class="text-left text-xs font-semibold text-gray-400 uppercase tracking-wider py-4 px-5">Email</th>
                    <th class="text-center text-xs font-semibold text-gray-400 uppercase tracking-wider py-4 px-5">Status</th>
                    <th class="text-center text-xs font-semibold text-gray-400 uppercase tracking-wider py-4 px-5">Plan</th>
                    <th class="text-center text-xs font-semibold text-gray-400 uppercase tracking-wider py-4 px-5">Expires</th>
                    <th class="text-center text-xs font-semibold text-gray-400 uppercase tracking-wider py-4 px-5">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-white/[0.05] bg-transparent">
                @forelse($members as $member)
                    <tr class="hover:bg-white/[0.04] transition-all duration-300 group">
                        <td class="py-4 px-5">
                            <button wire:click="openViewModal({{ $member->id }})" class="font-medium text-white underline decoration-white/30 hover:decoration-white cursor-pointer transition-colors">{{ $member->name }}</button>
                        </td>
                        <td class="py-4 px-5 text-gray-300">{{ $member->email }}</td>
                        
                        <td class="py-4 px-5 text-center">
                            @if($member->status === 'active')
                                <span class="inline-flex items-center gap-1.5 px-2 py-1 rounded-md text-xs font-semibold bg-green-500/10 text-green-400 border border-green-500/30 shadow-[0_0_10px_rgba(34,197,94,0.15)] group-hover:shadow-[0_0_12px_rgba(34,197,94,0.25)] transition-shadow">
                                    <span class="w-1.5 h-1.5 rounded-full bg-green-400 shadow-[0_0_6px_rgba(34,197,94,0.8)]"></span> Active
                                </span>
                            @elseif($member->status === 'inactive')
                                <span class="inline-flex items-center gap-1.5 px-2 py-1 rounded-md text-xs font-semibold bg-red-500/10 text-red-400 border border-red-500/30 shadow-[0_0_10px_rgba(239,68,68,0.15)] group-hover:shadow-[0_0_12px_rgba(239,68,68,0.25)] transition-shadow">
                                    <span class="w-1.5 h-1.5 rounded-full bg-red-400 shadow-[0_0_6px_rgba(239,68,68,0.8)]"></span> Inactive
                                </span>
                            @else
                                <span class="inline-flex px-2 py-1 rounded-md text-xs font-semibold bg-white/5 text-gray-300 border border-white/10">{{ ucfirst($member->status) }}</span>
                            @endif
                        </td>
                        
                        <td class="py-4 px-5 text-gray-300 text-center font-medium">{{ $member->activeMembership?->plan?->name ?? '—' }}</td>
                        
                        <td class="py-4 px-5 text-gray-400 text-center">{{ $member->activeMembership?->expires_at?->format('M j, Y') ?? '—' }}</td>
                        
                        <td class="py-4 px-5">
                            <div class="flex items-center justify-center relative">
                                <div class="relative group">
                                    <button class="text-gray-400 hover:text-gray-200 transition-all p-1.5 hover:bg-gray-700/50 rounded-lg border border-transparent hover:border-gray-600" title="More actions">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                            <circle cx="3" cy="10" r="1.5" fill="currentColor"/>
                                            <circle cx="10" cy="10" r="1.5" fill="currentColor"/>
                                            <circle cx="17" cy="10" r="1.5" fill="currentColor"/>
                                        </svg>
                                    </button>
                                    <div class="absolute right-0 mt-2 w-48 bg-dark-card border border-gray-600 rounded-lg shadow-xl opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 z-50">
                                        <button wire:click="openViewModal({{ $member->id }})" class="w-full text-left px-4 py-2 text-sm text-gray-300 hover:bg-gray-700/50 hover:text-blue-400 transition-colors rounded-t-lg flex items-center gap-2">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor"><path d="M10 12a2 2 0 100-4 2 2 0 000 4z"/><path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"/></svg>
                                            View Details
                                        </button>
                                        <button wire:click="confirmNotify({{ $member->id }})" class="w-full text-left px-4 py-2 text-sm text-gray-300 hover:bg-gray-700/50 hover:text-blue-400 transition-colors flex items-center gap-2 border-t border-gray-700">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor"><path d="M10 2a6 6 0 100 12 6 6 0 000-12zM0 10a10 10 0 1120 0 10 10 0 01-20 0z"/></svg>
                                            Notify Expiry
                                        </button>
                                        @if($member->status === 'active')
                                        <button wire:click="confirmDeactivate({{ $member->id }})" class="w-full text-left px-4 py-2 text-sm text-gray-300 hover:bg-gray-700/50 hover:text-amber-400 transition-colors flex items-center gap-2 border-t border-gray-700">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M13.477 14.89A6 6 0 015.11 6.524l8.367 8.368zm1.414-1.414L6.524 5.11a6 6 0 018.367 8.367zM18 10a8 8 0 11-16 0 8 8 0 0116 0z" clip-rule="evenodd"/></svg>
                                            Deactivate
                                        </button>
                                        @endif
                                        <button wire:click="confirmDelete({{ $member->id }})" class="w-full text-left px-4 py-2 text-sm text-red-400 hover:bg-red-900/20 hover:text-red-300 transition-colors rounded-b-lg flex items-center gap-2 border-t border-gray-700">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                                            Delete
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="py-16 text-center text-gray-400">
                            <div class="flex flex-col items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-white/20 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                </svg>
                                <p class="font-medium text-gray-300">No members found</p>
                                <p class="text-xs mt-1 text-gray-500">Try adjusting your search or status filter</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $members->links() }}</div>

@if($showAddModal)
        <style>
            .gold-gradient-bg {
                background-size: 200% 200%;
                animation: pan-gradient 4s ease infinite;
            }
            @keyframes pan-gradient {
                0% { background-position: 0% 50%; }
                50% { background-position: 100% 50%; }
                100% { background-position: 0% 50%; }
            }
            /* Custom scrollbar for the new dropdown */
            .glass-scrollbar::-webkit-scrollbar { width: 6px; }
            .glass-scrollbar::-webkit-scrollbar-track { background: transparent; }
            .glass-scrollbar::-webkit-scrollbar-thumb { background: rgba(255, 255, 255, 0.2); border-radius: 10px; }
            .glass-scrollbar::-webkit-scrollbar-thumb:hover { background: rgba(255, 255, 255, 0.3); }
        </style>
        
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/70 backdrop-blur-sm p-4">
            <div class="relative w-full max-w-md mx-auto group">
                <div class="absolute -inset-[1.5px] bg-gradient-to-r from-amber-300 via-yellow-600 to-amber-400 rounded-2xl gold-gradient-bg opacity-80 blur-[2px] transition-opacity duration-500"></div>
                
                <div class="relative bg-[#000000] rounded-2xl shadow-[0_0_40px_rgba(0,0,0,0.5)] p-8 w-full">
                    
                    <div class="flex flex-col items-center justify-center mb-8 text-center">
                        <div class="bg-gradient-to-br from-amber-400/20 to-amber-600/20 border border-amber-500/30 text-amber-400 p-3.5 rounded-full mb-4 shadow-[0_0_20px_rgba(251,191,36,0.15)]">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                            </svg>
                        </div>
                        <h2 class="text-2xl font-extrabold text-white tracking-wide">Add Member</h2>
                        <p class="text-sm text-gray-400 mt-1.5">Register a new walk-in client</p>
                    </div>
                    
                    @if($tempPasswordResult)
                        <div class="bg-green-500/10 border border-green-500/30 text-green-300 p-5 rounded-xl mb-4 text-sm backdrop-blur-md text-center">
                            <div class="flex justify-center mb-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-green-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                            </div>
                            <p class="font-bold text-base mb-2 text-white">Success!</p>
                            <p class="mb-3">Temporary Password:</p>
                            <code class="bg-black/50 border border-white/10 px-4 py-2 rounded-lg text-lg text-amber-400 font-mono tracking-wider block mb-3">{{ $tempPasswordResult }}</code>
                            <p class="text-xs mt-2 text-green-400/80">Please copy this password. It won't be shown again.</p>
                            <div class="mt-5">
                                <button wire:click="closeAddModal" class="w-full bg-white/10 hover:bg-white/20 text-white font-medium px-4 py-2.5 rounded-xl transition-all">Done</button>
                            </div>
                        </div>
                    @else
                        <form wire:submit="saveMember" class="space-y-5">
                            
                            <div>
                                <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1.5 ml-1">Full Name</label>
                                <input type="text" wire:model="addName" placeholder="e.g. John Doe"
                                    class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-amber-500/50 focus:border-amber-500/50 focus:bg-white/10 backdrop-blur-md transition-all shadow-inner" required>
                                @error('addName') <span class="text-xs text-red-400 mt-1 ml-1 block">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1.5 ml-1">Email Address</label>
                                <input type="email" wire:model="addEmail" placeholder="john@example.com"
                                    class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-amber-500/50 focus:border-amber-500/50 focus:bg-white/10 backdrop-blur-md transition-all shadow-inner" required>
                                @error('addEmail') <span class="text-xs text-red-400 mt-1 ml-1 block">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1.5 ml-1">Membership Plan</label>
                                
                                <div x-data="{ 
                                        open: false, 
                                        selectedName: 'Select a plan...', 
                                        selectPlan(id, name) { 
                                            $wire.set('addPlanId', id); 
                                            this.selectedName = name; 
                                            this.open = false; 
                                        } 
                                     }" 
                                     class="relative" wire:ignore.self>
                                     
                                    <button @click="open = !open" type="button" 
                                        class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white flex justify-between items-center focus:outline-none focus:ring-2 focus:ring-amber-500/50 focus:bg-white/10 backdrop-blur-md transition-all shadow-inner"
                                        :class="{ 'ring-2 ring-amber-500/50 bg-white/10 border-amber-500/50': open }">
                                        <span x-text="selectedName" class="text-sm font-medium" :class="{'text-gray-400': selectedName === 'Select a plan...'}"></span>
                                        <svg class="w-5 h-5 text-gray-400 transition-transform duration-300" :class="{'rotate-180 text-amber-400': open}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                    </button>

                                    <div x-show="open" @click.away="open = false" 
                                         x-transition:enter="transition ease-out duration-200"
                                         x-transition:enter-start="transform opacity-0 -translate-y-2"
                                         x-transition:enter-end="transform opacity-100 translate-y-0"
                                         x-transition:leave="transition ease-in duration-150"
                                         x-transition:leave-start="transform opacity-100 translate-y-0"
                                         x-transition:leave-end="transform opacity-0 -translate-y-2"
                                         class="absolute z-50 w-full mt-2 bg-[#1a2c23]/95 backdrop-blur-xl border border-white/10 rounded-xl shadow-[0_15px_50px_rgba(0,0,0,0.7)] overflow-hidden"
                                         style="display: none;">
                                        
                                        <div class="py-2 max-h-56 overflow-y-auto glass-scrollbar">
                                            @foreach($plans as $plan)
                                                <button type="button" 
                                                        @click="selectPlan({{ $plan->id }}, '{{ addslashes($plan->name) }}')"
                                                        class="w-full text-left px-5 py-3 hover:bg-white/10 transition-colors group flex justify-between items-center border-b border-white/5 last:border-0">
                                                    <div>
                                                        <div class="text-sm font-semibold text-gray-200 group-hover:text-white">{{ $plan->name }}</div>
                                                        <div class="text-xs text-gray-500 group-hover:text-amber-400/80 mt-0.5 transition-colors">{{ $plan->duration_days }} Days</div>
                                                    </div>
                                                    <div class="text-sm font-bold text-amber-400">
                                                        {{ config('paymongo.currency', 'PHP') }} {{ number_format($plan->price, 0) }}
                                                    </div>
                                                </button>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                                @error('addPlanId') <span class="text-xs text-red-400 mt-1 ml-1 block">{{ $message }}</span> @enderror
                            </div>
                            
                            <div class="flex gap-3 mt-8 pt-6 border-t border-white/10">
                                <button type="button" wire:click="closeAddModal" class="flex-1 px-4 py-3 text-sm font-semibold text-gray-300 bg-white/5 hover:bg-white/10 border border-white/10 rounded-xl transition-all">Cancel</button>
                                <button type="submit" class="flex-[2] bg-gradient-to-r from-amber-400 to-yellow-500 hover:from-amber-500 hover:to-yellow-600 text-black font-bold px-4 py-3 rounded-xl shadow-[0_0_20px_rgba(251,191,36,0.3)] hover:shadow-[0_0_25px_rgba(251,191,36,0.5)] transition-all transform hover:-translate-y-0.5">Create Member</button>
                            </div>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    @endif
<style>
    /* Complete, solid continuous rotating gradients (No empty spots) */
    .spin-bg-gold { background: conic-gradient(from 0deg, #fbbf24, #b45309, #fde68a, #b45309, #fbbf24); }
    .spin-bg-blue { background: conic-gradient(from 0deg, #3b82f6, #1e3a8a, #bfdbfe, #1e3a8a, #3b82f6); }
    .spin-bg-orange { background: conic-gradient(from 0deg, #f97316, #9a3412, #fed7aa, #9a3412, #f97316); }
    .spin-bg-red { background: conic-gradient(from 0deg, #ef4444, #7f1d1d, #fecaca, #7f1d1d, #ef4444); }
    
    /* Optional: View Modal Scrollbar */
    .custom-scrollbar::-webkit-scrollbar { width: 6px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: #4b5563; border-radius: 10px; }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #6b7280; }
</style>
    @if($showViewModal && $selectedMember)
        <style>
            .custom-scrollbar::-webkit-scrollbar {
                width: 6px;
            }
            .custom-scrollbar::-webkit-scrollbar-track {
                background: transparent; 
            }
            .custom-scrollbar::-webkit-scrollbar-thumb {
                background: #4b5563; 
                border-radius: 10px;
            }
            .custom-scrollbar::-webkit-scrollbar-thumb:hover {
                background: #6b7280;
            }
        </style>

        <div class="fixed inset-0 z-[60] flex items-center justify-center bg-black/70 backdrop-blur-md p-4">
            
            <div class="relative w-full max-w-6xl h-[90vh] mx-auto group overflow-hidden rounded-2xl p-[2px] bg-[#1a2c23]">
                
                <div class="absolute top-1/2 left-1/2 w-[150%] h-[150%] origin-center -translate-x-1/2 -translate-y-1/2 animate-[spin_8s_linear_infinite] opacity-100 spin-bg-gold"></div>
                
                <div class="relative z-10 bg-dark-card rounded-xl w-full h-full flex flex-col overflow-hidden isolate shadow-2xl">
                    
                    <div class="flex justify-between items-start p-6 border-b border-gray-700 shrink-0 bg-dark-card">
                        <div>
                            <h2 class="text-3xl font-bold text-white">{{ $selectedMember->name }}</h2>
                            <p class="text-sm text-gray-400 mt-1">Member #{{ $selectedMember->id }}</p>
                        </div>
                        <button wire:click="closeViewModal" class="text-gray-400 hover:text-white transition-colors p-2 bg-gray-800/50 hover:bg-gray-700 rounded-full focus:outline-none">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                
                <div class="p-6 overflow-hidden flex-1 flex flex-col min-h-0">
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 h-full min-h-0">
                        
                        <div class="flex flex-col gap-6">
                            
                            {{-- Profile Info --}}
                            <div class="bg-gray-800/30 border border-gray-700 rounded-xl p-5 shadow-inner shrink-0">
                                <h2 class="text-xs font-bold text-amber-400 uppercase tracking-widest mb-4">Profile Details</h2>
                                <div class="grid grid-cols-2 gap-x-6 gap-y-4">
                                    <div class="flex flex-col gap-1">
                                        <span class="text-xs text-gray-500 uppercase tracking-wide">Email Address</span>
                                        <p class="text-sm font-medium text-white truncate" title="{{ $selectedMember->email }}">{{ $selectedMember->email }}</p>
                                    </div>
                                    <div class="flex flex-col gap-1">
                                        <span class="text-xs text-gray-500 uppercase tracking-wide">Account Status</span>
                                        <div class="flex items-center">
                                            @if($selectedMember->status === 'active')
                                                <span class="inline-flex items-center gap-1.5 px-2 py-1 rounded-md text-xs font-semibold bg-green-500/10 text-green-400 border border-green-500/30 shadow-[0_0_8px_rgba(34,197,94,0.4)]">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-green-400 shadow-[0_0_6px_rgba(34,197,94,0.8)]"></span> Active
                                                </span>
                                            @elseif($selectedMember->status === 'inactive')
                                                <span class="inline-flex items-center gap-1.5 px-2 py-1 rounded-md text-xs font-semibold bg-red-500/10 text-red-400 border border-red-500/30 shadow-[0_0_8px_rgba(239,68,68,0.4)]">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-red-400 shadow-[0_0_6px_rgba(239,68,68,0.8)]"></span> Inactive
                                                </span>
                                            @else
                                                <span class="inline-flex px-2 py-1 rounded-md text-xs font-semibold bg-gray-700 text-gray-300 border border-gray-600 shadow-[0_0_8px_rgba(156,163,175,0.3)]">{{ ucfirst($selectedMember->status) }}</span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="flex flex-col gap-1">
                                        <span class="text-xs text-gray-500 uppercase tracking-wide">System Role</span>
                                        <p class="text-sm font-medium text-white">{{ ucfirst($selectedMember->role ?? 'Member') }}</p>
                                    </div>
                                    <div class="flex flex-col gap-1">
                                        <span class="text-xs text-gray-500 uppercase tracking-wide">Date Joined</span>
                                        <p class="text-sm font-medium text-white">{{ $selectedMember->created_at->format('M j, Y') }}</p>
                                    </div>
                                </div>
                            </div>

                            {{-- Legal Agreements / Consent History --}}
                            <div class="bg-gray-800/30 border border-gray-700 rounded-xl overflow-hidden shadow-inner shrink-0">
                                <div class="px-4 py-3 border-b border-gray-700 bg-gray-800/50">
                                    <h2 class="text-xs font-bold text-amber-400 uppercase tracking-widest">Legal Agreements</h2>
                                </div>
                                @if(!isset($selectedMember->consents) || $selectedMember->consents->isEmpty())
                                    <div class="p-4 text-xs text-gray-500 text-center italic">No consent records found.</div>
                                @else
                                    <div class="overflow-hidden">
                                        <table class="w-full text-xs">
                                            <thead class="border-b border-gray-700 bg-gray-800/30">
                                                <tr>
                                                    <th class="text-left font-medium text-gray-400 uppercase tracking-wide py-2 px-4">Document</th>
                                                    <th class="text-center font-medium text-gray-400 uppercase tracking-wide py-2 px-3">Method</th>
                                                    <th class="text-center font-medium text-gray-400 uppercase tracking-wide py-2 px-3">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody class="divide-y divide-gray-700/50">
                                                @foreach($selectedMember->consents as $consent)
                                                    @php
                                                        $docTitle = match($consent->document_key) {
                                                            'legal.terms_and_conditions' => 'Terms & Conditions',
                                                            'legal.membership_contract'  => 'Membership Contract',
                                                            'legal.liability_waiver'     => 'Liability Waiver',
                                                            'legal.privacy_policy'       => 'Privacy Policy',
                                                            default                      => $consent->document_key,
                                                        };
                                                    @endphp
                                                    <tr class="hover:bg-gray-700/30 transition-colors">
                                                        <td class="py-2 px-4">
                                                            <div class="font-medium text-white">{{ $docTitle }}</div>
                                                            <div class="text-[10px] text-gray-500">v{{ $consent->version }} • {{ $consent->accepted_at->format('M j, y') }}</div>
                                                        </td>
                                                        <td class="py-2 px-3 text-center">
                                                            @if($consent->method === 'staff_witnessed')
                                                                <span class="inline-flex px-1.5 py-0.5 rounded text-[10px] font-medium bg-yellow-500/10 text-yellow-400 border border-yellow-500/20">Staff</span>
                                                            @else
                                                                <span class="inline-flex px-1.5 py-0.5 rounded text-[10px] font-medium bg-blue-500/10 text-blue-400 border border-blue-500/20">Online</span>
                                                            @endif
                                                        </td>
                                                        <td class="py-2 px-3 text-center">
                                                            @if($consent->snapshot)
                                                                <flux:modal.trigger :name="'snapshot-'.$consent->id">
                                                                    <button class="text-blue-400 hover:text-blue-300 transition-colors font-medium">View</button>
                                                                </flux:modal.trigger>
                                                            @else
                                                                <span class="text-gray-600 italic">N/A</span>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="flex flex-col h-full min-h-0">
                            <div class="bg-gray-800/30 border border-gray-700 rounded-xl overflow-hidden flex flex-col shadow-inner h-full min-h-0 w-full">
                                <div class="px-5 py-4 border-b border-gray-700 bg-gray-800/50 shrink-0">
                                    <h2 class="text-xs font-bold text-amber-400 uppercase tracking-widest">Membership History</h2>
                                </div>
                                
                                <div class="overflow-y-auto overflow-x-hidden flex-1 custom-scrollbar relative">
                                    <table class="w-full text-sm table-fixed">
                                        <thead class="sticky top-0 bg-gray-800/95 backdrop-blur shadow-sm z-10 border-b border-gray-700">
                                            <tr>
                                                <th class="text-left text-xs font-medium text-gray-400 uppercase tracking-wide py-3 px-5 w-5/12">Plan</th>
                                                <th class="text-center text-xs font-medium text-gray-400 uppercase tracking-wide py-3 px-4 w-3/12">Status</th>
                                                <th class="text-center text-xs font-medium text-gray-400 uppercase tracking-wide py-3 px-4 w-4/12">Timeline</th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-gray-700/50">
                                            @forelse($selectedMember->memberships ?? [] as $ms)
                                                <tr class="hover:bg-gray-700/30 transition-colors">
                                                    <td class="py-4 px-5 font-medium text-white break-words">{{ $ms->plan?->name ?? '—' }}</td>
                                                    <td class="py-4 px-4 text-center">
                                                        @if($ms->status === 'active')
                                                            <span class="inline-flex px-2 py-1 rounded-md text-xs font-semibold bg-green-500/10 text-green-400 border border-green-500/30 shadow-[0_0_8px_rgba(34,197,94,0.4)]">Active</span>
                                                        @elseif($ms->status === 'expired')
                                                            <span class="inline-flex px-2 py-1 rounded-md text-xs font-semibold bg-red-500/10 text-red-400 border border-red-500/30 shadow-[0_0_8px_rgba(239,68,68,0.4)]">Expired</span>
                                                        @else
                                                            <span class="inline-flex px-2 py-1 rounded-md text-xs font-semibold bg-gray-700 text-gray-300 border border-gray-600 shadow-[0_0_8px_rgba(156,163,175,0.3)]">{{ ucfirst($ms->status) }}</span>
                                                        @endif
                                                    </td>
                                                    <td class="py-4 px-4 text-center break-words">
                                                        <div class="text-gray-300">{{ $ms->starts_at?->format('M j, Y') }}</div>
                                                        <div class="text-xs text-gray-500 mt-0.5">to {{ $ms->expires_at?->format('M j, Y') }}</div>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr><td colspan="3" class="py-10 text-center text-gray-500 italic">No membership history available.</td></tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
                
                <div class="p-5 border-t border-gray-700 bg-gray-800/40 shrink-0 flex flex-col sm:flex-row items-center justify-between gap-4">
                    <div class="text-sm text-gray-500 hidden sm:block">
                        Manage member record
                    </div>
                    
                    <div class="flex flex-wrap items-center justify-end gap-3 w-full sm:w-auto">
                        
                        <button wire:click="$set('showPaymentModal', true)" class="flex items-center justify-center gap-2 bg-green-600 hover:bg-green-500 text-white px-5 py-2.5 rounded-lg transition-colors font-semibold text-sm shadow-sm flex-1 sm:flex-none">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Record Payment
                        </button>

                        <button wire:click="openExtendModal({{ $selectedMember->id }})" class="flex items-center justify-center gap-2 bg-blue-600 hover:bg-blue-500 text-white px-5 py-2.5 rounded-lg transition-colors font-semibold text-sm shadow-sm flex-1 sm:flex-none">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                            Extend Expiry
                        </button>

                        <div class="hidden sm:block w-px h-8 bg-gray-600 mx-1"></div>

                        <button wire:click="closeViewModal" class="flex items-center justify-center gap-2 bg-gray-700 hover:bg-gray-600 border border-gray-600 text-white px-6 py-2.5 rounded-lg transition-all font-semibold text-sm shadow-sm w-full sm:w-auto">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                            Close
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

@if($showDeactivateModal && $selectedMember)
        <div class="fixed inset-0 z-[60] flex items-center justify-center bg-black/70 backdrop-blur-md p-4">
            
            <div class="relative w-full max-w-sm mx-auto overflow-hidden rounded-2xl p-[2px] bg-[#1a2c23]">
                
                <div class="absolute top-1/2 left-1/2 w-[250%] h-[250%] origin-center -translate-x-1/2 -translate-y-1/2 animate-[spin_8s_linear_infinite] opacity-100 spin-bg-orange"></div>
                
                <div class="relative z-10 bg-dark-card rounded-xl shadow-2xl p-6 w-full h-full isolate">
                    <div class="flex items-center gap-4 mb-4">
                        <div class="bg-amber-900/30 border border-amber-500/30 text-amber-500 p-3 rounded-full shadow-[0_0_15px_rgba(245,158,11,0.2)]">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                        </div>
                        <h2 class="text-xl font-bold text-white">Deactivate Member</h2>
                    </div>
                    <p class="text-gray-300 text-sm mb-6 leading-relaxed">Are you sure you want to deactivate <strong class="text-white">{{ $selectedMember->name }}</strong>? They will lose access to the portal immediately.</p>
                    <div class="flex justify-end gap-3">
                        <button wire:click="$set('showDeactivateModal', false)" class="px-4 py-2 text-sm font-medium text-gray-300 hover:text-white hover:bg-white/5 rounded-lg transition-colors focus:outline-none">Cancel</button>
                        <button wire:click="executeDeactivate" class="bg-orange-600 hover:bg-orange-500 text-white font-bold px-4 py-2 rounded-lg shadow-lg shadow-orange-900/50 transition-all focus:outline-none">Deactivate</button>
                    </div>
                </div>
            </div>
        </div>
    @endif

@if($showDeleteModal && $selectedMember)
        <div class="fixed inset-0 z-[60] flex items-center justify-center bg-black/70 backdrop-blur-md p-4">
            
            <div class="relative w-full max-w-sm mx-auto overflow-hidden rounded-2xl p-[2px] bg-[#1a2c23]">
                
                <div class="absolute top-1/2 left-1/2 w-[250%] h-[250%] origin-center -translate-x-1/2 -translate-y-1/2 animate-[spin_8s_linear_infinite] opacity-100 spin-bg-red"></div>
                
                <div class="relative z-10 bg-dark-card rounded-xl shadow-2xl p-6 w-full h-full isolate">
                    <div class="flex items-center gap-4 mb-4">
                        <div class="bg-red-900/30 border border-red-500/30 text-red-500 p-3 rounded-full shadow-[0_0_15px_rgba(239,68,68,0.2)]">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                        </div>
                        <h2 class="text-xl font-bold text-white">Delete Member</h2>
                    </div>
                    <p class="text-gray-300 text-sm mb-6 leading-relaxed">Are you sure you want to permanently delete <strong class="text-white">{{ $selectedMember->name }}</strong>? This action cannot be undone.</p>
                    <div class="flex justify-end gap-3">
                        <button wire:click="$set('showDeleteModal', false)" class="px-4 py-2 text-sm font-medium text-gray-300 hover:text-white hover:bg-white/5 rounded-lg transition-colors focus:outline-none">Cancel</button>
                        <button wire:click="executeDelete" class="bg-red-600 hover:bg-red-500 text-white font-bold px-4 py-2 rounded-lg shadow-lg shadow-red-900/50 transition-all focus:outline-none">Delete</button>
                    </div>
                </div>
            </div>
        </div>
    @endif

@if($showNotifyModal && $selectedMember)
        <div class="fixed inset-0 z-[60] flex items-center justify-center bg-black/70 backdrop-blur-md p-4">
            
            <div class="relative w-full max-w-sm mx-auto overflow-hidden rounded-2xl p-[2px] bg-[#1a2c23]">
                
                <div class="absolute top-1/2 left-1/2 w-[250%] h-[250%] origin-center -translate-x-1/2 -translate-y-1/2 animate-[spin_8s_linear_infinite] opacity-100 spin-bg-blue"></div>
                
                <div class="relative z-10 bg-dark-card rounded-xl shadow-2xl p-6 w-full h-full isolate">
                    <div class="flex items-center gap-4 mb-4">
                        <div class="bg-blue-900/30 border border-blue-500/30 text-blue-400 p-3 rounded-full shadow-[0_0_15px_rgba(59,130,246,0.2)]">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                            </svg>
                        </div>
                        <h2 class="text-xl font-bold text-white">Notify Expiry</h2>
                    </div>
                    <p class="text-gray-300 text-sm mb-6 leading-relaxed">Send an expiry notification to <strong class="text-white">{{ $selectedMember->name }}</strong>? They will receive a reminder about their membership expiration.</p>
                    <div class="flex justify-end gap-3">
                        <button wire:click="$set('showNotifyModal', false)" class="px-4 py-2 text-sm font-medium text-gray-300 hover:text-white hover:bg-white/5 rounded-lg transition-colors focus:outline-none">Cancel</button>
                        <button wire:click="executeNotify" class="bg-blue-600 hover:bg-blue-500 text-white font-bold px-4 py-2 rounded-lg shadow-lg shadow-blue-900/50 transition-all focus:outline-none">Send Notification</button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if($showExtendModal && $selectedMember)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-md">
            <div class="bg-dark-card border border-amber-500/50 rounded-xl shadow-2xl p-6 w-full max-w-lg mx-4">
                <div class="flex items-center gap-4 mb-6 border-b border-gray-700 pb-4">
                    <div class="bg-amber-900/30 text-amber-400 p-3 rounded-full">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold text-white">Extend Subscription</h2>
                        <p class="text-sm text-gray-400">Add plan to <strong class="text-white">{{ $selectedMember->name }}</strong></p>
                    </div>
                </div>

                <form wire:submit="saveExtension" class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-1">Select Membership Plan</label>
                        <select wire:model="extendPlanId" class="w-full bg-dark-page border border-gray-600 rounded-md px-3 py-2 text-white focus:outline-none focus:ring-1 focus:ring-amber-400" required>
                            <option value="" class="bg-dark-page">Select a plan...</option>
                            @foreach($plans as $plan)
                                <option value="{{ $plan->id }}" class="bg-dark-page">{{ $plan->name }} ({{ config('paymongo.currency', 'PHP') }} {{ number_format($plan->price, 2) }})</option>
                            @endforeach
                        </select>
                        @error('extendPlanId') <span class="text-xs text-red-400">{{ $message }}</span> @enderror
                    </div>
                    
                    <p class="text-xs text-gray-400 bg-dark-page p-3 rounded border border-gray-700">
                        <strong class="text-amber-400">Note:</strong> If the member has an active subscription, the new plan's duration will be added to their current expiration date.
                    </p>
                    
                    <div class="flex justify-end gap-3 mt-6 pt-4 border-t border-gray-700">
                        <button type="button" wire:click="closeExtendModal" class="px-4 py-2 text-sm text-gray-300 hover:text-white transition-colors">Cancel</button>
                        <button type="submit" class="bg-gradient-to-r from-amber-400 to-yellow-500 hover:from-amber-500 hover:to-yellow-600 text-black font-bold px-4 py-2 rounded-md shadow-sm transition-colors">Extend</button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    @if($showPaymentModal && $selectedMember)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-md">
            <div class="bg-dark-card border border-green-500/50 rounded-xl shadow-2xl p-6 w-full max-w-lg mx-4">
                <div class="flex items-center gap-4 mb-6 border-b border-gray-700 pb-4">
                    <div class="bg-green-900/30 text-green-400 p-3 rounded-full">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold text-white">Record Cash Payment</h2>
                        <p class="text-sm text-gray-400">For <strong class="text-white">{{ $selectedMember->name }}</strong></p>
                    </div>
                </div>

                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-1">Select Membership Plan</label>
                        <select wire:model="walkInPlanId" class="w-full bg-dark-page border border-gray-600 rounded-md px-3 py-2 text-white focus:outline-none focus:ring-1 focus:ring-green-400">
                            <option value="" class="bg-dark-page">Select plan…</option>
                            @foreach($plans as $plan)
                                <option value="{{ $plan->id }}" class="bg-dark-page">{{ $plan->name }} — {{ config('paymongo.currency', 'PHP') }} {{ number_format($plan->price, 0) }}</option>
                            @endforeach
                        </select>
                        @error('walkInPlanId') <span class="text-xs text-red-400 mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div class="bg-dark-page p-4 rounded-lg border border-gray-700 mt-4">
                        <label class="flex items-start gap-3 text-sm text-gray-300 cursor-pointer">
                            <input type="checkbox" wire:model.live="witnessedConsent" class="mt-1 shrink-0 w-4 h-4 text-green-500 bg-gray-800 border-gray-600 rounded focus:ring-green-500 focus:ring-2">
                            <span class="leading-relaxed">I confirm this member has physically read and signed the Membership Contract and Liability Waiver in person.</span>
                        </label>
                        @error('witnessedConsent') <span class="text-xs text-red-400 mt-2 block">{{ $message }}</span> @enderror
                    </div>
                    
                    <div class="flex justify-end gap-3 mt-6 pt-4 border-t border-gray-700">
                        <button type="button" wire:click="$set('showPaymentModal', false)" class="px-4 py-2 text-sm text-gray-300 hover:text-white transition-colors">Cancel</button>
                        <button type="button" wire:click="recordCashPayment" wire:loading.attr="disabled" class="bg-green-600 hover:bg-green-500 text-white font-bold px-5 py-2.5 rounded-lg shadow-sm transition-colors flex items-center gap-2">
                            <span wire:loading.remove wire:target="recordCashPayment">Record Payment</span>
                            <span wire:loading wire:target="recordCashPayment">Processing...</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>