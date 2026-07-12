@extends('front.includes.master')

@section('title',$product->product_name)

@section('content')
<style>
    .d-flex flex-column thumbnails{

        height: 505px !important;
    }

</style>

<div class="container">

         <div class="shop-detail">
            <div class="breadcrumb-area">
               <ul>
                  <li><a href="{{url('index')}}">Home</a></li>
                  <li><span>Shop</span></li>
               </ul>
            </div>


            <div class="row">

            <div class="col-xl-6 col-lg-6 col-12">
                  <div class="product-detail-img">
                     <div class="large-image">
                        <div class="tab-content" id="myTabContent-1">
                            <?php
                            $srno=1;
                            ?>

                           <div class="tab-pane fade show active" id="tab{{$srno}}" role="tabpanel" aria-labelledby="tab-{{$srno}}">
                              <img id="img1" src="/product_image/{{$product->cover_image}}" alt="image">
                           </div>
                           @if($product_multi_image)

                            @foreach($product_multi_image as $imgs)
                            <?php
                            $srno++;
                            ?>
                            <div class="tab-pane fade" id="tab{{$srno}}" role="tabpanel" aria-labelledby="tab-{{$srno}}">
                                <img  src="/product_image/{{$imgs->product_image}}" alt="image" data-pagespeed-url-hash="17684657" onload="pagespeed.CriticalImages.checkImageForCriticality(this);">
                            </div>
                            @endforeach
                           @endif
                        </div>
                     </div>
                     <div class="thumb-img-tabs">
                        <ul class="nav nav-tabs" id="myTab1" role="tablist">
                            <?php
                            $srno1=1;
                            ?>
                           <li class="nav-item">
                              <a class="nav-link active" id="tab-{{$srno1}}" data-toggle="tab" href="#tab{{$srno1}}" role="tab" aria-controls="tab{{$srno1}}" aria-selected="true">
                                  <img class="nav-img" style="max-width:100px !important;height:100px !important" src="/product_image/{{$product->cover_image}}" alt="Product image"></a>
                           </li>
                           @if($product_multi_image)
                           @foreach($product_multi_image as $imgss)
                           <?php
                            $srno1++;
                            ?>
                           <li class="nav-item">
                              <a class="nav-link" id="tab-{{$srno1}}" data-toggle="tab" href="#tab{{$srno1}}" role="tab" aria-controls="tab{{$srno1}}" aria-selected="false">
                                  <img style="max-width:100px !important;height:100px !important" src="/product_image/{{$imgss->product_image}}" alt="Product image" data-pagespeed-url-hash="312184578" onload="pagespeed.CriticalImages.checkImageForCriticality(this);"></a>
                           </li>
                           @endforeach
                           @endif

                        </ul>
                     </div>
                  </div>
               </div>


               <div class="col-xl-6 col-lg-6 col-12">
                   @if(session()->has("success"))
                       <div class="alert alert-success">
                           {{session()->get("success")}}
                       </div>
                   @endif
                  <div class="product-detail-content">
                     <div class="title">

                        <h1>{{$product->product_name}}</h1>
                     </div>
                     <div class="rating">
                        <div class="star">
                           <ul>
                              <li><i class="fas fa-star"></i></li>
                              <li><i class="fas fa-star"></i></li>
                              <li><i class="fas fa-star"></i></li>
                              <li><i class="fas fa-star"></i></li>
                              <li><i class="fas fa-star"></i></li>
                              <li><a href="#">5 Ratings</a></li>

                           </ul>
                        </div>
                     </div>
                     <div class="price">
                         <?php
                          $tax=$product->price."<br>";
                         $tax=$product->price*$product->gst_per/100;
                         $tax1=$tax;
                          $tax1."<br>";
                         $price1=$product->price+$tax1;
                           $price1."<br>";
                         $price=number_format((int)$price1, 2, '.', '');
                           $price."<br>";
                         ?>
                         @if($product->price_show_hide=="show")

                             <i class="fas fa-rupee-sign"></i> <?php echo $price;?>
                         @endif

{{--                        <span>$238.99</span>--}}
                     </div>
                     <input type="hidden" name="price" id="price_id" value="{{$price}}">
                     <div class="desc">
                        {!! $product->product_description !!}
                     </div>

                     <div class="attribute">

                         @if($product->mname)
                         <p><span style="width: 100px">Manufacturer </span> :  {{$product->mname}}</p>
                         @endif
                         @if($product->iname)
                         <p><span style="width: 100px">Importer </span> :  {{$product->iname}}</p>
                         @endif
                         @if($product->pname)
                         <p><span style="width: 100px">Packer </span> :  {{$product->pname}}</p>
                         @endif

                     </div>
                      {{Form::open(['method'=>'post','route'=>'add_to_cart','class'=>'cart-form'])}}
                      <input type="hidden" name="price" id="price_id1" value="{{$price}}">
                      <input type="hidden" name="id" id="product_id1" value="{{$product->id}}">
                      <input type="hidden" name="item_code" id="item_code" value="{{$product->item_code}}">
                      @foreach($product_attribute as $attribute_name)
                     <div class="{{$attribute_name->attribute_name}}">
                        <p>{{$attribute_name->attribute_name}}<span>*</span></p>
                        <select name="product_attribute[]">
                            <option value="{{$attribute_name->attribute_name}},{{$attribute_name->option_value}}">{{$attribute_name->option_value}}</option>
                        </select>
                     </div>
                      @endforeach

                     <div class="row">
                         @if(isset($product->value2))
                         <div class="col-md-6">
                            <div class="size">
                                <p>Size</p>
                                <select name="size[]" id="size" class="size" onchange="get_product_details(this.value)">
                                    <option value="">Select Size</option>
                                    <option value="{{$product->value2}}">{{$product->value2}}</option>
                                    @foreach($product1 as $size)
                                    @if($size->id != $product->id)
                                    <option value="{{$size->value2}}">{{$size->value2}}</option>
                                    @endif
                                    @endforeach
                                </select>
                            </div>
                         </div>
                         @endif
                         @if(isset($product->value1))
                         <div class="col-md-6">
                             <div class="size">
                                <p>Colour</p>
                                <select name="color[]" class="color" id="color" onchange="get_product_detail_image(this.value)">
                                    <option value="">Select Colour</option>
                                    <option value="{{$product->id}}">{{$product->value1}}</option>
                                    @foreach($product1 as $color)
                                    @if($color->id !=$product->id)
                                    <option value="{{$color->id}}">{{$color->value1}}</option>
                                    @endif
                                    @endforeach
                                </select>
                             </div>
                         </div>
                         @endif
                         <div class="col-md-12">
                             <div class="quantity">
                        <label>Quantity:</label>
                        <div class="cart-plus-minus">
                           <input type="text" name="quantity" value="1">
                           <div class="dec qtybutton">-</div>
                           <div class="inc qtybutton">+</div>
                        </div>

                     </div>
                         </div>
                     </div>

                     <!-- <form action="catalogue.php" class="cart-form catalogue">
                           <button class="btn theme-btn">Request Catalogue</button>
                        </form> -->
                     <div class="cart-options">
                        <!-- <form action="inquiry.php" class="cart-form">
                           <button class="btn theme-btn">Add To Inquire</button>
                        </form> -->
                        <div class="form-group">
                            <label>Custom Description</label>
                            <textarea name="custom_description" class="form-control"></textarea>
                        </div>

                         <button class="btn theme-btn" value="inquiry" name="inquiry"><i class="fa fa-shopping-cart"></i> Add To Inquiry</button>

                         <button class="btn theme-btn" value="cart" name="cart"><i class="fa fa-shopping-cart"></i> Add To Cart</button>

                         {{--
<button class="btn theme-btn" value="wishlist" name="wishlist"><i class="fa fa-heart"></i> Add to wishlist</button>--}}
                         {{Form::close()}}

                     </div>
                     <div class="share-it" style="display:none;">
                        <ul>
                           <li><label>Share it:</label></li>
                           <li><a href="#"><i class="fab fa-facebook-f"></i></a></li>
                           <li><a href="#"><i class="fab fa-twitter"></i></a></li>
                           <li><a href="#"><i class="fab fa-instagram"></i></a></li>
                           <li><a href="#"><i class="fab fa-pinterest"></i></a></li>
                        </ul>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
      <script>
          function get_product_details(product){
              $("#color").empty();
              var item_code = $("#item_code").val();

              var appurl="{{url('/')}}"
              $.ajax({
                  url:appurl+"/api/get_product_detail",
                  method:"get",
                  dataType: "json",
                  data:{size:product,item_code:item_code},
                  success:function(response){
                      // $("#price_id").val(response.price);
                      // $("#price_id1").val(response.price);
                      // $(".price_id").html(response.price);
                      // $("#product_id1").val(response.product_id);
                      // var pathimg="/product_image/"+response.product_image;
                      // $('#picimg').prop('src',pathimg);
                      //

                      $.each(response.product, function (index, value) {
                          // APPEND OR INSERT DATA TO SELECT ELEMENT.
                          $('#sel').append('<option value="' + value.value1 + '">' + value.value1 + '</option>');
                      });

                      var stringToAppend = "<option>select color</option>";

                      // Iterate over object and add options to select
                      $.each(response.color, function(index, value){
                          stringToAppend += "<option value='" + value.value1 + "'>" + value.value1 + "</option>";

                      });

                      $("#color").html(stringToAppend);



                      // $('#color').append(option);

                      //$("#color").html("<option value=''>select color</option><option>"+response.product+"</option>");
                      // $(".price").html("<i class='fas fa-rupee-sign'></i> "+response.price);
                  }
              });
          }

          function get_product_detail_image(product){

              $(".tab-pane").removeClass("active");
              $(".tab-pane").removeClass("show");

              $("#tab1").addClass("active show");

                var size = $('#size :selected').text();
                var color = $('#color :selected').text();
                var item_code = $('#item_code').val();
                console.log(size,color);
              var appurl="{{url('/')}}"
              $.ajax({
                  url:appurl+"/api/get_product_detail_image",
                  method:"get",
                  dataType: "json",
                  data:{size:size,color:color,item_code:item_code},
                  success:function(response){

                      $("#price_id").val(response.price);
                      $("#price_id1").val(response.price);
                      $(".price").html("<i class='fas fa-rupee-sign'>"+response.price);
                      $("#product_id1").val(response.product_id);

                      var pathimg="/product_image/"+response.image;
                      $('#img1').prop('src',pathimg);




                  }
              });
          }
      </script>
     @endsection
