@extends('admin.layout.master_material')

@section('title', 'Report | Roll Material Consumption')

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
                            <h4 class="page-title">Roll Material Consumption Report</h4>
                            <ol class="breadcrumb p-0 m-0">
                                <li><a href="{{ url('admin') }}">{{Session::get('software_title')}}</a></li>
                                <li>Reports</li>
                                <li class="active">Roll Material Consumption</li>
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
                                        <label>Raw Material</label>
                                        <input type="text" name="material" value="{{ request('material') }}" class="form-control" placeholder="Dhaga / thread name">
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
                                    <div class="col-sm-3">
                                        <label>Status</label>
                                        <select name="status" class="form-control">
                                            <option value="">Completed &amp; pending</option>
                                            <option value="Y" {{ request('status') == 'Y' ? 'selected' : '' }}>Completed only</option>
                                            <option value="N" {{ request('status') == 'N' ? 'selected' : '' }}>Pending only</option>
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
                                ['label' => 'Materials', 'value' => number_format($rows->count()), 'icon' => 'mdi-format-list-bulleted', 'color' => 'blue'],
                                ['label' => 'Planned', 'value' => number_format($totalPlanned, 2), 'icon' => 'mdi-clipboard-text', 'color' => 'cyan'],
                                ['label' => 'Actual', 'value' => number_format($totalActual, 2), 'icon' => 'mdi-package-variant', 'color' => 'green'],
                                ['label' => 'Variance', 'value' => number_format($totalVariance, 2), 'icon' => 'mdi-alert', 'color' => $totalVariance > 0 ? 'red' : 'green'],
                            ]])

                            <p class="text-muted" style="padding:12px 16px 0;">
                                Grouped by niwar category, so a composite like Roto shows one row per thread &mdash;
                                the planned figures are the recipe, the actuals are what the floor reported at completion.
                                Cancelled batches are excluded. Quantities are in grams for KG-tracked dhaga.
                            </p>

                            <div class="table-responsive">
                                <table class="table table-striped rpt-table">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Category</th>
                                        <th>Raw Material</th>
                                        <th>Batches</th>
                                        <th>Planned</th>
                                        <th>Actual</th>
                                        <th>Variance</th>
                                        <th>Variance %</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @forelse($rows as $row)
                                        <tr>
                                            <td style="width:2%;text-align:center;">{{ $loop->iteration }}</td>
                                            <td>{{ $row->category_name ?? '-' }}</td>
                                            <td>{{ $row->material_name ?? ('Material #' . $row->material) }}</td>
                                            <td style="text-align:center;">{{ $row->batches }}</td>
                                            <td style="text-align:right;">{{ number_format($row->planned_qty, 2) }} {{ $row->unit }}</td>
                                            <td style="text-align:right;">{{ number_format($row->actual_qty, 2) }} {{ $row->unit }}</td>
                                            <td style="text-align:right;" class="{{ $row->variance > 0 ? 'rpt-over' : ($row->variance < 0 ? 'rpt-under' : '') }}">
                                                {{ $row->variance > 0 ? '+' : '' }}{{ number_format($row->variance, 2) }} {{ $row->unit }}
                                            </td>
                                            <td style="text-align:right;" class="{{ $row->variance > 0 ? 'rpt-over' : ($row->variance < 0 ? 'rpt-under' : '') }}">
                                                {{ $row->planned_qty > 0 ? ($row->variance > 0 ? '+' : '') . number_format($row->variance / $row->planned_qty * 100, 2) : '-' }}
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="8" class="text-center" style="padding:30px;color:#999;">No dhaga consumption recorded for this filter</td>
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

@endsection
