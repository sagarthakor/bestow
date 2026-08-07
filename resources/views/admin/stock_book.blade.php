@extends('admin.layout.table_master_material')

@section('title', 'List | Quotation')

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
                            <h4 class="page-title">Stock Book </h4>
                            <ol class="breadcrumb p-0 m-0">
                                <li>
                                    <a href="#">{{Session::get('software_title')}}</a>
                                </li>

                                <li class="active">
                                    Stock Book List
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
                            <div class="alert alert-info" style="background-color: #188ae2 !important">
                                <strong style="color: #fff">{{session()->get('message')}}</strong>
                            </div>
                        </div>
                    @endif

                    <div class="col-sm-12">
                        <div class="card-box">
                            <form method="get" class="row g-2 mb-3">
                                <div class="col-md-2">
                                    <input type="text" name="particular" class="form-control" placeholder="Particular" value="{{ request('particular') }}">
                                </div>
                                <div class="col-md-2">
                                    <input type="text" name="product_name" class="form-control" placeholder="Product Name" value="{{ request('product_name') }}">
                                </div>
                                <div class="col-md-2">
                                    <input type="date" name="from_date" class="form-control" value="{{ request('from_date') }}">
                                </div>
                                <div class="col-md-2">
                                    <input type="date" name="to_date" class="form-control" value="{{ request('to_date') }}">
                                </div>
                                <div class="col-md-2 d-flex gap-2">
                                    <button type="submit" class="btn btn-primary w-50">Search</button>
                                    <a href="{{ url()->current() }}" class="btn btn-primary w-50">Reset</a>
                                </div>
                            </form>
                        </div>
                        <div class="card-box">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Particular</th>
                                        <th>Date</th>
                                        <th>Product Name</th>
                                        <th>Inward Qty</th>
                                        <th>Outward Qty</th>
                                        {{--<th>User</th>--}}
                                        <th>Timestamp</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($data as $srno => $list)
                                        <tr>
                                            <td>{{ $srno + 1 }}</td>
                                            <td>{{ $list->particular }}</td>
                                            <td>{{ date('d-m-Y', strtotime($list->inward_date)) }}</td>
                                            <td><x-product-name :row="$list" /></td>
                                            <td>{{ $list->inward_qty ?? 0 }}</td>
                                            <td>{{ $list->outward_qty ?? 0 }}</td>
                                            {{--<td>{{ $list->user_name }}</td>--}}
                                            <td>{{ $list->created_time }}</td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                                {{ $data->appends(request()->input())->links() }}
                            </div>
                        </div>

                       {{-- <div class="card-box table-responsive">
                            <form method="get">
                                <table class="table table-striped table-bordered">
                                    <thead>
                                    <tr>
                                        <th>#.</th>
                                        <th>Particular</th>
                                        <th>Date</th>
                                        <!--<th>Inward From</th>-->

                                        <!--<th>Material Inward No</th>-->
                                        <!--<th>Customer Name</th>-->
                                        <!--<th>vendor Name</th>-->
                                        <!--<th>Type of Inward</th>-->
                                        <th>Product Name</th>
                                        <th>Inward Qty</th>
                                        <th>Outward Qty</th>
                                        <th>User</th>
                                        <th>Timestamp</th>
                                        <!--<th>Remaining Qty</th>-->

                                    </tr>

                                    <tr>
                                        <td></td>
                                        <td><input type="text" class="listSearchContributor inputElement" name="particular" value="@if(isset($_GET['particular'])){{$_GET['particular']}}@endif"></td>
                                        <td>
                                            <input type="text" class="listSearchContributor inputElement" name="from_date" id="from_date" value="@if(isset($_GET['from_date'])){{date('d-m-Y',strtotime($_GET['from_date']))}}@endif">
                                            <input type="text" class="listSearchContributor inputElement" name="to_date" id="to_date" value="@if(isset($_GET['to_date'])){{$_GET['to_date']}}@endif">
                                        </td>

                                    <!--<td><input type="text" class="listSearchContributor inputElement" name="inward_from" value="@if(isset($_GET['inward_from'])){{$_GET['inward_from']}}@endif"></td>-->

                                    <!--<td><input type="text" class="listSearchContributor inputElement" name="inward_no" value="@if(isset($_GET['inward_no'])){{$_GET['inward_no']}}@endif"></td>-->
                                    <!--<td><input type="text" class="listSearchContributor inputElement" name="customer_name" value="@if(isset($_GET['customer_name'])){{$_GET['customer_name']}}@endif"></td>-->
                                    <!--<td><input type="text" class="listSearchContributor inputElement" name="vendor_name" value="@if(isset($_GET['vendor_name'])){{$_GET['vendor_name']}}@endif"></td>-->
                                    <!--<td><input type="text" class="listSearchContributor inputElement" name="inward_type" value="@if(isset($_GET['inward_type'])){{$_GET['inward_type']}}@endif"></td>-->
                                        <td><input type="text" class="listSearchContributor inputElement" name="product_name" value="@if(isset($_GET['product_name'])){{$_GET['product_name']}}@endif"></td>
                                        <td><input type="text" class="listSearchContributor inputElement" name="inward_qty" value="@if(isset($_GET['inward_qty'])){{$_GET['inward_qty']}}@endif"></td>
                                        <td><input type="text" class="listSearchContributor inputElement" name="outward_qty" value="@if(isset($_GET['outward_qty'])){{$_GET['outward_qty']}}@endif"></td>
                                        <td><input type="text" class="listSearchContributor inputElement" name="user" value="@if(isset($_GET['user'])){{$_GET['user']}}@endif"></td>
                                    <!--<td><input type="text" class="listSearchContributor inputElement" name="remaining_qty" value="@if(isset($_GET['remaining_qty'])){{$_GET['remaining_qty']}}@endif"></td>-->
                                        <td></td>
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
                                            <td style="width: 5%;text-align:center">{{$srno}}</td>
                                            <td>{{$list->particular}}</td>
                                            <td  style="width: 15%;">{{date('d-m-Y',strtotime($list->inward_date))}}</td>
                                        <!--<td>{{$list->inward_from}}</td>-->

                                        <!--<td>{{$list->inward_no}}</td>-->
                                        <!--<td>{{$list->customer_name}}</td>-->
                                        <!--<td>{{$list->vendor_name}}</td>-->
                                        <!--<td>{{$list->inward_type}}</td>-->
                                            <td><x-product-name :row="$list" /></td>
                                            <td style="width:5%;text-align:center">{{$list->inward_qty ?? 0}}</td>
                                            <td style="width:5%;text-align:center">{{$list->outward_qty ?? 0}}</td>
                                            <td style="width:5%;text-align:center">{{$list->user_name}}</td>
                                            <td style="width:10%;text-align:center">{{$list->created_time}}</td>
                                        <!--<td>{{$list->remaining_qty}}</td>-->


                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </form>
                            {{$data->appends(request()->input())->links()}}
                        </div>--}}
                    </div>
                </div>



                <!-- end row -->



            </div> <!-- container -->

        </div> <!-- content -->
@endsection
