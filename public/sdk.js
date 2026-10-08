/**
 * GTech Payment Orchestrator Client SDK
 */
window.GTechPay = {
    checkout: async function (options) {
        if (!options.key) {
            alert("GTechPay Error: Missing merchant public key.");
            return;
        }

        try {
            // Automatically detect current backend origin or default to current host
            const scriptTag = document.currentScript || document.querySelector('script[src*="sdk.js"]');
            const baseUrl = scriptTag ? new URL(scriptTag.src).origin : window.location.origin;

            const response = await fetch(`${baseUrl}/api/v1/checkout/session`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    key: options.key,
                    amount: options.amount,
                    currency: options.currency || 'USD',
                    order_id: options.order_id || 'ORDER-' + Date.now(),
                    customer_name: options.customer_name || '',
                    customer_email: options.customer_email || '',
                    return_url: options.return_url || window.location.href,
                    cancel_url: options.cancel_url || window.location.href,
                })
            });

            const data = await response.json();

            if (data.checkout_url) {
                // Redirect customer to the secure hosted checkout
                window.location.href = data.checkout_url;
            } else {
                alert("Payment initiation failed: " + (data.error || "Unknown error"));
            }
        } catch (error) {
            console.error("GTechPay checkout error:", error);
            alert("Unable to reach payment gateway orchestrator.");
        }
    }
};