<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <!-- SEO Friendly Meta -->
    <meta name="viewport" content="width=device-width, height=device-height, initial-scale=1.0, maximum-scale=1.0"/>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="@yield('meta_description', 'Bestow – A Change Would Do You Good. Shop premium quality products at unbeatable prices.')">
    <meta name="keywords" content="@yield('meta_keywords', 'bestow, online shopping, premium store, ecommerce, offers')">
    <meta name="author" content="Bestow">

    <!-- OpenGraph (Facebook / WhatsApp / LinkedIn share) -->
    <meta property="og:title" content="@yield('title') | {{ config('project.company', 'Your Store') }}">
    <meta property="og:description" content="@yield('meta_description', 'Premium quality products at Bestow.')">
    <meta property="og:image" content="{{ asset('logo/favcon.png') }}">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:type" content="website">

    <!-- Twitter Card -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="@yield('title') | {{ config('project.company', 'Your Store') }}">
    <meta name="twitter:description" content="@yield('meta_description', 'Premium quality products at Bestow.')">
    <meta name="twitter:image" content="{{ asset('logo/favcon.png') }}">

    <!-- Title -->
    <title>@yield('title') | Welcome To {{ config('project.company', 'Your Store') }}</title>

    <!-- ✅ Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('logo/favcon.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('logo/favcon.png') }}">

    <!-- CSS -->
    <link rel="stylesheet" href="{{ asset('website-assets/css/vendors.css') }}">
    <link rel="stylesheet" href="{{ asset('website-assets/css/aiz-core.css') }}">
    <link rel="stylesheet" href="{{ asset('website-assets/css/custom-style.css') }}">
    <link rel="stylesheet" href="{{ asset('website-assets/css/style.css') }}">

    <!-- jQuery -->
    <script src="{{ asset('website-assets/js/jquery.min.js') }}"></script>

    <!-- Your Custom Styles -->
    <style>
        /* 🎨 Modern Unique Colors */
        :root{
            --primary-dark:#222831;
            --secondary-dark:#31363F;
            --accent:#FF5C5C;
            --accent-hover:#E64545;
            --light-bg:#F5F7FA;
            --card-bg:#FFFFFF;
            --text-color:#222;
        }

        .amz-header {
            background: #e83e10 !important;
            color: #ffffff;
        }

        .amz-subnav {
            background: #3A3A3A !important;
            color: #ffffff;
        }
        /* … other CSS you already added … */
    </style>

    @yield('page-css')
</head>
<body>
<div class="aiz-main-wrapper d-flex flex-column">
    @include('website.template.header')
    @yield('content')
    @include('website.template.footer')
</div>

<!-- JS base -->
<script src="{{ asset('website-assets/js/vendors.js') }}"></script>
<script src="{{ asset('website-assets/js/aiz-core.js') }}"></script>
<script src="{{ asset('website-assets/js/script.js') }}"></script>

<!-- ✅ Axios MUST be before our global functions -->
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

<!-- ✅ SweetAlert2 (Toast) -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // Simple toast helper
    const toast = (title = 'Done', icon = 'success') => {
        Swal.fire({
            toast: true,
            position: window.innerWidth < 768 ? 'bottom' : 'top-end',
            showConfirmButton: false,
            timer: 1400,
            timerProgressBar: true,
            icon: icon,
            title: title,
            background: '#333',
            color: '#fff',
        });
    };


    // CSRF
    window.csrfToken = document.querySelector('meta[name=csrf-token]').getAttribute('content');

    // ✅ Global: Update cart badge helper
    window.updateCartCount = function(count){
        const el1 = document.getElementById('cart-count');
        const el2 = document.querySelector('.cart-count');
        if (el1) el1.textContent = count;
        if (el2) el2.textContent = count;
    };

    // ✅ Global: Add to Cart
    window.addToCart = function(variant_id, qty = 1){
        return axios.post("{{ route('website.cart.add') }}", {
            variant_id: variant_id,
            quantity: qty,
            _token: window.csrfToken
        })
            .then(r => {
                if (r.data?.count !== undefined) {
                    window.updateCartCount(r.data.count);
                }
                toast('Added to cart');
            })
            .catch(err => {
                console.error(err);
                Swal.fire('Failed', 'Failed to add to cart', 'error');
            });
    };

    // ✅ Global: Buy Now (add then go to checkout)
    window.buyNow = function(variant_id, qty = 1){
        return window.addToCart(variant_id, qty).then(() => {
            window.location.href = "{{ route('website.checkout.view') }}";
        });
    };

    // (Optional) provide alias if any inline onclick="addToCart()" is used somewhere older
    function addToCart(variant_id, qty = 1){ return window.addToCart(variant_id, qty); }
    function buyNow(variant_id, qty = 1){ return window.buyNow(variant_id, qty); }
</script>

<!-- Page specific JS comes last -->
@yield('page-javascript')
</body>
</html>
