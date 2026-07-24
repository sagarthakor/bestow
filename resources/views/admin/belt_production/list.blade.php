@extends('admin.layout.master_material')

@section('title', 'List | Belt Production')

@section('sidebar')
    @parent
@endsection

@section('content')

    <div class="content-page">
        <div class="content">
            <div class="container">

                <div class="row">
                    <div class="col-xs-12">
                        <div class="page-title-box">
                            <h4 class="page-title">Belt Production</h4>
                            <ol class="breadcrumb p-0 m-0">
                                <li><a href="{{ url('admin') }}">{{Session::get('software_title')}}</a></li>
                                <li>Belt Production</li>
                                <li class="active">List</li>
                                <li style="text-align: right;margin-bottom: 5px">
                                    <a class="btn btn-primary" href="{{ route('admin.belt_production.add') }}">Add New</a>
                                </li>
                            </ol>
                            <div class="clearfix"></div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-12">
                        <div class="card-box table-responsive">

                            @if(session()->has('message'))
                                <div class="col-sm-12">
                                    <div class="alert alert-info" style="background-color: #188ae2 !important">
                                        <strong style="color: #fff">{{session()->get('message')}}</strong>
                                    </div>
                                </div>
                            @endif
                            @if(session()->has('error'))
                                <div class="col-sm-12">
                                    <div class="alert alert-danger">
                                        <strong>{{session()->get('error')}}</strong>
                                    </div>
                                </div>
                            @endif

                            <table class="table table-striped table-bordered">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Batch No</th>
                                    <th>Belt</th>
                                    <th>Customer</th>
                                    <th>Planned Qty</th>
                                    <th>Produced Qty</th>
                                    <th>Wastage</th>
                                    <th>Status</th>
                                    <th></th>
                                </tr>
                                </thead>
                                <tbody>
                                @forelse($data as $row)
                                    <tr>
                                        <td style="width:3%;text-align:center;">{{ $loop->iteration }}</td>
                                        <td>{{ $row->batch_no }}</td>
                                        <td>{{ $row->belt_item->product_name ?? '-' }}</td>
                                        <td>{{ $row->customer_item->customer_name ?? '-' }}</td>
                                        <td style="text-align:right;">{{ $row->planned_qty }}</td>
                                        <td style="text-align:right;">{{ $row->total_production ?? '-' }}</td>
                                        <td style="text-align:right;">
                                            @if($row->status == 'Y')
                                                <span class="{{ $row->total_wastage_nos > 0 ? 'text-danger' : 'text-success' }}">{{ $row->total_wastage_nos }}</span>
                                                @if($row->total_wastage_nos > 0)
                                                    <br><a href="javascript:void(0)" onclick="openWastage({{ $row->id }})" style="font-size:11px;">View Wastage</a>
                                                @endif
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td style="text-align:center;">
                                            @if($row->status == 'Y')
                                                <span class="label label-success">Completed</span>
                                            @else
                                                <span class="label label-warning">Pending</span>
                                            @endif
                                        </td>
                                        <td style="text-align:center;">
                                            @if($row->status == 'N')
                                                <a href="javascript:void(0)" onclick="openComplete({{ $row->id }}, {{ $row->planned_qty }})" class="btn btn-sm btn-default">Complete</a>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="text-center" style="padding:30px;color:#999;">No belt production batches yet</td>
                                    </tr>
                                @endforelse
                                </tbody>
                            </table>
                            {{ $data->links() }}

                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- Complete Batch Modal -->
    <div id="completeModal" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5); z-index:9999;">
        <div style="background:#fff; max-width:420px; margin:10% auto; padding:20px; border-radius:4px;">
            <h4>Complete Belt Production Batch</h4>
            {{ Form::open(['method' => 'post', 'route' => 'admin.belt_production.complete']) }}
            <input type="hidden" name="id" id="complete_id">
            <div class="form-group">
                <label>Planned Qty</label>
                <input type="text" id="planned_qty_display" class="form-control" readonly>
            </div>
            <div class="form-group">
                <label>Actual Qty Produced</label>
                <input type="text" name="total_production" id="total_production" class="form-control" required>
                <p class="help-block">If less than planned, the difference is recorded as wastage.</p>
            </div>
            <button type="submit" class="btn btn-primary">Save</button>
            <button type="button" class="btn btn-default" onclick="document.getElementById('completeModal').style.display='none'">Cancel</button>
            {{ Form::close() }}
        </div>
    </div>

    <!-- Wastage Material Modal -->
    <div id="wastageModal" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5); z-index:9999;">
        <div style="background:#fff; max-width:500px; margin:10% auto; padding:20px; border-radius:4px;">
            <h4>Raw Material Wasted</h4>
            <div id="wastage_content">Loading...</div>
            <button type="button" class="btn btn-default" style="margin-top:10px;" onclick="document.getElementById('wastageModal').style.display='none'">Close</button>
        </div>
    </div>

    <script>
        function openComplete(id, plannedQty) {
            document.getElementById('complete_id').value = id;
            document.getElementById('planned_qty_display').value = plannedQty;
            document.getElementById('total_production').value = plannedQty;
            document.getElementById('completeModal').style.display = 'block';
        }

        function openWastage(id) {
            document.getElementById('wastage_content').innerHTML = 'Loading...';
            document.getElementById('wastageModal').style.display = 'block';
            $.ajax({
                url: '{{ route("admin.belt_production.wastage_material") }}',
                data: { id: id },
                method: 'get',
                success: function (res) {
                    document.getElementById('wastage_content').innerHTML = res.html;
                }
            });
        }
    </script>

@endsection
