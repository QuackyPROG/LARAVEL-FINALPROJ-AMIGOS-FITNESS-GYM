@extends('layouts.portal')

@section('title', 'My Membership')

@section('content')

<div class="mb-6">
    <p class="text-xs font-medium uppercase tracking-wide text-gray-400 mb-1">Member Portal</p>
    <h1 class="text-xl font-semibold text-gray-900">My Membership</h1>
</div>

@php
    $activeMembership = $user->memberships->where('status', 'active')->sortByDesc('starts_at')->first();
@endphp

{{-- Active Membership Card --}}
<div class="bg-white border border-gray-200 rounded-md p-5 mb-4">
    <h2 class="text-sm font-semibold text-gray-700 uppercase tracking-wide mb-4">Current Membership</h2>

    @if($activeMembership)
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <div>
                <p class="text-xs text-gray-400 uppercase tracking-wide mb-1">Plan</p>
                <p class="text-sm font-medium text-gray-900">{{ $activeMembership->plan?->name ?? '—' }}</p>
            </div>
            <div>
                <p class="text-xs text-gray-400 uppercase tracking-wide mb-1">Status</p>
                <span class="inline-flex px-2 py-0.5 rounded text-xs font-medium bg-green-50 text-green-700 border border-green-200">Active</span>
            </div>
            <div>
                <p class="text-xs text-gray-400 uppercase tracking-wide mb-1">Started</p>
                <p class="text-sm font-medium text-gray-900">{{ $activeMembership->starts_at?->format('M j, Y') ?? '—' }}</p>
            </div>
            <div>
                <p class="text-xs text-gray-400 uppercase tracking-wide mb-1">Expires</p>
                <p class="text-sm font-medium text-gray-900">{{ $activeMembership->expires_at?->format('M j, Y') ?? '—' }}</p>
            </div>
        </div>
    @else
        <div class="py-4 text-center">
            <p class="text-sm text-gray-400">No active membership found.</p>
        </div>
    @endif
</div>

{{-- Documents I Signed --}}
<div class="bg-white border border-gray-200 rounded-md overflow-hidden">
    <div class="px-5 py-4 border-b border-gray-100">
        <h2 class="text-sm font-semibold text-gray-700">Documents I Signed</h2>
        <p class="text-xs text-gray-400 mt-0.5">These are the exact documents you agreed to during registration. Your signed copies are preserved and cannot be altered.</p>
    </div>

    @if($user->consents->isEmpty())
        <div class="p-5">
            <p class="text-sm text-gray-500">Your registration predates our digital consent system.</p>
            <p class="text-sm text-gray-400 mt-0.5">Please visit the gym to obtain a copy of your signed documents.</p>
        </div>
    @else
        <table class="w-full text-sm">
            <thead class="border-b border-gray-100 bg-gray-50">
                <tr>
                    <th class="text-left text-xs font-medium text-gray-500 uppercase tracking-wide py-3 px-4">Document</th>
                    <th class="text-left text-xs font-medium text-gray-500 uppercase tracking-wide py-3 px-4">Version</th>
                    <th class="text-left text-xs font-medium text-gray-500 uppercase tracking-wide py-3 px-4">Accepted</th>
                    <th class="text-left text-xs font-medium text-gray-500 uppercase tracking-wide py-3 px-4">View</th>
                </tr>
            </thead>
            <tbody>
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
                    <tr class="border-b border-gray-100">
                        <td class="py-3 px-4 font-medium text-gray-900">{{ $docTitle }}</td>
                        <td class="py-3 px-4">
                            <span class="inline-flex px-2 py-0.5 rounded text-xs font-medium bg-green-50 text-green-700 border border-green-200">v{{ $consent->version }}</span>
                        </td>
                        <td class="py-3 px-4 text-gray-500">{{ $consent->accepted_at->format('M j, Y') }}</td>
                        <td class="py-3 px-4">
                            @if($consent->snapshot)
                                <flux:modal.trigger :name="'my-doc-'.$consent->id">
                                    <button class="text-sm text-gray-700 underline">View</button>
                                </flux:modal.trigger>
                            @else
                                <span class="text-gray-300">—</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        {{-- Document Modals --}}
        @foreach($user->consents as $consent)
            @if($consent->snapshot)
                <flux:modal :name="'my-doc-'.$consent->id">
                    <div class="p-2">
                        <h3 class="text-base font-semibold text-gray-900 mb-1">
                            {{ match($consent->document_key) {
                                'legal.terms_and_conditions' => 'Terms & Conditions',
                                'legal.membership_contract'  => 'Membership Contract',
                                'legal.liability_waiver'     => 'Liability Waiver',
                                'legal.privacy_policy'       => 'Privacy Policy',
                                default                      => $consent->document_key,
                            } }}
                        </h3>
                        <p class="text-xs text-gray-400 mb-4">
                            You accepted version {{ $consent->version }} on {{ $consent->accepted_at->format('F j, Y') }}.
                            This is the exact document that was presented to you at the time of registration.
                        </p>
                        <div class="text-sm text-gray-700 max-h-96 overflow-y-auto border border-gray-100 rounded p-3">
                            {!! $consent->snapshot->body !!}
                        </div>
                        <div class="mt-4 flex justify-end">
                            <flux:modal.close>
                                <button class="border border-gray-300 text-gray-700 text-sm px-4 py-2 rounded-md">Close</button>
                            </flux:modal.close>
                        </div>
                    </div>
                </flux:modal>
            @endif
        @endforeach
    @endif
</div>

@endsection
