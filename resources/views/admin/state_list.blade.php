@extends('admin.layout.table_master_material')

@section('title', 'List of State')

@section('sidebar')
    @parent

@endsection

@section('content')
    <div class="content-page">
        <!-- Start content -->
        <div class="content">
            <div class="container">


                <div class="row">
                    <div class="col-xs-12">
                        <div class="page-title-box">
                            <h4 class="page-title">State List </h4>
                            <ol class="breadcrumb p-0 m-0">
                                <li>
                                    <a href="#">{{Session::get('software_title')}}</a>
                                </li>

                                <li class="active">
                                    State List
                                </li>
                                <li style="text-align: right;margin-bottom: 5px">
                                    <a class="btn btn-primary" href="{{route('admin.state.add')}}">Add New</a>
                                </li>
                            </ol>
                            <div class="clearfix"></div>
                        </div>
                    </div>
                </div>
                <!-- end row -->

                <div class="row">
                    @if(session()->has('message'))
                        <div class="col-sm-12">
                            <div class="alert alert-info">
                                <strong>{{session()->get('message')}}</strong>
                            </div>
                        </div>
                    @endif
                    <div class="col-sm-12">

                        <div class="card-box">
                            <h4 class="m-t-0 header-title">Filter</h4>
                            <form method="get">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Country</label>
                                        <input type="text" placeholder="Country Name" class="form-control" name="country" value="@if(isset($_GET['country'])){{$_GET['country']}}@endif">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>State Name</label>
                                        <input type="text" placeholder="State Name" class="form-control" name="state" value="@if(isset($_GET['state'])){{$_GET['state']}}@endif">
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <label class="hidden-xs">&nbsp;</label>
                                    <button class="btn btn-primary btn-block waves-effect waves-light"><i class="fa fa-search"></i> Search</button>
                                </div>
                            </div>
                            </form>
                        </div>

                        <div class="card-box table-responsive">
                                <table class="table table-striped table-bordered table-hover">
                                    <thead>
                                    <tr>
                                        <th>Sr.</th>
                                        <th>Country</th>
                                        <th>State Name</th>

                                        <th></th>
                                    </tr>
                                    </thead>

                                    <tbody>
                                    <?php
                                    $srno=0;
                                    ?>
                                    @foreach($data as $list)
                                        <?php
                                        $srno++;
                                        ?>
                                        <tr>
                                            <td style="width: 5%">{{$srno}}</td>
                                            <td>{{$list->country_name}}</td>
                                            <td>{{$list->state_name}}</td>

                                            <td class="actions" width="5%">
                                                <a href="{{route('admin.state.edit',['id' => $list->id])}}" class="btn btn-xs btn-primary waves-effect"><i class="fa fa-pencil"></i> Edit</a>
                                                <a href="{{route('admin.state.delete',['id' => $list->id])}}" class="btn btn-xs btn-danger waves-effect" onclick="return confirm('Are you sure you want to delete this item?');"><i class="fa fa-trash-o"></i> Delete</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                                {{$data->appends(request()->input())->links()}}
                        </div>
                    </div>
                </div>



                <!-- end row -->



            </div> <!-- container -->

        </div> <!-- content -->

@endsection
