<?php

namespace App\Http\Controllers;

use App\BeltProduction;
use App\BeltProductionMaterial;
use App\BuckleFormulaMst;
use App\BuckleFormulaMstItem;
use App\customers;
use App\product;
use App\stock_book;
use App\stock_status;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Session;

class BeltProductionController extends Controller
{
    /**
     * Same KG-vs-piece/length unit convention as ProductionController's
     * production - kept local since belt raw material is PCS/Meter today,
     * but this keeps the check correct if a KG-tracked belt material is
     * ever added later.
     */
    private function materialConversionFactor($materialId)
    {
        $uomName = DB::table('product')
            ->join('uom', 'uom.id', 'product.uom')
            ->where('product.id', $materialId)
            ->value('uom.uom_name');

        return strtoupper($uomName) === 'KG' ? 1000 : 1;
    }

    /**
     * Compute required qty per raw material (formula qty x planned qty) and
     * compare against current stock. Shared by the AJAX preview and the
     * server-side check in store() so the two can never disagree.
     */
    private function computeMaterialRequirement($beltProductId, $plannedQty)
    {
        $formula = BuckleFormulaMst::where('product', $beltProductId)->first();

        if (empty($formula)) {
            return ['formula' => null, 'lines' => collect(), 'sufficient' => false];
        }

        $items = BuckleFormulaMstItem::select('buckle_formula_mst_item.*', 'product.product_name', 'product.uom as material_uom', 'uom.uom_name', 'stock_status.qty as stock_qty')
            ->leftJoin('product', 'product.id', 'buckle_formula_mst_item.material')
            ->leftJoin('uom', 'uom.id', 'product.uom')
            ->leftJoin('stock_status', 'stock_status.product', 'buckle_formula_mst_item.material')
            ->where('buckle_formula_mst_item.formula_id', $formula->id)
            ->get();

        $sufficient = true;
        $lines = $items->map(function ($item) use ($plannedQty, &$sufficient) {
            $factor = $this->materialConversionFactor($item->material);
            $requiredQty = round($item->qty * $plannedQty, 2);
            $availableStock = ($item->stock_qty ?? 0) * $factor;
            $shortBy = round($requiredQty - $availableStock, 2);
            $isShort = $shortBy > 0;
            if ($isShort) {
                $sufficient = false;
            }

            // Formula qty (and the stock converted via $factor) are both in
            // grams for KG-tracked materials - the KG unit label only makes
            // sense for the raw, un-converted stock figure, not these.
            $displayUnit = $factor > 1 ? 'g' : $item->uom_name;

            return (object) [
                'material' => $item->material,
                'product_name' => $item->product_name,
                'uom_name' => $displayUnit,
                'required_qty' => $requiredQty,
                'available_stock' => $availableStock,
                'short_by' => max($shortBy, 0),
                'is_short' => $isShort,
            ];
        });

        return ['formula' => $formula, 'lines' => $lines, 'sufficient' => $sufficient];
    }

    function belt_production_list(Request $request)
    {
        $data = BeltProduction::with(['belt_item:id,product_name', 'customer_item:id,customer_name'])
            ->orderBy('id', 'desc')
            ->paginate(20);

        return view('admin.belt_production.list', compact('data'));
    }

    function belt_production_add(Request $request)
    {
        $beltProductIds = BuckleFormulaMst::pluck('product');
        $belts = product::whereIn('id', $beltProductIds)->orderBy('product_name', 'asc')->get();
        $customer = customers::orderBy('customer_name', 'asc')->get();

        return view('admin.belt_production.create', compact('belts', 'customer'));
    }

    function belt_production_wastage_material(Request $request)
    {
        $belt = BeltProduction::find($request->id);

        if (empty($belt) || $belt->status != 'Y' || empty($belt->total_wastage_nos) || $belt->total_wastage_nos <= 0) {
            return response()->json(['html' => '<div class="alert alert-info">No wastage recorded for this batch.</div>']);
        }

        $result = $this->computeMaterialRequirement($belt->belt_product, $belt->total_wastage_nos);

        if (empty($result['formula'])) {
            return response()->json(['html' => '<div class="alert alert-danger">No formula defined for this belt in Buckle Formula Master.</div>']);
        }

        $html = '<p>Raw material used up by the ' . $belt->total_wastage_nos . ' wasted unit(s):</p>';
        $html .= '<table class="table table-bordered"><tr><th>Raw Material</th><th>Qty Wasted</th></tr>';
        foreach ($result['lines'] as $line) {
            $html .= "<tr><td>{$line->product_name}</td><td>{$line->required_qty} {$line->uom_name}</td></tr>";
        }
        $html .= '</table>';

        return response()->json(['html' => $html]);
    }

    function belt_production_check_material(Request $request)
    {
        $qty = (float) $request->qty;

        if (empty($request->belt_product) || $qty <= 0) {
            return response()->json(['html' => '', 'sufficient' => false]);
        }

        $result = $this->computeMaterialRequirement($request->belt_product, $qty);

        if (empty($result['formula'])) {
            return response()->json(['html' => '<div class="alert alert-danger">No formula defined for this belt in Buckle Formula Master.</div>', 'sufficient' => false]);
        }

        $html = '<table class="table table-bordered"><tr><th>Raw Material</th><th>Required Qty</th><th>Available Stock</th><th>Short By</th></tr>';
        foreach ($result['lines'] as $line) {
            $rowStyle = $line->is_short ? "style='background:#fdeaea'" : '';
            $html .= "<tr $rowStyle>
                <td>{$line->product_name}</td>
                <td>{$line->required_qty} {$line->uom_name}</td>
                <td>{$line->available_stock} {$line->uom_name}</td>
                <td>" . ($line->is_short ? "<b style='color:#c0392b'>{$line->short_by} {$line->uom_name} short</b>" : 'OK') . "</td>
            </tr>";
        }
        $html .= '</table>';

        if (!$result['sufficient']) {
            $html .= '<div class="alert alert-danger">Insufficient raw material stock - this batch cannot be created until stock is available.</div>';
        }

        return response()->json(['html' => $html, 'sufficient' => $result['sufficient']]);
    }

    function belt_production_store(Request $request)
    {
        $request->validate([
            'belt_product' => 'required',
            'customer' => 'required',
            'planned_qty' => 'required|numeric|min:1',
        ]);

        $result = $this->computeMaterialRequirement($request->belt_product, $request->planned_qty);

        if (empty($result['formula'])) {
            return back()->withInput()->with('error', 'No formula defined for this belt in Buckle Formula Master.');
        }

        // Server-side is the real gate - the AJAX preview is only a convenience,
        // so re-check here regardless of what the client submitted.
        if (!$result['sufficient']) {
            $shortages = $result['lines']->where('is_short', true)
                ->map(fn ($l) => "{$l->product_name} (short by {$l->short_by} {$l->uom_name})")
                ->implode(', ');

            return back()->withInput()->with('error', 'Insufficient raw material stock, production not created. Short: ' . $shortages);
        }

        date_default_timezone_set('Asia/Kolkata');

        $lastBatch = BeltProduction::max('id');
        $year = date('y');
        $nextYear = $year + 1;
        $batchNo = 'BELT-' . str_pad(($lastBatch ?? 0) + 1, 4, '0', STR_PAD_LEFT) . '_' . $year . '-' . $nextYear;

        $belt = new BeltProduction();
        $belt->batch_no = $batchNo;
        $belt->belt_product = $request->belt_product;
        $belt->customer = $request->customer;
        $belt->planned_qty = $request->planned_qty;
        $belt->status = 'N';
        $belt->timestamp = date('d-m-Y h:i:s a');
        $belt->user_id = Session::get('user_id');
        $belt->save();

        foreach ($result['lines'] as $line) {
            $material = new BeltProductionMaterial();
            $material->belt_production_id = $belt->id;
            $material->material = $line->material;
            $material->required_qty = $line->required_qty;
            $material->avalible_stock = $line->available_stock;
            $material->timestamp = date('d-m-Y h:i:s a');
            $material->user_id = Session::get('user_id');
            $material->save();

            $factor = $this->materialConversionFactor($line->material);
            $stock = stock_status::where('product', $line->material)->first();
            $deductQty = $line->required_qty / $factor;
            $newQty = ($stock->qty ?? 0) - $deductQty;
            $stock->qty = $newQty;
            $stock->save();

            $book = new stock_book();
            $book->product = $line->material;
            $book->inward_date = date('Y-m-d');
            $book->outward_qty = $deductQty;
            $book->remaining_qty = $newQty;
            $book->particular = 'Used in Belt Production ' . $batchNo;
            $book->created_time = date('d-m-Y h:i:s a');
            $book->user_id = Session::get('user_id');
            $book->save();
        }

        return redirect()->route('admin.belt_production.list')->with('message', 'Belt production batch created: ' . $batchNo);
    }

    function belt_production_complete(Request $request)
    {
        $request->validate([
            'id' => 'required',
            'total_production' => 'required|numeric|min:0',
        ]);

        $belt = BeltProduction::find($request->id);
        $belt->total_production = $request->total_production;
        $belt->total_wastage_nos = max($belt->planned_qty - $request->total_production, 0);
        $belt->status = 'Y';
        $belt->save();

        $checkproduct = stock_status::where('product', $belt->belt_product)->first();

        if (empty($checkproduct)) {
            $status = new stock_status();
            $status->product = $belt->belt_product;
            $status->qty = $request->total_production;
            $status->particular = 'Inward From Belt Production Batch : ' . $belt->batch_no;
            $status->inward_date = date('Y-m-d');
            $status->user_id = Session::get('user_id');
            $status->created_time = date('d-m-Y h:i:s a');
            $status->save();
        } else {
            $checkproduct->qty = $checkproduct->qty + $request->total_production;
            $checkproduct->inward_date = date('Y-m-d');
            $checkproduct->particular = 'Inward From Belt Production Batch : ' . $belt->batch_no;
            $checkproduct->created_time = date('d-m-Y h:i:s a');
            $checkproduct->save();
        }

        $book = new stock_book();
        $book->product = $belt->belt_product;
        $book->inward_date = date('Y-m-d');
        $book->inward_qty = $request->total_production;
        $book->remaining_qty = $request->total_production;
        $book->particular = 'Inward From Belt Production Batch : ' . $belt->batch_no;
        $book->created_time = date('d-m-Y h:i:s a');
        $book->user_id = Session::get('user_id');
        $book->save();

        return redirect()->route('admin.belt_production.list')->with('message', 'Belt production batch completed');
    }
}
