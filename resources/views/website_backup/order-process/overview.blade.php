@extends('website.template.layout')
@section('title', 'Order Overview: ' . $order->customer_order_id)
@section('content')
    <section class="pt-5 mb-4">
        <div class="container">
            <div class="row">
                <div class="col-xl-8 mx-auto">
                    <div class="row aiz-steps arrow-divider">
                        <div class="col done">
                            <div class="text-center text-success">
                                <i class="la-3x mb-2 las la-shopping-cart"></i>
                                <h3 class="fs-14 fw-600 d-none d-lg-block">@lang('1. My Cart')</h3>
                            </div>
                        </div>
                        <div class="col done">
                            <div class="text-center text-success">
                                <i class="la-3x mb-2 las la-map"></i>
                                <h3 class="fs-14 fw-600 d-none d-lg-block">@lang('2. Shipping info')</h3>
                            </div>
                        </div>
                        <div class="col done">
                            <div class="text-center text-success">
                                <i class="la-3x mb-2 las la-credit-card"></i>
                                <h3 class="fs-14 fw-600 d-none d-lg-block">@lang('3. Payment')</h3>
                            </div>
                        </div>
                        @if($order->status == \App\Models\Order::APPROVED && $order->payment_status == \App\Models\Order::PAYMENT_SUCCESS)
                            <div class="col done">
                                <div class="text-center text-success">
                                    <i class="la-3x mb-2 las la-check-circle"></i>
                                    <h3 class="fs-14 fw-600 d-none d-lg-block">@lang('4. Confirmation')</h3>
                                </div>
                            </div>
                        @elseif($order->payment_status != \App\Models\Order::PAYMENT_SUCCESS)
                            <div class="col active">
                                <div class="text-center text-primary">
                                    <i class="la-3x mb-2 las la-check-circle"></i>
                                    <h3 class="fs-14 fw-600 d-none d-lg-block">@lang('4. Confirmation')</h3>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="py-4">
        <div class="container text-left">
            <div class="row">
                <div class="col-xl-8 mx-auto">
                    <div class="text-center py-4 mb-4">
                        @if($order->status == \App\Models\Order::APPROVED && $order->payment_status == \App\Models\Order::PAYMENT_SUCCESS)
                            <i class="la la-check-circle la-3x text-success mb-3"></i>
                            <h1 class="h3 mb-3 fw-600">@lang('Thank You for Your Order!')</h1>
                            <p class="mb-2 fw-600">Payment is successfully processed and your order is on the way</p>
                            <p>Order ID : {{ $order->customer_order_id }}</p>
                            <p class="opacity-70 font-italic">@lang('You can view your order summary and track your order through the <b>My Order History</b> page.')</p>
                        @elseif($order->payment_status != \App\Models\Order::PAYMENT_SUCCESS)
                            <div class="success-text"><i class="la la-times-circle-o la-3x  text-danger"
                                                         aria-hidden="true"></i>
                                <p class="h6 mb-3 fw-600">Something goes wrong with Your Payment</p>
                                <p class="h6 mb-3 fw-600">We're unable to place your order due to Payment
                                    Failure..!!</p>
                            </div>
                        @endif
                    </div>
                    <div class="mb-4 bg-white p-4 rounded shadow-sm">
                        <h5 class="fw-600 mb-3 fs-17 pb-2">@lang('Order Summary')<span class="h5 float-right">@lang('Order Id :') <span
                                    class="fw-700 text-primary">{{ $order->customer_order_id }}</span></span></h5>

                        <div class="row">
                            <div class="col-md-6">
                                <table class="table">
                                    <tr>
                                        <td class="w-50 fw-600">@lang('Order date'):</td>
                                        <td>{{ $order->created_at->format('M d, Y h:i A') }}</td>
                                    </tr>
                                    <tr>
                                        <td class="w-50 fw-600">@lang('Name'):</td>
                                        <td>{{ $order->shipping_address->name }} </td>
                                    </tr>
                                    <tr>
                                        <td class="w-50 fw-600">@lang('Email'):</td>
                                        <td>{{ $order->shipping_address->email }} </td>
                                    </tr>
                                    <tr>
                                        <td class="w-50 fw-600">@lang('Shipping address'):</td>
                                        <td>
                                            {{ $order->shipping_address->address }}
                                            {{ $order->shipping_address->city->name }},
                                            {{ $order->shipping_address->state->name }},
                                            {{ $order->shipping_address->country->name }}
                                            - {{ $order->shipping_address->pin_code }}

                                            Contact No. {{ $order->shipping_address->mobile }}
                                            </ul>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <table class="table">
                                    <tr>
                                        <td class="w-50 fw-600">@lang('Order BV') :</td>
                                        <td>{{ number_format($order->total_bv) }}</td>
                                    </tr>
                                    <tr>
                                        <td class="w-50 fw-600">@lang('Total order amount') :</td>
                                        <td>₹ {{ number_format($order->total) }}</td>
                                    </tr>
                                    <tr>
                                        <td class="w-50 fw-600">@lang('Shipping') :</td>
                                        <td>₹ {{ number_format($order->shipping_charge) }} (@lang('Flat shipping rate')
                                            )
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="w-50 fw-600">@lang('Payment Status'):</td>
                                        <td>
                                            @if($order->payment_status ==1 )
                                                @lang('Checkout')
                                            @elseif($order->payment_status ==2)
                                                @lang('Success')
                                            @elseif($order->payment_status ==3)
                                                @lang('Pending')
                                            @else
                                                @lang('Failed')
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="w-50 fw-600">@lang('Payment method'):</td>
                                        <td>
                                            @if($order->payment_source ==1 )
                                                @lang('Wallet')
                                            @elseif($order->payment_source ==2)
                                                @lang('Online')
                                            @else
                                                @lang('Mixed')
                                            @endif
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div class="card shadow-sm border-0 rounded">
                        <div class="card-body">
                            <div>
                                <h5 class="fw-600 mb-3 fs-17 pb-2">@lang('Order Details')</h5>
                                <div>
                                    <table class="table table-responsive-md">
                                        <thead>
                                        <tr>
                                            <th>#</th>
                                            <th width="30%">@lang('Product')</th>
                                            <th>@lang('Quantity')</th>
                                            <th class="text-right">@lang('Price')</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($order->details as $key => $detail)
                                            <tr>
                                                <td>{{ $key+1 }}</td>
                                                <td width="45%">
                                                    @if ($detail != null)
                                                        <a href="#" target="_blank" class="text-reset">
                                                            <img width="55"
                                                                 alt="{{ $detail->product_price->product->name }}"
                                                                 src="{{ $detail->product_price->primary_image }}"
                                                                 class="img-fluid rounded">
                                                            {{ $detail->product_price->product->name }}
                                                        </a>
                                                    @else
                                                        <strong>@lang('Product Unavailable')</strong>
                                                    @endif
                                                </td>
                                                <td>
                                                    {{ number_format($detail->qty) }} Qty
                                                </td>
                                                <td class="text-right">
                                                    ₹ {{ number_format($detail->total_amount, 2) }}</td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                <div class="row">
                                    <div class="col-xl-5 col-md-6 ml-auto mr-0">
                                        <table class="table ">
                                            <tbody>
                                            <tr>
                                                <th>@lang('Subtotal')</th>
                                                <td class="text-right">
                                                    <span class="fw-600">₹ {{ number_format($order->amount,2) }}</span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>@lang('Shipping')</th>
                                                <td class="text-right">
                                                    <span
                                                        class="font-italic">₹ {{ number_format($order->shipping_charge,2) }}</span>
                                                </td>
                                            </tr>
                                            {{-- <tr>
                                                <th>@lang('Tax')</th>
                                                <td class="text-right">
                                                    <span class="font-italic">₹ 0.00</span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>@lang('Coupon Discount')</th>
                                                <td class="text-right">
                                                    <span class="font-italic">₹ 0.00</span>
                                                </td>
                                            </tr> --}}
                                            @if($order->online_amount > 0)
                                                <tr>
                                                    <th>@lang('Online Pay')</th>
                                                    <td class="text-right">
                                                    <span
                                                        class="font-italic">₹ {{number_format($order->online_amount,2)}}</span>
                                                    </td>
                                                </tr>
                                            @endif
                                            <tr>
                                                <th><span class="fw-600">@lang('Total')</span></th>
                                                <td class="text-right">
                                                    <strong><span>₹ {{ number_format($order->total,2) }}</span></strong>
                                                </td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
