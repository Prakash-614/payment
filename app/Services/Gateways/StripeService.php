<?php

namespace App\Services\Gateways;

use App\Models\Transaction;
use App\Models\WebsiteGatewaySetting;
use RuntimeException;
use Stripe\Checkout\Session;
use Stripe\Stripe;

class StripeService
{
    protected string $secretKey;

    public function __construct(WebsiteGatewaySetting $setting)
    {
        $mode = $setting->mode; // 'sandbox' or 'live'
        $credentials = $setting->getCredentials($mode);

        $this->secretKey = $credentials['secret_key'] ?? '';

        if (empty($this->secretKey)) {
            throw new RuntimeException("Stripe Secret Key is missing for this website.");
        }

        Stripe::setApiKey($this->secretKey);
    }

    /**
     * Create Stripe Checkout Session and return redirect URL
     */
    public function createCheckoutSession(Transaction $transaction): string
    {
        $returnUrl = route('checkout.callback', ['stripe', 'success', $transaction->session_token]) . '?session_id={CHECKOUT_SESSION_ID}';
        $cancelUrl = route('checkout.callback', ['stripe', 'cancel', $transaction->session_token]);

        // Stripe amounts are in cents (e.g. $150.00 = 15000)
        $unitAmount = (int) round($transaction->amount * 100);

        $session = Session::create([
            'line_items' => [[
                'price_data' => [
                    'currency' => strtolower($transaction->currency),
                    'product_data' => [
                        'name' => "Order #{$transaction->order_id}",
                        'description' => "Payment to {$transaction->website->name}",
                    ],
                    'unit_amount' => $unitAmount,
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'success_url' => $returnUrl,
            'cancel_url' => $cancelUrl,
            'client_reference_id' => $transaction->transaction_uuid,
            'customer_email' => $transaction->customer_email ?: null,
        ]);

        return $session->url;
    }

    /**
     * Verify Stripe session status
     */
    public function verifySession(string $sessionId): array
    {
        $session = Session::retrieve($sessionId);

        return [
            'success' => ($session && $session->payment_status === 'paid'),
            'raw' => $session ? $session->toArray() : [],
        ];
    }
}
