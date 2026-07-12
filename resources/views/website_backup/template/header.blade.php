@php $isLoggedIn = Session::has('user'); @endphp

<header class="amz-header shadow-sm">

    <style>

        /*************************************************
            COMMON CATEGORY MENU
        *************************************************/
        .category-menu ul{
            margin:0; padding:0; list-style:none;
        }
        .category-menu li{ position:relative; }
        .category-menu li a{
            display:flex;
            justify-content:space-between;
            padding:10px 12px;
            color:#111;
            font-size:15px;
            text-decoration:none;
        }
        .category-menu li a:hover{
            background:#f3f3f3;
        }

        /*************************************************
            AMAZON ACCOUNT DROPDOWN
        *************************************************/
        .account-menu{
            display:none;
            position:absolute;
            top:100%; right:0;
            width:320px;
            background:#fff;
            border:1px solid #ddd;
            border-radius:4px;
            box-shadow:0 4px 12px rgba(0,0,0,0.2);
            z-index:99999;
        }
        .account-wrapper:hover .account-menu{ display:block; }

        .amz-acc-box{ padding:12px 18px; }
        .amz-acc-box h4{
            margin-bottom:8px;
            font-size:15px; font-weight:700;
        }
        .amz-acc-box a{
            display:block;
            padding:5px 0;
            font-size:14px;
            color:#111;
            text-decoration:none;
        }
        .amz-acc-box a:hover{ color:#e83e10; }

        .amz-signin-btn{
            background:#ffd814;
            padding:10px 16px;
            border-radius:4px;
            font-weight:600;
            border:1px solid #f0c400;
            display:inline-block;
        }
        .amz-signin-btn:hover{ background:#f7ca00; }

        /*************************************************
            DESKTOP CATEGORY MEGA MENU
        *************************************************/
        @media(min-width:992px){

            .amz-mega{
                position:absolute;
                top:40px;
                left:0;
                width:260px;
                background:#fff;
                border:1px solid #ddd;
                display:none;
                z-index:9000;
            }
            .amz-all:hover .amz-mega{ display:block; }

            /* RIGHT SUBCATEGORY PANEL */
            .submenu{
                display:none;
                position:absolute;
                top:0; left:260px;
                width:250px;
                background:#fff;
                border:1px solid #ddd;
                z-index:99999;
            }
            .has-submenu:hover > .submenu{
                display:block;
            }

            .submenu li a{
                color:#111 !important;
                padding:8px 14px;
                font-size:14px;
                display:block;
            }
            .submenu li a:hover{
                background:#f3f3f3;
                color:#e83e10 !important;
            }

            .has-submenu i{ display:inline-block; }
        }

        /*************************************************
            MOBILE HEADER FIX (Amazon Style)
        *************************************************/
        @media(max-width:991px){

            /* HEADER FIXED */
            .amz-header{
                position:fixed !important;
                top:0; left:0;
                width:100%;
                z-index:999999 !important;
            }

            body{ padding-top:110px !important; }

            /* SEARCH BAR FIX */
            .amz-search{
                flex:1;
                max-width:100% !important;
                margin:0 8px !important;
                position:relative;
                z-index:999999 !important;
            }

            /* MOBILE DRAWER */
            .amz-mega{
                position:fixed;
                top:110px !important;   /* drawer under header */
                left:-260px;
                width:260px;
                height:100vh;
                background:#fff;
                overflow-y:auto;
                transition:left .3s;
                z-index:99998;
            }
            .amz-all.active .amz-mega{
                left:0;
            }

            /* hide arrows & submenu */
            .submenu{ display:none !important; }
            .has-submenu i{ display:none !important; }
        }

        /*************************************************
            SEARCH BAR STYLING
        *************************************************/
        .amz-search{
            display:flex;
            flex:1;
            max-width:650px;
            margin:0 20px;
        }
        .amz-cat{
            background:#f3f3f3;
            border:1px solid #ccc;
            border-right:0;
            padding:8px 10px;
            border-radius:4px 0 0 4px;
            font-size:14px;
            min-width:90px;
        }
        .amz-input{
            flex:1;
            border:1px solid #ccc;
            border-left:0;
            border-right:0;
            padding:8px;
            outline:none;
            font-size:14px;
        }
        .amz-btn{
            background:#f3a847;
            border:1px solid #e1a94f;
            padding:8px 12px;
            border-radius:0 4px 4px 0;
        }

    </style>



    {{-- TOP HEADER --}}
    <div class="container-fluid d-flex align-items-center justify-content-between py-2">

        {{-- LOGO --}}
        <a href="{{ route('website.home') }}">
            <img src="{{ asset('/company_logo/SHREEYOGITRADERS.jpg') }}" style="height:40px;">
        </a>

        {{-- SEARCH BAR --}}
        <form action="{{ route('website.search') }}" method="GET" class="amz-search">

            <select class="amz-cat" name="slugs">
                <option value="">All</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat->slug }}" {{ request('slugs')==$cat->slug ? 'selected':'' }}>
                        {{ $cat->category_name ?? $cat->name }}
                    </option>
                @endforeach
            </select>

            <input name="keyword" class="amz-input" placeholder="Search products..." value="{{ request('keyword') }}">

            <button class="amz-btn"><i class="la la-search"></i></button>

        </form>

        {{-- ACCOUNT + CART --}}
        <div class="d-flex align-items-center">

            {{-- AMAZON ACCOUNT MENU --}}
            <div class="account-wrapper text-white px-3" style="position:relative; cursor:pointer;">
                <div style="line-height:1;">
                    <small>Hello, {{ $isLoggedIn ? Session::get('user')['name'] : 'Sign in' }}</small><br>
                    <strong>Account & Lists</strong>
                </div>

                <div class="account-menu">
                    @if($isLoggedIn)

                        <div class="amz-acc-box">
                            <h4>Your Account</h4>
                            <a href="{{ route('website.orders') }}">Your Orders</a>
                            <a href="{{ route('website.account.dashboard') }}">Account Settings</a>
                        </div>

                        <hr>

                        <div class="amz-acc-box">
                            <a href="{{ route('website.logout') }}" style="color:#c40000;">Sign Out</a>
                        </div>

                    @else
                        <div class="amz-acc-box text-center">
                            <a href="{{ route('website.login') }}" class="amz-signin-btn">Sign in</a>
                            <p style="font-size:13px;margin-top:8px;">
                                New customer? <a href="{{ route('website.register') }}">Start here.</a>
                            </p>
                        </div>
                    @endif
                </div>
            </div>

            {{-- CART --}}
            <a href="{{ route('website.cart.view') }}" class="position-relative px-3 text-white">
                <i class="la la-shopping-cart la-2x"></i>
                <span id="cart-count" class="cart-count"
                      style="position:absolute; top:-6px; right:0;
      background:#f08804; color:#111;
      border-radius:10px; padding:0 6px;">
      {{ session('cart_count', 0) }}
</span>

            </a>

        </div>

    </div>



    {{-- NAVBAR --}}
    <div class="amz-subnav" style="background:#3A3A3A; color:white;">
        <div class="container-fluid d-flex align-items-center" style="height:40px;">

            {{-- ALL CATEGORY BUTTON --}}
            <div class="amz-all" style="cursor:pointer; padding:0 15px;">
                <span class="text-white"><i class="la la-bars mr-2"></i> All</span>

                <div class="amz-mega">
                    @include('website.partials.category_menu')
                </div>
            </div>

            {{-- LINKS --}}
            <div class="d-none d-lg-flex">
                <a href="{{ route('website.categories.all') }}" class="px-3 py-2 text-white">Categories</a>
                <a href="{{ route('website.brands.all') }}" class="px-3 py-2 text-white">Brands</a>
            </div>

        </div>
    </div>




</header>

<script>
    document.addEventListener("DOMContentLoaded", () => {

        const drawer = document.querySelector(".amz-all");
        const trigger = drawer.querySelector("span");

        trigger.addEventListener("click", (e) => {
            if(window.innerWidth < 992){
                e.preventDefault();
                e.stopPropagation();
                drawer.classList.toggle("active");
            }
        });

        document.addEventListener("click", (e) => {
            if(!drawer.contains(e.target)){
                drawer.classList.remove("active");
            }
        });

    });
</script>
