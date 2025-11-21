<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Notifications\OrderShipped;
use App\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $orders = Order::latest()->paginate(20);
        return view('admin.website-order.index', compact('orders'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        $order = Order::find($request->id);
        $request->validate([
            'status' => 'required',
            'courier_name' => 'required_if:status,shipped',
            'tracking_number' => 'required_if:status,shipped',
        ]);

        $order->status = $request->status;

        if ($request->status === 'shipped') {
            $order->courier_name = $request->courier_name;
            $order->tracking_number = $request->tracking_number;
            $order->tracking_url = $request->tracking_url ?? null;
            $order->shipped_at = now();

            // ✅ Email notification to the customer
            if ($order->user) {
                $order->user->notify(new OrderShipped($order));
            }
        }

        if ($request->status === 'delivered') {
            $order->delivered_at = now();
        }

        if ($request->status === 'confirmed') {
            $order->confirmed_at = now();
        }

        $order->save();
        return back()->with('message', 'Order status updated successfully!');
    }

    public function view($id)
    {
        $order = Order::with(['user', 'city', 'state', 'order_items.product'])->findOrFail($id);
        return view('admin.website-order.view', compact('order'));
    }

}
