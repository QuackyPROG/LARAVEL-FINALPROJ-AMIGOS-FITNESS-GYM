@extends('layouts.public')

@section('title', 'Payment Failed')

@section('content')

<div class="min-h-[60vh] flex items-center justify-center px-4 py-16">
    <div class="bg-white border border-gray-200 rounded-md p-8 w-full max-w-md text-center">

        <div>
            <h1 class="text-xl font-semibold text-gray-900 mb-3">Payment Did Not Go Through</h1>
            <p class="text-sm text-gray-500 mb-2">No charge was made to your account.</p>
            <p class="text-sm text-gray-500 mb-6">This can happen if the payment was cancelled, the session timed out, or the card was declined. You can try again at any time.</p>

            <div class="flex flex-col items-center gap-3">
                <a href="{{ $membership ? '/register?plan='.$membership->plan_id : '/register' }}" class="inline-block bg-gray-900 text-white text-sm px-6 py-2 rounded-md">Try Again</a>

                <a href="/" class="text-sm text-gray-500 underline">Back to Home</a>

                @if($gymPhone)
                    <p class="text-sm text-gray-400">
                        Need help? Call us at
                        <a href="tel:{{ $gymPhone }}" class="text-gray-700 underline">{{ $gymPhone }}</a>
                    </p>
                @endif
            </div>
        </div>

    </div>
</div>

@endsection
