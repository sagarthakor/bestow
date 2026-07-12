@extends('website.template.layout')
@section('title', 'Shopping Cart')
@section('content')

    <section class="pt-5 mb-4">
        <div class="container">
            <div class="row">
                <div class="col-xl-8 mx-auto">
                    <div class="row aiz-steps arrow-divider">
                        <div class="col active">
                            <div class="text-center text-primary">
                                <i class="la-3x mb-2 las la-shopping-cart"></i>
                                <h3 class="fs-14 fw-600 d-none d-lg-block">@lang('1. My Cart')</h3>
                            </div>
                        </div>
                        <div class="col">
                            <div class="text-center">
                                <i class="la-3x mb-2 opacity-50 las la-map"></i>
                                <h3 class="fs-14 fw-600 d-none d-lg-block opacity-50">@lang('2. Shipping info')</h3>
                            </div>
                        </div>
                        <div class="col">
                            <div class="text-center">
                                <i class="la-3x mb-2 opacity-50 las la-credit-card"></i>
                                <h3 class="fs-14 fw-600 d-none d-lg-block opacity-50">@lang('3. Payment')</h3>
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

    <section class="mb-4" id="cartPage">
        <div class="container" v-if="items.length === 0">
            <div class="row">
                <div class="col-xl-8 mx-auto">
                    <div class="shadow-sm bg-white p-4 rounded text-center">
                        <i class="las la-frown la-3x opacity-60 mb-3"></i>
                        <h3 class="h4 fw-700">@lang('Your Cart is empty')</h3>
                        <a href="{{ route('website.product.view') }}" class="btn btn-primary mt-3">
                            @lang('Return to shop')
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="container" v-else>
            <div class="row">
                <div class="col-xxl-8 col-xl-10 mx-auto">
                    <div class="shadow-sm bg-white p-3 p-lg-4 rounded text-left">
                        <!-- Cart Table Header -->
                        <div class="row gutters-5 d-none d-lg-flex border-bottom mb-3 pb-3">
                            <div class="col-md-5 fw-600">@lang('Product')</div>
                            <div class="col fw-600">@lang('Price')</div>
                            <div class="col fw-600">@lang('Quantity')</div>
                            <div class="col fw-600">@lang('Total')</div>
                            <div class="col-auto fw-600">@lang('Remove')</div>
                        </div>

                        <!-- Cart Items -->
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item px-0 px-lg-3" v-for="(item,index) in items" :key="item.id">
                                <div class="row gutters-5">
                                    <div class="col-lg-5 d-flex">
                                        <a :href="'/product/details/' + item.slug + '/' + item.code">
                                            <img class="img-fit size-60px rounded mr-2" :src="item.image" :alt="item.product">
                                        </a>
                                        <span class="fs-14 opacity-80">@{{ item.product }}</span>
                                        <span class="badge badge-warning badge-inline badge-pill absolute-top-right--10px" v-if="item.offer_id"> Offer Item</span>
                                    </div>
                                    <div class="col-lg col-4 order-1 order-lg-0 my-3 my-lg-0">
                                        <h5 class="opacity-80 fs-14">
                                        <span class="discount-cross font-small-2">
                                            @{{ item.price | currency }}
                                        </span>
                                        </h5>
                                        <span class="d-inline-block rounded px-2 bg-soft-primary border-soft-primary border text-white" v-if="accountType === 'USER'">
                                        BV : @{{ item.bv }}
                                    </span>
                                    </div>
                                    <div class="col-lg col-6 order-4 order-lg-0">
                                        <div class="input-group">
                                            <counter-btn v-model="item.quantity" min="1" max="100" :value="item.quantity" :price-id="item.id"></counter-btn>
                                        </div>
                                    </div>
                                    <div class="col-lg col-4 order-3 order-lg-0 my-3 my-lg-0">
                                    <span class="fw-600 fs-16 text-primary">
                                        @{{ (item.quantity * item.price) | currency }}
                                    </span>
                                    </div>
                                    <div class="col-lg-auto col-6 order-5 order-lg-0 text-right">
                                        @{{ item.id }}
                                        <a href="javascript:void(0)" @click="removeItem(item.variant_id)" class="btn btn-icon btn-sm btn-soft-primary btn-circle text-white">
                                            <i class="las la-trash"></i>
                                        </a>
                                    </div>
                                </div>
                            </li>
                        </ul>

                        <!-- Totals -->
                        <div class="px-3 py-2 mb-2 border-top d-flex justify-content-between">
                            <span class="opacity-60 fs-15">@lang('Subtotal')</span>
                            <span class="fw-600 fs-17">@{{ orderSummary.amount | currency }}</span>
                        </div>
                        <div class="px-3 py-2 mb-2 border-top d-flex justify-content-between">
                            <span class="opacity-60 fs-15">@lang('Shipping Charge')</span>
                            <span class="fw-600 fs-17">@{{ orderSummary.shipping_charge | currency }}</span>
                        </div>
                        <div class="px-3 py-2 mb-2 border-top bg-soft-danger d-flex justify-content-between">
                            <span class="fs-15 text-black">@lang('Total')</span>
                            <span class="fw-600 fs-17 text-black">@{{ orderSummary.total_amount | currency }}</span>
                            <span class="d-inline-block rounded px-2 bg-soft-primary border-soft-primary border text-white fw-600 fs-15" v-if="accountType === 'USER'">
                            Total BV : @{{ orderSummary.total_bv }} BV
                        </span>
                        </div>

                        <!-- Checkout Button -->
                        <div class="row align-items-center mt-3">
                            <div class="col-md-6 text-center text-md-left">
                                <a href="{{ route('website.product.view') }}" class="btn btn-link">
                                    <i class="las la-arrow-left"></i> @lang('Return to shop')
                                </a>
                            </div>
                            <div class="col-md-6 text-center text-md-right">
                                <button class="btn btn-primary fw-600" type="button" @click="checkout">@lang('Continue to Shipping')</button>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection

@section('page-javascript')
    <script>
        Vue.prototype.$http = axios;

        new Vue({
            el: '#cartPage',
            data: {
                accountType: @json($accountType),
                items: @json($items),
                orderSummary: {
                    total_bv: 0,
                    total_qty: 0,
                    amount: 0,
                    shipping_charge: 0,
                    total_amount: 0
                },
            },
            mounted() {
                this.updateCartSummary();
            },
            watch: {
                items: {
                    handler() {
                        this.updateCartSummary();
                    },
                    deep: true
                }
            },
            methods: {
                updateCartSummary() {
                    this.orderSummary.total_bv = _.sumBy(this.items, item => item.quantity * item.bv);
                    this.orderSummary.total_qty = _.sumBy(this.items, item => item.quantity);
                    this.orderSummary.amount = _.round(_.sumBy(this.items, item => item.quantity * item.price), 2);
                    this.orderSummary.shipping_charge = this.calculateShippingCharge();
                    this.orderSummary.total_amount = _.round(this.orderSummary.amount + this.orderSummary.shipping_charge, 2);
                },
                calculateShippingCharge() {
                    return this.orderSummary.amount <= 2500 ? 0 : 0;
                },
                removeItem(id) {
                    let self = this;
                    swal({
                        title: "Are you sure?",
                        text: "Do you want to remove this item?",
                        icon: "warning",
                        buttons: true,
                        dangerMode: true,
                    }).then((confirm) => {
                        if (confirm) {
                            self.$http.get('{{ route("website.cart.remove") }}', { params: { priceId: id }})
                                .then(response => {
                                    if (response.data.status) {
                                        self.items = response.data.cart; // update cart items
                                        self.updateCartSummary(); // recalc totals

                                        swal({
                                            title: "Removed!",
                                            text: response.data.message,
                                            icon: "success",
                                            timer: 1500,
                                            buttons: false,
                                        });

                                    } else {
                                        swal("Oops", response.data.message, "error");
                                    }
                                })
                                .catch(() => {
                                    swal("Oops", "Something went wrong. Try again!", "error");
                                });
                        }
                    });
                },
                checkout() {
                    this.$http.post('{{ route("website.cart.checkout") }}', {
                        items: this.items,
                        orderSummary: this.orderSummary
                    }).then(response => {
                        if(response.data.status) {
                            window.location = response.data.redirect_route;
                        } else {
                            swal("Oops", response.data.message, "error");
                        }
                    });
                }
            }
        });
    </script>
@endsection
