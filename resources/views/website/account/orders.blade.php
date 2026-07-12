@extends('website.template.layout')
@section('title', 'My Orders')

@section('page-css')
<style>
    .order-card {
        background: #fff;
        border-radius: 12px;
        border: 1px solid var(--border);
        margin-bottom: 16px;
        overflow: hidden;
        transition: box-shadow .2s;
    }
    .order-card:hover { box-shadow: var(--shadow); }

    .order-header {
        background: #f8f9fa;
        padding: 12px 20px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 8px;
        border-bottom: 1px solid var(--border);
        cursor: pointer;
        user-select: none;
    }
    .order-meta { display: flex; gap: 24px; flex-wrap: wrap; }
    .order-meta-item small { display: block; font-size: 11px; color: var(--mid); text-transform: uppercase; letter-spacing: .5px; }
    .order-meta-item strong { font-size: 14px; }

    .order-body { padding: 16px 20px; display: none; }
    .order-body.open { display: block; }

    .item-row {
        display: flex; gap: 14px; align-items: center;
        padding: 10px 0; border-bottom: 1px dashed var(--border);
    }
    .item-row:last-child { border-bottom: none; }
    .item-row img {
        width: 60px; height: 60px;
        object-fit: contain; border-radius: 8px;
        border: 1px solid var(--border); padding: 4px; flex-shrink: 0;
    }
    .item-name { font-size: 14px; font-weight: 600; margin-bottom: 2px; }
    .item-attr { font-size: 12px; color: var(--mid); }

    .order-summary-mini {
        background: #f8f9fa; border-radius: 8px; padding: 14px;
        margin-top: 12px; font-size: 14px;
    }
    .sum-row-mini { display: flex; justify-content: space-between; padding: 4px 0; }
    .sum-row-mini.grand { font-weight: 700; font-size: 16px; color: var(--brand); border-top: 1px solid var(--border); padding-top: 10px; margin-top: 6px; }

    .addr-box {
        background: #fff; border: 1px solid var(--border); border-radius: 8px; padding: 12px; font-size: 13px;
    }

    /* Status pills */
    .status-pill {
        padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 600;
        display: inline-flex; align-items: center; gap: 4px;
    }
    .sp-pending    { background: #fef9c3; color: #713f12; }
    .sp-processing { background: #dbeafe; color: #1e40af; }
    .sp-shipped    { background: #e0e7ff; color: #3730a3; }
    .sp-delivered  { background: #dcfce7; color: #14532d; }
    .sp-cancelled  { background: #fee2e2; color: #7f1d1d; }

    .empty-orders {
        text-align: center; padding: 60px 20px;
        background: #fff; border-radius: 12px; border: 1px solid var(--border);
    }
    .empty-orders i { font-size: 72px; color: #d1d5db; }
</style>
@endsection

@section('content')

<!-- Breadcrumb -->
<div class="ec-breadcrumb">
    <div class="container">
        <ol>
            <li><a href="{{ route('website.home') }}">Home</a></li>
            <li><a href="{{ route('website.account.dashboard') }}">Account</a></li>
            <li>My Orders</li>
        </ol>
    </div>
</div>

<div class="container py-4">
    <div class="section-header mb-4">
        <h4>My Orders
            @if($orders->count())
                <span style="font-size:14px; font-weight:400; color:var(--mid);">({{ $orders->count() }} orders)</span>
            @endif
        </h4>
        <a href="{{ route('website.track.order') }}" class="view-all-link">
            <i class="la la-truck"></i> Track Order
        </a>
    </div>

    @if($orders->isEmpty())
        <div class="empty-orders">
            <i class="la la-shopping-bag"></i>
            <h4 style="font-size:20px; font-weight:700; margin:16px 0 8px;">No Orders Yet</h4>
            <p style="color:var(--mid); margin-bottom:20px;">You haven't placed any orders yet.</p>
            <a href="{{ route('website.product.view') }}" class="btn-brand" style="display:inline-block; padding:12px 32px;">
                Start Shopping
            </a>
        </div>
    @else
        @foreach($orders as $order)
        @php
            $statusClass = match(strtolower($order->order_status ?? 'pending')) {
                'delivered'  => 'sp-delivered',
                'shipped', 'dispatched' => 'sp-shipped',
                'processing', 'confirmed' => 'sp-processing',
                'cancelled'  => 'sp-cancelled',
                default      => 'sp-pending',
            };
        @endphp

        <div class="order-card">

            <div class="order-header" onclick="toggleOrder({{ $order->id }})">
                <div class="order-meta">
                    <div class="order-meta-item">
                        <small>Order #</small>
                        <strong>{{ $order->order_number ?? $order->id }}</strong>
                    </div>
                    <div class="order-meta-item">
                        <small>Date</small>
                        <strong>{{ $order->created_at->format('d M Y') }}</strong>
                    </div>
                    <div class="order-meta-item">
                        <small>Total</small>
                        <strong>₹{{ number_format($order->total_amount, 2) }}</strong>
                    </div>
                    <div class="order-meta-item">
                        <small>Payment</small>
                        <strong>{{ strtoupper($order->payment_method ?? '-') }}</strong>
                    </div>
                </div>
                <div style="display:flex; align-items:center; gap:10px;">
                    <span class="status-pill {{ $statusClass }}">{{ ucfirst($order->order_status ?? 'Pending') }}</span>
                    <i class="la la-angle-down" id="arrow-{{ $order->id }}" style="transition:transform .2s; font-size:18px;"></i>
                </div>
            </div>

            <div class="order-body" id="body-{{ $order->id }}">

                <!-- Items -->
                @foreach($order->order_items as $item)
                <div class="item-row">
                    <img src="{{ asset('product_image/' . ($item->product->product_image ?? '')) }}"
                         onerror="this.src='{{ asset('website-assets/img/placeholder.jpg') }}'">
                    <div style="flex:1;">
                        <div class="item-name">{{ $item->product->clean_name ?: ($item->product->product_name ?? 'Product') }}</div>
                        <div class="item-attr">
                            Qty: {{ $item->qty }}
                            @if($item->product->value1 ?? false) &nbsp;|&nbsp; Color: {{ $item->product->value1 }} @endif
                            @if($item->product->value2 ?? false) &nbsp;|&nbsp; Size: {{ $item->product->value2 }}  @endif
                        </div>
                    </div>
                    <div style="text-align:right; flex-shrink:0;">
                        <strong>₹{{ number_format($item->price, 2) }}</strong><br>
                        <small style="color:var(--mid);">× {{ $item->qty }} = ₹{{ number_format($item->price * $item->qty, 2) }}</small>
                    </div>
                </div>
                @endforeach

                <div class="row mt-3">
                    <!-- Address -->
                    <div class="col-md-6 mb-3">
                        <h6 style="font-size:13px; font-weight:700; margin-bottom:8px; text-transform:uppercase; letter-spacing:.5px; color:var(--mid);">Shipping Address</h6>
                        <div class="addr-box">
                            <strong>{{ $order->name ?? '-' }}</strong><br>
                            {{ $order->address }}<br>
                            {{ $order->customer_city }}, {{ $order->customer_state }} – {{ $order->pincode }}<br>
                            Phone: {{ $order->phone }}
                        </div>
                    </div>

                    <!-- Summary -->
                    <div class="col-md-6 mb-3">
                        <h6 style="font-size:13px; font-weight:700; margin-bottom:8px; text-transform:uppercase; letter-spacing:.5px; color:var(--mid);">Price Summary</h6>
                        <div class="order-summary-mini">
                            <div class="sum-row-mini">
                                <span>Subtotal</span>
                                <span>₹{{ number_format($order->amount ?? $order->total_amount, 2) }}</span>
                            </div>
                            <div class="sum-row-mini">
                                <span>Shipping</span>
                                <span>₹{{ number_format($order->shipping_charge ?? 0, 2) }}</span>
                            </div>
                            <div class="sum-row-mini grand">
                                <span>Total Paid</span>
                                <span>₹{{ number_format($order->total_amount, 2) }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div style="display:flex; gap:10px; flex-wrap:wrap; padding-top:10px; border-top:1px solid var(--border);">
                    <a href="{{ route('website.orders.invoice', $order->id) }}"
                       class="btn-outline-brand" style="padding:8px 16px; font-size:13px; border-radius:6px;">
                        <i class="la la-file-pdf"></i> Invoice
                    </a>
                    @if(in_array(strtolower($order->order_status ?? ''), ['placed','pending','confirmed','processing']))
                        <form method="POST" action="{{ route('website.orders.cancel', $order->id) }}"
                              onsubmit="return confirm('Cancel this order?')">
                            @csrf
                            <button type="submit" style="background:none; border:1px solid #dc2626; color:#dc2626; padding:8px 16px; border-radius:6px; font-size:13px; font-weight:600; cursor:pointer;">
                                Cancel Order
                            </button>
                        </form>
                    @endif
                </div>

            </div>
        </div>
        @endforeach
    @endif
</div>
@endsection

@section('page-javascript')
<script>
function toggleOrder(id) {
    const body  = document.getElementById('body-' + id);
    const arrow = document.getElementById('arrow-' + id);
    body.classList.toggle('open');
    arrow.style.transform = body.classList.contains('open') ? 'rotate(180deg)' : 'rotate(0)';
}
</script>
@endsection
