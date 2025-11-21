@extends('admin.layout.master')

@section('title', 'Add Packer')

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
                            <h4 class="page-title">Add New Packer</h4>
                            <ol class="breadcrumb p-0 m-0">
                                <li>
                                    <a href="#">{{Session::get('software_title')}}</a>
                                </li>

                                <li>
                                    <a href="{{url('item/packer/list')}}">Packer List </a>
                                </li>
                                <li class="active">
                                    Add Packer
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

                                    <div class="row justify-content-md-center">
                                        {{Form::open(['method'=>'post','route'=>'post.packer_save'])}}
                                        <div class="col-md-12">
                                            <div class="demo-box">

                                                <div class="row">
                                                    <div class="col-sm-12">

                                                        <h3>Organization Details</h3>

                                                    </div>

                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>Packer Name <span style="color: red">*</span></label>
                                                            {{Form::text('manufacturer_name',null,['class'=>'form-control'. $errors->first('manufacturer_name', ' error')])}}
                                                            @if ($errors->has('manufacturer_name'))
                                                                <p class="help-block">{{ $errors->first('manufacturer_name') }}</p>
                                                            @endif
                                                        </div>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>Primary Phone <span style="color: red">*</span></label>
                                                            {{Form::text('primary_phone',null,['class'=>'form-control'. $errors->first('primary_phone', ' error')])}}
                                                            @if ($errors->has('primary_phone')) <p class="help-block">This field is required</p> @endif

                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col-md-6">

                                                        <div class="form-group">
                                                            <label>Industry </label>
                                                            {{Form::select('industry',$industry,null,['class'=>'form-control'])}}

                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label> Secondary Phone </label>
                                                            {{Form::text('secondary_phone',null,['class'=>'form-control'])}}

                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col-md-6">

                                                        <div class="form-group">
                                                            <label>Company Type</label>
                                                            {{Form::select('type',$type,null,['class'=>'form-control'])}}

                                                        </div>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label> Primary Email <span style="color: red">*</span></label>
                                                            {{Form::email('primary_email',null,['class'=>'form-control'.$errors->first('primary_email',' error')])}}

                                                            @if($errors->has('primary_email'))
                                                                <p class="help-block">This field is required</p>
                                                            @endif

                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row">
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
                                                </div>

                                                <div class="row">
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

                                                </div>
                                                <div class="row">

                                                    <div class="col-lg-6 align-self-center">
                                                        <div class="form-check form-check-inline" style="float: left;padding: 7px;margin: 5px;">
                                                            <input name="radio-button" id="ab0f36521" class="form-check-input ember-view" type="radio" value="true">
                                                            <label class="form-check-label" for="ab0f36521">Taxable</label> </div>
                                                        <div class="form-check form-check-inline" style="float: left;padding: 7px;margin: 5px;">
                                                            <input name="radio-button" id="af103842b" class="form-check-input ember-view" type="radio" value="false">
                                                            <label class="form-check-label" for="af103842b">Tax Exempt</label>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label>Payement Terms</label>
                                                        {{Form::select('payment_terms',[''=>'select','Net 15'=>'Net 15','Net 30'=>'Net 30','Net 45'=>'Net 45','Net 60'=>'Net 60'],null,['class'=>'form-control'])}}
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-sm-12">

                                                    <h3>Organization Contact Person Information</h3>

                                                </div>
                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label>Full Name <span style="color: red">*</span></label>
                                                        {{Form::text('owner_name',null,['class'=>'form-control'.$errors->first('owner_name',' error')])}}
                                                        @if($errors->has('owner_name'))
                                                            <p class="help-block">This field is required</p>
                                                        @endif

                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label>Mobile No <span style="color: red">*</span></label>
                                                        {{Form::text('owner_mobile',null,['class'=>'form-control'.$errors->first('owner_mobile',' error')])}}
                                                        @if($errors->has('owner_mobile'))
                                                            <p class="help-block">This field is required</p>

                                                        @endif

                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label>Alternate No.<span style="color: red">*</span></label>
                                                        {{Form::text('alternate_no',null,['class'=>'form-control'.$errors->first('alternate_no',' error')])}}
                                                        @if($errors->has('alternate_no'))
                                                            <p class="help-block">This field is required</p>
                                                        @endif

                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label>Personal Email <span style="color: red">*</span></label>
                                                        {{Form::text('owner_email',null,['class'=>'form-control'.$errors->first('owner_email',' error')])}}
                                                        @if($errors->has('owner_email'))
                                                            <p class="help-block">This field is required</p>
                                                        @endif

                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label>Work Email <span style="color: red">*</span></label>
                                                        {{Form::text('work_email',null,['class'=>'form-control'.$errors->first('work_email',' error')])}}
                                                        @if($errors->has('work_email'))
                                                            <p class="help-block">This field is required</p>
                                                        @endif

                                                    </div>
                                                </div>

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



                                                <div class="row">
                                                    <div class="col-sm-12">

                                                        <h3>Address Information</h3>

                                                    </div>
                                                    <div class="col-md-4">

                                                        <div class="form-group">
                                                            <label>Billing Address <span style="color: red">*</span></label>
                                                            {{Form::textarea('billing_address',null,['class'=>'form-control'.$errors->first('billing_address',' error'),'cols'=>'1','rows'=>'1','style'=>'min-height: 38px !important','id'=>'billing_address'])}}
                                                            @if($errors->has('billing_address'))
                                                                <p class="help-block">This field is required</p>
                                                            @endif
                                                        </div>

                                                    </div>


                                                    <div class="col-md-2">
                                                        <div class="form-group">
                                                            <label>Country <span style="color: red">*</span></label>

                                                            {{Form::select('billing_country',$country,null,['class'=>'form-control'.$errors->first('billing_country',' error'),'id'=>"billing_country"])}}
                                                            @if($errors->has('billing_country'))
                                                                <p class="help-block">This field is required</p>
                                                            @endif
                                                        </div>
                                                    </div>

                                                    <div class="col-md-2">
                                                        <div class="form-group">
                                                            <label>State  <span style="color: red">*</span></label>

                                                            {{Form::select('billing_state',$state,null,['class'=>'form-control'.$errors->first('billing_state',' error'),'id'=>"billing_state"])}}
                                                            @if($errors->has('billing_state'))
                                                                <p class="help-block">This field is required</p>
                                                            @endif
                                                        </div>
                                                    </div>

                                                    <div class="col-md-2">
                                                        <div class="form-group">
                                                            <label>City   <span style="color: red">*</span></label>

                                                            {{Form::select('billing_city',$city,null,['class'=>'form-control'.$errors->first('billing_city',' error'),'id'=>"billing_city"])}}
                                                            @if($errors->has('billing_city'))
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
                                                <div class="col-md-12">
                                                    <label id="copy_billing" style="cursor: pointer;font-weight: 600">Copy to Shipping Address</label>
                                                </div>

                                                <div class="row">

                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label>Shipping Address  <span style="color: red">*</span></label>
                                                            {{Form::textarea('shipping_address',null,['class'=>'form-control'.$errors->first('shipping_address',' error'),'cols'=>'1','rows'=>'1','style'=>'min-height: 38px !important','id'=>'shipping_address'])}}

                                                            @if($errors->has('shipping_address'))
                                                                <p class="help-block">This field is required</p>
                                                            @endif
                                                        </div>
                                                    </div>

                                                    <div class="col-md-2">
                                                        <div class="form-group">
                                                            <label>Country <span style="color: red">*</span></label>
                                                            {{Form::select('shipping_country',$country,null,['class'=>'form-control'.$errors->first('shipping_country',' error'),'id'=>'shipping_country'])}}

                                                            @if($errors->has('shipping_country'))
                                                                <p class="help-block">This field is required</p>
                                                            @endif
                                                        </div>
                                                    </div>

                                                    <div class="col-md-2">
                                                        <div class="form-group">
                                                            <label>State <span style="color: red">*</span></label>
                                                            {{Form::select('shipping_state',$state,null,['class'=>'form-control'.$errors->first('shipping_state',' error'),'id'=>"shipping_state"])}}

                                                            @if($errors->has('shipping_state'))
                                                                <p class="help-block">This field is required</p>
                                                            @endif
                                                        </div>
                                                    </div>

                                                    <div class="col-md-2">
                                                        <div class="form-group">
                                                            <label>City  <span style="color: red">*</span></label>
                                                            {{Form::select('shipping_city',$city,null,['class'=>'form-control'.$errors->first('shipping_city',' error'),'id'=>'shipping_city'])}}

                                                            @if($errors->has('shipping_city'))
                                                                <p class="help-block">This field is required</p>
                                                            @endif
                                                        </div>
                                                    </div>


                                                    <div class="col-md-2">
                                                        <div class="form-group">
                                                            <label>Postal Code  <span style="color: red">*</span></label>
                                                            {{Form::text('shipping_postalcode',null,['class'=>'form-control'.$errors->first('billing_city',' error'),'id'=>'shipping_postalcode'])}}

                                                            @if($errors->has('shipping_postalcode'))
                                                                <p class="help-block">This field is required</p>
                                                            @endif
                                                        </div>
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
        <script src="https://cdn.ckeditor.com/ckeditor5/20.0.0/classic/ckeditor.js"></script>
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

@endsection
