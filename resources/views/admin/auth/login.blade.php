<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Login | GTech Payment Orchestrator</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=JetBrains+Mono:wght@500;700&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; }
        body {
            margin: 0;
            padding: 24px;
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, sans-serif;
            background: #030712;
            color: #f8fafc;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
        }

        /* Ambient Glass Orbs */
        .ambient-orb {
            position: fixed; border-radius: 50%; filter: blur(110px); z-index: 0; pointer-events: none; opacity: 0.55;
        }
        .orb-1 { width: 500px; height: 500px; background: radial-gradient(circle, #6366f1, transparent 70%); top: -120px; left: -120px; }
        .orb-2 { width: 450px; height: 450px; background: radial-gradient(circle, #0ea5e9, transparent 70%); bottom: -100px; right: -100px; }

        .login-container {
            width: 100%;
            max-width: 440px;
            position: relative;
            z-index: 1;
        }

        .glass-card {
            background: rgba(15, 23, 42, 0.55);
            backdrop-filter: blur(24px) saturate(180%);
            -webkit-backdrop-filter: blur(24px) saturate(180%);
            border: 1px solid rgba(255, 255, 255, 0.12);
            border-radius: 26px;
            padding: 44px 36px;
            box-shadow: 0 30px 60px -15px rgba(0, 0, 0, 0.7), inset 0 1px 1px rgba(255, 255, 255, 0.2);
            position: relative;
            overflow: hidden;
        }

        .glass-card::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0;
            height: 2px;
            background: linear-gradient(90deg, #6366f1, #38bdf8, #a855f7);
            box-shadow: 0 0 15px rgba(99, 102, 241, 0.6);
        }

        .brand-header {
            text-align: center;
            margin-bottom: 32px;
        }

        .brand-logo {
            width: 56px; height: 56px;
            background: linear-gradient(135deg, rgba(99, 102, 241, 0.8), rgba(14, 165, 233, 0.6));
            border: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: 16px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-weight: 800;
            font-size: 20px;
            box-shadow: 0 10px 30px rgba(99, 102, 241, 0.4);
            margin-bottom: 16px;
        }

        h1 {
            margin: 0;
            font-size: 22px;
            font-weight: 800;
            letter-spacing: -0.02em;
            color: #ffffff;
        }

        .subtitle {
            margin: 6px 0 0;
            font-size: 13px;
            color: #94a3b8;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            font-size: 13px;
            font-weight: 600;
            color: #cbd5e1;
            margin-bottom: 8px;
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
        }

        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 13px 16px 13px 46px;
            background: rgba(255, 255, 255, 0.04);
            border: 1.5px solid rgba(255, 255, 255, 0.09);
            border-radius: 12px;
            font-size: 14px;
            font-family: inherit;
            color: #ffffff;
            transition: all 0.2s;
        }

        input:focus {
            outline: none;
            background: rgba(255, 255, 255, 0.07);
            border-color: #6366f1;
            box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.2), 0 0 20px rgba(99, 102, 241, 0.25);
        }

        input::placeholder { color: #475569; }

        .checkbox-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 26px;
            font-size: 13px;
        }

        .checkbox-label {
            display: flex;
            align-items: center;
            gap: 8px;
            color: #94a3b8;
            cursor: pointer;
            user-select: none;
        }

        .checkbox-label input[type="checkbox"] {
            accent-color: #6366f1;
            width: 16px;
            height: 16px;
            cursor: pointer;
        }

        .btn-submit {
            width: 100%;
            padding: 14px 22px;
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
            gap: 8px;
            box-shadow: 0 10px 25px -5px rgba(79, 70, 229, 0.5), inset 0 1px 1px rgba(255, 255, 255, 0.4);
            transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 15px 35px -5px rgba(79, 70, 229, 0.7);
        }

        .error-banner {
            background: rgba(239, 68, 68, 0.15);
            border: 1px solid rgba(239, 68, 68, 0.3);
            color: #fca5a5;
            padding: 12px 16px;
            border-radius: 12px;
            font-size: 13px;
            margin-bottom: 22px;
        }

        .status-banner {
            background: rgba(16, 185, 129, 0.15);
            border: 1px solid rgba(52, 211, 153, 0.3);
            color: #6ee7b7;
            padding: 12px 16px;
            border-radius: 12px;
            font-size: 13px;
            margin-bottom: 22px;
        }
    </style>
</head>
<body>
    <div class="ambient-orb orb-1"></div>
    <div class="ambient-orb orb-2"></div>

    <div class="login-container">
        <div class="glass-card">
            <div class="brand-header">
                <div class="brand-logo">GT</div>
                <h1>Payment Orchestrator</h1>
                <p class="subtitle">Sign in to manage merchants, keys & gateway routing</p>
            </div>

            @if(session('status'))
                <div class="status-banner">&#x2714; {{ session('status') }}</div>
            @endif

            @if($errors->any())
                <div class="error-banner">
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('admin.login.submit') }}">
                @csrf

                <div class="form-group">
                    <label for="email">Admin Email</label>
                    <div class="input-wrapper">
                        <div class="input-icon">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <rect width="20" height="16" x="2" y="4" rx="2"/>
                                <path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/>
                            </svg>
                        </div>
                        <input type="email" id="email" name="email" value="{{ old('email') }}" placeholder="admin@gtechvision.com" required autofocus>
                    </div>
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <div class="input-wrapper">
                        <div class="input-icon">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <rect width="18" height="11" x="3" y="11" rx="2" ry="2"/>
                                <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                            </svg>
                        </div>
                        <input type="password" id="password" name="password" placeholder="••••••••••••" required>
                    </div>
                </div>

                <div class="checkbox-row">
                    <label class="checkbox-label">
                        <input type="checkbox" name="remember" value="1" {{ old('remember') ? 'checked' : '' }}>
                        <span>Remember me for 30 days</span>
                    </label>
                </div>

                <button type="submit" class="btn-submit">
                    <span>Sign In to Dashboard</span>
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M5 12h14"/>
                        <path d="m12 5 7 7-7 7"/>
                    </svg>
                </button>
            </form>
        </div>
    </div>
</body>
</html>