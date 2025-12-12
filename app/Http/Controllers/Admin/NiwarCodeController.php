<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\NiwarCode;
use Illuminate\Http\Request;

class NiwarCodeController extends Controller
{
    public function index()
    {
        $items = NiwarCode::all();
        return view('admin.belt.niwar_codes.index', compact('items'));
    }

    public function create()
    {
        return view('admin.belt.niwar_codes.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'type' => 'required',
            'code' => 'required|integer|unique:niwar_codes',
            'rate' => 'nullable|numeric'
        ]);

        NiwarCode::create($request->all());
        return redirect()->route('admin.niwar.list')->with('success', 'Added Successfully');
    }

    public function edit($id)
    {
        $item = NiwarCode::findOrFail($id);
        return view('admin.belt.niwar_codes.edit', compact('item'));
    }

    public function update(Request $request, $id)
    {
        $item = NiwarCode::findOrFail($id);
        $request->validate([
            'type' => 'required',
            'code' => 'required|integer|unique:niwar_codes,code,' . $item->id,
            'rate' => 'nullable|numeric'
        ]);
        $item->update($request->all());

        return redirect()->route('admin.niwar.list')->with('success', 'Updated Successfully');
    }

    public function destroy($id)
    {
        NiwarCode::destroy($id);
        return redirect()->route('admin.niwar.list')->with('success', 'Deleted Successfully');
    }

    public function getNiwarData($id)
    {
        $n = NiwarCode::find($id);

        return response()->json([
            'rate' => $n->rate
        ]);
    }

}
