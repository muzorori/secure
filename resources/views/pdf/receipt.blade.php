<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; color: #041853; margin: 0; padding: 40px; }
        .header { display: table; width: 100%; margin-bottom: 30px; }
        .header .logo { display: table-cell; width: 60%; vertical-align: middle; }
        .header .logo img { height: 40px; }
        .header .receipt-label { display: table-cell; width: 40%; text-align: right; vertical-align: middle; }
        .receipt-label h1 { font-size: 22px; color: #77C607; margin: 0; }
        .receipt-label p { margin: 2px 0 0; color: #6b7280; font-size: 11px; }

        .meta { display: table; width: 100%; margin-bottom: 25px; border-top: 2px solid #041853; border-bottom: 1px solid #e5e7eb; padding: 15px 0; }
        .meta .col { display: table-cell; width: 33%; vertical-align: top; }
        .meta .col .label { color: #6b7280; font-size: 10px; text-transform: uppercase; letter-spacing: 0.04em; }
        .meta .col .value { font-size: 12px; font-weight: bold; margin-top: 2px; }

        table.items { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        table.items th { text-align: left; background: #041853; color: #fff; padding: 10px 12px; font-size: 11px; text-transform: uppercase; letter-spacing: 0.03em; }
        table.items th.right, table.items td.right { text-align: right; }
        table.items td { padding: 12px; border-bottom: 1px solid #e5e7eb; font-size: 12px; }

        .totals { width: 100%; margin-top: 10px; }
        .totals td { padding: 6px 12px; font-size: 12px; }
        .totals tr.grand-total td { border-top: 2px solid #041853; font-size: 14px; font-weight: bold; padding-top: 10px; }
        .totals tr.grand-total td.right { color: #77C607; }

        .status-badge {
            display: inline-block;
            background: rgba(119, 198, 7, 0.15);
            color: #4c8000;
            font-weight: bold;
            font-size: 11px;
            padding: 4px 12px;
            border-radius: 999px;
            margin-top: 20px;
        }

        .footer { margin-top: 50px; padding-top: 15px; border-top: 1px solid #e5e7eb; color: #6b7280; font-size: 10px; text-align: center; }
    </style>
</head>
<body>
    <div class="header">
        <div class="logo">
            @if ($logoData)
                <img src="{{ $logoData }}" alt="SMS-Pop">
            @else
                <strong style="font-size: 20px; color: #041853;">SMS-Pop</strong>
            @endif
        </div>
        <div class="receipt-label">
            <h1>RECEIPT</h1>
            <p>{{ $reference }}</p>
        </div>
    </div>

    <div class="meta">
        <div class="col">
            <div class="label">Billed To</div>
            <div class="value">{{ $order['email'] }}</div>
        </div>
        <div class="col">
            <div class="label">Date Paid</div>
            <div class="value">{{ \Illuminate\Support\Carbon::parse($order['paid_at'])->format('d M Y, H:i') }}</div>
        </div>
        <div class="col">
            <div class="label">Paynow Reference</div>
            <div class="value">{{ $order['paynow_reference'] ?: '—' }}</div>
        </div>
    </div>

    <table class="items">
        <thead>
            <tr>
                <th>Description</th>
                <th class="right">Amount</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>{{ $order['description'] }}</td>
                <td class="right">${{ number_format($order['amount'], 2) }}</td>
            </tr>
        </tbody>
    </table>

    <table class="totals">
        <tr class="grand-total">
            <td>Total Paid</td>
            <td class="right">${{ number_format($order['amount'], 2) }} USD</td>
        </tr>
    </table>

    <div class="status-badge">✓ PAID VIA PAYNOW</div>

    <div class="footer">
        SMS-Pop &middot; Zimbabwe's Bulk SMS Platform &middot; support@smspop.co.zw<br>
        This receipt was generated automatically upon successful payment confirmation.
    </div>
</body>
</html>
