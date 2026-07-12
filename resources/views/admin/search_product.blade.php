@extends('admin.layout.table_master')

@section('title', 'List | Product')

@section('sidebar')
    @parent

@endsection

@section('content')
<style>
    .loader {
    position: fixed;
    left: 0px;
    top: 0px;
    width: 100%;
    height: 100%;
    z-index: 9999;
    background: url('https://lkp.dispendik.surabaya.go.id/assets/loading.gif') 50% 50% no-repeat rgb(249,249,249);
    display:none;
    }
</style>
        <div class="content-page">
            <!-- Start content -->
            <div class="content">
                <div class="container">


                    <div class="row">
                       <div class="col-xs-12">
                        <div class="page-title-box">
                            <h4 class="page-title">Products</h4>
                            <ol class="breadcrumb p-0 m-0">
                                <li>
                                    <a href="#">{{Session::get('software_title')}}</a>
                                </li>

                                <li class="active">
                                    Products
                                </li>
                            </ol>
                            <div class="clearfix"></div>
                        </div>
                    </div>
                </div>
                <!-- end row -->





            <div class="row">
                @if(session()->has('message'))
                <div class="col-sm-12">
                    <div class="alert alert-info" style="background-color: #188ae2 !important">
                        <strong style="color: #fff">{{session()->get('message')}}</strong>
                    </div>
                </div>
                @endif
                
                    <div class="col-sm-12">

                        <div class="card-box table-responsive">
                            <ul class="nav nav-tabs">
                                <li class="active"><a data-toggle="tab" href="#product">Product</a></li>
                                <!--<li><a data-toggle="tab" href="#service">Service</a></li>-->
                                <!--  <li><a data-toggle="tab" href="#menu2">Vendor</a></li> -->
                                <ul class="nav navbar-nav navbar-right">
                                    <li></li>
                                    <li></li>
                                </ul>
                            </ul>
                            <div  style="border:1px solid #ccc;">
                                <div style="display:inline-block;margin-right:5px;margin-top: 5px;width: 100%">
                                    <div class="col-md-6">
                                        <label>Customer Name</label>
                                        <select name="customer_name" id="customer_name"  class="submit_on_enter customer_name js-example-basic-single">
                                            <option value="">Select customer</option>
                                            @foreach($customer as $cu)
                                                <option value="{{$cu->id}}">{{$cu->customer_name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                   
                                </div>
                            </div>

                            <div class="tab-content">
                                <div id="product" class="tab-pane fade in active">

                                    <div class="card-box table-responsive" style="padding: 1px !important;">

                                        <div id="cart_item">
                                        </div>

                                        <div class="col-md-12" style="margin-bottom: 15px;">

                                            <div id="cart">
                                            </div>


                                            <div id="rel2">
                                                <div class="loader"></div>
                                            </div>
                                            <!-- <button type="addNewProduct" name="addNewProduct" id="addNewProduct">Add New Product</button> -->
                                            <div class="tablesearch">
                                                <!--{{Form::select('pagesize', array('1' => '1','5' => '5', '15' => '15','25' => '25','50' => '50'),null, array('class' => 'form-control','id'=>'pagesize'))}}-->
                                                <table class="table table-striped table-bordered">
                                                    <thead>
                                                    <tr>
                                                        <th>#.</th>
                                                        <th>Item Code</th>
                                                        <th>Product</th>
                                                        <th>Image</th>
                                                        <th>hsn</th>
                                                        <th>Price</th>
                                                        <th>GST</th>
                                                        <th>Qty</th>
                                                        <th>Select</th>

                                                    </tr>
                                                    </thead>

                                                    <tbody>
                                                    <tr>
                                                        <td><form method="get"></td>
                                                        <td>
                                                            <input placeholder="" name="item_code" type="text" value="@if(isset($_GET['item_code'])) {{$_GET['item_code']}} @endif" class="listSearchContributor inputElement">
                                                        </td>
                                                        <td>
                                                            <input placeholder="" name="product_name" type="text" value="@if(isset($_GET['product_name'])) {{$_GET['product_name']}} @endif" class="listSearchContributor inputElement">
                                                        </td>
                                                        <td></td>
                                                        <td></td>
                                                        <td><input name="prices" type="text" class="listSearchContributor inputElement"  value="@if(isset($_GET['prices'])) {{$_GET['prices']}} @endif"></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td><button class="btn btn-default">Search</button></form></td>
                                                    </tr>

                                                    <?php
                                                    $srno=$i=0;
                                                    ?>
                                                    @foreach($data as $list)
                                                        <?php

                                                        $srno++;
                                                       
                                                        ?>
                                                      
                                                            <tr>
                                                            <td>{{($data->currentPage() - 1) * $data->perPage() + $loop->iteration}}</td>
                                                            <td>
                                                                {{$list->item_code}}
                                                            </td>
                                                            <td style="width:20%">
                                                                <input type="hidden" name="product[]" class="product" value="{{$list->id}}">
                                                               {{$list->product_name}}
                                                            </td>
                                                            <td>
                                                                <img style="height:80px" src="{{asset('public/product_image/'.$list->product_image)}}"
                                                            </td>
                                                            <td>
                                                                {{$list->hsn}}
                                                            </td>
                                                           
                                                           
                                                            <td style="text-align: center;">
                                                                <input type="hidden" name="price[]" value="{{$list->price}}" class="price">{{$list->price,2}}
                                                            </td>

                                                            <td>{{$list->gst_per}}</td>
                                                            <td>
                                                                <input type="text" name="qty[]" class="qty" style="width:100px" id="qty{{$srno}}">
                                                                <br><span class="errorMsg" style="color:red"></span>
                                                            </td>

                                                            
                                                            <td style="text-align: center;">
                                                                <button class="btn btn-dark addCart" onclick="add_to_cart(this)" class="btn" data-id="{{$list->id}}" data-name="{{$list->product_name}}" data-summary="{{$list->description}}" data-price="{{$list->price}}" data-quantity="1" data-image="@if($list->product_image !=''){{asset('public/product_category/'.$list->category_image)}}@else{{asset('public/product_image/noimage.jpg')}}@endif
                                                                    ">Add to Cart</button>
                                                            </td>

                                                        </tr>
                                                    @endforeach
                                                    </tbody>
                                                </table>


                                                {!! $data->appends(Request::except('page'))->render() !!}

                                               <br>
                                              Displaying  {{($data->currentPage()-1)* $data->perPage() + 1}} to 
{{ ($data->currentPage()-1)* $data->perPage() + $data->perPage() }} from   
{{ $data->total() }} product(s).
<br>
<!--{{Form::select('pagesize', array('1' => '1','5' => '5', '15' => '15','25' => '25','50' => '50'),null, array('class' => 'form-control','id'=>'pagesize'))}}-->
                                            </div>

                                        </div>
                                    </div>
                                </div>


                                <div id="service" class="tab-pane fade">

                                    <div  style="border:1px solid #ccc;padding:10px;">
                                        <div style="display:inline-block;margin-right:5px;margin-top: 5px;">Service Name. : <input name="service_product_name" type="text" id="product_name1" value="{{$product_name ?? ''}}" style="width:80px;" class="submit_on_enter product_name"></div>

                                        <div style="display:inline-block;margin-right:5px;margin-top: 5px;">Category. : <input name="category" type="text" id="category1" style="width:70px;" value="{{$category ?? ''}}" class="submit_on_enter"></div>
                                        <div style="display:inline-block;margin-right:5px;margin-top: 5px;">Material. : <input name="material" type="text" id="material1" style="width:70px;" class="submit_on_enter"></div>
                                        <div style="display:inline-block;margin-right:5px;margin-top: 5px;"><input type="submit" name="dnn$ctr440$details$btn_SearchSeals" value="Search" id="btnsearch1" onclick="btnsearch1()" class="SearchProfile" style="width:88px;"></div>


                                    </div>

                                    <div class="card-box table-responsive" style="padding: 1px !important;">

                                        <div class="col-md-12" style="margin-bottom: 15px;">


                                            <div>
                                                <h1>
                                                    <div style="float: right; cursor: pointer;">
                                                        <span class="glyphicon glyphicon-shopping-cart my-cart-icon"><span class="badge badge-notify my-cart-badge"></span></span>
                                                    </div>
                                                </h1>
                                            </div>


                                            <!--  <div id="rel23">
                                              <div class="loader"></div>
                                            </div> -->
                                            <!-- <button type="addNewProduct" name="addNewProduct" id="addNewProduct">Add New Product</button> -->
                                            <div class="tablesearch1">
                                                <table class="table table-striped table-bordered">
                                                    <thead>
                                                    <tr>
                                                        <th>Sr.No</th>

                                                        <th>Service Name</th>


                                                        <th>Category</th>
                                                        <th>Material</th>

                                                        <th>Price</th>
                                                        <th>Select</th>
                                                        <th></th>
                                                    </tr>
                                                    </thead>

                                                    <tbody>
                                                    <?php
                                                    $srno=$i=0;
                                                    ?>
                                                    @foreach($service as $list)
                                                        <?php

                                                        $srno++;
                                                        ?>

                                                        <tr>
                                                            <td> {{$srno}}</td>

                                                            <td>{{$list->product_name}}</td>


                                                            <td style="text-align: center;">{{$list->catname}}</td>

                                                            <td style="text-align: center;">{{$list->matname}}</td>

                                                            <td style="text-align: center;">{{number_format($list->price)}}</td>
                                                            <td style="text-align: center;">

                                                                <button  class="btn btn-danger my-cart-btn" data-id="{{$list->id}}" data-name="{{$list->product_name}}" data-summary="{{$list->description}}" data-price="{{$list->price}}" data-quantity="1" data-image="@if($list->product_image !=''){{asset('public/product_category/'.$list->category_image)}}@else{{asset('public/product_image/noimage.jpg')}}@endif
                                                                    ">Add to Cart</button>
                                                            </td>
                                                            <td class="actions" style="width: 5%">
                                                                @foreach(Session::get('parent_module') as $child)
                                                                    @if($child->module_name=="Product Master" and $child->module_edit=='1')
                                                                        <a href="{{url('client/product/edit/'.$list->id)}}" class="on-default edit-row">
                                                                            <i class="fa fa-pencil"></i>
                                                                        </a>
                                                                    @endif
                                                                    @if($child->module_name=="Product Master" and $child->module_delete=='1')
                                                                        <a href="{{url('product_delete/'.$list->id)}}" class="on-default remove-row" onclick="return confirm('Are you sure you want to delete this item?');">
                                                                            <i class="fa fa-trash-o"></i>
                                                                        </a>
                                                                    @endif
                                                                @endforeach
                                                            </td>

                                                        </tr>
                                                    @endforeach
                                                    </tbody>
                                                </table>

                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>




       <!-- end row -->



   </div> <!-- container -->

</div> <!-- content -->

<script src="{{asset('public/adminpanel/default/assets/js/jquery.min.js')}}"></script>
<script type="text/javascript">
    // Using jQuery.
    $(document).ready(function() {
        $(".addCart").click(function (e){
            e.preventDefault();
        });
        getcart();
      $('.submit_on_enter').keydown(function(event) {
        // enter has keyCode = 13, change it if you want to use another button
        if (event.keyCode == 13) {
          btnsearch();
          return false;
        }
      });

    });

    function add_to_cart(ele){
        var product=$(ele).closest('tr').find('.product').val();
        var qty=$(ele).closest('tr').find('.qty').val();
        var price=$(ele).closest("tr").find(".price").val();
      var customer_name=$("#customer_name").val();
      if(qty=="")
      {
          $(ele).closest("tr").find(".errorMsg").text("please enter qty");
      }else{
          $(ele).closest("tr").find(".errorMsg").text("");
          var appurl="{{ url('/') }}";
        $.ajax({
            url:appurl+'/quot_add_to_cart',
            data:{id:product,price:price,quantity:qty},
            method:'get',
            beforeSend: function(){
    // Show image container
            $(".loader").show();
            },
            success:function(response)
            {
                var customer_name=$("#customer_name").val();
                $("#cust").val(customer_name);
                getcart();
            },
            complete:function(data){
    // Hide image container
                $(".loader").hide();
               }
        }); 
      }
       
      }
      function model_open()
      {
        var customer_name=$("#customer_name").val();
        $("#cust").val(customer_name);
      }
      function getcart()
      {
        var appurl="{{ url('/') }}";
        $.ajax({
            url:appurl+'/getcart',
            method:'get',
            success:function(response)
            {
                var customer_name=$("#customer_name").val();
                $("#cust").val(customer_name);
                $("#cart").html(response);
                get_cart_item();
            }
        });
      }

      function get_cart_item()
      {
        var appurl="{{ url('/') }}";
        $.ajax({
            url:appurl+'/get_cart_item',
            method:'get',
            success:function(response)
            {
                $("#cart_item").html(response);
                var customer_name=$("#customer_name").val();
                $("#cust").val(customer_name);
            },
            complete:function(data){
    // Hide image container
                $(".loader").hide();
               }
        });
      }

      function delete_item(id)
      {
        $("#myModal").modal('hide');
          var appurl="{{ url('/') }}";
          $.ajax({
            url:appurl+'/quot_cart_item_remove',
            data:{id:id},
            method:'get',
            beforeSend: function(){
    // Show image container
            $(".loader").show();
            },
            success:function(response)
            {
                getcart();
            }
        });
      }

      function checkout()
      {
        var customer_name=$("#cust").val();
        if(customer_name=="")
        {
            alert("please select customer");
        }else{
            var appurl = "{{url('/')}}";
            window.location = appurl + "/client/checkout/quot/" + customer_name;
        }

       //
      }
    </script>


<script type="text/javascript">


    function add_to_quot(product)
    {
     var appurl="{{url('/')}}";
     $.ajax({
       url:appurl+'/client/save_to_quot',
       data:{product:product},
       method:'get',
       success:function(res)
       {

       }
     });
   }

    function btnsearch()
    {
      var appurl="{{url('/')}}";
      var product_name=$("#product_name").val();
      var category=$("#category").val();
      var material=$("#material").val();
      var inner_from=$("#inner_from").val();
      var inner_to=$("#inner_to").val();
      var outer_from=$("#outer_from").val();
      var outer_to=$("#outer_to").val();
      var thik=$("#thik").val();
      var customer=$("#customer_name").val();

      $.ajax({
        type:'get',
        beforeSend: function(){
          $("#rel2").show();
        },
        url:appurl+'/client/product/search',
        data: {'product_name':product_name,category:category,material:material,inner_from:inner_from,inner_to:inner_to,outer_from:outer_from,outer_to:outer_to,thik:thik,customer:customer},
        success:function(data){
          $(".tablesearch").html(data);

      // $('.tablesearch').removeHighlight().highlight($('.product_name').val());
    },
    complete: function(){
     $("#rel2").hide();
   }
  });


    }

    function btnsearch1()
    {
      //alert("s");
      var appurl="{{url('/')}}";
      var product_name=$("#product_name1").val();
      var category=$("#category1").val();
      var material=$("#material1").val();
      var inner_from=$("#inner_from1").val();
      var inner_to=$("#inner_to1").val();
      var outer_from=$("#outer_from1").val();
      var outer_to=$("#outer_to1").val();
      var thik=$("#thik1").val();
      var customer=$("#customer_name").val();

      $.ajax({
        type:'get',
        beforeSend: function(){
          $("#rel2").show();
        },
        url:appurl+'/client/service/search',
        data: {'product_name':product_name,category:category,material:material,inner_from:inner_from,inner_to:inner_to,outer_from:outer_from,outer_to:outer_to,thik:thik,customer:customer},
        success:function(data){
          $(".tablesearch1").html(data);

      // $('.tablesearch').removeHighlight().highlight($('.product_name').val());
    },
    complete: function(){
     $("#rel2").hide();
   }
  });


    }




  </script>


        <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.8/js/select2.min.js" defer></script>
        <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet" />
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>
              <script type="text/javascript">
            $(document).ready(function() {
                $('.js-example-basic-single').select2();
            });
        </script>

   </script>

@endsection
