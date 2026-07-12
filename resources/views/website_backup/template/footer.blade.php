<section class="bg-white border-top mt-auto py-4">
    <div class="container">
        <div class="row">

            {{-- Company Info --}}
            <div class="col-lg-3 mb-4">
                <a href="{{ route('website.home') }}" class="d-inline-block mb-3">
                    <img src="{{ asset('/company_logo/SHREEYOGITRADERS.jpg') }}" height="48" alt="{{ env('APP_NAME') }}">
                </a>
                <p class="text-muted small mb-2">
                    Manufacturer / Exporter / Supplier / Retailer of Kids Fancy Socks, Men's Sports Socks, Women's Ankle Socks & more.
                </p>
                <p class="text-muted small">
                    <strong>Phone:</strong> +91 8154876897 , 9377794101<br>
                    <strong>Email:</strong> bestowsales1@gmail.com
                </p>
            </div>

            {{-- About & Contact --}}
            <div class="col-6 col-md-3 col-lg-2 mb-4">
                <h6 class="text-dark">Company</h6>
                <ul class="list-unstyled small mb-0">
                    <li><a href="{{ route('website.about') }}" class="text-reset">About Us</a></li>
                    <li><a href="{{ route('website.contact') }}" class="text-reset">Contact Us</a></li>
                    {{--<li><a href="{{ route('website.gallery.view') }}" class="text-reset">Gallery</a></li>
                    <li><a href="{{ route('website.legal') }}" class="text-reset">Legal Info</a></li>--}}
                </ul>
            </div>

            {{-- Social Media --}}
            <div class="col-6 col-md-3 col-lg-2 mb-4">
                <h6 class="text-dark">Follow Us</h6>
                <ul class="list-unstyled small mb-0">
                    <li><a href="#" class="text-reset">Facebook</a></li>
                    <li><a href="#" class="text-reset">Instagram</a></li>
                    <li><a href="#" class="text-reset">LinkedIn</a></li>
                    <li><a href="#" class="text-reset">YouTube</a></li>
                </ul>
            </div>

            {{-- Address / Working Hours --}}
            <div class="col-md-6 col-lg-5 mb-4">
                <h6 class="text-dark">Our Address</h6>
                <p class="text-muted small mb-1">
                    Ground Floor, Pagedar’s Wado, Sardar Bhavan Ln, near Guru classes, Kadwa Sheri,<br> Vadodara, Gujarat 390001
                </p>
                <h6 class="text-dark mb-0">Working Hours</h6>
                <p class="text-muted small mb-0">
                    Mon - Sat: 9:00 AM – 7:00 PM<br>
                    Sunday: Closed
                </p>
            </div>

        </div>

        <hr>

        {{-- Bottom Footer --}}
        <div class="row align-items-center">
            <div class="col-md-6 text-center text-md-left mb-2 mb-md-0">
                <small class="text-muted">&copy; {{ date('Y') }} {{ config('project.brand', 'Store') }}. All Rights Reserved.</small>
            </div>
            <div class="col-md-6 text-center text-md-right">
                <small class="text-muted">Made with ❤️ in India</small>
            </div>
        </div>
    </div>
</section>

<style>
    .footer-disabled a {
        pointer-events: none;
        color: #999 !important;
        cursor: default;
        text-decoration: none;
    }
</style>
