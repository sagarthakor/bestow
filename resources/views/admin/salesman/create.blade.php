@extends('admin.layout.master_material')

@section('title', 'Add New | Salesman')

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
                            <h4 class="page-title">Add New Salesman</h4>
                            <ol class="breadcrumb p-0 m-0">
                                <li>
                                    <a href="#">{{Session::get('software_title')}}</a>
                                </li>
                                <li>
                                    <a href="{{url('admin/salesman/list')}}">Salesman List </a>
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
                            <!--   @if ($errors->any())
                                <div class="col-xs-12">
                                    <div class="alert alert-danger">
                                        <ul>
                                            @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                            @endforeach
                                    </ul>
                                </div>
                            </div>
@endif -->

                                @if (session()->has('message'))
                                    <div class="col-xs-12">
                                        <div class="alert alert-danger">
                                            <ul>

                                                <li>{{ session()->get('message') }}</li>

                                            </ul>
                                        </div>
                                    </div>
                                @endif

                                <div class="col-xs-12">

                                    <div class="row">
                                        {{Form::open(['method'=>'post','route'=>'admin.salesman.save','files'=>'true'])}}
                                        <div class="col-md-12">
                                            <div class="demo-box">

                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>Salesman Name <span style="color: red">*</span></label>
                                                            {{Form::text('salesman_name',null,['required','class'=>'form-control'. $errors->first('salesman_name', ' error')])}}
                                                            @if ($errors->has('salesman_name'))
                                                                <p class="help-block">This field is required</p>
                                                            @endif
                                                        </div>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>Salesman Area <span style="color: red">*</span></label>
                                                            {{Form::text('salesman_area',null,['required','class'=>'form-control'])}}

                                                        </div>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>Salesman Code <span style="color: red">*</span></label>
                                                            {{Form::text('salesman_code',null,['required','class'=>'form-control'])}}

                                                        </div>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>Salesman Mobile <span style="color: red">*</span></label>
                                                            {{Form::text('salesman_mobile',null,['required','class'=>'form-control'])}}

                                                        </div>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>Salesman Email (This mail id use for login) <span style="color: red">*</span></label>
                                                            {{Form::text('salesman_email',null,['required','class'=>'form-control'])}}

                                                        </div>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>Password (This password use for login) <span style="color: red">*</span></label>
                                                            {{Form::text('salesman_password',null,['required','class'=>'form-control'])}}

                                                        </div>
                                                    </div>

                                                <div class="col-md-12">
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

        <script src="{{asset('public/adminpanel/default/assets/js/jquery-1.12.4.js')}}"></script>
        <script>
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
@endsection
