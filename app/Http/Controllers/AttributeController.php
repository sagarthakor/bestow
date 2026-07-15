<?php

namespace App\Http\Controllers;

use App\attribute;
use Illuminate\Http\Request;

class AttributeController extends Controller
{
    //
    function attribute_delete(Request $request)
    {
        $attribute=attribute::find($request->id);
        if($attribute->delete())
        {
            return redirect()->route("admin.attribute.list")
                ->with("message","Attribute delete successfully");
        }
    }

    function attribute_update(Request $request)
    {
        $attribute=attribute::find($request->id);
        $attribute->attribute_name=$request->attribute_name;
        if($attribute->save())
        {
            return redirect()->route("admin.attribute.list")
                ->with("message","Attribute update successfully");
        }
    }
    function edit(Request $request)
    {
        $data=attribute::find($request->id);
        return view("admin.attribute.edit",compact("data"));
    }

    function attribute_save(Request $request)
    {
        $attribute=new attribute();
        $attribute->attribute_name=$request->attribute_name;
        if($attribute->save())
        {
            return redirect()->route("admin.attribute.list")->with("message","Attribute save successfully");
        }
    }
    function list(Request $request)
    {
        $list=attribute::orderBy('attribute_name', 'asc')->paginate(session('records_per_page', 30));
        return view("admin/attribute/index",compact("list"));
    }
}
