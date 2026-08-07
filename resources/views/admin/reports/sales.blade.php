@extends('admin.layout.master_material')

@section('title', 'Report | Sales')

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
    </style>

    <div class="content-page">
        <div class="content">
            <div class="container">

                <div class="row">
                    <div class="col-xs-12">
                        <div class="page-title-box">
                            <h4 class="page-title">Sales Report</h4>
                            <ol class="breadcrumb p-0 m-0">
                                <li><a href="{{ url('admin') }}">{{Session::get('software_title')}}</a></li>
                                <li>Reports</li>
                                <li class="active">Sales</li>
                            </ol>
                            <div class="clearfix"></div>
                        </div>
                    </div>
                </div>

                @if(session()->has('message'))
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="alert alert-info" style="background-color: #188ae2 !important">
                                <strong style="color: #fff">{{session()->get('message')}}</strong>
                            </div>
                        </div>
                    </div>
                @endif

                {{ Form::model(request(), ['method' => 'get']) }}

                <div class="row">
                    <div class="col-sm-12">
                        <div class="panel panel-default">
                            <div class="rpt-panel-heading">Filter</div>
                            <div class="rpt-panel-body">
                                <div class="row">
                                    <div class="col-sm-3">
                                        <label>Sales No</label>
                                        <input type="text" name="salaesorder_no" value="{{ request('salaesorder_no') }}" class="form-control" placeholder="Sales order no">
                                    </div>
                                    <div class="col-sm-3">
                                        <label>Customer</label>
                                        <input type="text" name="client_name" value="{{ request('client_name') }}" class="form-control" placeholder="Customer name">
                                    </div>
                                    <div class="col-sm-3">
                                        <label>Salesman</label>
                                        <input type="text" name="salesman" value="{{ request('salesman') }}" class="form-control" placeholder="Salesman name">
                                    </div>
                                    <div class="col-sm-3">
                                        <label>Status</label>
                                        <input type="text" name="status" value="{{ request('status') }}" class="form-control" placeholder="Status">
                                    </div>
                                </div>
                                <div class="row" style="margin-top:12px;">
                                    <div class="col-sm-3">
                                        <label>Subject</label>
                                        <input type="text" name="subject" value="{{ request('subject') }}" class="form-control" placeholder="Subject">
                                    </div>
                                    <div class="col-sm-3">
                                        <label>Amount</label>
                                        <input type="text" name="amount" value="{{ request('amount') }}" class="form-control" placeholder="Amount">
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
                                ['label' => 'Sales Orders', 'value' => number_format($totalRecords), 'icon' => 'mdi-cart-outline', 'color' => 'blue'],
                                ['label' => 'Total Amount', 'value' => number_format($totalAmount, 2), 'icon' => 'mdi-cash-multiple', 'color' => 'green'],
                            ]])

                            <div class="table-responsive">
                                <table class="table table-striped rpt-table">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Sales No</th>
                                        <th>Sales Date</th>
                                        <th>Customer</th>
                                        <th>Salesman</th>
                                        <th>Subject</th>
                                        <th>Amount</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @forelse($list as $data)
                                        <tr>
                                            <td style="width:2%;text-align:center;">
                                                {{ ($list->currentPage() - 1) * $list->perPage() + $loop->iteration }}
                                            </td>
                                            <td>
                                                @can('sales_view')
                                                    {{ $data->quotation_no }}
                                                @endcan
                                            </td>
                                            <td style="white-space:nowrap;">{{ $data->quot_date ? date('d-m-Y', strtotime($data->quot_date)) : '-' }}</td>
                                            <td>{{ $data->customer_name ?? '-' }}</td>
                                            <td>{{ $data->salesman_name ?? '-' }}</td>
                                            <td>{{ $data->subject ?? '-' }}</td>
                                            <td style="text-align:right;">{{ number_format($data->grand_total, 2) }}</td>
                                        </tr>
                                        @include('admin.reports.partials.doc_items', ['items' => $items[$data->salaesorder_no] ?? null, 'colspan' => 7])
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center" style="padding:30px;color:#999;">No sales orders found</td>
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

@endsection
