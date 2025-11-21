@extends('website.template.layout')
@section('title', $product->clean_name . ' : Details')

@section('content')

    <style>
        /* ---- Gallery ---- */
        .img-zoom-wrap { position: relative; }
        .main-box { width: 100%; height: 480px; background:#f8f9fa; border:1px solid #ddd; }
        #mainImage { width:100%; height:100%; object-fit:contain; cursor: zoom-in; }
        #zoomResult {
            position:absolute; left:103%; top:0;
            width: 450px; height: 450px;
            border:1px solid #ddd; background-repeat:no-repeat; background-size:200% 200%;
            display:none; z-index:1000;
        }
        .thumbs img { width:70px; height:70px; object-fit:cover; border:1px solid #ccc; border-radius:5px; cursor:pointer; margin-bottom:8px; }
        .thumbs img.active { border:2px solid #0d6efd; }

        /* ---- Variants ---- */
        .color-pill { width:86px; cursor:pointer; border-radius:8px; border:1px solid #ddd; background:#fff; padding:6px; text-align:center; transition:.15s; }
        .color-pill.active { border:2px solid #0d6efd; box-shadow:0 0 0 3px rgba(13,110,253,.15); }
        .size-pill { min-width:62px; text-transform:uppercase; }
        .size-pill.active { background:#0d6efd; color:#fff; border-color:#0d6efd; }

        /* ---- Amazon Buy Box ---- */
        .buy-box {
            background:#f8f9fa;
            border:1px solid #ddd;
            padding:16px;
            border-radius:8px;
            max-width:340px;
        }
        .btn-amz-add {
            background:#ffd814;
            border-color:#f7ca00;
            font-weight:600;
        }
        .btn-amz-buy {
            background:#ffa41c;
            border-color:#f08804;
            font-weight:600;
        }

        /* ---- Description ---- */
        .desc-text {
            line-height:1.7;
            font-size:14px;
        }

        /* ---- Specifications ---- */
        .spec-table th {
            background:#f3f3f3 !important;
            font-weight:600;
            width:180px;
            color:#333;
        }
        .spec-table td {
            background:#fff;
            color:#555;
        }
        .spec-table { font-size:14px; }
    </style>

    <div class="container py-4" id="pdp">
        <div class="row g-4">

            <!-- LEFT: gallery -->
            <div class="col-lg-6">
                <div class="row no-gutters">
                    <div class="col-auto pr-2 d-none d-md-block">
                        <div class="thumbs">
                            @foreach($thumbs as $i => $img)
                                <img src="{{ asset('product_image/'.$img) }}"
                                     class="{{ $i===0?'active':'' }}"
                                     onclick="setMainImage('{{ asset('product_image/'.$img) }}', this)">
                            @endforeach
                        </div>
                    </div>

                    <div class="col">
                        <div class="img-zoom-wrap">
                            <div class="main-box">
                                <img id="mainImage"
                                     src="{{ asset('product_image/'.$initialImage) }}"
                                     alt="{{ $product->clean_name }}"
                                     onerror="this.src='{{ asset('website-assets/img/placeholder.jpg') }}'">
                            </div>
                            <div id="zoomResult"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- RIGHT: Amazon style -->
            <div class="col-lg-6">

                <!-- Title -->
                <h2 class="fw-bold mb-1">{{ $product->clean_name }}</h2>
                <p class="text-muted small mb-2">Item Code: {{ $product->item_code }}</p>

                {{--<span class="badge bg-success mb-3">In stock</span>--}}

                <hr>

                <!-- PRICE -->
                <div class="mb-3">
                    <span class="text-muted small">Price:</span>
                    <h3 class="text-primary fw-bold mb-0">
                        ₹<span id="priceBox">{{ number_format($initialPrice,2) }}</span>
                    </h3>
                    <p class="text-success small mt-1">Inclusive of all taxes</p>
                </div>

                <hr>

                <!-- COLOR -->
                <div class="mb-3">
                    <label class="fw-bold mb-2">Color:</label>
                    <div id="colorRow" class="d-flex flex-wrap gap-2">
                        @foreach($variant_groups as $group)
                            <button type="button"
                                    class="color-pill {{ $group['color']===$defaultColor?'active':'' }}"
                                    data-color="{{ $group['color'] }}"
                                    data-image="{{ asset('product_image/'.$group['image']) }}"
                                    onclick="selectColor(this)">
                                <img src="{{ asset('product_image/'.$group['image']) }}"
                                     class="rounded mb-1"
                                     style="width:58px;height:58px;object-fit:cover">
                                <div class="small fw-semibold">{{ $group['color'] }}</div>
                            </button>
                        @endforeach
                    </div>
                </div>

                <!-- SIZE -->
                <div class="mb-3">
                    <label class="fw-bold mb-2">Size:</label>
                    <div id="sizeRow" class="d-flex flex-wrap gap-2"></div>
                </div>

                <hr>

                <!-- BUY BOX -->
                <div class="buy-box">

                    <button class="btn btn-amz-add w-100 mb-2" onclick="addToCartAction()">
                        <i class="la la-shopping-cart me-1"></i> Add to Cart
                    </button>

                    <button class="btn btn-amz-buy w-100" onclick="buyNowAction()">
                        <i class="la la-bolt me-1"></i> Buy Now
                    </button>

                    <p class="text-muted small mt-2 mb-0">
                        <i class="la la-shield-alt text-success"></i> Secure transaction
                    </p>

                </div>

                <hr>

                <!-- DESCRIPTION -->
                <div class="mt-4">
                    <h5 class="fw-bold mb-2">Product Description</h5>

                    @if(!empty($product->product_description))
                        <div class="desc-text">{!! nl2br(e($product->product_description)) !!}</div>
                    @else
                        <p class="text-muted small">No description available.</p>
                    @endif
                </div>

                <!-- SPECIFICATIONS -->
                <hr class="my-4">

                <h5 class="fw-bold mb-3">Product Specifications</h5>

                @if(count($specs))
                    <table class="table table-bordered spec-table">
                        <tbody>
                        @foreach($specs as $k => $v)
                            <tr>
                                <th>{{ $k }}</th>
                                <td>{{ $v }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                @else
                    <p class="text-muted small">No specifications available.</p>
                @endif

            </div>
        </div>
    </div>


@endsection


@section('page-javascript')
    <script>
        /** ------------ Data ------------ */
        const VARIANTS = @json($variant_groups);
        const DEFAULT_COLOR = @json($defaultColor);

        let selectedColor = DEFAULT_COLOR || (VARIANTS[0]?.color ?? null);
        let selectedVariant = null;

        /** Init */
        document.addEventListener('DOMContentLoaded', () => {
            renderSizesForColor(selectedColor, true);
            setupZoom();
        });

        /** ---- Gallery ---- */
        function setMainImage(src, thumbEl = null) {
            const img = document.getElementById('mainImage');
            const zoom = document.getElementById('zoomResult');
            img.src = src;
            zoom.style.backgroundImage = `url('${src}')`;
            if (thumbEl) {
                document.querySelectorAll('.thumbs img').forEach(i => i.classList.remove('active'));
                thumbEl.classList.add('active');
            }
        }

        /** ---- Color ---- */
        function selectColor(btn) {
            document.querySelectorAll('#colorRow .color-pill').forEach(b => b.classList.remove('active'));
            btn.classList.add('active');

            selectedColor = btn.dataset.color;
            setMainImage(btn.dataset.image);
            renderSizesForColor(selectedColor, true);
        }

        /** ---- Size Rendering ---- */
        function renderSizesForColor(color, autoPickFirst = false) {
            const wrap = document.getElementById('sizeRow');
            wrap.innerHTML = '';
            const group = VARIANTS.find(v => v.color === color);
            if (!group) { selectedVariant = null; return; }

            group.sizes.forEach(s => {
                const btn = document.createElement('button');
                btn.type = 'button';
                btn.className = 'btn btn-outline-secondary size-pill';
                btn.dataset.id = s.id;
                btn.dataset.size = s.size;
                btn.dataset.price = s.price;
                btn.dataset.img = `/product_image/${s.product_image || ''}`;
                btn.textContent = s.size;
                btn.onclick = () => selectSize(btn);
                wrap.appendChild(btn);
            });

            if (autoPickFirst && group.sizes.length) {
                const first = wrap.querySelector('.size-pill');
                if (first) selectSize(first);
            }
        }

        /** ---- Size Select ---- */
        function selectSize(btn) {
            document.querySelectorAll('#sizeRow .size-pill').forEach(b => b.classList.remove('active'));
            btn.classList.add('active');

            selectedVariant = {
                id: btn.dataset.id,
                size: btn.dataset.size,
                price: parseFloat(btn.dataset.price || '0'),
                image: btn.dataset.img
            };

            document.getElementById('priceBox').textContent = selectedVariant.price.toFixed(2);
            if (selectedVariant.image) setMainImage(selectedVariant.image);
        }

        /** ---- Zoom ---- */
        function setupZoom() {
            const img = document.getElementById('mainImage');
            const result = document.getElementById('zoomResult');

            img.addEventListener('mouseover', function() {
                result.style.display = 'block';
                result.style.backgroundImage = `url('${img.src}')`;
            });
            img.addEventListener('mouseout', function() {
                result.style.display = 'none';
            });
            img.addEventListener('mousemove', function(e) {
                const rect = img.getBoundingClientRect();
                const x = ((e.clientX - rect.left) / img.clientWidth) * 100;
                const y = ((e.clientY - rect.top) / img.clientHeight) * 100;
                result.style.backgroundPosition = `${x}% ${y}%`;
            });
        }

        /** ---- Cart Actions ---- */
        function addToCartAction() {
            if (!selectedVariant) { Swal.fire('Select variant', 'Please select color and size.', 'info'); return; }
            window.addToCart(selectedVariant.id, 1);
        }
        function buyNowAction() {
            if (!selectedVariant) { Swal.fire('Select variant', 'Please select color and size.', 'info'); return; }
            window.buyNow(selectedVariant.id, 1);
        }
    </script>
@endsection
