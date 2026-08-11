<?php

namespace App\Http\Controllers\Admin;

use App\BeltCosting;
use App\BukkalCode;
use App\Http\Controllers\Controller;
use App\NiwarCode;
use App\product;
use Illuminate\Http\Request;

class BeltCostingController extends Controller
{
    public function index()
    {
        // fittings is shown per row, so it is loaded with the list.
        $data = BeltCosting::with(['bukkal', 'niwar', 'fittings'])->get();
        return view('admin.belt.belt_costings.index', compact('data'));
    }

    public function create()
    {
        $bukkal = BukkalCode::all();
        $niwar = NiwarCode::all();
        $fittingProducts = $this->fittingProducts();
        return view('admin.belt.belt_costings.create', compact('bukkal','niwar','fittingProducts'));
    }

    public function store(Request $request)
    {

        $costing = BeltCosting::create([
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
            // The fitting list itself is saved separately - see saveFittings().
        ]);

        $this->saveFittings($costing, $request);

        return redirect()->route('admin.belt.list')->with('success', 'Saved Successfully');
    }

    public function edit($id)
    {
        $item = BeltCosting::with('fittings')->findOrFail($id);
        $bukkal = BukkalCode::all();
        $niwar = NiwarCode::all();

        $fittingProducts = $this->fittingProducts();

        return view('admin.belt.belt_costings.edit', compact('item','bukkal','niwar','fittingProducts'));
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
            // The fitting list itself is saved separately - see saveFittings().
        ]);

        $this->saveFittings($item, $request);

        return redirect()->route('admin.belt.list')->with('success', 'Updated Successfully');
    }

    /**
     * Products that can stand behind a fitting line. Raw material and semi
     * finished both appear because a bukkal is bought in while a kadi may be
     * made in-house.
     */
    /**
     * Replaces the whole fitting list. Rows arrive parallel by index; a row whose
     * product was cleared is dropped, which is how the screen removes one.
     */
    private function saveFittings(BeltCosting $costing, Request $request)
    {
        \App\BeltCostingFitting::where('belt_costing_id', $costing->id)->delete();

        foreach ($request->fitting_product ?? [] as $i => $productId) {
            $qty = (float) ($request->fitting_qty[$i] ?? 0);

            if (empty($productId) || $qty <= 0) {
                continue;
            }

            \App\BeltCostingFitting::create([
                'belt_costing_id' => $costing->id,
                'label' => trim((string) ($request->fitting_label[$i] ?? '')) ?: 'Fitting',
                'product' => $productId,
                'qty' => $qty,
            ]);
        }
    }

    private function fittingProducts()
    {
        return product::whereIn('status', ['raw material', 'semi finished'])
            ->orderBy('product_name')
            ->get(['id', 'product_name', 'value1', 'value2']);
    }

    public function destroy($id)
    {
        BeltCosting::destroy($id);
        return redirect()->route('admin.belt.list')->with('success', 'Deleted Successfully');
    }

}
