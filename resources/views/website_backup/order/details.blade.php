@extends('website.template.layout')
@section('title', 'Order Details')

@section('content')
    <div class="container py-4">
        <h3>Order Details: {{ $order->order_number }}</h3>

        <strong>Name:</strong> {{ $order->customer_name }}<br>
        <strong>Phone:</strong> {{ $order->customer_phone }}<br>
        <strong>Address:</strong> {{ $order->customer_address }}<br>

        <table class="table table-bordered mt-3">
            <thead>
            <tr>
                <th>Product</th>
                <th>Qty</th>
                <th>Price</th>
                <th>Total</th>
            </tr>
            </thead>
            <tbody>
            @foreach($order->items as $item)
                <tr>
                    <td>{{ $item->product->clean_name }}</td>
                    <td>{{ $item->qty }}</td>
                    <td>₹{{ $item->price }}</td>
                    <td>₹{{ $item->qty * $item->price }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
@endsection
