@extends('layouts.public')

@section('title', 'Membership Verification')

@section('content')
<div class="min-h-[60vh] flex items-center justify-center px-4 py-16">
    <div class="w-full max-w-sm">
        <div class="bg-white border border-gray-200 rounded-md p-6">

            @if($valid)
                <h1 class="text-xl font-semibold text-gray-900 mb-4">Member Verified</h1>

                <div class="border border-gray-100 rounded-md divide-y divide-gray-100">
                    <div class="flex items-center justify-between px-4 py-3">
                        <span class="text-xs text-gray-400 uppercase tracking-wide">Member Name</span>
                        <p class="text-sm font-medium text-gray-900">{{ $user->name }}</p>
                    </div>
                    @if($membership)
                        <div class="flex items-center justify-between px-4 py-3">
                            <span class="text-xs text-gray-400 uppercase tracking-wide">Plan</span>
                            <p class="text-sm font-medium text-gray-900">{{ $membership->plan?->name ?? '—' }}</p>
                        </div>
                        <div class="flex items-center justify-between px-4 py-3">
                            <span class="text-xs text-gray-400 uppercase tracking-wide">Status</span>
                            <p class="text-sm font-medium text-gray-900">{{ ucfirst($membership->status) }}</p>
                        </div>
                        <div class="flex items-center justify-between px-4 py-3">
                            <span class="text-xs text-gray-400 uppercase tracking-wide">Expires</span>
                            <p class="text-sm font-medium text-gray-900">{{ $membership->expires_at?->format('F j, Y') ?? '—' }}</p>
                        </div>
                    @endif
                </div>

            @else
                @if(isset($tokenExpired) && $tokenExpired)
                    <h1 class="text-xl font-semibold text-gray-900 mb-3">Token Expired</h1>
                    <p class="text-sm text-gray-500">This membership card token has expired. Please ask the member to refresh their digital card.</p>
                @else
                    <h1 class="text-xl font-semibold text-gray-900 mb-3">Invalid Card</h1>
                    <p class="text-sm text-gray-500">This membership card could not be verified.</p>
                @endif
            @endif

            <p class="text-xs text-gray-400 mt-4">This card was verified at {{ $verifiedAt }}</p>
        </div>
    </div>
</div>
@endsection
