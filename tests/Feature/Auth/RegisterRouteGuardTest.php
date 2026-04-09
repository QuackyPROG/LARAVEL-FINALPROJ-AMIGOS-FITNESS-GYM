<?php

use App\Livewire\Public\RegistrationForm;
use App\Models\MembershipPlan;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('shows the registration form to guests', function (): void {
    $this->get(route('register'))
        ->assertOk()
        ->assertSeeLivewire(RegistrationForm::class);
});

it('shows the registration form to guests with a plan pre-selected', function (): void {
    $plan = MembershipPlan::factory()->create(['is_active' => true]);

    $this->get(route('register', ['plan' => $plan->id]))
        ->assertOk()
        ->assertSeeLivewire(RegistrationForm::class);
});

it('redirects an authenticated member away from /register to the portal dashboard', function (): void {
    $member = User::factory()->create([
        'role' => 'member',
        'must_change_password' => false,
    ]);

    $this->actingAs($member)
        ->get(route('register'))
        ->assertRedirect(route('portal.dashboard'));
});

it('redirects an authenticated admin away from /register to the admin dashboard', function (): void {
    $admin = User::factory()->create([
        'role' => 'admin',
        'must_change_password' => false,
    ]);

    $this->actingAs($admin)
        ->get(route('register'))
        ->assertRedirect(route('admin.dashboard'));
});
