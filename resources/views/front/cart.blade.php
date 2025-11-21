

@extends('includes.master')

@section('title')

@section('content')
<div class="container">

         <div class="cart-page">
            <div class="breadcrumb-area">
               <ul>
                  <li><a href="{{url('index')}}">Home</a></li>
                  <li><span>Cart</span></li>
               </ul>
            </div>
            <div class="row">
               <div class="col-12">
                  <form action="#">
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
                              <tr>
                                 <td class="product-thumbnail"><a href="{{url('view')}}"><img src="/assets/images/product/s-1.jpg" alt=""></a></td>
                                 <td>
                                    <textarea rows="3" style="border:none; "></textarea>
                                 </td>
                                 <td class="product-name"><a href="{{url('view')}}">Socks</a></td>
                                 <td class="product-price"><span class="amount">$100.00</span></td>
                                 <td class="product-quantity">
                                    <div class="cart-plus-minus">
                                       <input type="text" value="1">
                                       <div class="dec qtybutton">-</div>
                                       <div class="inc qtybutton">+</div>
                                    </div>
                                 </td>
                                 <td class="product-subtotal"><span class="amount">$100.00</span></td>
                                 <td class="product-remove"><a href="#"><i class="far fa-trash-alt"></i></a></td>
                              </tr>
                              <tr>
                                 <td class="product-thumbnail"><a href="{{url('view')}}"><img src="/assets/images/product/t-1.jpg" alt=""></a></td>
                                 <td>
                                    <textarea rows="3" style="border:none; "></textarea>
                                 </td>
                                 <td class="product-name"><a href="{{url('view')}}">Tie</a></td>
                                 <td class="product-price"><span class="amount">$80.00</span></td>
                                 <td class="product-quantity">
                                    <div class="cart-plus-minus">
                                       <input type="text" value="1">
                                       <div class="dec qtybutton">-</div>
                                       <div class="inc qtybutton">+</div>
                                    </div>
                                 </td>
                                 <td class="product-subtotal"><span class="amount">$80.00</span></td>
                                 <td class="product-remove"><a href="#"><i class="far fa-trash-alt"></i></a></td>
                              </tr>
                           </tbody>
                        </table>
                     </div>
                  </form>
                  <div class="row">
                     <div class="col-12">
                        <div class="coupon-area">
                           <!-- <div class="coupon">
                              <input id="coupon_code" class="input-text" name="coupon_code" value="" placeholder="Coupon code" type="text">
                              <button class="btn theme-btn-2" name="apply_coupon" type="submit">Apply coupon</button>
                           </div> -->
                           <div class="coupon2">
                              <a href="{{url('socks')}}"><input class="btn theme-btn-2" name="update_cart" value="Continue Shopping" type="submit"></a>
                              <input class="btn theme-btn-2" name="update_cart" value="Update cart" type="submit">
                           </div>
                        </div>
                     </div>
                  </div>
                  <div class="row">
                     <div class="col-lg-5 ml-auto">
                        <div class="cart-page-total">
                           <h2>Cart totals</h2>
                           <ul>
                              <li>Subtotal <span>$180.00</span></li>
                              <li>Total <span>$180.00</span></li>
                           </ul>
                           <a class="btn theme-btn" href="{{url('checkout')}}">Proceed to checkout</a>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
      @endsection
