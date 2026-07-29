@extends('admin.layout.master_material')

@section('title', 'Report | Product Wise Available Stock')

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
        .rpt-stock-ok { color: #1c8a56; font-weight: 600; }
        .rpt-stock-zero { color: #c0392b; font-weight: 600; }
    </style>

    <div class="content-page">
        <div class="content">
            <div class="container">

                <div class="row">
                    <div class="col-xs-12">
                        <div class="page-title-box">
                            <h4 class="page-title">Product Wise Available Stock Report</h4>
                            <ol class="breadcrumb p-0 m-0">
                                <li><a href="{{ url('admin') }}">{{Session::get('software_title')}}</a></li>
                                <li>Reports</li>
                                <li class="active">Available Stock</li>
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
                                        <label>&nbsp;</label>
                                        <div class="checkbox" style="margin-top:8px;">
                                            <label style="font-weight:normal;">
                                                <input type="checkbox" name="only_available" value="1" {{ request('only_available') ? 'checked' : '' }}>
                                                Only show stock &gt; 0
                                            </label>
                                        </div>
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
                                ['label' => 'Products', 'value' => number_format($totalProducts), 'icon' => 'mdi-shopping', 'color' => 'blue'],
                                ['label' => 'Total Stock Qty', 'value' => number_format($totalStockQty, 2), 'icon' => 'mdi-package-variant', 'color' => 'cyan'],
                            ]])

                            <div class="table-responsive">
                                <table class="table table-striped rpt-table">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Product</th>
                                        <th>Item Code</th>
                                        <th>UOM</th>
                                        <th>Category</th>
                                        <th>Subcategory</th>
                                        <th>Available Stock</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @forelse($list as $data)
                                        <tr>
                                            <td style="width:2%;text-align:center;">
                                                {{ ($list->currentPage() - 1) * $list->perPage() + $loop->iteration }}
                                            </td>
                                            <td>{{ $data->product }}</td>
                                            <td>{{ $data->item_code ?? '-' }}</td>
                                            <td>{{ $data->uom ?? '-' }}</td>
                                            <td>{{ $data->category_name ?? '-' }}</td>
                                            <td>{{ $data->subcategory_name ?? '-' }}</td>
                                            <td style="text-align:right;">
                                                @php $qty = (float) $data->stock_qty; @endphp
                                                <span class="{{ $qty > 0 ? 'rpt-stock-ok' : 'rpt-stock-zero' }}">{{ number_format($qty, 2) }}</span>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center" style="padding:30px;color:#999;">No products found</td>
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
