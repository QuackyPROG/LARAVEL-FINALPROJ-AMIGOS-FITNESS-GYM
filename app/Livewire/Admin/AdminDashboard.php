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

    public string $period = 'month';

    public string $customStart = '';

    public string $customEnd = '';

    public function render(): View
    {
        [$start, $end] = $this->resolvePeriod();
        [$priorStart, $priorEnd] = $this->resolvePriorPeriod($start, $end);

        // Total Members — count within period
        $totalMembers = User::where('role', 'member')
            ->whereBetween('created_at', [$start, $end])
            ->count();
        $totalMembersPrior = User::where('role', 'member')
            ->whereBetween('created_at', [$priorStart, $priorEnd])
            ->count();
        $totalMembersChange = $this->calculatePercentageChange($totalMembers, $totalMembersPrior);

        // Active Members — memberships active during period
        $activeMembers = Membership::where('starts_at', '<=', $end)
            ->where('expires_at', '>=', $start)
            ->where('status', 'active')
            ->distinct('user_id')
            ->count('user_id');
        $activeMembersPrior = Membership::where('starts_at', '<=', $priorEnd)
            ->where('expires_at', '>=', $priorStart)
            ->where('status', 'active')
            ->distinct('user_id')
            ->count('user_id');
        $activeMembersChange = $this->calculatePercentageChange($activeMembers, $activeMembersPrior);

        // Expiring Soon — expiring within 7 days of period end
        $expiringSoon = Membership::where('status', 'active')
            ->whereBetween('expires_at', [$end, $end->copy()->addDays(7)])
            ->count();
        $expiringSoonPrior = Membership::where('status', 'active')
            ->whereBetween('expires_at', [$priorEnd, $priorEnd->copy()->addDays(7)])
            ->count();
        $expiringChange = $this->calculatePercentageChange($expiringSoon, $expiringSoonPrior);

        // New Members — created within period
        $newInPeriod = User::where('role', 'member')
            ->whereBetween('created_at', [$start, $end])
            ->count();
        $newInPeriodPrior = User::where('role', 'member')
            ->whereBetween('created_at', [$priorStart, $priorEnd])
            ->count();
        $newInPeriodChange = $this->calculatePercentageChange($newInPeriod, $newInPeriodPrior);

        $memberGrowthData = $this->getMemberGrowthData($start, $end);

        $recentSignups = User::where('role', 'member')
            ->when($this->search, function ($q) {
                $term = '%'.strtolower($this->search).'%';
                $q->where(function ($query) use ($term) {
                    $query->whereRaw('LOWER(name) LIKE ?', [$term])
                        ->orWhereRaw('LOWER(email) LIKE ?', [$term]);
                });
            })
            ->when($this->dateFilter, fn ($q) => $q->whereDate('created_at', $this->dateFilter))
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
            'newInPeriod',
            'newInPeriodChange',
            'memberGrowthData',
            'recentSignups',
        ));
    }

    private function resolvePeriod(): array
    {
        return match ($this->period) {
            'week' => [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()],
            'year' => [Carbon::now()->startOfYear(), Carbon::now()->endOfYear()],
            'custom' => $this->customStart && $this->customEnd
                ? [Carbon::parse($this->customStart)->startOfDay(), Carbon::parse($this->customEnd)->endOfDay()]
                : [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()],
            default => [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()],
        };
    }

    private function resolvePriorPeriod(Carbon $start, Carbon $end): array
    {
        $length = $start->diffInDays($end) + 1;
        $priorEnd = $start->copy()->subSecond();
        $priorStart = $priorEnd->copy()->subDays($length - 1)->startOfDay();

        return [$priorStart, $priorEnd];
    }

    private function getMemberGrowthData(Carbon $start, Carbon $end): array
    {
        $bucketType = $this->resolveBucketType($start, $end);

        $rows = User::where('role', 'member')
            ->whereBetween('created_at', [$start, $end])
            ->selectRaw('DATE(created_at) as day, COUNT(*) as count')
            ->groupBy('day')
            ->orderBy('day')
            ->get()
            ->keyBy('day');

        return $this->buildBuckets($start, $end, $bucketType, $rows, 'count');
    }

    private function resolveBucketType(Carbon $start, Carbon $end): string
    {
        if ($this->period === 'week') {
            return 'daily';
        }
        if ($this->period === 'month') {
            return 'weekly';
        }
        if ($this->period === 'year') {
            return 'monthly';
        }
        // custom: auto-detect
        $days = $start->diffInDays($end) + 1;
        if ($days <= 14) {
            return 'daily';
        }
        if ($days <= 90) {
            return 'weekly';
        }

        return 'monthly';
    }

    private function buildBuckets(Carbon $start, Carbon $end, string $bucketType, $rows, string $field): array
    {
        $labels = [];
        $values = [];

        if ($bucketType === 'daily') {
            $cursor = $start->copy()->startOfDay();
            while ($cursor->lte($end)) {
                $key = $cursor->format('Y-m-d');
                $labels[] = $cursor->format('D');
                $values[] = (float) ($rows->get($key)?->{$field} ?? 0);
                $cursor->addDay();
            }
        } elseif ($bucketType === 'weekly') {
            $weekStart = $start->copy()->startOfWeek();
            $weekNum = 1;
            while ($weekStart->lte($end)) {
                $weekEnd = $weekStart->copy()->endOfWeek();
                $total = 0.0;
                $day = $weekStart->copy();
                while ($day->lte($weekEnd) && $day->lte($end)) {
                    $key = $day->format('Y-m-d');
                    $total += (float) ($rows->get($key)?->{$field} ?? 0);
                    $day->addDay();
                }
                if ($weekStart->gte($start)) {
                    $labels[] = 'Week '.$weekNum;
                    $values[] = $total;
                }
                $weekNum++;
                $weekStart->addWeek();
            }
        } else {
            // monthly
            $cursor = $start->copy()->startOfMonth();
            while ($cursor->lte($end)) {
                $monthStart = $cursor->copy()->startOfMonth()->max($start);
                $monthEnd = $cursor->copy()->endOfMonth()->min($end);
                $total = 0.0;
                $day = $monthStart->copy();
                while ($day->lte($monthEnd)) {
                    $key = $day->format('Y-m-d');
                    $total += (float) ($rows->get($key)?->{$field} ?? 0);
                    $day->addDay();
                }
                $labels[] = $cursor->format('M');
                $values[] = $total;
                $cursor->addMonth();
            }
        }

        return ['labels' => $labels, 'values' => $values];
    }

    private function calculatePercentageChange(int $current, int $previous): array
    {
        if ($previous === 0) {
            return [
                'value' => $current > 0 ? 100 : 0,
                'trend' => $current > 0 ? 'up' : 'neutral',
            ];
        }

        $change = (($current - $previous) / $previous) * 100;

        return [
            'value' => abs((int) round($change)),
            'trend' => $change > 0 ? 'up' : ($change < 0 ? 'down' : 'neutral'),
        ];
    }
}
