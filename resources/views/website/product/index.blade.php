@extends('website.template.layout')
@section('title','Products')

@section('content')
    <div class="container py-3">
        <div class="row">

            <!-- SIDEBAR -->
            <div class="col-lg-3 mb-3">
                <div class="card shadow-sm">
                    <div class="card-header fw-bold">Filters</div>
                    <div class="card-body">
                        <form method="GET">

                            <!-- CATEGORY -->
                            @foreach($categories as $cat)
                                <label class="d-block">
                                    <input type="checkbox" name="slugs[]"
                                           value="{{ $cat->slug }}"
                                        {{ in_array($cat->slug,(array)request()->slugs)?'checked':'' }}>
                                    {{ $cat->category_name }}
                                </label>
                            @endforeach

                            <hr>

                            <!-- BRAND -->
                            @foreach($brands as $b)
                                <label class="d-block">
                                    <input type="checkbox" name="brand[]"
                                           value="{{ $b->id }}"
                                        {{ in_array($b->id,(array)request()->brand)?'checked':'' }}>
                                    {{ $b->brand_name }}
                                </label>
                            @endforeach

                            <button class="btn btn-primary btn-sm w-100 mt-3">Apply</button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- PRODUCTS -->
            <div class="col-lg-9">
                <div class="row g-3">
                    @forelse($items as $product)
                        <div class="col-6 col-md-4 col-lg-3">
                            <div class="card h-100 shadow-sm">

                                <a href="{{ route('website.product.details',$product->slug) }}">
                                    <img src="{{ asset('product_image/'.$product->product_image) }}"
                                         class="card-img-top p-2"
                                         onerror="this.src='{{ asset('website-assets/img/placeholder.jpg') }}'">
                                </a>

                                <div class="card-body p-2 d-flex flex-column">
                                    <a href="{{ route('website.product.details',$product->slug) }}"
                                       class="small text-dark">
                                        {{ $product->clean_name }}
                                    </a>

                                    <div class="mt-auto">
                                        <strong class="d-block mb-2">
                                            ₹{{ number_format($product->price,2) }}
                                        </strong>

                                        <button class="btn btn-sm btn-primary w-100"
                                                onclick="addToCart({{ $product->id }},1)">
                                            Add to Cart
                                        </button>
                                    </div>
                                </div>

                            </div>
                        </div>
                    @empty
                        <div class="col-12">
                            <div class="alert alert-info">No products found</div>
                        </div>
                    @endforelse
                </div>

                <div class="mt-3">
                    {{ $items->appends(request()->all())->links() }}
                </div>
            </div>

        </div>
    </div>
@endsection
