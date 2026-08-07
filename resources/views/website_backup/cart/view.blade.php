@extends('website.template.layout')
@section('title','Your Cart')
@section('content')
@php
    $dbCart = isset($cart) ? $cart : (\App\Cart::query()->first());
    $items = $dbCart ? $dbCart->items : collect();
@endphp
<div class="container py-4" id="cartApp">
    <h4 class="mb-3">Shopping Cart</h4>
    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow-sm">
                <div class="card-body">
                    @if($items->isEmpty())
                        <p>Your cart is empty.</p>
                    @else
                        @foreach($items as $row)
                            <div class="d-flex align-items-center py-3 border-bottom">
                                <img src="{{ asset('product_image/' . ($row->image ?? '')) }}" onerror="this.src='{{ asset('website-assets/img/placeholder.jpg') }}'" style="width:80px;height:80px;object-fit:contain" class="mr-3">
                                <div class="flex-grow-1">
                                    <div class="small">{{ $row->product->product_name ?? 'Product' }}</div>
                                    <div class="small">{{ $row->product->attribute1 ?? 'Color' }} : {{ $row->product->value1 ?? 'Color' }}</div>
                                    <div class="small">{{ $row->product->attribute2 ?? 'Size' }} : {{ $row->product->value2 ?? 'Size' }}</div>
                                    <div class="price">₹{{ number_format($row->price ?? 0, 2) }}</div>
                                    <div class="mt-2 d-flex align-items-center">
                                        <button class="btn btn-sm btn-outline-secondary" onclick="updateQty({{ $row->variant_id }}, -1)">-</button>
                                        <input type="text" class="form-control text-center mx-2" style="width:60px" value="{{ $row->qty ?? 1 }}" readonly>
                                        <button class="btn btn-sm btn-outline-secondary" onclick="updateQty({{ $row->variant_id }}, 1)">+</button>
                                        <button class="btn btn-sm btn-link text-danger ml-3" onclick="removeItem({{ $row->variant_id }})">Remove</button>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>
        </div>
        <div class="col-lg-4 mt-3 mt-lg-0">
            <div class="card shadow-sm">
                <div class="card-body">
                    @php
                        $subtotal = $items->sum(fn($r)=> ($r->price ?? 0) * ($r->qty ?? 1));
                        $shipping = 0;
                        $total = $subtotal + $shipping;
                    @endphp
                    <div class="d-flex justify-content-between">
                        <span>Subtotal</span><strong>₹{{ number_format($subtotal,2) }}</strong>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between">
                        <span>Total</span><strong>₹{{ number_format($total,2) }}</strong>
                    </div>
                    <a href="{{ route('website.checkout.view') }}" class="btn btn-primary btn-block mt-3">Proceed to Checkout</a>
                </div>
            </div>
        </div>
    </div>
</div>

@section('page-javascript')
<script>
function updateQty(variant_id, diff){
    axios.post("{{ route('website.cart.update') }}",{ variant_id, diff, _token: window.csrfToken })
        .then(()=>window.location.reload());
}
function removeItem(variant_id){
    axios.post("{{ route('website.cart.remove') }}",{ variant_id, _token: window.csrfToken })
        .then(()=>window.location.reload());
}
</script>
@endsection
@endsection
