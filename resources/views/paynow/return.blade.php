<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Payment Status - {{ config('app.name') }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #041853;
            --accent: #77C607;
            --muted: #6b7280;
            --border: #e5e7eb;
        }
        body {
            margin: 0;
            font-family: "Plus Jakarta Sans", -apple-system, BlinkMacSystemFont, sans-serif;
            background: #f9fafb;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 2rem;
        }
        img.logo { height: 30px; margin-bottom: 1.5rem; }
        .card {
            background: #fff;
            padding: 2.5rem;
            border-radius: 16px;
            text-align: center;
            box-shadow: 0 20px 60px rgba(4, 24, 83, 0.1);
            max-width: 420px;
            width: 100%;
        }
        .icon-badge {
            width: 56px;
            height: 56px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1rem;
            font-size: 1.6rem;
        }
        .icon-badge.success { background: rgba(119, 198, 7, 0.15); color: #4c8000; }
        .icon-badge.pending { background: rgba(245, 158, 11, 0.15); color: #b45309; }
        h1 { font-size: 1.4rem; margin-bottom: 0.5rem; color: var(--primary); }
        p { color: var(--muted); }
        a.btn {
            display: inline-block;
            margin-top: 1.5rem;
            background: var(--primary);
            color: #fff;
            text-decoration: none;
            padding: 0.8rem 1.75rem;
            border-radius: 8px;
            font-weight: 600;
        }
        a.secondary {
            display: block;
            margin-top: 1rem;
            color: var(--muted);
            font-size: 0.85rem;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <img class="logo" src="{{ asset('images/smspop-logo.png') }}" alt="SMS-Pop">
    <div class="card">
        @if ($paid)
            <div class="icon-badge success">✓</div>
            <h1>Payment successful</h1>
            <p>Thanks for your purchase — <strong>{{ $productName }}</strong> is complete. Your receipt is ready.</p>
            <a class="btn" href="{{ $downloadUrl }}">Download PDF Receipt</a>
            <a class="secondary" href="{{ url('/') }}">Back to home</a>
        @else
            <div class="icon-badge pending">⏳</div>
            <h1>Payment not confirmed yet</h1>
            <p>We couldn't confirm your payment. If you just paid, wait a moment and refresh this page — Paynow can take a little while to confirm mobile or bank transfers.</p>
            <a class="btn" href="{{ url()->current() }}">Refresh status</a>
            <a class="secondary" href="{{ url('/') }}">Back to home</a>
        @endif
    </div>
</body>
</html>
