<?php

namespace App\Livewire\Admin;

use App\Models\Coach;
use Illuminate\Contracts\View\View;
use Livewire\Component;
use Livewire\WithPagination;

class CoachDashboard extends Component
{
    use WithPagination;

    public Coach $coach;

    public function mount(Coach $coach): void
    {
        $this->coach = $coach;
    }

    public function render(): View
    {
        $schedules = $this->coach->classSchedules()
            ->orderBy('day_of_week')
            ->orderBy('time')
            ->limit(200)
            ->get();

        $bookings = $this->coach->bookings()
            ->with('member')
            ->latest('scheduled_at')
            ->paginate(15);

        $allBookings = $this->coach->bookings()->get(['status', 'member_id']);
        $stats = [
            'total_bookings' => $allBookings->count(),
            'confirmed_bookings' => $allBookings->where('status', 'confirmed')->count(),
            'cancelled_bookings' => $allBookings->where('status', 'cancelled')->count(),
            'unique_members' => $allBookings->pluck('member_id')->unique()->count(),
        ];

        return view('livewire.admin.coach-dashboard', compact('schedules', 'bookings', 'stats'));
    }
}
