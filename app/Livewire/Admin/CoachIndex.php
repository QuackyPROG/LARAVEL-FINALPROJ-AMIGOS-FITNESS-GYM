<?php

namespace App\Livewire\Admin;

use App\Models\Coach;
use App\Services\AuditLogger;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Rule;
use Livewire\Component;
use Livewire\WithFileUploads;

class CoachIndex extends Component
{
    use WithFileUploads;

    public bool $showForm = false;

    public ?int $editingId = null;

    #[Rule('required|string|max:100')]
    public string $name = '';

    #[Rule('nullable|string')]
    public string $bio = '';

    #[Rule('nullable|string')]
    public string $specializationsRaw = '';

    #[Rule('nullable|image|max:2048')]
    public $photo = null;

    public function openCreate(): void
    {
        $this->resetForm();
        $this->showForm = true;
    }

    public function openEdit(int $id): void
    {
        $coach = Coach::findOrFail($id);
        $this->editingId = $id;
        $this->name = $coach->name;
        $this->bio = $coach->bio ?? '';
        $this->specializationsRaw = implode("\n", $coach->specializations ?? []);
        $this->showForm = true;
    }

    public function save(): void
    {
        $this->validate([
            'name' => 'required|string|max:100',
            'bio' => 'nullable|string',
            'specializationsRaw' => 'nullable|string',
            'photo' => 'nullable|image|max:2048',
        ]);

        $specializations = array_filter(array_map('trim', explode("\n", $this->specializationsRaw)));

        $data = [
            'name' => $this->name,
            'bio' => $this->bio ?: null,
            'specializations' => array_values($specializations),
        ];

        if ($this->photo) {
            $data['photo'] = $this->photo->storeAs('coaches', $this->photo->hashName(), 'public');
        }

        if ($this->editingId) {
            $coach = Coach::findOrFail($this->editingId);
            $coach->update($data);
            app(AuditLogger::class)->log('coach.updated', $coach, ['name' => $this->name]);
        } else {
            $coach = Coach::create($data);
            app(AuditLogger::class)->log('coach.created', $coach, ['name' => $this->name]);
        }

        $this->resetForm();
        session()->flash('success', 'Coach saved.');
    }

    public function delete(int $id): void
    {
        $coach = Coach::findOrFail($id);
        app(AuditLogger::class)->log('coach.deleted', $coach, ['name' => $coach->name]);
        $coach->delete();
        session()->flash('success', 'Coach deleted.');
    }

    private function resetForm(): void
    {
        $this->showForm = false;
        $this->editingId = null;
        $this->name = '';
        $this->bio = '';
        $this->specializationsRaw = '';
        $this->photo = null;
    }

    public function render(): View
    {
        return view('livewire.admin.coach-index', [
            'coaches' => Coach::withCount('bookings')->get(),
        ]);
    }
}
