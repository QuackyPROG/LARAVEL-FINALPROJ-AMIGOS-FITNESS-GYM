@extends('layouts.public')

@section('title', 'Payment Failed')

@section('content')

<div>
    <div>

        <div>
            <h1>Payment Did Not Go Through</h1>
            <p>No charge was made to your account.</p>
            <p>This can happen if the payment was cancelled, the session timed out, or the card was declined. You can try again at any time.</p>

            <a href="{{ $membership ? '/register?plan='.$membership->plan_id : '/register' }}">Try Again</a>

            <a href="/">Back to Home</a>

            @if($gymPhone)
                <p>
                    Need help? Call us at
                    <a href="tel:{{ $gymPhone }}">{{ $gymPhone }}</a>
                </p>
            @endif
        </div>

    </div>
</div>

@endsection
