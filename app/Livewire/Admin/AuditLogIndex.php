<?php

namespace App\Livewire\Admin;

use App\Models\AuditLog;
use Illuminate\Contracts\View\View;
use Livewire\Component;
use Livewire\WithPagination;

class AuditLogIndex extends Component
{
    use WithPagination;

    public int $onEachSide = 1;

    public string $search = '';

    public string $modelFilter = '';

    public string $dateFrom = '';

    public string $dateTo = '';

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingModelFilter(): void
    {
        $this->resetPage();
    }

    public function updatingDateFrom(): void
    {
        $this->resetPage();
    }

    public function updatingDateTo(): void
    {
        $this->resetPage();
    }

    public function render(): View
    {
        $query = AuditLog::with('actor')
            ->when($this->search, function ($q) {
                $term = '%'.strtolower($this->search).'%';
                $q->where(function ($q2) use ($term) {
                    $q2->whereRaw('LOWER(action) LIKE ?', [$term])
                        ->orWhereRaw('LOWER(model_type) LIKE ?', [$term])
                        ->orWhereHas('actor', fn ($q3) => $q3->whereRaw('LOWER(name) LIKE ?', [$term]));
                });
            })
            ->when($this->modelFilter, fn ($q) => $q->where('action', 'like', $this->modelFilter.'%'))
            ->when($this->dateFrom, fn ($q) => $q->whereDate('created_at', '>=', $this->dateFrom))
            ->when($this->dateTo, fn ($q) => $q->whereDate('created_at', '<=', $this->dateTo))
            ->latest();

        return view('livewire.admin.audit-log-index', [
            'logs' => $query->paginate(8),
        ]);
    }
}
