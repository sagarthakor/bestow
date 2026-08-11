<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\RollFormulaCategoryTotals;
use App\NiwarCode;
use App\NiwarTypeMaterial;
use App\product;
use App\RollFormulaMst;
use App\RollFormulaMstItem;
use App\RollFormulaRevision;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Session;

/**
 * Roll Formula master: how one meter of a size-less semi product (e.g. "NAVY 3
 * PATTI ROLL") is made.
 *
 * The niwar code fixes the categories and their gm per meter - Mono 9.22 and
 * Roto 12.1 on a PP niwar, no Mono at all on a cotton one. This screen picks the
 * actual raw materials filling each category, and their gm per meter must add up
 * to the category's total exactly. Roll Production then just multiplies by the
 * meters being woven.
 *
 * Every save that changes the numbers bumps the version and freezes the previous
 * state into roll_formula_revision, so a batch woven on V1 can still be shown as
 * it was woven even after the recipe has moved on to V3.
 */
class RollFormulaController extends Controller
{
    use RollFormulaCategoryTotals;

    function roll_formula_list(Request $request)
    {
        $query = RollFormulaMst::with(['product_item:id,product_name,item_code', 'niwar'])
            ->withCount('items')
            ->orderBy('id', 'desc');

        if ($request->search != '') {
            $query->whereIn('product', product::where('product_name', 'like', '%' . $request->search . '%')->pluck('id'));
        }
        if ($request->status != '') {
            $query->where('status', $request->status);
        }

        $data = $query->paginate(session('records_per_page', 30))->appends($request->all());

        return view('admin.roll_formula.list', compact('data'));
    }

    function roll_formula_add(Request $request)
    {
        return view('admin.roll_formula.create', $this->formData());
    }

    function roll_formula_store(Request $request)
    {
        $request->validate([
            'niwar_code_id' => 'required|exists:niwar_codes,id',
            'belt_product_id' => 'required|exists:product,id',
            'material' => 'required|array|min:1',
            'gm_per_meter' => 'required|array|min:1',
            'effective_date' => 'nullable|date',
            'notes' => 'nullable|string|max:1000',
        ]);

        $rows = $this->collectRows($request);
        $this->guardCategoryTotals($request->niwar_code_id, $rows);

        return DB::transaction(function () use ($request, $rows) {
            $productId = $this->resolveSemiProduct($request);

            if (RollFormulaMst::where('product', $productId)->exists()) {
                throw ValidationException::withMessages([
                    'belt_product_id' => ['This belt already has a roll formula on this niwar code. Edit that one instead.'],
                ]);
            }

            $formula = new RollFormulaMst();
            $formula->product = $productId;
            $formula->belt_product_id = $request->belt_product_id ?: null;
            $formula->niwar_code_id = $request->niwar_code_id;
            $formula->version = 1;
            $formula->status = 'active';
            $formula->effective_date = $request->effective_date ?: date('Y-m-d');
            $formula->notes = $request->notes;
            $formula->user_id = Session::get('user_id');
            $formula->save();

            $this->saveItems($formula, $rows);
            $this->freezeRevision($formula, $rows, 'Initial version.');

            return redirect()->route('admin.roll_formula.list')
                ->with('message', 'Roll formula created successfully (version 1)');
        });
    }

    function roll_formula_edit(Request $request)
    {
        $data = RollFormulaMst::with(['product_item:id,product_name', 'belt_product_item:id,product_name,item_code,value1,value2', 'items'])->findOrFail($request->id);
        $usedInBatches = DB::table('belt_roll_production')->where('roll_formula_id', $data->id)->count();

        return view('admin.roll_formula.edit', $this->formData() + compact('data', 'usedInBatches'));
    }

    function roll_formula_update(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:roll_formula_mst,id',
            'niwar_code_id' => 'required|exists:niwar_codes,id',
            'material' => 'required|array|min:1',
            'gm_per_meter' => 'required|array|min:1',
            'belt_product_id' => 'nullable|exists:product,id',
            'status' => 'nullable|in:active,inactive',
            'effective_date' => 'nullable|date',
            'notes' => 'nullable|string|max:1000',
        ]);

        $rows = $this->collectRows($request);
        $this->guardCategoryTotals($request->niwar_code_id, $rows);

        return DB::transaction(function () use ($request, $rows) {
            $formula = RollFormulaMst::with('items')->lockForUpdate()->find($request->id);

            // Nothing about a batch that has already been woven may move, so a
            // changed recipe becomes a new version rather than an edit in place.
            $changed = RollFormulaRevision::fingerprint($formula->niwar_code_id, $formula->itemRows())
                !== RollFormulaRevision::fingerprint($request->niwar_code_id, $rows);

            $formula->niwar_code_id = $request->niwar_code_id;
            $formula->belt_product_id = $request->belt_product_id ?: null;
            $formula->status = $request->status ?: 'active';
            $formula->effective_date = $request->effective_date ?: $formula->effective_date;
            $formula->notes = $request->notes;
            $formula->user_id = Session::get('user_id');

            if ($changed) {
                $formula->version = $formula->version + 1;
            }
            $formula->save();

            RollFormulaMstItem::where('roll_formula_id', $formula->id)->delete();
            $this->saveItems($formula, $rows);

            if ($changed) {
                $this->freezeRevision($formula, $rows, $request->notes);
            }

            return redirect()->route('admin.roll_formula.list')->with(
                'message',
                $changed
                    ? 'Roll formula updated and saved as version ' . $formula->version
                      . '. Batches already woven keep the version they used.'
                    : 'Roll formula saved - the recipe itself did not change, so it stays on version ' . $formula->version . '.'
            );
        });
    }

    function roll_formula_delete(Request $request)
    {
        $inUse = DB::table('belt_roll_production')->where('roll_formula_id', $request->id)->exists();

        if ($inUse) {
            return redirect()->route('admin.roll_formula.list')
                ->with('error', 'This roll formula has already been used in a roll batch, so it cannot be deleted. '
                    . 'Set it to Inactive instead - that keeps it off new batches without touching history.');
        }

        DB::transaction(function () use ($request) {
            RollFormulaMstItem::where('roll_formula_id', $request->id)->delete();
            RollFormulaRevision::where('roll_formula_id', $request->id)->delete();
            RollFormulaMst::where('id', $request->id)->delete();
        });

        return redirect()->route('admin.roll_formula.list')->with('message', 'Roll formula deleted');
    }

    /**
     * Version history of one formula - what each version's recipe was and which
     * batches were woven on it.
     */
    function roll_formula_revisions(Request $request)
    {
        $formula = RollFormulaMst::with(['product_item:id,product_name', 'niwar', 'revisions'])->find($request->id);

        if (empty($formula)) {
            return response()->json(['html' => '<div class="alert alert-danger">Roll formula not found.</div>']);
        }

        $batchesByVersion = DB::table('belt_roll_production')
            ->where('roll_formula_id', $formula->id)
            ->groupBy('roll_formula_version')
            ->select('roll_formula_version', DB::raw('COUNT(*) as batches'), DB::raw('SUM(planned_mtr) as mtr'))
            ->get()
            ->keyBy('roll_formula_version');

        $html = '<p><b>' . e($formula->product_item->product_name ?? '-') . '</b> &mdash; '
            . e($formula->niwar->label ?? '') . ', currently on version ' . $formula->version . '</p>';

        foreach ($formula->revisions as $revision) {
            $used = $batchesByVersion[$revision->version] ?? null;

            $html .= '<div style="border:1px solid #ddd;padding:10px;margin-bottom:10px;">'
                . '<b>Version ' . $revision->version . '</b> '
                . ($revision->version == $formula->version ? '<span class="label label-success">Current</span>' : '')
                . ' <small class="text-muted">saved ' . e(optional($revision->created_at)->format('d-m-Y H:i')) . '</small>'
                . '<br><small>' . ($used
                    ? 'Used by <b>' . $used->batches . '</b> batch(es), ' . round($used->mtr, 2) . ' mtr planned'
                    : 'Not used by any batch') . '</small>';

            if ($revision->notes) {
                $html .= '<br><small class="text-muted">' . e($revision->notes) . '</small>';
            }

            $html .= '<table class="table table-bordered" style="margin-top:8px;margin-bottom:0;">'
                . '<tr><th>Category</th><th>Raw Material</th><th>Gm / Meter</th></tr>';
            foreach ($revision->itemsWithNames() as $item) {
                $html .= '<tr><td>' . e($item->category_name) . '</td><td>' . e($item->material_name) . '</td>'
                    . '<td style="text-align:right;">' . $item->gm_per_meter . '</td></tr>';
            }
            $html .= '</table></div>';
        }

        return response()->json(['html' => $html]);
    }

    /**
     * Categories of a niwar code with their fixed gm per meter, for the create /
     * edit screen to build one material table per category.
     */
    function roll_formula_categories(Request $request)
    {
        $categories = NiwarTypeMaterial::with('material_item:id,product_name')
            ->where('niwar_code_id', $request->niwar_code_id)
            ->get()
            ->map(fn ($c) => [
                'id' => $c->id,
                'name' => $c->material_item->product_name ?? ('Material #' . $c->material),
                'gm_per_meter' => (float) $c->gm_per_meter,
                'is_group' => (int) $c->is_group,
            ]);

        return response()->json(['categories' => $categories]);
    }

    /**
     * Finished belt products for the "which belt does this roll become" picker.
     *
     * One row per product family rather than per size variant: the size on the
     * pick is meaningless to a roll, so offering the same belt twenty times over
     * would only invite the wrong conclusion. The id returned is a representative
     * variant; cutting resolves the family from it.
     */
    function roll_formula_belt_products(Request $request)
    {
        $beltCategoryId = DB::table('category')->where('category_name', 'Belt')->value('id');

        // Grouped by name *and* item code: two belts can share a name and differ
        // only by code (BRASS_COTTON vs BRASS_MALAI), and they are not the same
        // product. Sizes within a group are what cutting will offer.
        $query = DB::table('product')
            ->where('status', 'product')
            ->select(DB::raw('MIN(id) as id'), 'product_name', 'item_code', DB::raw('COUNT(*) as size_count'))
            ->groupBy('product_name', 'item_code')
            ->orderBy('product_name')
            ->orderBy('item_code')
            ->limit(50);

        if ($beltCategoryId) {
            $query->where('category', $beltCategoryId);
        }

        // Matched word by word, like the product picker on the document screens:
        // every word typed has to appear somewhere on the belt, in any order, so
        // the operator can type the item code, part of the name and a size
        // together.
        //
        // Matched against the family as a whole - its name and code plus every
        // size and colour it comes in - rather than against a single row. A row
        // kept only because its own variant is size 30 would report "1 size"
        // instead of the 13 the belt really has, and asking the question per row
        // (one subquery each) is what made this search crawl.
        $searchable = "CONCAT_WS(' ',"
            . " product.product_name,"
            . " COALESCE(product.item_code, ''),"
            . " GROUP_CONCAT(DISTINCT COALESCE(product.sku, '') SEPARATOR ' '),"
            . " GROUP_CONCAT(DISTINCT COALESCE(product.value2, '') SEPARATOR ' '),"
            . " GROUP_CONCAT(DISTINCT COALESCE(product.value1, '') SEPARATOR ' ')"
            . ')';

        foreach (preg_split('/\s+/', trim((string) $request->search), -1, PREG_SPLIT_NO_EMPTY) ?: [] as $word) {
            $like = '%' . str_replace(['%', '_'], ['\%', '\_'], $word) . '%';

            $query->havingRaw("$searchable LIKE ?", [$like]);
        }

        return response()->json([
            'products' => $query->get()->map(fn ($p) => [
                'id' => $p->id,
                'name' => $p->product_name,
                'item_code' => $p->item_code,
                'size_count' => $p->size_count,
            ]),
        ]);
    }

    private function formData(): array
    {
        return [
            'niwarCodes' => NiwarCode::orderBy('type', 'asc')->get(),
            'rawmaterial' => product::with('uomName')->orderBy('product_name', 'asc')->where('status', 'raw material')->get(),
            'semiProducts' => product::where('status', 'semi finished')->orderBy('product_name', 'asc')->get(),
        ];
    }

    /**
     * The screen either picks an existing semi product or types a name for a new
     * one. There is no other way to create a size-less semi-finished product in
     * the system, so it is created here on the spot.
     */
    private function resolveSemiProduct(Request $request)
    {
        $belt = product::find($request->belt_product_id);

        if (empty($belt)) {
            throw ValidationException::withMessages([
                'belt_product_id' => ['Pick the belt product this roll becomes.'],
            ]);
        }

        $niwar = NiwarCode::find($request->niwar_code_id);
        $name = $this->semiProductName($belt, $niwar);

        $existing = product::where('product_name', $name)->where('status', 'semi finished')->value('id');
        if ($existing) {
            return $existing;
        }

        return DB::table('product')->insertGetId([
            'product_name' => $name,
            'slug' => Str::slug($name),
            'item_code' => 'ROLL-' . substr(strtoupper(Str::slug($name, '')), 0, 40),
            'uom' => $this->meterUomId(),
            'category' => $this->rollCategoryId(),
            'category_name' => 'Niwar Roll',
            'status' => 'semi finished',
            'website_id' => 1,
            'show_hide' => 'hide',
            'price_show_hide' => 'hide',
            'created_time' => date('d-m-Y h:i:s a'),
        ]);
    }

    /**
     * The roll's own stock name, built from the belt it becomes and the niwar it
     * is woven on - the two things that actually distinguish one roll from
     * another. Nobody types it: a roll is an internal, size-less semi product and
     * asking for a name only invited two names for the same thing.
     *
     * The item code is part of it because two belts can share a name and differ
     * only by code, and those are different rolls.
     */
    private function semiProductName(product $belt, ?NiwarCode $niwar): string
    {
        $code = trim((string) $belt->item_code);

        return trim(
            trim($belt->product_name)
            . ($code === '' ? '' : ' [' . $code . ']')
            . ' ROLL'
            . ($niwar ? ' - ' . $niwar->label : '')
        );
    }

    /**
     * Posted rows as {niwar_type_material_id, material, gm_per_meter}, blanks
     * dropped so validation, saving and the revision snapshot always see the
     * same set in the same shape.
     */
    private function collectRows(Request $request): array
    {
        $rows = [];

        foreach ($request->material ?? [] as $i => $materialId) {
            $gm = $request->gm_per_meter[$i] ?? '';
            $category = $request->category[$i] ?? '';

            if ($materialId == '' || $gm === '' || $category === '') {
                continue;
            }

            if (!is_numeric($gm) || (float) $gm <= 0) {
                throw ValidationException::withMessages([
                    'material' => ['Every raw material row needs a gm/meter greater than zero.'],
                ]);
            }

            $rows[] = [
                'niwar_type_material_id' => (int) $category,
                'material' => (int) $materialId,
                'gm_per_meter' => (float) $gm,
            ];
        }

        if (empty($rows)) {
            throw ValidationException::withMessages([
                'material' => ['Add at least one raw material with its gm/meter.'],
            ]);
        }

        return $rows;
    }

    private function guardCategoryTotals($niwarCodeId, array $rows)
    {
        $errors = $this->categoryTotalErrors($niwarCodeId, $rows);

        if ($errors) {
            throw ValidationException::withMessages(['material' => $errors]);
        }
    }

    private function saveItems(RollFormulaMst $formula, array $rows)
    {
        foreach ($rows as $row) {
            $item = new RollFormulaMstItem();
            $item->roll_formula_id = $formula->id;
            $item->niwar_type_material_id = $row['niwar_type_material_id'];
            $item->material = $row['material'];
            $item->gm_per_meter = $row['gm_per_meter'];
            $item->save();
        }
    }

    /**
     * Freezes the recipe exactly as saved, so the version a batch records can
     * always be read back even after the master has moved on.
     */
    private function freezeRevision(RollFormulaMst $formula, array $rows, $notes)
    {
        $revision = new RollFormulaRevision();
        $revision->roll_formula_id = $formula->id;
        $revision->version = $formula->version;
        $revision->niwar_code_id = $formula->niwar_code_id;
        $revision->items = $rows;
        $revision->effective_date = $formula->effective_date;
        $revision->notes = $notes;
        $revision->user_id = Session::get('user_id');
        $revision->save();
    }

    private function meterUomId()
    {
        $id = DB::table('uom')->whereRaw('LOWER(uom_name) IN (?, ?)', ['meter', 'mtr'])->value('id');

        return $id ?: DB::table('uom')->insertGetId(['uom_name' => 'Meter', 'website_id' => 1, 'user_id' => 1]);
    }

    private function rollCategoryId()
    {
        $id = DB::table('category')->where('category_name', 'Niwar Roll')->value('id');

        return $id ?: DB::table('category')->insertGetId([
            'category_name' => 'Niwar Roll',
            'slug' => 'niwar-roll',
            'website_id' => 1,
            'user_id' => 1,
        ]);
    }
}
