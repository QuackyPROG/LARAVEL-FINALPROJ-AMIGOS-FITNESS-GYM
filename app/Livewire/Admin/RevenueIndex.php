<?php

namespace App\Livewire\Admin;

use App\Models\Booking;
use App\Models\Membership;
use App\Models\MembershipPlan;
use App\Models\Payment;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Carbon;
use Livewire\Component;

class RevenueIndex extends Component
{
    public string $period = 'month';

    public string $planFilter = '';

    public function render(): View
    {
        [$start, $end] = $this->resolvePeriod();

        // Payment revenue (amount stored in centavos)
        $totalRevenue = Payment::where('status', 'paid')
            ->whereBetween('created_at', [$start, $end])
            ->sum('amount') / 100;

        $transactionCount = Payment::where('status', 'paid')
            ->whereBetween('created_at', [$start, $end])
            ->count();

        // Memberships by plan
        $membershipsByPlan = Membership::with('plan')
            ->whereBetween('starts_at', [$start, $end])
            ->when($this->planFilter, fn ($q) => $q->where('plan_id', $this->planFilter))
            ->get()
            ->groupBy('plan.name')
            ->map(fn ($items) => [
                'count' => $items->count(),
                'revenue' => $items->sum(fn ($m) => $m->plan->price ?? 0),
            ]);

        // Booking stats
        $totalBookings = Booking::whereBetween('created_at', [$start, $end])->count();
        $confirmedBookings = Booking::where('status', 'confirmed')
            ->whereBetween('created_at', [$start, $end])
            ->count();

        $plans = MembershipPlan::active()->orderBy('name')->get();

        return view('livewire.admin.revenue-index', compact(
            'totalRevenue',
            'transactionCount',
            'membershipsByPlan',
            'totalBookings',
            'confirmedBookings',
            'plans',
        ));
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
