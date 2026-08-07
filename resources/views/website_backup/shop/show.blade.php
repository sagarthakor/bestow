@extends('layouts.app')
@section('content')
    <div class="row">
        <div class="col-md-6">
            <img src="{{ asset('storage/'.$product->cover_image) }}" class="img-fluid">
        </div>
        <div class="col-md-6">
            <h2>{{ $product->product_name }}</h2>
            <p>{!! nl2br(e($product->product_description)) !!}</p>

            <form method="POST" action="{{ route('cart.add') }}" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="product_id" value="{{ $product->id }}">
                <div class="form-group">
                    <label>Choose Variant</label>
                    <select name="variant_id" class="form-control" required>
                        @foreach($product->variants as $v)
                            <option value="{{ $v->id }}" data-price="{{ $v->price }}">
                                {{ $v->size }} {{ $v->color }} — ₹{{ number_format($v->price,2) }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label>Quantity</label>
                    <input type="number" name="quantity" value="1" min="1" class="form-control">
                </div>
                <button class="btn btn-success">Add to cart</button>
            </form>
        </div>
    </div>
@endsection
