@extends('admin.layout.master_material')

@section('title', 'Edit Belt Costing')

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
                            <h4 class="page-title">Edit Belt Costing</h4>
                            <ol class="breadcrumb p-0 m-0">
                                <li><a href="{{ url('admin') }}">{{ Session::get('software_title') }}</a></li>
                                <li><a href="{{ route('admin.belt.list') }}">Belt Costing List</a></li>
                                <li>Edit Belt Costing</li>
                            </ol>
                            <div class="clearfix"></div>
                        </div>
                    </div>
                </div>

                <div class="card-box">

                    {!! Form::model($item, ['route' => ['admin.belt.update', $item->id], 'method' => 'POST']) !!}
                    <div class="row">

                        <div class="col-md-12">
                            <table class="table table-bordered text-center">
                                <thead style="background:#ffff66;">
                                <tr>
                                    <th>Bukkal Type</th>
                                    <th>Bukkal Code</th>
                                    <th>Bukkal Rate</th>
                                    <th>Miter</th>
                                    <th>Kadi Qty</th>
                                    <th>Kadi Rate</th>
                                    <th>Niwar Type</th>
                                    <th>Niwar Rate</th>
                                    <th>Size Label</th>
                                    <th>Panni Packing</th>
                                    <th>Total Costing</th>
                                </tr>
                                </thead>

                                <tbody>
                                <tr>

                                    {{-- Bukkal Dropdown --}}
                                    <td>
                                        <select name="bukkal_id" id="bukkal_id" class="form-control">
                                            <option value="">Select</option>
                                            @foreach($bukkal as $b)
                                                <option value="{{ $b->id }}"
                                                    {{ $item->bukkal_id == $b->id ? 'selected' : '' }}>
                                                    {{ $b->type }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </td>

                                    {{-- Auto Bukkal Code --}}
                                    <td>
                                        <input type="text" name="bukkal_code" id="bukkal_code"
                                               value="{{ $item->bukkal_code }}" class="form-control">
                                    </td>

                                    {{-- Auto Bukkal Rate --}}
                                    <td>
                                        <input type="text" name="bukkal_rate" id="bukkal_rate"
                                               value="{{ $item->bukkal_rate }}" class="form-control">
                                    </td>

                                    <td><input type="text" id="miter" name="miter" class="form-control" value="{{ $item->miter }}"></td>
                                    <td><input type="text" id="kadi_qty" name="kadi_qty" class="form-control" value="{{ $item->kadi_qty }}"></td>
                                    <td><input type="text" id="kadi_rate" name="kadi_rate" class="form-control" value="{{ $item->kadi_rate }}"></td>

                                    {{-- Niwar Dropdown --}}
                                    <td>
                                        <select name="niwar_id" id="niwar_id" class="form-control">
                                            <option value="">Select</option>
                                            @foreach($niwar as $n)
                                                <option value="{{ $n->id }}"
                                                    {{ $item->niwar_id == $n->id ? 'selected' : '' }}>
                                                    {{ $n->type }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </td>

                                    {{-- Auto Niwar Rate --}}
                                    <td>
                                        <input type="text" name="niwar_rate" id="niwar_rate"
                                               value="{{ $item->niwar_rate }}" class="form-control">
                                    </td>

                                    <td><input type="text" id="size_label" name="size_label" class="form-control" value="{{ $item->size_label }}"></td>

                                    <td><input type="text" id="panni_packaging_rate" name="panni_packaging_rate" class="form-control" value="{{ $item->panni_packing }}"></td>

                                    {{-- Total --}}
                                    <td>
                                        <input type="text" id="total_costing" name="total_costing"
                                               class="form-control" value="{{ $item->total_cost }}" readonly>
                                    </td>

                                </tr>
                                </tbody>

                            </table>
                        </div>

                        <div class="col-md-12 text-center" style="margin-top:20px;">
                            <button type="submit" class="btn btn-primary">Update</button>
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
        // Auto-calc total
        function calculateTotal() {

            let bukkal_rate = parseFloat($('#bukkal_rate').val()) || 0;
            let kadi_qty = parseFloat($('#kadi_qty').val()) || 0;
            let kadi_rate = parseFloat($('#kadi_rate').val()) || 0;
            let niwar_rate = parseFloat($('#niwar_rate').val()) || 0;
            let size_label = parseFloat($('#size_label').val()) || 0;
            let panni_pack = parseFloat($('#panni_packaging_rate').val()) || 0;

            let total = bukkal_rate + (kadi_qty * kadi_rate) + niwar_rate + size_label + panni_pack;

            $('#total_costing').val(total.toFixed(2));
        }

        // On input change
        $('#bukkal_rate, #kadi_qty, #kadi_rate, #niwar_rate, #size_label, #panni_packaging_rate')
            .on('keyup change', calculateTotal);


        // Bukkal Ajax
        $('#bukkal_id').on('change', function () {
            let id = $(this).val();
            if (id) {
                $.ajax({
                    url: "/admin/get-bukkal-data/" + id,
                    type: "GET",
                    success: function (res) {
                        $('#bukkal_code').val(res.code);
                        $('#bukkal_rate').val(res.rate);
                        calculateTotal();
                    }
                });
            }
        });

        // Niwar Ajax
        $('#niwar_id').on('change', function () {
            let id = $(this).val();
            if (id) {
                $.ajax({
                    url: "/admin/get-niwar-data/" + id,
                    type: "GET",
                    success: function (res) {
                        $('#niwar_rate').val(res.rate);
                        calculateTotal();
                    }
                });
            }
        });
    </script>
@endsection
