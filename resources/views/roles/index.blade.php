@extends('admin.layout.table_master')

@section('title', 'List of Roles')

@section('sidebar')
    @parent

@endsection

@section('content')
<style>
    /* Custom CSS to set a fixed height and enable scrolling */
    .table-responsive {
        max-height: 300px; /* Adjust the height as needed */
        overflow-y: auto;
    }
</style>

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
                                    <div class="alert alert-info" style="background-color: #188ae2 !important">
                                        <strong style="color: #fff">{{session()->get('message')}}</strong>
                                    </div>
                                </div>
                            @endif
                            <div class="table-responsive">
                                <table class="table table-bordered">
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
                                                <div class="dropdown">
                                                    <button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown">Action
                                                        <span class="caret"></span></button>
                                                    <ul class="dropdown-menu">

                                                        {{--@can('role-edit')--}}
                                                        <li><a title="Edit" href="{{ route('admin.roles.edit',['id' => $role->id]) }}">Edit</a></li>
                                                        {{-- @endcan
                                                         @can('role-delete')--}}
                                                        <li><a title="Delete" href="{{ route('admin.roles.destroy',['id' => $role->id]) }}">Delete</a></li>
                                                        {{--   @endcan--}}
                                                    </ul>
                                                </div>
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
