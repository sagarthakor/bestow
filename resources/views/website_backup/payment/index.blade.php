@extends('website.template.layout')
@section('title','Payment')
@section('content')
<div class="container py-4">
    <h4 class="mb-3">Payment</h4>
    <div class="card shadow-sm">
        <div class="card-body">
            <p>Pay with Razorpay</p>
            <button id="rzp-button1" class="btn btn-primary">Pay ₹{{ number_format($order->amount,2) }}</button>
        </div>
    </div>
</div>
@endsection

@section('page-javascript')
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
<script>
var options = {
    "key": "{{ $razorpay_key }}",
    "amount": {{ (int) round($order->amount * 100) }},
    "currency": "{{ $order->currency }}",
    "name": "{{ config('project.brand', 'Store') }}",
    "description": "Order #{{ $order->id }}",
    "order_id": "{{ $order->razorpay_order_id }}",
    "handler": function (response){
        fetch("{{ route('website.payment.success') }}", {
            method: "POST",
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').getAttribute('content')
            },
            body: JSON.stringify({
                order_id: {{ $order->id }},
                razorpay_order_id: response.razorpay_order_id,
                razorpay_payment_id: response.razorpay_payment_id,
                razorpay_signature: response.razorpay_signature
            })
        }).then(()=> window.location = "{{ route('website.home') }}");
    },
    "prefill": { "name": "{{ $order->name }}", "email": "", "contact": "{{ $order->phone }}" },
    "theme": { "color": "#3399cc" }
};
var rzp1 = new Razorpay(options);
document.getElementById('rzp-button1').onclick = function(e){
    rzp1.open();
    e.preventDefault();
}
</script>
@endsection
