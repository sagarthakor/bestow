<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use App\Cart;
use App\CartItem;
use App\product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    protected function getCart(): Cart
    {
        $userId = session('user')['id'] ?? null;
        $sessionId = request()->session()->getId();

        // First try to find cart by session or user
        $cart = Cart::query()
            ->when($userId, fn($q) => $q->where('user_id', $userId))
            ->when(!$userId, fn($q) => $q->where('session_id', $sessionId))
            ->first();

        // If not found, create a new cart
        if (!$cart) {
            $cart = Cart::create([
                'user_id'   => $userId,
                'session_id'=> $userId ? null : $sessionId,
            ]);
        }

        // Always store cart_id in session
        session(['cart_id' => $cart->id]);

        // ✅ Always return a Cart model (not null)
        return $cart;
    }


    public function view()
    {
        $cart = $this->getCart()->load('items');
        return view('website.cart.view', compact('cart'));
    }

    public function add(Request $request)
    {
        $request->validate([
            'variant_id' => 'required|integer',
            'quantity'   => 'nullable|integer|min:1'
        ]);

        $qty = max(1, (int) $request->get('quantity', 1));
        $variantId = (int) $request->variant_id;

        $cart = $this->getCart(); // Must return logged-in user cart or session cart
        $p = product::findOrFail($variantId);


        $item = CartItem::firstOrCreate(
            ['cart_id' => $cart->id, 'variant_id' => $variantId],
            ['product_id' => $p->id, 'qty' => 0, 'price' => $p->price, 'name' => $p->clean_name, 'image' => $p->product_image]
        );

        $item->qty += $qty;
        $item->price = $p->price;
        $item->name  = $p->clean_name;
        $item->image = $p->product_image;
        $item->save();

        $count = CartItem::where('cart_id', $cart->id)->sum('qty');
        session(['cart_count' => $count]);

        return response()->json(['status' => true, 'count' => $count]);
    }


    public function update(Request $request)
    {
        $request->validate(['variant_id'=>'required|integer','diff'=>'required|integer']);
        $cart = $this->getCart();
        $item = CartItem::where('cart_id',$cart->id)->where('variant_id',$request->variant_id)->first();
        if($item){
            $item->qty += (int)$request->diff;
            if($item->qty <= 0) $item->delete();
            else $item->save();
        }
        $count = CartItem::where('cart_id',$cart->id)->sum('qty');
        session(['cart_count'=>$count]);
        return response()->json(['status'=>true,'count'=>$count]);
    }

    public function remove(Request $request)
    {
        $request->validate(['variant_id'=>'required|integer']);
        $cart = $this->getCart();
        CartItem::where('cart_id',$cart->id)->where('variant_id',$request->variant_id)->delete();
        $count = CartItem::where('cart_id',$cart->id)->sum('qty');
        session(['cart_count'=>$count]);
        return response()->json(['status'=>true,'count'=>$count]);
    }
}
