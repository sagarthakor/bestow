@extends('admin.layout.master_material')

@section('title', 'List | Belt Cutting')

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
                            <h4 class="page-title">Belt Cutting &amp; Fitting</h4>
                            <ol class="breadcrumb p-0 m-0">
                                <li><a href="{{ url('admin') }}">{{Session::get('software_title')}}</a></li>
                                <li>Belt</li>
                                <li class="active">Belt Cutting</li>
                                <li style="text-align: right;margin-bottom: 5px">
                                    <a class="btn btn-primary" href="{{ route('admin.belt_cutting.add') }}">Add New</a>
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

                            {{ Form::open(['method' => 'get', 'route' => 'admin.belt_cutting.list']) }}
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Roll No</label>
                                        <input type="text" name="roll_no" value="{{ request('roll_no') }}" class="form-control" placeholder="e.g. R-000012">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Cutting No</label>
                                        <input type="text" name="cutting_no" value="{{ request('cutting_no') }}" class="form-control" placeholder="e.g. CUT-0001">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>&nbsp;</label><br>
                                        <button type="submit" class="btn btn-primary">Search</button>
                                        <a href="{{ route('admin.belt_cutting.list') }}" class="btn btn-default">Reset</a>
                                    </div>
                                </div>
                            </div>
                            {{ Form::close() }}

                            <table class="table table-striped table-bordered">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Cutting No</th>
                                    <th>Roll No</th>
                                    <th>Niwar Code</th>
                                    <th>Sizes Cut</th>
                                    <th>Pieces</th>
                                    <th>Rejected</th>
                                    <th>Mtr Used</th>
                                    <th>Trim Mtr</th>
                                    <th>Roll Balance</th>
                                    <th>Customer</th>
                                    <th>Status</th>
                                    <th></th>
                                </tr>
                                </thead>
                                <tbody>
                                @forelse($data as $row)
                                    <tr>
                                        <td style="width:3%;text-align:center;">{{ $loop->iteration }}</td>
                                        <td>{{ $row->cutting_no }}</td>
                                        <td><b>{{ $row->roll->roll_no ?? '-' }}</b></td>
                                        <td>{{ $row->roll->niwar->label ?? '-' }}</td>
                                        <td>
                                            @foreach($row->items as $item)
                                                <span class="label label-default" style="margin-right:3px;">{{ $item->size }} &times; {{ $item->pieces }}</span>
                                            @endforeach
                                        </td>
                                        <td style="text-align:right;">{{ $row->total_pieces }}</td>
                                        <td style="text-align:right;" class="{{ $row->total_rejected_pieces > 0 ? 'text-danger' : '' }}">{{ $row->total_rejected_pieces ?: '-' }}</td>
                                        <td style="text-align:right;">{{ $row->total_meter_used }}</td>
                                        <td style="text-align:right;">{{ $row->wastage_mtr ? round($row->wastage_mtr, 2) : '-' }}</td>
                                        <td style="text-align:right;">{{ $row->balance_mtr }}</td>
                                        <td>{{ $row->customer_item->customer_name ?? '-' }}</td>
                                        <td style="text-align:center;">
                                            @if($row->status == 'C')
                                                <span class="label label-danger" title="{{ $row->cancel_reason }}">Cancelled</span>
                                            @else
                                                <span class="label label-success">Completed</span>
                                            @endif
                                        </td>
                                        <td style="text-align:center;white-space:nowrap;">
                                            <a href="javascript:void(0)" onclick="openDetail({{ $row->id }})" class="btn btn-sm btn-default">View</a>
                                            @if($row->status != 'C')
                                                @can('belt_cutting_cancel')
                                                    <a href="javascript:void(0)" onclick="openCancel({{ $row->id }}, '{{ $row->cutting_no }}')" class="btn btn-sm btn-danger">Cancel</a>
                                                @endcan
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="13" class="text-center" style="padding:30px;color:#999;">No cutting entries yet</td>
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

    <!-- Cutting Detail Modal -->
    <div id="detailModal" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5); z-index:9999; overflow:auto;">
        <div style="background:#fff; max-width:600px; margin:6% auto; padding:20px; border-radius:4px;">
            <h4>Cutting Detail</h4>
            <div id="detail_content">Loading...</div>
            <button type="button" class="btn btn-default" style="margin-top:10px;" onclick="document.getElementById('detailModal').style.display='none'">Close</button>
        </div>
    </div>

    <!-- Cancel Cutting Modal -->
    <div id="cancelModal" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5); z-index:9999;">
        <div style="background:#fff; max-width:480px; margin:10% auto; padding:20px; border-radius:4px;">
            <h4>Cancel Cutting <span id="cancel_cutting_no"></span></h4>
            {{ Form::open(['method' => 'post', 'route' => 'admin.belt_cutting.cancel', 'id' => 'cancelForm']) }}
            <input type="hidden" name="id" id="cancel_id">
            <div class="alert alert-warning">
                This takes the belts back out of finished stock, returns the fitting material to the shelf
                and puts the meters back on the roll.
            </div>
            <div class="form-group">
                <label>Reason</label>
                <input type="text" name="cancel_reason" class="form-control" required maxlength="255" placeholder="Why is this cutting being reversed?">
            </div>
            <button type="submit" id="btnCancelCutting" class="btn btn-danger">Cancel Cutting</button>
            <button type="button" class="btn btn-default" onclick="document.getElementById('cancelModal').style.display='none'">Close</button>
            {{ Form::close() }}
        </div>
    </div>

@endsection

@section('import-javascript')

<script>
        function openCancel(id, cuttingNo) {
            document.getElementById('cancel_id').value = id;
            document.getElementById('cancel_cutting_no').innerText = cuttingNo;
            document.getElementById('cancelModal').style.display = 'block';
        }

        // Reversing moves stock in three places, so it must not be sent twice.
        $('#cancelForm').on('submit', function (e) {
            if ($('#btnCancelCutting').data('sent')) { e.preventDefault(); return false; }
            $('#btnCancelCutting').data('sent', true).prop('disabled', true).text('Cancelling...');
        });

        function openDetail(id) {
            document.getElementById('detail_content').innerHTML = 'Loading...';
            document.getElementById('detailModal').style.display = 'block';
            $.ajax({
                url: '{{ route("admin.belt_cutting.detail") }}',
                data: {id: id},
                method: 'get',
                success: function (res) {
                    document.getElementById('detail_content').innerHTML = res.html;
                }
            });
        }
    </script>

@endsection
