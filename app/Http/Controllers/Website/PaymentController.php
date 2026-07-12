<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function index(Request $request)
    {
        $order = Order::findOrFail($request->order_id);
        return view('website.payment.index', [
            'order' => $order,
            'razorpay_key' => config('services.razorpay.key'),
        ]);
    }

    public function success(Request $request)
    {
        $request->validate([
            'razorpay_order_id' => 'required',
            'razorpay_payment_id' => 'required',
            'razorpay_signature' => 'required',
            'order_id' => 'required|integer',
        ]);

        $order = Order::findOrFail($request->order_id);
        $generated_signature = hash_hmac('sha256', $request->razorpay_order_id . '|' . $request->razorpay_payment_id, config('services.razorpay.secret'));

        if (hash_equals($generated_signature, $request->razorpay_signature)) {
            $order->update([
                'status' => 'paid',
                'razorpay_payment_id' => $request->razorpay_payment_id,
                'razorpay_signature' => $request->razorpay_signature,
            ]);
            return redirect()->route('website.home')->with('success', 'Payment successful!');
        }

        return redirect()->route('website.payment.index', ['order_id'=>$order->id])->with('error','Signature verification failed.');
    }
}
