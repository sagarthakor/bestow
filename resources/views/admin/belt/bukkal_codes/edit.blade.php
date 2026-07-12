@extends('admin.layout.master')

@section('title', 'Edit Bukkal Code')

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
                            <h4 class="page-title">Edit Bukkal Code</h4>
                            <ol class="breadcrumb p-0 m-0">
                                <li><a href="{{ url('admin') }}">{{ Session::get('software_title') }}</a></li>
                                <li><a href="{{ route('admin.bukkal.list') }}">Bukkal Code List</a></li>
                                <li>Edit</li>
                            </ol>
                        </div>
                    </div>
                </div>

                <div class="card-box">

                    {!! Form::model($item, ['route' => ['admin.bukkal.update', $item->id], 'method' => 'POST']) !!}
                    <div class="row">

                        <div class="col-md-4">
                            <label>Bukkal Type</label>
                            <input type="text" name="type" class="form-control" value="{{ $item->type }}" required>
                        </div>

                        <div class="col-md-4">
                            <label>Code</label>
                            <input type="text" name="code" class="form-control" value="{{ $item->code }}" required>
                        </div>

                        <div class="col-md-4">
                            <label>Rate</label>
                            <input type="text" name="rate" class="form-control" value="{{ $item->rate }}">
                        </div>

                        <div class="col-md-12 text-center" style="margin-top:20px;">
                            <button class="btn btn-primary">Update</button>
                        </div>

                    </div>
                    {!! Form::close() !!}

                </div>

            </div>
        </div>
    </div>

@endsection
