<?php

namespace App\Services;

use Illuminate\Http\Request;
use Luigel\Paymongo\Facades\Paymongo;

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
        array $paymentMethodTypes = ['gcash', 'card', 'paymaya', 'grabpay', 'qrph'],
    ): array {
        $session = Paymongo::checkoutSession()->create([
            'data' => [
                'attributes' => [
                    'line_items' => $lineItems,
                    'payment_method_types' => $paymentMethodTypes,
                    'success_url' => $successUrl,
                    'cancel_url' => $cancelUrl,
                    'billing' => [
                        'name' => auth()->user()?->name ?? 'Guest',
                        'email' => auth()->user()?->email ?? '',
                    ],
                ],
            ],
        ]);

        return $session->getData();
    }

    /**
     * Retrieve a Checkout Session by ID.
     */
    public function getCheckoutSession(string $id): array
    {
        return Paymongo::checkoutSession()->find($id)->getData();
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
        $intent = Paymongo::paymentIntent()->create([
            'data' => [
                'attributes' => [
                    'amount' => $amount,
                    'currency' => $currency,
                    'payment_method_allowed' => $paymentMethodTypes,
                ],
            ],
        ]);

        return $intent->getData();
    }

    /**
     * Create a Payment Method.
     *
     * @param  array{number: string, exp_month: int, exp_year: int, cvc: string}  $cardDetails
     */
    public function createPaymentMethod(array $cardDetails, array $billingDetails = []): array
    {
        $method = Paymongo::paymentMethod()->create([
            'data' => [
                'attributes' => [
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
                ],
            ],
        ]);

        return $method->getData();
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
            [$key, $value] = explode('=', $part, 2);
            $parts[$key] = $value;
        }

        $timestamp = $parts['t'] ?? '';
        $expectedHash = hash_hmac('sha256', $timestamp.'.'.$payload, $secret);

        if (! hash_equals($expectedHash, $parts['te'] ?? '')) {
            throw new \RuntimeException('Invalid webhook signature.');
        }

        return json_decode($payload, true);
    }
}
