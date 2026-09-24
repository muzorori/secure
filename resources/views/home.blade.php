<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name') }} &mdash; Credit Top-Up</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg: #f9fafb;
            --card-bg: #ffffff;
            --text: #041853;
            --muted: #6b7280;
            --primary: #041853;
            --accent: #77C607;
            --accent-soft: rgba(119, 198, 7, 0.12);
            --border: #e5e7eb;
            --danger: #dc2626;
        }
        * { box-sizing: border-box; }
        body {
            margin: 0;
            font-family: "Plus Jakarta Sans", -apple-system, BlinkMacSystemFont, sans-serif;
            background: var(--bg);
            color: var(--text);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
        }
        .product-card {
            background: var(--card-bg);
            border-radius: 18px;
            box-shadow: 0 20px 60px rgba(4, 24, 83, 0.1);
            max-width: 480px;
            width: 100%;
            overflow: hidden;
        }
        .product-thumb {
            background: #ffffff;
            padding: 2rem;
            text-align: center;
            border-bottom: 1px solid var(--border);
        }
        .product-thumb img { height: 40px; }
        .product-thumb .badge {
            display: inline-block;
            margin-top: 0.9rem;
            font-size: 0.7rem;
            letter-spacing: 0.05em;
            text-transform: uppercase;
            background: var(--primary);
            color: #fff;
            font-weight: 700;
            padding: 0.3rem 0.7rem;
            border-radius: 999px;
        }
        .product-body { padding: 1.75rem 2rem 2rem; }
        h1 { font-size: 1.4rem; margin: 0 0 0.5rem; }
        p.description { color: var(--muted); font-size: 0.9rem; line-height: 1.5; margin-bottom: 1.25rem; }
        ul.features { list-style: none; padding: 0; margin: 0 0 1.5rem; color: var(--text); font-size: 0.88rem; }
        ul.features li { padding: 0.3rem 0; display: flex; gap: 0.5rem; }
        ul.features li::before { content: "✓"; color: var(--accent); font-weight: bold; }
        .price-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-top: 1px solid var(--border);
            padding-top: 1.25rem;
        }
        .price { font-size: 1.6rem; font-weight: 800; }
        .price small { font-size: 0.9rem; font-weight: 400; color: var(--muted); }
        .checkout-btn {
            background: var(--primary);
            color: #fff;
            border: none;
            padding: 0.85rem 1.75rem;
            font-size: 0.95rem;
            font-weight: 600;
            border-radius: 10px;
            cursor: pointer;
        }
        .checkout-btn:hover { opacity: 0.92; }

        .modal-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(4, 24, 83, 0.55);
            align-items: center;
            justify-content: center;
            z-index: 1000;
        }
        .modal-overlay.open { display: flex; }
        .modal {
            background: var(--card-bg);
            border-radius: 16px;
            padding: 2rem;
            width: 100%;
            max-width: 420px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.25);
            text-align: left;
        }
        .modal h2 { margin-top: 0; font-size: 1.25rem; }
        .modal p.hint { color: var(--muted); font-size: 0.85rem; margin-top: -0.5rem; }
        .field { margin-bottom: 1rem; }
        .field label { display: block; font-size: 0.85rem; margin-bottom: 0.35rem; font-weight: 500; }
        .field input {
            width: 100%;
            padding: 0.65rem 0.8rem;
            border: 1px solid var(--border);
            border-radius: 8px;
            font-size: 0.95rem;
            font-family: inherit;
        }
        .field input:focus { outline: 2px solid var(--accent); border-color: var(--accent); }
        .summary {
            background: var(--accent-soft);
            border-radius: 10px;
            padding: 1rem 1.2rem;
            margin-bottom: 1rem;
        }
        .summary dl { margin: 0; display: grid; grid-template-columns: auto 1fr; gap: 0.5rem 1rem; }
        .summary dt { color: var(--muted); font-size: 0.85rem; }
        .summary dd { margin: 0; font-size: 0.95rem; font-weight: 600; text-align: right; }
        .modal-actions {
            display: flex;
            gap: 0.75rem;
            margin-top: 1.5rem;
        }
        .btn {
            flex: 1;
            padding: 0.75rem;
            border-radius: 8px;
            border: none;
            font-size: 0.95rem;
            font-weight: 600;
            cursor: pointer;
        }
        .btn-primary { background: var(--primary); color: #fff; }
        .btn-secondary { background: #f0f0f3; color: var(--text); }
        .error-text { color: var(--danger); font-size: 0.85rem; margin-top: 0.5rem; display: none; }
    </style>
</head>
<body>
    <div class="product-card">
        <div class="product-thumb">
            <img src="{{ asset('images/smspop-logo.png') }}" alt="SMS-Pop">
            <div><span class="badge">Credit Top-Up</span></div>
        </div>
        <div class="product-body">
            <h1>SMS-Pop Credit Top-Up</h1>
            <p class="description">
                Top up your SMS-Pop account balance to send bulk SMS campaigns.
                Pay securely by card — ideal if you're outside Zimbabwe and don't have EcoCash.
            </p>
            <ul class="features">
                <li>Instant balance top-up after payment</li>
                <li>Pay by Visa, Mastercard or other cards via Paynow</li>
                <li>No EcoCash or local bank account required</li>
                <li>PDF receipt emailed &amp; downloadable instantly</li>
            </ul>
            <div class="price-row">
                <div class="price">$2.00 <small>USD</small></div>
                <button class="checkout-btn" id="openCheckout">Checkout</button>
            </div>
        </div>
    </div>

    <div class="modal-overlay" id="checkoutOverlay">
        <div class="modal">
            <h2>Checkout</h2>
            <p class="hint">You'll be securely redirected to Paynow to enter your card details.</p>
            <form id="checkoutForm">
                <div class="field">
                    <label for="email">Email address</label>
                    <input type="email" id="email" name="email" required placeholder="you@example.com">
                </div>
                <div class="summary">
                    <dl>
                        <dt>Item</dt>
                        <dd>SMS-Pop Credit Top-Up</dd>
                        <dt>Total</dt>
                        <dd>$2.00</dd>
                    </dl>
                </div>
                <p class="error-text" id="errorText"></p>
                <div class="modal-actions">
                    <button type="button" class="btn btn-secondary" id="closeCheckout">Cancel</button>
                    <button type="submit" class="btn btn-primary" id="payBtn">Pay with Paynow</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        const overlay = document.getElementById('checkoutOverlay');
        const openBtn = document.getElementById('openCheckout');
        const closeBtn = document.getElementById('closeCheckout');
        const form = document.getElementById('checkoutForm');
        const payBtn = document.getElementById('payBtn');
        const errorText = document.getElementById('errorText');

        openBtn.addEventListener('click', () => overlay.classList.add('open'));
        closeBtn.addEventListener('click', () => overlay.classList.remove('open'));
        overlay.addEventListener('click', (e) => {
            if (e.target === overlay) overlay.classList.remove('open');
        });

        form.addEventListener('submit', async (e) => {
            e.preventDefault();
            errorText.style.display = 'none';
            payBtn.disabled = true;
            payBtn.textContent = 'Processing...';

            try {
                const response = await fetch('{{ route('checkout') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    },
                    body: JSON.stringify({
                        email: document.getElementById('email').value,
                    }),
                });

                const data = await response.json();

                if (!response.ok) {
                    throw new Error(data.message || 'Something went wrong.');
                }

                window.location.href = data.redirect_url;
            } catch (err) {
                errorText.textContent = err.message;
                errorText.style.display = 'block';
                payBtn.disabled = false;
                payBtn.textContent = 'Pay with Paynow';
            }
        });
    </script>
</body>
</html>
