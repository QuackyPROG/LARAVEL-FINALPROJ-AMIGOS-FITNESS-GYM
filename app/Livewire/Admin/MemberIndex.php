<?php

namespace App\Livewire\Admin;

use App\Models\User;
use App\Services\AuditLogger;
use Illuminate\Contracts\View\View;
use Livewire\Component;
use Livewire\WithPagination;

class MemberIndex extends Component
{
    use WithPagination;

    public string $search = '';

    public string $statusFilter = '';

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingStatusFilter(): void
    {
        $this->resetPage();
    }

    public function deactivate(int $userId): void
    {
        $user = User::findOrFail($userId);
        $user->status = 'inactive';
        $user->save();

        app(AuditLogger::class)->log('member.deactivated', $user, ['status' => 'inactive']);
        session()->flash('success', "Member {$user->name} deactivated.");
    }

    public function resetPassword(int $userId): void
    {
        $user = User::findOrFail($userId);
        $user->must_change_password = true;
        $user->save();

        app(AuditLogger::class)->log('member.password_reset', $user, []);
        session()->flash('success', "Password reset flag set for {$user->name}.");
    }

    public function softDelete(int $userId): void
    {
        $user = User::findOrFail($userId);
        app(AuditLogger::class)->log('member.deleted', $user, ['name' => $user->name]);
        $user->delete();
        session()->flash('success', 'Member deleted.');
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
        ]);
    }
}
