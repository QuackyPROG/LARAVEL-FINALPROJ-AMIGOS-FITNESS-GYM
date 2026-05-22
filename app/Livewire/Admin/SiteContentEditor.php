<?php

namespace App\Livewire\Admin;

use App\Models\SiteContent;
use Illuminate\View\View;
use Livewire\Attributes\Rule;
use Livewire\Component;

class SiteContentEditor extends Component
{
    #[Rule('required|string|max:120')]
    public string $hero_title = '';

    #[Rule('required|string|max:500')]
    public string $hero_subtitle = '';

    #[Rule('required|string|max:200')]
    public string $gym_hours = '';

    #[Rule('required|string|max:200')]
    public string $gym_address = '';

    #[Rule('required|string|max:50')]
    public string $gym_phone = '';

    public bool $saved = false;

    public function mount(): void
    {
        $this->hero_title = SiteContent::get('hero_title');
        $this->hero_subtitle = SiteContent::get('hero_subtitle');
        $this->gym_hours = SiteContent::get('gym_hours');
        $this->gym_address = SiteContent::get('gym_address');
        $this->gym_phone = SiteContent::get('gym_phone');
    }

    public function save(): void
    {
        $this->validate();

        SiteContent::set('hero_title', $this->hero_title);
        SiteContent::set('hero_subtitle', $this->hero_subtitle);
        SiteContent::set('gym_hours', $this->gym_hours);
        SiteContent::set('gym_address', $this->gym_address);
        SiteContent::set('gym_phone', $this->gym_phone);

        $this->saved = true;
    }

    public function render(): View
    {
        return view('livewire.admin.site-content-editor');
    }
}
