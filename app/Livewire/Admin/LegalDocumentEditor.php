<?php

namespace App\Livewire\Admin;

use App\Http\Controllers\Admin\LegalDocumentController;
use App\Models\SiteContent;
use App\Services\AuditLogger;
use App\Services\LegalDraftService;
use Illuminate\View\View;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Rule;
use Livewire\Component;

class LegalDocumentEditor extends Component
{
    public string $slug = '';

    public string $siteContentKey = '';

    public string $title = '';

    #[Rule('required|string|min:10')]
    public string $body = '';

    public int $version = 1;

    public string $draftBody = '';

    public bool $showPreview = false;

    public bool $showDraftPreview = false;

    public bool $isDrafting = false;

    public string $draftError = '';

    public bool $saved = false;

    public function mount(string $slug): void
    {
        abort_unless(array_key_exists($slug, LegalDocumentController::DOCUMENTS), 404);

        $this->slug = $slug;
        $this->siteContentKey = LegalDocumentController::DOCUMENTS[$slug]['key'];
        $this->title = LegalDocumentController::DOCUMENTS[$slug]['title'];
        $this->body = SiteContent::get($this->siteContentKey);
        $this->version = (int) SiteContent::get($this->siteContentKey.'_version', '1');
    }

    public function save(): void
    {
        $this->validate();

        SiteContent::set($this->siteContentKey, $this->body, 'html');

        $newVersion = $this->version + 1;
        SiteContent::set($this->siteContentKey.'_version', (string) $newVersion, 'text');
        $this->version = $newVersion;

        $record = SiteContent::where('key', $this->siteContentKey)->first();
        if ($record) {
            app(AuditLogger::class)->log('legal.document.updated', $record, [
                'document_key' => $this->siteContentKey,
                'new_version' => $newVersion,
            ]);
        }

        $this->saved = true;
    }

    public function preview(): void
    {
        $this->showPreview = true;
    }

    public function draftWithAi(): void
    {
        $this->isDrafting = true;
        $this->draftError = '';
        $this->draftBody = '';

        try {
            $gymName = SiteContent::get('gym_name', 'AmigosFitnessGym');
            $this->draftBody = app(LegalDraftService::class)->draft($this->slug, $gymName);
            $this->showDraftPreview = true;
        } catch (\Throwable $e) {
            $this->draftError = 'Could not generate draft. Try again or write manually.';
        } finally {
            $this->isDrafting = false;
        }
    }

    public function useDraft(): void
    {
        $this->body = $this->draftBody;
        $this->draftBody = '';
        $this->showDraftPreview = false;
        $this->saved = false;
    }

    #[Computed]
    public function previewBody(): string
    {
        return str_replace(
            ['{{member_name}}', '{{plan_name}}', '{{plan_price}}', '{{start_date}}', '{{gym_name}}'],
            ['Juan dela Cruz', 'Monthly Plan', '₱1,500.00', now()->format('F j, Y'), SiteContent::get('gym_name', 'AmigosFitnessGym')],
            $this->body
        );
    }

    public function render(): View
    {
        return view('livewire.admin.legal-document-editor', [
            'previewBody' => $this->previewBody(),
        ]);
    }
}
