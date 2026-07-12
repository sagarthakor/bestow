 <!DOCTYPE html>
<html class="no-js" lang="en">
   <meta http-equiv="content-type" content="text/html;charset=utf-8" />
<head>
      <meta charset="utf-8">
      <meta http-equiv="x-ua-compatible" content="ie=edge">
      <title>BESTOW - @yield('title')</title>
      <meta name="description" content="">
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <link rel="shortcut icon" href="/assets/images/favicon.ico">
      <!-- FontAwesome -->
      <link rel="stylesheet" href="/front/css/all.css">
      <!-- Bootstrap v4.3.1 -->
      <link rel="stylesheet" href="/front/css/bootstrap.min.css">
      <!-- Google Fonts -->
      <link href="https://fonts.googleapis.com/css?family=Poppins:300,400,500" rel="stylesheet">
      <link href="https://fonts.googleapis.com/css?family=Oswald:300,400" rel="stylesheet">
      <!-- Global CSS -->
      <link rel="stylesheet" href="/front/css/normalize.css">
      <link rel="stylesheet" href="/front/css/main.css">
      <!-- Jquery ui -->
      <link rel="stylesheet" href="/front/css/jquery-ui.css">
      <!-- Responsive Menu CSS -->
      <link rel="stylesheet" href="/front/css/meanmenu.css">
      <!-- Theme CSS -->
      <link rel="stylesheet" href="/front/css/styles.css">
    <style>
        .error{
            border: 1px solid red !important;
        }
        .help-block{
            color:red !important;
        }
    </style>
</head>
   <body>
      <div class="container">
      <header class="header">
            <div class="topcontain">
               <div class="row">
                  <div class="col-xl-2 col-lg-3 col-md-3 col-sm-12">
                     <div class="logo">
                        <a href="{{url('/')}}">
                            <?php
                            $logo=\App\company::select("logo")->first();
                            ?>
                            <img src="/company_logo/{{$logo->logo}}" alt="Logo" />
                        </a>
                        <!-- <h6>Laxmi Sales Corporation</h6> -->
                     </div>
                  </div>
                  <div style="margin-top:25px" class="col-xl-5 col-lg-3 col-md-2 col-sm-12">
                     <div class="menubar">
                        <nav id="mobile-menu">
                           <ul>
                              <li>
                                 <a href="{{url('index')}}">Home</a>
                              </li>
                              <li>
                                 <a href="{{url('about_us')}}">About Us</a>
                              </li>

                              <li>
                                 <a href="#">Products</a>
                                 <ul class="mega-menu">
                                     @foreach($maincategory as $cat)
                                    <li>
                                       <a href="{{url('product-category/'.$cat->category_name)}}">{{$cat->category_name}}</a>
                                       <ul>
                                           @foreach($mainsubcategory as $subcat)
                                                @if($subcat->category==$cat->id)
                                                   <li><a href="{{url('products/'.$cat->category_name.'/'.$subcat->subcategory_name)}}">{{$subcat->subcategory_name}}</a></li>
                                               @endif
                                           @endforeach
                                       </ul>
                                    </li>
                                     @endforeach
                                 </ul>
                              </li>

                              <li><a href="{{url('contact')}}">Contact</a></li>

                           </ul>
                        </nav>
                     </div>
                  </div>
                  <div style="margin-top:25px" class="col-xl-5 col-lg-6 col-md-7 col-sm-12">
                     <div class="head_right">
                        <div class="login_bar">
                           <ul>
                               @if(session()->has('customer_session'))
                                   <li><a href="{{url('user/logout')}}">Logout</a></li>
                                   <li><a href="{{url('user/order')}}">Your Order</a></li>
                               @else
                                   @if(session()->has('salesman_session'))
                                       @else
                                        <li><a href="{{url('customer_login')}}">Login</a></li>
                                   @endif
                               @endif

                                   @if(session()->has('salesman_session'))
                                       <li><a href="{{url('salesman/logout')}}">Salesman Logout</a></li>
                                       <li><a href="{{url('salesman/order/list')}}">Orders</a></li>
                                   @else
                                       @if(session()->has('customer_session'))
                                       @else
                                       <li><a href="{{url('salesman/login')}}">Salesman Login</a></li>
                                       @endif
                                   @endif

                              <li><a href="{{url('register')}}">Register</a></li>
                              <li class="shop-cart">
                                 <a href="{{url('cart')}}">Cart<span>({{ count((array) session('cart')) }})</span></a>
                                 <ul class="minicart">
                                     <?php $total = 0 ?>
                                     @if(session('cart'))

                                         @foreach(session('cart') as $id => $details)

                                             <?php
                                             //print_r($details)
                                             $cartprice=$details['price'] ?? 0;
                                             $cartimage=$details['photo'] ?? "no_image.png";
                                             $cartname=$details['name'] ?? "";
                                             $total += $cartprice * $details['quantity'];
                                             ?>
                                             <li>

                                                         <div class="cart-img">
                                                             <img alt="Abridge" src="/product_image/{{$cartimage}}">
                                                         </div>

                                                         <div class="cart-content">
                                                             <h3>{{$cartname}}</h3>
                                                             <div class="cart-price">
                                                                 @if($cartprice > 0)
                                                                 <span style="margin-left: 12px" class="new-price">@if($cartprice==null) - @else <i class="fas fa-rupee-sign"></i> {{$cartprice * $details['quantity']}} @endif</span>
                                                                 @endif

                                                             </div>
                                                         </div>

                                                         <div class="delete-icon">
                                                             <a onclick="remove_cart({{$id}})" href="#" class="close-cart">
                                                                 <i class="far fa-trash-alt"></i>
                                                             </a>
                                                         </div>


                                             </li>
                                         @endforeach
                                     @endif


                                    <li>
                                       <div class="total-price">
                                          <span class="f-left">Total:</span>
                                          <span class="f-right"> <i class="fas fa-rupee-sign"></i> {{$total}} </span>
                                       </div>
                                    </li>
                                    <li>
                                        <div class="row">
                                            <div class="col-md-6 col-sm-6" style="float: left">
                                                <div class="checkout-btn">
                                                    <a href="{{url("cart")}}">Cart</a>
                                                </div>
                                            </div>
                                            <div class="col-md-6 col-sm-6" style="float: left">
                                                <div class="checkout-btn">
                                                    <a href="{{url("checkout")}}">Check out</a>
                                                </div>
                                            </div>
                                        </div>

                                    </li>
                                 </ul>
                              </li>
                                   <li class="shop-cart">
                                       <a href="{{url('inquiry')}}">Inquiry<span>({{ count((array) session('inquiry')) }})</span></a>
                                       <ul class="minicart">
                                           <?php $total = 0 ?>
                                           @if(session('inquiry'))

                                               @foreach(session('inquiry') as $id => $details)

                                                   <?php
                                                   $cartprice=$details['price'] ?? 0;
                                                   $cartimage=$details['photo'] ?? "no_image.png";
                                                   $cartname=$details['name'] ?? "";
                                                   //print_r($details);
                                                   $total += $cartprice * $details['quantity'];
                                                   ?>
                                                   <li>

                                                       <div class="cart-img">
                                                           <img alt="Abridge" src="/product_image/{{$cartimage}}">
                                                       </div>

                                                       <div class="cart-content">
                                                           <h3>{{$cartname}}</h3>
                                                           <div class="cart-price">
                                                               @if($cartprice > 0)
                                                                   <span style="margin-left: 12px" class="new-price">@if($cartprice==0) - @else <i class="fas fa-rupee-sign"></i> {{$cartprice * $details['quantity']}} @endif</span>
                                                               @endif

                                                           </div>
                                                       </div>

                                                       <div class="delete-icon">
                                                           <a href="#" onclick="remove_inquiry({{$id}})" data-id="{{ $id }}" class="close-cart">
                                                               <i class="far fa-trash-alt"></i>
                                                           </a>
                                                       </div>


                                                   </li>
                                               @endforeach
                                           @endif


                                           <li>
                                               <div class="total-price">
                                                   <span class="f-left">Total:</span>
                                                   <span class="f-right"> <i class="fas fa-rupee-sign"></i> {{$total}} </span>
                                               </div>
                                           </li>
                                           <li>
                                               <div class="row">
                                                   <div class="col-md-6 col-sm-6" style="float: left">
                                                       <div class="checkout-btn">
                                                           <a href="{{url('inquiry')}}">Cart</a>
                                                       </div>
                                                   </div>
                                                   <div class="col-md-6 col-sm-6" style="float: left">
                                                       <div class="checkout-btn">
                                                           <a href="{{url('inquiry_checkout')}}">Check out</a>
                                                       </div>
                                                   </div>
                                               </div>

                                           </li>
                                       </ul>
                                   </li>
                           </ul>
                        </div>
                     </div>
                  </div>
                  <div class="col-12 d-xl-none">
                     <div class="mobile-menu"></div>
                  </div>
               </div>
            </div>
         </header>
      </div>


      @show
      @yield('content')

      <footer>
         <div class="container">
            <div class="footer-area">
               <div class="footer-content">
                  <ul>
                     <li>
                        <p>Customer Services</p>
                        <ul>
                           <li><a href="{{url('delivery-returns')}}">Delivery & Returns</a></li>
                           <li><a href="#">Trade Services</a></li>
                           <li><a href="{{url('privacy-policy')}}">Privacy & Policy</a></li>
                           <li><a href="#">FAQs</a></li>
                           <li><a href="{{url('terms-condition')}}">Terms & Condition</a></li>
                        </ul>
                     </li>
                     <li>
                        <p>Quick Links</p>
                        <ul>
                           <li><a href="{{url('index')}}">Home</a></li>
                           <li><a href="{{url('about_us')}}">About</a></li>
                           <li><a href="{{url('contact')}}">Contact</a></li>
                           <li><a href="{{url('inquiry')}}">Inquiry</a></li>
                        </ul>
                     </li>
                     <li>
                        <p>Social Media</p>
                        <ul>
                           <li><a href="#">Facebook</a></li>
                           <li><a href="#">Twitter</a></li>
                           <li><a href="#">Instagram</a></li>
                           <li><a href="#">Linkedin</a></li>
                           <li><a href="#">Youtube</a></li>
                        </ul>
                     </li>
{{--                     <li>--}}
{{--                        <p>Customer Services</p>--}}
{{--                        <ul>--}}
{{--                           <li><a href="#">Delivery & Returns</a></li>--}}
{{--                           <li><a href="#">Trade Services</a></li>--}}
{{--                           <li><a href="#">Contact Us</a></li>--}}
{{--                           <li><a href="#">FAQs</a></li>--}}
{{--                           <li><a href="#">Terms & Condition</a></li>--}}
{{--                        </ul>--}}
{{--                     </li>--}}
                  </ul>
               </div>
               <div class="copyright-area">
                  <div class="social">
                     <ul>
                        <li>
                           <p>Connect with us:</p>
                        </li>
                        <li><a href="#"><i class="fab fa-facebook"></i></a></li>
                        <li><a href="#"><i class="fab fa-twitter"></i></a></li>
                        <li><a href="#"><i class="fab fa-pinterest"></i></a></li>
                        <li><a href="#"><i class="fab fa-youtube"></i></a></li>
                        <li><a href="#"><i class="fab fa-instagram"></i></a></li>
                     </ul>
                  </div>
               </div>
            </div>
            <div id="top"><i class="fas fa-arrow-up"></i></div>
         </div>
         <!-- Global JS -->
         <script src="/front/js/vendor/modernizr-3.7.1.min.js"></script>
         <script src="/front/js/vendor/jquery-3.3.1.min.js"></script>
         <script src="/front/js/popper.min.js"></script>
         <script src="/front/js/plugins.js"></script>
         <script src="/front/js/bootstrap.min.js"></script>
         <script src="/front/js/jquery-ui.js"></script>
         <script src="/front/js/jquery.meanmenu.min.js"></script>
         <!-- Isotope JS -->
         <script src="/front/js/isotope.pkgd.min.js"></script>
         <script src="/front/js/imagesloaded.pkgd.min.js"></script>
         <!-- Custom JS -->
         <script src="/front/js/main.js"></script>
         <!-- Google Map JS -->
         <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBvEEMx3XDpByNzYNn0n62Zsq_sVYPx1zY"></script>
         <!-- Google Analytics: change UA-XXXXX-Y to be your site's ID. -->
         <script>
            window.ga = function () { ga.q.push(arguments) }; ga.q = []; ga.l = +new Date;
            ga('create', 'UA-XXXXX-Y', 'auto'); ga('set','transport','beacon'); ga('send', 'pageview')
         </script>

          <script type="text/javascript">
            function remove_cart(id)
            {
                $.ajax({
                url:'{{url('cart-remove-cart')}}',
                method:"get",
                data:{id:id},
                success:function(response)
                {
                    window.location.reload();
                }
                });
            }

            function remove_inquiry(id)
            {
                $.ajax({
                    url:'{{url('inquiry-remove-cart')}}',
                    method:"get",
                    data:{id:id},
                    success:function(response)
                    {
                        window.location.reload();
                    }
                });
            }

              $(".update-cart").click(function (e) {
                  e.preventDefault();
                  var ele = $(this);
                  $(".message").hide();
                  $.ajax({
                      url: '{{ url('update-cart') }}',
                      method: "post",
                      data: {_token: '{{ csrf_token() }}', id: ele.attr("data-id"), quantity: ele.parents("tr").find(".quantity").val(),custom_description: ele.parents("tr").find(".custom_description").val()},
                      success: function (response) {
                          $("#cart_message").show();
                          ele.parents("tr").find(".message").show();
                          //window.location.reload();
                      }
                  });
              });

              $(".update-inquiry").click(function (e) {
                  e.preventDefault();
                  var ele = $(this);
                  $(".message").hide();
                  $.ajax({
                      url: '{{ url('api/update-inquiry') }}',
                      method: "get",
                      data: {_token: '{{ csrf_token() }}', id: ele.attr("data-id"), quantity: ele.parents("tr").find(".quantity").val(),custom_description: ele.parents("tr").find(".custom_description").val()},
                      success: function (response) {
                          $("#cart_message").show();
                          ele.parents("tr").find(".message").show();
                          //window.location.reload();
                      }
                  });
              });

              $(".remove-from-cart").click(function (e) {
                  e.preventDefault();
                  var ele = $(this);
                  if(confirm("Are you sure")) {
                      $.ajax({
                          url: '{{ url('remove-from-cart') }}',
                          method: "post",
                          data: {_token: '{{ csrf_token() }}', id: ele.attr("data-id")},
                          success: function (response) {
                              window.location.reload();
                          }
                      });
                  }
              });


          </script>
      </footer>
   </body>
</html>
