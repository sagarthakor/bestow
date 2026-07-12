<?php

namespace App\Library\Api;

use App\Models\Order;
use App\Models\PaymentGatewayTransaction;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Http\Request;
use paytm\paytmchecksum\PaytmChecksum;

class Paytm
{
    /**
     * @param Order $order
     * @param string $callback_url
     * @return object
     */
    public function generateTxnToken(Order $order, $callback_url)
    {

        $body = [
            "requestType" => "Payment",
            "mid" => env('PAYTM_MERCHANT_ID'),
            "orderId" => $order->customer_order_id,
            "websiteName" => "WEBSTAGING",
            "callbackUrl" => $callback_url,
            "txnAmount" => [
                "value" => $order->total,
                "currency" => "INR",
            ],
            "userInfo" => [
                "custId" => $order->user ? $order->user->tracking_id : $order->customer->mobile
            ],
        ];

        $checkSum = PaytmChecksum::generateSignature(
            json_encode($body, JSON_UNESCAPED_SLASHES), env('PAYTM_MERCHANT_KEY')
        );

        $post_data = json_encode([
            'head' => [
                "signature" => $checkSum
            ],
            'body' => $body
        ], JSON_UNESCAPED_SLASHES);

        try {

            $response = (new Client())->request('POST', env('PAYTM_BASE_URL') . "theia/api/v1/initiateTransaction?mid=" . env('PAYTM_MERCHANT_ID') . "&orderId=" . $order->customer_order_id, [
                'body' => $post_data,
                'headers' => [
                    'Content-Type' => 'application/json'
                ]
            ]);

            $response = json_decode($response->getBody());


            return (object)[
                'status' => true, 'token' => $response->body->txnToken, 'payment_gateway' => 2
            ];

        } catch (RequestException $e) {


            \Log::warning('Paytm Token Generation Failed', [
                'order_id' => $order->customer_order_id,
                'exception' => $e->getMessage()
            ]);

            return (object)[
                'status' => false, 'token' => null
            ];
        }
    }

    public function handleResponse(Order $order, Request $request)
    {

        if ($request->get('custom_status') == 'CANCEL') {
            return (object)['status' => 'cancel', 'message' => 'Cancel By the User while entering Bank details'];

        }
        else {

            $body = [
                "mid" => env('PAYTM_MERCHANT_ID'),
                "orderId" => $order->customer_order_id,
            ];

            $checkSum = PaytmChecksum::generateSignature(
                json_encode($body, JSON_UNESCAPED_SLASHES), env('PAYTM_MERCHANT_KEY')
            );

            $post_data = json_encode([
                'head' => [
                    "signature" => $checkSum,
                    "channelId" => "WEB"
                ],
                'body' => $body
            ], JSON_UNESCAPED_SLASHES);


            try {

                $response = (new Client())->request('POST', env('PAYTM_BASE_URL') . "v3/order/status", [
                    'body' => $post_data,
                    'headers' => [
                        'Content-Type' => 'application/json'
                    ]
                ]);

                $this->paymentData(json_decode($response->getBody()), $request, $order);


            } catch (RequestException $e) {

                \Log::warning('Paytm Token Generation Failed', [
                    'order_id' => $order->customer_order_id,
                    'exception' => $e->getMessage()
                ]);

                return (object)[
                    'status' => false, 'token' => null
                ];
            }
        }
    }


    /**
     * @param $paytm_response
     * @param $request
     * @param Order $order
     * @return mixed
     * @throws \Throwable
     */
    public function paymentData($paytm_response, $request, Order $order)
    {

        \DB::transaction(function () use ($paytm_response, $request, $order) {


            if ($paytm_response->body->resultInfo->resultStatus == "TXN_SUCCESS") {

                $status = PaymentGatewayTransaction::SUCCESS;

                $order->payment_reference = $request->TXNID;
                $order->payment_status = Order::PAYMENT_SUCCESS;
                $order->approved_at = now();
                $order->status = Order::APPROVED;
                $order->save();

            }
            elseif ($paytm_response->body->resultInfo->resultStatus == "TXN_FAILURE") {

                $status = PaymentGatewayTransaction::FAILED;
                $order->payment_reference = $request->TXNID;
                $order->payment_status = Order::PAYMENT_FAILED;
                $order->status = Order::FAILED;
                $order->save();

            }
            else {

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
                    'provider' => 'paytm',
                    'reference_id' => $request->TXNID,
                    'response' => collect($paytm_response)->toArray(),
                    'status' => $status
                ]);

            }

        });

        return $order;

    }
}
