<?php

namespace App\Livewire\Admin;

use App\Models\User;
use App\Models\MembershipPlan;
use App\Models\Membership;
use App\Services\AuditLogger;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithPagination;

class MemberIndex extends Component
{
    use WithPagination;

    public string $search = '';
    public string $statusFilter = '';

    public bool $showAddModal = false;
    public bool $showViewModal = false;
    public bool $showDeactivateModal = false;
    public bool $showDeleteModal = false;
    public bool $showExtendModal = false;

    public ?int $selectedMemberId = null;
    public ?User $selectedMember = null;

    public string $addName = '';
    public string $addEmail = '';
    public ?int $addPlanId = null;
    public ?string $tempPasswordResult = null;

    public ?int $extendPlanId = null;

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
        $data = $this->validate([
            'extendPlanId' => 'required|exists:membership_plans,id',
        ]);

        $user = User::findOrFail($this->selectedMemberId);
        $plan = MembershipPlan::findOrFail($data['extendPlanId']);

        // Check if there is an active membership and extend from its expiration,
        // otherwise start from today
        $activeMembership = $user->activeMembership;
        $startDate = $activeMembership && $activeMembership->expires_at->isFuture() 
            ? $activeMembership->expires_at 
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
            'added_days' => $plan->duration_days
        ]);

        session()->flash('success', "Subscription extended for {$user->name}.");
        $this->showExtendModal = false;
    }

    public function openAddModal(): void
    {
        $this->reset(['addName', 'addEmail', 'addPlanId', 'tempPasswordResult']);
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
        session()->flash('success', "Walk-in member {$user->name} created. Temp password: {$tempPassword}");
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
        if ($this->selectedMemberId) {
            $user = User::findOrFail($this->selectedMemberId);
            $user->status = 'inactive';
            $user->save();

            app(AuditLogger::class)->log('member.deactivated', $user, ['status' => 'inactive']);
            session()->flash('success', "Member {$user->name} deactivated.");
        }
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
        if ($this->selectedMemberId) {
            $user = User::findOrFail($this->selectedMemberId);
            app(AuditLogger::class)->log('member.deleted', $user, ['name' => $user->name]);
            $user->delete();
            session()->flash('success', 'Member deleted.');
        }
        $this->showDeleteModal = false;
        $this->selectedMemberId = null;
        $this->selectedMember = null;
    }

    public function resetPassword(int $userId): void
    {
        $user = User::findOrFail($userId);
        $user->must_change_password = true;
        $user->save();

        app(AuditLogger::class)->log('member.password_reset', $user, []);
        session()->flash('success', "Password reset flag set for {$user->name}.");
    }

    public function render(): View
    {
        $query = User::where('role', 'member')
            ->with(['activeMembership.plan'])
            ->when($this->search, fn ($q) => $q->where(function ($q2): void {
                $q2->where('name', 'ilike', '%'.$this->search.'%')
                    ->orWhere('email', 'ilike', '%'.$this->search.'%');
            }))
            ->when($this->statusFilter, fn ($q) => $q->where('status', $this->statusFilter))
            ->latest();

        return view('livewire.admin.member-index', [
            'members' => $query->paginate(15),
            'plans' => MembershipPlan::active()->orderBy('price')->get(),
        ]);
    }
}
