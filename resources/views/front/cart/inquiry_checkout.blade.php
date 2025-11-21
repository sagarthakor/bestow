

@extends('front.includes.master')

@section('title',"checkout")

@section('content')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <div class="container">

        <div class="cart-page">
            <div class="breadcrumb-area">
                <ul>
                    <li><a href="{{url('index')}}">Home</a></li>
                    <li><span>Inquiry</span></li>
                </ul>
            </div>
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            @if(session()->has("message"))
                <div class="alert alert-danger">
                    {{session()->get("message")}}
                </div>
            @endif
            {{Form::model($data,['method'=>'post','route'=>'inquiry_placeorder'])}}
            <div class="row">
                <div class="col-md-12">
                    <div class="form-title">
                        <h1>Order Review</h1>
                    </div>
                    <div class="order-review">
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                <tr>
                                    <th>Image</th>
                                    <th>Product</th>
                                    <th>Quantity</th>
                                    <th>Price</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php $total = 0 ?>
                                @if(session('inquiry'))

                                    @foreach(session('inquiry') as $id => $details)

                                        <?php
                                        //print_r($details);
                                        $total += $details['price'] * $details['quantity'];
                                        ?>
                                        <tr>
                                            <td> <a href="{{url('product-details/c/s/'.$details['name'])}}">
                                                    <img style="height: 80;width: 80px" src="/product_image/{{$details['photo'] }}" alt="">
                                                </a></td>
                                            <td>{{$details['name']}}</td>

                                            <td>{{$details['quantity']}}</td>

                                            <td><i class="fas fa-rupee-sign"></i>@if($details['price']==null) - @else {{$details['price']}} @endif</td>
                                        </tr>
                                    @endforeach
                                @endif

                                <tr>
                                    <th style="text-align: right" colspan="3">Subtotal:</th>
                                    <th> @if($total==null) - @else <i class="fas fa-rupee-sign"></i> {{$total}} @endif</th>
                                </tr>

                                <tr>
                                    <th style="text-align: right" colspan="3">Total:</th>

                                    <th><span class="cart-total"> @if($total==null) - @else <i class="fas fa-rupee-sign"></i> {{$total}}.00 @endif</span></th>
                                </tr></tbody>
                            </table>

                            <div style="padding-left: 70%">
                                <div class="form-group">
                                    <label>Sales Person Code</label>
                                    {{Form::select('salesman',$salesman,null,['required'])}}
                                </div>
                                <div class="place-order">
                                    <button style="width: 100%" id="placebtn" class="btn theme-btn">Place Order</button>
                                </div>
                            </div>




                        </div>
                    </div>
                </div>
                <div class="col-md-12">

                    <div class="row">
                        <div class="col-sm-12">

                            <h3>Organization Details</h3>

                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Customer Name <span style="color: red">*</span></label>
                                {{Form::text('customer_name',null,['class'=>'form-control'. $errors->first('customer_name', ' error')])}}
                                @if ($errors->has('customer_name'))
                                    <p class="help-block">{{ $errors->first('customer_name') }}</p>
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
                                {{Form::select('industry',$industry,null,['class'=>'form-control1'])}}

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
                                {{Form::select('type',$type,null,['class'=>'form-control1'])}}

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
                                {{Form::radio("tax_preference","true",['class'=>'form-check-input ember-view','value'=>'true'])}}
                                <label class="form-check-label" for="ab0f36521">Taxable</label> </div>
                            <div class="form-check form-check-inline" style="float: left;padding: 7px;margin: 5px;">
                                {{Form::radio("tax_preference","false",['class'=>'form-check-input ember-view','value'=>'false'])}}
                                <label class="form-check-label" for="af103842b">Tax Exempt</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label>Payement Terms</label>
                            {{Form::select('payment_terms',$payment_terms,null,['class'=>'form-control1'])}}
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-12">

                            <h3>Organization Contact Person Information</h3>

                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Full Name <span style="color: red">*</span></label>
                                {{Form::text('owner_name',null,['class'=>'form-control'.$errors->first('owner_name',' error')])}}
                                @if($errors->has('owner_name'))
                                    <p class="help-block">This field is required</p>
                                @endif

                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Mobile No <span style="color: red">*</span></label>
                                {{Form::text('owner_mobile',null,['class'=>'form-control'.$errors->first('owner_mobile',' error')])}}
                                @if($errors->has('owner_mobile'))
                                    <p class="help-block">This field is required</p>

                                @endif

                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Alternate No.<span style="color: red">*</span></label>
                                {{Form::text('alternate_no',null,['class'=>'form-control'.$errors->first('alternate_no',' error')])}}
                                @if($errors->has('alternate_no'))
                                    <p class="help-block">This field is required</p>
                                @endif

                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Personal Email <span style="color: red">*</span></label>
                                {{Form::text('owner_email',null,['class'=>'form-control'.$errors->first('owner_email',' error')])}}
                                @if($errors->has('owner_email'))
                                    <p class="help-block">This field is required</p>
                                @endif

                            </div>
                        </div>
                        <div class="col-md-6">
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

                                    {{Form::select('billing_country',$country,null,['onchange'=>'billing_country_f()','class'=>'form-control1'.$errors->first('billing_country',' error'),'id'=>"billing_country"])}}
                                    @if($errors->has('billing_country'))
                                        <p class="help-block">This field is required</p>
                                    @endif
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>State  <span style="color: red">*</span></label>

                                    {{Form::select('billing_state',$state,null,['onchange'=>'billing_state()','class'=>'form-control1'.$errors->first('billing_state',' error'),'id'=>"billing_state"])}}
                                    @if($errors->has('billing_state'))
                                        <p class="help-block">This field is required</p>
                                    @endif
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>City   <span style="color: red">*</span></label>

                                    {{Form::select('billing_city',$city,null,['class'=>'form-control1'.$errors->first('billing_city',' error'),'id'=>"billing_city"])}}
                                    @if($errors->has('billing_city'))
                                        <p class="help-block">This field is required</p>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>Zip Code  <span style="color: red">*</span></label>

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
                                    {{Form::select('shipping_country',$country,null,['class'=>'form-control1'.$errors->first('shipping_country',' error'),'id'=>'shipping_country'])}}

                                    @if($errors->has('shipping_country'))
                                        <p class="help-block">This field is required</p>
                                    @endif
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>State <span style="color: red">*</span></label>
                                    {{Form::select('shipping_state',$state,null,['class'=>'form-control1'.$errors->first('shipping_state',' error'),'id'=>"shipping_state"])}}

                                    @if($errors->has('shipping_state'))
                                        <p class="help-block">This field is required</p>
                                    @endif
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>City  <span style="color: red">*</span></label>
                                    {{Form::select('shipping_city',$city,null,['class'=>'form-control1'.$errors->first('shipping_city',' error'),'id'=>'shipping_city'])}}

                                    @if($errors->has('shipping_city'))
                                        <p class="help-block">This field is required</p>
                                    @endif
                                </div>
                            </div>


                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>Zip Code  <span style="color: red">*</span></label>
                                    {{Form::text('shipping_postalcode',null,['class'=>'form-control'.$errors->first('billing_city',' error'),'id'=>'shipping_postalcode'])}}

                                    @if($errors->has('shipping_postalcode'))
                                        <p class="help-block">This field is required</p>
                                    @endif
                                </div>
                            </div>
                        </div>


                    </div>
                </div>

            </div>
            </form>

        </div>
    </div>

    </div>
    </div>
    </div>

    <script src="/adminpanel/default/assets/js/jquery-1.12.4.js"></script>
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
