<?php

namespace App\Livewire\Portal;

use App\Models\ClassSchedule;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class ClassScheduleGrid extends Component
{
    public function render(): View
    {
        $days = [
            1 => 'Monday',
            2 => 'Tuesday',
            3 => 'Wednesday',
            4 => 'Thursday',
            5 => 'Friday',
            6 => 'Saturday',
            0 => 'Sunday',
        ];

        $schedules = ClassSchedule::with('coach')
            ->orderBy('day_of_week')
            ->orderBy('time')
            ->get()
            ->groupBy('day_of_week');

        return view('livewire.portal.class-schedule-grid', compact('days', 'schedules'));
    }
}
