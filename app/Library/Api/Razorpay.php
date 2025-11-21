<?php
/**
 * Created by PhpStorm.
 * User: tymk
 * Date: 27/9/21
 * Time: 5:35 PM
 */

namespace App\Library\Api;


use App\Models\Order;
use App\Models\PaymentGatewayTransaction;
use Illuminate\Http\Request;
use Razorpay\Api\Api;
use Razorpay\Api\Errors\BadRequestError;

class Razorpay
{
    public function handleResponse(Order $order, Request $request)
    {
        if ($request->get('custom_status') == 'CANCEL')
        {
            $razorpay_response = (object)['status' => 'cancel', 'message' => 'Cancel By the User while entering Bank details'];
        } else {

            try {

                $razorpay_response = (new Api(env('RAZORPAY_KEY'), env('RAZORPAY_SECRET')))
                    ->payment->fetch($request->get('reference_id'))
                    ->capture(['amount' => $order->total * 100]);

            } catch (BadRequestError $e) {

                $razorpay_response = (object)['status' => 'failed', 'message' => $e->getMessage()];
            }
        }

        \DB::transaction(function () use ($razorpay_response, $request, $order) {

            if ($razorpay_response->status == 'authorized') {

                $status = PaymentGatewayTransaction::PENDING;

                $order->payment_reference = $request->get('reference_id');
                $order->payment_status = Order::PAYMENT_PENDING;
                $order->status = Order::CHECKOUT;
                $order->save();
            } elseif ($razorpay_response->status == 'captured') {

                $status = PaymentGatewayTransaction::SUCCESS;

                $order->payment_reference = $request->get('reference_id');
                $order->payment_status = Order::PAYMENT_SUCCESS;
                $order->approved_at = now();
                $order->status = Order::APPROVED;
                $order->save();
            } elseif ($razorpay_response->status == 'failed') {

                $status = PaymentGatewayTransaction::FAILED;

                $order->payment_reference = $request->get('reference_id');
                $order->payment_status = Order::PAYMENT_FAILED;
                $order->status = Order::FAILED;
                $order->save();
            } else {

                $status = PaymentGatewayTransaction::CANCEL;

                $order->payment_status = Order::PAYMENT_FAILED;
                $order->status = Order::REJECTED;
                $order->save();
            }

            if (!PaymentGatewayTransaction::whereOrderId($order->id)->exists()) {

                if (in_array($order->status, [Order::REJECTED, Order::FAILED]))
                    $order->refundWalletAmount('Refund - Payment Failed of Order: ' . $order->customer_order_id);

                PaymentGatewayTransaction::create([
                    'order_id' => $order->id,
                    'provider' => 'razorpay',
                    'reference_id' => $request->get('reference_id'),
                    'response' => collect($razorpay_response)->toArray(),
                    'status' => $status
                ]);

            }


        });

        return $order;
    }
}