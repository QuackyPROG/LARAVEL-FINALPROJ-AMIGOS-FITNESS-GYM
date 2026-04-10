<div>

    <div class="mb-6">
        <h1 class="text-xl font-semibold text-gray-900">Welcome back, {{ $user->name }}</h1>
        <p class="text-sm text-gray-500">Member Dashboard</p>
    </div>

    {{-- Membership Status Card --}}
    <div class="bg-white border border-gray-200 rounded-md p-5 mb-6">
        <div class="flex items-start justify-between">
            <div>
                <p class="text-xs text-gray-400 uppercase tracking-wide mb-1">Membership</p>
                @if($membership)
                    <p class="font-semibold text-gray-900">{{ $membership->plan?->name ?? 'Unknown Plan' }}</p>
                    <p class="text-sm text-gray-500 mt-0.5">Expires {{ $membership->expires_at?->format('F j, Y') ?? '—' }}</p>
                @else
                    <p class="font-semibold text-gray-900">No Active Membership</p>
                @endif
            </div>

            <div class="text-right">
                {{-- Status Badge --}}
                @if($membershipStatus === 'active')
                    <span class="inline-flex px-2 py-0.5 rounded text-xs font-medium bg-green-50 text-green-700 border border-green-200">Active</span>
                @elseif($membershipStatus === 'expiring_soon')
                    <span class="inline-flex px-2 py-0.5 rounded text-xs font-medium bg-yellow-50 text-yellow-700 border border-yellow-200">Expiring Soon</span>
                @elseif($membershipStatus === 'expired')
                    <span class="inline-flex px-2 py-0.5 rounded text-xs font-medium bg-red-50 text-red-700 border border-red-200">Expired</span>
                @else
                    <span class="inline-flex px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-600 border border-gray-200">No Membership</span>
                @endif

                {{-- Days Remaining --}}
                @if($daysRemaining !== null)
                    <p class="text-sm text-gray-500 mt-1">
                        @if($daysRemaining >= 0)
                            <span class="font-semibold text-gray-900">{{ $daysRemaining }}</span> day{{ $daysRemaining === 1 ? '' : 's' }} remaining
                        @else
                            Expired {{ abs($daysRemaining) }} day{{ abs($daysRemaining) === 1 ? '' : 's' }} ago
                        @endif
                    </p>
                @endif

                {{-- Renew Button --}}
                @if($showRenew)
                    <a href="/register{{ $membership?->plan_id ? '?plan='.$membership->plan_id : '' }}" class="inline-block mt-2 border border-gray-300 text-gray-700 text-sm px-3 py-1.5 rounded-md">
                        Renew Membership
                    </a>
                @endif
            </div>
        </div>
    </div>

    {{-- Quick Links --}}
    <div class="mb-6">
        <h2 class="text-sm font-semibold text-gray-700 uppercase tracking-wide mb-3">Quick Links</h2>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
            <a href="{{ route('portal.card') }}" class="bg-white border border-gray-200 rounded-md p-4 text-center">
                <span class="text-sm font-medium text-gray-700">View My Card</span>
            </a>

            <a href="{{ route('portal.coaches') }}" class="bg-white border border-gray-200 rounded-md p-4 text-center">
                <span class="text-sm font-medium text-gray-700">Book a Coach</span>
            </a>

            <a href="{{ route('portal.schedule') }}" class="bg-white border border-gray-200 rounded-md p-4 text-center">
                <span class="text-sm font-medium text-gray-700">Class Schedule</span>
            </a>

            <a href="{{ route('portal.support') }}" class="bg-white border border-gray-200 rounded-md p-4 text-center">
                <span class="text-sm font-medium text-gray-700">Chat Support</span>
            </a>
        </div>
    </div>

    {{-- My Bookings --}}
    <div class="bg-white border border-gray-200 rounded-md p-5">
        <h2 class="text-sm font-semibold text-gray-700 uppercase tracking-wide mb-3">My Bookings</h2>
        <div>
            <div class="py-6 text-center">
                <p class="text-sm text-gray-400">No upcoming bookings</p>
                <p class="text-xs text-gray-300 mt-0.5">Coach booking sessions will appear here</p>
            </div>
        </div>
    </div>

</div>
