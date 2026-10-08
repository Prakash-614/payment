<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>The Soaltee Kathmandu &middot; Booking Checkout Demo</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap"
        rel="stylesheet">
    <style>
        body {
            margin: 0;
            padding: 40px 20px;
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: #f1f5f9;
            color: #0f172a;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
        }

        .hotel-card {
            background: #fff;
            max-width: 460px;
            width: 100%;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 20px 40px -10px rgba(0, 0, 0, 0.1);
            border: 1px solid #e2e8f0;
        }

        .hotel-img {
            height: 180px;
            background: linear-gradient(135deg, #1e293b, #334155);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-size: 24px;
            font-weight: 800;
        }

        .hotel-body {
            padding: 30px;
        }

        .hotel-title {
            font-size: 20px;
            font-weight: 800;
            margin: 0 0 6px;
        }

        .hotel-desc {
            font-size: 14px;
            color: #64748b;
            margin: 0 0 20px;
        }

        .price-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 16px 0;
            border-top: 1px dashed #e2e8f0;
            border-bottom: 1px dashed #e2e8f0;
            margin-bottom: 24px;
        }

        .price-label {
            font-size: 14px;
            color: #475569;
            font-weight: 600;
        }

        .price-val {
            font-size: 24px;
            font-weight: 800;
            color: #0f172a;
        }

        .btn-pay {
            width: 100%;
            background: #4f46e5;
            color: #fff;
            padding: 16px;
            border: none;
            border-radius: 12px;
            font-size: 16px;
            font-weight: 700;
            font-family: inherit;
            cursor: pointer;
            box-shadow: 0 10px 20px -5px rgba(79, 70, 229, 0.4);
            transition: all 0.2s;
        }

        .btn-pay:hover {
            background: #4338ca;
            transform: translateY(-1px);
        }
    </style>
</head>

<body>
    <div class="hotel-card">
        <div class="hotel-img">
            The Soaltee Kathmandu ★★★★
        </div>
        <div class="hotel-body">
            <h2 class="hotel-title">Deluxe Ocean Suite</h2>
            <p class="hotel-desc">2 Nights &middot; 2 Guests &middot; Free Breakfast</p>

            <div class="price-row">
                <span class="price-label">Total to Pay</span>
                <span class="price-val">$150.00</span>
            </div>

            <!-- YOUR GTECH INTEGRATION SNIPPET -->
            <button type="button" id="gtech-pay-btn" class="btn-pay">
                Pay with PayPal or Credit Card &rarr;
            </button>
        </div>
    </div>

    <!-- 1. Include GTech Payment SDK -->
    <script src="{{ url('/sdk.js') }}"></script>

    <!-- 2. Snippet Logic -->
    <script>
        document.getElementById('gtech-pay-btn').addEventListener('click', function() {
            GTechPay.checkout({
                key: 'pk_hIHXUQ6u727IMjNelSVPpzUtl0HtVzPz',
                amount: 150.00,
                currency: 'USD',
                order_id: 'BOOK-' + Date.now(),
                customer_name: 'John Doe',
                customer_email: 'john@example.com',
                return_url: '{{ url('/demo/success') }}',
                cancel_url: '{{ url('/demo/cancel') }}'
            });
        });
    </script>
</body>

</html>
