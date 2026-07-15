<?php

namespace App\Http\Controllers;

use App\BuckleFormulaMst;
use App\BuckleFormulaMstItem;
use App\product;
use Illuminate\Http\Request;

class BuckleFormulaController extends Controller
{
    function buckle_formula_list(Request $request)
    {
        $data = BuckleFormulaMst::with('product_item:id,product_name')
            ->search($request->search, ['product_item.product_name', 'size'])
            ->paginate(session('records_per_page', 30));

        return view('admin.buckle_formula.list', compact('data'));
    }

    function buckle_formula_add(Request $request)
    {
        $product = product::orderBy('product_name', 'asc')->where('status', 'product')->get();
        $rawmaterial = product::orderBy('product_name', 'asc')->where('status', 'raw material')->get();

        return view('admin.buckle_formula.create', compact('product', 'rawmaterial'));
    }

    function buckle_formula_store(Request $request)
    {
        $request->validate([
            'product' => 'required|unique:buckle_formula_mst,product',
            'size' => 'required',
            'material' => 'required|array|min:1',
            'qty' => 'required|array|min:1',
        ]);

        $formula = new BuckleFormulaMst();
        $formula->product = $request->product;
        $formula->size = $request->size;
        $formula->nos = $request->nos ?: 1;
        $formula->save();

        foreach ($request->material as $i => $materialId) {
            if ($materialId == '' || $request->qty[$i] == '') {
                continue;
            }
            $item = new BuckleFormulaMstItem();
            $item->formula_id = $formula->id;
            $item->material = $materialId;
            $item->qty = $request->qty[$i];
            $item->save();
        }

        return redirect()->route('admin.production.buckle_formula_list')->with('message', 'Buckle formula created successfully');
    }

    function buckle_formula_edit(Request $request)
    {
        $data = BuckleFormulaMst::find($request->id);

        $data_item = BuckleFormulaMstItem::select('buckle_formula_mst_item.*', 'product.product_name', 'uom.uom_name')
            ->leftJoin('product', 'product.id', 'buckle_formula_mst_item.material')
            ->leftJoin('uom', 'uom.id', 'product.uom')
            ->where('buckle_formula_mst_item.formula_id', $data->id)
            ->orderBy('buckle_formula_mst_item.id', 'asc')
            ->get();

        $rawmaterial = product::orderBy('product_name', 'asc')->where('status', 'raw material')->get();

        return view('admin.buckle_formula.edit', compact('data', 'data_item', 'rawmaterial'));
    }

    function buckle_formula_update(Request $request)
    {
        $request->validate([
            'size' => 'required',
            'material' => 'required|array|min:1',
            'qty' => 'required|array|min:1',
        ]);

        $formula = BuckleFormulaMst::find($request->id);
        $formula->size = $request->size;
        $formula->nos = $request->nos ?: 1;
        $formula->save();

        BuckleFormulaMstItem::where('formula_id', $formula->id)->delete();

        foreach ($request->material as $i => $materialId) {
            if ($materialId == '' || $request->qty[$i] == '') {
                continue;
            }
            $item = new BuckleFormulaMstItem();
            $item->formula_id = $formula->id;
            $item->material = $materialId;
            $item->qty = $request->qty[$i];
            $item->save();
        }

        return redirect()->route('admin.production.buckle_formula_list')->with('message', 'Buckle formula updated successfully');
    }

    function buckle_formula_delete(Request $request)
    {
        BuckleFormulaMstItem::where('formula_id', $request->id)->delete();
        BuckleFormulaMst::find($request->id)->delete();

        return redirect()->route('admin.production.buckle_formula_list')->with('message', 'Buckle formula deleted');
    }
}
