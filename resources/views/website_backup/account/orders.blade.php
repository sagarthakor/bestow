@extends('website.template.layout')
@section('title', 'Your Orders')

@section('content')
    <div class="container py-4">
        <h3 class="mb-3">Your Orders</h3>

        @if($orders->count() > 0)
            @foreach($orders as $order)
                <div class="card mb-3 shadow-sm">

                    <!-- ✅ Order Header -->
                    <div class="card-header bg-light d-flex justify-content-between align-items-center toggle-order"
                         data-target="order-{{ $order->id }}" style="cursor: pointer;">
                        <div>
                            <strong>Order #{{ $order->id }}</strong><br>
                            <small>Placed on {{ $order->created_at->format('d M Y, h:i A') }}</small>
                        </div>
                        <div class="text-right">
                            <!-- ✅ Total Amount -->
                            <strong>₹{{ number_format($order->total_amount, 2) }}</strong><br>
                            <small>Status: {{ ucfirst($order->order_status) }}</small>
                            <i class="la la-angle-down toggle-icon ml-2"></i>
                        </div>
                    </div>

                    <!-- ✅ Order Details -->
                    <div class="card-body order-details" id="order-{{ $order->id }}" style="display: none;">

                        <!-- ✅ Product List -->
                        <h6 class="mb-3">Items in this order</h6>
                        @foreach($order->order_items as $item)
                            <div class="d-flex border-bottom pb-2 mb-2">
                                <img src="{{ asset('product_image/' . ($item->product->product_image ?? 'placeholder.jpg')) }}"
                                     width="60" height="60" class="mr-3 rounded"
                                     onerror="this.src='{{ asset('website-assets/img/placeholder.jpg') }}'">

                                <div class="flex-grow-1">
                                    <strong>{{ $item->product->product_name ?? 'Product not found' }}</strong><br>
                                    <small>Qty: {{ $item->qty }}</small><br>
                                    <small class="text-muted">
                                        @if($item->product->value1) Color: {{ $item->product->value1 }} @endif
                                        @if($item->product->value2) | Size: {{ $item->product->value2 }} @endif
                                    </small>
                                </div>

                                <div class="text-right">
                                    ₹{{ number_format($item->price, 2) }}<br>
                                    <small>Total: ₹{{ number_format($item->price * $item->qty, 2) }}</small>
                                </div>
                            </div>
                        @endforeach

                        <!-- ✅ Order Amount Summary -->
                        <div class="mt-3 p-3 border rounded bg-light">
                            <div class="d-flex justify-content-between">
                                <span>Subtotal (Products)</span>
                                <strong>₹{{ number_format($order->amount, 2) }}</strong>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span>Shipping Charge</span>
                                <strong>₹{{ number_format($order->shipping_charge, 2) }}</strong>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span>Grand Total</span>
                                <strong>₹{{ number_format($order->total_amount, 2) }}</strong>
                            </div>
                        </div>

                        <!-- ✅ Shipping & Payment Info -->
                        <div class="row mt-4">
                            <div class="col-md-6">
                                <h6>Shipping Address</h6>
                                <div class="p-2 border rounded">
                                    <strong>{{ $order->name ?? 'N/A' }}</strong><br>
                                    {{ $order->address }}<br>
                                    @if($order->city) {{ $order->city->city_name }}, @endif
                                    @if($order->state) {{ $order->state->state_name }} @endif - {{ $order->pincode }}<br>
                                    <small>Phone: {{ $order->phone }}</small>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <h6>Payment Details</h6>
                                <div class="p-2 border rounded">
                                    <strong>Method:</strong> {{ strtoupper($order->payment_method) }}<br>
                                    <strong>Subtotal:</strong> ₹{{ number_format($order->amount, 2) }}<br>
                                    <strong>Shipping:</strong> ₹{{ number_format($order->shipping_charge, 2) }}<br>
                                    <strong>Total Paid:</strong> ₹{{ number_format($order->total_amount, 2) }}<br>

                                    @if($order->order_status == 'delivered')
                                        <small class="text-success">Delivered on: {{ $order->updated_at->format('d M Y') }}</small>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- ✅ Action Buttons -->
                        <div class="mt-4 d-flex justify-content-between">
                            <a href="{{ route('website.orders.invoice', $order->id) }}" class="btn btn-sm btn-outline-primary">
                                <i class="la la-file-pdf"></i> Download Invoice
                            </a>

                            <div>
                                {{--@if(in_array($order->order_status, ['placed','confirmed']))
                                    <form action="{{ route('website.orders.cancel', $order->id) }}" method="POST"
                                          onsubmit="return confirm('Cancel this order?');">
                                        @csrf
                                        <button class="btn btn-sm btn-danger">Cancel Order</button>
                                    </form>
                                @endif--}}
                            </div>
                        </div>
                    </div>

                </div>
            @endforeach
        @else
            <div class="alert alert-info">No orders found.</div>
        @endif
    </div>
@endsection

@section('page-javascript')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.toggle-order').forEach(header => {
                header.addEventListener('click', function() {
                    const target = document.getElementById(this.dataset.target);
                    const icon = this.querySelector('.toggle-icon');

                    if (target.style.display === 'none' || target.style.display === '') {
                        target.style.display = 'block';
                        icon.style.transform = 'rotate(180deg)';
                    } else {
                        target.style.display = 'none';
                        icon.style.transform = 'rotate(0)';
                    }
                });
            });
        });
    </script>
@endsection
