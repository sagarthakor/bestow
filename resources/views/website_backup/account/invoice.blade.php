<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Invoice #{{ $order->id }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
        .table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        .table th, .table td { border: 1px solid #ddd; padding: 8px; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .no-border { border: none !important; }
        .header { font-weight: bold; font-size: 18px; margin-bottom: 10px; }
        .logo { width: 120px; }
    </style>
</head>
<body>

<!-- ✅ HEADER WITH LOGO -->
<table class="table no-border">
    <tr>
        <td class="no-border">
            <img src="{{ public_path('company_logo/SHREEYOGITRADERS.jpg') }}" class="logo">
        </td>
        <td class="text-right no-border">
            <strong>{{ env('COMPANY_NAME', 'Your Store') }}</strong><br>
            {{ env('COMPANY_ADDRESS', 'India') }}<br>
            <strong>GSTIN:</strong> {{ env('COMPANY_GSTIN', 'N/A') }}
        </td>
    </tr>
</table>

<!-- ✅ INVOICE TITLE -->
<h2 class="header">TAX INVOICE</h2>

<!-- ✅ INVOICE DETAILS -->
<table class="table">
    <tr>
        <td><strong>Invoice No:</strong> #{{ $order->id }}</td>
        <td><strong>Date:</strong> {{ $order->created_at->format('d M Y') }}</td>
        <td><strong>Payment:</strong> {{ strtoupper($order->payment_method) }}</td>
    </tr>
</table>

<!-- ✅ ADDRESS SECTION -->
<table class="table">
    <tr>
        <th>Billing Address</th>
        <th>Shipping Address</th>
    </tr>
    <tr>
        <td>
            {{ $order->name }}<br>
            {{ $order->address }}<br>
            {{ $order->city->city_name ?? '' }}, {{ $order->state->state_name ?? '' }} - {{ $order->pincode }}<br>
            Phone: {{ $order->phone }}
        </td>
        <td>
            {{ $order->name }}<br>
            {{ $order->address }}<br>
            {{ $order->city->city_name ?? '' }}, {{ $order->state->state_name ?? '' }} - {{ $order->pincode }}<br>
            Phone: {{ $order->phone }}
        </td>
    </tr>
</table>

<!-- ✅ PRODUCT DETAILS -->
<table class="table">
    <tr>
        <th>#</th>
        <th>Product</th>
        <th>HSN</th>
        <th>Qty</th>
        <th>Price</th>
        <th>Total</th>
    </tr>
    @php $subTotal = 0; @endphp
    @foreach($order->order_items as $index => $item)
        @php $lineTotal = $item->price * $item->qty; $subTotal += $lineTotal; @endphp
        <tr>
            <td>{{ $index + 1 }}</td>
            <td>{{ $item->product->product_name }}</td>
            <td>{{ $item->product->hsn_code ?? 'N/A' }}</td>
            <td>{{ $item->qty }}</td>
            <td>₹{{ number_format($item->price, 2) }}</td>
            <td>₹{{ number_format($lineTotal, 2) }}</td>
        </tr>
    @endforeach

    <!-- ✅ Summary Section -->
    <tr>
        <td colspan="5" class="text-right"><strong>Subtotal:</strong></td>
        <td>₹{{ number_format($order->amount, 2) }}</td>
    </tr>
    <tr>
        <td colspan="5" class="text-right"><strong>Shipping Charge:</strong></td>
        <td>₹{{ number_format($order->shipping_charge, 2) }}</td>
    </tr>
    <tr>
        <td colspan="5" class="text-right"><strong>Total Amount:</strong></td>
        <td><strong>₹{{ number_format($order->total_amount, 2) }}</strong></td>
    </tr>
</table>

<p class="text-center">Thank you for shopping with us!</p>
</body>
</html>
