<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('shows the login form', function (): void {
    $response = $this->get('/login');

    $response->assertStatus(200);
    $response->assertSee('Sign in');
});

it('allows a member to log in and redirects to portal dashboard', function (): void {
    $member = User::factory()->create([
        'role' => 'member',
        'must_change_password' => false,
    ]);

    $response = $this->post('/login', [
        'email' => $member->email,
        'password' => 'password',
    ]);

    $response->assertRedirect('/portal/dashboard');
    $this->assertAuthenticatedAs($member);
});

it('allows an admin to log in and redirects to admin dashboard', function (): void {
    $admin = User::factory()->admin()->create();

    $response = $this->post('/login', [
        'email' => $admin->email,
        'password' => 'password',
    ]);

    $response->assertRedirect('/admin/dashboard');
    $this->assertAuthenticatedAs($admin);
});

it('rejects invalid credentials', function (): void {
    User::factory()->create(['email' => 'member@test.com']);

    $response = $this->post('/login', [
        'email' => 'member@test.com',
        'password' => 'wrongpassword',
    ]);

    $response->assertSessionHasErrors('email');
    $this->assertGuest();
});

it('redirects user with must_change_password to change-password page', function (): void {
    $user = User::factory()->mustChangePassword()->create(['role' => 'member']);

    $response = $this->post('/login', [
        'email' => $user->email,
        'password' => 'password',
    ]);

    $response->assertRedirect('/change-password');
});

it('logs out the authenticated user', function (): void {
    $user = User::factory()->create();

    $this->actingAs($user)->post('/logout')
        ->assertRedirect('/login');

    $this->assertGuest();
});
