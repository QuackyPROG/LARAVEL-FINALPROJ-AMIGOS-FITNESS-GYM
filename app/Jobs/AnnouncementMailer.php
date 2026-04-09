<?php

namespace App\Jobs;

use App\Mail\AnnouncementEmail;
use App\Models\Announcement;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class AnnouncementMailer implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        public readonly int $announcementId,
        public readonly array $recipientIds,
    ) {}

    public function handle(): void
    {
        $announcement = Announcement::findOrFail($this->announcementId);

        foreach ($this->recipientIds as $userId) {
            $user = User::find($userId);
            if ($user) {
                Mail::to($user->email)->send(new AnnouncementEmail($announcement));
            }
        }
    }
}
