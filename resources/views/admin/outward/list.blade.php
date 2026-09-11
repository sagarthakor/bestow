@extends('admin.layout.master_material')

@section('title', 'List | Outward Stock')

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
                            <h4 class="page-title">Outward Stock</h4>
                            <ol class="breadcrumb p-0 m-0">
                                <li><a href="{{ url('admin') }}">{{Session::get('software_title')}}</a></li>
                                <li>Inventory</li>
                                <li class="active">Outward Stock</li>
                                @can('outward_stock_create')
                                    <li style="text-align: right;margin-bottom: 5px">
                                        <a class="btn btn-primary" href="{{ route('admin.outward.add') }}">Add New</a>
                                    </li>
                                @endcan
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

                            {{ Form::open(['method' => 'get', 'route' => 'admin.outward.list']) }}
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Doc No</label>
                                        <input type="text" name="doc_no" value="{{ request('doc_no') }}" class="form-control" placeholder="e.g. OUT-0001">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Date From</label>
                                        <input type="date" name="date_from" value="{{ request('date_from') }}" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Date To</label>
                                        <input type="date" name="date_to" value="{{ request('date_to') }}" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>&nbsp;</label><br>
                                        <button type="submit" class="btn btn-primary">Search</button>
                                        <a href="{{ route('admin.outward.list') }}" class="btn btn-default">Reset</a>
                                    </div>
                                </div>
                            </div>
                            {{ Form::close() }}

                            <table class="table table-striped table-bordered">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Doc No</th>
                                    <th>Date</th>
                                    <th>Reason</th>
                                    <th>Items</th>
                                    <th>Status</th>
                                    <th></th>
                                </tr>
                                </thead>
                                <tbody>
                                @forelse($data as $row)
                                    <tr>
                                        <td style="width:3%;text-align:center;">{{ $loop->iteration }}</td>
                                        <td>{{ $row->doc_no }}</td>
                                        <td>{{ $row->date }}</td>
                                        <td>{{ $row->reason ?: '-' }}</td>
                                        <td>
                                            @foreach($row->items as $item)
                                                <span class="label label-default" style="margin-right:3px;">
                                                    {{ \App\product::nameWithVariantInline(optional($item->product_item)->product_name, optional($item->product_item)->value1, optional($item->product_item)->value2) }}
                                                    &times; {{ $item->qty }}
                                                </span>
                                            @endforeach
                                        </td>
                                        <td style="text-align:center;">
                                            @if($row->status == 'C')
                                                <span class="label label-danger" title="{{ $row->cancel_reason }}">Cancelled</span>
                                            @else
                                                <span class="label label-success">Completed</span>
                                            @endif
                                        </td>
                                        <td style="text-align:center;white-space:nowrap;">
                                            @if($row->status != 'C')
                                                @can('outward_stock_cancel')
                                                    <a href="javascript:void(0)" onclick="openCancel({{ $row->id }}, '{{ $row->doc_no }}')" class="btn btn-sm btn-danger">Cancel</a>
                                                @endcan
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center" style="padding:30px;color:#999;">No outward stock entries yet</td>
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

    <!-- Cancel Modal -->
    <div id="cancelModal" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5); z-index:9999;">
        <div style="background:#fff; max-width:480px; margin:10% auto; padding:20px; border-radius:4px;">
            <h4>Cancel Outward Stock <span id="cancel_doc_no"></span></h4>
            {{ Form::open(['method' => 'post', 'route' => 'admin.outward.cancel', 'id' => 'cancelForm']) }}
            <input type="hidden" name="id" id="cancel_id">
            <div class="alert alert-warning">
                This puts the quantity back into stock.
            </div>
            <div class="form-group">
                <label>Reason</label>
                <input type="text" name="cancel_reason" class="form-control" required maxlength="255" placeholder="Why is this outward being reversed?">
            </div>
            <button type="submit" id="btnCancelOutward" class="btn btn-danger">Cancel Outward</button>
            <button type="button" class="btn btn-default" onclick="document.getElementById('cancelModal').style.display='none'">Close</button>
            {{ Form::close() }}
        </div>
    </div>

@endsection

@section('import-javascript')

<script>
        function openCancel(id, docNo) {
            document.getElementById('cancel_id').value = id;
            document.getElementById('cancel_doc_no').innerText = docNo;
            document.getElementById('cancelModal').style.display = 'block';
        }

        $('#cancelForm').on('submit', function (e) {
            if ($('#btnCancelOutward').data('sent')) { e.preventDefault(); return false; }
            $('#btnCancelOutward').data('sent', true).prop('disabled', true).text('Cancelling...');
        });
    </script>

@endsection
