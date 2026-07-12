@extends('admin.layout.master')

@section('title', 'Out Of Stock Report')

@section('content')

<style>
    :root {
        --oos-primary: #4e73df;
        --oos-primary-dark: #224abe;
        --oos-danger: #e5484d;
        --oos-danger-dark: #c0392b;
        --oos-success: #1cc88a;
        --oos-success-dark: #17a673;
        --oos-amber: #f5a524;
        --oos-ink: #2d3748;
        --oos-muted: #718096;
        --oos-border: #e9ecf5;
    }

    * { box-sizing: border-box; }

    /* ── Page wrapper ── */
    .oos-page { padding: 22px 12px 32px; }

    /* ── Page header ── */
    .oos-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 16px;
        margin-bottom: 24px;
    }
    .oos-header .title-group { display: flex; align-items: center; gap: 14px; }
    .oos-header .header-icon {
        width: 50px; height: 50px;
        background: linear-gradient(135deg, var(--oos-danger), var(--oos-danger-dark));
        border-radius: 14px;
        display: flex; align-items: center; justify-content: center;
        color: #fff; font-size: 24px;
        box-shadow: 0 6px 16px rgba(229,72,77,.32);
        flex-shrink: 0;
    }
    .oos-header h4 { margin: 0; font-size: 21px; font-weight: 800; color: var(--oos-ink); letter-spacing: -.2px; }
    .oos-header p  { margin: 2px 0 0; font-size: 12.5px; color: var(--oos-muted); }

    /* ── Filter card ── */
    .filter-card {
        background: #fff;
        border-radius: 16px;
        box-shadow: 0 2px 20px rgba(31,45,90,.06);
        padding: 22px 24px 18px;
        margin-bottom: 22px;
        border: 1px solid var(--oos-border);
    }
    .filter-card .section-label {
        display: flex;
        align-items: center;
        gap: 6px;
        font-size: 11.5px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: .8px;
        color: var(--oos-primary);
        margin-bottom: 16px;
    }
    .filter-card label {
        font-size: 12px;
        font-weight: 700;
        color: #556;
        margin-bottom: 6px;
        display: block;
    }
    .filter-card .form-control {
        border-radius: 9px;
        border: 1.5px solid #e6e9f5;
        font-size: 13px;
        height: 40px;
        background: #fbfcfe;
        transition: border-color .2s, box-shadow .2s, background .2s;
    }
    .filter-card .form-control:focus {
        border-color: var(--oos-primary);
        box-shadow: 0 0 0 3px rgba(78,115,223,.14);
        background: #fff;
        outline: none;
    }
    .filter-divider {
        border: none; border-top: 1px dashed #e6e9f5;
        margin: 16px 0;
    }
    .oos-btn {
        display: inline-flex; align-items: center; justify-content: center; gap: 6px;
        border: none;
        border-radius: 9px; height: 40px;
        font-size: 13px; font-weight: 700;
        width: 100%;
        transition: opacity .2s, transform .12s, box-shadow .2s;
        cursor: pointer;
    }
    .oos-btn:active { transform: translateY(1px); }
    .btn-search {
        background: linear-gradient(135deg, var(--oos-primary), var(--oos-primary-dark));
        color: #fff;
        box-shadow: 0 4px 12px rgba(78,115,223,.28);
    }
    .btn-search:hover { opacity: .92; color: #fff; }

    .btn-reset {
        background: #fff;
        color: var(--oos-danger);
        border: 1.5px solid #f3c9ca;
        text-decoration: none;
    }
    .btn-reset:hover { background: #fff5f5; color: var(--oos-danger); }

    .btn-export {
        background: linear-gradient(135deg, var(--oos-success), var(--oos-success-dark));
        color: #fff;
        padding: 0 20px;
        box-shadow: 0 4px 12px rgba(28,200,138,.28);
        white-space: nowrap;
        width: auto;
    }
    .btn-export:hover { opacity: .92; color: #fff; }

    /* ── Summary cards ── */
    .summary-row { margin-bottom: 22px; }
    .stat-card {
        position: relative;
        overflow: hidden;
        border-radius: 16px;
        padding: 20px 22px;
        color: #fff;
        display: flex;
        align-items: center;
        gap: 16px;
        box-shadow: 0 8px 20px rgba(31,45,90,.14);
        margin-bottom: 16px;
        transition: transform .25s, box-shadow .25s;
    }
    .stat-card:hover { transform: translateY(-4px); box-shadow: 0 12px 26px rgba(31,45,90,.2); }
    .stat-card::after {
        content: '';
        position: absolute; right: -18px; top: -18px;
        width: 90px; height: 90px;
        border-radius: 50%;
        background: rgba(255,255,255,.08);
    }
    .stat-card.blue   { background: linear-gradient(135deg, #5a80e8, #2a4fc4); }
    .stat-card.red    { background: linear-gradient(135deg, #ee6a6e, #d63b40); }
    .stat-card.amber  { background: linear-gradient(135deg, #f7b955, #e0932a); }
    .stat-card.purple { background: linear-gradient(135deg, #a06de0, #7a3fc9); }
    .stat-card .stat-icon {
        width: 50px; height: 50px;
        background: rgba(255,255,255,.22);
        border-radius: 13px;
        display: flex; align-items: center; justify-content: center;
        font-size: 24px; flex-shrink: 0;
        position: relative; z-index: 1;
    }
    .stat-card .stat-body { position: relative; z-index: 1; }
    .stat-card .stat-body h6 { margin: 0 0 4px; font-size: 11.5px; font-weight: 700; opacity: .9; text-transform: uppercase; letter-spacing: .6px; color: #fff; }
    .stat-card .stat-body h3 { margin: 0; font-size: 27px; font-weight: 800; line-height: 1; letter-spacing: -.3px; color: #fff; }

    /* ── Table card ── */
    .table-card {
        background: #fff;
        border-radius: 16px;
        box-shadow: 0 2px 20px rgba(31,45,90,.06);
        overflow: hidden;
        margin-bottom: 30px;
        border: 1px solid var(--oos-border);
    }
    .table-card-header {
        padding: 16px 22px;
        background: linear-gradient(135deg, #f8f9fd, #eef1fb);
        border-bottom: 1px solid var(--oos-border);
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 8px;
    }
    .table-card-header h6 {
        margin: 0;
        display: flex; align-items: center; gap: 8px;
        font-size: 14px;
        font-weight: 800;
        color: var(--oos-ink);
    }
    .table-card-header .count-pill {
        font-size: 12px;
        font-weight: 700;
        color: var(--oos-primary);
        background: #eef1fd;
        padding: 4px 12px;
        border-radius: 20px;
    }
    .table-responsive { overflow-x: auto; -webkit-overflow-scrolling: touch; }
    .oos-table { width: 100%; margin: 0; border-collapse: separate; border-spacing: 0; }
    .oos-table thead tr th {
        background: #f8f9fd;
        color: #566;
        font-size: 10.5px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: .6px;
        padding: 13px 14px;
        border-bottom: 2px solid var(--oos-border);
        white-space: nowrap;
        text-align: center;
        position: sticky;
        top: 0;
        z-index: 2;
    }
    .oos-table tbody tr td {
        padding: 12px 14px;
        font-size: 13px;
        color: #445;
        border-bottom: 1px solid #f1f3fa;
        vertical-align: middle;
        text-align: center;
    }
    .oos-table tbody tr:nth-child(even) td { background: #fbfcff; }
    .oos-table tbody tr:last-child td { border-bottom: none; }
    .oos-table tbody tr:hover td { background: #eef2ff; }

    .badge-so {
        display: inline-flex; align-items: center; gap: 4px;
        background: #edf2ff;
        color: var(--oos-primary);
        font-size: 12px;
        font-weight: 700;
        padding: 5px 11px;
        border-radius: 20px;
        white-space: nowrap;
    }
    .badge-cat {
        background: #e3fbf3;
        color: #159b76;
        font-size: 11px;
        font-weight: 700;
        padding: 3px 10px;
        border-radius: 20px;
        white-space: nowrap;
    }
    .badge-sub {
        background: #fff4de;
        color: #b7791f;
        font-size: 11px;
        font-weight: 700;
        padding: 3px 10px;
        border-radius: 20px;
        white-space: nowrap;
    }
    .qty-cell { font-weight: 700; color: var(--oos-ink); }

    .need-pill {
        display: inline-flex; align-items: center; gap: 5px;
        font-size: 12.5px;
        font-weight: 800;
        padding: 5px 12px;
        border-radius: 20px;
        white-space: nowrap;
    }
    .need-pill.ok   { background: #e6f9f1; color: #17a673; }
    .need-pill.warn { background: #fff1e0; color: #c9760a; }
    .need-pill.bad  { background: #fdeaea; color: #d63b40; }

    .serial-no {
        color: #a7b0c3;
        font-size: 12px;
        font-weight: 700;
    }

    .empty-state {
        padding: 60px 20px;
        text-align: center;
        color: #a7b0c3;
    }
    .empty-state .empty-icon { font-size: 46px; margin-bottom: 14px; color: #cdd4e6; }
    .empty-state p { font-size: 15px; font-weight: 700; margin: 0; color: #7c8aa5; }

    /* ── Pagination wrapper ── */
    .pagination-wrap {
        padding: 14px 22px;
        border-top: 1px solid #f1f3fa;
        display: flex;
        justify-content: flex-end;
    }
    .pagination-wrap .pagination { margin: 0; }

    /* ── Mobile tweaks ── */
    @media (max-width: 767px) {
        .filter-card { padding: 18px 16px 14px; }
        .filter-card .col-md-2,
        .filter-card .col-md-3 { margin-bottom: 10px; }
        .stat-card h3 { font-size: 22px; }
        .table-card-header { flex-direction: column; align-items: flex-start; }
        .btn-export { width: 100%; }
        .oos-header h4 { font-size: 18px; }
    }
</style>

<div class="content-page">
    <div class="content">
        <div class="container-fluid oos-page">

            {{-- Page Header --}}
            <div class="oos-header">
                <div class="title-group">
                    <div class="header-icon"><i class="mdi mdi-package-variant-closed"></i></div>
                    <div>
                        <h4>Out Of Stock Report</h4>
                        <p>Sales orders whose items don't have enough stock to fulfil</p>
                    </div>
                </div>
            </div>

            {{ Form::model(request(), ['method' => 'get']) }}

            {{-- Filter Card --}}
            <div class="filter-card">
                <div class="section-label"><i class="mdi mdi-filter-variant"></i> Filter Options</div>

                <div class="row">
                    <div class="col-md-2 col-sm-6 col-xs-12" style="margin-bottom:12px;">
                        <label>SO No</label>
                        <input type="text" name="order_no" value="{{ request('order_no') }}"
                               class="form-control" placeholder="e.g. SO-001">
                    </div>

                    <div class="col-md-2 col-sm-6 col-xs-12" style="margin-bottom:12px;">
                        <label>Customer</label>
                        <input type="text" name="customer" value="{{ request('customer') }}"
                               class="form-control" placeholder="Customer name">
                    </div>

                    <div class="col-md-2 col-sm-6 col-xs-12" style="margin-bottom:12px;">
                        <label>Product</label>
                        <input type="text" name="product" value="{{ request('product') }}"
                               class="form-control" placeholder="Product name">
                    </div>

                    <div class="col-md-2 col-sm-6 col-xs-12" style="margin-bottom:12px;">
                        <label>Category</label>
                        <select name="category" id="category_filter" class="form-control">
                            <option value="">All Categories</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>
                                    {{ $cat->category_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-2 col-sm-6 col-xs-12" style="margin-bottom:12px;">
                        <label>Subcategory</label>
                        <select name="subcategory" id="subcategory_filter" class="form-control">
                            <option value="">All Subcategories</option>
                            @foreach($subcategories as $sc)
                                <option value="{{ $sc->id }}"
                                        data-category="{{ $sc->category }}"
                                        {{ request('subcategory') == $sc->id ? 'selected' : '' }}>
                                    {{ $sc->subcategory_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-1 col-sm-6 col-xs-12" style="margin-bottom:12px;">
                        <label>From Date</label>
                        <input type="date" name="from_date" value="{{ request('from_date') }}" class="form-control">
                    </div>

                    <div class="col-md-1 col-sm-6 col-xs-12" style="margin-bottom:12px;">
                        <label>To Date</label>
                        <input type="date" name="end_date" value="{{ request('end_date') }}" class="form-control">
                    </div>
                </div>

                <hr class="filter-divider">

                <div class="row">
                    <div class="col-md-2 col-sm-4 col-xs-6" style="margin-bottom:8px;">
                        <button type="submit" class="oos-btn btn-search">
                            <i class="mdi mdi-magnify"></i> Search
                        </button>
                    </div>
                    <div class="col-md-2 col-sm-4 col-xs-6" style="margin-bottom:8px;">
                        <a href="{{ url()->current() }}" class="oos-btn btn-reset">
                            <i class="mdi mdi-close-circle-outline"></i> Reset
                        </a>
                    </div>
                    <div class="col-md-8 col-sm-12 col-xs-12" style="margin-bottom:8px;text-align:right;">
                        <button type="submit" name="export_excel" value="export_excel" class="oos-btn btn-export">
                            <i class="mdi mdi-file-excel"></i> Export Excel
                        </button>
                    </div>
                </div>
            </div>

            {{-- Summary Cards --}}
            <div class="row summary-row">
                <div class="col-md-3 col-sm-6 col-xs-12">
                    <div class="stat-card blue">
                        <div class="stat-icon"><i class="mdi mdi-clipboard-text"></i></div>
                        <div class="stat-body">
                            <h6>Total Records</h6>
                            <h3>{{ number_format($list->total()) }}</h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6 col-xs-12">
                    <div class="stat-card purple">
                        <div class="stat-icon"><i class="mdi mdi-cube-outline"></i></div>
                        <div class="stat-body">
                            <h6>Products Affected</h6>
                            <h3>{{ number_format($summary->affected_products ?? 0) }}</h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6 col-xs-12">
                    <div class="stat-card amber">
                        <div class="stat-icon"><i class="mdi mdi-cart-outline"></i></div>
                        <div class="stat-body">
                            <h6>Sales Orders Affected</h6>
                            <h3>{{ number_format($summary->affected_orders ?? 0) }}</h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6 col-xs-12">
                    <div class="stat-card red">
                        <div class="stat-icon"><i class="mdi mdi-alert-circle-outline"></i></div>
                        <div class="stat-body">
                            <h6>This Page</h6>
                            <h3>{{ $list->count() }}</h3>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Table Card --}}
            <div class="table-card">
                <div class="table-card-header">
                    <h6><i class="mdi mdi-package-variant-closed"></i> Out Of Stock Items</h6>
                    <span class="count-pill">
                        Showing {{ $list->firstItem() ?? 0 }}–{{ $list->lastItem() ?? 0 }} of {{ number_format($list->total()) }}
                    </span>
                </div>

                <div class="table-responsive">
                    <table class="oos-table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>SO No</th>
                                <th>Date</th>
                                <th>Customer</th>
                                <th>Category</th>
                                <th>Subcategory</th>
                                <th>Product</th>
                                <th>Sold Qty</th>
                                <th>Stock Qty</th>
                                <th>Need To Purchase Qty</th>
                            </tr>
                        </thead>
                        <tbody>
                        @forelse($list as $data)
                            <tr>
                                <td>
                                    <span class="serial-no">
                                        {{ ($list->currentPage() - 1) * $list->perPage() + $loop->iteration }}
                                    </span>
                                </td>
                                <td>
                                    <a href="{{ route('admin.sales.view', $data->so_id) }}" class="badge-so" style="text-decoration:none;">
                                        <i class="mdi mdi-file-document"></i> {{ $data->order_no }}
                                    </a>
                                </td>
                                <td style="white-space:nowrap;">
                                    {{ \Carbon\Carbon::parse($data->order_date)->format('d M Y') }}
                                </td>
                                <td style="text-align:left;">{{ $data->customer }}</td>
                                <td>
                                    @if($data->category_name)
                                        <span class="badge-cat">{{ $data->category_name }}</span>
                                    @else
                                        <span style="color:#cbd5e0;">—</span>
                                    @endif
                                </td>
                                <td>
                                    @if($data->subcategory_name)
                                        <span class="badge-sub">{{ $data->subcategory_name }}</span>
                                    @else
                                        <span style="color:#cbd5e0;">—</span>
                                    @endif
                                </td>
                                <td style="text-align:left;">{{ $data->product }}</td>
                                <td class="qty-cell">{{ number_format($data->sold_qty, 2) }}</td>
                                <td class="qty-cell">{{ number_format($data->stock_qty, 2) }}</td>
                                <td>
                                    @php
                                        $need = (float) $data->need_to_purchase_qty;
                                        $pillClass = $need <= 0 ? 'ok' : ($need < $data->sold_qty ? 'warn' : 'bad');
                                        $pillIcon  = $need <= 0 ? 'mdi-check-circle-outline' : 'mdi-alert-outline';
                                    @endphp
                                    <span class="need-pill {{ $pillClass }}">
                                        <i class="mdi {{ $pillIcon }}"></i> {{ number_format($need, 2) }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10">
                                    <div class="empty-state">
                                        <div class="empty-icon"><i class="mdi mdi-check-circle-outline"></i></div>
                                        <p>No out of stock items found</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="pagination-wrap">
                    {{ $list->appends(request()->input())->links() }}
                </div>
            </div>

            {{ Form::close() }}

        </div>
    </div>
</div>

<script>
(function () {
    var categorySelect    = document.getElementById('category_filter');
    var subcategorySelect = document.getElementById('subcategory_filter');
    var allOptions        = Array.from(subcategorySelect.options);

    function filterSubcategories() {
        var selectedCategory = categorySelect.value;
        var currentSub       = subcategorySelect.value;

        while (subcategorySelect.options.length > 1) {
            subcategorySelect.remove(1);
        }

        allOptions.forEach(function (opt) {
            if (opt.value === '') return;
            if (!selectedCategory || opt.dataset.category === selectedCategory) {
                subcategorySelect.appendChild(opt.cloneNode(true));
            }
        });

        subcategorySelect.value = currentSub;
    }

    categorySelect.addEventListener('change', filterSubcategories);
    filterSubcategories();
    subcategorySelect.value = '{{ request('subcategory') }}';
})();
</script>

@endsection
