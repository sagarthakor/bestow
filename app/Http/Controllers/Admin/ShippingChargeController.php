<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\ShippingCharge;
use App\state;
use Illuminate\Http\Request;

class ShippingChargeController extends Controller
{
    public function index()
    {
        $states = state::all();
        $charges = ShippingCharge::with('state')->get();

        // अगर edit button क्लिक किया गया हो
        $editData = null;
        if (request()->has('edit') && request('edit') != '') {
            $editData = ShippingCharge::find(request('edit'));
        }

        return view('admin.shipping.index', compact('states', 'charges', 'editData'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'state_id'     => 'required|integer',
            'min_amount'    => 'required|numeric',
            'max_amount'    => 'nullable|numeric',
            'shipping_fee'=> 'required|numeric',
        ]);

        ShippingCharge::create($request->all());
        return redirect()->back()->with('message', 'Shipping charge added successfully!');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'state_id'     => 'required|integer',
            'min_amount'    => 'required|numeric',
            'max_amount'    => 'nullable|numeric',
            'shipping_fee'=> 'required|numeric',
        ]);

        ShippingCharge::findOrFail($id)->update($request->all());
        return redirect()->route('admin.shipping.index')->with('message', 'Updated successfully!');
    }

    public function destroy($id)
    {
        ShippingCharge::destroy($id);
        return redirect()->back()->with('message', 'Shipping charge deleted!');
    }
}

