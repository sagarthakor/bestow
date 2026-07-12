 @extends('includes.master')

@section('title',"checkout")

@section('content')
 <div class="container">

         <div class="checkout-page">
            <div class="breadcrumb-area">
               <ul>
                  <li><a href="{{url('index')}}">Home</a></li>
                  <li><span>Checkout</span></li>
               </ul>
            </div>
            <form action="#">
               <div class="checkout-form">
                  <h2 class="page-title mb-5">Checkout</h2>
                  <div class="row">
                     <div class="col-lg-6">
                        <div class="top-accordion">
                           <div class="mini-login">
                              <h3>Already Registered? Please<a href="{{url('login')}}"> <span>Login</span></h3></a>
                              <div class="mini-login-form">
                                 <div class="form-group">
                                    <label>Email <span class="required">*</span></label>
                                    <input type="email" id="email">
                                 </div>
                                 <div class="form-group">
                                    <label>Password <span class="required">*</span></label>
                                    <input type="password" id="password">
                                 </div>
                                 <div class="form-group">
                                    <button class="btn theme-btn mr-2">Login</button>
                                    <label>
                                    <input type="checkbox"> Remember me
                                    </label>
                                    <p class="checkbox-area">
                                       <a href="#">Lost your password?</a>
                                    </p>
                                 </div>
                              </div>
                           </div>
                        </div>
                        <div class="form-title">
                           <h1>Billing Address</h1>
                        </div>
                        <div class="billing-address">
                           <div class="form-row">
                              <div class="form-group col-md-6">
                                 <label>First Name <span class="required">*</span></label>
                                 <input type="text">
                              </div>
                              <div class="form-group col-md-6">
                                 <label>Last Name <span class="required">*</span></label>
                                 <input type="text" >
                              </div>
                              <div class="form-group col-md-6">
                                 <label>Email <span class="required">*</span></label>
                                 <input type="email">
                              </div>
                              <div class="form-group col-md-6">
                                 <label>Phone <span class="required">*</span></label>
                                 <input type="text" >
                              </div>
                              <div class="form-group col-md-6">
                                 <label for="code">Code:<span class="required"> *</span></label>
                                   <select id="code">
                                     <option>999 1111 2222</option>
                                     <option>999 2222 3333</option>
                                     <option>999 4444 1111</option>
                                     <option>999 5555 2222</option>
                                   </select>
                              </div>
                              <div class="form-group col-12">
                                 <label>Company</label>
                                 <input type="text">
                              </div>
                              <div class="form-group col-12">
                                 <label>Address <span class="required">*</span></label>
                                 <textarea rows="3"></textarea>
                              </div>
                              <div class="form-group col-12">
                                 <label>Country <span class="required">*</span></label>
                                 <select>
                                    <option value="0">Select your country</option>
                                    <option value="1">Algeria</option>
                                    <option value="2">Afghanistan</option>
                                    <option value="3">Bahrain</option>
                                    <option value="4">Belgium</option>
                                    <option value="5">Colombia</option>
                                    <option value="6">Denmark</option>
                                    <option value="7">USA</option>
                                    <option value="8">UK</option>
                                 </select>
                              </div>
                              <div class="form-group col-md-6">
                                 <label>City / Town <span class="required">*</span></label>
                                 <input type="text">
                              </div>
                              <div class="form-group col-md-6">
                                 <label>Zip / Postal <span class="required">*</span></label>
                                 <input type="text" >
                              </div>
                              <div class="form-group col-12">
                                 <input class="checkbox" type="checkbox"> <label>Create an account ? </label>
                              </div>
                              <div class="create-account col-12">
                                 <div class="form-row">
                                    <div class="form-group col-md-6">
                                       <label>Password <span class="required">*</span></label>
                                       <input type="password" >
                                    </div>
                                    <div class="form-group col-md-6">
                                       <label>Confirm Password <span class="required">*</span></label>
                                       <input type="password">
                                    </div>
                                 </div>
                              </div>
                              <div class="form-group col-12">
                                 <input class="different" type="checkbox"> <label>Ship to a different address ? </label>
                              </div>
                              <div class="diffrent-address col-12">
                                 <div class="form-row">
                                    <div class="form-group col-md-6">
                                       <label>First Name <span class="required">*</span></label>
                                       <input type="text">
                                    </div>
                                    <div class="form-group col-md-6">
                                       <label>Last Name <span class="required">*</span></label>
                                       <input type="text" >
                                    </div>
                                    <div class="form-group col-md-6">
                                       <label>Email <span class="required">*</span></label>
                                       <input type="email">
                                    </div>
                                    <div class="form-group col-md-6">
                                       <label>Phone <span class="required">*</span></label>
                                       <input type="text" >
                                    </div>
                                    <div class="form-group col-12">
                                       <label>Company</label>
                                       <input type="text">
                                    </div>
                                    <div class="form-group col-12">
                                       <label>Address <span class="required">*</span></label>
                                       <textarea rows="3"></textarea>
                                    </div>
                                    <div class="form-group col-12">
                                       <label>Country <span class="required">*</span></label>
                                       <select>
                                          <option value="0">Select your country</option>
                                          <option value="1">Algeria</option>
                                          <option value="2">Afghanistan</option>
                                          <option value="3">Bahrain</option>
                                          <option value="4">Belgium</option>
                                          <option value="5">Colombia</option>
                                          <option value="6">Denmark</option>
                                          <option value="7">USA</option>
                                          <option value="8">UK</option>
                                       </select>
                                    </div>
                                    <div class="form-group col-md-6">
                                       <label>City / Town <span class="required">*</span></label>
                                       <input type="text">
                                    </div>
                                    <div class="form-group col-md-6">
                                       <label>Zip / Postal <span class="required">*</span></label>
                                       <input type="text" >
                                    </div>
                                 </div>
                              </div>
                           </div>
                        </div>
                     </div>
                     <div class="col-lg-6">
                        <div class="top-accordion">
                           <div class="mini-coupon">
<!--                               <h3>Have a coupon? <span>Click here to enter your code</span></h3>
 -->                              <div class="coupon-box">
                                 <div class="form-group">
                                    <label>Coupon Code</label>
                                    <input type="text" id="coupon">
                                 </div>
                                 <div class="form-group">
                                    <a href="#"><button class="btn theme-btn mr-2">Apply Coupon</button></a>
                                 </div>
                              </div>
                           </div>
                        </div>
                        <div class="right-order-form">
                           <div class="form-title">
                              <h1>Delivery Method</h1>
                           </div>
                           <div class="delivery-method">
                              <div class="form-row">
                                 <div class="form-group col-12">
                                    <input type="radio"> <label>Shipping rate: $50 </label>
                                 </div>
                              </div>
                           </div>
                           <div class="form-title">
                              <h1>Order Review</h1>
                           </div>
                           <div class="order-review">
                              <div class="table-responsive">
                                 <table class="table">
                                    <thead>
                                       <tr>
                                          <th>Product</th>
                                          <th>Quantity</th>
                                          <th>Price</th>
                                       </tr>
                                    </thead>
                                    <tbody>
                                       <tr>
                                          <td>Lorem ism</td>
                                          <td>1</td>
                                          <td>$50.00</td>
                                       </tr>
                                       <tr>
                                          <td>Jsm Korem</td>
                                          <td>1</td>
                                          <td>$40.00</td>
                                       </tr>
                                       <tr>
                                          <th>Subtotal:</th>
                                          <th></th>
                                          <th>$90.00</th>
                                       </tr>
                                       <tr>
                                          <th>Shipping:</th>
                                          <th></th>
                                          <th>$20.00</th>
                                       </tr>
                                       <tr>
                                          <th>Total:</th>
                                          <th></th>
                                          <th><span class="cart-total">$200.00</span></th>
                                       </tr>
                                    </tbody>
                                 </table>
                              </div>
                           </div>
                           <div class="form-title">
                              <h1>Payment Method</h1>
                           </div>
                           <div class="payment-method">
                              <div class="accordion" id="accordionExample">
                                 <div class="card">
                                    <div class="card-header" id="headingOne">
                                       <a href="#" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">Bank Transfer
                                       </a>
                                    </div>
                                    <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordionExample">
                                       <div class="card-body">
                                          Make your payment directly into our bank account. Please use your Order ID as the payment reference. Your order won’t be shipped until the funds have cleared in our account.
                                       </div>
                                    </div>
                                 </div>
                                 <div class="card">
                                    <div class="card-header" id="headingTwo">
                                       <a href="#" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">Paypal
                                       </a>
                                    </div>
                                    <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionExample">
                                       <div class="card-body">
                                          You can pay with your credit card
                                       </div>
                                    </div>
                                 </div>
                              </div>
                              <div class="place-order">
                                 <button class="btn theme-btn">Place Order</button>
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
            </form>
         </div>
      </div>

      @endsection
