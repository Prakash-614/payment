<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Website;
use App\Services\Gateways\PayPalService;
use App\Services\Gateways\StripeService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use NepalPay\Gateway\Facades\NepalPayment;

class CheckoutController extends Controller
{
    /**
     * Called by The Soaltee Kathmandu's SDK to initiate checkout session.
     */
    public function createSession(Request $request)
    {
        $request->validate([
            'key' => 'required|string',
            'amount' => 'required|numeric|min:0.5',
            'currency' => 'nullable|string|max:10',
            'order_id' => 'nullable|string',
            'customer_name' => 'nullable|string',
            'customer_email' => 'nullable|email',
            'return_url' => 'required|url',
            'cancel_url' => 'required|url',
        ]);

        $website = Website::where('public_key', $request->key)
            ->where('is_active', true)
            ->first();

        if (!$website) {
            return response()->json(['error' => 'Invalid or inactive merchant public key.'], 401);
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
        ]);
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
     * Handle return from PayPal / Stripe after customer pays or cancels.
     */
    /**
     * Handle return from PayPal, Stripe, eSewa, or Khalti after customer pays or cancels.
     */
    public function callback(Request $request, string $gateway, string $action, string $token)
    {
        $transaction = Transaction::with(['website.gatewaySettings'])
            ->where('session_token', $token)
            ->firstOrFail();

        if ($action === 'cancel') {
            $transaction->update([
                'status' => 'cancelled',
                'gateway_response' => ['event' => 'user_cancelled', 'query' => $request->query()],
            ]);
            return redirect()->away($transaction->cancel_url . (str_contains($transaction->cancel_url, '?') ? '&' : '?') . 'status=cancelled&order_id=' . $transaction->order_id);
        }

        $setting = $transaction->website->getSettingFor($gateway);
        $isSuccess = false;
        $gatewayResponse = [];

        try {
            if ($gateway === 'paypal') {
                $orderId = $request->query('token'); // PayPal passes order ID as ?token=
                if ($orderId) {
                    $paypal = new PayPalService($setting);
                    $result = $paypal->captureOrder($orderId);
                    $isSuccess = (bool) ($result['success'] ?? false);
                    $gatewayResponse = $result['raw'] ?? [];
                }
            } elseif ($gateway === 'stripe') {
                $sessionId = $request->query('session_id');
                if ($sessionId) {
                    $stripe = new StripeService($setting);
                    $result = $stripe->verifySession($sessionId);
                    $isSuccess = (bool) ($result['success'] ?? false);
                    $gatewayResponse = $result['raw'] ?? [];
                }
            } elseif (in_array($gateway, ['esewa', 'khalti'])) {
                // Uses the driver already inside your laravel-gateway package
                $driver = \NepalPay\Gateway\Facades\NepalPayment::driver($gateway);
                $result = $driver->handleReturn($request);
                $isSuccess = (bool) $result->success;
                $gatewayResponse = $result->raw;
            }
        } catch (\Throwable $e) {
            $isSuccess = false;
            $gatewayResponse = ['error' => $e->getMessage()];
        }

        if ($isSuccess) {
            $transaction->update([
                'status' => 'completed',
                'gateway_response' => $gatewayResponse,
            ]);
            $redirectTarget = $transaction->return_url;
            $delim = str_contains($redirectTarget, '?') ? '&' : '?';
            return redirect()->away("{$redirectTarget}{$delim}status=success&order_id={$transaction->order_id}&trx={$transaction->transaction_uuid}");
        }

        $transaction->update([
            'status' => 'failed',
            'gateway_response' => $gatewayResponse,
        ]);
        $redirectTarget = $transaction->cancel_url;
        $delim = str_contains($redirectTarget, '?') ? '&' : '?';
        return redirect()->away("{$redirectTarget}{$delim}status=failed&order_id={$transaction->order_id}");
    }
}
