<?php

namespace App\Http\Controllers;

use App\Models\Membership;
use App\Models\WebhookLog;
use App\Services\MembershipPaymentService;
use App\Services\PayMongoService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class PayMongoWebhookController extends Controller
{
    public function __construct(
        private readonly PayMongoService $payMongoService,
        private readonly MembershipPaymentService $membershipPaymentService,
    ) {}

    public function handle(Request $request): JsonResponse
    {
        try {
            $event = $this->payMongoService->verifyAndParseWebhook($request);
        } catch (\RuntimeException $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }

        $eventType = $event['data']['attributes']['type'] ?? '';
        $checkoutSessionId = $event['data']['id'] ?? null;
        $paymongoId = $checkoutSessionId ?? Str::uuid()->toString();

        // Log every event immediately
        $log = WebhookLog::create([
            'event_type' => $eventType,
            'payload' => $event,
            'status' => 'received',
            'payment_ref' => $paymongoId,
        ]);

        // Idempotency — do not process the same payment_ref twice
        if (Membership::where('payment_ref', $paymongoId)->exists()) {
            $log->update(['status' => 'already_processed']);

            return response()->json(['status' => 'already_processed']);
        }

        if (! $checkoutSessionId) {
            $log->update(['status' => 'failed']);

            return response()->json(['error' => 'Missing checkout session id'], 422);
        }

        $membership = Membership::where('checkout_session_id', $checkoutSessionId)->first();

        if (! $membership) {
            // Unknown session — log and acknowledge (PayMongo expects 200)
            $log->update(['status' => 'failed']);
            Log::warning('Webhook received for unknown checkout_session_id', ['id' => $checkoutSessionId]);

            return response()->json(['status' => 'ok']);
        }

        match ($eventType) {
            'checkout_session.payment.paid' => $this->activateMembership($membership, $paymongoId, $log),
            'checkout_session.payment.failed' => $this->failMembership($membership, $log),
            default => $log->update(['status' => 'ignored']),
        };

        return response()->json(['status' => 'ok']);
    }

    private function activateMembership(Membership $membership, string $paymongoId, WebhookLog $log): void
    {
        $this->membershipPaymentService->activate($membership, $paymongoId);
        $log->update(['status' => 'processed']);
    }

    private function failMembership(Membership $membership, WebhookLog $log): void
    {
        $this->membershipPaymentService->fail($membership);
        $log->update(['status' => 'processed']);
    }
}
