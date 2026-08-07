@extends('admin.layout.master_material')

@section('title', 'Report | Salesman Wise Sales')

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
        table.rpt-table tr.rpt-salesman td { background: #dcecfb; font-weight: 700; color: #0f5a94; border-top: 2px solid #b9d9f5; }
        table.rpt-table tr.rpt-group td { background: #eaf4fd; font-weight: 600; color: #146bb3; }
        table.rpt-table tr.rpt-subtotal td { background: #eefcf5; font-weight: 600; color: #1c8a56; }
        table.rpt-table tr.rpt-grandtotal td { background: #188ae2; color: #fff; font-weight: 700; }
    </style>

    <div class="content-page">
        <div class="content">
            <div class="container">

                <div class="row">
                    <div class="col-xs-12">
                        <div class="page-title-box">
                            <h4 class="page-title">Sales-MAN Wise Sales Report</h4>
                            <ol class="breadcrumb p-0 m-0">
                                <li><a href="{{ url('admin') }}">{{Session::get('software_title')}}</a></li>
                                <li>Reports</li>
                                <li class="active">Salesman Wise Sales</li>
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
                                    <div class="col-sm-2">
                                        <label>View By</label>
                                        <select name="period" class="form-control">
                                            <option value="daily" {{ $period == 'daily' ? 'selected' : '' }}>Daily</option>
                                            <option value="monthly" {{ $period == 'monthly' ? 'selected' : '' }}>Monthly</option>
                                        </select>
                                    </div>
                                    <div class="col-sm-2">
                                        <label>From Date</label>
                                        <input type="date" name="from_date" value="{{ $from }}" class="form-control">
                                    </div>
                                    <div class="col-sm-2">
                                        <label>To Date</label>
                                        <input type="date" name="end_date" value="{{ $to }}" class="form-control">
                                    </div>
                                    <div class="col-sm-3">
                                        <label>Salesman</label>
                                        <select name="salesman" class="form-control">
                                            <option value="">All Salesmen</option>
                                            @foreach($salesmen as $sm)
                                                <option value="{{ $sm->salesman_name }}" {{ request('salesman') == $sm->salesman_name ? 'selected' : '' }}>{{ $sm->salesman_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-sm-3">
                                        <label>Customer</label>
                                        <input type="text" name="customer_name" value="{{ request('customer_name') }}" class="form-control" placeholder="Customer name">
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

                            @include('admin.reports.partials.stat_cards', [
                                'period' => ['from' => $from, 'to' => $to],
                                'stats' => [
                                    ['label' => 'Salesmen', 'value' => number_format($groups->count()), 'icon' => 'mdi-account-multiple', 'color' => 'blue'],
                                    ['label' => 'Total Amount', 'value' => number_format($grandTotal, 2), 'icon' => 'mdi-cash-multiple', 'color' => 'green'],
                                ],
                            ])

                            <div class="table-responsive">
                                <table class="table table-striped rpt-table">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>SO No</th>
                                        <th>Date</th>
                                        <th>Customer</th>
                                        <th>Status</th>
                                        <th>Grand Total</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @forelse($groups as $salesmanName => $periodGroups)
                                        <tr class="rpt-salesman">
                                            <td colspan="6">{{ $salesmanName }}</td>
                                        </tr>
                                        @php $salesmanTotal = 0; $salesmanCount = 0; @endphp
                                        @foreach($periodGroups as $periodLabel => $rows)
                                            <tr class="rpt-group">
                                                <td colspan="6">{{ $periodLabel }}</td>
                                            </tr>
                                            @foreach($rows as $row)
                                                <tr>
                                                    <td style="width:2%;text-align:center;">{{ $loop->iteration }}</td>
                                                    <td><a href="{{ route('admin.sales.view', $row->id) }}">{{ $row->salaesorder_no }}</a></td>
                                                    <td style="white-space:nowrap;">{{ \Carbon\Carbon::parse($row->salaesorder_date)->format('d-m-Y') }}</td>
                                                    <td>{{ $row->customer_name }}</td>
                                                    <td>{{ $row->status }}</td>
                                                    <td style="text-align:right;">{{ number_format($row->grand_total, 2) }}</td>
                                                </tr>
                                                @include('admin.reports.partials.doc_items', ['items' => $items[$row->salaesorder_no] ?? null, 'colspan' => 6])
                                            @endforeach
                                            <tr class="rpt-subtotal">
                                                <td colspan="5" class="text-right">Subtotal ({{ $rows->count() }} orders)</td>
                                                <td style="text-align:right;">{{ number_format($rows->sum('grand_total'), 2) }}</td>
                                            </tr>
                                            @php $salesmanTotal += $rows->sum('grand_total'); $salesmanCount += $rows->count(); @endphp
                                        @endforeach
                                        <tr class="rpt-grandtotal">
                                            <td colspan="5" class="text-right">{{ $salesmanName }} Total ({{ $salesmanCount }} orders)</td>
                                            <td style="text-align:right;">{{ number_format($salesmanTotal, 2) }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center" style="padding:30px;color:#999;">No sales found for the selected filters</td>
                                        </tr>
                                    @endforelse
                                    </tbody>
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
