<?php

namespace App\Http\Controllers\Website;

use App\Address;
use App\country;
use App\customers;
use App\Http\Controllers\Controller;
use App\Library\Helper;
use App\Library\PaymentGateway\Paytm;
use App\Library\PaymentGateway\Razorpay;
use App\Models\Inventory\StockTransaction;
use App\Models\Order;
use App\Models\OrderDetail;
use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class OrderProcessController extends Controller
{
    public function address(Request $request)
    {
        $selected_address = null;

        $user = customers::whereId(\Session::get('user')['id'])->first();

        $addresses = Address::whereUserId($user->id)->get();

        if ($request->isMethod('post')) {

            $this->validate($request, [
                'address_id' => 'required|exists:addresses,id'
            ], [
                'address_id.required' => 'Select any Single Address for Process',
                'address_id.exists' => 'Invalid Address Data, Try again'
            ]);

            $address = Address::with('state')->whereId($request->address_id)->first();

            if (!$address->name || !$address->mobile)
                return redirect()->back()->with(['error' => 'Kindly Add Your Name and Mobile Number in Address']);

            $new_order = [
                'items' => \Session::get('order-checkout')['items'],
                'orderSummary' => \Session::get('order-checkout')['orderSummary'],
                'address' => $address
            ];

            \Session::forget('order-checkout');
            \Session::put('order-checkout', $new_order);

            return redirect()->route('website.order.checkout.payment');
        }

        $userAddress = (object)[
            'name' => $user->customer_name,
            'mobile' => $user->primary_phone,
            'email' => $user->primary_email,
        ];
        \Session::put('orderUser', $userAddress);

        if ($request->id) {
            $selected_address = \App\Address::whereUserId(\Session::get('user')['id'])->whereId($request->id)->first();
        }

        $order = \Session::get('order-checkout');

        return view('website.order-process.address', [
            'order' => Helper::arrayToObject($order),
            'countries' => country::pluck('country_name', 'id')->toArray(),
            'addresses' => $addresses,
            'selected_address' => $selected_address
        ]);
    }

    public function payment(Request $request)
    {
        $order = \Session::get('order-checkout');

        return view('website.order-process.payment', [
            'order' => Helper::arrayToObject($order),
            'user' => customers::whereId(\Session::get('user')['id'])->first(),
        ]);
    }

    public function process(Request $request): JsonResponse
    {
        $validator = \Validator::make($request->all(), [
            'items' => 'required|array',
            'orderSummary' => 'required',
            'shippingAddressId' => 'required',
        ]);

        if ($validator->fails())
            return response()->json(['status' => false, 'message' => $validator->errors()->first()]);

        if (Order::where('user_id', \Session::get('user')->id)
            ->where('approved_at', '>', now()->subMinute())
            ->exists()) {
            return response()->json([
                'status' => false,
                'message' => 'We are currently processing your last order. You can order after a minute.'
            ]);
    }


    $formRequest = Helper::arrayToObject($request->all());

    if (!$formRequest->isWalletUse && !$formRequest->paymentGatewayId) {
        return response()->json(['status' => false, 'message' => 'Payment gateway or Wallet selection is required.']);
    }

    if ($formRequest->isWalletUse && $formRequest->orderSummary->total_amount > 0 && !$formRequest->paymentGatewayId) {
        return response()->json([
            'status' => false,
            'message' => 'Payment gateway selection is required for remaining amount of Rs.' . $formRequest->orderSummary->total_amount
        ]);
    }

    if ($formRequest->orderSummary->total_amount == 0 && $formRequest->orderSummary->wallet > 0) {
        $payment_gateway = null;
    } else {
        $payment_gateway = $formRequest->paymentGatewayId;
    }

    if ($formRequest->orderSummary->total_amount == 0 && $formRequest->orderSummary->wallet > 0) {
        $payment_source = Order::PAY_WALLET;

    } elseif ($formRequest->orderSummary->total_amount > 0 && $formRequest->orderSummary->wallet > 0) {
        $payment_source = Order::PAY_MIXED;

    } else {
        $payment_source = Order::PAY_GATEWAY;
    }

    $order = \DB::transaction(function () use ($formRequest, $payment_source, $payment_gateway) {

        $shipping_address = Address::with([
            'country:id,name', 'state:id,name', 'city:id,name'
        ])->whereId($formRequest->shippingAddressId)->with(['state:id,name'])->first();

        $order = Order::create([
            'user_id' => \Session::get('user')->id,
            'amount' => $formRequest->orderSummary->amount,
            'total' => $formRequest->orderSummary->total_amount + $formRequest->orderSummary->wallet,
            'online_amount' => $formRequest->orderSummary->total_amount,
            'total_bv' => $formRequest->orderSummary->total_bv,
            'payment_source' => $payment_source,
            'shipping_charge' => $formRequest->orderSummary->shipping_charge,
            'wallet' => $formRequest->orderSummary->wallet,
            'payment_gateway' => $payment_gateway,
            'shipping_address' => $shipping_address,
            'status' => Order::CHECKOUT,
            'discount' => 0,
            'payment_status' => Order::PAYMENT_CHECKOUT,
        ]);

        if ($order->payment_source == Order::PAY_WALLET) {
            $order->status = Order::APPROVED;
            $order->payment_status = Order::PAYMENT_SUCCESS;
            $order->approved_at = now();
        }

        $order->customer_order_id = 'WO' . now()->timestamp . $order->id;
        $order->save();

        collect(Helper::arrayToObject($formRequest->items))->map(function ($item) use ($order) {
            $details = OrderDetail::create([
                'order_id' => $order->id,
                'product_price_id' => $item->id,
                'price' => $item->price,
                'selling_price' => $item->selling_price,
                'qty' => $item->quantity,
                'gst' => [
                    'code' => $item->gst->code,
                    'percentage' => $item->gst->percentage
                ]
            ]);
        });

        if ($order->wallet > 0) {
            User::find(\Session::get('user')->id)->debitShoppingWallet(collect([
                'order_id' => $order->id, 'amount' => $order->wallet,
                'remarks' => 'Debit against Order Id: ' . $order->customer_order_id
            ]));

            if ($order->total == $order->wallet) {
                $order->invoice_no = Order::generateInvoiceNumber();
                $order->save();

                collect($order->details)->map(function ($item) {
                    (new StockTransaction())->stockCredit($item);
                });

                $totalBv = Order::whereUserId($order->user_id)->whereNotNull('approved_at')->sum('total_bv') ?? 0;

                if ($totalBv >= 60 && empty($order->user->activated_at)) {
                    User::whereId($order->user_id)->update(['activated_at' => now()]);
                }
            }
        }

        return $order;
    });

    \Session::forget('order-checkout');
    \Session::forget('cart');
    \Session::put('order_number', $order->customer_order_id);

    return response()->json([
        'status' => true,
        'message' => "Order: {$order->customer_order_id} is placed successfully",
        'customer_order_id' => $order->customer_order_id,
        'user' => [
            'name' => $order->user->info->full_name,
            'mobile' => $order->user->mobile,
            'email' => $order->user->email
        ],
        'amount' => (float)$order->total == (float)$order->wallet ? 0 : (float)$order->total,
        'overview_route' => route('website.overview', ['customer_order_id' => $order->customer_order_id]),
    ]);
}

public function paymentResponse(Request $request)
{
    if ($request->ORDERID) {
        if (!$order = Order::whereCustomerOrderId($request->ORDERID)->first())
            return redirect()->route('website.home')->with(['error' => 'Payment failed or Order Not found try again']);
    } else {

        if (!$order = Order::whereCustomerOrderId($request->get('customer_order_id'))->first())
            return redirect()->route('website.home')->with(['error' => 'Payment failed or Order Not found try again']);
    }
    if ($order->payment_gateway == 2) {
        (new Razorpay())->handleResponse($order, $request);
    }

    return redirect(route('website.overview', ['customer_order_id' => $order->customer_order_id]));
}

public function overview(Request $request)
{
    $order = Order::with(['user.info', 'details.product_price.product'])->whereCustomerOrderId($request->customer_order_id)->first();

    if (!\Session::has('order_number')) {
        return redirect()->route('website.product.view');
    }
    \Session::forget('order_number');

    return view('website.order-process.overview', [
        'order' => $order
    ]);
}
}
