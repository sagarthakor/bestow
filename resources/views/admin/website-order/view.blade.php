@extends('admin.layout.table_master_material')

@section('title', 'Order Details')

@section('content')
    <div class="content-page">
        <div class="content">
            <div class="container">

                <!-- ✅ Page Header -->
                <div class="row">
                    <div class="col-xs-12">
                        <div class="page-title-box d-flex justify-content-between align-items-center">
                            <h4 class="page-title">Order #{{ $order->id }}</h4>
                            <ol class="breadcrumb m-0 p-0">
                                <li><a href="#">{{ Session::get('software_title') }}</a></li>
                                <li><a href="{{ route('admin.orders.index') }}">Orders</a></li>
                                <li class="active">View</li>
                            </ol>
                        </div>
                    </div>
                </div>

                <!-- ✅ Order Summary Box -->
                <div class="card-box">
                    <h5><strong>Order Summary</strong></h5>
                    <hr>
                    <div class="row">
                        <div class="col-md-4">
                            <p><strong>Order ID:</strong> #{{ $order->id }}</p>
                            <p><strong>Status:</strong> <span class="badge badge-info">{{ ucfirst($order->status) }}</span></p>
                            <p><strong>Placed On:</strong> {{ $order->created_at->format('d M Y, h:i A') }}</p>
                        </div>
                        <div class="col-md-4">
                            <p><strong>Payment Method:</strong> {{ strtoupper($order->payment_method) }}</p>
                            <p><strong>Total Amount:</strong> ₹{{ number_format($order->amount, 2) }}</p>
                        </div>
                        @if($order->status == 'shipped')
                            <div class="col-md-4">
                                <p><strong>Courier:</strong> {{ $order->courier_name }}</p>
                                <p><strong>Tracking No:</strong> {{ $order->tracking_number }}</p>
                                @if($order->tracking_url)
                                    <p><strong>Tracking URL:</strong> <a href="{{ $order->tracking_url }}" target="_blank">Track Shipment</a></p>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>

                <!-- ✅ Customer Info + Address -->
                <div class="row">
                    <div class="col-md-6">
                        <div class="card-box">
                            <h5><strong>Customer Details</strong></h5>
                            <hr>
                            <p><strong>Name:</strong> {{ $order->user->customer_name ?? "N/A" }}</p>
                            <p><strong>Email:</strong> {{ $order->user->primary_email ?? 'N/A' }}</p>
                            <p><strong>Phone:</strong> {{ $order->phone }}</p>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="card-box">
                            <h5><strong>Shipping Address</strong></h5>
                            <hr>
                            <p>{{ $order->address }}</p>
                            <p>
                                @if($order->city) {{ $order->city->city_name }}, @endif
                                @if($order->state) {{ $order->state->state_name }} @endif
                                - {{ $order->pincode }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- ✅ Ordered Products Table -->
                <div class="card-box">
                    <h5><strong>Ordered Items</strong></h5>
                    <hr>
                    <table class="table table-bordered">
                        <thead>
                        <tr>
                            <th>Product</th>
                            <th>Details</th>
                            <th>Qty</th>
                            <th>Price</th>
                            <th>Total</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($order->order_items as $item)
                            <tr>
                                <td width="80">
                                    <img src="{{ asset('product_image/'.$item->product->product_image) }}"
                                         width="70" class="rounded"
                                         onerror="this.src='{{ asset('website-assets/img/placeholder.jpg') }}'">
                                </td>
                                <td>
                                    <strong>{{ $item->product->product_name ?? 'N/A' }}</strong><br>
                                    <small>
                                        @if($item->product->value1) Color: {{ $item->product->value1 }} @endif
                                        @if($item->product->value2) | Size: {{ $item->product->value2 }} @endif
                                    </small>
                                </td>
                                <td>{{ $item->qty }}</td>
                                <td>₹{{ number_format($item->price,2) }}</td>
                                <td>₹{{ number_format($item->price * $item->qty,2) }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- ✅ Back + Invoice Button -->
                <div class="text-right mb-4">
                    <a href="{{ route('admin.orders.index') }}" class="btn btn-secondary">← Back to List</a>
                    <a href="{{ route('website.orders.invoice', $order->id) }}" class="btn btn-primary">
                        <i class="fa fa-file-pdf-o"></i> Download Invoice
                    </a>
                </div>

            </div>
        </div>
    </div>
@endsection
