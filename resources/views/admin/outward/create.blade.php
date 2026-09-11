@extends('admin.layout.master_material')

@section('title', 'Add New | Outward Stock')

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
                                <li><a href="{{ route('admin.outward.list') }}">Outward Stock</a></li>
                                <li class="active">Add New</li>
                            </ol>
                            <div class="clearfix"></div>
                        </div>
                    </div>
                </div>

                @if(session()->has('error'))
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="alert alert-danger">
                                <strong>{{session()->get('error')}}</strong>
                            </div>
                        </div>
                    </div>
                @endif
                @if ($errors->any())
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                @endif

                {{ Form::open(['method' => 'post', 'route' => 'admin.outward.store', 'id' => 'outwardForm']) }}

                <div class="row">
                    <div class="col-sm-12">
                        <div class="card-box">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Date <span class="text-danger">*</span></label>
                                        <input type="date" name="date" value="{{ old('date', date('Y-m-d')) }}" class="form-control" required>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Reason</label>
                                        <input type="text" name="reason" value="{{ old('reason') }}" class="form-control" placeholder="e.g. Damage, Sample, Stock correction">
                                    </div>
                                </div>
                                <div class="col-md-5">
                                    <div class="form-group">
                                        <label>Remark</label>
                                        <input type="text" name="remark" value="{{ old('remark') }}" class="form-control">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-12">
                        <div class="card-box table-responsive">

                            <table class="table table-striped table-bordered" id="itemtable">
                                <thead>
                                <tr>
                                    <th style="width:4%;">#</th>
                                    <th>Product / Raw Material</th>
                                    <th style="width:15%;">Qty</th>
                                    <th style="width:6%;"></th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td style="text-align:center;">1</td>
                                    <td>
                                        <select name="product[]" class="form-control product" required style="width:100%;"></select>
                                    </td>
                                    <td>
                                        <input type="number" name="qty[]" class="form-control" step="0.0001" min="0.0001" required>
                                    </td>
                                    <td style="text-align:center;">
                                        <a href="javascript:void(0)" onclick="remove_row(this)" class="rowremove"><i class="fa fa-trash"></i></a>
                                    </td>
                                </tr>
                                </tbody>
                            </table>

                            <button type="button" class="btn btn-default" id="add_row">+ Add Row</button>

                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-12" style="margin-bottom:20px;">
                        <button type="submit" id="btnSaveOutward" class="btn btn-primary">Save Outward Stock</button>
                        <a href="{{ route('admin.outward.list') }}" class="btn btn-default">Cancel</a>
                    </div>
                </div>

                {{ Form::close() }}

            </div>
        </div>
    </div>

@endsection

@section('import-javascript')

@include('admin.partials._product_search')

<script>
    var rowIndex = 1;

    function initRow($row) {
        ProductSearch.attach($row.find('select.product'), 'po_product');
    }

    $(function () {
        initRow($('#itemtable tbody tr').first());
    });

    $('#add_row').on('click', function () {
        rowIndex++;

        var row = '<tr>'
            + '<td style="text-align:center;">' + rowIndex + '</td>'
            + '<td><select name="product[]" class="form-control product" required style="width:100%;"></select></td>'
            + '<td><input type="number" name="qty[]" class="form-control" step="0.0001" min="0.0001" required></td>'
            + '<td style="text-align:center;"><a href="javascript:void(0)" onclick="remove_row(this)" class="rowremove"><i class="fa fa-trash"></i></a></td>'
            + '</tr>';

        var $row = $(row);
        $('#itemtable tbody').append($row);
        initRow($row);
    });

    function remove_row(ele) {
        if ($('#itemtable tbody tr').length > 1) {
            $(ele).closest('tr').remove();
        }
    }

    // Item lines move stock the moment this saves, so a double submit must not
    // send the same outward twice.
    $('#outwardForm').on('submit', function (e) {
        if ($('#btnSaveOutward').data('sent')) { e.preventDefault(); return false; }
        $('#btnSaveOutward').data('sent', true).prop('disabled', true).text('Saving...');
    });
</script>

@endsection
