@extends('layouts.portal')

@section('title', 'My Membership')

@push('styles')
<style>
.pub-btn-primary {
    display: inline-flex; align-items: center; justify-content: center; gap: 6px;
    background: #fbbf24; color: #000;
    font-size: 13px; font-weight: 700; letter-spacing: 0.02em;
    padding: 8px 18px; border-radius: 6px; border: none;
    text-decoration: none;
    transition: background 0.2s, transform 0.2s, box-shadow 0.2s;
    cursor: pointer;
}
.pub-btn-primary:hover {
    background: #f59e0b; color: #000;
    transform: translateY(-1px);
    box-shadow: 0 6px 20px rgba(251,191,36,0.3);
}
.pub-btn-primary:active { transform: translateY(0); }

.pub-btn-outline {
    display: inline-flex; align-items: center; justify-content: center; gap: 6px;
    background: transparent; color: #fff;
    font-size: 13px; font-weight: 700; letter-spacing: 0.02em;
    padding: 8px 18px; border-radius: 6px; border: 2px solid #3f3f46;
    text-decoration: none;
    transition: border-color 0.2s, color 0.2s;
    cursor: pointer;
}
.pub-btn-outline:hover { border-color: #fbbf24; color: #fbbf24; }
</style>
@endpush

@section('content')

<div class="mx-auto max-w-6xl space-y-6">
    <section class="relative overflow-hidden rounded-lg bg-[#080808] px-5 py-6 ring-1 ring-amber-400/10 sm:px-7">
        <div class="absolute inset-y-0 right-0 hidden w-1/2 bg-[url('/images/gym-bg.png')] bg-cover bg-center opacity-15 mix-blend-luminosity md:block"></div>
        <div class="absolute inset-0 bg-[radial-gradient(circle_at_85%_15%,rgba(251,191,36,0.14),transparent_34%)]"></div>
        <div class="relative">
            <span class="inline-flex rounded-full border border-amber-400/25 px-3 py-1 text-[11px] font-bold uppercase tracking-[0.16em] text-amber-400">
                Member Portal
            </span>
            <h1 class="mt-4 text-4xl font-black uppercase leading-none text-white sm:text-5xl">My Membership</h1>
            <p class="mt-3 max-w-2xl text-sm leading-6 text-zinc-400">
                Review your active plan and the exact documents you signed during registration.
            </p>
        </div>
    </section>

    @php
        $activeMembership = $user->memberships->where('status', 'active')->sortByDesc('starts_at')->first();
    @endphp

    <section class="rounded-lg bg-[#0b0b0b] p-5 ring-1 ring-white/10 sm:p-6">
        <h2 class="text-sm font-black uppercase tracking-[0.16em] text-amber-400">Current Membership</h2>

        @if($activeMembership)
            <div class="mt-5 grid grid-cols-1 gap-3 sm:grid-cols-2 lg:grid-cols-4">
                <div class="rounded-md bg-zinc-950/70 p-4 ring-1 ring-white/5">
                    <p class="text-[11px] font-bold uppercase tracking-[0.14em] text-zinc-500">Plan</p>
                    <p class="mt-2 text-sm font-semibold text-white">{{ $activeMembership->plan?->name ?? '-' }}</p>
                </div>
                <div class="rounded-md bg-zinc-950/70 p-4 ring-1 ring-white/5">
                    <p class="text-[11px] font-bold uppercase tracking-[0.14em] text-zinc-500">Status</p>
                    <span class="mt-2 inline-flex rounded-full border border-emerald-400/25 bg-emerald-400/10 px-3 py-1 text-xs font-bold text-emerald-300">Active</span>
                </div>
                <div class="rounded-md bg-zinc-950/70 p-4 ring-1 ring-white/5">
                    <p class="text-[11px] font-bold uppercase tracking-[0.14em] text-zinc-500">Started</p>
                    <p class="mt-2 text-sm font-semibold text-white">{{ $activeMembership->starts_at?->format('M j, Y') ?? '-' }}</p>
                </div>
                <div class="rounded-md bg-zinc-950/70 p-4 ring-1 ring-white/5">
                    <p class="text-[11px] font-bold uppercase tracking-[0.14em] text-zinc-500">Expires</p>
                    <p class="mt-2 text-sm font-semibold text-white">{{ $activeMembership->expires_at?->format('M j, Y') ?? '-' }}</p>
                </div>
            </div>
        @else
            <div class="mt-5 rounded-md border border-dashed border-zinc-800 bg-zinc-950/50 px-5 py-8 text-center">
                <p class="text-sm font-semibold text-zinc-300">No active membership found.</p>
            </div>
        @endif
    </section>

    <section class="overflow-hidden rounded-lg bg-[#0b0b0b] ring-1 ring-white/10">
        <div class="border-b border-amber-400/10 px-5 py-4 sm:px-6">
            <h2 class="text-sm font-black uppercase tracking-[0.16em] text-amber-400">Documents I Signed</h2>
            <p class="mt-1 text-xs leading-5 text-zinc-500">These are the exact documents you agreed to during registration. Your signed copies are preserved and cannot be altered.</p>
        </div>

        @if($user->consents->isEmpty())
            <div class="p-5 sm:p-6">
                <div class="rounded-md border border-dashed border-zinc-800 bg-zinc-950/50 px-5 py-8 text-center">
                    <p class="text-sm font-semibold text-zinc-300">Your registration predates our digital consent system.</p>
                    <p class="mt-1 text-xs text-zinc-600">Please visit the gym to obtain a copy of your signed documents.</p>
                </div>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full min-w-[680px] text-sm">
                    <thead class="border-b border-amber-400/10 bg-zinc-950/80">
                        <tr>
                            <th class="px-4 py-3 text-left text-[11px] font-bold uppercase tracking-[0.16em] text-zinc-500">Document</th>
                            <th class="px-4 py-3 text-left text-[11px] font-bold uppercase tracking-[0.16em] text-zinc-500">Version</th>
                            <th class="px-4 py-3 text-left text-[11px] font-bold uppercase tracking-[0.16em] text-zinc-500">Accepted</th>
                            <th class="px-4 py-3 text-left text-[11px] font-bold uppercase tracking-[0.16em] text-zinc-500">View</th>
                        </tr>
                    </thead>
                    <tbody class="[&>tr+tr]:border-t [&>tr+tr]:border-zinc-900">
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
                            <tr class="transition hover:bg-amber-400/5">
                                <td class="px-4 py-3 font-semibold text-white">{{ $docTitle }}</td>
                                <td class="px-4 py-3">
                                    <span class="inline-flex rounded-full border border-emerald-400/25 bg-emerald-400/10 px-3 py-1 text-xs font-bold text-emerald-300">v{{ $consent->version }}</span>
                                </td>
                                <td class="px-4 py-3 text-zinc-400">{{ $consent->accepted_at->format('M j, Y') }}</td>
                                <td class="px-4 py-3">
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
    </section>
</div>

@endsection