@extends('admin.layout.master_material')

@section('title', 'Update | Buckle Formula')

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
                            <h4 class="page-title">Update Buckle Formula</h4>
                            <ol class="breadcrumb p-0 m-0">
                                <li><a href="{{ url('admin') }}">{{Session::get('software_title')}}</a></li>
                                <li><a href="{{ route('admin.production.buckle_formula_list') }}">Buckle Formula List</a></li>
                                <li>Update</li>
                            </ol>
                            <div class="clearfix"></div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xs-12">
                        {{ Form::model($data, ['method' => 'post', 'route' => 'admin.production.buckle_formula_update']) }}
                        {{ Form::hidden('id', null) }}
                        <div class="card-box">

                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <div class="row">
                                <div class="col-md-5">
                                    <div class="form-group">
                                        <label class="control-label">Belt Product</label>
                                        <input type="text" class="form-control" value="{{ $data->product_item->product_name ?? '-' }}" readonly>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="control-label">Size</label>
                                        {{ Form::text('size', null, ['class' => 'form-control', 'required']) }}
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="control-label">Nos</label>
                                        {{ Form::text('nos', null, ['class' => 'form-control']) }}
                                    </div>
                                </div>
                            </div>

                            <h4>Belt Costing (Niwar Type / Bukkal Type)</h4>
                            <div class="row">
                                <div class="col-md-5">
                                    <div class="form-group">
                                        <label class="control-label">Belt Costing</label>
                                        <select name="belt_costing_id" id="belt_costing_id" class="form-control js-example-basic-single" required onchange="fillBeltCosting(this.value)">
                                            <option value="">Select bukkal / niwar combination</option>
                                            @foreach($beltCosting as $cost)
                                                <option value="{{ $cost->id }}" {{ $cost->id == $data->belt_costing_id ? 'selected' : '' }}>{{ $cost->bukkal->type ?? '-' }} / {{ $cost->niwar->type ?? '-' }} &mdash; Total: {{ number_format($cost->total_cost, 2) }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row" id="belt_costing_details" style="display:none;">
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label class="control-label">Bukkal Type</label>
                                        <input type="text" id="bc_bukkal_type" class="form-control" readonly>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label class="control-label">Bukkal Rate</label>
                                        <input type="text" id="bc_bukkal_rate" class="form-control" readonly>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label class="control-label">Niwar Type</label>
                                        <input type="text" id="bc_niwar_type" class="form-control" readonly>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label class="control-label">Niwar Rate</label>
                                        <input type="text" id="bc_niwar_rate" class="form-control" readonly>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label class="control-label">Miter</label>
                                        <input type="text" id="bc_miter" class="form-control" readonly>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label class="control-label">Kadi/Slider Qty</label>
                                        <input type="text" id="bc_kadi_qty" class="form-control" readonly>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label class="control-label">Kadi/Slider Rate</label>
                                        <input type="text" id="bc_kadi_rate" class="form-control" readonly>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label class="control-label">Size Label</label>
                                        <input type="text" id="bc_size_label" class="form-control" readonly>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label class="control-label">Panni Packing Rate</label>
                                        <input type="text" id="bc_panni_packing" class="form-control" readonly>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label class="control-label">Total Costing</label>
                                        <input type="text" id="bc_total_cost" class="form-control" readonly>
                                    </div>
                                </div>
                            </div>

                            <h4>Raw Material Required (per 1 Belt)</h4>

                            <table class="table table-bordered" id="caltable">
                                <thead>
                                <tr>
                                    <th>Raw Material</th>
                                    <th>Qty (per 1 Belt)</th>
                                    <th style="width:8%;"></th>
                                </tr>
                                </thead>
                                <tbody id="material-rows">
                                @foreach($data_item as $item)
                                    <tr>
                                        <td>
                                            <select name="material[]" class="form-control js-example-basic-single material-select" onchange="updateUomHint(this)">
                                                <option value="">Select raw material</option>
                                                @foreach($rawmaterial as $mat)
                                                    <option value="{{ $mat->id }}" data-uom="{{ strtoupper($mat->uomName->uom_name ?? '') }}" {{ $mat->id == $item->material ? 'selected' : '' }}>{{ $mat->product_name }}</option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td>
                                            <input type="text" name="qty[]" value="{{ $item->qty }}" class="form-control">
                                            <small class="uom-hint text-muted"></small>
                                        </td>
                                        <td class="text-center">
                                            <a href="javascript:void(0)" onclick="removeRow(this)" title="Remove"><i class="fa fa-trash"></i></a>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>

                            <button type="button" id="add_rawmaterial" class="btn btn-default">+ Add Raw Material</button>

                            <div style="margin-top:20px;">
                                <button type="submit" class="btn btn-primary">Save</button>
                            </div>

                        </div>
                        {{ Form::close() }}
                    </div>
                </div>

            </div>
        </div>
    </div>

    <script src="{{asset('/admin/assets/js/jquery.min.js')}}"></script>
    <script>
        $(document).ready(function () {
            $('.js-example-basic-single').select2();
            fillBeltCosting($('#belt_costing_id').val());
            $('.material-select').each(function () {
                updateUomHint(this);
            });
        });

        @php
            $beltCostingJs = [];
            foreach ($beltCosting as $cost) {
                $beltCostingJs[$cost->id] = [
                    'bukkal_type' => $cost->bukkal->type ?? '-',
                    'bukkal_rate' => $cost->bukkal_rate,
                    'niwar_type' => $cost->niwar->type ?? '-',
                    'niwar_rate' => $cost->niwar_rate,
                    'miter' => $cost->miter,
                    'kadi_qty' => $cost->kadi_qty,
                    'kadi_rate' => $cost->kadi_rate,
                    'size_label' => $cost->size_label,
                    'panni_packing' => $cost->panni_packing,
                    'total_cost' => $cost->total_cost,
                ];
            }
        @endphp
        var beltCostingData = @json($beltCostingJs);

        function fillBeltCosting(id) {
            var cost = beltCostingData[id];
            if (!cost) {
                $('#belt_costing_details').hide();
                return;
            }
            $('#bc_bukkal_type').val(cost.bukkal_type);
            $('#bc_bukkal_rate').val(cost.bukkal_rate);
            $('#bc_niwar_type').val(cost.niwar_type);
            $('#bc_niwar_rate').val(cost.niwar_rate);
            $('#bc_miter').val(cost.miter);
            $('#bc_kadi_qty').val(cost.kadi_qty);
            $('#bc_kadi_rate').val(cost.kadi_rate);
            $('#bc_size_label').val(cost.size_label);
            $('#bc_panni_packing').val(cost.panni_packing);
            $('#bc_total_cost').val(cost.total_cost);
            $('#belt_costing_details').show();
        }

        var materialOptions = `@foreach($rawmaterial as $mat)<option value="{{ $mat->id }}" data-uom="{{ strtoupper($mat->uomName->uom_name ?? '') }}">{{ $mat->product_name }}</option>@endforeach`;

        function updateUomHint(selectEl) {
            var $hint = $(selectEl).closest('tr').find('.uom-hint');
            var uom = $(selectEl).find(':selected').data('uom');
            if (!uom) {
                $hint.text('');
            } else if (uom === 'KG') {
                $hint.text('Enter in grams');
            } else {
                $hint.text('Unit: ' + uom);
            }
        }

        document.getElementById('add_rawmaterial').addEventListener('click', function () {
            var row = document.createElement('tr');
            row.innerHTML =
                '<td><select name="material[]" class="form-control js-example-basic-single material-select" onchange="updateUomHint(this)">' +
                '<option value="">Select raw material</option>' + materialOptions + '</select></td>' +
                '<td><input type="text" name="qty[]" class="form-control"><small class="uom-hint text-muted"></small></td>' +
                '<td class="text-center"><a href="javascript:void(0)" onclick="removeRow(this)" title="Remove"><i class="fa fa-trash"></i></a></td>';
            document.getElementById('material-rows').appendChild(row);
            $(row).find('.js-example-basic-single').select2();
        });

        function removeRow(el) {
            var rows = document.getElementById('material-rows').rows;
            if (rows.length > 1) {
                el.closest('tr').remove();
            }
        }
    </script>

@endsection
