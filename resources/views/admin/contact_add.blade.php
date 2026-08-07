@extends('admin.layout.master_material')

@section('title', 'Add Contact')

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
                            <h4 class="page-title">Add New Contact</h4>
                            <ol class="breadcrumb p-0 m-0">
                                <li>
                                    <a href="#">{{Session::get('software_title')}}</a>
                                </li>
                                <li>
                                    <a href="{{url('client/customer/contact')}}">Contact List </a>
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
                                        {{Form::open(['method'=>'post','route'=>'admin.contact.save','files'=>'true'])}}
                                        <div class="col-md-12">
                                            <div class="demo-box">

                                            <div class="row">
                                             <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Contact Name <span style="color: red">*</span></label>
                                                    {{Form::text('contact_name',null,['class'=>'form-control'. $errors->first('contact_name', ' error')])}}
                                                     @if ($errors->has('contact_name'))
                                                      <p class="help-block">This field is required</p>
                                                     @endif
                                                </div>
                                            </div>

                                              <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Customer <span style="color: red">*</span></label>
                                                    {{Form::select('customer',$customer,null,['class'=>'form-control'. $errors->first('customer', ' error')])}}
                                                    @if ($errors->has('customer'))
                                                      <p class="help-block">This field is required</p>
                                                     @endif

                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Office Phone </label>
                                                    {{Form::text('office_phone',null,['class'=>'form-control'])}}

                                                </div>
                                            </div>

                                              <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Office Email </label>
                                                    {{Form::email('office_email',null,['class'=>'form-control'])}}

                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Primary Phone <span style="color: red">*</span></label>
                                                    {{Form::text('primary_phone',null,['class'=>'form-control'. $errors->first('primary_phone', ' error')])}}
                                                     @if ($errors->has('primary_phone'))
                                                      <p class="help-block">This field is required</p>
                                                     @endif
                                                </div>
                                            </div>

                                             <div class="col-md-6">
                                                <div class="form-group">
                                                    <label> Primary Email <span style="color: red">*</span></label>
                                                    {{Form::email('primary_email',null,['class'=>'form-control'. $errors->first('primary_email', ' error')])}}
                                                    @if ($errors->has('primary_email'))
                                                      <p class="help-block">This field is required</p>
                                                     @endif
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">

                                             <div class="col-md-6">
                                                <div class="form-group">
                                                    <label> Department</label>
                                                    {{Form::text('department',null,['class'=>'form-control'])}}

                                                </div>
                                            </div>

                                              <div class="col-md-6">
                                                <div class="form-group">
                                                    <label> Designation </label>

                                                    {{Form::text('designation',null,['class'=>'form-control'])}}
                                                </div>
                                            </div>

                                        </div>

                                         <div class="row">
                                             <div class="col-md-12">
                                                <div class="form-group">
                                                    <label>Description</label>
                                                    {{Form::text('description',null,['class'=>'form-control'])}}

                                                </div>
                                            </div>

                                        </div>
                                          <!--   <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Photo</label>
                                                    {{Form::file('photo',['class'=>'form-control'])}}

                                                </div>
                                            </div> -->

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
