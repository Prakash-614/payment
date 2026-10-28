<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Redirecting to eSewa...</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            background: #030712; color: #f8fafc;
            display: flex; align-items: center; justify-content: center;
            height: 100vh; margin: 0; text-align: center;
        }
        .box {
            background: rgba(15, 23, 42, 0.6); padding: 40px; border-radius: 20px;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
        .spinner {
            width: 32px; height: 32px; border: 3px solid rgba(255,255,255,0.1);
            border-top: 3px solid #60bb46; border-radius: 50%;
            animation: spin 0.8s linear infinite; margin: 0 auto 20px;
        }
        @keyframes spin { 0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); } }
    </style>
</head>
<body>
    <div class="box">
        <div class="spinner"></div>
        <h2 style="margin: 0 0 8px;">Connecting to eSewa</h2>
        <p style="color: #94a3b8; font-size: 14px; margin: 0;">Please wait, redirecting to payment gateway...</p>

        <form id="esewaForm" action="{{ $action }}" method="POST">
            @foreach ($fields as $key => $value)
                <input type="hidden" name="{{ $key }}" value="{{ $value }}">
            @endforeach
        </form>
    </div>

    <script>
        document.getElementById('esewaForm').submit();
    </script>
</body>
</html>