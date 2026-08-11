<?php

namespace App\Http\Controllers;

use App\BeltCutting;
use App\BeltCuttingItem;
use App\BeltCuttingMaterial;
use App\BeltRoll;
use App\BeltCosting;
use App\customers;
use App\Http\Controllers\Concerns\BeltPurchaseRequest;
use App\product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Session;

/**
 * Stage B of belt manufacturing: cut one roll into pieces of one or more sizes,
 * fit kadi/slider/bukkal on them and take the finished, size-wise belts into
 * stock. One entry deliberately handles exactly one roll, so every finished belt
 * traces back to a single roll_no.
 *
 * Three different things can happen to the meters on a roll and they are kept
 * apart on purpose: meters that became belts, meters lost as trim on this cut
 * (wastage_mtr), and meters still usable and left on the roll for the next cut.
 * Only when the tail is genuinely too short does closing the roll write it off
 * as scrap.
 */
class BeltCuttingController extends Controller
{
    use BeltPurchaseRequest;

    /**
     * Belt products that can come out of this roll: every size-wise finished belt
     * whose size the roll's niwar is charted for.
     *
     * Selection is driven by the product master rather than by a belt formula.
     * The belt formula used to be the only way in, but it was written for the old
     * single-stage flow - its material rows are leftover dhaga, which Roll Formula
     * now owns - and only a handful of belts ever had one, so it kept almost every
     * product out of cutting. What cutting actually needs is the size (to read the
     * niwar chart) and the fitting bill, and those come from the product's own
     * size and from the belt costing.
     *
     * @param  string|null  $search  narrows a large catalogue by name or code
     */
    private function cuttableProducts(BeltRoll $roll, $search = null, $limit = null)
    {
        $niwar = $roll->niwar;

        if (empty($niwar)) {
            return collect();
        }

        $chartedSizes = $niwar->sizeChart->pluck('pp_size')->map(fn ($s) => trim((string) $s))->filter()->all();

        if (empty($chartedSizes)) {
            return collect();
        }

        $beltCategoryId = DB::table('category')->where('category_name', 'Belt')->value('id');

        $query = DB::table('product')
            ->where('status', 'product')
            ->whereIn(DB::raw('TRIM(value2)'), $chartedSizes)
            ->select('id', 'product_name', 'item_code', 'value1', 'value2');

        if ($beltCategoryId) {
            $query->where('category', $beltCategoryId);
        }

        // The roll formula names the belt this roll is woven to become, so the
        // sizes on offer are that one product's own range - not the catalogue.
        $family = $this->beltProductFamilyFor($roll);

        if ($family !== null) {
            $query->where('product_name', $family->name);
            // Two belts can share a name and differ only by item code
            // (BRASS_COTTON vs BRASS_MALAI); they are not the same product.
            $family->item_code === null
                ? $query->whereNull('item_code')
                : $query->where('item_code', $family->item_code);
        }

        // Matched word by word, like the product picker on the document screens:
        // every word typed has to appear somewhere on the belt - name, item code,
        // size or colour - in any order, so "111 30" finds size 30 of belt 111.
        foreach (preg_split('/\s+/', trim((string) $search), -1, PREG_SPLIT_NO_EMPTY) ?: [] as $word) {
            $like = '%' . str_replace(['%', '_'], ['\%', '\_'], $word) . '%';

            $query->where(function ($q) use ($like) {
                $q->where('product_name', 'like', $like)
                    ->orWhere('item_code', 'like', $like)
                    ->orWhere('value2', 'like', $like)
                    ->orWhere('value1', 'like', $like)
                    ->orWhere('sku', 'like', $like);
            });
        }

        $query->orderBy('product_name');

        if ($limit) {
            $query->limit($limit);
        }

        return $query->get()->map(function ($product) use ($niwar) {
            $size = trim((string) $product->value2);

            return (object) [
                'belt_product' => (int) $product->id,
                'product_name' => product::nameWithVariantInline($product->product_name, $product->value1, $product->value2),
                'base_name' => $product->product_name,
                'item_code' => $product->item_code,
                'size' => $size,
                'required_inch' => $niwar->inchForSize($size),
                'meter_per_piece' => $niwar->metersForSize($size),
                'gram_per_piece' => $niwar->gramsForSize($size),
            ];
        })->filter(fn ($p) => $p->meter_per_piece > 0)->values();
    }

    /**
     * The belt product family this roll was woven for, via its batch's roll
     * formula. Null when the formula names no belt, which leaves cutting open to
     * the whole sized catalogue as before.
     *
     * Cached per request: the plan re-resolves every posted line, and this would
     * otherwise be three joins per line.
     */
    private function beltProductFamilyFor(BeltRoll $roll)
    {
        if (array_key_exists($roll->id, $this->beltFamilyCache)) {
            return $this->beltFamilyCache[$roll->id];
        }

        $picked = DB::table('belt_roll_production as b')
            ->join('roll_formula_mst as f', 'f.id', '=', 'b.roll_formula_id')
            ->join('product as p', 'p.id', '=', 'f.belt_product_id')
            ->where('b.id', $roll->belt_roll_production_id)
            ->select('p.product_name', 'p.item_code')
            ->first();

        $family = null;
        if ($picked && trim((string) $picked->product_name) !== '') {
            $code = trim((string) $picked->item_code);
            $family = (object) [
                'name' => trim($picked->product_name),
                'item_code' => $code === '' ? null : $code,
                'label' => trim($picked->product_name) . ($code === '' ? '' : ' (' . $code . ')'),
            ];
        }

        return $this->beltFamilyCache[$roll->id] = $family;
    }

    /** beltProductFamilyFor() results for this request, keyed by roll id. */
    private $beltFamilyCache = [];

    /**
     * One belt product, resolved the same way the dropdown resolves it. Used when
     * saving, so a posted product is validated against the roll's chart - and
     * against the belt family the roll was woven for - rather than trusted.
     */
    private function cuttableProduct(BeltRoll $roll, $beltProductId)
    {
        $niwar = $roll->niwar;

        if (empty($niwar)) {
            return null;
        }

        $product = DB::table('product')->where('id', $beltProductId)->first();

        if (empty($product)) {
            return null;
        }

        // A product from a different belt family cannot come out of this roll,
        // however it reached the form.
        $family = $this->beltProductFamilyFor($roll);

        if ($family !== null) {
            $code = trim((string) ($product->item_code ?? ''));
            $sameName = trim((string) $product->product_name) === $family->name;
            $sameCode = ($code === '' ? null : $code) === $family->item_code;

            if (!$sameName || !$sameCode) {
                return null;
            }
        }

        $size = trim((string) $product->value2);
        $meters = $niwar->metersForSize($size);

        if (!$meters) {
            return null;
        }

        return (object) [
            'belt_product' => (int) $product->id,
            'product_name' => product::nameWithVariantInline($product->product_name, $product->value1, $product->value2),
            'base_name' => $product->product_name,
            'item_code' => $product->item_code ?? null,
            'size' => $size,
            'required_inch' => $niwar->inchForSize($size),
            'meter_per_piece' => $meters,
            'gram_per_piece' => $niwar->gramsForSize($size),
        ];
    }

    /**
     * Every P.P size the roll's niwar is charted for, split into the ones a
     * finished belt actually exists in and the ones with no product yet.
     *
     * Without this the screen could only ever show the intersection, so a size
     * missing from the dropdown looked like a bug rather than a missing product.
     */
    private function sizeChartSummary(BeltRoll $roll, $cuttable): array
    {
        $niwar = $roll->niwar;

        if (empty($niwar)) {
            return ['charted' => [], 'cuttable' => [], 'missing' => []];
        }

        $charted = $niwar->sizeChart
            ->map(fn ($row) => [
                'size' => trim((string) $row->pp_size),
                'inch' => (float) $row->required_inch,
                'mtr' => $niwar->metersForSize($row->pp_size),
                'gm' => $niwar->gramsForSize($row->pp_size),
            ])
            ->values();

        $cuttableSizes = collect($cuttable)->pluck('size')->unique();

        return [
            'niwar' => $niwar->label,
            'gm_per_meter' => $niwar->gramsPerMeter(),
            'weight_breakdown' => $niwar->weightBreakdown(),
            'charted' => $charted,
            'cuttable' => $charted->whereIn('size', $cuttableSizes)->values(),
            'missing' => $charted->whereNotIn('size', $cuttableSizes)->pluck('size')->values(),
        ];
    }

    /**
     * What one finished belt takes in fitting, for the screen to state up front
     * rather than only after pieces are entered.
     */
    private function fittingPreview($costing)
    {
        if (empty($costing)) {
            return ['lines' => [], 'configured' => false];
        }

        $lines = $costing->fittingPerBelt();
        $names = DB::table('product')->whereIn('id', $lines->pluck('product'))->pluck('product_name', 'id');

        return [
            'configured' => $lines->isNotEmpty(),
            'lines' => $lines->map(fn ($l) => [
                'label' => $l['label'],
                'qty' => $l['qty'],
                'name' => $names[$l['product']] ?? ('Product #' . $l['product']),
            ])->values(),
        ];
    }

    /**
     * Turns the posted size lines into cutting items plus the fitting material
     * they consume, and checks both roll meters and fitting stock. Shared by the
     * AJAX preview and store() so the two can never disagree.
     *
     * Rejected pieces are cut and fitted like any other, so they eat roll meters
     * and fitting material - they simply never reach finished goods stock.
     */
    private function computeCuttingPlan(BeltRoll $roll, array $beltProducts, array $pieces, array $rejected = [], $wastageMtr = 0)
    {
        $gramsPerMeter = $roll->niwar ? $roll->niwar->gramsPerMeter() : 0;

        $items = collect();
        foreach ($beltProducts as $i => $beltProductId) {
            $qty = (int) ($pieces[$i] ?? 0);
            $reject = max((int) ($rejected[$i] ?? 0), 0);

            if (empty($beltProductId) || $qty <= 0) {
                continue;
            }

            // Re-resolved from the roll's own chart, so a product posted for a
            // size this niwar cannot make is dropped rather than cut.
            $p = $this->cuttableProduct($roll, $beltProductId);

            if (empty($p)) {
                continue;
            }

            // Rounded once, here, and every figure below derived from it - so a
            // row's meters, its grams and the roll's own total all agree.
            $lineMeter = round($p->meter_per_piece * $qty, 2);

            $items->push((object) [
                'belt_product' => (int) $beltProductId,
                'product_name' => $p->product_name,
                'base_name' => $p->base_name,
                'size' => $p->size,
                'pieces' => $qty,
                'rejected_pieces' => min($reject, $qty),
                'good_pieces' => $qty - min($reject, $qty),
                'required_inch' => $p->required_inch,
                'meter_per_piece' => $p->meter_per_piece,
                'gram_per_piece' => $p->gram_per_piece,
                'total_inch' => round(($p->required_inch ?? 0) * $qty, 2),
                'total_meter' => $lineMeter,
                // From this row's own meters, not from the rounded per-piece
                // grams: the columns have to add up to what the roll lost.
                'total_gram' => round($lineMeter * $gramsPerMeter, 2),
            ]);
        }

        $wastageMtr = round(max((float) $wastageMtr, 0), 2);
        $cutMeter = round($items->sum('total_meter'), 2);
        $totalMeter = round($cutMeter + $wastageMtr, 2);
        $balance = round($roll->remaining_mtr - $totalMeter, 2);
        $rollOk = $items->isNotEmpty() && $balance >= 0;

        $materials = $this->computeFittingMaterials($roll, $items);
        $materialsOk = $materials->every(fn ($m) => !$m->is_short);

        // Weight of the niwar going into these belts, split the way the niwar code
        // is rated - Mono so much, Roto so much. The meters are already accounted
        // for; this says what those meters are made of.
        $niwar = $roll->niwar;
        $dhagaBreakdown = $niwar
            ? $niwar->weightBreakdown()->map(fn ($m) => (object) [
                'name' => $m->name,
                'is_group' => $m->is_group,
                'gm_per_meter' => $m->gm_per_meter,
                'gram_in_belts' => round($m->gm_per_meter * $cutMeter, 2),
                'gram_in_wastage' => round($m->gm_per_meter * $wastageMtr, 2),
                'gram_total' => round($m->gm_per_meter * $totalMeter, 2),
            ])
            : collect();

        return [
            'items' => $items,
            'materials' => $materials,
            'dhaga_breakdown' => $dhagaBreakdown,
            'gm_per_meter' => $gramsPerMeter,
            'total_pieces' => $items->sum('pieces'),
            'total_rejected' => $items->sum('rejected_pieces'),
            'total_good' => $items->sum('good_pieces'),
            'cut_meter' => $cutMeter,
            'cut_gram' => round($cutMeter * $gramsPerMeter, 2),
            'wastage_mtr' => $wastageMtr,
            'wastage_gram' => round($wastageMtr * $gramsPerMeter, 2),
            'total_meter' => $totalMeter,
            'total_gram' => round($totalMeter * $gramsPerMeter, 2),
            'balance' => $balance,
            'balance_gram' => round($balance * $gramsPerMeter, 2),
            'roll_ok' => $rollOk,
            'sufficient' => $rollOk && $materialsOk,
        ];
    }

    /**
     * Fitting the cut pieces consume - bukkal, kadi and panni - taken from the
     * belt costing of the roll's niwar and multiplied by the pieces cut.
     *
     * Aggregated per product, so a bukkal shared by several sizes is checked
     * against stock once rather than line by line.
     */
    private function computeFittingMaterials(BeltRoll $roll, $items)
    {
        if ($items->isEmpty() || empty($roll->niwar)) {
            return collect();
        }

        $costing = BeltCosting::where('niwar_id', $roll->niwar->id)->first();

        if (empty($costing)) {
            return collect();
        }

        $totalPieces = $items->sum('pieces');
        $required = [];
        $labels = [];

        foreach ($costing->fittingPerBelt() as $line) {
            $required[$line['product']] = ($required[$line['product']] ?? 0) + ($line['qty'] * $totalPieces);
            $labels[$line['product']] = $line['label'];
        }

        if (empty($required)) {
            return collect();
        }

        $materialIds = array_keys($required);
        $this->cacheConversionFactors($materialIds);

        $stockByProduct = DB::table('stock_status')->whereIn('product', $materialIds)->pluck('qty', 'product');
        $products = DB::table('product')
            ->leftJoin('uom', 'uom.id', '=', 'product.uom')
            ->whereIn('product.id', $materialIds)
            ->select('product.id', 'product.product_name', 'uom.uom_name')
            ->get()
            ->keyBy('id');

        return collect($materialIds)->map(function ($materialId) use ($required, $stockByProduct, $products, $labels) {
            $factor = $this->materialConversionFactor($materialId);
            $requiredQty = round($required[$materialId], 2);
            $availableStock = round(($stockByProduct[$materialId] ?? 0) * $factor, 2);
            $shortBy = round($requiredQty - $availableStock, 2);
            $isShort = $this->isShort($requiredQty, $availableStock);
            $uomName = $products[$materialId]->uom_name ?? '';

            return (object) [
                'material' => $materialId,
                'fitting_label' => $labels[$materialId] ?? '',
                'product_name' => $products[$materialId]->product_name ?? ('Material #' . $materialId),
                'uom_name' => $factor > 1 ? 'g' : $uomName,
                'stock_uom' => $factor > 1 ? 'KG' : $uomName,
                'stock_factor' => $factor,
                'required_qty' => $requiredQty,
                'available_stock' => $availableStock,
                'short_by' => $isShort ? max($shortBy, 0) : 0,
                'is_short' => $isShort,
                // What purchase would have to buy, in the unit they order in.
                'purchase_qty' => $isShort ? $this->purchaseQty($shortBy, $factor) : 0,
            ];
        })->values();
    }

    function belt_cutting_list(Request $request)
    {
        $query = BeltCutting::with(['roll.niwar', 'customer_item:id,customer_name', 'items.belt_item:id,product_name,value1,value2'])
            ->orderBy('id', 'desc');

        // Roll -> size traceability: which sizes came out of a given roll.
        if ($request->roll_no != '') {
            $query->whereIn('roll_id', BeltRoll::where('roll_no', 'like', '%' . $request->roll_no . '%')->pluck('id'));
        }
        if ($request->cutting_no != '') {
            $query->where('cutting_no', 'like', '%' . $request->cutting_no . '%');
        }

        $data = $query->paginate(session('records_per_page', 30))->appends($request->all());

        return view('admin.belt_cutting.list', compact('data'));
    }

    function belt_cutting_add(Request $request)
    {
        $rolls = BeltRoll::with('niwar')
            ->where('status', 'open')
            ->where('remaining_mtr', '>', 0)
            ->orderBy('id', 'asc')
            ->get();

        $customer = customers::orderBy('customer_name', 'asc')->get();
        $selectedRoll = $request->roll_id;

        return view('admin.belt_cutting.create', compact('rolls', 'customer', 'selectedRoll'));
    }

    /**
     * Sizes available for a roll, for the size dropdown in each cutting line.
     */
    function belt_cutting_products(Request $request)
    {
        $roll = BeltRoll::with(['niwar.sizeChart', 'niwar.materials.material_item:id,product_name'])->find($request->roll_id);

        if (empty($roll)) {
            return response()->json(['products' => [], 'remaining_mtr' => 0, 'size_chart' => null]);
        }

        // The catalogue runs to hundreds of sized belts, so the dropdown searches
        // rather than rendering the lot into every row.
        $shown = $this->cuttableProducts($roll, $request->search, 50);

        // A keystroke in the dropdown only needs the matching rows. The chart,
        // the fitting bill and the full size list are drawn once when the roll is
        // picked, and working them out again on every letter typed is what made
        // the search feel heavy.
        if ($request->has('search')) {
            return response()->json(['products' => $shown]);
        }

        $all = $this->cuttableProducts($roll);
        $semiProduct = DB::table('product')->where('id', $roll->roll_product_id)->value('product_name');
        $costing = BeltCosting::where('niwar_id', $roll->niwar->id)->first();

        return response()->json([
            'products' => $shown,
            'total_products' => $all->count(),
            'remaining_mtr' => round($roll->remaining_mtr, 2),
            'size_chart' => $this->sizeChartSummary($roll, $all),
            'semi_product' => $semiProduct,
            'belt_family' => optional($this->beltProductFamilyFor($roll))->label,
            'fitting' => $this->fittingPreview($costing),
        ]);
    }

    function belt_cutting_check(Request $request)
    {
        $roll = BeltRoll::with(['niwar.sizeChart', 'niwar.materials.material_item:id,product_name'])->find($request->roll_id);

        if (empty($roll)) {
            return response()->json(['html' => '', 'sufficient' => false]);
        }

        $plan = $this->computeCuttingPlan(
            $roll,
            $request->belt_product ?? [],
            $request->pieces ?? [],
            $request->rejected_pieces ?? [],
            $request->wastage_mtr ?? 0
        );

        if ($plan['items']->isEmpty()) {
            return response()->json(['html' => '', 'sufficient' => false]);
        }

        $balanceColour = $plan['balance'] < 0 ? '#c0392b' : '#27ae60';
        $html = '<div class="alert" style="background:#eef6fd;color:#333 !important;">'
            . '<b>' . $plan['total_pieces'] . '</b> piece(s) cut'
            . ($plan['total_rejected'] > 0
                ? ' &mdash; <b>' . $plan['total_good'] . '</b> good, <b style="color:#c0392b">' . $plan['total_rejected'] . '</b> rejected'
                : '')
            . '<br>Roll meters: <b>' . $plan['cut_meter'] . ' mtr</b> into belts'
            . ($plan['wastage_mtr'] > 0 ? ' + <b>' . $plan['wastage_mtr'] . ' mtr</b> trim wastage' : '')
            . ' = <b>' . $plan['total_meter'] . ' mtr</b> of the roll\'s ' . round($roll->remaining_mtr, 2) . ' mtr'
            . '<br>Usable balance left on the roll: <b style="color:' . $balanceColour . '">' . $plan['balance'] . ' mtr</b>'
            . ' (' . $plan['balance_gram'] . ' g)</div>';

        // Size-wise bifurcation: what each size takes in inch, meter and gram.
        $html .= '<h5>Size-wise Roll Usage</h5>'
            . '<table class="table table-bordered"><tr>'
            . '<th>Belt</th><th>P.P. Size</th><th>Pieces</th>'
            . '<th>Inch / Pc</th><th>Mtr / Pc</th><th>Gm / Pc</th>'
            . '<th>Total Inch</th><th>Total Mtr</th><th>Total Gm</th></tr>';

        foreach ($plan['items'] as $item) {
            $html .= '<tr>'
                . '<td>' . e($item->base_name) . '</td>'
                . '<td style="text-align:center;">' . e($item->size) . '</td>'
                . '<td style="text-align:right;">' . $item->pieces . '</td>'
                . '<td style="text-align:right;">' . $item->required_inch . '"</td>'
                . '<td style="text-align:right;">' . $item->meter_per_piece . '</td>'
                . '<td style="text-align:right;">' . $item->gram_per_piece . ' g</td>'
                . '<td style="text-align:right;">' . $item->total_inch . '"</td>'
                . '<td style="text-align:right;">' . $item->total_meter . '</td>'
                . '<td style="text-align:right;">' . $item->total_gram . ' g</td>'
                . '</tr>';
        }

        $html .= '<tr style="background:#f4f6fa;font-weight:600;">'
            . '<td colspan="2">Total into belts</td>'
            . '<td style="text-align:right;">' . $plan['total_pieces'] . '</td>'
            . '<td colspan="3"></td>'
            . '<td style="text-align:right;">' . round($plan['items']->sum('total_inch'), 2) . '"</td>'
            . '<td style="text-align:right;">' . $plan['cut_meter'] . '</td>'
            . '<td style="text-align:right;">' . $plan['cut_gram'] . ' g</td></tr>';

        if ($plan['wastage_mtr'] > 0) {
            $html .= '<tr><td colspan="7">Trim wastage</td>'
                . '<td style="text-align:right;">' . $plan['wastage_mtr'] . '</td>'
                . '<td style="text-align:right;">' . $plan['wastage_gram'] . ' g</td></tr>'
                . '<tr style="background:#f4f6fa;font-weight:600;"><td colspan="7">Off the roll altogether</td>'
                . '<td style="text-align:right;">' . $plan['total_meter'] . '</td>'
                . '<td style="text-align:right;">' . $plan['total_gram'] . ' g</td></tr>';
        }
        $html .= '</table>';

        // What those meters are made of, per the niwar code's rating.
        if ($plan['dhaga_breakdown']->isNotEmpty()) {
            $html .= '<h5>Dhaga In These Meters <small class="text-muted">(niwar rated '
                . $plan['gm_per_meter'] . ' g per meter)</small></h5>'
                . '<table class="table table-bordered"><tr>'
                . '<th>Dhaga / Category</th><th>Gm / Meter</th><th>In Belts</th>'
                . ($plan['wastage_mtr'] > 0 ? '<th>In Trim Wastage</th><th>Total</th>' : '')
                . '</tr>';

            foreach ($plan['dhaga_breakdown'] as $d) {
                $html .= '<tr><td>' . e($d->name)
                    . ($d->is_group ? ' <span class="label label-info">Composite</span>' : '') . '</td>'
                    . '<td style="text-align:right;">' . $d->gm_per_meter . '</td>'
                    . '<td style="text-align:right;">' . $d->gram_in_belts . ' g</td>'
                    . ($plan['wastage_mtr'] > 0
                        ? '<td style="text-align:right;">' . $d->gram_in_wastage . ' g</td>'
                          . '<td style="text-align:right;"><b>' . $d->gram_total . ' g</b></td>'
                        : '')
                    . '</tr>';
            }

            $html .= '<tr style="background:#f4f6fa;font-weight:600;"><td>Total</td>'
                . '<td style="text-align:right;">' . $plan['gm_per_meter'] . '</td>'
                . '<td style="text-align:right;">' . $plan['cut_gram'] . ' g</td>'
                . ($plan['wastage_mtr'] > 0
                    ? '<td style="text-align:right;">' . $plan['wastage_gram'] . ' g</td>'
                      . '<td style="text-align:right;">' . $plan['total_gram'] . ' g</td>'
                    : '')
                . '</tr></table>';
        }

        if (!$plan['roll_ok']) {
            $html .= '<div class="alert alert-danger">These sizes need more niwar than this roll has left. Reduce the pieces or use another roll.</div>';
        }

        if ($plan['materials']->isNotEmpty()) {
            $html .= '<h5>Fitting Consumed <small class="text-muted">(from this niwar\'s belt costing)</small></h5>'
                . '<table class="table table-bordered"><tr><th>Fitting</th><th>Product</th><th>Required Qty</th><th>Available Stock</th><th>Short By</th></tr>';
            foreach ($plan['materials'] as $m) {
                $rowStyle = $m->is_short ? "style='background:#fdeaea'" : '';
                $html .= "<tr $rowStyle>
                    <td>" . e($m->fitting_label) . "</td>
                    <td>" . e($m->product_name) . "</td>
                    <td style='text-align:right;'>{$m->required_qty} {$m->uom_name}</td>
                    <td style='text-align:right;'>{$m->available_stock} {$m->uom_name}</td>
                    <td>" . ($m->is_short ? "<b style='color:#c0392b'>{$m->short_by} {$m->uom_name} short</b>" : 'OK') . "</td>
                </tr>";
            }
            $html .= '</table>';

            if ($plan['materials']->contains('is_short', true)) {
                $html .= $this->shortageHtml($plan['materials']->where('is_short', true)->values());
            }
        } else {
            $html .= '<div class="alert alert-warning">The belt costing for this niwar names no bukkal, kadi or panni product, '
                . 'so no fitting stock will be consumed. Cutting will still record the pieces and take the meters off the roll. '
                . 'Set the fitting products in Belt Costing to have them deducted.</div>';
        }

        return response()->json([
            'html' => $html,
            'sufficient' => $plan['sufficient'],
            // Only fitting can be bought - a roll that is too short is a matter
            // for the loom, not for the purchase department.
            'can_purchase' => $plan['roll_ok'] && $plan['materials']->contains('is_short', true),
        ]);
    }

    function belt_cutting_store(Request $request)
    {
        $request->validate([
            'roll_id' => 'required|exists:belt_rolls,id',
            'belt_product' => 'required|array|min:1',
            'pieces' => 'required|array|min:1',
            'pieces.*' => 'nullable|integer|min:0|max:100000',
            'rejected_pieces' => 'nullable|array',
            'rejected_pieces.*' => 'nullable|integer|min:0|max:100000',
            'wastage_mtr' => 'nullable|numeric|min:0',
            'customer' => 'nullable|exists:customers,id',
        ]);

        date_default_timezone_set('Asia/Kolkata');

        // A fitting shortage is not a dead end either: it goes to the purchase
        // department exactly as a dhaga shortage does in roll production.
        if ($request->action === 'purchase_request') {
            return $this->raiseFittingPurchaseRequest($request);
        }

        try {
            $result = DB::transaction(function () use ($request) {
                // The roll is locked before its remaining meters are read, so two
                // cutting entries against the same roll cannot both be measured
                // against the same balance and together over-cut it.
                $roll = BeltRoll::lockForUpdate()->find($request->roll_id);
                $roll->load('niwar.sizeChart', 'niwar.materials.material_item:id,product_name');

                if ($roll->status != 'open') {
                    throw new \RuntimeException('Roll ' . $roll->roll_no . ' is ' . $roll->status . ' - pick an open roll.');
                }

                $plan = $this->computeCuttingPlan(
                    $roll,
                    $request->belt_product,
                    $request->pieces,
                    $request->rejected_pieces ?? [],
                    $request->wastage_mtr ?? 0
                );

                if ($plan['items']->isEmpty()) {
                    throw new \RuntimeException('Add at least one size with a piece count.');
                }

                if (!$plan['roll_ok']) {
                    throw new \RuntimeException('These sizes need ' . $plan['total_meter'] . ' mtr but roll '
                        . $roll->roll_no . ' only has ' . round($roll->remaining_mtr, 2) . ' mtr left.');
                }

                // Server-side is the real gate; the AJAX preview is only a convenience.
                if (!$plan['sufficient']) {
                    $shortages = $plan['materials']->where('is_short', true)
                        ->map(fn ($m) => "{$m->product_name} (short by {$m->short_by} {$m->uom_name}, "
                            . "buy {$m->purchase_qty} {$m->stock_uom})")
                        ->implode(', ');

                    throw new \RuntimeException('Insufficient fitting material stock, cutting not saved. Short: ' . $shortages);
                }

                $cutting = new BeltCutting();
                $cutting->cutting_no = $this->nextDocNo('CUT', 'belt_cutting', 'cutting_no');
                $cutting->roll_id = $roll->id;
                $cutting->customer = $request->customer ?: null;
                $cutting->total_pieces = $plan['total_pieces'];
                $cutting->total_rejected_pieces = $plan['total_rejected'];
                $cutting->total_meter_used = $plan['total_meter'];
                $cutting->wastage_mtr = $plan['wastage_mtr'];
                $cutting->balance_mtr = $plan['balance'];
                $cutting->status = 'Y';
                $cutting->timestamp = date('d-m-Y h:i:s a');
                $cutting->user_id = Session::get('user_id');
                $cutting->save();

                foreach ($plan['items'] as $item) {
                    $row = new BeltCuttingItem();
                    $row->belt_cutting_id = $cutting->id;
                    $row->belt_product = $item->belt_product;
                    $row->size = $item->size;
                    $row->pieces = $item->pieces;
                    $row->rejected_pieces = $item->rejected_pieces;
                    $row->meter_per_piece = $item->meter_per_piece;
                    $row->total_meter = $item->total_meter;
                    $row->save();

                    // Only pieces that passed reach finished goods.
                    if ($item->good_pieces > 0) {
                        $this->stockIn(
                            $item->belt_product,
                            $item->good_pieces,
                            'Inward From Belt Cutting ' . $cutting->cutting_no . ' (Roll ' . $roll->roll_no . ')'
                        );
                    }
                }

                foreach ($plan['materials'] as $m) {
                    $row = new BeltCuttingMaterial();
                    $row->belt_cutting_id = $cutting->id;
                    $row->material = $m->material;
                    $row->required_qty = $m->required_qty;
                    $row->stock_factor = $m->stock_factor;
                    $row->avalible_stock = $m->available_stock;
                    $row->timestamp = date('d-m-Y h:i:s a');
                    $row->user_id = Session::get('user_id');
                    $row->save();

                    $this->stockOut($m->material, $m->required_qty / $m->stock_factor, 'Used in Belt Cutting ' . $cutting->cutting_no);
                }

                // Belts and trim both leave the roll product's meter stock.
                $this->stockOut(
                    $roll->roll_product_id,
                    $plan['total_meter'],
                    'Cut in Belt Cutting ' . $cutting->cutting_no . ' (Roll ' . $roll->roll_no . ')'
                );

                $roll->remaining_mtr = $plan['balance'];
                $roll->scrap_mtr = round(($roll->scrap_mtr ?? 0) + $plan['wastage_mtr'], 2);
                if ($roll->remaining_mtr <= 0.005) {
                    $roll->status = 'consumed';
                    $roll->remaining_mtr = 0;
                }
                $roll->save();

                return compact('cutting', 'plan');
            });
        } catch (\RuntimeException $e) {
            return back()->withInput()->with('error', $e->getMessage());
        }

        $plan = $result['plan'];
        $message = 'Cutting saved: ' . $result['cutting']->cutting_no . ' - ' . $plan['total_good'] . ' belt(s) added to stock';
        if ($plan['total_rejected'] > 0) {
            $message .= ', ' . $plan['total_rejected'] . ' rejected';
        }

        return redirect()->route('admin.belt_cutting.list')->with('message', $message);
    }

    /**
     * Sends the short bukkal / kadi / panni of this cutting plan to purchase.
     *
     * The finished belt recorded on the requirement is the first size in the
     * plan - a cut can make several sizes of the same belt, and the purchase
     * department only needs to know what the fitting is being bought for.
     */
    private function raiseFittingPurchaseRequest(Request $request)
    {
        $roll = BeltRoll::with(['niwar.sizeChart', 'niwar.materials.material_item:id,product_name'])
            ->find($request->roll_id);

        if (empty($roll)) {
            return back()->withInput()->with('error', 'Pick a roll first.');
        }

        $plan = $this->computeCuttingPlan(
            $roll,
            $request->belt_product,
            $request->pieces,
            $request->rejected_pieces ?? [],
            $request->wastage_mtr ?? 0
        );

        $short = $plan['materials']->where('is_short', true)->values();

        if ($short->isEmpty()) {
            return back()->withInput()->with('error', 'No fitting material is short - nothing to request.');
        }

        $requirement = $this->raiseBeltPurchaseRequest(
            $short,
            optional($plan['items']->first())->belt_product,
            $request->customer
        );

        return redirect()->route('admin.belt_cutting.list')
            ->with('message', $this->purchaseRequestMessage($requirement, $short->count()));
    }

    /**
     * Unwinds a cutting entry: belts back out of finished stock, fitting material
     * back on the shelf, and the meters returned to the roll.
     */
    function belt_cutting_cancel(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:belt_cutting,id',
            'cancel_reason' => 'required|string|max:255',
        ]);

        date_default_timezone_set('Asia/Kolkata');

        try {
            $cutting = DB::transaction(function () use ($request) {
                $cutting = BeltCutting::with(['items', 'materials'])->lockForUpdate()->find($request->id);

                if ($cutting->status == 'C') {
                    throw new \RuntimeException('This cutting entry is already cancelled.');
                }

                $roll = BeltRoll::lockForUpdate()->find($cutting->roll_id);

                if (empty($roll)) {
                    throw new \RuntimeException('The roll this cutting came from no longer exists.');
                }
                if ($roll->status == 'cancelled') {
                    throw new \RuntimeException('Roll ' . $roll->roll_no . ' has been voided, so this cutting cannot be reversed onto it.');
                }

                foreach ($cutting->items as $item) {
                    $good = $item->pieces - ($item->rejected_pieces ?? 0);
                    if ($good > 0) {
                        $this->stockOut($item->belt_product, $good, 'Reversal of Belt Cutting ' . $cutting->cutting_no);
                    }
                }

                foreach ($cutting->materials as $material) {
                    $this->stockIn(
                        $material->material,
                        $material->required_qty / ($material->stock_factor ?: 1),
                        'Reversal of Belt Cutting ' . $cutting->cutting_no
                    );
                }

                $this->stockIn($roll->roll_product_id, $cutting->total_meter_used, 'Reversal of Belt Cutting ' . $cutting->cutting_no);

                // The meters go back onto the roll, which becomes usable again
                // unless it was written off in the meantime.
                $roll->remaining_mtr = round($roll->remaining_mtr + $cutting->total_meter_used, 2);
                $roll->scrap_mtr = round(max(($roll->scrap_mtr ?? 0) - ($cutting->wastage_mtr ?? 0), 0), 2);
                if ($roll->status == 'consumed') {
                    $roll->status = 'open';
                }
                $roll->save();

                $cutting->status = 'C';
                $cutting->cancelled_by = Session::get('user_id');
                $cutting->cancelled_at = now();
                $cutting->cancel_reason = $request->cancel_reason;
                $cutting->save();

                return $cutting;
            });
        } catch (\RuntimeException $e) {
            return back()->with('error', $e->getMessage());
        }

        return redirect()->route('admin.belt_cutting.list')
            ->with('message', 'Cutting ' . $cutting->cutting_no . ' cancelled - belts removed from stock and the meters returned to the roll.');
    }

    /**
     * The tail end of a roll is usually too short for any size. Closing writes it
     * off as scrap and takes the meters out of roll stock, instead of leaving
     * unusable meters on the books forever.
     */
    function belt_cutting_close_roll(Request $request)
    {
        $request->validate(['roll_id' => 'required|exists:belt_rolls,id']);

        try {
            $result = DB::transaction(function () use ($request) {
                $roll = BeltRoll::lockForUpdate()->find($request->roll_id);

                if ($roll->status != 'open') {
                    throw new \RuntimeException('Roll ' . $roll->roll_no . ' is already closed.');
                }

                $balance = round($roll->remaining_mtr, 2);

                if ($balance > 0) {
                    $this->stockOut($roll->roll_product_id, $balance, 'Roll ' . $roll->roll_no . ' closed - end piece scrap');
                }

                $roll->scrap_mtr = round(($roll->scrap_mtr ?? 0) + $balance, 2);
                $roll->remaining_mtr = 0;
                $roll->status = 'scrap';
                $roll->save();

                return compact('roll', 'balance');
            });
        } catch (\RuntimeException $e) {
            return back()->with('error', $e->getMessage());
        }

        return back()->with('message', 'Roll ' . $result['roll']->roll_no . ' closed, ' . $result['balance'] . ' mtr written off as scrap');
    }

    /**
     * Roll -> size traceability: which sizes came out of a roll and how the
     * roll's meters were accounted for.
     */
    function belt_cutting_detail(Request $request)
    {
        $cutting = BeltCutting::with([
            'roll.niwar',
            'roll.roll_production:id,batch_no,roll_formula_id,roll_formula_version',
            'customer_item:id,customer_name',
            'items.belt_item:id,product_name,value1,value2',
            'materials.material_item:id,product_name',
        ])->find($request->id);

        if (empty($cutting)) {
            return response()->json(['html' => '<div class="alert alert-danger">Cutting entry not found.</div>']);
        }

        // Full chain back from the finished belt to the batch it was woven in.
        $roll = $cutting->roll;
        $html = '<p><b>' . e($cutting->cutting_no) . '</b>'
            . ($cutting->status == 'C' ? ' <span class="label label-danger">Cancelled</span>' : '')
            . '<br><small class="text-muted">Roll ' . e($roll->roll_no ?? '-')
            . ' &larr; batch ' . e($roll->roll_production->batch_no ?? '-')
            . ($roll && $roll->roll_production && $roll->roll_production->roll_formula_version
                ? ' (formula v' . $roll->roll_production->roll_formula_version . ')' : '')
            . ' &larr; ' . e($roll->niwar->label ?? '-') . '</small></p>';

        if ($cutting->status == 'C') {
            $html .= '<div class="alert alert-danger">Cancelled: ' . e($cutting->cancel_reason) . '</div>';
        }

        $html .= '<table class="table table-bordered"><tr><th>Size</th><th>Cut</th><th>Rejected</th><th>Good</th><th>Mtr/Piece</th><th>Total Mtr</th></tr>';
        foreach ($cutting->items as $item) {
            $name = product::nameWithVariantInline(
                $item->belt_item->product_name ?? '-',
                $item->belt_item->value1 ?? null,
                $item->belt_item->value2 ?? null
            );
            $rejected = $item->rejected_pieces ?? 0;
            $html .= '<tr><td>' . e($name) . '</td><td style="text-align:right;">' . $item->pieces . '</td>'
                . '<td style="text-align:right;">' . ($rejected ?: '-') . '</td>'
                . '<td style="text-align:right;"><b>' . ($item->pieces - $rejected) . '</b></td>'
                . '<td style="text-align:right;">' . $item->meter_per_piece . '</td>'
                . '<td style="text-align:right;">' . $item->total_meter . '</td></tr>';
        }
        $html .= '</table>';

        $html .= '<p><b>Roll meters:</b> ' . round($cutting->total_meter_used - ($cutting->wastage_mtr ?? 0), 2)
            . ' mtr into belts, ' . round($cutting->wastage_mtr ?? 0, 2) . ' mtr trim wastage, '
            . round($cutting->balance_mtr, 2) . ' mtr left on the roll.</p>';

        if ($cutting->materials->isNotEmpty()) {
            $html .= '<h5>Fitting Material Used</h5><table class="table table-bordered"><tr><th>Material</th><th>Qty</th></tr>';
            foreach ($cutting->materials as $m) {
                $name = $m->material_item->product_name ?? ('Material #' . $m->material);
                $html .= '<tr><td>' . e($name) . '</td><td style="text-align:right;">' . $m->required_qty . '</td></tr>';
            }
            $html .= '</table>';
        }

        return response()->json(['html' => $html]);
    }
}
