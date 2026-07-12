@extends('website.template.layout')
@section('title', 'Checkout')

@section('page-css')
<style>
    /* ── Step header ─────────────────────── */
    .checkout-steps {
        display: flex;
        justify-content: center;
        gap: 0;
        margin-bottom: 28px;
    }
    .checkout-step {
        display: flex;
        align-items: center;
        font-size: 13px;
        font-weight: 600;
        color: #9ca3af;
    }
    .checkout-step .step-num {
        width: 28px; height: 28px;
        border-radius: 50%;
        background: #e5e7eb;
        color: #9ca3af;
        display: flex; align-items: center; justify-content: center;
        font-size: 12px; font-weight: 700;
        margin-right: 6px;
    }
    .checkout-step.active { color: var(--brand); }
    .checkout-step.active .step-num { background: var(--brand); color: #fff; }
    .checkout-step.done .step-num { background: #16a34a; color: #fff; }
    .step-divider {
        width: 50px; height: 2px;
        background: #e5e7eb;
        margin: 0 8px;
        align-self: center;
    }

    /* ── Cards ───────────────────────────── */
    .co-card {
        background: #fff;
        border-radius: 12px;
        border: 1px solid var(--border);
        margin-bottom: 16px;
        overflow: hidden;
    }
    .co-card-header {
        padding: 14px 20px;
        border-bottom: 1px solid var(--border);
        background: #f8f9fa;
        display: flex; align-items: center; gap: 10px;
    }
    .co-card-header .step-badge {
        width: 24px; height: 24px;
        background: var(--brand); color: #fff;
        border-radius: 50%; display: flex; align-items: center; justify-content: center;
        font-size: 12px; font-weight: 700; flex-shrink: 0;
    }
    .co-card-header h6 { font-size: 15px; font-weight: 700; margin: 0; color: var(--navy); }
    .co-card-body { padding: 20px; }

    /* ── Address blocks ───────────────────── */
    .addr-radio-block {
        border: 2px solid var(--border);
        border-radius: 10px;
        padding: 14px;
        margin-bottom: 10px;
        cursor: pointer;
        transition: border-color .15s, background .15s;
    }
    .addr-radio-block:has(input:checked) {
        border-color: var(--brand);
        background: var(--brand-light);
    }
    .addr-radio-block label {
        display: flex; gap: 12px; cursor: pointer; margin: 0; align-items: flex-start;
    }
    .addr-radio-block input[type=radio] {
        accent-color: var(--brand);
        width: 16px; height: 16px; margin-top: 2px; flex-shrink: 0;
    }
    .addr-name { font-size: 14px; font-weight: 700; margin-bottom: 3px; }
    .addr-detail { font-size: 13px; color: var(--mid); line-height: 1.5; }
    .addr-default { background: var(--brand); color: #fff; font-size: 10px; font-weight: 700; padding: 2px 8px; border-radius: 10px; }

    /* ── Summary ──────────────────────────── */
    .co-summary {
        background: #fff;
        border-radius: 12px;
        border: 1px solid var(--border);
        position: sticky;
        top: 80px;
        overflow: hidden;
    }
    .co-summary-header {
        background: var(--navy); color: #fff; padding: 14px 18px;
        font-size: 15px; font-weight: 700;
    }
    .co-summary-body { padding: 16px 18px; }

    .sum-product {
        display: flex; gap: 10px; align-items: center;
        padding: 8px 0; border-bottom: 1px dashed var(--border);
        font-size: 13px;
    }
    .sum-product:last-of-type { border-bottom: none; }
    .sum-product img { width: 44px; height: 44px; object-fit: contain; border-radius: 6px; border: 1px solid var(--border); padding: 2px; flex-shrink: 0; }
    .sum-product .name { flex: 1; font-weight: 500; line-height: 1.3; }
    .sum-product .price { font-weight: 700; flex-shrink: 0; }

    .co-divider { border: none; border-top: 1px dashed var(--border); margin: 10px 0; }

    .sum-row {
        display: flex; justify-content: space-between;
        font-size: 14px; padding: 6px 0;
    }
    .sum-row.total { font-size: 18px; font-weight: 800; color: var(--brand); padding-top: 12px; }

    /* Payment methods */
    .pay-option {
        border: 2px solid var(--border);
        border-radius: 8px;
        padding: 12px 14px;
        margin-bottom: 8px;
        cursor: pointer;
        display: flex; align-items: center; gap: 12px;
        transition: border-color .15s, background .15s;
    }
    .pay-option:has(input:checked) {
        border-color: var(--brand); background: var(--brand-light);
    }
    .pay-option input { accent-color: var(--brand); flex-shrink: 0; }
    .pay-option .pay-icon { font-size: 22px; color: var(--brand); flex-shrink: 0; }
    .pay-option strong { font-size: 14px; display: block; }
    .pay-option small  { font-size: 12px; color: var(--mid); }

    .place-order-btn {
        display: block; width: 100%;
        background: var(--brand); color: #fff;
        border: none; border-radius: 10px;
        padding: 15px; font-size: 16px; font-weight: 800;
        cursor: pointer; transition: background .2s;
        font-family: 'Poppins', sans-serif;
        margin-top: 14px;
    }
    .place-order-btn:hover { background: var(--brand-dark); }
    .place-order-btn:disabled { background: #9ca3af; cursor: not-allowed; }
</style>
@endsection

@section('content')

<!-- Breadcrumb -->
<div class="ec-breadcrumb">
    <div class="container">
        <ol>
            <li><a href="{{ route('website.home') }}">Home</a></li>
            <li><a href="{{ route('website.cart.view') }}">Cart</a></li>
            <li>Checkout</li>
        </ol>
    </div>
</div>

<div class="container py-4">

    <!-- Steps -->
    <div class="checkout-steps">
        <div class="checkout-step done">
            <div class="step-num">✓</div> Cart
        </div>
        <div class="step-divider"></div>
        <div class="checkout-step active">
            <div class="step-num">2</div> Checkout
        </div>
        <div class="step-divider"></div>
        <div class="checkout-step">
            <div class="step-num">3</div> Confirmation
        </div>
    </div>

    <form id="checkoutForm" method="POST">
        @csrf

        <div class="row">

            <!-- ── LEFT ──────────────────────────── -->
            <div class="col-lg-7 mb-4">

                <!-- Delivery Address -->
                <div class="co-card">
                    <div class="co-card-header">
                        <div class="step-badge">1</div>
                        <h6>Delivery Address</h6>
                    </div>
                    <div class="co-card-body">

                        @if(($addresses ?? collect())->count())
                            @foreach($addresses as $addr)
                                <div class="addr-radio-block">
                                    <label>
                                        <input type="radio"
                                               name="selected_address_id"
                                               value="{{ $addr->id }}"
                                               class="select-existing-address"
                                               data-state="{{ $addr->state_id }}"
                                            {{ $addr->is_default ? 'checked' : '' }}>
                                        <div>
                                            <div class="addr-name">
                                                {{ $addr->name }}
                                                @if($addr->is_default)
                                                    <span class="addr-default ml-1">Default</span>
                                                @endif
                                            </div>
                                            <div class="addr-detail">
                                                {{ $addr->phone }}<br>
                                                {{ $addr->address }},
                                                {{ optional($addr->city)->city_name }},
                                                {{ optional($addr->state)->state_name }} – {{ $addr->pincode }}
                                            </div>
                                        </div>
                                    </label>
                                </div>
                            @endforeach
                        @endif

                        <div class="addr-radio-block">
                            <label>
                                <input type="radio" name="selected_address_id" value=""
                                       class="select-new-address"
                                    {{ ($addresses ?? collect())->isEmpty() ? 'checked' : '' }}>
                                <div>
                                    <div class="addr-name"><i class="la la-plus-circle" style="color:var(--brand);"></i> Use a new address</div>
                                    <div class="addr-detail" style="font-size:12px;">Fill in your delivery details below</div>
                                </div>
                            </label>
                        </div>

                        <!-- New address form -->
                        <div id="newAddressFields" class="mt-3">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label>Full Name *</label>
                                    <input type="text" name="name" class="form-control"
                                           value="{{ session('user')['name'] ?? old('name') }}"
                                           placeholder="Recipient's name">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label>Phone *</label>
                                    <input type="text" name="phone" class="form-control"
                                           value="{{ session('user')['phone'] ?? old('phone') }}"
                                           placeholder="10-digit mobile">
                                </div>
                                <div class="col-12 mb-3">
                                    <label>Address *</label>
                                    <input type="text" name="address" class="form-control"
                                           placeholder="House No, Street, Area, Landmark">
                                </div>
                                <div class="col-md-4 mb-3" id="stateDropdownCol">
                                    <label>State *</label>
                                    <select name="state_id" id="stateDropdown" class="form-control">
                                        <option value="">Select State</option>
                                        @foreach($states as $state)
                                            <option value="{{ $state->id }}">{{ $state->state_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-4 mb-3" id="stateManualCol" style="display:none;">
                                    <label>State * <span style="font-size:11px;color:var(--brand);">(manual)</span></label>
                                    <input type="text" name="state_name_custom" id="stateManualInput"
                                           class="form-control" placeholder="Type your state name"
                                           disabled>
                                </div>

                                <div class="col-md-4 mb-3" id="cityDropdownCol">
                                    <label>City *</label>
                                    <select name="city_id" id="cityDropdown" class="form-control">
                                        <option value="">Select City</option>
                                    </select>
                                </div>
                                <div class="col-md-4 mb-3" id="cityManualCol" style="display:none;">
                                    <label>City * <span style="font-size:11px;color:var(--brand);">(manual)</span></label>
                                    <input type="text" name="city_name_custom" id="cityManualInput"
                                           class="form-control" placeholder="Type your city name"
                                           disabled>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label>PIN Code *</label>
                                    <input type="text" name="pincode" id="pincode" class="form-control"
                                           placeholder="6-digit PIN">
                                </div>
                            </div>

                            {{-- Hidden mode field --}}
                            <input type="hidden" name="address_mode" id="addressMode" value="dropdown">

                            {{-- Manual toggle --}}
                            <div style="margin-bottom:12px;">
                                <button type="button" id="toggleManualBtn"
                                    style="background:none;border:none;padding:0;font-size:13px;color:var(--brand);cursor:pointer;font-family:'Poppins',sans-serif;text-decoration:underline;">
                                    <i class="la la-question-circle"></i> Can't find your state / city?
                                </button>
                                <span id="manualActiveBadge" style="display:none;margin-left:8px;background:#fef2f0;color:var(--brand);font-size:11px;font-weight:700;padding:2px 10px;border-radius:20px;border:1px solid var(--brand);">
                                    Manual entry ON
                                </span>
                            </div>

                            <div style="display:flex; gap:20px; flex-wrap:wrap;">
                                <label style="font-size:13px; cursor:pointer; display:flex; gap:6px; align-items:center; font-weight:400; margin:0;">
                                    <input type="checkbox" name="save_address" value="1" id="save_address" style="accent-color:var(--brand);">
                                    Save this address
                                </label>
                                <label style="font-size:13px; cursor:pointer; display:flex; gap:6px; align-items:center; font-weight:400; margin:0;">
                                    <input type="checkbox" name="is_default" value="1" id="is_default" style="accent-color:var(--brand);">
                                    Set as default
                                </label>
                            </div>
                        </div>

                    </div>
                </div>

                <!-- Payment Method -->
                <div class="co-card">
                    <div class="co-card-header">
                        <div class="step-badge">2</div>
                        <h6>Payment Method</h6>
                    </div>
                    <div class="co-card-body">

                        <label class="pay-option">
                            <input type="radio" name="payment_method" value="COD" id="paymentMethod" checked>
                            <i class="la la-money-bill pay-icon"></i>
                            <div>
                                <strong>Cash on Delivery</strong>
                                <small>Pay when your order arrives</small>
                            </div>
                        </label>

                        <label class="pay-option">
                            <input type="radio" name="payment_method" value="razorpay" id="paymentMethod">
                            <i class="la la-credit-card pay-icon"></i>
                            <div>
                                <strong>Online Payment</strong>
                                <small>Pay via Razorpay (UPI, Card, Net Banking)</small>
                            </div>
                        </label>

                    </div>
                </div>

            </div>

            <!-- ── RIGHT: SUMMARY ─────────────────── -->
            <div class="col-lg-5">
                <div class="co-summary">
                    <div class="co-summary-header">
                        <i class="la la-receipt mr-1"></i> Order Summary ({{ $cartItems->count() }} items)
                    </div>
                    <div class="co-summary-body">

                        <!-- Products -->
                        @foreach($cartItems as $item)
                        <div class="sum-product">
                            <img src="{{ asset('product_image/' . ($item->image ?? '')) }}"
                                 onerror="this.src='{{ asset('website-assets/img/placeholder.jpg') }}'">
                            <div class="name">
                                {{ $item->product->product_name ?? 'Product' }}
                                <span style="color:var(--mid); font-size:12px;"> × {{ $item->qty }}</span>
                            </div>
                            <div class="price">₹{{ number_format($item->qty * $item->price, 2) }}</div>
                        </div>
                        @endforeach

                        <hr class="co-divider">

                        <div class="sum-row">
                            <span>Subtotal</span>
                            <span id="subtotalAmount" data-subtotal="{{ $subtotal }}">₹{{ number_format($subtotal, 2) }}</span>
                        </div>
                        <div class="sum-row">
                            <span>Shipping</span>
                            <span id="shippingAmount" data-current="{{ $shippingCharge }}">
                                @if($shippingCharge == 0)
                                    <span style="color:#16a34a; font-weight:700;">FREE</span>
                                @else
                                    ₹{{ number_format($shippingCharge, 2) }}
                                @endif
                            </span>
                        </div>

                        <hr class="co-divider">

                        <div class="sum-row total">
                            <span>Total Payable</span>
                            <span id="finalAmount">₹{{ number_format($finalTotal, 2) }}</span>
                        </div>

                        <button class="place-order-btn" type="submit" id="placeOrderBtn">
                            <i class="la la-lock"></i> Place Order Securely
                        </button>

                        <div style="text-align:center; margin-top:10px;">
                            <small style="font-size:11px; color:#9ca3af;">
                                <i class="la la-shield-alt" style="color:#16a34a;"></i>
                                Your data is secure. Powered by Razorpay & COD.
                            </small>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </form>
</div>

<script src="https://checkout.razorpay.com/v1/checkout.js"></script>

<script>
// ── must be declared before toggleNewAddressFields() is called ──
let manualMode = false;

function toggleNewAddressFields() {
    const selected = document.querySelector('input[name="selected_address_id"]:checked');
    const usingExisting = selected && selected.value !== '';

    document.querySelectorAll('#newAddressFields input, #newAddressFields select').forEach(el => {
        // Manual inputs keep their own disabled state when not using existing address
        if (['state_name_custom','city_name_custom'].includes(el.name)) {
            el.disabled = usingExisting || !manualMode;
        } else if (['state_id','city_id'].includes(el.name)) {
            el.disabled = usingExisting || manualMode;
        } else {
            el.disabled = usingExisting;
        }

        const requiredFields = manualMode
            ? ['name','phone','address','state_name_custom','city_name_custom','pincode']
            : ['name','phone','address','state_id','city_id','pincode'];
        el.required = !usingExisting && requiredFields.includes(el.name);
    });

    document.getElementById('save_address').disabled = usingExisting;
    document.getElementById('is_default').disabled   = usingExisting;
}

function formatINR(n) {
    return '₹' + new Intl.NumberFormat('en-IN', { minimumFractionDigits:2, maximumFractionDigits:2 }).format(n);
}

function refreshShipping(stateId) {
    const subtotal = parseFloat(document.getElementById('subtotalAmount').dataset.subtotal || 0);
    if (!stateId || !subtotal) return;
    fetch("{{ route('website.checkout.shipping') }}", {
        method: 'POST',
        headers: { 'Content-Type':'application/json', 'X-CSRF-TOKEN':'{{ csrf_token() }}' },
        body: JSON.stringify({ state_id: stateId, subtotal: subtotal })
    }).then(r => r.json()).then(res => {
        const shipEl  = document.getElementById('shippingAmount');
        const finalEl = document.getElementById('finalAmount');
        shipEl.dataset.current = res.shipping || 0;
        shipEl.innerHTML = res.shipping == 0
            ? '<span style="color:#16a34a;font-weight:700;">FREE</span>'
            : formatINR(res.shipping || 0);
        finalEl.textContent = formatINR(res.final_total || subtotal);
    }).catch(() => {});
}

// Radio change
document.querySelectorAll('input[name="selected_address_id"]').forEach(r => {
    r.addEventListener('change', function() {
        toggleNewAddressFields();
        if (this.value) {
            refreshShipping(this.getAttribute('data-state'));
        } else {
            const st = document.getElementById('stateDropdown')?.value;
            if (st) refreshShipping(st);
        }
    });
});

toggleNewAddressFields();

// ── Manual state/city toggle ──────────────────────────
document.getElementById('toggleManualBtn').addEventListener('click', function() {
    manualMode = !manualMode;
    document.getElementById('addressMode').value = manualMode ? 'manual' : 'dropdown';

    // State swap
    document.getElementById('stateDropdownCol').style.display  = manualMode ? 'none' : '';
    document.getElementById('stateManualCol').style.display    = manualMode ? '' : 'none';
    document.getElementById('stateDropdown').disabled          = manualMode;
    document.getElementById('stateManualInput').disabled       = !manualMode;

    // City swap
    document.getElementById('cityDropdownCol').style.display   = manualMode ? 'none' : '';
    document.getElementById('cityManualCol').style.display     = manualMode ? '' : 'none';
    document.getElementById('cityDropdown').disabled           = manualMode;
    document.getElementById('cityManualInput').disabled        = !manualMode;

    // Badge
    document.getElementById('manualActiveBadge').style.display = manualMode ? '' : 'none';

    this.innerHTML = manualMode
        ? '<i class="la la-times-circle"></i> Use state / city dropdown instead'
        : '<i class="la la-question-circle"></i> Can\'t find your state / city?';
});

// State → cities
$('#stateDropdown').on('change', function() {
    const stateId = $(this).val();
    $('#cityDropdown').html('<option>Loading…</option>');
    if (stateId) {
        const usingExisting = document.querySelector('input[name="selected_address_id"]:checked')?.value;
        if (!usingExisting) refreshShipping(stateId);
        $.get("{{ url('/get-cities') }}/" + stateId, function(data) {
            $('#cityDropdown').html('<option value="">Select City</option>');
            $.each(data, (_, city) => {
                $('#cityDropdown').append(`<option value="${city.id}">${city.city_name}</option>`);
            });
        });
    }
});

// Form submit
$('#checkoutForm').on('submit', function(e) {
    e.preventDefault();
    const btn = document.getElementById('placeOrderBtn');
    btn.disabled = true;
    btn.textContent = 'Processing…';

    $.post("{{ route('website.checkout.place') }}", $(this).serialize(), function(res) {
        if (res.payment_method === 'razorpay') {
            const options = {
                key: res.key,
                amount: res.amount,
                currency: 'INR',
                name: '{{ config("project.company", "Bestow") }}',
                description: 'Order #' + res.order_id,
                order_id: res.razorpay_order_id,
                handler: function(response) {
                    $.post("{{ route('website.payment.verify') }}", {
                        _token: '{{ csrf_token() }}',
                        razorpay_payment_id: response.razorpay_payment_id,
                        razorpay_order_id:   res.razorpay_order_id,
                        razorpay_signature:  response.razorpay_signature
                    }, function(verifyRes) {
                        if (verifyRes.status) window.location.href = verifyRes.redirect;
                    });
                },
                modal: { ondismiss: function() { btn.disabled = false; btn.innerHTML = '<i class="la la-lock"></i> Place Order Securely'; } }
            };
            new Razorpay(options).open();
        } else if (res.redirect_url) {
            window.location.href = res.redirect_url;
        } else if (res.message) {
            alert(res.message);
            btn.disabled = false;
            btn.innerHTML = '<i class="la la-lock"></i> Place Order Securely';
        }
    }).fail(function(xhr) {
        alert(xhr.responseJSON?.message || 'Something went wrong. Please try again.');
        btn.disabled = false;
        btn.innerHTML = '<i class="la la-lock"></i> Place Order Securely';
    });
});
</script>

@endsection
