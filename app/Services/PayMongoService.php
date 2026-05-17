<?php

namespace App\Services;

use Illuminate\Http\Request;
use Kirame\PayMongo\Facades\PayMongo;

class PayMongoService
{
    /**
     * Create a PayMongo Checkout Session.
     *
     * @param  array<array{name: string, amount: int, currency: string, quantity: int}>  $lineItems
     */
    public function createCheckoutSession(
        array $lineItems,
        string $successUrl,
        string $cancelUrl,
        array $paymentMethodTypes = ['gcash', 'card', 'paymaya', 'grab_pay', 'qrph'],
        array $billingDetails = [],
    ): array {
        return PayMongo::createCheckoutSession([
            'line_items' => $lineItems,
            'payment_method_types' => $paymentMethodTypes,
            'success_url' => $successUrl,
            'cancel_url' => $cancelUrl,
            'billing' => $billingDetails ?: [
                'name' => auth()->user()?->name ?? 'Guest',
                'email' => auth()->user()?->email ?? '',
            ],
        ]);
    }

    /**
     * Retrieve a Checkout Session by ID.
     */
    public function getCheckoutSession(string $id): array
    {
        return PayMongo::retrieveCheckoutSession($id);
    }

    /**
     * Create a Payment Intent for custom payment UI flows.
     *
     * @param  int  $amount  Amount in centavos (e.g. 99900 = PHP 999.00)
     */
    public function createPaymentIntent(
        int $amount,
        string $currency = 'PHP',
        array $paymentMethodTypes = ['card'],
    ): array {
        return PayMongo::createPaymentIntent([
            'amount' => $amount,
            'currency' => $currency,
            'payment_method_allowed' => $paymentMethodTypes,
        ]);
    }

    /**
     * Create a Payment Method.
     *
     * @param  array{number: string, exp_month: int, exp_year: int, cvc: string}  $cardDetails
     */
    public function createPaymentMethod(array $cardDetails, array $billingDetails = []): array
    {
        return PayMongo::createPaymentMethod([
            'type' => 'card',
            'details' => $cardDetails,
            'billing' => $billingDetails ?: [
                'name' => auth()->user()?->name ?? 'Guest',
                'email' => auth()->user()?->email ?? '',
                'phone' => '',
                'address' => [
                    'line1' => '',
                    'city' => '',
                    'state' => '',
                    'postal_code' => '',
                    'country' => 'PH',
                ],
            ],
        ]);
    }

    /**
     * Verify the PayMongo webhook signature and return the parsed event.
     * Throws \RuntimeException if signature is invalid.
     */
    public function verifyAndParseWebhook(Request $request): array
    {
        $payload = $request->getContent();
        $signature = $request->header('Paymongo-Signature');
        $secret = config('paymongo.webhook_secret');

        if (! $signature || ! $secret) {
            throw new \RuntimeException('Missing webhook signature or secret.');
        }

        // PayMongo signature format: t=timestamp,te=hash,li=hash
        $parts = [];
        foreach (explode(',', $signature) as $part) {
            $segments = explode('=', $part, 2);

            if (count($segments) === 2) {
                $parts[$segments[0]] = $segments[1];
            }
        }

        $timestamp = $parts['t'] ?? '';
        $providedHash = $parts['te'] ?? $parts['li'] ?? '';
        $expectedHash = hash_hmac('sha256', $timestamp.'.'.$payload, $secret);

        if (! $timestamp || ! hash_equals($expectedHash, $providedHash)) {
            throw new \RuntimeException('Invalid webhook signature.');
        }

        $event = json_decode($payload, true);

        if (! is_array($event)) {
            throw new \RuntimeException('Invalid webhook payload.');
        }

        return $event;
    }
}
