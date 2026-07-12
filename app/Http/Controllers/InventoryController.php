<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Session;

class InventoryController extends Controller
{
    //
    function items(Request $request)
    {
        $product=DB::table('product');
        $product=$product->select('product.*','gst.gst_per','uom.uom_name','category.category_name as catname','material.material_name as matname');
        $product=$product->leftJoin('gst','gst.id','product.gst');
        $product=$product->leftJoin('uom','uom.id','product.uom');
        $product=$product->leftJoin('category','category.id','product.category');
        $product=$product->leftJoin('material','material.id','product.material');
        $product=$product->where('product.status','product');
        $product=$product;


        if(isset($request->product_name))
        {
            $product=$product->where('product.product_name','like','%'.$request->product_name.'%');
        }

        if(isset($request->category))
        {
            $product=$product->where('product.category_name','like','%'.$request->category.'%');
        }

        if(isset($request->material))
        {
            $product=$product->where('product.material_name','like','%'.$request->material.'%');

        }

        if(isset($request->usage_unit))
        {
            $product=$product->where('uom.uom_name','like','%'.$request->usage_unit.'%');

        }

        if(isset($request->price))
        {
            $product=$product->where('product.price','like','%'.$request->price.'%');

        }

        if(isset($request->gst))
        {
            $product=$product->where('gst.gst_per','like','%'.$request->gst.'%');

        }
        $product=$product->orderBy("id","desc");
        $product=$product->paginate(10);


        // dd($product);
        return view("admin/product-list")->with(['data'=>$product]);
    }
}
