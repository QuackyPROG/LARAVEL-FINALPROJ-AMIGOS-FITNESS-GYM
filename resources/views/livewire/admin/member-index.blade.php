<div>
    <div class="mb-6 flex justify-between items-center gap-4">
        <div>
            <h1 class="text-3xl font-bold text-amber-400 mb-2">Members</h1>
            <p class="text-gray-300">All registered members</p>
        </div>
        <div class="flex items-center gap-3">
            <input type="text" wire:model.live.debounce.300ms="search"
                placeholder="Search name or email…"
                class="bg-dark-card text-white placeholder-gray-500 px-3 py-2 text-sm rounded-md focus:outline-none focus:ring-1 focus:ring-amber-400 w-64">
            <select wire:model.live="statusFilter" class="bg-dark-card text-white px-3 py-2 text-sm rounded-md focus:outline-none focus:ring-1 focus:ring-amber-400 w-40">
                <option value="" class="bg-dark-page text-white">All statuses</option>
                <option value="active" class="bg-dark-page text-white">Active</option>
                <option value="inactive" class="bg-dark-page text-white">Inactive</option>
                <option value="pending" class="bg-dark-page text-white">Pending</option>
            </select>
            <button wire:click="openAddModal" class="bg-gradient-to-r from-amber-400 to-yellow-500 hover:from-amber-500 hover:to-yellow-600 transition-colors text-black font-bold text-xs px-2.5 py-1 rounded shadow-sm">+ Add Walk-in Member</button>
        </div>
    </div>

    @if(session('success'))
        <div class="bg-green-900/20 border border-green-700 text-green-300 text-sm px-4 py-3 rounded-md mb-4">{{ session('success') }}</div>
    @endif

    <div class="bg-dark-card border border-gray-600 rounded-md overflow-hidden">
        <table class="w-full text-sm">
                <thead class="border-b border-gray-600 bg-dark-card">
                    <tr>
                        <th class="text-left text-xs font-medium text-gray-300 uppercase tracking-wide py-3 px-4">Name</th>
                        <th class="text-left text-xs font-medium text-gray-300 uppercase tracking-wide py-3 px-4">Email</th>
                        <th class="text-left text-xs font-medium text-gray-300 uppercase tracking-wide py-3 px-4">Status</th>
                        <th class="text-left text-xs font-medium text-gray-300 uppercase tracking-wide py-3 px-4">Plan</th>
                        <th class="text-left text-xs font-medium text-gray-300 uppercase tracking-wide py-3 px-4">Expires</th>
                        <th class="text-left text-xs font-medium text-gray-300 uppercase tracking-wide py-3 px-4">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-dark-card">
                    @forelse($members as $member)
                        <tr class="border-b border-gray-600 hover:bg-gray-700 transition-colors">
                            <td class="py-3 px-4">
                                <a href="{{ route('admin.members.show', $member) }}" class="font-medium text-white underline">{{ $member->name }}</a>
                            </td>
                            <td class="py-3 px-4 text-gray-300">{{ $member->email }}</td>
                            <td class="py-3 px-4">
                                @if($member->status === 'active')
                                    <span class="inline-flex px-2 py-0.5 rounded text-xs font-medium bg-green-900/20 text-green-300 border border-green-700">Active</span>
                                @elseif($member->status === 'inactive')
                                    <span class="inline-flex px-2 py-0.5 rounded text-xs font-medium bg-red-900/20 text-red-300 border border-red-700">Inactive</span>
                                @else
                                    <span class="inline-flex px-2 py-0.5 rounded text-xs font-medium bg-gray-700 text-gray-300 border border-gray-600">{{ ucfirst($member->status) }}</span>
                                @endif
                            </td>
                            <td class="py-3 px-4 text-gray-300">{{ $member->activeMembership?->plan?->name ?? '—' }}</td>
                            <td class="py-3 px-4 text-gray-400">{{ $member->activeMembership?->expires_at?->format('M j, Y') ?? '—' }}</td>
                            <td class="py-3 px-4">
                                <div class="flex items-center gap-3">
                                    <button wire:click="openViewModal({{ $member->id }})" title="View Member" class="text-gray-400 hover:text-amber-400 transition-colors">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                          <path d="M10 12a2 2 0 100-4 2 2 0 000 4z" />
                                          <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd" />
                                        </svg>
                                    </button>
                                    @if($member->status === 'active')
                                    <button wire:click="confirmDeactivate({{ $member->id }})" title="Deactivate Member" class="text-gray-400 hover:text-orange-400 transition-colors">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                          <path fill-rule="evenodd" d="M13.477 14.89A6 6 0 015.11 6.524l8.367 8.368zm1.414-1.414L6.524 5.11a6 6 0 018.367 8.367zM18 10a8 8 0 11-16 0 8 8 0 0116 0z" clip-rule="evenodd" />
                                        </svg>
                                    </button>
                                    @endif
                                    <button wire:click="confirmDelete({{ $member->id }})" title="Delete Member" class="text-gray-400 hover:text-red-500 transition-colors">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                          <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-8 text-center text-gray-400">
                                <p>No members found</p>
                                <p class="text-xs mt-0.5">Try adjusting your search or status filter</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
    </div>

    <div class="mt-4">{{ $members->links() }}</div>

    <!-- Modals -->
    <!-- Add Member Modal -->
    @if($showAddModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-md">
            <div class="bg-dark-card border border-gray-600 rounded-xl shadow-2xl p-6 w-full max-w-lg mx-4">
                <h2 class="text-xl font-bold text-white mb-4">Add Walk-in Member</h2>
                
                @if($tempPasswordResult)
                    <div class="bg-green-900/30 border border-green-600 text-green-300 p-4 rounded-md mb-4 text-sm">
                        <p class="font-bold mb-1">Member Created Successfully!</p>
                        <p>Temporary Password: <code class="bg-black px-2 py-1 rounded text-white">{{ $tempPasswordResult }}</code></p>
                        <p class="text-xs mt-2 opacity-80">Please copy this password. It won't be shown again.</p>
                        <div class="mt-4 text-right">
                            <button wire:click="closeAddModal" class="bg-gray-800 hover:bg-gray-700 text-white px-4 py-2 rounded-md transition-colors">Close</button>
                        </div>
                    </div>
                @else
                    <form wire:submit="saveMember" class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-1">Full Name</label>
                            <input type="text" wire:model="addName" class="w-full bg-dark-page border border-gray-600 rounded-md px-3 py-2 text-white focus:outline-none focus:ring-1 focus:ring-amber-400" required>
                            @error('addName') <span class="text-xs text-red-400">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-1">Email Address</label>
                            <input type="email" wire:model="addEmail" class="w-full bg-dark-page border border-gray-600 rounded-md px-3 py-2 text-white focus:outline-none focus:ring-1 focus:ring-amber-400" required>
                            @error('addEmail') <span class="text-xs text-red-400">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-1">Membership Plan</label>
                            <select wire:model="addPlanId" class="w-full bg-dark-page border border-gray-600 rounded-md px-3 py-2 text-white focus:outline-none focus:ring-1 focus:ring-amber-400" required>
                                <option value="" class="bg-dark-page">Select a plan...</option>
                                @foreach($plans as $plan)
                                    <option value="{{ $plan->id }}" class="bg-dark-page">{{ $plan->name }} ({{ config('paymongo.currency', 'PHP') }} {{ number_format($plan->price, 2) }})</option>
                                @endforeach
                            </select>
                            @error('addPlanId') <span class="text-xs text-red-400">{{ $message }}</span> @enderror
                        </div>
                        
                        <div class="flex justify-end gap-3 mt-6 pt-4 border-t border-gray-700">
                            <button type="button" wire:click="closeAddModal" class="px-4 py-2 text-sm text-gray-300 hover:text-white transition-colors">Cancel</button>
                            <button type="submit" class="bg-gradient-to-r from-amber-400 to-yellow-500 hover:from-amber-500 hover:to-yellow-600 text-black font-bold px-4 py-2 rounded-md shadow-sm transition-colors">Create Member</button>
                        </div>
                    </form>
                @endif
            </div>
        </div>
    @endif

    <!-- View Member Modal -->
    @if($showViewModal && $selectedMember)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-md">
            <div class="bg-dark-card border border-gray-600 rounded-xl shadow-2xl p-6 w-full max-w-4xl mx-4 max-h-[90vh] overflow-y-auto">
                <div class="flex justify-between items-start mb-6 border-b border-gray-700 pb-4">
                    <div class="flex items-center gap-4">
                        <div>
                            <h2 class="text-2xl font-bold text-white">{{ $selectedMember->name }}</h2>
                            <p class="text-gray-400 text-sm">{{ $selectedMember->email }}</p>
                        </div>
                    </div>
                    <button wire:click="closeViewModal" class="text-gray-400 hover:text-white transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <!-- DETAILS CARD -->
                    <div class="md:col-span-2 space-y-4">
                        <div class="grid grid-cols-2 gap-4">
                            <!-- Status Card -->
                            @if($selectedMember->status === 'active')
                                <div class="p-4 rounded-xl border border-green-500/50 bg-green-900/10 flex items-center gap-4">
                                    <div class="bg-green-500/20 p-2 rounded-lg text-green-400">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    </div>
                                    <div>
                                        <span class="text-xs text-gray-500 uppercase tracking-wider block mb-0.5 font-semibold">Account Status</span>
                                        <span class="text-white font-medium capitalize text-lg">{{ $selectedMember->status }}</span>
                                    </div>
                                </div>
                            @else
                                <div class="p-4 rounded-xl border border-red-500/50 bg-red-900/10 flex items-center gap-4">
                                    <div class="bg-red-500/20 p-2 rounded-lg text-red-400">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    </div>
                                    <div>
                                        <span class="text-xs text-gray-500 uppercase tracking-wider block mb-0.5 font-semibold">Account Status</span>
                                        <span class="text-white font-medium capitalize text-lg">{{ $selectedMember->status }}</span>
                                    </div>
                                </div>
                            @endif

                            <!-- Joined Card -->
                            <div class="p-4 rounded-xl border border-blue-500/50 bg-blue-900/10 flex items-center gap-4">
                                <div class="bg-blue-500/20 p-2 rounded-lg text-blue-400">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                </div>
                                <div>
                                    <span class="text-xs text-gray-500 uppercase tracking-wider block mb-0.5 font-semibold">Joined</span>
                                    <span class="text-white font-medium text-lg">{{ $selectedMember->created_at->format('M j, Y') }}</span>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Membership Details -->
                        <div class="bg-dark-page p-5 rounded-xl border border-gray-700">
                            <div class="flex items-center gap-3 mb-4 pb-4 border-b border-gray-700/50">
                                <div class="bg-amber-500/20 p-2 rounded-lg text-amber-400">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z" />
                                    </svg>
                                </div>
                                <h3 class="text-base font-semibold text-white uppercase tracking-wide">Current Membership</h3>
                            </div>

                            @if($selectedMember->activeMembership)
                                <div class="grid grid-cols-2 gap-y-6 gap-x-4">
                                    <div>
                                        <span class="text-xs text-gray-500 uppercase tracking-wider block mb-1">Plan Name</span>
                                        <span class="text-white font-medium text-lg">{{ $selectedMember->activeMembership->plan->name }}</span>
                                    </div>
                                    <div>
                                        <span class="text-xs text-gray-500 uppercase tracking-wider block mb-1">Payment Reference</span>
                                        <span class="text-white font-medium">{{ $selectedMember->activeMembership->payment_ref ?? 'N/A' }}</span>
                                    </div>
                                    
                                    <div class="col-span-2 p-3 rounded-lg border border-blue-500/30 bg-blue-900/10 flex items-center gap-3">
                                        <div class="text-blue-400">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                        </div>
                                        <div>
                                            <span class="text-xs text-blue-300 uppercase tracking-wider block mb-0.5">Expiration Date</span>
                                            <span class="text-white font-medium">{{ $selectedMember->activeMembership->expires_at->format('M j, Y') }}</span>
                                        </div>
                                    </div>
                                </div>
                            @else
                                <div class="flex items-center justify-center p-6 text-gray-500">
                                    <p>No active membership plan attached.</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- ACTIONS CARD -->
                    <div class="md:col-span-1 space-y-3 bg-dark-page/50 p-5 rounded-xl border border-gray-700 h-fit">
                        <h3 class="text-sm font-semibold text-gray-400 uppercase tracking-wider mb-4 border-b border-gray-700/50 pb-2">Quick Actions</h3>

                        <!-- Extend Subscription Action -->
                        <button wire:click="openExtendModal({{ $selectedMember->id }})" class="w-full flex items-center gap-3 p-3 rounded-lg border border-gray-600 hover:border-amber-400 bg-dark-card hover:bg-amber-900/10 transition-all text-left group">
                            <div class="bg-gray-700 group-hover:bg-amber-500/20 p-2 rounded-md text-gray-400 group-hover:text-amber-400 transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                </svg>
                            </div>
                            <div>
                                <span class="block text-sm font-medium text-white group-hover:text-amber-400 transition-colors">Extend Subscription</span>
                                <span class="block text-xs text-gray-400">Add plan to member</span>
                            </div>
                        </button>

                        <!-- Deactivate Action -->
                        @if($selectedMember->status === 'active')
                            <button wire:click="confirmDeactivate({{ $selectedMember->id }})" class="w-full flex items-center gap-3 p-3 rounded-lg border border-orange-500/30 hover:border-orange-500 bg-orange-900/10 hover:bg-orange-900/30 transition-all text-left group">
                                <div class="bg-orange-500/20 p-2 rounded-md text-orange-400">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
                                    </svg>
                                </div>
                                <div>
                                    <span class="block text-sm font-medium text-orange-400">Deactivate</span>
                                    <span class="block text-xs text-orange-500/70">Suspend access</span>
                                </div>
                            </button>
                        @endif

                        <!-- Delete Action -->
                        <button wire:click="confirmDelete({{ $selectedMember->id }})" class="w-full flex items-center gap-3 p-3 rounded-lg border border-red-500/30 hover:border-red-500 bg-red-900/10 hover:bg-red-900/30 transition-all text-left group">
                            <div class="bg-red-500/20 p-2 rounded-md text-red-400">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                            </div>
                            <div>
                                <span class="block text-sm font-medium text-red-400">Delete Member</span>
                                <span class="block text-xs text-red-500/70">Permanent removal</span>
                            </div>
                        </button>
                    </div>
                </div>
                
                <div class="mt-6 pt-4 border-t border-gray-700 flex justify-end">
                    <button wire:click="closeViewModal" class="bg-gray-800 hover:bg-gray-700 text-white px-6 py-2 rounded-md transition-colors font-medium shadow-sm">Close Dashboard</button>
                </div>
            </div>
        </div>
    @endif

    <!-- Deactivate Confirm Modal -->
    @if($showDeactivateModal && $selectedMember)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-md">
            <div class="bg-dark-card border border-orange-500/50 rounded-xl shadow-2xl p-6 w-full max-w-sm mx-4">
                <div class="flex items-center gap-4 mb-4">
                    <div class="bg-orange-900/30 text-orange-400 p-3 rounded-full">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                    </div>
                    <h2 class="text-xl font-bold text-white">Deactivate Member</h2>
                </div>
                <p class="text-gray-300 text-sm mb-6">Are you sure you want to deactivate <strong class="text-white">{{ $selectedMember->name }}</strong>? They will no longer be able to access their account.</p>
                <div class="flex justify-end gap-3">
                    <button wire:click="$set('showDeactivateModal', false)" class="px-4 py-2 text-sm text-gray-300 hover:text-white transition-colors">Cancel</button>
                    <button wire:click="executeDeactivate" class="bg-orange-500 hover:bg-orange-600 text-white font-bold px-4 py-2 rounded-md transition-colors">Deactivate</button>
                </div>
            </div>
        </div>
    @endif

    <!-- Delete Confirm Modal -->
    @if($showDeleteModal && $selectedMember)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-md">
            <div class="bg-dark-card border border-red-500/50 rounded-xl shadow-2xl p-6 w-full max-w-sm mx-4">
                <div class="flex items-center gap-4 mb-4">
                    <div class="bg-red-900/30 text-red-400 p-3 rounded-full">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                    </div>
                    <h2 class="text-xl font-bold text-white">Delete Member</h2>
                </div>
                <p class="text-gray-300 text-sm mb-6">Are you sure you want to permanently delete <strong class="text-white">{{ $selectedMember->name }}</strong>? This action cannot be undone.</p>
                <div class="flex justify-end gap-3">
                    <button wire:click="$set('showDeleteModal', false)" class="px-4 py-2 text-sm text-gray-300 hover:text-white transition-colors">Cancel</button>
                    <button wire:click="executeDelete" class="bg-red-600 hover:bg-red-700 text-white font-bold px-4 py-2 rounded-md transition-colors">Delete</button>
                </div>
            </div>
        </div>
    @endif

    <!-- Extend Subscription Modal -->
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
</div>
