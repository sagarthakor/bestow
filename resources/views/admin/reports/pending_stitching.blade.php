@extends('admin.layout.master')

@section('title', 'Report | Stitching Pending')

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
    </style>

    <div class="content-page">
        <div class="content">
            <div class="container">

                <div class="row">
                    <div class="col-xs-12">
                        <div class="page-title-box">
                            <h4 class="page-title">Stitching Pending Report</h4>
                            <ol class="breadcrumb p-0 m-0">
                                <li><a href="{{ url('admin') }}">{{Session::get('software_title')}}</a></li>
                                <li>Reports</li>
                                <li class="active">Stitching Pending</li>
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
                                    <div class="col-sm-4">
                                        <label>Batch No</label>
                                        <input type="text" name="batch_no" value="{{ request('batch_no') }}" class="form-control" placeholder="Batch no">
                                    </div>
                                    <div class="col-sm-4">
                                        <label>Customer</label>
                                        <input type="text" name="customer" value="{{ request('customer') }}" class="form-control" placeholder="Customer name">
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
                                Pending Batches: <b>{{ number_format($list->total()) }}</b>
                            </div>

                            <div class="table-responsive">
                                <table class="table table-striped rpt-table">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Batch No</th>
                                        <th>Product</th>
                                        <th>Customer</th>
                                        <th>Machine</th>
                                        <th>Nos</th>
                                        <th>Size</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @forelse($list as $data)
                                        <tr>
                                            <td style="width:2%;text-align:center;">
                                                {{ ($list->currentPage() - 1) * $list->perPage() + $loop->iteration }}
                                            </td>
                                            <td>{{ $data->batch_no }}</td>
                                            <td>{{ $data->product_name ?? '-' }}</td>
                                            <td>{{ $data->customer_name ?? '-' }}</td>
                                            <td>{{ $data->machine_name ?? '-' }}</td>
                                            <td style="text-align:right;">{{ $data->nos }}</td>
                                            <td>{{ $data->size }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center" style="padding:30px;color:#999;">No pending stitching batches found</td>
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
