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
        <title>{{Session::get('software_title')}} - Income Sadar List</title>

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
                                    <h4 class="page-title">Category List </h4>
                                    <ol class="breadcrumb p-0 m-0">
                                        <li>
                                            <a href="#">Zircos</a>
                                        </li>
                                    
                                        <li class="active">
                                            Category List
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
                                <a class="btn btn-primary" href="{{url('client/product/category/add')}}">Add New</a>
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
                                <div class="row">
                                    {{Form::open(['method'=>'post'])}}
                                
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <input value="<?php if(isset($_GET['category_name'])){echo $_GET['category_name']; }?>" type="text" class="form-control" name="category_name" placeholder="Category Name">
                                        </div>
                                    </div>
                                    {{Form::close()}}
                            </div>
                                <div class="card-box table-responsive">
                                    <table id="datatable" class="table table-striped table-bordered">
                                        <thead>
                                        <tr>
                                            <th>Sr.</th>
                                            <th>Income Sadar Name</th>
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
                                            <td style="width: 5%"> {{($data->currentPage() - 1) * $data->perPage() + $loop->iteration}}</td>
                                            <td style="">{{$list->income_name}}</td>
                                           
                                            <td class="actions" style="width: 5%">
                                                   
                                                    <a href="{{url('client/category/edit/'.$list->id)}}" class="on-default edit-row"><i class="fa fa-pencil"></i></a>
                                                <a href="{{url('client/category/delete/'.$list->id)}}" class="on-default remove-row" onclick="return confirm('Are you sure you want to delete this item?');"><i class="fa fa-trash-o"></i></a>
                                                </td>
                                        </tr>
                                            @endforeach
                                        </tbody>
                                    </table>

                                    {{$data->links()}}
                                    
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
    </body>
</html>