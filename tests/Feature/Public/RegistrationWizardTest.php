<?php

use App\Livewire\Public\RegistrationForm;
use App\Models\MembershipPlan;
use App\Models\SiteContent;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

uses(RefreshDatabase::class);

function wizardPlan(): MembershipPlan
{
    return MembershipPlan::create([
        'name' => 'Monthly',
        'duration_days' => 30,
        'price' => 999.00,
        'benefits' => ['Full access'],
        'is_active' => true,
    ]);
}

it('renders step 1 on initial load', function (): void {
    wizardPlan();

    Livewire::test(RegistrationForm::class)
        ->assertSet('currentStep', 1);
});

it('advances to step 2 after valid personal details', function (): void {
    wizardPlan();

    Livewire::test(RegistrationForm::class)
        ->set('name', 'Juan dela Cruz')
        ->set('email', 'juan@example.com')
        ->set('phone', '+63 917 000 0000')
        ->set('dob', '1990-01-01')
        ->call('nextStep')
        ->assertHasNoErrors()
        ->assertSet('currentStep', 2);
});

it('blocks step 3 advancement when not all consents checked', function (): void {
    SiteContent::updateOrCreate(['key' => 'legal.terms_and_conditions'], ['value' => '<p>T&C</p>', 'type' => 'html']);
    SiteContent::updateOrCreate(['key' => 'legal.terms_and_conditions_version'], ['value' => '1', 'type' => 'text']);
    SiteContent::updateOrCreate(['key' => 'legal.membership_contract'], ['value' => '<p>Contract</p>', 'type' => 'html']);
    SiteContent::updateOrCreate(['key' => 'legal.membership_contract_version'], ['value' => '1', 'type' => 'text']);
    SiteContent::updateOrCreate(['key' => 'legal.liability_waiver'], ['value' => '<p>Waiver</p>', 'type' => 'html']);
    SiteContent::updateOrCreate(['key' => 'legal.liability_waiver_version'], ['value' => '1', 'type' => 'text']);
    SiteContent::updateOrCreate(['key' => 'legal.privacy_policy'], ['value' => '<p>Privacy</p>', 'type' => 'html']);
    SiteContent::updateOrCreate(['key' => 'legal.privacy_policy_version'], ['value' => '1', 'type' => 'text']);

    $plan = wizardPlan();

    Livewire::test(RegistrationForm::class)
        ->set('currentStep', 3)
        ->set('planId', $plan->id)
        ->set('consentTerms', true)
        ->set('consentContract', false)
        ->set('consentWaiver', false)
        ->set('consentPrivacy', false)
        ->call('nextStep')
        ->assertHasErrors(['consentContract', 'consentWaiver', 'consentPrivacy'])
        ->assertSet('currentStep', 3);
});

it('back button from step 2 returns to step 1 with data preserved', function (): void {
    $plan = wizardPlan();

    Livewire::test(RegistrationForm::class)
        ->set('name', 'Juan dela Cruz')
        ->set('email', 'juan@example.com')
        ->set('phone', '+63 917 000 0000')
        ->set('dob', '1990-01-01')
        ->set('planId', $plan->id)
        ->set('currentStep', 2)
        ->call('prevStep')
        ->assertSet('currentStep', 1)
        ->assertSet('name', 'Juan dela Cruz')
        ->assertSet('email', 'juan@example.com');
});
