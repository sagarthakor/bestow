@extends('admin.layout.master_material')

@section('title', 'Report | Out Of Stock')

@section('sidebar')
    @parent
@endsection

@section('content')

    <style type="text/css">
        nav { float: right; }
        .rpt-panel-heading {
            background: #188ae2;
            color: #fff;
            font-weight: 600;
            font-size: 14px;
            padding: 10px 16px;
            border-radius: 3px 3px 0 0;
        }
        .rpt-panel-body { padding: 18px 16px 6px; }
        .rpt-panel-body label { font-weight: 600; color: #555; font-size: 12.5px; margin-bottom: 4px; }
        .rpt-actions { padding: 0 16px 16px; text-align: right; border-top: 1px solid #eceff5; margin-top: 12px; padding-top: 14px; }
        table.rpt-table thead th { background: #f4f6fa; font-weight: 600; color: #444; border-bottom: 2px solid #e3e6ee; vertical-align: middle; }
        table.rpt-table { border: 1px solid #e3e6ee; border-collapse: collapse; box-shadow: 0 1px 3px rgba(20,30,60,.04); }
        table.rpt-table th, table.rpt-table td { border: 1px solid #eceff5; padding: 10px 12px; vertical-align: middle; }
        table.rpt-table tbody tr:hover { background-color: #f5f8fc; }
        .rpt-badge { display: inline-block; padding: 3px 10px; border-radius: 12px; font-size: 11.5px; font-weight: 700; white-space: nowrap; }
        .rpt-badge-green  { background: #e6f9f1; color: #17a673; }
        .rpt-badge-red    { background: #fdeaea; color: #d63b40; }
        .rpt-badge-amber  { background: #fff1e0; color: #c9760a; }
    </style>

    <div class="content-page">
        <div class="content">
            <div class="container">

                <div class="row">
                    <div class="col-xs-12">
                        <div class="page-title-box">
                            <h4 class="page-title">Out Of Stock Report</h4>
                            <ol class="breadcrumb p-0 m-0">
                                <li><a href="{{ url('admin') }}">{{Session::get('software_title')}}</a></li>
                                <li>Reports</li>
                                <li class="active">Sales Out of Stock Items</li>
                            </ol>
                            <div class="clearfix"></div>
                        </div>
                    </div>
                </div>

                {{ Form::model(request(), ['method' => 'get']) }}

                <div class="row">
                    <div class="col-sm-12">
                        <div class="panel panel-default">
                            <div class="rpt-panel-heading">Filter</div>
                            <div class="rpt-panel-body">
                                <div class="row">
                                    <div class="col-sm-3">
                                        <label>SO No</label>
                                        <input type="text" name="order_no" value="{{ request('order_no') }}" class="form-control" placeholder="e.g. SO-001">
                                    </div>
                                    <div class="col-sm-3">
                                        <label>Customer</label>
                                        <input type="text" name="customer" value="{{ request('customer') }}" class="form-control" placeholder="Customer name">
                                    </div>
                                    <div class="col-sm-3">
                                        <label>Product</label>
                                        <input type="text" name="product" value="{{ request('product') }}" class="form-control" placeholder="Product name">
                                    </div>
                                    <div class="col-sm-3">
                                        <label>Category</label>
                                        <select name="category" id="category_filter" class="form-control">
                                            <option value="">All Categories</option>
                                            @foreach($categories as $cat)
                                                <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>{{ $cat->category_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="row" style="margin-top:12px;">
                                    <div class="col-sm-3">
                                        <label>Subcategory</label>
                                        <select name="subcategory" id="subcategory_filter" class="form-control">
                                            <option value="">All Subcategories</option>
                                            @foreach($subcategories as $sc)
                                                <option value="{{ $sc->id }}" data-category="{{ $sc->category }}" {{ request('subcategory') == $sc->id ? 'selected' : '' }}>{{ $sc->subcategory_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-sm-3">
                                        <label>From Date</label>
                                        <input type="date" name="from_date" value="{{ request('from_date') }}" class="form-control">
                                    </div>
                                    <div class="col-sm-3">
                                        <label>To Date</label>
                                        <input type="date" name="end_date" value="{{ request('end_date') }}" class="form-control">
                                    </div>
                                </div>
                            </div>
                            <div class="rpt-actions">
                                <a href="{{ url()->current() }}" class="btn btn-default">Reset</a>
                                <button type="submit" class="btn btn-primary">Search</button>
                                <button type="submit" name="export_excel" value="export_excel" class="btn btn-success">Export Excel</button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-12">
                        <div class="card-box" style="padding:0;">

                            @include('admin.reports.partials.stat_cards', ['stats' => [
                                ['label' => 'Records', 'value' => number_format($list->total()), 'icon' => 'mdi-format-list-bulleted', 'color' => 'blue'],
                                ['label' => 'Products Affected', 'value' => number_format($summary->affected_products ?? 0), 'icon' => 'mdi-alert-circle', 'color' => 'orange'],
                                ['label' => 'Sales Orders Affected', 'value' => number_format($summary->affected_orders ?? 0), 'icon' => 'mdi-alert-circle', 'color' => 'orange'],
                                ['label' => 'Total Sold Qty', 'value' => number_format($summary->total_sold_qty ?? 0, 2), 'icon' => 'mdi-package-variant', 'color' => 'purple'],
                            ]])

                            <div class="table-responsive">
                                <table class="table table-striped rpt-table">
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
                                            <td style="width:2%;text-align:center;">
                                                {{ ($list->currentPage() - 1) * $list->perPage() + $loop->iteration }}
                                            </td>
                                            <td><a href="{{ route('admin.sales.view', $data->so_id) }}">{{ $data->order_no }}</a></td>
                                            <td style="white-space:nowrap;">{{ \Carbon\Carbon::parse($data->order_date)->format('d-m-Y') }}</td>
                                            <td>{{ $data->customer }}</td>
                                            <td>{{ $data->category_name ?? '-' }}</td>
                                            <td>{{ $data->subcategory_name ?? '-' }}</td>
                                            <td><x-product-name :name="$data->product" :color="$data->value1 ?? null" :size="$data->value2 ?? null" /></td>
                                            <td style="text-align:right;">{{ number_format($data->sold_qty, 2) }}</td>
                                            <td style="text-align:right;">{{ number_format($data->stock_qty, 2) }}</td>
                                            <td style="text-align:right;">
                                                @php
                                                    $need = (float) $data->need_to_purchase_qty;
                                                    $needClass = $need <= 0 ? 'rpt-badge-green' : ($need < $data->sold_qty ? 'rpt-badge-amber' : 'rpt-badge-red');
                                                @endphp
                                                <span class="rpt-badge {{ $needClass }}">{{ number_format($need, 2) }}</span>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="10" class="text-center" style="padding:30px;color:#999;">No out of stock items found</td>
                                        </tr>
                                    @endforelse
                                    </tbody>
                                </table>
                            </div>

                            <div style="padding: 10px 16px;">
                                {{ $list->appends(request()->input())->links() }}
                            </div>

                        </div>
                    </div>
                </div>

                {{ Form::close() }}

            </div>
        </div>
    </div>

    <script src="{{asset('/admin/assets/js/jquery.min.js')}}"></script>
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
