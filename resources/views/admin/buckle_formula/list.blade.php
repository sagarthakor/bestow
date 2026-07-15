@extends('admin.layout.master_material')

@section('title', 'List | Buckle Formula')

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
                            <h4 class="page-title">Buckle Formula Master</h4>
                            <ol class="breadcrumb p-0 m-0">
                                <li><a href="{{ url('admin') }}">{{Session::get('software_title')}}</a></li>
                                <li>Production</li>
                                <li class="active">Buckle Formula List</li>
                                <li style="text-align: right;margin-bottom: 5px">
                                    <a class="btn btn-primary" href="{{ route('admin.production.buckle_formula_add') }}">Add New</a>
                                </li>
                            </ol>
                            <div class="clearfix"></div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-12">
                        <div class="card-box table-responsive">

                            @if(session()->has('message'))
                                <div class="col-sm-12">
                                    <div class="alert alert-info" style="background-color: #188ae2 !important">
                                        <strong style="color: #fff">{{session()->get('message')}}</strong>
                                    </div>
                                </div>
                            @endif

                            <table class="table table-striped table-bordered">
                                <thead>
                                <tr>
                                    <td></td>
                                    <td colspan="3">
                                        <form method="get">
                                            <input type="text" class="form-control" name="search" placeholder="Search by product or size" value="{{ Request::get('search') }}">
                                    </td>
                                    <td><button class="btn btn-primary">Search</button></form></td>
                                </tr>
                                <tr>
                                    <th>#</th>
                                    <th>Belt Product</th>
                                    <th>Size</th>
                                    <th>Materials</th>
                                    <th></th>
                                </tr>
                                </thead>
                                <tbody>
                                @forelse($data as $list)
                                    <tr>
                                        <td style="width:5%;text-align:center;">{{ $loop->iteration }}</td>
                                        <td>
                                            <a href="{{ route('admin.production.buckle_formula_edit', ['id' => $list->id]) }}">
                                                {{ $list->product_item->product_name ?? '-' }}
                                            </a>
                                        </td>
                                        <td style="text-align:center;">{{ $list->size }}</td>
                                        <td style="text-align:center;">{{ $list->items->count() }}</td>
                                        <td class="actions" style="width:10%;">
                                            <a href="{{ route('admin.production.buckle_formula_edit', ['id' => $list->id]) }}"><i class="fa fa-pencil"></i></a>
                                            <a href="{{ route('admin.production.buckle_formula_delete', ['id' => $list->id]) }}" onclick="return confirm('Are you sure you want to delete this item?');"><i class="fa fa-trash-o"></i></a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center" style="padding:30px;color:#999;">No buckle formulas found</td>
                                    </tr>
                                @endforelse
                                </tbody>
                            </table>
                            {{ $data->links() }}

                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

@endsection
