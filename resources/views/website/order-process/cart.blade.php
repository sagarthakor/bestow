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
    <div class="container" v-if="items.length == 0">
        <div class="row">
            <div class="col-xl-8 mx-auto">
                <div class="shadow-sm bg-white p-4 rounded">
                    <div class="text-center p-3">
                        <i class="las la-frown la-3x opacity-60 mb-3"></i>
                        <h3 class="h4 fw-700">@lang('Your Cart is empty')</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <section class="mb-4">
        <div class="container" v-if="items.length > 0">
            <div class="row">
                <div class="col-xxl-8 col-xl-10 mx-auto">
                    <div class="shadow-sm bg-white p-3 p-lg-4 rounded text-left">
                        <div class="mb-4">
                            <div class="row gutters-5 d-none d-lg-flex border-bottom mb-3 pb-3">
                                <div class="col-md-5 fw-600">@lang('Product')</div>
                                <div class="col fw-600">@lang('Price')</div>
                                <div class="col fw-600">@lang('Quantity')</div>
                                <div class="col fw-600">@lang('Total')</div>
                                <div class="col-auto fw-600">@lang('Remove')</div>
                            </div>
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item px-0 px-lg-3">
                                    <div class="row gutters-5" v-for="(item,index) in items">
                                        <div class="col-lg-5 d-flex">
                                            <span class="mr-2 ml-0">
                                                <a :href="'/product/details/' + item.slug + '/' + item.code">
                                                    <img class="img-fit size-60px rounded" :alt="item.product"
                                                    :src="item.image"
                                                    alt="item.product">
                                                </a>
                                            </span>
                                            <span class="fs-14 opacity-80">@{{ item.product }}</span>&nbsp;
                                            <span
                                            class="badge badge-warning badge-inline badge-pill absolute-top-right--10px"
                                            v-if="item.offer_id"> Offer Item</span>
                                        </div>
                                        <div class="col-lg col-4 order-1 order-lg-0 my-3 my-lg-0">
                                            <h5 class="opacity-80 fs-14">
                                                <span class="font-small-2"
                                                v-if="accountType === 'GUEST'"><del>@{{ item.price | currency }}</del>
                                            @{{ item.selling_price | currency }}</span>
                                            <span class="discount-cross font-small-2"
                                            v-if="accountType === 'USER'">
                                            <del>@{{ item.price | currency }}</del>
                                        @{{ item.selling_price | currency }}</span>
                                    </h5>&nbsp;
                                    <span
                                    class="d-inline-block rounded px-2 bg-soft-primary border-soft-primary border text-white"
                                    v-if="accountType === 'USER'"> BV : @{{ item.bv }}</span>

                                </div>
                                <div class="col-lg col-6 order-4 order-lg-0">
                                    <div class="offer-item-qty" v-if="qualifiedOffer">
                                        @{{ item.quantity }} Qty
                                    </div>
                                    <div class="input-group" v-if="!qualifiedOffer"
                                    style="margin-top: 0.0rem;">
                                    <counter-btn v-model="item.quantity" min="1" max="100"
                                    :value="item.quantity"
                                    :price-id="item.id"></counter-btn>
                                </div>
                            </div>
                            <div class="col-lg col-4 order-3 order-lg-0 my-3 my-lg-0">
                               <span class="fw-600 fs-16 text-primary" v-if="accountType === 'GUEST'">
                                @{{ _.round(item.quantity * item.selling_price, 2) | currency }}
                            </span>
                            <span class="fw-600 fs-16 text-primary" v-if="accountType === 'USER'">
                                @{{ _.round(item.quantity * item.selling_price, 2) | currency }}
                            </span>
                        </div>
                        <div class="col-lg-auto col-6 order-5 order-lg-0 text-right">
                            <a href="javascript:void(0)" @click="removeItem(item.id)"
                            class="btn btn-icon btn-sm btn-soft-primary btn-circle text-white">
                            <i class="las la-trash"></i>
                        </a>

                    </div>
                </div>
            </li>
        </ul>
    </div>

    <div class="px-3 py-2 mb-2 border-top d-flex justify-content-between">
        <span class="opacity-60 fs-15">@lang('Subtotal')</span>
        <span class="fw-600 fs-17">@{{ orderSummary.amount | currency }}</span>
    </div>
    <div class="px-3 py-2 mb-2 border-top d-flex justify-content-between">
        <span class="opacity-60 fs-15">@lang('Shipping Charge')</span>
        <span class="fw-600 fs-17">@{{ orderSummary.shipping_charge | currency }}</span>
    </div>
    <div class="px-3 py-2 mb-2 border-top bg-soft-dark d-flex justify-content-between">
        <span class="fs-15 text-white">@lang('Total')</span>
        <span
        class="d-inline-block rounded px-2 bg-soft-primary border-soft-primary border text-white fw-600 fs-15"
        v-if="accountType === 'USER'">Total BV : @{{ orderSummary.total_bv }} BV</span>
        <span class="fw-600 fs-17 text-white">@{{ orderSummary.total_amount | currency }}</span>
    </div>
    <div class="row align-items-center">
        <div class="col-md-6 text-center text-md-left order-1 order-md-0">
            <a href="{{ route('website.product.view') }}" class="btn btn-link">
                <i class="las la-arrow-left"></i>
                @lang('Return to shop')
            </a>
        </div>
        <div class="col-md-6 text-center text-md-right" v-if="items.length > 0">
            <button class="btn btn-primary fw-600" v-if="!qualifiedOffer"
            type="button"
            @click="checkOffer">@lang('Continue to Shipping')
        </button>
        <a href="javascript:void(0)" @click="checkout"
        class="btn btn-primary fw-600" v-if="qualifiedOffer">
        @lang('Continue to Shipping') <i class="fa fa-arrow-right"></i>
    </a>
</div>
<div class="col-md-12 text-center font-medium-2 mt-2" v-if="qualifiedOffer">
    OR To Modify The Order
    <br>

    <a href="javascript:void(0)" class="btn btn-solid bg-warning text-dark"
    @click="removeOffer">Remove Offer <i class="fa fa-trash"></i></a>
</div>

</div>
</div>
</div>
{{-- Item Selection Modal --}}
<div class="modal fade" id="offerAutoItemSelectionModal" tabindex="-1" role="dialog"
aria-labelledby="myModalLabelauto" aria-hidden="true">
<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title" id="myModalLabelauto">
                Select Any One Option to
                <b class="text-danger">Apply Offer</b></h4>
            </div>
            <div class="modal-body">
                <div v-for="(offerItemOption, index) in offerItemOptions">
                    <h5>
                        Option @{{ (index+1) }}
                        <button type="button" @click="selectOfferOption(offerItemOption)"
                        class="btn btn-danger btn-sm pull-right">Select Option @{{ (index+1)
                    }}
                </button>
            </h5>
            <hr>
            <ul class="list-group mb-1 mt-1">
                <li class="list-group-item" v-for="item in offerItemOption">
                    @{{ item.product | str_limit(40) }}
                    <span class="pull-right">Qty: @{{ item.quantity }}</span>
                </li>
            </ul>
            <hr v-if="index != Object.keys(offerItemOption).length - 1">
        </div>
    </div>
</div>
</div>
</div>
<div class="modal fade" id="offerManualItemSelectionModal" tabindex="-1" role="dialog"
aria-labelledby="myModalLabelmanual" aria-hidden="true">
<div class="modal-dialog modal-lg">
    <div class="modal-content" v-if="qualifiedOffer">
        <div class="modal-header">
            <h4 class="modal-title text-dark" id="myModalLabelmanual">
                Choose Items from Below List Upto Rs. @{{qualifiedOffer.offer_amount }}
            </h4>
        </div>
        <div class="modal-body">
            <div class="table-responsive">
                <table class="table table-bordered" style="table-layout: fixed;">
                    <thead>
                        <tr>
                            <th width="5%">#</th>
                            <th>Name</th>
                            <th>Qty</th>
                            <th>Selling Price</th>
                            <th>Offer Price</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(offer_item, index) in offerItemOptions">
                            <td>@{{ index+1 }}</td>
                            <td>@{{ offer_item.product }}</td>
                            <td>
                                <div class="qty-box">
                                    <counter-btn :min="offer_item.minimum_qty"
                                    :max="offer_item.maximum_qty"
                                    :max-alert="'You can not add qty more than ' + offer_item.maximum_qty + ' of ' + offer_item.product"
                                    v-model="offer_item.quantity">
                                </counter-btn>
                            </div>
                        </td>
                        <td>@{{ offer_item.distributor_price }}
                        </td>
                        <td>@{{ offer_item.selling_price }}</td>
                    </tr>
                    <tr>
                        <td colspan="2"></td>
                        <td class="bg-danger text-white">@{{ offerItemSummary.qty }}</td>
                        <td class="bg-danger text-white">@{{ offerItemSummary.total }}</td>
                        <td>-</td>
                    </tr>
                </tbody>
            </table>
        </div>
        <hr>
        <div class="text-center" v-if="offerItemSummary.total > 0">
            <button type="button" @click="addOfferItems()"
            v-if="(orderUser == 1 || orderUser == 3) && offerItemSummary.total <= qualifiedOffer.offer_amount"
            class="btn btn-danger">
            Add Items
        </button>
        <div
        v-if="(orderUser == 1 || orderUser == 3) && offerItemSummary.total > qualifiedOffer.offer_amount"
        class="alert alert-danger">
        Offer is applicable till Rs. @{{ qualifiedOffer.offer_amount }} Only
    </div>
    <button type="button" @click="addOfferItems()"
    v-if="orderUser == 2 && offerItemSummary.total <= qualifiedOffer.offer_customer_amount"
    class="btn btn-danger">
    Add Items
</button>
<div
v-if="orderUser == 2 && offerItemSummary.total > qualifiedOffer.offer_customer_amount"
class="alert alert-danger">
Offer is applicable till Rs. @{{ qualifiedOffer.offer_customer_amount }}
Only
</div>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
</section>
</section>
@endsection
@section('page-javascript')
<script>
    Vue.prototype.$http = axios;
    new Vue({
        el: '#cartPage',
        data: {
            accountType: @json(app('accountType')),
            items: @json($items),
            orderSummary: {
                total_bv: 0, total_qty: 0, amount: 0, shipping_charge: 0, wallet: 0,
                discount: 0, service_charge: 0, total_amount: 0
            },
            /* Offer Variable */
            activeOffers: {!! collect($active_offers)->toJson() !!},
            offerItemOptions: [],
            offerItemSummary: {
                total: 0, qty: 0
            },
            qualifiedOffer: null,
        },
        mounted: function () {
            this.cartManager();
        },
        watch: {
            items: {
                handler() {
                    this.cartManager();
                },
                deep: true
            },
            offerItemOptions: {
                handler() {
                    this.offerItemSummary.total = _.round(_.sumBy(this.offerItemOptions, offerItem => {
                        return _.round(offerItem.quantity) * offerItem.distributor_price;
                    }), 2);

                    this.offerItemSummary.qty = _.round(_.sumBy(this.offerItemOptions, offerItem => {
                        return _.round(offerItem.quantity);
                    }));

                },
                deep: true
            }
        },
        methods: {
            cartManager() {
                this.orderSummary.total_bv = _.sumBy(this.items, item => {
                    return item.quantity * item.bv;
                });
                this.orderSummary.total_qty = _.sumBy(this.items, item => {
                    return item.quantity;
                });
                this.orderSummary.amount = _.chain(this.items).sumBy(item => {
                    return item.quantity * parseFloat(item.selling_price);
                }).round(2).value();

                this.orderSummary.shipping_charge = this.calculateShippingCharge();

                if (parseInt(this.orderSummary.paymentSourceId) === 1 || parseInt(this.orderSummary.paymentSourceId) === 3) {
                    let payableAmount = _.round(this.orderSummary.amount + this.orderSummary.shipping_charge);
                    this.orderSummary.wallet = this.shopping_wallet_balance > payableAmount ? payableAmount : this.shopping_wallet_balance;
                    let totalAmount = payableAmount - this.orderSummary.wallet;
                    this.orderSummary.total_amount = totalAmount + this.orderSummary.service_charge;
                } else {
                    this.orderSummary.total_amount = _.round(this.orderSummary.amount + this.orderSummary.shipping_charge, 2);
                    this.orderSummary.wallet = 0;
                }
            },
            calculateShippingCharge() {

                let shippingCharge = 0;
                let totalAmount = this.orderSummary.amount;

                if (totalAmount <= 2500) {
                    shippingCharge = 99;
                } else {
                    shippingCharge = 0;
                }
                return shippingCharge;
            },
            removeItem(item_id) {

                let self = this;

                swal({
                    title: `Are you sure?`,
                    text: 'Are You sure to remove this item ?',
                    icon: "warning",
                    buttons: true,
                    dangerMode: true,
                }).then((confirm) => {
                    if (confirm) {
                        self.$http.get('{{ route("website.cart.remove") }}', {
                            params: {
                                removeAllQty: true,
                                priceId: item_id
                            }
                        }).then(response => {

                            if (response.data.status) {
                                self.items = _.chain(self.items).reject(item => {
                                    return parseInt(item.id) === parseInt(item_id);
                                }).value();

                                if (parseInt(response.data.total_items) === 0) {
                                    location.reload();
                                } else {
                                    location.reload();
                                }
                            } else {
                                swal("Oops", response.data.message, "error");
                            }
                        })
                    }
                });
            },
            checkout() {

                ANALOG.blockUI(true);

                this.$http.post('{{ route("website.cart.checkout") }}', {
                    items: this.items,
                    orderSummary: this.orderSummary
                }).then(response => {

                    ANALOG.blockUI(false);

                    if (response.data.status) {
                        window.location = response.data.redirect_route;
                    } else {
                        swal('Oops', response.data.message, 'error');
                    }

                });
            },
            async checkOffer() {

                /* Priority is Offer Type 2 */
                let qualifiedOffer = null;
                let condition_item_exists = null;

                /*Get type 2 offers*/
                let productToProductExists = _.chain(this.activeOffers).filter(active_offer => {
                    return (parseInt(active_offer.type) === 2);
                }).value();

                if (productToProductExists.length > 0) {

                    _.map(productToProductExists, p2POffer => {
                        let condition_item = p2POffer.details.condition_items[0];

                        if (!qualifiedOffer) {

                            condition_item_exists = _.chain(this.items).filter(item => {
                                return parseInt(item.id) === parseInt(condition_item.product_price_id) && parseInt(item.quantity) >= parseInt(condition_item.qty);
                            }).head().value();

                            if (condition_item_exists && !qualifiedOffer) {
                                condition_item_exists.required_qty = condition_item.qty;
                                qualifiedOffer = p2POffer;
                            }

                        }
                    });
                }

                if (!qualifiedOffer) {
                    qualifiedOffer = _.chain(this.activeOffers).filter(active_offer => {
                        return (
                            (parseInt(active_offer.qualified) === 0) && (
                                (parseInt(active_offer.billing_type) === 2 ? parseFloat(this.orderSummary.total_bv) : parseFloat(this.orderSummary.total_amount)
                                    ) >= parseFloat(active_offer.min_amount)
                                ) && (
                                (parseInt(active_offer.billing_type) === 2 ? parseFloat(this.orderSummary.total_bv) : parseFloat(this.orderSummary.total_amount)
                                    ) <= parseFloat(active_offer.max_amount)
                                )
                                );
                    }).head().value();
                }

                if (qualifiedOffer) {

                    _.map(this.activeOffers, activeOffer => {
                        if (parseInt(activeOffer.id) === parseInt(qualifiedOffer.id)) {
                            activeOffer.qualified = 1;
                        }
                        return activeOffer;
                    });

                    this.qualifiedOffer = qualifiedOffer;

                    ANALOG.blockUI(true);

                    this.$http.post('{{ route("website.cart.load.offer.items") }}', {
                        offer_id: this.qualifiedOffer.id,
                        condition_item: condition_item_exists
                    }).then(response => {

                        if (response.data.status) {

                            setTimeout(() => {
                                ANALOG.blockUI(false);
                                swal("Offer Applied", this.qualifiedOffer.name + '. Click on Place Order for Order', "success");
                            }, 1000);

                            if (parseInt(this.qualifiedOffer.type) !== 3) {

                                let offer_items = _.chain(response.data.items).groupBy('group_id').toArray().value();

                                if (offer_items.length > 1) {

                                    this.offerItemOptions = offer_items;

                                    $('#offerAutoItemSelectionModal').modal('show', {
                                        backdrop: 'static', keyboard: false
                                    });

                                } else {
                                    this.items = this.items.concat(response.data.items);
                                }
                            } else {
                                this.items = this.items.concat(response.data.items);
                            }

                        } else {
                            ANALOG.blockUI(false);
                            swal("Oops", response.data.message, "error");
                        }
                    });

                } else {
                    this.checkout();
                }

            },
            /* Manual on Basis of User Selection */
            async addOfferItems() {

                ANALOG.blockUI(true);

                let selected_items = await _.chain(this.offerItemOptions).filter(offerItem => {
                    return _.round(offerItem.quantity) > 0;
                }).value();

                this.items = this.items.concat(selected_items);

                swal("Offer Applied", this.qualifiedOffer.name + '. Click on Place Order to Complete the order', "success");

                setTimeout(() => {
                    $('#offerManualItemSelectionModal').modal('hide');
                    this.offerItemOptions = [];
                    ANALOG.blockUI(false);
                }, 500);
            },
            selectOfferOption: function (selected_items) {

                $('#offerAutoItemSelectionModal').modal('hide');
                ANALOG.blockUI(true);
                setTimeout(() => {
                    this.offerItemOptions = [];
                    this.items = this.items.concat(selected_items);
                    ANALOG.blockUI(false);
                }, 500);
            },
            removeOffer: function () {
                if (this.qualifiedOffer) {
                    ANALOG.blockUI(true);

                    setTimeout(() => {
                        ANALOG.blockUI(false);
                    }, 1000);

                    /* Remove exists Offered Item */
                    this.items = _.chain(this.items).reject(item => {
                        return item.offer_id;
                    }).value();

                    _.map(this.activeOffers).map(activeOffer => {

                        if (parseInt(activeOffer.id) === parseInt(this.qualifiedOffer.id)) {
                            activeOffer.qualified = 0;
                        }
                        return activeOffer;
                    });

                    this.qualifiedOffer = null;
                }
            },
        }
    });
</script>
@endsection
