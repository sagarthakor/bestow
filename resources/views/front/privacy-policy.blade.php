@extends('front.includes.master')

@section('title',"Privacy Policy")

@section('content')
    <div class="container">

        <div class="login-page">
            <div class="breadcrumb-area">
                <ul>
                    <li><a href="{{url('index')}}">Home</a></li>
                    <li><a href="{{url('privacy-policy')}}"><span>Privacy Policy</span></a></li>
                </ul>
            </div>
            {{Form::open(['method'=>'post','route'=>'post.register_save'])}}
            <div class="login-form">
                <h2 class="page-title">Privacy Policy</h2>
                <div class="row">

                    <div class="col-xs-12">

                        <div class="row justify-content-md-center">
                            {{Form::open(['method'=>'post','route'=>'post.register_save'])}}
                            {{--                            @if ($errors->any())--}}
                            {{--                                <div class="alert alert-danger">--}}
                            {{--                                    <ul>--}}
                            {{--                                        @foreach ($errors->all() as $error)--}}
                            {{--                                            <li>{{ $error }}</li>--}}
                            {{--                                        @endforeach--}}
                            {{--                                    </ul>--}}
                            {{--                                </div>--}}
                            {{--                            @endif--}}
                            <div class="col-md-12">
                                <div class="demo-box">



                                </div>


                            </div>
                            {{Form::close()}}

                        </div><!-- end row -->


                    </div>

                </div>

            </div>
        </div>
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
                    url:appurl+'/clients/get_state',
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
                    url:appurl+'/clients/get_city',
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
                    url:appurl+'/clients/get_state',
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
                    url:appurl+'/clients/get_city',
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
