window.GTechPay = {
    checkout: async function (options) {
        if (!options.key) {
            alert("GTechPay Error: Missing merchant public key.");
            return;
        }

        try {
            // Detect from options or script tag:
            const scriptTag = document.currentScript || document.querySelector('script[src*="sdk.js"]');
            let baseUrl = options.orchestrator_url || '';
            
            if (!baseUrl && scriptTag && scriptTag.src) {
                try {
                    const parsedUrl = new URL(scriptTag.src);
                    baseUrl = parsedUrl.origin;
                } catch(e) {}
            }

            if (!baseUrl) {
                baseUrl = 'http://127.0.0.1:8000';
            }

            console.log("[GTechPay] Calling orchestrator endpoint at:", baseUrl);

            const response = await fetch(`${baseUrl}/api/v1/checkout/session`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'ngrok-skip-browser-warning': 'true'
                },
                body: JSON.stringify({
                    key: options.key,
                    amount: options.amount,
                    currency: options.currency || 'USD',
                    order_id: options.order_id || ('ORDER-' + Date.now()),
                    customer_name: options.customer_name || 'Guest',
                    customer_email: options.customer_email || 'guest@example.com',
                    return_url: options.return_url || window.location.href,
                    cancel_url: options.cancel_url || window.location.href,
                })
            });

            const data = await response.json();

            if (response.ok && data.checkout_url) {
                console.log("[GTechPay] Redirecting to checkout URL:", data.checkout_url);
                window.location.href = data.checkout_url;
            } else {
                console.error("[GTechPay] Server returned error:", data);
                alert("Payment initiation failed: " + (data.error || JSON.stringify(data)));
            }
        } catch (error) {
            console.error("[GTechPay] Fetch Exception:", error);
            alert("Connection Error: " + error.message + "\n\nMake sure your Laravel server is running on " + (typeof baseUrl !== 'undefined' ? baseUrl : 'http://localhost:8000'));
        }
    }
};