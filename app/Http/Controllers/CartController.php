<?php

namespace App\Http\Controllers;

use App\item_group;
use App\product;
use Illuminate\Http\Request;
use Session;
class CartController extends Controller
{
    //
    function quot_add_to_cart(Request $request)
    {
        //dd($request->all());

      // dd(Session::get('quot_cart'));
       // dd($request->all());

            $product = product::find($request->id);
            if(!$product) {
                abort(404);
            }

            $price=0;

                $price=$product->price;


            $cart = session()->get('quot_cart');

            $orderid=date('ymdhis');
            // if cart is empty then this the first product
            if(!$cart) {
                $cart = [
                    $orderid => [
                        "product_id"=>$product->id,
                        "name" => $product->product_name,
                        "quantity" =>$request->quantity,
                        "price" => $price,
                        "attribute1"=>$request->product_attribute    ?? "",
                        "value1"=>$request->options1 ?? "",
                        "attribute2"=>$request->attribute2 ?? "",
                        "value2"=>$request->options2 ?? "",
                        "photo"=>$product->product_image ?? "",
                        "custom_description"=>$request->custom_descripion ?? ""
                    ]
                ];
                session()->put('quot_cart', $cart);
                return redirect()->back()->with('success', 'Product added to cart successfully!');
            }
            // if cart not empty then check if this product exist then increment quantity
            if(isset($cart[$orderid])) {
                $cart[$request->id]['quantity']++;
                session()->put('quot_cart', $cart);
                return redirect()->back()->with('success', 'Product added to cart successfully!');
            }
            // if item not exist in cart then add to cart with quantity = 1
            $cart[$orderid] = [
                "product_id"=>$product->id,
                "name" => $product->product_name,
                "quantity" =>$request->quantity,
                "price" => $price,
                "attribute1"=>$request->product_attribute ?? "",
                "value1"=>$request->options1 ?? "",
                "attribute2"=>$request->attribute2 ?? "",
                "value2"=>$request->options2 ?? "",
                "photo"=>$product->product_image ?? "",
                "custom_description"=>$request->custom_descripion ?? ""
            ];
            session()->put('quot_cart', $cart);

            return back()->with("success","Item Add in cart successfully");




    }
    function update_inquiry(Request $request)
    {
        
        $cart = session()->get('inquiry');
        
        if($request->id and $request->quantity)
        {
            
            $cart[$request->id]["quantity"] = $request->quantity;
            $cart[$request->id]["custom_description"]=$request->custom_description ?? "";
            //dd($cart);
            session()->put('inquiry', $cart);
            session()->flash('success', 'inquiry updated successfully');
        }
        return back();
    }
    
    function inquiry_action(Request $request)
    {
        if(isset($request->update_cart))
        {
            if($request->id and $request->quantity)
            {
                $cart = session()->get('inquiry');
                $cart[$request->id]["quantity"] = $request->quantity;
                $cart[$request->id]["custom_description"]=$request->custom_description ?? "";
                //dd($cart);
                session()->put('inquiry', $cart);
                session()->flash('success', 'Cart updated successfully');
            } 
            return back()->with("message","Cart updated successfully");
        }
        
        if(isset($request->delete_cart))
        {
            if($request->id) {
                $cart = session()->get('inquiry');
                if(isset($cart[$request->id])) {
                    unset($cart[$request->id]);
                    session()->put('inquiry', $cart);
                }
                session()->flash('success', 'Product removed successfully');
            }
            return back()->with("message","Product removed successfully");
        }
    }
    
    function inquiry_remove(Request $request)
    {
        $cart = session()->get('inquiry');
        if(isset($cart[$request->id])) {
        unset($cart[$request->id]);
        session()->put('inquiry', $cart);
        }
        session()->flash('success', 'Product removed successfully');
    }
    
    function cart_action(Request $request)
    {
        if(isset($request->update_cart))
        {
            if($request->id and $request->quantity)
            {
                $cart = session()->get('cart');
                $cart[$request->id]["quantity"] = $request->quantity;
                $cart[$request->id]["custom_description"]=$request->custom_description ?? "";
                //dd($cart);
                session()->put('cart', $cart);
                session()->flash('success', 'Cart updated successfully');
            } 
            return back()->with("message","Cart updated successfully");
        }
        
        if(isset($request->delete_cart))
        {
            if($request->id) {
                $cart = session()->get('cart');
                if(isset($cart[$request->id])) {
                    unset($cart[$request->id]);
                    session()->put('cart', $cart);
                }
                session()->flash('success', 'Product removed successfully');
            }
            return back()->with("message","Product removed successfully");
        }
    }
    
    public function update(Request $request)
    {
        $cart = session()->get('cart');
        $cart[$request->id]["quantity"] = $request->quantity;
        $cart[$request->id]["custom_description"]=$request->custom_description ?? "";
                //dd($cart);
        session()->put('cart', $cart);
        session()->flash('success', 'Cart updated successfully');
        //return back()->with("message","Cart updated successfully");
        return response()->json($cart);
    }
    
    function cart_update_cart(Request $request)
    {
        if($request->id)
        {
            $cart = session()->get('cart');
            $cart[$request->id]["quantity"] = $request->quantity;
            $cart[$request->id]["custom_description"]=$request->custom_description ?? "";
                    //dd($cart);
            session()->put('cart', $cart);
            session()->flash('success', 'cart updated successfully');
            //return back()->with("message","Cart updated successfully");
            return response()->json($cart);
        }
    }
    
    function inquiry_update_cart(Request $request)
    {
        if($request->id)
        {
            $cart = session()->get('inquiry');
            $cart[$request->id]["quantity"] = $request->quantity;
            $cart[$request->id]["custom_description"]=$request->custom_description ?? "";
                    //dd($cart);
            session()->put('inquiry', $cart);
            session()->flash('success', 'inquiry updated successfully');
            //return back()->with("message","Cart updated successfully");
            return response()->json($cart);
        }
    }
    
    public function inquiry_update(Request $request)
    {
        
      
        if(session()->get('inquiry'))
        {
            foreach(session()->get('inquiry') as $id => $details)
            {
                print_r($details);
            }
        }
        
        $cart = session()->get('inquiry');
        
        if($request->id and $request->quantity)
        {
            
            $cart[$request->id]["quantity"] = $request->quantity;
            $cart[$request->id]["custom_description"]=$request->custom_description ?? "";
            //dd($cart);
            session()->put('inquiry', $cart);
            session()->flash('success', 'inquiry updated successfully');
        }
        
    }
    
    public function remove(Request $request)
    {
        if($request->id) {
            $cart = session()->get('cart');
            if(isset($cart[$request->id])) {
                unset($cart[$request->id]);
                session()->put('cart', $cart);
            }
            session()->flash('success', 'Product removed successfully');
        }
    }
    
    public function quot_remove(Request $request)
    {
        if($request->id) {
            $cart = session()->get('quot_cart');
            if(isset($cart[$request->id])) {
                unset($cart[$request->id]);
                session()->put('quot_cart', $cart);
            }
            session()->flash('success', 'Product removed successfully');
        }
    }

    function get_cart_item()
    {
        $var='<div id="myModal" class="modal fade" role="dialog">
        <div class="modal-dialog">

          <!-- Modal content-->
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal">&times;</button>
              <h4 class="modal-title">Cart</h4>
            </div>
            <div class="modal-body">';
              if(session('quot_cart'))
              {
                  $var .='<div class="media-body">';
                  $var .='<div class="row">
                  <div class="col-md-12" style="display:none">
                  <div class="form-group">
                  <label>Customer Name</label>
                  <input type="text" id="cust" name="cust" class="form-control">
                  </div>
                  </div>
                  <div class="col-md-12">';
                  $var .='<table class="table table-responsive-sm">';

                  $var .='<tr><th>Sr</th><th style="text-align:left">Product Name</th><th>Quantity</th><th>Price</th></tr>';
                  $srno=0;
              foreach(session('quot_cart') as $id => $details)
              {
                    $srno++;
                  $var .='<tr class="rowid">';
                  $var .='<td>'.$srno.'</td>';
                  $var .='<td ><input type="hidden" class="pid" value="'.$details['product_id'].'">'.$details['name'].'</td>';
                  $var .='<td style="text-align:center">'.$details['quantity'].'</td>';
                  $var .='<td style="text-align:center">'.$details['price'].'</td>';
                  $var .='<td><a onclick="delete_item('.$id.')" class="btn btn-danger btn-sm" href="#">Delete</a></td>';
                  $var .='</tr>';
               }
               $var .='</table></div></div>';
            }
           $var .='</div>
            <div class="modal-footer">
            <button type="button" onclick="checkout()" class="btn btn-brown">Checkout</button>
              <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
          </div>

        </div>
      </div>';

      return $var;
    }

    function getcart()
    {

        // foreach(session('cart') as $id => $details)
        // {
        //     echo session('cart')[$id];
        // }


        $var='<div>
        <h1>
          <div style="float: right; cursor: pointer;">
            <span data-toggle="modal" data-target="#myModal" onclick="model_open()" class="glyphicon glyphicon-shopping-cart my-cart-icon"><span class="badge badge-notify my-cart-badge">'.count((array) session('quot_cart')) .'</span></span>
          </div>
        </h1>
      </div>';
      return $var;
    }
    function add_to_cart(Request $request)
    {
      
        if(isset($request->cart))
        {
            $product = product::find($request->id);
            if(!$product) {
                abort(404);
            }

            $price=0;
            if($product->price_show_hide=="show")
            {
                $price=$request->price;
            }

            $cart = session()->get('cart');

            $orderid=date('ymdhis');
            // if cart is empty then this the first product
            if(!$cart) {
                $cart = [
                    $orderid => [
                        "product_id"=>$product->id,
                        "name" => $product->product_name,
                        "quantity" =>$request->quantity,
                        "price" =>  $price ?? 0,
                        "attribute1"=>$request->product_attribute    ?? "",
                        "value1"=>$request->options1 ?? "",
                        "attribute2"=>$request->attribute2 ?? "",
                        "value2"=>$request->options2 ?? "",
                        "photo"=>$product->product_image ?? "",
                        "custom_description"=>$request->custom_description ?? ""
                    ]
                ];
                session()->put('cart', $cart);
                return redirect()->back()->with('success', 'Product added to cart successfully!');
            }
            // if cart not empty then check if this product exist then increment quantity
            if(isset($cart[$orderid])) {
                $cart[$request->id]['quantity']++;
                session()->put('cart', $cart);
                return redirect()->back()->with('success', 'Product added to cart successfully!');
            }
            // if item not exist in cart then add to cart with quantity = 1
            $cart[$orderid] = [
                "product_id"=>$product->id,
                "name" => $product->product_name,
                "quantity" =>$request->quantity,
                "price" => $price ?? 0,
                "attribute1"=>$request->product_attribute ?? "",
                "value1"=>$request->options1 ?? "",
                "attribute2"=>$request->attribute2 ?? "",
                "value2"=>$request->options2 ?? "",
                "photo"=>$product->product_image ?? "",
                "custom_description"=>$request->custom_description ?? ""
            ];
            session()->put('cart', $cart);

            return back()->with("success","Item Add in cart successfully");
        }

        if(isset($request->inquiry))
        {
            $product = product::find($request->id);
            if(!$product) {
                abort(404);
            }
            $price=0;
            if($product->price_show_hide=="show")
            {
                $price=$product->price ?? 0;
            }
            $cart = session()->get('inquiry');

            $orderid=date('ymdhis');
            // if cart is empty then this the first product
            if(!$cart) {
                $cart = [
                    $orderid => [
                        "product_id"=>$product->id,
                        "name" => $product->product_name,
                        "quantity" =>$request->quantity,
                        "price" => $price,
                        "attribute1"=>$request->product_attribute    ?? "",
                        "value1"=>$request->options1 ?? "",
                        "attribute2"=>$request->attribute2 ?? "",
                        "value2"=>$request->options2 ?? "",
                        "photo"=>$product->product_image ?? "",
                        "custom_description"=>$request->custom_description ?? ""
                    ]
                ];
                session()->put('inquiry', $cart);
                return redirect()->back()->with('success', 'Product added to inquiry successfully!');
            }
            // if cart not empty then check if this product exist then increment quantity
            if(isset($cart[$orderid])) {
                $cart[$request->id]['quantity']++;
                session()->put('inquiry', $cart);
                return redirect()->back()->with('success', 'Product added to inquiry successfully!');
            }
            // if item not exist in cart then add to cart with quantity = 1
            $cart[$orderid] = [
                "product_id"=>$product->id,
                "name" => $product->product_name,
                "quantity" =>$request->quantity,
                "price" => $price,
                "attribute1"=>$request->product_attribute ?? "",
                "value1"=>$request->options1 ?? "",
                "attribute2"=>$request->attribute2 ?? "",
                "value2"=>$request->options2 ?? "",
                "photo"=>$product->product_image ?? "",
                "custom_description"=>$request->custom_description ?? ""
            ];
            session()->put('inquiry', $cart);

            return back()->with("success","Item Add in inquiry successfully");
        }

    }
}
