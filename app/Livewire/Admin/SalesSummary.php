<?php

namespace App\Livewire\Admin;

use App\Models\Membership;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Carbon;
use Livewire\Component;
use Livewire\WithPagination;

class SalesSummary extends Component
{
    use WithPagination;

    public int $onEachSide = 1;

    public string $search = '';

    public string $period = 'month';

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingPeriod(): void
    {
        $this->resetPage();
    }

    public function render(): View
    {
        [$start, $end] = $this->resolvePeriod();

        $query = User::where('role', 'member')
            ->with(['memberships' => fn ($q) => $q->with('plan')->whereBetween('starts_at', [$start, $end])])
            ->when($this->search, fn ($q) => $q->where(function ($q2): void {
                $term = '%'.strtolower($this->search).'%';
                $q2->whereRaw('LOWER(name) LIKE ?', [$term])
                    ->orWhereRaw('LOWER(email) LIKE ?', [$term]);
            }))
            ->whereHas('memberships', fn ($q) => $q->whereBetween('starts_at', [$start, $end]))
            ->latest();

        $members = $query->paginate(15);

        $totalSales = User::where('role', 'member')
            ->join('memberships', 'users.id', '=', 'memberships.user_id')
            ->join('membership_plans', 'memberships.plan_id', '=', 'membership_plans.id')
            ->whereBetween('memberships.starts_at', [$start, $end])
            ->sum('membership_plans.price');

        $totalTransactions = Membership::whereBetween('starts_at', [$start, $end])->count();

        return view('livewire.admin.sales-summary', [
            'members' => $members,
            'totalSales' => $totalSales,
            'totalTransactions' => $totalTransactions,
            'start' => $start,
            'end' => $end,
        ]);
    }

    private function resolvePeriod(): array
    {
        return match ($this->period) {
            'week' => [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()],
            'year' => [Carbon::now()->startOfYear(), Carbon::now()->endOfYear()],
            default => [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()],
        };
    }
}
