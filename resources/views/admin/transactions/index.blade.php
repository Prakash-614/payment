<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Transaction Ledger | GTech Payment Orchestrator</title>
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
            opacity: 0.55;
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

        .container {
            max-width: 1200px;
            margin: 0 auto;
            position: relative;
            z-index: 1;
        }

        .topbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 28px;
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
        }

        .brand-title p {
            margin: 2px 0 0;
            font-size: 13px;
            color: #94a3b8;
        }

        .nav-links {
            display: flex;
            gap: 12px;
        }

        .nav-btn {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            color: #cbd5e1;
            padding: 10px 18px;
            border-radius: 12px;
            text-decoration: none;
            font-size: 13px;
            font-weight: 700;
            transition: all 0.2s;
        }

        .nav-btn:hover {
            background: rgba(99, 102, 241, 0.2);
            border-color: #6366f1;
            color: #fff;
        }

        .nav-btn.active {
            background: linear-gradient(135deg, rgba(99, 102, 241, 0.8), rgba(79, 70, 229, 0.9));
            color: #fff;
            border-color: rgba(255, 255, 255, 0.2);
            box-shadow: 0 4px 20px rgba(99, 102, 241, 0.35);
        }

        /* Stats Grid */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 18px;
            margin-bottom: 28px;
        }

        .stat-card {
            background: rgba(255, 255, 255, 0.03);
            backdrop-filter: blur(24px) saturate(180%);
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 20px;
            padding: 22px 26px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2), inset 0 1px 1px rgba(255, 255, 255, 0.1);
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

        /* Filter Bar */
        .filter-bar {
            background: rgba(15, 23, 42, 0.45);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 18px;
            padding: 18px 22px;
            margin-bottom: 24px;
            display: flex;
            gap: 14px;
            flex-wrap: wrap;
            align-items: center;
        }

        .filter-select {
            background: rgba(255, 255, 255, 0.04);
            border: 1.5px solid rgba(255, 255, 255, 0.09);
            border-radius: 10px;
            color: #fff;
            padding: 10px 14px;
            font-size: 13px;
            font-family: inherit;
        }

        .filter-select:focus {
            outline: none;
            border-color: #6366f1;
        }

        .filter-btn {
            background: #4f46e5;
            color: #fff;
            border: none;
            padding: 10px 18px;
            border-radius: 10px;
            font-size: 13px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.2s;
        }

        .filter-btn:hover {
            background: #4338ca;
        }

        .clear-link {
            color: #94a3b8;
            font-size: 13px;
            text-decoration: none;
        }

        .clear-link:hover {
            color: #fff;
        }

        /* Table Card */
        .glass-card {
            background: rgba(15, 23, 42, 0.5);
            backdrop-filter: blur(24px) saturate(180%);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 24px;
            overflow: hidden;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            text-align: left;
        }

        th {
            background: rgba(255, 255, 255, 0.02);
            padding: 16px 20px;
            font-size: 12px;
            font-weight: 700;
            color: #94a3b8;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            border-bottom: 1px solid rgba(255, 255, 255, 0.07);
        }

        td {
            padding: 18px 20px;
            font-size: 14px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.04);
            vertical-align: middle;
        }

        tr:hover td {
            background: rgba(255, 255, 255, 0.02);
        }

        .order-badge {
            font-family: 'JetBrains Mono', monospace;
            font-size: 12px;
            color: #38bdf8;
        }

        .gateway-pill {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 3px 10px;
            border-radius: 8px;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.08);
            color: #cbd5e1;
        }

        /* Status Pills */
        .status-pill {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 4px 12px;
            border-radius: 30px;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
        }

        .status-completed {
            background: rgba(34, 197, 94, 0.15);
            color: #4ade80;
            border: 1px solid rgba(34, 197, 94, 0.3);
        }

        .status-pending {
            background: rgba(234, 179, 8, 0.15);
            color: #facc15;
            border: 1px solid rgba(234, 179, 8, 0.3);
        }

        .status-failed {
            background: rgba(239, 68, 68, 0.15);
            color: #f87171;
            border: 1px solid rgba(239, 68, 68, 0.3);
        }

        .status-cancelled {
            background: rgba(148, 163, 184, 0.15);
            color: #94a3b8;
            border: 1px solid rgba(148, 163, 184, 0.3);
        }

        .dot {
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background: currentColor;
        }

        .btn-inspect {
            background: rgba(99, 102, 241, 0.15);
            border: 1px solid rgba(99, 102, 241, 0.35);
            color: #a5b4fc;
            padding: 6px 12px;
            border-radius: 8px;
            font-size: 12px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
        }

        .btn-inspect:hover {
            background: #6366f1;
            color: #fff;
        }

        /* Modal */
        .modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.8);
            backdrop-filter: blur(12px);
            display: none;
            align-items: center;
            justify-content: center;
            z-index: 100;
            padding: 20px;
        }

        .modal-box {
            background: #090d16;
            border: 1px solid rgba(255, 255, 255, 0.15);
            border-radius: 20px;
            max-width: 680px;
            width: 100%;
            max-height: 80vh;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            box-shadow: 0 25px 60px rgba(0, 0, 0, 0.8);
        }

        .modal-header {
            padding: 18px 24px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .modal-body {
            padding: 24px;
            overflow-y: auto;
            font-family: 'JetBrains Mono', monospace;
            font-size: 12px;
            color: #38bdf8;
            background: #020617;
            line-height: 1.5;
        }

        .modal-close {
            background: transparent;
            border: none;
            color: #94a3b8;
            font-size: 20px;
            cursor: pointer;
        }

        .modal-close:hover {
            color: #fff;
        }
    </style>
</head>

<body>
    <div class="ambient-orb orb-1"></div>
    <div class="ambient-orb orb-2"></div>

    <div class="container">
        <div class="topbar">
            <div class="brand">
                <div class="brand-logo">GT</div>
                <div class="brand-title">
                    <h1>Transaction Ledger</h1>
                    <p>Audit Trail, Digital Receipts & Payment Logs</p>
                </div>
            </div>
            <div class="nav-links" style="align-items: center;">
                <a href="{{ route('admin.websites.index') }}" class="nav-btn">Websites</a>
                <a href="{{ route('admin.transactions.index') }}" class="nav-btn active">Transactions</a>
                <form method="POST" action="{{ route('admin.logout') }}" style="margin: 0;">
                    @csrf
                    <button type="submit" class="nav-btn"
                        style="cursor:pointer; background:rgba(239, 68, 68, 0.1); border-color:rgba(239, 68, 68, 0.25); color:#f87171;">
                        Log out
                    </button>
                </form>
            </div>

            <!-- Metric Cards -->
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-label">Total Volume (Settled)</div>
                    <div class="stat-val">${{ number_format($totalVolume, 2) }}</div>
                </div>
                <div class="stat-card">
                    <div class="stat-label">Successful Transactions</div>
                    <div class="stat-val">{{ $successfulCount }}</div>
                </div>
                <div class="stat-card">
                    <div class="stat-label">Total Payment Requests</div>
                    <div class="stat-val">{{ $totalCount }}</div>
                </div>
                <div class="stat-card">
                    <div class="stat-label">Success Rate</div>
                    <div class="stat-val">{{ $successRate }}%</div>
                </div>
            </div>

            <!-- Filters Form -->
            <form method="GET" action="{{ route('admin.transactions.index') }}" class="filter-bar">
                <select name="website_id" class="filter-select">
                    <option value="">All Client Websites</option>
                    @foreach ($websites as $site)
                        <option value="{{ $site->id }}" {{ request('website_id') == $site->id ? 'selected' : '' }}>
                            {{ $site->name }} ({{ $site->domain }})
                        </option>
                    @endforeach
                </select>

                <select name="status" class="filter-select">
                    <option value="">All Statuses</option>
                    <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Completed
                    </option>
                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="failed" {{ request('status') === 'failed' ? 'selected' : '' }}>Failed</option>
                    <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Cancelled
                    </option>
                </select>

                <select name="gateway" class="filter-select">
                    <option value="">All Gateways</option>
                    <option value="stripe" {{ request('gateway') === 'stripe' ? 'selected' : '' }}>Stripe</option>
                    <option value="paypal" {{ request('gateway') === 'paypal' ? 'selected' : '' }}>PayPal</option>
                </select>

                <button type="submit" class="filter-btn">Filter Ledger</button>
                @if (request()->hasAny(['website_id', 'status', 'gateway']))
                    <a href="{{ route('admin.transactions.index') }}" class="clear-link">Reset</a>
                @endif
            </form>

            <!-- Transaction Table -->
            <div class="glass-card">
                <table>
                    <thead>
                        <tr>
                            <th>Date & Order</th>
                            <th>Merchant</th>
                            <th>Amount</th>
                            <th>Gateway</th>
                            <th>Customer</th>
                            <th>Status</th>
                            <th style="text-align: right;">Receipt</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($transactions as $trx)
                            <tr>
                                <td>
                                    <div><strong>{{ $trx->created_at->format('M d, Y H:i') }}</strong></div>
                                    <div class="order-badge">#{{ $trx->order_id ?? $trx->transaction_uuid }}</div>
                                </td>
                                <td>
                                    <strong>{{ $trx->website->name }}</strong>
                                    <div style="font-size:12px; color:#64748b;">{{ $trx->website->domain }}</div>
                                </td>
                                <td>
                                    <strong style="font-size: 15px;">{{ $trx->currency }}
                                        {{ number_format($trx->amount, 2) }}</strong>
                                </td>
                                <td>
                                    <span class="gateway-pill">{{ $trx->gateway ?? 'N/A' }}</span>
                                </td>
                                <td>
                                    <div>{{ $trx->customer_name ?: 'Guest' }}</div>
                                    <div style="font-size:12px; color:#64748b;">
                                        {{ $trx->customer_email ?: 'No email' }}</div>
                                </td>
                                <td>
                                    <span class="status-pill status-{{ $trx->status }}">
                                        <span class="dot"></span>
                                        {{ ucfirst($trx->status) }}
                                    </span>
                                </td>
                                <td style="text-align: right;">
                                    @if (!empty($trx->gateway_response))
                                        <button type="button" class="btn-inspect"
                                            onclick='inspectReceipt(@json($trx->gateway_response))'>
                                            View Receipt JSON
                                        </button>
                                    @else
                                        <span style="font-size: 12px; color: #64748b;">No receipt</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" style="text-align: center; padding: 48px; color: #64748b;">
                                    No transactions recorded matching the filters.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- JSON Receipt Inspection Modal -->
        <div id="receipt-modal" class="modal-overlay">
            <div class="modal-box">
                <div class="modal-header">
                    <strong style="font-size: 16px;">Raw Gateway Receipt (JSON)</strong>
                    <button type="button" class="modal-close" onclick="closeModal()">&times;</button>
                </div>
                <pre id="modal-json" class="modal-body"></pre>
            </div>
        </div>

        <script>
            function inspectReceipt(data) {
                document.getElementById('modal-json').textContent = JSON.stringify(data, null, 2);
                document.getElementById('receipt-modal').style.display = 'flex';
            }

            function closeModal() {
                document.getElementById('receipt-modal').style.display = 'none';
            }

            // Close when clicking background
            document.getElementById('receipt-modal').addEventListener('click', function(e) {
                if (e.target === this) closeModal();
            });
        </script>
</body>

</html>
