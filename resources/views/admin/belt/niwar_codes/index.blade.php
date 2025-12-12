@extends('admin.layout.table_master')

@section('title', 'Niwar Code List')

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
                            <h4 class="page-title">{{ Session::get('software_title') }}</h4>
                            <ol class="breadcrumb p-0 m-0">
                                <li><a href="#">{{ Session::get('software_title') }}</a></li>
                                <li class="active">Niwar Code List</li>
                                <li style="text-align:right;margin-bottom:5px;">
                                    <a class="btn btn-primary" href="{{ route('admin.niwar.add') }}">Add New</a>
                                </li>
                            </ol>
                        </div>
                    </div>
                </div>

                @if(session()->has('success'))
                    <div class="alert alert-info" style="background-color:#188ae2!important;">
                        <strong style="color:#fff">{{ session()->get('success') }}</strong>
                    </div>
                @endif

                <div class="card-box table-responsive">
                    <table class="table table-striped table-bordered" width="100%">
                        <thead>
                        <tr>
                            <th>Sr</th>
                            <th>Niwar Type</th>
                            <th>Code</th>
                            <th>Rate</th>
                            <th>Action</th>
                        </tr>
                        </thead>

                        <tbody>
                        @php $sr = 0; @endphp
                        @foreach($items as $n)
                            @php $sr++; @endphp
                            <tr>
                                <td>{{ $sr }}</td>
                                <td>{{ $n->type }}</td>
                                <td>{{ $n->code }}</td>
                                <td>{{ $n->rate }}</td>

                                <td>
                                    <div class="dropdown">
                                        <button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown">
                                            Action <span class="caret"></span>
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li><a href="{{ route('admin.niwar.edit', $n->id) }}">Edit</a></li>
                                            <li><a href="{{ route('admin.niwar.delete', $n->id) }}" onclick="return confirm('Delete?')">Delete</a></li>
                                        </ul>
                                    </div>
                                </td>

                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>

@endsection
