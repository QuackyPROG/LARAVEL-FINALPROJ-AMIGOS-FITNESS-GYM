@extends('layouts.public')

@section('title', 'Payment Confirmed')

@section('content')

<div class="min-h-[60vh] flex items-center justify-center px-4 py-16">
    <div class="bg-white border border-gray-200 rounded-md p-8 w-full max-w-md text-center">

        @if($isPending)
            <div>
                <h1 class="text-xl font-semibold text-gray-900 mb-3">Payment Processing</h1>
                <p class="text-sm text-gray-500">Your payment is being confirmed. Please check your email shortly — your login credentials will arrive within a few minutes.</p>
            </div>
        @elseif($membership && $membership->status === 'active')
            <div>
                <h1 class="text-xl font-semibold text-gray-900 mb-3">Payment Confirmed!</h1>
                <p class="text-sm text-gray-500 mb-6">Welcome to AmigosFitnessGym. Your account is ready.</p>

                <div class="text-left border border-gray-100 rounded-md divide-y divide-gray-100 mb-6">
                    @if($membership->plan)
                        <div class="flex items-center justify-between px-4 py-3">
                            <span class="text-xs text-gray-400 uppercase tracking-wide">Plan</span>
                            <p class="text-sm font-medium text-gray-900">{{ $membership->plan->name }}</p>
                        </div>
                    @endif
                    @if($membership->expires_at)
                        <div class="flex items-center justify-between px-4 py-3">
                            <span class="text-xs text-gray-400 uppercase tracking-wide">Valid Until</span>
                            <p class="text-sm font-medium text-gray-900">{{ $membership->expires_at->format('F j, Y') }}</p>
                        </div>
                    @endif
                </div>

                <p class="text-sm text-gray-500">Check your email for your login credentials. You will be asked to set a new password on first login.</p>
            </div>
        @else
            <div>
                <h1 class="text-xl font-semibold text-gray-900 mb-3">Payment Received</h1>
                <p class="text-sm text-gray-500">Your payment is being processed. Please check your email for your login credentials.</p>
            </div>
        @endif

        <a href="/login" class="inline-block mt-6 bg-gray-900 text-white text-sm px-6 py-2 rounded-md">Go to Login</a>

        <p class="text-xs text-gray-400 mt-4">If you don't receive an email within 5 minutes, please contact us at the gym.</p>
    </div>
</div>

@endsection
