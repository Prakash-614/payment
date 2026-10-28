<?php

namespace App\Services;

use App\Models\Transaction;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WebhookService
{
    /**
     * Dispatch an HMAC-SHA256 signed webhook notification to the merchant.
     */
    public static function dispatch(Transaction $transaction, string $event = 'payment.succeeded'): void
    {
        $website = $transaction->website;

        if (empty($website->webhook_url)) {
            return;
        }

        // 1. Prepare plain data (scalars only, no Eloquent models)
        $webhookUrl = (string) $website->webhook_url;
        $merchantName = (string) $website->name;
        $secretKey = (string) $website->secret_key;
        $transactionUuid = (string) $transaction->transaction_uuid;

        $payload = [
            'event' => $event,
            'timestamp' => now()->toIso8601String(),
            'data' => [
                'transaction_id' => $transactionUuid,
                'order_id' => (string) $transaction->order_id,
                'amount' => (float) $transaction->amount,
                'currency' => (string) $transaction->currency,
                'gateway' => (string) $transaction->gateway,
                'status' => (string) $transaction->status,
                'customer_name' => (string) $transaction->customer_name,
                'customer_email' => (string) $transaction->customer_email,
            ],
        ];

        $jsonPayload = json_encode($payload, JSON_UNESCAPED_SLASHES);
        $signature = hash_hmac('sha256', $jsonPayload, $secretKey);

        // 2. Prevent local deadlocks when webhook_url is the same local artisan serve port
        $isLocalSelfCall = str_contains($webhookUrl, '127.0.0.1:8000') || str_contains($webhookUrl, 'localhost:8000');

        if ($isLocalSelfCall) {
            Log::info("Skipped self-calling webhook to 127.0.0.1:8000 on single-threaded dev server (prevents deadlock). For local testing, use a webhook.site URL.");
            return;
        }

        // 3. Send HTTP request safely
        try {
            $response = Http::timeout(6)
                ->withHeaders([
                    'Content-Type' => 'application/json',
                    'User-Agent' => 'GTech-Payment-Webhook/1.0',
                    'X-GTech-Signature' => $signature,
                ])
                ->withBody($jsonPayload, 'application/json')
                ->post($webhookUrl);

            Log::info("Webhook delivered to [{$merchantName}]", [
                'url' => $webhookUrl,
                'status' => $response->status(),
                'transaction' => $transactionUuid,
            ]);
        } catch (\Throwable $e) {
            Log::error("Webhook delivery failed for [{$merchantName}]", [
                'url' => $webhookUrl,
                'error' => $e->getMessage(),
            ]);
        }
    }
}