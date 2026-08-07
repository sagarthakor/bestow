<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="@yield('meta_description', 'Bestow – Premium Quality Socks & More. Shop at unbeatable prices.')">
    <meta name="keywords" content="@yield('meta_keywords', 'bestow, socks, online shopping, premium store')">
    <meta name="author" content="Bestow">

    <meta property="og:title" content="@yield('title') | {{ config('project.company', 'Bestow') }}">
    <meta property="og:description" content="@yield('meta_description', 'Premium socks & accessories at Bestow.')">
    <meta property="og:image" content="{{ asset('logo/favcon.png') }}">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:type" content="website">

    <title>@yield('title') | {{ config('project.company', 'Bestow Store') }}</title>

    <link rel="icon" type="image/png" href="{{ asset('logo/favcon.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('logo/favcon.png') }}">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Bootstrap 4 + Line Awesome -->
    <link rel="stylesheet" href="{{ asset('website-assets/css/vendors.css') }}">
    <link rel="stylesheet" href="{{ asset('website-assets/css/aiz-core.css') }}">
    <link rel="stylesheet" href="{{ asset('website-assets/css/custom-style.css') }}">
    <link rel="stylesheet" href="{{ asset('website-assets/css/style.css') }}">

    <!-- jQuery -->
    <script src="{{ asset('website-assets/js/jquery.min.js') }}"></script>

    <style>
        :root {
            --brand:       #e83e10;
            --brand-dark:  #c23209;
            --brand-light: #fff3ef;
            --navy:        #1a1f36;
            --dark:        #222831;
            --mid:         #555;
            --light-bg:    #f8f9fa;
            --card-bg:     #ffffff;
            --border:      #e5e7eb;
            --text:        #1f2937;
            --radius:      10px;
            --shadow:      0 2px 12px rgba(0,0,0,.08);
            --shadow-hover:0 6px 24px rgba(0,0,0,.14);
        }

        * { box-sizing: border-box; }

        body {
            font-family: 'Poppins', sans-serif;
            color: var(--text);
            background: var(--light-bg);
            margin: 0;
            padding: 0;
        }

        a { color: inherit; text-decoration: none; }
        a:hover { color: var(--brand); }

        /* ── Buttons ────────────────────────────────── */
        .btn-brand {
            background: var(--brand);
            color: #fff;
            border: none;
            border-radius: 6px;
            padding: 10px 22px;
            font-weight: 600;
            font-size: 14px;
            transition: background .2s, transform .15s;
            cursor: pointer;
        }
        .btn-brand:hover { background: var(--brand-dark); color: #fff; transform: translateY(-1px); }

        .btn-outline-brand {
            background: transparent;
            color: var(--brand);
            border: 2px solid var(--brand);
            border-radius: 6px;
            padding: 8px 20px;
            font-weight: 600;
            font-size: 14px;
            transition: all .2s;
            cursor: pointer;
        }
        .btn-outline-brand:hover { background: var(--brand); color: #fff; }

        /* ── Cards ──────────────────────────────────── */
        .ec-card {
            background: var(--card-bg);
            border-radius: var(--radius);
            box-shadow: var(--shadow);
            border: 1px solid var(--border);
            transition: box-shadow .2s, transform .2s;
        }
        .ec-card:hover { box-shadow: var(--shadow-hover); transform: translateY(-2px); }

        /* ── Product Card ───────────────────────────── */
        .product-card {
            background: #fff;
            border-radius: var(--radius);
            border: 1px solid var(--border);
            overflow: hidden;
            display: flex;
            flex-direction: column;
            transition: box-shadow .2s, transform .2s;
            position: relative;
        }
        .product-card:hover { box-shadow: var(--shadow-hover); transform: translateY(-3px); }

        .product-card .img-wrap {
            aspect-ratio: 1 / 1;
            overflow: hidden;
            background: #f9f9f9;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 12px;
        }
        .product-card .img-wrap img {
            max-width: 100%;
            max-height: 100%;
            object-fit: contain;
            transition: transform .3s;
        }
        .product-card:hover .img-wrap img { transform: scale(1.05); }

        .product-card .card-body { padding: 12px; display: flex; flex-direction: column; flex: 1; }
        .product-card .p-title {
            font-size: 13px;
            font-weight: 500;
            line-height: 1.45;
            height: 38px;
            overflow: hidden;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            color: var(--text);
            margin-bottom: 8px;
        }
        .product-card .p-price {
            font-size: 16px;
            font-weight: 700;
            color: var(--brand);
            margin-bottom: 8px;
        }
        .product-card .p-actions { margin-top: auto; display: flex; gap: 6px; }
        .product-card .p-actions .btn { flex: 1; font-size: 12px; font-weight: 600; padding: 7px 4px; border-radius: 6px; }

        /* ── Section Header ─────────────────────────── */
        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 18px;
        }
        .section-header h4 {
            font-size: 20px;
            font-weight: 700;
            color: var(--navy);
            margin: 0;
            position: relative;
            padding-bottom: 6px;
        }
        .section-header h4::after {
            content: '';
            position: absolute;
            bottom: 0; left: 0;
            width: 40px; height: 3px;
            background: var(--brand);
            border-radius: 2px;
        }
        .section-header a { font-size: 13px; color: var(--brand); font-weight: 600; }

        /* ── Breadcrumb ─────────────────────────────── */
        .ec-breadcrumb { background: #fff; padding: 10px 0; border-bottom: 1px solid var(--border); }
        .ec-breadcrumb ol { margin: 0; padding: 0; list-style: none; display: flex; flex-wrap: wrap; gap: 6px; }
        .ec-breadcrumb li { font-size: 13px; color: var(--mid); }
        .ec-breadcrumb li a { color: var(--brand); }
        .ec-breadcrumb li + li::before { content: '/'; margin-right: 6px; }

        /* ── Alerts ─────────────────────────────────── */
        .alert { border-radius: 8px; font-size: 14px; padding: 12px 16px; }

        /* ── Form Controls ──────────────────────────── */
        .form-control, .form-select, select.form-control {
            border-radius: 8px !important;
            border: 1px solid var(--border) !important;
            font-size: 14px !important;
            padding: 10px 14px !important;
            font-family: 'Poppins', sans-serif;
            transition: border-color .2s, box-shadow .2s;
        }
        .form-control:focus, .form-select:focus, select.form-control:focus {
            border-color: var(--brand) !important;
            box-shadow: 0 0 0 3px rgba(232,62,16,.12) !important;
            outline: none;
        }
        label { font-size: 13px; font-weight: 500; color: var(--dark); margin-bottom: 4px; display: block; }

        /* ── Badge ──────────────────────────────────── */
        .badge-brand { background: var(--brand); color: #fff; border-radius: 20px; padding: 3px 10px; font-size: 12px; font-weight: 600; }
        .badge-success { background: #16a34a; color: #fff; border-radius: 20px; padding: 3px 10px; font-size: 12px; font-weight: 600; }
        .badge-warning { background: #f59e0b; color: #111; border-radius: 20px; padding: 3px 10px; font-size: 12px; font-weight: 600; }
        .badge-danger  { background: #dc2626; color: #fff; border-radius: 20px; padding: 3px 10px; font-size: 12px; font-weight: 600; }

        /* ── Page wrapper ───────────────────────────── */
        .page-wrapper { min-height: calc(100vh - 200px); }

        /* ── Scrollbar ──────────────────────────────── */
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: #f1f1f1; }
        ::-webkit-scrollbar-thumb { background: #ccc; border-radius: 3px; }
        ::-webkit-scrollbar-thumb:hover { background: var(--brand); }

        /* ── Utility ────────────────────────────────── */
        .text-brand { color: var(--brand) !important; }
        .bg-brand   { background: var(--brand) !important; }
        .fw-600     { font-weight: 600; }
        .fw-700     { font-weight: 700; }

        /* ── SweetAlert2 toast — always above sticky header ── */
        .swal2-container { z-index: 999999 !important; }

    </style>

    @yield('page-css')
</head>
<body>

<div class="aiz-main-wrapper d-flex flex-column">
    @include('website.template.header')
    <main class="page-wrapper">
        @yield('content')
    </main>
    @include('website.template.footer')
</div>

<!-- Vendors JS -->
<script src="{{ asset('website-assets/js/vendors.js') }}"></script>
<script src="{{ asset('website-assets/js/aiz-core.js') }}"></script>
<script src="{{ asset('website-assets/js/script.js') }}"></script>

<!-- Axios -->
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    /* ── Toast helper ──────────────────────────────── */
    window.toast = function(title = 'Done', icon = 'success') {
        Swal.fire({
            toast: true,
            position: 'bottom-end',
            showConfirmButton: false,
            timer: 2500,
            timerProgressBar: true,
            icon: icon,
            title: title,
            background: '#1a1f36',
            color: '#fff',
            iconColor: icon === 'success' ? '#4ade80' : '#f87171',
            customClass: { container: 'swal-toast-top' },
        });
    };

    /* ── CSRF ──────────────────────────────────────── */
    window.csrfToken = document.querySelector('meta[name=csrf-token]').getAttribute('content');
    axios.defaults.headers.common['X-CSRF-TOKEN'] = window.csrfToken;

    /* ── Cart count badge ──────────────────────────── */
    window.updateCartCount = function(count) {
        document.querySelectorAll('.cart-badge').forEach(el => el.textContent = count);
    };

    /* ── Add to Cart ───────────────────────────────── */
    window.addToCart = function(variant_id, qty = 1) {
        return axios.post("{{ route('website.cart.add') }}", { variant_id, quantity: qty, _token: window.csrfToken })
            .then(r => {
                if (r.data?.count !== undefined) window.updateCartCount(r.data.count);
                window.toast('Added to cart ✓');
                return r;
            })
            .catch(err => {
                console.error(err);
                window.toast('Could not add to cart', 'error');
            });
    };

    /* ── Buy Now ───────────────────────────────────── */
    window.buyNow = function(variant_id, qty = 1) {
        return window.addToCart(variant_id, qty).then(() => {
            window.location.href = "{{ route('website.checkout.view') }}";
        });
    };

    // Legacy compat
    function addToCart(v, q = 1) { return window.addToCart(v, q); }
    function buyNow(v, q = 1)    { return window.buyNow(v, q); }
</script>

@yield('page-javascript')
</body>
</html>
