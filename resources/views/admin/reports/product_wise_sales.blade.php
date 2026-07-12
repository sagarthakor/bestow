@extends('admin.layout.master')

@section('title', 'Report | Product Wise Sales')

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
        .rpt-stats { padding: 10px 16px; border-bottom: 1px solid #eceff5; background: #fafbfd; font-size: 13px; color: #666; }
        .rpt-stats b { color: #222; }
        table.rpt-table thead th { background: #f4f6fa; font-weight: 600; color: #444; border-bottom: 2px solid #e3e6ee; vertical-align: middle; }
        table.rpt-table tr.rpt-group td { background: #eaf4fd; font-weight: 600; color: #146bb3; border-top: 1px solid #d7e8fa; }
        table.rpt-table tr.rpt-subtotal td { background: #eefcf5; font-weight: 600; color: #1c8a56; }
        table.rpt-table tr.rpt-grandtotal td { background: #188ae2; color: #fff; font-weight: 700; }
        .rpt-tag { display: inline-block; font-size: 11px; padding: 1px 8px; border-radius: 3px; background: #eaf4fd; color: #146bb3; margin-left: 6px; }
    </style>

    <div class="content-page">
        <div class="content">
            <div class="container">

                <div class="row">
                    <div class="col-xs-12">
                        <div class="page-title-box">
                            <h4 class="page-title">Product Wise Sales Report</h4>
                            <ol class="breadcrumb p-0 m-0">
                                <li><a href="{{ url('admin') }}">{{Session::get('software_title')}}</a></li>
                                <li>Reports</li>
                                <li class="active">Product Wise Sales</li>
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
                                        <select name="category" class="form-control">
                                            <option value="">All Categories</option>
                                            @foreach($categories as $cat)
                                                <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>{{ $cat->category_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-sm-3">
                                        <label>From Date</label>
                                        <input type="date" name="from_date" value="{{ $from }}" class="form-control">
                                    </div>
                                    <div class="col-sm-3">
                                        <label>To Date</label>
                                        <input type="date" name="end_date" value="{{ $to }}" class="form-control">
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

                            <div class="rpt-stats">
                                Period: <b>{{ $from ?: 'All' }}</b> to <b>{{ $to ?: 'All' }}</b>
                                &nbsp;&nbsp;|&nbsp;&nbsp; Products: <b>{{ number_format($groups->count()) }}</b>
                                &nbsp;&nbsp;|&nbsp;&nbsp; Qty Sold: <b>{{ number_format($grandQty) }}</b>
                                &nbsp;&nbsp;|&nbsp;&nbsp; Amount: <b>{{ number_format($grandAmount, 2) }}</b>
                            </div>

                            <div class="table-responsive">
                                <table class="table table-striped rpt-table">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>SO No</th>
                                        <th>Date</th>
                                        <th>Customer</th>
                                        <th>Qty</th>
                                        <th>Price</th>
                                        <th>Amount</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @forelse($groups as $productName => $rows)
                                        <tr class="rpt-group">
                                            <td colspan="7">
                                                {{ $productName }}
                                                @if($rows->first()->category_name)
                                                    <span class="rpt-tag">{{ $rows->first()->category_name }}</span>
                                                @endif
                                            </td>
                                        </tr>
                                        @foreach($rows as $row)
                                            <tr>
                                                <td style="width:2%;text-align:center;">{{ $loop->iteration }}</td>
                                                <td><a href="{{ route('admin.sales.view', $row->so_id) }}">{{ $row->order_no }}</a></td>
                                                <td style="white-space:nowrap;">{{ \Carbon\Carbon::parse($row->order_date)->format('d-m-Y') }}</td>
                                                <td>{{ $row->customer }}</td>
                                                <td style="text-align:right;">{{ number_format($row->qty) }}</td>
                                                <td style="text-align:right;">{{ number_format($row->price, 2) }}</td>
                                                <td style="text-align:right;">{{ number_format($row->amount, 2) }}</td>
                                            </tr>
                                        @endforeach
                                        <tr class="rpt-subtotal">
                                            <td colspan="4" class="text-right">Subtotal</td>
                                            <td style="text-align:right;">{{ number_format($rows->sum('qty')) }}</td>
                                            <td></td>
                                            <td style="text-align:right;">{{ number_format($rows->sum('amount'), 2) }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center" style="padding:30px;color:#999;">No sales found for the selected filters</td>
                                        </tr>
                                    @endforelse
                                    </tbody>
                                    @if($groups->count())
                                        <tfoot>
                                        <tr class="rpt-grandtotal">
                                            <td colspan="4" class="text-right">Grand Total</td>
                                            <td style="text-align:right;">{{ number_format($grandQty) }}</td>
                                            <td></td>
                                            <td style="text-align:right;">{{ number_format($grandAmount, 2) }}</td>
                                        </tr>
                                        </tfoot>
                                    @endif
                                </table>
                            </div>

                        </div>
                    </div>
                </div>

                {{ Form::close() }}

            </div>
        </div>
    </div>

    <script src="{{asset('/admin/assets/js/jquery.min.js')}}"></script>

@endsection
