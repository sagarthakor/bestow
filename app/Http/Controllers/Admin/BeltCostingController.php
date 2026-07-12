<?php

namespace App\Http\Controllers\Admin;

use App\BeltCosting;
use App\BukkalCode;
use App\Http\Controllers\Controller;
use App\NiwarCode;
use Illuminate\Http\Request;

class BeltCostingController extends Controller
{
    public function index()
    {
        $data = BeltCosting::with(['bukkal', 'niwar'])->get();
        return view('admin.belt.belt_costings.index', compact('data'));
    }

    public function create()
    {
        $bukkal = BukkalCode::all();
        $niwar = NiwarCode::all();
        return view('admin.belt.belt_costings.create', compact('bukkal','niwar'));
    }

    public function store(Request $request)
    {

        BeltCosting::create([
            'bukkal_id' => $request->bukkal_id,
            'bukkal_rate' => $request->bukkal_rate,
            'bukkal_code' => $request->bukkal_code,
            'niwar_id'  => $request->niwar_id,
            'niwar_rate'  => $request->niwar_rate,
            'miter'     => $request->miter,
            'kadi_qty'  => $request->kadi_qty,
            'kadi_rate' => $request->kadi_rate,
            'size_label' => $request->size_label,
            'panni_packing' => $request->panni_packaging_rate,
            'total_cost' => $request->total_costing,
        ]);

        return redirect()->route('admin.belt.list')->with('success', 'Saved Successfully');
    }

    public function edit($id)
    {
        $item = BeltCosting::findOrFail($id);
        $bukkal = BukkalCode::all();
        $niwar = NiwarCode::all();

        return view('admin.belt.belt_costings.edit', compact('item','bukkal','niwar'));
    }

    public function update(Request $request, $id)
    {
        $item = BeltCosting::findOrFail($id);

        $item->update([
            'bukkal_id' => $request->bukkal_id,
            'bukkal_rate' => $request->bukkal_rate,
            'bukkal_code' => $request->bukkal_code,
            'niwar_id'  => $request->niwar_id,
            'niwar_rate'  => $request->niwar_rate,
            'miter'     => $request->miter,
            'kadi_qty'  => $request->kadi_qty,
            'kadi_rate' => $request->kadi_rate,
            'size_label' => $request->size_label,
            'panni_packing' => $request->panni_packaging_rate,
            'total_cost' => $request->total_costing,
        ]);

        return redirect()->route('admin.belt.list')->with('success', 'Updated Successfully');
    }

    public function destroy($id)
    {
        BeltCosting::destroy($id);
        return redirect()->route('admin.belt.list')->with('success', 'Deleted Successfully');
    }

}
