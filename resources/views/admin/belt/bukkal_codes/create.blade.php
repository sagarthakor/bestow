@extends('admin.layout.master_material')

@section('title', 'Add Bukkal Code')

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
                            <h4 class="page-title">Add Bukkal Code</h4>
                            <ol class="breadcrumb p-0 m-0">
                                <li><a href="{{ url('admin') }}">{{ Session::get('software_title') }}</a></li>
                                <li><a href="{{ route('admin.bukkal.list') }}">Bukkal Code List</a></li>
                                <li>Add</li>
                            </ol>
                            <div class="clearfix"></div>
                        </div>
                    </div>
                </div>

                @if($errors->any())
                    <div class="alert alert-danger"><ul>
                            @foreach($errors->all() as $e) <li>{{ $e }}</li> @endforeach
                        </ul></div>
                @endif

                <div class="card-box">

                    {!! Form::open(['route' => 'admin.bukkal.store', 'method' => 'POST']) !!}

                    <div class="row">

                        <div class="col-md-4">
                            <label><strong>Bukkal Type</strong></label>
                            <input type="text" name="type" class="form-control" required>
                        </div>

                        <div class="col-md-4">
                            <label><strong>Code</strong></label>
                            <input type="text" name="code" class="form-control" required>
                        </div>

                        <div class="col-md-4">
                            <label><strong>Rate</strong></label>
                            <input type="text" name="rate" class="form-control">
                        </div>

                        <div class="col-md-12 text-center" style="margin-top:20px;">
                            <button class="btn btn-primary">Submit</button>
                        </div>

                    </div>

                    {!! Form::close() !!}

                </div>

            </div>
        </div>
    </div>

@endsection
