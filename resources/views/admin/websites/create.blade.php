<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Register Client Website | GTech Payment Orchestrator</title>
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
            padding: 40px 20px;
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, sans-serif;
            background: #030712;
            color: #f8fafc;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow-x: hidden;
        }

        /* Ambient Glassmorphism Mesh Lights */
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
            background: radial-gradient(circle, #0ea5e9, transparent 70%);
            bottom: -80px;
            right: -80px;
        }

        .orb-3 {
            width: 380px;
            height: 380px;
            background: radial-gradient(circle, #a855f7, transparent 70%);
            top: 40%;
            left: 60%;
        }

        .container {
            width: 100%;
            max-width: 640px;
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
            transition: color 0.15s ease;
        }

        .nav-back:hover {
            color: #ffffff;
        }

        /* Master Glass Card */
        .glass-card {
            background: rgba(15, 23, 42, 0.5);
            backdrop-filter: blur(24px) saturate(180%);
            -webkit-backdrop-filter: blur(24px) saturate(180%);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 26px;
            padding: 44px 38px;
            box-shadow: 0 30px 60px -15px rgba(0, 0, 0, 0.6), inset 0 1px 1px rgba(255, 255, 255, 0.2);
            position: relative;
            overflow: hidden;
        }

        .glass-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 2px;
            background: linear-gradient(90deg, #6366f1, #38bdf8, #a855f7);
            box-shadow: 0 0 15px rgba(99, 102, 241, 0.6);
        }

        .header {
            display: flex;
            align-items: center;
            gap: 16px;
            margin-bottom: 30px;
        }

        .icon-box {
            width: 52px;
            height: 52px;
            background: linear-gradient(135deg, rgba(99, 102, 241, 0.25), rgba(14, 165, 233, 0.2));
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #a5b4fc;
            flex-shrink: 0;
            box-shadow: 0 8px 25px rgba(99, 102, 241, 0.3);
        }

        .title-area h1 {
            margin: 0;
            font-size: 22px;
            font-weight: 800;
            letter-spacing: -0.02em;
            color: #ffffff;
            text-shadow: 0 2px 10px rgba(0, 0, 0, 0.4);
        }

        .title-area p {
            margin: 4px 0 0;
            font-size: 13px;
            color: #94a3b8;
        }

        /* Glass Step Pills */
        .step-pills {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 32px;
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid rgba(255, 255, 255, 0.07);
            backdrop-filter: blur(12px);
            border-radius: 14px;
            padding: 6px;
            box-shadow: inset 0 1px 1px rgba(255, 255, 255, 0.05);
        }

        .step-pill {
            flex: 1;
            text-align: center;
            font-size: 12px;
            font-weight: 700;
            padding: 9px;
            border-radius: 10px;
            color: #64748b;
            transition: all 0.2s;
        }

        .step-pill.active {
            background: linear-gradient(135deg, rgba(99, 102, 241, 0.3), rgba(14, 165, 233, 0.2));
            color: #fff;
            border: 1px solid rgba(255, 255, 255, 0.2);
            box-shadow: 0 4px 15px rgba(99, 102, 241, 0.25), inset 0 1px 1px rgba(255, 255, 255, 0.3);
        }

        .form-group {
            margin-bottom: 22px;
        }

        label {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 13px;
            font-weight: 600;
            color: #cbd5e1;
            margin-bottom: 8px;
        }

        label .label-tip {
            font-size: 11px;
            font-weight: 500;
            color: #64748b;
        }

        .input-wrapper {
            position: relative;
            display: flex;
            align-items: center;
        }

        .input-icon {
            position: absolute;
            left: 16px;
            color: #64748b;
            pointer-events: none;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        input {
            width: 100%;
            padding: 14px 16px 14px 48px;
            background: rgba(255, 255, 255, 0.04);
            border: 1.5px solid rgba(255, 255, 255, 0.09);
            border-radius: 14px;
            font-size: 14px;
            font-family: inherit;
            color: #ffffff;
            transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1);
        }

        input:focus {
            outline: none;
            background: rgba(255, 255, 255, 0.07);
            border-color: #6366f1;
            box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.25), 0 0 25px rgba(99, 102, 241, 0.3);
        }

        input::placeholder {
            color: #475569;
        }

        .domain-preview {
            margin-top: 8px;
            font-size: 12px;
            color: #94a3b8;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .domain-preview span {
            color: #38bdf8;
            font-family: 'JetBrains Mono', monospace;
            background: rgba(56, 189, 248, 0.1);
            border: 1px solid rgba(56, 189, 248, 0.25);
            padding: 2px 8px;
            border-radius: 6px;
        }

        /* Glass Glow Submit Button */
        .submit-btn {
            width: 100%;
            margin-top: 30px;
            padding: 15px 22px;
            background: linear-gradient(135deg, rgba(99, 102, 241, 0.9), rgba(79, 70, 229, 0.95));
            color: #ffffff;
            border: 1px solid rgba(255, 255, 255, 0.25);
            border-radius: 14px;
            font-size: 14px;
            font-weight: 700;
            font-family: inherit;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            box-shadow: 0 10px 25px -5px rgba(79, 70, 229, 0.5), inset 0 1px 1px rgba(255, 255, 255, 0.4);
            transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .submit-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 15px 35px -5px rgba(79, 70, 229, 0.7), 0 0 25px rgba(99, 102, 241, 0.4);
        }

        .submit-btn:active {
            transform: translateY(0);
        }

        .error-banner {
            background: rgba(239, 68, 68, 0.15);
            border: 1px solid rgba(239, 68, 68, 0.3);
            color: #fca5a5;
            padding: 12px 18px;
            border-radius: 12px;
            font-size: 13px;
            margin-bottom: 24px;
            box-shadow: 0 0 20px rgba(239, 68, 68, 0.15);
        }
    </style>
</head>

<body>
    <!-- Ambient Glass Lights -->
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

        <div class="glass-card">
            <div class="header">
                <div class="icon-box">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10" />
                        <path d="M12 2a14.5 14.5 0 0 0 0 20 14.5 14.5 0 0 0 0-20" />
                        <path d="M2 12h20" />
                    </svg>
                </div>
                <div class="title-area">
                    <h1>Register Client Website</h1>
                    <p>Onboard a merchant site to start processing multi-currency payments</p>
                </div>
            </div>

            <!-- Glass Step Progress Bar -->
            <div class="step-pills">
                <div class="step-pill active">1. Website Registration</div>
                <div class="step-pill">2. Gateways & Keys</div>
                <div class="step-pill">3. Snippet Ready</div>
            </div>

            @if ($errors->any())
                <div class="error-banner">
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('admin.websites.store') }}">
                @csrf

                <!-- Website Name -->
                <div class="form-group">
                    <label>
                        Client / Website Name
                        <span class="label-tip">e.g. The Soaltee Kathmandu, Himalayan Treks</span>
                    </label>
                    <div class="input-wrapper">
                        <div class="input-icon">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M6 22V4a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v18Z" />
                                <path d="M6 12H4a2 2 0 0 0-2 2v6a2 2 0 0 0 2 2h2" />
                                <path d="M18 9h2a2 2 0 0 1 2 2v9a2 2 0 0 1-2 2h-2" />
                                <path d="M10 6h4" />
                                <path d="M10 10h4" />
                                <path d="M10 14h4" />
                                <path d="M10 18h4" />
                            </svg>
                        </div>
                        <input type="text" name="name" value="{{ old('name') }}"
                            placeholder="The Soaltee Kathmandu" required autofocus>
                    </div>
                </div>

                <!-- Domain -->
                <div class="form-group">
                    <label>
                        Authorized Domain
                        <span class="label-tip">Domain whitelisted for CORS & security</span>
                    </label>
                    <div class="input-wrapper">
                        <div class="input-icon">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="12" cy="12" r="10" />
                                <path d="m4.93 4.93 4.24 4.24" />
                                <path d="m14.83 9.17 4.24-4.24" />
                                <path d="m14.83 14.83 4.24 4.24" />
                                <path d="m9.17 14.83-4.24 4.24" />
                                <circle cx="12" cy="12" r="4" />
                            </svg>
                        </div>
                        <input type="text" id="domain-input" name="domain" value="{{ old('domain') }}"
                            placeholder="hotelinn.com" required>
                    </div>
                    <div class="domain-preview" id="domain-preview" style="display: none;">
                        Clean origin: <span id="preview-text"></span>
                    </div>
                </div>

                <!-- Webhook URL -->
                <div class="form-group">
                    <label>
                        Server-to-Server Webhook URL
                        <span class="label-tip">Optional &middot; Receives payment notifications</span>
                    </label>
                    <div class="input-wrapper">
                        <div class="input-icon">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9" />
                                <path d="M13.73 21a2 2 0 0 1-3.46 0" />
                            </svg>
                        </div>
                        <input type="url" name="webhook_url" value="{{ old('webhook_url') }}"
                            placeholder="https://hotelinn.com/api/payment-webhook">
                    </div>
                </div>

                <!-- Submit Button -->
                <button type="submit" class="submit-btn">
                    <span>Create & Configure Payment Gateways</span>
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M5 12h14" />
                        <path d="m12 5 7 7-7 7" />
                    </svg>
                </button>
            </form>
        </div>
    </div>

    <script>
        const domainInput = document.getElementById('domain-input');
        const domainPreview = document.getElementById('domain-preview');
        const previewText = document.getElementById('preview-text');

        domainInput.addEventListener('input', (e) => {
            let val = e.target.value.trim().replace(/^https?:\/\//i, '').replace(/\/.*$/, '');
            if (val.length > 2) {
                previewText.textContent = val;
                domainPreview.style.display = 'flex';
            } else {
                domainPreview.style.display = 'none';
            }
        });
    </script>
</body>

</html>
