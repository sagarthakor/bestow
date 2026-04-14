<?php

namespace App\Http\Controllers\Website;

use App\CartItem;
use App\city;
use App\company;
use App\customers;
use App\Http\Controllers\Controller;
use App\Cart;
use App\Mail\OrderPlacedMail;
use App\Order;
use App\OrderItem;
use App\ShippingCharge;
use App\state;
use App\UserAddress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use Razorpay\Api\Api;

class CheckoutController extends Controller
{
    /**
     * Helper: compute shipping fee by state + subtotal
     */
    private function calculateShippingFee($stateId, $subtotal)
    {
        if (empty($stateId) || $subtotal === null) {
            return 0;
        }

        $charge = ShippingCharge::where('state_id', $stateId)
            ->where('min_amount', '<=', $subtotal)
            ->where(function ($q) use ($subtotal) {
                $q->where('max_amount', '>=', $subtotal)->orWhereNull('max_amount');
            })
            ->orderBy('min_amount', 'asc')
            ->first();

        return $charge ? (float) $charge->shipping_fee : 0.0;
    }

    /**
     * Checkout page
     */
    public function index()
    {
        // Logged-in customer (stored in session('user'))
        $user = null;
        if (session()->has('user')) {
            $user = customers::find(session('user')['id']);
        }

        // States
        $states = state::all();

        // Cart items (by cart_id or session_id fallback)
        $cartItems = CartItem::with('product')->where('cart_id', session('cart_id'))->get();
        if (empty(session('cart_id'))) {
            $cartItems = CartItem::with('product')
                ->whereHas('cart', function ($q) {
                    $q->where('session_id', request()->session()->getId());
                })
                ->get();
        }

        if ($cartItems->count() === 0) {
            return redirect()->route('website.cart.view')->with('error', 'Your cart is empty.');
        }

        // Subtotal
        $subtotal = $cartItems->sum(fn($i) => $i->qty * $i->price);

        // Saved addresses
        $addresses = collect();
        if ($user) {
            $addresses = UserAddress::with(['state','city'])
                ->where('user_id', $user->id)
                ->orderByDesc('is_default')
                ->orderByDesc('id')
                ->get();
        }

        // Default shipping by default address (if any)
        $shippingCharge = 0;
        if ($addresses->count()) {
            $defaultStateId = optional($addresses->firstWhere('is_default', 1))->state_id
                ?? $addresses->first()->state_id;
            $shippingCharge = $this->calculateShippingFee($defaultStateId, $subtotal);
        }

        $finalTotal = $subtotal + $shippingCharge;

        // Also pass $total to avoid "Undefined $total" in older blade lines
        $total = $subtotal;

        return view('website.checkout.index', compact(
            'cartItems',
            'subtotal',
            'total',
            'shippingCharge',
            'finalTotal',
            'user',
            'states',
            'addresses'
        ));
    }

    /**
     * AJAX: recompute shipping by state + subtotal
     * Route name suggestion: website.checkout.shipping (POST)
     */
    public function calculateShipping(Request $request)
    {
        $request->validate([
            'state_id' => 'required|integer',
            'subtotal' => 'required|numeric'
        ]);

        $shipping = $this->calculateShippingFee($request->state_id, (float) $request->subtotal);

        return response()->json([
            'shipping'    => $shipping,
            'final_total' => (float) $request->subtotal + $shipping
        ]);
    }

    /**
     * Optional: Save address via a separate AJAX (if you use a modal)
     */
    public function storeAddress(Request $request)
    {
        if (!session()->has('user')) {
            return response()->json(['status' => false, 'message' => 'Please sign in to save addresses.'], 401);
        }

        $request->validate([
            'name'       => 'required|string|max:255',
            'phone'      => 'required|string|max:20',
            'address'    => 'required|string|max:500',
            'state_id'   => 'required|integer',
            'city_id'    => 'required|integer',
            'pincode'    => 'required|string|max:10',
            'is_default' => 'nullable|boolean'
        ]);

        $userId = session('user')['id'];

        DB::transaction(function () use ($request, $userId) {
            if ($request->boolean('is_default')) {
                UserAddress::where('user_id', $userId)->update(['is_default' => 0]);
            }

            UserAddress::create([
                'user_id'    => $userId,
                'name'       => $request->name,
                'phone'      => $request->phone,
                'address'    => $request->address,
                'state_id'   => $request->state_id,
                'city_id'    => $request->city_id,
                'pincode'    => $request->pincode,
                'is_default' => $request->boolean('is_default') ? 1 : 0,
            ]);
        });

        $addresses = UserAddress::with(['state','city'])
            ->where('user_id', $userId)
            ->orderByDesc('is_default')->orderByDesc('id')->get();

        return response()->json(['status' => true, 'addresses' => $addresses]);
    }

    /**
     * Place order (COD / Razorpay)
     * - Saves new address if "save_address" is checked
     * - Sends order emails (customer + admin)
     */
    /**
     * Handle Order Request (Only Create Razorpay Order – No DB Save Yet)
     */
    public function placeOrder(Request $request)
    {
        $isManual = $request->input('address_mode') === 'manual';

        $request->validate([
            'payment_method'      => 'required|in:COD,razorpay',
            'selected_address_id' => 'nullable|exists:user_addresses,id',

            'name'    => 'required_without:selected_address_id|string|max:255',
            'phone'   => 'required_without:selected_address_id|string|max:20',
            'address' => 'required_without:selected_address_id|string|max:500',
            'pincode' => 'required_without:selected_address_id|string|max:10',

            // Dropdown mode
            'state_id' => $isManual ? 'nullable' : 'required_without:selected_address_id|integer',
            'city_id'  => $isManual ? 'nullable' : 'required_without:selected_address_id|integer',

            // Manual mode
            'state_name_custom' => $isManual ? 'required_without:selected_address_id|string|max:100' : 'nullable',
            'city_name_custom'  => $isManual ? 'required_without:selected_address_id|string|max:100' : 'nullable',

            'save_address' => 'nullable|boolean',
            'is_default'   => 'nullable|boolean',
        ]);

        $cartItems = CartItem::with('product')->where('cart_id', session('cart_id'))->get();
        if (!$cartItems->count()) {
            return response()->json(['status'=>false,'message'=>'Cart is empty']);
        }

        $subtotal = $cartItems->sum(fn($i)=> $i->qty * $i->price);
        $userId   = session('user')['id'] ?? null;

        /** ✅ Resolve Address */
        if ($request->filled('selected_address_id')) {
            $addr = UserAddress::find($request->selected_address_id);
            $addressData = $addr->only(['name','phone','address','state_id','city_id','pincode']);
            $addressData['state_name'] = optional($addr->state)->state_name;
            $addressData['city_name']  = optional($addr->city)->city_name;
        } else {
            if ($isManual) {
                $addressData = [
                    'name'       => $request->name,
                    'phone'      => $request->phone,
                    'address'    => $request->address,
                    'pincode'    => $request->pincode,
                    'state_id'   => null,
                    'city_id'    => null,
                    'state_name' => trim($request->state_name_custom),
                    'city_name'  => trim($request->city_name_custom),
                ];
            } else {
                $addressData = $request->only(['name','phone','address','state_id','city_id','pincode']);
                $addressData['state_name'] = null;
                $addressData['city_name']  = null;

                if ($userId && $request->boolean('save_address')) {
                    DB::transaction(function () use ($request,$userId) {
                        if ($request->boolean('is_default')) {
                            UserAddress::where('user_id',$userId)->update(['is_default'=>0]);
                        }
                        UserAddress::create([
                            'user_id'    => $userId,
                            'name'       => $request->name,
                            'phone'      => $request->phone,
                            'address'    => $request->address,
                            'state_id'   => $request->state_id,
                            'city_id'    => $request->city_id,
                            'pincode'    => $request->pincode,
                            'is_default' => $request->boolean('is_default') ? 1 : 0,
                        ]);
                    });
                }
            }
        }

        /** ✅ Calculate Shipping (manual mode has no state_id → 0 charge) */
        $shippingCharge = $addressData['state_id']
            ? $this->calculateShippingFee($addressData['state_id'], $subtotal)
            : 0;
        $totalAmount    = $subtotal + $shippingCharge;

        /** ✅ If COD → Direct Save Order & Return */
        if ($request->payment_method === 'COD') {
            return $this->createOrderAndSendMail($cartItems, $subtotal, $shippingCharge, $totalAmount, $addressData, 'COD');
        }

        /** ✅ If Razorpay → Create Razorpay Order (No DB Save Yet) */
        $api = new Api(config('services.razorpay.key'), config('services.razorpay.secret'));
        $rzpOrder = $api->order->create([
            'receipt'         => 'ORD-' . strtoupper(Str::random(6)),
            'amount'          => $totalAmount * 100,
            'currency'        => 'INR',
            'payment_capture' => 1,
        ]);

        session([
            'pending_order' => [
                'cart_items' => $cartItems->toArray(),
                'subtotal'   => $subtotal,
                'shipping'   => $shippingCharge,
                'total'      => $totalAmount,
                'address'    => $addressData,
                'razorpay_order_id' => $rzpOrder['id']
            ],
        ]);

        return response()->json([
            'payment_method'=>'razorpay',
            'key'=>config('services.razorpay.key'),
            'amount'=>$rzpOrder['amount'],
            'razorpay_order_id'=>$rzpOrder['id']
        ]);
    }


    /**
     * ✅ Razorpay Payment Verification & Final Save Order
     */
    public function verifyRazorpay(Request $request)
    {
        try {
            $api = new Api(config('services.razorpay.key'), config('services.razorpay.secret'));

            $api->utility->verifyPaymentSignature([
                'razorpay_order_id'   => $request->razorpay_order_id,
                'razorpay_payment_id' => $request->razorpay_payment_id,
                'razorpay_signature'  => $request->razorpay_signature
            ]);

            $data = session('pending_order');
            if (!$data) {
                return response()->json(['status'=>false,'message'=>'No pending order!']);
            }

            $response = $this->createOrderAndSendMail(
                collect($data['cart_items']),
                $data['subtotal'],
                $data['shipping'],
                $data['total'],
                $data['address'],
                'razorpay',
                $request->razorpay_payment_id,
                $request->razorpay_signature,
                $data['razorpay_order_id']
            );

            session()->forget('pending_order');
            return response()->json(['status'=>true,'redirect'=>$response['redirect_url']]);

        } catch (\Exception $e) {
            return response()->json(['status'=>false,'message'=>'Payment verification failed']);
        }
    }
    /**
     * ✅ Final Helper: Save Order + Items + Send Email + Clear Cart
     * This will run only:
     * - COD (on placeOrder directly)
     * - Razorpay (after verify success)
     */
    private function createOrderAndSendMail($cartItems, $subtotal, $shippingCharge, $totalAmount, $addressData, $paymentMethod, $rzpPaymentId = null, $rzpSignature = null, $rzpOrderId = null)
    {
        DB::beginTransaction();
        try {
            $userId = session('user')['id'] ?? null;
            $user = $userId ? customers::find($userId) : null;

            // ✅ Save Order in DB
            $order = Order::create([
                'user_id'         => $userId,
                'cart_id'         => session('cart_id'),
                'order_number'    => 'ORD-' . strtoupper(Str::random(8)),
                'name'            => $addressData['name'],
                'phone'           => $addressData['phone'],
                'address'         => $addressData['address'],
                'state_id'        => $addressData['state_id']   ?? null,
                'city_id'         => $addressData['city_id']    ?? null,
                'state_name'      => $addressData['state_name'] ?? null,
                'city_name'       => $addressData['city_name']  ?? null,
                'pincode'         => $addressData['pincode'],

                'amount'          => $subtotal,
                'shipping_charge' => $shippingCharge,
                'total_amount'    => $totalAmount,

                'payment_method'  => $paymentMethod,
                'payment_status'  => $paymentMethod == 'COD' ? 'pending' : 'paid',
                'order_status'    => $paymentMethod == 'COD' ? 'placed' : 'confirmed',

                'razorpay_order_id'   => $rzpOrderId,
                'razorpay_payment_id' => $rzpPaymentId,
                'razorpay_signature'  => $rzpSignature,
            ]);

            // ✅ Save Order Items
            foreach ($cartItems as $item) {
                OrderItem::create([
                    'order_id'   => $order->id,
                    'product_id' => $item['product_id'],
                    'variant_id' => $item['variant_id'] ?? null,
                    'qty'        => $item['qty'],
                    'price'      => $item['price'],
                ]);
            }

            // ✅ Mail Data
            $mailData = [
                'order'     => $order->fresh(['state','city']),
                'items'     => array_map(function($i){
                    return [
                        'name'  => $i['product']['product_name'] ?? 'Product',
                        'image' => $i['image'] ?? null,
                        'qty'   => $i['qty'],
                        'price' => $i['price'],
                        'total' => $i['qty'] * $i['price'],
                    ];
                }, $cartItems->toArray()),

                'subtotal'  => $subtotal,
                'shipping'  => $shippingCharge,
                'grand'     => $totalAmount,
                'address'   => $addressData,
                'order_url' => route('website.order.success', $order->id)
            ];

            // ✅ Send Emails (Customer + Admin)
            try {
                if ($user && !empty($user->primary_email)) {
                    Mail::to($user->primary_email)->queue(new OrderPlacedMail($order, $mailData));
                }

                $company = company::select('email')->first();
                $companyEmail = $company->email ?? 'saggyt19@gmail.com';
                Mail::to($companyEmail)->queue(new OrderPlacedMail($order, $mailData));
            } catch (\Throwable $mailEx) {}

            // ✅ Clear cart after final successful order store
            CartItem::where('cart_id', session('cart_id'))->delete();
            session()->forget('cart_count');

            DB::commit();

            return [
                'status'       => true,
                'redirect_url' => route('website.order.success', $order->id)
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            return ['status'=>false, 'message'=>$e->getMessage()];
        }
    }


/**
     * Cities for a state (for the dropdown)
     */
    public function getCities($state_id)
    {
        return response()->json(city::where('state', $state_id)->get());
    }
}
