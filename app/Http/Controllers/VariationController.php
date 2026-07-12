<?php

namespace App\Http\Controllers;

use App\attribute;
use App\variation;
use Illuminate\Http\Request;

class VariationController extends Controller
{
    public function delete(Request $request)
    {
        $variation = variation::find($request->id);
        if ($variation->delete()) {
            return redirect()->route("admin.variation.list")
                ->with("message", "variation deleted successfully");
        }
    }

    //
    function update(Request $request)
    {
        $variation = variation::find($request->id);
        $variation->attribute = $request->attribute;
        $variation->variation_name = $request->variation_name;
        if ($variation->save()) {
            return redirect()->route("admin.variation.list")
                ->with("message", "variation update successfully");
        }
    }

    function insert(Request $request)
    {
        $variation = new variation();
        $variation->attribute = $request->attribute;
        $variation->variation_name = $request->variation_name;
        if ($variation->save()) {
            return redirect()->route("admin.variation.list")
                ->with("message", "variation save successfully");
        }
    }

    function add(Request $request)
    {
        $attribute = ['' => 'select attribute'] + attribute::orderBy('attribute_name', 'asc')->get()
                ->pluck("attribute_name", "id")->toArray();

        return view("admin.variation.create", compact("attribute"));
    }

    function list()
    {
        $list = variation::select("variation.*", "attribute.attribute_name")
            ->leftJoin("attribute", "attribute.id", "variation.attribute")
            ->orderBy("variation.id", "desc")
            ->paginate(10);

        return view("admin/variation/index", compact("list"));
    }
}
