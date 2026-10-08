<?php

namespace App\Services\Gateways;

use App\Models\Transaction;
use App\Models\WebsiteGatewaySetting;
use Illuminate\Support\Facades\Http;
use RuntimeException;

class PayPalService
{
    protected string $baseUrl;
    protected string $clientId;
    protected string $clientSecret;

    public function __construct(WebsiteGatewaySetting $setting)
    {
        $mode = $setting->mode; // 'sandbox' or 'live'
        $credentials = $setting->getCredentials($mode);

        $this->baseUrl = $mode === 'live'
            ? 'https://api-m.paypal.com'
            : 'https://api-m.sandbox.paypal.com';

        $this->clientId = $credentials['client_id'] ?? '';
        $this->clientSecret = $credentials['client_secret'] ?? '';

        if (empty($this->clientId) || empty($this->clientSecret)) {
            throw new RuntimeException("PayPal credentials are missing for this website.");
        }
    }

    /**
     * Get OAuth2 Access Token from PayPal
     */
    protected function getAccessToken(): string
    {
        $response = Http::asForm()
            ->withBasicAuth($this->clientId, $this->clientSecret)
            ->post("{$this->baseUrl}/v1/oauth2/token", [
                'grant_type' => 'client_credentials',
            ]);

        if (!$response->successful() || !isset($response['access_token'])) {
            throw new RuntimeException("PayPal Authentication failed: " . ($response['error_description'] ?? 'Invalid credentials'));
        }

        return $response['access_token'];
    }

    /**
     * Create PayPal v2 Order and return approval link
     */
    public function createOrder(Transaction $transaction): string
    {
        $token = $this->getAccessToken();

        $returnUrl = route('checkout.callback', ['paypal', 'success', $transaction->session_token]);
        $cancelUrl = route('checkout.callback', ['paypal', 'cancel', $transaction->session_token]);

        $response = Http::withToken($token)
            ->post("{$this->baseUrl}/v2/checkout/orders", [
                'intent' => 'CAPTURE',
                'purchase_units' => [[
                    'reference_id' => $transaction->transaction_uuid,
                    'description' => "Order #{$transaction->order_id} - {$transaction->website->name}",
                    'amount' => [
                        'currency_code' => $transaction->currency,
                        'value' => number_format((float) $transaction->amount, 2, '.', ''),
                    ],
                ]],
                'application_context' => [
                    'brand_name' => $transaction->website->name,
                    'return_url' => $returnUrl,
                    'cancel_url' => $cancelUrl,
                    'user_action' => 'PAY_NOW',
                ],
            ]);

        $body = $response->json();

        if (!$response->successful() || !isset($body['links'])) {
            throw new RuntimeException("PayPal Order creation failed: " . json_encode($body));
        }

        foreach ($body['links'] as $link) {
            if ($link['rel'] === 'approve') {
                return $link['href'];
            }
        }

        throw new RuntimeException("No PayPal approval link found in response.");
    }

    /**
     * Capture PayPal Order upon return
     */
    public function captureOrder(string $orderId): array
    {
        $token = $this->getAccessToken();

        $response = Http::withToken($token)
            ->withHeaders(['Content-Type' => 'application/json'])
            ->send('POST', "{$this->baseUrl}/v2/checkout/orders/{$orderId}/capture");

        $body = $response->json() ?? [];

        return [
            'success' => ($response->successful() && ($body['status'] ?? '') === 'COMPLETED'),
            'raw' => $body,
        ];
    }
}
