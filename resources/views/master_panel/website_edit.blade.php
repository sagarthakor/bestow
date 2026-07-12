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
        <title>Zircos - Website Add</title>

        <!-- Plugins css-->
        @extends('admin.form_header')

    </head>


    <body class="fixed-left">

        <!-- Begin page -->
        <div id="wrapper">

            <!-- Top Bar Start -->
            @include('master_panel/side_bar')
            
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
                                    <h4 class="page-title">Website Add </h4>
                                    <ol class="breadcrumb p-0 m-0">
                                        <li>
                                            <a href="#">Zircos</a>
                                        </li>
                                        <li>
                                            <a href="{{url('master/website-list')}}">Website List </a>
                                        </li>
                                        
                                    </ol>
                                    <div class="clearfix"></div>
                                </div>
                            </div>
                        </div>
                        <!-- end row -->



                        <div class="row">
                            <div class="col-xs-12">
                                <div class="card-box">

                                    <div class="row">
                                        @if ($errors->any())
                                        <div class="col-xs-12">
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
</div>
@endif
                                        <div class="col-xs-12">

                                            <div class="row">
                                                {{Form::model($data,['method'=>'post','route'=>'post.website_update'])}}
                                                {{Form::hidden('id',null)}}
                                                <div class="col-md-12">
                                                    <div class="demo-box">
                                                        
                                                         <div class="col-md-6">
                                                          <div class="form-group">
                                                                    <label class="control-label">Customer Name</label>
                                                                    {{Form::text('customer_name',null,['class'=>'form-control'])}}
                                                            </div>
                                                        </div>
                                                        
                                                        
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Primary Phone <span style="color: red">*</span></label>
                                                    {{Form::text('primary_phone',null,['class'=>'form-control'])}}

                                                </div>
                                            </div>
                                           
                                             <div class="col-md-6">
                                                <div class="form-group">
                                                    <label> Secondary Phone </label>
                                                    {{Form::text('secondary_phone',null,['class'=>'form-control'])}}

                                                </div>
                                            </div>
                                          

                                                <div class="col-md-6">
                                                <div class="form-group">
                                                    <label> Primary Email <span style="color: red">*</span></label>
                                                    {{Form::email('primary_email',null,['class'=>'form-control'])}}

                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>GST No </label>
                                                    {{Form::text('owner_gst',null,['class'=>'form-control'])}}

                                                </div>
                                            </div>
                                             <div class="col-md-6">
                                                <div class="form-group">
                                                    <label> Secondary Email </label>
                                                    {{Form::email('secondary_email',null,['class'=>'form-control'])}}

                                                </div>
                                            </div>
                                             <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>PAN No </label>
                                                    {{Form::text('owner_pan',null,['class'=>'form-control'])}}

                                                </div>
                                            </div>
                                            

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Website</label>
                                                    {{Form::text('website',null,['class'=>'form-control'])}}

                                                </div>
                                            </div>

                                             <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Softwate Title</label>
                                                    {{Form::text('software_title',null,['class'=>'form-control'])}}

                                                </div>
                                            </div>

                                              

                                             <div class="col-sm-12">
                                                
                                                <h3>Organization Contact Person Information</h3>

                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label>Full Name <span style="color: red">*</span></label>
                                                    {{Form::text('owner_name',null,['class'=>'form-control'])}}

                                                </div>
                                            </div>
                                              <div class="col-md-2">
                                                <div class="form-group">
                                                    <label>Mobile No <span style="color: red">*</span></label>
                                                    {{Form::text('owner_mobile',null,['class'=>'form-control'])}}

                                                </div>
                                            </div>
                                             <div class="col-md-2">
                                                <div class="form-group">
                                                    <label>Alternate No.<span style="color: red">*</span></label>
                                                    {{Form::text('alternate_no',null,['class'=>'form-control'])}}

                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label>Personal Email <span style="color: red">*</span></label>
                                                    {{Form::text('owner_email',null,['class'=>'form-control'])}}

                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label>Work Email <span style="color: red">*</span></label>
                                                    {{Form::text('work_email',null,['class'=>'form-control'])}}

                                                </div>
                                            </div>
                                           

                                            <div class="col-sm-12">
                                                <h3>Description Details</h3>
                                            </div>
                                             
                                             <div class="col-md-12">
                                                <div class="form-group">
                                                    <label>Description</label>
                                                    {{Form::textarea('description',null,['class'=>'form-control','cols'=>'1','rows'=>'1','style'=>'min-height: 38px !important','id'=>'editor3'])}}

                                                </div>
                                            </div>
                                                        <div class="col-md-2">
                                                            <div class="form-group">
                                                                <button class="btn btn-primary">Save</button>
                                                            </div>
                                                        </div>
                                                        
                                                    </div>

                                                </div>
                                                {{Form::close()}}
                                              
                                            </div><!-- end row -->


                                        </div>

                                    </div>
                                    <!-- end row -->


                                    <!-- end row -->


                                </div> <!-- end card-box -->
                            </div><!-- end col-->

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



            @extends("admin.form_fotter")

    </body>
</html>