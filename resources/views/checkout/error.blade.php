<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Payment Error</title>
    <style>
        body { font-family: sans-serif; background: #030712; color: #f8fafc; padding: 40px; text-align: center; }
        .box { max-width: 480px; margin: 40px auto; background: rgba(255,255,255,0.05); padding: 32px; border-radius: 16px; border: 1px solid rgba(255,255,255,0.1); }
        h2 { color: #f87171; }
        a { color: #38bdf8; text-decoration: none; }
    </style>
</head>
<body>
    <div class="box">
        <h2>Payment Initialization Failed</h2>
        <p>{{ $message }}</p>
        <p><a href="{{ route('checkout.show', $transaction->session_token) }}">&larr; Back to checkout options</a></p>
    </div>
</body>
</html>