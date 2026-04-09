@extends('layouts.public')

@section('title', 'Payment Confirmed')

@section('content')

<div>
    <div>

        @if($isPending)
            <div>
                <h1>Payment Processing</h1>
                <p>Your payment is being confirmed. Please check your email shortly — your login credentials will arrive within a few minutes.</p>
            </div>
        @elseif($membership && $membership->status === 'active')
            <div>
                <h1>Payment Confirmed!</h1>
                <p>Welcome to AmigosFitnessGym. Your account is ready.</p>

                <div>
                    @if($membership->plan)
                        <div>
                            <span>Plan</span>
                            <p>{{ $membership->plan->name }}</p>
                        </div>
                    @endif
                    @if($membership->expires_at)
                        <div>
                            <span>Valid Until</span>
                            <p>{{ $membership->expires_at->format('F j, Y') }}</p>
                        </div>
                    @endif
                </div>

                <p>Check your email for your login credentials. You will be asked to set a new password on first login.</p>
            </div>
        @else
            <div>
                <h1>Payment Received</h1>
                <p>Your payment is being processed. Please check your email for your login credentials.</p>
            </div>
        @endif

        <a href="/login">Go to Login</a>

        <p>If you don't receive an email within 5 minutes, please contact us at the gym.</p>
    </div>
</div>

@endsection
