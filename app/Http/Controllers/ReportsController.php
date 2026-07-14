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

        $result = $product->paginate(10)->appends($request->all());

        return view('admin.reports.quotation')
                ->with(['list'=>$result,'stage'=>$stage,'totalRecords'=>$totalRecords,'totalAmount'=>$totalAmount]);
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

        $result = $product->paginate(30)->appends($request->all());

        $company_name=company::select('company_name')->first();

        return view('admin.reports.sales')->with(['list'=>$result,'company'=>$company_name->company_name,'totalRecords'=>$totalRecords,'totalAmount'=>$totalAmount]);
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

        $result = $product->paginate(30)->appends($request->all());

        $company_name = company::select('company_name')->first();


        $stage = array('Created' => 'Created', 'Sent' => 'Sent', 'Reviewing' => 'Reviewing', 'QuoteRivision' => 'QuoteRivision', 'Accepted' => 'Accepted', 'Invoiced' => 'Invoiced', 'Canceled' => 'Canceled');


        return view("admin.reports.challan")->with(['list' => $result, 'company' => $company_name->company_name, 'stage' => $stage, 'totalRecords' => $totalRecords, 'totalAmount' => $totalAmount]);
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

        $result = $product->paginate(10)->appends($request->all());

        $company_name = company::select('company_name')->first();

        return view("admin.reports.invoice")->with(['list' => $result, 'company' => $company_name->company_name, 'stage' => $stage, 'totalRecords' => $totalRecords, 'totalAmount' => $totalAmount]);
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
                'p.category',
                'p.subcategory',
                's.id',
                's.salaesorder_no',
                's.customer_name',
                's.salaesorder_date',
                'cat.category_name',
                'sc.subcategory_name'
            )
            ->select(
                'soi.product as product_id',
                'p.product_name as product',
                'p.category as category_id',
                'p.subcategory as subcategory_id',
                's.id as so_id',
                's.salaesorder_no as order_no',
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
                'w.category_id',
                'w.subcategory_id',
                'w.so_id',
                'w.order_no',
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

        $list = $query->paginate(20);

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

        return view('admin.reports.sales_summary', compact('groups', 'period', 'grandTotal', 'grandCount', 'from', 'to'));
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

        return view('admin.reports.salesman_wise_sales', compact('groups', 'period', 'grandTotal', 'from', 'to', 'salesmen'));
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
            ->groupBy('ss.product', 'p.product_name', 'p.item_code', 'p.uom', 'cat.category_name', 'sc.subcategory_name')
            ->select(
                'ss.product as product_id',
                'p.product_name as product',
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

        $list = $query->paginate(20)->appends($request->all());

        return view('admin.reports.stock_available', compact('list', 'subcategories', 'totalProducts', 'totalStockQty'));
    }

    /**
     * RAW Material Required Pending Report.
     * Production batches whose raw material need is not yet fully covered
     * by available stock (need_to_order_stock > 0), grouped by raw material.
     */
    function rawMaterialPending(Request $request)
    {
        $query = DB::table('production_material as pm')
            ->join('product as rm', 'rm.id', '=', 'pm.required_material')
            ->leftJoin('customers as c', 'c.id', '=', 'pm.customer')
            ->leftJoin('product as fp', 'fp.id', '=', 'pm.finish_product')
            ->where('pm.need_to_order_stock', '>', 0)
            ->select(
                'pm.id',
                'pm.batch_no',
                'rm.product_name as raw_material',
                'rm.uom',
                'pm.required_qty',
                'pm.avalible_stock',
                'pm.need_to_order_stock',
                'fp.product_name as finish_product',
                'c.customer_name as customer',
                'pm.timestamp'
            );

        if ($request->raw_material != '') {
            $query->where('rm.product_name', 'like', '%' . $request->raw_material . '%');
        }
        if ($request->customer != '') {
            $query->where('c.customer_name', 'like', '%' . $request->customer . '%');
        }
        if ($request->batch_no != '') {
            $query->where('pm.batch_no', 'like', '%' . $request->batch_no . '%');
        }

        $query->orderBy('rm.product_name', 'asc')->orderBy('pm.id', 'desc');

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

        $list = $query->paginate(20)->appends($request->all());

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

        $list = $query->paginate(20)->appends($request->all());

        return view('admin.reports.belt_production', compact(
            'list', 'totalPlanned', 'totalProduced', 'totalWastage', 'totalPending',
            'pendingBatches', 'completedBatches'
        ));
    }

}
