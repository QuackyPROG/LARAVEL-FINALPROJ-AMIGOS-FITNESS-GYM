<?php

namespace App\Mail;

use App\Models\Membership;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ExpiryWarningEmail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public readonly User $member,
        public readonly Membership $membership,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Your membership is expiring soon',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.expiry-warning',
        );
    }
}
