<?php

namespace App\Services;

use App\Jobs\SendWelcomeEmail;
use App\Models\Membership;
use Illuminate\Support\Str;

class MembershipPaymentService
{
    public function activate(Membership $membership, string $paymentRef): void
    {
        if ($membership->status === 'active') {
            return;
        }

        $tempPassword = Str::random(12);
        $user = $membership->user;

        $user->password = bcrypt($tempPassword);
        $user->must_change_password = true;
        $user->status = 'active';
        $user->email_verified_at = now();
        $user->save();

        $membership->update([
            'status' => 'active',
            'payment_ref' => $paymentRef,
        ]);

        SendWelcomeEmail::dispatch($user->fresh(), $tempPassword);
    }

    public function fail(Membership $membership): void
    {
        if ($membership->status !== 'active') {
            $membership->update(['status' => 'failed']);
        }
    }
}
