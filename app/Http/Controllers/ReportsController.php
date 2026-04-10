<?php

namespace App\Http\Controllers;

use App\Exports\OutOfStockExport;
use App\quotation;
use App\salesorder;
use App\company;
use App\stitching_machine;
use App\washing_machine;
use Illuminate\Http\Request;
use App\Exports\invoiceExport;
use App\Exports\quotationExport;
use App\Exports\salesExport;
use Excel;
use Illuminate\Support\Facades\DB;
use session;
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
         $result = $product->paginate(10);
        $stage = array('Created' => 'Created', 'Sent' => 'Sent', 'Reviewing' => 'Reviewing', 'QuoteRivision' => 'QuoteRivision', 'Accepted' => 'Accepted', 'Invoiced' => 'Invoiced', 'Canceled' => 'Canceled');

       /* if(isset($request->tally_quotation))
        {
            return Excel::download(new InvoiceExport($request), 'quotation_tally.xlsx');
        }
        if(isset($request->export_excel))
        {
            return Excel::download(new quotationExport($request), 'QuotationReport.xlsx');
        }*/
        return view('admin.reports.quotation')
                ->with(['list'=>$result,'stage'=>$stage]);
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
            $product = $product->Where('salesorder.status','like','%'.$request->quot_stage.'%');
        }
        //echo print_r($request->all());

        $product=$product->orderBy("id",'desc');
        $product=$product->whereNull("delete_status");
        $result = $product->paginate(30);

        if(isset($request->export_excel))
        {
            return Excel::download(new salesExport($request), 'SalesReport.xlsx');
        }


        $company_name=company::select('company_name')->first();

        return view('admin.reports/sales')->with(['list'=>$result,'company'=>$company_name->company_name]);
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
        $result = $product->paginate(30);

        if(isset($request->export_excel))
        {
            return Excel::download(new challanExport($request), 'ChallanReport.xlsx');
        }

        $company_name = company::select('company_name')->first();


        $stage = array('Created' => 'Created', 'Sent' => 'Sent', 'Reviewing' => 'Reviewing', 'QuoteRivision' => 'QuoteRivision', 'Accepted' => 'Accepted', 'Invoiced' => 'Invoiced', 'Canceled' => 'Canceled');


        return view("admin.reports.challan")->with(['list' => $result, 'company' => $company_name->company_name, 'stage' => $stage]);
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
        $result = $product->paginate(10);

        $company_name = company::select('company_name')->first();



        $stage = array('Created' => 'Created', 'Sent' => 'Sent', 'Reviewing' => 'Reviewing', 'QuoteRivision' => 'QuoteRivision', 'Accepted' => 'Accepted', 'Invoiced' => 'Invoiced', 'Canceled' => 'Canceled');

       /* if(isset($request->export_excel))
        {
            return Excel::download(new invoiceExport($request), 'InvoicesReport.xlsx');
        }*/

        return view("admin.reports.invoice")->with(['list' => $result, 'company' => $company_name->company_name, 'stage' => $stage]);
    }

    public function salesOutOfStockItems(Request $request)
    {
        $categories    = DB::table('category')->orderBy('category_name')->get();
        $subcategories = DB::table('subcategory')->orderBy('subcategory_name')->get();

        $query = DB::table('salesorder_item as soi')
            ->leftJoin('stock_status as ss', 'ss.product', '=', 'soi.product')
            ->leftJoin('product as p', 'p.id', '=', 'soi.product')
            ->leftJoin('salesorder as s', 's.id', '=', 'soi.soid')
            ->leftJoin('category as cat', 'cat.id', '=', 'p.category')
            ->leftJoin('subcategory as sc', 'sc.id', '=', 'p.subcategory')
            ->select(
                'p.product_name as product',
                's.salaesorder_no as order_no',
                's.customer_name as customer',
                's.salaesorder_date as order_date',
                'cat.category_name as category_name',
                'sc.subcategory_name as subcategory_name',
                DB::raw('SUM(soi.qty) as sold_qty'),
                DB::raw('SUM(ss.qty) as stock_qty'),
                DB::raw('(IFNULL(SUM(ss.qty),0) - IFNULL(SUM(soi.qty),0)) as balance')
            )
            ->whereNull('s.delete_status')
            ->groupBy(
                'soi.product',
                'p.product_name',
                's.salaesorder_no',
                's.customer_name',
                's.salaesorder_date',
                'cat.category_name',
                'sc.subcategory_name'
            )
            ->having('balance', '<=', 0)
            ->orderBy('s.salaesorder_date', 'desc');

        // Filters
        if ($request->product) {
            $query->where('p.product_name', 'like', '%' . $request->product . '%');
        }

        if ($request->order_no) {
            $query->where('s.salaesorder_no', 'like', '%' . $request->order_no . '%');
        }

        if ($request->customer) {
            $query->where('s.customer_name', 'like', '%' . $request->customer . '%');
        }

        if ($request->from_date) {
            $query->whereDate('s.salaesorder_date', '>=', $request->from_date);
        }

        if ($request->end_date) {
            $query->whereDate('s.salaesorder_date', '<=', $request->end_date);
        }

        if ($request->category) {
            $query->where('p.category', $request->category);
        }

        if ($request->subcategory) {
            $query->where('p.subcategory', $request->subcategory);
        }

        // Export must be checked BEFORE paginate to export all records
        if ($request->export_excel) {
            $data = $query->get();
            return Excel::download(new OutOfStockExport($data), 'out_of_stock.xlsx');
        }

        $list = $query->paginate(20);

        return view('admin.reports.sales_out_of_stock', compact('list', 'categories', 'subcategories'));
    }

}
