<?php

namespace App\Http\Controllers;

use App\Exports\ProductExport;
use Illuminate\Http\Request;
use     Excel;
use DB;
use Session;
use App\product;
use App\category;
use App\material;
use App\uom;
use App\gst;
class ExportController extends Controller
{
    //
    public function excel_export()
    {
        $product=DB::table('product');
        $product=$product->select('product.*','gst.gst_per','uom.uom_name','category.category_name as catname','material.material_name as matname');
        $product=$product->leftJoin('gst','gst.id','product.gst');
        $product=$product->leftJoin('uom','uom.id','product.uom');
        $product=$product->leftJoin('category','category.id','product.category');
        $product=$product->leftJoin('material','material.id','product.material');
        $product=$product->where('product.status','product');
        $product=$product->where('product.website_id',Session::get('website_id'));


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

        $data=$product->get();
        return Excel::download(new ProductExport($data), 'Product.xlsx');

    }
}
