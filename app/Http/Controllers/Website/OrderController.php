<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use App\Order;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class OrderController extends Controller
{
    // Order Success Page
    public function success($id, $razorpay_order_id = null)
    {
        $order = Order::with('order_items.product')->findOrFail($id);
        return view('website.order.success', compact('order'));
    }

    // My Orders (For Logged-in Users)
    public function myOrders()
    {
        $user = Session::get('user');

        if (!$user) {
            return redirect()->route('website.login')->with('error', 'Please login to view orders.');
        }

        // Fetch user-specific orders with items
        $orders = Order::where('user_id', $user['id'])
            ->with('order_items.product', 'city', 'state')
            ->latest()
            ->get();

        return view('website.account.orders', compact('orders'));
    }

    // Order Details (Show Items)
    public function orderDetails($id)
    {
        $user = Session::get('user');

        $query = Order::where('id', $id)->with('order_items.product', 'state', 'city');
        if ($user) {
            $query->where('user_id', $user['id']);
        }
        $order = $query->firstOrFail();

        return view('website.order.details', compact('order'));
    }

    // Guest Track Order Form
    public function trackOrderForm()
    {
        return view('website.order.track');
    }

    // Guest Track Order Submit
    public function trackOrder(Request $request)
    {
        $request->validate([
            'order_number' => 'required',
            'phone' => 'required'
        ]);

        $order = Order::where('order_number', $request->order_number)
            ->where('phone', $request->phone)
            ->with('order_items.product', 'state', 'city')
            ->first();

        if(!$order){
            return back()->with('error', 'Order not found! Check your Order ID & Phone number.');
        }

        return view('website.order.details', compact('order'));
    }

    public function cancel($id)
    {
        $order = Order::where('id', $id)->where('user_id', Session::get('user')['id'])->firstOrFail();

        if (in_array($order->status, ['placed','confirmed'])) {
            $order->status = 'cancelled';
            $order->save();
            return back()->with('success', 'Order cancelled successfully.');
        }

        return back()->with('error', 'Order cannot be cancelled now.');
    }

    public function return($id)
    {
        $order = Order::where('id', $id)->where('user_id', Session::get('user')['id'])->firstOrFail();

        if ($order->status == 'delivered') {
            $order->status = 'return_requested';
            $order->save();
            return back()->with('success', 'Return request submitted.');
        }

        return back()->with('error', 'Return not allowed.');
    }

    public function invoice($id)
    {
        $order = Order::with('order_items.product', 'state', 'city')
            ->where('id', $id)
            ->firstOrFail();

        $pdf = PDF::loadView('website.account.invoice', compact('order'))
            ->setPaper('a4');

        return $pdf->download(Carbon::now()->format('ymdhis').'_invoice_order_'.$order->id.'.pdf');
    }
}
