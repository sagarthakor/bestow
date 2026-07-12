<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="A fully featured admin theme which can be used to build CRM, CMS, etc.">
    <meta name="author" content="Coderthemes">
    <link rel="shortcut icon" href="assets/images/favicon.ico">
    <title>{{Session::get('software_title')}} - Inward List</title>
    @extends('admin.table_header_script')
</head>


<body class="fixed-left">

<!-- Begin page -->
<div id="wrapper">
@include('admin/side_bar')
<!-- Left Sidebar End -->

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
                                    <a href="#">Demo</a>
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
                                <table id="datatable" class="table table-striped table-bordered">
                                    <thead>
                                    <tr>
                                        <th>Sr.</th>

                                        <th>Product Name</th>

                                        <th>Qty</th>

                                    </tr>

                                    <tr>
                                        <td><button>Search</button></td>
                                        <td>
                                            <input type="text" class="listSearchContributor inputElement" name="product_name" value="@if(isset($_GET['product_name'])){{$_GET['product_name']}}@endif">
                                        </td>
                                       <td>
                                           <input type="text" class="listSearchContributor inputElement" name="qty" value="@if(isset($_GET['qty'])){{$_GET['qty']}}@endif">
                                       </td>
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
                                            <td style="width: 5%">{{($data->currentPage() - 1) * $data->perPage() + $loop->iteration}}</td>
                                              <td>{{$list->product_name}}</td>
                                            <td>{{$list->qty}}</td>


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

        @extends('admin.footer')

    </div>


    <!-- ============================================================== -->
    <!-- End Right content here -->
    <!-- ============================================================== -->


    <!-- Right Sidebar -->

    <!-- /Right-bar -->

</div>
<!-- END wrapper -->



@extends('admin.table_footer_script')


</body>
</html>
