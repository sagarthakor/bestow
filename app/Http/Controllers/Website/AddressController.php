<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use App\Address;
use App\User;
use Illuminate\Http\Request;

class AddressController extends Controller
{
    public function create(Request $request)
    {
        $validator = \Validator($request->all(), [
            'name' => 'required',
            'mobile' => 'required|regex:/^[9876][0-9]{9}$/|digits:10',
            'email' => 'email',
            'address' => 'required|min:10|max:180',
            'country_id' => 'required',
            'city_id' => 'required',
            'state_id' => 'required',
            'pin_code' => 'required|digits:6'
        ], [
            'name.required' => 'Name is required',
            'mobile.required' => 'Mobile Number is required',
            'mobile.regex' => 'Invalid Mobile Number',
            'mobile.digits' => 'Mobile Number must be 10 Digits',
            'email.email' => 'Invalid Email Format',
            'address.required' => 'Your Address is required',
            'address.min' => 'Address should be more than 10 character',
            'country_id.required' => 'Country is required',
            'state_id.required' => 'State is required',
            'city_id.required' => 'City is required',
            'pin_code.required' => 'PinCode is required',
            'pin_code.digits' => 'PinCode should be in 6 digits',
        ]);

        if ($validator->fails())
            return back()->withInput()->with(['address_errors' => $validator->errors()->all()]);

        Address::create([
            'user_id' => \Session::get('user')['id'],
            'name' => $request->name,
            'mobile' => $request->mobile,
            'email' => $request->email,
            'address' => $request->address,
            'country_id' => $request->country_id,
            'city_id' => $request->city_id,
            'state_id' => $request->state_id,
            'pin_code' => $request->pin_code,
            'type' => $request->type
        ]);

        return redirect()->route($request->page)->with(['success' => 'New address has been added']);
    }

    public function update(Request $request)
    {
        $validator = \Validator($request->all(), [
            'name' => 'required',
            'mobile' => 'required|regex:/^[9876][0-9]{9}$/|digits:10',
            'email' => 'email',
            'address' => 'required|min:10|max:180',
            'country_id' => 'required',
            'city_id' => 'required',
            'state_id' => 'required',
            'pin_code' => 'required|digits:6'
        ], [
            'name.required' => 'Name is required',
            'mobile.required' => 'Mobile Number is required',
            'mobile.regex' => 'Invalid Mobile Number',
            'mobile.digits' => 'Mobile Number must be 10 Digits',
            'email.email' => 'Invalid Email Format',
            'address.required' => 'Your Address is required',
            'address.min' => 'Address should be more than 10 character',
            'country_id.required' => 'Country is required',
            'state_id.required' => 'State is required',
            'city_id.required' => 'City is required',
            'pin_code.required' => 'PinCode is required',
            'pin_code.digits' => 'PinCode should be in 6 digits',
        ]);

        if ($validator->fails())
            return back()->withInput()->with(['address_errors' => $validator->errors()->all()]);

        if (!$address = Address::whereUserId(\Session::get('user')['id'])->whereId($request->id)->first())
            return back()->withInput()->with(['address_errors' => ['Invalid Address Data for Edit']]);

        $address->name = $request->name;
        $address->mobile = $request->mobile;
        $address->email = $request->email;
        $address->address = $request->address;
        $address->country_id = $request->country_id;
        $address->city_id = $request->city_id;
        $address->state_id = $request->state_id;
        $address->pin_code = $request->pin_code;
        $address->type = $request->type;
        $address->save();

        return redirect()->route($request->page)->with(['success' => 'Your Address is updated']);

    }
}
