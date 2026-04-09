<?php

use App\Livewire\Admin\SiteContentEditor;
use App\Models\SiteContent;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

uses(RefreshDatabase::class);

it('allows admin to view the site content editor', function (): void {
    $admin = User::factory()->admin()->create();

    $response = $this->actingAs($admin)->get('/admin/site-content');

    $response->assertStatus(200);
});

it('redirects guest to login when accessing site content editor', function (): void {
    $response = $this->get('/admin/site-content');

    $response->assertRedirect('/login');
});

it('returns 403 for a member accessing the site content editor', function (): void {
    $member = User::factory()->create([
        'role' => 'member',
        'must_change_password' => false,
    ]);

    $response = $this->actingAs($member)->get('/admin/site-content');

    $response->assertStatus(403);
});

it('admin saving hero_title updates the site_contents table', function (): void {
    SiteContent::updateOrCreate(
        ['key' => 'hero_title'],
        ['value' => 'Old Title', 'type' => 'text']
    );
    SiteContent::updateOrCreate(['key' => 'hero_subtitle'], ['value' => 'Sub', 'type' => 'text']);
    SiteContent::updateOrCreate(['key' => 'gym_hours'], ['value' => 'Hours', 'type' => 'text']);
    SiteContent::updateOrCreate(['key' => 'gym_address'], ['value' => 'Address', 'type' => 'text']);
    SiteContent::updateOrCreate(['key' => 'gym_phone'], ['value' => 'Phone', 'type' => 'text']);

    $admin = User::factory()->admin()->create();

    Livewire::actingAs($admin)
        ->test(SiteContentEditor::class)
        ->set('hero_title', 'New Gym Title')
        ->call('save')
        ->assertHasNoErrors();

    expect(SiteContent::get('hero_title'))->toBe('New Gym Title');
});

it('updated CMS content is reflected on the public homepage', function (): void {
    SiteContent::updateOrCreate(
        ['key' => 'hero_title'],
        ['value' => 'Old Title', 'type' => 'text']
    );
    SiteContent::updateOrCreate(['key' => 'hero_subtitle'], ['value' => 'Sub', 'type' => 'text']);
    SiteContent::updateOrCreate(['key' => 'gym_hours'], ['value' => 'Hours', 'type' => 'text']);
    SiteContent::updateOrCreate(['key' => 'gym_address'], ['value' => 'Address', 'type' => 'text']);
    SiteContent::updateOrCreate(['key' => 'gym_phone'], ['value' => 'Phone', 'type' => 'text']);

    $admin = User::factory()->admin()->create();

    Livewire::actingAs($admin)
        ->test(SiteContentEditor::class)
        ->set('hero_title', 'Updated Headline')
        ->call('save');

    $response = $this->get('/');
    $response->assertSee('Updated Headline');
});
