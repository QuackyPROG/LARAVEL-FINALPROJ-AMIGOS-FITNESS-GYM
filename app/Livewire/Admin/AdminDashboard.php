<?php

namespace App\Livewire\Admin;

use App\Models\Membership;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Carbon;
use Livewire\Component;

class AdminDashboard extends Component
{
    public string $search = '';
    public string $dateFilter = '';

    public function render(): View
    {
        $now = Carbon::now();
        $thirtyDaysAgo = $now->copy()->subDays(30);

        $totalMembers = User::where('role', 'member')->count();
        $totalMembersOld = User::where('role', 'member')->where('created_at', '<=', $thirtyDaysAgo)->count();
        $totalMembersChange = $this->calculatePercentageChange($totalMembers, $totalMembersOld);

        $activeMembers = User::where('role', 'member')->where('status', 'active')->count();
        $activeMembersOld = Membership::where('starts_at', '<=', $thirtyDaysAgo)
            ->where('expires_at', '>', $thirtyDaysAgo)
            ->distinct('user_id')
            ->count('user_id');
        $activeMembersChange = $this->calculatePercentageChange($activeMembers, $activeMembersOld);

        $expiringSoon = Membership::where('status', 'active')
            ->whereBetween('expires_at', [Carbon::today(), Carbon::today()->addDays(7)])
            ->count();
        $expiredLastWeek = Membership::whereBetween('expires_at', [Carbon::today()->subDays(7), Carbon::today()])->count();
        $expiringChange = $this->calculatePercentageChange($expiringSoon, $expiredLastWeek);

        $newThisMonth = User::where('role', 'member')
            ->whereMonth('created_at', $now->month)
            ->whereYear('created_at', $now->year)
            ->count();
        $newLastMonth = User::where('role', 'member')
            ->whereMonth('created_at', $now->copy()->subMonth()->month)
            ->whereYear('created_at', $now->copy()->subMonth()->year)
            ->count();
        $newMembersChange = $this->calculatePercentageChange($newThisMonth, $newLastMonth);

        $recentSignups = User::where('role', 'member')
            ->when($this->search, function ($q) {
                $term = '%' . strtolower($this->search) . '%';
                $q->where(function ($query) use ($term) {
                    $query->whereRaw('LOWER(name) LIKE ?', [$term])
                          ->orWhereRaw('LOWER(email) LIKE ?', [$term]);
                });
            })
            ->when($this->dateFilter, fn($q) => $q->whereDate('created_at', $this->dateFilter))
            ->latest()
            ->take(5)
            ->with('activeMembership.plan')
            ->get();

        return view('livewire.admin.admin-dashboard', compact(
            'totalMembers',
            'totalMembersChange',
            'activeMembers',
            'activeMembersChange',
            'expiringSoon',
            'expiringChange',
            'newThisMonth',
            'newMembersChange',
            'recentSignups',
        ));
    }

    private function calculatePercentageChange(int $current, int $previous): array
    {
        if ($previous === 0) {
            return [
                'value' => $current > 0 ? 100 : 0,
                'trend' => $current > 0 ? 'up' : 'neutral'
            ];
        }

        $change = (($current - $previous) / $previous) * 100;
        
        return [
            'value' => abs((int) round($change)),
            'trend' => $change > 0 ? 'up' : ($change < 0 ? 'down' : 'neutral')
        ];
    }
}
