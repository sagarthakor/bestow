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
             @if(session()->has("register_message"))
                 <strong style="color:green;">{{session()->get("register_message")}}</strong>
             @endif
             @if(session()->has("message"))
                 <strong style="color:red;">{{session()->get("message")}}</strong>
             @endif
             {{Form::open(['method'=>'post','route'=>'login.email.check'])}}
               <div class="login-form">
                  <h2 class="page-title">Login</h2>
                  <div class="row">
                     <div class="col-lg-6 col-md-8 offset-lg-3 offset-md-2">
                        <div class="form-group">
                           <label for="email">Email <span class="required">*</span></label>
                           <input type="email" name="email" id="email">
                        </div>
{{--                        <div class="form-group">--}}
{{--                           <label for="password">Password <span class="required">*</span></label>--}}
{{--                           <input type="password" id="password">--}}
{{--                        </div>--}}
                        <div class="form-group">
                          <button class="btn theme-btn mr-2" style="width: 100%">Login</button>

                        </div>
                         <div class="form-group">
                             <a style="width: 100%;" href="{{url('register')}}" class="btn theme-btn mr-2">Register</a>
                         </div>
                     </div>
                  </div>
               </div>
            </form>
         </div>
  @endsection
