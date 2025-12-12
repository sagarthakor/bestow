@extends('website.template.layout')
@section('title','Products')

@section('content')
    <div class="container py-3">
        <div class="row">
            <!-- Sidebar Filters -->
            <div class="col-lg-3 mb-3">
                <div class="card filter-card shadow-sm">
                    <div class="card-header"><strong>Filters</strong></div>
                    <div class="card-body">
                        <form method="GET" action="{{ route('website.product.view') }}">

                            <!-- 🔹 Category Filter -->
                            <div class="mb-3">
                                <div class="small text-muted mb-2">Category</div>
                                @foreach($categories as $cat)
                                    <div class="mb-2">
                                        <label class="d-flex align-items-center">
                                            <input type="checkbox" name="slugs[]" value="{{ $cat->slug }}"
                                                {{ in_array($cat->slug, (array)request()->slugs) ? 'checked' : '' }}>
                                            <span class="ml-2">{{ $cat->category_name }}</span>
                                        </label>

                                        @if($cat->subcategories->count())
                                            <div class="pl-4">
                                                @foreach($cat->subcategories as $sub)
                                                    <label class="d-block small">
                                                        <input type="checkbox"
                                                               name="child_category[]"
                                                               value="{{ $sub->slug }}"
                                                            {{ in_array($sub->slug, (array)request()->child_category) ? 'checked' : '' }}>
                                                        <span class="ml-1">{{ $sub->subcategory_name }}</span>
                                                    </label>
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>
                                @endforeach
                            </div>

                            <!-- 🔹 Brand Filter -->
                            <div class="mb-3">
                                <div class="small text-muted mb-2">Brands</div>
                                @foreach($brands as $brand)
                                    <label class="d-block small">
                                        <input type="checkbox" name="brand[]" value="{{ $brand->id }}"
                                            {{ in_array($brand->id, (array)request()->brand) ? 'checked' : '' }}>
                                        <span class="ml-1">{{ $brand->brand_name }}</span>
                                    </label>
                                @endforeach
                            </div>

                            <!-- 🔹 Price Filter -->
                            <div class="mb-3">
                                <div class="small text-muted mb-2">Price</div>
                                <div class="d-flex">
                                    <input type="number" name="min_price" class="form-control" placeholder="Min"
                                           value="{{ request()->min_price }}">
                                    <span class="px-2">-</span>
                                    <input type="number" name="max_price" class="form-control" placeholder="Max"
                                           value="{{ request()->max_price }}">
                                </div>
                            </div>

                            <button class="btn btn-sm btn-primary w-100">Apply Filters</button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Product List -->
            <div class="col-lg-9">
                <!-- 🔹 Sort Dropdown -->
                <div class="d-flex justify-content-end align-items-center mb-2">
                    <form method="GET" action="{{ route('website.product.view') }}" class="form-inline">
                        @foreach(request()->except('sort_by', 'page') as $k => $v)
                            @if(is_array($v))
                                @foreach($v as $vv)
                                    <input type="hidden" name="{{ $k }}[]" value="{{ $vv }}">
                                @endforeach
                            @else
                                <input type="hidden" name="{{ $k }}" value="{{ $v }}">
                            @endif
                        @endforeach

                        <select name="sort_by" class="form-control form-control-sm" onchange="this.form.submit()">
                            <option value="">Sort by</option>
                            <option value="newest" {{ request('sort_by')=='newest'?'selected':'' }}>Newest</option>
                            <option value="oldest" {{ request('sort_by')=='oldest'?'selected':'' }}>Oldest</option>
                            <option value="price-asc" {{ request('sort_by')=='price-asc'?'selected':'' }}>Price: Low to High</option>
                            <option value="price-desc" {{ request('sort_by')=='price-desc'?'selected':'' }}>Price: High to Low</option>
                        </select>
                    </form>
                </div>

                <!-- 🔹 Product Cards -->
                <div class="row g-3">
                    @forelse($items as $product)
                        <div class="col-6 col-md-4 col-lg-3">
                            <div class="card product-card border-0 shadow-sm h-100">
                                <a href="{{ route('website.product.details', ['slug' => $product->slug, 'vid' => $product->default_variant_id]) }}">
                                <img src="{{ asset('product_image/' . $product->default_image) }}"
                                         class="card-img-top p-2"
                                         onerror="this.src='{{ asset('website-assets/img/placeholder.jpg') }}'">
                                </a>
                                <div class="card-body p-2 d-flex flex-column">
                                    <a href="{{ route('website.product.details', ['slug' => $product->slug, 'vid' => $product->default_variant_id]) }}"
                                       class="title text-reset small">
                                        {{ $product->clean_name ?: $product->product_name }}
                                    </a>

                                    <div class="mt-auto">

    <span class="price d-block mb-2">
        ₹{{ number_format($product->default_price ?? $product->price, 2) }}
    </span>

                                        <div class="product-actions d-flex flex-wrap gap-2">

                                            <button class="btn btn-sm btn-primary product-btn d-flex align-items-center justify-content-center"
                                                    onclick="window.addToCart({{ $product->default_variant_id }}, 1)">
                                                <i class="la la-shopping-cart mr-1"></i>
                                                Add to Cart
                                            </button>

                                            <button class="btn btn-sm btn-warning product-btn d-flex align-items-center justify-content-center"
                                                    onclick="window.buyNow({{ $product->default_variant_id }}, 1)">
                                                <i class="la la-bolt mr-1"></i>
                                                Buy Now
                                            </button>

                                        </div>

                                    </div>


                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-12">
                            <div class="alert alert-info">No products found.</div>
                        </div>
                    @endforelse
                </div>

                <!-- 🔹 Pagination -->
                <div class="mt-3">
                    {{ $items->appends(request()->all())->links('pagination::bootstrap-4') }}
                </div>
            </div>
        </div>
    </div>
@endsection
<style>
    .product-actions {
        display: flex;
        gap: 6px;
    }

    .product-btn {
        flex: 1;
        white-space: nowrap;
        padding: 6px 8px !important;
        border-radius: 6px !important;
        font-size: 13px;
    }

    /* Desktop (side-by-side) */
    @media (min-width: 576px) {
        .product-btn {
            flex: none;
            min-width: 95px;
        }
    }

    /* Mobile (full width stacked) */
    @media (max-width: 575px) {
        .product-actions {
            flex-direction: column;
        }
        .product-btn {
            width: 100%;
        }
    }


</style>
