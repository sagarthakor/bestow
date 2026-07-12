

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

                                    {{Form::model($salesman,['method'=>'post','route'=>'front.post.salesman_update'])}}
                                    {{Form::hidden('id',Session::get('salesman_session'))}}
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Salesman Name <span style="color: red">*</span></label>
                                                {{Form::text('salesman_name',null,['class'=>'form-control'. $errors->first('salesman_name', ' error')])}}
                                                @if ($errors->has('salesman_name'))
                                                    <p class="help-block">This field is required</p>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Salesman Area </label>
                                                {{Form::text('salesman_area',null,['class'=>'form-control'])}}

                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Salesman Code </label>
                                                {{Form::text('salesman_code',null,['class'=>'form-control'])}}

                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Salesman Mobile </label>
                                                {{Form::text('salesman_mobile',null,['class'=>'form-control'])}}

                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Salesman Email </label>
                                                {{Form::text('salesman_email',null,['class'=>'form-control'])}}

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
                                    </div>
                                </div>
                            </div>
                        </div>



                    </div>
                </div>
@endsection
