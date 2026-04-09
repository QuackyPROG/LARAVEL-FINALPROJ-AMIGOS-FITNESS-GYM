<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PaymentFailed
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * @param  array<string, mixed>  $payload  Raw PayMongo webhook payload
     */
    public function __construct(
        public readonly string $paymongoId,
        public readonly string $type,
        public readonly ?string $failureCode,
        public readonly ?string $failureMessage,
        public readonly array $payload,
    ) {}
}
