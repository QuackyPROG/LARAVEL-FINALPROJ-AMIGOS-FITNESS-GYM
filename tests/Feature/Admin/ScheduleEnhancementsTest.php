<?php

declare(strict_types=1);

use App\Livewire\Admin\ScheduleIndex;
use App\Models\Booking;
use App\Models\ClassSchedule;
use App\Models\Coach;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;
use Livewire\Livewire;

uses(RefreshDatabase::class);

/**
 * Helper: create a Coach row directly (no factory available).
 */
function makeCoach(): Coach
{
    return Coach::create(['name' => 'Test Coach', 'bio' => '', 'specializations' => []]);
}

it('bookings table has class_schedule_id column', function (): void {
    expect(Schema::hasColumn('bookings', 'class_schedule_id'))->toBeTrue();
});

it('enrolledBookings excludes cancelled bookings', function (): void {
    $coach = makeCoach();
    $schedule = ClassSchedule::create([
        'name' => 'Morning Yoga',
        'coach_id' => $coach->id,
        'day_of_week' => 1,
        'time' => '09:00',
        'capacity' => 10,
        'is_recurring' => true,
    ]);

    $member = User::factory()->create(['role' => 'member', 'status' => 'active']);

    // Active booking — should be counted
    Booking::create([
        'member_id' => $member->id,
        'coach_id' => $coach->id,
        'class_schedule_id' => $schedule->id,
        'scheduled_at' => now()->addDays(3),
        'status' => 'confirmed',
    ]);

    // Cancelled booking — should NOT be counted
    Booking::create([
        'member_id' => $member->id,
        'coach_id' => $coach->id,
        'class_schedule_id' => $schedule->id,
        'scheduled_at' => now()->addDays(4),
        'status' => 'cancelled',
    ]);

    expect($schedule->enrolledBookings()->count())->toBe(1);
});

it('ScheduleIndex render passes enrolled_count on schedules', function (): void {
    $admin = User::factory()->admin()->create(['must_change_password' => false]);
    $coach = makeCoach();

    $schedule = ClassSchedule::create([
        'name' => 'Spin Class',
        'coach_id' => $coach->id,
        'day_of_week' => 2,
        'time' => '08:00',
        'capacity' => 15,
        'is_recurring' => true,
    ]);

    $member = User::factory()->create(['role' => 'member', 'status' => 'active']);
    Booking::create([
        'member_id' => $member->id,
        'coach_id' => $coach->id,
        'class_schedule_id' => $schedule->id,
        'scheduled_at' => now()->addDays(2),
        'status' => 'pending',
    ]);

    $component = Livewire::actingAs($admin)->test(ScheduleIndex::class);
    $schedules = $component->viewData('schedules');

    $found = $schedules->firstWhere('id', $schedule->id);
    expect($found)->not->toBeNull();
    expect((int) $found->enrolled_count)->toBe(1);
});

it('showAllMembers toggle shows active members panel', function (): void {
    $admin = User::factory()->admin()->create(['must_change_password' => false]);

    // Active member
    User::factory()->create(['role' => 'member', 'status' => 'active', 'name' => 'Active Alice']);
    // Inactive member — should NOT appear
    User::factory()->create(['role' => 'member', 'status' => 'inactive', 'name' => 'Inactive Bob']);

    $component = Livewire::actingAs($admin)
        ->test(ScheduleIndex::class)
        ->assertSet('showAllMembers', false)
        ->set('showAllMembers', true);

    $activeMembers = $component->viewData('activeMembers');
    expect($activeMembers)->not->toBeNull();
    expect($activeMembers->pluck('name')->contains('Active Alice'))->toBeTrue();
    expect($activeMembers->pluck('name')->contains('Inactive Bob'))->toBeFalse();
});
