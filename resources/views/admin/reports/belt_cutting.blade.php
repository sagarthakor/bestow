@extends('admin.layout.master_material')

@section('title', 'Report | Belt Cutting')

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
                            <h4 class="page-title">Belt Cutting Report</h4>
                            <ol class="breadcrumb p-0 m-0">
                                <li><a href="{{ url('admin') }}">{{Session::get('software_title')}}</a></li>
                                <li>Reports</li>
                                <li class="active">Belt Cutting</li>
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
                                        <label>Cutting No</label>
                                        <input type="text" name="cutting_no" value="{{ request('cutting_no') }}" class="form-control" placeholder="Cutting no">
                                    </div>
                                    <div class="col-sm-3">
                                        <label>Roll No</label>
                                        <input type="text" name="roll_no" value="{{ request('roll_no') }}" class="form-control" placeholder="Roll no">
                                    </div>
                                    <div class="col-sm-3">
                                        <label>Belt Product</label>
                                        <input type="text" name="product" value="{{ request('product') }}" class="form-control" placeholder="Belt product name">
                                    </div>
                                    <div class="col-sm-3">
                                        <label>Status</label>
                                        <select name="status" class="form-control">
                                            <option value="">All</option>
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
                                ['label' => 'Cutting Entries', 'value' => number_format($totalCuttings), 'icon' => 'mdi-content-cut', 'color' => 'blue'],
                                ['label' => 'Pieces Cut', 'value' => number_format($totalPieces), 'icon' => 'mdi-package-variant', 'color' => 'cyan'],
                                ['label' => 'Good Pieces', 'value' => number_format($totalPieces - $totalRejected), 'icon' => 'mdi-check-circle', 'color' => 'green'],
                                ['label' => 'Rejected', 'value' => number_format($totalRejected), 'icon' => 'mdi-close-circle', 'color' => 'red'],
                                ['label' => 'Meters Consumed', 'value' => number_format($totalMeters, 2), 'icon' => 'mdi-ruler', 'color' => 'purple'],
                                ['label' => 'Trim Wastage Mtr', 'value' => number_format($totalWastage, 2), 'icon' => 'mdi-alert', 'color' => 'orange'],
                            ]])

                            <div class="table-responsive">
                                <table class="table table-striped rpt-table">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Date</th>
                                        <th>Cutting No</th>
                                        <th>Roll No</th>
                                        <th>Batch No</th>
                                        <th>Belt</th>
                                        <th>Size</th>
                                        <th>Cut</th>
                                        <th>Rejected</th>
                                        <th>Good</th>
                                        <th>Mtr/Pc</th>
                                        <th>Consumed Mtr</th>
                                        <th>Trim Mtr</th>
                                        <th>Roll Balance</th>
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
                                            <td>{{ $data->cutting_no }}</td>
                                            <td>{{ $data->roll_no ?? '-' }}</td>
                                            <td>{{ $data->batch_no ?? '-' }}</td>
                                            <td><x-product-name :name="$data->product" :color="$data->value1 ?? null" :size="$data->value2 ?? null" /></td>
                                            <td>{{ $data->size ?? '-' }}</td>
                                            <td style="text-align:right;">{{ $data->pieces }}</td>
                                            <td style="text-align:right;" class="{{ ($data->rejected_pieces ?? 0) > 0 ? 'rpt-over' : '' }}">{{ $data->rejected_pieces ?? 0 }}</td>
                                            <td style="text-align:right;"><b>{{ $data->pieces - ($data->rejected_pieces ?? 0) }}</b></td>
                                            <td style="text-align:right;">{{ $data->meter_per_piece }}</td>
                                            <td style="text-align:right;">{{ number_format($data->total_meter, 2) }}</td>
                                            <td style="text-align:right;">{{ number_format($data->wastage_mtr ?? 0, 2) }}</td>
                                            <td style="text-align:right;">{{ number_format($data->balance_mtr ?? 0, 2) }}</td>
                                            <td>
                                                @if($data->status == 'C')
                                                    <span class="rpt-status-pending">Cancelled</span>
                                                @else
                                                    <span class="rpt-status-completed">Completed</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="15" class="text-center" style="padding:30px;color:#999;">No cutting entries found</td>
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
