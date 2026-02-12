<div class="row row-cols-2 row-cols-md-3 row-cols-lg-4 g-3">

    @foreach($items as $item)
        <div class="col">
            <div class="product-card">

                <img src="{{ asset('product_image/'.$item->product_image) }}"
                     class="product-img">

                <div class="mt-2 product-title">
                    {{ $item->name }}
                </div>

                <div class="rating">
                    ★★★★☆
                </div>

                <div class="price mt-1">
                    ₹{{ $item->price }}
                </div>

                <button class="btn btn-cart mt-2 " onclick="addToCart({{ $item->id }},1)">
                    Add to Cart
                </button>

            </div>
        </div>
    @endforeach

</div>

<div class="mt-4">
    {{ $items->links() }}
</div>
