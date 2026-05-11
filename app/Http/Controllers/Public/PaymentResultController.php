<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Membership;
use App\Models\SiteContent;
use App\Services\MembershipPaymentService;
use App\Services\PayMongoService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class PaymentResultController extends Controller
{
    public function __construct(
        private readonly PayMongoService $payMongoService,
        private readonly MembershipPaymentService $membershipPaymentService,
    ) {}

    public function success(Request $request): View
    {
        $ref = $request->query('ref');
        $membership = $ref ? Membership::with(['user', 'plan'])->find($ref) : null;

        if ($membership && $membership->status === 'pending_payment') {
            $this->confirmCheckoutSessionPayment($membership);
            $membership->refresh()->load(['user', 'plan']);
        }

        $isPending = $membership && $membership->status === 'pending_payment';

        return view('public.payment-success', compact('membership', 'isPending'));
    }

    public function failed(Request $request): View
    {
        $ref = $request->query('ref');
        $membership = $ref ? Membership::with('plan')->find($ref) : null;
        $gymPhone = SiteContent::get('gym_phone');

        return view('public.payment-failed', compact('membership', 'gymPhone'));
    }

    private function confirmCheckoutSessionPayment(Membership $membership): void
    {
        if (! $membership->checkout_session_id || ! config('paymongo.secret_key')) {
            return;
        }

        try {
            $session = $this->payMongoService->getCheckoutSession($membership->checkout_session_id);
        } catch (\Throwable $e) {
            Log::warning('Unable to verify PayMongo checkout session after redirect.', [
                'membership_id' => $membership->id,
                'checkout_session_id' => $membership->checkout_session_id,
                'exception' => $e::class,
                'message' => $e->getMessage(),
            ]);

            return;
        }

        if (! $this->checkoutSessionIsPaid($session)) {
            return;
        }

        $this->membershipPaymentService->activate(
            $membership,
            $session['id'] ?? $membership->checkout_session_id,
        );
    }

    /**
     * PayMongo may expose paid state differently across checkout-session
     * responses, so accept the known paid status locations.
     */
    private function checkoutSessionIsPaid(array $session): bool
    {
        $statusPaths = [
            'attributes.status',
            'attributes.payment_status',
            'attributes.checkout_status',
            'attributes.data.attributes.status',
            'attributes.payment_intent.attributes.status',
            'attributes.payments.0.attributes.status',
        ];

        foreach ($statusPaths as $path) {
            $status = strtolower((string) data_get($session, $path));

            if (in_array($status, ['paid', 'succeeded', 'success'], true)) {
                return true;
            }
        }

        return false;
    }
}
