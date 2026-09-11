<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\BeltStockMovement;
use App\OutwardStock;
use App\OutwardStockItem;
use App\stock_status;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Session;

/**
 * Manual stock adjustment - taking stock out for a reason other than an
 * existing document (Delivery Challan, Production, BOM inward, ...) already
 * covers, e.g. damage, sample, physical-count correction. The counterpart to
 * Inward. Uses the same stock_status/stock_book plumbing as every other
 * stock movement via BeltStockMovement, so the running balance and ledger
 * stay in step with the rest of the app.
 */
class OutwardStockController extends Controller
{
    use BeltStockMovement;

    function outward_list(Request $request)
    {
        $query = OutwardStock::with('items.product_item')->orderBy('id', 'desc');

        if ($request->doc_no != '') {
            $query->where('doc_no', 'like', '%' . $request->doc_no . '%');
        }
        if ($request->date_from != '') {
            $query->where('date', '>=', $request->date_from);
        }
        if ($request->date_to != '') {
            $query->where('date', '<=', $request->date_to);
        }

        $data = $query->paginate(session('records_per_page', 30))->appends($request->all());

        return view('admin.outward.list', compact('data'));
    }

    function outward_add(Request $request)
    {
        return view('admin.outward.create');
    }

    function outward_store(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
            'reason' => 'nullable|string|max:255',
            'remark' => 'nullable|string|max:1000',
            'product' => 'required|array|min:1',
            'product.*' => 'required|integer',
            'qty' => 'required|array|min:1',
            'qty.*' => 'required|numeric|min:0.0001',
        ]);

        date_default_timezone_set('Asia/Kolkata');

        try {
            $outward = DB::transaction(function () use ($request) {
                $outward = new OutwardStock();
                $outward->doc_no = $this->nextDocNo('OUT', 'outward_stocks', 'doc_no');
                $outward->date = $request->date;
                $outward->reason = $request->reason;
                $outward->remark = $request->remark;
                $outward->status = 'Y';
                $outward->user_id = Session::get('user_id');
                $outward->save();

                foreach ($request->product as $i => $productId) {
                    $qty = (float) ($request->qty[$i] ?? 0);
                    if ($qty <= 0) {
                        continue;
                    }

                    // The available balance is the real gate; a manual outward
                    // taking stock below zero would only hide a shortage rather
                    // than record one.
                    $available = (float) (stock_status::where('product', $productId)->value('qty') ?? 0);
                    if (round($qty - $available, 4) > 0) {
                        $p = \App\product::find($productId);
                        $name = $p ? \App\product::nameWithVariantInline($p->product_name, $p->value1, $p->value2) : $productId;
                        throw new \RuntimeException("Not enough stock for {$name} - available {$available}, requested {$qty}.");
                    }

                    $item = new OutwardStockItem();
                    $item->outward_stock_id = $outward->id;
                    $item->product = $productId;
                    $item->qty = $qty;
                    $item->save();

                    $this->stockOut($productId, $qty, 'Outward Stock ' . $outward->doc_no . ($request->reason ? ' (' . $request->reason . ')' : ''));
                }

                return $outward;
            });
        } catch (\RuntimeException $e) {
            return back()->withInput()->with('error', $e->getMessage());
        }

        return redirect()->route('admin.outward.list')->with('message', 'Outward stock ' . $outward->doc_no . ' saved.');
    }

    function outward_cancel(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:outward_stocks,id',
            'cancel_reason' => 'required|string|max:255',
        ]);

        date_default_timezone_set('Asia/Kolkata');

        try {
            $outward = DB::transaction(function () use ($request) {
                $outward = OutwardStock::with('items')->lockForUpdate()->find($request->id);

                if ($outward->status == 'C') {
                    throw new \RuntimeException('This outward stock entry is already cancelled.');
                }

                foreach ($outward->items as $item) {
                    $this->stockIn($item->product, $item->qty, 'Reversal of Outward Stock ' . $outward->doc_no);
                }

                $outward->status = 'C';
                $outward->cancelled_by = Session::get('user_id');
                $outward->cancelled_at = now();
                $outward->cancel_reason = $request->cancel_reason;
                $outward->save();

                return $outward;
            });
        } catch (\RuntimeException $e) {
            return back()->with('error', $e->getMessage());
        }

        return redirect()->route('admin.outward.list')->with('message', 'Outward stock ' . $outward->doc_no . ' cancelled, stock restored.');
    }
}
