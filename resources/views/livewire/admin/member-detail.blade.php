<div>
    <div>
        <a href="{{ route('admin.members.index') }}">← Back</a>
        <div>
            <h1>{{ $member->name }}</h1>
            <p>Member #{{ $member->id }}</p>
        </div>
    </div>

    @if(session('success'))
        <div>{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div>{{ session('error') }}</div>
    @endif

    <div>
        {{-- Profile Info --}}
        <div>
            <div>
                <h2>Profile</h2>
                <div>
                    <div><span>Email</span><p>{{ $member->email }}</p></div>
                    <div><span>Status</span><p>{{ ucfirst($member->status) }}</p></div>
                    <div><span>Role</span><p>{{ ucfirst($member->role) }}</p></div>
                    <div><span>Joined</span><p>{{ $member->created_at->format('M j, Y') }}</p></div>
                </div>
            </div>

            {{-- Membership History --}}
            <div>
                <div>
                    <h2>Membership History</h2>
                </div>
                <table>
                    <thead>
                        <tr>
                            <th>Plan</th>
                            <th>Status</th>
                            <th>Starts</th>
                            <th>Expires</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($memberships as $ms)
                            <tr>
                                <td>{{ $ms->plan?->name ?? '—' }}</td>
                                <td>
                                    @if($ms->status === 'active')
                                        <span>Active</span>
                                    @elseif($ms->status === 'expired')
                                        <span>Expired</span>
                                    @else
                                        <span>{{ ucfirst($ms->status) }}</span>
                                    @endif
                                </td>
                                <td>{{ $ms->starts_at?->format('M j, Y') }}</td>
                                <td>{{ $ms->expires_at?->format('M j, Y') }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="4">No membership history.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Extend Expiry Form --}}
            @if($showExtendForm)
                <div>
                    <h3>Extend Membership Expiry</h3>
                    <div>
                        <input type="number" wire:model="extendDays" min="1" max="365">
                        <span>days</span>
                        <button wire:click="extendExpiry"
                            wire:loading.attr="disabled"
                            wire:loading.class="opacity-75 cursor-wait">
                            <span wire:loading.remove wire:target="extendExpiry">Extend</span>
                            <span wire:loading wire:target="extendExpiry">Saving...</span>
                        </button>
                        <button wire:click="$set('showExtendForm', false)">Cancel</button>
                    </div>
                    @error('extendDays')<p>{{ $message }}</p>@enderror
                </div>
            @endif

            {{-- Walk-in Cash Payment Form --}}
            @if($showWalkInForm)
                <div>
                    <h3>Record Cash Payment</h3>
                    <div>
                        <select wire:model="walkInPlanId">
                            <option value="">Select plan…</option>
                            @foreach($plans as $plan)
                                <option value="{{ $plan->id }}">{{ $plan->name }} — ₱{{ number_format($plan->price, 0) }}</option>
                            @endforeach
                        </select>
                    </div>
                    @error('walkInPlanId')<p>{{ $message }}</p>@enderror

                    <label>
                        <input type="checkbox" wire:model.live="witnessedConsent">
                        <span>I confirm this member has physically read and signed the Membership Contract and Liability Waiver in person.</span>
                    </label>
                    @error('witnessedConsent')<p>{{ $message }}</p>@enderror

                    <div>
                        <button wire:click="recordCashPayment"
                            wire:loading.attr="disabled"
                            wire:loading.class="opacity-75 cursor-wait">
                            <span wire:loading.remove wire:target="recordCashPayment">Record Payment</span>
                            <span wire:loading wire:target="recordCashPayment">Recording...</span>
                        </button>
                        <button wire:click="$set('showWalkInForm', false)">Cancel</button>
                    </div>
                </div>
            @endif

            {{-- Legal Agreements / Consent History --}}
            <div>
                <div>
                    <h2>Legal Agreements</h2>
                </div>
                @if($consents->isEmpty())
                    <div>No consent records found.</div>
                @else
                    <table>
                        <thead>
                            <tr>
                                <th>Document</th>
                                <th>Version</th>
                                <th>Method</th>
                                <th>Accepted At</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
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
                                <tr>
                                    <td>{{ $docTitle }}</td>
                                    <td>
                                        <span>v{{ $consent->version }}</span>
                                    </td>
                                    <td>
                                        @if($consent->method === 'staff_witnessed')
                                            <span>Staff Witnessed</span>
                                        @else
                                            <span>Online</span>
                                        @endif
                                    </td>
                                    <td>{{ $consent->accepted_at->format('M j, Y g:i A') }}</td>
                                    <td>
                                        @if($consent->snapshot)
                                            <flux:modal.trigger :name="'snapshot-'.$consent->id">
                                                <button>View Signed</button>
                                            </flux:modal.trigger>
                                        @else
                                            <span>No snapshot</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    {{-- Snapshot Modals --}}
                    @foreach($consents as $consent)
                        @if($consent->snapshot)
                            <flux:modal :name="'snapshot-'.$consent->id">
                                <div>
                                    <h3>
                                        {{ match($consent->document_key) {
                                            'legal.terms_and_conditions' => 'Terms & Conditions',
                                            'legal.membership_contract'  => 'Membership Contract',
                                            'legal.liability_waiver'     => 'Liability Waiver',
                                            'legal.privacy_policy'       => 'Privacy Policy',
                                            default                      => $consent->document_key,
                                        } }}
                                    </h3>
                                    <p>
                                        Accepted v{{ $consent->version }} on {{ $consent->accepted_at->format('F j, Y \a\t g:i A') }}
                                        ({{ $consent->ip_address }}) — {{ $consent->method === 'staff_witnessed' ? 'Staff Witnessed' : 'Online' }}
                                    </p>
                                    <div>
                                        {!! $consent->snapshot->body !!}
                                    </div>
                                    <div>
                                        <flux:modal.close>
                                            <button>Close</button>
                                        </flux:modal.close>
                                    </div>
                                </div>
                            </flux:modal>
                        @endif
                    @endforeach
                @endif
            </div>
        </div>

        {{-- Action Buttons --}}
        <div>
            <div>
                <h2>Actions</h2>
                <button wire:click="$set('showWalkInForm', true)">Record Cash Payment</button>
                <button wire:click="$set('showExtendForm', true)">Extend Expiry</button>
                <button wire:click="deactivate" wire:confirm="Deactivate this member?">Deactivate Member</button>
                @if($govIdUrl)
                <a href="{{ $govIdUrl }}" target="_blank">
                    View Government ID <span>(link valid 30 min)</span>
                </a>
                @endif
            </div>
        </div>
    </div>
</div>
