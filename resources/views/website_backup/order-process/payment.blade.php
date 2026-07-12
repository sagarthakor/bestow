@extends('website.template.layout')
@section('title', 'Order Payment')
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
                    <div class="col active">
                        <div class="text-center text-primary">
                            <i class="la-3x mb-2 las la-credit-card"></i>
                            <h3 class="fs-14 fw-600 d-none d-lg-block">@lang('3. Payment')</h3>
                        </div>
                    </div>
                    <div class="col">
                        <div class="text-center">
                            <i class="la-3x mb-2 opacity-50 las la-check-circle"></i>
                            <h3 class="fs-14 fw-600 d-none d-lg-block opacity-50">@lang('4. Confirmation')</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="mb-4 gry-bg" id="paymentPage">
    <div class="container">
        <div class="row cols-xs-space cols-sm-space cols-md-space">
            <div class="col-md-8 offset-md-2 offset-lg-2">
                <div class="card shadow-sm border-0 rounded">
                    <div class="card-header p-3">
                        <h3 class="fs-16 fw-600 mb-0">
                            @lang('Checkout - Order Payment')
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <h5>Order Summary</h5>
                                <ul class="list-group">
                                    <li class="list-group-item font-weight-bolder">
                                        Name : <span class="float-right">{{ $order->address->name }} ({{ $order->address->mobile }})</span>
                                    </li>
                                    <li class="list-group-item font-weight-bolder">
                                        Address : <span class="float-right">
                                            {{ $order->address->address }}, {{ $order->address->city->name }}, {{ $order->address->state->name }}-{{ $order->address->pin_code }}.
                                        </span>
                                    </li>
                                    <li class="list-group-item font-weight-bolder">
                                        Amount : <span
                                        class="float-right">@{{ orderSummary.amount | currency }}</span>
                                    </li>
                                    <li class="list-group-item font-weight-bolder">
                                        Shipping Charge (+) :<span
                                        class="float-right">@{{ orderSummary.shipping_charge | currency }}</span>
                                    </li>
                                    <li class="list-group-item font-weight-bolder" v-if="isWalletUse">
                                        Wallet (-) :<span
                                        class="float-right">@{{ orderSummary.wallet | currency }}</span>
                                    </li>
                                    <li class="list-group-item font-weight-bolder">
                                        Total Amount :<span class="float-right">@{{ orderSummary.total_amount | currency }}</span>
                                    </li>
                                </ul>
                            </div>
                            <div class="col-md-6">
                                <h5>Payment</h5>
                                <ul class="list-group">
                                    <li class="list-group-item">
                                        <h4 class="text-dark">Shopping Wallet</h4>
                                        <img src="/user-assets/images/svg/wallet.svg" width="40px">
                                        <h3 class="float-right text-danger font-weight-bolder">
                                        @{{ shopping_wallet_balance | currency }}</h3>
                                    </li>
                                    <li class="list-group-item">
                                        <label class="aiz-megabox d-block bg-white mb-1"
                                        :for="'selectpaymentGateway' + index"
                                        v-for="(paymentGateway, index) in paymentGateways" :key="index">
                                        <input type="radio" v-model="paymentGatewayId"
                                        :value="paymentGateway.id" :id="'selectpaymentGateway' + index"
                                        :checked="paymentGateways.length === 1"/>
                                        <span class="d-flex aiz-megabox-elem">
                                            <span class="text-center">
                                                <img width="40%" :alt="paymentGateway.name"
                                                :src="paymentGateway.logo"/>
                                            </span>
                                        </span>
                                    </label>
                                </li>
                            </ul>
                            <label class="aiz-checkbox mt-2">
                                <input type="checkbox" id="agree_checkbox" v-model="isWalletUse">
                                <span class="aiz-square-check"></span>
                                <span>@lang('Check here to use wallet.')</span>
                            </label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="pt-3">
                <label class="aiz-checkbox">
                    <input type="checkbox" checked id="agree_checkbox">
                    <span class="aiz-square-check"></span>
                    <span>@lang('I agree to the')</span>
                </label>
                <a href="{{ url('/shopping/policy/terms-conditions/1') }}">@lang('terms and conditions')</a>,
                <a href="{{ url('/shopping/policy/refund-exchange-policy/4') }}">@lang('return policy')</a> &
                <a href="{{ url('/shopping/policy/privacy-policy/2') }}">@lang('privacy policy')</a>
            </div>
            <div class="row align-items-center pt-3">
                <div class="col-6">
                    <a href="{{ route('website.product.view') }}" class="link link--style-3">
                        <i class="las la-arrow-left"></i>
                        @lang('Return to shop')
                    </a>
                </div>
                <div class="col-6 text-right">
                    <button type="button" @click="processOrder" :disabled="orderProcessStarted"
                    class="btn btn-primary fw-600">@lang('Complete Order')</button>
                </div>
            </div>
        </div>
    </div>
</div>
</section>
@endsection

@section('import-javascript')
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
@stop

@section('page-javascript')
<script>
    document.addEventListener('wheel', function (e) {
        if (this.orderProcessStarted) {
            e.preventDefault();
        }
    }, {
        passive: false
    });

    Vue.prototype.$http = axios;
    new Vue({
        el: '#paymentPage',
        data: {
            accountType: @json(app('accountType')),
            items: @json($order->items),
            orderSummary: @json($order->orderSummary),
            shippingAddressId: @json($order->address->id),
            orderProcessStarted: false,
            shopping_wallet_balance: @json($user->shopping_wallet_balance),
            paymentGateways: @json(\App\Order::getPaymentGateway()),
            paymentGatewayId: null,
            isWalletUse: false,
        },
        watch: {
            isWalletUse: function (val) {
                this.useWallet()
            },
            paymentGatewayId: function (val) {
                if (this.shopping_wallet_balance > 0 && this.orderSummary.total_amount == 0) {
                    this.paymentGatewayId = null;
                        // swal('Warning', 'Your Wallet balance is applicable to payable amount.', 'warning');
                        return false;
                    }
                }
            },
            methods: {
                useWallet() {

                    if (this.shopping_wallet_balance == 0) {
                        this.isWalletUse = false;
                        swal('Warning', 'You do not have enough "Shopping Wallet Balance"', 'warning');
                        return false;
                    }

                    if (this.isWalletUse) {
                        let payableAmount = _.round(this.orderSummary.amount + this.orderSummary.shipping_charge);
                        this.orderSummary.wallet = this.shopping_wallet_balance > payableAmount ? payableAmount : this.shopping_wallet_balance;

                        let totalAmount = payableAmount - this.orderSummary.wallet;
                        this.orderSummary.total_amount = totalAmount + this.orderSummary.service_charge;
                    } else {
                        this.orderSummary.total_amount = _.round(this.orderSummary.amount + this.orderSummary.shipping_charge, 2);
                        this.orderSummary.wallet = 0;
                    }

                    if (this.paymentGatewayId && this.orderSummary.total_amount == 0) {
                        this.paymentGatewayId = null;
                    }
                },
                processOrder: function () {

                    if (!this.isWalletUse && !this.paymentGatewayId) {
                        swal('Warning', 'Payment gateway selection is required.', 'warning');
                        return false;
                    }

                    if (this.isWalletUse && this.orderSummary.total_amount > 0 && !this.paymentGatewayId) {
                        swal('Warning', 'Payment gateway selection is required for remaining amount of Rs.' + this.orderSummary.total_amount, 'warning');
                        return false;
                    }

                    swal('Processing', 'Do not Close or Refresh this page..!!', 'info');

                    this.orderProcessStarted = true;

                    let random_seconds = (Math.floor(Math.random() * 6) + 2) * 1000;

                    setTimeout(() => {

                        ANALOG.blockUI(true);

                        this.$http.post('{{ route("website.order.checkout.process") }}', {
                            items: this.items,
                            orderSummary: this.orderSummary,
                            shippingAddressId: this.shippingAddressId,
                            paymentGatewayId: this.paymentGatewayId,
                            isWalletUse: this.isWalletUse
                        }).then(response => {
                            ANALOG.blockUI(false);

                            if (response.data.status) {
                                if (response.data.amount === 0) {
                                    swal('Order is placed', response.data.message, 'success');
                                    setTimeout(() => {
                                        window.location = response.data.overview_route;
                                    }, 1000);
                                } else {
                                    if (parseInt(this.paymentGatewayId) === 2) {
                                        this.openRazorPay(response.data);
                                    } else {
                                        swal('Error', 'Select valid payment mode.', 'error');
                                    }
                                }

                            } else {
                                ANALOG.blockUI(false);
                                swal('Error', response.data.message, 'error');
                                this.orderProcessStarted = false;
                            }

                        });

                    }, random_seconds);
                },
                openRazorPay: function (orderResponse) {

                    let options = {
                        "key": '{{ env("RAZORPAY_KEY") }}',
                        "amount": orderResponse.amount * 100,
                        "name": "{{ ucwords(config('project.brand')) }}",
                        "description": 'Health Care & Personal Care Items',
                        "image": "https://nexwaves.in/logo/logo.png",
                        "handler": function (response) {
                            window.location.href = '{{ route("website.order.payment.response") }}?customer_order_id=' + orderResponse.customer_order_id + '&reference_id=' + response.razorpay_payment_id;
                        },
                        "prefill": {
                            'name': orderResponse.user.name,
                            'contact': orderResponse.user.mobile,
                            'email': orderResponse.user.email
                        },
                        "theme": {
                            "color": "#ec6333"
                        },
                        "notes": {
                            "customer_order_id": orderResponse.customer_order_id
                        },
                        "modal": {
                            escape: false,
                            "ondismiss": function () {
                                window.location.href = '{{ route("website.order.payment.response") }}?customer_order_id=' + orderResponse.customer_order_id + '&custom_status=CANCEL';
                            }

                        }
                    };

                    let razorPay = new Razorpay(options);
                    razorPay.open();
                },
            },
        });
    </script>
    @endsection
