<?php

use App\Livewire\Public\RegistrationForm;
use App\Models\MembershipPlan;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

uses(RefreshDatabase::class);

it('returns 200 for the registration page', function (): void {
    $response = $this->get('/register');

    $response->assertStatus(200);
});

it('shows active plans on the registration page step 2', function (): void {
    MembershipPlan::create([
        'name' => 'Active Register Plan',
        'duration_days' => 30,
        'price' => 999.00,
        'benefits' => ['Gym access'],
        'is_active' => true,
    ]);

    // Plans are shown on step 2 of the wizard — advance to it via Livewire
    Livewire::test(RegistrationForm::class)
        ->set('currentStep', 2)
        ->assertSee('Active Register Plan');
});

it('hides inactive plans on the registration page step 2', function (): void {
    MembershipPlan::create([
        'name' => 'Inactive Register Plan',
        'duration_days' => 30,
        'price' => 500.00,
        'benefits' => ['Gym access'],
        'is_active' => false,
    ]);

    Livewire::test(RegistrationForm::class)
        ->set('currentStep', 2)
        ->assertDontSee('Inactive Register Plan');
});
