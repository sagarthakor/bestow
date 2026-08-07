<?php

namespace App\Http\Controllers;

use App\BeltCosting;
use App\BuckleFormulaMst;
use App\BuckleFormulaMstItem;
use App\category;
use App\NiwarCode;
use App\NiwarTypeMaterial;
use App\product;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class BuckleFormulaController extends Controller
{
    function buckle_formula_list(Request $request)
    {
        $data = BuckleFormulaMst::with('product_item:id,product_name,value1,value2')
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
        $niwarTypes = NiwarCode::with(['materials', 'sizeChart'])->get();

        return view('admin.buckle_formula.create', compact('product', 'rawmaterial', 'beltCosting', 'niwarTypes'));
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

        $this->validateNiwarGroupTotals($request);

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
            $item->is_auto = !empty($request->is_auto[$i]);
            $item->niwar_type_material_id = !empty($request->niwar_group[$i]) ? $request->niwar_group[$i] : null;
            $item->save();
        }

        return redirect()->route('admin.production.buckle_formula_list')->with('message', 'Belt formula created successfully');
    }

    function buckle_formula_edit(Request $request)
    {
        $data = BuckleFormulaMst::find($request->id);

        $data_item = BuckleFormulaMstItem::select('buckle_formula_mst_item.*', 'product.product_name', 'product.value1', 'product.value2', 'uom.uom_name')
            ->leftJoin('product', 'product.id', 'buckle_formula_mst_item.material')
            ->leftJoin('uom', 'uom.id', 'product.uom')
            ->where('buckle_formula_mst_item.formula_id', $data->id)
            ->where('buckle_formula_mst_item.is_auto', 0)
            ->orderBy('buckle_formula_mst_item.id', 'asc')
            ->get();

        $rawmaterial = product::with('uomName')->orderBy('product_name', 'asc')->where('status', 'raw material')->get();
        $beltCosting = BeltCosting::with(['bukkal', 'niwar'])->get();
        $niwarTypes = NiwarCode::with(['materials', 'sizeChart'])->get();

        $groupItems = [];
        foreach ($data_item as $item) {
            if ($item->niwar_type_material_id) {
                $groupItems[$item->niwar_type_material_id][] = ['material' => $item->material, 'qty' => $item->qty];
            }
        }

        return view('admin.buckle_formula.edit', compact('data', 'data_item', 'rawmaterial', 'beltCosting', 'niwarTypes', 'groupItems'));
    }

    function buckle_formula_update(Request $request)
    {
        $request->validate([
            'belt_costing_id' => 'required|exists:belt_costings,id',
            'size' => 'required',
            'material' => 'required|array|min:1',
            'qty' => 'required|array|min:1',
        ]);

        $this->validateNiwarGroupTotals($request);

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
            $item->is_auto = !empty($request->is_auto[$i]);
            $item->niwar_type_material_id = !empty($request->niwar_group[$i]) ? $request->niwar_group[$i] : null;
            $item->save();
        }

        return redirect()->route('admin.production.buckle_formula_list')->with('message', 'Belt formula updated successfully');
    }

    /**
     * Ensure raw materials belonging to a "group" Niwar material (e.g. 300/ROTO, which is
     * fulfilled by a mix of different dhaga raw materials rather than a single one) sum up to
     * exactly the auto-calculated target (meter x gm_per_meter) for the submitted size.
     */
    private function validateNiwarGroupTotals(Request $request)
    {
        $beltCosting = BeltCosting::find($request->belt_costing_id);
        if (!$beltCosting || !$beltCosting->niwar_id) {
            return;
        }

        $niwar = NiwarCode::with('sizeChart')->find($beltCosting->niwar_id);
        if (!$niwar) {
            return;
        }

        $sizeChart = $niwar->sizeChart->firstWhere('pp_size', trim((string) $request->size));
        if (!$sizeChart) {
            return;
        }

        $inchPerMeter = $niwar->inch_per_meter ?: 39.37;
        $meter = $sizeChart->required_inch / $inchPerMeter;

        $groupMaterials = NiwarTypeMaterial::with('material_item:id,product_name')
            ->where('niwar_code_id', $niwar->id)
            ->where('is_group', 1)
            ->get();

        if ($groupMaterials->isEmpty()) {
            return;
        }

        $sums = [];
        foreach ($request->niwar_group ?? [] as $i => $groupId) {
            if (empty($groupId)) {
                continue;
            }
            $sums[$groupId] = ($sums[$groupId] ?? 0) + (float) ($request->qty[$i] ?? 0);
        }

        $errors = [];
        foreach ($groupMaterials as $gm) {
            $target = round($meter * $gm->gm_per_meter, 2);
            $actual = round($sums[$gm->id] ?? 0, 2);
            if (abs($target - $actual) > 0.01) {
                $name = $gm->material_item->product_name ?? ('Material #' . $gm->material);
                $errors[] = "{$name} group total qty is {$actual}, but it must exactly equal {$target} (per Niwar Code material details for size {$request->size}).";
            }
        }

        if ($errors) {
            throw ValidationException::withMessages(['niwar_group' => $errors]);
        }
    }

    function buckle_formula_delete(Request $request)
    {
        BuckleFormulaMstItem::where('formula_id', $request->id)->delete();
        BuckleFormulaMst::find($request->id)->delete();

        return redirect()->route('admin.production.buckle_formula_list')->with('message', 'Belt formula deleted');
    }
}
