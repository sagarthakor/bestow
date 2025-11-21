@extends('website.template.layout')
@section('title', 'Order Success')

@section('content')

    <style>
        .success-wrapper {
            max-width: 520px;
            margin: auto;
            background: #ffffff;
            border-radius: 12px;
            padding: 30px;
            box-shadow: 0 3px 12px rgba(0,0,0,0.08);
            text-align: center;
        }

        .success-icon {
            font-size: 60px;
            color: #28a745;
            margin-bottom: 10px;
        }

        .order-number-box {
            background: #f3f3f3;
            padding: 12px;
            border-radius: 8px;
            margin: 15px 0;
            font-size: 20px;
            font-weight: 700;
            color: #333;
        }

        @media (max-width: 576px) {
            .success-wrapper {
                padding: 20px;
                margin-top: 20px;
            }
            .success-icon {
                font-size: 50px;
            }
            .order-number-box {
                font-size: 18px;
            }
        }
    </style>

    <div class="container py-4 d-flex justify-content-center mt-5">
        <div class="success-wrapper">

            <div class="success-icon">🎉</div>

            <h3 class="text-success fw-bold">Order Placed Successfully!</h3>

            <p class="mt-2 mb-1">Your Order Number:</p>

            <div class="order-number-box">
                {{ $order->order_number }}
            </div>

            <p class="text-muted">We’ve sent the complete order details to your email/mobile.</p>

            <a href="{{ route('website.home') }}" class="btn btn-primary btn-lg mt-3 w-100">
                Continue Shopping
            </a>

        </div>
    </div>

@endsection
