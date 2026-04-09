<div>

    <div>
        <h1>Welcome back, {{ $user->name }}</h1>
        <p>Member Dashboard</p>
    </div>

    {{-- Membership Status Card --}}
    <div>
        <div>
            <div>
                <p>Membership</p>
                @if($membership)
                    <p>{{ $membership->plan?->name ?? 'Unknown Plan' }}</p>
                    <p>Expires {{ $membership->expires_at?->format('F j, Y') ?? '—' }}</p>
                @else
                    <p>No Active Membership</p>
                @endif
            </div>

            <div>
                {{-- Status Badge --}}
                @if($membershipStatus === 'active')
                    <span>Active</span>
                @elseif($membershipStatus === 'expiring_soon')
                    <span>Expiring Soon</span>
                @elseif($membershipStatus === 'expired')
                    <span>Expired</span>
                @else
                    <span>No Membership</span>
                @endif

                {{-- Days Remaining --}}
                @if($daysRemaining !== null)
                    <p>
                        @if($daysRemaining >= 0)
                            <span>{{ $daysRemaining }}</span> day{{ $daysRemaining === 1 ? '' : 's' }} remaining
                        @else
                            Expired {{ abs($daysRemaining) }} day{{ abs($daysRemaining) === 1 ? '' : 's' }} ago
                        @endif
                    </p>
                @endif

                {{-- Renew Button --}}
                @if($showRenew)
                    <a href="/register{{ $membership?->plan_id ? '?plan='.$membership->plan_id : '' }}">
                        Renew Membership
                    </a>
                @endif
            </div>
        </div>
    </div>

    {{-- Quick Links --}}
    <div>
        <h2>Quick Links</h2>
        <div>
            <a href="{{ route('portal.card') }}">
                <span>View My Card</span>
            </a>

            <a href="{{ route('portal.coaches') }}">
                <span>Book a Coach</span>
            </a>

            <a href="{{ route('portal.schedule') }}">
                <span>Class Schedule</span>
            </a>

            <a href="{{ route('portal.support') }}">
                <span>Chat Support</span>
            </a>
        </div>
    </div>

    {{-- My Bookings --}}
    <div>
        <h2>My Bookings</h2>
        <div>
            <div>
                <p>No upcoming bookings</p>
                <p>Coach booking sessions will appear here</p>
            </div>
        </div>
    </div>

</div>
