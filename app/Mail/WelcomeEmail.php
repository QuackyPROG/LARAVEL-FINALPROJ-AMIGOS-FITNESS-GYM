<?php

namespace App\Mail;

use App\Models\Membership;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class WelcomeEmail extends Mailable
{
    use Queueable, SerializesModels;

    public readonly ?Membership $membership;

    public function __construct(
        public readonly User $user,
        public readonly string $tempPassword,
    ) {
        $this->membership = $user->memberships()
            ->with('plan')
            ->latest()
            ->first();
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Welcome to AmigosFitnessGym — Your Login Details',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.welcome',
            with: [
                'memberName' => $this->user->name,
                'email' => $this->user->email,
                'tempPassword' => $this->tempPassword,
                'loginUrl' => url('/login'),
                'planName' => $this->membership?->plan?->name ?? 'Membership',
                'expiryDate' => $this->membership?->expires_at?->format('F j, Y') ?? '',
            ],
        );
    }
}
