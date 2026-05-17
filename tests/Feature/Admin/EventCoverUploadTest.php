<?php

use App\Livewire\Admin\EventIndex;
use App\Models\Event;
use Livewire\Livewire;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('updates event cover image when editing with base64 cropped image', function (): void {
    // create initial event
    $event = Event::create([
        'title' => 'Test Event',
        'description' => 'Desc',
        'date' => now()->addDay(),
        'is_visible' => true,
    ]);

    // a tiny 1x1 jpg base64 (valid small jpeg)
    $img = imagecreatetruecolor(1,1);
    ob_start();
    imagejpeg($img);
    $data = ob_get_clean();
    imagedestroy($img);
    $base64 = 'data:image/jpeg;base64,' . base64_encode($data);

    Livewire::test(EventIndex::class)
        ->call('openEdit', $event->id)
        ->set('coverImageCropped', $base64)
        ->call('save');

    $event->refresh();
    expect($event->cover_image)->not->toBeNull();
    // file exists in storage
    $this->assertTrue(\Illuminate\Support\Facades\Storage::disk('public')->exists($event->cover_image));
});
