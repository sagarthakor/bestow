@extends('front.includes.master')

@section('title')

@section('content')
    <div class="container">

        <div class="login-page">
            <div class="breadcrumb-area">
                <ul>
                    <li><a href="{{url('index')}}">Home</a></li>
                    <li><a href="{{url('login')}}"><span>Login</span></a></li>
                </ul>
            </div>

            {{Form::open(['method'=>'post','route'=>'login.verify.otp'])}}


            <br>

            <div class="login-form">
                <h2 class="page-title">Login</h2>
                @if(session()->has("register_message"))
                    <strong style="color:green;">{{session()->get("register_message")}}</strong>
                @endif

                <div class="row">
                    <div class="col-lg-6 col-md-8 offset-lg-3 offset-md-2">
                        @if(session()->has('message'))
                            <label style="color:red;">{{session()->get('message')}}</label><br>
                        @endif
                            <label style="color: green">OTP is send to your email id</label>
                            <br>
                        <label>{{$email}}</label><br>
                        <strong style="color:red;"><label style="color: red">{{$message ?? ""}}</label></strong>
                        <div class="form-group">
                            <label for="email">Password <span class="required">*</span></label>
                            <input type="hidden" name="email" value="{{$email}}">
                            <input required type="text" name="otp" id="otp">
                        </div>
                        {{--                        <div class="form-group">--}}
                        {{--                           <label for="password">Password <span class="required">*</span></label>--}}
                        {{--                           <input type="password" id="password">--}}
                        {{--                        </div>--}}
                        <div class="form-group">
                            <button class="btn theme-btn mr-2" style="width: 100%">Verify</button>

                        </div>

                    </div>
                </div>
            </div>
            </form>
        </div>
@endsection
