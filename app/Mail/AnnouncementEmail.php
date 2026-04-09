<?php

namespace App\Mail;

use App\Models\Announcement;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AnnouncementEmail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public readonly Announcement $announcement) {}

    public function envelope(): Envelope
    {
        return new Envelope(subject: $this->announcement->subject);
    }

    public function content(): Content
    {
        return new Content(view: 'emails.announcement');
    }
}
