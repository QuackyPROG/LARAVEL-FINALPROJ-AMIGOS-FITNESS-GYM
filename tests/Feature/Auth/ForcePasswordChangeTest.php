<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('redirects user with must_change_password=true to change-password page', function (): void {
    $user = User::factory()->mustChangePassword()->create(['role' => 'member']);

    $this->actingAs($user)
        ->get('/portal/dashboard')
        ->assertRedirect('/change-password');
});

it('allows user with must_change_password=false to access portal', function (): void {
    $user = User::factory()->create([
        'role' => 'member',
        'must_change_password' => false,
    ]);

    $this->actingAs($user)
        ->get('/portal/dashboard')
        ->assertStatus(200);
});

it('clears must_change_password after submitting new password', function (): void {
    $user = User::factory()->mustChangePassword()->create(['role' => 'member']);

    $this->actingAs($user)->post('/change-password', [
        'password' => 'newSecurePassword1!',
        'password_confirmation' => 'newSecurePassword1!',
    ])->assertRedirect();

    expect($user->fresh()->must_change_password)->toBeFalse();
});

it('requires password confirmation to match', function (): void {
    $user = User::factory()->mustChangePassword()->create(['role' => 'member']);

    $this->actingAs($user)->post('/change-password', [
        'password' => 'newSecurePassword1!',
        'password_confirmation' => 'differentPassword',
    ])->assertSessionHasErrors('password');
});
