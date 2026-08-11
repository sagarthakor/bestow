<?php

namespace App\Http\Controllers;

use App\BeltRoll;
use App\BeltRollProduction;
use App\BeltRollProductionMaterial;
use App\customers;
use App\Http\Controllers\Concerns\BeltPurchaseRequest;
use App\Http\Controllers\Concerns\RollFormulaCategoryTotals;
use App\NiwarCode;
use App\RollFormulaMst;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Session;

/**
 * Stage A of belt manufacturing: weave dhaga into niwar rolls of a chosen
 * length. Nothing here is size-aware - a roll is just meters of a size-less semi
 * product. Sizes only appear later, in BeltCuttingController.
 *
 * The semi product's roll formula is per 1 meter, so a batch is simply that
 * formula times the meters being woven. If stock will not cover it, the shortage
 * goes to the purchase department as a requirement instead of becoming a batch -
 * the same route sock production takes from machine allocation.
 *
 * Dhaga is issued when the batch is created, against the plan. What the floor
 * really consumed is recorded when the batch is completed, and the difference is
 * posted to stock then, so the ledger ends up holding actuals rather than
 * estimates.
 */
class BeltRollProductionController extends Controller
{
    use BeltPurchaseRequest;
    use RollFormulaCategoryTotals;

    /**
     * What a batch of $totalMtr meters of this formula consumes, with stock
     * compared per raw material. Shared by the AJAX preview, the batch gate and
     * the purchase request, so all three always agree.
     */
    private function computeMaterialRequirement($rollFormulaId, $totalMtr)
    {
        $formula = RollFormulaMst::with(['items', 'product_item:id,product_name', 'niwar'])->find($rollFormulaId);

        if (empty($formula)) {
            return ['formula' => null, 'lines' => collect(), 'shortages' => collect(), 'sufficient' => false, 'errors' => ['Roll formula not found.']];
        }

        $rows = $formula->materialsForMeters($totalMtr);

        if ($rows->isEmpty()) {
            return ['formula' => $formula, 'lines' => collect(), 'shortages' => collect(), 'sufficient' => false, 'errors' => []];
        }

        // The niwar code may have been re-rated after this formula was saved, in
        // which case the recipe no longer adds up and must not issue dhaga.
        $errors = $this->categoryTotalErrors(
            $formula->niwar_code_id,
            $formula->items->map(fn ($i) => [
                'niwar_type_material_id' => (int) $i->niwar_type_material_id,
                'material' => (int) $i->material,
                'gm_per_meter' => (float) $i->gm_per_meter,
            ])->all()
        );

        $materialIds = $rows->pluck('material')->unique()->all();
        $this->cacheConversionFactors($materialIds);

        $stockByProduct = DB::table('stock_status')->whereIn('product', $materialIds)->pluck('qty', 'product');
        $products = DB::table('product')
            ->leftJoin('uom', 'uom.id', '=', 'product.uom')
            ->whereIn('product.id', $materialIds)
            ->select('product.id', 'product.product_name', 'uom.uom_name')
            ->get()
            ->keyBy('id');

        // One material can appear in two categories of the same formula - a dhaga
        // used both on its own and inside a group. Stock has to answer for the
        // whole of it, so what is short is worked out per material and not per
        // row: checking each row against the full stock on its own would pass a
        // batch that then consumes more than the shelf holds.
        $byMaterial = $rows->groupBy('material');
        $totalByMaterial = $byMaterial->map(fn ($g) => round($g->sum('required_qty'), 2));
        $rowsPerMaterial = $byMaterial->map(fn ($g) => $g->count());

        $lines = $rows->map(function ($row) use ($stockByProduct, $products, $totalByMaterial, $rowsPerMaterial) {
            $factor = $this->materialConversionFactor($row->material);
            $availableStock = round(($stockByProduct[$row->material] ?? 0) * $factor, 2);
            $requiredTotal = $totalByMaterial[$row->material];
            $shortBy = round($requiredTotal - $availableStock, 2);
            $isShort = $this->isShort($requiredTotal, $availableStock);

            // Requirement and converted stock are both in grams for a KG-tracked
            // material, so the KG label would be wrong on them.
            return (object) [
                'material' => $row->material,
                'niwar_type_material_id' => $row->niwar_type_material_id,
                'product_name' => $products[$row->material]->product_name ?? ('Material #' . $row->material),
                'uom_name' => $factor > 1 ? 'g' : ($products[$row->material]->uom_name ?? ''),
                'stock_factor' => $factor,
                'category' => $row->category,
                'gm_per_meter' => $row->gm_per_meter,
                'required_qty' => $row->required_qty,
                // What this material needs across every row it appears in, which
                // is the figure stock is judged against.
                'required_total' => $requiredTotal,
                'repeats' => $rowsPerMaterial[$row->material] > 1,
                'available_stock' => $availableStock,
                'short_by' => $isShort ? max($shortBy, 0) : 0,
                'is_short' => $isShort,
            ];
        });

        // One entry per short material, already carrying the quantity to ask
        // purchase for - so the screen, the batch gate and the purchase request
        // can never quote three different numbers.
        $shortages = $lines->where('is_short', true)
            ->unique('material')
            ->map(function ($line) {
                $line = clone $line;
                $line->purchase_qty = $this->purchaseQty($line->short_by, $line->stock_factor);
                $line->stock_uom = $line->stock_factor > 1 ? 'KG' : $line->uom_name;

                return $line;
            })
            ->values();

        return [
            'formula' => $formula,
            'lines' => $lines,
            'shortages' => $shortages,
            'errors' => $errors,
            'sufficient' => empty($errors) && $shortages->isEmpty(),
        ];
    }

    function roll_production_list(Request $request)
    {
        $query = BeltRollProduction::with(['niwar', 'roll_product:id,product_name', 'customer_item:id,customer_name'])
            ->withCount('rolls')
            ->orderBy('id', 'desc');

        if ($request->batch_no != '') {
            $query->where('batch_no', 'like', '%' . $request->batch_no . '%');
        }
        if ($request->status != '') {
            $query->where('status', $request->status);
        }

        $data = $query->paginate(session('records_per_page', 30))->appends($request->all());

        return view('admin.belt_roll_production.list', compact('data'));
    }

    function roll_production_add(Request $request)
    {
        // An obsolete recipe stays readable for its old batches but must not be
        // offered for a new one.
        $formulas = RollFormulaMst::with(['product_item:id,product_name', 'niwar'])
            ->active()
            ->get()
            ->sortBy(fn ($f) => $f->product_item->product_name ?? '')
            ->values();

        $customer = customers::orderBy('customer_name', 'asc')->get();

        return view('admin.belt_roll_production.create', compact('formulas', 'customer'));
    }

    function roll_production_check_material(Request $request)
    {
        $rollLength = (float) $request->roll_length_mtr;
        $noOfRolls = (int) $request->no_of_rolls;
        $totalMtr = round($rollLength * $noOfRolls, 2);

        if (empty($request->roll_formula_id) || $rollLength <= 0 || $noOfRolls <= 0) {
            return response()->json(['html' => '', 'sufficient' => false, 'has_formula' => false]);
        }

        $result = $this->computeMaterialRequirement($request->roll_formula_id, $totalMtr);

        if (empty($result['formula'])) {
            return response()->json(['html' => '<div class="alert alert-danger">Roll formula not found.</div>', 'sufficient' => false, 'has_formula' => false]);
        }

        if ($result['lines']->isEmpty()) {
            return response()->json([
                'html' => '<div class="alert alert-danger">This semi product\'s roll formula has no raw material rows. Open Roll Formula and add them.</div>',
                'sufficient' => false,
                'has_formula' => false,
            ]);
        }

        $formula = $result['formula'];
        $rollName = $formula->product_item->product_name ?? '';
        $niwarLabel = $formula->niwar->label ?? '';

        $html = '<p>Weaving <b>' . $noOfRolls . ' roll(s) x ' . $rollLength . ' mtr = ' . $totalMtr . ' mtr</b> of '
            . e($rollName)
            // The name usually already carries the niwar; do not say it twice.
            . ($niwarLabel && !str_contains($rollName, $niwarLabel) ? ' (' . e($niwarLabel) . ')' : '')
            . ', on formula version <b>' . $formula->version . '</b>.</p>';

        if (!empty($result['errors'])) {
            $html .= '<div class="alert alert-danger"><b>This formula no longer balances against its niwar code, '
                . 'so no dhaga can be issued against it:</b><ul>';
            foreach ($result['errors'] as $error) {
                $html .= '<li>' . e($error) . '</li>';
            }
            $html .= '</ul>Fix it in Roll Formula first.</div>';
        }

        $html .= '<table class="table table-bordered"><tr><th>Category</th><th>Raw Material</th><th>Gm/Meter</th><th>Required Qty</th><th>Available Stock</th><th>Short By</th></tr>';
        foreach ($result['lines'] as $line) {
            $rowStyle = $line->is_short ? "style='background:#fdeaea'" : '';
            $html .= "<tr $rowStyle>
                <td>" . e($line->category ?? '-') . "</td>
                <td>" . e($line->product_name) . "</td>
                <td style='text-align:right;'>{$line->gm_per_meter}</td>
                <td style='text-align:right;'>{$line->required_qty} {$line->uom_name}"
                // A dhaga used in two categories is judged on the two together,
                // so the row says which total its stock was measured against.
                . ($line->repeats
                    ? "<br><small class='text-muted'>{$line->required_total} {$line->uom_name} in this batch altogether</small>"
                    : '')
                . "</td>
                <td style='text-align:right;'>{$line->available_stock} {$line->uom_name}</td>
                <td>" . ($line->is_short ? "<b style='color:#c0392b'>{$line->short_by} {$line->uom_name} short</b>" : 'OK') . "</td>
            </tr>";
        }
        $html .= '</table>';

        $hasShortage = $result['shortages']->isNotEmpty();

        if ($hasShortage) {
            $html .= $this->shortageHtml($result['shortages']);
        }

        return response()->json([
            'html' => $html,
            'sufficient' => $result['sufficient'],
            'has_formula' => true,
            'can_purchase' => $hasShortage && empty($result['errors']),
        ]);
    }

    function roll_production_store(Request $request)
    {
        $request->validate([
            'roll_formula_id' => 'required|exists:roll_formula_mst,id',
            'roll_length_mtr' => 'required|numeric|min:0.01|max:100000',
            'no_of_rolls' => 'required|integer|min:1|max:10000',
            'customer' => 'nullable|exists:customers,id',
        ]);

        $totalMtr = round($request->roll_length_mtr * $request->no_of_rolls, 2);
        $result = $this->computeMaterialRequirement($request->roll_formula_id, $totalMtr);
        $formula = $result['formula'];

        if ($result['lines']->isEmpty()) {
            return back()->withInput()->with('error', 'This semi product\'s roll formula has no raw material rows - add them in Roll Formula first.');
        }

        if (!empty($result['errors'])) {
            return back()->withInput()->with('error', 'This roll formula no longer balances against its niwar code, so dhaga cannot be issued: '
                . implode(' ', $result['errors']));
        }

        // Shortages become a purchase requirement rather than a blocked screen,
        // exactly as sock production does at machine allocation.
        if ($request->action === 'purchase_request') {
            return $this->raisePurchaseRequest($result, $request);
        }

        if (!$formula->isActive()) {
            return back()->withInput()->with('error', 'This roll formula is inactive and cannot start a new batch.');
        }

        // Server-side is the real gate; the AJAX preview is only a convenience.
        if (!$result['sufficient']) {
            $shortages = $result['shortages']
                ->map(fn ($l) => "{$l->product_name} (short by {$l->short_by} {$l->uom_name}, "
                    . "buy {$l->purchase_qty} {$l->stock_uom})")
                ->implode(', ');

            return back()->withInput()->with('error', 'Insufficient raw material stock, roll batch not created. Short: ' . $shortages);
        }

        date_default_timezone_set('Asia/Kolkata');

        // Batch, its frozen material list and the dhaga issue are one unit of
        // work: dhaga off the shelf with no batch to explain it is worse than a
        // failed save.
        $batch = DB::transaction(function () use ($request, $result, $formula, $totalMtr) {
            $batch = new BeltRollProduction();
            $batch->batch_no = $this->nextDocNo('ROLL', 'belt_roll_production', 'batch_no');
            $batch->roll_formula_id = $formula->id;
            $batch->roll_formula_version = $formula->version;
            $batch->niwar_code_id = $formula->niwar_code_id;
            $batch->roll_product_id = $formula->product;
            $batch->roll_length_mtr = $request->roll_length_mtr;
            $batch->no_of_rolls = $request->no_of_rolls;
            $batch->planned_mtr = $totalMtr;
            $batch->customer = $request->customer ?: null;
            $batch->status = 'N';
            $batch->timestamp = date('d-m-Y h:i:s a');
            $batch->user_id = Session::get('user_id');
            $batch->save();

            foreach ($result['lines'] as $line) {
                $material = new BeltRollProductionMaterial();
                $material->belt_roll_production_id = $batch->id;
                $material->material = $line->material;
                $material->gm_per_meter = $line->gm_per_meter;
                $material->category_name = $line->category;
                $material->required_qty = $line->required_qty;
                $material->stock_factor = $line->stock_factor;
                $material->avalible_stock = $line->available_stock;
                $material->timestamp = date('d-m-Y h:i:s a');
                $material->user_id = Session::get('user_id');
                $material->save();

                $this->stockOut(
                    $line->material,
                    $line->required_qty / $line->stock_factor,
                    'Used in Roll Production ' . $batch->batch_no
                );
            }

            return $batch;
        });

        return redirect()->route('admin.belt_roll_production.list')->with('message', 'Roll production batch created: ' . $batch->batch_no);
    }

    /**
     * Sends only the shortfall - what stock already covers does not need buying.
     */
    private function raisePurchaseRequest(array $result, Request $request)
    {
        $short = $result['shortages'];

        if ($short->isEmpty()) {
            return back()->withInput()->with('error', 'Nothing is short - no purchase request needed.');
        }

        $requirement = $this->raiseBeltPurchaseRequest($short, $result['formula']->product, $request->customer);

        return redirect()->route('admin.belt_roll_production.list')
            ->with('message', $this->purchaseRequestMessage($requirement, $short->count()));
    }

    /**
     * The completion screen: what was planned per material, for the operator to
     * correct against what the floor actually used.
     */
    function roll_production_completion_form(Request $request)
    {
        $batch = BeltRollProduction::with(['materials.material_item:id,product_name', 'roll_product:id,product_name', 'niwar'])
            ->find($request->id);

        if (empty($batch)) {
            return response()->json(['html' => '<div class="alert alert-danger">Roll batch not found.</div>', 'ok' => false]);
        }

        if ($batch->status != 'N') {
            return response()->json(['html' => '<div class="alert alert-danger">This batch is not open.</div>', 'ok' => false]);
        }

        $html = '<table class="table table-bordered"><tr><th>Category</th><th>Raw Material</th>'
            . '<th style="text-align:right;">Planned</th><th style="width:26%;">Actual Used</th></tr>';

        foreach ($batch->materials as $material) {
            $name = $material->material_item->product_name ?? ('Material #' . $material->material);
            $unit = $material->stock_factor > 1 ? 'g' : '';

            $html .= '<tr><td>' . e($material->category_name ?? '-') . '</td><td>' . e($name) . '</td>'
                . '<td style="text-align:right;">' . $material->required_qty . ' ' . $unit . '</td>'
                . '<td><input type="text" class="form-control actual-qty" name="actual_qty[' . $material->id . ']" '
                . 'data-planned="' . $material->required_qty . '" value="' . $material->required_qty . '"></td></tr>';
        }
        $html .= '</table>';
        $html .= '<p class="text-muted">Leave the actual figures as they are if the floor used exactly what was planned. '
            . 'A difference is posted to dhaga stock when the batch is completed.</p>';

        return response()->json([
            'html' => $html,
            'ok' => true,
            'planned_mtr' => (float) $batch->planned_mtr,
            'roll_length_mtr' => (float) $batch->roll_length_mtr,
        ]);
    }

    /**
     * Completing the batch is what actually creates the physical rolls and puts
     * meters into roll stock. The floor reports total meters woven, which may
     * fall short of plan, so the rolls are cut from that actual figure: full
     * rolls at the planned length and a shorter last roll for the remainder.
     *
     * Weaving more than was planned is refused: dhaga was issued against the
     * plan, so meters beyond it are niwar the batch never had the material for.
     * More meters really coming off the loom means the plan was wrong, and the
     * batch should be raised for the quantity actually intended.
     */
    function roll_production_complete(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:belt_roll_production,id',
            'produced_mtr' => 'required|numeric|min:0|max:1000000',
            'actual_qty' => 'nullable|array',
            'actual_qty.*' => 'nullable|numeric|min:0',
        ]);

        date_default_timezone_set('Asia/Kolkata');

        try {
            $batch = DB::transaction(function () use ($request) {
                // Locked before anything is read: two operators hitting Complete
                // on the same batch would otherwise each create a full set of
                // rolls and each add the meters to stock.
                $batch = BeltRollProduction::with('materials')->lockForUpdate()->find($request->id);

                if ($batch->status == 'Y') {
                    throw new \RuntimeException('This roll batch is already completed.');
                }
                if ($batch->status == 'C') {
                    throw new \RuntimeException('This roll batch was cancelled and cannot be completed.');
                }

                $producedMtr = round((float) $request->produced_mtr, 2);

                // Half a centimeter of tolerance so a figure that is really equal
                // to plan cannot be refused by floating-point drift.
                if ($producedMtr > round($batch->planned_mtr, 2) + 0.005) {
                    throw new \RuntimeException(
                        'Produced meters cannot be more than the planned ' . round($batch->planned_mtr, 2)
                        . ' mtr - dhaga was only issued for the planned quantity. If more was genuinely woven, '
                        . 'raise a batch for the quantity actually intended.'
                    );
                }

                $this->applyActualConsumption($batch, $request->actual_qty ?? []);

                $batch->produced_mtr = $producedMtr;
                $batch->wastage_mtr = round(max($batch->planned_mtr - $producedMtr, 0), 2);
                $batch->status = 'Y';
                $batch->completed_by = Session::get('user_id');
                $batch->completed_at = now();
                $batch->save();

                foreach ($this->splitIntoRolls($producedMtr, $batch->roll_length_mtr) as $length) {
                    $roll = new BeltRoll();
                    $roll->roll_no = $this->nextRollNo();
                    $roll->belt_roll_production_id = $batch->id;
                    $roll->niwar_code_id = $batch->niwar_code_id;
                    $roll->roll_product_id = $batch->roll_product_id;
                    $roll->length_mtr = $length;
                    $roll->remaining_mtr = $length;
                    $roll->scrap_mtr = 0;
                    $roll->status = 'open';
                    $roll->user_id = Session::get('user_id');
                    $roll->save();
                }

                if ($producedMtr > 0) {
                    $this->stockIn($batch->roll_product_id, $producedMtr, 'Inward From Roll Production Batch : ' . $batch->batch_no);
                }

                return $batch;
            });
        } catch (\RuntimeException $e) {
            return back()->with('error', $e->getMessage());
        }

        return redirect()->route('admin.belt_roll_production.list')
            ->with('message', 'Roll batch ' . $batch->batch_no . ' completed, ' . $batch->rolls()->count() . ' roll(s) added to stock');
    }

    /**
     * Records what the floor really consumed and posts only the difference from
     * what was already issued at batch creation.
     *
     * A group category such as Roto has a planned total its threads must add up
     * to exactly - but that rule governs the recipe, not the scale. Actual
     * consumption is a measurement, so the threads are recorded as reported and
     * the variance is what the reports show.
     */
    private function applyActualConsumption(BeltRollProduction $batch, array $actuals)
    {
        foreach ($batch->materials as $material) {
            $actual = array_key_exists($material->id, $actuals) && $actuals[$material->id] !== null && $actuals[$material->id] !== ''
                ? round((float) $actuals[$material->id], 2)
                : (float) $material->required_qty;

            if ($actual < 0) {
                throw new \RuntimeException('Actual consumption cannot be negative.');
            }

            $factor = $material->stock_factor ?: 1;
            $delta = round($actual - $material->required_qty, 4);

            $material->actual_qty = $actual;
            $material->save();

            // Negative delta returns the unused dhaga to the shelf, positive
            // takes the extra off it.
            $this->moveStock(
                $material->material,
                -$delta / $factor,
                'Consumption variance on Roll Production ' . $batch->batch_no
            );
        }
    }

    /**
     * Unwinds a batch completely: dhaga back on the shelf, rolls voided and their
     * meters taken back out of roll stock.
     *
     * Only possible while every roll it produced is still untouched - once a roll
     * has been cut, the belts made from it are already in finished stock and
     * unwinding the batch underneath them would leave stock nothing explains.
     */
    function roll_production_cancel(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:belt_roll_production,id',
            'cancel_reason' => 'required|string|max:255',
        ]);

        date_default_timezone_set('Asia/Kolkata');

        try {
            $batch = DB::transaction(function () use ($request) {
                $batch = BeltRollProduction::with(['materials', 'rolls'])->lockForUpdate()->find($request->id);

                if ($batch->status == 'C') {
                    throw new \RuntimeException('This roll batch is already cancelled.');
                }

                $touched = $batch->rolls->filter(
                    fn ($roll) => $roll->status != 'open' || round($roll->remaining_mtr, 2) != round($roll->length_mtr, 2)
                );

                if ($touched->isNotEmpty()) {
                    throw new \RuntimeException(
                        'Roll ' . $touched->pluck('roll_no')->implode(', ') . ' has already been cut or closed, '
                        . 'so this batch can no longer be cancelled. Reverse the cutting entries first.'
                    );
                }

                // Dhaga back: whatever was actually taken off the shelf, which is
                // the actual figure once completed and the planned one before.
                foreach ($batch->materials as $material) {
                    $issued = $material->actual_qty !== null ? (float) $material->actual_qty : (float) $material->required_qty;
                    $this->stockIn(
                        $material->material,
                        $issued / ($material->stock_factor ?: 1),
                        'Reversal of Roll Production ' . $batch->batch_no
                    );
                }

                if ($batch->status == 'Y' && $batch->produced_mtr > 0) {
                    $this->stockOut(
                        $batch->roll_product_id,
                        $batch->produced_mtr,
                        'Reversal of Roll Production ' . $batch->batch_no
                    );
                }

                foreach ($batch->rolls as $roll) {
                    $roll->status = 'cancelled';
                    $roll->remaining_mtr = 0;
                    $roll->save();
                }

                $batch->status = 'C';
                $batch->cancelled_by = Session::get('user_id');
                $batch->cancelled_at = now();
                $batch->cancel_reason = $request->cancel_reason;
                $batch->save();

                return $batch;
            });
        } catch (\RuntimeException $e) {
            return back()->with('error', $e->getMessage());
        }

        return redirect()->route('admin.belt_roll_production.list')
            ->with('message', 'Roll batch ' . $batch->batch_no . ' cancelled - dhaga returned to stock and its rolls voided.');
    }

    /**
     * 340 mtr at a 70 mtr roll length becomes 4 x 70 + 1 x 60. A leftover shorter
     * than a full roll is still a usable roll, just a short one.
     */
    private function splitIntoRolls($producedMtr, $rollLength): array
    {
        $lengths = [];
        $left = round($producedMtr, 2);

        if ($rollLength <= 0) {
            return $left > 0 ? [$left] : [];
        }

        while ($left >= $rollLength) {
            $lengths[] = (float) $rollLength;
            $left = round($left - $rollLength, 2);
        }

        if ($left > 0) {
            $lengths[] = $left;
        }

        return $lengths;
    }

    private function nextRollNo(): string
    {
        return 'R-' . $this->nextSequence('belt_rolls', 'roll_no', 'R-', '', 6);
    }

    /**
     * Raw material issued to a batch, what the floor actually used, and the
     * difference between the two.
     */
    function roll_production_material(Request $request)
    {
        $batch = BeltRollProduction::with(['niwar', 'roll_product:id,product_name', 'materials.material_item:id,product_name'])
            ->find($request->id);

        if (empty($batch)) {
            return response()->json(['html' => '<div class="alert alert-danger">Roll batch not found.</div>']);
        }

        $html = '<p><b>' . e($batch->batch_no) . '</b> &mdash; ' . e($batch->roll_product->product_name ?? '')
            . ' (' . e($batch->niwar->label ?? '') . ')'
            . ($batch->roll_formula_version ? ', formula version ' . $batch->roll_formula_version : '')
            . '<br>Planned ' . $batch->planned_mtr . ' mtr';

        if ($batch->status == 'Y') {
            $html .= ', produced ' . $batch->produced_mtr . ' mtr, wastage ' . $batch->wastage_mtr . ' mtr';
        } elseif ($batch->status == 'C') {
            $html .= ' &mdash; <b class="text-danger">cancelled</b> (' . e($batch->cancel_reason) . ')';
        }
        $html .= '</p>';

        $showActual = $batch->status == 'Y';

        $html .= '<table class="table table-bordered"><tr><th>Category</th><th>Raw Material</th><th>Gm/Mtr</th><th>Planned</th>'
            . ($showActual ? '<th>Actual</th><th>Variance</th>' : '') . '</tr>';

        foreach ($batch->materials as $material) {
            $name = $material->material_item->product_name ?? ('Material #' . $material->material);
            $unit = $material->stock_factor > 1 ? 'g' : '';

            $html .= '<tr><td>' . e($material->category_name ?? '-') . '</td><td>' . e($name) . '</td>'
                . '<td style="text-align:right;">' . ($material->gm_per_meter !== null ? rtrim(rtrim($material->gm_per_meter, '0'), '.') : '-') . '</td>'
                . '<td style="text-align:right;">' . $material->required_qty . ' ' . $unit . '</td>';

            if ($showActual) {
                $actual = $material->actual_qty !== null ? (float) $material->actual_qty : (float) $material->required_qty;
                $variance = round($actual - $material->required_qty, 2);
                $colour = $variance > 0 ? '#c0392b' : ($variance < 0 ? '#27ae60' : '#777');

                $html .= '<td style="text-align:right;">' . $actual . ' ' . $unit . '</td>'
                    . '<td style="text-align:right;color:' . $colour . ';">'
                    . ($variance > 0 ? '+' : '') . $variance . ' ' . $unit . '</td>';
            }
            $html .= '</tr>';
        }
        $html .= '</table>';

        return response()->json(['html' => $html]);
    }

    /**
     * Roll register - every physical roll with what is left on it.
     */
    function roll_register(Request $request)
    {
        $query = BeltRoll::with(['niwar', 'roll_product:id,product_name', 'roll_production:id,batch_no'])
            ->orderBy('id', 'desc');

        if ($request->status != '') {
            $query->where('status', $request->status);
        }
        if ($request->niwar_code_id != '') {
            $query->where('niwar_code_id', $request->niwar_code_id);
        }
        if ($request->roll_no != '') {
            $query->where('roll_no', 'like', '%' . $request->roll_no . '%');
        }
        if ($request->batch != '') {
            $query->where('belt_roll_production_id', $request->batch);
        }

        $niwarCodes = NiwarCode::orderBy('type', 'asc')->get();

        // Aggregated in the database rather than by loading every matching roll -
        // the register is the one screen that will grow without bound.
        $summary = (clone $query)->toBase()
            ->reorder()
            ->selectRaw('COUNT(*) as rolls, COALESCE(SUM(remaining_mtr), 0) as remaining, COALESCE(SUM(scrap_mtr), 0) as scrap')
            ->first();

        $data = $query->paginate(session('records_per_page', 30))->appends($request->all());

        return view('admin.belt_roll_production.register', [
            'data' => $data,
            'niwarCodes' => $niwarCodes,
            'totalRolls' => (int) ($summary->rolls ?? 0),
            'totalRemaining' => round($summary->remaining ?? 0, 2),
            'totalScrap' => round($summary->scrap ?? 0, 2),
        ]);
    }
}
