@extends('admin.layout.master')

@section('title', 'Report | Quotation')

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
        .rpt-badge { display: inline-block; padding: 3px 10px; border-radius: 12px; font-size: 11.5px; font-weight: 700; white-space: nowrap; }
        .rpt-badge-green  { background: #e6f9f1; color: #17a673; }
        .rpt-badge-red    { background: #fdeaea; color: #d63b40; }
        .rpt-badge-amber  { background: #fff1e0; color: #c9760a; }
        .rpt-badge-blue   { background: #edf2ff; color: #4e73df; }
        .rpt-badge-grey   { background: #eef0f3; color: #666; }
    </style>

    <div class="content-page">
        <div class="content">
            <div class="container">

                <div class="row">
                    <div class="col-xs-12">
                        <div class="page-title-box">
                            <h4 class="page-title">Quotation Report</h4>
                            <ol class="breadcrumb p-0 m-0">
                                <li><a href="{{ url('admin') }}">{{Session::get('software_title')}}</a></li>
                                <li>Reports</li>
                                <li class="active">Quotation</li>
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
                                        <label>Quot No</label>
                                        <input type="text" name="quot_no" value="{{ request('quot_no') }}" class="form-control" placeholder="Quotation no">
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
                                        <label>Stage</label>
                                        <select name="quot_stage" class="form-control">
                                            <option value="">All Stages</option>
                                            @foreach($stage as $key => $label)
                                                <option value="{{ $key }}" {{ request('quot_stage') == $key ? 'selected' : '' }}>{{ $label }}</option>
                                            @endforeach
                                        </select>
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

                            <div class="rpt-stats">
                                Quotations: <b>{{ number_format($totalRecords) }}</b>
                                &nbsp;&nbsp;|&nbsp;&nbsp; Total Amount: <b>{{ number_format($totalAmount, 2) }}</b>
                            </div>

                            <div class="table-responsive">
                                <table class="table table-striped rpt-table">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Quot No</th>
                                        <th>Quot Date</th>
                                        <th>Customer</th>
                                        <th>Salesman</th>
                                        <th>Subject</th>
                                        <th>Stage</th>
                                        <th>Amount</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @forelse($list as $data)
                                        <tr>
                                            <td style="width:2%;text-align:center;">
                                                {{ ($list->currentPage() - 1) * $list->perPage() + $loop->iteration }}
                                            </td>
                                            <td>{{ $data->quotation_no }}</td>
                                            <td style="white-space:nowrap;">{{ $data->quot_date ? date('d-m-Y', strtotime($data->quot_date)) : '-' }}</td>
                                            <td>{{ $data->customer_name ?? '-' }}</td>
                                            <td>{{ $data->salesman_name ?? '-' }}</td>
                                            <td>{{ $data->subject ?? '-' }}</td>
                                            <td>
                                                @php
                                                    $stageClass = match($data->quot_stage) {
                                                        'Accepted', 'Invoiced' => 'rpt-badge-green',
                                                        'Canceled' => 'rpt-badge-red',
                                                        'Sent', 'Reviewing', 'QuoteRivision' => 'rpt-badge-amber',
                                                        default => 'rpt-badge-grey',
                                                    };
                                                @endphp
                                                <span class="rpt-badge {{ $stageClass }}">{{ $data->quot_stage ?? '-' }}</span>
                                            </td>
                                            <td style="text-align:right;">{{ number_format($data->grand_total, 2) }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="8" class="text-center" style="padding:30px;color:#999;">No quotations found</td>
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
