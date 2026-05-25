<?php

namespace App\Livewire\Admin;

use App\Models\MemberConsent;
use App\Models\MemberProfile;
use App\Models\Membership;
use App\Models\MembershipPlan;
use App\Models\SiteContent;
use App\Models\User;
use App\Services\AuditLogger;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\URL;
use Livewire\Attributes\Rule;
use Livewire\Component;

class MemberDetail extends Component
{
    public User $member;

    public ?string $activeAction = null;

    public bool $witnessedConsent = false;

    #[Rule('required|exists:membership_plans,id')]
    public ?int $walkInPlanId = null;

    #[Rule('required|integer|min:1|max:365')]
    public int $extendDays = 30;

    public string $editIdType = '';

    public string $editIdNumber = '';

    public function mount(User $member): void
    {
        $this->member = $member;
        $this->editIdType = $member->profile?->id_type ?? '';
        $this->editIdNumber = $member->profile?->id_number ?? '';
    }

    public function toggleAction(string $action): void
    {
        $this->activeAction = $this->activeAction === $action ? null : $action;
    }

    public function extendExpiry(): void
    {
        $this->validate(['extendDays' => 'required|integer|min:1|max:365']);

        $membership = $this->member->activeMembership;

        if (! $membership) {
            session()->flash('error', 'No active membership to extend.');

            return;
        }

        $old = $membership->expires_at->toDateString();
        $membership->expires_at = $membership->expires_at->addDays($this->extendDays);
        $membership->save();

        app(AuditLogger::class)->log('membership.extended', $membership, [
            'from' => $old,
            'to' => $membership->expires_at->toDateString(),
            'days' => $this->extendDays,
        ]);

        $this->activeAction = null;
        session()->flash('success', "Expiry extended by {$this->extendDays} days.");
        $this->member->refresh();
    }

    public function recordCashPayment(): void
    {
        $this->validate([
            'walkInPlanId' => 'required|exists:membership_plans,id',
            'witnessedConsent' => 'accepted',
        ]);

        $plan = MembershipPlan::findOrFail($this->walkInPlanId);

        $membership = Membership::create([
            'user_id' => $this->member->id,
            'plan_id' => $plan->id,
            'starts_at' => now()->toDateString(),
            'expires_at' => now()->addDays($plan->duration_days)->toDateString(),
            'status' => 'active',
            'payment_ref' => 'cash-'.now()->format('YmdHis'),
        ]);

        $this->member->status = 'active';
        $this->member->save();

        app(AuditLogger::class)->log('membership.cash_payment', $membership, [
            'plan' => $plan->name,
            'expires_at' => $membership->expires_at->toDateString(),
            'witnessed_by' => auth()->id(),
        ]);

        $documentKeys = [
            'legal.terms_and_conditions',
            'legal.membership_contract',
            'legal.liability_waiver',
            'legal.privacy_policy',
        ];

        foreach ($documentKeys as $key) {
            MemberConsent::create([
                'user_id' => $this->member->id,
                'document_key' => $key,
                'version' => (int) SiteContent::get($key.'_version', '1'),
                'ip_address' => 'staff-recorded',
                'method' => 'staff_witnessed',
                'accepted_at' => now(),
            ]);
        }

        $this->witnessedConsent = false;
        $this->activeAction = null;
        session()->flash('success', "Cash membership recorded for {$plan->name}.");
        $this->member->refresh();
    }

    public function deactivate(): void
    {
        $this->member->status = 'inactive';
        $this->member->save();

        app(AuditLogger::class)->log('member.deactivated', $this->member, ['status' => 'inactive']);
        $this->activeAction = null;
        session()->flash('success', 'Member deactivated.');
        $this->member->refresh();
    }

    public function saveIdFields(): void
    {
        $idNumberRules = ['required', 'string', 'max:50'];
        if ($this->editIdType) {
            $idNumberRules[] = MemberProfile::validationRuleForType($this->editIdType);
        }

        $this->validate([
            'editIdType' => 'required|in:'.implode(',', MemberProfile::ID_TYPES),
            'editIdNumber' => $idNumberRules,
        ]);

        $profile = $this->member->profile ?? MemberProfile::create(['user_id' => $this->member->id]);

        $profile->update([
            'id_type' => $this->editIdType,
            'id_number' => $this->editIdNumber,
        ]);

        app(AuditLogger::class)->log('member.id_updated', $this->member, [
            'id_type' => $this->editIdType,
        ]);

        $this->activeAction = null;
        session()->flash('success', 'ID information updated.');
        $this->member->refresh();
    }

    public function render(): View
    {
        $this->member->load(['memberships.plan', 'profile']);
        $plans = MembershipPlan::active()->orderBy('price')->get();

        $govIdUrl = $this->member->profile?->government_id_path
            ? URL::temporarySignedRoute('admin.members.gov-id', now()->addMinutes(30), ['member' => $this->member])
            : null;

        $consents = $this->member->consents()->with('snapshot')->latest()->get();

        return view('livewire.admin.member-detail', [
            'member' => $this->member,
            'plans' => $plans,
            'memberships' => $this->member->memberships()->with('plan')->latest()->get(),
            'govIdUrl' => $govIdUrl,
            'consents' => $consents,
        ]);
    }
}
