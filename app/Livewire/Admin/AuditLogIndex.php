<?php

namespace App\Livewire\Admin;

use App\Models\AuditLog;
use Illuminate\Contracts\View\View;
use Livewire\Component;
use Livewire\WithPagination;

class AuditLogIndex extends Component
{
    use WithPagination;

    public string $modelFilter = '';

    public string $dateFrom = '';

    public string $dateTo = '';

    public function updatingModelFilter(): void
    {
        $this->resetPage();
    }

    public function render(): View
    {
        $query = AuditLog::with('actor')
            ->when($this->modelFilter, fn ($q) => $q->where('action', 'like', $this->modelFilter.'%'))
            ->when($this->dateFrom, fn ($q) => $q->whereDate('created_at', '>=', $this->dateFrom))
            ->when($this->dateTo, fn ($q) => $q->whereDate('created_at', '<=', $this->dateTo))
            ->latest();

        return view('livewire.admin.audit-log-index', [
            'logs' => $query->paginate(20),
        ]);
    }
}
