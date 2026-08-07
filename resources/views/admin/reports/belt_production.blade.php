@extends('admin.layout.master_material')

@section('title', 'Report | Belt Production')

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
        .rpt-status-completed { color: #1c8a56; font-weight: 600; }
        .rpt-status-pending { color: #c0392b; font-weight: 600; }
    </style>

    <div class="content-page">
        <div class="content">
            <div class="container">

                <div class="row">
                    <div class="col-xs-12">
                        <div class="page-title-box">
                            <h4 class="page-title">Belt Production Report</h4>
                            <ol class="breadcrumb p-0 m-0">
                                <li><a href="{{ url('admin') }}">{{Session::get('software_title')}}</a></li>
                                <li>Reports</li>
                                <li class="active">Belt Production</li>
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
                                        <label>Batch No</label>
                                        <input type="text" name="batch_no" value="{{ request('batch_no') }}" class="form-control" placeholder="Batch no">
                                    </div>
                                    <div class="col-sm-3">
                                        <label>Product</label>
                                        <input type="text" name="product" value="{{ request('product') }}" class="form-control" placeholder="Product name">
                                    </div>
                                    <div class="col-sm-3">
                                        <label>Customer</label>
                                        <input type="text" name="customer" value="{{ request('customer') }}" class="form-control" placeholder="Customer name">
                                    </div>
                                    <div class="col-sm-3">
                                        <label>Status</label>
                                        <select name="status" class="form-control">
                                            <option value="">All</option>
                                            <option value="N" {{ request('status') == 'N' ? 'selected' : '' }}>Pending</option>
                                            <option value="Y" {{ request('status') == 'Y' ? 'selected' : '' }}>Completed</option>
                                        </select>
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
                                ['label' => 'Batches', 'value' => number_format($pendingBatches + $completedBatches), 'icon' => 'mdi-format-list-bulleted', 'color' => 'blue'],
                                ['label' => 'Pending Batches', 'value' => number_format($pendingBatches), 'icon' => 'mdi-clock-alert', 'color' => 'orange'],
                                ['label' => 'Completed Batches', 'value' => number_format($completedBatches), 'icon' => 'mdi-check-circle', 'color' => 'green'],
                                ['label' => 'Total Planned', 'value' => number_format($totalPlanned, 2), 'icon' => 'mdi-package-variant', 'color' => 'cyan'],
                                ['label' => 'Total Produced', 'value' => number_format($totalProduced, 2), 'icon' => 'mdi-package-variant', 'color' => 'green'],
                                ['label' => 'Total Wastage', 'value' => number_format($totalWastage, 2), 'icon' => 'mdi-alert', 'color' => 'red'],
                                ['label' => 'Total Pending Qty', 'value' => number_format($totalPending, 2), 'icon' => 'mdi-package-variant', 'color' => 'purple'],
                            ]])

                            <div class="table-responsive">
                                <table class="table table-striped rpt-table">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Batch No</th>
                                        <th>Product</th>
                                        <th>Customer</th>
                                        <th>Planned Qty</th>
                                        <th>Produced Qty</th>
                                        <th>Wastage Qty</th>
                                        <th>Pending Qty</th>
                                        <th>Status</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @forelse($list as $data)
                                        <tr>
                                            <td style="width:2%;text-align:center;">
                                                {{ ($list->currentPage() - 1) * $list->perPage() + $loop->iteration }}
                                            </td>
                                            <td>{{ $data->batch_no }}</td>
                                            <td><x-product-name :name="$data->product" :color="$data->value1 ?? null" :size="$data->value2 ?? null" /></td>
                                            <td>{{ $data->customer ?? '-' }}</td>
                                            <td style="text-align:right;">{{ number_format($data->planned_qty, 2) }}</td>
                                            <td style="text-align:right;">{{ number_format($data->total_production ?? 0, 2) }}</td>
                                            <td style="text-align:right;">{{ number_format($data->total_wastage_nos ?? 0, 2) }}</td>
                                            <td style="text-align:right;">{{ number_format($data->pending_qty, 2) }}</td>
                                            <td>
                                                @if($data->status == 'Y')
                                                    <span class="rpt-status-completed">Completed</span>
                                                @else
                                                    <span class="rpt-status-pending">Pending</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="9" class="text-center" style="padding:30px;color:#999;">No belt production batches found</td>
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

@endsection
