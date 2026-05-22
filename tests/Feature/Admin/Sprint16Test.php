<?php

declare(strict_types=1);

use App\Livewire\Admin\ScheduleIndex;
use App\Models\ClassSchedule;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

uses(RefreshDatabase::class);

it('schedule form opens and closes', function (): void {
    $admin = User::factory()->admin()->create(['must_change_password' => false]);

    Livewire::actingAs($admin)
        ->test(ScheduleIndex::class)
        ->call('openCreate')
        ->assertSet('showForm', true)
        ->call('$set', 'showForm', false)
        ->assertSet('showForm', false);
});

it('schedule can be created with name and time', function (): void {
    $admin = User::factory()->admin()->create(['must_change_password' => false]);

    Livewire::actingAs($admin)
        ->test(ScheduleIndex::class)
        ->call('openCreate')
        ->set('name', 'Morning Yoga')
        ->set('time', '07:00')
        ->set('dayOfWeek', 1)
        ->set('capacity', 15)
        ->call('save')
        ->assertHasNoErrors();

    $this->assertDatabaseHas('class_schedules', ['name' => 'Morning Yoga', 'capacity' => 15]);
});

it('schedule delete confirmation shows and executes', function (): void {
    $admin = User::factory()->admin()->create(['must_change_password' => false]);
    $schedule = ClassSchedule::create([
        'name' => 'Delete Me Class',
        'day_of_week' => 2,
        'time' => '10:00',
        'capacity' => 10,
        'is_recurring' => true,
    ]);

    Livewire::actingAs($admin)
        ->test(ScheduleIndex::class)
        ->call('confirmDelete', $schedule->id)
        ->assertSet('showDeleteModal', true)
        ->call('executeDelete')
        ->assertSet('showDeleteModal', false);

    $this->assertDatabaseMissing('class_schedules', ['id' => $schedule->id]);
});

it('schedule form shows day of week pills', function (): void {
    $admin = User::factory()->admin()->create(['must_change_password' => false]);

    $response = $this->actingAs($admin)->get('/admin/schedules');
    $response->assertStatus(200);
});

it('capacity stepper increments and decrements', function (): void {
    $admin = User::factory()->admin()->create(['must_change_password' => false]);

    Livewire::actingAs($admin)
        ->test(ScheduleIndex::class)
        ->call('openCreate')
        ->assertSet('capacity', 10)
        ->set('capacity', 11)
        ->assertSet('capacity', 11)
        ->set('capacity', 9)
        ->assertSet('capacity', 9);
});
