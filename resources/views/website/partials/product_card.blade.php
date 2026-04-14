@php
    $slug       = $product->slug ?? '';
    $vid        = $product->id ?? '';
    $name       = $product->clean_name ?: ($product->product_name ?? 'Product');
    $price      = $product->price ?? 0;
    $img        = $product->cover_image ?: ($product->product_image ?? '');
    $colorCount = $product->color_count ?? 1;
    $link       = route('website.product.details', ['slug' => $slug]);
@endphp

<div class="product-card h-100">
    <a href="{{ $link }}" class="img-wrap">
        <img src="{{ asset('product_image/' . $img) }}"
             alt="{{ $name }}"
             onerror="this.src='{{ asset('website-assets/img/placeholder.jpg') }}'">
        @if($colorCount > 1)
            <span class="color-count-badge">{{ $colorCount }} colors</span>
        @endif
    </a>

    <div class="card-body">
        <a href="{{ $link }}" class="p-title">{{ $name }}</a>
        <div class="p-price">₹{{ number_format($price, 2) }}</div>

        <div class="p-actions">
            <a href="{{ $link }}" class="btn btn-outline-secondary btn-sm" style="font-size:11px;">Details</a>
            <button class="btn btn-brand btn-sm" onclick="addToCart({{ $vid }}, 1)">
                <i class="la la-cart-plus"></i> Add
            </button>
        </div>
    </div>
</div>
