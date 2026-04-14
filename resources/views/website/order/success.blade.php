@extends('website.template.layout')
@section('title', 'Order Placed!')

@section('page-css')
<style>
    .success-page {
        min-height: 70vh;
        display: flex;
        align-items: center;
        background: linear-gradient(135deg, #f0fdf4 0%, #fff 50%, #fef2f0 100%);
        padding: 40px 0;
    }

    .success-card {
        background: #fff;
        border-radius: 20px;
        box-shadow: 0 12px 48px rgba(0,0,0,.1);
        max-width: 520px;
        width: 100%;
        margin: 0 auto;
        overflow: hidden;
    }

    .success-card-top {
        background: linear-gradient(135deg, #16a34a, #15803d);
        padding: 36px 32px 28px;
        text-align: center;
        color: #fff;
        position: relative;
    }
    .success-icon-ring {
        width: 80px; height: 80px;
        border-radius: 50%;
        background: rgba(255,255,255,.15);
        border: 3px solid rgba(255,255,255,.4);
        display: flex; align-items: center; justify-content: center;
        font-size: 40px;
        margin: 0 auto 16px;
        animation: bounceIn .6s ease;
    }
    @keyframes bounceIn {
        0%   { transform: scale(0); opacity:0; }
        60%  { transform: scale(1.1); opacity:1; }
        100% { transform: scale(1); }
    }
    .success-card-top h3 { font-size: 24px; font-weight: 800; margin: 0 0 6px; }
    .success-card-top p  { font-size: 14px; opacity: .85; margin: 0; }

    .success-card-body { padding: 28px 32px; }

    .order-number-box {
        background: linear-gradient(135deg, #fef2f0, #fff);
        border: 2px dashed var(--brand);
        border-radius: 10px;
        padding: 16px;
        text-align: center;
        margin-bottom: 22px;
    }
    .order-number-box small { font-size: 12px; color: var(--mid); display: block; margin-bottom: 4px; text-transform: uppercase; letter-spacing: .8px; }
    .order-number-box strong { font-size: 22px; font-weight: 800; color: var(--brand); }

    .info-pills {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
        justify-content: center;
        margin-bottom: 24px;
    }
    .info-pill {
        background: #f8f9fa;
        border: 1px solid var(--border);
        border-radius: 8px;
        padding: 8px 14px;
        font-size: 12px;
        color: var(--mid);
        display: flex; align-items: center; gap: 6px;
    }
    .info-pill strong { color: var(--text); }

    .btn-continue {
        display: block;
        background: var(--brand);
        color: #fff !important;
        text-align: center;
        padding: 14px;
        border-radius: 10px;
        font-size: 15px;
        font-weight: 700;
        margin-bottom: 12px;
        transition: background .2s;
    }
    .btn-continue:hover { background: var(--brand-dark); }

    .btn-orders {
        display: block;
        background: #f8f9fa;
        color: var(--text) !important;
        text-align: center;
        padding: 12px;
        border-radius: 10px;
        font-size: 14px;
        font-weight: 600;
        border: 1px solid var(--border);
        transition: background .2s;
    }
    .btn-orders:hover { background: #f1f5f9; }

    @media(max-width:576px) {
        .success-card-body { padding: 20px; }
        .success-card-top  { padding: 28px 20px 20px; }
    }
</style>
@endsection

@section('content')
<div class="success-page">
    <div class="container">
        <div class="success-card">

            <div class="success-card-top">
                <div class="success-icon-ring">✓</div>
                <h3>Order Placed!</h3>
                <p>Thank you for shopping with us. Your order is confirmed.</p>
            </div>

            <div class="success-card-body">

                <div class="order-number-box">
                    <small>Your Order Number</small>
                    <strong>{{ $order->order_number }}</strong>
                </div>

                <div class="info-pills">
                    <div class="info-pill">
                        <i class="la la-credit-card" style="color:var(--brand);"></i>
                        <span>Payment: <strong>{{ strtoupper($order->payment_method ?? 'COD') }}</strong></span>
                    </div>
                    <div class="info-pill">
                        <i class="la la-rupee-sign" style="color:var(--brand);"></i>
                        <span>Total: <strong>₹{{ number_format($order->total_amount ?? 0, 2) }}</strong></span>
                    </div>
                    <div class="info-pill">
                        <i class="la la-truck" style="color:var(--brand);"></i>
                        <span>Est. Delivery: <strong>3–7 Days</strong></span>
                    </div>
                </div>

                <div style="background:#f0fdf4; border-radius:8px; padding:12px 16px; font-size:13px; color:#14532d; margin-bottom:22px; display:flex; gap:8px;">
                    <i class="la la-envelope" style="font-size:18px; flex-shrink:0; margin-top:1px;"></i>
                    <span>Order confirmation details have been sent to your registered email/mobile.</span>
                </div>

                <a href="{{ route('website.home') }}" class="btn-continue">
                    <i class="la la-shopping-bag"></i> Continue Shopping
                </a>

                <a href="{{ route('website.orders') }}" class="btn-orders">
                    <i class="la la-list-alt"></i> View All Orders
                </a>

            </div>
        </div>
    </div>
</div>
@endsection
