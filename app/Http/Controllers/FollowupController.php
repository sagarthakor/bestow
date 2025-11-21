<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\product;
use App\customers;
use App\quotation_item;
use App\quotation;
use App\company;
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
use App\contact;
use App\material;
use App\vendor_contact;
use App\salesorder_item;
use App\salesorder;
use App\quot_followup;

class FollowupController extends Controller
{
    //
    function quot_followup_save(Request $request)
    {
        //dd($request->all());
        date_default_timezone_set("Asia/Kolkata");
        $request->validate([
            'quotid' => 'required',
            'followup_date' => 'required',
            'next_followup_date' =>'required',
            'remark' => 'required',
        ]);
        $quot=quotation::find($request->quotid);

        $quot_followup=new quot_followup();
        $quot_followup->quot_id=$quot->id;
        $quot_followup->quot_no=$quot->quotation_no;
        $quot_followup->quot_date=date('Y-m-d',strtotime($quot->quot_date));
        $quot_followup->followup_date=date('Y-m-d',strtotime($request->followup_date));
        $quot_followup->next_follwup_date=date('Y-m-d',strtotime($request->next_followup_date));
        $quot_followup->remark=$request->remark;
        $quot_followup->customer_name=$quot->customer_name;
        $quot_followup->quot_stage="Reviewing";
        $quot->quot_stage="Reviewing";
        $quot->save();
        $quot_followup->user_id=Session::get('user_id');
        $quot_followup->website_id=Session::get('website_id');
        $quot_followup->followp_time=date('Y-m-d h:i:s A');
        //dd($quot_followup);
        if($quot_followup->save())
        {

            return back()->with('message','Followup save successfully');
        }
    }

    function quot_followup(Request $request)
    {
        $quot=quotation::select('quotation.id as quotid','quotation.quotation_no','quotation.quot_date','quotation.quot_stage','customers.customer_name')
            ->leftJoin('customers','customers.id','quotation.customer')
            ->where('quotation.id',$request->id)
            ->where('quotation.website_id',Session::get('website_id'))
            ->first();

        $followup=quot_followup::where('quot_id',$request->id)->orderBy('id','desc')->get();

        return view("followup/quot_followup",compact('quot','followup'));
    }
}
