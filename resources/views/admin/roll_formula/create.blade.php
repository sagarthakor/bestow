@extends('admin.layout.master_material')

@section('title', 'Add New | Roll Formula')

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
                            <h4 class="page-title">Create Roll Formula</h4>
                            <ol class="breadcrumb p-0 m-0">
                                <li><a href="{{ url('admin') }}">{{Session::get('software_title')}}</a></li>
                                <li><a href="{{ route('admin.roll_formula.list') }}">Roll Formula</a></li>
                                <li>Create New</li>
                            </ol>
                            <div class="clearfix"></div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xs-12">
                        {{ Form::open(['method' => 'post', 'route' => 'admin.roll_formula.store', 'id' => 'rollFormulaForm']) }}
                        <div class="card-box">
                            @include('admin.roll_formula._form', ['data' => null])
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

@include('admin.roll_formula._scripts', ['savedRows' => [], 'data' => null])

@endsection
