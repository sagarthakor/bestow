@extends('front.includes.master')

@section('title')

@section('content')
    <div class="container">

        <div class="checkout-page">
            <div class="breadcrumb-area">
                <ul>
                    <li><a href="{{url('index')}}">Home</a></li>
                    <li><span>Checkout</span></li>
                </ul>
            </div>
            <form action="#">
                <div class="checkout-form">
                    <h2 class="page-title mb-5">Order Placed</h2>
                    <div class="row">
                        <h2>Thank You for Your Order<br>
                            Your Order No. {{$order_no}}</h2>
                    </div>
                </div>
            </form>
        </div>
    </div>

@endsection
