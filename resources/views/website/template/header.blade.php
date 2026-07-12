@php
    $isLoggedIn = Session::has('user');
    $cartCount  = session('cart_count', 0);
@endphp

<style>
    /* ─── HEADER SHELL ───────────────────────────────── */
    .site-header {
        position: sticky;
        top: 0;
        z-index: 9999;
        width: 100%;
        background: #1a1f36;
        box-shadow: 0 2px 12px rgba(0,0,0,.25);
    }

    /* ─── TOP BAR ────────────────────────────────────── */
    .header-top {
        background: #e83e10;
        padding: 4px 0;
        font-size: 12px;
        color: #fff;
        text-align: center;
    }

    /* ─── MAIN ROW ───────────────────────────────────── */
    .header-main {
        display: grid;
        grid-template-columns: 1fr auto 1fr;
        align-items: center;
        gap: 16px;
        padding: 12px 20px;
    }

    /* Logo — left column */
    .header-logo {
        justify-self: start;
    }
    .header-logo img {
        height: 42px;
        object-fit: contain;
        border-radius: 4px;
    }

    /* ─── SEARCH BAR — center column ────────────────── */
    .header-search {
        width: 100%;
        max-width: 580px;
        display: flex;
        border-radius: 8px;
        overflow: hidden;
        border: 2px solid transparent;
        transition: border-color .2s;
        background: #fff;
    }
    .header-search:focus-within { border-color: #e83e10; }

    .header-search select {
        background: #f0f0f0;
        border: none;
        padding: 0 10px;
        font-size: 13px;
        color: #333;
        min-width: 90px;
        outline: none;
        border-right: 1px solid #ddd;
        font-family: 'Poppins', sans-serif;
    }

    .header-search input {
        flex: 1;
        border: none;
        padding: 10px 14px;
        font-size: 14px;
        outline: none;
        font-family: 'Poppins', sans-serif;
        color: #222;
        background: #fff;
    }

    .header-search button {
        background: #e83e10;
        color: #fff;
        border: none;
        padding: 0 18px;
        cursor: pointer;
        font-size: 16px;
        transition: background .2s;
    }
    .header-search button:hover { background: #c23209; }

    /* ─── HEADER ACTIONS — right column ─────────────── */
    .header-actions {
        display: flex;
        align-items: center;
        gap: 6px;
        justify-self: end;
    }

    .hdr-btn {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        color: #fff;
        cursor: pointer;
        padding: 6px 10px;
        border-radius: 8px;
        font-size: 11px;
        text-decoration: none;
        transition: background .2s;
        line-height: 1.2;
        position: relative;
        min-width: 52px;
        text-align: center;
    }
    .hdr-btn:hover { background: rgba(255,255,255,.1); color: #fff; }
    .hdr-btn i { font-size: 22px; margin-bottom: 2px; }
    .hdr-btn strong { font-size: 12px; font-weight: 600; }

    /* Cart badge */
    .cart-badge {
        position: absolute;
        top: 2px; right: 4px;
        background: #e83e10;
        color: #fff;
        border-radius: 50%;
        width: 18px; height: 18px;
        font-size: 10px; font-weight: 700;
        display: flex; align-items: center; justify-content: center;
        border: 2px solid #1a1f36;
    }

    /* ─── ACCOUNT DROPDOWN ───────────────────────────── */
    .account-wrapper { position: relative; }
    .account-dropdown {
        display: none;
        position: absolute;
        top: calc(100% + 8px);
        right: 0;
        width: 260px;
        background: #fff;
        border-radius: 10px;
        box-shadow: 0 8px 32px rgba(0,0,0,.18);
        border: 1px solid #e5e7eb;
        z-index: 99999;
        overflow: hidden;
    }
    .account-wrapper:hover .account-dropdown { display: block; }

    .acc-header {
        background: linear-gradient(135deg,#1a1f36,#2d3561);
        color: #fff;
        padding: 16px;
    }
    .acc-header small { font-size: 11px; opacity: .7; }
    .acc-header strong { font-size: 15px; display: block; margin-top: 2px; }

    .acc-links { padding: 10px 0; }
    .acc-links a {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 9px 18px;
        font-size: 13px;
        color: #1f2937;
        text-decoration: none;
        transition: background .15s;
    }
    .acc-links a:hover { background: #f8f9fa; color: #e83e10; }
    .acc-links a i { font-size: 16px; width: 20px; }

    .acc-sign-btn {
        display: block;
        margin: 12px 16px;
        background: #e83e10;
        color: #fff !important;
        text-align: center;
        padding: 10px;
        border-radius: 8px;
        font-weight: 600;
        font-size: 14px;
        transition: background .2s;
    }
    .acc-sign-btn:hover { background: #c23209 !important; }

    .acc-footer {
        border-top: 1px solid #f1f1f1;
        padding: 10px 18px;
        font-size: 12px;
        color: #888;
    }
    .acc-footer a { color: #e83e10; }

    /* ─── NAV BAR ────────────────────────────────────── */
    .header-nav {
        background: #141828;
        display: flex;
        align-items: center;
        padding: 0 20px;
        height: 40px;
        gap: 4px;
        overflow: visible;
    }

    .nav-item-link {
        color: #d1d5db;
        font-size: 13px;
        padding: 0 14px;
        height: 40px;
        display: flex;
        align-items: center;
        gap: 6px;
        font-weight: 500;
        border-radius: 4px;
        transition: background .15s, color .15s;
        white-space: nowrap;
    }
    .nav-item-link:hover { background: rgba(255,255,255,.08); color: #fff; }

    /* ─── CATEGORY MEGA MENU ─────────────────────────── */
    .nav-all {
        position: relative;
        flex-shrink: 0;
    }

    .nav-all-btn {
        display: flex;
        align-items: center;
        gap: 8px;
        color: #fff;
        font-size: 13px;
        font-weight: 600;
        padding: 0 14px;
        height: 40px;
        background: rgba(232,62,16,.2);
        border-radius: 4px;
        cursor: pointer;
        border: 1px solid rgba(232,62,16,.3);
        transition: background .15s;
        white-space: nowrap;
    }
    .nav-all-btn:hover { background: rgba(232,62,16,.4); }

    .mega-menu {
        display: none;
        position: absolute;
        top: 40px; left: 0;
        width: 240px;
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 0 0 10px 10px;
        box-shadow: 0 8px 24px rgba(0,0,0,.14);
        z-index: 9000;
    }
    .nav-all:hover .mega-menu { display: block; }

    .mega-menu ul,
    .mega-menu .category-menu ul { margin: 0; padding: 8px 0; list-style: none; }
    .mega-menu li { position: relative; }
    .mega-menu li > a,
    .mega-menu li > .menu-link {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 10px 16px;
        font-size: 13px;
        color: #1f2937;
        font-weight: 500;
    }
    .mega-menu li > a:hover,
    .mega-menu li > .menu-link:hover { background: #fef2f0; color: #e83e10; }

    /* category_item partial uses class "submenu" */
    .submenu {
        display: none;
        position: absolute;
        top: 0; left: 240px;
        width: 220px;
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 0 10px 10px 0;
        box-shadow: 4px 4px 16px rgba(0,0,0,.1);
        z-index: 9001;
    }
    .mega-menu li.has-submenu:hover > .submenu { display: block; }
    .submenu { padding: 8px 0; list-style: none; margin: 0; }
    .submenu li a { padding: 8px 16px; font-size: 13px; color: #374151; display: block; }
    .submenu li a:hover { background: #fef2f0; color: #e83e10; }

    /* ─── HAMBURGER BUTTON ───────────────────────────── */
    .hamburger-btn {
        display: none;
        background: none;
        border: none;
        color: #fff;
        font-size: 26px;
        cursor: pointer;
        padding: 4px 6px;
        line-height: 1;
        flex-shrink: 0;
    }

    /* ─── MOBILE DRAWER ──────────────────────────────── */
    .mobile-drawer {
        display: none;
        position: fixed;
        top: 0; left: -300px;
        width: 280px;
        height: 100vh;
        background: #1a1f36;
        z-index: 999999;
        overflow-y: auto;
        transition: left .28s ease;
        flex-direction: column;
    }
    .mobile-drawer.open { left: 0; }

    .drawer-header {
        background: #141828;
        padding: 16px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        border-bottom: 1px solid rgba(255,255,255,.1);
    }
    .drawer-header img { height: 36px; object-fit: contain; border-radius: 4px; }
    .drawer-close {
        background: none; border: none; color: #fff;
        font-size: 24px; cursor: pointer; line-height: 1; padding: 0;
    }

    .drawer-user {
        padding: 14px 16px;
        background: linear-gradient(135deg,#e83e10,#ff7043);
        color: #fff;
    }
    .drawer-user small { font-size: 11px; opacity: .8; display: block; }
    .drawer-user strong { font-size: 14px; }
    .drawer-user a {
        display: inline-block; margin-top: 8px;
        background: #fff; color: #e83e10;
        font-size: 12px; font-weight: 700;
        padding: 5px 14px; border-radius: 6px;
        text-decoration: none;
    }

    .drawer-nav { padding: 8px 0; }
    .drawer-nav a {
        display: flex; align-items: center; gap: 12px;
        padding: 13px 20px;
        color: #d1d5db;
        font-size: 14px; font-weight: 500;
        text-decoration: none;
        border-bottom: 1px solid rgba(255,255,255,.05);
        transition: background .15s, color .15s;
    }
    .drawer-nav a:hover { background: rgba(255,255,255,.08); color: #fff; }
    .drawer-nav a i { font-size: 18px; width: 22px; color: #e83e10; }
    .drawer-nav .drawer-section-title {
        padding: 10px 20px 4px;
        font-size: 10px; font-weight: 700; letter-spacing: 1.5px;
        color: rgba(255,255,255,.3); text-transform: uppercase;
    }

    /* ─── MOBILE ─────────────────────────────────────── */
    @media (max-width: 991px) {
        body { padding-top: 0 !important; }

        .header-main {
            grid-template-columns: auto auto 1fr auto;
            padding: 10px 12px;
            gap: 8px;
        }

        .header-search { width: 100%; max-width: 100%; }
        .header-search select { display: none; }

        .hdr-btn strong { display: none; }
        .hdr-btn { min-width: 36px; padding: 4px 6px; }

        .header-nav { display: none; }

        .hamburger-btn { display: block; }
        .mobile-drawer { display: flex; }

        .mobile-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,.5);
            z-index: 99998;
        }
        .mobile-overlay.show { display: block; }
    }
</style>

<!-- TOP BAR -->
<div class="header-top">
    Free delivery on orders above ₹499 &nbsp;|&nbsp; 100% Authentic Products &nbsp;|&nbsp; Easy Returns
</div>

<header class="site-header">

    <!-- MAIN ROW -->
    <div class="header-main">

        <!-- Hamburger (mobile only) -->
        <button class="hamburger-btn" id="hamburgerBtn" aria-label="Menu">
            <i class="la la-bars"></i>
        </button>

        <!-- Logo -->
        <a href="{{ route('website.home') }}" class="header-logo flex-shrink-0">
            <img src="{{ asset('/company_logo/SHREEYOGITRADERS.jpg') }}" alt="Bestow">
        </a>

        <!-- Search -->
        <form action="{{ route('website.search') }}" method="GET" class="header-search">
            <select name="slugs">
                <option value="">All</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat->slug }}" {{ request('slugs') == $cat->slug ? 'selected' : '' }}>
                        {{ $cat->category_name ?? $cat->name }}
                    </option>
                @endforeach
            </select>
            <input name="keyword" placeholder="Search products, brands…" value="{{ request('keyword') }}" autocomplete="off">
            <button type="submit"><i class="la la-search"></i></button>
        </form>

        <!-- Actions -->
        <div class="header-actions">

            <!-- Account -->
            <div class="account-wrapper">
                <div class="hdr-btn">
                    <i class="la la-user-circle"></i>
                    <strong>Account</strong>
                </div>
                <div class="account-dropdown">
                    @if($isLoggedIn)
                        <div class="acc-header">
                            <small>Hello,</small>
                            <strong>{{ Session::get('user')['name'] }}</strong>
                        </div>
                        <div class="acc-links">
                            <a href="{{ route('website.account.dashboard') }}"><i class="la la-tachometer-alt"></i> My Dashboard</a>
                            <a href="{{ route('website.orders') }}"><i class="la la-shopping-bag"></i> My Orders</a>
                            <a href="{{ route('website.logout') }}" style="color:#dc2626;"><i class="la la-sign-out-alt"></i> Sign Out</a>
                        </div>
                    @else
                        <div class="acc-header">
                            <small>Welcome!</small>
                            <strong>Sign in to your account</strong>
                        </div>
                        <a href="{{ route('website.login') }}" class="acc-sign-btn">Sign In</a>
                        <div class="acc-footer">
                            New? <a href="{{ route('website.register') }}">Create account</a>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Cart -->
            <a href="{{ route('website.cart.view') }}" class="hdr-btn" style="position:relative;">
                <i class="la la-shopping-cart"></i>
                <strong>Cart</strong>
                <span class="cart-badge">{{ $cartCount }}</span>
            </a>

        </div>
    </div>

    <!-- NAV BAR -->
    <nav class="header-nav">

        <!-- All Categories -->
        <div class="nav-all" id="navAll">
            <div class="nav-all-btn" id="navAllBtn">
                <i class="la la-bars"></i> All Categories
            </div>
            <div class="mega-menu" id="megaMenu">
                @include('website.partials.category_menu')
            </div>
        </div>

        <!-- Quick links -->
        <a href="{{ route('website.home') }}" class="nav-item-link">
            <i class="la la-home"></i> Home
        </a>
        <a href="{{ route('website.categories.all') }}" class="nav-item-link">
            <i class="la la-th-list"></i> Categories
        </a>
        <a href="{{ route('website.brands.all') }}" class="nav-item-link">
            <i class="la la-certificate"></i> Brands
        </a>
        <a href="{{ route('website.contact') }}" class="nav-item-link">
            <i class="la la-phone"></i> Contact
        </a>
        <a href="{{ route('website.track.order') }}" class="nav-item-link">
            <i class="la la-truck"></i> Track Order
        </a>

    </nav>

</header>

<!-- Mobile overlay -->
<div class="mobile-overlay" id="mobileOverlay"></div>

<!-- Mobile Drawer -->
<div class="mobile-drawer" id="mobileDrawer">

    <div class="drawer-header">
        <img src="{{ asset('/company_logo/SHREEYOGITRADERS.jpg') }}" alt="Bestow">
        <button class="drawer-close" id="drawerClose"><i class="la la-times"></i></button>
    </div>

    @if($isLoggedIn)
    <div class="drawer-user">
        <small>Hello,</small>
        <strong>{{ Session::get('user')['name'] }}</strong>
    </div>
    @else
    <div class="drawer-user">
        <small>Welcome to Bestow!</small>
        <strong>Sign in for best experience</strong><br>
        <a href="{{ route('website.login') }}">Sign In / Register</a>
    </div>
    @endif

    <div class="drawer-nav">
        <div class="drawer-section-title">Navigation</div>
        <a href="{{ route('website.home') }}"><i class="la la-home"></i> Home</a>
        <a href="{{ route('website.product.view') }}"><i class="la la-th-large"></i> All Products</a>
        <a href="{{ route('website.categories.all') }}"><i class="la la-th-list"></i> Categories</a>
        <a href="{{ route('website.brands.all') }}"><i class="la la-certificate"></i> Brands</a>

        @if(isset($categories) && $categories->count())
        <div class="drawer-section-title">Shop by Category</div>
        @foreach($categories as $cat)
        <a href="{{ route('website.product.view', ['category' => $cat->slug]) }}">
            <i class="la la-tag"></i> {{ $cat->category_name ?? $cat->name }}
        </a>
        @endforeach
        @endif

        <div class="drawer-section-title">Help</div>
        <a href="{{ route('website.track.order') }}"><i class="la la-truck"></i> Track Order</a>
        <a href="{{ route('website.contact') }}"><i class="la la-phone"></i> Contact Us</a>

        @if($isLoggedIn)
        <div class="drawer-section-title">Account</div>
        <a href="{{ route('website.account.dashboard') }}"><i class="la la-tachometer-alt"></i> My Dashboard</a>
        <a href="{{ route('website.orders') }}"><i class="la la-shopping-bag"></i> My Orders</a>
        <a href="{{ route('website.logout') }}" style="color:#f87171;"><i class="la la-sign-out-alt"></i> Sign Out</a>
        @endif
    </div>

</div>

<script>
(function(){
    const overlay      = document.getElementById('mobileOverlay');
    const mobileDrawer = document.getElementById('mobileDrawer');
    const hamburgerBtn = document.getElementById('hamburgerBtn');
    const drawerClose  = document.getElementById('drawerClose');

    function openDrawer(){
        mobileDrawer.classList.add('open');
        overlay.classList.add('show');
        document.body.style.overflow = 'hidden';
    }
    function closeDrawer(){
        mobileDrawer.classList.remove('open');
        overlay.classList.remove('show');
        document.body.style.overflow = '';
    }

    if(hamburgerBtn) hamburgerBtn.addEventListener('click', openDrawer);
    if(drawerClose)  drawerClose.addEventListener('click', closeDrawer);
    if(overlay)      overlay.addEventListener('click', closeDrawer);

    /* ── Desktop: All Categories mega menu hover ── */
    const navAll   = document.getElementById('navAll');
    const navBtn   = document.getElementById('navAllBtn');
    const megaMenu = document.getElementById('megaMenu');

    if(navBtn && megaMenu){
        navBtn.addEventListener('click', function(e){
            if(window.innerWidth < 992) return; // desktop only
        });
    }
})();
</script>
