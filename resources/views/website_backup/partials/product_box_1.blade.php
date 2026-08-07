<div class="aiz-card-box border border-light rounded hov-shadow-md mt-1 mb-2 has-transition bg-white">
    @php
    $discount = $product->price->price - $product->price->selling_price;
    $dp = ($discount * 100) / $product->price->price;
    @endphp
    {{-- @user --}}
    @if(round($dp) > 0)
    <span class="badge-custom">@lang('OFF')<span class="box ml-1 mr-0">&nbsp;{{ round($dp) }}%</span></span>
    @endif
    {{-- @enduser --}}
    <div class="position-relative">
        {{-- <div class="absolute-top-right aiz-p-hov-icon">
            <a href="javascript:void(0)" onclick="addToWishList()" data-toggle="tooltip" data-title="@lang('Add to wishlist')" data-placement="left">
                <i class="la la-heart-o"></i>
            </a>
            <a href="javascript:void(0)" onclick="addToCompare()" data-toggle="tooltip" data-title="@lang('Add to compare')" data-placement="left">
                <i class="las la-sync"></i>
            </a>
            <a href="javascript:void(0)" onclick="showAddToCartModal()" data-toggle="tooltip" data-title="@lang('Add to cart')" data-placement="left">
                <i class="las la-shopping-cart"></i>
            </a>
        </div> --}}
        <a href="{{ route('website.product.details', ['slug' => $product->slug, 'code' => $product->price->code]) }}" class="d-block">
            <img
            class="img-fit lazyload mx-auto h-140px h-md-210px"
            src="{{ asset('website-assets/img/placeholder.jpg') }}"
            data-src="{{$product->price->primary_image}}"
            alt="{{$product->name}}"
            onerror="this.onerror=null;this.src='{{ asset('website-assets/img/placeholder.jpg') }}';"
            >
        </a>
        {{-- @if ($product->isBeauticuian) --}}
        {{-- <span class="absolute-bottom-left fs-11 text-white fw-600 px-2 lh-1-8" style="background-color: #455a64">
            @lang('Beautician') --}}
            {{-- @endif --}}
        </div>
        <div class="p-md-3 p-2 text-left">
            <div class="fs-15">
                @guest
                <del class="fw-600 opacity-50 mr-1">₹ {{$product->price->price}}</del>
                <span class="fw-700 text-primary">₹ {{$product->price->selling_price}}</span>
                @endguest
                @user
                <del class="fw-600 opacity-50 mr-1">₹ {{$product->price->price}}</del>
                <span class="fw-700 text-primary">₹ {{$product->price->selling_price}}</span>
                @enduser
            </div>
            <div class="rating rating-sm mt-1">
               {{ App\Library\Helper::renderStarRating(5) }}
           </div>
           <h3 class="fw-600 fs-13 text-truncate-2 lh-1-4 mb-0 h-35px">
            <a href="{{ route('website.product.details', ['slug' => $product->slug, 'code' => $product->price->code]) }}" class="d-block text-reset">{{$product->name}}</a>
        </h3>
        @user
        <div class="rounded px-2 mt-2 text-white bg-soft-primary border-soft-primary border">
            @lang('SV Points'):
            <span class="fw-700 float-right">{{ $product->price->points }}</span>
        </div>
        @enduser
    </div>
</div>
