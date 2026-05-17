<?php

namespace App\Livewire\Admin;

use App\Models\Coach;
use App\Services\AuditLogger;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Livewire\Attributes\Rule;
use Livewire\Component;
use Livewire\WithFileUploads;

class CoachIndex extends Component
{
    use WithFileUploads;

    public bool $showForm = false;
    public bool $showDeleteModal = false;

    public ?int $editingId = null;
    public ?int $selectedCoachId = null;
    public ?Coach $selectedCoach = null;

    #[Rule('required|string|max:100')]
    public string $name = '';

    #[Rule('nullable|string')]
    public string $bio = '';

    #[Rule('nullable|string')]
    public string $specializationsRaw = '';

    #[Rule('nullable|image|max:2048')]
    public $photo = null;
    // base64 cropped image from client-side Cropper.js (data URL)
    public ?string $photoCropped = null;

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
            'photoCropped' => 'nullable|string',
        ]);

        $specializations = array_filter(array_map('trim', explode("\n", $this->specializationsRaw)));

        $data = [
            'name' => $this->name,
            'bio' => $this->bio ?: null,
            'specializations' => array_values($specializations),
        ];

        if ($this->photoCropped) {
            if (preg_match('/^data:image\/([a-zA-Z]+);base64,/', $this->photoCropped, $matches)) {
                $ext = strtolower($matches[1]) === 'jpeg' ? 'jpg' : strtolower($matches[1]);
            } else {
                $ext = 'jpg';
            }
            $filename = 'coaches/' . Str::random(40) . '.' . $ext;
            $dataBody = substr($this->photoCropped, strpos($this->photoCropped, ',') + 1);
            $decoded = base64_decode($dataBody);
            Storage::disk('public')->put($filename, $decoded);
            $data['photo'] = $filename;
        } elseif ($this->photo) {
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

    public function confirmDelete(int $id): void
    {
        $this->selectedCoachId = $id;
        $this->selectedCoach = Coach::findOrFail($id);
        $this->showDeleteModal = true;
    }

    public function executeDelete(): void
    {
        if ($this->selectedCoachId) {
            $coach = Coach::findOrFail($this->selectedCoachId);
            app(AuditLogger::class)->log('coach.deleted', $coach, ['name' => $coach->name]);
            $coach->delete();
            session()->flash('success', 'Coach deleted.');
        }
        $this->showDeleteModal = false;
        $this->selectedCoachId = null;
        $this->selectedCoach = null;
    }

    private function resetForm(): void
    {
        $this->showForm = false;
        $this->editingId = null;
        $this->name = '';
        $this->bio = '';
        $this->specializationsRaw = '';
        $this->reset('photo');
        $this->photoCropped = null;
    }

    public function render(): View
    {
        return view('livewire.admin.coach-index', [
            'coaches' => Coach::withCount('bookings')->get(),
        ]);
    }
}
