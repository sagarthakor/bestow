@extends('website.template.layout')
@section('title', 'Track Order')

@section('content')
    <div class="container py-5">
        <h3>Track Your Order</h3>
        <form method="POST" action="{{ route('website.track.order.submit') }}">
            @csrf
            <div class="form-group">
                <label>Order Number</label>
                <input type="text" name="order_number" class="form-control" required>
            </div>
            <div class="form-group mt-2">
                <label>Phone Number</label>
                <input type="text" name="phone" class="form-control" required>
            </div>
            <button class="btn btn-primary mt-3">Track Order</button>
        </form>
    </div>
@endsection
