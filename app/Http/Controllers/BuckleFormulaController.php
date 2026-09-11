<?php

namespace App\Http\Controllers;

use App\BeltCosting;
use App\BuckleFormulaMst;
use App\BuckleFormulaMstItem;
use App\category;
use App\NiwarCode;
use App\product;
use Illuminate\Http\Request;

/**
 * Belt formula is fitting material only - bukkal, kadi, slider, panni per belt.
 * Dhaga moved to the roll formula on the Niwar Code screen (it is per meter of
 * niwar, not per belt), and the niwar a belt eats is derived from the size chart
 * at cutting time rather than stored per formula.
 */
class BuckleFormulaController extends Controller
{
    function buckle_formula_list(Request $request)
    {
        $query = BuckleFormulaMst::with('product_item:id,product_name,value1,value2');

        // Same fix as FormulaController::formula_list - Eloquence's ->search()
        // scored relevance rather than requiring every word, so a multi-word
        // query matched almost everything. Word-by-word AND instead.
        $words = preg_split('/\s+/', trim((string) $request->search), -1, PREG_SPLIT_NO_EMPTY) ?: [];
        foreach ($words as $word) {
            $like = '%' . str_replace(['%', '_'], ['\%', '\_'], $word) . '%';
            $query->where(function ($q) use ($like) {
                $q->where('size', 'like', $like)
                    ->orWhereHas('product_item', function ($p) use ($like) {
                        $p->where('product_name', 'like', $like)
                            ->orWhere('value1', 'like', $like)
                            ->orWhere('value2', 'like', $like);
                    });
            });
        }

        $data = $query->paginate(session('records_per_page', 30))->appends($request->all());

        return view('admin.buckle_formula.list', compact('data'));
    }

    function buckle_formula_add(Request $request)
    {
        $beltCategoryId = category::where('category_name', 'Belt')->value('id');
        $product = product::orderBy('product_name', 'asc')->where('status', 'product')->where('category', $beltCategoryId)->get();
        $rawmaterial = product::with('uomName')->orderBy('product_name', 'asc')->where('status', 'raw material')->get();
        $beltCosting = BeltCosting::with(['bukkal', 'niwar'])->get();
        $niwarTypes = NiwarCode::with('sizeChart')->get();
        $rollFormulas = $this->rollFormulaOptions();

        return view('admin.buckle_formula.create', compact('product', 'rawmaterial', 'beltCosting', 'niwarTypes', 'rollFormulas'));
    }

    function buckle_formula_store(Request $request)
    {
        $request->validate([
            'product' => 'required|unique:buckle_formula_mst,product',
            'belt_costing_id' => 'required|exists:belt_costings,id',
            'roll_formula_id' => 'nullable|exists:roll_formula_mst,id',
            'size' => 'required',
            'material' => 'required|array|min:1',
            'qty' => 'required|array|min:1',
        ]);

        $formula = new BuckleFormulaMst();
        $formula->product = $request->product;
        $formula->belt_costing_id = $request->belt_costing_id;
        $formula->roll_formula_id = $request->roll_formula_id ?: null;
        $formula->size = $request->size;
        $formula->nos = $request->nos ?: 1;
        $formula->save();

        $this->saveItems($formula, $request);

        return redirect()->route('admin.production.buckle_formula_list')->with('message', 'Belt formula created successfully');
    }

    function buckle_formula_edit(Request $request)
    {
        $data = BuckleFormulaMst::with('product_item:id,product_name,value1,value2')->find($request->id);

        $data_item = BuckleFormulaMstItem::select('buckle_formula_mst_item.*', 'product.product_name', 'product.value1', 'product.value2', 'uom.uom_name')
            ->leftJoin('product', 'product.id', 'buckle_formula_mst_item.material')
            ->leftJoin('uom', 'uom.id', 'product.uom')
            ->where('buckle_formula_mst_item.formula_id', $data->id)
            ->orderBy('buckle_formula_mst_item.id', 'asc')
            ->get();

        $rawmaterial = product::with('uomName')->orderBy('product_name', 'asc')->where('status', 'raw material')->get();
        $beltCosting = BeltCosting::with(['bukkal', 'niwar'])->get();
        $niwarTypes = NiwarCode::with('sizeChart')->get();
        $rollFormulas = $this->rollFormulaOptions();

        return view('admin.buckle_formula.edit', compact('data', 'data_item', 'rawmaterial', 'beltCosting', 'niwarTypes', 'rollFormulas'));
    }

    function buckle_formula_update(Request $request)
    {
        $request->validate([
            'belt_costing_id' => 'required|exists:belt_costings,id',
            'roll_formula_id' => 'nullable|exists:roll_formula_mst,id',
            'size' => 'required',
            'material' => 'required|array|min:1',
            'qty' => 'required|array|min:1',
        ]);

        $formula = BuckleFormulaMst::find($request->id);
        $formula->belt_costing_id = $request->belt_costing_id;
        $formula->roll_formula_id = $request->roll_formula_id ?: null;
        $formula->size = $request->size;
        $formula->nos = $request->nos ?: 1;
        $formula->save();

        BuckleFormulaMstItem::where('formula_id', $formula->id)->delete();
        $this->saveItems($formula, $request);

        return redirect()->route('admin.production.buckle_formula_list')->with('message', 'Belt formula updated successfully');
    }

    /**
     * Semi products a belt can be cut from, each carrying its niwar code so the
     * form can show only the ones belonging to the chosen belt costing.
     */
    private function rollFormulaOptions()
    {
        return \App\RollFormulaMst::with('product_item:id,product_name')
            ->get()
            ->map(fn ($f) => (object) [
                'id' => $f->id,
                'niwar_code_id' => $f->niwar_code_id,
                'name' => $f->product_item->product_name ?? ('Product #' . $f->product),
            ])
            ->sortBy('name')
            ->values();
    }

    private function saveItems(BuckleFormulaMst $formula, Request $request)
    {
        foreach ($request->material as $i => $materialId) {
            if ($materialId == '' || !isset($request->qty[$i]) || $request->qty[$i] == '') {
                continue;
            }
            $item = new BuckleFormulaMstItem();
            $item->formula_id = $formula->id;
            $item->material = $materialId;
            $item->qty = $request->qty[$i];
            $item->save();
        }
    }

    function buckle_formula_delete(Request $request)
    {
        BuckleFormulaMstItem::where('formula_id', $request->id)->delete();
        BuckleFormulaMst::find($request->id)->delete();

        return redirect()->route('admin.production.buckle_formula_list')->with('message', 'Belt formula deleted');
    }
}
