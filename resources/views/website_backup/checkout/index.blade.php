@extends('website.template.layout')

@section('title', 'Checkout')

@section('content')
    <style>
        /* --- Responsive Layout --- */
        .checkout-wrapper {
            display: flex;
            gap: 20px;
            flex-wrap: wrap;
        }
        .checkout-left {
            flex: 1;
            min-width: 300px;
        }
        .checkout-right {
            width: 100%;
            max-width: 380px;
        }

        /* Mobile full width right column */
        @media (max-width: 768px) {
            .checkout-right {
                max-width: 100%;
            }
        }

        /* Cards */
        .checkout-card {
            background: #fff;
            border-radius: 8px;
            border: 1px solid #e3e3e3;
            padding: 16px;
        }

        /* Address radio block */
        .address-block {
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 10px;
            margin-bottom: 10px;
        }
        .address-block input {
            transform: scale(1.2);
            margin-right: 10px;
        }

        /* Order summary styling */
        .summary-item {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px solid #f1f1f1;
            font-size: 14px;
        }
        .summary-total {
            font-weight: 700;
            font-size: 16px;
        }

        /* Responsive form fields */
        .form-control, select {
            /*border-radius: 6px !important;*/
        }

        .checkout-title {
            font-size: 22px;
            font-weight: 700;
            margin-bottom: 18px;
        }

    </style>

    <div class="container py-4">

        <h3 class="checkout-title">Checkout</h3>

        <form id="checkoutForm" method="POST">
            @csrf

            <div class="checkout-wrapper">

                <!-- LEFT SIDE -->
                <div class="checkout-left">

                    {{-- ADDRESS SECTION --}}
                    <div class="checkout-card mb-3">
                        <h5 class="mb-3">Delivery Address</h5>

                        {{-- Existing Addresses --}}
                        @if(($addresses ?? collect())->count())
                            @foreach($addresses as $addr)
                                <label class="address-block">
                                    <input type="radio"
                                           name="selected_address_id"
                                           value="{{ $addr->id }}"
                                           class="select-existing-address"
                                           data-state="{{ $addr->state_id }}"
                                        {{ $addr->is_default ? 'checked' : '' }}>
                                    <div>
                                        <strong>{{ $addr->name }}</strong> — {{ $addr->phone }}<br>
                                        <span class="text-muted small">
                                        {{ $addr->address }},
                                        {{ optional($addr->state)->state_name }},
                                        {{ optional($addr->city)->city_name }} - {{ $addr->pincode }}
                                    </span>
                                        @if($addr->is_default)
                                            <span class="badge badge-success ml-2" style="width: 47px !important;">Default</span>
                                        @endif
                                    </div>
                                </label>
                            @endforeach
                        @endif

                        {{-- Add New Address --}}
                        <label class="address-block">
                            <input type="radio"
                                   name="selected_address_id"
                                   value=""
                                   class="select-new-address"
                                {{ ($addresses ?? collect())->isEmpty() ? 'checked' : '' }}>
                            <strong>Use a new address</strong>
                        </label>

                        {{-- NEW ADDRESS FORM --}}
                        <div id="newAddressFields" class="mt-3">
                            <div class="row">
                                <div class="col-md-6 mt-2">
                                    <label>Name *</label>
                                    <input type="text" name="name" class="form-control"
                                           value="{{ session('user')['name'] ?? old('name') }}">
                                </div>

                                <div class="col-md-6 mt-2">
                                    <label>Phone *</label>
                                    <input type="text" name="phone" class="form-control"
                                           value="{{ session('user')['phone'] ?? old('phone') }}">
                                </div>

                                <div class="col-12 mt-2">
                                    <label>Address *</label>
                                    <input type="text" name="address" class="form-control"
                                           placeholder="House No, Street, Area">
                                </div>

                                <div class="col-md-4 mt-2">
                                    <label>State *</label>
                                    <select name="state_id" id="stateDropdown" class="form-control">
                                        <option value="">Select State</option>
                                        @foreach($states as $state)
                                            <option value="{{ $state->id }}">{{ $state->state_name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-4 mt-2">
                                    <label>City *</label>
                                    <select name="city_id" id="cityDropdown" class="form-control">
                                        <option value="">Select City</option>
                                    </select>
                                </div>

                                <div class="col-md-4 mt-2">
                                    <label>Pincode *</label>
                                    <input type="text" name="pincode" id="pincode" class="form-control">
                                </div>
                            </div>

                            <div class="form-check mt-3">
                                <input type="checkbox" class="form-check-input" id="save_address" name="save_address" value="1">
                                <label class="form-check-label">Save this address</label>
                            </div>

                            <div class="form-check mt-2">
                                <input type="checkbox" class="form-check-input" id="is_default" name="is_default" value="1">
                                <label class="form-check-label">Set as default</label>
                            </div>
                        </div>

                    </div>

                </div>

                <!-- RIGHT SIDE -->
                <div class="checkout-right">

                    {{-- ORDER SUMMARY --}}
                    <div class="checkout-card mb-3">
                        <h5 class="mb-3">Order Summary</h5>

                        @foreach ($cartItems as $item)
                            <div class="summary-item">
                                <span>{{ $item->product->product_name }} (x{{ $item->qty }})</span>
                                <span>₹{{ number_format($item->qty * $item->price, 2) }}</span>
                            </div>
                        @endforeach

                        <div class="summary-item">
                            <span>Subtotal</span>
                            <span id="subtotalAmount" data-subtotal="{{ $subtotal }}">
                            ₹{{ number_format($subtotal, 2) }}
                        </span>
                        </div>

                        <div class="summary-item">
                            <span>Shipping</span>
                            <span id="shippingAmount" data-current="{{ $shippingCharge }}">
                            ₹{{ number_format($shippingCharge, 2) }}
                        </span>
                        </div>

                        <div class="summary-item summary-total">
                            <span>Total Payable</span>
                            <span id="finalAmount">
                            ₹{{ number_format($finalTotal, 2) }}
                        </span>
                        </div>

                        {{-- PAYMENT METHOD --}}
                        <label class="mt-3">Payment Method *</label>
                        <select name="payment_method" id="paymentMethod" class="form-control" required>
                            <option value="COD">Cash on Delivery</option>
                            <option value="razorpay">Razorpay (Online Payment)</option>
                        </select>

                        <button class="btn btn-primary btn-lg mt-3 w-100" type="submit">
                            Place Order
                        </button>
                    </div>

                </div>

            </div> {{-- wrapper end --}}
        </form>

    </div>

    {{-- Razorpay --}}
    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>

    <script>
        // Enable/disable new address fields based on radio selection
        function toggleNewAddressFields() {
            const selected = document.querySelector('input[name="selected_address_id"]:checked');
            const usingExisting = selected && selected.value !== '';
            document.querySelectorAll('#newAddressFields input, #newAddressFields select').forEach(el => {
                el.disabled = usingExisting;
                el.required = !usingExisting && ['name','phone','address','state_id','city_id','pincode'].includes(el.name);
            });
            document.getElementById('save_address').disabled = usingExisting;
            document.getElementById('is_default').disabled = usingExisting;
        }

        function formatINR(n){
            return new Intl.NumberFormat('en-IN', { minimumFractionDigits: 2, maximumFractionDigits: 2 }).format(n);
        }

        // Call server to compute shipping + final total
        function refreshShippingByState(stateId){
            const subtotal = parseFloat(document.getElementById('subtotalAmount').dataset.subtotal || 0);
            if(!stateId || !subtotal){ return; }
            fetch("{{ route('website.checkout.shipping') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ state_id: stateId, subtotal: subtotal })
            }).then(r => r.json()).then(res => {
                const shipEl = document.getElementById('shippingAmount');
                const finalEl = document.getElementById('finalAmount');
                shipEl.dataset.current = res.shipping || 0;
                shipEl.textContent = '₹' + formatINR(res.shipping || 0);
                finalEl.textContent = '₹' + formatINR(res.final_total || subtotal);
            }).catch(() => {});
        }

        // Radio change (existing vs new)
        document.querySelectorAll('input[name="selected_address_id"]').forEach(r => {
            r.addEventListener('change', function(){
                toggleNewAddressFields();
                // if existing selected -> compute by that address state
                if(this.value){
                    const st = this.getAttribute('data-state');
                    if(st){ refreshShippingByState(st); }
                } else {
                    // "Use a new address" selected -> wait for state dropdown change
                    const stSel = document.getElementById('stateDropdown');
                    if(stSel && stSel.value){ refreshShippingByState(stSel.value); }
                }
            });
        });

        // Initial toggle
        toggleNewAddressFields();

        // Load cities when state changes & recompute shipping
        $('#stateDropdown').on('change', function() {
            let stateId = $(this).val();
            $('#cityDropdown').html('<option>Loading...</option>');
            if(stateId){
                // recompute shipping for new-address flow
                const usingExisting = document.querySelector('input[name="selected_address_id"]:checked')?.value;
                if(!usingExisting){ refreshShippingByState(stateId); }
            }
            $.get("{{ url('/get-cities') }}/" + stateId, function(data) {
                $('#cityDropdown').html('<option value="">Select City</option>');
                $.each(data, function(_, city) {
                    $('#cityDropdown').append(`<option value="${city.id}">${city.city_name}</option>`);
                });
            });
        });

        // Submit order
        $('#checkoutForm').on('submit', function(e){
            e.preventDefault();

            $.post("{{ route('website.checkout.place') }}", $(this).serialize(), function(res){
                if(res.payment_method === 'razorpay'){
                    var options = {
                        key: res.key,
                        amount: res.amount, // already grandTotal * 100 (paise)
                        currency: "INR",
                        name: "Order Payment",
                        description: "Order #"+res.order_id,
                        order_id: res.razorpay_order_id,
                        handler: function (response){
                            $.post("{{ route('website.payment.verify') }}", {
                                _token: '{{ csrf_token() }}',
                                razorpay_payment_id: response.razorpay_payment_id,
                                razorpay_order_id: res.razorpay_order_id,
                                razorpay_signature: response.razorpay_signature
                            }, function(verifyRes){
                                if(verifyRes.status){
                                    window.location.href = verifyRes.redirect;
                                }
                            });
                        }
                    };
                    var rzp = new Razorpay(options);
                    rzp.open();
                } else if(res.redirect_url){
                    window.location.href = res.redirect_url;
                } else if(res.message){
                    alert(res.message);
                }
            }).fail(function(xhr){
                alert(xhr.responseJSON?.message || 'Something went wrong.');
            });
        });
    </script>
@endsection
