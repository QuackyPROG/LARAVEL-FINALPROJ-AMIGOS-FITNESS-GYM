<?php

namespace App\Livewire\Admin;

use App\Models\ClassSchedule;
use App\Models\Coach;
use App\Services\AuditLogger;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Rule;
use Livewire\Component;

class ScheduleIndex extends Component
{
    private const DAY_LABELS = [
        0 => 'Sunday',
        1 => 'Monday',
        2 => 'Tuesday',
        3 => 'Wednesday',
        4 => 'Thursday',
        5 => 'Friday',
        6 => 'Saturday',
    ];

    public bool $showForm = false;

    public ?int $editingId = null;

    #[Rule('required|string|max:100')]
    public string $name = '';

    #[Rule('nullable|exists:coaches,id')]
    public ?int $coachId = null;

    #[Rule('required|integer|between:0,6')]
    public int $dayOfWeek = 1;

    #[Rule('required|string')]
    public string $time = '09:00';

    #[Rule('required|integer|min:1')]
    public int $capacity = 10;

    public bool $isRecurring = true;

    public function openCreate(): void
    {
        $this->resetForm();
        $this->showForm = true;
    }

    public function openEdit(int $id): void
    {
        $schedule = ClassSchedule::findOrFail($id);
        $this->editingId = $id;
        $this->name = $schedule->name;
        $this->coachId = $schedule->coach_id;
        $this->dayOfWeek = (int) $schedule->day_of_week;
        $this->time = $schedule->time;
        $this->capacity = $schedule->capacity;
        $this->isRecurring = $schedule->is_recurring;
        $this->showForm = true;
    }

    public function save(): void
    {
        $this->validate();

        $data = [
            'name' => $this->name,
            'coach_id' => $this->coachId,
            'day_of_week' => $this->dayOfWeek,
            'time' => $this->time,
            'capacity' => $this->capacity,
            'is_recurring' => $this->isRecurring,
        ];

        if ($this->editingId) {
            $schedule = ClassSchedule::findOrFail($this->editingId);
            $schedule->update($data);
            app(AuditLogger::class)->log('schedule.updated', $schedule, $data);
        } else {
            $schedule = ClassSchedule::create($data);
            app(AuditLogger::class)->log('schedule.created', $schedule, $data);
        }

        $this->resetForm();
        session()->flash('success', 'Schedule saved.');
    }

    public function delete(int $id): void
    {
        $schedule = ClassSchedule::findOrFail($id);
        app(AuditLogger::class)->log('schedule.deleted', $schedule, []);
        $schedule->delete();
    }

    private function resetForm(): void
    {
        $this->showForm = false;
        $this->editingId = null;
        $this->name = '';
        $this->coachId = null;
        $this->dayOfWeek = 1;
        $this->time = '09:00';
        $this->capacity = 10;
        $this->isRecurring = true;
    }

    public function dayLabel(int $day): string
    {
        return self::DAY_LABELS[$day] ?? 'Unknown';
    }

    public function dayOptions(): array
    {
        return self::DAY_LABELS;
    }

    public function render(): View
    {
        return view('livewire.admin.schedule-index', [
            'schedules' => ClassSchedule::with('coach')->orderBy('day_of_week')->orderBy('time')->get(),
            'coaches' => Coach::orderBy('name')->get(),
        ]);
    }
}
