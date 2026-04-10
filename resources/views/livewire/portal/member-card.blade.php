<div>

    <div class="mb-6">
        <h1 class="text-xl font-semibold text-gray-900">My Membership Card</h1>
        <p class="text-sm text-gray-500">Your digital gym pass</p>
    </div>

    {{-- Digital Card --}}
    <div class="mb-6">
        <div class="bg-white border-2 border-gray-300 rounded-lg p-6 max-w-sm">
            {{-- Header --}}
            <div class="flex items-start justify-between mb-4">
                <div>
                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Amigos Fitness Gym</p>
                    <p class="text-lg font-semibold text-gray-900 mt-0.5">{{ $user->name }}</p>
                    <p class="text-xs text-gray-400">{{ $memberId }}</p>
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
                            <span class="inline-flex px-2 py-0.5 rounded text-xs font-medium bg-red-50 text-red-700 border border-red-200">Expired</span>
                        @elseif($isExpiring)
                            <span class="inline-flex px-2 py-0.5 rounded text-xs font-medium bg-yellow-50 text-yellow-700 border border-yellow-200">Expiring Soon</span>
                        @else
                            <span class="inline-flex px-2 py-0.5 rounded text-xs font-medium bg-green-50 text-green-700 border border-green-200">Active</span>
                        @endif
                    @else
                        <span class="inline-flex px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-500 border border-gray-200">No Membership</span>
                    @endif
                </div>
            </div>

            {{-- Plan & Expiry --}}
            @if($membership)
                <div class="grid grid-cols-2 gap-4 mb-4 py-4 border-t border-b border-gray-100">
                    <div>
                        <p class="text-xs text-gray-400 uppercase tracking-wide mb-1">Plan</p>
                        <p class="text-sm font-medium text-gray-900">{{ $membership->plan?->name ?? '—' }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400 uppercase tracking-wide mb-1">Expires</p>
                        <p class="text-sm font-medium text-gray-900">{{ $membership->expires_at?->format('M j, Y') ?? '—' }}</p>
                    </div>
                </div>
            @endif

            {{-- QR Code --}}
            <div class="flex justify-center mb-3">
                <div class="p-3 bg-gray-50 border border-gray-200 rounded-md inline-block">
                    {!! $qrSvg !!}
                </div>
            </div>

            <p class="text-xs text-center text-gray-400">Scan to verify membership</p>
        </div>
    </div>

    {{-- Download PDF --}}
    <a href="{{ route('portal.card.pdf') }}" class="border border-gray-300 text-gray-700 text-sm px-4 py-2 rounded-md">Download PDF</a>

</div>
