<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Order {{ $order->order_number }}</title>
</head>
<body>
<h2>Thanks for your order, {{ $order->name }}!</h2>
<p>Order Number: <strong>{{ $order->order_number }}</strong></p>

<h3>Items</h3>
<table cellpadding="6" cellspacing="0" border="1" width="100%">
    <tr>
        <th>#</th>
        <th>Image</th>
        <th>Product</th>
        <th>Qty</th>
        <th>Price</th>
        <th>Total</th>
    </tr>
    @foreach($items as $i => $it)
        <tr>
            <td>{{ $i+1 }}</td>
            <td>
                @if($it['image'])
                    <img src="{{ asset('/product_image/'.$it['image']) }}" alt="product" width="60">
                @endif
            </td>
            <td>{{ $it['name'] }}</td>
            <td>{{ $it['qty'] }}</td>
            <td>₹{{ number_format($it['price'],2) }}</td>
            <td>₹{{ number_format($it['total'],2) }}</td>
        </tr>
    @endforeach
    <tr>
        <td colspan="5" align="right"><strong>Subtotal</strong></td>
        <td><strong>₹{{ number_format($subtotal,2) }}</strong></td>
    </tr>
    <tr>
        <td colspan="5" align="right"><strong>Shipping</strong></td>
        <td><strong>₹{{ number_format($shipping,2) }}</strong></td>
    </tr>
    <tr>
        <td colspan="5" align="right"><strong>Grand Total</strong></td>
        <td><strong>₹{{ number_format($grand,2) }}</strong></td>
    </tr>
</table>

<h3>Delivery Address</h3>
<p>
    {{ $address['name'] }} ({{ $order->phone }})<br>
    {{ $address['address'] }}<br>
    {{ optional($order->city)->city_name }}, {{ optional($order->state)->state_name }} - {{ $order->pincode }}
</p>
</body>
</html>
