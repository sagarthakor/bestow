@extends('admin.layout.master_material')

@section('title', 'Update | Roll Formula')

@section('sidebar')
    @parent
@endsection

@section('content')

    @php
        // Saved rows keyed by niwar category, for the script to reinstate once
        // the category tables have been fetched.
        $savedRows = $data->items->groupBy('niwar_type_material_id')->map(function ($rows) {
            return $rows->map(fn ($r) => ['material' => $r->material, 'gm_per_meter' => (float) $r->gm_per_meter])->values();
        });
    @endphp

    <div class="content-page">
        <div class="content">
            <div class="container">

                <div class="row">
                    <div class="col-xs-12">
                        <div class="page-title-box">
                            <h4 class="page-title">Update Roll Formula</h4>
                            <ol class="breadcrumb p-0 m-0">
                                <li><a href="{{ url('admin') }}">{{Session::get('software_title')}}</a></li>
                                <li><a href="{{ route('admin.roll_formula.list') }}">Roll Formula</a></li>
                                <li>Update</li>
                            </ol>
                            <div class="clearfix"></div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xs-12">
                        {{ Form::open(['method' => 'post', 'route' => 'admin.roll_formula.update', 'id' => 'rollFormulaForm']) }}
                        <input type="hidden" name="id" value="{{ $data->id }}">
                        <div class="card-box">
                            @include('admin.roll_formula._form', ['data' => $data])
                        </div>
                        {{ Form::close() }}
                    </div>
                </div>

            </div>
        </div>
    </div>

@endsection

@section('import-javascript')

@include('admin.partials._product_photo')

@include('admin.roll_formula._scripts', ['savedRows' => $savedRows, 'data' => $data])

@endsection
