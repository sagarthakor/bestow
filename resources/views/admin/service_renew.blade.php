@extends('admin.layout.table_master')

@section('title', 'List of Services Renewal')

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
                                    <h4 class="page-title">Services Renewal List </h4>
                                    <ol class="breadcrumb p-0 m-0">
                                        <li>
                                            <a href="#">{{Session::get('software_title')}}</a>
                                        </li>

                                        <li class="active">
                                            Services Renewal List
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
                            @can('service_create')
                                <div class="col-sm-4" style="text-align: right;margin-bottom: 5px">
                                    <a class="btn btn-primary" href="{{url('client/service/renew/add')}}">Add New</a>
                                </div>
                            @endcan
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

                                    <table class="table table-striped table-bordered">
                                        <thead>
                                        <tr>
                                            <th>Sr.</th>
                                            <th>Organization Name</th>
                                            <th>Services Name</th>

                                            <th>Usage Unit</th>
                                            <th>Category</th>
                                            <th>Sales Start Date</th>
                                            <th>Sales End Date</th>
                                            <th>Support Start Date</th>
                                            <th>Support Expiry Date</th>
                                            <th>Price</th>
                                            <th>Purchase Cost</th>
                                            <th></th>
                                        </tr>
                                        <tr>
                                            <td></td>
                                            <td><input type="text" class="listSearchContributor inputElement" name="customer_name" placeholder="Organization Name" value="@if(isset($_GET['customer_name'])){{$_GET['customer_name']}}@endif">
                                            </td>
                                            <td><input type="text" class="listSearchContributor inputElement" name="service_name" placeholder="Service Name" value="@if(isset($_GET['service_name'])){{$_GET['service_name']}}@endif">
                                            </td>
                                            <td><input type="text" class="listSearchContributor inputElement" name="usage_unit" placeholder="Usage Unit" value="@if(isset($_GET['usage_unit'])){{$_GET['usage_unit']}}@endif">
                                            </td>
                                            <td><input type="text" class="listSearchContributor inputElement" name="category_name" placeholder="Category Name" value="@if(isset($_GET['category_name'])){{$_GET['category_name']}}@endif">
                                            </td>
                                            <td><input type="date" class="listSearchContributor inputElement" name="sales_start_date" placeholder="Sales Start Date" value="@if(isset($_GET['sales_start_date'])){{$_GET['sales_start_date']}}@endif">
                                            </td>
                                            <td><input type="date" class="listSearchContributor inputElement" name="sales_end_date" placeholder="Sales End Date" value="@if(isset($_GET['sales_end_date'])){{$_GET['sales_end_date']}}@endif">
                                            </td>
                                            <td><input type="date" class="listSearchContributor inputElement" name="support_start_date" placeholder="Support Start Date" value="@if(isset($_GET['support_start_date'])){{$_GET['support_start_date']}}@endif">
                                            </td>
                                            <td><input type="date" class="listSearchContributor inputElement" name="support_end_date" placeholder="Support End Date" value="@if(isset($_GET['support_end_date'])){{$_GET['support_end_date']}}@endif">
                                            </td>
                                            <td><input type="text" class="listSearchContributor inputElement" name="Price" placeholder="Price" value="@if(isset($_GET['price'])){{$_GET['price']}}@endif">
                                            </td>
                                            <td><input type="text" class="listSearchContributor inputElement" name="purchase_cost" placeholder="Purchase Cost" value="@if(isset($_GET['purchase_cost'])){{$_GET['purchase_cost']}}@endif">
                                            </td>
                                            <td><button>Search</button></td>
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
                                                <td>{{$srno}}</td>
                                                <td>{{$list->customer_name}}</td>
                                                <td><x-product-name :row="$list" /></td>
                                                <td>{{$list->uom_name}}</td>

                                                <td>{{$list->category_name}}</td>
                                                <td>{{date('d-m-Y',strtotime($list->sales_start_date))}}</td>
                                                <td>{{date('d-m-Y',strtotime($list->sales_end_date))}}</td>
                                                <td>{{date('d-m-Y',strtotime($list->support_start_date))}}</td>
                                                <td>{{date('d-m-Y',strtotime($list->support_expiry_date))}}</td>
                                                <td>{{$list->price}}</td>
                                                <td>{{$list->purchase_cost}}</td>


                                                <td class="actions" style="width: 5%">
                                                    @can('service_update')
                                                        <a href="{{url('client/service/renew/edit/'.$list->id)}}" class="on-default edit-row"><i class="fa fa-pencil"></i></a>
                                                    @endcan
                                                    @can('service_delete')
                                                            <a href="{{url('client/service/renew/delete/'.$list->id)}}" class="on-default remove-row"><i class="fa fa-trash-o"></i></a>
                                                    @endcan

                                                </td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>



                        <!-- end row -->



                    </div> <!-- container -->

                </div> <!-- content -->

              @endsection
