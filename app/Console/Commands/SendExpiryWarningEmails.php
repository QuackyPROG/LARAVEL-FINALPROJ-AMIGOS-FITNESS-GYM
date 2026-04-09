<?php

namespace App\Console\Commands;

use App\Mail\ExpiryWarningEmail;
use App\Models\Membership;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendExpiryWarningEmails extends Command
{
    protected $signature = 'memberships:send-expiry-warnings';

    protected $description = 'Email members whose membership expires within 7 days (once per expiry cycle)';

    public function handle(): int
    {
        $expiring = Membership::with(['user', 'plan'])
            ->where('status', 'active')
            ->whereBetween('expires_at', [now(), now()->addDays(7)])
            ->whereNull('expiry_warned_at')
            ->get();

        foreach ($expiring as $membership) {
            Mail::to($membership->user->email)
                ->queue(new ExpiryWarningEmail($membership->user, $membership));

            $membership->update(['expiry_warned_at' => now()]);
        }

        $this->info("Expiry warnings queued for {$expiring->count()} member(s).");

        return self::SUCCESS;
    }
}
