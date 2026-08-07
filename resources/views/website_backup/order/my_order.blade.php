@extends('website.template.layout')
@section('title', 'My Orders')

@section('content')
    <div class="container py-4">
        <h3>My Orders</h3>
        <table class="table table-bordered mt-3">
            <thead>
            <tr>
                <th>Order No</th>
                <th>Total</th>
                <th>Payment</th>
                <th>Status</th>
                <th>Date</th>
                <th>View</th>
            </tr>
            </thead>
            <tbody>
            @foreach($orders as $o)
                <tr>
                    <td>{{ $o->order_number }}</td>
                    <td>₹{{ number_format($o->total_amount,2) }}</td>
                    <td>{{ $o->payment_method }} ({{ $o->payment_status }})</td>
                    <td>{{ $o->order_status }}</td>
                    <td>{{ $o->created_at->format('d-M-Y') }}</td>
                    <td><a href="{{ route('website.my.order.details', $o->id) }}" class="btn btn-sm btn-primary">View</a></td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
@endsection
