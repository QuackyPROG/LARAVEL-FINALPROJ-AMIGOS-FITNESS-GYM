<?php

use App\Livewire\Public\RegistrationForm;
use App\Models\Membership;
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

function activePlan(): MembershipPlan
{
    return MembershipPlan::create([
        'name' => 'Monthly',
        'duration_days' => 30,
        'price' => 999.00,
        'benefits' => ['Gym access'],
        'is_active' => true,
    ]);
}

function seedLegalDocs(): void
{
    $keys = [
        'legal.terms_and_conditions',
        'legal.membership_contract',
        'legal.liability_waiver',
        'legal.privacy_policy',
    ];
    foreach ($keys as $key) {
        SiteContent::updateOrCreate(['key' => $key], ['value' => '<p>Document content.</p>', 'type' => 'html']);
        SiteContent::updateOrCreate(['key' => $key.'_version'], ['value' => '1', 'type' => 'text']);
    }
}

it('renders the registration form page', function (): void {
    $response = $this->get('/register');

    $response->assertStatus(200);
});

it('shows only active plans on wizard step 2', function (): void {
    $active = activePlan();
    MembershipPlan::create([
        'name' => 'Inactive Plan',
        'duration_days' => 90,
        'price' => 2499.00,
        'benefits' => [],
        'is_active' => false,
    ]);

    Livewire::test(RegistrationForm::class)
        ->set('currentStep', 2)
        ->assertSee($active->name)
        ->assertDontSee('Inactive Plan');
});

it('shows a validation error when name is missing on step 1', function (): void {
    activePlan();

    Livewire::test(RegistrationForm::class)
        ->set('email', 'juan@example.com')
        ->set('phone', '+63 900 000 0000')
        ->set('dob', '1990-01-01')
        ->call('nextStep')
        ->assertHasErrors(['name']);
});

it('shows a validation error for an invalid email on step 1', function (): void {
    activePlan();

    Livewire::test(RegistrationForm::class)
        ->set('name', 'Juan Dela Cruz')
        ->set('email', 'not-an-email')
        ->set('phone', '+63 900 000 0000')
        ->set('dob', '1990-01-01')
        ->call('nextStep')
        ->assertHasErrors(['email']);
});

it('shows a validation error for a duplicate email on step 1', function (): void {
    activePlan();
    User::factory()->create(['email' => 'taken@example.com']);

    Livewire::test(RegistrationForm::class)
        ->set('name', 'Juan Dela Cruz')
        ->set('email', 'taken@example.com')
        ->set('phone', '+63 900 000 0000')
        ->set('dob', '1990-01-01')
        ->call('nextStep')
        ->assertHasErrors(['email']);
});

it('creates a pending user and membership on valid submission', function (): void {
    Queue::fake();
    Storage::fake('local');
    seedLegalDocs();

    $plan = activePlan();

    $this->mock(PayMongoService::class, function ($mock): void {
        $mock->shouldReceive('createCheckoutSession')->once()->andReturn([
            'id' => 'cs_test_mock_001',
            'attributes' => ['checkout_url' => 'https://checkout.paymongo.com/test'],
        ]);
    });

    $file = UploadedFile::fake()->create('id_card.jpg', 100, 'image/jpeg');

    Livewire::test(RegistrationForm::class)
        ->set('name', 'Maria Santos')
        ->set('email', 'maria@example.com')
        ->set('phone', '+63 917 000 0000')
        ->set('dob', '1992-05-15')
        ->set('planId', $plan->id)
        ->set('governmentId', $file)
        ->set('consentTerms', true)
        ->set('consentContract', true)
        ->set('consentWaiver', true)
        ->set('consentPrivacy', true)
        ->call('submit');

    expect(User::where('email', 'maria@example.com')->exists())->toBeTrue();
    expect(Membership::where('status', 'pending_payment')->count())->toBe(1);
});

it('stores the government ID to private (non-public) disk', function (): void {
    Queue::fake();
    Storage::fake('local');
    Storage::fake('public');
    seedLegalDocs();

    $plan = activePlan();

    $this->mock(PayMongoService::class, function ($mock): void {
        $mock->shouldReceive('createCheckoutSession')->once()->andReturn([
            'id' => 'cs_test_mock_002',
            'attributes' => ['checkout_url' => 'https://checkout.paymongo.com/test'],
        ]);
    });

    $file = UploadedFile::fake()->create('government_id.jpg', 100, 'image/jpeg');

    Livewire::test(RegistrationForm::class)
        ->set('name', 'Pedro Reyes')
        ->set('email', 'pedro@example.com')
        ->set('phone', '+63 917 111 1111')
        ->set('dob', '1988-03-20')
        ->set('planId', $plan->id)
        ->set('governmentId', $file)
        ->set('consentTerms', true)
        ->set('consentContract', true)
        ->set('consentWaiver', true)
        ->set('consentPrivacy', true)
        ->call('submit');

    $profile = User::where('email', 'pedro@example.com')->first()?->profile;
    expect($profile?->government_id_path)->not->toBeNull();

    $path = $profile->government_id_path;
    expect(str_starts_with($path, 'gov-ids/'))->toBeTrue();

    Storage::disk('public')->assertMissing($path);
});

it('redirects to PayMongo checkout URL on successful submission', function (): void {
    Queue::fake();
    Storage::fake('local');
    seedLegalDocs();

    $plan = activePlan();

    $this->mock(PayMongoService::class, function ($mock): void {
        $mock->shouldReceive('createCheckoutSession')->once()->andReturn([
            'id' => 'cs_test_mock_003',
            'attributes' => ['checkout_url' => 'https://checkout.paymongo.com/test_redirect'],
        ]);
    });

    $file = UploadedFile::fake()->create('id.jpg', 100, 'image/jpeg');

    $component = Livewire::test(RegistrationForm::class)
        ->set('name', 'Ana Cruz')
        ->set('email', 'ana@example.com')
        ->set('phone', '+63 917 222 2222')
        ->set('dob', '1995-08-10')
        ->set('planId', $plan->id)
        ->set('governmentId', $file)
        ->set('consentTerms', true)
        ->set('consentContract', true)
        ->set('consentWaiver', true)
        ->set('consentPrivacy', true)
        ->call('submit');

    $component->assertRedirect('https://checkout.paymongo.com/test_redirect');
});
