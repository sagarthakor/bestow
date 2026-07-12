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

            {{Form::open(['method'=>'post','route'=>'sales.check.password'])}}

            <br>

            <div class="login-form">
                <h2 class="page-title">Login</h2>
                @if(session()->has("register_message"))
                    <strong style="color:green;">{{session()->get("register_message")}}</strong>
                @endif

                <div class="row">
                    <div class="col-lg-6 col-md-8 offset-lg-3 offset-md-2">
                        <label>{{$email}}</label><br>
                        <strong style="color:red;"><label style="color: red">{{$message ?? ""}}</label></strong>
                        <div class="form-group">
                            <label for="email">Password <span class="required">*</span></label>
                            <input type="hidden" name="email" value="{{$email}}">
                            <input required type="password" name="password" id="password">
                        </div>
                        {{--                        <div class="form-group">--}}
                        {{--                           <label for="password">Password <span class="required">*</span></label>--}}
                        {{--                           <input type="password" id="password">--}}
                        {{--                        </div>--}}
                        <div class="form-group">
                            <button class="btn theme-btn mr-2" style="width: 100%">Login</button>

                        </div>
                        <p class="checkbox-area">
                            <a href="{{url('sales/forgotpassword/'.$email)}}">Forgot password?</a>
                        </p>

                    </div>
                </div>
            </div>
            </form>
        </div>
@endsection
