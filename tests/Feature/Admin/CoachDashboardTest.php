<?php

declare(strict_types=1);

use App\Models\Booking;
use App\Models\ClassSchedule;
use App\Models\Coach;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('admin can access coach dashboard and gets 200', function (): void {
    $admin = User::factory()->admin()->create(['must_change_password' => false]);
    $coach = Coach::create(['name' => 'Test Coach', 'bio' => null, 'specializations' => []]);

    $this->actingAs($admin)
        ->get(route('admin.coaches.show', $coach))
        ->assertOk();
});

it('non-admin is forbidden from coach dashboard', function (): void {
    $member = User::factory()->create(['role' => 'member', 'must_change_password' => false]);
    $coach = Coach::create(['name' => 'Test Coach', 'bio' => null, 'specializations' => []]);

    $this->actingAs($member)
        ->get(route('admin.coaches.show', $coach))
        ->assertForbidden();
});

it('coach dashboard page shows coach name', function (): void {
    $admin = User::factory()->admin()->create(['must_change_password' => false]);
    $coach = Coach::create(['name' => 'Jane Doe', 'bio' => 'Expert trainer.', 'specializations' => ['Yoga', 'Pilates']]);

    $this->actingAs($admin)
        ->get(route('admin.coaches.show', $coach))
        ->assertOk()
        ->assertSee('Jane Doe');
});

it('coach dashboard shows schedule data when schedules exist', function (): void {
    $admin = User::factory()->admin()->create(['must_change_password' => false]);
    $coach = Coach::create(['name' => 'Sam Smith', 'specializations' => []]);

    ClassSchedule::create([
        'coach_id' => $coach->id,
        'name' => 'Morning Yoga',
        'day_of_week' => 1,
        'time' => '08:00:00',
        'capacity' => 20,
        'is_recurring' => true,
    ]);

    $this->actingAs($admin)
        ->get(route('admin.coaches.show', $coach))
        ->assertOk()
        ->assertSee('Morning Yoga')
        ->assertSee('Monday');
});

it('coach dashboard shows booking data when bookings exist', function (): void {
    $admin = User::factory()->admin()->create(['must_change_password' => false]);
    $member = User::factory()->create(['name' => 'Alice Member', 'must_change_password' => false]);
    $coach = Coach::create(['name' => 'Bob Coach', 'specializations' => []]);

    Booking::create([
        'coach_id' => $coach->id,
        'member_id' => $member->id,
        'scheduled_at' => now()->addDay(),
        'status' => 'confirmed',
    ]);

    $this->actingAs($admin)
        ->get(route('admin.coaches.show', $coach))
        ->assertOk()
        ->assertSee('Alice Member')
        ->assertSee('Confirmed');
});
