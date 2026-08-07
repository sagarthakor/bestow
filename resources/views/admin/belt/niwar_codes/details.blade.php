@extends('admin.layout.master_material')

@section('title', 'Manage Details | Niwar Type')

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
                            <h4 class="page-title">Manage Details — Niwar Type: {{ $niwar->type }} ({{ $niwar->code }})</h4>
                            <ol class="breadcrumb p-0 m-0">
                                <li><a href="{{ url('admin') }}">{{ Session::get('software_title') }}</a></li>
                                <li><a href="{{ route('admin.niwar.list') }}">Niwar Code List</a></li>
                                <li>Manage Details</li>
                            </ol>
                            <div class="clearfix"></div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xs-12">
                        {{ Form::open(['method' => 'post', 'route' => ['admin.niwar.save_details', $niwar->id]]) }}
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
                            @if(session()->has('success'))
                                <div class="alert alert-info" style="background-color:#188ae2!important;">
                                    <strong style="color:#fff">{{ session()->get('success') }}</strong>
                                </div>
                            @endif

                            <h4>Meter Conversion</h4>
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="control-label">1 Meter = How Many Inches?</label>
                                        <input type="text" name="inch_per_meter" value="{{ $niwar->inch_per_meter ?? 39.37 }}" class="form-control">
                                        <small class="text-muted">Standard is 39.37. Change only if this niwar type uses a different rounding on the floor.</small>
                                    </div>
                                </div>
                            </div>

                            <h4>Raw Material Rate (per 1 Meter of Niwar)</h4>
                            <p class="text-muted">Used to auto-calculate belt size-wise material qty in Belt Formula Master (e.g. MONO 10gm, 300/ROTO PP 8.49gm). Check "Is Group" for a rate that is fulfilled by multiple raw materials in Belt Formula (e.g. 300/ROTO is a mix of different dhaga colors) &mdash; their qty must sum up to exactly this gm/meter rate.</p>

                            <table class="table table-bordered" id="materialtable">
                                <thead>
                                <tr>
                                    <th>Raw Material</th>
                                    <th>Gm per Meter</th>
                                    <th>Is Group</th>
                                    <th style="width:8%;"></th>
                                </tr>
                                </thead>
                                <tbody id="material-rows">
                                @forelse($niwar->materials as $m)
                                    <tr>
                                        <td>
                                            <select name="material[]" class="form-control js-example-basic-single">
                                                <option value="">Select raw material</option>
                                                @foreach($rawmaterial as $mat)
                                                    <option value="{{ $mat->id }}" {{ $mat->id == $m->material ? 'selected' : '' }}>{{ \App\product::nameWithVariantInline($mat->product_name, $mat->value1 ?? null, $mat->value2 ?? null) }}</option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td>
                                            <input type="text" name="gm_per_meter[]" value="{{ $m->gm_per_meter }}" class="form-control">
                                        </td>
                                        <td class="text-center">
                                            <input type="hidden" name="is_group[]" value="{{ $m->is_group ? 1 : 0 }}" class="is-group-hidden">
                                            <input type="checkbox" onchange="this.previousElementSibling.value = this.checked ? 1 : 0" {{ $m->is_group ? 'checked' : '' }}>
                                        </td>
                                        <td class="text-center">
                                            <a href="javascript:void(0)" onclick="removeRow(this)" title="Remove"><i class="fa fa-trash"></i></a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td>
                                            <select name="material[]" class="form-control js-example-basic-single">
                                                <option value="">Select raw material</option>
                                                @foreach($rawmaterial as $mat)
                                                    <option value="{{ $mat->id }}">{{ \App\product::nameWithVariantInline($mat->product_name, $mat->value1 ?? null, $mat->value2 ?? null) }}</option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td>
                                            <input type="text" name="gm_per_meter[]" class="form-control">
                                        </td>
                                        <td class="text-center">
                                            <input type="hidden" name="is_group[]" value="0" class="is-group-hidden">
                                            <input type="checkbox" onchange="this.previousElementSibling.value = this.checked ? 1 : 0">
                                        </td>
                                        <td class="text-center">
                                            <a href="javascript:void(0)" onclick="removeRow(this)" title="Remove"><i class="fa fa-trash"></i></a>
                                        </td>
                                    </tr>
                                @endforelse
                                </tbody>
                            </table>

                            <button type="button" id="add_material" class="btn btn-default">+ Add Raw Material</button>

                            <hr>

                            <h4>PP Size &rarr; Required Niwar Inch Chart</h4>
                            <p class="text-muted">e.g. PP Size 28 requires 30 inch of niwar cut length.</p>

                            <table class="table table-bordered" id="sizetable">
                                <thead>
                                <tr>
                                    <th>P.P Size</th>
                                    <th>As Per Size Required (Niwar) Inch</th>
                                    <th style="width:8%;"></th>
                                </tr>
                                </thead>
                                <tbody id="size-rows">
                                @forelse($niwar->sizeChart as $s)
                                    <tr>
                                        <td><input type="text" name="pp_size[]" value="{{ $s->pp_size }}" class="form-control"></td>
                                        <td><input type="text" name="required_inch[]" value="{{ $s->required_inch }}" class="form-control"></td>
                                        <td class="text-center">
                                            <a href="javascript:void(0)" onclick="removeRow(this)" title="Remove"><i class="fa fa-trash"></i></a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td><input type="text" name="pp_size[]" class="form-control"></td>
                                        <td><input type="text" name="required_inch[]" class="form-control"></td>
                                        <td class="text-center">
                                            <a href="javascript:void(0)" onclick="removeRow(this)" title="Remove"><i class="fa fa-trash"></i></a>
                                        </td>
                                    </tr>
                                @endforelse
                                </tbody>
                            </table>

                            <button type="button" id="add_size" class="btn btn-default">+ Add Size</button>

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
        });

        var materialOptions = `@foreach($rawmaterial as $mat)<option value="{{ $mat->id }}">{{ \App\product::nameWithVariantInline($mat->product_name, $mat->value1 ?? null, $mat->value2 ?? null) }}</option>@endforeach`;

        document.getElementById('add_material').addEventListener('click', function () {
            var row = document.createElement('tr');
            row.innerHTML =
                '<td><select name="material[]" class="form-control js-example-basic-single">' +
                '<option value="">Select raw material</option>' + materialOptions + '</select></td>' +
                '<td><input type="text" name="gm_per_meter[]" class="form-control"></td>' +
                '<td class="text-center"><input type="hidden" name="is_group[]" value="0" class="is-group-hidden">' +
                '<input type="checkbox" onchange="this.previousElementSibling.value = this.checked ? 1 : 0"></td>' +
                '<td class="text-center"><a href="javascript:void(0)" onclick="removeRow(this)" title="Remove"><i class="fa fa-trash"></i></a></td>';
            document.getElementById('material-rows').appendChild(row);
            $(row).find('.js-example-basic-single').select2();
        });

        document.getElementById('add_size').addEventListener('click', function () {
            var row = document.createElement('tr');
            row.innerHTML =
                '<td><input type="text" name="pp_size[]" class="form-control"></td>' +
                '<td><input type="text" name="required_inch[]" class="form-control"></td>' +
                '<td class="text-center"><a href="javascript:void(0)" onclick="removeRow(this)" title="Remove"><i class="fa fa-trash"></i></a></td>';
            document.getElementById('size-rows').appendChild(row);
        });

        function removeRow(el) {
            var tbody = el.closest('tbody');
            if (tbody.rows.length > 1) {
                el.closest('tr').remove();
            }
        }
    </script>

@endsection
