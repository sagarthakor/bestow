<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="A fully featured admin theme which can be used to build CRM, CMS, etc.">
    <meta name="author" content="Coderthemes">

    <!-- App favicon -->
    <link rel="shortcut icon" href="assets/images/favicon.ico">
    <!-- App title -->
    <title>{{Session::get('software_title')}} - Product List</title>

    <!-- DataTables -->
    @extends('admin.table_header_script')
</head>


<body class="fixed-left">

    <!-- Begin page -->
    <div id="wrapper">

        <!-- Top Bar Start -->

        <!-- Top Bar End -->


        <!-- ========== Left Sidebar Start ========== -->
        @include('admin/side_bar')
        <!-- Left Sidebar End -->



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
                            <h4 class="page-title">Product List </h4>
                            <ol class="breadcrumb p-0 m-0">
                                <li>
                                    <a href="#">Zircos</a>
                                </li>

                                <li class="active">
                                    Product List
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
                    @can('product_create')
                        <div class="col-sm-4" style="text-align: right;margin-bottom: 5px">
                            <a class="btn btn-primary" href="{{url('client/product/normal/add/')}}">Add New</a>
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
                            <table id="datatable" class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th>Sr.</th>
                                        <th>Product Name</th>
                                        <th>Categrory</th>
                                        <th>Material</th>
                                        <th>Usage Unit</th>
                                        <th>Price</th>
                                        <th>GST</th>

                                        <th></th>
                                    </tr>
                                    <tr>
                                        <td><button>Search</button></td>
                                        <td><input type="text" class="listSearchContributor inputElement" name="product_name" placeholder="Product Name" value="@if(isset($_GET['product_name'])){{$_GET['product_name']}}@endif">
                                        </td>
                                        <td>
                                            <input type="text" class="listSearchContributor inputElement" name="category" placeholder="Categrory" value="@if(isset($_GET['category'])){{$_GET['category']}}@endif">
                                        </td>
                                        <td>
                                            <input type="text" class="listSearchContributor inputElement" name="material" placeholder="Material" value="@if(isset($_GET['material'])){{$_GET['material']}}@endif">
                                        </td>
                                        <td>
                                            <input type="text" style="width:55px;border-radius: 1px;
                                            box-shadow: none;
                                            border: 1px solid #cccccc;height: 30px;padding: 3px 8px;" name="usage_unit" placeholder="Unit" value="@if(isset($_GET['usage_unit'])){{$_GET['usage_unit']}}@endif">
                                        </td>
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
                                    <td style="width: 5%">{{$srno}}</td>
                                    <td><a href="{{url('client/product/normal/preview/'.$list->id)}}">{{$list->product_name}}</a></td>
                                    <td style="text-align: center;">{{$list->category_name}}</td>
                                    <td style="text-align: center;">{{$list->material_name}}</td>
                                    <td style="text-align: center;">{{$list->uom_name}}</td>
                                    <td style="text-align: center;">{{number_format($list->price,2,'.',',')}}</td>
                                    <td style="text-align: center;">{{$list->gst_per}}</td>
                                    <td class="actions" style="width: 5%">
                                        @can('product_update')
                                            <a href="{{url('client/product/normal/edit/'.$list->id)}}" class="on-default edit-row"><i class="fa fa-pencil"></i></a>
                                        @endcan
                                        @can('product_delete')
                                                <a href="{{url('client/product/normal/delete/'.$list->id)}}" class="on-default remove-row" onclick="return confirm('Are you sure you want to delete this item?');"><i class="fa fa-trash-o"></i></a>
                                        @endcan
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


      <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
      <script type="text/javascript">
// Using jQuery.

$(function() {
    $('form').each(function() {
        $(this).find('input').keypress(function(e) {
            // Enter pressed?
            if(e.which == 10 || e.which == 13) {
                this.form.submit();
            }
        });

        $(this).find('input[type=submit]').hide();
    });
});
</script>
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
