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
    <title>{{Session::get('software_title')}} - Profile</title>

    <!-- Plugins css-->
    @extends('admin.form_header')
    <script src="https://cdn.ckeditor.com/ckeditor5/20.0.0/classic/ckeditor.js"></script>


</head>


<body class="fixed-left">

<!-- Begin page -->
<div id="wrapper">

    <!-- Top Bar Start -->
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
                            <h4 class="page-title">Add New Customer</h4>
                            <ol class="breadcrumb p-0 m-0">
                                <li>
                                    <a href="#">Zircos</a>
                                </li>
                                <li>
                                    <a href="{{url('customer-list')}}">Customer List </a>
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

                                <div class="col-xs-12">
                                    @if(session()->has('message'))
                                        <div class="alert alert-success">
                                            <strong>{{session()->get('message')}}</strong>
                                        </div>
                                    @endif

                                    <div class="row justify-content-md-center">
                                        {{Form::model($data,['method'=>'post','route'=>'post.profile_update'])}}
                                        <div class="col-md-12">
                                            <div class="demo-box">

                                            <div class="row">
                                                {{Form::hidden('id',null)}}
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>User Name <span style="color: red">*</span></label>
                                                        {{Form::text('user_name',null,['class'=>'form-control'. $errors->first('user_name', ' error')])}}
                                                        @if ($errors->has('user_name'))
                                                            <p class="help-block">{{ $errors->first('user_name') }}</p>
                                                        @endif
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Password <span style="color: red">*</span></label>
                                                        {{Form::text('password',$data->password,['class'=>'form-control'. $errors->first('password', ' error')])}}
                                                        @if ($errors->has('password'))
                                                            <p class="help-block">{{ $errors->first('password') }}</p>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>First Name <span style="color: red">*</span></label>
                                                            {{Form::text('first_name',null,['class'=>'form-control'. $errors->first('first_name', ' error')])}}
                                                            @if ($errors->has('first_name'))
                                                                <p class="help-block">{{ $errors->first('first_name') }}</p>
                                                            @endif
                                                        </div>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>Last Name <span style="color: red">*</span></label>
                                                            {{Form::text('last_name',null,['class'=>'form-control'. $errors->first('last_name', ' error')])}}
                                                            @if ($errors->has('last_name'))
                                                                <p class="help-block">{{ $errors->first('last_name') }}</p>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>Primary Phone <span style="color: red">*</span></label>
                                                            {{Form::text('primary_phone',null,['class'=>'form-control'. $errors->first('primary_phone', ' error')])}}
                                                            @if ($errors->has('primary_phone')) <p class="help-block">This field is required</p> @endif

                                                        </div>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label> Primary Email <span style="color: red">*</span></label>
                                                            {{Form::email('email',null,['class'=>'form-control'.$errors->first('email',' error')])}}

                                                            @if($errors->has('email'))
                                                                <p class="help-block">This field is required</p>
                                                            @endif

                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row">

                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label> Secondary Phone </label>
                                                            {{Form::text('secondary_phone',null,['class'=>'form-control'])}}

                                                        </div>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label> Secondary Email </label>
                                                            {{Form::email('secondary_email',null,['class'=>'form-control'])}}

                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row">



                                                </div>

                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>Aadhar Card No </label>
                                                            {{Form::text('aadhar_card',null,['class'=>'form-control'])}}

                                                        </div>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>PAN No </label>
                                                            {{Form::text('pan_card',null,['class'=>'form-control'])}}

                                                        </div>
                                                    </div>
                                                </div>


                                                <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Department <span style="color: red">*</span></label>
                                                        {{Form::text('department',null,['class'=>'form-control'. $errors->first('department', ' error')])}}
                                                        @if ($errors->has('department'))
                                                            <p class="help-block">This field is required</p>
                                                        @endif
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Designation <span style="color: red">*</span></label>
                                                        {{Form::text('designation',null,['class'=>'form-control'. $errors->first('designation', ' error')])}}
                                                        @if ($errors->has('designation'))
                                                            <p class="help-block">This field is required</p>
                                                        @endif
                                                    </div>
                                                </div>
                                                </div>

                                                <div class="row">
                                                <div class="col-sm-12">

                                                    <h3>Address Information</h3>

                                                </div>


                                                    <div class="col-md-4">

                                                        <div class="form-group">
                                                            <label>Address <span style="color: red">*</span></label>
                                                            {{Form::textarea('address',null,['class'=>'form-control'.$errors->first('address',' error'),'cols'=>'1','rows'=>'1','style'=>'min-height: 38px !important','id'=>'address'])}}
                                                            @if($errors->has('address'))
                                                                <p class="help-block">This field is required</p>
                                                            @endif
                                                        </div>

                                                    </div>


                                                    <div class="col-md-2">
                                                        <div class="form-group">
                                                            <label>Country <span style="color: red">*</span></label>

                                                            {{Form::select('country',$country,null,['class'=>'form-control'.$errors->first('country',' error'),'id'=>"billing_country"])}}
                                                            @if($errors->has('country'))
                                                                <p class="help-block">This field is required</p>
                                                            @endif
                                                        </div>
                                                    </div>

                                                    <div class="col-md-2">
                                                        <div class="form-group">
                                                            <label>State  <span style="color: red">*</span></label>

                                                            {{Form::select('state',$state,null,['class'=>'form-control'.$errors->first('state',' error'),'id'=>"billing_state"])}}
                                                            @if($errors->has('state'))
                                                                <p class="help-block">This field is required</p>
                                                            @endif
                                                        </div>
                                                    </div>

                                                    <div class="col-md-2">
                                                        <div class="form-group">
                                                            <label>City   <span style="color: red">*</span></label>

                                                            {{Form::select('city',$city,null,['class'=>'form-control'.$errors->first('city',' error'),'id'=>"billing_city"])}}
                                                            @if($errors->has('city'))
                                                                <p class="help-block">This field is required</p>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <div class="form-group">
                                                            <label>Postal Code  <span style="color: red">*</span></label>

                                                            {{Form::text('billing_postalcode',null,['class'=>'form-control'.$errors->first('billing_country',' error'),'id'=>"billing_postalcode"])}}
                                                            @if($errors->has('billing_postalcode'))
                                                                <p class="help-block">This field is required</p>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                               <div class="row">
                                                <div class="col-sm-12">
                                                    <h3>Description Details</h3>
                                                </div>

                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label>Description</label>
                                                        {{Form::textarea('description',null,['class'=>'form-control','cols'=>'1','rows'=>'1','style'=>'min-height: 38px !important','id'=>'editor3'])}}

                                                    </div>
                                                </div>
                                               </div>

                                                <div class="col-md-4">
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
<script src="{{asset('public/adminpanel/default/assets/js/jquery-1.12.4.js')}}"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script>
    ClassicEditor
        .create( document.querySelector( '#editor' ) )
        .catch( error => {
            console.error( error );
        } );

    ClassicEditor
        .create( document.querySelector( '#editor2' ) )
        .catch( error => {
            console.error( error );
        } );

    ClassicEditor
        .create( document.querySelector( '#editor3' ) )
        .catch( error => {
            console.error( error );
        } );

    $("#copy_billing").click(function(){

        var billing_city=$("#billing_city").val();
        var billing_address=$("#billing_address").val();
        var billing_state=$("#billing_state").val();
        var billing_city=$("#billing_city").val();
        var billing_country=$("#billing_country").val();
        var billing_postalcode=$("#billing_postalcode").val();


        $("#shipping_city").val(billing_city);
        $("#shipping_address").val(billing_address);
        $("#shipping_state").val(billing_state);
        $("#shipping_city").val(billing_city);
        $("#shipping_country").val(billing_country);
        $("#shipping_postalcode").val(billing_postalcode);

    });

    $("#billing_country").change(function(){
        var country=$("#billing_country").val();
        var appurl="{{url('/')}}";
        $.ajax({
            url:appurl+'/client/get_state',
            data:{country:country},
            method:'get',
            success:function(res)
            {
                $("#billing_state").html(res);
            }
        });
    });

    $("#billing_state").change(function(){
        var state=$("#billing_state").val();
        var appurl="{{url('/')}}";
        $.ajax({
            url:appurl+'/client/get_city',
            data:{state:state},
            method:'get',
            success:function(res)
            {
                $("#billing_city").html(res);
            }
        });
    });

    $("#shipping_country").change(function(){
        var country=$("#shipping_country").val();
        var appurl="{{url('/')}}";
        $.ajax({
            url:appurl+'/client/get_state',
            data:{country:country},
            method:'get',
            success:function(res)
            {
                $("#shipping_state").html(res);
            }
        });
    });

    $("#shipping_state").change(function(){
        var state=$("#shipping_state").val();
        var appurl="{{url('/')}}";
        $.ajax({
            url:appurl+'/client/get_city',
            data:{state:state},
            method:'get',
            success:function(res)
            {
                $("#shipping_city").html(res);
            }
        });
    });
</script>
</body>
</html>
