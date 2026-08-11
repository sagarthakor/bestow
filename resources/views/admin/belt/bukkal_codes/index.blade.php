@extends('admin.layout.table_master_material')

@section('title', 'Bukkal Code List')

@section('sidebar')
    @parent
@endsection

@section('content')

    @include('admin.belt._list_styles')

    <div class="content-page">
        <div class="content">
            <div class="container">

                <div class="row">
                    <div class="col-xs-12">
                        <div class="page-title-box">
                            <h4 class="page-title">Bukkal Code</h4>
                            <ol class="breadcrumb p-0 m-0">
                                <li><a href="{{ url('admin') }}">{{ Session::get('software_title') }}</a></li>
                                <li>Belt</li>
                                <li class="active">Bukkal Code</li>
                                <li style="text-align:right;margin-bottom:5px;">
                                    <a class="btn btn-primary" href="{{ route('admin.bukkal.add') }}">Add New</a>
                                </li>
                            </ol>
                            <div class="clearfix"></div>
                        </div>
                    </div>
                </div>

                @if(session()->has('success'))
                    <div class="alert alert-info" style="background-color:#188ae2!important;">
                        <strong style="color:#fff">{{ session()->get('success') }}</strong>
                    </div>
                @endif

                <div class="row">
                    <div class="col-sm-12">
                        <div class="card-box table-responsive">

                            <table class="table table-striped belt-table dt-responsive nowrap" width="100%">
                                <thead>
                                <tr>
                                    <th>Sr</th>
                                    <th>Bukkal Type</th>
                                    <th>Code</th>
                                    <th>Rate</th>
                                    <th>Action</th>
                                </tr>
                                </thead>

                                <tbody>
                                @php $sr = 0; @endphp
                                @foreach($items as $b)
                                    @php $sr++; @endphp
                                    <tr>
                                        <td class="sr">{{ $sr }}</td>
                                        <td><b>{{ $b->type }}</b></td>
                                        <td>{{ $b->code }}</td>
                                        <td class="num">{{ number_format($b->rate, 2) }}</td>

                                        <td class="belt-actions-cell">
                                            <a class="belt-act" href="{{ route('admin.bukkal.edit', $b->id) }}"><i class="mdi mdi-pencil"></i>Edit</a>
                                            <a class="belt-act belt-act-danger" href="{{ route('admin.bukkal.delete', $b->id) }}"
                                               onclick="return confirm('Delete bukkal code {{ $b->type }}/{{ $b->code }}?')"><i class="mdi mdi-delete"></i>Delete</a>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>

                            </table>

                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

@endsection
