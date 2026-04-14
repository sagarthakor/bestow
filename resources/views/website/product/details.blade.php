@extends('website.template.layout')
@section('title', $product->clean_name ?: $product->product_name)

@section('page-css')
<style>
    /* ─── IMAGE GALLERY ──────────────────── */
    .gallery-main {
        border: 1px solid var(--border);
        border-radius: 12px;
        background: #fff;
        overflow: hidden;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 20px;
        min-height: 420px;
    }
    .gallery-main img {
        max-height: 380px;
        max-width: 100%;
        object-fit: contain;
        transition: transform .35s ease;
        cursor: zoom-in;
    }
    .gallery-main img:hover { transform: scale(1.06); }

    /* ─── SIZE CHIPS ─────────────────────── */
    .size-btn {
        min-width: 52px;
        padding: 8px 14px;
        border: 2px solid var(--border);
        border-radius: 8px;
        background: #fff;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        transition: all .18s;
        color: var(--text);
    }
    .size-btn:hover { border-color: var(--brand); color: var(--brand); }
    .size-btn.active { border-color: var(--brand); background: var(--brand); color: #fff; }
    .size-btn.out-of-stock { opacity: .4; text-decoration: line-through; cursor: not-allowed; }

    /* ─── QTY CONTROL ────────────────────── */
    .qty-wrap {
        display: flex;
        align-items: center;
        border: 1px solid var(--border);
        border-radius: 8px;
        overflow: hidden;
        width: fit-content;
    }
    .qty-btn {
        width: 38px; height: 38px;
        background: #f8f9fa;
        border: none;
        font-size: 18px;
        cursor: pointer;
        display: flex; align-items: center; justify-content: center;
        transition: background .15s;
    }
    .qty-btn:hover { background: #e83e10; color: #fff; }
    .qty-input {
        width: 52px;
        text-align: center;
        border: none;
        border-left: 1px solid var(--border);
        border-right: 1px solid var(--border);
        font-size: 15px;
        font-weight: 600;
        outline: none;
        padding: 6px 0;
        font-family: 'Poppins', sans-serif;
    }

    /* ─── BUY BOX ────────────────────────── */
    .buy-box {
        background: #fff;
        border: 1px solid var(--border);
        border-radius: 12px;
        padding: 20px;
    }

    /* ─── TABS ───────────────────────────── */
    .pd-tabs .nav-link {
        color: var(--mid);
        font-weight: 500;
        font-size: 14px;
        padding: 10px 20px;
        border: none;
        border-bottom: 2px solid transparent;
        border-radius: 0;
    }
    .pd-tabs .nav-link.active {
        color: var(--brand);
        border-bottom-color: var(--brand);
        background: transparent;
    }

    /* ─── STOCK BADGE ────────────────────── */
    .stock-in  { color: #16a34a; font-weight: 600; font-size: 13px; }
    .stock-out { color: #dc2626; font-weight: 600; font-size: 13px; }
</style>
@endsection

@section('content')

<!-- Breadcrumb -->
<div class="ec-breadcrumb">
    <div class="container">
        <ol>
            <li><a href="{{ route('website.home') }}">Home</a></li>
            <li><a href="{{ route('website.product.view') }}">Products</a></li>
            <li>{{ $product->clean_name ?: $product->product_name }}</li>
        </ol>
    </div>
</div>

<div class="container py-4">
    <div class="row g-4">

        {{-- ── LEFT: IMAGE ─────────────────────────────── --}}
        <div class="col-lg-5">
            <div class="gallery-main mb-3">
                <img id="mainImage"
                     src="{{ asset('product_image/' . ($product->cover_image ?: $initialImage)) }}"
                     alt="{{ $product->clean_name ?: $product->product_name }}">
            </div>
        </div>

        {{-- ── RIGHT: INFO ──────────────────────────────── --}}
        <div class="col-lg-7">

            <!-- Title -->
            <h2 class="fw-700" style="font-size:22px; line-height:1.3; margin-bottom:6px;">
                {{ $product->clean_name ?: $product->product_name }}
            </h2>

            @if($product->item_code)
                <p class="text-muted" style="font-size:12px; margin-bottom:12px;">
                    Item Code: <strong>{{ $product->item_code }}</strong>
                </p>
            @endif

            <!-- Price -->
            <div style="margin-bottom:14px;">
                <span style="font-size:30px; font-weight:800; color:var(--brand);">
                    ₹<span id="priceBox">{{ number_format($initialPrice, 2) }}</span>
                </span>
                <span class="ml-2" style="font-size:13px; color:#16a34a; font-weight:600;">
                    <i class="la la-check-circle"></i> Inclusive of all taxes
                </span>
            </div>

            <!-- Stock -->
            <div id="stockStatus" class="stock-in mb-3">
                <i class="la la-check-circle"></i> In Stock
            </div>

            <hr style="border-color:var(--border);">

            <!-- Color Selection -->
            @if(count($colorGroups) > 1 || ($colorGroups[0]['color'] ?? 'Default') !== 'Default')
            <div class="mb-4">
                <label style="font-size:15px; font-weight:700; margin-bottom:10px; display:block;">
                    Color:
                    <span id="selectedColorLabel" style="color:var(--brand); font-weight:600;"></span>
                </label>
                <div id="colorRow" class="d-flex flex-wrap" style="gap:8px;"></div>
            </div>
            @endif

            <!-- Size Selection -->
            <div class="mb-4">
                <label style="font-size:15px; font-weight:700; margin-bottom:10px; display:block;">
                    Size:
                    <span id="selectedSizeLabel" style="color:var(--brand); font-weight:600;"></span>
                </label>
                <div id="sizeRow" class="d-flex flex-wrap" style="gap:10px;"></div>
            </div>

            <!-- Quantity -->
            <div class="mb-4">
                <label style="font-size:15px; font-weight:700; margin-bottom:10px;">Quantity:</label>
                <div class="qty-wrap">
                    <button class="qty-btn" onclick="changeQty(-1)">−</button>
                    <input type="number" class="qty-input" id="qtyInput" value="1" min="1" max="99">
                    <button class="qty-btn" onclick="changeQty(1)">+</button>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="buy-box mb-4">
                <div class="d-flex" style="gap:12px; flex-wrap:wrap;">
                    <button class="btn-brand" style="flex:1; min-width:140px; padding:14px 20px; font-size:15px; border-radius:8px;"
                            onclick="addToCartAction()">
                        <i class="la la-shopping-cart"></i> Add to Cart
                    </button>
                    <button class="btn-brand" style="flex:1; min-width:140px; padding:14px 20px; font-size:15px; border-radius:8px; background:var(--navy);"
                            onclick="buyNowAction()">
                        <i class="la la-bolt"></i> Buy Now
                    </button>
                </div>

                <!-- Delivery info -->
                <div class="mt-3 p-3" style="background:#f8f9fa; border-radius:8px;">
                    <div class="d-flex align-items-center gap-2 mb-1" style="gap:10px; display:flex;">
                        <i class="la la-truck" style="color:var(--brand); font-size:18px;"></i>
                        <small><strong>Free delivery</strong> on orders above ₹499</small>
                    </div>
                    <div class="d-flex align-items-center gap-2" style="gap:10px; display:flex;">
                        <i class="la la-undo-alt" style="color:var(--brand); font-size:18px;"></i>
                        <small><strong>Easy 7-day returns</strong> &amp; exchange</small>
                    </div>
                </div>
            </div>

        </div>
    </div>

    {{-- ── DESCRIPTION TABS ──────────────────────────── --}}
    <div class="row mt-2">
        <div class="col-12">
            <div class="ec-card" style="padding:0; overflow:hidden;">
                <ul class="nav pd-tabs border-bottom">
                    <li class="nav-item">
                        <a class="nav-link active" href="#desc" data-toggle="tab">Description</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#details" data-toggle="tab">Product Details</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#shipping" data-toggle="tab">Shipping Info</a>
                    </li>
                </ul>
                <div class="tab-content p-4">
                    <div class="tab-pane fade show active" id="desc">
                        @if($product->description)
                            <p style="line-height:1.8; color:var(--mid);">{{ $product->description }}</p>
                        @else
                            <p style="color:var(--mid);">Premium quality product. Comfortable and durable.</p>
                        @endif
                    </div>
                    <div class="tab-pane fade" id="details">
                        <table class="table table-sm" style="font-size:14px;">
                            <tr><td class="text-muted" width="160">Item Code</td><td><strong>{{ $product->item_code ?? '-' }}</strong></td></tr>
                            @if($product->attribute1 ?? false)
                            <tr><td class="text-muted">{{ $product->attribute1 }}</td><td><strong>{{ $product->value1 ?? '-' }}</strong></td></tr>
                            @endif
                            @if($product->attribute2 ?? false)
                            <tr><td class="text-muted">{{ $product->attribute2 }}</td><td><strong>{{ $product->value2 ?? '-' }}</strong></td></tr>
                            @endif
                        </table>
                    </div>
                    <div class="tab-pane fade" id="shipping">
                        <ul style="line-height:2; color:var(--mid); font-size:14px; padding-left:18px;">
                            <li>Free delivery on orders above ₹499</li>
                            <li>Standard delivery in 3–7 business days</li>
                            <li>Orders are dispatched within 24–48 hours</li>
                            <li>7-day easy return &amp; exchange policy</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

@endsection

@section('page-javascript')
<script>
    const COLOR_GROUPS    = @json($colorGroups);
    const SELECTED_COLOR  = @json($selectedColor);
    const DEFAULT_SIZE    = @json($defaultSize);

    let activeColor   = SELECTED_COLOR;
    let selectedVariant = null;

    document.addEventListener('DOMContentLoaded', () => {
        renderColors();
        renderSizes(activeColor);
    });

    /* ── Color chips ── */
    function renderColors() {
        const wrap = document.getElementById('colorRow');
        if (!wrap) return;
        wrap.innerHTML = '';

        COLOR_GROUPS.forEach(grp => {
            const btn = document.createElement('button');
            btn.type      = 'button';
            btn.className = 'size-btn' + (grp.color === activeColor ? ' active' : '');
            btn.textContent = grp.color;
            btn.title       = grp.color;
            btn.onclick     = () => selectColor(grp.color, btn);
            wrap.appendChild(btn);
        });

        // Set label
        const lbl = document.getElementById('selectedColorLabel');
        if (lbl) lbl.textContent = activeColor !== 'Default' ? activeColor : '';
    }

    function selectColor(color, btn) {
        activeColor = color;
        document.querySelectorAll('#colorRow .size-btn').forEach(b => b.classList.remove('active'));
        btn.classList.add('active');

        const lbl = document.getElementById('selectedColorLabel');
        if (lbl) lbl.textContent = color !== 'Default' ? color : '';

        // Update image to this color's image
        const grp = COLOR_GROUPS.find(g => g.color === color);
        if (grp && grp.image) {
            document.getElementById('mainImage').src = '/product_image/' + grp.image;
        }

        // Re-render sizes for new color (no pre-selection)
        renderSizes(color);

        // Update URL to this color's slug
        if (grp && grp.slug) {
            const url = new URL(window.location);
            const newPath = url.pathname.replace(/\/[^\/]+$/, '/' + grp.slug);
            window.history.replaceState({}, '', newPath);
        }
    }

    /* ── Size chips ── */
    function renderSizes(color) {
        const wrap = document.getElementById('sizeRow');
        wrap.innerHTML = '';
        selectedVariant = null;

        const grp = COLOR_GROUPS.find(g => g.color === color);
        if (!grp || !grp.sizes.length) return;

        grp.sizes.forEach(size => {
            const btn = document.createElement('button');
            btn.type        = 'button';
            btn.className   = 'size-btn';
            btn.textContent = size.size;
            btn.dataset.id    = size.id;
            btn.dataset.price = size.price;
            btn.dataset.image = size.image;
            btn.onclick = () => selectSize(btn);
            wrap.appendChild(btn);

            // Auto-select default size when on the same color
            if (color === SELECTED_COLOR && size.size === DEFAULT_SIZE) {
                selectSize(btn);
            }
        });
    }

    function selectSize(btn) {
        document.querySelectorAll('#sizeRow .size-btn').forEach(b => b.classList.remove('active'));
        btn.classList.add('active');

        selectedVariant = {
            id:    btn.dataset.id,
            price: parseFloat(btn.dataset.price),
        };

        document.getElementById('priceBox').textContent = selectedVariant.price.toFixed(2);
        document.getElementById('selectedSizeLabel').textContent = btn.textContent;

        // Image does NOT change on size select — only changes when color/design is switched
        const url = new URL(window.location);
        url.searchParams.set('variant', selectedVariant.id);
        window.history.replaceState({}, '', url);
    }

    /* ── Qty ── */
    function changeQty(delta) {
        const input = document.getElementById('qtyInput');
        let val = parseInt(input.value) + delta;
        if (val < 1)  val = 1;
        if (val > 99) val = 99;
        input.value = val;
    }

    function getQty() {
        return parseInt(document.getElementById('qtyInput').value) || 1;
    }

    /* ── Cart / Buy Now ── */
    function addToCartAction() {
        if (!selectedVariant) { window.toast('Please select a size', 'warning'); return; }
        window.addToCart(selectedVariant.id, getQty());
    }

    function buyNowAction() {
        if (!selectedVariant) { window.toast('Please select a size', 'warning'); return; }
        window.buyNow(selectedVariant.id, getQty());
    }
</script>
@endsection
