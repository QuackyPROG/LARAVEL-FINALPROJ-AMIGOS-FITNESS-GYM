<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('allows a member to access the portal dashboard', function (): void {
    $member = User::factory()->create(['role' => 'member']);

    $this->actingAs($member)
        ->get('/portal/dashboard')
        ->assertStatus(200);
});

it('blocks a member from accessing admin routes with 403', function (): void {
    $member = User::factory()->create(['role' => 'member']);

    $this->actingAs($member)
        ->get('/admin/dashboard')
        ->assertStatus(403);
});

it('allows an admin to access the admin dashboard', function (): void {
    $admin = User::factory()->admin()->create();

    $this->actingAs($admin)
        ->get('/admin/dashboard')
        ->assertStatus(200);
});

it('blocks an admin from accessing portal routes with 403', function (): void {
    $admin = User::factory()->admin()->create();

    $this->actingAs($admin)
        ->get('/portal/dashboard')
        ->assertStatus(403);
});

it('redirects unauthenticated users to the login page', function (): void {
    $this->get('/portal/dashboard')->assertRedirect('/login');
    $this->get('/admin/dashboard')->assertRedirect('/login');
});
