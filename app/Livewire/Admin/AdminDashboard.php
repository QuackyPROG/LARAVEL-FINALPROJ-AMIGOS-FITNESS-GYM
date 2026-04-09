<?php

namespace App\Livewire\Admin;

use App\Models\Membership;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Carbon;
use Livewire\Component;

class AdminDashboard extends Component
{
    public function render(): View
    {
        $totalMembers = User::where('role', 'member')->count();
        $activeMembers = User::where('role', 'member')->where('status', 'active')->count();
        $expiringSoon = Membership::where('status', 'active')
            ->whereBetween('expires_at', [Carbon::today(), Carbon::today()->addDays(7)])
            ->count();
        $newThisMonth = User::where('role', 'member')
            ->whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->count();
        $recentSignups = User::where('role', 'member')
            ->latest()
            ->take(5)
            ->with('activeMembership.plan')
            ->get();

        return view('livewire.admin.admin-dashboard', compact(
            'totalMembers',
            'activeMembers',
            'expiringSoon',
            'newThisMonth',
            'recentSignups',
        ));
    }
}
