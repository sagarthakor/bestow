<?php

namespace App\Http\Controllers;

use App\company;
use App\Exports\quotationExport;
use App\quotation;
use http\Exception\RuntimeException;
use Illuminate\Http\Request;
use Session;
use Excel;

class Reports extends Controller
{
    //
    function quot_report(Request $request)
    {
        $product = new quotation();

        $quot_no=$client_name=$quot_date=$subject=$amount=$quot_stage="";

        $product=$product->select('quotation.*','customers.customer_name','customers.primary_email','customers.secondary_email');
        $product=$product->leftJoin('customers','customers.id','quotation.customer');
        $product=$product->where('quotation.website_id',Session::get('website_id'));

        if($request->quot_no != '')
        {
            $quot_no=$request->quot_no;
            $product = $product->Where('quotation.quotation_no','like','%'.$request->quot_no.'%');
        }
        if($request->client_name != '')
        {
            $client_name=$request->client_name;
            $product = $product->Where('quotation.customer_name','like','%'.$request->client_name.'%');
        }

        if(isset($request->from_date) and isset($request->end_date))
        {
            $from=date('Y-m-d',strtotime($request->from_date));
            $to=date('Y-m-d',strtotime($request->end_date));

            $product = $product->whereBetween('quot_date',[$from,$to]);
        }

        if($request->quot_date != '')
        {
            $quot_date=date('Y-m-d',strtotime($request->quot_date));
            $product = $product->Where('quotation.quot_date','like','%'.$quot_date.'%');
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
        //echo print_r($request->all());

        $product=$product->orderBy('quotation.id','desc');
        $result = $product->paginate(10);

        $company_name=company::select('company_name')->first();


        $stage = array('Created' => 'Created', 'Sent' => 'Sent', 'Reviewing' => 'Reviewing', 'QuoteRivision' => 'QuoteRivision', 'Accepted' => 'Accepted', 'Invoiced' => 'Invoiced', 'Canceled' => 'Canceled');

        if(isset($request->export_excel))
    {
        return Excel::download(new quotationExport($request), 'QuotationReport.xlsx');
    }else{
        return view('admin.reports.quot_report')
            ->with(['list'=>$result,'company'=>$company_name->company_name,'stage'=>$stage]);
    }


    }
}
