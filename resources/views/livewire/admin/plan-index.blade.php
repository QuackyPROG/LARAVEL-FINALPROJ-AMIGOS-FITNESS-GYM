<div>
    <x-admin-splash target="save, toggleActive" />
    <div class="flex items-start justify-between mb-6">
        <div>
            <h1 class="mb-2 text-3xl font-bold text-white">Membership Plans</h1>
            <p class="text-gray-300">Manage plans shown on the public site</p>
        </div>
        <button wire:click="openCreate" class="inline-flex items-center gap-2 px-4 py-2 mt-2 text-sm font-bold text-gray-900 transition-all duration-300 rounded-lg shadow-lg bg-gradient-to-r from-amber-400 via-yellow-500 to-amber-500 hover:from-amber-300 hover:via-yellow-400 hover:to-amber-400 shadow-yellow-500/20 hover:shadow-yellow-500/40 hover:-translate-y-0.5">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
            </svg>
            New Plan
        </button>
    </div>

    @if($showForm)
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
        </style>
        
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/70 backdrop-blur-sm">
            <div class="relative w-full max-w-2xl mx-auto group">
                <div class="absolute -inset-[1.5px] bg-gradient-to-r from-amber-300 via-yellow-600 to-amber-400 rounded-2xl gold-gradient-bg opacity-80 blur-[2px] transition-opacity duration-500"></div>
                
                <div class="relative bg-[#000000] rounded-2xl shadow-[0_0_40px_rgba(0,0,0,0.5)] p-8 w-full">
                    
                    <div class="flex items-center mb-8 text-left">
                        <div class="flex-shrink-0 mr-4 bg-gradient-to-br from-amber-400/20 to-yellow-600/20 border border-amber-500/30 text-amber-400 p-3.5 rounded-full shadow-[0_0_20px_rgba(251,191,36,0.15)]">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4" />
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-2xl font-extrabold tracking-wide text-white">{{ $editingId ? 'Edit Plan' : 'New Plan' }}</h2>
                            <p class="text-sm text-gray-400 mt-1">{{ $editingId ? 'Update membership details' : 'Create a new membership plan' }}</p>
                        </div>
                    </div>
                    
                    <form wire:submit="save">
                        <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                            
                            <div class="space-y-5">
                                <div>
                                    <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1.5 ml-1">Plan Name</label>
                                    <input type="text" wire:model="name" placeholder="e.g. Premium Plus"
                                        class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-2.5 text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-amber-500/50 focus:border-amber-500/50 focus:bg-white/10 backdrop-blur-md transition-all shadow-inner" required>
                                    @error('name') <span class="block mt-1 ml-1 text-xs text-red-400">{{ $message }}</span> @enderror
                                </div>

                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1.5 ml-1">Duration (days)</label>
                                        <input type="number" wire:model="durationDays" placeholder="30"
                                            class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-2.5 text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-amber-500/50 focus:border-amber-500/50 focus:bg-white/10 backdrop-blur-md transition-all shadow-inner" required>
                                        @error('durationDays') <span class="block mt-1 ml-1 text-xs text-red-400">{{ $message }}</span> @enderror
                                    </div>

                                    <div>
                                        <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1.5 ml-1">Price (₱)</label>
                                        <input type="number" wire:model="price" step="0.01" placeholder="1500"
                                            class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-2.5 text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-amber-500/50 focus:border-amber-500/50 focus:bg-white/10 backdrop-blur-md transition-all shadow-inner" required>
                                        @error('price') <span class="block mt-1 ml-1 text-xs text-red-400">{{ $message }}</span> @enderror
                                    </div>
                                </div>

                                <div class="flex items-center gap-3 bg-white/5 border border-white/10 rounded-xl px-4 py-3">
                                    <input type="checkbox" wire:model="isActive" id="isActive" class="rounded border-white/20 text-amber-500 focus:ring-amber-500 bg-white/5">
                                    <label for="isActive" class="flex-1 text-sm text-gray-300 cursor-pointer">Show on public site</label>
                                </div>
                            </div>

                            <div class="flex flex-col">
                                <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1.5 ml-1">Benefits (one per line)</label>
                                <textarea wire:model="benefitsRaw" placeholder="Unlimited gym access&#10;Free yoga class per week&#10;Personal trainer consultation"
                                    class="flex-1 w-full h-full min-h-[150px] resize-none bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-amber-500/50 focus:border-amber-500/50 focus:bg-white/10 backdrop-blur-md transition-all shadow-inner"></textarea>
                                @error('benefitsRaw') <span class="block mt-1 ml-1 text-xs text-red-400">{{ $message }}</span> @enderror
                            </div>
                            
                        </div>
                        
                        <div class="flex items-center justify-end gap-3 mt-8 pt-6 border-t border-white/10">
                            <button type="button" wire:click="cancel" class="px-5 py-2.5 text-sm font-semibold text-gray-300 transition-all border rounded-lg bg-white/5 hover:bg-white/10 border-white/10">
                                Cancel
                            </button>
                            <button type="submit" class="px-6 py-2.5 text-sm font-bold text-gray-900 transition-all transform rounded-lg bg-gradient-to-r from-amber-400 to-yellow-600 hover:from-amber-500 hover:to-yellow-700 shadow-[0_0_20px_rgba(251,191,36,0.2)] hover:shadow-[0_0_25px_rgba(251,191,36,0.4)] hover:-translate-y-0.5">
                                {{ $editingId ? 'Update Plan' : 'Create Plan' }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif

    <div class="bg-white/5 backdrop-blur-md border border-white/10 rounded-xl shadow-xl overflow-hidden">
        <table class="w-full text-sm">
            <thead class="border-b border-white/10 bg-white/5">
                <tr>
                    <th class="px-4 py-3.5 text-xs font-semibold tracking-wider text-left text-gray-200 uppercase first:rounded-tl-xl">Name</th>
                    <th class="px-4 py-3.5 text-xs font-semibold tracking-wider text-left text-gray-200 uppercase">Duration</th>
                    <th class="px-4 py-3.5 text-xs font-semibold tracking-wider text-left text-gray-200 uppercase">Price</th>
                    <th class="px-4 py-3.5 text-xs font-semibold tracking-wider text-left text-gray-200 uppercase">Status</th>
                    <th class="px-4 py-3.5 text-xs font-semibold tracking-wider text-center text-gray-200 uppercase last:rounded-tr-xl">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-white/10">
                @forelse($plans as $plan)
                    <tr class="transition-colors hover:bg-white/5 group">
                        <td class="px-4 py-3.5 font-medium text-white">{{ $plan->name }}</td>
                        <td class="px-4 py-3.5 text-gray-300">{{ $plan->duration_days }} days</td>
                        <td class="px-4 py-3.5 text-gray-300">₱{{ number_format($plan->price, 2) }}</td>
                        <td class="px-4 py-3.5">
                            @if($plan->is_active)
                                <span class="inline-flex px-2 py-0.5 rounded text-xs font-medium bg-green-900/40 text-green-400 border border-green-700/50">Active</span>
                            @else
                                <span class="inline-flex px-2 py-0.5 rounded text-xs font-medium bg-white/5 text-gray-400 border border-white/10">Inactive</span>
                            @endif
                        </td>
                        <td class="px-4 py-3.5">
                            <div class="flex items-center justify-center gap-2">
                                <button wire:click="openEdit({{ $plan->id }})" class="p-1.5 text-gray-400 bg-white/5 rounded hover:text-amber-400 hover:bg-white/10 border border-white/10 transition-all focus:outline-none focus:ring-2 focus:ring-amber-500/50" title="Edit Plan">
                                    <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                    </svg>
                                </button>
                                
                                <button wire:click="toggleActive({{ $plan->id }})" 
                                    class="p-1.5 rounded border transition-all focus:outline-none focus:ring-2 {{ $plan->is_active ? 'text-green-400 bg-green-900/30 border-green-700/50 hover:bg-green-900/50 focus:ring-green-500/50' : 'text-gray-400 bg-white/5 border-white/10 hover:text-green-400 hover:bg-white/10 focus:ring-amber-500/50' }}" 
                                    title="{{ $plan->is_active ? 'Deactivate (Hide)' : 'Activate (Show)' }}">
                                    @if($plan->is_active)
                                        <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                    @else
                                        <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                                        </svg>
                                    @endif
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="py-12 text-center text-gray-500">
                            <div class="flex justify-center mb-3 text-gray-600">
                                <svg class="w-10 h-10" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                                </svg>
                            </div>
                            <p class="font-medium text-gray-400">No plans yet</p>
                            <p class="mt-1 text-sm text-gray-500">Create your first membership plan to get started</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>