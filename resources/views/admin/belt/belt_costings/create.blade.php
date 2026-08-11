@extends('admin.layout.master_material')

@section('title', 'Add Belt Costing')

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
                            <h4 class="page-title">Add Belt Costing</h4>
                            <ol class="breadcrumb p-0 m-0">
                                <li><a href="{{ url('admin') }}">{{ Session::get('software_title') }}</a></li>
                                <li><a href="{{ route('admin.belt.list') }}">Belt Costing List</a></li>
                                <li>Add Belt Costing</li>
                            </ol>
                            <div class="clearfix"></div>
                        </div>
                    </div>
                </div>

                @if ($errors->any())
                    <div class="alert alert-danger"><ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul></div>
                @endif

                <div class="card-box">

                    {!! Form::open(['route' => 'admin.belt.store', 'method' => 'POST']) !!}

                    <div class="row">

                        <div class="col-md-12">
                            <table class="table table-bordered">
                                <thead>
                                <tr>
                                    <th>Bukkal Type</th>
                                    <th>Bukkal Code</th>
                                    <th>Bukkal Rate</th>
                                    <th>Miter (1Miter=39 inch)</th>
                                    <th>Kadi / Slider</th>
                                    <th>Kadi / Slider Rate</th>
                                    <th>Niwar Type</th>
                                    <th>Rate</th>
                                    <th>Size Lable</th>
                                    <th>Panni Packing Rate</th>
                                    <th>Total Costing</th>
                                </tr>
                                </thead>
                                <tr>
                                    <td>
                                        <select name="bukkal_id" id="bukkal_id" class="form-control" required>
                                            <option value="">Select</option>
                                            @foreach($bukkal as $b)
                                                <option value="{{ $b->id }}">{{ $b->type }}</option>
                                            @endforeach
                                        </select>
                                    </td>

                                    <td>
                                        <input type="text" name="bukkal_code" id="bukkal_code" class="form-control" required>
                                    </td>

                                    <td>
                                        <input type="text" name="bukkal_rate" id="bukkal_rate" class="form-control" required>
                                    </td>

                                    <td>
                                        <input type="text" name="miter" id="miter" class="form-control" value="1" required>
                                    </td>

                                    <td>
                                        <input type="text" name="kadi_qty" id="kadi_qty" class="form-control" value="1" required>
                                    </td>

                                    <td>
                                        <input type="text" name="kadi_rate" id="kadi_rate" class="form-control" value="1" required>
                                    </td>

                                    <td>
                                        <select name="niwar_id" id="niwar_id" class="form-control" required>
                                            <option value="">Select</option>
                                            @foreach($niwar as $n)
                                                <option value="{{ $n->id }}">{{ $n->type }}</option>
                                            @endforeach
                                        </select>
                                    </td>

                                    <td>
                                        <input type="text" name="niwar_rate" id="niwar_rate" class="form-control" required>
                                    </td>

                                    <td>
                                        <input type="text" name="size_label" id="size_label" class="form-control" required>
                                    </td>

                                    <td>
                                        <input type="text" name="panni_packaging_rate" id="panni_packaging_rate" class="form-control" required>
                                    </td>

                                    <td>
                                        <input type="text" name="total_costing" id="total_costing" class="form-control" required>
                                    </td>
                                </tr>

                                <tbody>
                                </tbody>
                            </table>
                        </div>


                        <div class="col-md-12" style="margin-top:25px;">
                            <h4>Fitting Consumed Per Belt <small class="text-muted">(used by Belt Cutting)</small></h4>
                            <p class="text-muted">
                                The rates above price a belt; this list says which products a belt is actually
                                fitted with, so Belt Cutting can take them out of stock. Add a row per component
                                &mdash; bukkal, kadi, slider, rivet, packaging, whatever this belt takes.
                            </p>
                            <table class="table table-bordered" id="fittingTable">
                                <thead>
                                <tr>
                                    <th style="width:22%;">Component</th>
                                    <th>Product</th>
                                    <th style="width:18%;">Qty Per Belt</th>
                                    <th style="width:6%;"></th>
                                </tr>
                                </thead>
                                <tbody id="fitting-rows">
                                @foreach(($item->fittings ?? collect()) as $fit)
                                    <tr>
                                        <td><input type="text" name="fitting_label[]" class="form-control" value="{{ $fit->label }}" placeholder="e.g. Bukkal"></td>
                                        <td>
                                            <select name="fitting_product[]" class="form-control js-example-basic-single">
                                                <option value="">-- remove this row --</option>
                                                @foreach($fittingProducts as $fp)
                                                    <option value="{{ $fp->id }}" {{ $fit->product == $fp->id ? 'selected' : '' }}>
                                                        {{ \App\product::nameWithVariantInline($fp->product_name, $fp->value1, $fp->value2) }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td><input type="text" name="fitting_qty[]" class="form-control" value="{{ $fit->qty }}"></td>
                                        <td class="text-center"><a href="javascript:void(0)" class="remove-fitting" title="Remove"><i class="fa fa-trash"></i></a></td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                            <button type="button" class="btn btn-default btn-sm" id="add_fitting">+ Add Component</button>
                        </div>

                        <div class="col-md-12 text-center" style="margin-top:20px;">
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </div>

                    </div>

                    {!! Form::close() !!}

                </div>

            </div>
        </div>
    </div>

@endsection
@section('import-javascript')
    <script>
        function calculateTotal() {

            let bukkal_rate   = parseFloat($('#bukkal_rate').val()) || 0;
            let kadi_qty      = parseFloat($('#kadi_qty').val()) || 0;
            let kadi_rate     = parseFloat($('#kadi_rate').val()) || 0;
            let niwar_rate    = parseFloat($('#niwar_rate').val()) || 0;
            let size_label    = parseFloat($('#size_label').val()) || 0;
            let panni_pack    = parseFloat($('#panni_packaging_rate').val()) || 0;

            // total = bukkal + (kadi_qty × kadi_rate) + niwar_rate + size_label + panni_pack
            let total = bukkal_rate + (kadi_qty * kadi_rate) + niwar_rate + size_label + panni_pack;

            $('#total_costing').val(total.toFixed(2));
        }

        // 🟢 Trigger on any input change
        $('#bukkal_rate, #kadi_qty, #kadi_rate, #niwar_rate, #size_label, #panni_packaging_rate')
            .on('keyup change', calculateTotal);

        // 🟢 Trigger after AJAX auto-fill (bukkal)
        $('#bukkal_id').on('change', function () {
            let id = $(this).val();
            if (id) {
                $.ajax({
                    url: "/admin/get-bukkal-data/" + id,
                    type: "GET",
                    success: function (res) {
                        $('#bukkal_rate').val(res.rate);
                        $('#bukkal_code').val(res.code);
                        calculateTotal(); // recalc
                    }
                });
            }
        });

        // 🟢 Trigger after AJAX auto-fill (niwar)
        $('#niwar_id').on('change', function () {
            let id = $(this).val();
            if (id) {
                $.ajax({
                    url: "/admin/get-niwar-data/" + id,
                    type: "GET",
                    success: function (res) {
                        $('#niwar_rate').val(res.rate);
                        calculateTotal(); // recalc
                    }
                });
            }
        });
    </script>


    <script>
        // Repeatable fitting rows. A row whose product is cleared is simply not
        // saved, which is how a component is removed without a delete endpoint.
        var fittingProductOptions = `<option value="">-- remove this row --</option>@foreach($fittingProducts as $fp)<option value="{{ $fp->id }}">{{ \App\product::nameWithVariantInline($fp->product_name, $fp->value1, $fp->value2) }}</option>@endforeach`;

        function addFittingRow(label, qty) {
            var row = $('<tr></tr>').html(
                '<td><input type="text" name="fitting_label[]" class="form-control" value="' + (label || '') + '" placeholder="e.g. Bukkal"></td>' +
                '<td><select name="fitting_product[]" class="form-control">' + fittingProductOptions + '</select></td>' +
                '<td><input type="text" name="fitting_qty[]" class="form-control" value="' + (qty || 1) + '"></td>' +
                '<td class="text-center"><a href="javascript:void(0)" class="remove-fitting" title="Remove"><i class="fa fa-trash"></i></a></td>'
            );
            $('#fitting-rows').append(row);
            row.find('select').select2();
        }

        $(document).ready(function () {
            $('#add_fitting').on('click', function () { addFittingRow('', 1); });
            $(document).on('click', '.remove-fitting', function () { $(this).closest('tr').remove(); });

            if ($('#fitting-rows tr').length === 0) {
                addFittingRow('Bukkal', 1);
                addFittingRow('Kadi', 1);
            }
        });
    </script>

@endsection
