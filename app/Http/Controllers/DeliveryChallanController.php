<?php

namespace App\Http\Controllers;

use App\bom_sub_product;
use App\company;
use App\contact;
use App\customers;
use App\delivery_challan;
use App\delivery_challan_item;
use App\delivery_challan_without;
use App\delivery_challan_item_without;
use App\invoice;
use App\invoice_item;
use App\payment_terms;
use App\product;
use App\purchase;
use App\quotation;
use App\salesman;
use App\state;
use App\salesorder;
use App\salesorder_item;
use App\stock_book;
use App\stock_status;
use App\terms;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Session;
use PDF;
use Storage;
use App\finacial_year;

class DeliveryChallanController extends Controller
{
    //
    function delivery_delete(Request $request)
    {
        date_default_timezone_set("Asia/Kolkata");

        $sales =delivery_challan::find($request->id);
        $sales->delete_status=1;
        $sales->delete_user=Session::get('user_id');
        $sales->delete_datetime=date("d-m-Y h:i:s a");
        if($sales->save())
        {
            return back()->with("message","Challan delete successfully");
        }
    }

    function deleiverychallan_list_preview(Request $request)
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
        $product = $product->where('delivery_challan.customer', $request->id);
        $product = $product->whereNull('delivery_challan.delete_status');
        $product=$product->orderBy('delivery_challan.id','desc');
        $result = $product->paginate(session('records_per_page', 30));

        $company_name = company::select('company_name')->first();


        $stage = array('Created' => 'Created', 'Sent' => 'Sent', 'Reviewing' => 'Reviewing', 'QuoteRivision' => 'QuoteRivision', 'Accepted' => 'Accepted', 'Invoiced' => 'Invoiced', 'Canceled' => 'Canceled');


        return view("admin.challan.deliverychallan_list_preview")->with(['list' => $result, 'company' => $company_name->company_name, 'stage' => $stage]);
    }


    function deliverychallan_print(Request $request)
    {
        $so=delivery_challan::select("delivery_challan.*","customers.primary_email",'customers.customer_name','customers.primary_phone as primphone','contact.contact_name as contactname','contact.primary_phone as contact_phone','website_user.*','customers.tax_preference')
            ->leftJoin('contact','contact.id','delivery_challan.contact_name')
            ->leftJoin('website_user','website_user.id','delivery_challan.user_id')
            ->leftJoin("customers","customers.id","delivery_challan.customer")
            ->where('delivery_challan.id',$request->id)
            ->first();

        $soitem=delivery_challan_item::select('delivery_challan_item.*',"product.item_code",'product.product_name','product.value1','product.value2','product.make','product.model','uom.uom_name','product.product_image','product.material_name','category.category_image')
            ->leftJoin('product','product.id','delivery_challan_item.product')
            ->leftJoin('category','category.id','product.category')
            ->leftJoin("uom","uom.id","product.uom")
            ->where('delivery_challan_item.invoice_no',$so->challan_number)
            ->get();
        $state = state::where('state_name', $so->billing_state)->first();
        //dd($soitem);

        $discsum=delivery_challan_item::select('delivery_challan_item.*','product.product_name','product.make','product.model')
            ->leftJoin('product','product.id','delivery_challan_item.product')
            ->where('delivery_challan_item.invoice_no',$so->challan_number)
            ->sum('delivery_challan_item.discount_amount');

        // dd($discsum);

        $customer=customers::select("customers.*",'state.state_name')
            ->leftJoin("state","state.id","customers.billing_state")
            ->where('customers.id',$so->customer)
            ->first();

        //dd($customer);
        $product=product::select('product.*','gst.gst_per','uom.uom_name')
            ->leftJoin('gst','gst.id','product.gst')
            ->leftJoin('uom','uom.id','product.uom')
            ->where('product.status','product')
            ->orderBy('product.product_name','asc')
            ->get();

        $service=product::select('product.*','gst.gst_per','uom.uom_name')
            ->leftJoin('gst','gst.id','product.gst')
            ->leftJoin('uom','uom.id','product.uom')
            ->where('product.status','service')
            ->orderBy('product.product_name','asc')
            ->get();

        //$term=terms::where('website_id',Session::get('website_id'))->first();

        $module=terms::get()->pluck('module','id')->toArray();

        $company=company::select("company.*","state.state_name")
            ->leftJoin("state","state.id","company.state")
            ->first();
        $filename=$so->challan_number.'_';
        $filename .=$customer->customer_name;
        $filename .=date('ymdhis');
        $filename .='.pdf';

        $quot=$so;
        $quotitem=$soitem;

        //return view('admin.challan.challan_print_old',compact("discsum","quot","company","quotitem","customer"));
        //\LogActivity::addToLog($so->challan_number.' Delivery Challan Print');
        if($customer->tax_preference=="true")
        {
            $pdf = PDF::loadView('admin.challan.challan_print_old',compact('state',"discsum","quot","company","quotitem","customer"));

            return $pdf->download($filename);
        }
        if($customer->tax_preference=="false"){

            $pdf = PDF::loadView('admin.challan.challan_print_old',compact('state',"discsum","quot","company","quotitem","customer"));

            return $pdf->download($filename);
        }
        return back()->with("message","Please select Customer Tax Preference");

    }


    function dc_print(Request $request)
    {
        $so=delivery_challan_without::select("delivery_challan_without.*",'customers.*','customers.primary_phone as primphone','contact.contact_name','contact.contact_name','contact.primary_phone as contact_phone','website_user.*')
            ->leftJoin('contact','contact.id','delivery_challan_without.contact_name')
            ->leftJoin('website_user','website_user.id','delivery_challan_without.user_id')
            ->leftJoin("customers","customers.id","delivery_challan_without.customer")
            ->where('delivery_challan_without.id',$request->id)
            ->first();

        $soitem=delivery_challan_item_without::select('delivery_challan_item_without.*','product.product_name','product.make','product.model','uom.uom_name','product.product_image','product.material_name','category.category_image')
            ->leftJoin('product','product.id','delivery_challan_item_without.product')
            ->leftJoin('category','category.id','product.category')
            ->leftJoin("uom","uom.id","product.uom")
            ->where('delivery_challan_item_without.invoice_no',$so->challan_number)
            ->get();

        //dd($soitem);

        $discsum=delivery_challan_item_without::select('delivery_challan_item_without.*','product.product_name','product.make','product.model')
            ->leftJoin('product','product.id','delivery_challan_item_without.product')
            ->where('delivery_challan_item_without.invoice_no',$so->challan_number)
            ->sum('delivery_challan_item_without.discount_amount');

        // dd($discsum);

        $customer=customers::select("customers.*",'state.state_name')
            ->leftJoin("state","state.id","customers.billing_state")
            ->where('customers.id',$so->customer)
            ->first();

        //dd($customer);
        $product=product::select('product.*','gst.gst_per','uom.uom_name')
            ->leftJoin('gst','gst.id','product.gst')
            ->leftJoin('uom','uom.id','product.uom')
            ->where('product.status','product')
            ->orderBy('product.product_name','asc')
            ->get();

        $service=product::select('product.*','gst.gst_per','uom.uom_name')
            ->leftJoin('gst','gst.id','product.gst')
            ->leftJoin('uom','uom.id','product.uom')
            ->where('product.status','service')
            ->orderBy('product.product_name','asc')
            ->get();

        //$term=terms::where('website_id',Session::get('website_id'))->first();

        $module=terms::query()
            ->get()
            ->pluck('module','id')
            ->toArray();

        $company=company::select("company.*","state.state_name")
            ->leftJoin("state","state.id","company.state")
            ->first();
        $filename=$so->challan_number.'_';
        $filename .=$customer->customer_name;
        $filename .='.pdf';

        $quot=$so;
        $quotitem=$soitem;

        //\LogActivity::addToLog($so->challan_number.' Delivery Challan Print');

        if($customer->tax_preference=="true")
        {
            $pdf = PDF::loadView('admin.challan.challan_print_old',compact("discsum","quot","company","quotitem","customer"));

            return $pdf->download($filename);
        }
        if($customer->tax_preference=="false"){

            $pdf = PDF::loadView('admin.challan.challan_print_old',compact("discsum","quot","company","quotitem","customer"));

            return $pdf->download($filename);
        }
        return back()->with("message","Please select Customer Tax Preference");

    }

    function deliverychallan_preview(Request $request)
    {
        $so=delivery_challan::select("delivery_challan.*",'customers.customer_name',"customers.primary_email",'customers.primary_phone as primphone','contact.contact_name as contactname','contact.primary_phone as contact_phone','website_user.*')
            ->leftJoin('contact','contact.id','delivery_challan.contact_name')
            ->leftJoin('website_user','website_user.id','delivery_challan.user_id')
            ->leftJoin("customers","customers.id","delivery_challan.customer")
            ->where('delivery_challan.id',$request->id)
            ->first();

        $soitem=delivery_challan_item::select('delivery_challan_item.*',"product.item_code",'product.product_name','product.value1','product.value2','product.make','product.model','uom.uom_name','product.product_image','product.material_name','category.category_image')
            ->leftJoin('product','product.id','delivery_challan_item.product')
            ->leftJoin('category','category.id','product.category')
            ->leftJoin("uom","uom.id","product.uom")
            ->where('delivery_challan_item.invoice_no',$so->challan_number)
            ->get();

        //dd($soitem);

        $discsum=delivery_challan_item::select('delivery_challan_item.*','product.product_name','product.make','product.model')
            ->leftJoin('product','product.id','delivery_challan_item.product')
            ->where('delivery_challan_item.invoice_no',$so->challan_number)
            ->sum('delivery_challan_item.discount_amount');

        // dd($discsum);

        $customer=customers::select("customers.*",'state.state_name')
            ->leftJoin("state","state.id","customers.billing_state")
            ->where('customers.id',$so->customer)
            ->first();

        //dd($customer);
        $product=product::select('product.*','gst.gst_per','uom.uom_name')
            ->leftJoin('gst','gst.id','product.gst')
            ->leftJoin('uom','uom.id','product.uom')
            ->where('product.status','product')
            ->orderBy('product.product_name','asc')
            ->get();

        $service=product::select('product.*','gst.gst_per','uom.uom_name')
            ->leftJoin('gst','gst.id','product.gst')
            ->leftJoin('uom','uom.id','product.uom')
            ->where('product.status','service')
            ->orderBy('product.product_name','asc')
            ->get();

        //$term=terms::where('website_id',Session::get('website_id'))->first();

        $module=terms::query()
            ->get()
            ->pluck('module','id')
            ->toArray();

        $company=company::select("company.*","state.state_name")
            ->leftJoin("state","state.id","company.state")
            ->first();

        $filename=$customer->customer_name;
        $filename .=date('ymdhis');
        $filename .='.pdf';

        $quot=$so;
        $quotitem=$soitem;

        //\LogActivity::addToLog($so->challan_number.' Delivery Challan Preview');

        if($customer->tax_preference=="true")
        {
            $pdf = PDF::loadView('admin.challan.challan_print_old',compact("discsum","quot","company","quotitem","customer"));

            $content = $pdf->download()->getOriginalContent();

            Storage::put('public/challan/pdf/'.$filename,$content) ;

            $url=url('/storage/app/public/challan/pdf/'.$filename);
            return Redirect::to($url);
        }
        if($customer->tax_preference=="false"){

            $pdf = PDF::loadView('admin.challan.challan_print_old',compact("discsum","quot","company","quotitem","customer"));


            $content = $pdf->download()->getOriginalContent();

            Storage::put('public/challan/pdf/'.$filename,$content) ;

            $url=url('/storage/app/public/challan/pdf/'.$filename);
            return Redirect::to($url);
        }
        return back()->with("message","Please select Customer Tax Preference");

    }

    function dc_preview(Request $request)
    {
        $so=delivery_challan_without::select("delivery_challan_without.*",'customers.customer_name','customers.primary_phone as primphone','contact.contact_name','contact.contact_name','contact.primary_phone as contact_phone','website_user.*')
            ->leftJoin('contact','contact.id','delivery_challan_without.contact_name')
            ->leftJoin('website_user','website_user.id','delivery_challan_without.user_id')
            ->leftJoin("customers","customers.id","delivery_challan_without.customer")
            ->where('delivery_challan_without.id',$request->id)
            ->first();

        $soitem=delivery_challan_item_without::select('delivery_challan_item_without.*',"product.item_code",'product.product_name','product.make','product.model','uom.uom_name','product.product_image','product.material_name','category.category_image')
            ->leftJoin('product','product.id','delivery_challan_item_without.product')
            ->leftJoin('category','category.id','product.category')
            ->leftJoin("uom","uom.id","product.uom")
            ->where('delivery_challan_item_without.invoice_no',$so->challan_number)
            ->get();

        //dd($soitem);

        $discsum=delivery_challan_item_without::select('delivery_challan_item_without.*','product.product_name','product.make','product.model')
            ->leftJoin('product','product.id','delivery_challan_item_without.product')
            ->where('delivery_challan_item_without.invoice_no',$so->challan_number)
            ->sum('delivery_challan_item_without.discount_amount');

        // dd($discsum);

        $customer=customers::select("customers.*",'state.state_name')
            ->leftJoin("state","state.id","customers.billing_state")
            ->where('customers.id',$so->customer)
            ->first();

        //dd($customer);
        $product=product::select('product.*','gst.gst_per','uom.uom_name')
            ->leftJoin('gst','gst.id','product.gst')
            ->leftJoin('uom','uom.id','product.uom')
            ->where('product.status','product')
            ->orderBy('product.product_name','asc')
            ->get();

        $service=product::select('product.*','gst.gst_per','uom.uom_name')
            ->leftJoin('gst','gst.id','product.gst')
            ->leftJoin('uom','uom.id','product.uom')
            ->where('product.status','service')
            ->orderBy('product.product_name','asc')
            ->get();

        //$term=terms::where('website_id',Session::get('website_id'))->first();

        $module=terms::query()
            ->get()
            ->pluck('module','id')
            ->toArray();

        $company=company::select("company.*","state.state_name")
            ->leftJoin("state","state.id","company.state")
            ->first();

        $filename=$customer->customer_name;
        $filename .=date('ymdhis');
        $filename .='.pdf';

        $quot=$so;
        $quotitem=$soitem;

        //\LogActivity::addToLog($so->challan_number.' Delivery Challan Preview');

        if($customer->tax_preference=="true")
        {
            $pdf = PDF::loadView('admin.challan.challan_print_old',compact("discsum","quot","company","quotitem","customer"));

            $content = $pdf->download()->getOriginalContent();

            Storage::put('public/challan/pdf/'.$filename,$content) ;

            $url=url('/storage/app/public/challan/pdf/'.$filename);
            return Redirect::to($url);
        }
        if($customer->tax_preference=="false"){

            $pdf = PDF::loadView('admin.challan.challan_print_old',compact("discsum","quot","company","quotitem","customer"));


            $content = $pdf->download()->getOriginalContent();

            Storage::put('public/challan/pdf/'.$filename,$content) ;

            $url=url('/storage/app/public/challan/pdf/'.$filename);
            return Redirect::to($url);
        }
        return back()->with("message","Please select Customer Tax Preference");

    }

    function invoice_add(Request $request)
    {
        $invoice_no=0;
        $module=terms::query()
            ->get()
            ->pluck('module','id')
            ->toArray();

        if($request->id==0)
        {
            $quot="";
            $quotitem=array();
            $pterms="";
            $customer=[''=>'select customer']+customers::orderBy('customer_name', "asc")
                    ->pluck("customer_name", "id")
                    ->toArray();
            $payment_terms = payment_terms::orderBy("terms_name", "asc")
                ->get();
            foreach ($payment_terms as $pt) {
                $pterms .= "<option value='" . $pt->days . "'>" . $pt->terms_name . "</option>";
            }

            $duedate = Date('d-m-Y', strtotime('+ 15 days'));

            $salesMan=[''=>'select invoice']+salesman::orderBy('salesman_name', "asc")
                    ->pluck("salesman_name", "id")
                    ->toArray();

        }else{
            $quot = delivery_challan::where('id', $request->id)
                ->first();


            $year = date("y");
            $nextyear = $year + 1;
            $n2 = "INV-";
            $n2 .= str_pad($quot->challan_no, 5, 0, STR_PAD_LEFT);
            $n2 .= '/';
            $n2 .= $year;
            $n2 .= '-';
            $n2 .= $nextyear;

            $invoice_no=$n2;
            //dd($invoice_no);
            $quotitem = delivery_challan_item::select('delivery_challan_item.*',"product.product_image",'product.product_name', 'uom.uom_name', 'stock_status.qty as stockqty')
                ->leftJoin('product', 'product.id', 'delivery_challan_item.product')
                ->leftJoin("uom", "uom.id", "product.uom")
                ->leftJoin("stock_status", "stock_status.product", "delivery_challan_item.product")
                ->where('delivery_challan_item.invid', $quot->id)
                ->get();
            $customer = customers::where('id', $quot->customer)
                ->pluck("customer_name", "id")
                ->toArray();

            $salesMan = salesman::where('id', $quot->salesman_id)
                ->pluck("salesman_name", "id")
                ->toArray();

            $customer_terms = customers::where('id', $quot->customer)
                ->first();


            if(empty($quot->contact_name))
            {
            $contact_name=[''=>'select contact']+contact::query()
                    ->where('customer',$quot->customer)
                    ->orderBy('contact_name','asc')
                    ->get()
                    ->pluck('contact_name','id')
                    ->toArray();
            }else{
            $cname=contact::select('id','contact_name')->where('id',$quot->contact_name)->first();

            $contact_name=[$cname->id=>$cname->contact_name]+contact::query()
                    ->where('customer',$quot->customer)
                    ->orderBy('contact_name','asc')
                    ->get()
                    ->pluck('contact_name','id')
                    ->toArray();
            }

            $module=terms::query()
            ->get()
            ->pluck('module','id')
            ->toArray();

            //dd($customer_terms);
            $pterms = "";
            $duedate = date('d-m-Y');
            if (empty($customer_terms->payment_terms)) {
                $payment_terms = payment_terms::orderBy("terms_name", "asc")
                    ->get();
                foreach ($payment_terms as $pt) {
                    $pterms .= "<option value='" . $pt->days . "'>" . $pt->terms_name . "</option>";
                }

            } else {

                $payment_terms = payment_terms::where("days", $customer_terms->payment_terms)
                    ->first();

                $pterms .= "<option value='" . $payment_terms->days . "'>" . $payment_terms->terms_name . "</option>";

                $duedate = Date('d-m-Y', strtotime('+' . $payment_terms->days . ' days'));
                $payment_terms1 = payment_terms::orderBy("terms_name", "asc")
                    ->get();
                foreach ($payment_terms1 as $pt) {
                    $pterms .= "<option value='" . $pt->days . "'>" . $pt->terms_name . "</option>";
                }
                //$payment_terms=[$payment_terms->id=>$payment_terms->terms_name];
            }
        }


        // Product/service/BOM picking on this page uses the select2 AJAX
        // search endpoint (product_search_options) now, so the full
        // product-table dump that used to be passed to the view is gone.

        //$term=terms::where('website_id',Session::get('website_id'))->first();


        $stockstatus = stock_status::get();

        return view("admin.invoice.deliverychallan_invoice")->with(["contact_name"=>$contact_name,'invoice_no'=>$invoice_no,'stockstatus' => $stockstatus, 'duedate' => $duedate, 'payment_terms' => $pterms, 'data' => $quot, 'quotitem' => $quotitem, 'customer' => $customer, 'module' => $module, 'salesMan' => $salesMan]);

    }

    function deliverychallan_view(Request $request)
    {
        $quot = delivery_challan::select("delivery_challan.*","customers.customer_name","contact.contact_name as contactname")
            ->leftJoin("customers","customers.id","delivery_challan.customer")
            ->leftJoin("contact","contact.id","delivery_challan.contact_name")
            ->where("delivery_challan.id", $request->id)
            ->first();

        $quotitem = delivery_challan_item::select('delivery_challan_item.*',"product.product_image", 'product.product_name', 'product.item_code', 'product.value1', 'product.value2', 'uom.uom_name', 'stock_status.qty as stockqty')
            ->leftJoin('product', 'product.id', 'delivery_challan_item.product')
            ->leftJoin("uom", "uom.id", "product.uom")
            ->leftJoin("stock_status", "stock_status.product", "delivery_challan_item.product")
            ->where('delivery_challan_item.invoice_no', $quot->challan_number)
            ->get();

        //\LogActivity::addToLog($quot->challan_number.' Delivery Challan View');

        return view("admin/challan/deliverychallan_view")
            ->with(['data'=>$quot,'item'=>$quotitem]);
    }

    function so_deliverychallan_update(Request $request)
    {
        //dd($request->all());
        date_default_timezone_set('Asia/Kolkata');

        $customer_name = customers::find($request->customer);

        $request->validate([

            'customer' => 'required',
            'invoice_date' => 'required',
            'product' => 'required',
            'billing_address' => 'required',
            'shipping_address' => 'required',
            'billing_city' => 'required',
            'shipping_city' => 'required',
            'billing_state' => 'required',
            'shipping_state' => 'required',
            'billing_postalcode' => 'required',
            'shipping_postalcode' => 'required',
            'billing_country' => 'required',
            'shipping_country' => 'required',
            'term_condition' => 'required',

        ]);

        $now = date('Y-m-d',strtotime($request->invoice_date));

        $finacial_year= finacial_year::where('start_date', '<=', $now)->where('end_date', '>=', $now)
       ->first();

        $invoice = delivery_challan::find($request->id);

        $invoice->customer = $request->customer;
        $invoice->contact_name=$request->contact_name;
        $invoice->subject = $request->subject;
        $invoice->salaesorder_no = $request->salaesorder_no;
        $invoice->invoice_date = date('Y-m-d', strtotime($request->invoice_date));
        $invoice->payment_terms = $request->payment_terms;
        $invoice->invoice_duedate = date('Y-m-d', strtotime($request->invoice_duedate));
        $invoice->status = $request->status;
        $invoice->remark = $request->remark;
        $invoice->item_total = $request->item_total;
        $invoice->discount_total = $request->discount_total;
        $invoice->cgsttotal = $request->cgsttotal;
        $invoice->sgsttotal = $request->sgsttotal;
        $invoice->igsttotal = $request->igsttotal;
        $invoice->adjustment = $request->adjustment;
        $invoice->grand_total = $request->grand_total;

        $invoice->billing_address = $request->billing_address;
        $invoice->shipping_address = $request->shipping_address;
        $invoice->billing_city = $request->billing_city;
        $invoice->shipping_city = $request->shipping_city;
        $invoice->billing_state = $request->billing_state;
        $invoice->shipping_state = $request->shipping_state;
        $invoice->billing_postalcode = $request->billing_postalcode;
        $invoice->shipping_postalcode = $request->shipping_postalcode;
        $invoice->billing_country = $request->billing_country;
        $invoice->shipping_country = $request->shipping_country;
        $invoice->term_condition=$request->term_condition;
        $invoice->module=$request->module;
        $invoice->user_id=Session::get("user_id");
        $invoice->website_id=Session::get("website_id");

        $invoice->purchase_order=$request->purchase_order;
        $invoice->purchase_order_date=$request->purchase_order_date;
        $invoice->quot_date=$request->quot_date;
        $invoice->quotation_no=$request->quotation_no;
        $invoice->salaesorder_date=$request->salaesorder_date;
        //$invoice->purchase_order=$request->purchase_order;

        $invoice->finacial_year=$finacial_year->id;
        $invoice->updated_by = Auth::user()->id;

        if ($invoice->save()) {

           // \LogActivity::addToLog($invoice->challan_number.' Delivery Challan Update');

            delivery_challan_item::where("invoice_no", $invoice->challan_number)->delete();

            if (empty($request->product)) {
            } else {

                $totproduct = count($request->product);
                for ($i = 0; $i < $totproduct; $i++) {
                    $item = new delivery_challan_item();
                    $item->invid = $invoice->id;
                    $item->invoice_no = $invoice->challan_number;
                    $item->salaesorder_no=$request->salaesorder_no;
                    $item->product = $request->product[$i];
                    $item->description = $request->description[$i];
                    $item->inner_diamitter = $request->inner_diamitter[$i] ?? "";
                    $item->outer_diamitter = $request->outer_diamitter[$i] ?? "";
                    $item->thikness = $request->thikness[$i] ?? "";
                    $item->hsn = $request->hsn[$i] ?? "";
                    $item->qty = $request->qty[$i] ?? "";
                    $item->price = $request->price[$i] ?? "";
                    $item->total_amount = $request->total_amount[$i];
                    $item->discount_per = $request->discount_per[$i];
                    $item->discount_amount = $request->discount_amount[$i];
                    $item->cgst_per = $request->cgst_per[$i];
                    $item->cgst_amount = $request->cgst_amount[$i];
                    $item->sgst_per = $request->sgst_per[$i];
                    $item->sgst_amount = $request->sgst_amount[$i];
                    $item->igst_per = $request->gst_per[$i];
                    $item->igst_amount = $request->gst_amount[$i];
                    $item->net_price = $request->net_price[$i];

                    if ($item->save()) {

                        $prod = product::find($request->product[$i]);

                        if ($prod->status == "bom") {
                            $bom_sub_product = bom_sub_product::where("bom_id", $request->product[$i])
                                ->get();
                            foreach ($bom_sub_product as $bprod) {
                                $checkproduct = stock_status::where("product", $bprod->product[$i])
                                    ->first();

                                //stock adjust first

                                if (empty($checkproduct)) {
                                } else {
                                    $qty = $checkproduct->qty + $bprod->qty[$i];
                                    $checkproduct->qty = $qty;
                                    $checkproduct->save();

                                    $book = new stock_book();
                                    $book->purchase_no = $invoice->invoice_no;
                                    $book->product = $bprod->product[$i];
                                    $book->inward_date = date('Y-m-d');
                                    $book->inward_qty = $bprod->qty[$i];
                                    $book->remaining_qty = $qty;
                                    $book->particular = "Delivery Challan adjust edit";
                                    $book->created_time = date('d-m-Y h:i:s a');
                                    $book->user_id = Session::get("user_id");
                                    $book->save();

                                }
                                //

                                if (empty($checkproduct)) {
                                } else {
                                    if ($checkproduct->qty > 0) {

                                        $qty = $checkproduct->qty - $bprod->qty[$i];
                                        $checkproduct->qty = $qty;
                                        $checkproduct->save();

                                        $book = new stock_book();
                                        $book->purchase_no = $invoice->invoice_no;
                                        $book->product = $bprod->product[$i];
                                        $book->inward_date = date('Y-m-d');
                                        $book->outward_qty = $bprod->qty[$i];
                                        $book->remaining_qty = $qty;
                                        $book->particular = "Delivery Challan";
                                        $book->created_time = date('d-m-Y h:i:s a');
                                        $book->user_id = Session::get("user_id");
                                        $book->save();
                                    }
                                }
                            }
                        }

                        $checkproduct = stock_status::where("product", $request->product[$i])
                            ->first();
                            //dd($checkproduct);
                        if (empty($checkproduct)) {
                        } else {
                            if ($checkproduct->qty > 0) {

                                //dd($checkproduct);
                                $olditem=delivery_challan_item::where("invoice_no", $invoice->challan_number)
                                ->first();

                                $qty = $checkproduct->qty + $request->old_qty[$i] ?? 0;
                                $checkproduct->qty = $qty;
                                $checkproduct->save();

                                $book = new stock_book();
                                $book->purchase_no = $invoice->invoice_no;
                                $book->product = $request->product[$i];
                                $book->inward_date = date('Y-m-d');
                                $book->inward_qty =$request->old_qty[$i] ?? 0;
                                $book->remaining_qty = $qty;
                                $book->particular = "Delivery Challan Adjust Edit";
                                $book->created_time = date('d-m-Y h:i:s a');
                                $book->user_id = Session::get("user_id");
                                $book->save();
                            }
                        }

                        if (empty($checkproduct)) {
                        } else {
                            if ($checkproduct->qty > 0) {

                                //dd($checkproduct);
                                // $olditem=delivery_challan_item::where("invoice_no", $invoice->challan_number)
                                // ->first();

                                $qty = $checkproduct->qty - $request->qty[$i];
                                $checkproduct->qty = $qty;
                                $checkproduct->save();

                                $book = new stock_book();
                                $book->purchase_no = $invoice->invoice_no;
                                $book->product = $request->product[$i];
                                $book->inward_date = date('Y-m-d');
                                $book->outward_qty =$request->qty[$i];
                                $book->remaining_qty = $qty;
                                $book->particular = "Delivery Challan";
                                $book->created_time = date('d-m-Y h:i:s a');
                                $book->user_id = Session::get("user_id");
                                $book->save();
                            }
                        }

                    }
                }


            }


            if(empty($request->salaesorder_no)){}else{
                $so= salesorder::where('salaesorder_no',$request->salaesorder_no)->first();
                $so->deliverychallan_status="Y";
                $so->save();
            }

            return redirect()->route("admin.challan.list")->with("message", "Deliverychallan Update Successfully");
        }
    }

    function so_dc_update(Request $request)
    {

        date_default_timezone_set('Asia/Kolkata');

        $customer_name = customers::find($request->customer);

        $request->validate([

            'customer' => 'required',
            'invoice_date' => 'required',
            'product' => 'required',
            'billing_address' => 'required',
            'shipping_address' => 'required',
            'billing_city' => 'required',
            'shipping_city' => 'required',
            'billing_state' => 'required',
            'shipping_state' => 'required',
            'billing_postalcode' => 'required',
            'shipping_postalcode' => 'required',
            'billing_country' => 'required',
            'shipping_country' => 'required',
            'term_condition' => 'required',

        ]);

        $now = date('Y-m-d',strtotime($request->invoice_date));

        $finacial_year= finacial_year::where('start_date', '<=', $now)->where('end_date', '>=', $now)
       ->first();

        $invoice = delivery_challan_without::find($request->id);

        $invoice->customer = $request->customer;
        $invoice->contact_name=$request->contact_name;
        $invoice->subject = $request->subject;
        $invoice->salaesorder_no = $request->salaesorder_no;
        $invoice->invoice_date = date('Y-m-d', strtotime($request->invoice_date));
        $invoice->payment_terms = $request->payment_terms;
        $invoice->invoice_duedate = date('Y-m-d', strtotime($request->invoice_duedate));
        $invoice->status = $request->status;
        $invoice->remark = $request->remark;
        $invoice->item_total = $request->item_total;
        $invoice->discount_total = $request->discount_total;
        $invoice->cgsttotal = $request->cgsttotal;
        $invoice->sgsttotal = $request->sgsttotal;
        $invoice->igsttotal = $request->igsttotal;
        $invoice->adjustment = $request->adjustment;
        $invoice->grand_total = $request->grand_total;

        $invoice->billing_address = $request->billing_address;
        $invoice->shipping_address = $request->shipping_address;
        $invoice->billing_city = $request->billing_city;
        $invoice->shipping_city = $request->shipping_city;
        $invoice->billing_state = $request->billing_state;
        $invoice->shipping_state = $request->shipping_state;
        $invoice->billing_postalcode = $request->billing_postalcode;
        $invoice->shipping_postalcode = $request->shipping_postalcode;
        $invoice->billing_country = $request->billing_country;
        $invoice->shipping_country = $request->shipping_country;
        $invoice->term_condition=$request->term_condition;
        $invoice->module=$request->module;
        $invoice->user_id=Session::get("user_id");
        $invoice->website_id=Session::get("website_id");

        $invoice->po_no=$request->po_no;
        $invoice->finacial_year=$finacial_year->id;
        $invoice->updated_by = Auth::user()->id;

        if ($invoice->save()) {
            //\LogActivity::addToLog($invoice->challan_number.' Delivery Challan Update');

            delivery_challan_item_without::where("invoice_no", $invoice->challan_number)->delete();

            if (empty($request->product)) {
            } else {

                $totproduct = count($request->product);
                for ($i = 0; $i < $totproduct; $i++) {
                    $item = new delivery_challan_item_without();
                    $item->invid = $invoice->id;
                    $item->invoice_no = $invoice->challan_number;
                    $item->product = $request->product[$i];
                    $item->description = $request->description[$i];
                    $item->inner_diamitter = $request->inner_diamitter[$i];
                    $item->outer_diamitter = $request->outer_diamitter[$i];
                    $item->thikness = $request->thikness[$i];
                    $item->hsn = $request->hsn[$i];
                    $item->qty = $request->qty[$i];
                    $item->price = $request->price[$i];
                    $item->total_amount = $request->total_amount[$i];
                    $item->discount_per = $request->discount_per[$i];
                    $item->discount_amount = $request->discount_amount[$i];
                    $item->cgst_per = $request->cgst_per[$i];
                    $item->cgst_amount = $request->cgst_amount[$i];
                    $item->sgst_per = $request->sgst_per[$i];
                    $item->sgst_amount = $request->sgst_amount[$i];
                    $item->igst_per = $request->gst_per[$i];
                    $item->igst_amount = $request->gst_amount[$i];
                    $item->net_price = $request->net_price[$i];

                    if ($item->save()) {

                        $prod = product::find($request->product[$i]);

                        if ($prod->status == "bom") {
                            $bom_sub_product = bom_sub_product::where("bom_id", $request->product[$i])
                                ->get();
                            foreach ($bom_sub_product as $bprod) {
                                $checkproduct = stock_status::where("product", $bprod->product[$i])
                                    ->first();

                                //stock adjust first

                                if (empty($checkproduct)) {
                                } else {
                                    $qty = $checkproduct->qty + $bprod->qty[$i];
                                    $checkproduct->qty = $qty;
                                    $checkproduct->save();

                                    $book = new stock_book();
                                    $book->purchase_no = $invoice->invoice_no;
                                    $book->product = $bprod->product[$i];
                                    $book->inward_date = date('Y-m-d');
                                    $book->inward_qty = $bprod->qty[$i];
                                    $book->remaining_qty = $qty;
                                    $book->particular = "Invoice adjust edit";
                                    $book->created_time = date('d-m-Y h:i:s a');
                                    $book->user_id = Session::get("user_id");
                                    $book->save();

                                }
                                //

                                if (empty($checkproduct)) {
                                } else {
                                    if ($checkproduct->qty > 0) {

                                        $qty = $checkproduct->qty - $bprod->qty[$i];
                                        $checkproduct->qty = $qty;
                                        $checkproduct->save();

                                        $book = new stock_book();
                                        $book->purchase_no = $invoice->invoice_no;
                                        $book->product = $bprod->product[$i];
                                        $book->inward_date = date('Y-m-d');
                                        $book->outward_qty = $bprod->qty[$i];
                                        $book->remaining_qty = $qty;
                                        $book->particular = "Invoice";
                                        $book->created_time = date('d-m-Y h:i:s a');
                                        $book->user_id = Session::get("user_id");
                                        $book->save();
                                    }
                                }
                            }
                        }

                        $checkproduct = stock_status::where("product", $request->product[$i])
                            ->first();
                            //dd($checkproduct);
                        if (empty($checkproduct)) {
                        } else {
                            if ($checkproduct->qty > 0) {

                                //dd($checkproduct);
                                $olditem=delivery_challan_item_without::where("invoice_no", $invoice->challan_number)
                                ->first();

                                $qty = $checkproduct->qty + $olditem->qty;
                                $checkproduct->qty = $qty;
                                $checkproduct->save();

                                $book = new stock_book();
                                $book->purchase_no = $invoice->invoice_no;
                                $book->product = $request->product[$i];
                                $book->inward_date = date('Y-m-d');
                                $book->inward_qty =$olditem->qty;
                                $book->remaining_qty = $qty;
                                $book->particular = "Invoice Adjust Edit";
                                $book->created_time = date('d-m-Y h:i:s a');
                                $book->user_id = Session::get("user_id");
                                $book->save();
                            }
                        }

                        if (empty($checkproduct)) {
                        } else {
                            if ($checkproduct->qty > 0) {

                                //dd($checkproduct);
                                // $olditem=delivery_challan_item_without::where("invoice_no", $invoice->challan_number)
                                // ->first();

                                $qty = $checkproduct->qty - $request->qty[$i];
                                $checkproduct->qty = $qty;
                                $checkproduct->save();

                                $book = new stock_book();
                                $book->purchase_no = $invoice->invoice_no;
                                $book->product = $request->product[$i];
                                $book->inward_date = date('Y-m-d');
                                $book->outward_qty =$request->qty[$i];
                                $book->remaining_qty = $qty;
                                $book->particular = "Invoice";
                                $book->created_time = date('d-m-Y h:i:s a');
                                $book->user_id = Session::get("user_id");
                                $book->save();
                            }
                        }

                    }
                }


            }


            if(empty($request->salaesorder_no)){}else{
                $so= salesorder::where('salaesorder_no',$request->salaesorder_no)->first();
                $so->deliverychallan_status="Y";
                $so->save();
            }

            return redirect()->route("dc/list")->with("message", "Deliverychallan Update Successfully");
        }
    }

    function deliverychallan_duplicate(Request $request)
    {
        $quot = delivery_challan::where("id", $request->id)
            ->first();

        if(empty($quot->contact_name))
        {
            $contact_name=[''=>'select contact']+contact::query()
                    ->where('customer',$quot->customer)
                    ->orderBy('contact_name','asc')
                    ->get()
                    ->pluck('contact_name','id')
                    ->toArray();
        }else{
            $cname=contact::select('id','contact_name')->where('id',$quot->contact_name)->first();

            $contact_name=[$cname->id=>$cname->contact_name]+contact::query()
                    ->where('customer',$quot->customer)
                    ->orderBy('contact_name','asc')
                    ->get()
                    ->pluck('contact_name','id')
                    ->toArray();
        }

        $quotitem = delivery_challan_item::select('delivery_challan_item.*', 'product.product_name', 'uom.uom_name', 'stock_status.qty as stockqty')
            ->leftJoin('product', 'product.id', 'delivery_challan_item.product')
            ->leftJoin("uom", "uom.id", "product.uom")
            ->leftJoin("stock_status", "stock_status.product", "delivery_challan_item.product")
            ->where('delivery_challan_item.invoice_no', $quot->challan_number)
            ->get();

        $customer = customers::where('id', $quot->customer)
            ->pluck("customer_name", "id")
            ->toArray();


        $product = product::select('product.*', 'gst.gst_per', 'uom.uom_name','stock_status.qty as stockqty')
            ->leftJoin('gst', 'gst.id', 'product.gst')
            ->leftJoin('uom', 'uom.id', 'product.uom')
            ->leftJoin("stock_status","stock_status.product","product.id")
            ->where('product.status', 'product')
            ->orderBy('product.product_name', 'asc')
            ->get();

        $service = product::select('product.*', 'gst.gst_per', 'uom.uom_name')
            ->leftJoin('gst', 'gst.id', 'product.gst')
            ->leftJoin('uom', 'uom.id', 'product.uom')
            ->where('product.status', 'service')
            ->orderBy('product.product_name', 'asc')
            ->get();

        $bom = product::select('product.*', 'gst.gst_per', 'uom.uom_name')
            ->leftJoin('gst', 'gst.id', 'product.gst')
            ->leftJoin('uom', 'uom.id', 'product.uom')
            ->where('product.status', 'bom')
            ->orderBy('product.product_name', 'asc')
            ->get();

        //$term=terms::where('website_id',Session::get('website_id'))->first();

        $term = terms::query()
            ->get();

        $customer_terms = customers::where('id', $quot->customer)
            ->first();
        //dd($customer_terms);
        $pterms = "";
        $duedate = date('d-m-Y');
        if (empty($customer_terms->payment_terms)) {
            $payment_terms = payment_terms::orderBy("terms_name", "asc")
                ->get();
            foreach ($payment_terms as $pt) {
                $pterms .= "<option value='" . $pt->days . "'>" . $pt->terms_name . "</option>";
            }

        } else {

            $payment_terms = payment_terms::where("days", $customer_terms->payment_terms)
                ->first();

            $pterms .= "<option value='" . $payment_terms->days . "'>" . $payment_terms->terms_name . "</option>";

            $duedate = Date('d-m-Y', strtotime('+' . $payment_terms->days . ' days'));
            $payment_terms1 = payment_terms::orderBy("terms_name", "asc")
                ->get();
            foreach ($payment_terms1 as $pt) {
                $pterms .= "<option value='" . $pt->days . "'>" . $pt->terms_name . "</option>";
            }
            //$payment_terms=[$payment_terms->id=>$payment_terms->terms_name];
        }

        $stockstatus = stock_status::get();

        $module=[''=>'select terms']+terms::query()
            ->get()
            ->pluck('module','id')
            ->toArray();

        return view("admin.challan.deliverychallanDuplicate")
            ->with(['contact_name'=>$contact_name,'module'=>$module,'stockstatus' => $stockstatus, 'duedate' => $duedate, 'payment_terms' => $pterms, 'bom' => $bom, 'data' => $quot, 'quotitem' => $quotitem, 'customer' => $customer, 'product' => $product, 'service' => $service, 'term' => $term]);

    }

    function deliverychallan_edit(Request $request)
    {
        $quot = delivery_challan::where("id", $request->id)
            ->first();

        if(empty($quot->contact_name))
        {
            $contact_name=[''=>'select contact']+contact::query()
                    ->where('customer',$quot->customer)
                    ->orderBy('contact_name','asc')
                    ->get()
                    ->pluck('contact_name','id')
                    ->toArray();
        }else{
            $cname=contact::select('id','contact_name')->where('id',$quot->contact_name)->first();

            $contact_name=[$cname->id=>$cname->contact_name]+contact::query()
                    ->where('customer',$quot->customer)
                    ->orderBy('contact_name','asc')
                    ->get()
                    ->pluck('contact_name','id')
                    ->toArray();
        }

        $quotitem = delivery_challan_item::select('delivery_challan_item.*',"product.product_image",'product.product_name', 'product.item_code', 'product.value1', 'product.value2', 'uom.uom_name', 'stock_status.qty as stockqty')
            ->leftJoin('product', 'product.id', 'delivery_challan_item.product')
            ->leftJoin("uom", "uom.id", "product.uom")
            ->leftJoin("stock_status", "stock_status.product", "delivery_challan_item.product")
            ->where('delivery_challan_item.invoice_no', $quot->challan_number)
            ->get();

        $customer = customers::where('id', $quot->customer)
            ->pluck("customer_name", "id")
            ->toArray();


        // Product/service/BOM picking on this page uses the select2 AJAX
        // search endpoint (product_search_options) now, so the full
        // product-table dump that used to be passed to the view is gone.

        //$term=terms::where('website_id',Session::get('website_id'))->first();

        $term = terms::query()
            ->get();

        $customer_terms = customers::where('id', $quot->customer)
            ->first();
        //dd($customer_terms);
        $pterms = "";
        $duedate = date('d-m-Y');
        if (empty($customer_terms->payment_terms)) {
            $payment_terms = payment_terms::orderBy("terms_name", "asc")
                ->get();
            foreach ($payment_terms as $pt) {
                $pterms .= "<option value='" . $pt->days . "'>" . $pt->terms_name . "</option>";
            }

        } else {

            $payment_terms = payment_terms::where("days", $customer_terms->payment_terms)
                ->first();

            $pterms .= "<option value='" . $payment_terms->days . "'>" . $payment_terms->terms_name . "</option>";

            $duedate = Date('d-m-Y', strtotime('+' . $payment_terms->days . ' days'));
            $payment_terms1 = payment_terms::orderBy("terms_name", "asc")
                ->get();
            foreach ($payment_terms1 as $pt) {
                $pterms .= "<option value='" . $pt->days . "'>" . $pt->terms_name . "</option>";
            }
            //$payment_terms=[$payment_terms->id=>$payment_terms->terms_name];
        }

        $stockstatus = stock_status::get();

        $module=[''=>'select terms']+terms::query()
            ->get()
            ->pluck('module','id')
            ->toArray();

        return view("admin.challan.deliverychallanedit")
            ->with(['contact_name'=>$contact_name,'module'=>$module,'stockstatus' => $stockstatus, 'duedate' => $duedate, 'payment_terms' => $pterms, 'data' => $quot, 'quotitem' => $quotitem, 'customer' => $customer, 'term' => $term]);

    }

    function dc_edit(Request $request)
    {
        $quot = delivery_challan_without::where("id", $request->id)
            ->first();

        if(empty($quot->contact_name))
        {
            $contact_name=[''=>'select contact']+contact::query()
                    ->where('customer',$quot->customer)
                    ->orderBy('contact_name','asc')
                    ->get()
                    ->pluck('contact_name','id')
                    ->toArray();
        }else{
            $cname=contact::select('id','contact_name')->where('id',$quot->contact_name)->first();

            $contact_name=[$cname->id=>$cname->contact_name]+contact::query()
                    ->where('customer',$quot->customer)
                    ->orderBy('contact_name','asc')
                    ->get()
                    ->pluck('contact_name','id')
                    ->toArray();
        }

        $quotitem = delivery_challan_item_without::select('delivery_challan_item_without.*', 'product.product_name', 'uom.uom_name', 'stock_status.qty as stockqty')
            ->leftJoin('product', 'product.id', 'delivery_challan_item_without.product')
            ->leftJoin("uom", "uom.id", "product.uom")
            ->leftJoin("stock_status", "stock_status.product", "delivery_challan_item_without.product")
            ->where('delivery_challan_item_without.invoice_no', $quot->challan_number)
            ->get();

        $customer = customers::where('id', $quot->customer)
            ->pluck("customer_name", "id")
            ->toArray();


        $product = product::select('product.*', 'gst.gst_per', 'uom.uom_name','stock_status.qty as stockqty')
            ->leftJoin('gst', 'gst.id', 'product.gst')
            ->leftJoin('uom', 'uom.id', 'product.uom')
            ->leftJoin("stock_status","stock_status.product","product.id")
            ->where('product.status', 'product')
            ->orderBy('product.product_name', 'asc')
            ->get();

        $service = product::select('product.*', 'gst.gst_per', 'uom.uom_name')
            ->leftJoin('gst', 'gst.id', 'product.gst')
            ->leftJoin('uom', 'uom.id', 'product.uom')
            ->where('product.status', 'service')
            ->orderBy('product.product_name', 'asc')
            ->get();

        $bom = product::select('product.*', 'gst.gst_per', 'uom.uom_name')
            ->leftJoin('gst', 'gst.id', 'product.gst')
            ->leftJoin('uom', 'uom.id', 'product.uom')
            ->where('product.status', 'bom')
            ->orderBy('product.product_name', 'asc')
            ->get();

        //$term=terms::where('website_id',Session::get('website_id'))->first();

        $term = terms::query()
            ->get();

        $customer_terms = customers::where('id', $quot->customer)
            ->first();
        //dd($customer_terms);
        $pterms = "";
        $duedate = date('d-m-Y');
        if (empty($customer_terms->payment_terms)) {
            $payment_terms = payment_terms::orderBy("terms_name", "asc")
                ->get();
            foreach ($payment_terms as $pt) {
                $pterms .= "<option value='" . $pt->days . "'>" . $pt->terms_name . "</option>";
            }

        } else {

            $payment_terms = payment_terms::where("days", $customer_terms->payment_terms)
                ->first();

            $pterms .= "<option value='" . $payment_terms->days . "'>" . $payment_terms->terms_name . "</option>";

            $duedate = Date('d-m-Y', strtotime('+' . $payment_terms->days . ' days'));
            $payment_terms1 = payment_terms::orderBy("terms_name", "asc")
                ->get();
            foreach ($payment_terms1 as $pt) {
                $pterms .= "<option value='" . $pt->days . "'>" . $pt->terms_name . "</option>";
            }
            //$payment_terms=[$payment_terms->id=>$payment_terms->terms_name];
        }

        $stockstatus = stock_status::get();

        $module=[''=>'select terms']+terms::query()
            ->get()
            ->pluck('module','id')
            ->toArray();

        return view("admin.challan.dcedit")
            ->with(['contact_name'=>$contact_name,'module'=>$module,'stockstatus' => $stockstatus, 'duedate' => $duedate, 'payment_terms' => $pterms, 'bom' => $bom, 'data' => $quot, 'quotitem' => $quotitem, 'customer' => $customer, 'product' => $product, 'service' => $service, 'term' => $term]);

    }

    function deliverychallan_list(Request $request)
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
        //$product = $product->where('delivery_challan.finacial_year', Session::get('finacial_year_id'));
        $product=$product->orderBy('delivery_challan.id','desc');
        $product = $product->whereNull('delivery_challan.delete_status');
        $result = $product->paginate(session('records_per_page', 30));

        $company_name = company::select('company_name')->first();


        $stage = array('Created' => 'Created', 'Sent' => 'Sent', 'Reviewing' => 'Reviewing', 'QuoteRivision' => 'QuoteRivision', 'Accepted' => 'Accepted', 'Invoiced' => 'Invoiced', 'Canceled' => 'Canceled');


        return view("admin.challan.deliverychallan_list")->with(['list' => $result, 'company' => $company_name->company_name, 'stage' => $stage]);

    }

    function dc_list(Request $request)
    {

        $quot_no = $client_name = $quot_date = $subject = $amount = $quot_stage = "";

        $product = new delivery_challan_without();

        $quot_no=$client_name=$quot_date=$subject=$amount=$quot_stage="";

        $product=$product->select('delivery_challan_without.*','customers.customer_name','customers.primary_email','customers.secondary_email');
        $product=$product->leftJoin('customers','customers.id','delivery_challan_without.customer');

        if($request->invoice_no != '')
        {
            $quot_no=$request->quot_no;
            $product = $product->Where('delivery_challan_without.challan_number','like','%'.$request->invoice_no.'%');
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
            $product = $product->whereBetween('delivery_challan_without.invoice_date',[$from,$to]);
        }
        if($request->subject != '')
        {
            $subject=$request->subject;
            $product = $product->Where('delivery_challan_without.subject','like','%'.$request->subject.'%');
        }
        if($request->amount != '')
        {
            $amount=$request->amount;
            $product = $product->Where('delivery_challan_without.grand_total','like','%'.$request->amount.'%');
        }
        if($request->status != '')
        {
            $quot_stage=$request->status;
            $product = $product->Where('delivery_challan_without.status','like','%'.$request->status.'%');
        }


        //echo print_r($request->all());
        $product = $product->where('delivery_challan_without.finacial_year', Session::get('finacial_year_id'));
        $product=$product->orderBy('delivery_challan_without.id','desc');
        $product = $product->whereNull('delivery_challan.delete_status');
        $result = $product->paginate(session('records_per_page', 30));

        $company_name = company::select('company_name')->first();


        $stage = array('Created' => 'Created', 'Sent' => 'Sent', 'Reviewing' => 'Reviewing', 'QuoteRivision' => 'QuoteRivision', 'Accepted' => 'Accepted', 'Invoiced' => 'Invoiced', 'Canceled' => 'Canceled');


        return view("admin.challan.dc_list")->with(['list' => $result, 'company' => $company_name->company_name, 'stage' => $stage]);

    }

    function so_deliverychallan(Request $request)
    {
        //echo $totproduct = count($request->product);

        //dd($request->all());

        date_default_timezone_set('Asia/Kolkata');

        $customer_name = customers::find($request->customer);

        $request->validate([

            'customer' => 'required',
            'invoice_date' => 'required',
            'product' => 'required',
            'billing_address' => 'required',
            'shipping_address' => 'required',
            'billing_city' => 'required',
            'shipping_city' => 'required',
            'billing_state' => 'required',
            'shipping_state' => 'required',
            'billing_postalcode' => 'required',
            'shipping_postalcode' => 'required',
            'billing_country' => 'required',
            'shipping_country' => 'required',
            'term_condition' => 'required',

        ]);

        $now = date('Y-m-d',strtotime($request->invoice_date));

        $finacial_year= finacial_year::where('start_date', '<=', $now)->where('end_date', '>=', $now)
       ->first();
       $start=date("y",strtotime($finacial_year->start_date));
       $end=date("y",strtotime($finacial_year->end_date));
        $qno=delivery_challan::where("finacial_year",$finacial_year->id)
       ->max('challan_no');

        //$qno = delivery_challan::max('challan_no');

        if (empty($qno)) {
            $year = date("y",strtotime($request->invoice_date));
            $nextyear = $year + 1;

            $n2 = "DC-";
            $n2 .= $start;
            $n2 .= $end;
            $n2 .= str_pad($qno + 1, 5, 0, STR_PAD_LEFT);
            $qno = 0;

        } else {
            $year = date("y",strtotime($request->invoice_date));
            $nextyear = $year + 1;
            $n2 = "DC-";
             $n2 .= $start;
            $n2 .= $end;
            $n2 .= str_pad($qno + 1, 5, 0, STR_PAD_LEFT);
        }

        $invoice = new delivery_challan();
        $invoice->challan_no = $qno + 1;
        $invoice->challan_number = $n2;
        $invoice->customer = $request->customer;
        $invoice->contact_name=$request->contact_name;
        $invoice->subject = $request->subject;
        $invoice->salaesorder_no = $request->salaesorder_no;
        $invoice->invoice_date = date('Y-m-d', strtotime($request->invoice_date));
        $invoice->payment_terms = $request->payment_terms;
        $invoice->invoice_duedate = date('Y-m-d', strtotime($request->invoice_duedate));
        $invoice->status = $request->status;
        $invoice->remark = $request->remark;
        $invoice->item_total = $request->item_total;
        $invoice->discount_total = $request->discount_total;
        $invoice->cgsttotal = $request->cgsttotal;
        $invoice->sgsttotal = $request->sgsttotal;
        $invoice->igsttotal = $request->igsttotal;
        $invoice->adjustment = $request->adjustment;
        $invoice->grand_total = $request->grand_total;

        $invoice->billing_address = $request->billing_address;
        $invoice->shipping_address = $request->shipping_address;
        $invoice->billing_city = $request->billing_city;
        $invoice->shipping_city = $request->shipping_city;
        $invoice->billing_state = $request->billing_state;
        $invoice->shipping_state = $request->shipping_state;
        $invoice->billing_postalcode = $request->billing_postalcode;
        $invoice->shipping_postalcode = $request->shipping_postalcode;
        $invoice->billing_country = $request->billing_country;
        $invoice->shipping_country = $request->shipping_country;
        $invoice->term_condition=$request->term_condition;
        $invoice->module=$request->module;
        $invoice->user_id=Session::get("user_id");
        $invoice->website_id=Session::get("website_id");

        $invoice->purchase_order=$request->purchase_order;
        $invoice->purchase_order_date=$request->purchase_order_date;
        $invoice->quot_date=$request->quot_date;
        $invoice->quotation_no=$request->quotation_no;
        $invoice->salaesorder_date=$request->salaesorder_date;
        $invoice->finacial_year=$finacial_year->id;
        $invoice->salesman_id = $request->salesman_id;
        $invoice->created_by = Auth::user()->id;

        if ($invoice->save()) {
            //\LogActivity::addToLog($invoice->challan_number.' Delivery Challan Create');
            if (empty($request->product)) {
            } else {
                $totproduct = count($request->product);
                for ($i = 0; $i < $totproduct; $i++) {
                    $item = new delivery_challan_item();
                    $item->invid = $invoice->id;
                    $item->invoice_no = $n2;
                    $item->salaesorder_no = $request->salaesorder_no;
                    $item->product = $request->product[$i];
                    $item->description = $request->description[$i];
                    $item->inner_diamitter = $request->inner_diamitter[$i] ?? "";
                    $item->outer_diamitter = $request->outer_diamitter[$i] ?? "";
                    $item->thikness = $request->thikness[$i] ?? "";
                    $item->hsn = $request->hsn[$i] ?? "";
                    $item->qty = $request->qty[$i];
                    $item->price = $request->price[$i];
                    $item->total_amount = $request->total_amount[$i];
                    $item->discount_per = $request->discount_per[$i];
                    $item->discount_amount = $request->discount_amount[$i];
                    $item->cgst_per = $request->cgst_per[$i];
                    $item->cgst_amount = $request->cgst_amount[$i];
                    $item->sgst_per = $request->sgst_per[$i];
                    $item->sgst_amount = $request->sgst_amount[$i];
                    $item->igst_per = $request->gst_per[$i];
                    $item->igst_amount = $request->gst_amount[$i];
                    $item->net_price = $request->net_price[$i];

                    if ($item->save()) {

                         $prod = product::find($request->product[$i]);
                        if ($prod->status == "bom") {
                            $bom_sub_product = bom_sub_product::where("bom_id", $request->product[$i])
                                ->get();
                            //dd($bom_sub_product);
                            foreach ($bom_sub_product as $bprod) {
                                $checkproduct = stock_status::where("product", $bprod->product)
                                    ->first();
                                if (empty($checkproduct)) {
                                } else {
                                    if ($checkproduct->qty > 0) {

                                        $qty = $checkproduct->qty - $bprod->qty;
                                        $checkproduct->qty = $qty;
                                        $checkproduct->save();

                                        $book = new stock_book();
                                        $book->purchase_no = $n2;
                                        $book->product = $bprod->product;
                                        $book->inward_date = date('Y-m-d');
                                        $book->outward_qty = $bprod->qty;
                                        $book->remaining_qty = $qty;
                                        $book->particular = "Delivery Challan";
                                        $book->created_time = date('d-m-Y h:i:s a');
                                        $book->user_id = Session::get("user_id");
                                        $book->save();
                                    }
                                }
                            }
                        }

                        $checkproduct = stock_status::where("product", $request->product[$i])
                            ->first();
                        if (empty($checkproduct)) {
                        } else {
                            if ($checkproduct->qty > 0) {

                                $qty = $checkproduct->qty - $request->qty[$i];
                                $checkproduct->qty = $qty;
                                $checkproduct->save();

                                $book = new stock_book();
                                $book->purchase_no = $n2;
                                $book->product = $request->product[$i];
                                $book->inward_date = date('Y-m-d');
                                $book->outward_qty = $request->qty[$i];
                                $book->remaining_qty = $qty;
                                $book->particular = "Delivery Challan";
                                $book->created_time = date('d-m-Y h:i:s a');
                                $book->user_id = Session::get("user_id");
                                $book->save();
                            }
                        }
                    }

                }
            }

            if(empty($request->salaesorder_no)){}else{
                $so= salesorder::where('salaesorder_no',$request->salaesorder_no)->first();
                $so->deliverychallan_status="Y";
                $so->save();
            }
            return redirect()->route("admin.challan.list")->with("message", "Delivery Challan Create Successfully");
        }
    }

    function so_dc(Request $request)
    {
        //echo $totproduct = count($request->product);

        //dd($request->all());

        date_default_timezone_set('Asia/Kolkata');

        $customer_name = customers::find($request->customer);

        $request->validate([

            'customer' => 'required',
            'invoice_date' => 'required',
            'product' => 'required',
            'billing_address' => 'required',
            'shipping_address' => 'required',
            'billing_city' => 'required',
            'shipping_city' => 'required',
            'billing_state' => 'required',
            'shipping_state' => 'required',
            'billing_postalcode' => 'required',
            'shipping_postalcode' => 'required',
            'billing_country' => 'required',
            'shipping_country' => 'required',
            'term_condition' => 'required',

        ]);

        $now = date('Y-m-d',strtotime($request->invoice_date));

        $finacial_year= finacial_year::where('start_date', '<=', $now)->where('end_date', '>=', $now)
       ->first();
       $start=date("y",strtotime($finacial_year->start_date));
       $end=date("y",strtotime($finacial_year->end_date));
        $qno=delivery_challan_without::where("finacial_year",$finacial_year->id)
       ->max('challan_no');

        //$qno = delivery_challan::max('challan_no');

        if (empty($qno)) {
            $year = date("y",strtotime($request->invoice_date));
            $nextyear = $year + 1;

            $n2 = "DCW-";
            $n2 .= str_pad($qno + 1, 5, 0, STR_PAD_LEFT);
            $qno = 0;
            $n2 .= '/';
            $n2 .= $start;
            $n2 .= '-';
            $n2 .= $end;
        } else {
            $year = date("y",strtotime($request->invoice_date));
            $nextyear = $year + 1;
            $n2 = "DCW-";
            $n2 .= str_pad($qno + 1, 5, 0, STR_PAD_LEFT);
            $n2 .= '/';
            $n2 .= $start;
            $n2 .= '-';
            $n2 .= $end;
        }

        $invoice = new delivery_challan_without();
        $invoice->challan_no = $qno + 1;
        $invoice->challan_number = $n2;
        $invoice->customer = $request->customer;
        $invoice->contact_name=$request->contact_name;
        $invoice->subject = $request->subject;
        $invoice->salaesorder_no = $request->salaesorder_no;
        $invoice->invoice_date = date('Y-m-d', strtotime($request->invoice_date));
        $invoice->payment_terms = $request->payment_terms;
        $invoice->invoice_duedate = date('Y-m-d', strtotime($request->invoice_duedate));
        $invoice->status = $request->status;
        $invoice->remark = $request->remark;
        $invoice->item_total = $request->item_total;
        $invoice->discount_total = $request->discount_total;
        $invoice->cgsttotal = $request->cgsttotal;
        $invoice->sgsttotal = $request->sgsttotal;
        $invoice->igsttotal = $request->igsttotal;
        $invoice->adjustment = $request->adjustment;
        $invoice->grand_total = $request->grand_total;

        $invoice->billing_address = $request->billing_address;
        $invoice->shipping_address = $request->shipping_address;
        $invoice->billing_city = $request->billing_city;
        $invoice->shipping_city = $request->shipping_city;
        $invoice->billing_state = $request->billing_state;
        $invoice->shipping_state = $request->shipping_state;
        $invoice->billing_postalcode = $request->billing_postalcode;
        $invoice->shipping_postalcode = $request->shipping_postalcode;
        $invoice->billing_country = $request->billing_country;
        $invoice->shipping_country = $request->shipping_country;
        $invoice->term_condition=$request->term_condition;
        $invoice->module=$request->module;
        $invoice->user_id=Session::get("user_id");
        $invoice->website_id=Session::get("website_id");

        $invoice->po_no=$request->po_no;
        $invoice->finacial_year=$finacial_year->id;
        $invoice->created_by = Auth::user()->id;
        if ($invoice->save()) {
          //  \LogActivity::addToLog($invoice->challan_number.' Delivery Challan Create');

            if (empty($request->product)) {
            } else {
                $totproduct = count($request->product);
                for ($i = 0; $i < $totproduct; $i++) {
                    $item = new delivery_challan_item_without();
                    $item->invid = $invoice->id;
                    $item->invoice_no = $n2;
                    $item->product = $request->product[$i];
                    $item->description = $request->description[$i];
                    $item->inner_diamitter = $request->inner_diamitter[$i];
                    $item->outer_diamitter = $request->outer_diamitter[$i];
                    $item->thikness = $request->thikness[$i];
                    $item->hsn = $request->hsn[$i];
                    $item->qty = $request->qty[$i];
                    $item->price = $request->price[$i];
                    $item->total_amount = $request->total_amount[$i];
                    $item->discount_per = $request->discount_per[$i];
                    $item->discount_amount = $request->discount_amount[$i];
                    $item->cgst_per = $request->cgst_per[$i];
                    $item->cgst_amount = $request->cgst_amount[$i];
                    $item->sgst_per = $request->sgst_per[$i];
                    $item->sgst_amount = $request->sgst_amount[$i];
                    $item->igst_per = $request->gst_per[$i];
                    $item->igst_amount = $request->gst_amount[$i];
                    $item->net_price = $request->net_price[$i];

                    if ($item->save()) {

                         $prod = product::find($request->product[$i]);
                        if ($prod->status == "bom") {
                            $bom_sub_product = bom_sub_product::where("bom_id", $request->product[$i])
                                ->get();
                            //dd($bom_sub_product);
                            foreach ($bom_sub_product as $bprod) {
                                $checkproduct = stock_status::where("product", $bprod->product)
                                    ->first();
                                if (empty($checkproduct)) {
                                } else {
                                    if ($checkproduct->qty > 0) {

                                        $qty = $checkproduct->qty - $bprod->qty;
                                        $checkproduct->qty = $qty;
                                        $checkproduct->save();

                                        $book = new stock_book();
                                        $book->purchase_no = $n2;
                                        $book->product = $bprod->product;
                                        $book->inward_date = date('Y-m-d');
                                        $book->outward_qty = $bprod->qty;
                                        $book->remaining_qty = $qty;
                                        $book->particular = "Delivery Challan";
                                        $book->created_time = date('d-m-Y h:i:s a');
                                        $book->user_id = Session::get("user_id");
                                        $book->save();
                                    }
                                }
                            }
                        }

                        $checkproduct = stock_status::where("product", $request->product[$i])
                            ->first();
                        if (empty($checkproduct)) {
                        } else {
                            if ($checkproduct->qty > 0) {

                                $qty = $checkproduct->qty - $request->qty[$i];
                                $checkproduct->qty = $qty;
                                $checkproduct->save();

                                $book = new stock_book();
                                $book->purchase_no = $n2;
                                $book->product = $request->product[$i];
                                $book->inward_date = date('Y-m-d');
                                $book->outward_qty = $request->qty[$i];
                                $book->remaining_qty = $qty;
                                $book->particular = "Delivery Challan";
                                $book->created_time = date('d-m-Y h:i:s a');
                                $book->user_id = Session::get("user_id");
                                $book->save();
                            }
                        }
                    }

                }
            }

            if(empty($request->salaesorder_no)){}else{
                $so= salesorder::where('salaesorder_no',$request->salaesorder_no)->first();
                $so->deliverychallan_status="Y";
                $so->save();
            }
            return redirect()->route("dc/list")->with("message", "Delivery Challan Create Successfully");
        }
    }

    function dc_add(Request $request)
    {


        $contact_name=array();
        if($request->id==0)
        {
            if(isset($request->customer))
            {

                $customer=customers::orderBy('customer_name', "asc")
                    ->where("id",$request->customer)
                    ->pluck("customer_name", "id")
                    ->toArray();
                    //dd($customer);
            }else{
                $customer=[''=>'select customer']+customers::orderBy('customer_name', "asc")
                    ->pluck("customer_name", "id")
                    ->toArray();
            }
            $quot="";
            $quotitem=array();
            $pterms="";

            $payment_terms = payment_terms::orderBy("terms_name", "asc")
                ->get();
            foreach ($payment_terms as $pt) {
                $pterms .= "<option value='" . $pt->days . "'>" . $pt->terms_name . "</option>";
            }

            $duedate = Date('d-m-Y', strtotime('+ 15 days'));
        }else{
            $quot = salesorder::where('id', $request->id)
                ->first();

            if(empty($quot->contact_name))
            {
                $contact_name=[''=>'select contact']+contact::query()
                        ->orderBy('contact_name','asc')
                        ->where('customer',$quot->customer)
                        ->get()
                        ->pluck('contact_name','id')
                        ->toArray();
            }else{

                $cname=contact::select('id','contact_name')->where('id',$quot->contact_name)->first();

                $contact_name=[$cname->id=>$cname->contact_name]+contact::query()
                        ->where('customer',$quot->customer)
                        ->orderBy('contact_name','asc')
                        ->get()
                        ->pluck('contact_name','id')
                        ->toArray();
                //dd($contact_name);
            }

            $quotitem = salesorder_item::select('salesorder_item.*', 'product.product_name', 'uom.uom_name', 'stock_status.qty as stockqty')
                ->leftJoin('product', 'product.id', 'salesorder_item.product')
                ->leftJoin("uom", "uom.id", "product.uom")
                ->leftJoin("stock_status", "stock_status.product", "salesorder_item.product")
                ->where('salesorder_item.soid', $quot->sono)
                ->get();
            $customer = customers::where('id', $quot->customer)
                ->pluck("customer_name", "id")
                ->toArray();

            $customer_terms = customers::where('id', $quot->customer)
                ->first();
            //dd($customer_terms);
            $pterms = "";
            $duedate = date('d-m-Y');
            if (empty($customer_terms->payment_terms)) {
                $payment_terms = payment_terms::orderBy("terms_name", "asc")
                    ->get();
                foreach ($payment_terms as $pt) {
                    $pterms .= "<option value='" . $pt->days . "'>" . $pt->terms_name . "</option>";
                }

            } else {

                $payment_terms = payment_terms::where("days", $customer_terms->payment_terms)
                    ->first();

                $pterms .= "<option value='" . $payment_terms->days . "'>" . $payment_terms->terms_name . "</option>";

                $duedate = Date('d-m-Y', strtotime('+' . $payment_terms->days . ' days'));
                $payment_terms1 = payment_terms::orderBy("terms_name", "asc")
                    ->get();
                foreach ($payment_terms1 as $pt) {
                    $pterms .= "<option value='" . $pt->days . "'>" . $pt->terms_name . "</option>";
                }
                //$payment_terms=[$payment_terms->id=>$payment_terms->terms_name];
            }
        }




        $product = product::select('product.*', 'gst.gst_per', 'uom.uom_name','stock_status.qty as stockqty')
            ->leftJoin('gst', 'gst.id', 'product.gst')
            ->leftJoin('uom', 'uom.id', 'product.uom')
            ->leftJoin("stock_status", "stock_status.product", "product.id")
            ->where('product.status', 'product')
            ->orderBy('product.product_name', 'asc')
            ->get();

        $service = product::select('product.*', 'gst.gst_per', 'uom.uom_name')
            ->leftJoin('gst', 'gst.id', 'product.gst')
            ->leftJoin('uom', 'uom.id', 'product.uom')
            ->where('product.status', 'service')
            ->orderBy('product.product_name', 'asc')
            ->get();

        $bom = product::select('product.*', 'gst.gst_per', 'uom.uom_name')
            ->leftJoin('gst', 'gst.id', 'product.gst')
            ->leftJoin('uom', 'uom.id', 'product.uom')
            ->where('product.status', 'bom')
            ->orderBy('product.product_name', 'asc')
            ->get();

        //$term=terms::where('website_id',Session::get('website_id'))->first();

        $term = terms::query()
            ->get();



        $stockstatus = stock_status::get();



        return view("admin.challan.dc_new")->with(['contact_name'=>$contact_name,'stockstatus' => $stockstatus, 'duedate' => $duedate, 'payment_terms' => $pterms, 'bom' => $bom, 'data' => $quot, 'quotitem' => $quotitem, 'customer' => $customer, 'product' => $product, 'service' => $service, 'term' => $term]);

    }

    function challan_add(Request $request)
    {

        $contact_name=array();

        $module=terms::query()
            ->get()
            ->pluck('module','id')
            ->toArray();

        if($request->id==0)
        {
            if(isset($request->customer))
            {

                $customer=customers::orderBy('customer_name', "asc")
                    ->where("id",$request->customer)
                    ->pluck("customer_name", "id")
                    ->toArray();
                    //dd($customer);
            }else{
                $customer=[''=>'select customer']+customers::orderBy('customer_name', "asc")
                    ->pluck("customer_name", "id")
                    ->toArray();

                $salesMan=[''=>'select salesman']+salesman::orderBy('salesman_name', "asc")
                        ->pluck("salesman_name", "id")
                        ->toArray();
            }
            $quot="";
            $quotitem=array();
            $pterms="";

            $payment_terms = payment_terms::orderBy("terms_name", "asc")
                ->get();
            foreach ($payment_terms as $pt) {
                $pterms .= "<option value='" . $pt->days . "'>" . $pt->terms_name . "</option>";
            }

            $duedate = Date('d-m-Y', strtotime('+ 15 days'));
        }else{
            $quot = salesorder::where('id', $request->id)
                ->first();

            if(empty($quot->contact_name))
            {
                $contact_name=[''=>'select contact']+contact::query()
                        ->orderBy('contact_name','asc')
                        ->where('customer',$quot->customer)
                        ->get()
                        ->pluck('contact_name','id')
                        ->toArray();
            }else{

                $cname=contact::select('id','contact_name')->where('id',$quot->contact_name)->first();

                $contact_name=[$cname->id=>$cname->contact_name]+contact::query()
                        ->where('customer',$quot->customer)
                        ->orderBy('contact_name','asc')
                        ->get()
                        ->pluck('contact_name','id')
                        ->toArray();
                //dd($contact_name);
            }

            $quotitem = salesorder_item::select('salesorder_item.*', 'product.product_name', 'product.item_code', 'product.value1', 'product.value2', 'uom.uom_name', 'stock_status.qty as stockqty',"product.product_image","product.bar_code")
                ->leftJoin('product', 'product.id', 'salesorder_item.product')
                ->leftJoin("uom", "uom.id", "product.uom")
                ->leftJoin("stock_status", "stock_status.product", "salesorder_item.product")
                ->where('salesorder_item.sono', $quot->salaesorder_no)
                ->get();
            $customer = customers::where('id', $quot->customer)
                ->pluck("customer_name", "id")
                ->toArray();

            $salesMan = salesman::where('id', $quot->salesman_id)
                ->pluck("salesman_name", "id")
                ->toArray();

            $customer_terms = customers::where('id', $quot->customer)
                ->first();
            //dd($customer_terms);
            $pterms = "";
            $duedate = date('d-m-Y');
            if (empty($customer_terms->payment_terms)) {
                $payment_terms = payment_terms::orderBy("terms_name", "asc")
                    ->get();
                foreach ($payment_terms as $pt) {
                    $pterms .= "<option value='" . $pt->days . "'>" . $pt->terms_name . "</option>";
                }

            } else {

                $payment_terms = payment_terms::where("days", $customer_terms->payment_terms)
                    ->first();

                $pterms .= "<option value='" . $payment_terms->days . "'>" . $payment_terms->terms_name . "</option>";

                $duedate = Date('d-m-Y', strtotime('+' . $payment_terms->days . ' days'));
                $payment_terms1 = payment_terms::orderBy("terms_name", "asc")
                    ->get();
                foreach ($payment_terms1 as $pt) {
                    $pterms .= "<option value='" . $pt->days . "'>" . $pt->terms_name . "</option>";
                }
                //$payment_terms=[$payment_terms->id=>$payment_terms->terms_name];
            }


            $module=terms::query()
            ->get()
            ->pluck('module','id')
            ->toArray();
        }

        // Product/service/BOM picking on this page uses the select2 AJAX
        // search endpoint (product_search_options) now, so the full
        // product-table dump that used to be passed to the view is gone.

        //$term=terms::where('website_id',Session::get('website_id'))->first();

        $term = terms::query()
            ->get();



        $stockstatus = stock_status::get();



        return view("admin.challan.deliverychallan_new")->with(['contact_name'=>$contact_name,'stockstatus' => $stockstatus, 'duedate' => $duedate, 'payment_terms' => $pterms, 'data' => $quot, 'quotitem' => $quotitem, 'customer' => $customer, 'module' => $module, 'term' => $term, 'salesMan' => $salesMan]);

    }
}
