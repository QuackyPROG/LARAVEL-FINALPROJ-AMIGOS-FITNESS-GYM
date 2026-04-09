<?php

use App\Jobs\AnnouncementMailer;
use App\Jobs\SendBookingConfirmation;
use App\Livewire\Admin\AnnouncementIndex;
use App\Livewire\Admin\CoachIndex;
use App\Livewire\Admin\EventIndex;
use App\Livewire\Portal\CoachRoster;
use App\Models\Booking;
use App\Models\Coach;
use App\Models\Event;
use App\Models\Membership;
use App\Models\MembershipPlan;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Queue;
use Livewire\Livewire;

uses(RefreshDatabase::class);

function sprint6Admin(): User
{
    return User::factory()->admin()->create(['must_change_password' => false]);
}

function sprint6Member(): User
{
    $plan = MembershipPlan::create(['name' => 'Monthly', 'duration_days' => 30, 'price' => 999, 'benefits' => ['x'], 'is_active' => true]);
    $user = User::factory()->create(['role' => 'member', 'status' => 'active', 'must_change_password' => false]);
    Membership::create(['user_id' => $user->id, 'plan_id' => $plan->id, 'starts_at' => now()->toDateString(), 'expires_at' => now()->addDays(30)->toDateString(), 'status' => 'active']);

    return $user;
}

it('admin coaches page returns 200', function (): void {
    $response = $this->actingAs(sprint6Admin())->get('/admin/coaches');
    $response->assertStatus(200);
});

it('admin schedules page returns 200', function (): void {
    $response = $this->actingAs(sprint6Admin())->get('/admin/schedules');
    $response->assertStatus(200);
});

it('admin events page returns 200', function (): void {
    $response = $this->actingAs(sprint6Admin())->get('/admin/events');
    $response->assertStatus(200);
});

it('admin announcements page returns 200', function (): void {
    $response = $this->actingAs(sprint6Admin())->get('/admin/announcements');
    $response->assertStatus(200);
});

it('coach CRUD creates coach via Livewire', function (): void {
    $admin = sprint6Admin();

    Livewire::actingAs($admin)
        ->test(CoachIndex::class)
        ->call('openCreate')
        ->set('name', 'Coach Bob')
        ->set('bio', 'Expert trainer')
        ->set('specializationsRaw', "HIIT\nStrength")
        ->call('save');

    expect(Coach::where('name', 'Coach Bob')->exists())->toBeTrue();
});

it('event CRUD creates event via Livewire', function (): void {
    $admin = sprint6Admin();

    Livewire::actingAs($admin)
        ->test(EventIndex::class)
        ->call('openCreate')
        ->set('title', 'Summer Bootcamp')
        ->set('date', now()->addDays(10)->format('Y-m-d\TH:i'))
        ->set('description', 'Join us!')
        ->call('save');

    expect(Event::where('title', 'Summer Bootcamp')->exists())->toBeTrue();
});

it('announcement queues correct recipient count for all members', function (): void {
    Queue::fake();
    $admin = sprint6Admin();
    sprint6Member();
    sprint6Member();

    Livewire::actingAs($admin)
        ->test(AnnouncementIndex::class)
        ->call('openCreate')
        ->set('subject', 'Gym Update')
        ->set('body', 'We are open!')
        ->set('recipientFilter', 'all')
        ->call('send');

    Queue::assertPushed(AnnouncementMailer::class, fn ($job) => count($job->recipientIds) === 2);
});

it('portal coaches page returns 200', function (): void {
    $response = $this->actingAs(sprint6Member())->get('/portal/coaches');
    $response->assertStatus(200);
});

it('portal schedule page returns 200', function (): void {
    $response = $this->actingAs(sprint6Member())->get('/portal/schedule');
    $response->assertStatus(200);
});

it('portal events page returns 200', function (): void {
    $response = $this->actingAs(sprint6Member())->get('/portal/events');
    $response->assertStatus(200);
});

it('booking created with confirmation email queued', function (): void {
    Queue::fake();
    $member = sprint6Member();
    $coach = Coach::create(['name' => 'Test Coach', 'specializations' => []]);

    Livewire::actingAs($member)
        ->test(CoachRoster::class)
        ->call('openBooking', $coach->id)
        ->set('scheduledDate', now()->addDays(3)->format('Y-m-d\TH:i'))
        ->call('confirmBooking');

    expect(Booking::where('member_id', $member->id)->where('coach_id', $coach->id)->exists())->toBeTrue();
    Queue::assertPushed(SendBookingConfirmation::class);
});

it('booking cancellation within 24h is rejected', function (): void {
    $member = sprint6Member();
    $coach = Coach::create(['name' => 'Coach X', 'specializations' => []]);

    $booking = Booking::create([
        'member_id' => $member->id,
        'coach_id' => $coach->id,
        'scheduled_at' => now()->addHours(2),
        'status' => 'confirmed',
    ]);

    Livewire::actingAs($member)
        ->test(CoachRoster::class)
        ->call('cancel', $booking->id);

    $booking->refresh();
    expect($booking->status)->toBe('confirmed');
});

it('booking outside availability — future date accepted', function (): void {
    Queue::fake();
    $member = sprint6Member();
    $coach = Coach::create(['name' => 'Coach Y', 'specializations' => []]);

    Livewire::actingAs($member)
        ->test(CoachRoster::class)
        ->call('openBooking', $coach->id)
        ->set('scheduledDate', now()->addDays(5)->format('Y-m-d\TH:i'))
        ->call('confirmBooking');

    expect(Booking::where('member_id', $member->id)->exists())->toBeTrue();
});
