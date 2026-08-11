<?php

namespace App\Http\Controllers\Concerns;

use App\stock_book;
use App\stock_status;
use Illuminate\Support\Facades\DB;
use LogicException;
use Session;

/**
 * Stock in/out for the two-stage belt flow. Roll production, cutting, the roll
 * close-off and every reversal move stock the same way, so the stock_status
 * snapshot and the stock_book ledger stay in step in one place instead of six.
 *
 * Every mutating helper here must run inside a transaction: a belt operation
 * touches several products plus its own documents, and a half-applied one leaves
 * stock that no document explains. The helpers refuse to run outside one rather
 * than trusting each caller to remember.
 */
trait BeltStockMovement
{
    /** materialConversionFactor() results for this request. */
    private $conversionFactorCache = [];

    /**
     * Formula quantities for KG-tracked materials are written in grams, while
     * stock is held in KG - same convention as ProductionController.
     *
     * Looked up for every material of every line, so the whole set is fetched
     * once per request instead of one query per line.
     */
    protected function materialConversionFactor($materialId)
    {
        $materialId = (int) $materialId;

        if (!array_key_exists($materialId, $this->conversionFactorCache)) {
            $this->cacheConversionFactors([$materialId]);
        }

        return $this->conversionFactorCache[$materialId];
    }

    /**
     * Warms the factor cache for a whole set of materials in a single query.
     */
    protected function cacheConversionFactors(array $materialIds)
    {
        $missing = array_values(array_diff(
            array_map('intval', array_unique($materialIds)),
            array_keys($this->conversionFactorCache)
        ));

        if (empty($missing)) {
            return;
        }

        $uoms = DB::table('product')
            ->leftJoin('uom', 'uom.id', '=', 'product.uom')
            ->whereIn('product.id', $missing)
            ->pluck('uom.uom_name', 'product.id');

        foreach ($missing as $id) {
            $this->conversionFactorCache[$id] = strtoupper((string) ($uoms[$id] ?? '')) === 'KG' ? 1000 : 1;
        }
    }

    /**
     * Requirement and stock are compared in the formula's own unit - grams, for
     * KG-tracked dhaga - where a hundredth is far below anything the floor can
     * weigh out. Without a tolerance, a leftover that small reads on screen as
     * "out of stock" and blocks a batch whose material is really on the shelf.
     */
    const STOCK_TOLERANCE = 0.01;

    /**
     * Is this material genuinely short, or only short by less than the scale can
     * measure? Both quantities are in the requirement's own unit.
     */
    protected function isShort($requiredQty, $availableQty): bool
    {
        return round($requiredQty - $availableQty, 2) > self::STOCK_TOLERANCE;
    }

    /**
     * What to actually ask the purchase department for, in the material's own
     * stock unit, given a shortfall in the requirement's unit.
     *
     * Always rounded *up*, never to nearest: a shortfall of 462.2 g is 0.4622 KG,
     * and a purchase order raised - or received - for 0.46 KG leaves production
     * still short, which is exactly the case where the request looks like it
     * never worked. Two decimals is the precision the purchase and inward
     * screens are worked in, so the figure asked for is one a person can type
     * back unchanged.
     */
    protected function purchaseQty($shortBy, $factor)
    {
        $inStockUnit = max((float) $shortBy, 0) / ($factor ?: 1);

        // Rounded before the ceiling, so a quantity that is already exact is not
        // bumped up a whole step by float representation alone.
        return max(ceil(round($inStockUnit * 100, 6)) / 100, 0.01);
    }

    /**
     * The most recent purchase requirement raised for each of these materials,
     * with the purchase order it became and what has been received against it.
     *
     * Shown next to a shortage so "but I already requested that material" is a
     * question the screen answers - short by 20 g against a request for 0.02 KG
     * that never became a purchase order is a different problem from one that
     * was ordered and received.
     */
    protected function lastPurchaseRequests(array $materialIds)
    {
        if (empty($materialIds)) {
            return collect();
        }

        return DB::table('purchase_required_material as prm')
            ->join('purchase_requirement as pr', 'pr.id', '=', 'prm.order_id')
            ->leftJoin('purchase_item as pi', function ($join) {
                $join->on('pi.pono', '=', 'pr.po_no')->on('pi.product', '=', 'prm.raw_material');
            })
            ->whereIn('prm.raw_material', $materialIds)
            ->orderBy('prm.id', 'desc')
            ->select(
                'prm.raw_material',
                'prm.qty as requested_qty',
                'pr.order_no',
                'pr.date',
                'pr.po_no',
                'pi.qty as ordered_qty',
                'pi.received_qty'
            )
            ->get()
            // One row per material - the newest, which is the one being asked about.
            ->unique('raw_material')
            ->keyBy('raw_material');
    }

    /**
     * Take qty (already in the product's own stock unit) out of stock.
     */
    protected function stockOut($productId, $qty, $particular)
    {
        return $this->moveStock($productId, -abs($qty), $particular);
    }

    /**
     * Put qty (in the product's own stock unit) into stock.
     */
    protected function stockIn($productId, $qty, $particular)
    {
        return $this->moveStock($productId, abs($qty), $particular);
    }

    /**
     * Signed move - positive in, negative out. Used where the direction is only
     * known at runtime, such as an actual-consumption variance that may go either
     * way, or a reversal undoing whatever the original did.
     *
     * A zero move writes nothing: an untouched material has no place in the
     * ledger, and a "0 qty" row only makes the stock book harder to read.
     */
    protected function moveStock($productId, $qty, $particular)
    {
        $this->assertInTransaction();

        $qty = round((float) $qty, 4);

        if ($qty == 0.0) {
            return null;
        }

        // Locked for the rest of the transaction, so two operations touching the
        // same dhaga cannot both read the old balance and each write their own
        // total over the other's.
        $stock = stock_status::where('product', $productId)->lockForUpdate()->first();

        if (empty($stock)) {
            $stock = new stock_status();
            $stock->product = $productId;
            $stock->qty = 0;
        }

        $stock->qty = round($stock->qty + $qty, 4);
        $stock->inward_date = date('Y-m-d');
        $stock->particular = $particular;
        $stock->created_time = date('d-m-Y h:i:s a');
        $stock->user_id = Session::get('user_id');
        $stock->save();

        $book = new stock_book();
        $book->product = $productId;
        $book->inward_date = date('Y-m-d');
        $book->inward_qty = $qty > 0 ? $qty : null;
        $book->outward_qty = $qty < 0 ? abs($qty) : null;
        $book->remaining_qty = $stock->qty;
        $book->particular = $particular;
        $book->created_time = date('d-m-Y h:i:s a');
        $book->user_id = Session::get('user_id');
        $book->save();

        return $stock->qty;
    }

    /**
     * Financial-year suffixed document number, matching the BELT-0001_25-26
     * format already used by belt production batches.
     *
     * Numbered from the documents themselves rather than from max(id), and read
     * under a lock inside the caller's transaction, so two batches saved in the
     * same instant cannot be handed the same number. The unique index on the
     * column is the backstop if they somehow are.
     */
    protected function nextDocNo($prefix, $table, $column = null)
    {
        $this->assertInTransaction();

        $year = date('y');
        $suffix = '_' . $year . '-' . ($year + 1);

        return $prefix . '-' . $this->nextSequence($table, $column ?: 'doc_no', $prefix . '-', $suffix, 4) . $suffix;
    }

    /**
     * Next zero-padded sequence for documents whose number starts with $prefix
     * and ends with $suffix. Zero padding makes the lexical max the numeric max.
     */
    protected function nextSequence($table, $column, $prefix, $suffix, $pad)
    {
        $this->assertInTransaction();

        $last = DB::table($table)
            ->where($column, 'like', $prefix . '%' . $suffix)
            ->lockForUpdate()
            ->max($column);

        $sequence = $last ? (int) substr($last, strlen($prefix), $pad) : 0;

        return str_pad($sequence + 1, $pad, '0', STR_PAD_LEFT);
    }

    /**
     * Guards against a helper being called from a path that forgot to open a
     * transaction - the failure mode that leaves stock moved with no document.
     */
    protected function assertInTransaction()
    {
        if (DB::transactionLevel() < 1) {
            throw new LogicException('Belt stock movements must run inside a database transaction.');
        }
    }
}
