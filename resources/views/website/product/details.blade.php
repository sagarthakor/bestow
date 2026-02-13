@extends('website.template.layout')
@section('title', $product->product_name)

@section('content')

    <div class="container py-4">
        <div class="row g-4">

            {{-- LEFT IMAGE --}}
            <div class="col-lg-6">
                <div class="border p-3 text-center">
                    <img id="mainImage"
                         src="{{ asset('product_image/'.$initialImage) }}"
                         class="img-fluid"
                         style="max-height:500px;object-fit:contain;">
                </div>
            </div>

            {{-- RIGHT --}}
            <div class="col-lg-6">

                <h3 class="fw-bold">{{ $product->product_name }}</h3>
                <p class="text-muted small">Item Code: {{ $product->item_code }}</p>

                <hr>

                {{-- PRICE --}}
                <h3 class="text-danger">
                    ₹<span id="priceBox">{{ number_format($initialPrice,2) }}</span>
                </h3>
                <small class="text-success">Inclusive of all taxes</small>

                <hr>

                {{-- SIZE SELECT --}}
                <div class="mt-3">
                    <label class="fw-bold mb-2">Size:</label>
                    <div id="sizeRow" class="d-flex flex-wrap gap-2"></div>
                </div>

                <hr>

                {{-- BUY BOX --}}
                <div class="border p-3 rounded mt-3">
                    <button class="btn btn-warning w-100 mb-2"
                            onclick="addToCartAction()">
                        Add to Cart
                    </button>

                    <button class="btn btn-danger w-100"
                            onclick="buyNowAction()">
                        Buy Now
                    </button>
                </div>

            </div>
        </div>
    </div>

@endsection


@section('page-javascript')
    <script>

        const VARIANT_GROUP = @json($variant_group);
        const DEFAULT_SIZE = @json($defaultSize);

        let selectedVariant = null;

        document.addEventListener('DOMContentLoaded', function () {
            renderSizes();
        });


        function renderSizes() {

            const wrap = document.getElementById('sizeRow');
            wrap.innerHTML = '';

            VARIANT_GROUP.sizes.forEach(size => {

                const btn = document.createElement('button');
                btn.type = 'button';
                btn.className = 'btn btn-outline-dark';
                btn.innerText = size.size;

                btn.dataset.id = size.id;
                btn.dataset.price = size.price;
                btn.dataset.image = size.image;

                btn.onclick = function() {
                    selectSize(this);
                };

                wrap.appendChild(btn);

                if (size.size === DEFAULT_SIZE) {
                    selectSize(btn);
                }
            });
        }


        function selectSize(btn) {

            document.querySelectorAll('#sizeRow button')
                .forEach(b => b.classList.remove('active'));

            btn.classList.add('active');

            selectedVariant = {
                id: btn.dataset.id,
                price: parseFloat(btn.dataset.price),
                image: btn.dataset.image
            };

            document.getElementById('priceBox')
                .textContent = selectedVariant.price.toFixed(2);

            if (selectedVariant.image) {
                document.getElementById('mainImage').src =
                    '/product_image/' + selectedVariant.image;
            }

            // Amazon style URL update
            const url = new URL(window.location);
            url.searchParams.set('variant', selectedVariant.id);
            window.history.replaceState({}, '', url);
        }


        function addToCartAction() {
            if (!selectedVariant) {
                alert('Please select size');
                return;
            }
            window.addToCart(selectedVariant.id,1);
        }

        function buyNowAction() {
            if (!selectedVariant) {
                alert('Please select size');
                return;
            }
            window.buyNow(selectedVariant.id,1);
        }

    </script>
@endsection
