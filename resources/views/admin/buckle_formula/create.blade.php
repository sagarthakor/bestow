@extends('admin.layout.master_material')

@section('title', 'Add New | Belt Formula')

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
                            <h4 class="page-title">Create Belt Formula</h4>
                            <ol class="breadcrumb p-0 m-0">
                                <li><a href="{{ url('admin') }}">{{Session::get('software_title')}}</a></li>
                                <li><a href="{{ route('admin.production.buckle_formula_list') }}">Belt Formula List</a></li>
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

                            @include('admin.buckle_formula._header_fields', ['formula' => null, 'product' => $product, 'beltCosting' => $beltCosting])

                            @include('admin.buckle_formula._roll_info')

                            <h4 style="margin-top:20px;">Fitting Material <small class="text-muted">(per 1 belt &mdash; bukkal, kadi, slider, panni packing)</small></h4>
                            <p class="text-muted">Dhaga is not entered here any more &mdash; it is consumed when the roll is woven, in Roll Production.</p>

                            <table class="table table-bordered" id="caltable">
                                <thead>
                                <tr>
                                    <th>Raw Material</th>
                                    <th style="width:25%;">Qty (per 1 Belt)</th>
                                    <th style="width:8%;"></th>
                                </tr>
                                </thead>
                                <tbody id="material-rows">
                                    <tr>
                                        <td>
                                            <select name="material[]" class="form-control js-example-basic-single material-select" onchange="updateUomHint(this)">
                                                <option value="">Select raw material</option>
                                                @foreach($rawmaterial as $mat)
                                                    <option value="{{ $mat->id }}" data-uom="{{ strtoupper($mat->uomName->uom_name ?? '') }}">{{ \App\product::nameWithVariantInline($mat->product_name, $mat->value1 ?? null, $mat->value2 ?? null) }}</option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td>
                                            <input type="text" name="qty[]" class="form-control">
                                            <small class="uom-hint text-muted"></small>
                                        </td>
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
    @include('admin.partials._product_photo')
    @include('admin.buckle_formula._form_scripts', ['rawmaterial' => $rawmaterial, 'niwarTypes' => $niwarTypes])

@endsection
