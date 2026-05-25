<?php

namespace App\Livewire\Admin;

use App\Models\ClassSchedule;
use App\Models\Coach;
use App\Services\AuditLogger;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Rule;
use Livewire\Component;
use Livewire\WithPagination;

class ScheduleIndex extends Component
{
    use WithPagination;

    public int $onEachSide = 1;

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

    public bool $showDeleteModal = false;

    public ?int $editingId = null;

    public ?int $selectedScheduleId = null;

    public ?ClassSchedule $selectedSchedule = null;

    public string $search = '';

    public string $sort = 'name_asc';

    public string $coachFilter = '';

    public string $dayFilter = '';

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

    public function updated($property): void
    {
        if (in_array($property, ['sort', 'coachFilter', 'dayFilter', 'search'])) {
            $this->resetPage();
        }
    }

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

    public function confirmDelete(int $id): void
    {
        $this->selectedScheduleId = $id;
        $this->selectedSchedule = ClassSchedule::findOrFail($id);
        $this->showDeleteModal = true;
    }

    public function executeDelete(): void
    {
        if ($this->selectedScheduleId) {
            $schedule = ClassSchedule::findOrFail($this->selectedScheduleId);
            app(AuditLogger::class)->log('schedule.deleted', $schedule, []);
            $schedule->delete();
            session()->flash('success', 'Schedule deleted.');
        }
        $this->showDeleteModal = false;
        $this->selectedScheduleId = null;
        $this->selectedSchedule = null;
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
        $query = ClassSchedule::with('coach');

        if ($this->search !== '') {
            $query->whereRaw('LOWER(name) LIKE ?', ['%'.strtolower($this->search).'%']);
        }

        if ($this->coachFilter !== '') {
            $query->where('coach_id', $this->coachFilter);
        }

        if ($this->dayFilter !== '') {
            $query->where('day_of_week', $this->dayFilter);
        }

        if ($this->sort === 'name_asc') {
            $query->orderBy('name', 'asc');
        } elseif ($this->sort === 'name_desc') {
            $query->orderBy('name', 'desc');
        } elseif ($this->sort === 'capacity_asc') {
            $query->orderBy('capacity', 'asc');
        } elseif ($this->sort === 'capacity_desc') {
            $query->orderBy('capacity', 'desc');
        }

        return view('livewire.admin.schedule-index', [
            'schedules' => $query->paginate(6),
            'coaches' => Coach::orderBy('name')->get(),
        ]);
    }
}
