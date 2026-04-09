@extends('layouts.public')

@section('title', 'Membership Verification')

@section('content')
<div>
    <div>
        <div>

            @if($valid)
                <h1>Member Verified</h1>

                <div>
                    <div>
                        <span>Member Name</span>
                        <p>{{ $user->name }}</p>
                    </div>
                    @if($membership)
                        <div>
                            <span>Plan</span>
                            <p>{{ $membership->plan?->name ?? '—' }}</p>
                        </div>
                        <div>
                            <span>Status</span>
                            <p>{{ ucfirst($membership->status) }}</p>
                        </div>
                        <div>
                            <span>Expires</span>
                            <p>{{ $membership->expires_at?->format('F j, Y') ?? '—' }}</p>
                        </div>
                    @endif
                </div>

            @else
                @if(isset($tokenExpired) && $tokenExpired)
                    <h1>Token Expired</h1>
                    <p>This membership card token has expired. Please ask the member to refresh their digital card.</p>
                @else
                    <h1>Invalid Card</h1>
                    <p>This membership card could not be verified.</p>
                @endif
            @endif

            <p>This card was verified at {{ $verifiedAt }}</p>
        </div>
    </div>
</div>
@endsection
