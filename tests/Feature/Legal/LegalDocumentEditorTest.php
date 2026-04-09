<?php

use App\Livewire\Admin\LegalDocumentEditor;
use App\Models\AuditLog;
use App\Models\SiteContent;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

uses(RefreshDatabase::class);

function seedDoc(string $slug = 'terms-and-conditions'): void
{
    $key = match ($slug) {
        'terms-and-conditions' => 'legal.terms_and_conditions',
        'membership-contract' => 'legal.membership_contract',
        'liability-waiver' => 'legal.liability_waiver',
        'privacy-policy' => 'legal.privacy_policy',
        default => 'legal.terms_and_conditions',
    };
    SiteContent::updateOrCreate(['key' => $key], ['value' => '<p>Original content.</p>', 'type' => 'html']);
    SiteContent::updateOrCreate(['key' => $key.'_version'], ['value' => '1', 'type' => 'text']);
}

it('admin can view legal documents index', function (): void {
    seedDoc();
    $admin = User::factory()->admin()->create();

    $this->actingAs($admin)->get('/admin/legal')->assertOk();
});

it('admin can view legal document edit page', function (): void {
    seedDoc();
    $admin = User::factory()->admin()->create();

    $this->actingAs($admin)->get('/admin/legal/terms-and-conditions/edit')->assertOk();
});

it('increments document version on save', function (): void {
    seedDoc();
    $admin = User::factory()->admin()->create();

    Livewire::actingAs($admin)
        ->test(LegalDocumentEditor::class, ['slug' => 'terms-and-conditions'])
        ->assertSet('version', 1)
        ->set('body', '<p>Updated terms content with enough characters.</p>')
        ->call('save')
        ->assertSet('version', 2)
        ->assertSet('saved', true);

    expect(SiteContent::get('legal.terms_and_conditions_version'))->toBe('2');
});

it('saves audit log entry with action legal.document.updated on save', function (): void {
    seedDoc();
    $admin = User::factory()->admin()->create();

    Livewire::actingAs($admin)
        ->test(LegalDocumentEditor::class, ['slug' => 'terms-and-conditions'])
        ->set('body', '<p>Audited terms content update here.</p>')
        ->call('save');

    $log = AuditLog::where('action', 'legal.document.updated')->first();
    expect($log)->not->toBeNull();
    expect($log->actor_id)->toBe($admin->id);
});

it('returns 404 for unknown document slug', function (): void {
    $admin = User::factory()->admin()->create();

    $this->actingAs($admin)->get('/admin/legal/unknown-doc/edit')->assertNotFound();
});
