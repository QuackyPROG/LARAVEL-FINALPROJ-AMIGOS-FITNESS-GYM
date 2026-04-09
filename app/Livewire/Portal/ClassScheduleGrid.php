<?php

namespace App\Livewire\Portal;

use App\Models\ClassSchedule;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class ClassScheduleGrid extends Component
{
    public function render(): View
    {
        $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
        $schedules = ClassSchedule::with('coach')
            ->orderBy('time')
            ->get()
            ->groupBy('day_of_week');

        return view('livewire.portal.class-schedule-grid', compact('days', 'schedules'));
    }
}
