<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use App\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class AccountController extends Controller
{
    public function dashboard()
    {
        $user = Session::get('user');

        if (!$user) {
            return redirect()->route('website.login')->with('error', 'Please login to access account.');
        }

        $userId = $user['id'] ?? null;

        $totalOrders     = $userId ? Order::where('user_id', $userId)->count() : 0;
        $deliveredOrders = $userId ? Order::where('user_id', $userId)->where('order_status', 'delivered')->count() : 0;
        $pendingOrders   = $userId ? Order::where('user_id', $userId)->whereIn('order_status', ['pending', 'processing', 'confirmed', 'shipped'])->count() : 0;

        return view('website.account.dashboard', compact('user', 'totalOrders', 'deliveredOrders', 'pendingOrders'));
    }
}
