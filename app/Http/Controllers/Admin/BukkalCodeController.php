<?php

namespace App\Http\Controllers\Admin;

use App\BukkalCode;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BukkalCodeController extends Controller
{
    public function index()
    {
        $items = BukkalCode::all();
        return view('admin.belt.bukkal_codes.index', compact('items'));
    }

    public function create()
    {
        return view('admin.belt.bukkal_codes.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'type' => 'required',
            'code' => 'required|integer|unique:bukkal_codes',
            'rate' => 'nullable|numeric'
        ]);

        BukkalCode::create($request->all());
        return redirect()->route('admin.bukkal.list')->with('success', 'Added Successfully');
    }

    public function edit($id)
    {
        $item = BukkalCode::findOrFail($id);
        return view('admin.belt.bukkal_codes.edit', compact('item'));
    }

    public function update(Request $request, $id)
    {
        $item = BukkalCode::findOrFail($id);

        $request->validate([
            'type' => 'required',
            'code' => 'required|integer|unique:bukkal_codes,code,' . $id,
            'rate' => 'nullable|numeric'
        ]);

        $item->update($request->all());

        return redirect()->route('admin.bukkal.list')->with('success', 'Updated Successfully');
    }

    public function destroy($id)
    {
        BukkalCode::destroy($id);
        return redirect()->route('admin.bukkal.list')->with('success', 'Deleted');
    }

    public function getBukkalData($id)
    {
        $b = BukkalCode::find($id);

        return response()->json([
            'code' => $b->code,
            'rate' => $b->rate
        ]);
    }

}
