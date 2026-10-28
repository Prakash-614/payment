<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Configure {{ $website->name }} | GTech Payment Orchestrator</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=JetBrains+Mono:wght@500;700&display=swap"
        rel="stylesheet">
    <style>
        *,
        *::before,
        *::after {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            padding: 40px 24px;
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, sans-serif;
            background: #030712;
            color: #f8fafc;
            min-height: 100vh;
            position: relative;
            overflow-x: hidden;
        }

        /* Ambient Glass Orbs */
        .ambient-orb {
            position: fixed;
            border-radius: 50%;
            filter: blur(100px);
            z-index: 0;
            pointer-events: none;
            opacity: 0.6;
        }

        .orb-1 {
            width: 500px;
            height: 500px;
            background: radial-gradient(circle, #6366f1, transparent 70%);
            top: -100px;
            left: -100px;
        }

        .orb-2 {
            width: 450px;
            height: 450px;
            background: radial-gradient(circle, #38bdf8, transparent 70%);
            bottom: -80px;
            right: -80px;
        }

        .orb-3 {
            width: 380px;
            height: 380px;
            background: radial-gradient(circle, #8b5cf6, transparent 70%);
            top: 50%;
            left: 65%;
        }

        .container {
            max-width: 1000px;
            margin: 0 auto;
            position: relative;
            z-index: 1;
        }

        .nav-back {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            color: #94a3b8;
            text-decoration: none;
            font-size: 13px;
            font-weight: 600;
            margin-bottom: 24px;
            transition: color 0.15s;
        }

        .nav-back:hover {
            color: #fff;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 28px;
            flex-wrap: wrap;
            gap: 16px;
        }

        .header h1 {
            margin: 0;
            font-size: 26px;
            font-weight: 800;
            letter-spacing: -0.02em;
            text-shadow: 0 2px 10px rgba(0, 0, 0, 0.5);
        }

        .header p {
            margin: 4px 0 0;
            font-size: 13px;
            color: #94a3b8;
        }

        /* Glass Tabs */
        .glass-tabs {
            display: flex;
            gap: 8px;
            background: rgba(255, 255, 255, 0.03);
            backdrop-filter: blur(20px) saturate(180%);
            -webkit-backdrop-filter: blur(20px) saturate(180%);
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 16px;
            padding: 6px;
            margin-bottom: 24px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.3), inset 0 1px 1px rgba(255, 255, 255, 0.1);
        }

        .tab-btn {
            flex: 1;
            padding: 12px 18px;
            border-radius: 12px;
            background: transparent;
            border: none;
            color: #94a3b8;
            font-size: 13px;
            font-weight: 700;
            font-family: inherit;
            cursor: pointer;
            transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .tab-btn:hover {
            color: #fff;
            background: rgba(255, 255, 255, 0.04);
        }

        .tab-btn.active {
            background: linear-gradient(135deg, rgba(99, 102, 241, 0.3), rgba(14, 165, 233, 0.2));
            color: #fff;
            border: 1px solid rgba(255, 255, 255, 0.2);
            box-shadow: 0 4px 20px rgba(99, 102, 241, 0.3), inset 0 1px 1px rgba(255, 255, 255, 0.3);
        }

        /* Glass Cards */
        .glass-card {
            background: rgba(15, 23, 42, 0.5);
            backdrop-filter: blur(24px) saturate(180%);
            -webkit-backdrop-filter: blur(24px) saturate(180%);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 24px;
            padding: 34px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5), inset 0 1px 1px rgba(255, 255, 255, 0.15);
            margin-bottom: 24px;
        }

        .status-banner {
            background: rgba(16, 185, 129, 0.15);
            border: 1px solid rgba(52, 211, 153, 0.3);
            color: #6ee7b7;
            padding: 14px 20px;
            border-radius: 14px;
            margin-bottom: 24px;
            font-size: 13px;
            font-weight: 600;
            box-shadow: 0 0 20px rgba(16, 185, 129, 0.15);
        }

        label {
            display: block;
            font-size: 13px;
            font-weight: 600;
            color: #cbd5e1;
            margin-bottom: 8px;
            margin-top: 20px;
        }

        label:first-of-type {
            margin-top: 0;
        }

        input[type="text"],
        input[type="password"],
        input[type="url"],
        select {
            width: 100%;
            padding: 13px 16px;
            background: rgba(255, 255, 255, 0.04);
            border: 1.5px solid rgba(255, 255, 255, 0.09);
            border-radius: 12px;
            font-size: 14px;
            color: #fff;
            font-family: inherit;
            transition: all 0.2s;
        }

        input:focus,
        select:focus {
            outline: none;
            background: rgba(255, 255, 255, 0.07);
            border-color: #6366f1;
            box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.2), 0 0 20px rgba(99, 102, 241, 0.25);
        }

        input[readonly] {
            background: rgba(0, 0, 0, 0.3);
            color: #94a3b8;
            font-family: 'JetBrains Mono', monospace;
            border-color: rgba(255, 255, 255, 0.05);
        }

        /* Glass Gateway Pods */
        .gateway-pod {
            background: rgba(255, 255, 255, 0.02);
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 20px;
            padding: 26px;
            margin-bottom: 22px;
            box-shadow: inset 0 1px 1px rgba(255, 255, 255, 0.05);
        }

        .gateway-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding-bottom: 18px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.06);
            margin-bottom: 20px;
        }

        .gw-brand {
            display: flex;
            align-items: center;
            gap: 14px;
        }

        .gw-icon {
            width: 44px;
            height: 44px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 800;
            font-size: 15px;
            color: #fff;
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.3);
        }

        .gw-paypal {
            background: linear-gradient(135deg, #003087, #0079c1);
        }

        .gw-stripe {
            background: linear-gradient(135deg, #635bff, #7a73ff);
        }

        /* Glowing Switch */
        .switch {
            position: relative;
            display: inline-block;
            width: 48px;
            height: 26px;
        }

        .switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }

        .slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: rgba(255, 255, 255, 0.1);
            transition: .3s;
            border-radius: 26px;
            border: 1px solid rgba(255, 255, 255, 0.15);
        }

        .slider:before {
            position: absolute;
            content: "";
            height: 18px;
            width: 18px;
            left: 3px;
            bottom: 3px;
            background-color: #fff;
            transition: .3s;
            border-radius: 50%;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.3);
        }

        input:checked+.slider {
            background-color: #22c55e;
            box-shadow: 0 0 15px rgba(34, 197, 94, 0.5);
        }

        input:checked+.slider:before {
            transform: translateX(22px);
        }

        .btn-submit {
            background: linear-gradient(135deg, rgba(99, 102, 241, 0.9), rgba(79, 70, 229, 0.95));
            color: #fff;
            border: 1px solid rgba(255, 255, 255, 0.25);
            padding: 14px 28px;
            border-radius: 14px;
            font-size: 14px;
            font-weight: 700;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            box-shadow: 0 10px 25px -5px rgba(79, 70, 229, 0.5), inset 0 1px 1px rgba(255, 255, 255, 0.4);
            transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 14px 35px -5px rgba(79, 70, 229, 0.7);
        }

        /* Crystal Code Box */
        .crystal-code {
            background: rgba(0, 0, 0, 0.5);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.4), inset 0 1px 1px rgba(255, 255, 255, 0.08);
        }

        .code-top {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: rgba(255, 255, 255, 0.03);
            padding: 14px 20px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.07);
            font-size: 12px;
            color: #94a3b8;
            font-family: 'JetBrains Mono', monospace;
        }

        .btn-copy {
            background: linear-gradient(135deg, rgba(99, 102, 241, 0.3), rgba(14, 165, 233, 0.2));
            border: 1px solid rgba(255, 255, 255, 0.2);
            color: #fff;
            padding: 7px 14px;
            border-radius: 8px;
            font-size: 12px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.2s;
        }

        .btn-copy:hover {
            background: rgba(99, 102, 241, 0.6);
            box-shadow: 0 0 15px rgba(99, 102, 241, 0.4);
        }

        pre {
            margin: 0;
            padding: 22px;
            font-family: 'JetBrains Mono', monospace;
            font-size: 13px;
            color: #38bdf8;
            line-height: 1.6;
            overflow-x: auto;
        }
    </style>
</head>

<body>
    <!-- Background Glass Lights -->
    <div class="ambient-orb orb-1"></div>
    <div class="ambient-orb orb-2"></div>
    <div class="ambient-orb orb-3"></div>

    <div class="container">
        <a href="{{ route('admin.websites.index') }}" class="nav-back">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                stroke-linecap="round" stroke-linejoin="round">
                <path d="m15 18-6-6 6-6" />
            </svg>
            Back to Client Websites
        </a>

        <div class="header">
            <div>
                <h1>{{ $website->name }}</h1>
                <p>Whitelisted Domain: <code
                        style="color:#38bdf8; font-family:'JetBrains Mono', monospace;">{{ $website->domain }}</code>
                </p>
            </div>
        </div>

        @if (session('status'))
            <div class="status-banner">&#x2714; {{ session('status') }}</div>
        @endif

        <!-- Glass Tabs -->
        <div class="glass-tabs">
            <button type="button" class="tab-btn active" onclick="switchTab('gateways')">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                    stroke-width="2">
                    <rect width="20" height="14" x="2" y="5" rx="2" />
                    <line x1="2" x2="22" y1="10" y2="10" />
                </svg>
                Payment Gateways & Keys
            </button>
            <button type="button" class="tab-btn" onclick="switchTab('info')">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                    stroke-width="2">
                    <circle cx="12" cy="12" r="10" />
                    <path d="M12 16v-4" />
                    <path d="M12 8h.01" />
                </svg>
                Website Info & API Keys
            </button>
            <button type="button" class="tab-btn" onclick="switchTab('snippet')">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                    stroke-width="2">
                    <polyline points="16 18 22 12 16 6" />
                    <polyline points="8 6 2 12 8 18" />
                </svg>
                Get Embed Code (Step 5)
            </button>
        </div>

        <form method="POST" action="{{ route('admin.websites.update', $website->id) }}">
            @csrf

            <!-- TAB 1: GATEWAYS -->
            <div id="tab-gateways" class="tab-content">
                <!-- PayPal -->
                <div class="gateway-pod">
                    <div class="gateway-header">
                        <div class="gw-brand">
                            <div class="gw-icon gw-paypal">PP</div>
                            <div>
                                <strong style="font-size:16px;">PayPal</strong>
                                <div style="font-size:12px; color:#94a3b8;">International Credit Cards & PayPal Wallets
                                </div>
                            </div>
                        </div>
                        <label class="switch">
                            <input type="checkbox" name="gateways[paypal][is_enabled]" value="1"
                                {{ $gatewayData['paypal']['setting']->is_enabled ? 'checked' : '' }}>
                            <span class="slider"></span>
                        </label>
                    </div>

                    <div style="display:grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                        <div>
                            <label>Active Mode</label>
                            <select name="gateways[paypal][mode]">
                                <option value="sandbox"
                                    {{ $gatewayData['paypal']['setting']->mode === 'sandbox' ? 'selected' : '' }}>
                                    Sandbox (Testing)</option>
                                <option value="live"
                                    {{ $gatewayData['paypal']['setting']->mode === 'live' ? 'selected' : '' }}>Live
                                    (Production)</option>
                            </select>
                        </div>
                    </div>

                    <label>Sandbox Client ID</label>
                    <input type="text" name="gateways[paypal][sandbox][client_id]"
                        value="{{ $gatewayData['paypal']['sandbox']['client_id'] ?? '' }}" placeholder="A21AA...">
                    <label>Sandbox Secret</label>
                    <input type="password" name="gateways[paypal][sandbox][client_secret]"
                        value="{{ $gatewayData['paypal']['sandbox']['client_secret'] ?? '' }}" placeholder="••••••••">

                    <label style="margin-top:20px;">Live Client ID</label>
                    <input type="text" name="gateways[paypal][live][client_id]"
                        value="{{ $gatewayData['paypal']['live']['client_id'] ?? '' }}"
                        placeholder="Production Client ID">
                    <label>Live Secret</label>
                    <input type="password" name="gateways[paypal][live][client_secret]"
                        value="{{ $gatewayData['paypal']['live']['client_secret'] ?? '' }}" placeholder="••••••••">
                </div>

                <!-- Stripe -->
                <div class="gateway-pod">
                    <div class="gateway-header">
                        <div class="gw-brand">
                            <div class="gw-icon gw-stripe">S</div>
                            <div>
                                <strong style="font-size:16px;">Stripe</strong>
                                <div style="font-size:12px; color:#94a3b8;">Credit/Debit Cards, Apple Pay & Google Pay
                                </div>
                            </div>
                        </div>
                        <label class="switch">
                            <input type="checkbox" name="gateways[stripe][is_enabled]" value="1"
                                {{ $gatewayData['stripe']['setting']->is_enabled ? 'checked' : '' }}>
                            <span class="slider"></span>
                        </label>
                    </div>

                    <div style="display:grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                        <div>
                            <label>Active Mode</label>
                            <select name="gateways[stripe][mode]">
                                <option value="sandbox"
                                    {{ $gatewayData['stripe']['setting']->mode === 'sandbox' ? 'selected' : '' }}>Test
                                    / Sandbox</option>
                                <option value="live"
                                    {{ $gatewayData['stripe']['setting']->mode === 'live' ? 'selected' : '' }}>Live
                                </option>
                            </select>
                        </div>
                    </div>

                    <label>Test Publishable Key</label>
                    <input type="text" name="gateways[stripe][sandbox][publishable_key]"
                        value="{{ $gatewayData['stripe']['sandbox']['publishable_key'] ?? '' }}"
                        placeholder="pk_test_...">
                    <label>Test Secret Key</label>
                    <input type="password" name="gateways[stripe][sandbox][secret_key]"
                        value="{{ $gatewayData['stripe']['sandbox']['secret_key'] ?? '' }}"
                        placeholder="sk_test_...">

                    <label style="margin-top:20px;">Live Publishable Key</label>
                    <input type="text" name="gateways[stripe][live][publishable_key]"
                        value="{{ $gatewayData['stripe']['live']['publishable_key'] ?? '' }}"
                        placeholder="pk_live_...">
                    <label>Live Secret Key</label>
                    <input type="password" name="gateways[stripe][live][secret_key]"
                        value="{{ $gatewayData['stripe']['live']['secret_key'] ?? '' }}" placeholder="sk_live_...">
                </div>
                <!-- eSewa -->
                <div class="gateway-pod">
                    <div class="gateway-header">
                        <div class="gw-brand">
                            <div class="gw-icon" style="background:#60bb46;">eS</div>
                            <div>
                                <strong style="font-size:16px;">eSewa</strong>
                                <div style="font-size:12px; color:#94a3b8;">Nepal's leading digital wallet (NPR)</div>
                            </div>
                        </div>
                        <label class="switch">
                            <input type="checkbox" name="gateways[esewa][is_enabled]" value="1"
                                {{ $gatewayData['esewa']['setting']->is_enabled ? 'checked' : '' }}>
                            <span class="slider"></span>
                        </label>
                    </div>

                    <div style="display:grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                        <div>
                            <label>Active Mode</label>
                            <select name="gateways[esewa][mode]">
                                <option value="sandbox"
                                    {{ $gatewayData['esewa']['setting']->mode === 'sandbox' ? 'selected' : '' }}>
                                    Sandbox (EPAYTEST)</option>
                                <option value="live"
                                    {{ $gatewayData['esewa']['setting']->mode === 'live' ? 'selected' : '' }}>Live
                                    (Production)</option>
                            </select>
                        </div>
                    </div>

                    <label>Merchant Code (Live)</label>
                    <input type="text" name="gateways[esewa][live][merchant_code]"
                        value="{{ $gatewayData['esewa']['live']['merchant_code'] ?? '' }}"
                        placeholder="Your live merchant code">
                    <label>Secret Key (Live)</label>
                    <input type="password" name="gateways[esewa][live][secret_key]"
                        value="{{ $gatewayData['esewa']['live']['secret_key'] ?? '' }}"
                        placeholder="Your live secret key">
                </div>

                <!-- Khalti -->
                <div class="gateway-pod">
                    <div class="gateway-header">
                        <div class="gw-brand">
                            <div class="gw-icon" style="background:#5c2d91;">Kh</div>
                            <div>
                                <strong style="font-size:16px;">Khalti</strong>
                                <div style="font-size:12px; color:#94a3b8;">Khalti Digital Wallet & Mobile Banking
                                    (NPR)</div>
                            </div>
                        </div>
                        <label class="switch">
                            <input type="checkbox" name="gateways[khalti][is_enabled]" value="1"
                                {{ $gatewayData['khalti']['setting']->is_enabled ? 'checked' : '' }}>
                            <span class="slider"></span>
                        </label>
                    </div>

                    <div style="display:grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                        <div>
                            <label>Active Mode</label>
                            <select name="gateways[khalti][mode]">
                                <option value="sandbox"
                                    {{ $gatewayData['khalti']['setting']->mode === 'sandbox' ? 'selected' : '' }}>
                                    Sandbox / Test</option>
                                <option value="live"
                                    {{ $gatewayData['khalti']['setting']->mode === 'live' ? 'selected' : '' }}>Live
                                    (Production)</option>
                            </select>
                        </div>
                    </div>

                    <label>Live Secret Key</label>
                    <input type="password" name="gateways[khalti][live][secret_key]"
                        value="{{ $gatewayData['khalti']['live']['secret_key'] ?? '' }}"
                        placeholder="Live secret key">
                    <label>Sandbox Secret Key</label>
                    <input type="password" name="gateways[khalti][sandbox][secret_key]"
                        value="{{ $gatewayData['khalti']['sandbox']['secret_key'] ?? '' }}"
                        placeholder="Test secret key">
                </div>

                <div style="text-align: right; margin-top: 28px;">
                    <button type="submit" class="btn-submit">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2.5">
                            <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z" />
                            <polyline points="17 21 17 13 7 13 7 21" />
                            <polyline points="7 3 7 8 15 8" />
                        </svg>
                        Save Gateway Credentials
                    </button>
                </div>
            </div>

            <!-- TAB 2: WEBSITE INFO -->
            <div id="tab-info" class="tab-content" style="display: none;">
                <div class="glass-card">
                    <label>Website Name</label>
                    <input type="text" name="name" value="{{ $website->name }}" required>

                    <label>Whitelisted Domain</label>
                    <input type="text" name="domain" value="{{ $website->domain }}" required>

                    <label>Webhook Notification URL</label>
                    <input type="url" name="webhook_url" value="{{ $website->webhook_url }}"
                        placeholder="https://{{ $website->domain }}/api/payment-webhook">

                    <label>Public Client API Key (Embed in Frontend)</label>
                    <input type="text" value="{{ $website->public_key }}" readonly>

                    <label>Secret Server API Key (Never Expose to Frontend)</label>
                    <input type="text" value="{{ $website->secret_key }}" readonly>

                    <label style="margin-top: 24px; display: flex; align-items: center; gap: 10px; cursor: pointer;">
                        <input type="checkbox" name="is_active" value="1"
                            {{ $website->is_active ? 'checked' : '' }} style="width: auto;">
                        <span style="font-weight: 600;">Website Active (Allow processing payments)</span>
                    </label>

                    <div style="text-align: right; margin-top: 28px;">
                        <button type="submit" class="btn-submit">Update Website Settings</button>
                    </div>
                </div>
            </div>
        </form>

        <!-- TAB 3: GET CODE (STEP 5) -->
        <div id="tab-snippet" class="tab-content" style="display: none;">
            <div class="glass-card">
                <h3 style="margin-top: 0; font-size: 20px;">Step 5: Client Embed Code for {{ $website->domain }}</h3>
                <p style="color: #94a3b8; font-size: 13px; line-height: 1.6;">
                    Copy and paste this snippet directly into <strong>{{ $website->name }}</strong>'s checkout or
                    booking confirmation page.
                    When a customer clicks the button, the GTech payment orchestrator handles PayPal or Stripe
                    automatically.
                </p>

                <div class="crystal-code">
                    <div class="code-top">
                        <span>hotelinn_checkout.html</span>
                        <button type="button" class="btn-copy" onclick="copySnippet()">Copy Snippet</button>
                    </div>
                    <pre id="snippet-code">&lt;!-- 1. Include GTech Payment SDK --&gt;
&lt;script src="{{ url('/') }}/sdk.js"&gt;&lt;/script&gt;

&lt;!-- 2. Drop-in Checkout Button --&gt;
&lt;button type="button" id="gtech-pay-btn" style="background:#4f46e5;color:#fff;padding:14px 28px;border:none;border-radius:10px;font-weight:bold;cursor:pointer;"&gt;
    Pay with PayPal or Credit Card
&lt;/button&gt;

&lt;script&gt;
document.getElementById('gtech-pay-btn').addEventListener('click', function () {
    GTechPay.checkout({
        key: '{{ $website->public_key }}',
        amount: 150.00,
        currency: 'USD',
        order_id: 'BOOK-' + Date.now(),
        customer_name: 'John Doe',
        customer_email: 'john@example.com',
        return_url: 'https://{{ $website->domain }}/booking-success',
        cancel_url: 'https://{{ $website->domain }}/booking-cancelled'
    });
});
&lt;/script&gt;</pre>
                </div>
            </div>
        </div>
    </div>

    <script>
        function switchTab(tabId) {
            document.querySelectorAll('.tab-content').forEach(el => el.style.display = 'none');
            document.querySelectorAll('.tab-btn').forEach(el => el.classList.remove('active'));

            document.getElementById('tab-' + tabId).style.display = 'block';
            event.currentTarget.classList.add('active');
        }

        function copySnippet() {
            const text = document.getElementById('snippet-code').innerText;
            navigator.clipboard.writeText(text).then(() => {
                alert('Embed snippet copied to clipboard! Paste it into ' + '{{ $website->domain }}');
            });
        }
    </script>
</body>

</html>
