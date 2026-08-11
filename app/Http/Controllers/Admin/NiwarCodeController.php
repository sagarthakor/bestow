<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\NiwarCode;
use App\NiwarSizeChart;
use App\NiwarTypeMaterial;
use App\product;
use Illuminate\Http\Request;

class NiwarCodeController extends Controller
{
    public function index()
    {
        // The list now shows whether each code is actually usable - its rate rows
        // and its size chart - so both are loaded up front rather than per row.
        $items = NiwarCode::with(['materials', 'sizeChart'])->orderBy('type')->get();
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

    public function details($id)
    {
        $niwar = NiwarCode::with(['materials.material_item:id,product_name', 'sizeChart'])->findOrFail($id);
        $rawmaterial = product::orderBy('product_name', 'asc')->where('status', 'raw material')->get();

        return view('admin.belt.niwar_codes.details', compact('niwar', 'rawmaterial'));
    }

    public function saveDetails(Request $request, $id)
    {
        $niwar = NiwarCode::findOrFail($id);

        $request->validate([
            'inch_per_meter' => 'nullable|numeric|min:0.01',
        ]);

        $niwar->inch_per_meter = $request->inch_per_meter ?: 39.37;
        $niwar->save();

        $this->saveMaterialRows($niwar, $request);
        $this->saveSizeChart($niwar, $request);

        return redirect()->route('admin.niwar.details', $niwar->id)->with('success', 'Niwar type details updated');
    }

    /**
     * The raw-material categories a meter of this niwar weighs - Mono, Roto and
     * so on - with the gm per meter each must come to. Which actual raw materials
     * fill a category is chosen per semi product, in its roll formula.
     */
    private function saveMaterialRows(NiwarCode $niwar, Request $request)
    {
        // Every roll formula points its rows at these category rows by id, so
        // wiping and re-creating them - even with identical numbers - would
        // orphan every formula built on this niwar code. A category is identified
        // by its material, so rows are matched on that and kept in place; only a
        // category genuinely removed from the screen is deleted.
        $existing = NiwarTypeMaterial::where('niwar_code_id', $niwar->id)->get()->keyBy('material');
        $keptIds = [];

        foreach ($request->material ?? [] as $i => $materialId) {
            $gm = $request->gm_per_meter[$i] ?? '';
            if ($materialId == '' || $gm === '') {
                continue;
            }

            $attributes = [
                'gm_per_meter' => (float) $gm,
                'is_group' => !empty($request->is_group[$i]),
            ];

            $row = $existing->get($materialId);

            if ($row) {
                $row->update($attributes);
            } else {
                $row = NiwarTypeMaterial::create($attributes + [
                    'niwar_code_id' => $niwar->id,
                    'material' => $materialId,
                ]);
            }

            $keptIds[] = $row->id;
        }

        NiwarTypeMaterial::where('niwar_code_id', $niwar->id)->whereNotIn('id', $keptIds ?: [0])->delete();
    }

    private function saveSizeChart(NiwarCode $niwar, Request $request)
    {
        NiwarSizeChart::where('niwar_code_id', $niwar->id)->delete();

        foreach ($request->pp_size ?? [] as $i => $ppSize) {
            if ($ppSize == '' || !isset($request->required_inch[$i]) || $request->required_inch[$i] == '') {
                continue;
            }
            NiwarSizeChart::create([
                'niwar_code_id' => $niwar->id,
                'pp_size' => trim($ppSize),
                'required_inch' => $request->required_inch[$i],
            ]);
        }
    }
}
