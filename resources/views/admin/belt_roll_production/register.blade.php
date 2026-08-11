@extends('admin.layout.master_material')

@section('title', 'Roll Stock Register')

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
                            <h4 class="page-title">Roll Stock Register</h4>
                            <ol class="breadcrumb p-0 m-0">
                                <li><a href="{{ url('admin') }}">{{Session::get('software_title')}}</a></li>
                                <li>Belt</li>
                                <li class="active">Roll Stock Register</li>
                            </ol>
                            <div class="clearfix"></div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-12">
                        <div class="card-box table-responsive">

                            {{ Form::open(['method' => 'get', 'route' => 'admin.belt_roll_production.register']) }}
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Roll No</label>
                                        <input type="text" name="roll_no" value="{{ request('roll_no') }}" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Niwar Code</label>
                                        <select name="niwar_code_id" class="form-control">
                                            <option value="">All</option>
                                            @foreach($niwarCodes as $n)
                                                <option value="{{ $n->id }}" {{ request('niwar_code_id') == $n->id ? 'selected' : '' }}>{{ $n->label }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Status</label>
                                        <select name="status" class="form-control">
                                            <option value="">All</option>
                                            <option value="open" {{ request('status') == 'open' ? 'selected' : '' }}>Open</option>
                                            <option value="consumed" {{ request('status') == 'consumed' ? 'selected' : '' }}>Fully Used</option>
                                            <option value="scrap" {{ request('status') == 'scrap' ? 'selected' : '' }}>Closed / Scrap</option>
                                            <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Voided</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>&nbsp;</label><br>
                                        <button type="submit" class="btn btn-primary">Search</button>
                                        <a href="{{ route('admin.belt_roll_production.register') }}" class="btn btn-default">Reset</a>
                                    </div>
                                </div>
                            </div>
                            {{ Form::close() }}

                            @if(session()->has('message'))
                                <div class="alert alert-info" style="background-color: #188ae2 !important">
                                    <strong style="color: #fff">{{session()->get('message')}}</strong>
                                </div>
                            @endif
                            @if(session()->has('error'))
                                <div class="alert alert-danger"><strong>{{session()->get('error')}}</strong></div>
                            @endif

                            <div class="row" style="margin-bottom:10px;">
                                <div class="col-md-4"><b>Rolls:</b> {{ $totalRolls }}</div>
                                <div class="col-md-4"><b>Balance on rolls:</b> {{ $totalRemaining }} mtr</div>
                                <div class="col-md-4"><b>Closed as scrap:</b> {{ $totalScrap }} mtr</div>
                            </div>

                            <table class="table table-striped table-bordered">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Roll No</th>
                                    <th>Niwar Code</th>
                                    <th>Roll Product</th>
                                    <th>Batch</th>
                                    <th>Length Mtr</th>
                                    <th>Used Mtr</th>
                                    <th>Balance Mtr</th>
                                    <th>Scrap Mtr</th>
                                    <th>Status</th>
                                    <th></th>
                                </tr>
                                </thead>
                                <tbody>
                                @forelse($data as $row)
                                    <tr>
                                        <td style="width:3%;text-align:center;">{{ $loop->iteration }}</td>
                                        <td><b>{{ $row->roll_no }}</b></td>
                                        <td>{{ $row->niwar->label ?? '-' }}</td>
                                        <td>{{ $row->roll_product->product_name ?? '-' }}</td>
                                        <td>{{ $row->roll_production->batch_no ?? '-' }}</td>
                                        <td style="text-align:right;">{{ $row->length_mtr }}</td>
                                        <td style="text-align:right;">{{ round($row->length_mtr - $row->remaining_mtr - ($row->scrap_mtr ?? 0), 2) }}</td>
                                        <td style="text-align:right;">{{ round($row->remaining_mtr, 2) }}</td>
                                        <td style="text-align:right;">{{ $row->scrap_mtr ? round($row->scrap_mtr, 2) : '-' }}</td>
                                        <td style="text-align:center;">
                                            @if($row->status == 'open')
                                                <span class="label label-warning">Open</span>
                                            @elseif($row->status == 'consumed')
                                                <span class="label label-success">Fully Used</span>
                                            @elseif($row->status == 'cancelled')
                                                <span class="label label-danger">Voided</span>
                                            @else
                                                <span class="label label-default">Closed / Scrap</span>
                                            @endif
                                        </td>
                                        <td style="text-align:center;">
                                            @if($row->status == 'open')
                                                <a href="{{ route('admin.belt_cutting.add', ['roll_id' => $row->id]) }}" class="btn btn-sm btn-primary">Cut</a>
                                                @if($row->remaining_mtr > 0)
                                                    <a href="javascript:void(0)" onclick="openClose({{ $row->id }}, '{{ $row->roll_no }}', {{ round($row->remaining_mtr, 2) }})" class="btn btn-sm btn-default">Close</a>
                                                @endif
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="11" class="text-center" style="padding:30px;color:#999;">No rolls found</td>
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

    <!-- Close Roll Modal -->
    <div id="closeModal" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5); z-index:9999;">
        <div style="background:#fff; max-width:460px; margin:10% auto; padding:20px; border-radius:4px;">
            <h4>Close Roll</h4>
            {{ Form::open(['method' => 'post', 'route' => 'admin.belt_cutting.close_roll', 'id' => 'closeRollForm']) }}
            <input type="hidden" name="roll_id" id="close_roll_id">
            <p>Roll <b id="close_roll_no"></b> has <b id="close_balance"></b> mtr left, too short to cut any more belts.</p>
            <p class="text-muted">Closing writes that balance off as scrap and takes it out of roll stock, so the meters do not sit on the books forever.</p>
            <button type="submit" class="btn btn-primary">Close as Scrap</button>
            <button type="button" class="btn btn-default" onclick="document.getElementById('closeModal').style.display='none'">Cancel</button>
            {{ Form::close() }}
        </div>
    </div>

@endsection

@section('import-javascript')

<script>
        function openClose(id, rollNo, balance) {
            document.getElementById('close_roll_id').value = id;
            document.getElementById('close_roll_no').innerText = rollNo;
            document.getElementById('close_balance').innerText = balance;
            document.getElementById('closeModal').style.display = 'block';
        }

        // Closing writes meters off stock, so it must not be submitted twice.
        $('#closeRollForm').on('submit', function (e) {
            if ($(this).data('sent')) { e.preventDefault(); return false; }
            $(this).data('sent', true).find('button[type=submit]').prop('disabled', true).text('Closing...');
        });
    </script>

@endsection
