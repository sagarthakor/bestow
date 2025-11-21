<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class AccountController extends Controller
{
    public function dashboard()
    {
        $user = Session::get('user'); // If you store user in session manually

        if (!$user) {
            return redirect()->route('website.login')->with('error', 'Please login to access account.');
        }

        return view('website.account.dashboard', compact('user'));
    }
}
