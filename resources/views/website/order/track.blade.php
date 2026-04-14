@extends('website.template.layout')
@section('title', 'Track Your Order')

@section('page-css')
<style>
    .track-page {
        min-height: 60vh;
        background: linear-gradient(135deg, #f8f9fa, #fff3ef);
        padding: 50px 0;
    }
    .track-card {
        background: #fff;
        border-radius: 16px;
        box-shadow: 0 8px 40px rgba(0,0,0,.08);
        max-width: 520px;
        margin: 0 auto;
        overflow: hidden;
    }
    .track-card-header {
        background: linear-gradient(135deg, #1a1f36, #2d3561);
        padding: 28px 30px;
        color: #fff;
        text-align: center;
    }
    .track-icon {
        font-size: 48px;
        margin-bottom: 12px;
        display: block;
    }
    .track-card-header h4 { font-size: 22px; font-weight: 700; margin: 0 0 6px; }
    .track-card-header p  { font-size: 13px; opacity: .75; margin: 0; }
    .track-card-body { padding: 30px; }

    .btn-track {
        display: block; width: 100%;
        background: var(--brand); color: #fff;
        border: none; border-radius: 8px;
        padding: 13px; font-size: 15px; font-weight: 700;
        cursor: pointer; transition: background .2s;
        font-family: 'Poppins', sans-serif;
        margin-top: 6px;
    }
    .btn-track:hover { background: var(--brand-dark); }

    /* Track result */
    .track-result {
        border-radius: 12px;
        border: 1px solid var(--border);
        overflow: hidden;
        margin-top: 24px;
    }
    .track-result-header {
        background: var(--brand);
        color: #fff;
        padding: 14px 18px;
        font-weight: 700;
    }
    .track-result-body { padding: 18px; }

    .timeline-step {
        display: flex; gap: 12px; margin-bottom: 14px;
    }
    .t-dot {
        width: 28px; height: 28px; border-radius: 50%;
        background: #e5e7eb; display: flex; align-items: center; justify-content: center;
        font-size: 12px; font-weight: 700; flex-shrink: 0;
    }
    .t-dot.done   { background: #16a34a; color: #fff; }
    .t-dot.active { background: var(--brand); color: #fff; }
    .t-label { font-size: 14px; font-weight: 600; }
</style>
@endsection

@section('content')
<div class="track-page">
    <div class="container">

        <div class="track-card">
            <div class="track-card-header">
                <span class="track-icon">📦</span>
                <h4>Track Your Order</h4>
                <p>Enter your order number and phone to track delivery</p>
            </div>

            <div class="track-card-body">

                @if(session('track_error'))
                    <div class="alert alert-danger" style="font-size:13px;">
                        <i class="la la-exclamation-triangle"></i> {{ session('track_error') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('website.track.order.submit') }}">
                    @csrf

                    <div class="form-group-ec" style="margin-bottom:16px;">
                        <label style="font-size:13px; font-weight:600; margin-bottom:6px; display:block;">Order Number *</label>
                        <div style="position:relative;">
                            <i class="la la-hashtag" style="position:absolute; left:14px; top:50%; transform:translateY(-50%); color:#9ca3af; font-size:18px;"></i>
                            <input type="text" name="order_number" class="form-control"
                                   placeholder="e.g. ORD-2024-0001"
                                   style="padding-left:44px !important;"
                                   value="{{ old('order_number') }}" required>
                        </div>
                    </div>

                    <div class="form-group-ec" style="margin-bottom:20px;">
                        <label style="font-size:13px; font-weight:600; margin-bottom:6px; display:block;">Registered Phone *</label>
                        <div style="position:relative;">
                            <i class="la la-phone" style="position:absolute; left:14px; top:50%; transform:translateY(-50%); color:#9ca3af; font-size:18px;"></i>
                            <input type="text" name="phone" class="form-control"
                                   placeholder="Your phone number"
                                   style="padding-left:44px !important;"
                                   value="{{ old('phone') }}" required>
                        </div>
                    </div>

                    <button type="submit" class="btn-track">
                        <i class="la la-search"></i> Track Order
                    </button>
                </form>

                @if(isset($order))
                    <div class="track-result">
                        <div class="track-result-header">
                            Order #{{ $order->order_number }}
                        </div>
                        <div class="track-result-body">
                            @php
                                $steps = [
                                    ['label' => 'Order Placed',  'key' => ['pending']],
                                    ['label' => 'Confirmed',     'key' => ['confirmed', 'processing']],
                                    ['label' => 'Shipped',       'key' => ['shipped', 'dispatched']],
                                    ['label' => 'Delivered',     'key' => ['delivered']],
                                ];
                                $statusOrder = ['pending' => 0, 'processing' => 1, 'confirmed' => 1, 'shipped' => 2, 'dispatched' => 2, 'delivered' => 3, 'cancelled' => -1];
                                $currentLevel = $statusOrder[strtolower($order->order_status ?? 'pending')] ?? 0;
                            @endphp

                            @foreach($steps as $idx => $step)
                                <div class="timeline-step">
                                    <div class="t-dot {{ $idx < $currentLevel ? 'done' : ($idx == $currentLevel ? 'active' : '') }}">
                                        {{ $idx < $currentLevel ? '✓' : ($idx + 1) }}
                                    </div>
                                    <div>
                                        <div class="t-label">{{ $step['label'] }}</div>
                                        <small style="font-size:12px; color:{{ $idx <= $currentLevel ? 'var(--brand)' : 'var(--mid)' }};">
                                            {{ $idx < $currentLevel ? 'Completed' : ($idx == $currentLevel ? 'Current' : 'Upcoming') }}
                                        </small>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <div class="text-center mt-4">
                    <small style="color:var(--mid); font-size:12px;">
                        Need help? Call us at <a href="tel:+918154876897" style="color:var(--brand); font-weight:600;">+91 81548 76897</a>
                    </small>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
