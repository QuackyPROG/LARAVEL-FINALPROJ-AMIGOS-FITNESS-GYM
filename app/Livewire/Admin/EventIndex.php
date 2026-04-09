<?php

namespace App\Livewire\Admin;

use App\Models\Event;
use App\Services\AuditLogger;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Rule;
use Livewire\Component;
use Livewire\WithFileUploads;

class EventIndex extends Component
{
    use WithFileUploads;

    public bool $showForm = false;

    public ?int $editingId = null;

    #[Rule('required|string|max:200')]
    public string $title = '';

    #[Rule('nullable|string')]
    public string $description = '';

    #[Rule('required|date')]
    public string $date = '';

    public bool $isVisible = true;

    #[Rule('nullable|image|max:4096')]
    public $coverImage = null;

    public function openCreate(): void
    {
        $this->resetForm();
        $this->showForm = true;
    }

    public function openEdit(int $id): void
    {
        $event = Event::findOrFail($id);
        $this->editingId = $id;
        $this->title = $event->title;
        $this->description = $event->description ?? '';
        $this->date = $event->date->format('Y-m-d\TH:i');
        $this->isVisible = $event->is_visible;
        $this->showForm = true;
    }

    public function save(): void
    {
        $this->validate([
            'title' => 'required|string|max:200',
            'description' => 'nullable|string',
            'date' => 'required|date',
            'coverImage' => 'nullable|image|max:4096',
        ]);

        $data = [
            'title' => $this->title,
            'description' => $this->description ?: null,
            'date' => $this->date,
            'is_visible' => $this->isVisible,
        ];

        if ($this->coverImage) {
            $data['cover_image'] = $this->coverImage->storeAs('events', $this->coverImage->hashName(), 'public');
        }

        if ($this->editingId) {
            $event = Event::findOrFail($this->editingId);
            $event->update($data);
            app(AuditLogger::class)->log('event.updated', $event, ['title' => $this->title]);
        } else {
            $event = Event::create($data);
            app(AuditLogger::class)->log('event.created', $event, ['title' => $this->title]);
        }

        $this->resetForm();
        session()->flash('success', 'Event saved.');
    }

    public function toggleVisible(int $id): void
    {
        $event = Event::findOrFail($id);
        $event->is_visible = ! $event->is_visible;
        $event->save();
        app(AuditLogger::class)->log('event.toggled', $event, ['is_visible' => $event->is_visible]);
    }

    private function resetForm(): void
    {
        $this->showForm = false;
        $this->editingId = null;
        $this->title = '';
        $this->description = '';
        $this->date = '';
        $this->isVisible = true;
        $this->coverImage = null;
    }

    public function render(): View
    {
        return view('livewire.admin.event-index', [
            'events' => Event::orderByDesc('date')->get(),
        ]);
    }
}
