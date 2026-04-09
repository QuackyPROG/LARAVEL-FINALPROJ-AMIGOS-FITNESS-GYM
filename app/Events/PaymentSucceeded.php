<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PaymentSucceeded
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * @param  array<string, mixed>  $payload  Raw PayMongo webhook payload
     */
    public function __construct(
        public readonly string $paymongoId,
        public readonly string $type,
        public readonly int $amount,
        public readonly string $currency,
        public readonly array $payload,
    ) {}
}
