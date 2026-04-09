<?php

namespace App\Livewire\Portal;

use App\Models\Event;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class EventsGrid extends Component
{
    public function render(): View
    {
        $events = Event::where('is_visible', true)
            ->orderByDesc('date')
            ->get();

        return view('livewire.portal.events-grid', compact('events'));
    }
}
