<div>

    <div>
        <h1>My Membership Card</h1>
        <p>Your digital gym pass</p>
    </div>

    {{-- Digital Card --}}
    <div>
        <div>
            {{-- Header --}}
            <div>
                <div>
                    <p>Amigos Fitness Gym</p>
                    <p>{{ $user->name }}</p>
                    <p>{{ $memberId }}</p>
                </div>
                <div>
                    @if($membership)
                        @php
                            $now = \Illuminate\Support\Carbon::today();
                            $daysLeft = $membership->expires_at ? (int) $now->diffInDays($membership->expires_at, false) : null;
                            $isExpiring = $daysLeft !== null && $daysLeft <= 7 && $daysLeft >= 0;
                            $isExpired = $daysLeft !== null && $daysLeft < 0;
                        @endphp
                        @if($isExpired)
                            <span>Expired</span>
                        @elseif($isExpiring)
                            <span>Expiring Soon</span>
                        @else
                            <span>Active</span>
                        @endif
                    @else
                        <span>No Membership</span>
                    @endif
                </div>
            </div>

            {{-- Plan & Expiry --}}
            @if($membership)
                <div>
                    <div>
                        <p>Plan</p>
                        <p>{{ $membership->plan?->name ?? '—' }}</p>
                    </div>
                    <div>
                        <p>Expires</p>
                        <p>{{ $membership->expires_at?->format('M j, Y') ?? '—' }}</p>
                    </div>
                </div>
            @endif

            {{-- QR Code --}}
            <div>
                <div>
                    {!! $qrSvg !!}
                </div>
            </div>

            <p>Scan to verify membership</p>
        </div>
    </div>

    {{-- Download PDF --}}
    <a href="{{ route('portal.card.pdf') }}">Download PDF</a>

</div>
