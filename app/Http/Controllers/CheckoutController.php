<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Website;
use App\Services\Gateways\PayPalService;
use App\Services\Gateways\StripeService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use NepalPay\Gateway\Facades\NepalPayment;
use App\Services\WebhookService;
use App\Services\Gateways\EsewaService;
use App\Services\Gateways\KhaltiService;

class CheckoutController extends Controller
{
    /**
     * Called by The Soaltee Kathmandu's SDK to initiate checkout session.
     */
   public function createSession(Request $request)
    {
        $corsHeaders = [
            'Access-Control-Allow-Origin' => '*',
            'Access-Control-Allow-Methods' => 'POST, GET, OPTIONS',
            'Access-Control-Allow-Headers' => 'Content-Type, Authorization, Accept, X-Requested-With, ngrok-skip-browser-warning',
        ];

        if ($request->isMethod('OPTIONS')) {
            return response('', 200, $corsHeaders);
        }

        // 2. Validate with custom error return
        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
            'key' => 'required|string',
            'amount' => 'required|numeric|min:0.5',
            'currency' => 'nullable|string|max:10',
            'order_id' => 'nullable|string',
            'customer_name' => 'nullable|string',
            'customer_email' => 'nullable',
            'return_url' => 'required',
            'cancel_url' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => 'Validation error: ' . implode(', ', $validator->errors()->all())
            ], 422)->header('Access-Control-Allow-Origin', '*');
        }

        $website = Website::where('public_key', $request->key)
            ->where('is_active', true)
            ->first();

        if (!$website) {
            return response()->json([
                'error' => 'Invalid or inactive merchant key [' . $request->key . ']. Check admin dashboard.'
            ], 401)->header('Access-Control-Allow-Origin', '*');
        }

        $sessionToken = 'cs_' . Str::random(40);
        $transactionUuid = (string) Str::uuid();

        Transaction::create([
            'website_id' => $website->id,
            'session_token' => $sessionToken,
            'transaction_uuid' => $transactionUuid,
            'order_id' => $request->order_id,
            'amount' => $request->amount,
            'currency' => strtoupper($request->currency ?? 'USD'),
            'customer_name' => $request->customer_name,
            'customer_email' => $request->customer_email,
            'return_url' => $request->return_url,
            'cancel_url' => $request->cancel_url,
            'status' => 'pending',
        ]);

        return response()->json([
            'checkout_url' => route('checkout.show', $sessionToken),
            'session_token' => $sessionToken,
        ])->header('Access-Control-Allow-Origin', '*');
    }

    /**
     * Hosted checkout page opened by the customer.
     */
    public function show(string $token)
    {
        $transaction = Transaction::with(['website.gatewaySettings'])
            ->where('session_token', $token)
            ->firstOrFail();

        if ($transaction->status !== 'pending') {
            return view('checkout.completed', compact('transaction'));
        }

        $enabledGateways = $transaction->website->gatewaySettings
            ->filter(function ($gw) {
                return (bool) $gw->is_enabled === true;
            })
            ->values();

        return view('checkout.show', compact('transaction', 'enabledGateways'));
    }

    /**
     * Initiate payment redirect to PayPal or Stripe.
     */
    public function process(string $token, string $gateway)
    {
        $transaction = Transaction::with(['website.gatewaySettings'])
            ->where('session_token', $token)
            ->where('status', 'pending')
            ->firstOrFail();

        $setting = $transaction->website->getSettingFor($gateway);

        if (!$setting || !$setting->is_enabled) {
            return back()->with('error', "Gateway [{$gateway}] is not enabled for this merchant.");
        }

        $transaction->update(['gateway' => $gateway]);

        try {
            if ($gateway === 'paypal') {
                $paypal = new PayPalService($setting);
                $redirectUrl = $paypal->createOrder($transaction);
                $transaction->update([
                    'gateway_response' => ['initiation' => 'order_created', 'redirect_url' => $redirectUrl],
                ]);
                return redirect()->away($redirectUrl);
            }

            if ($gateway === 'stripe') {
                $stripe = new StripeService($setting);
                $redirectUrl = $stripe->createCheckoutSession($transaction);
                return redirect()->away($redirectUrl);
            }
            if ($gateway === 'esewa') {
                $esewa = new EsewaService($setting);
                $formData = $esewa->getFormData($transaction);
                return view('checkout.esewa-redirect', $formData);
            }

            if ($gateway === 'khalti') {
                $khalti = new KhaltiService($setting);
                $redirectUrl = $khalti->initiate($transaction);
                return redirect()->away($redirectUrl);
            }

            // Delegate eSewa and Khalti directly to laravel-gateway package drivers
            if (in_array($gateway, ['esewa', 'khalti'])) {
                $driver = NepalPayment::driver($gateway);

                $initiation = $driver->initiate([
                    'amount' => $transaction->amount,
                    'metadata' => [
                        'session_token' => $transaction->session_token,
                        'order_id' => $transaction->order_id,
                    ],
                    'success_url' => route('checkout.callback', [$gateway, 'success', $transaction->session_token]),
                    'failure_url' => route('checkout.callback', [$gateway, 'cancel', $transaction->session_token]),
                ]);

                return view($initiation->view, $initiation->viewData);
            }

            return back()->with('error', "Unsupported gateway [{$gateway}].");
        } catch (\Throwable $e) {
            return response()->view('checkout.error', [
                'message' => $e->getMessage(),
                'transaction' => $transaction,
            ], 500);
        }
    }
    /**
     * Handle return from PayPal / Stripe / eSewa / Khalti after customer pays or cancels.
     */
    public function callback(Request $request, string $gateway, string $action, string $token)
    {
        $transaction = Transaction::with(['website.gatewaySettings'])
            ->where('session_token', $token)
            ->firstOrFail();

        // 1. If customer clicked Cancel on the gateway page
        if ($action === 'cancel') {
            $transaction->update(['status' => 'cancelled']);
            $redirectTarget = $transaction->cancel_url;
            $delim = str_contains($redirectTarget, '?') ? '&' : '?';
            return redirect()->away("{$redirectTarget}{$delim}status=cancelled&order_id={$transaction->order_id}");
        }

        $setting = $transaction->website->getSettingFor($gateway);
        $isSuccess = false;
        $rawResponse = null;

        try {
            // 2. Verify PayPal
            if ($gateway === 'paypal') {
                $orderId = $request->query('token'); // PayPal passes order ID as ?token=
                if ($orderId) {
                    $paypal = new PayPalService($setting);
                    $res = $paypal->captureOrder($orderId);
                    $isSuccess = (bool) ($res['success'] ?? false);
                    $rawResponse = $res['raw'] ?? [];
                }
            }
            // 3. Verify Stripe
            elseif ($gateway === 'stripe') {
                $sessionId = $request->query('session_id');
                if ($sessionId) {
                    $stripe = new StripeService($setting);
                    $res = $stripe->verifySession($sessionId);
                    $isSuccess = (bool) ($res['success'] ?? false);
                    $rawResponse = $res['raw'] ?? [];
                }
            }
            // 4. Verify eSewa
            elseif ($gateway === 'esewa') {
                $esewa = new EsewaService($setting);
                $res = $esewa->verify($transaction);
                $isSuccess = (bool) ($res['success'] ?? false);
                $rawResponse = $res['raw'] ?? [];
            }
            // 5. Verify Khalti
            elseif ($gateway === 'khalti') {
                $pidx = $request->query('pidx') ?? ($transaction->gateway_response['pidx'] ?? null);
                if ($pidx) {
                    $khalti = new KhaltiService($setting);
                    $res = $khalti->verify($pidx, $transaction);
                    $isSuccess = (bool) ($res['success'] ?? false);
                    $rawResponse = $res['raw'] ?? [];
                }
            }
        } catch (\Throwable $e) {
            $isSuccess = false;
            $rawResponse = ['error' => $e->getMessage()];
        }

        // 6. Payment Successful
        if ($isSuccess) {
            $transaction->update([
                'status' => 'completed',
                'gateway_response' => $rawResponse,
            ]);

            // Dispatch webhook via background queue (falls back gracefully if queue worker is not running)
            try {
                \App\Jobs\SendMerchantWebhookJob::dispatch($transaction, 'payment.succeeded');
            } catch (\Throwable $e) {
                \App\Services\WebhookService::dispatch($transaction, 'payment.succeeded');
            }

            $redirectTarget = $transaction->return_url;
            $delim = str_contains($redirectTarget, '?') ? '&' : '?';
            return redirect()->away("{$redirectTarget}{$delim}status=success&order_id={$transaction->order_id}&trx={$transaction->transaction_uuid}");
        }

        // 7. Payment Failed
        $transaction->update([
            'status' => 'failed',
            'gateway_response' => $rawResponse,
        ]);

        $redirectTarget = $transaction->cancel_url;
        $delim = str_contains($redirectTarget, '?') ? '&' : '?';
        return redirect()->away("{$redirectTarget}{$delim}status=failed&order_id={$transaction->order_id}");
    }
}