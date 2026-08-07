@extends('admin.layout.table_master_material')

@section('title', 'List of Roles')

@section('sidebar')
    @parent

@endsection

@section('content')

    <!-- ============================================================== -->
    <!-- Start right Content here -->
    <!-- ============================================================== -->
    <div class="content-page">
        <!-- Start content -->
        <div class="content">
            <div class="container">


                <div class="row">
                    <div class="col-xs-12">
                        <div class="page-title-box">
                            <h4 class="page-title">{{Session::get('software_title')}} </h4>
                            <ol class="breadcrumb p-0 m-0">
                                <li>
                                    <a href="#">{{Session::get('software_title')}}</a>
                                </li>
                                <li class="active">
                                    <a href="{{ route('admin.roles.list') }}">Roles List </a>
                                </li>
                                <li style="text-align: right;margin-bottom: 5px">
                                    <a class="btn btn-primary" href="{{route('admin.role.add')}}">Add New</a>
                                </li>
                            </ol>
                            <div class="clearfix"></div>
                        </div>
                    </div>
                </div>
                <!-- end row -->






                <div class="row">
                    <div class="col-sm-12">
                        <div class="card-box">

                            @if(session()->has('message'))
                                <div class="col-sm-12">
                                    <div class="alert alert-info">
                                        <strong>{{session()->get('message')}}</strong>
                                    </div>
                                </div>
                            @endif
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped table-hover">
                                    <tr>
                                        <th>No</th>
                                        <th>Name</th>
                                        <th width="280px">Action</th>
                                    </tr>
                                    @foreach ($roles as $key => $role)
                                        <tr>
                                            <td>{{ ++$i }}</td>
                                            <td>{{ $role->name }}</td>
                                            <td>
                                                <a title="Edit" href="{{ route('admin.roles.edit',['id' => $role->id]) }}" class="btn btn-xs btn-primary waves-effect"><i class="fa fa-pencil"></i> Edit</a>
                                                <a title="Delete" href="{{ route('admin.roles.destroy',['id' => $role->id]) }}" class="btn btn-xs btn-danger waves-effect" onclick="return confirm('Are you sure you want to delete this item?');"><i class="fa fa-trash-o"></i> Delete</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </table>
                            </div>


                            {!! $roles->render() !!}
                        </div>
                    </div>
                </div>



                <!-- end row -->



            </div> <!-- container -->

        </div> <!-- content -->

@endsection
