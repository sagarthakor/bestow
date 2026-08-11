@extends('admin.layout.master_material')

@section('title', 'Report | Roll Production')

@section('sidebar')
    @parent
@endsection

@section('content')

    @include('admin.reports.partials.rpt_styles')

    <div class="content-page">
        <div class="content">
            <div class="container">

                <div class="row">
                    <div class="col-xs-12">
                        <div class="page-title-box">
                            <h4 class="page-title">Roll Production Report</h4>
                            <ol class="breadcrumb p-0 m-0">
                                <li><a href="{{ url('admin') }}">{{Session::get('software_title')}}</a></li>
                                <li>Reports</li>
                                <li class="active">Roll Production</li>
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
                                        <label>Semi Product</label>
                                        <input type="text" name="product" value="{{ request('product') }}" class="form-control" placeholder="Roll product name">
                                    </div>
                                    <div class="col-sm-3">
                                        <label>Niwar Code</label>
                                        <select name="niwar_code_id" class="form-control">
                                            <option value="">All</option>
                                            @foreach($niwarCodes as $n)
                                                <option value="{{ $n->id }}" {{ request('niwar_code_id') == $n->id ? 'selected' : '' }}>{{ trim($n->type . '/' . $n->code) }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-sm-3">
                                        <label>Status</label>
                                        <select name="status" class="form-control">
                                            <option value="">All</option>
                                            <option value="N" {{ request('status') == 'N' ? 'selected' : '' }}>Pending</option>
                                            <option value="Y" {{ request('status') == 'Y' ? 'selected' : '' }}>Completed</option>
                                            <option value="C" {{ request('status') == 'C' ? 'selected' : '' }}>Cancelled</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="row" style="margin-top:10px;">
                                    <div class="col-sm-3">
                                        <label>From Date</label>
                                        <input type="date" name="from_date" value="{{ request('from_date') }}" class="form-control">
                                    </div>
                                    <div class="col-sm-3">
                                        <label>To Date</label>
                                        <input type="date" name="to_date" value="{{ request('to_date') }}" class="form-control">
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
                                ['label' => 'Batches', 'value' => number_format($totalBatches), 'icon' => 'mdi-format-list-bulleted', 'color' => 'blue'],
                                ['label' => 'Pending Batches', 'value' => number_format($pendingBatches), 'icon' => 'mdi-clock-alert', 'color' => 'orange'],
                                ['label' => 'Cancelled Batches', 'value' => number_format($cancelledBatches), 'icon' => 'mdi-close-circle', 'color' => 'red'],
                                ['label' => 'Planned Mtr', 'value' => number_format($totalPlanned, 2), 'icon' => 'mdi-ruler', 'color' => 'cyan'],
                                ['label' => 'Produced Mtr', 'value' => number_format($totalProduced, 2), 'icon' => 'mdi-package-variant', 'color' => 'green'],
                                ['label' => 'Wastage Mtr', 'value' => number_format($totalWastage, 2), 'icon' => 'mdi-alert', 'color' => 'red'],
                                ['label' => 'Efficiency %', 'value' => number_format($efficiency, 2), 'icon' => 'mdi-speedometer', 'color' => 'purple'],
                            ]])

                            <div class="table-responsive">
                                <table class="table table-striped rpt-table">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Date</th>
                                        <th>Batch No</th>
                                        <th>Semi Product</th>
                                        <th>Niwar Code</th>
                                        <th>Formula</th>
                                        <th>Customer</th>
                                        <th>Roll Plan</th>
                                        <th>Planned Mtr</th>
                                        <th>Produced Mtr</th>
                                        <th>Wastage Mtr</th>
                                        <th>Eff. %</th>
                                        <th>Rolls</th>
                                        <th>Status</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @forelse($list as $data)
                                        <tr>
                                            <td style="width:2%;text-align:center;">
                                                {{ ($list->currentPage() - 1) * $list->perPage() + $loop->iteration }}
                                            </td>
                                            <td>{{ $data->created_at ? date('d-m-Y', strtotime($data->created_at)) : '-' }}</td>
                                            <td>{{ $data->batch_no }}</td>
                                            <td>{{ $data->product ?? '-' }}</td>
                                            <td>{{ trim(($data->niwar_type ?? '') . '/' . ($data->niwar_code ?? '')) }}</td>
                                            <td>{{ $data->roll_formula_version ? 'V' . $data->roll_formula_version : '-' }}</td>
                                            <td>{{ $data->customer ?? '-' }}</td>
                                            <td style="text-align:right;">{{ $data->no_of_rolls }} &times; {{ $data->roll_length_mtr }}</td>
                                            <td style="text-align:right;">{{ number_format($data->planned_mtr, 2) }}</td>
                                            <td style="text-align:right;">{{ number_format($data->produced_mtr ?? 0, 2) }}</td>
                                            <td style="text-align:right;">{{ number_format($data->wastage_mtr ?? 0, 2) }}</td>
                                            <td style="text-align:right;">
                                                {{ $data->status == 'Y' && $data->planned_mtr > 0 ? number_format($data->produced_mtr / $data->planned_mtr * 100, 2) : '-' }}
                                            </td>
                                            <td style="text-align:center;">{{ $data->rolls_made }}</td>
                                            <td>
                                                @if($data->status == 'Y')
                                                    <span class="rpt-status-completed">Completed</span>
                                                @elseif($data->status == 'C')
                                                    <span class="rpt-status-pending">Cancelled</span>
                                                @else
                                                    <span class="rpt-status-pending">Pending</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="14" class="text-center" style="padding:30px;color:#999;">No roll production batches found</td>
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
