@extends('website.template.layout')
@section('title', 'Home')

@section('page-css')
<style>
    /* ─── HERO CAROUSEL ────────────────────── */
    .hero-carousel { position: relative; border-radius: 14px; overflow: hidden; margin-bottom: 36px; }
    .hero-carousel .carousel-item img {
        width: 100%; height: 440px; object-fit: cover;
    }
    .hero-carousel .carousel-caption {
        bottom: auto; top: 50%; transform: translateY(-50%);
        left: 7%; right: auto; text-align: left;
        text-shadow: none;
    }
    .hero-carousel .hero-tag {
        display: inline-block; background: var(--brand); color: #fff;
        font-size: 11px; font-weight: 700; padding: 4px 14px;
        border-radius: 20px; letter-spacing: 1.5px; text-transform: uppercase;
        margin-bottom: 12px;
    }
    .hero-carousel .hero-title {
        font-size: 38px; font-weight: 800; color: #fff; line-height: 1.2; margin-bottom: 10px;
        text-shadow: 0 2px 16px rgba(0,0,0,.5);
    }
    .hero-carousel .hero-sub {
        font-size: 15px; color: rgba(255,255,255,.85); margin-bottom: 20px;
    }
    .hero-carousel .btn-hero {
        background: #fff; color: var(--brand); font-weight: 700;
        padding: 11px 28px; border-radius: 8px; font-size: 14px;
        border: none; transition: background .2s; display: inline-block;
        text-decoration: none;
    }
    .hero-carousel .btn-hero:hover { background: #fef2f0; }
    .carousel-control-prev-icon, .carousel-control-next-icon {
        background-color: rgba(0,0,0,.4); border-radius: 50%; padding: 14px;
    }
    @media(max-width:767px){
        .hero-carousel .carousel-item img { height: 200px; }
        .hero-carousel .hero-title { font-size: 20px; }
        .hero-carousel .carousel-caption { display: block !important; }
        .hero-carousel .hero-sub { display: none; }
        .hero-carousel .btn-hero { font-size: 12px; padding: 7px 14px; }
    }

    /* ─── CATEGORY STRIP ───────────────────── */
    .cat-strip .cat-item {
        display: flex; flex-direction: column; align-items: center;
        gap: 8px; text-decoration: none; transition: transform .2s;
    }
    .cat-strip .cat-item:hover { transform: translateY(-4px); }
    .cat-strip .cat-img-wrap {
        width: 110px; height: 110px; border-radius: 50%;
        background: #fff; border: 2px solid var(--border);
        display: flex; align-items: center; justify-content: center;
        overflow: hidden; box-shadow: var(--shadow);
        transition: border-color .2s, box-shadow .2s;
    }
    .cat-strip .cat-item:hover .cat-img-wrap {
        border-color: var(--brand); box-shadow: 0 4px 16px rgba(232,62,16,.2);
    }
    .cat-strip .cat-img-wrap img { width: 82px; height: 82px; object-fit: contain; }
    .cat-strip .cat-name { font-size: 13px; font-weight: 600; color: var(--text); text-align: center; }

    /* ─── PROMO BOXES ──────────────────────── */
    .promo-box {
        border-radius: 14px; overflow: hidden; position: relative;
        height: 170px; display: flex; align-items: center; padding: 28px;
        transition: transform .2s, box-shadow .2s;
    }
    .promo-box:hover { transform: translateY(-3px); box-shadow: 0 8px 28px rgba(0,0,0,.15); }
    .promo-box h5 { font-size: 19px; font-weight: 700; margin-bottom: 4px; }
    .promo-box p  { font-size: 12px; margin-bottom: 12px; opacity: .9; }
    .promo-box a  { font-size: 13px; font-weight: 700; text-decoration: none; padding: 8px 20px; border-radius: 6px; display: inline-block; }

    /* ─── SECTIONS ─────────────────────────── */
    .home-section { margin-bottom: 44px; }
    .view-all-link {
        font-size: 13px; color: var(--brand); font-weight: 600;
        display: flex; align-items: center; gap: 4px;
    }
    .view-all-link:hover { color: var(--brand-dark); }

    /* ─── TRUST BADGES ─────────────────────── */
    .trust-strip {
        background: #fff; border: 1px solid var(--border);
        border-radius: 12px; padding: 20px 28px; margin-bottom: 44px;
    }
    .trust-item {
        display: flex; align-items: center; gap: 12px;
    }
    .trust-item i { font-size: 28px; color: var(--brand); }
    .trust-item h6 { font-size: 13px; font-weight: 700; margin: 0 0 2px; }
    .trust-item p  { font-size: 11px; color: var(--mid); margin: 0; }

    /* ─── SHOP BY CATEGORY CARDS ───────────── */
    .cat-card {
        border-radius: 12px; overflow: hidden;
        position: relative; height: 200px; display: block;
        text-decoration: none; transition: transform .2s, box-shadow .2s;
    }
    .cat-card:hover { transform: translateY(-4px); box-shadow: 0 10px 30px rgba(0,0,0,.15); }
    .cat-card img { width: 100%; height: 100%; object-fit: cover; }
    .cat-card-overlay {
        position: absolute; inset: 0;
        background: linear-gradient(to top, rgba(0,0,0,.7) 40%, transparent);
        display: flex; align-items: flex-end; padding: 16px;
    }
    .cat-card-overlay span {
        color: #fff; font-size: 15px; font-weight: 700; text-transform: uppercase; letter-spacing: .5px;
    }

    /* ─── CTA BANNER ───────────────────────── */
    .cta-banner {
        border-radius: 14px; overflow: hidden;
        background: linear-gradient(135deg, #1a1f36 0%, #e83e10 100%);
        padding: 48px 40px; text-align: center; margin-bottom: 44px;
    }
    .cta-banner h3 { color: #fff; font-size: 28px; font-weight: 800; margin-bottom: 8px; }
    .cta-banner p  { color: rgba(255,255,255,.8); font-size: 15px; margin-bottom: 24px; }
    @media(max-width:767px){
        .cta-banner { padding: 32px 20px; }
        .cta-banner h3 { font-size: 20px; }
    }
</style>
@endsection

@section('content')
<div class="container py-4">

    {{-- ═══════════ HERO BANNER ═══════════ --}}
    @if(isset($banners) && $banners->count())
    <div id="heroCarousel" class="carousel slide hero-carousel mb-4" data-ride="carousel" data-interval="4000">
        <ol class="carousel-indicators">
            @foreach($banners as $i => $bn)
                <li data-target="#heroCarousel" data-slide-to="{{ $i }}" class="{{ $i==0?'active':'' }}"></li>
            @endforeach
        </ol>
        <div class="carousel-inner">
            @foreach($banners as $i => $bn)
                <div class="carousel-item {{ $i==0?'active':'' }}">
                    <img src="{{ asset($bn->image) }}" alt="{{ $bn->title ?? '' }}"
                         onerror="this.src='{{ asset('website-assets/img/placeholder.jpg') }}'">
                    @if($bn->title || $bn->button_text)
                        <div class="carousel-caption d-none d-md-block">
                            <div class="hero-tag">New Collection</div>
                            <h2 class="hero-title">{{ $bn->title }}</h2>
                            <p class="hero-sub">Premium quality socks for every occasion</p>
                            @if($bn->button_text)
                                <a href="{{ $bn->button_link ?? route('website.product.view') }}" class="btn-hero">
                                    {{ $bn->button_text }} &rarr;
                                </a>
                            @endif
                        </div>
                    @endif
                </div>
            @endforeach
        </div>
        <a class="carousel-control-prev" href="#heroCarousel" data-slide="prev">
            <span class="carousel-control-prev-icon"></span>
        </a>
        <a class="carousel-control-next" href="#heroCarousel" data-slide="next">
            <span class="carousel-control-next-icon"></span>
        </a>
    </div>
    @endif

    {{-- ═══════════ TRUST BADGES ═══════════ --}}
    <div class="trust-strip mb-4">
        <div class="row text-center text-md-left">
            <div class="col-6 col-md-3 mb-3 mb-md-0">
                <div class="trust-item justify-content-center justify-content-md-start">
                    <i class="la la-truck"></i>
                    <div>
                        <h6>Free Delivery</h6>
                        <p>On orders above ₹499</p>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3 mb-3 mb-md-0">
                <div class="trust-item justify-content-center justify-content-md-start">
                    <i class="la la-shield-alt"></i>
                    <div>
                        <h6>Quality Assured</h6>
                        <p>100% premium material</p>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="trust-item justify-content-center justify-content-md-start">
                    <i class="la la-undo"></i>
                    <div>
                        <h6>Easy Returns</h6>
                        <p>Hassle-free return policy</p>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="trust-item justify-content-center justify-content-md-start">
                    <i class="la la-headset"></i>
                    <div>
                        <h6>24/7 Support</h6>
                        <p>Dedicated customer care</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ═══════════ CATEGORY STRIP ═══════════ --}}
    @if(isset($categories) && $categories->count())
    <div class="home-section">
        <div class="section-header">
            <h4>Shop by Category</h4>
            <a href="{{ route('website.categories.all') }}" class="view-all-link">View All <i class="la la-arrow-right"></i></a>
        </div>
        <div class="cat-strip d-flex flex-wrap" style="gap:20px;">
            @foreach($categories->take(10) as $cat)
                <a href="{{ route('website.product.view', ['category' => $cat->slug]) }}" class="cat-item">
                    <div class="cat-img-wrap">
                        <img src="{{ asset('product_category/'.$cat->category_image) }}"
                             onerror="this.src='{{ asset('website-assets/img/placeholder.jpg') }}'">
                    </div>
                    <span class="cat-name">{{ $cat->name ?? $cat->category_name }}</span>
                </a>
            @endforeach
        </div>
    </div>
    @endif

    {{-- ═══════════ PROMO BOXES ═══════════ --}}
    <div class="row mb-5">
        <div class="col-md-4 mb-3">
            <div class="promo-box" style="background:linear-gradient(135deg,#e83e10,#ff7043); color:#fff;">
                <div>
                    <h5>New Arrivals</h5>
                    <p>Fresh designs just landed!</p>
                    <a href="{{ route('website.product.view') }}" style="background:#fff;color:#e83e10;">Shop Now &rarr;</a>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="promo-box" style="background:linear-gradient(135deg,#1a1f36,#2d3561); color:#fff;">
                <div>
                    <h5>Kids Collection</h5>
                    <p>Colorful &amp; comfortable styles</p>
                    <a href="{{ route('website.product.view', ['keyword' => 'kids']) }}" style="background:#e83e10;color:#fff;">Explore &rarr;</a>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="promo-box" style="background:linear-gradient(135deg,#047857,#059669); color:#fff;">
                <div>
                    <h5>Bulk Orders</h5>
                    <p>Special wholesale prices</p>
                    <a href="{{ route('website.contact') }}" style="background:#fff;color:#047857;">Contact Us &rarr;</a>
                </div>
            </div>
        </div>
    </div>

    {{-- ═══════════ NEW ARRIVALS (one per category) ═══════════ --}}
    @if(isset($newArrivals) && $newArrivals->count())
    <div class="home-section">
        <div class="section-header">
            <h4>New Arrivals</h4>
            <a href="{{ route('website.product.view') }}" class="view-all-link">View All <i class="la la-arrow-right"></i></a>
        </div>
        <div class="row g-3">
            @foreach($newArrivals as $p)
            <div class="col-6 col-md-4 col-lg-2">
                @include('website.partials.product_card', ['product' => $p])
            </div>
            @endforeach
        </div>
    </div>
    @endif

    {{-- ═══════════ CTA BANNER ═══════════ --}}
    <div class="cta-banner">
        <h3>Explore Our Full Collection</h3>
        <p>300+ premium socks, hosiery &amp; accessories — something for everyone.</p>
        <a href="{{ route('website.product.view') }}" class="btn-hero" style="background:#fff; color:var(--brand); padding:14px 40px; font-size:15px;">
            Shop All Products &rarr;
        </a>
    </div>

</div>
@endsection