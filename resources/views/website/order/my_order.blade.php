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

    .order-card-header {
        background: #f8f9fa;
        padding: 12px 20px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 8px;
        border-bottom: 1px solid var(--border);
    }
    .order-card-header .order-meta { display: flex; gap: 24px; flex-wrap: wrap; }
    .order-meta-item small { display: block; font-size: 11px; color: var(--mid); text-transform: uppercase; letter-spacing: .5px; }
    .order-meta-item strong { font-size: 14px; }

    .order-card-body { padding: 16px 20px; }

    .order-item-row {
        display: flex;
        align-items: center;
        gap: 14px;
        padding: 10px 0;
        border-bottom: 1px dashed var(--border);
    }
    .order-item-row:last-child { border-bottom: none; }
    .order-item-row img {
        width: 60px; height: 60px;
        object-fit: contain;
        border-radius: 8px;
        border: 1px solid var(--border);
        padding: 4px;
        flex-shrink: 0;
    }
    .order-item-name { font-size: 13px; font-weight: 600; margin-bottom: 2px; }
    .order-item-meta { font-size: 12px; color: var(--mid); }

    .order-card-footer {
        padding: 12px 20px;
        border-top: 1px solid var(--border);
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 10px;
        background: #fafafa;
    }

    /* Status pills */
    .status-pill {
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 4px;
    }
    .status-pending    { background: #fef9c3; color: #713f12; }
    .status-processing { background: #dbeafe; color: #1e40af; }
    .status-shipped    { background: #e0e7ff; color: #3730a3; }
    .status-delivered  { background: #dcfce7; color: #14532d; }
    .status-cancelled  { background: #fee2e2; color: #7f1d1d; }

    /* Empty */
    .empty-orders {
        text-align: center; padding: 60px 20px;
        background: #fff; border-radius: 12px;
        border: 1px solid var(--border);
    }
    .empty-orders i { font-size: 72px; color: #d1d5db; }
    .empty-orders h4 { font-size: 20px; font-weight: 700; margin: 16px 0 8px; }
    .empty-orders p  { color: var(--mid); margin-bottom: 20px; }
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
        <h4>My Orders</h4>
        <a href="{{ route('website.track.order') }}" class="view-all-link">
            <i class="la la-truck"></i> Track an Order
        </a>
    </div>

    @if($orders->isEmpty())
        <div class="empty-orders">
            <i class="la la-shopping-bag"></i>
            <h4>No Orders Yet</h4>
            <p>You haven't placed any orders. Start shopping!</p>
            <a href="{{ route('website.product.view') }}" class="btn-brand" style="display:inline-block; padding:12px 32px;">
                Shop Now
            </a>
        </div>
    @else
        @foreach($orders as $o)
        @php
            $statusClass = match(strtolower($o->order_status ?? 'pending')) {
                'delivered'  => 'status-delivered',
                'shipped', 'dispatched' => 'status-shipped',
                'processing', 'confirmed' => 'status-processing',
                'cancelled'  => 'status-cancelled',
                default      => 'status-pending',
            };
            $statusIcon = match(strtolower($o->order_status ?? 'pending')) {
                'delivered'  => '✓',
                'shipped', 'dispatched' => '🚚',
                'processing', 'confirmed' => '⚙',
                'cancelled'  => '✕',
                default      => '⏳',
            };
        @endphp
        <div class="order-card">

            <div class="order-card-header">
                <div class="order-meta">
                    <div class="order-meta-item">
                        <small>Order Number</small>
                        <strong>#{{ $o->order_number }}</strong>
                    </div>
                    <div class="order-meta-item">
                        <small>Date</small>
                        <strong>{{ $o->created_at->format('d M Y') }}</strong>
                    </div>
                    <div class="order-meta-item">
                        <small>Total</small>
                        <strong>₹{{ number_format($o->total_amount, 2) }}</strong>
                    </div>
                    <div class="order-meta-item">
                        <small>Payment</small>
                        <strong>{{ strtoupper($o->payment_method ?? '-') }}</strong>
                    </div>
                </div>
                <span class="status-pill {{ $statusClass }}">
                    {{ $statusIcon }} {{ ucfirst($o->order_status ?? 'Pending') }}
                </span>
            </div>

            @if($o->order_items && $o->order_items->count())
            <div class="order-card-body">
                @foreach($o->order_items->take(3) as $item)
                <div class="order-item-row">
                    <img src="{{ asset('product_image/' . ($item->product->product_image ?? '')) }}"
                         onerror="this.src='{{ asset('website-assets/img/placeholder.jpg') }}'">
                    <div>
                        <div class="order-item-name">{{ $item->product->product_name ?? 'Product' }}</div>
                        <div class="order-item-meta">Qty: {{ $item->qty }} &nbsp;|&nbsp; ₹{{ number_format($item->price, 2) }}</div>
                    </div>
                    <div class="ml-auto">
                        <strong style="font-size:14px;">₹{{ number_format($item->qty * $item->price, 2) }}</strong>
                    </div>
                </div>
                @endforeach

                @if($o->order_items->count() > 3)
                    <div class="text-muted" style="font-size:12px; padding-top:6px;">
                        +{{ $o->order_items->count() - 3 }} more item(s)
                    </div>
                @endif
            </div>
            @endif

            <div class="order-card-footer">
                <div style="font-size:13px; color:var(--mid);">
                    Payment: <span class="badge-{{ $o->payment_status == 'paid' ? 'success' : 'warning' }}">
                        {{ ucfirst($o->payment_status ?? 'pending') }}
                    </span>
                </div>
                <div style="display:flex; gap:10px; flex-wrap:wrap;">
                    <a href="{{ route('website.my.order.details', $o->id) }}"
                       class="btn-outline-brand" style="padding:7px 16px; font-size:13px; border-radius:6px;">
                        View Details
                    </a>
                    @if(in_array(strtolower($o->order_status ?? ''), ['pending', 'processing', 'confirmed']))
                        <form method="POST" action="{{ route('website.orders.cancel', $o->id) }}"
                              onsubmit="return confirm('Cancel this order?')">
                            @csrf
                            <button type="submit"
                                    style="background:none; border:1px solid #dc2626; color:#dc2626; padding:7px 16px; border-radius:6px; font-size:13px; font-weight:600; cursor:pointer;">
                                Cancel
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
