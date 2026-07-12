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
    <title>Zircos - User Add</title>

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
                                <h4 class="page-title">User Add </h4>
                                <ol class="breadcrumb p-0 m-0">
                                    <li>
                                        <a href="#">Zircos</a>
                                    </li>
                                    <li>
                                        <a href="{{url('master/user-list')}}">User List </a>
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
                                            {{Form::open(['method'=>'post','route'=>'post.user_save'])}}
                                            <div class="col-md-12">
                                                <div class="demo-box">
                                                    <div class="col-md-6">
                                                      <div class="form-group">
                                                        <label class="control-label">Website Name</label>
                                                        {{Form::select('website',$website,null,['class'=>'form-control'])}}

                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                  <div class="form-group">
                                                    <label class="control-label">User Name</label>
                                                    {{Form::text('user_name',null,['class'=>'form-control'])}}
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                              <div class="form-group">
                                                <label class="control-label">Email</label>
                                                {{Form::email('email',null,['class'=>'form-control'])}}
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                          <div class="form-group">
                                            <label class="control-label">Password</label>
                                            {{Form::text('password',null,['class'=>'form-control'])}}
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <label style="">Select Modules For Access</label>

                                        <div class="form-group">
                                            <input type="checkbox" id="ckbCheckAll" /> Check All
                                            <p id="checkBoxes">
                                            </p></div>
                                        <?php
                                        echo $str;
                                        ?>
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
            <script src="{{asset('public/adminpanel/default/assets/js/jquery.min.js')}}"></script>

            <script type="text/javascript">
                $("#ckbCheckAll").click(function () {
                    //alert("sad");
                    $(".checkBoxClass").prop('checked', $(this).prop('checked'));
                });
            </script>
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
