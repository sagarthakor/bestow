@extends('website.template.layout')
@section('title','Home')

@section('content')
    <div class="container py-3">

        {{-- ================= Banner Carousel ================= --}}
        @if(isset($banners) && $banners->count())
            <div id="homeCarousel" class="carousel slide mb-4" data-ride="carousel" data-interval="3000">
                <ol class="carousel-indicators">
                    @foreach($banners as $i => $bn)
                        <li data-target="#homeCarousel" data-slide-to="{{ $i }}" class="{{ $i==0?'active':'' }}"></li>
                    @endforeach
                </ol>

                <div class="carousel-inner shadow-sm">
                    @foreach($banners as $i => $bn)
                        <div class="carousel-item {{ $i==0?'active':'' }}">
                            <img class="d-block w-100 banner-image" src="{{ asset($bn->image) }}" alt="">

                            @if($bn->title || $bn->button_text)
                                <div class="carousel-caption text-left d-none d-md-block">
                                    <h3 class="banner-title">{{ $bn->title }}</h3>
                                    @if($bn->button_text)
                                        <a href="{{ $bn->button_link }}" class="btn btn-primary btn-sm">
                                            {{ $bn->button_text }}
                                        </a>
                                    @endif
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>

                <a class="carousel-control-prev" href="#homeCarousel" role="button" data-slide="prev">
                    <span class="carousel-control-prev-icon"></span>
                </a>
                <a class="carousel-control-next" href="#homeCarousel" role="button" data-slide="next">
                    <span class="carousel-control-next-icon"></span>
                </a>
            </div>
        @endif

        {{-- ================= Category Strip ================= --}}
        @if(isset($categories) && $categories->count())
            <div class="mb-4">
                <h5 class="fw-bold mb-2">Shop by Category</h5>
                <div class="row g-2">
                    @foreach($categories as $cat)
                        <div class="col-4 col-md-2">
                            <a href="{{ route('website.product.view', ['slugs' => $cat->slug]) }}"
                               class="text-decoration-none text-reset d-block">
                                <div class="card border-0 shadow-sm h-100 text-center">
                                    <img src="{{ asset('product_category/'.$cat->category_image) }}"
                                         class="card-img-top p-2"
                                         onerror="this.src='{{ asset('website-assets/img/placeholder.jpg') }}'">
                                    <div class="card-body p-2">
                                        <div class="small">{{ $cat->name ?? $cat->category_name }}</div>
                                    </div>
                                </div>
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        {{-- ================= Featured ================= --}}
        @if(isset($featured) && $featured->count())
            <div class="mb-4">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <h5 class="fw-bold m-0">Featured</h5>
                </div>
                <div class="row g-3">
                    @foreach($featured as $p)
                        <div class="col-6 col-md-3 col-lg-2">
                            <div class="card product-card border-0 shadow-sm h-100">
                                <a href="{{ route('website.product.details', $p->slug) }}">
                                    <img src="{{ asset('product_image/' . $p->product_image) }}"
                                         class="card-img-top p-2"
                                         onerror="this.src='{{ asset('website-assets/img/placeholder.jpg') }}'">
                                </a>
                                <div class="card-body p-2 d-flex flex-column">
                                    <a href="{{ route('website.product.details', $p->slug) }}"
                                       class="title text-reset small">{{ $p->clean_name ?? $p->product_name }}</a>
                                    <div class="mt-auto d-flex justify-content-between align-items-center">
                                        <span class="price">₹{{ number_format($p->price, 2) }}</span>
                                        <a href="{{ route('website.product.details', $p->slug) }}"
                                           class="btn btn-sm btn-primary">View</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        {{-- ================= New Arrivals ================= --}}
        @if(isset($newArrivals) && $newArrivals->count())
            <div class="mb-4">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <h5 class="fw-bold m-0">New Arrivals</h5>
                </div>
                <div class="row g-3">
                    @foreach($newArrivals as $p)
                        <div class="col-6 col-md-3 col-lg-2">
                            <div class="card product-card border-0 shadow-sm h-100">
                                <a href="{{ route('website.product.details', $p->slug) }}">
                                    <img src="{{ asset('product_image/' . $p->product_image) }}"
                                         class="card-img-top p-2"
                                         onerror="this.src='{{ asset('website-assets/img/placeholder.jpg') }}'">
                                </a>
                                <div class="card-body p-2 d-flex flex-column">
                                    <a href="{{ route('website.product.details', $p->slug) }}"
                                       class="title text-reset small">{{ $p->clean_name ?? $p->product_name }}</a>
                                    <div class="mt-auto d-flex justify-content-between align-items-center">
                                        <span class="price">₹{{ number_format($p->price, 2) }}</span>
                                        <a href="{{ route('website.product.details', $p->slug) }}"
                                           class="btn btn-sm btn-outline-primary">Buy</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        {{-- ================= All Products (Paginated) ================= --}}
        <h5 class="fw-bold mt-4">All Products</h5>
        <div class="row g-3 mt-1">
            @foreach($products as $product)
                <div class="col-6 col-md-3 col-lg-2">
                    <div class="card product-card border-0 shadow-sm h-100">
                        <a href="{{ route('website.product.details', $product->slug) }}">
                            <img src="{{ asset('product_image/' . $product->product_image) }}"
                                 class="card-img-top p-2"
                                 onerror="this.src='{{ asset('website-assets/img/placeholder.jpg') }}'">
                        </a>
                        <div class="card-body p-2 d-flex flex-column">
                            <a href="{{ route('website.product.details', $product->slug) }}"
                               class="title text-reset small">{{ $product->clean_name ?? $product->product_name }}</a>
                            <div class="mt-auto d-flex justify-content-between align-items-center">
                                <span class="price">₹{{ number_format($product->price, 2) }}</span>
                                <a href="{{ route('website.product.details', $product->slug) }}"
                                   class="btn btn-sm btn-primary">View</a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

       {{-- <div class="mt-3">
            {{ $products->links('pagination::bootstrap-4') }}
        </div>--}}
    </div>

    {{-- Minimal styling tweaks --}}
    <style>
        .banner-image {
            width: 100%;
            height: 380px;
            object-fit: cover;
        }
        @media(max-width:768px){ .banner-image{ height: 220px; } }
        .carousel-caption {
            top: 20%; left: 5%;
            transform: translateY(-20%);
            text-shadow: 1px 1px 3px rgba(0,0,0,.6);
        }
        .banner-title {
            font-size: 28px;
            font-weight: 700;
            color: white;
        }
        .product-card .price{ font-weight:600; }
        .product-card .title{
            display:-webkit-box; -webkit-line-clamp:2; -webkit-box-orient:vertical;
            overflow:hidden; min-height:2.6em;
        }
    </style>
@endsection
