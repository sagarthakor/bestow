@extends('admin.layout.master_material')

@section('title', 'List | Roll Production')

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
                            <h4 class="page-title">Roll Production</h4>
                            <ol class="breadcrumb p-0 m-0">
                                <li><a href="{{ url('admin') }}">{{Session::get('software_title')}}</a></li>
                                <li>Belt</li>
                                <li class="active">Roll Production</li>
                                <li style="text-align: right;margin-bottom: 5px">
                                    <a class="btn btn-primary" href="{{ route('admin.belt_roll_production.add') }}">Add New</a>
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

                            {{ Form::open(['method' => 'get', 'route' => 'admin.belt_roll_production.list']) }}
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Batch No</label>
                                        <input type="text" name="batch_no" value="{{ request('batch_no') }}" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Status</label>
                                        <select name="status" class="form-control">
                                            <option value="">All</option>
                                            <option value="N" {{ request('status') == 'N' ? 'selected' : '' }}>Pending</option>
                                            <option value="Y" {{ request('status') == 'Y' ? 'selected' : '' }}>Completed</option>
                                            <option value="C" {{ request('status') == 'C' ? 'selected' : '' }}>Cancelled</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>&nbsp;</label><br>
                                        <button type="submit" class="btn btn-primary">Search</button>
                                        <a href="{{ route('admin.belt_roll_production.list') }}" class="btn btn-default">Reset</a>
                                    </div>
                                </div>
                            </div>
                            {{ Form::close() }}

                            <p class="text-muted">Rolls are made without any size. Size comes later, in Belt Cutting.</p>

                            <table class="table table-striped table-bordered">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Batch No</th>
                                    <th>Semi Product</th>
                                    <th>Niwar Code</th>
                                    <th>Formula</th>
                                    <th>Customer</th>
                                    <th>Roll Plan</th>
                                    <th>Planned Mtr</th>
                                    <th>Produced Mtr</th>
                                    <th>Wastage Mtr</th>
                                    <th>Rolls Made</th>
                                    <th>Status</th>
                                    <th></th>
                                </tr>
                                </thead>
                                <tbody>
                                @forelse($data as $row)
                                    <tr>
                                        <td style="width:3%;text-align:center;">{{ $loop->iteration }}</td>
                                        <td>{{ $row->batch_no }}</td>
                                        <td><b>{{ $row->roll_product->product_name ?? '-' }}</b></td>
                                        <td>{{ $row->niwar->label ?? '-' }}</td>
                                        <td>{{ $row->roll_formula_version ? 'V' . $row->roll_formula_version : '-' }}</td>
                                        <td>{{ $row->customer_item->customer_name ?? '-' }}</td>
                                        <td style="text-align:right;">{{ $row->no_of_rolls }} x {{ $row->roll_length_mtr }} mtr</td>
                                        <td style="text-align:right;">{{ $row->planned_mtr }}</td>
                                        <td style="text-align:right;">{{ $row->status == 'Y' ? $row->produced_mtr : '-' }}</td>
                                        <td style="text-align:right;">
                                            @if($row->status == 'Y')
                                                <span class="{{ $row->wastage_mtr > 0 ? 'text-danger' : 'text-success' }}">{{ $row->wastage_mtr }}</span>
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td style="text-align:center;">
                                            @if($row->rolls_count > 0)
                                                <a href="{{ route('admin.belt_roll_production.register', ['batch' => $row->id]) }}">{{ $row->rolls_count }}</a>
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td style="text-align:center;">
                                            @if($row->status == 'Y')
                                                <span class="label label-success">Completed</span>
                                            @elseif($row->status == 'C')
                                                <span class="label label-danger" title="{{ $row->cancel_reason }}">Cancelled</span>
                                            @else
                                                <span class="label label-warning">Pending</span>
                                            @endif
                                        </td>
                                        <td style="text-align:center;white-space:nowrap;">
                                            @if($row->status == 'N')
                                                @can('belt_roll_production_create')
                                                    <a href="javascript:void(0)" onclick="openComplete({{ $row->id }}, '{{ $row->batch_no }}')" class="btn btn-sm btn-default">Complete</a>
                                                @endcan
                                            @endif
                                            <a href="javascript:void(0)" onclick="openMaterial({{ $row->id }})" class="btn btn-sm btn-default">Dhaga Used</a>
                                            @if($row->status != 'C')
                                                @can('belt_roll_production_cancel')
                                                    <a href="javascript:void(0)" onclick="openCancel({{ $row->id }}, '{{ $row->batch_no }}')" class="btn btn-sm btn-danger">Cancel</a>
                                                @endcan
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="13" class="text-center" style="padding:30px;color:#999;">No roll production batches yet</td>
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

    <!-- Complete Roll Batch Modal -->
    <div id="completeModal" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5); z-index:9999; overflow:auto;">
        <div style="background:#fff; max-width:720px; margin:5% auto; padding:20px; border-radius:4px;">
            <h4>Complete Roll Production Batch <span id="complete_batch_no"></span></h4>
            {{ Form::open(['method' => 'post', 'route' => 'admin.belt_roll_production.complete', 'id' => 'completeForm']) }}
            <input type="hidden" name="id" id="complete_id">

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Planned Meters</label>
                        <input type="text" id="planned_display" class="form-control" readonly>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Actual Meters Produced</label>
                        <input type="number" step="0.01" min="0" name="produced_mtr" id="produced_mtr" class="form-control" required onkeyup="previewRolls()" onchange="previewRolls()">
                        <small class="text-muted">Cannot exceed the plan &mdash; dhaga was issued for the planned meters only.</small>
                    </div>
                </div>
            </div>

            <div class="alert alert-info" id="roll_preview" style="background-color:#eef6fd;color:#333 !important;"></div>

            <h5>Dhaga Actually Consumed</h5>
            <div id="complete_materials">Loading...</div>

            <div style="margin-top:12px;">
                <button type="submit" id="btnComplete" class="btn btn-primary">Save</button>
                <button type="button" class="btn btn-default" onclick="document.getElementById('completeModal').style.display='none'">Cancel</button>
            </div>
            {{ Form::close() }}
        </div>
    </div>

    <!-- Cancel Roll Batch Modal -->
    <div id="cancelModal" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5); z-index:9999;">
        <div style="background:#fff; max-width:480px; margin:10% auto; padding:20px; border-radius:4px;">
            <h4>Cancel Roll Batch <span id="cancel_batch_no"></span></h4>
            {{ Form::open(['method' => 'post', 'route' => 'admin.belt_roll_production.cancel', 'id' => 'cancelForm']) }}
            <input type="hidden" name="id" id="cancel_id">
            <div class="alert alert-warning">
                This puts every gram of dhaga back into stock, voids the rolls this batch produced and
                takes their meters back out of roll stock. Only possible while none of its rolls have been cut.
            </div>
            <div class="form-group">
                <label>Reason</label>
                <input type="text" name="cancel_reason" class="form-control" required maxlength="255" placeholder="Why is this batch being cancelled?">
            </div>
            <button type="submit" id="btnCancelBatch" class="btn btn-danger">Cancel Batch</button>
            <button type="button" class="btn btn-default" onclick="document.getElementById('cancelModal').style.display='none'">Close</button>
            {{ Form::close() }}
        </div>
    </div>

    <!-- Dhaga Used Modal -->
    <div id="materialModal" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5); z-index:9999; overflow:auto;">
        <div style="background:#fff; max-width:720px; margin:6% auto; padding:20px; border-radius:4px;">
            <h4>Dhaga Issued To This Batch</h4>
            <div id="material_content">Loading...</div>
            <button type="button" class="btn btn-default" style="margin-top:10px;" onclick="document.getElementById('materialModal').style.display='none'">Close</button>
        </div>
    </div>

@endsection

@section('import-javascript')

<script>
        var completeRollLength = 0;
        var completePlanned = 0;

        function openMaterial(id) {
            document.getElementById('material_content').innerHTML = 'Loading...';
            document.getElementById('materialModal').style.display = 'block';
            $.get('{{ route("admin.belt_roll_production.material") }}', {id: id}, function (res) {
                document.getElementById('material_content').innerHTML = res.html;
            });
        }

        function openCancel(id, batchNo) {
            document.getElementById('cancel_id').value = id;
            document.getElementById('cancel_batch_no').innerText = batchNo;
            document.getElementById('cancelModal').style.display = 'block';
        }

        /**
         * The planned consumption is fetched rather than rendered into the page,
         * so the actual figures the operator corrects are always the ones the
         * batch was really issued.
         */
        function openComplete(id, batchNo) {
            document.getElementById('complete_id').value = id;
            document.getElementById('complete_batch_no').innerText = batchNo;
            document.getElementById('complete_materials').innerHTML = 'Loading...';
            document.getElementById('roll_preview').innerHTML = '';
            document.getElementById('completeModal').style.display = 'block';
            $('#btnComplete').prop('disabled', true);

            $.get('{{ route("admin.belt_roll_production.completion_form") }}', {id: id}, function (res) {
                document.getElementById('complete_materials').innerHTML = res.html;

                if (!res.ok) {
                    return;
                }

                completePlanned = res.planned_mtr;
                completeRollLength = res.roll_length_mtr;
                document.getElementById('planned_display').value = res.planned_mtr;
                document.getElementById('produced_mtr').value = res.planned_mtr;
                document.getElementById('produced_mtr').max = res.planned_mtr;
                previewRolls();
            });
        }

        /**
         * Mirrors BeltRollProductionController::splitIntoRolls so the operator can
         * see how many roll numbers this will create before saving.
         */
        function previewRolls() {
            var produced = parseFloat(document.getElementById('produced_mtr').value) || 0;
            var box = document.getElementById('roll_preview');
            var variance = Math.round((produced - completePlanned) * 100) / 100;

            // Mirrors the server's refusal, so the operator is stopped at the
            // field rather than after a round trip.
            if (variance > 0) {
                box.innerHTML = '<span style="color:#c0392b"><b>' + produced + ' mtr is ' + variance
                    + ' mtr over the planned ' + completePlanned + ' mtr.</b> Dhaga was only issued for the plan, '
                    + 'so this cannot be saved. Reduce the figure, or raise a new batch for the quantity intended.</span>';
                $('#btnComplete').prop('disabled', true);
                return;
            }

            $('#btnComplete').prop('disabled', false);

            if (produced <= 0 || completeRollLength <= 0) {
                box.innerHTML = produced <= 0 ? 'No meters produced - the batch will close with no rolls.' : '';
                return;
            }

            var full = Math.floor(produced / completeRollLength);
            var left = Math.round((produced - full * completeRollLength) * 100) / 100;

            var text = full > 0 ? full + ' roll(s) of ' + completeRollLength + ' mtr' : '';
            if (left > 0) {
                text += (text ? ' + ' : '') + '1 short roll of ' + left + ' mtr';
            }

            var note = variance < 0
                ? ' <span style="color:#c0392b">' + Math.abs(variance) + ' mtr short of plan, recorded as wastage.</span>'
                : '';

            box.innerHTML = 'This will create <b>' + text + '</b>.' + note;
        }

        // Completing and cancelling both move stock, so neither may be submitted twice.
        $('#completeForm').on('submit', function (e) {
            if ($('#btnComplete').data('sent')) { e.preventDefault(); return false; }
            $('#btnComplete').data('sent', true).prop('disabled', true).text('Saving...');
        });
        $('#cancelForm').on('submit', function (e) {
            if ($('#btnCancelBatch').data('sent')) { e.preventDefault(); return false; }
            $('#btnCancelBatch').data('sent', true).prop('disabled', true).text('Cancelling...');
        });
    </script>

@endsection
