<?php

declare(strict_types=1);

use App\Livewire\Admin\MemberDetail;
use App\Models\MemberProfile;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

uses(RefreshDatabase::class);

it('admin can save id type and number via MemberDetail', function (): void {
    $admin = User::factory()->admin()->create(['must_change_password' => false]);
    $member = User::factory()->create();

    MemberProfile::create(['user_id' => $member->id]);

    Livewire::actingAs($admin)
        ->test(MemberDetail::class, ['member' => $member])
        ->set('editIdType', 'national')
        ->set('editIdNumber', '1234-5678-9012')
        ->call('saveIdFields')
        ->assertHasNoErrors();

    $this->assertDatabaseHas('member_profiles', [
        'user_id' => $member->id,
        'id_type' => 'national',
        'id_number' => '1234-5678-9012',
    ]);
});

it('invalid format for a given type fails validation', function (): void {
    $admin = User::factory()->admin()->create(['must_change_password' => false]);
    $member = User::factory()->create();

    MemberProfile::create(['user_id' => $member->id]);

    Livewire::actingAs($admin)
        ->test(MemberDetail::class, ['member' => $member])
        ->set('editIdType', 'national')
        ->set('editIdNumber', 'WRONG-FORMAT')
        ->call('saveIdFields')
        ->assertHasErrors(['editIdNumber']);
});

it('valid national id format passes validation', function (): void {
    $admin = User::factory()->admin()->create(['must_change_password' => false]);
    $member = User::factory()->create();

    MemberProfile::create(['user_id' => $member->id]);

    Livewire::actingAs($admin)
        ->test(MemberDetail::class, ['member' => $member])
        ->set('editIdType', 'national')
        ->set('editIdNumber', '9876-5432-1098')
        ->call('saveIdFields')
        ->assertHasNoErrors();

    $this->assertDatabaseHas('member_profiles', [
        'user_id' => $member->id,
        'id_type' => 'national',
        'id_number' => '9876-5432-1098',
    ]);
});

it('valid passport format passes validation', function (): void {
    $admin = User::factory()->admin()->create(['must_change_password' => false]);
    $member = User::factory()->create();

    MemberProfile::create(['user_id' => $member->id]);

    Livewire::actingAs($admin)
        ->test(MemberDetail::class, ['member' => $member])
        ->set('editIdType', 'passport')
        ->set('editIdNumber', 'P1234567A')
        ->call('saveIdFields')
        ->assertHasNoErrors();

    $this->assertDatabaseHas('member_profiles', [
        'user_id' => $member->id,
        'id_type' => 'passport',
        'id_number' => 'P1234567A',
    ]);
});

it('invalid passport format fails validation', function (): void {
    $admin = User::factory()->admin()->create(['must_change_password' => false]);
    $member = User::factory()->create();

    MemberProfile::create(['user_id' => $member->id]);

    Livewire::actingAs($admin)
        ->test(MemberDetail::class, ['member' => $member])
        ->set('editIdType', 'passport')
        ->set('editIdNumber', 'AB1234567')
        ->call('saveIdFields')
        ->assertHasErrors(['editIdNumber']);
});

it('saveIdFields creates profile if member has none', function (): void {
    $admin = User::factory()->admin()->create(['must_change_password' => false]);
    $member = User::factory()->create();

    // No profile created

    Livewire::actingAs($admin)
        ->test(MemberDetail::class, ['member' => $member])
        ->set('editIdType', 'sss')
        ->set('editIdNumber', '12-3456789-0')
        ->call('saveIdFields')
        ->assertHasNoErrors();

    $this->assertDatabaseHas('member_profiles', [
        'user_id' => $member->id,
        'id_type' => 'sss',
        'id_number' => '12-3456789-0',
    ]);
});

it('rejects unknown id type', function (): void {
    $admin = User::factory()->admin()->create(['must_change_password' => false]);
    $member = User::factory()->create();

    MemberProfile::create(['user_id' => $member->id]);

    Livewire::actingAs($admin)
        ->test(MemberDetail::class, ['member' => $member])
        ->set('editIdType', 'unknown_type')
        ->set('editIdNumber', '1234-5678-9012')
        ->call('saveIdFields')
        ->assertHasErrors(['editIdType']);
});
