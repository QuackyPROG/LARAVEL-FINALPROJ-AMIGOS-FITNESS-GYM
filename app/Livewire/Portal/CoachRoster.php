<?php

namespace App\Livewire\Portal;

use App\Jobs\SendBookingConfirmation;
use App\Models\Booking;
use App\Models\Coach;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Carbon;
use Livewire\Attributes\Rule;
use Livewire\Component;

class CoachRoster extends Component
{
    public ?int $bookingCoachId = null;

    public ?int $selectedAvailabilityId = null;

    #[Rule('required|date|after:today')]
    public string $scheduledDate = '';

    public function openBooking(int $coachId): void
    {
        $this->bookingCoachId = $coachId;
        $this->scheduledDate = '';
        $this->selectedAvailabilityId = null;
    }

    public function closeBooking(): void
    {
        $this->bookingCoachId = null;
    }

    public function confirmBooking(): void
    {
        $this->validate();

        $coach = Coach::find($this->bookingCoachId);

        if (! $coach) {
            $this->closeBooking();
            session()->flash('error', 'This coach is no longer available. Please try another.');

            return;
        }

        $scheduledAt = Carbon::parse($this->scheduledDate);

        try {
            $booking = Booking::create([
                'member_id' => auth()->id(),
                'coach_id' => $coach->id,
                'scheduled_at' => $scheduledAt,
                'status' => 'confirmed',
            ]);

            SendBookingConfirmation::dispatch($booking->id);

            $this->closeBooking();
            session()->flash('success', "Booking confirmed with {$coach->name} on {$scheduledAt->format('M j, Y')}.");
        } catch (\Throwable) {
            session()->flash('error', 'Booking could not be saved. Please try again.');
        }
    }

    public function cancel(int $bookingId): void
    {
        $booking = Booking::where('id', $bookingId)
            ->where('member_id', auth()->id())
            ->firstOrFail();

        if ($booking->scheduled_at->diffInHours(now()) < 24) {
            session()->flash('error', 'Cannot cancel a booking within 24 hours.');

            return;
        }

        $booking->status = 'cancelled';
        $booking->save();
        session()->flash('success', 'Booking cancelled.');
    }

    public function render(): View
    {
        $coaches = Coach::all();
        $myBookings = Booking::where('member_id', auth()->id())
            ->with('coach')
            ->where('status', 'confirmed')
            ->where('scheduled_at', '>=', now())
            ->orderBy('scheduled_at')
            ->get();

        $bookingCoach = $this->bookingCoachId
            ? Coach::with('availabilities')->find($this->bookingCoachId)
            : null;

        return view('livewire.portal.coach-roster', compact('coaches', 'myBookings', 'bookingCoach'));
    }
}
