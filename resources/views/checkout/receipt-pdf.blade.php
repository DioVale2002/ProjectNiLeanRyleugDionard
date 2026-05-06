<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Receipt #{{ $order->order_id }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; color: #111827; font-size: 12px; }
        .container { width: 100%; }
        .header { margin-bottom: 16px; }
        .title { font-size: 20px; font-weight: bold; margin-bottom: 4px; }
        .muted { color: #6b7280; }
        .card { border: 1px solid #e5e7eb; padding: 12px; margin-bottom: 12px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border-bottom: 1px solid #e5e7eb; padding: 8px; text-align: left; }
        th { background: #f9fafb; }
        .right { text-align: right; }
        .totals td { border: none; }
    </style>
</head>
<body>
    @php
        $items = $order->cart->items;
        $subtotal = (float) $items->sum('subtotal');
        $discount = $order->voucher
            ? ($order->voucher->voucherType === 'percentage'
                ? $subtotal * ((float) $order->voucher->voucherAmount / 100)
                : min($subtotal, (float) $order->voucher->voucherAmount))
            : 0;
    @endphp

    <div class="container">
        <div class="header">
            <div class="title">New Century Books Receipt</div>
            <div class="muted">Order #{{ str_pad((string) $order->order_id, 10, '0', STR_PAD_LEFT) }}</div>
            <div class="muted">Date: {{ $order->created_at->format('M d, Y h:i A') }}</div>
        </div>

        <div class="card">
            <div><strong>Customer:</strong> {{ $order->customer->first_name }} {{ $order->customer->last_name }}</div>
            <div><strong>Email:</strong> {{ $order->customer->email }}</div>
            <div><strong>Payment:</strong> {{ $order->paymentMethod->methodName }}</div>
            @if($order->gcash_reference)
                <div><strong>GCash Ref:</strong> {{ $order->gcash_reference }}</div>
                <div><strong>Review:</strong> {{ ucfirst($order->payment_review_status) }}</div>
            @endif
            <div><strong>Status:</strong> {{ $order->order_status }}</div>
        </div>

        <table>
            <thead>
                <tr>
                    <th>Item</th>
                    <th class="right">Qty</th>
                    <th class="right">Unit Price</th>
                    <th class="right">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach($items as $item)
                    <tr>
                        <td>{{ $item->product->Title }}</td>
                        <td class="right">{{ $item->quantity }}</td>
                        <td class="right">PHP {{ number_format($item->unitPrice, 2) }}</td>
                        <td class="right">PHP {{ number_format($item->subtotal, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <table class="totals" style="margin-top: 12px;">
            <tr>
                <td class="right"><strong>Subtotal:</strong></td>
                <td class="right" style="width: 180px;">PHP {{ number_format($subtotal, 2) }}</td>
            </tr>
            <tr>
                <td class="right"><strong>Discount:</strong></td>
                <td class="right">PHP {{ number_format($discount, 2) }}</td>
            </tr>
            <tr>
                <td class="right"><strong>Total:</strong></td>
                <td class="right"><strong>PHP {{ number_format($order->total_price, 2) }}</strong></td>
            </tr>
        </table>
    </div>
</body>
</html>
