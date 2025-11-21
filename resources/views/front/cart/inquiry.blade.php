@extends('front.includes.master')

@section('title')

@section('content')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <div class="container">

        <div class="cart-page">
            <div class="breadcrumb-area">
                <ul>
                    <li><a href="{{url('index')}}">Home</a></li>
                    <li><span>Inquiry</span></li>
                </ul>
            </div>
            <div class="row">
                <div class="col-12">
                    @if(session()->has("message"))
                        <div class="alert alert-success">
                            <strong>{{session()->get("message")}}</strong>
                        </div>
                    @endif


                        <div class="table-content table-responsive">
                            <table class="table">
                                <thead>
                                <tr>
                                    <th>Images</th>
                                    <th>Description</th>
                                    <th>Product</th>
                                    <th>Unit Price</th>
                                    <th>Quantity</th>
                                    <th>Total</th>
                                    <th>Remove</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                $total = $pricetotal=0;

                                ?>
                                @if(session('inquiry'))

                                    @foreach(session('inquiry') as $id => $details)

                                        <?php
                                        $cartprice=$details['price'] ?? 0;
                                        $cartProductName=$details['name'] ?? "";
                                        $cartProductPhoto=$details['photo'] ?? "no_image.png";
                                        $total += $cartprice * $details['quantity'];
                                        $pricetotal=$cartprice*$details['quantity'];
                                        ?>

                                <tr>
                                    <td class="product-thumbnail">

                                        <a href="{{url('product-details/c/s/'.$cartProductName)}}">
                                            <img src="/product_image/{{$cartProductPhoto}}" alt="">
                                        </a>
                                    </td>
                                    <td>

                                        <input type="hidden" class="inquiry_product_id" name="id" value="{{$id}}">
                                        <textarea class="custom_description" placeholder="Custom Description...." name="custom_description" rows="3" style="border:none; ">{{$details['custom_description']}}</textarea>
                                    </td>
                                    <td style="text-align:left"><a href="{{url('product-details/c/s/'.$cartProductName)}}">{{$cartProductName}}</a>
                                    @php $product=\App\product::find($details['product_id'] ?? 0) @endphp
                                    <br><span>Colour : {{$product->value1 ?? ""}}</span>
                                    <br><span>Size   : {{$product->value2 ?? ""}}</span>
                                    </td>
                                    <td class="product-price"><span class="amount">@if($cartprice==null) - @else<i class="fas fa-rupee-sign"></i> {{$cartprice}}@endif</span></td>
                                    <td class="product-quantity">
                                        <div class="cart-plus-minus">
                                            <input type="text" class="quantity" name="quantity" value="{{$details['quantity']}}">
                                            <div class="dec qtybutton">-</div>
                                            <div class="inc qtybutton">+</div>
                                        </div>
                                        <strong style="display: none;color:green" class="message">Update &nbsp; &nbsp;<i class="fa fa-check"></i></strong>
                                    </td>
                                    <td class="product-subtotal"><span class="amount">@if($pricetotal==null) - @else <i class="fas fa-rupee-sign"></i> {{$pricetotal}} @endif</span></td>
                                    <td class="product-remove">
                                        <button name="update_cart" value="update_cart"><i style="margin: 5px;cursor: pointer;" title="Update Item From Cart" data-id="{{ $id }}" class="qtybutton fa fa-refresh"></i></button>
                                        <button name="delete_cart" value="delete_cart"><i style="margin: 5px;cursor: pointer;" title="Remove Item From Cart" data-id="{{ $id }}" class="remove-from-inquiry fa fa-trash"></i></button>
                                    </td>

                                </tr>

                                    @endforeach
                                @endif
                                </tbody>
                            </table>
                        </div>




                </div>
            </div>
        </div>
    </div>
    <script src="/front/js/vendor/jquery-3.3.1.min.js"></script>
    <script>
    $(document).on('click', '.qtybutton', function (ele) {
        ele.preventDefault();
        var ele = $(this);
        var id=ele.parents("tr").find(".inquiry_product_id").val();

        $(".message").hide();
        $.ajax({
            url: '{{ url('inquiry-update-cart') }}',
            method: "get",
            data: {id:id,quantity: ele.parents("tr").find(".quantity").val(),custom_description: ele.parents("tr").find(".custom_description").val()},
            success: function (response) {
            $("#cart_message").show();
            ele.parents("tr").find(".message").show();
                window.location.reload();
            }
        });
    });

    $(document).on('click', '.remove-from-inquiry', function (ele) {
        ele.preventDefault();
        var ele = $(this);
        var id=ele.parents("tr").find(".inquiry_product_id").val();

        $.ajax({
            url:'{{url('inquiry-remove-cart')}}',
            method:"get",
            data:{id:id},
            success:function(response)
            {
                window.location.reload();
            }
        });
        });
</script>
@endsection
