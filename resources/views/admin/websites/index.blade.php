<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Merchant Websites | GTech Payment Orchestrator</title>
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
            bottom: -50px;
            right: -50px;
        }

        .orb-3 {
            width: 350px;
            height: 350px;
            background: radial-gradient(circle, #a855f7, transparent 70%);
            top: 40%;
            left: 60%;
        }

        .container {
            max-width: 1180px;
            margin: 0 auto;
            position: relative;
            z-index: 1;
        }

        .topbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 32px;
            flex-wrap: wrap;
            gap: 16px;
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 14px;
        }

        .brand-logo {
            width: 48px;
            height: 48px;
            background: linear-gradient(135deg, rgba(99, 102, 241, 0.8), rgba(14, 165, 233, 0.6));
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-weight: 800;
            font-size: 17px;
            box-shadow: 0 8px 32px rgba(99, 102, 241, 0.35);
        }

        .brand-title h1 {
            margin: 0;
            font-size: 24px;
            font-weight: 800;
            letter-spacing: -0.02em;
            text-shadow: 0 2px 10px rgba(0, 0, 0, 0.5);
        }

        .brand-title p {
            margin: 2px 0 0;
            font-size: 13px;
            color: #94a3b8;
        }

        .btn-add {
            background: linear-gradient(135deg, rgba(99, 102, 241, 0.8), rgba(79, 70, 229, 0.9));
            backdrop-filter: blur(12px);
            color: #fff;
            padding: 12px 22px;
            border-radius: 14px;
            text-decoration: none;
            font-size: 13px;
            font-weight: 700;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            border: 1px solid rgba(255, 255, 255, 0.2);
            box-shadow: 0 8px 25px -5px rgba(99, 102, 241, 0.5), inset 0 1px 1px rgba(255, 255, 255, 0.4);
            transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .btn-add:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 30px -5px rgba(99, 102, 241, 0.7), inset 0 1px 1px rgba(255, 255, 255, 0.6);
        }

        /* Glass Cards for Metrics */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
            gap: 18px;
            margin-bottom: 32px;
        }

        .stat-card {
            background: rgba(255, 255, 255, 0.03);
            backdrop-filter: blur(24px) saturate(180%);
            -webkit-backdrop-filter: blur(24px) saturate(180%);
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 20px;
            padding: 22px 26px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2), inset 0 1px 1px rgba(255, 255, 255, 0.1);
            position: relative;
            overflow: hidden;
            transition: transform 0.2s, border-color 0.2s;
        }

        .stat-card:hover {
            transform: translateY(-2px);
            border-color: rgba(255, 255, 255, 0.18);
        }

        .stat-label {
            font-size: 12px;
            font-weight: 700;
            color: #94a3b8;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            margin-bottom: 6px;
        }

        .stat-val {
            font-size: 28px;
            font-weight: 800;
            color: #fff;
            text-shadow: 0 0 20px rgba(255, 255, 255, 0.2);
        }

        /* Master Glass Table Card */
        .glass-card {
            background: rgba(15, 23, 42, 0.45);
            backdrop-filter: blur(24px) saturate(180%);
            -webkit-backdrop-filter: blur(24px) saturate(180%);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 24px;
            overflow: hidden;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5), inset 0 1px 1px rgba(255, 255, 255, 0.15);
        }

        .status-banner {
            background: rgba(16, 185, 129, 0.15);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid rgba(52, 211, 153, 0.3);
            color: #6ee7b7;
            padding: 14px 24px;
            font-size: 13px;
            font-weight: 600;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            text-align: left;
        }

        th {
            background: rgba(255, 255, 255, 0.02);
            padding: 18px 24px;
            font-size: 12px;
            font-weight: 700;
            color: #94a3b8;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            border-bottom: 1px solid rgba(255, 255, 255, 0.07);
        }

        td {
            padding: 20px 24px;
            font-size: 14px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.04);
            vertical-align: middle;
        }

        tr:hover td {
            background: rgba(255, 255, 255, 0.03);
        }

        .site-meta {
            display: flex;
            align-items: center;
            gap: 14px;
        }

        .site-avatar {
            width: 42px;
            height: 42px;
            background: rgba(99, 102, 241, 0.15);
            border: 1px solid rgba(99, 102, 241, 0.35);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 800;
            color: #a5b4fc;
            font-size: 14px;
            box-shadow: 0 4px 15px rgba(99, 102, 241, 0.2);
        }

        .site-name {
            font-weight: 700;
            color: #fff;
            margin-bottom: 2px;
        }

        .site-domain {
            font-size: 12px;
            color: #64748b;
            font-family: 'JetBrains Mono', monospace;
        }

        .badge-pill {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 4px 12px;
            border-radius: 30px;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.04em;
        }

        .badge-active {
            background: rgba(34, 197, 94, 0.12);
            color: #4ade80;
            border: 1px solid rgba(34, 197, 94, 0.3);
            box-shadow: 0 0 15px rgba(34, 197, 94, 0.2);
        }

        .badge-inactive {
            background: rgba(239, 68, 68, 0.12);
            color: #f87171;
            border: 1px solid rgba(239, 68, 68, 0.3);
        }

        .badge-dot {
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background: currentColor;
            box-shadow: 0 0 8px currentColor;
        }

        .key-glass {
            font-family: 'JetBrains Mono', monospace;
            font-size: 12px;
            background: rgba(255, 255, 255, 0.04);
            border: 1px solid rgba(255, 255, 255, 0.09);
            padding: 5px 10px;
            border-radius: 8px;
            color: #38bdf8;
            cursor: pointer;
            transition: all 0.15s;
        }

        .key-glass:hover {
            background: rgba(56, 189, 248, 0.12);
            border-color: rgba(56, 189, 248, 0.4);
            box-shadow: 0 0 15px rgba(56, 189, 248, 0.2);
        }

        .gw-pills {
            display: flex;
            gap: 6px;
            flex-wrap: wrap;
        }

        .gw-pill {
            font-size: 11px;
            font-weight: 700;
            padding: 3px 9px;
            border-radius: 8px;
            background: rgba(255, 255, 255, 0.04);
            color: #64748b;
            border: 1px solid rgba(255, 255, 255, 0.06);
        }

        .gw-pill.enabled {
            background: rgba(99, 102, 241, 0.18);
            color: #c7d2fe;
            border-color: rgba(99, 102, 241, 0.4);
            box-shadow: 0 0 10px rgba(99, 102, 241, 0.15);
        }

        .action-glass {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            color: #fff;
            padding: 9px 16px;
            border-radius: 10px;
            text-decoration: none;
            font-size: 13px;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            transition: all 0.2s;
        }

        .action-glass:hover {
            background: rgba(99, 102, 241, 0.25);
            border-color: rgba(99, 102, 241, 0.5);
            color: #c7d2fe;
            box-shadow: 0 0 20px rgba(99, 102, 241, 0.3);
        }
    </style>
</head>

<body>
    <!-- Background Glass Lights -->
    <div class="ambient-orb orb-1"></div>
    <div class="ambient-orb orb-2"></div>
    <div class="ambient-orb orb-3"></div>

    <div class="container">
        <div class="topbar">
            <div class="brand">
                <div class="brand-logo">GT</div>
                <div class="brand-title">
                    <h1>Payment Orchestrator</h1>
                    <p>Client Websites, Gateway Routing & Live Keys</p>
                </div>
            </div>
            <a href="{{ route('admin.websites.create') }}" class="btn-add">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                    stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M5 12h14" />
                    <path d="M12 5v14" />
                </svg>
                + Register New Website
            </a>
        </div>

        <!-- Metrics Row -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-label">Onboarded Clients</div>
                <div class="stat-val">{{ $websites->count() }}</div>
            </div>
            <div class="stat-card">
                <div class="stat-label">Active Deployments</div>
                <div class="stat-val">{{ $websites->where('is_active', true)->count() }}</div>
            </div>
            <div class="stat-card">
                <div class="stat-label">Payment Engines</div>
                <div class="stat-val">4 <span style="font-size:14px; color:#94a3b8; font-weight:500;">(PayPal, Stripe,
                        eSewa, Khalti)</span></div>
            </div>
        </div>

        <!-- Table -->
        <div class="glass-card">
            @if (session('status'))
                <div class="status-banner">&#x2714; {{ session('status') }}</div>
            @endif

            <table>
                <thead>
                    <tr>
                        <th>Client & Domain</th>
                        <th>Public Key</th>
                        <th>Active Gateways</th>
                        <th>Status</th>
                        <th style="text-align: right;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($websites as $site)
                        <tr>
                            <td>
                                <div class="site-meta">
                                    <div class="site-avatar">{{ strtoupper(substr($site->name, 0, 2)) }}</div>
                                    <div>
                                        <div class="site-name">{{ $site->name }}</div>
                                        <div class="site-domain">{{ $site->domain }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="key-glass"
                                    onclick="navigator.clipboard.writeText('{{ $site->public_key }}'); alert('Public Key copied to clipboard!');"
                                    title="Click to copy">
                                    {{ substr($site->public_key, 0, 14) }}... &#x2398;
                                </span>
                            </td>
                            <td>
                                <div class="gw-pills">
                                    @foreach (['paypal', 'stripe', 'esewa', 'khalti'] as $gw)
                                        @php $setting = $site->getSettingFor($gw); @endphp
                                        <span class="gw-pill {{ $setting && $setting->is_enabled ? 'enabled' : '' }}">
                                            {{ ucfirst($gw) }}
                                        </span>
                                    @endforeach
                                </div>
                            </td>
                            <td>
                                <span class="badge-pill {{ $site->is_active ? 'badge-active' : 'badge-inactive' }}">
                                    <span class="badge-dot"></span>
                                    {{ $site->is_active ? 'Active' : 'Disabled' }}
                                </span>
                            </td>
                            <td style="text-align: right;">
                                <a href="{{ route('admin.websites.edit', $site->id) }}" class="action-glass">
                                    <span>Configure & Code</span>
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none"
                                        stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round">
                                        <path d="m9 18 6-6-6-6" />
                                    </svg>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" style="text-align: center; padding: 60px 20px; color: #64748b;">
                                No merchant websites registered yet. Click "+ Register New Website" above to add your
                                first client.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</body>

</html>
