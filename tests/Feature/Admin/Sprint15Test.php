<?php

declare(strict_types=1);

use App\Livewire\Admin\CoachIndex;
use App\Models\Coach;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

uses(RefreshDatabase::class);

it('coach form opens and closes', function (): void {
    $admin = User::factory()->admin()->create(['must_change_password' => false]);

    Livewire::actingAs($admin)
        ->test(CoachIndex::class)
        ->call('openCreate')
        ->assertSet('showForm', true)
        ->call('$set', 'showForm', false)
        ->assertSet('showForm', false);
});

it('coach can be created with name only', function (): void {
    $admin = User::factory()->admin()->create(['must_change_password' => false]);

    Livewire::actingAs($admin)
        ->test(CoachIndex::class)
        ->call('openCreate')
        ->set('name', 'Coach Maria')
        ->set('bio', 'Expert trainer')
        ->set('specializationsRaw', "Yoga\nCardio")
        ->call('save')
        ->assertHasNoErrors();

    $this->assertDatabaseHas('coaches', ['name' => 'Coach Maria']);
});

it('coach save with photoCropped stores base64 image', function (): void {
    $admin = User::factory()->admin()->create(['must_change_password' => false]);

    $base64 = 'data:image/jpeg;base64,'.base64_encode('fake-image-data');

    Livewire::actingAs($admin)
        ->test(CoachIndex::class)
        ->call('openCreate')
        ->set('name', 'Photo Coach')
        ->set('photoCropped', $base64)
        ->call('save')
        ->assertHasNoErrors();

    $coach = Coach::where('name', 'Photo Coach')->first();
    expect($coach)->not->toBeNull();
    expect($coach->photo)->not->toBeNull();
});

it('coach delete confirmation shows and executes', function (): void {
    $admin = User::factory()->admin()->create(['must_change_password' => false]);
    $coach = Coach::create(['name' => 'Delete Me', 'bio' => null, 'specializations' => []]);

    Livewire::actingAs($admin)
        ->test(CoachIndex::class)
        ->call('confirmDelete', $coach->id)
        ->assertSet('showDeleteModal', true)
        ->call('executeDelete')
        ->assertSet('showDeleteModal', false);

    $this->assertDatabaseMissing('coaches', ['id' => $coach->id]);
});

it('coach list cards show first 2 specializations only', function (): void {
    $admin = User::factory()->admin()->create(['must_change_password' => false]);
    Coach::create(['name' => 'Multi Spec', 'bio' => null, 'specializations' => ['A', 'B', 'C', 'D']]);

    $response = $this->actingAs($admin)->get('/admin/coaches');
    $response->assertSee('A');
    $response->assertSee('B');
    $response->assertSee('+2 more');
});
