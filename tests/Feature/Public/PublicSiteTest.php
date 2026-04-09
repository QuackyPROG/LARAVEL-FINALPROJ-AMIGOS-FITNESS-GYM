<?php

use App\Models\Coach;
use App\Models\MembershipPlan;
use App\Models\SiteContent;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('returns 200 for the homepage', function (): void {
    $response = $this->get('/');

    $response->assertStatus(200);
});

it('shows active plan names on the homepage', function (): void {
    MembershipPlan::create([
        'name' => 'Monthly Test',
        'duration_days' => 30,
        'price' => 999.00,
        'benefits' => ['Gym access'],
        'is_active' => true,
    ]);

    $response = $this->get('/');

    $response->assertSee('Monthly Test');
});

it('hides inactive plans on the homepage', function (): void {
    MembershipPlan::create([
        'name' => 'Hidden Plan',
        'duration_days' => 30,
        'price' => 500.00,
        'benefits' => ['Gym access'],
        'is_active' => false,
    ]);

    $response = $this->get('/');

    $response->assertDontSee('Hidden Plan');
});

it('shows seeded coach names on the homepage', function (): void {
    Coach::create([
        'name' => 'Test Coach',
        'bio' => 'A great coach.',
        'specializations' => ['Strength'],
        'photo' => null,
    ]);

    $response = $this->get('/');

    $response->assertSee('Test Coach');
});

it('shows hero content from site_contents on the homepage', function (): void {
    SiteContent::updateOrCreate(
        ['key' => 'hero_title'],
        ['value' => 'Unique Hero Headline', 'type' => 'text']
    );

    $response = $this->get('/');

    $response->assertSee('Unique Hero Headline');
});
