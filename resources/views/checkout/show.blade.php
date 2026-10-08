<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Pay to {{ $transaction->website->name }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=JetBrains+Mono:wght@500;700&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; }
        body {
            margin: 0; padding: 40px 20px;
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, sans-serif;
            background: #030712; color: #f8fafc;
            min-height: 100vh; display: flex; align-items: center; justify-content: center;
            position: relative; overflow-x: hidden;
        }
        .ambient-orb {
            position: fixed; border-radius: 50%; filter: blur(100px); z-index: 0; pointer-events: none; opacity: 0.55;
        }
        .orb-1 { width: 500px; height: 500px; background: radial-gradient(circle, #6366f1, transparent 70%); top: -100px; left: -100px; }
        .orb-2 { width: 450px; height: 450px; background: radial-gradient(circle, #0ea5e9, transparent 70%); bottom: -80px; right: -80px; }

        .container { width: 100%; max-width: 480px; position: relative; z-index: 1; }

        .glass-card {
            background: rgba(15, 23, 42, 0.55);
            backdrop-filter: blur(24px) saturate(180%);
            -webkit-backdrop-filter: blur(24px) saturate(180%);
            border: 1px solid rgba(255, 255, 255, 0.12);
            border-radius: 26px; padding: 36px 32px;
            box-shadow: 0 30px 60px -15px rgba(0, 0, 0, 0.6), inset 0 1px 1px rgba(255, 255, 255, 0.2);
            text-align: center;
        }

        .merchant-badge {
            display: inline-flex; align-items: center; gap: 8px;
            background: rgba(255, 255, 255, 0.05); border: 1px solid rgba(255, 255, 255, 0.1);
            padding: 6px 14px; border-radius: 30px; font-size: 13px; font-weight: 600; color: #cbd5e1;
            margin-bottom: 20px;
        }
        .merchant-avatar {
            width: 22px; height: 22px; background: #6366f1; border-radius: 50%;
            display: flex; align-items: center; justify-content: center; font-size: 11px; font-weight: 800; color: #fff;
        }

        .amount-display {
            font-size: 38px; font-weight: 800; letter-spacing: -0.03em;
            color: #ffffff; margin: 4px 0 6px;
            text-shadow: 0 0 25px rgba(255,255,255,0.25);
        }
        .order-desc { color: #94a3b8; font-size: 13px; margin: 0 0 28px; }

        .section-title {
            text-align: left; font-size: 12px; font-weight: 700; color: #94a3b8;
            text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 12px;
        }

        .payment-methods { display: flex; flex-direction: column; gap: 12px; }

        .btn-payment {
            width: 100%; padding: 16px 20px; border-radius: 14px;
            font-family: inherit; font-size: 15px; font-weight: 700;
            display: flex; align-items: center; justify-content: space-between;
            cursor: pointer; border: 1px solid rgba(255, 255, 255, 0.15);
            transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
            text-decoration: none; color: #fff;
        }
        .btn-payment:hover { transform: translateY(-2px); box-shadow: 0 10px 25px rgba(0,0,0,0.4); }

        .btn-paypal { background: linear-gradient(135deg, #003087, #0079c1); }
        .btn-paypal:hover { box-shadow: 0 10px 25px rgba(0, 121, 193, 0.4); }

        .btn-stripe { background: linear-gradient(135deg, #635bff, #7a73ff); }
        .btn-stripe:hover { box-shadow: 0 10px 25px rgba(99, 91, 255, 0.4); }

        .btn-esewa { background: linear-gradient(135deg, #4d9a37, #60bb46); }
        .btn-khalti { background: linear-gradient(135deg, #482372, #5c2d91); }

        .cancel-link {
            display: inline-block; margin-top: 24px; color: #64748b;
            text-decoration: none; font-size: 13px; font-weight: 600;
            transition: color 0.15s;
        }
        .cancel-link:hover { color: #f87171; }
    </style>
</head>
<body>
    <div class="ambient-orb orb-1"></div>
    <div class="ambient-orb orb-2"></div>

    <div class="container">
        <div class="glass-card">
            <!-- Merchant Brand -->
            <div class="merchant-badge">
                <div class="merchant-avatar">{{ strtoupper(substr($transaction->website->name, 0, 1)) }}</div>
                <span>Paying to {{ $transaction->website->name }}</span>
            </div>

            <!-- Amount -->
            <div class="amount-display">
                {{ $transaction->currency }} {{ number_format($transaction->amount, 2) }}
            </div>
            <p class="order-desc">Order: <strong>{{ $transaction->order_id ?? $transaction->transaction_uuid }}</strong></p>

            <div class="section-title">Select Payment Method</div>

            <div class="payment-methods">
                @forelse($enabledGateways as $gw)
                    @if($gw->gateway === 'paypal')
                        <a href="{{ route('checkout.process', [$transaction->session_token, 'paypal']) }}" class="btn-payment btn-paypal">
                            <span>Pay with PayPal</span>
                            <span style="font-size:12px; opacity:0.8;">Cards & PayPal &rarr;</span>
                        </a>
                    @elseif($gw->gateway === 'stripe')
                        <a href="{{ route('checkout.process', [$transaction->session_token, 'stripe']) }}" class="btn-payment btn-stripe">
                            <span>Pay with Stripe</span>
                            <span style="font-size:12px; opacity:0.8;">Credit / Debit &rarr;</span>
                        </a>
                    @elseif($gw->gateway === 'esewa')
                        <a href="{{ route('checkout.process', [$transaction->session_token, 'esewa']) }}" class="btn-payment btn-esewa">
                            <span>Pay with eSewa</span>
                            <span style="font-size:12px; opacity:0.8;">eSewa Wallet &rarr;</span>
                        </a>
                    @elseif($gw->gateway === 'khalti')
                        <a href="{{ route('checkout.process', [$transaction->session_token, 'khalti']) }}" class="btn-payment btn-khalti">
                            <span>Pay with Khalti</span>
                            <span style="font-size:12px; opacity:0.8;">Khalti Wallet &rarr;</span>
                        </a>
                    @endif
                @empty
                    <div style="background:rgba(239, 68, 68, 0.1); border:1px solid rgba(239, 68, 68, 0.2); padding:16px; border-radius:12px; color:#f87171; font-size:13px;">
                        No payment gateways are currently enabled by {{ $transaction->website->name }}.
                    </div>
                @endforelse
            </div>

            <a href="{{ $transaction->cancel_url }}" class="cancel-link">Cancel and return to {{ $transaction->website->domain }}</a>
        </div>
    </div>
</body>
</html>