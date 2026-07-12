

@extends('front.includes.master')

@section('title')

@section('content')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <div class="container">

        <div class="checkout-page">
            <div class="breadcrumb-area">
                <ul>
                    <li><a href="index.php">Home</a></li>
                    <li><span>Checkout</span></li>
                </ul>
            </div>
            <ul class="nav nav-tabs justify-content-center" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" href="#checkout" role="tab" data-toggle="tab" style="    padding: 20px;">Order Details</a>
                </li>
{{--                <li class="nav-item">--}}
{{--                    <a class="nav-link" href="#successful" role="tab" data-toggle="tab" style="    padding: 20px;">Order Successful</a>--}}
{{--                </li>--}}
                <li class="nav-item">
                    <a class="nav-link" href="#profile" role="tab" data-toggle="tab" style="    padding: 20px;">User Profile</a>
                </li>
            </ul>
            @if(session()->has("message"))
                <div class="alert alert-success">
                    <strong>{{session()->get('message')}}</strong>
                </div>
            @endif
            <div class="tab-content">
                <div role="tabpanel" class="tab-pane active" id="checkout">
                    <div class="checkout-form" >
                        <h2 class="page-title mb-5" ></h2>
                        <div class="row">
                            <div class="col-lg-12">

                                <div class="form-title">
                                    <h1>Order Summary</h1>
                                </div>

                                <div class="order-review">
                                    <div class="table-responsive">
                                        <table class="table">
                                            <thead>

                                            </thead>
                                            <tbody>
                                            @foreach($order as $order_details)
                                                <tr>
                                                    <td colspan="4">
                                                        <table class="table">
                                                            <tr>
                                                                <td >Order No. : {{$order_details->order_number}}</td>
                                                                <td>Order Date. : {{date('d-m-Y',strtotime($order_details->order_date))}}</td>
                                                                <td >Salesman Code. : {{$order_details->salesman}}</td>
                                                            </tr>
                                                        </table>
                                                    </td>

                                                </tr>
                                                <tr>
                                                    <th>Images</th>
                                                    <th>Product</th>
                                                    <th>Quantity</th>
                                                    <th>Price</th>
                                                </tr>
                                                @foreach($order_item as $item)
                                                    @if($item->order_no == $order_details->order_number)
                                                <tr>
                                                    <td class="product-thumbnail" style="width: 20px;">
                                                        <a href="view.php"><img src="{{asset('public/product_image/'.$item->product_image)}}" alt=""></a>
                                                    </td>
                                                    <td>{{$item->product_name}}</td>
                                                    <td>{{$item->qty}}</td>
                                                    <td>{{$item->price}}</td>
                                                </tr>
                                                    @endif
                                                @endforeach
                                            @endforeach


                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                            </div>

                        </div>
                    </div>
                </div>
                <!-- -------------------------------- -->
                <div role="tabpanel" class="tab-pane fade" id="successful">
                    <div class="checkout-form" >
                        <h2 class="page-title mb-5" ></h2>
                        <div class="row">

                            <div class="col-lg-6 mx-auto">
                                <div class="right-order-form">
                                    <div class="form-title">
                                        <h1 class="text-center">Thank You For Your Order!<br><span><h6>Order Successful</h6></span></h1>

                                    </div>


                                    <div class="order-review">
                                        <div class="table-responsive">
                                            <table class="table">
                                                <thead>
                                                <tr>
                                                    <th>Product</th>
                                                    <th>Quantity</th>
                                                    <th>Price</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <tr>
                                                    <td>Lorem ism</td>
                                                    <td>1</td>
                                                    <td>$50.00</td>
                                                </tr>
                                                <tr>
                                                    <td>Jsm Korem</td>
                                                    <td>1</td>
                                                    <td>$40.00</td>
                                                </tr>
                                                <tr>
                                                    <th>Subtotal:</th>
                                                    <th></th>
                                                    <th>$90.00</th>
                                                </tr>
                                                <tr>
                                                    <th>Shipping:</th>
                                                    <th></th>
                                                    <th>$20.00</th>
                                                </tr>
                                                <tr>
                                                    <th>Total:</th>
                                                    <th></th>
                                                    <th><span class="cart-total">$200.00</span></th>
                                                </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- -------------------------------- -->
                <div role="tabpanel" class="tab-pane fade" id="profile">
                    <div class="checkout-form" >
                        <h2 class="page-title mb-5" ></h2>
                        <div class="row">

                            <div class="col-lg-12 mx-auto">
                                <div class="right-order-form">
                                    <div class="form-title">
                                        <h1 class="text-center">Your Proifile</h1>
                                    </div>

                                    {{Form::model($details,['method'=>'post','route'=>'front.customer.update'])}}
                                    {{Form::hidden('id',null)}}
                                    <div class="row">
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
                                                <div class="col-md-2">
                                                <button class="btn btn-success">Save</button>
                                                </div>
                                                {{Form::close()}}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>



        </div>
    </div>
@endsection
