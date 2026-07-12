<style>
    .site-footer {
        background: #1a1f36;
        color: #d1d5db;
        margin-top: auto;
        font-size: 14px;
    }

    /* ── Newsletter ───────────────────────── */
    .footer-newsletter {
        background: linear-gradient(135deg,#e83e10,#c23209);
        padding: 32px 0;
    }
    .footer-newsletter h5 {
        color: #fff;
        font-size: 20px;
        font-weight: 700;
        margin-bottom: 4px;
    }
    .footer-newsletter p { color: rgba(255,255,255,.85); margin: 0; font-size: 14px; }

    .nl-form {
        display: flex;
        gap: 0;
        border-radius: 8px;
        overflow: hidden;
        max-width: 420px;
    }
    .nl-form input {
        flex: 1;
        padding: 12px 16px;
        border: none;
        font-size: 14px;
        outline: none;
        font-family: 'Poppins', sans-serif;
    }
    .nl-form button {
        background: #1a1f36;
        color: #fff;
        border: none;
        padding: 12px 20px;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        transition: background .2s;
    }
    .nl-form button:hover { background: #141828; }

    /* ── Trust badges ─────────────────────── */
    .trust-bar {
        background: #141828;
        border-top: 1px solid rgba(255,255,255,.06);
        border-bottom: 1px solid rgba(255,255,255,.06);
        padding: 16px 0;
    }
    .trust-item {
        display: flex;
        align-items: center;
        gap: 10px;
        color: #9ca3af;
    }
    .trust-item i { font-size: 28px; color: #e83e10; }
    .trust-item strong { display: block; color: #f9fafb; font-size: 13px; line-height: 1.2; }
    .trust-item small { font-size: 12px; }

    /* ── Main footer ──────────────────────── */
    .footer-main { padding: 48px 0 32px; }

    .footer-logo img { height: 48px; border-radius: 6px; margin-bottom: 14px; }

    .footer-desc {
        color: #9ca3af;
        font-size: 13px;
        line-height: 1.7;
        margin-bottom: 16px;
    }

    .footer-contact p {
        font-size: 13px;
        color: #9ca3af;
        margin-bottom: 6px;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .footer-contact p i { color: #e83e10; font-size: 16px; }

    .footer-social { display: flex; gap: 10px; margin-top: 16px; }
    .social-icon {
        width: 36px; height: 36px;
        border-radius: 8px;
        background: rgba(255,255,255,.07);
        display: flex; align-items: center; justify-content: center;
        color: #d1d5db;
        font-size: 16px;
        transition: background .2s, color .2s;
    }
    .social-icon:hover { background: #e83e10; color: #fff; }

    /* Footer columns */
    .footer-col h6 {
        color: #f9fafb;
        font-weight: 700;
        font-size: 14px;
        margin-bottom: 16px;
        position: relative;
        padding-bottom: 8px;
    }
    .footer-col h6::after {
        content: '';
        position: absolute;
        bottom: 0; left: 0;
        width: 32px; height: 2px;
        background: #e83e10;
        border-radius: 1px;
    }

    .footer-links { list-style: none; padding: 0; margin: 0; }
    .footer-links li { margin-bottom: 9px; }
    .footer-links li a {
        color: #9ca3af;
        font-size: 13px;
        display: flex;
        align-items: center;
        gap: 6px;
        transition: color .15s, padding-left .15s;
    }
    .footer-links li a::before {
        content: '›';
        color: #e83e10;
        font-size: 16px;
        line-height: 1;
    }
    .footer-links li a:hover { color: #fff; padding-left: 4px; }

    /* ── Bottom bar ───────────────────────── */
    .footer-bottom {
        border-top: 1px solid rgba(255,255,255,.07);
        padding: 16px 0;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 10px;
    }
    .footer-bottom small { color: #6b7280; font-size: 12px; }
    .footer-bottom .pay-icons { display: flex; gap: 8px; align-items: center; }
    .pay-icons img { height: 22px; filter: grayscale(30%); opacity: .8; }
</style>

<!-- ── NEWSLETTER ──────────────────────────────── -->
<div class="footer-newsletter">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-5 mb-3 mb-lg-0">
                <h5>Subscribe for Exclusive Deals</h5>
                <p>Get the latest offers, new arrivals & special discounts.</p>
            </div>
            <div class="col-lg-7">
                <form class="nl-form" onsubmit="return false;">
                    <input type="email" placeholder="Enter your email address">
                    <button type="submit">Subscribe</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- ── TRUST BAR ──────────────────────────────────── -->
<div class="trust-bar">
    <div class="container">
        <div class="row text-center text-lg-left">
            <div class="col-6 col-lg-3 mb-3 mb-lg-0">
                <div class="trust-item justify-content-center justify-content-lg-start">
                    <i class="la la-truck"></i>
                    <div>
                        <strong>Free Delivery</strong>
                        <small>On orders above ₹499</small>
                    </div>
                </div>
            </div>
            <div class="col-6 col-lg-3 mb-3 mb-lg-0">
                <div class="trust-item justify-content-center justify-content-lg-start">
                    <i class="la la-shield-alt"></i>
                    <div>
                        <strong>100% Authentic</strong>
                        <small>Genuine products only</small>
                    </div>
                </div>
            </div>
            <div class="col-6 col-lg-3">
                <div class="trust-item justify-content-center justify-content-lg-start">
                    <i class="la la-undo-alt"></i>
                    <div>
                        <strong>Easy Returns</strong>
                        <small>7-day return policy</small>
                    </div>
                </div>
            </div>
            <div class="col-6 col-lg-3">
                <div class="trust-item justify-content-center justify-content-lg-start">
                    <i class="la la-lock"></i>
                    <div>
                        <strong>Secure Payment</strong>
                        <small>Razorpay & COD</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ── MAIN FOOTER ────────────────────────────────── -->
<footer class="site-footer">
    <div class="footer-main">
        <div class="container">
            <div class="row">

                <!-- Brand col -->
                <div class="col-lg-4 mb-4">
                    <div class="footer-logo">
                        <img src="{{ asset('/company_logo/SHREEYOGITRADERS.jpg') }}" alt="Bestow">
                    </div>
                    <p class="footer-desc">
                        Manufacturer, Exporter &amp; Supplier of premium Kids Fancy Socks, Men's Sports Socks,
                        Women's Ankle Socks &amp; more. Quality you can feel.
                    </p>
                    <div class="footer-contact">
                        <p><i class="la la-phone"></i> +91 8154876897 / 9377794101</p>
                        <p><i class="la la-envelope"></i> bestowsales1@gmail.com</p>
                        <p><i class="la la-map-marker"></i> Pagedar's Wado, Vadodara, Gujarat 390001</p>
                    </div>
                    <div class="footer-social">
                        <a href="#" class="social-icon"><i class="la la-facebook-f"></i></a>
                        <a href="#" class="social-icon"><i class="la la-instagram"></i></a>
                        <a href="#" class="social-icon"><i class="la la-youtube"></i></a>
                        <a href="#" class="social-icon"><i class="la la-linkedin"></i></a>
                    </div>
                </div>

                <!-- Company -->
                <div class="col-6 col-lg-2 mb-4">
                    <div class="footer-col">
                        <h6>Company</h6>
                        <ul class="footer-links">
                            <li><a href="{{ route('website.about') }}">About Us</a></li>
                            <li><a href="{{ route('website.contact') }}">Contact Us</a></li>
                            <li><a href="{{ route('website.home') }}">Blog</a></li>
                            <li><a href="{{ route('website.home') }}">Careers</a></li>
                        </ul>
                    </div>
                </div>

                <!-- Shopping -->
                <div class="col-6 col-lg-2 mb-4">
                    <div class="footer-col">
                        <h6>Shopping</h6>
                        <ul class="footer-links">
                            <li><a href="{{ route('website.categories.all') }}">All Categories</a></li>
                            <li><a href="{{ route('website.brands.all') }}">Brands</a></li>
                            <li><a href="{{ route('website.track.order') }}">Track Order</a></li>
                            <li><a href="{{ route('website.cart.view') }}">My Cart</a></li>
                        </ul>
                    </div>
                </div>

                <!-- Account -->
                <div class="col-6 col-lg-2 mb-4">
                    <div class="footer-col">
                        <h6>My Account</h6>
                        <ul class="footer-links">
                            <li><a href="{{ route('website.login') }}">Login</a></li>
                            <li><a href="{{ route('website.register') }}">Register</a></li>
                            <li><a href="{{ route('website.orders') }}">My Orders</a></li>
                            <li><a href="{{ route('website.account.dashboard') }}">Dashboard</a></li>
                        </ul>
                    </div>
                </div>

                <!-- Hours -->
                <div class="col-6 col-lg-2 mb-4">
                    <div class="footer-col">
                        <h6>Working Hours</h6>
                        <ul class="footer-links">
                            <li><a href="#">Mon – Sat: 9AM – 7PM</a></li>
                            <li><a href="#">Sunday: Closed</a></li>
                        </ul>
                        <div style="margin-top:16px;">
                            <div style="background:rgba(232,62,16,.15); border-left:3px solid #e83e10; padding:10px 12px; border-radius:4px;">
                                <small style="color:#f9fafb; font-size:12px; line-height:1.6;">
                                    <strong style="color:#e83e10;">Need help?</strong><br>
                                    Call or WhatsApp us at<br>
                                    <strong>+91 81548 76897</strong>
                                </small>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- ── BOTTOM ─────────────────────────────────── -->
    <div class="container">
        <div class="footer-bottom">
            <small>&copy; {{ date('Y') }} {{ config('project.brand', 'Bestow') }}. All rights reserved.</small>
            <small>Designed &amp; Built with ♥ in India</small>
        </div>
    </div>
</footer>
