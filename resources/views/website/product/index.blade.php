@extends('website.template.layout')
@section('title','Products')

@section('content')

    <div class="container py-3">
        <div class="row">

            <!-- ================= FILTER SIDEBAR ================= -->
            <div class="col-lg-3">
                <div class="sticky-filter">

                    <form method="GET">

                        <!-- ===== CATEGORY ===== -->
                        @foreach($categories as $cat)
                            <div class="category-block mb-2">

                                <!-- Parent -->
                                <label>
                                    <input type="checkbox"
                                           class="parent-category"
                                           name="category[]"
                                           value="{{ $cat->slug }}"
                                        {{ in_array($cat->slug,(array)request()->category)?'checked':'' }}>
                                    <strong>{{ $cat->category_name }}</strong>
                                </label>

                                <!-- Subcategories -->
                                <ul class="ms-3 mt-1">
                                    @foreach($cat->subcategories as $sub)
                                        <li>
                                            <label>
                                                <input type="checkbox"
                                                       class="child-category"
                                                       name="child_category[]"
                                                       value="{{ $sub->slug }}"
                                                    {{ in_array($sub->slug,(array)request()->child_category)?'checked':'' }}>
                                                {{ $sub->subcategory_name }}
                                            </label>
                                        </li>
                                    @endforeach
                                </ul>

                            </div>
                        @endforeach


                        <!-- ===== BRAND ===== -->
                        <div class="filter-section">
                            <div class="filter-title">Brand</div>
                            @foreach($brands as $b)
                                <div>
                                    <label>
                                        <input type="checkbox"
                                               name="brand[]"
                                               value="{{ $b->id }}"
                                            {{ in_array($b->id,(array)request()->brand)?'checked':'' }}>
                                        {{ $b->brand_name }}
                                    </label>
                                </div>
                            @endforeach
                        </div>

                        <!-- ===== SIZE ===== -->
                        <div class="filter-section">
                            <div class="filter-title">Size</div>
                            <div class="row g-2">
                                @foreach($sizes as $size)
                                    <div class="col-3">
                                        <label class="w-100">
                                            <input type="checkbox"
                                                   name="size[]"
                                                   value="{{ $size }}"
                                                   class="d-none"
                                                {{ in_array($size,(array)request()->size)?'checked':'' }}>
                                            <div class="size-box">
                                                {{ $size }}
                                            </div>
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- ===== COLOR ===== -->
                        <div class="filter-section">
                            <div class="filter-title">Color</div>
                            <div class="d-flex flex-wrap gap-2">
                                @foreach($colors as $color)
                                    <label>
                                        <input type="checkbox"
                                               name="color[]"
                                               value="{{ $color }}"
                                               class="d-none"
                                            {{ in_array($color,(array)request()->color)?'checked':'' }}>

                                        <div class="color-circle"
                                             style="background: {{ $color }};"
                                             title="{{ $color }}">
                                        </div>
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        <!-- ===== PRICE ===== -->
                        <div class="filter-section">
                            <div class="filter-title">Price</div>

                            <input type="number"
                                   name="max_price"
                                   placeholder="Under ₹"
                                   class="form-control form-control-sm"
                                   value="{{ request('max_price') }}">
                        </div>

                        <button class="btn btn-warning btn-sm w-100">
                            Apply
                        </button>

                    </form>

                </div>
            </div>


            <!-- ================= PRODUCT SECTION ================= -->
            <div class="col-lg-9">

                <!-- SORT -->
                <div class="d-flex justify-content-end mb-3">
                    <form method="GET" class="d-flex">

                        @foreach(request()->except('sort') as $key => $value)
                            @if(is_array($value))
                                @foreach($value as $v)
                                    <input type="hidden" name="{{ $key }}[]" value="{{ $v }}">
                                @endforeach
                            @else
                                <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                            @endif
                        @endforeach

                        <select name="sort" class="form-select form-select-sm w-auto me-2">
                            <option value="">Sort by</option>
                            <option value="price_low"
                                {{ request('sort')=='price_low'?'selected':'' }}>
                                Price: Low to High
                            </option>
                            <option value="price_high"
                                {{ request('sort')=='price_high'?'selected':'' }}>
                                Price: High to Low
                            </option>
                        </select>

                        <button class="btn btn-sm btn-dark">Go</button>
                    </form>
                </div>

                <!-- PRODUCT GRID -->
                <div class="row g-3">
                    @forelse($items as $product)
                        @php
                            $product->clean_name = preg_replace('/^\d+\s+\d+\s+/', '', $product->product_name);
                        @endphp
                        <div class="col-6 col-md-4 col-lg-3">
                            <div class="card product-card shadow-sm">

                                <!-- IMAGE FIXED HEIGHT -->
                                <div class="product-image-wrapper">
                                    <a href="{{ route('website.product.details',$product->slug) }}">
                                        <img src="{{ asset('product_image/'.$product->product_image) }}"
                                             onerror="this.src='{{ asset('website-assets/img/placeholder.jpg') }}'">
                                    </a>
                                </div>

                                <div class="card-body p-2">

                                    <a href="{{ route('website.product.details',$product->slug) }}"
                                       class="text-dark text-decoration-none product-title">
                                        {{ $product->product_name }}
                                    </a>

                                    <div class="mt-auto">
                                        <strong class="d-block mb-2">
                                            ₹{{ number_format($product->price,2) }}
                                        </strong>

                                        <button class="btn btn-sm btn-warning w-100"
                                                onclick="addToCart({{ $product->id }},1)">
                                            Add to Cart
                                        </button>
                                    </div>

                                </div>

                            </div>
                        </div>

                    @empty
                        <div class="col-12">
                            <div class="alert alert-info text-center">
                                No products found
                            </div>
                        </div>
                    @endforelse
                </div>

                <div class="mt-4">
                    {{ $items->appends(request()->all())->links() }}
                </div>

            </div>

        </div>
    </div>

@endsection
@section('page-javascript')
    <script>
        document.addEventListener('DOMContentLoaded', function () {

            document.querySelectorAll('.category-block').forEach(function(block){

                let parentCheckbox = block.querySelector('.parent-category');
                let childCheckboxes = block.querySelectorAll('.child-category');

                // When subcategory changes
                childCheckboxes.forEach(function(child){

                    child.addEventListener('change', function(){

                        let anyChecked = false;

                        childCheckboxes.forEach(function(c){
                            if(c.checked){
                                anyChecked = true;
                            }
                        });

                        parentCheckbox.checked = anyChecked;
                    });

                });

                // If parent unchecked → uncheck all children
                parentCheckbox.addEventListener('change', function(){
                    if(!this.checked){
                        childCheckboxes.forEach(function(c){
                            c.checked = false;
                        });
                    }
                });

            });

        });
    </script>

@endsection
@section('page-css')
    <style>
        /* PRODUCT CARD FIXED HEIGHT */

        .product-card {
            height: 100%;
            display: flex;
            flex-direction: column;
        }

        .product-image-wrapper {
            height: 220px;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }

        .product-image-wrapper img {
            max-height: 100%;
            max-width: 100%;
            object-fit: contain;
        }

        /* Product Title - 2 Line Limit */
        .product-title {
            font-size: 14px;
            line-height: 1.4;
            height: 40px;
            overflow: hidden;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
        }

        /* Force card body stretch */
        .card-body {
            display: flex;
            flex-direction: column;
        }

        .card-body .mt-auto {
            margin-top: auto;
        }

        /* ===== AMAZON STYLE FILTER ===== */

        .filter-title{
            font-weight:600;
            font-size:14px;
            margin-bottom:10px;
        }

        .filter-section{
            margin-bottom:25px;
        }

        .filter-section ul{
            list-style:none;
            padding-left:0;
        }

        .filter-section ul li{
            margin-bottom:6px;
        }

        .filter-section ul li a{
            text-decoration:none;
            color:#111;
            font-size:14px;
        }

        .filter-section ul li a:hover{
            color:#c45500;
        }

        /* SIZE BOX */
        .size-box{
            border:1px solid #ddd;
            padding:6px 0;
            text-align:center;
            font-size:13px;
            cursor:pointer;
            border-radius:4px;
            transition:0.2s;
        }

        .size-box:hover{
            border-color:#111;
        }

        input:checked + .size-box{
            border:2px solid #111;
            font-weight:600;
        }

        /* COLOR CIRCLE */
        .color-circle{
            width:28px;
            height:28px;
            border-radius:50%;
            border:1px solid #ddd;
            cursor:pointer;
        }

        input:checked + .color-circle{
            border:2px solid #111;
        }

        /* Sticky Sidebar */
        .sticky-filter{
            position:sticky;
            top:20px;
        }
    </style>

@endsection
