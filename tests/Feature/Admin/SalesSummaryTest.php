<?php

declare(strict_types=1);

use App\Livewire\Admin\SalesSummary;
use App\Models\Membership;
use App\Models\MembershipPlan;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Livewire\Livewire;

uses(RefreshDatabase::class);

it('admin can access the sales summary page', function (): void {
    $admin = User::factory()->admin()->create(['must_change_password' => false]);

    $this->actingAs($admin)
        ->get(route('admin.sales-summary.index'))
        ->assertStatus(200);
});

it('non-admin gets 403 on sales summary page', function (): void {
    $member = User::factory()->create(['role' => 'member', 'must_change_password' => false]);

    $this->actingAs($member)
        ->get(route('admin.sales-summary.index'))
        ->assertStatus(403);
});

it('sales summary page shows Sales Summary heading', function (): void {
    $admin = User::factory()->admin()->create(['must_change_password' => false]);

    $this->actingAs($admin)
        ->get(route('admin.sales-summary.index'))
        ->assertSee('Sales Summary');
});

it('total sales is computed correctly from memberships in period', function (): void {
    $admin = User::factory()->admin()->create(['must_change_password' => false]);

    $plan = MembershipPlan::factory()->create(['price' => 1500]);

    $member1 = User::factory()->create(['role' => 'member', 'must_change_password' => false]);
    $member2 = User::factory()->create(['role' => 'member', 'must_change_password' => false]);

    // Two memberships this month
    Membership::factory()->create([
        'user_id' => $member1->id,
        'plan_id' => $plan->id,
        'starts_at' => Carbon::now()->startOfMonth()->addDay(),
    ]);

    Membership::factory()->create([
        'user_id' => $member2->id,
        'plan_id' => $plan->id,
        'starts_at' => Carbon::now()->startOfMonth()->addDays(2),
    ]);

    // One membership outside this month — should not be counted
    Membership::factory()->create([
        'user_id' => $member1->id,
        'plan_id' => $plan->id,
        'starts_at' => Carbon::now()->subMonths(2)->startOfMonth(),
    ]);

    Livewire::actingAs($admin)
        ->test(SalesSummary::class)
        ->assertSet('period', 'month')
        ->assertSee('₱3,000.00'); // 1500 + 1500
});

it('search filters members by name', function (): void {
    $admin = User::factory()->admin()->create(['must_change_password' => false]);

    $plan = MembershipPlan::factory()->create(['price' => 999]);

    $targetMember = User::factory()->create([
        'role' => 'member',
        'name' => 'Carlos Fernandez',
        'must_change_password' => false,
    ]);

    $otherMember = User::factory()->create([
        'role' => 'member',
        'name' => 'Maria Santos',
        'must_change_password' => false,
    ]);

    Membership::factory()->create([
        'user_id' => $targetMember->id,
        'plan_id' => $plan->id,
        'starts_at' => Carbon::now()->startOfMonth()->addDay(),
    ]);

    Membership::factory()->create([
        'user_id' => $otherMember->id,
        'plan_id' => $plan->id,
        'starts_at' => Carbon::now()->startOfMonth()->addDay(),
    ]);

    Livewire::actingAs($admin)
        ->test(SalesSummary::class)
        ->set('search', 'carlos')
        ->assertSee('Carlos Fernandez')
        ->assertDontSee('Maria Santos');
});
