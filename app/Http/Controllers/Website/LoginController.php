<?php

namespace App\Http\Controllers\Website;

use App\Cart;
use App\customers;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;

class LoginController extends Controller
{
    public function loginPage()
    {
        if (session()->has('user')) {
            return redirect()->route('website.home');
        }
        return view('website.auth.login');
    }

    // Login Submit
    public function loginSubmit(Request $request)
    {
        $request->validate([
            'email'    => 'required',
            'password' => 'required|min:4',
        ]);

        $user = customers::where('primary_email', $request->email)
            ->orWhere('primary_phone', $request->email)
            ->first();

        if (!$user) {
            return back()->with('error', 'User not found');
        }

        if (!Hash::check($request->password, $user->password)) {
            return back()->with('error', 'Incorrect password');
        }

        // ✅ Set session
        session(['user' => [
            'id'    => $user->id,
            'name'  => $user->customer_name,
            'email' => $user->primary_email,
            'phone' => $user->primary_phone,
        ]]);
        $sessionId = request()->session()->getId();
        $cart = Cart::query()->where('session_id', $sessionId)->update(['user_id' => $user->id]);
        return redirect()->route('website.home')->with('success', 'Logged in successfully!');
    }

    // Show Register Page
    public function registerPage()
    {
        if (session()->has('user')) {
            return redirect()->route('website.home');
        }
        return view('website.auth.register');
    }

    // Register Submit
    public function registerSubmit(Request $request)
    {
        dd($request->all());
        $request->validate([
            'name'                  => 'required|string|max:255',
            'email'                 => 'required|email|unique:customers,primary_email',
            'mobile'                => 'nullable|string|max:15|unique:customers,primary_phone',
            'password'              => 'required|min:4|confirmed',
        ]);

        $user = new customers();
        $user->customer_name = $request->name;
        $user->primary_email = $request->email;
        $user->primary_phone = $request->mobile;
        $user->password      = Hash::make($request->password);
        $user->save();

        return redirect()->route('website.login')->with('success', 'Registration successful! Please login.');
    }

    // Logout
    public function logout()
    {
        session()->forget('user');
        return redirect()->route('website.login')->with('success', 'Logged out successfully');
    }

    public function forgotPassword() {
        return view('website.auth.forgot_password');
    }

    public function forgotPasswordPost(Request $request) {
        $request->validate(['email' => 'required|email']);
        $user = customers::where('primary_email', $request->email)->first();

        if(!$user) {
            return back()->with('error', 'Email not found!');
        }

        $token = Str::random(64);
        DB::table('password_resets')->insert([
            'email' => $request->email,
            'token' => $token,
            'created_at' => now()
        ]);

        Mail::send('website.auth.emails.reset_link', ['token' => $token], function($message) use($request) {
            $message->to($request->email);
            $message->subject('Password Reset Link');
        });

        return back()->with('success', 'Password reset link sent to your email');
    }

    public function resetPassword($token) {
        return view('website.auth.reset_password', compact('token'));
    }

    public function resetPasswordPost(Request $request) {
        $request->validate([
            'password' => 'required|min:6|confirmed'
        ]);

        $resetRecord = DB::table('password_resets')->where('token', $request->token)->first();

        if(!$resetRecord) {
            return back()->with('error', 'Invalid or expired link');
        }

        customers::where('primary_email', $resetRecord->email)->update([
            'password' => Hash::make($request->password)
        ]);

        DB::table('password_resets')->where('email', $resetRecord->email)->delete();

        return redirect()->route('website.login')->with('success', 'Password updated successfully!');
    }
}
