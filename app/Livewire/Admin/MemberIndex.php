<?php

namespace App\Livewire\Admin;

use App\Models\MemberConsent;
use App\Models\Membership;
use App\Models\MembershipPlan;
use App\Models\SiteContent;
use App\Models\User;
use App\Services\AuditLogger;
use App\Services\PlanAdvisorService;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithPagination;

class MemberIndex extends Component
{
    use WithPagination;

    public int $onEachSide = 1;

    public string $search = '';

    public string $statusFilter = '';

    public bool $showAddModal = false;

    public bool $showViewModal = false;

    public bool $showDeactivateModal = false;

    public bool $showDeleteModal = false;

    public bool $showNotifyModal = false;

    public bool $showNotifyExpiringModal = false;

    public bool $showExtendModal = false;

    public bool $showPaymentModal = false;

    public ?int $selectedMemberId = null;

    public ?User $selectedMember = null;

    public string $addName = '';

    public string $addEmail = '';

    public ?int $addPlanId = null;

    public ?string $tempPasswordResult = null;

    public ?string $advisorRationale = null;

    public ?int $advisorPlanId = null;

    public bool $advisorLoading = false;

    public ?int $extendPlanId = null;

    public ?int $walkInPlanId = null;

    public bool $witnessedConsent = false;

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingStatusFilter(): void
    {
        $this->resetPage();
    }

    public function openExtendModal(int $userId): void
    {
        $this->showViewModal = false;
        $this->selectedMemberId = $userId;
        $this->selectedMember = User::findOrFail($userId);
        $this->reset(['extendPlanId']);
        $this->showExtendModal = true;
    }

    public function closeExtendModal(): void
    {
        $this->showExtendModal = false;
    }

    public function saveExtension(): void
    {
        if ($this->selectedMemberId === null) {
            Session::flash('error', 'No member selected.');

            return;
        }

        $data = $this->validate([
            'extendPlanId' => 'required|exists:membership_plans,id',
        ]);

        $user = User::findOrFail($this->selectedMemberId);
        $plan = MembershipPlan::findOrFail($data['extendPlanId']);

        // Check if there is an active membership and extend from its expiration,
        // otherwise start from today
        $activeMembership = $user->activeMembership;
        $activeExpiresAt = $activeMembership?->expires_at;
        $startDate = $activeExpiresAt && $activeExpiresAt->isFuture()
            ? $activeExpiresAt
            : now();

        Membership::create([
            'user_id' => $user->id,
            'plan_id' => $plan->id,
            'starts_at' => $startDate->toDateString(),
            'expires_at' => $startDate->copy()->addDays($plan->duration_days)->toDateString(),
            'status' => 'active',
            'payment_ref' => 'manual-ext-'.now()->format('YmdHis'),
        ]);

        // Ensure user is marked as active if they were inactive
        if ($user->status !== 'active') {
            $user->status = 'active';
            $user->save();
        }

        app(AuditLogger::class)->log('member.subscription_extended', $user, [
            'plan' => $plan->name,
            'added_days' => $plan->duration_days,
        ]);

        Session::flash('success', "Subscription extended for {$user->name}.");
        $this->showExtendModal = false;
    }

    public function updatedAddEmail(): void
    {
        $this->advisorRationale = null;
        $this->advisorPlanId = null;

        if (! filter_var($this->addEmail, FILTER_VALIDATE_EMAIL)) {
            return;
        }

        $this->advisorLoading = true;

        $result = app(PlanAdvisorService::class)->recommend($this->addEmail);

        $this->advisorLoading = false;

        if ($result) {
            $this->advisorRationale = $result['rationale'];
            $this->advisorPlanId = $result['plan_id'];
            if (! $this->addPlanId) {
                $this->addPlanId = $result['plan_id'];
            }
        }
    }

    public function openAddModal(): void
    {
        $this->reset(['addName', 'addEmail', 'addPlanId', 'tempPasswordResult', 'advisorRationale', 'advisorPlanId', 'advisorLoading']);
        $this->showAddModal = true;
    }

    public function closeAddModal(): void
    {
        $this->showAddModal = false;
    }

    public function saveMember(): void
    {
        $data = $this->validate([
            'addName' => 'required|string|max:255',
            'addEmail' => 'required|email|unique:users,email',
            'addPlanId' => 'required|exists:membership_plans,id',
        ]);

        $plan = MembershipPlan::findOrFail($data['addPlanId']);
        $tempPassword = Str::random(12);

        $user = User::create([
            'name' => $data['addName'],
            'email' => $data['addEmail'],
            'password' => Hash::make($tempPassword),
            'role' => 'member',
            'status' => 'active',
            'must_change_password' => true,
            'email_verified_at' => now(),
        ]);

        Membership::create([
            'user_id' => $user->id,
            'plan_id' => $plan->id,
            'starts_at' => now()->toDateString(),
            'expires_at' => now()->addDays($plan->duration_days)->toDateString(),
            'status' => 'active',
            'payment_ref' => 'walk-in-'.now()->format('YmdHis'),
        ]);

        app(AuditLogger::class)->log('member.created_walk_in', $user, [
            'plan' => $plan->name,
            'temp_password_set' => true,
        ]);

        $this->tempPasswordResult = $tempPassword;
        Session::flash('success', "Walk-in member {$user->name} created. Temp password: {$tempPassword}");
        $this->showAddModal = false;
    }

    public function openViewModal(int $userId): void
    {
        $this->selectedMemberId = $userId;
        $this->selectedMember = User::with(['profile', 'activeMembership.plan'])->findOrFail($userId);
        $this->showViewModal = true;
    }

    public function closeViewModal(): void
    {
        $this->showViewModal = false;
        $this->selectedMember = null;
    }

    public function confirmDeactivate(int $userId): void
    {
        $this->showViewModal = false;
        $this->selectedMemberId = $userId;
        $this->selectedMember = User::findOrFail($userId);
        $this->showDeactivateModal = true;
    }

    public function executeDeactivate(): void
    {
        if ($this->selectedMemberId === null) {
            $this->showDeactivateModal = false;

            return;
        }

        $user = User::findOrFail($this->selectedMemberId);
        $user->status = 'inactive';
        $user->save();

        app(AuditLogger::class)->log('member.deactivated', $user, ['status' => 'inactive']);
        Session::flash('success', "Member {$user->name} deactivated.");
        $this->showDeactivateModal = false;
        $this->selectedMemberId = null;
        $this->selectedMember = null;
    }

    public function confirmDelete(int $userId): void
    {
        $this->showViewModal = false;
        $this->selectedMemberId = $userId;
        $this->selectedMember = User::findOrFail($userId);
        $this->showDeleteModal = true;
    }

    public function executeDelete(): void
    {
        if ($this->selectedMemberId === null) {
            $this->showDeleteModal = false;

            return;
        }

        $user = User::findOrFail($this->selectedMemberId);
        app(AuditLogger::class)->log('member.deleted', $user, ['name' => $user->name]);
        $user->delete();
        Session::flash('success', 'Member deleted.');
        $this->showDeleteModal = false;
        $this->selectedMemberId = null;
        $this->selectedMember = null;
    }

    public function confirmNotify(int $userId): void
    {
        $this->showViewModal = false;
        $this->selectedMemberId = $userId;
        $this->selectedMember = User::findOrFail($userId);
        $this->showNotifyModal = true;
    }

    public function executeNotify(): void
    {
        if ($this->selectedMemberId === null) {
            $this->showNotifyModal = false;

            return;
        }

        $user = User::findOrFail($this->selectedMemberId);
        app(AuditLogger::class)->log('member.expiry_notified', $user, ['email' => $user->email]);
        Session::flash('success', "Expiry notification sent to {$user->name}.");
        $this->showNotifyModal = false;
        $this->selectedMemberId = null;
        $this->selectedMember = null;
    }

    public function confirmNotifyExpiring(): void
    {
        $this->showNotifyExpiringModal = true;
    }

    public function executeNotifyExpiring(): void
    {
        $expiringMembers = User::where('role', 'member')
            ->where('status', 'active')
            ->whereHas('activeMembership', function ($q) {
                $q->whereBetween('expires_at', [now()->startOfDay(), now()->addDays(7)->endOfDay()]);
            })
            ->get();

        if ($expiringMembers->isEmpty()) {
            Session::flash('success', 'No members are expiring within 7 days.');
            $this->showNotifyExpiringModal = false;

            return;
        }

        $expiringMembers->each(function (User $user): void {
            app(AuditLogger::class)->log('member.expiry_notified', $user, ['email' => $user->email, 'bulk' => true]);
        });

        Session::flash('success', "Expiry notifications queued for {$expiringMembers->count()} members.");
        $this->showNotifyExpiringModal = false;
    }

    public function resetPassword(int $userId): void
    {
        $user = User::findOrFail($userId);
        $user->must_change_password = true;
        $user->save();

        app(AuditLogger::class)->log('member.password_reset', $user, []);
        Session::flash('success', "Password reset flag set for {$user->name}.");
    }

    public function recordCashPayment(): void
    {
        $data = $this->validate([
            'walkInPlanId' => 'required|exists:membership_plans,id',
            'witnessedConsent' => 'required|accepted',
        ]);

        if ($this->selectedMemberId === null) {
            Session::flash('error', 'No member selected.');

            return;
        }

        $user = User::findOrFail($this->selectedMemberId);
        $plan = MembershipPlan::findOrFail($data['walkInPlanId']);

        $membership = Membership::create([
            'user_id' => $user->id,
            'plan_id' => $plan->id,
            'starts_at' => now()->toDateString(),
            'expires_at' => now()->addDays($plan->duration_days)->toDateString(),
            'status' => 'active',
            'payment_ref' => 'cash-'.now()->format('YmdHis'),
        ]);

        // Ensure user is marked as active if they were inactive
        if ($user->status !== 'active') {
            $user->status = 'active';
            $user->save();
        }

        app(AuditLogger::class)->log('membership.cash_payment', $membership, [
            'plan' => $plan->name,
            'expires_at' => $membership->expires_at?->format('Y-m-d'),
            'witnessed_by' => auth()->id(),
        ]);

        // Record legal agreement consent
        $documentKeys = [
            'legal.terms_and_conditions',
            'legal.membership_contract',
            'legal.liability_waiver',
            'legal.privacy_policy',
        ];

        foreach ($documentKeys as $key) {
            MemberConsent::create([
                'user_id' => $user->id,
                'document_key' => $key,
                'version' => (int) SiteContent::get($key.'_version', '1'),
                'ip_address' => 'staff-recorded',
                'method' => 'staff_witnessed',
                'accepted_at' => now(),
            ]);
        }

        Session::flash('success', "Cash payment recorded for {$plan->name}.");
        $this->showPaymentModal = false;
        $this->reset(['walkInPlanId', 'witnessedConsent']);
    }

    public function render(): View
    {
        $query = User::where('role', 'member')
            ->with(['activeMembership.plan'])
            ->when($this->search, fn ($q) => $q->where(function ($q2): void {
                $term = '%'.strtolower($this->search).'%';
                $q2->whereRaw('LOWER(name) LIKE ?', [$term])
                    ->orWhereRaw('LOWER(email) LIKE ?', [$term]);
            }))
            ->when($this->statusFilter, fn ($q) => $q->where('status', $this->statusFilter))
            ->latest();

        return view('livewire.admin.member-index', [
            'members' => $query->paginate(15),
            'plans' => MembershipPlan::active()->orderByDesc('is_daily')->orderBy('price')->get(),
        ]);
    }
}
