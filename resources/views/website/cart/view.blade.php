@extends('website.template.layout')
@section('title', 'Shopping Cart')

@section('page-css')
<style>
    .cart-page { background: var(--light-bg); min-height: 60vh; }

    /* Cart item row */
    .cart-item {
        background: #fff;
        border-radius: 10px;
        border: 1px solid var(--border);
        padding: 16px;
        margin-bottom: 12px;
        display: flex;
        gap: 16px;
        align-items: flex-start;
        transition: box-shadow .2s;
    }
    .cart-item:hover { box-shadow: var(--shadow); }

    .cart-item-img {
        width: 90px; height: 90px;
        object-fit: contain;
        border-radius: 8px;
        border: 1px solid var(--border);
        padding: 4px;
        background: #fff;
        flex-shrink: 0;
    }

    .cart-item-info { flex: 1; }
    .cart-item-name { font-size: 14px; font-weight: 600; color: var(--text); margin-bottom: 4px; }
    .cart-item-attr { font-size: 12px; color: var(--mid); margin-bottom: 6px; }
    .cart-item-price { font-size: 18px; font-weight: 700; color: var(--brand); }

    .cart-item-actions { display: flex; align-items: center; gap: 12px; flex-wrap: wrap; margin-top: 8px; }

    /* Qty control */
    .qty-ctrl {
        display: flex;
        align-items: center;
        border: 1px solid var(--border);
        border-radius: 8px;
        overflow: hidden;
    }
    .qty-ctrl button {
        width: 34px; height: 34px;
        border: none;
        background: #f8f9fa;
        font-size: 18px;
        cursor: pointer;
        display: flex; align-items: center; justify-content: center;
        transition: background .15s;
    }
    .qty-ctrl button:hover { background: var(--brand); color: #fff; }
    .qty-ctrl input {
        width: 46px; height: 34px;
        text-align: center;
        border: none;
        border-left: 1px solid var(--border);
        border-right: 1px solid var(--border);
        font-size: 14px; font-weight: 600;
        outline: none;
        font-family: 'Poppins', sans-serif;
    }

    .remove-btn {
        font-size: 12px; color: #dc2626; background: none; border: none;
        cursor: pointer; font-weight: 600; padding: 0; display: flex; align-items: center; gap: 4px;
    }
    .remove-btn:hover { text-decoration: underline; }

    /* Summary card */
    .cart-summary {
        background: #fff;
        border-radius: 12px;
        border: 1px solid var(--border);
        padding: 20px;
        position: sticky;
        top: 80px;
    }
    .cart-summary h5 { font-size: 18px; font-weight: 700; margin-bottom: 18px; color: var(--navy); }

    .sum-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 8px 0;
        font-size: 14px;
        border-bottom: 1px dashed var(--border);
    }
    .sum-row:last-of-type { border-bottom: none; }
    .sum-row.total { font-size: 18px; font-weight: 700; color: var(--brand); padding-top: 14px; }

    .checkout-cta {
        display: block;
        background: var(--brand);
        color: #fff !important;
        text-align: center;
        padding: 14px;
        border-radius: 8px;
        font-size: 16px;
        font-weight: 700;
        margin-top: 18px;
        transition: background .2s;
    }
    .checkout-cta:hover { background: var(--brand-dark); }

    .continue-link {
        display: block;
        text-align: center;
        font-size: 13px;
        color: var(--mid);
        margin-top: 12px;
    }
    .continue-link:hover { color: var(--brand); }

    /* Empty cart */
    .empty-cart {
        text-align: center;
        padding: 60px 20px;
        background: #fff;
        border-radius: 12px;
        border: 1px solid var(--border);
    }
    .empty-cart i { font-size: 72px; color: #d1d5db; }
    .empty-cart h4 { font-size: 20px; font-weight: 700; color: var(--navy); margin: 16px 0 8px; }
    .empty-cart p { color: var(--mid); margin-bottom: 20px; }
</style>
@endsection

@section('content')
@php
    $dbCart = isset($cart) ? $cart : null;
    $items  = $dbCart ? $dbCart->items : collect();
@endphp

<!-- Breadcrumb -->
<div class="ec-breadcrumb">
    <div class="container">
        <ol>
            <li><a href="{{ route('website.home') }}">Home</a></li>
            <li>Shopping Cart</li>
        </ol>
    </div>
</div>

<div class="container py-4 cart-page">

    <div class="section-header mb-4">
        <h4>Shopping Cart
            @if($items->isNotEmpty())
                <span style="font-size:14px; font-weight:400; color:var(--mid);">({{ $items->count() }} {{ $items->count() == 1 ? 'item' : 'items' }})</span>
            @endif
        </h4>
    </div>

    <div class="row">

        <!-- ── CART ITEMS ───────────────────────── -->
        <div class="col-lg-8 mb-4">
            @if($items->isEmpty())
                <div class="empty-cart">
                    <i class="la la-shopping-cart"></i>
                    <h4>Your Cart is Empty</h4>
                    <p>Looks like you haven't added anything to your cart yet.</p>
                    <a href="{{ route('website.product.view') }}" class="btn-brand" style="display:inline-block; padding:12px 32px;">
                        Start Shopping
                    </a>
                </div>
            @else
                @foreach($items as $row)
                    <div class="cart-item" id="item-{{ $row->variant_id }}">
                        <img class="cart-item-img"
                             src="{{ asset('product_image/' . ($row->image ?? '')) }}"
                             onerror="this.src='{{ asset('website-assets/img/placeholder.jpg') }}'">

                        <div class="cart-item-info">
                            <div class="cart-item-name">{{ $row->product->clean_name ?: ($row->product->product_name ?? 'Product') }}</div>

                            @if($row->product->attribute1 ?? false)
                                <div class="cart-item-attr">
                                    {{ $row->product->attribute1 }}: {{ $row->product->value1 ?? '-' }}
                                    @if($row->product->attribute2 ?? false)
                                        &nbsp;|&nbsp; {{ $row->product->attribute2 }}: {{ $row->product->value2 ?? '-' }}
                                    @endif
                                </div>
                            @endif

                            <div class="cart-item-price">₹{{ number_format($row->price ?? 0, 2) }}</div>

                            <div class="cart-item-actions">
                                <div class="qty-ctrl">
                                    <button onclick="updateQty({{ $row->variant_id }}, -1)">−</button>
                                    <input type="text" value="{{ $row->qty ?? 1 }}" readonly id="qty-{{ $row->variant_id }}">
                                    <button onclick="updateQty({{ $row->variant_id }}, 1)">+</button>
                                </div>

                                <button class="remove-btn" onclick="removeItem({{ $row->variant_id }})">
                                    <i class="la la-trash-alt"></i> Remove
                                </button>
                            </div>
                        </div>

                        <div class="text-right flex-shrink-0" style="min-width:80px;">
                            <strong style="font-size:16px;">
                                ₹{{ number_format(($row->price ?? 0) * ($row->qty ?? 1), 2) }}
                            </strong>
                        </div>
                    </div>
                @endforeach
            @endif
        </div>

        <!-- ── ORDER SUMMARY ───────────────────── -->
        @if($items->isNotEmpty())
        <div class="col-lg-4">
            <div class="cart-summary">
                <h5>Order Summary</h5>

                @php
                    $subtotal = $items->sum(fn($r) => ($r->price ?? 0) * ($r->qty ?? 1));
                    $shipping = $subtotal >= 499 ? 0 : 60;
                    $total    = $subtotal + $shipping;
                @endphp

                <div class="sum-row">
                    <span>Subtotal ({{ $items->count() }} items)</span>
                    <strong>₹{{ number_format($subtotal, 2) }}</strong>
                </div>
                <div class="sum-row">
                    <span>Shipping</span>
                    @if($shipping == 0)
                        <strong style="color:#16a34a;">FREE</strong>
                    @else
                        <strong>₹{{ number_format($shipping, 2) }}</strong>
                    @endif
                </div>
                <div class="sum-row total">
                    <span>Total Payable</span>
                    <span>₹{{ number_format($total, 2) }}</span>
                </div>

                @if($subtotal < 499)
                    <div style="background:#fef9c3; border-radius:6px; padding:8px 12px; font-size:12px; margin-top:10px; color:#713f12;">
                        <i class="la la-info-circle"></i>
                        Add ₹{{ number_format(499 - $subtotal, 2) }} more for <strong>FREE delivery</strong>!
                    </div>
                @else
                    <div style="background:#dcfce7; border-radius:6px; padding:8px 12px; font-size:12px; margin-top:10px; color:#14532d;">
                        <i class="la la-check-circle"></i> You qualify for <strong>FREE delivery</strong>!
                    </div>
                @endif

                <a href="{{ route('website.checkout.view') }}" class="checkout-cta">
                    Proceed to Checkout <i class="la la-arrow-right"></i>
                </a>

                <a href="{{ route('website.product.view') }}" class="continue-link">
                    <i class="la la-arrow-left"></i> Continue Shopping
                </a>

                <!-- Safe checkout badges -->
                <div class="text-center mt-3">
                    <small class="text-muted d-block mb-2">Secure checkout</small>
                    <div style="display:flex; gap:8px; justify-content:center; flex-wrap:wrap;">
                        <span style="background:#f8f9fa; border:1px solid #ddd; border-radius:4px; padding:3px 8px; font-size:11px;">
                            <i class="la la-lock" style="color:#16a34a;"></i> SSL Secure
                        </span>
                        <span style="background:#f8f9fa; border:1px solid #ddd; border-radius:4px; padding:3px 8px; font-size:11px;">
                            COD Available
                        </span>
                        <span style="background:#f8f9fa; border:1px solid #ddd; border-radius:4px; padding:3px 8px; font-size:11px;">
                            Razorpay
                        </span>
                    </div>
                </div>
            </div>
        </div>
        @endif

    </div>
</div>

@endsection

@section('page-javascript')
<script>
function updateQty(variant_id, diff) {
    axios.post("{{ route('website.cart.update') }}", { variant_id, diff, _token: window.csrfToken })
        .then(() => location.reload())
        .catch(() => window.toast('Update failed', 'error'));
}

function removeItem(variant_id) {
    axios.post("{{ route('website.cart.remove') }}", { variant_id, _token: window.csrfToken })
        .then(() => location.reload())
        .catch(() => window.toast('Remove failed', 'error'));
}
</script>
@endsection
