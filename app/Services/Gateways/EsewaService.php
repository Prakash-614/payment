<?php

namespace App\Services\Gateways;

use App\Models\Transaction;
use App\Models\WebsiteGatewaySetting;
use Illuminate\Support\Facades\Http;
use RuntimeException;

class EsewaService
{
    protected string $formUrl;
    protected string $statusUrl;
    protected string $merchantCode;
    protected string $secretKey;

    public function __construct(WebsiteGatewaySetting $setting)
    {
        $mode = $setting->mode; // 'sandbox' or 'live'
        $credentials = $setting->getCredentials($mode);

        if ($mode === 'live') {
            $this->formUrl = 'https://epay.esewa.com.np/api/epay/main/v2/form';
            $this->statusUrl = 'https://esewa.com.np/api/epay/transaction/status/';
            $this->merchantCode = $credentials['merchant_code'] ?? '';
            $this->secretKey = $credentials['secret_key'] ?? '';
        } else {
            // eSewa Public Sandbox defaults if not filled
            $this->formUrl = 'https://rc-epay.esewa.com.np/api/epay/main/v2/form';
            $this->statusUrl = 'https://rc.esewa.com.np/api/epay/transaction/status/';
            $this->merchantCode = !empty($credentials['merchant_code']) ? $credentials['merchant_code'] : 'EPAYTEST';
            $this->secretKey = !empty($credentials['secret_key']) ? $credentials['secret_key'] : '8gBm/:&EnhH.1/q';
        }

        if (empty($this->merchantCode) || empty($this->secretKey)) {
            throw new RuntimeException("eSewa credentials are missing for this website.");
        }
    }

    /**
     * Build the form fields and signature for eSewa v2
     */
    public function getFormData(Transaction $transaction): array
    {
        $amount = number_format((float) $transaction->amount, 2, '.', '');
        $productCode = $this->merchantCode;
        $transactionUuid = $transaction->transaction_uuid;

        // eSewa v2 signature format: total_amount,transaction_uuid,product_code
        $signedFieldNames = 'total_amount,transaction_uuid,product_code';
        $message = "total_amount={$amount},transaction_uuid={$transactionUuid},product_code={$productCode}";
        $signature = base64_encode(hash_hmac('sha256', $message, $this->secretKey, true));

        $successUrl = route('checkout.callback', ['esewa', 'success', $transaction->session_token]);
        $failureUrl = route('checkout.callback', ['esewa', 'cancel', $transaction->session_token]);

        return [
            'action' => $this->formUrl,
            'fields' => [
                'amount' => $amount,
                'tax_amount' => '0',
                'total_amount' => $amount,
                'transaction_uuid' => $transactionUuid,
                'product_code' => $productCode,
                'product_service_charge' => '0',
                'product_delivery_charge' => '0',
                'success_url' => $successUrl,
                'failure_url' => $failureUrl,
                'signed_field_names' => $signedFieldNames,
                'signature' => $signature,
            ],
        ];
    }

    /**
     * Verify transaction with eSewa's server-to-server status API
     */
    public function verify(Transaction $transaction): array
    {
        $amount = number_format((float) $transaction->amount, 2, '.', '');

        $response = Http::timeout(10)->get($this->statusUrl, [
            'product_code' => $this->merchantCode,
            'total_amount' => $amount,
            'transaction_uuid' => $transaction->transaction_uuid,
        ]);

        $body = $response->json() ?? [];
        $status = $body['status'] ?? 'FAILED';

        return [
            'success' => ($response->successful() && $status === 'COMPLETE'),
            'raw' => $body,
        ];
    }
}