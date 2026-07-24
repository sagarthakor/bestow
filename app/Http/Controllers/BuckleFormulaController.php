<?php

namespace App\Http\Controllers;

use App\BeltCosting;
use App\BuckleFormulaMst;
use App\BuckleFormulaMstItem;
use App\category;
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
        $beltCategoryId = category::where('category_name', 'Belt')->value('id');
        $product = product::orderBy('product_name', 'asc')->where('status', 'product')->where('category', $beltCategoryId)->get();
        $rawmaterial = product::with('uomName')->orderBy('product_name', 'asc')->where('status', 'raw material')->get();
        $beltCosting = BeltCosting::with(['bukkal', 'niwar'])->get();

        return view('admin.buckle_formula.create', compact('product', 'rawmaterial', 'beltCosting'));
    }

    function buckle_formula_store(Request $request)
    {
        $request->validate([
            'product' => 'required|unique:buckle_formula_mst,product',
            'belt_costing_id' => 'required|exists:belt_costings,id',
            'size' => 'required',
            'material' => 'required|array|min:1',
            'qty' => 'required|array|min:1',
        ]);

        $formula = new BuckleFormulaMst();
        $formula->product = $request->product;
        $formula->belt_costing_id = $request->belt_costing_id;
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

        $rawmaterial = product::with('uomName')->orderBy('product_name', 'asc')->where('status', 'raw material')->get();
        $beltCosting = BeltCosting::with(['bukkal', 'niwar'])->get();

        return view('admin.buckle_formula.edit', compact('data', 'data_item', 'rawmaterial', 'beltCosting'));
    }

    function buckle_formula_update(Request $request)
    {
        $request->validate([
            'belt_costing_id' => 'required|exists:belt_costings,id',
            'size' => 'required',
            'material' => 'required|array|min:1',
            'qty' => 'required|array|min:1',
        ]);

        $formula = BuckleFormulaMst::find($request->id);
        $formula->belt_costing_id = $request->belt_costing_id;
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
