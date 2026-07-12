@extends('website.template.layout')
@section('title', 'Order Details')

@section('page-css')
<style>
    .detail-card {
        background: #fff;
        border-radius: 12px;
        border: 1px solid var(--border);
        overflow: hidden;
        margin-bottom: 20px;
    }
    .detail-card-header {
        background: #f8f9fa;
        padding: 14px 20px;
        border-bottom: 1px solid var(--border);
        display: flex; align-items: center; gap: 10px;
    }
    .detail-card-header h6 { font-size: 15px; font-weight: 700; margin: 0; }
    .detail-card-header i  { font-size: 20px; color: var(--brand); }
    .detail-card-body { padding: 20px; }

    .info-row { display: flex; gap: 4px; margin-bottom: 10px; font-size: 14px; }
    .info-row .lbl { color: var(--mid); min-width: 140px; flex-shrink: 0; }
    .info-row .val { font-weight: 600; }

    .item-row {
        display: flex; gap: 14px; align-items: center;
        padding: 12px 0; border-bottom: 1px dashed var(--border);
    }
    .item-row:last-child { border-bottom: none; padding-bottom: 0; }
    .item-row img {
        width: 64px; height: 64px;
        object-fit: contain; border-radius: 8px;
        border: 1px solid var(--border); padding: 4px;
        flex-shrink: 0;
    }
    .item-name { font-size: 14px; font-weight: 600; margin-bottom: 3px; }
    .item-attr { font-size: 12px; color: var(--mid); }
    .item-price { margin-left: auto; text-align: right; flex-shrink: 0; }
    .item-price .unit { font-size: 12px; color: var(--mid); }
    .item-price .total { font-size: 16px; font-weight: 700; color: var(--brand); }

    .total-row {
        display: flex; justify-content: space-between; align-items: center;
        padding: 8px 0; font-size: 14px; border-bottom: 1px dashed var(--border);
    }
    .total-row:last-child { border-bottom: none; }
    .total-row.grand { font-size: 18px; font-weight: 700; padding-top: 14px; color: var(--brand); }

    .status-timeline { padding: 8px 0; }
    .timeline-step {
        display: flex; gap: 14px; margin-bottom: 16px; position: relative;
    }
    .timeline-step::before {
        content: ''; position: absolute;
        left: 15px; top: 30px;
        width: 2px; height: calc(100% + 6px);
        background: var(--border);
    }
    .timeline-step:last-child::before { display: none; }
    .step-dot {
        width: 30px; height: 30px; border-radius: 50%;
        border: 2px solid var(--border);
        background: #fff; display: flex; align-items: center; justify-content: center;
        font-size: 14px; flex-shrink: 0; position: relative; z-index: 1;
    }
    .step-dot.done { background: #16a34a; border-color: #16a34a; color: #fff; }
    .step-dot.active { background: var(--brand); border-color: var(--brand); color: #fff; }
    .step-label { font-size: 13px; font-weight: 600; margin-bottom: 2px; }
    .step-time  { font-size: 11px; color: var(--mid); }
</style>
@endsection

@section('content')

<!-- Breadcrumb -->
<div class="ec-breadcrumb">
    <div class="container">
        <ol>
            <li><a href="{{ route('website.home') }}">Home</a></li>
            <li><a href="{{ route('website.orders') }}">My Orders</a></li>
            <li>#{{ $order->order_number }}</li>
        </ol>
    </div>
</div>

<div class="container py-4">

    <!-- Header -->
    <div style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:12px; margin-bottom:20px;">
        <div>
            <h4 class="fw-700 mb-0">Order #{{ $order->order_number }}</h4>
            <small class="text-muted">Placed on {{ $order->created_at->format('d M Y, h:i A') }}</small>
        </div>
        <div style="display:flex; gap:10px;">
            @if(Route::has('website.orders.invoice'))
                <a href="{{ route('website.orders.invoice', $order->id) }}"
                   class="btn-outline-brand" style="padding:8px 18px; font-size:13px; border-radius:6px;">
                    <i class="la la-file-pdf"></i> Invoice
                </a>
            @endif
            <a href="{{ route('website.orders') }}" style="background:#f8f9fa; border:1px solid var(--border); color:var(--text); padding:8px 18px; border-radius:6px; font-size:13px; font-weight:600;">
                ← Back to Orders
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">

            <!-- Order Items -->
            <div class="detail-card">
                <div class="detail-card-header">
                    <i class="la la-box"></i>
                    <h6>Order Items</h6>
                </div>
                <div class="detail-card-body">
                    @foreach($order->order_items as $item)
                    <div class="item-row">
                        <img src="{{ asset('product_image/' . ($item->product->product_image ?? '')) }}"
                             onerror="this.src='{{ asset('website-assets/img/placeholder.jpg') }}'">
                        <div style="flex:1;">
                            <div class="item-name">{{ $item->product->clean_name ?: ($item->product->product_name ?? 'Product') }}</div>
                            <div class="item-attr">Qty: {{ $item->qty }}</div>
                        </div>
                        <div class="item-price">
                            <div class="unit">₹{{ number_format($item->price, 2) }} × {{ $item->qty }}</div>
                            <div class="total">₹{{ number_format($item->qty * $item->price, 2) }}</div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Delivery Address -->
            <div class="detail-card">
                <div class="detail-card-header">
                    <i class="la la-map-marker"></i>
                    <h6>Delivery Address</h6>
                </div>
                <div class="detail-card-body">
                    <div class="info-row"><span class="lbl">Name</span><span class="val">{{ $order->customer_name }}</span></div>
                    <div class="info-row"><span class="lbl">Phone</span><span class="val">{{ $order->customer_phone }}</span></div>
                    <div class="info-row"><span class="lbl">Address</span><span class="val">{{ $order->customer_address }}</span></div>
                    @if($order->customer_city ?? false)
                    <div class="info-row"><span class="lbl">City / State</span><span class="val">{{ $order->customer_city }}, {{ $order->customer_state }}</span></div>
                    @endif
                    @if($order->customer_pincode ?? false)
                    <div class="info-row"><span class="lbl">PIN Code</span><span class="val">{{ $order->customer_pincode }}</span></div>
                    @endif
                </div>
            </div>

        </div>

        <div class="col-lg-4">

            <!-- Order Summary -->
            <div class="detail-card">
                <div class="detail-card-header">
                    <i class="la la-receipt"></i>
                    <h6>Price Details</h6>
                </div>
                <div class="detail-card-body">
                    <div class="total-row">
                        <span>Subtotal</span>
                        <strong>₹{{ number_format($order->subtotal ?? $order->total_amount, 2) }}</strong>
                    </div>
                    @if(($order->shipping_charge ?? 0) > 0)
                    <div class="total-row">
                        <span>Shipping</span>
                        <strong>₹{{ number_format($order->shipping_charge, 2) }}</strong>
                    </div>
                    @else
                    <div class="total-row">
                        <span>Shipping</span>
                        <strong style="color:#16a34a;">FREE</strong>
                    </div>
                    @endif
                    <div class="total-row grand">
                        <span>Total Paid</span>
                        <span>₹{{ number_format($order->total_amount, 2) }}</span>
                    </div>
                </div>
            </div>

            <!-- Payment Info -->
            <div class="detail-card">
                <div class="detail-card-header">
                    <i class="la la-credit-card"></i>
                    <h6>Payment</h6>
                </div>
                <div class="detail-card-body">
                    <div class="info-row"><span class="lbl">Method</span><span class="val">{{ strtoupper($order->payment_method ?? '-') }}</span></div>
                    <div class="info-row">
                        <span class="lbl">Status</span>
                        <span class="val">
                            <span class="badge-{{ $order->payment_status == 'paid' ? 'success' : 'warning' }}">
                                {{ ucfirst($order->payment_status ?? 'pending') }}
                            </span>
                        </span>
                    </div>
                </div>
            </div>

            <!-- Order Status -->
            <div class="detail-card">
                <div class="detail-card-header">
                    <i class="la la-truck"></i>
                    <h6>Order Status</h6>
                </div>
                <div class="detail-card-body">
                    @php
                        $steps = ['Order Placed', 'Confirmed', 'Shipped', 'Delivered'];
                        $statusMap = [
                            'pending'    => 0,
                            'processing' => 1, 'confirmed' => 1,
                            'shipped'    => 2, 'dispatched' => 2,
                            'delivered'  => 3,
                        ];
                        $currentStep = $statusMap[strtolower($order->order_status ?? 'pending')] ?? 0;
                    @endphp

                    <div class="status-timeline">
                        @foreach($steps as $idx => $step)
                        <div class="timeline-step">
                            <div class="step-dot {{ $idx < $currentStep ? 'done' : ($idx == $currentStep ? 'active' : '') }}">
                                {{ $idx < $currentStep ? '✓' : ($idx + 1) }}
                            </div>
                            <div>
                                <div class="step-label">{{ $step }}</div>
                                @if($idx == $currentStep)
                                    <div class="step-time" style="color:var(--brand);">Current Status</div>
                                @elseif($idx < $currentStep)
                                    <div class="step-time" style="color:#16a34a;">Completed</div>
                                @else
                                    <div class="step-time">Pending</div>
                                @endif
                            </div>
                        </div>
                        @endforeach
                    </div>

                    @if(in_array(strtolower($order->order_status ?? ''), ['pending', 'processing', 'confirmed']))
                        <form method="POST" action="{{ route('website.orders.cancel', $order->id) }}"
                              onsubmit="return confirm('Are you sure you want to cancel this order?')" class="mt-3">
                            @csrf
                            <button type="submit"
                                    style="width:100%; background:none; border:1px solid #dc2626; color:#dc2626; padding:10px; border-radius:8px; font-size:13px; font-weight:600; cursor:pointer;">
                                Cancel Order
                            </button>
                        </form>
                    @endif
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
