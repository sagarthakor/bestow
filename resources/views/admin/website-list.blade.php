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
        <title>{{Session::get('software_title')}} - Website List</title>

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
                                    <h4 class="page-title">Website List </h4>
                                    <ol class="breadcrumb p-0 m-0">
                                        <li>
                                            <a href="#">Zircos</a>
                                        </li>
                                    
                                        <li class="active">
                                            Website List
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
                                <a class="btn btn-primary" href="{{url('website_add')}}">Add New</a>
                            </div>
                        </div>
                        <div class="row">
                            @if(session()->has('message'))
                                <div class="col-sm-12">
                                    <div class="alert alert-success">
                                        <strong>{{session()->get('message')}}</strong>
                                    </div>
                                </div>
                            @endif
                            <div class="col-sm-12">

                                <div class="card-box table-responsive">
                                    <table id="datatable-buttons" class="table table-striped table-bordered">
                                        <thead>
                                        <tr>
                                            <th>Id</th>
                                            <th>Website Name</th>
                                            <th>Customer Name</th>
                                            <th>Start Date</th>
                                            <th>End date</th>
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
                                            <td>{{$srno}}</td>
                                            <td>{{$list->website_name}}</td>
                                            <td>{{$list->customer_name}}</td>
                                            <td>{{date('d-m-Y',strtotime($list->start_date))}}</td>
                                            <td>{{date('d-m-Y',strtotime($list->end_date))}}</td>
                                            <td class="actions">
                                                    <a href="#" class="hidden on-editing save-row"><i class="fa fa-save"></i></a>
                                                    <a href="#" class="hidden on-editing cancel-row"><i class="fa fa-times"></i></a>
                                                    <a href="#" class="on-default edit-row"><i class="fa fa-pencil"></i></a>
                                                    <a href="#" class="on-default remove-row"><i class="fa fa-trash-o"></i></a>
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