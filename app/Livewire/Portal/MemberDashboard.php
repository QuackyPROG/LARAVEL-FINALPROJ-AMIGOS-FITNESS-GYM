<?php

namespace App\Livewire\Portal;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Carbon;
use Livewire\Component;

class MemberDashboard extends Component
{
    public function render(): View
    {
        $user = auth()->user();
        $membership = $user->memberships()
            ->with('plan')
            ->latest('created_at')
            ->first();
        $upcomingBookings = $user->bookings()
            ->with('coach')
            ->where('scheduled_at', '>=', now())
            ->where('status', '!=', 'cancelled')
            ->orderBy('scheduled_at')
            ->limit(3)
            ->get();

        $status = 'no_membership';
        $daysRemaining = null;
        $showRenew = false;

        if ($membership) {
            $now = Carbon::today();
            $expiresAt = $membership->expires_at;

            if ($membership->status === 'active') {
                $daysRemaining = (int) $now->diffInDays($expiresAt, false);

                if ($daysRemaining < 0) {
                    $status = 'expired';
                    $showRenew = true;
                } elseif ($daysRemaining <= 7) {
                    $status = 'expiring_soon';
                    $showRenew = true;
                } else {
                    $status = 'active';
                }
            } else {
                $status = 'expired';
                $showRenew = true;
            }
        }

        return view('livewire.portal.member-dashboard', [
            'user' => $user,
            'membership' => $membership,
            'membershipStatus' => $status,
            'daysRemaining' => $daysRemaining,
            'showRenew' => $showRenew,
            'upcomingBookings' => $upcomingBookings,
        ]);
    }
}
