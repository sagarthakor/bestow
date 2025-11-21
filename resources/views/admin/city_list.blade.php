@extends('admin.layout.table_master')

@section('title', 'List of City')

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
                            <h4 class="page-title">City List </h4>
                            <ol class="breadcrumb p-0 m-0">
                                <li>
                                    <a href="#">{{Session::get('software_title')}}</a>
                                </li>

                                <li class="active">
                                    City List
                                </li>
                            </ol>
                            <div class="clearfix"></div>
                        </div>
                    </div>
                </div>
                <!-- end row -->




                <div class="row">
                    <div class="col-sm-4">
                    </div>
                    <div class="col-sm-4">
                    </div>

                    <div class="col-sm-4" style="text-align: right;margin-bottom: 5px">
                        <a class="btn btn-primary" href="{{route('admin.city.add')}}">Add New</a>
                    </div>

                </div>
                <div class="row">
                    @if(session()->has('message'))
                        <div class="col-sm-12">
                            <div class="alert alert-info" style="background-color: #188ae2 !important">
                                <strong style="color: #fff">{{session()->get('message')}}</strong>
                            </div>
                        </div>
                    @endif

                    <div class="col-sm-12">

                        <div class="card-box table-responsive">
                            <form method="get">

                                <table class="table table-striped table-bordered">
                                    <thead>
                                    <tr>
                                        <th>Sr.</th>
                                        <th>State</th>
                                        <th>City Name</th>

                                        <th></th>
                                    </tr>
                                    </thead>
                                    <tr>
                                        <td><button>Search</button></td>
                                        <td><input type="text" placeholder="State Name" class="listSearchContributor inputElement" name="state"></td>
                                        <td>
                                            <input type="text" placeholder="City Name" class="listSearchContributor inputElement" name="city">
                                        </td>
                                        <td></td>
                                    </tr>

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
                                            <td>{{$list->state_name}}</td>
                                            <td>{{$list->city_name}}</td>

                                            <td class="actions" width="5%">

                                                <a href="{{route('admin.city.edit',['id' => $list->id])}}" class="on-default edit-row"><i class="fa fa-pencil"></i></a>

                                                <a href="{{route('admin.city.delete',['id' => $list->id])}}" class="on-default remove-row" onclick="return confirm('Are you sure you want to delete this item?');"><i class="fa fa-trash-o"></i></a>

                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </form>
                            {{$data->appends(request()->input())->links()}}
                        </div>
                    </div>
                </div>



                <!-- end row -->



            </div> <!-- container -->

        </div> <!-- content -->

@endsection
