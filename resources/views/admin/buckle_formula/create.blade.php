@extends('admin.layout.master_material')

@section('title', 'Add New | Buckle Formula')

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
                            <h4 class="page-title">Create Buckle Formula</h4>
                            <ol class="breadcrumb p-0 m-0">
                                <li><a href="{{ url('admin') }}">{{Session::get('software_title')}}</a></li>
                                <li><a href="{{ route('admin.production.buckle_formula_list') }}">Buckle Formula List</a></li>
                                <li>Create New</li>
                            </ol>
                            <div class="clearfix"></div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xs-12">
                        {{ Form::open(['method' => 'post', 'route' => 'admin.production.buckle_formula_store']) }}
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
                                        <select name="product" class="form-control js-example-basic-single" required>
                                            <option value="">Select belt product</option>
                                            @foreach($product as $prod)
                                                <option value="{{ $prod->id }}">{{ $prod->product_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="control-label">Size</label>
                                        {{ Form::text('size', null, ['class' => 'form-control', 'placeholder' => 'e.g. 30', 'required']) }}
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="control-label">Nos</label>
                                        {{ Form::text('nos', 1, ['class' => 'form-control']) }}
                                    </div>
                                </div>
                            </div>

                            <h4>Raw Material Required (per 1 Belt)</h4>

                            <table class="table table-bordered" id="caltable">
                                <thead>
                                <tr>
                                    <th>Raw Material</th>
                                    <th>Qty</th>
                                    <th style="width:8%;"></th>
                                </tr>
                                </thead>
                                <tbody id="material-rows">
                                    <tr>
                                        <td>
                                            <select name="material[]" class="form-control js-example-basic-single">
                                                <option value="">Select raw material</option>
                                                @foreach($rawmaterial as $mat)
                                                    <option value="{{ $mat->id }}">{{ $mat->product_name }}</option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td><input type="text" name="qty[]" class="form-control"></td>
                                        <td class="text-center">
                                            <a href="javascript:void(0)" onclick="removeRow(this)" title="Remove"><i class="fa fa-trash"></i></a>
                                        </td>
                                    </tr>
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
        });

        var materialOptions = `@foreach($rawmaterial as $mat)<option value="{{ $mat->id }}">{{ $mat->product_name }}</option>@endforeach`;

        document.getElementById('add_rawmaterial').addEventListener('click', function () {
            var row = document.createElement('tr');
            row.innerHTML =
                '<td><select name="material[]" class="form-control js-example-basic-single">' +
                '<option value="">Select raw material</option>' + materialOptions + '</select></td>' +
                '<td><input type="text" name="qty[]" class="form-control"></td>' +
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
