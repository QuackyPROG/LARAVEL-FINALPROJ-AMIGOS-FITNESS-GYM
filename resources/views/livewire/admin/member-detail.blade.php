<div>
    <x-admin-splash target="extendExpiry, recordCashPayment, deactivate" />
    <div class="flex items-center gap-4 mb-8">
        <a href="{{ route('admin.members.index') }}" class="text-sm text-gray-400 underline hover:text-white">← Back</a>
        <div>
            <h1 class="text-3xl font-bold text-white">{{ $member->name }}</h1>
            <p class="text-sm text-gray-300 mt-0.5">Member #{{ $member->id }}</p>
        </div>
    </div>

    @if(session('error'))
        <div class="bg-red-900/20 border border-red-700 text-red-300 text-sm px-4 py-3 rounded-md mb-4">{{ session('error') }}</div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="col-span-2 space-y-4">

            {{-- Profile Info --}}
            <div class="bg-white/5 backdrop-blur-md border border-white/10 rounded-xl shadow-xl p-5 transition-all">
                <h2 class="text-sm font-semibold text-gray-300 uppercase tracking-wide mb-4">Profile</h2>
                <div class="grid grid-cols-2 gap-x-6 gap-y-3">
                    <div class="flex flex-col gap-0.5">
                        <span class="text-xs text-gray-400 uppercase tracking-wide">Email</span>
                        <p class="text-sm font-medium text-white">{{ $member->email }}</p>
                    </div>
                    <div class="flex flex-col gap-0.5">
                        <span class="text-xs text-gray-400 uppercase tracking-wide">Status</span>
                        <p class="text-sm font-medium text-white">{{ ucfirst($member->status) }}</p>
                    </div>
                    <div class="flex flex-col gap-0.5">
                        <span class="text-xs text-gray-400 uppercase tracking-wide">Role</span>
                        <p class="text-sm font-medium text-white">{{ ucfirst($member->role) }}</p>
                    </div>
                    <div class="flex flex-col gap-0.5">
                        <span class="text-xs text-gray-400 uppercase tracking-wide">Joined</span>
                        <p class="text-sm font-medium text-white">{{ $member->created_at->format('M j, Y') }}</p>
                    </div>
                </div>
            </div>

            {{-- Membership History --}}
            <div class="bg-white/5 backdrop-blur-md border border-white/10 rounded-xl shadow-xl overflow-hidden transition-all">
                <div class="px-5 py-4 border-b border-white/10 bg-white/5">
                    <h2 class="text-sm font-semibold text-white">Membership History</h2>
                </div>
                <table class="w-full text-sm">
                    <thead class="border-b border-white/10 bg-white/5">
                        <tr>
                            <th class="text-left text-xs font-medium text-gray-200 uppercase tracking-wider py-3 px-4">Plan</th>
                            <th class="text-left text-xs font-medium text-gray-200 uppercase tracking-wider py-3 px-4">Status</th>
                            <th class="text-left text-xs font-medium text-gray-200 uppercase tracking-wider py-3 px-4">Starts</th>
                            <th class="text-left text-xs font-medium text-gray-200 uppercase tracking-wider py-3 px-4">Expires</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/10">
                        @forelse($memberships as $ms)
                            <tr class="hover:bg-white/5 transition-colors">
                                <td class="py-3 px-4 font-medium text-white">{{ $ms->plan?->name ?? '—' }}</td>
                                <td class="py-3 px-4">
                                    @if($ms->status === 'active')
                                        <span class="inline-flex px-2 py-0.5 rounded text-xs font-medium bg-green-900/20 text-green-300 border border-green-700">Active</span>
                                    @elseif($ms->status === 'expired')
                                        <span class="inline-flex px-2 py-0.5 rounded text-xs font-medium bg-red-900/20 text-red-300 border border-red-700">Expired</span>
                                    @else
                                        <span class="inline-flex px-2 py-0.5 rounded text-xs font-medium bg-white/5 text-gray-300 border border-white/10">{{ ucfirst($ms->status) }}</span>
                                    @endif
                                </td>
                                <td class="py-3 px-4 text-gray-400">{{ $ms->starts_at?->format('M j, Y') }}</td>
                                <td class="py-3 px-4 text-gray-400">{{ $ms->expires_at?->format('M j, Y') }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="py-6 text-center text-gray-400 text-sm">No membership history.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Legal Agreements / Consent History --}}
            <div class="bg-white/5 backdrop-blur-md border border-white/10 rounded-xl shadow-xl overflow-hidden transition-all">
                <div class="px-5 py-4 border-b border-white/10 bg-white/5">
                    <h2 class="text-sm font-semibold text-white">Legal Agreements</h2>
                </div>
                @if($consents->isEmpty())
                    <div class="p-5 text-sm text-gray-400 bg-transparent rounded-b-xl">No consent records found.</div>
                @else
                    <div class="overflow-visible rounded-b-xl">
                        <table class="w-full text-sm">
                        <thead class="border-b border-white/10 bg-white/5">
                            <tr>
                                <th class="text-left text-xs font-medium text-gray-200 uppercase tracking-wider py-3 px-4">Document</th>
                                <th class="text-left text-xs font-medium text-gray-200 uppercase tracking-wider py-3 px-4">Version</th>
                                <th class="text-left text-xs font-medium text-gray-200 uppercase tracking-wider py-3 px-4">Method</th>
                                <th class="text-left text-xs font-medium text-gray-200 uppercase tracking-wider py-3 px-4">Accepted At</th>
                                <th class="text-left text-xs font-medium text-gray-200 uppercase tracking-wider py-3 px-4">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-white/10">
                            @foreach($consents as $consent)
                                @php
                                    $docTitle = match($consent->document_key) {
                                        'legal.terms_and_conditions' => 'Terms & Conditions',
                                        'legal.membership_contract'  => 'Membership Contract',
                                        'legal.liability_waiver'     => 'Liability Waiver',
                                        'legal.privacy_policy'       => 'Privacy Policy',
                                        default                      => $consent->document_key,
                                    };
                                @endphp
                                <tr class="hover:bg-white/5 transition-colors">
                                    <td class="py-3 px-4 font-medium text-white">{{ $docTitle }}</td>
                                    <td class="py-3 px-4">
                                        <span class="inline-flex px-2 py-0.5 rounded text-xs font-medium bg-green-50 text-green-700 border border-green-200">v{{ $consent->version }}</span>
                                    </td>
                                    <td class="py-3 px-4">
                                        @if($consent->method === 'staff_witnessed')
                                            <span class="inline-flex px-2 py-0.5 rounded text-xs font-medium bg-yellow-500/20 text-yellow-300 border border-yellow-500/30">Staff Witnessed</span>
                                        @else
                                            <span class="inline-flex px-2 py-0.5 rounded text-xs font-medium bg-white/5 text-gray-300 border border-white/10">Online</span>
                                        @endif
                                    </td>
                                    <td class="py-3 px-4 text-gray-400">{{ $consent->accepted_at->format('M j, Y g:i A') }}</td>
                                    <td class="py-3 px-4">
                                        @if($consent->snapshot)
                                            <flux:modal.trigger :name="'snapshot-'.$consent->id">
                                                <button class="text-sm text-gray-300 hover:text-white transition-colors underline">View Signed</button>
                                            </flux:modal.trigger>
                                        @else
                                            <span class="text-gray-300">No snapshot</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    </div>

                    {{-- Snapshot Modals --}}
                    @foreach($consents as $consent)
                        @if($consent->snapshot)
                            <flux:modal :name="'snapshot-'.$consent->id">
                                <div class="p-2">
                                    <h3 class="text-base font-semibold text-white mb-1">
                                        {{ match($consent->document_key) {
                                            'legal.terms_and_conditions' => 'Terms & Conditions',
                                            'legal.membership_contract'  => 'Membership Contract',
                                            'legal.liability_waiver'     => 'Liability Waiver',
                                            'legal.privacy_policy'       => 'Privacy Policy',
                                            default                      => $consent->document_key,
                                        } }}
                                    </h3>
                                    <p class="text-xs text-gray-400 mb-4">
                                        Accepted v{{ $consent->version }} on {{ $consent->accepted_at->format('F j, Y \a\t g:i A') }}
                                        ({{ $consent->ip_address }}) — {{ $consent->method === 'staff_witnessed' ? 'Staff Witnessed' : 'Online' }}
                                    </p>
                                    <div class="text-sm text-gray-300 max-h-96 overflow-y-auto border border-gray-600 rounded p-3">
                                        {!! $consent->snapshot->body !!}
                                    </div>
                                    <div class="mt-4 flex justify-end">
                                        <flux:modal.close>
                                            <button class="border border-gray-600 text-gray-300 hover:bg-gray-700 transition-colors text-sm px-4 py-2 rounded-md">Close</button>
                                        </flux:modal.close>
                                    </div>
                                </div>
                            </flux:modal>
                        @endif
                    @endforeach
                @endif
            </div>
        </div>

        {{-- Right Section: Quick Actions & Dynamic Forms --}}
        <div class="col-span-1 space-y-4">
            {{-- Quick Actions Card --}}
            <div class="bg-white/5 backdrop-blur-md border border-white/10 rounded-xl shadow-xl p-5 transition-all">
                <h2 class="text-sm font-semibold text-gray-300 uppercase tracking-wide mb-4">Actions</h2>
                <div class="flex flex-col gap-2">
                    <button wire:click="toggleAction('payment')" class="w-full text-left border {{ $activeAction === 'payment' ? 'border-amber-500/50 text-amber-400 bg-amber-500/10' : 'border-white/10 text-gray-300 hover:bg-white/10 hover:text-white' }} transition-colors text-sm px-3 py-2 rounded-lg">Record Cash Payment</button>
                    <button wire:click="toggleAction('extend')" class="w-full text-left border {{ $activeAction === 'extend' ? 'border-amber-500/50 text-amber-400 bg-amber-500/10' : 'border-white/10 text-gray-300 hover:bg-white/10 hover:text-white' }} transition-colors text-sm px-3 py-2 rounded-lg">Extend Expiry</button>
                    <button wire:click="deactivate" wire:confirm="Deactivate this member?" class="w-full text-left border border-amber-600/50 text-amber-400 hover:bg-amber-500/20 hover:border-amber-500 transition-colors text-sm px-3 py-2 rounded-lg">
                        Deactivate Member
                    </button>                    
                    @if($govIdUrl)
                    <a href="{{ $govIdUrl }}" target="_blank" class="text-sm text-gray-300 hover:text-white transition-colors underline mt-2 block">
                        View Government ID <span class="text-xs text-gray-400">(link valid 30 min)</span>
                    </a>
                    @endif
                </div>
            </div>

            {{-- Dynamic Action Cards --}}
            {{-- Extend Expiry Form --}}
            @if($activeAction === 'extend')
                <div class="bg-black/40 backdrop-blur-md border border-amber-500/30 shadow-[0_0_15px_rgba(245,158,11,0.15)] rounded-xl p-5">
                    <h3 class="text-sm font-semibold text-white mb-3">Extend Membership Expiry</h3>
                    <div class="flex flex-col gap-3">
                        <div class="flex items-center gap-3">
                            <input type="number" wire:model="extendDays" min="1" max="365"
                                class="border border-white/10 bg-white/5 backdrop-blur-md text-white rounded-xl px-3 py-2 text-sm w-full focus:outline-none focus:ring-2 focus:ring-amber-500/50">
                            <span class="text-sm text-gray-400 shrink-0">days</span>
                        </div>
                        @error('extendDays')<p class="text-xs text-red-400">{{ $message }}</p>@enderror
                        <div class="flex gap-2">
                            <button wire:click="extendExpiry"
                                wire:loading.attr="disabled"
                                wire:loading.class="opacity-75 cursor-wait"
                                class="bg-black hover:bg-gray-800 transition-colors text-white text-sm px-4 py-2 rounded-md flex-1">
                                <span wire:loading.remove wire:target="extendExpiry">Extend</span>
                                <span wire:loading wire:target="extendExpiry">Saving...</span>
                            </button>
                            <button wire:click="$set('activeAction', null)" class="border border-white/10 text-gray-300 hover:bg-white/10 transition-colors text-sm px-4 py-2 rounded-md flex-1">Cancel</button>
                        </div>
                    </div>
                </div>
            @endif

            {{-- Walk-in Cash Payment Form --}}
            @if($activeAction === 'payment')
                <div class="bg-black/40 backdrop-blur-md border border-amber-500/30 shadow-[0_0_15px_rgba(245,158,11,0.15)] rounded-xl p-5">
                    <h3 class="text-sm font-semibold text-white mb-3">Record Cash Payment</h3>
                    <div class="flex flex-col gap-1 mb-3">
                        <select wire:model="walkInPlanId" class="border border-white/10 bg-white/5 backdrop-blur-md text-white rounded-xl px-3 py-2 text-sm w-full focus:outline-none focus:ring-2 focus:ring-amber-500/50">
                            <option value="">Select plan…</option>
                            @foreach($plans as $plan)
                                <option value="{{ $plan->id }}">{{ $plan->name }} — ₱{{ number_format($plan->price, 0) }}</option>
                            @endforeach
                        </select>
                    </div>
                    @error('walkInPlanId')<p class="text-xs text-red-400 mb-2">{{ $message }}</p>@enderror

                    <label class="flex items-start gap-2 text-sm text-gray-300 mb-4">
                        <input type="checkbox" wire:model.live="witnessedConsent" class="mt-0.5 shrink-0">
                        <span class="leading-tight">I confirm this member has physically read and signed the Membership Contract and Liability Waiver in person.</span>
                    </label>
                    @error('witnessedConsent')<p class="text-xs text-red-400 mb-3">{{ $message }}</p>@enderror

                    <div class="flex gap-2">
                        <button wire:click="recordCashPayment"
                            wire:loading.attr="disabled"
                            wire:loading.class="opacity-75 cursor-wait"
                            class="bg-black hover:bg-gray-800 transition-colors text-white text-sm px-4 py-2 rounded-md flex-1">
                            <span wire:loading.remove wire:target="recordCashPayment">Record</span>
                            <span wire:loading wire:target="recordCashPayment">Recording...</span>
                        </button>
                            <button wire:click="$set('activeAction', null)" class="border border-white/10 text-gray-300 hover:bg-white/10 transition-colors text-sm px-4 py-2 rounded-md flex-1">Cancel</button>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
