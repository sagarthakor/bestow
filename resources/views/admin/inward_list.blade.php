@extends('admin.layout.table_master')

@section('title', 'List of Inward')

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
                                    <h4 class="page-title">Inward List </h4>
                                    <ol class="breadcrumb p-0 m-0">
                                        <li>
                                            <a href="#">{{Session::get('software_title')}}</a>
                                        </li>

                                        <li class="active">
                                            Inward List
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
                                <a class="btn btn-primary" href="{{url('client/inward/add')}}">Add New</a>
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
                                            <th>Inward From</th>
                                            <th>Product Name</th>
                                            <th>Customer Name</th>
                                            <th>Type of Inward</th>
                                            <th>Received Qty</th>
{{--                                            <th></th>--}}
                                        </tr>

                                        <tr>
                                            <td><button>Search</button></td>
                                            <td><input type="text" class="listSearchContributor inputElement" name="inward_from" value="@if(isset($_GET['inward_from'])){{$_GET['inward_from']}}@endif"></td>
                                            <td><input type="text" class="listSearchContributor inputElement" name="product_name" value="@if(isset($_GET['product_name'])){{$_GET['product_name']}}@endif"></td>
                                              <td><input type="text" class="listSearchContributor inputElement" name="customer_name" value="@if(isset($_GET['vendor_name'])){{$_GET['vendor_name']}}@endif"></td>
                                             <td><input type="text" class="listSearchContributor inputElement" name="inward_type" value="@if(isset($_GET['inward_type'])){{$_GET['inward_type']}}@endif"></td>
                                            <td></td>
{{--                                            <td></td>--}}
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
                                            <td>{{$list->inward_from}}</td>
                                            <td>{{$list->product_name}}</td>
{{--                                            <td>{{$list->inward_no}}</td>--}}
                                            <td>{{$list->customer_name}}</td>
{{--                                            <td>{{$list->vendor_name}}</td>--}}
                                            <td>{{$list->inward_type}}</td>
{{--                                            <td>{{$list->product_name}}</td>--}}
{{--                                            <td>{{$list->total_qty}}</td>--}}
                                            <td>{{$list->received_qty}}</td>
{{--                                            <td>{{$list->remaining_qty}}</td>--}}

{{--                                            <td class="actions" width="5%">--}}

{{--                                                    <a href="{{url('inward/edit/'.$list->id)}}" class="on-default edit-row"><i class="fa fa-pencil"></i></a>--}}
{{--                                                    <a href="{{url('inward/delete/'.$list->id.'/'.$list->product)}}" class="on-default remove-row" onclick="return confirm('Are you sure you want to delete this item?');"><i class="fa fa-trash-o"></i></a>--}}
{{--                                                </td>--}}
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
