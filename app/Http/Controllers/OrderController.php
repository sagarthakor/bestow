<?php

namespace App\Http\Controllers;

use App\company;
use App\contact;
use App\customers;
use App\finacial_year;
use App\payment_terms;
use App\product;
use App\quotation;
use App\quotation_item;
use App\terms;
use Illuminate\Http\Request;
use App\customer_order;
use App\customer_order_item;
use Session;

class OrderController extends Controller
{
    //
    function quot_save(Request $request)
    {
        //dd($request->all());
        date_default_timezone_set('Asia/Kolkata');

        $customer_name=customers::find($request->customer);

        $request->validate([
            'quot_stage' => 'required',
            'valid_until'=>'required',
            'customer'=>'required',
            'quot_date'=>'required',
            'product'=>'required',
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
            'term_condition'=>'required',

        ]);
        $now = date('Y-m-d',strtotime($request->quot_date));
        //die;
        $finacial_year= finacial_year::where('start_date', '<=', $now)->where('end_date', '>=', $now)
            ->first();
        //dd($finacial_year);
        $qno=quotation::where("finacial_year",$finacial_year->id)
            ->max('quot_no');

        //dd($qno);

        date_default_timezone_set('Asia/Kolkata');

        $customer_name=customers::find($request->customer);

        $request->validate([
            'quot_stage' => 'required',
            'subject' => 'required',
            'valid_until'=>'required',
            'customer'=>'required',
            'quot_date'=>'required',
            'product'=>'required',
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
            'term_condition'=>'required',

        ]);
        //$qno=quotation::max('quot_no');

        if(empty($qno))
        {
            $year = date("y",strtotime($request->quot_date));
            $nextyear = $year +1;

            $n2=str_pad($qno + 1, 4, 0, STR_PAD_LEFT);
            $qno=0;
            $n2 .='/';
            $n2 .=$year;
            $n2 .='-';
            $n2 .=$nextyear;
        }else{
            $year = date("y",strtotime($request->quot_date));
            $nextyear = $year +1;
            $n2 = str_pad($qno + 1, 4, 0, STR_PAD_LEFT);
            $n2 .='/';
            $n2 .=$year;
            $n2 .='-';
            $n2 .=$nextyear;
        }
        $customer_name=customers::find($request->customer);

        $quotation=new quotation();
        $quotation->quot_no=$qno+1;
        $quotation->quotation_no=$n2;
        $quotation->quot_date=date('Y-m-d',strtotime($request->quot_date));
        $quotation->subject=$request->subject;
        $quotation->customer=$request->customer;
        $quotation->contact_name=$request->contact_name;
        $quotation->customer_name=$customer_name->customer_name;
        $quotation->quot_stage=$request->quot_stage;
        $quotation->valid_until=date('Y-m-d',strtotime($request->valid_until));
        $quotation->billing_address=$request->billing_address;
        $quotation->shipping_address=$request->shipping_address;
        $quotation->billing_pobox=$request->billing_pobox;
        $quotation->shipping_pobox=$request->shipping_pobox;
        $quotation->billing_city=$request->billing_city;
        $quotation->shipping_city=$request->shipping_city;
        $quotation->billing_state=$request->billing_state;
        $quotation->shipping_state=$request->shipping_state;
        $quotation->billing_postalcode=$request->billing_postalcode;
        $quotation->shipping_postalcode=$request->shipping_postalcode;
        $quotation->billing_country=$request->billing_country;
        $quotation->shipping_country=$request->shipping_country;
        $quotation->term_condition=$request->term_condition;
        $quotation->website_id=Session::get('website_id');

        $quotation->user_id=Session::get('user_id');
        $quotation->gst_amount=$request->igsttotal;
        $quotation->cgsttotal=$request->cgsttotal;
        $quotation->sgsttotal=$request->sgsttotal;

        $quotation->adjustment=$request->adjustment;
        $quotation->discount_total=$request->discount_total;
        $quotation->net_amount=$request->item_total;
        $quotation->grand_total=$request->grand_total;
        $quotation->module=$request->module;
        $quotation->remark=$request->remark;
        $quotation->datetime=date('Y-m-d h:i:s a');
        $quotation->payment_terms=$request->payment_terms;
        $quotation->finacial_year=$finacial_year->id;
        if($quotation->save())
        {
            if(empty($request->product))
            {}else{
                $totproduct=count($request->product);
                for($i=0;$i<$totproduct;$i++)
                {

                    $item=new quotation_item();
                    $item->quot_no=$qno+1;
                    $item->quotation_no=$n2;
                    $item->product=$request->product[$i];
                    $item->description=$request->description[$i];
                    $item->custom_description=$request->custom_description[$i];
                    $item->qty=$request->qty[$i];
                    $item->customer=$request->customer;
                    if(empty($request->inner_diamitter[$i]))
                    {}else{
                        $item->inner_diameter=$request->inner_diamitter[$i];
                    }
                    if(empty($request->outer_diamitter[$i]))
                    {}else{
                        $item->outer_diameter=$request->outer_diamitter[$i];
                    }
                    if(empty($request->thikness[$i]))
                    {}else{
                        $item->thikness=$request->thikness[$i];
                    }
                    if(empty($request->hsn[$i]))
                    {}else{
                        $item->hsn=$request->hsn[$i];
                    }
                    $item->price=$request->price[$i];
                    $item->amount=$request->total_amount[$i];

                    $item->discount_per=$request->discount_per[$i];
                    $item->discount_amount=$request->discount_amount[$i];
                    $item->cgst_per=$request->cgst_per[$i];
                    $item->cgst_amount=$request->cgst_amount[$i];
                    $item->sgst_per=$request->sgst_per[$i];
                    $item->sgst_amount=$request->sgst_amount[$i];
                    $item->gst_per=$request->gst_per[$i];
                    $item->gst_amount=$request->gst_amount[$i];
                    $item->grand_total=$request->net_price[$i];
                    $item->save();
                }
            }

            if(session()->has('cart'))
            {
                $cart = session()->get('cart');
                foreach(session('cart') as $id => $details)
                {
                    unset($cart[$id]);
                    session()->put('cart', $cart);
                }
            }

            $orderlist=customer_order::find($request->id);
            $orderlist->quot_status="Y";
            $orderlist->save();

            return redirect()->route("quotation_list")->with('message','quotation create successfully');
        }else{
            return back();
        }
    }
    function quot_add(Request $request)
    {
        $orderlist=customer_order::find($request->id);

        $quotitem=customer_order_item::select('customer_order_item.*','uom.uom_name','product.product_name','product.product_image',"product.item_code","stock_status.qty as stockqty","product.price as pprice")
            ->leftJoin('product','product.id','customer_order_item.product')
            ->leftJoin('uom','uom.id','product.uom')
            ->leftJoin("stock_status","stock_status.product","product.id")
            ->where('customer_order_item.order_no',$orderlist->order_number)
            ->get();
        //dd($orderlist);


        $customer=customers::where('id',$orderlist->customer)
            ->get()
            ->pluck("customer_name","id")
            ->toArray();


        if(empty($orderlist->contact_name))
        {
            $contact_name=contact::query()
                    ->where('customer',$orderlist->customer)
                    ->orderBy('contact_name','asc')
                    ->get()
                    ->pluck('contact_name','id')
                    ->toArray();
        }else{
            $cname=contact::select('id','contact_name')->where('id',$orderlist->contact_name)->first();

            $contact_name=[$cname->id=>$cname->contact_name]+contact::query()
                    ->where('customer',$orderlist->customer)
                    ->orderBy('contact_name','asc')
                    ->get()
                    ->pluck('contact_name','id')
                    ->toArray();
        }

        $product=product::select('product.*','gst.gst_per','uom.uom_name',"stock_status.qty as stockqty")
            ->leftJoin('gst','gst.id','product.gst')
            ->leftJoin('uom','uom.id','product.uom')
            ->leftJoin("stock_status","stock_status.product","product.id")
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
        $customer_terms = customers::where('id', $orderlist->customer)
            ->first();

        $pterms = "";
        $duedate = date('d-m-Y');

        if(empty($orderlist->payment_terms))
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
            $payment_terms1 = payment_terms::where("days", $orderlist->payment_terms)
                ->first();
            //  dd($orderlist->payment_terms);

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

        $duedate = Date('d-m-Y', strtotime('+ 15 days'));

        $module=[''=>'select terms']+terms::query()
                ->get()
                ->pluck('module','id')
                ->toArray();
        return view("admin.order.order_quot")->with(["contact_name"=>$contact_name,"payment_terms"=>$pterms,"module"=>$module,"duedate"=>$duedate,'bom'=>$bom,'data'=>$orderlist,'quotitem'=>$quotitem,'customer'=>$customer,'product'=>$product,'service'=>$service,'term'=>$term]);

    }
    function order_update(Request $request)
    {
        //dd($request->all());
        $customerorder=customer_order::find($request->id);
        $customerorder->order_date=date('Y-m-d',strtotime($request->order_date));
        $customerorder->datetime=date('Y-m-d h:i:s');

        $customerorder->billing_address=$request->billing_address;
        $customerorder->shipping_address=$request->shipping_address;
        $customerorder->billing_city=$request->billing_city;
        $customerorder->shipping_city=$request->shipping_city;
        $customerorder->billing_state=$request->billing_state;
        $customerorder->shipping_state=$request->shipping_state;
        $customerorder->billing_postalcode=$request->billing_postalcode;
        $customerorder->shipping_postalcode=$request->shipping_postalcode;
        $customerorder->billing_country=$request->billing_country;
        $customerorder->shipping_country=$request->shipping_country;
        $customerorder->term_condition=$request->term_condition;
        $customerorder->payment_terms=$request->payment_terms;
        $customerorder->status=$request->status;
        $customerorder->net_amount=$request->item_total;
        $customerorder->cgsttotal=$request->cgsttotal;
        $customerorder->sgsttotal=$request->sgsttotal;
        $customerorder->gst_amount=$request->igsttotal;
        $customerorder->adjustment=$request->adjustment;
        $customerorder->grand_total=$request->grand_total;

        if($customerorder->save())
        {
            $totitem=count($request->item_id);
            for($i=0;$i<$totitem;$i++)
            {
                $citem=customer_order_item::find($request->item_id[$i]);
                $citem->product=$request->product[$i];
                $citem->custom_description=$request->custom_description[$i];
                $citem->attribute1=$request->attribute1[$i];
                $citem->value1=$request->value1[$i];
                $citem->attribute2=$request->attribute2[$i];
                $citem->value2=$request->value2[$i];
                $citem->qty=$request->qty[$i];
                $citem->price=$request->price[$i];
                $citem->total_amount=$request->total_amount[$i];
                $citem->discount_per=$request->discount_per[$i];
                $citem->discount_amount=$request->discount_amount[$i];
                $citem->cgst_per=$request->cgst_per[$i];
                $citem->cgst_amount=$request->cgst_amount[$i];
                $citem->sgst_per=$request->sgst_per[$i];
                $citem->sgst_amount=$request->sgst_amount[$i];
                $citem->gst_per=$request->gst_per[$i];
                $citem->gst_amount=$request->gst_amount[$i];
                $citem->net_price=$request->net_price[$i];
                $citem->save();

            }

            return redirect()->route("order_list")->with("message","Order Update Successfully");
        }
    }
    function order_edit(Request $request)
    {
        $orderlist=customer_order::find($request->id);

        $quotitem=customer_order_item::select('customer_order_item.*','product.product_name')
            ->leftJoin('product','product.id','customer_order_item.product')
            ->where('customer_order_item.order_no',$orderlist->order_number)
            ->get();
        //dd($orderlist);


        $customer=customers::where('id',$orderlist->customer)
            ->get()
            ->pluck("customer_name","id")
            ->toArray();


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
        $customer_terms = customers::where('id', $orderlist->customer)
            ->first();

        $pterms = "";
        $duedate = date('d-m-Y');

        if(empty($orderlist->payment_terms))
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
            $payment_terms1 = payment_terms::where("days", $orderlist->payment_terms)
                ->first();
          //  dd($orderlist->payment_terms);

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

        $duedate = Date('d-m-Y', strtotime('+ 15 days'));

        $module=[''=>'select terms']+terms::query()
                ->get()
                ->pluck('module','id')
                ->toArray();
        return view("admin.order.edit")->with(["payment_terms"=>$pterms,"module"=>$module,"duedate"=>$duedate,'bom'=>$bom,'data'=>$orderlist,'quotitem'=>$quotitem,'customer'=>$customer,'product'=>$product,'service'=>$service,'term'=>$term]);

    }
    function order_list(Request $request)
    {
        $orderlist=new customer_order();
        $orderlist=$orderlist->select("customer_order.*");
        if(isset($request->salaesorder_no))
        {
            $orderlist=$orderlist->where("order_number",$request->salaesorder_no);
        }
        if(isset($request->salesorder_date))
        {
            $date=date('Y-m-d',strtotime($request->salesorder_date));
            $orderlist=$orderlist->where("order_date",$date);
        }
        if(isset($request->client_name))
        {
            $orderlist=$orderlist->where("customer_name","like",'%'.$request->client_name.'%');
        }
        $orderlist=$orderlist->orderBy("id","desc");
        $orderlist=$orderlist->paginate(10);
        $company_name=company::select('company_name')->first();
        return view("admin.order.list")->with(['list'=>$orderlist,'company'=>$company_name->company_name]);
    }
}
