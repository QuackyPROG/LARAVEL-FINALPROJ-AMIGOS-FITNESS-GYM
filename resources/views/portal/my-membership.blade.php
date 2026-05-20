@extends('layouts.portal')

@section('title', 'My Membership')

@section('content')

<div class="mx-auto max-w-6xl space-y-6">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-white mb-2">My Membership</h1>
        <p class="text-gray-300">Review your active plan and signed registration documents</p>
    </div>

    @php
        $activeMembership = $user->memberships->where('status', 'active')->sortByDesc('starts_at')->first();
    @endphp

    <div class="bg-white/5 backdrop-blur-md border border-white/10 rounded-xl shadow-xl overflow-hidden">
        <div class="px-5 py-4 border-b border-white/10 bg-white/5">
            <h2 class="text-xs font-bold text-amber-400 uppercase tracking-widest">Current Membership</h2>
        </div>
        @if($activeMembership)
            <div class="p-5 grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
                <div class="bg-white/5 border border-white/10 rounded-lg p-4">
                    <p class="text-xs font-medium text-gray-400 uppercase tracking-wide">Plan</p>
                    <p class="mt-2 text-sm font-semibold text-white">{{ $activeMembership->plan?->name ?? '-' }}</p>
                </div>
                <div class="bg-white/5 border border-white/10 rounded-lg p-4">
                    <p class="text-xs font-medium text-gray-400 uppercase tracking-wide">Status</p>
                    <span class="mt-2 inline-flex items-center gap-1.5 px-2 py-1 rounded-md text-xs font-semibold bg-green-500/10 text-green-400 border border-green-500/30">
                        <span class="w-1.5 h-1.5 rounded-full bg-green-400 shadow-[0_0_6px_rgba(34,197,94,0.8)]"></span>
                        Active
                    </span>
                </div>
                <div class="bg-white/5 border border-white/10 rounded-lg p-4">
                    <p class="text-xs font-medium text-gray-400 uppercase tracking-wide">Started</p>
                    <p class="mt-2 text-sm font-semibold text-white">{{ $activeMembership->starts_at?->format('M j, Y') ?? '-' }}</p>
                </div>
                <div class="bg-white/5 border border-white/10 rounded-lg p-4">
                    <p class="text-xs font-medium text-gray-400 uppercase tracking-wide">Expires</p>
                    <p class="mt-2 text-sm font-semibold text-white">{{ $activeMembership->expires_at?->format('M j, Y') ?? '-' }}</p>
                </div>
            </div>
        @else
            <div class="py-16 text-center">
                <svg class="h-10 w-10 text-white/20 mb-3 mx-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                <p class="font-medium text-gray-300">No active membership found</p>
                <p class="text-xs mt-1 text-gray-500">Visit the registration page to choose a plan.</p>
                <a href="/register" class="mt-4 inline-flex bg-gradient-to-r from-amber-400 to-yellow-500 hover:from-amber-500 hover:to-yellow-600 text-black font-bold text-sm px-5 py-2 rounded-xl transition-all transform hover:-translate-y-0.5">Get Started</a>
            </div>
        @endif
    </div>

    <div class="bg-white/5 backdrop-blur-md border border-white/10 rounded-xl shadow-xl overflow-hidden">
        <div class="px-5 py-4 border-b border-white/10 bg-white/5">
            <h2 class="text-xs font-bold text-amber-400 uppercase tracking-widest">Documents I Signed</h2>
            <p class="mt-1 text-xs text-gray-400">The exact documents you agreed to during registration — preserved and unalterable.</p>
        </div>

        @if($user->consents->isEmpty())
            <div class="py-12 text-center">
                <p class="font-medium text-gray-300">Your registration predates our digital consent system.</p>
                <p class="text-xs mt-1 text-gray-500">Please visit the gym to obtain a copy of your signed documents.</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full min-w-[680px] text-sm">
                    <thead class="border-b border-white/10 bg-white/5">
                        <tr>
                            <th class="px-5 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Document</th>
                            <th class="px-5 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Version</th>
                            <th class="px-5 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Accepted</th>
                            <th class="px-5 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">View</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/5">
                        @foreach($user->consents->sortBy('accepted_at') as $consent)
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
                                <td class="py-3 px-5 font-medium text-white">{{ $docTitle }}</td>
                                <td class="py-3 px-5">
                                    <span class="inline-flex items-center gap-1.5 px-2 py-1 rounded-md text-xs font-semibold bg-green-500/10 text-green-400 border border-green-500/30">v{{ $consent->version }}</span>
                                </td>
                                <td class="py-3 px-5 text-gray-400">{{ $consent->accepted_at->format('M j, Y') }}</td>
                                <td class="py-3 px-5">
                                    @if($consent->snapshot)
                                        <flux:modal.trigger :name="'my-doc-'.$consent->id">
                                            <button style="display:inline-flex;align-items:center;gap:6px;background:#fbbf24;color:#000;font-size:12px;font-weight:700;letter-spacing:0.02em;padding:6px 14px;border-radius:6px;border:none;cursor:pointer;transition:background 0.2s,transform 0.2s,box-shadow 0.2s;white-space:nowrap;"
                                                onmouseover="this.style.background='#f59e0b';this.style.transform='translateY(-1px)';this.style.boxShadow='0 6px 20px rgba(251,191,36,0.3)'"
                                                onmouseout="this.style.background='#fbbf24';this.style.transform='none';this.style.boxShadow='none'">
                                                View
                                                <svg width="12" height="12" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                                </svg>
                                            </button>
                                        </flux:modal.trigger>
                                    @else
                                        <span class="text-zinc-700">—</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @foreach($user->consents as $consent)
                @if($consent->snapshot)
                    <flux:modal :name="'my-doc-'.$consent->id" class="!bg-[#0b0b0b] !text-white">
                        <div class="rounded-lg bg-[#0b0b0b] p-5 text-white ring-1 ring-white/10">
                            <h3 class="text-lg font-black uppercase text-white">
                                {{ match($consent->document_key) {
                                    'legal.terms_and_conditions' => 'Terms & Conditions',
                                    'legal.membership_contract'  => 'Membership Contract',
                                    'legal.liability_waiver'     => 'Liability Waiver',
                                    'legal.privacy_policy'       => 'Privacy Policy',
                                    default                      => $consent->document_key,
                                } }}
                            </h3>
                            <p class="mt-2 text-xs leading-5 text-zinc-500">
                                You accepted version {{ $consent->version }} on {{ $consent->accepted_at->format('F j, Y') }}.
                                This is the exact document that was presented to you at the time of registration.
                            </p>
                            <div class="mt-4 max-h-96 overflow-y-auto rounded-md border border-zinc-800 bg-black p-4 text-sm leading-6 text-zinc-300">
                                {!! $consent->snapshot->body !!}
                            </div>
                            <div class="mt-4">
                                <flux:modal.close>
                                    <button style="display:inline-flex;align-items:center;justify-content:center;gap:6px;width:100%;background:transparent;color:#fff;font-size:13px;font-weight:700;letter-spacing:0.02em;padding:8px 18px;border-radius:6px;border:2px solid #3f3f46;cursor:pointer;transition:border-color 0.2s,color 0.2s;"
                                        onmouseover="this.style.borderColor='#fbbf24';this.style.color='#fbbf24'"
                                        onmouseout="this.style.borderColor='#3f3f46';this.style.color='#fff'">Close</button>
                                </flux:modal.close>
                            </div>
                        </div>
                    </flux:modal>
                @endif
            @endforeach
        @endif
    </div>
</div>

@endsection