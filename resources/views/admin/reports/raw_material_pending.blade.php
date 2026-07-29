@extends('admin.layout.master_material')

@section('title', 'Report | Raw Material Pending')

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
        table.rpt-table tr.rpt-group td { background: #fdf1ea; font-weight: 600; color: #b3560f; border-top: 1px solid #fadfc9; }
        table.rpt-table tr.rpt-subtotal td { background: #fdeeee; font-weight: 600; color: #c0392b; }
        table.rpt-table tr.rpt-grandtotal td { background: #c0392b; color: #fff; font-weight: 700; }
        .rpt-need { color: #c0392b; font-weight: 600; }
    </style>

    <div class="content-page">
        <div class="content">
            <div class="container">

                <div class="row">
                    <div class="col-xs-12">
                        <div class="page-title-box">
                            <h4 class="page-title">RAW Material Required Pending Report</h4>
                            <ol class="breadcrumb p-0 m-0">
                                <li><a href="{{ url('admin') }}">{{Session::get('software_title')}}</a></li>
                                <li>Reports</li>
                                <li class="active">Raw Material Pending</li>
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
                                        <label>Raw Material</label>
                                        <input type="text" name="raw_material" value="{{ request('raw_material') }}" class="form-control" placeholder="Raw material name">
                                    </div>
                                    <div class="col-sm-4">
                                        <label>Customer</label>
                                        <input type="text" name="customer" value="{{ request('customer') }}" class="form-control" placeholder="Customer name">
                                    </div>
                                    <div class="col-sm-4">
                                        <label>Batch No</label>
                                        <input type="text" name="batch_no" value="{{ request('batch_no') }}" class="form-control" placeholder="Batch no">
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
                                ['label' => 'Raw Materials Pending', 'value' => number_format($groups->count()), 'icon' => 'mdi-format-list-bulleted', 'color' => 'blue'],
                                ['label' => 'Total Qty To Order', 'value' => number_format($grandNeed, 2), 'icon' => 'mdi-alert', 'color' => 'orange'],
                            ]])

                            <div class="table-responsive">
                                <table class="table table-striped rpt-table">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Batch No</th>
                                        <th>Finish Product</th>
                                        <th>Customer</th>
                                        <th>Required Qty</th>
                                        <th>Available Stock</th>
                                        <th>Need To Order</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @forelse($groups as $materialName => $rows)
                                        <tr class="rpt-group">
                                            <td colspan="7">{{ $materialName }} ({{ $rows->first()->uom ?? '-' }})</td>
                                        </tr>
                                        @foreach($rows as $row)
                                            <tr>
                                                <td style="width:2%;text-align:center;">{{ $loop->iteration }}</td>
                                                <td>
                                                    {{ $row->batch_no }}
                                                    @if($row->source === 'purchase_request')
                                                        <span class="label label-warning" title="Raised before a production batch was created; not yet converted to a PO">Pending PO</span>
                                                    @endif
                                                </td>
                                                <td>{{ $row->finish_product ?? '-' }}</td>
                                                <td>{{ $row->customer ?? '-' }}</td>
                                                <td style="text-align:right;">{{ $row->source === 'purchase_request' ? '-' : number_format($row->required_qty, 2) }}</td>
                                                <td style="text-align:right;">{{ $row->source === 'purchase_request' ? '-' : number_format($row->avalible_stock, 2) }}</td>
                                                <td style="text-align:right;"><span class="rpt-need">{{ number_format($row->need_to_order_stock, 2) }}</span></td>
                                            </tr>
                                        @endforeach
                                        <tr class="rpt-subtotal">
                                            <td colspan="6" class="text-right">Subtotal Need To Order</td>
                                            <td style="text-align:right;">{{ number_format($rows->sum('need_to_order_stock'), 2) }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center" style="padding:30px;color:#999;">No pending raw material requirements found</td>
                                        </tr>
                                    @endforelse
                                    </tbody>
                                    @if($groups->count())
                                        <tfoot>
                                        <tr class="rpt-grandtotal">
                                            <td colspan="6" class="text-right">Grand Total Need To Order</td>
                                            <td style="text-align:right;">{{ number_format($grandNeed, 2) }}</td>
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
