@extends('website.template.layout')
@section('title', 'Products')

@section('page-css')
<link rel="stylesheet" href="{{ asset('website-assets/css/product-filter.css') }}">
@endsection

@section('content')

<!-- Breadcrumb -->
<div class="ec-breadcrumb">
    <div class="container">
        <ol>
            <li><a href="{{ route('website.home') }}">Home</a></li>
            <li>Products</li>
            @if(request('keyword'))
                <li>Search: "{{ request('keyword') }}"</li>
            @endif
        </ol>
    </div>
</div>

<!-- Mobile filter overlay -->
<div class="filter-overlay" id="filterOverlay"></div>

<div class="container py-4">
    <div class="row">

        <!-- ── FILTER SIDEBAR ──────────────────── -->
        <div class="col-lg-3 mb-4 mb-lg-0" id="filterSidebarCol">
            <div class="filter-sidebar" id="filterSidebar">

                <div class="filter-header">
                    <span class="fh-title">Filters</span>
                    <button class="fh-clear" onclick="resetFilters()">Clear All</button>
                </div>

                <form method="GET" id="filterForm">

                    <!-- ── CATEGORY ── -->
                    @if($categories->count())
                    @php $activeCats = (array)request('category', []); $activeSubCats = (array)request('child_category', []); @endphp
                    <div class="filter-section">
                        <div class="filter-section-title" data-section="category">
                            Category
                            <div class="fst-right">
                                <i class="la la-angle-down"></i>
                            </div>
                        </div>
                        <div class="filter-section-body" id="section-category">
                            @foreach($categories as $i => $cat)
                            @php $cnt = $categoryCounts->get($cat->id, 0); @endphp
                            <label class="myn-check-item {{ $i >= 5 ? 'extra-item cat-extra' : '' }}" style="{{ $i >= 5 ? 'display:none' : '' }}">
                                <input type="checkbox" name="category[]" value="{{ $cat->slug }}"
                                    {{ in_array($cat->slug, $activeCats) ? 'checked' : '' }}>
                                <span class="myn-check-box"></span>
                                <span class="myn-check-label">{{ $cat->category_name }}</span>
                                @if($cnt) <span class="myn-check-count">({{ $cnt }})</span> @endif
                            </label>
                            @if($cat->subcategories->count())
                                @foreach($cat->subcategories as $sub)
                                <label class="myn-check-item sub-cat-indent {{ $i >= 5 ? 'extra-item cat-extra' : '' }}" style="{{ $i >= 5 ? 'display:none' : '' }}">
                                    <input type="checkbox" name="child_category[]" value="{{ $sub->slug }}"
                                        {{ in_array($sub->slug, $activeSubCats) ? 'checked' : '' }}>
                                    <span class="myn-check-box"></span>
                                    <span class="myn-check-label">{{ $sub->subcategory_name }}</span>
                                </label>
                                @endforeach
                            @endif
                            @endforeach
                            @if($categories->count() > 5)
                            <a class="show-more-link" onclick="toggleMore('cat-extra', this)">
                                + {{ $categories->count() - 5 }} more
                            </a>
                            @endif
                        </div>
                    </div>
                    @endif

                    <!-- ── BRAND ── -->
                    @if($brands->count())
                    @php $activeBrands = array_map('intval', (array)request('brand', [])); @endphp
                    <div class="filter-section">
                        <div class="filter-section-title" data-section="brand">
                            Brand
                            <div class="fst-right">
                                <i class="la la-search fst-search-icon" onclick="toggleSearch('brand-search', event)"></i>
                                <i class="la la-angle-down"></i>
                            </div>
                        </div>
                        <div class="filter-search-box" id="brand-search">
                            <input type="text" placeholder="Search brand" oninput="filterItems(this, 'brand-list')">
                        </div>
                        <div class="filter-section-body" id="section-brand">
                            <div id="brand-list">
                            @foreach($brands as $i => $b)
                            @php $cnt = $brandCounts->get($b->id, 0); @endphp
                            <label class="myn-check-item {{ $i >= 5 ? 'extra-item brand-extra' : '' }}" data-label="{{ strtolower($b->brand_name) }}" style="{{ $i >= 5 ? 'display:none' : '' }}">
                                <input type="checkbox" name="brand[]" value="{{ $b->id }}"
                                    {{ in_array($b->id, $activeBrands) ? 'checked' : '' }}>
                                <span class="myn-check-box"></span>
                                <span class="myn-check-label">{{ $b->brand_name }}</span>
                                @if($cnt) <span class="myn-check-count">({{ $cnt }})</span> @endif
                            </label>
                            @endforeach
                            </div>
                            @if($brands->count() > 5)
                            <a class="show-more-link" id="brand-more-link" onclick="toggleMore('brand-extra', this)">
                                + {{ $brands->count() - 5 }} more
                            </a>
                            @endif
                        </div>
                    </div>
                    @endif

                    <!-- ── SIZE ── -->
                    @if($sizes->count())
                    @php $activeSizes = (array)request('size', []); @endphp
                    <div class="filter-section">
                        <div class="filter-section-title" data-section="size">
                            Size
                            <div class="fst-right"><i class="la la-angle-down"></i></div>
                        </div>
                        <div class="filter-section-body" id="section-size">
                            @foreach($sizes as $i => $size)
                            <label class="myn-check-item {{ $i >= 6 ? 'extra-item size-extra' : '' }}" data-label="{{ strtolower($size) }}" style="{{ $i >= 6 ? 'display:none' : '' }}">
                                <input type="checkbox" name="size[]" value="{{ $size }}"
                                    {{ in_array($size, $activeSizes) ? 'checked' : '' }}>
                                <span class="myn-check-box"></span>
                                <span class="myn-check-label">{{ $size }}</span>
                            </label>
                            @endforeach
                            @if($sizes->count() > 6)
                            <a class="show-more-link" onclick="toggleMore('size-extra', this)">
                                + {{ $sizes->count() - 6 }} more
                            </a>
                            @endif
                        </div>
                    </div>
                    @endif

                    <!-- ── PRICE ── -->
                    @php
                        $sliderMin = $priceMin;
                        $sliderMax = $priceMax ?: 10000;
                        $curMin    = request('min_price', $sliderMin);
                        $curMax    = request('max_price', $sliderMax);
                    @endphp
                    <div class="filter-section">
                        <div class="filter-section-title" data-section="price">
                            Price
                            <div class="fst-right"><i class="la la-angle-down"></i></div>
                        </div>
                        <div class="filter-section-body" id="section-price">
                            <div class="price-slider-wrap">
                                <div class="price-range-display" id="priceDisplay">
                                    ₹<span id="priceMinLabel">{{ $curMin }}</span> – ₹<span id="priceMaxLabel">{{ $curMax }}</span>
                                </div>
                                <div class="dual-slider" id="dualSlider">
                                    <div class="track"></div>
                                    <div class="fill" id="sliderFill"></div>
                                    <input type="range" id="rangeMin"
                                        min="{{ $sliderMin }}" max="{{ $sliderMax }}"
                                        value="{{ $curMin }}" step="10">
                                    <input type="range" id="rangeMax"
                                        min="{{ $sliderMin }}" max="{{ $sliderMax }}"
                                        value="{{ $curMax }}" step="10">
                                </div>
                            </div>
                            {{-- hidden inputs submitted with form --}}
                            <input type="hidden" name="min_price" id="hiddenMinPrice" value="{{ $curMin }}">
                            <input type="hidden" name="max_price" id="hiddenMaxPrice" value="{{ $curMax }}">
                        </div>
                    </div>

                    <!-- ── COLOR ── -->
                    @if($colors->count())
                    @php $activeColors = (array)request('color', []); @endphp
                    <div class="filter-section">
                        <div class="filter-section-title" data-section="color">
                            Color
                            <div class="fst-right">
                                <i class="la la-search fst-search-icon" onclick="toggleSearch('color-search', event)"></i>
                                <i class="la la-angle-down"></i>
                            </div>
                        </div>
                        <div class="filter-search-box" id="color-search">
                            <input type="text" placeholder="Search color" oninput="filterItems(this, 'color-list')">
                        </div>
                        <div class="filter-section-body" id="section-color">
                            <div id="color-list">
                            @foreach($colors as $i => $color)
                            <label class="myn-color-item {{ $i >= 7 ? 'extra-item color-extra' : '' }}" data-label="{{ strtolower($color) }}" style="{{ $i >= 7 ? 'display:none' : '' }}">
                                <input type="checkbox" name="color[]" value="{{ $color }}"
                                    {{ in_array($color, $activeColors) ? 'checked' : '' }}>
                                <span class="myn-color-dot-wrap">
                                    <span class="myn-color-dot" style="background:{{ $color }};"></span>
                                </span>
                                <span class="myn-color-name">{{ $color }}</span>
                            </label>
                            @endforeach
                            </div>
                            @if($colors->count() > 7)
                            <a class="show-more-link" id="color-more-link" onclick="toggleMore('color-extra', this)">
                                + {{ $colors->count() - 7 }} more
                            </a>
                            @endif
                        </div>
                    </div>
                    @endif

                    {{-- Preserve keyword --}}
                    @if(request('keyword'))
                        <input type="hidden" name="keyword" value="{{ request('keyword') }}">
                    @endif
                    @if(request('sort'))
                        <input type="hidden" name="sort" value="{{ request('sort') }}">
                    @endif

                    {{-- Price filter auto-submits on slider release --}}

                </form>
            </div>
        </div>

        <!-- ── PRODUCTS ────────────────────────── -->
        <div class="col-lg-9">

            <!-- Mobile filter btn -->
            <button class="mobile-filter-btn" onclick="openFilter()">
                <i class="la la-sliders-h"></i> Filters
            </button>

            <!-- Sort bar -->
            <div class="sort-bar">
                <span class="result-count">
                    <strong>{{ $items->total() }}</strong> products found
                    @if(request('keyword'))
                        for "<em>{{ request('keyword') }}</em>"
                    @endif
                </span>

                <form method="GET" class="d-flex align-items-center" style="gap:8px;">
                    @foreach(request()->except('sort') as $key => $value)
                        @if(is_array($value))
                            @foreach($value as $v)
                                <input type="hidden" name="{{ $key }}[]" value="{{ $v }}">
                            @endforeach
                        @else
                            <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                        @endif
                    @endforeach

                    <select name="sort" onchange="this.form.submit()" class="form-control" style="width:auto; padding:7px 12px !important;">
                        <option value="">Sort by</option>
                        <option value="price_low"  {{ request('sort')=='price_low'  ? 'selected':'' }}>Price: Low to High</option>
                        <option value="price_high" {{ request('sort')=='price_high' ? 'selected':'' }}>Price: High to Low</option>
                        <option value="newest"     {{ request('sort')=='newest'     ? 'selected':'' }}>Newest First</option>
                    </select>
                </form>
            </div>

            <!-- Grid -->
            <div class="row g-3">
                @forelse($items as $product)
                    <div class="col-6 col-md-4 col-lg-3">
                        @include('website.partials.product_card', ['product' => $product])
                    </div>
                @empty
                    <div class="col-12">
                        <div style="text-align:center; padding:60px 20px; background:#fff; border-radius:12px; border:1px solid var(--border);">
                            <i class="la la-search" style="font-size:60px; color:#d1d5db;"></i>
                            <h5 style="margin:16px 0 8px;">No Products Found</h5>
                            <p style="color:var(--mid);">Try adjusting your filters or search query</p>
                            <a href="{{ route('website.product.view') }}" class="btn-brand" style="display:inline-block; padding:10px 24px;">
                                Clear Filters
                            </a>
                        </div>
                    </div>
                @endforelse
            </div>

            <!-- Pagination -->
            @if($items->lastPage() > 1)
            <div class="mt-4">
                {{ $items->appends(request()->all())->links('pagination::bootstrap-4') }}
            </div>
            @endif

        </div>
    </div>
</div>

@endsection

@section('page-javascript')
<script>
    /* ── Mobile filter drawer ── */
    function openFilter() {
        document.getElementById('filterSidebar').classList.add('open');
        document.getElementById('filterOverlay').classList.add('show');
    }
    document.getElementById('filterOverlay').addEventListener('click', function() {
        document.getElementById('filterSidebar').classList.remove('open');
        this.classList.remove('show');
    });

    function resetFilters() {
        window.location.href = "{{ route('website.product.view') }}";
    }

    /* ── Auto-submit on checkbox change ── */
    document.getElementById('filterForm').addEventListener('change', function(e) {
        if (e.target.type === 'checkbox') {
            this.submit();
        }
    });

    /* ── Auto-submit price slider on release ── */
    ['rangeMin', 'rangeMax'].forEach(function(id) {
        document.getElementById(id).addEventListener('change', function() {
            document.getElementById('filterForm').submit();
        });
    });

    /* ── Collapsible sections ── */
    document.querySelectorAll('.filter-section-title').forEach(title => {
        title.addEventListener('click', function(e) {
            if (e.target.closest('.fst-search-icon')) return;
            const key  = this.dataset.section;
            const body = document.getElementById('section-' + key);
            this.classList.toggle('collapsed');
            const collapsed = this.classList.contains('collapsed');
            if (body) body.style.display = collapsed ? 'none' : '';
            // hide search box when collapsing, don't force-show when expanding
            const search = document.getElementById(key + '-search');
            if (search && collapsed) search.style.display = 'none';
        });
    });

    /* ── Search icon toggle ── */
    function toggleSearch(id, e) {
        e.stopPropagation();
        const box = document.getElementById(id);
        const isOpen = box.style.display === 'block';
        box.style.display = isOpen ? 'none' : 'block';
        if (!isOpen) box.querySelector('input').focus();
    }

    /* ── Filter list by search text ── */
    function filterItems(input, listId) {
        const q    = input.value.toLowerCase();
        const list = document.getElementById(listId);
        list.querySelectorAll('.myn-check-item, .myn-color-item').forEach(item => {
            const label = item.dataset.label || '';
            item.style.display = label.includes(q) ? '' : 'none';
        });
    }

    /* ── Show more / less ── */
    function toggleMore(cls, link) {
        const items   = document.querySelectorAll('.' + cls);
        const showing = link.dataset.expanded === '1';
        items.forEach(el => el.style.display = showing ? 'none' : '');
        if (showing) {
            link.dataset.expanded = '0';
            const total = items.length;
            link.textContent = '+ ' + total + ' more';
        } else {
            link.dataset.expanded = '1';
            link.textContent = '- Show less';
        }
    }

    /* ── Size chip visual toggle ── */
    document.querySelectorAll('.size-chip').forEach(chip => {
        chip.addEventListener('click', () => chip.classList.toggle('sel'));
    });

    /* ── Dual range price slider ── */
    (function() {
        const rMin   = document.getElementById('rangeMin');
        const rMax   = document.getElementById('rangeMax');
        const fill   = document.getElementById('sliderFill');
        const minLbl = document.getElementById('priceMinLabel');
        const maxLbl = document.getElementById('priceMaxLabel');
        const hMin   = document.getElementById('hiddenMinPrice');
        const hMax   = document.getElementById('hiddenMaxPrice');

        if (!rMin || !rMax) return;

        function updateSlider() {
            const min  = parseInt(rMin.min), max = parseInt(rMax.max);
            const vMin = parseInt(rMin.value), vMax = parseInt(rMax.value);
            const pMin = ((vMin - min) / (max - min)) * 100;
            const pMax = ((vMax - min) / (max - min)) * 100;
            fill.style.left  = pMin + '%';
            fill.style.right = (100 - pMax) + '%';
            minLbl.textContent = vMin;
            maxLbl.textContent = vMax;
            hMin.value = vMin;
            hMax.value = vMax;
        }

        rMin.addEventListener('input', function() {
            if (parseInt(rMin.value) > parseInt(rMax.value) - 10)
                rMin.value = parseInt(rMax.value) - 10;
            updateSlider();
        });
        rMax.addEventListener('input', function() {
            if (parseInt(rMax.value) < parseInt(rMin.value) + 10)
                rMax.value = parseInt(rMin.value) + 10;
            updateSlider();
        });

        updateSlider();
    })();
</script>
@endsection
