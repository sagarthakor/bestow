@extends('front.includes.master')

@section('title',"Home")

@section('content')

      <div class="slidebar">
         <div id="carouselExampleIndicators" class="carousel slide carousel-fade" data-ride="carousel">
            <ol class="carousel-indicators">
               <li data-target="#carouselExampleIndicators" data-slide-to="0" class="active"></li>
               <li data-target="#carouselExampleIndicators" data-slide-to="1"></li>
               <li data-target="#carouselExampleIndicators" data-slide-to="2"></li>
            </ol>
            <div class="carousel-inner">
               <div class="carousel-item active" style="background-image: url(/front/images/slider/slider03-1.jpg)">

               </div>
               <div class="carousel-item" style="background-image: url(/front/images/slider/slider03-2.jpg)">

               </div>
               <div class="carousel-item" style="background-image: url(/front/images/slider/slider03-3.jpg)">

               </div>
            </div>
            <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="sr-only">Previous</span>
            </a>
            <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="sr-only">Next</span>
            </a>
         </div>
      </div>


      <div class="container mt-5">
         <div class="banner-area">
            <div class="row justify-content-center">

                @foreach($categories as $val)
                    <div class="banner-left col-lg-3 col-md-3 col-sm-10 ">
                        <div class="lg-banner banner">
                            <a href="{{url('product-category/'.$val->category_name)}}">
                                <img src="/product_category/{{$val->category_image}}" alt="Image">
                                <h2><span>{{$val->category_name}}</span></h2>
                            </a>
                        </div>
                    </div>
                @endforeach


{{--            <div class="row justify-content-center">--}}
{{--               <div class="banner-left col-lg-3 col-md-3 col-sm-10  ">--}}
{{--                  <div class="md-right banner">--}}
{{--                     <a href="{{url('t-shirt')}}">--}}
{{--                        <img src="{{asset('public/assets/images/Product/5.jpg')}}" alt="Image">--}}
{{--                        <h2><span>T-SHIRT</span></h2>--}}
{{--                     </a>--}}
{{--                  </div>--}}
{{--               </div>--}}
{{--               <div class="banner-left col-lg-3 col-md-3 col-sm-10 ">--}}
{{--                  <div class="lg-banner banner">--}}
{{--                     <a href="{{url('capry')}}">--}}
{{--                        <img src="{{asset('public/assets/images/Product/6.jpg')}}" alt="Image">--}}
{{--                        <h2><span>CAPRY</span></h2>--}}
{{--                     </a>--}}
{{--                  </div>--}}
{{--               </div>--}}
{{--               <div class="banner-left col-lg-3 col-md-3 col-sm-10 ">--}}
{{--                  <div class="md-left banner">--}}
{{--                     <a href="{{url('boxer')}}">--}}
{{--                        <img src="{{asset('public/assets/images/Product/7.jpeg')}}" alt="Image">--}}
{{--                        <h2><span>BOXERS</span></h2>--}}
{{--                     </a>--}}
{{--                  </div>--}}
{{--               </div>--}}

{{--            </div>--}}

         </div>
      </div>
      <div class="container">

         <div class="newsletter-area">
            <div class="newsletter">
               <h3>Tell Us What Are You Looking For ?</h3>

               <div class="newsletter-form">
                  <input type="text" class="newsl" name="newsletter" placeholder="Enter your name" >
                  <input type="tel" class="newsl" name="newsletter" placeholder="Enter your phone">
                  <textarea class="newsl" rows="5" id="message" placeholder="Description"></textarea><br>
                  <button class="newsletter-btn">Submit</button>
               </div>
            </div>
         </div>
      </div>
@endsection
