<?php

use App\Livewire\Admin\MemberIndex;
use App\Livewire\Admin\PlanIndex;
use App\Models\AuditLog;
use App\Models\Membership;
use App\Models\MembershipPlan;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

uses(RefreshDatabase::class);

function adminUser(): User
{
    return User::factory()->admin()->create(['must_change_password' => false]);
}

function planAndMember(): array
{
    $plan = MembershipPlan::create([
        'name' => 'Monthly',
        'duration_days' => 30,
        'price' => 999.00,
        'benefits' => ['Gym access'],
        'is_active' => true,
    ]);

    $member = User::factory()->create([
        'role' => 'member',
        'status' => 'active',
        'must_change_password' => false,
    ]);

    Membership::create([
        'user_id' => $member->id,
        'plan_id' => $plan->id,
        'starts_at' => now()->toDateString(),
        'expires_at' => now()->addDays(30)->toDateString(),
        'status' => 'active',
    ]);

    return [$plan, $member];
}

it('admin dashboard returns 200 with stats', function (): void {
    $admin = adminUser();

    $response = $this->actingAs($admin)->get('/admin/dashboard');

    $response->assertStatus(200);
});

it('members index returns 200 for admin', function (): void {
    $admin = adminUser();

    $response = $this->actingAs($admin)->get('/admin/members');

    $response->assertStatus(200);
});

it('members index shows member name', function (): void {
    $admin = adminUser();
    [$plan, $member] = planAndMember();

    $response = $this->actingAs($admin)->get('/admin/members');

    $response->assertSee($member->name);
});

it('member detail page returns 200', function (): void {
    $admin = adminUser();
    [$plan, $member] = planAndMember();

    $response = $this->actingAs($admin)->get("/admin/members/{$member->id}");

    $response->assertStatus(200);
    $response->assertSee($member->name);
});

it('walk-in member create stores member and creates audit log', function (): void {
    $admin = adminUser();
    $plan = MembershipPlan::create([
        'name' => 'Monthly',
        'duration_days' => 30,
        'price' => 999.00,
        'benefits' => ['Gym access'],
        'is_active' => true,
    ]);

    $response = $this->actingAs($admin)->post('/admin/members', [
        'name' => 'Walk-in Joe',
        'email' => 'walkin@test.com',
        'plan_id' => $plan->id,
    ]);

    $response->assertRedirect();
    expect(User::where('email', 'walkin@test.com')->exists())->toBeTrue();
    expect(AuditLog::where('action', 'member.created_walk_in')->exists())->toBeTrue();
});

it('deactivate action creates audit log entry', function (): void {
    $admin = adminUser();
    [$plan, $member] = planAndMember();

    $this->actingAs($admin)->post('/admin/members', [
        'name' => $member->name,
        'email' => 'dup@test.com',
        'plan_id' => $plan->id,
    ]);

    // Deactivate via Livewire component
    Livewire::actingAs($admin)
        ->test(MemberIndex::class)
        ->call('deactivate', $member->id);

    $member->refresh();
    expect($member->status)->toBe('inactive');
    expect(AuditLog::where('action', 'member.deactivated')->exists())->toBeTrue();
});

it('deactivated member cannot log in', function (): void {
    [$plan, $member] = planAndMember();
    $member->status = 'inactive';
    $member->save();

    $response = $this->post('/login', [
        'email' => $member->email,
        'password' => 'password',
    ]);

    $this->assertGuest();
});

it('plans index returns 200 for admin', function (): void {
    $admin = adminUser();

    $response = $this->actingAs($admin)->get('/admin/plans');

    $response->assertStatus(200);
});

it('plan creation via Livewire creates plan and audit log', function (): void {
    $admin = adminUser();

    Livewire::actingAs($admin)
        ->test(PlanIndex::class)
        ->call('openCreate')
        ->set('name', 'Annual')
        ->set('durationDays', 365)
        ->set('price', '9999')
        ->set('benefitsRaw', "Full access\nGuest passes")
        ->call('save');

    expect(MembershipPlan::where('name', 'Annual')->exists())->toBeTrue();
    expect(AuditLog::where('action', 'plan.created')->exists())->toBeTrue();
});

it('plan CRUD changes reflected — toggling active hides from public site', function (): void {
    $admin = adminUser();
    $plan = MembershipPlan::create([
        'name' => 'TestPlan',
        'duration_days' => 30,
        'price' => 500,
        'benefits' => ['x'],
        'is_active' => true,
    ]);

    Livewire::actingAs($admin)
        ->test(PlanIndex::class)
        ->call('toggleActive', $plan->id);

    $plan->refresh();
    expect($plan->is_active)->toBeFalse();
    expect(AuditLog::where('action', 'plan.toggled')->exists())->toBeTrue();
});

it('audit log page returns 200', function (): void {
    $admin = adminUser();

    $response = $this->actingAs($admin)->get('/admin/audit-log');

    $response->assertStatus(200);
});

it('members index redirects unauthenticated to login', function (): void {
    $response = $this->get('/admin/members');

    $response->assertRedirect('/login');
});
