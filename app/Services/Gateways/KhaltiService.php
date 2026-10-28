<?php

namespace App\Services\Gateways;

use App\Models\Transaction;
use App\Models\WebsiteGatewaySetting;
use Illuminate\Support\Facades\Http;
use RuntimeException;

class KhaltiService
{
    protected string $initiateUrl;
    protected string $lookupUrl;
    protected string $secretKey;

    public function __construct(WebsiteGatewaySetting $setting)
    {
        $mode = $setting->mode; // 'sandbox' or 'live'
        $credentials = $setting->getCredentials($mode);

        $this->initiateUrl = $mode === 'live'
            ? 'https://khalti.com/api/v2/epayment/initiate/'
            : 'https://dev.khalti.com/api/v2/epayment/initiate/';

        $this->lookupUrl = $mode === 'live'
            ? 'https://khalti.com/api/v2/epayment/lookup/'
            : 'https://dev.khalti.com/api/v2/epayment/lookup/';

        $this->secretKey = $credentials['secret_key'] ?? '';

        if (empty($this->secretKey)) {
            throw new RuntimeException("Khalti Secret Key is missing for this website.");
        }
    }

    /**
     * Initiate payment with Khalti and return payment_url
     */
    public function initiate(Transaction $transaction): string
    {
        // Khalti amounts are in Paisa (1 NPR = 100 Paisa)
        $amountInPaisa = (int) round(((float) $transaction->amount) * 100);

        $returnUrl = route('checkout.callback', ['khalti', 'success', $transaction->session_token]);

        $response = Http::timeout(10)
            ->withHeaders([
                'Authorization' => 'key ' . $this->secretKey,
            ])
            ->post($this->initiateUrl, [
                'return_url' => $returnUrl,
                'website_url' => "https://{$transaction->website->domain}",
                'amount' => $amountInPaisa,
                'purchase_order_id' => $transaction->transaction_uuid,
                'purchase_order_name' => "Order #{$transaction->order_id}",
                'customer_info' => [
                    'name' => $transaction->customer_name ?: 'Valued Guest',
                    'email' => $transaction->customer_email ?: 'guest@hotelinn.com',
                ],
            ]);

        $body = $response->json();

        if (!$response->successful() || empty($body['payment_url'])) {
            throw new RuntimeException("Khalti initiation failed: " . ($body['detail'] ?? json_encode($body)));
        }

        // Save Khalti's pidx in gateway_response for lookup later
        $transaction->update([
            'gateway_response' => ['pidx' => $body['pidx']],
        ]);

        return $body['payment_url'];
    }

    /**
     * Verify payment status using Khalti's lookup API
     */
    public function verify(string $pidx, Transaction $transaction): array
    {
        $response = Http::timeout(10)
            ->withHeaders([
                'Authorization' => 'key ' . $this->secretKey,
            ])
            ->post($this->lookupUrl, [
                'pidx' => $pidx,
            ]);

        $body = $response->json() ?? [];
        $status = $body['status'] ?? 'Failed';

        // Check if amount matches
        $paidPaisa = $body['total_amount'] ?? 0;
        $expectedPaisa = (int) round(((float) $transaction->amount) * 100);
        $amountMatches = (abs($paidPaisa - $expectedPaisa) <= 5);

        $isSuccess = ($response->successful() && $status === 'Completed' && $amountMatches);

        return [
            'success' => $isSuccess,
            'raw' => $body,
        ];
    }
}