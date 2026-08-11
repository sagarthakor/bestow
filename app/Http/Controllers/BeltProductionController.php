<?php

namespace App\Http\Controllers;

use App\BeltProduction;
use App\Http\Controllers\Concerns\BeltStockMovement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * Archive of the original single-stage belt production, kept read-only.
 *
 * Belts are now made in two stages - BeltRollProductionController weaves the
 * size-less roll, BeltCuttingController cuts it to size and fits it - so no new
 * batches start here. Batches that were already open can still be completed, and
 * the history stays visible and unchanged.
 */
class BeltProductionController extends Controller
{
    use BeltStockMovement;

    function belt_production_list(Request $request)
    {
        $data = BeltProduction::with(['belt_item:id,product_name,value1,value2', 'customer_item:id,customer_name'])
            ->orderBy('id', 'desc')
            ->paginate(session('records_per_page', 30));

        return view('admin.belt_production.list', compact('data'));
    }

    /**
     * Raw material lost with the wasted units. Read from the batch's own frozen
     * material snapshot rather than recomputed from the formula - the formula has
     * since become fitting-only, so recomputing would understate old batches.
     */
    function belt_production_wastage_material(Request $request)
    {
        $belt = BeltProduction::with('materials.material_item:id,product_name')->find($request->id);

        if (empty($belt) || $belt->status != 'Y' || empty($belt->total_wastage_nos) || $belt->total_wastage_nos <= 0) {
            return response()->json(['html' => '<div class="alert alert-info">No wastage recorded for this batch.</div>']);
        }

        if ($belt->planned_qty <= 0 || $belt->materials->isEmpty()) {
            return response()->json(['html' => '<div class="alert alert-info">No material detail recorded for this batch.</div>']);
        }

        $wastedShare = $belt->total_wastage_nos / $belt->planned_qty;

        $html = '<p>Raw material used up by the ' . $belt->total_wastage_nos . ' wasted unit(s):</p>';
        $html .= '<table class="table table-bordered"><tr><th>Raw Material</th><th>Qty Wasted</th></tr>';
        foreach ($belt->materials as $material) {
            $name = $material->material_item->product_name ?? ('Material #' . $material->material);
            $qty = round($material->required_qty * $wastedShare, 2);
            $html .= "<tr><td>{$name}</td><td>{$qty}</td></tr>";
        }
        $html .= '</table>';

        return response()->json(['html' => $html]);
    }

    /**
     * Finish a batch that was already open when the two-stage flow came in.
     */
    function belt_production_complete(Request $request)
    {
        $request->validate([
            'id' => 'required',
            'total_production' => 'required|numeric|min:0',
        ]);

        $belt = BeltProduction::find($request->id);

        if (empty($belt) || $belt->status == 'Y') {
            return back()->with('error', 'This batch is not open.');
        }

        date_default_timezone_set('Asia/Kolkata');

        DB::transaction(function () use ($belt, $request) {
            // Re-read under lock: two clicks on Complete would otherwise both
            // pass the status check above and each add the units to stock.
            $belt = BeltProduction::lockForUpdate()->find($belt->id);

            if ($belt->status == 'Y') {
                return;
            }

            $belt->total_production = $request->total_production;
            $belt->total_wastage_nos = max($belt->planned_qty - $request->total_production, 0);
            $belt->status = 'Y';
            $belt->save();

            $this->stockIn($belt->belt_product, $request->total_production, 'Inward From Belt Production Batch : ' . $belt->batch_no);
        });

        return redirect()->route('admin.belt_production.list')->with('message', 'Belt production batch completed');
    }

    /**
     * True while any pre-two-stage batch is still open, so the archive list can
     * explain why it is still showing a Complete button.
     */
    public static function hasOpenBatches(): bool
    {
        return DB::table('belt_production')->where('status', 'N')->exists();
    }
}
