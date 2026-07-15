<?php

namespace App\Http\Controllers;

use App\customer_order;
use App\customer_order_item;
use App\salesman;
use Illuminate\Http\Request;
use Session;

class SalesmanController extends Controller
{
    //
    function front_salesman_update(Request $request)
    {
        $data=salesman::findorFail($request->id);
        $data->salesman_name=$request->salesman_name;
        $data->salesman_area=$request->salesman_area;
        $data->salesman_code=$request->salesman_code;
        $data->salesman_mobile=$request->salesman_mobile;
        $data->salesman_email=$request->salesman_email;
        $data->salesman_password=$request->salesman_password;
        if($data->save())
        {
            return back()->with("message","salesman Details Update successfully");
        }
    }

    function order_list(Request $request)
    {
        $order=customer_order::where("salesman",Session::get("salesman_code"))->orderBy("id","desc")
            ->get();
        //dd($order);
        $order_item=customer_order_item::select("customer_order_item.*","product.product_name","product.product_image")
            ->leftJoin("product","product.id","customer_order_item.product")
            ->get();

        $salesman=salesman::find(Session::get("salesman_session"));

        return view("front.salesman.orderlist")
            ->with(['order'=>$order,'order_item'=>$order_item,'salesman'=>$salesman]);
    }
    function delete(Request $request)
    {
        $data=salesman::findorFail($request->id);
        if($data->delete())
        {
            return redirect()->route("admin.salesman.list")
                ->with("message","salesman Delete successfully");
        }
    }
    function salesman_update(Request $request)
    {
        $data=salesman::findorFail($request->id);
        $data->salesman_name=$request->salesman_name;
        $data->salesman_area=$request->salesman_area;
        $data->salesman_code=$request->salesman_code;
        $data->salesman_mobile=$request->salesman_mobile;
        $data->salesman_email=$request->salesman_email;
        $data->salesman_password=$request->salesman_password;
        if($data->save())
        {
            return redirect()->route("admin.salesman.list")
                ->with("message","salesman Update successfully");
        }
    }
    function edit(Request $request)
    {
        $data=salesman::findorFail($request->id);
        return view("admin.salesman.edit",compact('data'));
    }
    function salesman_save(Request $request)
    {
        $data=new salesman();
        $data->salesman_name=$request->salesman_name;
        $data->salesman_area=$request->salesman_area;
        $data->salesman_code=$request->salesman_code;
        $data->salesman_mobile=$request->salesman_mobile;
        $data->salesman_email=$request->salesman_email;
        $data->salesman_password=$request->salesman_password;
        if($data->save())
        {
            return redirect()->route("admin.salesman.list")
                ->with("message","salesman save successfully");
        }
    }

    function create()
    {
        return view("admin.salesman.create");
    }
    function index(Request $request)
    {
        $list=salesman::orderBy("id","desc")->paginate(session('records_per_page', 30));
        return view("admin.salesman.list",compact('list'));
    }
}
