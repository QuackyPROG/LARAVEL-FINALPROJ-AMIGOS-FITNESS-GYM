<div class="pb-24">
    <x-admin-splash target="send" />
    <div class="mb-6 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h1 class="text-3xl font-bold text-white mb-2">Announcements</h1>
            <p class="text-gray-300">Broadcast messages to all active members</p>
        </div>
        <div class="flex flex-wrap items-center gap-3">
            <div class="relative group">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none z-10">
                    <svg class="h-4 w-4 text-gray-600 group-hover:text-amber-500/50 transition-colors duration-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
                <input type="text" wire:model.live.debounce.300ms="search" placeholder="Search by subject..." class="bg-black border border-white/5 hover:border-amber-500/40 rounded-lg pl-9 pr-3 py-2 text-sm text-gray-200 focus:outline-none focus:ring-1 focus:ring-amber-500/50 transition-all duration-300 w-48 shadow-inner placeholder-gray-600">
            </div>

            <div class="relative group">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none z-10">
                    <svg class="h-4 w-4 text-gray-300 group-hover:text-amber-500/50 transition-colors duration-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                    </svg>
                </div>
                <select wire:model.live="filterStatus" class="appearance-none bg-black hover:bg-black/80 border border-white/5 hover:border-amber-500/30 rounded-lg pl-9 pr-10 py-2 text-sm text-gray-300 hover:text-gray-100 focus:outline-none focus:ring-1 focus:ring-amber-500/30 transition-all duration-300 shadow-inner cursor-pointer outline-none">
                    <option value="" class="bg-black text-gray-300">All Status</option>
                    <option value="sent" class="bg-black text-gray-300">Sent</option>
                    <option value="draft" class="bg-black text-gray-300">Draft</option>
                </select>
                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none z-10">
                    <svg class="h-4 w-4 text-gray-400 group-hover:text-amber-500/50 transition-colors duration-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </div>
            </div>
            
            <button wire:click="openCreate" class="bg-gradient-to-r from-amber-400 to-yellow-500 hover:from-amber-500 hover:to-yellow-600 transition-all duration-300 text-black font-bold text-sm px-4 py-2 rounded-lg shadow-[0_0_15px_rgba(251,191,36,0.2)] hover:shadow-[0_0_20px_rgba(251,191,36,0.4)] flex items-center gap-2 transform hover:-translate-y-0.5">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" /></svg>
                Compose
            </button>
        </div>
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
    
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/80 backdrop-blur-sm p-4">
        <div class="relative w-full max-w-3xl mx-auto group">
            <div class="absolute -inset-[1.5px] bg-gradient-to-r from-amber-300 via-yellow-600 to-amber-400 rounded-2xl gold-gradient-bg opacity-80 blur-[2px] transition-opacity duration-500"></div>
            
            <div class="relative bg-[#000000] rounded-2xl shadow-[0_0_40px_rgba(0,0,0,0.5)] p-8 w-full">
                
                <div class="flex items-start justify-between mb-8 border-b border-white/10 pb-6">
                    <div class="flex items-center gap-4">
                        <div class="bg-gradient-to-br from-amber-400/20 to-amber-600/20 border border-amber-500/30 text-amber-400 p-3 rounded-xl shadow-[0_0_20px_rgba(251,191,36,0.15)]">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-2xl font-extrabold text-white tracking-wide">Compose Announcement</h2>
                            <p class="text-sm text-gray-400 mt-1">Broadcast a message to your members</p>
                        </div>
                    </div>
                    <button type="button" wire:click="$set('showForm', false)" class="text-gray-500 hover:text-white bg-white/5 hover:bg-white/10 p-2 rounded-lg transition-all">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                
                <form wire:submit="send">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        
                        <div class="space-y-5">
                            <div>
                                <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1.5 ml-1">Subject</label>
                                <input type="text" wire:model="subject" placeholder="e.g. New Class Schedule Available"
                                    class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-amber-500/50 focus:border-amber-500/50 focus:bg-white/10 backdrop-blur-md transition-all shadow-inner" required>
                                @error('subject') <span class="text-xs text-red-400 mt-1 ml-1 block">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1.5 ml-1">Message Body</label>
                                <textarea wire:model="body" rows="7" placeholder="Share your announcement message..."
                                    class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-amber-500/50 focus:border-amber-500/50 focus:bg-white/10 backdrop-blur-md transition-all shadow-inner resize-none" required></textarea>
                                @error('body') <span class="text-xs text-red-400 mt-1 ml-1 block">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div class="bg-white/[0.02] border border-white/5 rounded-xl p-5 space-y-5 h-fit">
                            <div>
                                <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1.5 ml-1">Send To</label>
                                <select wire:model.live="recipientFilter" class="w-full bg-black border border-white/10 rounded-lg px-3 py-2.5 text-white focus:outline-none focus:ring-2 focus:ring-amber-500/50 focus:border-amber-500/50 transition-all cursor-pointer">
                                    <option value="all" class="bg-black text-white">All Active Members</option>
                                    <option value="plan" class="bg-black text-white">By Plan</option>
                                </select>
                            </div>

                            @if($recipientFilter === 'plan')
                            <div>
                                <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1.5 ml-1">Select Plan</label>
                                <select wire:model.live="planId" class="w-full bg-black border border-white/10 rounded-lg px-3 py-2.5 text-white focus:outline-none focus:ring-2 focus:ring-amber-500/50 focus:border-amber-500/50 transition-all cursor-pointer">
                                    <option value="" class="bg-black text-white">Select a plan…</option>
                                    @foreach($plans as $plan)<option value="{{ $plan->id }}" class="bg-black text-white">{{ $plan->name }}</option>@endforeach
                                </select>
                            </div>
                            @endif

                            <div class="relative overflow-hidden bg-black border border-white/10 rounded-xl p-4 mt-6 group">
                                <div class="absolute inset-0 bg-gradient-to-r from-amber-500/10 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                                <div class="flex items-center justify-between relative z-10">
                                    <div class="flex items-center gap-3">
                                        <div class="bg-white/5 border border-white/10 p-2.5 rounded-lg">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-amber-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                            </svg>
                                        </div>
                                        <div>
                                            <h4 class="text-sm font-semibold text-gray-200">Total Recipients</h4>
                                            <p class="text-xs text-gray-500 mt-0.5">Receiving this message</p>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <span class="text-3xl font-black text-amber-400">{{ $recipientCount }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                    </div>
                    
                    <div class="flex justify-end gap-3 mt-8 pt-6 border-t border-white/10">
                        <button type="button" wire:click="$set('showForm', false)" class="px-5 py-2.5 text-sm font-semibold text-gray-300 bg-white/5 hover:bg-white/10 border border-white/10 rounded-xl transition-all">Cancel</button>
                        
                        <button type="submit" class="bg-gradient-to-r from-amber-400 to-yellow-600 hover:from-amber-500 hover:to-yellow-700 text-black font-bold px-8 py-2.5 rounded-xl shadow-[0_0_15px_rgba(251,191,36,0.3)] hover:shadow-[0_0_20px_rgba(251,191,36,0.5)] transition-all transform hover:-translate-y-0.5">
                            Send
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif

    <div class="bg-[#1a1a1a]/95 backdrop-blur-md border border-white/10 rounded-2xl shadow-2xl overflow-visible">
        <table class="w-full text-sm border-separate border-spacing-0">
            <thead class="border-b border-white/10 bg-white/[0.04]">
                <tr>
                    <th class="text-left text-xs font-bold text-gray-200 uppercase tracking-[0.15em] py-5 px-6">Subject</th>
                    <th class="text-left text-xs font-bold text-gray-200 uppercase tracking-[0.15em] py-5 px-4">Sent By</th>
                    <th class="text-left text-xs font-bold text-gray-200 uppercase tracking-[0.15em] py-5 px-6">Sent At</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-white/10">
                @forelse($announcements as $a)
                    <tr class="hover:bg-white/[0.03] transition-all duration-200 group">
                        <td class="py-5 px-6 font-bold text-white">{{ $a->subject }}</td>
                        <td class="py-5 px-4 text-gray-300 font-semibold">{{ $a->admin?->name ?? 'System' }}</td>
                        <td class="py-5 px-6 text-gray-400">{{ $a->sent_at?->format('M j, Y H:i') ?? '—' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="py-12 text-center text-gray-400">
                            <div class="flex justify-center mb-3">
                                <svg class="w-10 h-10 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11 5.882V19.24a1.961 1.961 0 01-2.773 1.753L5.848 18.75m6.153-4.868V5.882m0 0a1.961 1.961 0 00-2.773-1.753m2.773 1.753l2.813-1.753a1.961 1.961 0 012.773 1.753V19.24m0 0L19.7 8.971m0 0a1.961 1.961 0 012.773-1.753m-2.773 1.753l2.813-1.753a1.961 1.961 0 012.773 1.753v13.286a1.961 1.961 0 01-2.773 1.753M5.848 18.75l2.813 1.753a1.961 1.961 0 002.773-1.753V5.882m0 0L7.921 3.129a1.961 1.961 0 00-2.773 1.753v13.286z" />
                                </svg>
                            </div>
                            <p class="font-bold text-gray-300">No announcements sent</p>
                            <p class="text-xs mt-1 text-gray-500">Compose a message to broadcast to all active members</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
