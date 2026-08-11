<?php

namespace App\Http\Controllers\Concerns;

use App\purchase_required_material;
use App\purchase_requirement;
use Illuminate\Support\Facades\DB;
use Session;

/**
 * A material shortage in the belt flow is not a dead end: it goes to the
 * purchase department as a requirement, the same route sock production takes
 * from machine allocation, and the batch is started once the stock arrives.
 *
 * Both belt stages raise them - roll production for dhaga, cutting for the
 * bukkal/kadi/panni fitting - and they must agree on what is asked for, or a
 * request that was received in full still reads as short afterwards.
 */
trait BeltPurchaseRequest
{
    use BeltStockMovement;

    /**
     * One requirement covering every short material.
     *
     * Only the shortfall is sent - what stock already covers does not need
     * buying - converted into the material's own stock unit, since that is what
     * the purchase and inward screens work in, and rounded up so that receiving
     * exactly what was asked for always clears the shortage.
     *
     * @param  \Illuminate\Support\Collection  $short  material, short_by, stock_factor
     */
    protected function raiseBeltPurchaseRequest($short, $finishProduct = null, $customer = null): purchase_requirement
    {
        date_default_timezone_set('Asia/Kolkata');

        return DB::transaction(function () use ($short, $finishProduct, $customer) {
            $requirement = new purchase_requirement();
            $requirement->date = date('Y-m-d');
            $requirement->timestamp = date('d-m-Y h:i:s a');
            $requirement->order_no = (purchase_requirement::lockForUpdate()->max('order_no') ?? 0) + 1;
            $requirement->module = 'belt';
            $requirement->finish_product = $finishProduct;
            $requirement->customer = $customer ?: null;
            $requirement->user_id = Session::get('user_id');
            $requirement->save();

            // Grouped by material: the same dhaga can sit in two categories of one
            // formula, and two lines for it on the purchase order only invite half
            // of it to be ordered.
            foreach ($short->groupBy('material') as $materialId => $lines) {
                $row = new purchase_required_material();
                $row->order_id = $requirement->id;
                $row->raw_material = $materialId;
                $row->qty = $this->purchaseQty($lines->sum('short_by'), $lines->first()->stock_factor);
                $row->timestamp = date('d-m-Y h:i:s a');
                $row->user_id = Session::get('user_id');
                $row->save();
            }

            return $requirement;
        });
    }

    /**
     * The shortage block under a material table: what would be bought, in the
     * unit purchase actually orders in, and what was last requested for the same
     * material.
     *
     * A shortfall in grams turns into a fiddly fraction of a KG, so the quantity
     * is spelled out in both - a purchase order typed as 0.46 KG against a 0.4622
     * KG shortage is received in full and still leaves production short, and that
     * is precisely the case this block exists to make visible.
     *
     * @param  \Illuminate\Support\Collection  $shortages  material, product_name, short_by,
     *                                                     uom_name, purchase_qty, stock_uom
     */
    protected function shortageHtml($shortages, $canRequest = true): string
    {
        $requests = $this->lastPurchaseRequests($shortages->pluck('material')->all());

        $html = '<div class="alert alert-warning" style="margin-top:10px;">'
            . '<b>Short of material, so a batch cannot start yet.</b>'
            . ($canRequest
                ? ' <b>Send Purchase Request</b> passes exactly the quantities below to the purchase department.'
                : '')
            . '</div>';

        $html .= '<table class="table table-bordered"><tr>'
            . '<th>Short Material</th><th>Short By</th><th>To Buy</th><th>Last Purchase Request</th></tr>';

        foreach ($shortages as $line) {
            $previous = $requests[$line->material] ?? null;

            if (empty($previous)) {
                $history = '<span class="text-muted">None</span>';
            } else {
                $history = 'PR-' . e($previous->order_no) . ' on ' . e($previous->date)
                    . ' for <b>' . round((float) $previous->requested_qty, 4) . ' ' . e($line->stock_uom) . '</b>';

                if (empty($previous->po_no)) {
                    $history .= '<br><span style="color:#c0392b;">No purchase order raised from it yet.</span>';
                } else {
                    $history .= '<br>' . e($previous->po_no)
                        . ($previous->ordered_qty !== null ? ', ordered ' . e($previous->ordered_qty) : '')
                        . ($previous->received_qty !== null
                            ? ', received <b>' . round((float) $previous->received_qty, 4) . '</b>'
                            : '');
                }
            }

            $html .= '<tr>'
                . '<td>' . e($line->product_name) . '</td>'
                . '<td style="text-align:right;">' . $line->short_by . ' ' . e($line->uom_name) . '</td>'
                . '<td style="text-align:right;"><b>' . $line->purchase_qty . ' ' . e($line->stock_uom) . '</b>'
                . ($line->stock_factor > 1
                    ? '<br><small class="text-muted">= ' . round($line->purchase_qty * 1000, 2) . ' g</small>'
                    : '')
                . '</td>'
                . '<td>' . $history . '</td>'
                . '</tr>';
        }

        return $html . '</table>';
    }

    /**
     * "PR-4 sent for 2 raw material(s)..." - the same sentence from both stages.
     */
    protected function purchaseRequestMessage(purchase_requirement $requirement, $count): string
    {
        return 'Purchase request PR-' . $requirement->order_no . ' sent to the purchase department for '
            . $count . ' short raw material(s). The quantity asked for covers the shortage in full, '
            . 'so the batch can start once that much has been received into stock.';
    }
}
