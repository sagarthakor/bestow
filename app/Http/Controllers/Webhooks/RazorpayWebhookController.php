<?php

namespace App\Http\Controllers\Webhooks;

use App\Http\Controllers\Controller;
use App\Order;
use Illuminate\Http\Request;

class RazorpayWebhookController extends Controller
{
    public function handle(Request $request)
    {
        $event = $request->all();

        if (($event['event'] ?? '') === 'payment.captured') {
            $rzpOrderId = $event['payload']['payment']['entity']['order_id'] ?? null;
            $rzpPaymentId = $event['payload']['payment']['entity']['id'] ?? null;

            if ($rzpOrderId && $rzpPaymentId) {
                $order = Order::where('razorpay_order_id', $rzpOrderId)->first();
                if ($order && $order->status !== 'paid') {
                    $order->status = 'paid';
                    $order->razorpay_payment_id = $rzpPaymentId;
                    $order->save();
                }
            }
        }
        return response()->json(['status'=>'ok']);
    }
}
