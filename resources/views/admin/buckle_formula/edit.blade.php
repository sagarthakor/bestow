@extends('admin.layout.master_material')

@section('title', 'Update | Belt Formula')

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
                            <h4 class="page-title">Update Belt Formula</h4>
                            <ol class="breadcrumb p-0 m-0">
                                <li><a href="{{ url('admin') }}">{{Session::get('software_title')}}</a></li>
                                <li><a href="{{ route('admin.production.buckle_formula_list') }}">Belt Formula List</a></li>
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
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label">Belt Product</label>
                                        <input type="text" class="form-control" value="{{ $data->product_item->product_name ?? '-' }}" readonly>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label">Belt Costing</label>
                                        <select name="belt_costing_id" id="belt_costing_id" class="form-control js-example-basic-single" required>
                                            <option value="">Select bukkal / niwar combination</option>
                                            @foreach($beltCosting as $cost)
                                                <option value="{{ $cost->id }}" data-niwar-id="{{ $cost->niwar_id }}" {{ $cost->id == $data->belt_costing_id ? 'selected' : '' }}>{{ $cost->bukkal->type ?? '-' }} / {{ $cost->niwar->type ?? '-' }} &mdash; Total: {{ number_format($cost->total_cost, 2) }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label class="control-label">Size</label>
                                        {{ Form::text('size', null, ['class' => 'form-control', 'id' => 'size_input', 'required']) }}
                                        <small id="niwar-auto-hint" class="text-muted"></small>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label class="control-label">Nos</label>
                                        {{ Form::text('nos', 1, ['class' => 'form-control', 'readonly' => 'readonly']) }}
                                        <small class="text-muted">Formula is always per 1 belt</small>
                                    </div>
                                </div>
                            </div>

                            <h4>Raw Material Required (per 1 Belt)</h4>

                            <div id="niwar-fixed-section" style="display:none;">
                                <table class="table table-bordered">
                                    <thead>
                                    <tr>
                                        <th>Raw Material (from Niwar Code)</th>
                                        <th>Qty (per 1 Belt)</th>
                                        <th style="width:8%;"></th>
                                    </tr>
                                    </thead>
                                    <tbody id="niwar-fixed-rows"></tbody>
                                </table>
                            </div>

                            <div id="niwar-groups-container"></div>

                            <h4 style="margin-top:20px;">Other Raw Materials <small class="text-muted">(not from Niwar Code, e.g. buckle, kadi, packaging)</small></h4>

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
                                    @continue($item->niwar_type_material_id)
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
                                            <input type="hidden" name="is_auto[]" value="0">
                                            <input type="hidden" name="niwar_group[]" value="">
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
            $('.material-select').each(function () {
                updateUomHint(this);
            });
            recalcNiwarMaterials();
        });

        var materialOptions = `@foreach($rawmaterial as $mat)<option value="{{ $mat->id }}" data-uom="{{ strtoupper($mat->uomName->uom_name ?? '') }}">{{ $mat->product_name }}</option>@endforeach`;

        var rawMaterialNames = {!! $rawmaterial->pluck('product_name', 'id')->toJson() !!};

        var savedGroupItems = {!! json_encode($groupItems ?? []) !!};

        var niwarTypeData = {!! $niwarTypes->mapWithKeys(function ($n) {
            return [$n->id => [
                'type' => $n->type,
                'code' => $n->code,
                'inchPerMeter' => (float) ($n->inch_per_meter ?: 39.37),
                'materials' => $n->materials->map(function ($m) {
                    return ['id' => $m->id, 'material' => $m->material, 'gm_per_meter' => (float) $m->gm_per_meter, 'is_group' => (bool) $m->is_group];
                })->values(),
                'sizeChart' => $n->sizeChart->pluck('required_inch', 'pp_size'),
            ]];
        })->toJson() !!};

        function niwarLabel(data) {
            return data.type + ' (' + data.code + ')';
        }

        function recalcNiwarMaterials() {
            $('#niwar-fixed-rows').empty();
            $('#niwar-fixed-section').hide();
            $('#niwar-auto-hint').removeClass('text-danger').text('');

            var niwarId = $('#belt_costing_id').find(':selected').data('niwar-id');
            var size = ($('#size_input').val() || '').trim();

            if (!niwarId || !niwarTypeData[niwarId]) {
                $('#niwar-groups-container').empty();
                return;
            }

            var data = niwarTypeData[niwarId];
            renderGroupBoxes(niwarId);

            if (size === '') {
                updateGroupTargets();
                return;
            }

            var requiredInch = data.sizeChart[size];

            if (requiredInch === undefined) {
                $('#niwar-auto-hint').addClass('text-danger')
                    .text('No inch mapping found for size "' + size + '" in Niwar Code ' + niwarLabel(data) + '. Add it via Niwar Code > Manage Details.');
                updateGroupTargets();
                return;
            }

            var meter = requiredInch / data.inchPerMeter;

            data.materials.forEach(function (m) {
                if (m.is_group) {
                    return;
                }
                var qty = Math.round(meter * m.gm_per_meter * 100) / 100;
                addAutoRow(m.material, qty, meter, m.gm_per_meter, requiredInch, niwarLabel(data), data.inchPerMeter);
            });

            $('#niwar-auto-hint').text('Niwar Code ' + niwarLabel(data) + ': size "' + size + '" = ' + requiredInch + '" ÷ ' + data.inchPerMeter + ' = ' + meter.toFixed(3) + 'm');
            updateGroupTargets();
        }

        function addAutoRow(materialId, qty, meter, gmPerMeter, requiredInch, label, inchPerMeter) {
            $('#niwar-fixed-section').show();
            var row = document.createElement('tr');
            row.className = 'niwar-auto-row';
            var breakdown = requiredInch + '" ÷ ' + inchPerMeter + ' = ' + meter.toFixed(3) + 'm × ' + gmPerMeter + 'gm/m = ' + qty + 'gm';
            row.innerHTML =
                '<td><select class="form-control" disabled><option>' + (rawMaterialNames[materialId] || 'Material #' + materialId) + '</option></select>' +
                '<input type="hidden" name="material[]" value="' + materialId + '"></td>' +
                '<td><input type="hidden" name="is_auto[]" value="1"><input type="hidden" name="niwar_group[]" value=""><input type="text" name="qty[]" class="form-control" value="' + qty + '" readonly><small class="text-muted">Auto (Niwar Code ' + label + '): ' + breakdown + '</small></td>' +
                '<td class="text-center"><i class="fa fa-lock text-muted" title="Auto-calculated from Niwar Code — single fixed material, cannot be added to"></i></td>';
            document.getElementById('niwar-fixed-rows').appendChild(row);
        }

        function renderGroupBoxes(niwarId) {
            var data = niwarTypeData[niwarId];
            var groupMaterials = (data.materials || []).filter(function (m) { return m.is_group; });

            var existingRows = {};
            $('.niwar-group-box').each(function () {
                var gid = $(this).data('group-id');
                var rows = [];
                $(this).find('.group-row').each(function () {
                    var mat = $(this).find('.group-material').val();
                    var qty = $(this).find('.group-qty').val();
                    if (mat || qty) {
                        rows.push({material: mat, qty: qty});
                    }
                });
                existingRows[gid] = rows;
            });

            $('#niwar-groups-container').empty();

            groupMaterials.forEach(function (gm) {
                var rows = existingRows[gm.id] || savedGroupItems[gm.id] || [];
                buildGroupBox(gm, rows, niwarLabel(data));
            });
        }

        function buildGroupBox(gm, rows, label) {
            var box = document.createElement('div');
            box.className = 'card-box niwar-group-box';
            box.setAttribute('data-group-id', gm.id);
            box.style.marginTop = '15px';
            box.style.border = '1px solid #ddd';

            var name = rawMaterialNames[gm.material] || ('Material #' + gm.material);
            box.innerHTML =
                '<h4>' + name + ' Group <small class="text-muted">(Niwar Code ' + label + ')</small> <small class="text-muted group-target-info"></small></h4>' +
                '<p class="text-muted">Fulfilled by multiple raw materials below — their total qty must exactly match the target (not less, not more).</p>' +
                '<table class="table table-bordered">' +
                '<thead><tr><th>Raw Material</th><th>Qty (per 1 Belt)</th><th style="width:8%;"></th></tr></thead>' +
                '<tbody class="group-rows"></tbody>' +
                '</table>' +
                '<button type="button" class="btn btn-default btn-sm add-group-row">+ Add Material</button>' +
                '<div class="group-sum-info" style="margin-top:8px;"></div>';

            document.getElementById('niwar-groups-container').appendChild(box);

            if (rows.length === 0) {
                rows = [{material: '', qty: ''}];
            }
            rows.forEach(function (r) {
                addGroupRow(box, gm.id, r.material, r.qty);
            });

            $(box).find('.add-group-row').on('click', function () {
                addGroupRow(box, gm.id, '', '');
                updateGroupTargets();
            });
        }

        function addGroupRow(box, groupId, materialId, qty) {
            var row = document.createElement('tr');
            row.className = 'group-row';
            row.innerHTML =
                '<td><select class="form-control js-example-basic-single group-material" name="material[]">' +
                '<option value="">Select raw material</option>' + materialOptions + '</select>' +
                '<input type="hidden" name="niwar_group[]" value="' + groupId + '">' +
                '<input type="hidden" name="is_auto[]" value="0"></td>' +
                '<td><input type="text" name="qty[]" class="form-control group-qty" value="' + (qty || '') + '"></td>' +
                '<td class="text-center"><a href="javascript:void(0)" class="remove-group-row" title="Remove"><i class="fa fa-trash"></i></a></td>';
            $(box).find('.group-rows').append(row);

            var $select = $(row).find('.group-material');
            $select.val(materialId || '');
            $select.select2();

            $(row).find('.remove-group-row').on('click', function () {
                var tbody = $(box).find('.group-rows')[0];
                if (tbody.rows.length > 1) {
                    row.remove();
                }
                updateGroupTargets();
            });
        }

        function updateGroupTargets() {
            var niwarId = $('#belt_costing_id').find(':selected').data('niwar-id');
            var size = ($('#size_input').val() || '').trim();
            var data = niwarId ? niwarTypeData[niwarId] : null;
            var requiredInch = (data && size !== '') ? data.sizeChart[size] : undefined;
            var meter = (requiredInch !== undefined) ? (requiredInch / data.inchPerMeter) : null;

            $('.niwar-group-box').each(function () {
                var $box = $(this);
                var gid = $box.data('group-id');
                var gmData = data ? data.materials.find(function (m) { return m.id == gid; }) : null;

                var sum = 0;
                $box.find('.group-qty').each(function () {
                    sum += parseFloat($(this).val()) || 0;
                });
                sum = Math.round(sum * 100) / 100;

                if (!gmData || meter === null) {
                    $box.find('.group-target-info').removeClass('text-success text-danger').addClass('text-muted')
                        .text('(target unavailable — select a size with an inch mapping)');
                    $box.find('.group-sum-info').removeClass('text-success text-danger')
                        .text('Current total: ' + sum + 'gm');
                    return;
                }

                var target = Math.round(meter * gmData.gm_per_meter * 100) / 100;
                var diff = Math.round((target - sum) * 100) / 100;
                $box.find('.group-target-info').removeClass('text-muted')
                    .text('— Target: ' + target + 'gm (' + requiredInch + '" ÷ ' + data.inchPerMeter + ' = ' + meter.toFixed(3) + 'm × ' + gmData.gm_per_meter + 'gm/m)');

                if (Math.abs(diff) <= 0.01) {
                    $box.find('.group-sum-info').removeClass('text-danger').addClass('text-success')
                        .text('✓ Current total: ' + sum + 'gm — matches target.');
                } else {
                    $box.find('.group-sum-info').removeClass('text-success').addClass('text-danger')
                        .text('✗ Current total: ' + sum + 'gm — must be ' + target + 'gm (' + (diff > 0 ? 'short by ' + diff : 'over by ' + Math.abs(diff)) + 'gm).');
                }
            });
        }

        $('#belt_costing_id').on('change', recalcNiwarMaterials);
        $('#size_input').on('input blur', recalcNiwarMaterials);
        $(document).on('input', '.group-qty', updateGroupTargets);

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
                '<td><input type="hidden" name="is_auto[]" value="0"><input type="hidden" name="niwar_group[]" value=""><input type="text" name="qty[]" class="form-control"><small class="uom-hint text-muted"></small></td>' +
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
