<?php

namespace App\Livewire\Admin;

use App\Models\Event;
use App\Services\AuditLogger;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Livewire\Attributes\Rule;
use Livewire\Component;
use Livewire\WithFileUploads;

class EventIndex extends Component
{
    use WithFileUploads;

    public bool $showForm = false;
    public bool $showDeleteModal = false;

    public ?int $editingId = null;
    public ?int $selectedEventId = null;
    public ?Event $selectedEvent = null;

    #[Rule('required|string|max:200')]
    public string $title = '';

    #[Rule('nullable|string')]
    public string $description = '';

    #[Rule('required|date')]
    public string $date = '';

    public bool $isVisible = true;

    #[Rule('nullable|image|max:4096')]
    public $coverImage = null;
    // cropped base64 data URL from client-side cropper
    public ?string $coverImageCropped = null;

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
            'coverImageCropped' => 'nullable|string',
        ]);

        $data = [
            'title' => $this->title,
            'description' => $this->description ?: null,
            'date' => $this->date,
            'is_visible' => $this->isVisible,
        ];

        if ($this->coverImageCropped) {
            if (preg_match('/^data:image\/([a-zA-Z]+);base64,/', $this->coverImageCropped, $matches)) {
                $ext = strtolower($matches[1]) === 'jpeg' ? 'jpg' : strtolower($matches[1]);
            } else {
                $ext = 'jpg';
            }
            $filename = 'events/' . Str::random(40) . '.' . $ext;
            $dataBody = substr($this->coverImageCropped, strpos($this->coverImageCropped, ',') + 1);
            $decoded = base64_decode($dataBody);
            Storage::disk('public')->put($filename, $decoded);
            $data['cover_image'] = $filename;
        } elseif ($this->coverImage) {
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

    public function confirmDelete(int $id): void
    {
        $this->selectedEventId = $id;
        $this->selectedEvent = Event::findOrFail($id);
        $this->showDeleteModal = true;
    }

    public function executeDelete(): void
    {
        if ($this->selectedEventId) {
            $event = Event::findOrFail($this->selectedEventId);
            app(AuditLogger::class)->log('event.deleted', $event, ['title' => $event->title]);
            $event->delete();
            session()->flash('success', 'Event deleted.');
        }
        $this->showDeleteModal = false;
        $this->selectedEventId = null;
        $this->selectedEvent = null;
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
        $this->coverImageCropped = null;
    }

    public function render(): View
    {
        return view('livewire.admin.event-index', [
            'events' => Event::orderByDesc('date')->get(),
        ]);
    }
}
