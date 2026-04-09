<?php

use App\Livewire\Admin\MemberDetail;
use App\Livewire\Public\RegistrationForm;
use App\Models\MemberConsent;
use App\Models\MembershipPlan;
use App\Models\SiteContent;
use App\Models\User;
use App\Services\PayMongoService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;

uses(RefreshDatabase::class);

function legalPlan(): MembershipPlan
{
    return MembershipPlan::create([
        'name' => 'Monthly',
        'duration_days' => 30,
        'price' => 999.00,
        'benefits' => ['Full access'],
        'is_active' => true,
    ]);
}

function seedLegalContent(): void
{
    $docs = [
        'legal.terms_and_conditions' => '<p>Terms content.</p>',
        'legal.membership_contract' => '<p>Contract for {{member_name}} on {{plan_name}}.</p>',
        'legal.liability_waiver' => '<p>Waiver content.</p>',
        'legal.privacy_policy' => '<p>Privacy content.</p>',
    ];
    foreach ($docs as $key => $body) {
        SiteContent::updateOrCreate(['key' => $key], ['value' => $body, 'type' => 'html']);
        SiteContent::updateOrCreate(['key' => $key.'_version'], ['value' => '1', 'type' => 'text']);
    }
}

it('records 4 consent rows on registration submit', function (): void {
    Queue::fake();
    Storage::fake('local');
    seedLegalContent();

    $plan = legalPlan();

    $this->mock(PayMongoService::class, function ($mock): void {
        $mock->shouldReceive('createCheckoutSession')->once()->andReturn([
            'id' => 'cs_test_consent',
            'attributes' => ['checkout_url' => 'https://checkout.paymongo.com/test'],
        ]);
    });

    $file = UploadedFile::fake()->create('id.jpg', 100, 'image/jpeg');

    Livewire::test(RegistrationForm::class)
        ->set('name', 'Consent User')
        ->set('email', 'consent@example.com')
        ->set('phone', '+63 917 000 0001')
        ->set('dob', '1990-01-01')
        ->set('planId', $plan->id)
        ->set('governmentId', $file)
        ->set('consentTerms', true)
        ->set('consentContract', true)
        ->set('consentWaiver', true)
        ->set('consentPrivacy', true)
        ->call('submit');

    $user = User::where('email', 'consent@example.com')->first();
    expect($user)->not->toBeNull();

    $consents = MemberConsent::where('user_id', $user->id)->get();
    expect($consents)->toHaveCount(4);
    expect($consents->pluck('document_key')->toArray())->toContain('legal.terms_and_conditions');
    expect($consents->pluck('document_key')->toArray())->toContain('legal.membership_contract');
    expect($consents->pluck('document_key')->toArray())->toContain('legal.liability_waiver');
    expect($consents->pluck('document_key')->toArray())->toContain('legal.privacy_policy');
});

it('snapshot body contains rendered member name not placeholder', function (): void {
    Queue::fake();
    Storage::fake('local');
    seedLegalContent();

    $plan = legalPlan();

    $this->mock(PayMongoService::class, function ($mock): void {
        $mock->shouldReceive('createCheckoutSession')->once()->andReturn([
            'id' => 'cs_test_snap',
            'attributes' => ['checkout_url' => 'https://checkout.paymongo.com/test'],
        ]);
    });

    $file = UploadedFile::fake()->create('id.jpg', 100, 'image/jpeg');

    Livewire::test(RegistrationForm::class)
        ->set('name', 'Juan dela Cruz')
        ->set('email', 'juan.snap@example.com')
        ->set('phone', '+63 917 000 0002')
        ->set('dob', '1990-01-01')
        ->set('planId', $plan->id)
        ->set('governmentId', $file)
        ->set('consentTerms', true)
        ->set('consentContract', true)
        ->set('consentWaiver', true)
        ->set('consentPrivacy', true)
        ->call('submit');

    $user = User::where('email', 'juan.snap@example.com')->first();
    $consent = MemberConsent::where('user_id', $user->id)
        ->where('document_key', 'legal.membership_contract')
        ->first();

    expect($consent)->not->toBeNull();
    expect($consent->snapshot)->not->toBeNull();
    expect($consent->snapshot->body)->toContain('Juan dela Cruz');
    expect($consent->snapshot->body)->not->toContain('{{member_name}}');
});

it('blocks nextStep advancement from step 3 when consents unchecked', function (): void {
    $plan = legalPlan();
    seedLegalContent();

    Livewire::test(RegistrationForm::class)
        ->set('currentStep', 3)
        ->set('planId', $plan->id)
        ->set('consentTerms', false)
        ->set('consentContract', false)
        ->set('consentWaiver', false)
        ->set('consentPrivacy', false)
        ->call('nextStep')
        ->assertHasErrors(['consentTerms', 'consentContract', 'consentWaiver', 'consentPrivacy'])
        ->assertSet('currentStep', 3);
});

it('records staff_witnessed consents on walk-in cash payment', function (): void {
    seedLegalContent();

    $admin = User::factory()->admin()->create();
    $plan = legalPlan();

    $member = User::factory()->create([
        'role' => 'member',
        'status' => 'pending',
        'must_change_password' => false,
    ]);

    Livewire::actingAs($admin)
        ->test(MemberDetail::class, ['member' => $member])
        ->set('showWalkInForm', true)
        ->set('walkInPlanId', $plan->id)
        ->set('witnessedConsent', true)
        ->call('recordCashPayment');

    $consents = MemberConsent::where('user_id', $member->id)->get();
    expect($consents)->toHaveCount(4);
    expect($consents->first()->method)->toBe('staff_witnessed');
    expect($consents->first()->ip_address)->toBe('staff-recorded');
});
