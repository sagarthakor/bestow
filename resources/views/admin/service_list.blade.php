@extends('admin.layout.table_master')

@section('title', 'List of Services')

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
                                <h4 class="page-title">Services List </h4>
                                <ol class="breadcrumb p-0 m-0">
                                    <li>
                                        <a href="#">{{Session::get('software_title')}}</a>
                                    </li>

                                    <li class="active">
                                        Services List
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
                                <a class="btn btn-primary" href="{{url('client/service/add')}}">Add New</a>
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
                            <form method="get">
                                <table class="table table-striped table-bordered">
                                    <thead>
                                    <tr>
                                        <th>Sr.No</th>
                                        <th>Services Name</th>
                                        <th>Category Name</th>
                                        <th>Price</th>
                                        <th>GST</th>
                                        <th>UOM</th>
                                        <th>HSN</th>

                                        <th></th>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td><input type="text" class="listSearchContributor inputElement" name="service_name" placeholder="Services Name" value="@if(isset($_GET['service_name'])){{$_GET['service_name']}}@endif">
                                        </td>
                                        <td>
                                            <input type="text" class="listSearchContributor inputElement" name="category" placeholder="Categrory" value="@if(isset($_GET['category'])){{$_GET['category']}}@endif">
                                        </td>
                                        <!--<td>-->
                                    <!--    <input type="text" class="listSearchContributor inputElement" name="material" placeholder="Material" value="@if(isset($_GET['material'])){{$_GET['material']}}@endif">-->
                                        <!--</td>-->
                                        <td>
                                            <input type="text" style="width:85px;border-radius: 1px;
                                            box-shadow: none;
                                            border: 1px solid #cccccc;height: 30px;padding: 3px 8px;" name="price" placeholder="Price" value="@if(isset($_GET['price'])){{$_GET['price']}}@endif">
                                        </td>

                                        <td>
                                            <input type="text" class="" style="width:35px;border-radius: 1px;
                                            box-shadow: none;
                                            border: 1px solid #cccccc;height: 30px;padding: 3px 8px;" name="gst" placeholder="GST" value="@if(isset($_GET['gst'])){{$_GET['gst']}}@endif">
                                        </td>

                                        <td>
                                            <input type="text" style="width:55px;border-radius: 1px;
                                            box-shadow: none;
                                            border: 1px solid #cccccc;height: 30px;padding: 3px 8px;" name="usage_unit" placeholder="Unit" value="@if(isset($_GET['usage_unit'])){{$_GET['usage_unit']}}@endif">
                                        </td>

                                        <td>
                                            <input type="text" style="width:55px;border-radius: 1px;
                                            box-shadow: none;
                                            border: 1px solid #cccccc;height: 30px;padding: 3px 8px;" name="hsn" placeholder="HSN" value="@if(isset($_GET['hsn'])){{$_GET['hsn']}}@endif">
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
                                            <td style="width:1%;text-align:center">{{$srno}}</td>
                                            <td>{{$list->product_name}}</td>
                                            <td>{{$list->category_name}}</td>
                                            <td>{{$list->price}}</td>
                                            <td>{{$list->gst_per}}</td>
                                            <td>{{$list->uom_name}}</td>
                                            <td>{{$list->hsn}}</td>

                                            <td class="actions" style="width: 5%">
                                                @can('service_update')
                                                    <a href="{{url('client/service/edit/'.$list->id)}}" class="on-default edit-row"><i class="fa fa-pencil"></i></a>
                                                @endcan
                                                @can('service_delete')
                                                        <a href="{{url('client/service/delete/'.$list->id)}}" class="on-default remove-row" onclick="return confirm('Are you sure you want to delete this item?');"><i class="fa fa-trash-o"></i></a>
                                                @endcan
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </form>
                   </div>
               </div>
           </div>



           <!-- end row -->



       </div> <!-- container -->

   </div> <!-- content -->
@endsection
