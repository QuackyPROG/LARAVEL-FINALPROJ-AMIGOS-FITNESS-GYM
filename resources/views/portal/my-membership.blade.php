@extends('layouts.portal')

@section('title', 'My Membership')

@section('content')

<div>
    <p>Member Portal</p>
    <h1>My Membership</h1>
</div>

@php
    $activeMembership = $user->memberships->where('status', 'active')->sortByDesc('starts_at')->first();
@endphp

{{-- Active Membership Card --}}
<div>
    <h2>Current Membership</h2>

    @if($activeMembership)
        <div>
            <div>
                <p>Plan</p>
                <p>{{ $activeMembership->plan?->name ?? '—' }}</p>
            </div>
            <div>
                <p>Status</p>
                <span>Active</span>
            </div>
            <div>
                <p>Started</p>
                <p>{{ $activeMembership->starts_at?->format('M j, Y') ?? '—' }}</p>
            </div>
            <div>
                <p>Expires</p>
                <p>{{ $activeMembership->expires_at?->format('M j, Y') ?? '—' }}</p>
            </div>
        </div>
    @else
        <div>
            <p>No active membership found.</p>
        </div>
    @endif
</div>

{{-- Documents I Signed --}}
<div>
    <div>
        <h2>Documents I Signed</h2>
        <p>These are the exact documents you agreed to during registration. Your signed copies are preserved and cannot be altered.</p>
    </div>

    @if($user->consents->isEmpty())
        <div>
            <p>Your registration predates our digital consent system.</p>
            <p>Please visit the gym to obtain a copy of your signed documents.</p>
        </div>
    @else
        <table>
            <thead>
                <tr>
                    <th>Document</th>
                    <th>Version</th>
                    <th>Accepted</th>
                    <th>View</th>
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
                    <tr>
                        <td>{{ $docTitle }}</td>
                        <td>
                            <span>v{{ $consent->version }}</span>
                        </td>
                        <td>{{ $consent->accepted_at->format('M j, Y') }}</td>
                        <td>
                            @if($consent->snapshot)
                                <flux:modal.trigger :name="'my-doc-'.$consent->id">
                                    <button>View</button>
                                </flux:modal.trigger>
                            @else
                                <span>—</span>
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
                            You accepted version {{ $consent->version }} on {{ $consent->accepted_at->format('F j, Y') }}.
                            This is the exact document that was presented to you at the time of registration.
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

@endsection
