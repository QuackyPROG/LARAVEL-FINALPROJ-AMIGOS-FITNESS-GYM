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
            ->get();

        $bookings = $this->coach->bookings()
            ->with('member')
            ->latest('scheduled_at')
            ->paginate(15);

        $stats = [
            'total_bookings' => $this->coach->bookings()->count(),
            'confirmed_bookings' => $this->coach->bookings()->where('status', 'confirmed')->count(),
            'cancelled_bookings' => $this->coach->bookings()->where('status', 'cancelled')->count(),
            'unique_members' => $this->coach->bookings()->distinct('member_id')->count('member_id'),
        ];

        return view('livewire.admin.coach-dashboard', compact('schedules', 'bookings', 'stats'));
    }
}
