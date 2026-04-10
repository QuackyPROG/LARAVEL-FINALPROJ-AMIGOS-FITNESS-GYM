<div>
    <div class="mb-6">
        <h1 class="text-xl font-semibold text-gray-900">Coaches</h1>
        <p class="text-sm text-gray-500">Book a personal coaching session</p>
    </div>

    @if(session('success'))<div class="bg-green-50 border border-green-200 text-green-700 text-sm px-4 py-3 rounded-md mb-4">{{ session('success') }}</div>@endif
    @if(session('error'))<div class="bg-red-50 border border-red-200 text-red-700 text-sm px-4 py-3 rounded-md mb-4">{{ session('error') }}</div>@endif

    {{-- My Bookings --}}
    @if($myBookings->count())
    <div class="mb-6">
        <h2 class="text-sm font-semibold text-gray-700 uppercase tracking-wide mb-3">My Upcoming Bookings</h2>
        <div class="space-y-2">
            @foreach($myBookings as $booking)
            <div class="bg-white border border-gray-200 rounded-md px-4 py-3 flex items-center justify-between">
                <div>
                    <p class="font-medium text-sm text-gray-900">{{ $booking->coach->name }}</p>
                    <p class="text-xs text-gray-400">{{ $booking->scheduled_at->format('M j, Y \a\t g:i A') }}</p>
                </div>
                <button wire:click="cancel({{ $booking->id }})" wire:confirm="Cancel this booking?" class="border border-red-200 text-red-600 text-sm px-3 py-1 rounded">Cancel</button>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    {{-- Coach Grid --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        @forelse($coaches as $coach)
        <div class="bg-white border border-gray-200 rounded-md p-5">
            <div class="w-10 h-10 rounded-full bg-gray-100 border border-gray-200 flex items-center justify-center mb-3">
                <span class="text-sm font-semibold text-gray-500">{{ strtoupper(substr($coach->name, 0, 1)) }}</span>
            </div>
            <div class="mb-3">
                <p class="font-medium text-gray-900">{{ $coach->name }}</p>
                @if($coach->bio)<p class="text-sm text-gray-500 mt-0.5">{{ $coach->bio }}</p>@endif
                @if($coach->specializations)
                    <div class="flex flex-wrap gap-1 mt-2">
                        @foreach($coach->specializations as $s)
                            <span class="inline-flex px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-600 border border-gray-200">{{ $s }}</span>
                        @endforeach
                    </div>
                @endif
            </div>
            <button wire:click="openBooking({{ $coach->id }})" class="w-full border border-gray-300 text-gray-700 text-sm px-3 py-2 rounded-md">Book Session</button>
        </div>
        @empty
        <div class="col-span-3 bg-white border border-gray-200 rounded-md p-8 text-center">
            <p class="text-sm text-gray-400">No coaches available yet</p>
            <p class="text-xs text-gray-300 mt-0.5">Check back soon for available coaching sessions</p>
        </div>
        @endforelse
    </div>

    {{-- Booking Modal --}}
    @if($bookingCoach)
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/30 px-4">
        <div class="bg-white border border-gray-200 rounded-md p-6 w-full max-w-sm">
            <h2 class="text-base font-semibold text-gray-900 mb-1">Book with {{ $bookingCoach->name }}</h2>
            <p class="text-sm text-gray-500 mb-4">Select a date and time for your session.</p>

            <div class="flex flex-col gap-1 mb-4">
                <label class="text-sm font-medium text-gray-700">Date &amp; Time</label>
                <input type="datetime-local" wire:model="scheduledDate" class="border border-gray-300 rounded-md px-3 py-2 text-sm w-full">
                @error('scheduledDate')<p class="text-xs text-red-500">{{ $message }}</p>@enderror
            </div>

            <div class="flex gap-3">
                <button wire:click="confirmBooking" class="bg-gray-900 text-white text-sm px-4 py-2 rounded-md">Confirm Booking</button>
                <button wire:click="closeBooking" class="border border-gray-300 text-gray-700 text-sm px-4 py-2 rounded-md">Cancel</button>
            </div>
        </div>
    </div>
    @endif
</div>
