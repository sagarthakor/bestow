@extends('admin.layout.master')

@section('title', 'Out Of Stock Report')

@section('content')

<style>
    /* ── Page wrapper ── */
    .oos-page { padding: 20px 10px; }

    /* ── Page header ── */
    .oos-header {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 22px;
    }
    .oos-header .header-icon {
        width: 46px; height: 46px;
        background: linear-gradient(135deg, #e74c3c, #c0392b);
        border-radius: 12px;
        display: flex; align-items: center; justify-content: center;
        color: #fff; font-size: 20px;
        box-shadow: 0 4px 12px rgba(231,76,60,.35);
        flex-shrink: 0;
    }
    .oos-header h4 { margin: 0; font-size: 20px; font-weight: 700; color: #2d3748; }
    .oos-header p  { margin: 0; font-size: 12px; color: #718096; }

    /* ── Filter card ── */
    .filter-card {
        background: #fff;
        border-radius: 14px;
        box-shadow: 0 2px 16px rgba(0,0,0,.08);
        padding: 20px 22px 16px;
        margin-bottom: 22px;
        border-top: 4px solid #4e73df;
    }
    .filter-card .section-label {
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .8px;
        color: #4e73df;
        margin-bottom: 14px;
    }
    .filter-card label {
        font-size: 12px;
        font-weight: 600;
        color: #4a5568;
        margin-bottom: 5px;
        display: block;
    }
    .filter-card .form-control {
        border-radius: 8px;
        border: 1px solid #e2e8f0;
        font-size: 13px;
        height: 38px;
        transition: border-color .2s, box-shadow .2s;
    }
    .filter-card .form-control:focus {
        border-color: #4e73df;
        box-shadow: 0 0 0 3px rgba(78,115,223,.15);
        outline: none;
    }
    .filter-divider {
        border: none; border-top: 1px solid #f0f0f0;
        margin: 14px 0;
    }
    .btn-search {
        background: linear-gradient(135deg, #4e73df, #224abe);
        color: #fff; border: none;
        border-radius: 8px; height: 38px;
        font-size: 13px; font-weight: 600;
        width: 100%;
        transition: opacity .2s, transform .1s;
    }
    .btn-search:hover { opacity: .9; transform: translateY(-1px); color: #fff; }

    .btn-reset {
        background: #fff;
        color: #e74c3c;
        border: 1.5px solid #e74c3c;
        border-radius: 8px; height: 38px;
        font-size: 13px; font-weight: 600;
        width: 100%;
        transition: background .2s, color .2s;
    }
    .btn-reset:hover { background: #e74c3c; color: #fff; }

    .btn-export {
        background: linear-gradient(135deg, #1cc88a, #17a673);
        color: #fff; border: none;
        border-radius: 8px;
        padding: 8px 18px;
        font-size: 13px; font-weight: 600;
        transition: opacity .2s, transform .1s;
        white-space: nowrap;
    }
    .btn-export:hover { opacity: .9; transform: translateY(-1px); color: #fff; }

    /* ── Summary cards ── */
    .summary-row { margin-bottom: 22px; }
    .stat-card {
        border-radius: 14px;
        padding: 18px 20px;
        color: #fff;
        display: flex;
        align-items: center;
        gap: 14px;
        box-shadow: 0 4px 16px rgba(0,0,0,.12);
        margin-bottom: 14px;
        transition: transform .2s;
    }
    .stat-card:hover { transform: translateY(-3px); }
    .stat-card.blue  { background: linear-gradient(135deg, #4e73df, #224abe); }
    .stat-card.red   { background: linear-gradient(135deg, #e74c3c, #c0392b); }
    .stat-card .stat-icon {
        width: 48px; height: 48px;
        background: rgba(255,255,255,.2);
        border-radius: 12px;
        display: flex; align-items: center; justify-content: center;
        font-size: 22px; flex-shrink: 0;
    }
    .stat-card .stat-body h6 { margin: 0 0 4px; font-size: 12px; font-weight: 600; opacity: .85; text-transform: uppercase; letter-spacing: .5px; }
    .stat-card .stat-body h3 { margin: 0; font-size: 28px; font-weight: 700; line-height: 1; }

    /* ── Table card ── */
    .table-card {
        background: #fff;
        border-radius: 14px;
        box-shadow: 0 2px 16px rgba(0,0,0,.08);
        overflow: hidden;
        margin-bottom: 30px;
    }
    .table-card-header {
        padding: 14px 20px;
        background: linear-gradient(135deg, #f8f9fc, #edf0fa);
        border-bottom: 1px solid #e3e6f0;
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 8px;
    }
    .table-card-header h6 {
        margin: 0;
        font-size: 14px;
        font-weight: 700;
        color: #4a5568;
    }
    .table-responsive { overflow-x: auto; -webkit-overflow-scrolling: touch; }
    .oos-table { width: 100%; margin: 0; border-collapse: separate; border-spacing: 0; }
    .oos-table thead tr th {
        background: #f8f9fc;
        color: #4a5568;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .6px;
        padding: 12px 14px;
        border-bottom: 2px solid #e3e6f0;
        white-space: nowrap;
        text-align: center;
    }
    .oos-table tbody tr td {
        padding: 11px 14px;
        font-size: 13px;
        color: #4a5568;
        border-bottom: 1px solid #f0f3f9;
        vertical-align: middle;
        text-align: center;
    }
    .oos-table tbody tr:last-child td { border-bottom: none; }
    .oos-table tbody tr:hover td { background: #f8f9ff; }

    .badge-so {
        background: #edf2ff;
        color: #4e73df;
        font-size: 12px;
        font-weight: 700;
        padding: 4px 10px;
        border-radius: 20px;
        white-space: nowrap;
    }
    .badge-cat {
        background: #e6fffa;
        color: #1a9e7e;
        font-size: 11px;
        font-weight: 600;
        padding: 3px 9px;
        border-radius: 20px;
        white-space: nowrap;
    }
    .badge-sub {
        background: #fff8e1;
        color: #b7791f;
        font-size: 11px;
        font-weight: 600;
        padding: 3px 9px;
        border-radius: 20px;
        white-space: nowrap;
    }
    .qty-cell { font-weight: 600; }
    .balance-neg { color: #e74c3c; font-weight: 700; }
    .balance-pos { color: #27ae60; font-weight: 700; }
    .serial-no {
        color: #a0aec0;
        font-size: 12px;
        font-weight: 600;
    }

    .empty-state {
        padding: 50px 20px;
        text-align: center;
        color: #a0aec0;
    }
    .empty-state .empty-icon { font-size: 48px; margin-bottom: 12px; }
    .empty-state p { font-size: 15px; font-weight: 600; margin: 0; }

    /* ── Pagination wrapper ── */
    .pagination-wrap {
        padding: 14px 20px;
        border-top: 1px solid #f0f3f9;
        display: flex;
        justify-content: flex-end;
    }
    .pagination-wrap .pagination { margin: 0; }

    /* ── Mobile tweaks ── */
    @media (max-width: 767px) {
        .filter-card { padding: 16px 14px 12px; }
        .filter-card .col-md-2,
        .filter-card .col-md-3 { margin-bottom: 10px; }
        .stat-card h3 { font-size: 22px; }
        .table-card-header { flex-direction: column; align-items: flex-start; }
        .btn-export { width: 100%; text-align: center; }
        .oos-header h4 { font-size: 17px; }
    }
</style>

<div class="content-page">
    <div class="content">
        <div class="container-fluid oos-page">

            {{-- Page Header --}}
            <div class="oos-header">
                <div class="header-icon">&#128230;</div>
                <div>
                    <h4>Out Of Stock Report</h4>
                    <p>Sales orders with items that have insufficient stock</p>
                </div>
            </div>

            {{ Form::model(request(), ['method' => 'get']) }}

            {{-- Filter Card --}}
            <div class="filter-card">
                <div class="section-label">&#128269; Filter Options</div>

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

                    <div class="col-md-2 col-sm-6 col-xs-12" style="margin-bottom:12px;">
                        <label>From Date</label>
                        <input type="date" name="from_date" value="{{ request('from_date') }}" class="form-control">
                    </div>

                    <div class="col-md-2 col-sm-6 col-xs-12" style="margin-bottom:12px;">
                        <label>To Date</label>
                        <input type="date" name="end_date" value="{{ request('end_date') }}" class="form-control">
                    </div>
                </div>

                <hr class="filter-divider">

                <div class="row">
                    <div class="col-md-2 col-sm-4 col-xs-6" style="margin-bottom:8px;">
                        <button type="submit" class="btn-search">
                            &#128269; Search
                        </button>
                    </div>
                    <div class="col-md-2 col-sm-4 col-xs-6" style="margin-bottom:8px;">
                        <a href="{{ url()->current() }}" class="btn-reset" style="display:flex;align-items:center;justify-content:center;text-decoration:none;">
                            &#10006; Reset
                        </a>
                    </div>
                    <div class="col-md-8 col-sm-12 col-xs-12" style="margin-bottom:8px;text-align:right;">
                        <button type="submit" name="export_excel" value="export_excel" class="btn-export">
                            &#11015; Export Excel
                        </button>
                    </div>
                </div>
            </div>

            {{-- Summary Cards --}}
            <div class="row summary-row">
                <div class="col-md-3 col-sm-6 col-xs-12">
                    <div class="stat-card blue">
                        <div class="stat-icon">&#128203;</div>
                        <div class="stat-body">
                            <h6>Total Records</h6>
                            <h3>{{ $list->total() }}</h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6 col-xs-12">
                    <div class="stat-card red">
                        <div class="stat-icon">&#128230;</div>
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
                    <h6>&#128203; Out Of Stock Items</h6>
                    <span style="font-size:12px;color:#718096;">
                        Showing {{ $list->firstItem() ?? 0 }}–{{ $list->lastItem() ?? 0 }}
                        of {{ $list->total() }} records
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
                                <th>Balance</th>
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
                                    <span class="badge-so">{{ $data->order_no }}</span>
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
                                <td class="{{ $data->balance <= 0 ? 'balance-neg' : 'balance-pos' }}">
                                    {{ number_format($data->balance, 2) }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10">
                                    <div class="empty-state">
                                        <div class="empty-icon">&#128230;</div>
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