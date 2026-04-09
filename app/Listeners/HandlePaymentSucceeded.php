<?php

namespace App\Listeners;

use App\Events\PaymentSucceeded;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;

class HandlePaymentSucceeded implements ShouldQueue
{
    /**
     * Handle the PaymentSucceeded event.
     *
     * Generator: implement the full order/subscription activation logic here.
     * This listener runs on the queue — do not block HTTP.
     *
     * Typical pattern:
     *   1. Find the related order/subscription via $event->paymongoId
     *   2. Update its status to 'active' / 'paid'
     *   3. Dispatch a confirmation email Job
     *   4. Log the transaction
     */
    public function handle(PaymentSucceeded $event): void
    {
        // Membership activation is handled directly in PayMongoWebhookController.
        // This listener is retained for observability only.
        Log::info('PaymentSucceeded event fired — handled by webhook controller directly.', [
            'paymongo_id' => $event->paymongoId,
        ]);
    }
}
