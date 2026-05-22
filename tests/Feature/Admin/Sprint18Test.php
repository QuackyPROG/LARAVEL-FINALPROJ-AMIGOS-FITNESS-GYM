<?php

declare(strict_types=1);

use App\Livewire\Admin\MemberIndex;
use App\Models\MembershipPlan;
use App\Models\User;
use App\Services\PlanAdvisorService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Livewire\Livewire;

uses(RefreshDatabase::class);

it('PlanAdvisorService returns null when api key is empty', function (): void {
    config(['services.anthropic.key' => '']);

    $plan = MembershipPlan::create(['name' => 'Daily Pass', 'duration_days' => 1, 'price' => 150, 'benefits' => [], 'is_active' => true, 'is_daily' => true]);

    Http::fake(['*' => Http::response(['error' => 'unauthorized'], 401)]);

    $result = app(PlanAdvisorService::class)->recommend('test@example.com');

    expect($result)->toBeNull();
});

it('PlanAdvisorService returns plan_id and rationale on success', function (): void {
    $plan = MembershipPlan::create(['name' => 'Daily Pass', 'duration_days' => 1, 'price' => 150, 'benefits' => [], 'is_active' => true, 'is_daily' => true]);

    Http::fake([
        'api.anthropic.com/*' => Http::response([
            'content' => [
                ['type' => 'text', 'text' => '{"plan_id": '.$plan->id.', "rationale": "Daily Pass recommended — first visit."}'],
            ],
        ], 200),
    ]);

    $result = app(PlanAdvisorService::class)->recommend('newmember@example.com');

    expect($result)->toBeArray()
        ->and($result['plan_id'])->toBe($plan->id)
        ->and($result['rationale'])->toBe('Daily Pass recommended — first visit.');
});

it('PlanAdvisorService returns null on invalid JSON response', function (): void {
    $plan = MembershipPlan::create(['name' => 'Monthly', 'duration_days' => 30, 'price' => 800, 'benefits' => [], 'is_active' => true, 'is_daily' => false]);

    Http::fake([
        'api.anthropic.com/*' => Http::response([
            'content' => [
                ['type' => 'text', 'text' => 'not valid json at all'],
            ],
        ], 200),
    ]);

    $result = app(PlanAdvisorService::class)->recommend('test@example.com');

    expect($result)->toBeNull();
});

it('advisor properties exist on MemberIndex component', function (): void {
    $admin = User::factory()->admin()->create(['must_change_password' => false]);

    Livewire::actingAs($admin)
        ->test(MemberIndex::class)
        ->assertSet('advisorRationale', null)
        ->assertSet('advisorPlanId', null)
        ->assertSet('advisorLoading', false);
});

it('openAddModal resets advisor state', function (): void {
    $admin = User::factory()->admin()->create(['must_change_password' => false]);

    Livewire::actingAs($admin)
        ->test(MemberIndex::class)
        ->set('advisorRationale', 'Some rationale')
        ->set('advisorPlanId', 99)
        ->call('openAddModal')
        ->assertSet('advisorRationale', null)
        ->assertSet('advisorPlanId', null);
});
