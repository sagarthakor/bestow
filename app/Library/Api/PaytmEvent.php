<?php

namespace App\Library\Api;

use App\Models\EventEnrollment;
use App\Models\Order;
use App\Models\PaymentGatewayTransaction;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Http\Request;
use Milon\Barcode\DNS1D;
use paytm\paytmchecksum\PaytmChecksum;

class PaytmEvent
{
    /**
     * @param EventEnrollment $eventEnrollment
     * @param string $callback_url
     * @return object
     */
    public function generateTxnToken(EventEnrollment $eventEnrollment, $callback_url)
    {

        $body = [
            "requestType" => "Payment",
            "mid" => env('PAYTM_MERCHANT_ID'),
            "orderId" => $eventEnrollment->enrollment_number,
            "websiteName" => "WEBSTAGING",
            "callbackUrl" => $callback_url,
            "txnAmount" => [
                "value" => $eventEnrollment->total,
                "currency" => "INR",
            ],
            "userInfo" => [
                "custId" => $eventEnrollment->user->tracking_id
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

            $response = (new Client())->request('POST', env('PAYTM_BASE_URL') . "theia/api/v1/initiateTransaction?mid=" . env('PAYTM_MERCHANT_ID') . "&orderId=" . $eventEnrollment->enrollment_number, [
                'body' => $post_data,
                'headers' => [
                    'Content-Type' => 'application/json'
                ]
            ]);

            $response = json_decode($response->getBody());

            return (object)[
                'status' => true, 'token' => $response->body->txnToken
            ];

        } catch (RequestException $e) {

            \Log::warning('Paytm Token Generation Failed', [
                'order_id' => $eventEnrollment->enrollment_number,
                'exception' => $e->getMessage()
            ]);

            return (object)[
                'status' => false, 'token' => null
            ];
        }
    }

    public function handleResponse(EventEnrollment $eventEnrollment, Request $request)
    {

        if ($request->get('custom_status') == 'CANCEL') {
            return (object)['status' => 'cancel', 'message' => 'Cancel By the User while entering Bank details'];

        }
        else {

            $body = [
                "mid" => env('PAYTM_MERCHANT_ID'),
                "orderId" => $eventEnrollment->enrollment_number,
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

                $this->paymentData(json_decode($response->getBody()), $request, $eventEnrollment);


            } catch (RequestException $e) {

                \Log::warning('Paytm Token Generation Failed', [
                    'event_id' => $eventEnrollment->id,
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
     * @param EventEnrollment $eventEnrollment
     * @return mixed
     * @throws \Throwable
     */
    public function paymentData($paytm_response, $request, EventEnrollment $eventEnrollment)
    {

        \DB::transaction(function () use ($paytm_response, $request, $eventEnrollment) {

            if ($paytm_response->body->resultInfo->resultStatus == "TXN_SUCCESS") {

                $status = PaymentGatewayTransaction::SUCCESS;

                $eventEnrollment->payment_reference = $request->TXNID;
                $eventEnrollment->payment_status = EventEnrollment::PAYMENT_SUCCESS;
                $eventEnrollment->status = EventEnrollment::APPROVED;
                $eventEnrollment->save();

                if($eventEnrollment->wallet > 0 && $eventEnrollment->amount == 0){
                    $payment_mode = EventEnrollment::WALLET;
                } elseif ($eventEnrollment->wallet > 0 && $eventEnrollment->amount > 0){
                    $payment_mode = EventEnrollment::WALLET_WITH_ONLINE;
                } else {
                    $payment_mode = EventEnrollment::ONLINE;
                }

                $number = EventEnrollment::generateEnrollmentNumber();
                while (EventEnrollment::whereUserEnrollmentNumber($number)->exists()){
                    $number = EventEnrollment::generateEnrollmentNumber($number);
                }

                $eventEnrollment->user_enrollment_number = $number;
                $eventEnrollment->barcode_data = (new DNS1D())->getBarcodePNG($eventEnrollment->user_enrollment_number, "C128");
                $eventEnrollment->payment_mode = $payment_mode;
                $eventEnrollment->save();

            } elseif ($paytm_response->body->resultInfo->resultStatus == "TXN_FAILURE") {

                $status = PaymentGatewayTransaction::FAILED;
                $eventEnrollment->payment_reference = $request->TXNID;
                $eventEnrollment->payment_status = Order::PAYMENT_FAILED;
                $eventEnrollment->status = Order::FAILED;
                $eventEnrollment->save();

                if ($eventEnrollment->wallet > 0) {

                    $eventEnrollment->user->creditShoppingWallet(collect([
                        'event_enrollment_id' => $eventEnrollment->id,
                        'amount' => $eventEnrollment->wallet,
                        'remarks' => 'Event Enrollment Rejected Credit Rs ' . $eventEnrollment->wallet . ' on ' . now()->format('M d, Y H:i A')
                    ]));

                }

            } else {

                $status = PaymentGatewayTransaction::CANCEL;
                $eventEnrollment->payment_status = Order::PAYMENT_FAILED;
                $eventEnrollment->status = Order::REJECTED;
                $eventEnrollment->save();

            }

            if (!PaymentGatewayTransaction::whereEventEnrollmentId($eventEnrollment->id)->exists()) {

                if (in_array($eventEnrollment->status, [EventEnrollment::REJECTED, EventEnrollment::FAILED]) && $eventEnrollment->wallet > 0) {
                    $eventEnrollment->user->creditShoppingWallet(collect([
                        'event_enrollment_id' => $eventEnrollment->id,
                        'amount' => $eventEnrollment->wallet,
                        'remarks' => 'Event Enrollment Rejected Credit Rs ' . $eventEnrollment->wallet . ' on ' . now()->format('M d, Y H:i A')
                    ]));
                }

                PaymentGatewayTransaction::create([
                    'event_enrollment_id' => $eventEnrollment->id,
                    'provider' => 'paytm',
                    'reference_id' => $request->TXNID,
                    'response' => collect($paytm_response)->toArray(),
                    'status' => $status
                ]);

                return redirect()->route('user-event-details', ['id' => $eventEnrollment->event->id])->with(['success' => $paytm_response->body->resultInfo->resultMsg]);

            }

        });

        return $eventEnrollment;

    }
}
