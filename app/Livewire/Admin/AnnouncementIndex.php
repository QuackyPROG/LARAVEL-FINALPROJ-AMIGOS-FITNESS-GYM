<?php

namespace App\Livewire\Admin;

use App\Jobs\AnnouncementMailer;
use App\Models\Announcement;
use App\Models\MembershipPlan;
use App\Models\User;
use App\Services\AuditLogger;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Rule;
use Livewire\Component;

class AnnouncementIndex extends Component
{
    public bool $showForm = false;

    public bool $showPreview = false;

    public string $search = '';

    public string $filterStatus = '';

    #[Rule('required|string|max:255')]
    public string $subject = '';

    #[Rule('required|string')]
    public string $body = '';

    public string $recipientFilter = 'all';

    public ?int $planId = null;

    public function openCreate(): void
    {
        $this->resetForm();
        $this->showForm = true;
    }

    public function recipientCount(): int
    {
        return $this->buildRecipientQuery()->count();
    }

    public function send(): void
    {
        $this->validate();

        $announcement = Announcement::create([
            'admin_id' => auth()->id(),
            'subject' => $this->subject,
            'body' => $this->body,
            'recipient_filter' => $this->recipientFilter,
            'plan_id' => $this->planId,
            'sent_at' => now(),
        ]);

        $recipients = $this->buildRecipientQuery()->pluck('id');

        AnnouncementMailer::dispatch($announcement->id, $recipients->toArray());

        app(AuditLogger::class)->log('announcement.sent', $announcement, [
            'subject' => $this->subject,
            'recipient_count' => $recipients->count(),
        ]);

        $this->resetForm();
        session()->flash('success', "Announcement queued for {$recipients->count()} recipients.");
    }

    private function buildRecipientQuery()
    {
        $q = User::where('role', 'member')->where('status', 'active');

        if ($this->recipientFilter === 'plan' && $this->planId) {
            $q->whereHas('activeMembership', fn ($m) => $m->where('plan_id', $this->planId));
        }

        return $q;
    }

    private function resetForm(): void
    {
        $this->showForm = false;
        $this->showPreview = false;
        $this->subject = '';
        $this->body = '';
        $this->recipientFilter = 'all';
        $this->planId = null;
    }

    public function render(): View
    {
        $query = Announcement::with('admin')
            ->when($this->search, function ($q) {
                $q->whereRaw('LOWER(subject) LIKE ?', ['%'.strtolower($this->search).'%']);
            })
            ->when($this->filterStatus === 'sent', fn ($q) => $q->whereNotNull('sent_at'))
            ->when($this->filterStatus === 'draft', fn ($q) => $q->whereNull('sent_at'));

        return view('livewire.admin.announcement-index', [
            'announcements' => $query->latest()->get(),
            'plans' => MembershipPlan::active()->get(),
            'recipientCount' => $this->recipientCount(),
        ]);
    }
}
