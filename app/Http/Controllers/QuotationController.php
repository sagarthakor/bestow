<?php

namespace App\Http\Controllers;

use App\contact;
use App\customers;
use App\company;
use App\product;
use App\quotation;
use App\quotation_item;
use PDF;
use App\terms;
use Illuminate\Http\Request;
use Session;
class QuotationController extends Controller
{
    //
    function revise_quot_save(Request $request)
    {
        $quot=quotation::where('id',$request->id)
            ->first();
        if($quot)
        {
            $revise_quot_no=quotation::where('id',$request->id)
                ->max("revise_quot_no");

            if(empty($revise_quot_no))
            {
                $reviseno=1;
            }else{
                $reviseno=$revise_quot_no+1;
            }
            $rno=$quot->quotation_no;
            $rno .='-amended-';
            $rno .=$reviseno;


            $customer_name=customers::find($request->customer);

            $quotation=new quotation();
            $quotation->quot_no=$quot->quot_no;
            $quotation->quotation_no=$rno;
            $quotation->revise_quot_no=$reviseno;
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
            if($quotation->save())
            {
                if(empty($request->product))
                {}else{
                    $totproduct=count($request->product);
                    for($i=0;$i<$totproduct;$i++)
                    {
                        $item=new quotation_item();
                        $item->quot_no=$quot->quot_no;
                        $item->quotation_no=$rno;
                        $item->product=$request->product[$i];
                        $item->description=$request->description[$i];
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


                return redirect()->route("quotation_list")->with('message','quotation create successfully');
            }else{
                return back();
            }

        }

    }

    function quot_revise(Request $request)
    {
        $quot=quotation::where('id',$request->id)
            ->first();

        //dd($quot);
        $quotitem=quotation_item::select('quot_item.*','product.product_name', 'product.value1', 'product.value2','product.make','product.model')
            ->leftJoin('product','product.id','quot_item.product')
            ->where('quot_item.quot_no',$quot->quot_no)
            ->get();


        $customer=[''=>'select customer']+customers::query()->orderBy('customer_name','asc')
                ->get()
                ->pluck('customer_name','id')
                ->toArray();

        if(empty($quot->contact_name))
        {
            $contact_name=[''=>'select contact']+contact::query()
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




        // Product/service/BOM picking on this page uses the select2 AJAX
        // search endpoint (product_search_options) now, so the full
        // product-table dump that used to be passed to the view is gone.

        //$term=terms::where('website_id',Session::get('website_id'))->first();

        $module=terms::query()
            ->get()
            ->pluck('module','id')
            ->toArray();


        return view("admin.quotation.quot_revise")->with(['data'=>$quot,'quotitem'=>$quotitem,'customer'=>$customer,'module'=>$module,'contact_name'=>$contact_name]);

    }
    
    function quot_proforma(Request $request)
    {

       // $quots=quotation::where('quot_no',$request->quot_no)->first();
       // $quots->customer=$request->customer;
       // $quots->quot_date=date('Y-m-d',strtotime($request->date));
       // $quots->save();

        $quot=quotation::select('quotation.*','customers.customer_name',"customers.owner_gst",'customers.primary_phone','customers.primary_email','contact.contact_name as contactname','contact.primary_phone','website_user.user_name','website_user.last_name','website_user.first_name','customers.tax_preference')
            ->leftJoin('customers','customers.id','quotation.customer')
            ->leftJoin('state','state.id','customers.state')
            ->leftJoin('contact','contact.id','quotation.contact_name')
            ->leftJoin('website_user','website_user.id','quotation.user_id')
            ->where('quotation.id',$request->id)
            ->first();

        $quotitem=quotation_item::select('quot_item.*','product.product_name', 'product.value1', 'product.value2','product.make','product.model','product.material_name','category.category_image','product.product_image',"product.hsn","product.item_code",'category.category_name as catname','uom.uom_name')
            ->leftJoin('product','product.id','quot_item.product')
            ->leftJoin('category','category.id','product.category')
            ->leftJoin('uom','uom.id','product.uom')
            ->where('quot_item.quotation_no',$quot->quotation_no)
            ->get();
        //dd($quotitem);

        $discsum=quotation_item::select('quot_item.*','product.product_name', 'product.value1', 'product.value2','product.make','product.model','product.product_image','product.material_name','category.category_image')
        ->leftJoin('product','product.id','quot_item.product')
        ->leftJoin('category','category.id','product.category')
        ->where('quot_item.quotation_no',$quot->quotation_no)
        ->sum('quot_item.discount_amount');

        //dd($discsum);

        $company=company::select("company.*","state.state_name")
            ->leftJoin("state","state.id","company.state")
            ->first();

        $terms=terms::query()->first();

        $filename=$quot->quotation_no;

        $filename .="_".$quot->customer_name;
        $filename .='.pdf';

         //LogActivity::addToLog($quot->quotation_no.' Quotation Print');


       $pdf = PDF::loadView('admin.quotation_proforma', compact('quot','quotitem','company','terms','discsum'));
        //return view('admin.quotation_print_old', compact('quot','quotitem','company','terms','discsum'));

//        $pdf = PDF::loadView('admin.quotation_print', compact('quot','quotitem','company','terms','discsum'));

        return $pdf->download($filename);

        //return view('admin.quotation_print')->with(['quot'=>$quot,'quotitem'=>$quotitem,'company'=>$company]);

    }
}
