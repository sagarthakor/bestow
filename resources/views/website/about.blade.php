@extends('website.template.layout')
@section('title','About Us')

@section('content')
    <div class="container py-5">

        {{-- Page Title --}}
        <div class="text-center mb-5">
            <h2 class="fw-bold">About Us</h2>
            <p class="text-muted small">Home / About Us</p>
        </div>

        {{-- About Section --}}
        <div class="row align-items-center mb-5">
            <div class="col-md-6">
                <h4 class="fw-bold">Who We Are</h4>
                <p class="text-muted">
                    We are a trusted <strong>Manufacturer, Exporter, Supplier and Retailer</strong> of quality socks for all age groups.
                    Our product range includes:
                    <strong>Kids Fancy Socks, Men's Sports Socks, Women's Ankle Socks, Cotton Lycra Socks, Kids Cotton Socks, Men's Argyle Socks, Men's Dress Socks</strong>.
                </p>
                <p class="text-muted">
                    With a focus on comfort, durability, and modern design, we ensure every pair of socks meets high-quality standards.
                    Our products are loved by customers across domestic and international markets.
                </p>
            </div>
            <div class="col-md-6 text-center">
                <img src="{{ asset('website-assets/img/about.jpg') }}" alt="About Us" class="img-fluid rounded shadow-sm"
                     onerror="this.src='{{ asset('website-assets/img/placeholder.jpg') }}'">
            </div>
        </div>

        {{-- Vision & Mission --}}
        <div class="row text-center mb-5">
            <div class="col-md-4 mb-3">
                <div class="card border-0 shadow-sm p-3 h-100">
                    <h5 class="fw-bold">Our Mission</h5>
                    <p class="small text-muted">To deliver premium-quality socks with innovation, comfort and affordability.</p>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="card border-0 shadow-sm p-3 h-100">
                    <h5 class="fw-bold">Our Vision</h5>
                    <p class="small text-muted">To be the most trusted socks brand across global markets.</p>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="card border-0 shadow-sm p-3 h-100">
                    <h5 class="fw-bold">Why Choose Us?</h5>
                    <p class="small text-muted">Premium Fabric • Modern Designs • Bulk Manufacturing • Timely Delivery</p>
                </div>
            </div>
        </div>

    </div>

    {{-- Optional Styling --}}
    <style>
        h2, h4 { color: #333; }
    </style>
@endsection
