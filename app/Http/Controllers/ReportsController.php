<?php

namespace App\Http\Controllers;

use App\Exports\OutOfStockExport;
use App\Exports\SalesSummaryExport;
use App\Exports\ProductWiseSalesExport;
use App\Exports\SalesmanWiseSalesExport;
use App\Exports\StockAvailableExport;
use App\Exports\RawMaterialPendingExport;
use App\Exports\ProductionPendingExport;
use App\Exports\BeltProductionExport;
use App\Exports\SocksMissingFormulaExport;
use App\Exports\BeltMissingFormulaExport;
use App\quotation;
use App\salesorder;
use App\salesman;
use App\company;
use App\stitching_machine;
use App\washing_machine;
use Illuminate\Http\Request;
use App\Exports\invoiceExport;
use App\Exports\quotationExport;
use App\Exports\salesExport;
use Excel;
use Illuminate\Support\Facades\DB;
use Session;
use App\delivery_challan;
use App\Exports\challanExport;
use App\invoice;

class ReportsController extends Controller
{
    /**
     * Line items for a page of documents, keyed by the parent document so a
     * report can list each document's products (with Size/Color) beneath its
     * row. One query per report page rather than one per document.
     *
     * @param  string  $itemTable  e.g. 'quot_item'
     * @param  string  $fk         column on $itemTable pointing at the parent
     * @param  array   $keys       parent key values from the current page
     */
    private function documentItems(string $itemTable, string $fk, array $keys)
    {
        $keys = array_values(array_filter($keys, fn ($k) => $k !== null && $k !== ''));
        if (!$keys) {
            return collect();
        }

        return DB::table($itemTable . ' as it')
            ->leftJoin('product as p', 'p.id', '=', 'it.product')
            ->whereIn('it.' . $fk, $keys)
            ->select(
                'it.' . $fk . ' as doc_key',
                'it.qty',
                'it.price',
                'it.description',
                'p.product_name',
                'p.item_code',
                'p.value1',
                'p.value2'
            )
            ->orderBy('it.id')
            ->get()
            ->groupBy('doc_key');
    }

    //
    function quotation(Request $request)
    {
        $product = new quotation();

        $quot_no=$client_name=$quot_date=$subject=$amount=$quot_stage="";
        $product=$product->select('quotation.*','customers.customer_name','customers.primary_email','customers.secondary_email','salesman.salesman_name');
        $product=$product->leftJoin('customers','customers.id','quotation.customer');
        $product=$product->leftJoin('salesman','salesman.id','quotation.salesman_id');
        if($request->quot_no != '')
        {
            $quot_no=$request->quot_no;
            $product = $product->Where('quotation.quotation_no','like','%'.$request->quot_no.'%');
        }
        if($request->client_name != '')
        {
            $product = $product->Where('quotation.customer_name','like','%'.$request->client_name.'%');
        }
        if(isset($request->salesman))
        {
            $salesmanName=$request->salesman;
            $product = $product->Where('salesman.salesman_name','like','%'.$salesmanName.'%');
        }
        if(isset($request->from_date) and isset($request->end_date))
        {
            $from=date('Y-m-d',strtotime($request->from_date));
            $to=date('Y-m-d',strtotime($request->end_date));
            $product = $product->whereBetween('quot_date',[$from,$to]);
        }
        if($request->subject != '')
        {
            $subject=$request->subject;
            $product = $product->Where('quotation.subject','like','%'.$request->subject.'%');
        }
        if($request->amount != '')
        {
            $amount=$request->amount;
            $product = $product->Where('quotation.grand_total','like','%'.$request->amount.'%');
        }
        if($request->quot_stage != '')
        {
            $quot_stage=$request->quot_stage;
            $product = $product->Where('quotation.quot_stage','like','%'.$request->quot_stage.'%');
        }
        if(isset($request->quotno_asc))
        {
            $product=$product->orderBy('quotation.quot_no','asc');
        }
        if(isset($request->quotno_desc))
        {
            $product=$product->orderBy('quotation.quot_no','desc');
        }
        if(isset($request->quot_date_asc))
        {
            $product=$product->orderBy('quotation.quot_date','asc');
        }
        if(isset($request->quot_date_desc))
        {
            $product=$product->orderBy('quotation.quot_date','desc');
        }

        if(isset($request->client_asc))
        {
            $product=$product->orderBy('quotation.customer_name','asc');
        }
        if(isset($request->client_desc))
        {
            $product=$product->orderBy('quotation.customer_name','desc');
        }

        if(isset($request->subject_asc))
        {
            $product=$product->orderBy('quotation.subject','asc');
        }
        if(isset($request->subject_desc))
        {
            $product=$product->orderBy('quotation.subject','desc');
        }

        if(isset($request->amount_asc))
        {
            $product=$product->orderBy('quotation.grand_total','asc');
        }
        if(isset($request->amount_desc))
        {
            $product=$product->orderBy('quotation.grand_total','desc');
        }
        if(isset($request->stage_asc))
        {
            $product=$product->orderBy('quotation.quot_stage','asc');
        }
        if(isset($request->stage_desc))
        {
            $product=$product->orderBy('quotation.quot_stage','desc');
        }

        $product=$product->orderBy('quotation.id','desc');
        $stage = array('Created' => 'Created', 'Sent' => 'Sent', 'Reviewing' => 'Reviewing', 'QuoteRivision' => 'QuoteRivision', 'Accepted' => 'Accepted', 'Invoiced' => 'Invoiced', 'Canceled' => 'Canceled');

        if ($request->export_excel) {
            return Excel::download(new quotationExport($product->get()), 'QuotationReport.xlsx');
        }

        $summary = (clone $product)->get();
        $totalRecords = $summary->count();
        $totalAmount = $summary->sum('grand_total');

        $result = $product->paginate(session('records_per_page', 30))->appends($request->all());

        $items = $this->documentItems('quot_item', 'quot_no', $result->pluck('quot_no')->all());

        return view('admin.reports.quotation')
                ->with(['list'=>$result,'stage'=>$stage,'totalRecords'=>$totalRecords,'totalAmount'=>$totalAmount,'items'=>$items]);
    }

    function sales(Request $request)
    {
        $product = new salesorder();
        $salaesorder_no=$client_name=$quot_date=$subject=$amount=$quot_stage="";

        $product=$product->select('salesorder.*','customers.customer_name','customers.primary_email','customers.secondary_email','salesman.salesman_name');
        $product=$product->leftJoin('customers','customers.id','salesorder.customer');
        $product=$product->leftJoin('salesman','salesman.id','salesorder.salesman_id');

        if($request->salaesorder_no != '')
        {
            $salaesorder_no=$request->quot_no;
            $product = $product->Where('salesorder.salaesorder_no','like','%'.$request->salaesorder_no.'%');
        }

        if($request->client_name != '')
        {
            $product = $product->Where('salesorder.customer_name','like','%'.$request->client_name.'%');
        }

        if(isset($request->salesman))
        {
            $product = $product->Where('salesman.salesman_name','like','%'.$request->salesman.'%');
        }

        if(isset($request->from_date) and isset($request->end_date))
        {
            $from=date('Y-m-d',strtotime($request->from_date));
            $to=date('Y-m-d',strtotime($request->end_date));
            $product = $product->whereBetween('salaesorder_date',[$from,$to]);
        }
        if($request->subject != '')
        {
            $subject=$request->subject;
            $product = $product->Where('salesorder.subject','like','%'.$request->subject.'%');
        }
        if($request->amount != '')
        {
            $amount=$request->amount;
            $product = $product->Where('salesorder.grand_total','like','%'.$request->amount.'%');
        }
        if($request->status != '')
        {
            $status=$request->status;
            $product = $product->Where('salesorder.status','like','%'.$request->status.'%');
        }
        //echo print_r($request->all());

        $product=$product->orderBy("id",'desc');
        $product=$product->whereNull("delete_status");

        if ($request->export_excel) {
            return Excel::download(new salesExport($product->get()), 'SalesReport.xlsx');
        }

        $summary = (clone $product)->get();
        $totalRecords = $summary->count();
        $totalAmount = $summary->sum('grand_total');

        $result = $product->paginate(session('records_per_page', 30))->appends($request->all());

        $company_name=company::select('company_name')->first();

        $items = $this->documentItems('salesorder_item', 'sono', $result->pluck('salaesorder_no')->all());

        return view('admin.reports.sales')->with(['list'=>$result,'company'=>$company_name->company_name,'totalRecords'=>$totalRecords,'totalAmount'=>$totalAmount,'items'=>$items]);
    }

    function challan(Request $request)
    {
           $quot_no = $client_name = $quot_date = $subject = $amount = $quot_stage = "";

        $product = new delivery_challan();

        $quot_no=$client_name=$quot_date=$subject=$amount=$quot_stage="";

        $product=$product->select('delivery_challan.*','customers.customer_name','customers.primary_email','customers.secondary_email');
        $product=$product->leftJoin('customers','customers.id','delivery_challan.customer');

        if($request->invoice_no != '')
        {
            $quot_no=$request->quot_no;
            $product = $product->Where('delivery_challan.challan_number','like','%'.$request->invoice_no.'%');
        }
        if($request->client_name != '')
        {
            $client_name=$request->client_name;
            $product = $product->Where('customers.customer_name','like','%'.$request->client_name.'%');
        }
        if(isset($request->from_date) and isset($request->end_date))
        {
            $from=date('Y-m-d',strtotime($request->from_date));
            $to=date('Y-m-d',strtotime($request->end_date));
            $product = $product->whereBetween('delivery_challan.invoice_date',[$from,$to]);
        }
        if($request->subject != '')
        {
            $subject=$request->subject;
            $product = $product->Where('delivery_challan.subject','like','%'.$request->subject.'%');
        }
        if($request->amount != '')
        {
            $amount=$request->amount;
            $product = $product->Where('delivery_challan.grand_total','like','%'.$request->amount.'%');
        }
        if($request->status != '')
        {
            $quot_stage=$request->status;
            $product = $product->Where('delivery_challan.status','like','%'.$request->status.'%');
        }


        //echo print_r($request->all());
        $product = $product->where('delivery_challan.finacial_year', Session::get('finacial_year_id'));
        $product = $product->whereNull('delivery_challan.delete_status');
        $product=$product->orderBy('delivery_challan.id','desc');

        if ($request->export_excel) {
            return Excel::download(new challanExport($product->get()), 'ChallanReport.xlsx');
        }

        $summary = (clone $product)->get();
        $totalRecords = $summary->count();
        $totalAmount = $summary->sum('grand_total');

        $result = $product->paginate(session('records_per_page', 30))->appends($request->all());

        $company_name = company::select('company_name')->first();


        $stage = array('Created' => 'Created', 'Sent' => 'Sent', 'Reviewing' => 'Reviewing', 'QuoteRivision' => 'QuoteRivision', 'Accepted' => 'Accepted', 'Invoiced' => 'Invoiced', 'Canceled' => 'Canceled');


        $items = $this->documentItems('delivery_challan_item', 'invid', $result->pluck('id')->all());

        return view("admin.reports.challan")->with(['list' => $result, 'company' => $company_name->company_name, 'stage' => $stage, 'totalRecords' => $totalRecords, 'totalAmount' => $totalAmount, 'items' => $items]);
    }

    function invoice(Request $request)
    {
          $quot_no = $client_name = $quot_date = $subject = $amount = $quot_stage = "";

        $product = new invoice();

        $quot_no=$client_name=$quot_date=$subject=$amount=$quot_stage="";

        $product=$product->select('invoice.*','customers.customer_name','customers.primary_email','customers.secondary_email','salesman.salesman_name');
        $product=$product->leftJoin('customers','customers.id','invoice.customer');
        $product=$product->leftJoin('salesman','salesman.id','invoice.salesman_id');

        if($request->invoice_no != '')
        {
            $product = $product->Where('invoice.invoice_number','like','%'.$request->invoice_no.'%');
        }

        if($request->client_name != '')
        {
            $product = $product->Where('customers.customer_name','like','%'.$request->client_name.'%');
        }
        if(isset($request->salesman))
        {
            $product = $product->Where('salesman.salesman_name','like','%'.$request->salesman.'%');
        }
        if(isset($request->from_date) and isset($request->end_date))
        {
            $from=date('Y-m-d',strtotime($request->from_date));
            $to=date('Y-m-d',strtotime($request->end_date));
            $product = $product->whereBetween('invoice.invoice_date',[$from,$to]);
        }
        if($request->subject != '')
        {
            $subject=$request->subject;
            $product = $product->Where('invoice.subject','like','%'.$request->subject.'%');
        }
        if($request->amount != '')
        {
            $amount=$request->amount;
            $product = $product->Where('invoice.grand_total','like','%'.$request->amount.'%');
        }
        if($request->status != '')
        {
            $quot_stage=$request->status;
            $product = $product->Where('invoice.status','like','%'.$request->status.'%');
        }


        $product=$product->orderBy('invoice.id','desc');

        $stage = array('Created' => 'Created', 'Sent' => 'Sent', 'Reviewing' => 'Reviewing', 'QuoteRivision' => 'QuoteRivision', 'Accepted' => 'Accepted', 'Invoiced' => 'Invoiced', 'Canceled' => 'Canceled');

        if ($request->export_excel) {
            return Excel::download(new invoiceExport($product->get()), 'InvoicesReport.xlsx');
        }

        $summary = (clone $product)->get();
        $totalRecords = $summary->count();
        $totalAmount = $summary->sum('grand_total');

        $result = $product->paginate(session('records_per_page', 30))->appends($request->all());

        $company_name = company::select('company_name')->first();

        $items = $this->documentItems('invoice_item', 'invoice_no', $result->pluck('invoice_number')->all());

        return view("admin.reports.invoice")->with(['list' => $result, 'company' => $company_name->company_name, 'stage' => $stage, 'totalRecords' => $totalRecords, 'totalAmount' => $totalAmount, 'items' => $items]);
    }

    public function salesOutOfStockItems(Request $request)
    {
        $categories    = DB::table('category')->orderBy('category_name')->get();
        $subcategories = DB::table('subcategory')->orderBy('subcategory_name')->get();

        // Step 1: one row per (product, sales order) with that order's own sold qty.
        // Built over the FULL order history (no filters) so the running balance below
        // is correct regardless of which rows the user later chooses to display.
        $perOrder = DB::table('salesorder_item as soi')
            ->join('salesorder as s', 's.id', '=', 'soi.soid')
            ->join('product as p', 'p.id', '=', 'soi.product')
            ->leftJoin('category as cat', 'cat.id', '=', 'p.category')
            ->leftJoin('subcategory as sc', 'sc.id', '=', 'p.subcategory')
            ->whereNull('s.delete_status')
            ->groupBy(
                'soi.product',
                'p.product_name',
                'p.value1',
                'p.value2',
                'p.category',
                'p.subcategory',
                's.id',
                's.salaesorder_no',
                's.customer',
                's.customer_name',
                's.salaesorder_date',
                'cat.category_name',
                'sc.subcategory_name'
            )
            ->select(
                'soi.product as product_id',
                'p.product_name as product',
                'p.value1',
                'p.value2',
                'p.category as category_id',
                'p.subcategory as subcategory_id',
                's.id as so_id',
                's.salaesorder_no as order_no',
                's.customer as customer_id',
                's.customer_name as customer',
                's.salaesorder_date as order_date',
                'cat.category_name as category_name',
                'sc.subcategory_name as subcategory_name',
                DB::raw('SUM(soi.qty) as sold_qty')
            );

        // Delivered qty per (order, product), pre-aggregated once instead of a
        // correlated subquery re-run for every row.
        $delivered = DB::table('delivery_challan_item as dci')
            ->join('delivery_challan as dc', 'dc.id', '=', 'dci.invid')
            ->whereNull('dc.delete_status')
            ->groupBy('dc.salaesorder_no', 'dci.product')
            ->select(
                'dc.salaesorder_no as order_no',
                'dci.product as product_id',
                DB::raw('SUM(dci.qty) as delivered_qty')
            );

        // A row only qualifies as "out of stock" if TODAY's actual on-hand stock
        // (stock_status) can't cover what was sold on that order - not a
        // historical snapshot. Otherwise a product that has since been restocked
        // kept showing up here (with "need to purchase" correctly at 0), which
        // just confused whoever was reading the report.
        $query = DB::query()
            ->fromSub($perOrder, 'w')
            ->leftJoin('stock_status as ss', 'ss.product', '=', 'w.product_id')
            ->leftJoinSub($delivered, 'd', function ($join) {
                $join->on('d.order_no', '=', 'w.order_no')
                     ->on('d.product_id', '=', 'w.product_id');
            })
            ->select(
                'w.product_id',
                'w.product',
                'w.value1',
                'w.value2',
                'w.category_id',
                'w.subcategory_id',
                'w.so_id',
                'w.order_no',
                'w.customer_id',
                'w.customer',
                'w.order_date',
                'w.category_name',
                'w.subcategory_name',
                'w.sold_qty'
            )
            ->selectRaw('IFNULL(ss.qty, 0) as stock_qty')
            ->selectRaw('GREATEST(w.sold_qty - IFNULL(ss.qty, 0), 0) as need_to_purchase_qty')
            ->whereRaw('IFNULL(ss.qty, 0) < w.sold_qty')
            // Already delivered (fully covered by a Delivery Challan) = resolved,
            // not "at risk" anymore, so don't flag it as insufficient stock.
            ->whereRaw('IFNULL(d.delivered_qty, 0) < w.sold_qty')
            ->orderBy('w.order_date', 'desc');

        // Filters (applied after the running balance is computed, so they only
        // control which rows are displayed - not the balance calculation itself)
        if ($request->product) {
            $query->where('w.product', 'like', '%' . $request->product . '%');
        }

        if ($request->order_no) {
            $query->where('w.order_no', 'like', '%' . $request->order_no . '%');
        }

        if ($request->customer) {
            $query->where('w.customer', 'like', '%' . $request->customer . '%');
        }

        if ($request->from_date) {
            $query->whereDate('w.order_date', '>=', $request->from_date);
        }

        if ($request->end_date) {
            $query->whereDate('w.order_date', '<=', $request->end_date);
        }

        if ($request->category) {
            $query->where('w.category_id', $request->category);
        }

        if ($request->subcategory) {
            $query->where('w.subcategory_id', $request->subcategory);
        }

        // Export must be checked BEFORE paginate to export all records
        if ($request->export_excel) {
            $data = $query->get();
            return Excel::download(new OutOfStockExport($data), 'out_of_stock.xlsx');
        }

        // Summary stats over the FULL filtered result set (not just this page).
        // Replace the select list entirely - can't mix these aggregates with the
        // row-level columns already selected on $query without a GROUP BY.
        $summary = (clone $query)
            ->reorder()
            ->select(DB::raw('
                COUNT(DISTINCT w.product_id) as affected_products,
                COUNT(DISTINCT w.order_no) as affected_orders,
                SUM(w.sold_qty) as total_sold_qty
            '))
            ->first();

        $list = $query->paginate(session('records_per_page', 30));

        return view('admin.reports.sales_out_of_stock', compact('list', 'categories', 'subcategories', 'summary'));
    }

    /**
     * Sales Report - Daily / Monthly.
     * Detailed sales order list grouped by day or by month, with a subtotal
     * per group and a grand total, per the client's requirement sheet.
     */
    function salesSummary(Request $request)
    {
        $period = $request->period == 'monthly' ? 'monthly' : 'daily';

        // Default to a trailing 90-day window (not "this calendar month") so the
        // report isn't silently empty just because today happens to fall early
        // in a month with no orders yet - a fixed calendar-month default hid
        // real recent orders (e.g. a salesman's last sale from a few weeks back
        // in the previous month) and looked like a bug.
        $from = $request->from_date ? date('Y-m-d', strtotime($request->from_date)) : date('Y-m-d', strtotime('-90 days'));
        $to   = $request->end_date ? date('Y-m-d', strtotime($request->end_date)) : date('Y-m-d');

        $query = salesorder::select('salesorder.*', DB::raw("IFNULL(salesman.salesman_name,'-') as salesman_name"))
            ->leftJoin('salesman', 'salesman.id', 'salesorder.salesman_id')
            ->whereNull('salesorder.delete_status')
            ->whereBetween('salesorder.salaesorder_date', [$from, $to]);

        if ($request->customer_name != '') {
            $query->where('salesorder.customer_name', 'like', '%' . $request->customer_name . '%');
        }
        if ($request->salesman != '') {
            $query->where('salesman.salesman_name', 'like', '%' . $request->salesman . '%');
        }

        $query->orderBy('salesorder.salaesorder_date', 'asc')->orderBy('salesorder.id', 'asc');

        if ($request->export_excel) {
            return Excel::download(new SalesSummaryExport($query->get(), $period), 'SalesReport_' . $period . '.xlsx');
        }

        $rows = $query->get();

        $groups = $rows->groupBy(function ($row) use ($period) {
            return $period === 'monthly'
                ? date('F Y', strtotime($row->salaesorder_date))
                : date('d M Y', strtotime($row->salaesorder_date));
        });

        $grandTotal = $rows->sum('grand_total');
        $grandCount = $rows->count();

        $items = $this->documentItems('salesorder_item', 'sono', $rows->pluck('salaesorder_no')->all());

        return view('admin.reports.sales_summary', compact('groups', 'period', 'grandTotal', 'grandCount', 'from', 'to', 'items'));
    }

    /**
     * Product Wise Sales Report.
     * Sales order line items grouped by product, with subtotal per product.
     */
    function productWiseSales(Request $request)
    {
        $from = $request->from_date ? date('Y-m-d', strtotime($request->from_date)) : date('Y-m-d', strtotime('-90 days'));
        $to   = $request->end_date ? date('Y-m-d', strtotime($request->end_date)) : date('Y-m-d');

        $query = DB::table('salesorder_item as soi')
            ->join('salesorder as s', 's.id', '=', 'soi.soid')
            ->join('product as p', 'p.id', '=', 'soi.product')
            ->leftJoin('category as cat', 'cat.id', '=', 'p.category')
            ->whereNull('s.delete_status')
            ->whereBetween('s.salaesorder_date', [$from, $to])
            ->select(
                'soi.product as product_id',
                'p.product_name as product',
                'p.value1',
                'p.value2',
                'cat.category_name as category_name',
                's.id as so_id',
                's.salaesorder_no as order_no',
                's.salaesorder_date as order_date',
                's.customer_name as customer',
                'soi.qty',
                'soi.price',
                'soi.grand_total as amount'
            );

        if ($request->product != '') {
            $query->where('p.product_name', 'like', '%' . $request->product . '%');
        }
        if ($request->category != '') {
            $query->where('p.category', $request->category);
        }

        $query->orderBy('p.product_name', 'asc')->orderBy('s.salaesorder_date', 'asc');

        if ($request->export_excel) {
            return Excel::download(new ProductWiseSalesExport($query->get()), 'ProductWiseSalesReport.xlsx');
        }

        $rows = $query->get();
        $groups = $rows->groupBy('product');
        $grandQty = $rows->sum('qty');
        $grandAmount = $rows->sum('amount');

        return view('admin.reports.product_wise_sales', compact('groups', 'grandQty', 'grandAmount', 'from', 'to'));
    }

    /**
     * Sales-MAN Wise Sales Report - Daily / Monthly.
     * Sales orders grouped by salesman, then by day/month within each
     * salesman, with a subtotal per group.
     */
    function salesmanWiseSales(Request $request)
    {
        $period = $request->period == 'monthly' ? 'monthly' : 'daily';

        $from = $request->from_date ? date('Y-m-d', strtotime($request->from_date)) : date('Y-m-d', strtotime('-90 days'));
        $to   = $request->end_date ? date('Y-m-d', strtotime($request->end_date)) : date('Y-m-d');

        $query = salesorder::select('salesorder.*', DB::raw("IFNULL(salesman.salesman_name,'Unassigned') as salesman_name"))
            ->leftJoin('salesman', 'salesman.id', 'salesorder.salesman_id')
            ->whereNull('salesorder.delete_status')
            ->whereBetween('salesorder.salaesorder_date', [$from, $to]);

        if ($request->salesman != '') {
            $query->where('salesman.salesman_name', 'like', '%' . $request->salesman . '%');
        }
        if ($request->customer_name != '') {
            $query->where('salesorder.customer_name', 'like', '%' . $request->customer_name . '%');
        }

        $query->orderBy('salesman_name', 'asc')->orderBy('salesorder.salaesorder_date', 'asc');

        if ($request->export_excel) {
            return Excel::download(new SalesmanWiseSalesExport($query->get(), $period), 'SalesmanWiseSalesReport_' . $period . '.xlsx');
        }

        $rows = $query->get();

        $groups = $rows->groupBy('salesman_name')->map(function ($salesmanRows) use ($period) {
            return $salesmanRows->groupBy(function ($row) use ($period) {
                return $period === 'monthly'
                    ? date('F Y', strtotime($row->salaesorder_date))
                    : date('d M Y', strtotime($row->salaesorder_date));
            });
        });

        $grandTotal = $rows->sum('grand_total');
        $salesmen = salesman::orderBy('salesman_name', 'asc')->get();

        $items = $this->documentItems('salesorder_item', 'sono', $rows->pluck('salaesorder_no')->all());

        return view('admin.reports.salesman_wise_sales', compact('groups', 'period', 'grandTotal', 'from', 'to', 'salesmen', 'items'));
    }

    /**
     * Product Wise Available Stock Report.
     * Current on-hand stock per product, from the stock_status snapshot.
     */
    function stockAvailable(Request $request)
    {
        $query = DB::table('stock_status as ss')
            ->join('product as p', 'p.id', '=', 'ss.product')
            ->leftJoin('category as cat', 'cat.id', '=', 'p.category')
            ->leftJoin('subcategory as sc', 'sc.id', '=', 'p.subcategory')
            ->groupBy('ss.product', 'p.product_name', 'p.value1', 'p.value2', 'p.item_code', 'p.uom', 'cat.category_name', 'sc.subcategory_name')
            ->select(
                'ss.product as product_id',
                'p.product_name as product',
                'p.value1',
                'p.value2',
                'p.item_code',
                'p.uom',
                'cat.category_name',
                'sc.subcategory_name'
            )
            ->selectRaw('SUM(ss.qty) as stock_qty');

        if ($request->product != '') {
            $query->where('p.product_name', 'like', '%' . $request->product . '%');
        }
        if ($request->category != '') {
            $query->where('p.category', $request->category);
        }
        if ($request->subcategory != '') {
            $query->where('p.subcategory', $request->subcategory);
        }
        if ($request->only_available) {
            $query->having('stock_qty', '>', 0);
        }

        $query->orderBy('p.product_name', 'asc');

        if ($request->export_excel) {
            $data = $query->get();
            return Excel::download(new StockAvailableExport($data), 'ProductWiseStockReport.xlsx');
        }

        $subcategories = DB::table('subcategory')->orderBy('subcategory_name')->get();

        $summary = (clone $query)->get();
        $totalProducts = $summary->count();
        $totalStockQty = $summary->sum('stock_qty');

        $list = $query->paginate(session('records_per_page', 30))->appends($request->all());

        return view('admin.reports.stock_available', compact('list', 'subcategories', 'totalProducts', 'totalStockQty'));
    }

    /**
     * RAW Material Required Pending Report.
     * Combines two sources of pending raw material need, grouped by raw material:
     * 1. Production batches whose need is not yet fully covered by stock
     *    (production_material.need_to_order_stock > 0).
     * 2. Shortages raised during machine allocation that never became a batch at
     *    all (ProductionController::machine_allocate diverts these straight to
     *    purchase_required_material instead of creating a production_material
     *    row), still outstanding because no PO has been raised yet (po_no is null).
     * Source 2's finish_product/customer come from purchase_requirement (captured
     * at machine-allocation time); older rows created before that column existed
     * still show '-'.
     */
    function rawMaterialPending(Request $request)
    {
        $prodQuery = DB::table('production_material as pm')
            ->join('product as rm', 'rm.id', '=', 'pm.required_material')
            ->leftJoin('uom as u', 'u.id', '=', 'rm.uom')
            ->leftJoin('customers as c', 'c.id', '=', 'pm.customer')
            ->leftJoin('product as fp', 'fp.id', '=', 'pm.finish_product')
            ->where('pm.need_to_order_stock', '>', 0)
            ->select(
                DB::raw("'production' as source"),
                'pm.id',
                'pm.batch_no',
                'rm.product_name as raw_material',
                'rm.value1',
                'rm.value2',
                'u.uom_name as uom',
                'pm.required_qty',
                'pm.avalible_stock',
                'pm.need_to_order_stock',
                'fp.product_name as finish_product',
                'fp.value1 as fp_value1',
                'fp.value2 as fp_value2',
                'c.customer_name as customer',
                'pm.timestamp'
            );

        if ($request->raw_material != '') {
            $prodQuery->where('rm.product_name', 'like', '%' . $request->raw_material . '%');
        }
        if ($request->customer != '') {
            $prodQuery->where('c.customer_name', 'like', '%' . $request->customer . '%');
        }
        if ($request->batch_no != '') {
            $prodQuery->where('pm.batch_no', 'like', '%' . $request->batch_no . '%');
        }

        $purchaseQuery = DB::table('purchase_required_material as prm')
            ->join('purchase_requirement as pr', 'pr.id', '=', 'prm.order_id')
            ->join('product as rm', 'rm.id', '=', 'prm.raw_material')
            ->leftJoin('uom as u', 'u.id', '=', 'rm.uom')
            ->leftJoin('customers as c', 'c.id', '=', 'pr.customer')
            ->leftJoin('product as fp', 'fp.id', '=', 'pr.finish_product')
            ->whereNull('pr.po_no')
            ->where('prm.qty', '>', 0)
            ->select(
                DB::raw("'purchase_request' as source"),
                'prm.id',
                // Belt roll production raises requests through the same tables as
                // sock production, so the label carries the module to keep the two
                // tellable apart (and searchable) in one list.
                DB::raw("CONCAT(CASE WHEN pr.module = 'belt' THEN 'BELT PR-' ELSE 'PR-' END, pr.order_no) as batch_no"),
                'rm.product_name as raw_material',
                'rm.value1',
                'rm.value2',
                'u.uom_name as uom',
                DB::raw('0 as required_qty'),
                DB::raw('0 as avalible_stock'),
                'prm.qty as need_to_order_stock',
                'fp.product_name as finish_product',
                'fp.value1 as fp_value1',
                'fp.value2 as fp_value2',
                'c.customer_name as customer',
                'prm.timestamp'
            );

        if ($request->raw_material != '') {
            $purchaseQuery->where('rm.product_name', 'like', '%' . $request->raw_material . '%');
        }
        if ($request->customer != '') {
            $purchaseQuery->where('c.customer_name', 'like', '%' . $request->customer . '%');
        }
        if ($request->batch_no != '') {
            $purchaseQuery->having('batch_no', 'like', '%' . $request->batch_no . '%');
        }

        $query = $prodQuery->unionAll($purchaseQuery)
            ->orderBy('raw_material', 'asc')
            ->orderBy('id', 'desc');

        if ($request->export_excel) {
            return Excel::download(new RawMaterialPendingExport($query->get()), 'RawMaterialPendingReport.xlsx');
        }

        $rows = $query->get();
        $groups = $rows->groupBy('raw_material');
        $grandNeed = $rows->sum('need_to_order_stock');

        return view('admin.reports.raw_material_pending', compact('groups', 'grandNeed'));
    }

    /**
     * Pending Report - Production, Stitching, Press and Packing.
     * A single page with a stage dropdown; each stage's table uses its own
     * *_status = 'N' convention (already used by ProductionController) to
     * mark a batch as not yet complete for that stage.
     */
    /**
     * Pending Report - Production / Stitching / Press / Packing.
     * Split into one method + view per stage (rather than a single page with
     * a stage dropdown) so each gets its own short menu label.
     */
    private function stageConfig()
    {
        return [
            'production' => ['table' => 'production', 'status_col' => 'production_status', 'machine_table' => 'machine',           'label' => 'Production'],
            'stitching'  => ['table' => 'stitching',  'status_col' => 'stitching_status',  'machine_table' => 'stitching_machine', 'label' => 'Stitching'],
            'pressing'   => ['table' => 'pressing',   'status_col' => 'pressing_status',   'machine_table' => 'pressing_machine',  'label' => 'Press'],
            'packaging'  => ['table' => 'packaging',  'status_col' => 'packaging_status',  'machine_table' => 'packaging_machine', 'label' => 'Packing'],
        ];
    }

    private function pendingByStage(Request $request, $stage)
    {
        $cfg = $this->stageConfig()[$stage];

        $query = DB::table($cfg['table'] . ' as t')
            ->leftJoin($cfg['machine_table'] . ' as m', 'm.id', '=', 't.machine')
            ->leftJoin('product as p', 'p.id', '=', 't.finish_product')
            ->leftJoin('customers as c', 'c.id', '=', 't.customer')
            ->where('t.' . $cfg['status_col'], 'N')
            ->select(
                't.id',
                't.batch_no',
                't.nos',
                't.size',
                't.total_material',
                't.timestamp',
                'm.machine_name',
                'p.product_name',
                'p.value1',
                'p.value2',
                'c.customer_name'
            );

        if ($request->batch_no != '') {
            $query->where('t.batch_no', 'like', '%' . $request->batch_no . '%');
        }
        if ($request->customer != '') {
            $query->where('c.customer_name', 'like', '%' . $request->customer . '%');
        }

        $query->orderBy('t.id', 'desc');

        if ($request->export_excel) {
            return Excel::download(new ProductionPendingExport($query->get(), $cfg['label']), 'PendingReport_' . $stage . '.xlsx');
        }

        $list = $query->paginate(session('records_per_page', 30))->appends($request->all());

        return view('admin.reports.pending_' . $stage, compact('list'));
    }

    function productionPending(Request $request)
    {
        return $this->pendingByStage($request, 'production');
    }

    function stitchingPending(Request $request)
    {
        return $this->pendingByStage($request, 'stitching');
    }

    function pressingPending(Request $request)
    {
        return $this->pendingByStage($request, 'pressing');
    }

    function packagingPending(Request $request)
    {
        return $this->pendingByStage($request, 'packaging');
    }

    /**
     * Belt Production Report.
     * Per batch: planned qty, actual produced qty, wastage qty, and pending
     * qty (only meaningful while status = 'N' i.e. not yet completed).
     */
    function beltProduction(Request $request)
    {
        $query = DB::table('belt_production as bp')
            ->join('product as p', 'p.id', '=', 'bp.belt_product')
            ->leftJoin('customers as c', 'c.id', '=', 'bp.customer')
            ->select(
                'bp.id',
                'bp.batch_no',
                'p.product_name as product',
                'p.value1',
                'p.value2',
                'c.customer_name as customer',
                'bp.planned_qty',
                'bp.total_production',
                'bp.total_wastage_nos',
                'bp.status',
                'bp.created_at'
            )
            ->selectRaw("CASE WHEN bp.status = 'N' THEN bp.planned_qty ELSE 0 END as pending_qty");

        if ($request->batch_no != '') {
            $query->where('bp.batch_no', 'like', '%' . $request->batch_no . '%');
        }
        if ($request->product != '') {
            $query->where('p.product_name', 'like', '%' . $request->product . '%');
        }
        if ($request->customer != '') {
            $query->where('c.customer_name', 'like', '%' . $request->customer . '%');
        }
        if ($request->status != '') {
            $query->where('bp.status', $request->status);
        }

        $query->orderBy('bp.id', 'desc');

        if ($request->export_excel) {
            return Excel::download(new BeltProductionExport($query->get()), 'BeltProductionReport.xlsx');
        }

        $summary = (clone $query)->get();
        $totalPlanned = $summary->sum('planned_qty');
        $totalProduced = $summary->sum('total_production');
        $totalWastage = $summary->sum('total_wastage_nos');
        $totalPending = $summary->sum('pending_qty');
        $pendingBatches = $summary->where('status', 'N')->count();
        $completedBatches = $summary->where('status', 'Y')->count();

        $list = $query->paginate(session('records_per_page', 30))->appends($request->all());

        return view('admin.reports.belt_production', compact(
            'list', 'totalPlanned', 'totalProduced', 'totalWastage', 'totalPending',
            'pendingBatches', 'completedBatches'
        ));
    }

    /**
     * Socks products (category = Socks) that have no matching row in formula_mst
     * yet - i.e. their production formula was never created. Anti-join on
     * formula_mst.product = product.id, same linkage FormulaController::formula_add
     * uses to build the "select product" list when creating a formula.
     */
    function socksMissingFormula(Request $request)
    {
        $socksCategoryId = DB::table('category')->where('category_name', 'Socks')->value('id');

        $query = DB::table('product as p')
            ->leftJoin('subcategory as sc', 'sc.id', '=', 'p.subcategory')
            ->leftJoin('formula_mst as fm', 'fm.product', '=', 'p.id')
            ->where('p.category', $socksCategoryId)
            ->where('p.status', 'product')
            ->whereNull('fm.id')
            ->select('p.id', 'p.product_name', 'p.value1', 'p.value2', 'p.item_code', 'p.uom', 'sc.subcategory_name');

        if ($request->product != '') {
            $query->where('p.product_name', 'like', '%' . $request->product . '%');
        }
        if ($request->subcategory != '') {
            $query->where('p.subcategory', $request->subcategory);
        }

        $query->orderBy('p.product_name', 'asc');

        if ($request->export_excel) {
            return Excel::download(new SocksMissingFormulaExport($query->get()), 'SocksProductsWithoutFormula.xlsx');
        }

        $subcategories = DB::table('subcategory')->where('category', $socksCategoryId)->orderBy('subcategory_name')->get();

        $summary = (clone $query)->get();
        $totalMissing = $summary->count();

        $list = $query->paginate(session('records_per_page', 30))->appends($request->all());

        return view('admin.reports.socks_missing_formula', compact('list', 'subcategories', 'totalMissing'));
    }

    /**
     * Belt products (category = Belt) that have no matching row in
     * buckle_formula_mst yet. Same anti-join shape as socksMissingFormula(),
     * against buckle_formula_mst.product = product.id (BuckleFormulaController
     * enforces one formula per product via a unique constraint on that column).
     */
    function beltMissingFormula(Request $request)
    {
        $beltCategoryId = DB::table('category')->where('category_name', 'Belt')->value('id');

        $query = DB::table('product as p')
            ->leftJoin('subcategory as sc', 'sc.id', '=', 'p.subcategory')
            ->leftJoin('buckle_formula_mst as bfm', 'bfm.product', '=', 'p.id')
            ->where('p.category', $beltCategoryId)
            ->where('p.status', 'product')
            ->whereNull('bfm.id')
            ->select('p.id', 'p.product_name', 'p.value1', 'p.value2', 'p.item_code', 'p.uom', 'sc.subcategory_name');

        if ($request->product != '') {
            $query->where('p.product_name', 'like', '%' . $request->product . '%');
        }
        if ($request->subcategory != '') {
            $query->where('p.subcategory', $request->subcategory);
        }

        $query->orderBy('p.product_name', 'asc');

        if ($request->export_excel) {
            return Excel::download(new BeltMissingFormulaExport($query->get()), 'BeltProductsWithoutFormula.xlsx');
        }

        $subcategories = DB::table('subcategory')->where('category', $beltCategoryId)->orderBy('subcategory_name')->get();

        $summary = (clone $query)->get();
        $totalMissing = $summary->count();

        $list = $query->paginate(session('records_per_page', 30))->appends($request->all());

        return view('admin.reports.belt_missing_formula', compact('list', 'subcategories', 'totalMissing'));
    }

    /**
     * Stage A of the belt flow: what was woven, on which formula version, and how
     * close the meters off the loom came to the meters planned.
     */
    function rollProduction(Request $request)
    {
        $query = $this->rollProductionBaseQuery($request)
            ->leftJoin('customers as c', 'c.id', '=', 'b.customer')
            ->select(
                'b.id',
                'b.batch_no',
                'b.roll_formula_version',
                'b.roll_length_mtr',
                'b.no_of_rolls',
                'b.planned_mtr',
                'b.produced_mtr',
                'b.wastage_mtr',
                'b.status',
                'b.created_at',
                'p.product_name as product',
                'n.type as niwar_type',
                'n.code as niwar_code',
                'c.customer_name as customer'
            )
            // Counted rather than joined, so a batch with five rolls stays one row.
            ->selectSub(
                DB::table('belt_rolls')->whereColumn('belt_rolls.belt_roll_production_id', 'b.id')->selectRaw('COUNT(*)'),
                'rolls_made'
            )
            ->orderBy('b.id', 'desc');

        if ($request->export_excel) {
            return Excel::download(new \App\Exports\RollProductionExport($query->get()), 'RollProductionReport.xlsx');
        }

        // Aggregated in the database - a year of batches should not be pulled
        // into PHP just to add up six columns. Built from the base query rather
        // than cloned, because the row query carries a select subquery whose
        // bindings do not belong in an aggregate.
        $totals = $this->rollProductionBaseQuery($request)->select(DB::raw(
            "COUNT(*) as batches,
             COALESCE(SUM(b.planned_mtr), 0) as planned,
             COALESCE(SUM(CASE WHEN b.status = 'Y' THEN b.produced_mtr ELSE 0 END), 0) as produced,
             COALESCE(SUM(CASE WHEN b.status = 'Y' THEN b.wastage_mtr ELSE 0 END), 0) as wastage,
             COALESCE(SUM(CASE WHEN b.status = 'N' THEN 1 ELSE 0 END), 0) as pending_batches,
             COALESCE(SUM(CASE WHEN b.status = 'C' THEN 1 ELSE 0 END), 0) as cancelled_batches"
        ))->first();

        $niwarCodes = DB::table('niwar_codes')->orderBy('type')->get();

        $list = $query->paginate(session('records_per_page', 30))->appends($request->all());

        return view('admin.reports.roll_production', [
            'list' => $list,
            'niwarCodes' => $niwarCodes,
            'totalBatches' => (int) ($totals->batches ?? 0),
            'totalPlanned' => round($totals->planned ?? 0, 2),
            'totalProduced' => round($totals->produced ?? 0, 2),
            'totalWastage' => round($totals->wastage ?? 0, 2),
            'pendingBatches' => (int) ($totals->pending_batches ?? 0),
            'cancelledBatches' => (int) ($totals->cancelled_batches ?? 0),
            'efficiency' => $totals->planned > 0 ? round($totals->produced / $totals->planned * 100, 2) : 0,
        ]);
    }

    /**
     * Dhaga planned against dhaga actually used, per raw material and per niwar
     * category - so a Roto whose threads consistently run over shows up as four
     * separate variances rather than one lump.
     *
     * Cancelled batches are excluded: their consumption was returned to stock, so
     * counting it would overstate what the floor really used.
     */
    function rollMaterialConsumption(Request $request)
    {
        // 'p' stays the roll product across both reports so the shared filters
        // mean the same thing; the raw material joins in under its own alias.
        $query = $this->rollProductionBaseQuery($request)
            ->join('belt_roll_production_material as m', 'm.belt_roll_production_id', '=', 'b.id')
            ->leftJoin('product as mp', 'mp.id', '=', 'm.material')
            ->where('b.status', '!=', 'C')
            ->groupBy('m.material', 'm.category_name', 'mp.product_name')
            ->select(
                'm.material',
                'm.category_name',
                'mp.product_name as material_name',
                DB::raw('MAX(m.stock_factor) as stock_factor'),
                DB::raw('COUNT(DISTINCT m.belt_roll_production_id) as batches'),
                DB::raw('COALESCE(SUM(m.required_qty), 0) as planned_qty'),
                DB::raw('COALESCE(SUM(COALESCE(m.actual_qty, m.required_qty)), 0) as actual_qty'),
                DB::raw('COALESCE(SUM(COALESCE(m.actual_qty, m.required_qty) - m.required_qty), 0) as variance')
            );

        if ($request->material != '') {
            $query->where('mp.product_name', 'like', '%' . $request->material . '%');
        }

        $query->orderBy('m.category_name')->orderBy('mp.product_name');

        $rows = collect($query->get())->map(function ($row) {
            $row->unit = $row->stock_factor > 1 ? 'g' : '';
            $row->planned_qty = round($row->planned_qty, 2);
            $row->actual_qty = round($row->actual_qty, 2);
            $row->variance = round($row->variance, 2);

            return $row;
        });

        if ($request->export_excel) {
            return Excel::download(new \App\Exports\RollMaterialConsumptionExport($rows), 'RollMaterialConsumption.xlsx');
        }

        $niwarCodes = DB::table('niwar_codes')->orderBy('type')->get();

        return view('admin.reports.roll_material_consumption', [
            'rows' => $rows,
            'niwarCodes' => $niwarCodes,
            'totalPlanned' => round($rows->sum('planned_qty'), 2),
            'totalActual' => round($rows->sum('actual_qty'), 2),
            'totalVariance' => round($rows->sum('variance'), 2),
        ]);
    }

    /**
     * Stage B: every size cut out of every roll, with the pieces that failed and
     * the meters lost as trim.
     */
    function beltCutting(Request $request)
    {
        $query = $this->beltCuttingBaseQuery($request)
            ->leftJoin('customers as c', 'c.id', '=', 'ct.customer')
            ->select(
                'i.id',
                'ct.cutting_no',
                'ct.wastage_mtr',
                'ct.balance_mtr',
                'ct.status',
                'ct.created_at',
                'r.roll_no',
                'b.batch_no',
                'p.product_name as product',
                'p.value1',
                'p.value2',
                'i.size',
                'i.pieces',
                'i.rejected_pieces',
                'i.meter_per_piece',
                'i.total_meter',
                'c.customer_name as customer'
            )
            ->orderBy('ct.id', 'desc')
            ->orderBy('i.id', 'asc');

        if ($request->export_excel) {
            return Excel::download(new \App\Exports\BeltCuttingExport($query->get()), 'BeltCuttingReport.xlsx');
        }

        $totals = $this->beltCuttingBaseQuery($request)->select(DB::raw(
            'COUNT(DISTINCT ct.id) as cuttings,
             COALESCE(SUM(i.pieces), 0) as pieces,
             COALESCE(SUM(i.rejected_pieces), 0) as rejected,
             COALESCE(SUM(i.total_meter), 0) as meters'
        ))->first();

        // Trim wastage lives on the cutting header, so summing it over the item
        // rows would multiply it by the number of sizes on the entry. Summed over
        // the distinct headers the filtered items belong to instead.
        $wastage = DB::table('belt_cutting as ct')
            ->whereIn('ct.id', $this->beltCuttingBaseQuery($request)->select('ct.id')->distinct())
            ->sum('ct.wastage_mtr');

        $list = $query->paginate(session('records_per_page', 30))->appends($request->all());

        return view('admin.reports.belt_cutting', [
            'list' => $list,
            'totalCuttings' => (int) ($totals->cuttings ?? 0),
            'totalPieces' => (int) ($totals->pieces ?? 0),
            'totalRejected' => (int) ($totals->rejected ?? 0),
            'totalMeters' => round($totals->meters ?? 0, 2),
            'totalWastage' => round($wastage, 2),
        ]);
    }

    /**
     * Cutting lines joined back through roll to batch and narrowed by the filter
     * row. Same reasoning as rollProductionBaseQuery: no select list, rebuilt per
     * caller.
     */
    private function beltCuttingBaseQuery(Request $request)
    {
        $query = DB::table('belt_cutting_item as i')
            ->join('belt_cutting as ct', 'ct.id', '=', 'i.belt_cutting_id')
            ->leftJoin('belt_rolls as r', 'r.id', '=', 'ct.roll_id')
            ->leftJoin('belt_roll_production as b', 'b.id', '=', 'r.belt_roll_production_id')
            ->leftJoin('product as p', 'p.id', '=', 'i.belt_product');

        if ($request->cutting_no != '') {
            $query->where('ct.cutting_no', 'like', '%' . $request->cutting_no . '%');
        }
        if ($request->roll_no != '') {
            $query->where('r.roll_no', 'like', '%' . $request->roll_no . '%');
        }
        if ($request->product != '') {
            $query->where('p.product_name', 'like', '%' . $request->product . '%');
        }
        if ($request->from_date != '') {
            $query->whereDate('ct.created_at', '>=', $request->from_date);
        }
        if ($request->to_date != '') {
            $query->whereDate('ct.created_at', '<=', $request->to_date);
        }
        if ($request->status != '') {
            $query->where('ct.status', $request->status);
        }

        return $query;
    }

    /**
     * Roll batches joined to their semi product and niwar code and narrowed by
     * the shared filter row - the common trunk of the roll production and
     * material consumption reports, and of both their totals queries.
     *
     * Returned without a select list so each caller can put its own on, and
     * rebuilt rather than cloned so no caller inherits another's select bindings.
     */
    private function rollProductionBaseQuery(Request $request)
    {
        $query = DB::table('belt_roll_production as b')
            ->leftJoin('product as p', 'p.id', '=', 'b.roll_product_id')
            ->leftJoin('niwar_codes as n', 'n.id', '=', 'b.niwar_code_id');

        $this->applyRollProductionFilters($query, $request);

        return $query;
    }

    /**
     * Batch-level filters shared by the roll production and consumption reports,
     * so the same filter row means the same thing on both.
     */
    private function applyRollProductionFilters($query, Request $request)
    {
        if ($request->batch_no != '') {
            $query->where('b.batch_no', 'like', '%' . $request->batch_no . '%');
        }
        if ($request->product != '') {
            $query->where('p.product_name', 'like', '%' . $request->product . '%');
        }
        if ($request->niwar_code_id != '') {
            $query->where('b.niwar_code_id', $request->niwar_code_id);
        }
        if ($request->status != '') {
            $query->where('b.status', $request->status);
        }
        if ($request->from_date != '') {
            $query->whereDate('b.created_at', '>=', $request->from_date);
        }
        if ($request->to_date != '') {
            $query->whereDate('b.created_at', '<=', $request->to_date);
        }
    }
}
