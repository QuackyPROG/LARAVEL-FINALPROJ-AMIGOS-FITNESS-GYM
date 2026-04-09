<?php

namespace App\Livewire\Admin;

use App\Models\MembershipPlan;
use App\Services\AuditLogger;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Rule;
use Livewire\Component;

class PlanIndex extends Component
{
    public bool $showForm = false;

    public ?int $editingId = null;

    #[Rule('required|string|max:100')]
    public string $name = '';

    #[Rule('required|integer|min:1')]
    public int $durationDays = 30;

    #[Rule('required|numeric|min:0')]
    public string $price = '';

    #[Rule('required|string')]
    public string $benefitsRaw = '';

    public bool $isActive = true;

    public function openCreate(): void
    {
        $this->resetForm();
        $this->showForm = true;
    }

    public function openEdit(int $id): void
    {
        $plan = MembershipPlan::findOrFail($id);
        $this->editingId = $id;
        $this->name = $plan->name;
        $this->durationDays = $plan->duration_days;
        $this->price = (string) $plan->price;
        $this->benefitsRaw = implode("\n", $plan->benefits ?? []);
        $this->isActive = $plan->is_active;
        $this->showForm = true;
    }

    public function save(): void
    {
        $this->validate();

        $benefits = array_filter(array_map('trim', explode("\n", $this->benefitsRaw)));

        $data = [
            'name' => $this->name,
            'duration_days' => $this->durationDays,
            'price' => $this->price,
            'benefits' => array_values($benefits),
            'is_active' => $this->isActive,
        ];

        if ($this->editingId) {
            $plan = MembershipPlan::findOrFail($this->editingId);
            $plan->update($data);
            app(AuditLogger::class)->log('plan.updated', $plan, $data);
            session()->flash('success', 'Plan updated.');
        } else {
            $plan = MembershipPlan::create($data);
            app(AuditLogger::class)->log('plan.created', $plan, $data);
            session()->flash('success', 'Plan created.');
        }

        $this->resetForm();
    }

    public function toggleActive(int $id): void
    {
        $plan = MembershipPlan::findOrFail($id);
        $plan->is_active = ! $plan->is_active;
        $plan->save();

        app(AuditLogger::class)->log('plan.toggled', $plan, ['is_active' => $plan->is_active]);
    }

    public function cancel(): void
    {
        $this->resetForm();
    }

    private function resetForm(): void
    {
        $this->showForm = false;
        $this->editingId = null;
        $this->name = '';
        $this->durationDays = 30;
        $this->price = '';
        $this->benefitsRaw = '';
        $this->isActive = true;
    }

    public function render(): View
    {
        return view('livewire.admin.plan-index', [
            'plans' => MembershipPlan::orderBy('price')->get(),
        ]);
    }
}
