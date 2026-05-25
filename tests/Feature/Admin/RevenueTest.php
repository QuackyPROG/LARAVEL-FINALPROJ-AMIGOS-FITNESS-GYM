<?php

declare(strict_types=1);

use App\Livewire\Admin\RevenueIndex;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Livewire\Livewire;

uses(RefreshDatabase::class);

it('admin can access revenue page', function (): void {
    $admin = User::factory()->admin()->create(['must_change_password' => false]);

    $this->actingAs($admin)
        ->get(route('admin.revenue.index'))
        ->assertStatus(200);
});

it('non-admin is forbidden from revenue page', function (): void {
    $member = User::factory()->create(['role' => 'member', 'must_change_password' => false]);

    $this->actingAs($member)
        ->get(route('admin.revenue.index'))
        ->assertStatus(403);
});

it('revenue page shows Total Revenue text', function (): void {
    $admin = User::factory()->admin()->create(['must_change_password' => false]);

    $this->actingAs($admin)
        ->get(route('admin.revenue.index'))
        ->assertSee('Total Revenue');
});

it('revenue component computes total revenue from paid payments', function (): void {
    $admin = User::factory()->admin()->create(['must_change_password' => false]);

    // Seed two paid payments in the current month (amount in centavos)
    Payment::create([
        'user_id' => $admin->id,
        'type' => 'membership',
        'status' => 'paid',
        'amount' => 50000, // ₱500.00
        'currency' => 'PHP',
        'paymongo_id' => 'cs_test_1',
        'payload' => [],
        'created_at' => Carbon::now()->startOfMonth()->addDay(),
    ]);

    Payment::create([
        'user_id' => $admin->id,
        'type' => 'membership',
        'status' => 'paid',
        'amount' => 80000, // ₱800.00
        'currency' => 'PHP',
        'paymongo_id' => 'cs_test_2',
        'payload' => [],
        'created_at' => Carbon::now()->startOfMonth()->addDays(2),
    ]);

    // A failed payment — should not be counted
    Payment::create([
        'user_id' => $admin->id,
        'type' => 'membership',
        'status' => 'failed',
        'amount' => 100000,
        'currency' => 'PHP',
        'paymongo_id' => 'cs_test_3',
        'payload' => [],
        'created_at' => Carbon::now()->startOfMonth()->addDays(3),
    ]);

    Livewire::actingAs($admin)
        ->test(RevenueIndex::class)
        ->assertSet('period', 'month')
        ->assertSee('₱1,300.00'); // 500 + 800
});
