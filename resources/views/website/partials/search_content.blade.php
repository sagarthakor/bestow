{{-- <div class="">
    @if (!$keywords->isEmpty())
    <div class="px-2 py-1 text-uppercase fs-10 text-right text-muted bg-soft-secondary">@lang('Popular Suggestions')</div>
    <ul class="list-group list-group-raw">
        @foreach ($keywords as $key => $keyword)
        <li class="list-group-item py-1">
            <a class="text-reset hov-text-primary" href="{{ route('website.suggestion.search', $keyword) }}">{{ $keyword }}</a>
        </li>
        @endforeach
    </ul>
    @endif
</div> --}}
<div class="">
    @if (!$categories->isEmpty())
    <div class="px-2 py-1 text-uppercase fs-10 text-right text-muted bg-soft-secondary">@lang('Category Suggestions')</div>
    <ul class="list-group list-group-raw">
        @foreach ($categories as $key => $category)
        <li class="list-group-item py-1">
            <a class="text-reset hov-text-primary" href="{{ route('website.products.category', $category->slug) }}">{{ $category->name }}</a>
        </li>
        @endforeach
    </ul>
    @endif
</div>
<div class="">
    @if (!$products->isEmpty())
    <div class="px-2 py-1 text-uppercase fs-10 text-right text-muted bg-soft-secondary">@lang('Products')</div>
    <ul class="list-group list-group-raw">
        @foreach ($products as $key => $product)
        <li class="list-group-item">
            <a class="text-reset" href="{{ route('website.product.details', ['slug' => $product->slug, 'code' => $product->price->code]) }}">
                <div class="d-flex search-product align-items-center">
                    <div class="mr-3">
                        <img class="size-40px img-fit rounded" src="{{$product->price->primary_image}}" alt="{{$product->name}}"
                        onerror="this.onerror=null;this.src='{{ asset('website-assets/img/placeholder.jpg') }}';">
                    </div>
                    <div class="flex-grow-1 overflow--hidden minw-0">
                        <div class="product-name text-truncate fs-14 mb-5px">
                            {{  $product->name  }}
                        </div>
                        <div class="">
                            @user
                            <del class="opacity-60 fs-15">{{ $product->price->price }}</del>
                            <span class="fw-600 fs-16 text-primary">{{ $product->price->selling_price }}</span>
                            @enduser
                            @guest
                            <span class="fw-600 fs-16 text-primary">{{ $product->price->price }}</span>
                            @endguest
                        </div>
                    </div>
                </div>
            </a>
        </li>
        @endforeach
    </ul>
    @endif
</div>
{{-- <div class="">
    @if (count($shops) > 0)
    <div class="px-2 py-1 text-uppercase fs-10 text-right text-muted bg-soft-secondary">{{translate('Shops')}}</div>
    <ul class="list-group list-group-raw">
        @foreach ($shops as $key => $shop)
        <li class="list-group-item">
            <a class="text-reset" href="{{ route('shop.visit', $shop->slug) }}">
                <div class="d-flex search-product align-items-center">
                    <div class="mr-3">
                        <img class="size-40px img-fit rounded" src="{{ uploaded_asset($shop->logo) }}">
                    </div>
                    <div class="flex-grow-1 overflow--hidden">
                        <div class="product-name text-truncate fs-14 mb-5px">
                            {{ $shop->name }}
                        </div>
                        <div class="opacity-60">
                            {{ $shop->address }}
                        </div>
                    </div>
                </div>
            </a>
        </li>
        @endforeach
    </ul>
    @endif
</div> --}}

