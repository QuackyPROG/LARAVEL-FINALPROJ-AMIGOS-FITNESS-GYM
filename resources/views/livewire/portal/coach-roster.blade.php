<div>
    <div>
        <h1>Coaches</h1>
        <p>Book a personal coaching session</p>
    </div>

    @if(session('success'))<div>{{ session('success') }}</div>@endif
    @if(session('error'))<div>{{ session('error') }}</div>@endif

    {{-- My Bookings --}}
    @if($myBookings->count())
    <div>
        <h2>My Upcoming Bookings</h2>
        <div>
            @foreach($myBookings as $booking)
            <div>
                <div>
                    <p>{{ $booking->coach->name }}</p>
                    <p>{{ $booking->scheduled_at->format('M j, Y \a\t g:i A') }}</p>
                </div>
                <button wire:click="cancel({{ $booking->id }})" wire:confirm="Cancel this booking?">Cancel</button>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    {{-- Coach Grid --}}
    <div>
        @forelse($coaches as $coach)
        <div>
            <div>
                <span>{{ strtoupper(substr($coach->name, 0, 1)) }}</span>
            </div>
            <div>
                <p>{{ $coach->name }}</p>
                @if($coach->bio)<p>{{ $coach->bio }}</p>@endif
                @if($coach->specializations)
                    <div>
                        @foreach($coach->specializations as $s)
                            <span>{{ $s }}</span>
                        @endforeach
                    </div>
                @endif
            </div>
            <button wire:click="openBooking({{ $coach->id }})">Book Session</button>
        </div>
        @empty
        <div>
            <p>No coaches available yet</p>
            <p>Check back soon for available coaching sessions</p>
        </div>
        @endforelse
    </div>

    {{-- Booking Modal --}}
    @if($bookingCoach)
    <div>
        <div>
            <h2>Book with {{ $bookingCoach->name }}</h2>
            <p>Select a date and time for your session.</p>

            <div>
                <label>Date &amp; Time</label>
                <input type="datetime-local" wire:model="scheduledDate">
                @error('scheduledDate')<p>{{ $message }}</p>@enderror
            </div>

            <div>
                <button wire:click="confirmBooking">Confirm Booking</button>
                <button wire:click="closeBooking">Cancel</button>
            </div>
        </div>
    </div>
    @endif
</div>
