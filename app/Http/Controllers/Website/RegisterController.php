<?php

namespace App\Http\Controllers\Website;

use App\customers;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class RegisterController extends Controller
{
    public function index(Request $request)
    {
        if ($request->isMethod('POST')) {
            $request->validate([
                'name' => 'required|string|max:100',
                'email' => 'required|email|unique:customers,primary_email',
                'mobile' => 'nullable|string|max:15|unique:customers,primary_phone',
                'password' => 'required|min:6|confirmed',
            ]);

            $user = new customers();
            $user->customer_name = $request->name;
            $user->primary_email = $request->email;
            $user->primary_phone = $request->mobile;
            $user->password = Hash::make($request->password);
            $user->save();

            // Store login details manually in session
            Session::put('user', [
                'id' => $user->id,
                'name' => $user->customer_name,
                'email' => $user->primary_email,
                'mobile' => $user->primary_phone,
                'login_time' => now(),
            ]);


            return redirect()->route('website.home')
                ->with('success', 'Account created successfully!');
        }
        return view('website.auth.register');
    }
}
