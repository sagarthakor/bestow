@extends('website.template.layout')
@section('title', 'All Brands')
@section('content')
    <div class="container py-4">
        <h3>All Brands</h3>
        <div class="row">
            @foreach($brands as $brand)
                <div class="col-md-2 col-6 text-center mb-3">
                    <a href="{{ route('website.product.view', ['brand' => $brand->slug ?? $brand->id]) }}" class="text-decoration-none text-dark">
                        <img src="{{ asset('brand_image/' . $brand->brand_image) }}"
                             class="img-fluid mb-2" alt="{{ $brand->brand_name }}"
                             onerror="this.src='{{ asset('website-assets/img/placeholder.jpg') }}'">
                        <h6>{{ $brand->brand_name }}</h6>
                    </a>
                </div>
            @endforeach
        </div>
    </div>
@endsection
