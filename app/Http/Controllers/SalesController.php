<?php

namespace App\Http\Controllers;

use App\contact;
use App\customer_order;
use App\customer_order_item;
use App\finacial_year;
use App\payment_terms;
use App\salesman;
use Illuminate\Http\Request;
use App\product;
use App\customers;
use App\quotation_item;
use App\quotation;
use App\company;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use PDF;
use App\terms;
use Session;
use DB;
use App\services;
use App\country;
use App\state;
use App\category;
use App\uom;
use App\gst;
use App\vendor;
use App\service_renewal;
use App\service_renewal_book;
use App\industry;
use App\type;
use Storage;
use Mail;
use App\website_user;
use App\module_rights;
use App\module;
use App\city;
use App\salesorder_item;
use App\salesorder;
use App\sales_update_reason;

class SalesController extends Controller
{
    function so_list_preview(Request $request)
    {
        $product = new salesorder();
        $salaesorder_no=$client_name=$quot_date=$subject=$amount=$quot_stage="";

        $product=$product->select('salesorder.*','customers.customer_name','customers.primary_email','customers.secondary_email');
        $product=$product->leftJoin('customers','customers.id','salesorder.customer');
        $product=$product;
        $product=$product->where('salesorder.finacial_year',Session::get('finacial_year_id'));

        if($request->salaesorder_no != '')
        {
            $salaesorder_no=$request->quot_no;
            $product = $product->Where('salesorder.salaesorder_no','like','%'.$request->salaesorder_no.'%');
        }

        if($request->client_name != '')
        {
            $client_name=$request->client_name;
            $product = $product->Where('salesorder.customer_name','like','%'.$request->client_name.'%');
        }
        if($request->salesorder_date != '')
        {
            $salaesorder_date=date('Y-m-d',strtotime($request->salesorder_date));
            $product = $product->Where('salesorder.salaesorder_date','like','%'.$request->salesorder_date.'%');
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
        $product = $product->Where('salesorder.customer',$request->id);
        $product=$product->orderBy("id",'desc');
        $product=$product->whereNull("delete_status");
        $result = $product->paginate(session('records_per_page', 30));

        $company_name=company::select('company_name')->first();

        return view('admin.sales/sales_list_preview')->with(['list'=>$result,'company'=>$company_name->company_name]);
    }

    function so_delete(Request $request)
    {
        date_default_timezone_set("Asia/Kolkata");

        $sales =salesorder::find($request->id);
        $sales->delete_status=1;
        $sales->delete_user=Session::get('user_id');
        $sales->delete_datetime=date("d-m-Y h:i:s a");
        if($sales->save())
        {
            return back()->with("message","Salesorder delete successfully");
        }
    }

    function sales_view(Request $request)
    {
        $so=salesorder::where('id',$request->id)
            ->first();

        $soitem=salesorder_item::select('salesorder_item.*','product.product_name','product.make','product.model','product.value1','product.value2')
            ->leftJoin('product','product.id','salesorder_item.product')
            ->where('salesorder_item.sono',$so->salaesorder_no)
            ->get();


        $customer=customers::query()
            ->where('id',$so->customer)
            ->first();


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


        return view("admin.sales_order_view")->with(['data'=>$so,'quotitem'=>$soitem,'customer'=>$customer,'product'=>$product,'service'=>$service,'term'=>$module]);
    }

    function order_view(Request $request)
    {
        $so=customer_order::where('id',$request->id)
            ->first();

        $soitem=customer_order_item::select('customer_order_item.*','product.product_name', 'product.value1', 'product.value2','product.make','product.model',"product.product_image")
            ->leftJoin('product','product.id','customer_order_item.product')
            ->where('customer_order_item.order_no',$so->order_number)
            ->get();


        $customer=customers::query()
            ->where('id',$so->customer)
            ->first();


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


        return view("admin.order/order_view")->with(['data'=>$so,'quotitem'=>$soitem,'customer'=>$customer,'product'=>$product,'service'=>$service,'term'=>$module]);

    }

    function sales_view1(Request $request)
    {
        $so=salesorder::select("salesorder.*","contact.contact_name")
            ->where('salesorder.id',$request->id)
            ->leftJoin("contact","contact.id","salesorder.contact_name")
            ->first();


        $soitem=salesorder_item::select('salesorder_item.*',"uom.uom_name","stock_status.qty as stockqty","product.product_image",'product.product_name','product.make','product.model','product.value1','product.value2')
            ->leftJoin('product','product.id','salesorder_item.product')
            ->leftJoin('stock_status','stock_status.product','product.id')
            ->leftJoin('uom','uom.id','product.uom')
            ->where('salesorder_item.sono',$so->salaesorder_no)
            ->get();


        $customer=customers::query()
            ->where('id',$so->customer)
            ->first();


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


        return view("admin.sales/sales_order_view")->with(['data'=>$so,'quotitem'=>$soitem,'customer'=>$customer,'product'=>$product,'service'=>$service,'term'=>$module]);
    }

    function so_mail(Request $request)
    {
        $data=salesorder::where('id',$request->salesid)
            ->first();

        $quotitem=salesorder_item::select('salesorder_item.*','product.product_name', 'product.value1', 'product.value2','product.make','product.model')
            ->leftJoin('product','product.id','salesorder_item.product')
            ->where('salesorder_item.sono',$data->id)
            ->get();

        $discsum=salesorder_item::select('salesorder_item.*','product.product_name', 'product.value1', 'product.value2','product.make','product.model')
            ->leftJoin('product','product.id','salesorder_item.product')
            ->where('salesorder_item.sono',$data->id)
            ->sum('salesorder_item.discount_amount');



        $customer=customers::query()
            ->where('id',$data->customer)
            ->first();


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

        $company=company::query()->first();

        $filename="ds";
        $filename .='.pdf';

        $pdf = PDF::loadView('admin.sales_order_print', compact('data','quotitem','company','discsum','customer','discsum'));

        $array=explode(',',$request->to_email);

        $tocc=explode(',',$request->to_cc);
        //$body=$request
        //dd($content);
        $subject=$request->to_subject;

        $data1 = array('body'=>$request->to_body);

        Mail::send('emails.salesorder', $data1, function ($message) use ($pdf,$filename,$subject,$array,$tocc) {
            $message->from('erp@nowtowow.co.in', 'Sales Order');
            foreach($array as $val)
            {
                if(empty($val))
                {}else{
                    $message->to($val)->subject($subject);
                }

            }
            if(empty($tocc))
            {}else{
                foreach($tocc as $val1)
                {
                    if(empty($val1))
                    {}else{
                        $message->cc($val1)->subject($subject);
                    }

                }
            }

            $message->attachData($pdf->output(),$filename);
        });

        return back()->with(['message'=>'mail send successfully']);
    }

    function so_print(Request $request)
    {
        $so=salesorder::select("salesorder.*",'customers.customer_name','customers.owner_gst','customers.primary_email','customers.primary_phone as primphone','contact.contact_name as contactname','contact.primary_phone as contact_phone','website_user.*','customers.tax_preference','state.id as state_id')
            ->leftJoin('contact','contact.id','salesorder.contact_name')
            ->leftJoin('website_user','website_user.id','salesorder.user_id')
            ->leftJoin("customers","customers.id","salesorder.customer")
            ->leftJoin("state","state.state_name","salesorder.billing_state")
            ->where('salesorder.id',$request->id)
            ->first();
           // dd($so);

        $soitem=salesorder_item::select('salesorder_item.*','product.product_name',"product.item_code",'product.make','product.model','uom.uom_name','product.product_image','product.material_name','category.category_image','uom.uom_name','product.value1','product.value2')
            ->leftJoin('product','product.id','salesorder_item.product')
            ->leftJoin('category','category.id','product.category')
            ->leftJoin("uom","uom.id","product.uom")
            ->where('salesorder_item.sono',$so->salaesorder_no)
            ->get();

       // dd($soitem);

        $discsum=salesorder_item::select('salesorder_item.*','product.product_name', 'product.value1', 'product.value2','product.make','product.model')
            ->leftJoin('product','product.id','salesorder_item.product')
            ->where('salesorder_item.sono',$so->salaesorder_no)
            ->sum('salesorder_item.discount_amount');

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
        $filename .='.pdf';

        $quot=$so;
        $quotitem=$soitem;

        $filename=$quot->salaesorder_no;
        $filename .='_';
        $filename .=$customer->customer_name;
        $filename .='.pdf';

        $quot=$so;
//      return view("admin.sales/so");
        //     $pdf = PDF::loadView('admin.sales/sales_order_print', compact('data','quotitem','company','discsum','customer','discsum'));
        //dd($customer);
        if($customer->tax_preference=="true") {
            $pdf = PDF::loadView('admin.sales.so_print_old', compact('quot', 'quotitem', 'company', 'discsum', 'customer', 'discsum'));
            return $pdf->download($filename);
        }else{
            $pdf = PDF::loadView('admin.sales.so_print_old', compact('quot', 'quotitem', 'company', 'discsum', 'customer', 'discsum'));
            return $pdf->download($filename);
        }
        //   $pdf = PDF::loadView('admin.sales.so_print', compact('quot','quotitem','company','discsum','customer','discsum'));



        //return view("admin.sales_order_print")->with(['data'=>$so,'quotitem'=>$soitem,'customer'=>$customer,'product'=>$product,'service'=>$service,'term'=>$module,'discsum'=>$discsum,'company'=>$company]);
    }

    function so_preview(Request $request)
    {
        $so=salesorder::select("salesorder.*",'customers.customer_name','customers.primary_email','customers.primary_phone as primphone','customers.owner_gst','contact.contact_name as contactname','contact.primary_phone as contact_phone','website_user.*')
            ->leftJoin('contact','contact.id','salesorder.contact_name')
            ->leftJoin('website_user','website_user.id','salesorder.user_id')
            ->leftJoin("customers","customers.id","salesorder.customer")
            ->where('salesorder.id',$request->id)
            ->first();
        //dd($so);

        $soitem=salesorder_item::select('salesorder_item.*',"product.item_code",'product.product_name', 'product.value1', 'product.value2',"product.product_image",'product.make','product.model','uom.uom_name','product.product_image','product.material_name','category.category_image')
            ->leftJoin('product','product.id','salesorder_item.product')
            ->leftJoin('category','category.id','product.category')
            ->leftJoin("uom","uom.id","product.uom")
            ->where('salesorder_item.sono',$so->salaesorder_no)
            ->get();

        //dd($soitem);

        $discsum=salesorder_item::select('salesorder_item.*',"product.item_code",'product.product_name', 'product.value1', 'product.value2','product.make','product.model')
            ->leftJoin('product','product.id','salesorder_item.product')
            ->where('salesorder_item.sono',$so->salaesorder_no)
            ->sum('salesorder_item.discount_amount');

        // dd($discsum);

        $customer=customers::select("customers.*",'state.state_name')
            ->leftJoin("state","state.id","customers.billing_state")
            ->where('customers.id',$so->customer)
            ->first();

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
        $filename .='_'.date('dmyhis');
        $filename .='.pdf';

        $quot=$so;
        $quotitem=$soitem;


            $pdf = PDF::loadView('admin.sales.so_print_old',compact("discsum","quot","company","quotitem","customer"));

            $content = $pdf->download()->getOriginalContent();

            Storage::put('public/so/pdf/'.$filename,$content) ;

            $url=url('/storage/app/public/so/pdf/'.$filename);
            return Redirect::to($url);


        return back()->with("message","Please select Customer Tax Preferance");

        //$pdf = PDF::loadView('admin.sales.so_print',compact("discsum","quot","company","quotitem","customer"));
//            ->with(['quot'=>$so,'quotitem'=>$soitem,'customer'=>$customer,'product'=>$product,'service'=>$service,'term'=>$module,'discsum'=>$discsum,'company'=>$company]);

        //return view("admin.sales.so_print")->with(['quot'=>$so,'quotitem'=>$soitem,'customer'=>$customer,'product'=>$product,'service'=>$service,'term'=>$module,'discsum'=>$discsum,'company'=>$company]);
    }

    function so_edit(Request $request)
    {
        $so=salesorder::where('id',$request->id)
            ->first();

        $soitem=salesorder_item::select('salesorder_item.*',"uom.uom_name","stock_status.qty as stockqty",'product.product_name','product.make','product.model','product.product_image','product.bar_code','product.value1','product.value2')
            ->leftJoin('product','product.id','salesorder_item.product')
            ->leftJoin('uom','uom.id','product.uom')
            ->leftJoin('stock_status','stock_status.product','product.id')
            ->where('salesorder_item.sono',$so->salaesorder_no)
            ->get();



         $customer=customers::where('id',$so->customer)
            ->first();


        // $customer=[''=>'select customer']+customers::where('website_id',Session::get('website_id'))->orderBy('customer_name','asc')
        //         ->get()
        //         ->pluck('customer_name','id')
        //         ->toArray();

        if(empty($so->contact_name))
        {
            $contact_name=[''=>'select contact']+contact::query()
                    ->where('customer',$so->customer)
                    ->orderBy('contact_name','asc')
                    ->get()
                    ->pluck('contact_name','id')
                    ->toArray();
        }else{
            $cname=contact::select('id','contact_name')->where('id',$so->contact_name)->first();

            $contact_name=[$cname->id=>$cname->contact_name]+contact::query()
                    ->where('customer',$so->customer)
                    ->orderBy('contact_name','asc')
                    ->get()
                    ->pluck('contact_name','id')
                    ->toArray();
        }

        $customer_terms = customers::where('id', $so->customer)
            ->first();
        //dd($customer_terms);
        //dd($customer_terms);
        $pterms = "";
        $duedate = date('d-m-Y');

        if(empty($so->payment_terms))
        {
            if (empty($customer_terms->payment_terms)) {
                $payment_terms = payment_terms::orderBy("terms_name", "asc")
                    ->get();
                foreach ($payment_terms as $pt) {
                    $pterms .= "<option value='" . $pt->days . "'>" . $pt->terms_name . "</option>";
                }

            } else {

                $payment_terms = payment_terms::where("id", $customer_terms->payment_terms)
                    ->first();
                //dd($payment_terms);
                $pterms .= "<option value='" . $payment_terms->days . "'>" . $payment_terms->terms_name . "</option>";

                $duedate = Date('d-m-Y', strtotime('+' . $payment_terms->days . ' days'));
                $payment_terms1 = payment_terms::orderBy("terms_name", "asc")
                    ->get();
                foreach ($payment_terms1 as $pt) {
                    $pterms .= "<option value='" . $pt->days . "'>" . $pt->terms_name . "</option>";
                }
                //$payment_terms=[$payment_terms->id=>$payment_terms->terms_name];
            }

        }else{
            $payment_terms1 = payment_terms::where("days", $so->payment_terms)
                ->first();

            $duedate = Date('d-m-Y', strtotime('+' . $payment_terms1->days . ' days'));
            //dd($payment_terms);
            $pterms .= "<option value='" . $payment_terms1->days . "'>" . $payment_terms1->terms_name . "</option>";
            // dd($pterms);
            $payment_terms = payment_terms::orderBy("terms_name", "asc")
                ->get();

            //dd($payment_terms);
            foreach ($payment_terms as $pt) {
                $pterms .= "<option value='" . $pt->days . "'>" . $pt->terms_name . "</option>";
            }


        }
        //dd($pterms);


        // Product/service/BOM picking on this page uses the select2 AJAX
        // search endpoint (product_search_options) now, so the full
        // product-table dump that used to be passed to the view is gone.

        //$term=terms::where('website_id',Session::get('website_id'))->first();

        $module=terms::query()
            ->get()
            ->pluck('module','id')
            ->toArray();

        $reason=sales_update_reason::select("sales_update_reason.*","website_user.first_name","website_user.last_name")
        ->leftJoin("website_user","website_user.id","sales_update_reason.user_id")
        ->orderBy("sales_update_reason.id","desc")
        ->where("sales_update_reason.so_no",$so->salaesorder_no)
        ->get();

        $salesMan = salesman::get()->pluck('salesman_name','id')->toArray();

        return view("admin.sales/sales_order_edit")->with(["reason"=>$reason,'contact_name'=>$contact_name,'duedate' => $duedate, 'payment_terms' => $pterms,'data'=>$so,'quotitem'=>$soitem,'customer'=>$customer,'module'=>$module, 'salesMan' => $salesMan]);
    }

    public function salesorder_list(Request $request)
    {
        $query = salesorder::query()
            ->select('salesorder.*', 'customers.customer_name', 'customers.primary_email', 'customers.secondary_email')
            ->leftJoin('customers', 'customers.id', '=', 'salesorder.customer')
            ->whereNull('salesorder.delete_status');



        // 🔹 Filters
        if ($request->filled('salaesorder_no')) {
            $query->where('salesorder.salaesorder_no', 'like', '%' . $request->salaesorder_no . '%');
        }

        if ($request->filled('client_name')) {
            $query->where('customers.customer_name', 'like', '%' . $request->client_name . '%');
        }

        // 🔹 Date range
        if ($request->filled('from_date') && $request->filled('to_date')) {
            $from = date('Y-m-d', strtotime($request->from_date));
            $to   = date('Y-m-d', strtotime($request->to_date));
            $query->whereBetween('salesorder.salaesorder_date', [$from, $to]);
        } elseif ($request->filled('from_date')) {
            $from = date('Y-m-d', strtotime($request->from_date));
            $query->whereDate('salesorder.salaesorder_date', '>=', $from);
        } elseif ($request->filled('to_date')) {
            $to = date('Y-m-d', strtotime($request->to_date));
            $query->whereDate('salesorder.salaesorder_date', '<=', $to);
        }

        if ($request->filled('subject')) {
            $query->where('salesorder.subject', 'like', '%' . $request->subject . '%');
        }

        if ($request->filled('amount')) {
            $query->where('salesorder.grand_total', 'like', '%' . $request->amount . '%');
        }

        // 🔹 DC Status Filter
        if ($request->filled('dc_status')) {
            if ($request->dc_status === 'dc_pending') {
                // Orders where delivered qty != ordered qty
                $query->whereRaw("
                (
                    SELECT COALESCE(SUM(si.qty), 0)
                    FROM salesorder_item si
                    JOIN product p ON p.id = si.product
                    WHERE (p.status = 'product' OR p.status = 'bom')
                    AND si.sono = salesorder.salaesorder_no
                ) !=
                (
                    SELECT COALESCE(SUM(di.qty), 0)
                    FROM delivery_challan_item di
                    JOIN product p2 ON p2.id = di.product
                    WHERE (p2.status = 'product' OR p2.status = 'bom')
                    AND di.salaesorder_no = salesorder.salaesorder_no
                )
            ");
            } elseif ($request->dc_status === 'out_of_stock') {
                // Orders where any pending SO qty > stock
                $query->whereExists(function ($sub) {
                    $sub->select(DB::raw(1))
                        ->from('salesorder_item as soi')
                        ->join('product as p', 'p.id', '=', 'soi.product')
                        ->leftJoin(DB::raw('(SELECT product, SUM(qty) as stock_qty FROM stock_status GROUP BY product) as ss'), 'ss.product', '=', 'soi.product')
                        ->leftJoin(DB::raw('(SELECT product, salaesorder_no, SUM(qty) as dc_qty FROM delivery_challan_item GROUP BY salaesorder_no, product) as dc'), function($join) {
                            $join->on('dc.product', '=', 'soi.product')
                                ->on('dc.salaesorder_no', '=', 'soi.sono');
                        })
                        ->whereRaw('(p.status = "product" OR p.status = "bom")')
                        ->whereColumn('soi.sono', 'salesorder.salaesorder_no')
                        ->whereRaw('(soi.qty - COALESCE(dc.dc_qty,0)) > COALESCE(ss.stock_qty,0)');
                });
            }
        }

        // 🔹 Fetch and compute custom status
        $result = $query->orderByDesc('salesorder.id')->paginate(session('records_per_page', 30));

        foreach ($result as $order) {
            $order->computed_status = 'done';

            // Total SO qty
            $soQty = DB::table('salesorder_item as si')
                ->join('product as p', 'p.id', '=', 'si.product')
                ->whereRaw('(p.status = "product" OR p.status = "bom")')
                ->where('si.sono', $order->salaesorder_no)
                ->sum('si.qty');

            // Total DC qty
            $dcQty = DB::table('delivery_challan_item as di')
                ->join('product as p2', 'p2.id', '=', 'di.product')
                ->whereRaw('(p2.status = "product" OR p2.status = "bom")')
                ->where('di.salaesorder_no', $order->salaesorder_no)
                ->sum('di.qty');

            // Check pending quantity vs stock for out_of_stock
            $outOfStock = DB::table('salesorder_item as soi')
                ->join('product as p', 'p.id', '=', 'soi.product')
                ->leftJoin(DB::raw('(SELECT product, SUM(qty) as stock_qty FROM stock_status GROUP BY product) as ss'), 'ss.product', '=', 'soi.product')
                ->leftJoin(DB::raw('(SELECT product, salaesorder_no, SUM(qty) as dc_qty FROM delivery_challan_item GROUP BY salaesorder_no, product) as dc'), function($join) {
                    $join->on('dc.product', '=', 'soi.product')
                        ->on('dc.salaesorder_no', '=', 'soi.sono');
                })
                ->whereRaw('(p.status = "product" OR p.status = "bom")')
                ->where('soi.sono', $order->salaesorder_no)
                ->whereRaw('(soi.qty - COALESCE(dc.dc_qty,0)) > COALESCE(ss.stock_qty,0)')
                ->exists();

            if ($soQty != $dcQty) {
                $order->computed_status = 'dc_pending';
            }

            if ($outOfStock) {
                $order->computed_status = 'out_of_stock';
            }
        }

        $company_name = company::select('company_name')->first();

        return view('admin.sales.sales_list', [
            'list' => $result,
            'company' => $company_name->company_name,
        ]);
    }




    function salesorder_update(Request $request)
    {

        $salesorder=salesorder::find($request->id);
        $customer_name=customers::find($request->customer);
        $salesorder->customer=$request->customer;
        $salesorder->customer_name=$customer_name->customer_name;
        $salesorder->contact_name=$request->contact_name;
        $salesorder->subject=$request->subject;
        $salesorder->quotation_no=$request->quotation_no;
        $salesorder->quot_no=$request->quot_no;
        $salesorder->quot_date=date('Y-m-d',strtotime($request->quot_date));
        $salesorder->salaesorder_date=date('Y-m-d',strtotime($request->salaesorder_date));

        $salesorder->payment_terms = $request->payment_terms;
        $salesorder->due_date = date('Y-m-d', strtotime($request->due_date));

//        $salesorder->due_date=date('Y-m-d',strtotime($request->due_date));
        $salesorder->purchase_order=$request->purchase_order;
        $salesorder->status=$request->status;
        $salesorder->billing_address=$request->billing_address;
        $salesorder->billing_country=$request->billing_country;
        $salesorder->billing_state=$request->billing_state;
        $salesorder->billing_city=$request->billing_city;
        $salesorder->billing_postalcode=$request->billing_postalcode;

        $salesorder->shipping_address=$request->shipping_address;
        $salesorder->shipping_country=$request->shipping_country;
        $salesorder->shipping_state=$request->shipping_state;
        $salesorder->shipping_city=$request->shipping_city;
        $salesorder->shipping_postalcode=$request->shipping_postalcode;

        $salesorder->grand_total=$request->grand_total;
        $salesorder->term_condition=$request->term_condition;
        $salesorder->remark=$request->remark;
        $salesorder->grand_total=$request->grand_total;
        $salesorder->website_id=Session::get('website_id');
        $salesorder->user_id=Session::get('user_id');
        $salesorder->net_amount=$request->item_total;
        $salesorder->gst_amount=$request->gsttotal;
        $salesorder->grand_total=$request->grand_total;

        if($salesorder->save())
        {
            $sitem1=salesorder_item::where('soid',$request->id)->delete();


            if(empty($request->quotation_no))
            {}else{
                $quotation=quotation::where('quotation_no',$request->quotation_no)->first();
                $quotation->so_status='Y';
                $quotation->save();
            }


            $tot=count($request->product);
            for($i=0;$i<$tot;$i++)
            {
                $sitem=new salesorder_item();
                $sitem->soid=$request->id;
                $sitem->sono=$request->salaesorder_no;
                $sitem->product=$request->product[$i];

                $sitem->qty=$request->qty[$i];
                $sitem->price=$request->price[$i];
                $sitem->total=$request->total_amount[$i];
                $sitem->discount_per=$request->discount_per[$i];
                $sitem->discount_amount=$request->discount_amount[$i];
                $sitem->gst_per=$request->gst_per[$i];
                $sitem->gst_amount=$request->gst_amount[$i];
                $sitem->grand_total=$request->net_price[$i];

                $sitem->save();
            }

            return redirect()->route("client/salesorder_list")->with('message','salesorder update successfully');
        }
    }

    function salesorder_update1(Request $request)
    {

        //    dd($request->all());
        $salesorder=salesorder::find($request->id);
        $customer_name=customers::find($request->customer);
        $salesorder->customer=$request->customer;
        $salesorder->customer_name=$customer_name->customer_name;
        $salesorder->contact_name=$request->contact_name;
        $salesorder->subject=$request->subject;
        $salesorder->quotation_no=$request->quotation_no;
        $salesorder->quot_no=$request->quot_no;
        $salesorder->quot_date=date('Y-m-d',strtotime($request->quot_date));
        $salesorder->salaesorder_date=date('Y-m-d',strtotime($request->salaesorder_date));

        $salesorder->payment_terms = $request->payment_terms;
        $salesorder->due_date = date('Y-m-d', strtotime($request->due_date));

//        $salesorder->due_date=date('Y-m-d',strtotime($request->due_date));
        $salesorder->purchase_order=$request->purchase_order;
        $salesorder->purchase_order_date=$request->purchase_order_date;
        $salesorder->status=$request->status;
        $salesorder->billing_address=$request->billing_address;
        $salesorder->billing_country=$request->billing_country;
        $salesorder->billing_state=$request->billing_state;
        $salesorder->billing_city=$request->billing_city;
        $salesorder->billing_postalcode=$request->billing_postalcode;

        $salesorder->shipping_address=$request->shipping_address;
        $salesorder->shipping_country=$request->shipping_country;
        $salesorder->shipping_state=$request->shipping_state;
        $salesorder->shipping_city=$request->shipping_city;
        $salesorder->shipping_postalcode=$request->shipping_postalcode;

        $salesorder->grand_total=$request->grand_total;
        $salesorder->term_condition=$request->term_condition;
        $salesorder->remark=$request->remark;
        $salesorder->grand_total=$request->grand_total;
        $salesorder->website_id=Session::get('website_id');
        $salesorder->user_id=Session::get('user_id');
        $salesorder->net_amount=$request->item_total;
        $salesorder->gst_amount=$request->gsttotal;
        $salesorder->cgstamount=$request->cgsttotal;
        $salesorder->sgstamount=$request->sgsttotal;
        $salesorder->adjustment=$request->adjustment;
        $salesorder->grand_total=$request->grand_total;
        $salesorder->salesman_id = $request->salesman_id;
        $salesorder->created_by = Auth::user()->id;
        if($salesorder->save()) {
            $sitem1 = salesorder_item::where('sono', $salesorder->salaesorder_no)->delete();

            if (empty($request->quotation_no)) {
            } else {
                $quotation = quotation::where('quotation_no', $request->quotation_no)->first();
                $quotation->so_status = 'Y';
                $quotation->save();
            }

            $tot=count($request->product);
            for($i=0;$i<$tot;$i++)
            {
                $sitem=new salesorder_item();
                $sitem->soid=$request->id;
                $sitem->sono=$request->salaesorder_no;
                $sitem->product=$request->product[$i];
                $sitem->description=$request->description[$i];
                $sitem->inner_daimitter=$request->inner_diamitter[$i] ?? "";
                $sitem->outer_daimitter=$request->outer_diamitter[$i] ?? "";
                $sitem->thk=$request->thikness[$i] ?? "";
                $sitem->hsn=$request->hsn[$i] ?? "";
                $sitem->qty=$request->qty[$i] ?? "";
                $sitem->price=$request->price[$i] ?? "";
                $sitem->total=$request->total_amount[$i];
                $sitem->discount_per=$request->discount_per[$i];
                $sitem->discount_amount=$request->discount_amount[$i];
                $sitem->cgst_per=$request->cgst_per[$i];
                $sitem->cgst_amount=$request->cgst_amount[$i];
                $sitem->sgst_per=$request->sgst_per[$i];
                $sitem->sgst_amount=$request->sgst_amount[$i];
                $sitem->gst_per=$request->gst_per[$i];
                $sitem->gst_amount=$request->gst_amount[$i];
                $sitem->grand_total=$request->net_price[$i];

                $sitem->save();
            }

            return redirect()->route("admin.sales.list")->with('message','Sales order update successfully');
        }
    }

    function salesorder_save(Request $request)
    {
        $request->validate([
            'salaesorder_date' => 'required',
            'due_date'=>'required',
            'status'=>'required',
            'billing_address'=>'required',
            'shipping_address'=>'required',
            'billing_city'=>'required',
            'shipping_city'=>'required',
            'billing_state'=>'required',
            'shipping_state'=>'required',
            'billing_postalcode'=>'required',
            'shipping_postalcode'=>'required',
            'billing_country'=>'required',
            'shipping_country'=>'required',

        ]);

        $now = date('Y-m-d',strtotime($request->salaesorder_date));
        //die;
       $finacial_year= finacial_year::where('start_date', '<=', $now)->where('end_date', '>=', $now)
       ->first();
       $start=date("y",strtotime($finacial_year->start_date));
       $end=date("y",strtotime($finacial_year->end_date));
       //dd($finacial_year);

        $qno=salesorder::where("finacial_year",$finacial_year->id)
            ->max('sono');

        if(empty($qno))
        {
            $year = date("Y");
            $nextyear = $year +1;

            $n2="SO-";
            $n2 .=$start;
            $n2 .=$end;
            $n2 .=str_pad($qno + 1, 5, 0, STR_PAD_LEFT);
            $qno=0;
        }else{
            $year = date("Y");
            $nextyear = $year +1;
            $n2 ="SO-";
            $n2 .=$start;
            $n2 .=$end;
            $n2 .= str_pad($qno + 1, 5, 0, STR_PAD_LEFT);
        }

        $salesorder=new salesorder();
        $customer_name=customers::find($request->customer);
        $salesorder->sono=$qno+1;
        $salesorder->salaesorder_no=$n2;
        $salesorder->customer=$request->customer;
        $salesorder->customer_name=$customer_name->customer_name;
        $salesorder->contact_name=$request->contact_name;
        $salesorder->subject=$request->subject;
        $salesorder->quotation_no=$request->quotation_no;
        $salesorder->quot_no=$request->quot_no;
        $salesorder->quot_date=date('Y-m-d',strtotime($request->quot_date));
        $salesorder->salaesorder_date=date('Y-m-d',strtotime($request->salaesorder_date));

        $salesorder->payment_terms = $request->payment_terms;
        $salesorder->due_date = date('Y-m-d', strtotime($request->due_date));

//        $salesorder->due_date=date('Y-m-d',strtotime($request->due_date));
        $salesorder->purchase_order=$request->purchase_order;
        $salesorder->status=$request->status;
        $salesorder->billing_address=$request->billing_address;
        $salesorder->billing_country=$request->billing_country;
        $salesorder->billing_state=$request->billing_state;
        $salesorder->billing_city=$request->billing_city;
        $salesorder->billing_postalcode=$request->billing_postalcode;

        $salesorder->shipping_address=$request->shipping_address;
        $salesorder->shipping_country=$request->shipping_country;
        $salesorder->shipping_state=$request->shipping_state;
        $salesorder->shipping_city=$request->shipping_city;
        $salesorder->shipping_postalcode=$request->shipping_postalcode;

        $salesorder->grand_total=$request->grand_total;
        $salesorder->term_condition=$request->term_condition;
        $salesorder->remark=$request->remark;
        $salesorder->grand_total=$request->grand_total;
        $salesorder->website_id=Session::get('website_id');
        $salesorder->user_id=Session::get('user_id');
        $salesorder->net_amount=$request->item_total;
        $salesorder->discount_total=$request->discount_total;
        $salesorder->gst_amount=$request->gsttotal;
        $salesorder->grand_total=$request->grand_total;
        $salesorder->finacial_year=$finacial_year->id;
        $salesorder->created_by = Auth::user()->id;
        if($salesorder->save())
        {


            $quotation=quotation::where('quotation_no',$request->quotation_no)->first();
            $quotation->so_status='Y';
            $quotation->save();

            $tot=count($request->product);
            for($i=0;$i<$tot;$i++)
            {
                $sitem=new salesorder_item();
                $sitem->soid=$salesorder->id;
                $sitem->sono=$n2;
                $sitem->product=$request->product[$i];
                // $sitem->inner_daimitter=$request->inner_diamitter[$i];
                // $sitem->outer_daimitter=$request->outer_diamitter[$i];
                // $sitem->thk=$request->thikness[$i];
                $sitem->hsn=$request->hsn[$i];
                $sitem->qty=$request->qty[$i];
                $sitem->price=$request->price[$i];
                $sitem->total=$request->total_amount[$i];
                $sitem->discount_per=$request->discount_per[$i];
                $sitem->discount_amount=$request->discount_amount[$i];
                $sitem->gst_per=$request->gst_per[$i];
                $sitem->gst_amount=$request->gst_amount[$i];
                $sitem->grand_total=$request->net_price[$i];

                $sitem->save();
            }

            return redirect()->route("client/salesorder_list")->with('message','salesorder create successfully');
        }
    }


    function store(Request $request)
    {
        //dd($request->all());
        $request->validate([
            'salaesorder_date' => 'required',
            'status'=>'required',
            'billing_address'=>'required',
            'shipping_address'=>'required',
            'billing_city'=>'required',
            'shipping_city'=>'required',
            'billing_state'=>'required',
            'shipping_state'=>'required',
            'billing_postalcode'=>'required',
            'shipping_postalcode'=>'required',
            'billing_country'=>'required',
            'shipping_country'=>'required',

        ]);

        $now = date('Y-m-d',strtotime($request->salaesorder_date));
        //die;
       $finacial_year= finacial_year::where('start_date', '<=', $now)->where('end_date', '>=', $now)
       ->first();
       $start=date("y",strtotime($finacial_year->start_date));
       $end=date("y",strtotime($finacial_year->end_date));
       //dd($finacial_year);

        $qno=salesorder::where("finacial_year",$finacial_year->id)
            ->max('sono');

        if(empty($qno))
        {
            $qno=0;
            $year = date("y");
            $nextyear = $year +1;

            $n2="SO-";
            $n2 .=$start;
            $n2 .=$end;
            $n2 .=str_pad($qno + 1, 5, 0, STR_PAD_LEFT);
        }else{
            $year = date("y");
            $nextyear = $year +1;
            $n2="SO-";
            $n2 .=$start;
            $n2 .=$end;
            $n2 .=str_pad($qno + 1, 5, 0, STR_PAD_LEFT);
        }

        $salesorder=new salesorder();
        $customer_name=customers::find($request->customer);
        $salesorder->sono=$qno+1;
        $salesorder->salaesorder_no=$n2;
        $salesorder->customer=$request->customer;
        $salesorder->customer_name=$customer_name->customer_name;
        $salesorder->contact_name=$request->contact_name;
        $salesorder->subject=$request->subject;
        $salesorder->quotation_no=$request->quotation_no;
        $salesorder->quot_no=$request->quot_no;
        $salesorder->quot_date=date('Y-m-d',strtotime($request->quot_date));
        $salesorder->salaesorder_date=date('Y-m-d',strtotime($request->salaesorder_date));

        $salesorder->payment_terms = $request->payment_terms;
        $salesorder->due_date = date('Y-m-d', strtotime($request->due_date));
//
//        $salesorder->due_date=date('Y-m-d',strtotime($request->due_date));
        $salesorder->purchase_order=$request->purchase_order;
        $salesorder->purchase_order_date=$request->purchase_order_date;
        $salesorder->status=$request->status;
        $salesorder->billing_address=$request->billing_address;
        $salesorder->billing_country=$request->billing_country;
        $salesorder->billing_state=$request->billing_state;
        $salesorder->billing_city=$request->billing_city;
        $salesorder->billing_postalcode=$request->billing_postalcode;

        $salesorder->shipping_address=$request->shipping_address;
        $salesorder->shipping_country=$request->shipping_country;
        $salesorder->shipping_state=$request->shipping_state;
        $salesorder->shipping_city=$request->shipping_city;
        $salesorder->shipping_postalcode=$request->shipping_postalcode;

        $salesorder->term_condition=$request->term_condition;
        $salesorder->remark=$request->remark;

        $salesorder->website_id=Session::get('website_id');
        $salesorder->user_id=Session::get('user_id');
        $salesorder->net_amount=$request->item_total;
        $salesorder->discount_total=$request->discount_total;
        $salesorder->gst_amount=$request->gsttotal;
        $salesorder->cgstamount=$request->cgsttotal;
        $salesorder->sgstamount=$request->sgsttotal;
        $salesorder->adjustment=$request->adjustment;
        $salesorder->grand_total=$request->grand_total;
        $salesorder->finacial_year=$finacial_year->id;
        $salesorder->salesman_id = $request->salesman_id;
        $salesorder->created_by = Auth::user()->id;
        if($salesorder->save())
        {

            if(empty($request->quotation_no))
            {}else{
                $quotation=quotation::where('quotation_no',$request->quotation_no)->first();
                $quotation->so_status='Y';
                $quotation->save();
            }


            $tot=count($request->product);

            //  dd($request->all());
            for($i=0;$i<$tot;$i++)
            {
                $sitem=new salesorder_item();
                $sitem->soid=$salesorder->id;
                $sitem->sono=$n2;
                $sitem->product=$request->product[$i];
                $sitem->description=$request->description[$i];

                $sitem->inner_daimitter=$request->inner_diamitter[$i] ?? "";
                $sitem->outer_daimitter=$request->outer_diamitter[$i] ?? "";
                $sitem->thk=$request->thikness[$i] ?? "";
                $sitem->hsn=$request->hsn[$i] ?? "";
                $sitem->qty=$request->qty[$i] ?? "";
                $sitem->price=$request->price[$i] ?? "";
                $sitem->total=$request->total_amount[$i] ?? "";
                $sitem->discount_per=$request->discount_per[$i];
                $sitem->discount_amount=$request->discount_amount[$i];
                $sitem->cgst_per=$request->cgst_per[$i];
                $sitem->cgst_amount=$request->cgst_amount[$i];
                $sitem->sgst_per=$request->sgst_per[$i];
                $sitem->sgst_amount=$request->sgst_amount[$i];
                $sitem->gst_per=$request->gst_per[$i];
                $sitem->gst_amount=$request->gst_amount[$i];
                $sitem->grand_total=$request->net_price[$i];

                $sitem->save();
            }

            return redirect()->route("admin.sales.list")->with('message','sales order create successfully');
        }
    }

    function salesorder_add(Request $request)
    {

        $quot=quotation::where('id',$request->id)
            ->first();

        $quotitem=quotation_item::select('quot_item.*','product.product_name', 'product.value1', 'product.value2','product.make','product.model',"product.item_code")
            ->leftJoin('product','product.id','quot_item.product')
            ->where('quot_item.quot_no',$quot->quot_no)
            ->get();


        $customer=customers::query()
            ->where('id',$quot->customer)
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

        $bom=product::select('product.*','gst.gst_per','uom.uom_name')
            ->leftJoin('gst','gst.id','product.gst')
            ->leftJoin('uom','uom.id','product.uom')
            ->where('product.status','bom')
            ->orderBy('product.product_name','asc')
            ->get();

        //$term=terms::where('website_id',Session::get('website_id'))->first();

        $term=terms::query()
            ->get();

        $pterms="";
        $customer=[''=>'select customer']+customers::orderBy('customer_name', "asc")
                ->where("id",$quot->customer)
                ->pluck("customer_name", "id")
                ->toArray();
        $payment_terms = payment_terms::orderBy("terms_name", "asc")
            ->get();
        foreach ($payment_terms as $pt) {
            $pterms .= "<option value='" . $pt->days . "'>" . $pt->terms_name . "</option>";
        }

        $duedate = Date('d-m-Y', strtotime('+ 15 days'));
        return view("admin.sales_order_add")->with(['contact_name'=>$contact_name,'bom'=>$bom,'data'=>$quot,'quotitem'=>$quotitem,'customer'=>$customer,'product'=>$product,'service'=>$service,'term'=>$term]);
    }

    function create(Request $request)
    {
        $quotitem=array();
        $quot=$customer="";
        if(isset($request->id))
        {
            $quot=quotation::where('id',$request->id)
                ->first();

            $quotitem=quotation_item::select('quot_item.*',"uom.uom_name","stock_status.qty as stockqty","product.item_code",'product.product_name','product.make','product.model',"product.product_image",'product.value1','product.value2')
                ->leftJoin('product','product.id','quot_item.product')
                ->leftJoin("stock_status","stock_status.product","product.id")
                ->leftJoin('uom','uom.id','product.uom')
                ->where('quot_item.quotation_no',$quot->quotation_no)
                ->get();


            $customer=[''=>'select customer']+customers::where("id",$quot->customer)
                    ->orderBy('customer_name','asc')
                    ->get()
                    ->pluck('customer_name','id')
                    ->toArray();
        }



        $contact_name=contact::query()
            ->orderBy('contact_name','asc')
            ->get()
            ->pluck('contact_name','id')
            ->toArray();

        if(empty($customer))
        {
            $customer=[''=>'select customer']+customers::orderBy('customer_name','asc')
                    ->get()
                    ->pluck('customer_name','id')
                    ->toArray();

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

            $salesMan=[''=>'select salesman']+salesman::orderBy('salesman_name', "asc")
                    ->pluck("salesman_name", "id")
                    ->toArray();
        }else{
            $pterms="";
            $customer=[''=>'select customer']+customers::orderBy('customer_name', "asc")
                    ->where("id",$quot->customer)
                    ->pluck("customer_name", "id")
                    ->toArray();
            $payment_terms = payment_terms::orderBy("terms_name", "asc")
                ->get();
            foreach ($payment_terms as $pt) {
                $pterms .= "<option value='" . $pt->days . "'>" . $pt->terms_name . "</option>";
            }

            $duedate = Date('d-m-Y', strtotime('+ 15 days'));

            $salesMan=[''=>'select salesman']+salesman::orderBy('salesman_name', "asc")
                    ->where("id",$quot->salesman_id)
                    ->pluck("salesman_name", "id")
                    ->toArray();
        }

        //dd($quotitem);

        // Product/service/BOM picking on this page uses the select2 AJAX
        // search endpoint (product_search_options) now, so the full
        // product-table dump that used to be passed to the view is gone.

        //$term=terms::where('website_id',Session::get('website_id'))->first();

        $term=terms::query()
            ->get();

         $module=terms::query()
            ->get()
            ->pluck('module','id')
            ->toArray();

        return view("admin.sales/sales_order_add")->with(["module"=>$module,'contact_name'=>$contact_name,'duedate' => $duedate, 'payment_terms' => $pterms,'data'=>$quot,'quotitem'=>$quotitem,'customer'=>$customer,'term'=>$term, 'salesMan' => $salesMan]);
    }

}
