<?php

namespace App\Http\Controllers;

use App\country;
use App\formula_material;
use App\formula_mst;
use App\formula_mst_item;
use App\product;
use Illuminate\Http\Request;
use Session;
use App\raw_material_group;

class FormulaController extends Controller
{
    //
    function formula_delete(Request $request)
    {
        $data=formula_mst::find($request->id);
        if($data->delete())
        {
            return redirect()->route("admin.production.formula_list")->with("message","Formula Deleted");
        }
    }

    function formula_view(Request $request)
    {
        $data=formula_mst::find($request->id);

        $data_item=formula_mst_item::select("formula_mst_item.*","product.product_name","uom.uom_name")
            ->leftJoin("product","product.id","formula_mst_item.material")
            ->leftJoin("uom","uom.id","product.uom")
            ->orderBy("formula_mst_item.id","asc")
            ->where("formula_mst_item.formula_id",$data->id)
            ->get();
        //dd($data_item);
        $rawmaterial=product::where("status","raw material")
            ->orderBy("product_name","asc")->get();

        return view("admin.formula.formula_view",compact('data','rawmaterial','data_item'));
    }

    function formula_update(Request $request)
    {
        //dd($request->all());
        $request->validate([
           'nos'=>'required',
           'size'=>'required',
           'required_qty'=>'required'
        ]);
        $formula_mst=formula_mst::find($request->id);
        $formula_mst->nos=$request->nos;
        $formula_mst->size=$request->size;
        $formula_mst->required_qty=$request->required_qty;
        if($formula_mst->save())
        {
            formula_mst_item::where("formula_id",$formula_mst->id)->delete();

            if(isset($request->material))
            {
                $tot=count($request->material);
                for ($i=0;$i<$tot;$i++)
                {
                    if(empty($request->material[$i])){
                        continue;
                    }
                    $item=new formula_mst_item();
                    $item->formula_id=$formula_mst->id;
                    $item->nos=$request->nos;
                    $item->size=$request->size;
                    $item->required_qty=$request->required_qty;
                    $item->material=$request->material[$i];
                    // $item->percentage=$request->percentage[$i];
                    $item->qty=$request->qty[$i];
                    $item->save();
                }
            }


        }

        // $item=formula_mst_item::find($request->id);
        // $item->nos=$request->nos;
        // $item->size=$request->size;
        // $item->required_qty=$request->required_qty;
        // $item->material=$request->material;
        // $item->percentage=$request->percentage;
        // $item->qty=$request->qty;
        // if($item->save())
        // {
            return redirect()->route("admin.production.formula_list")->with("message","Formula updated");
        //}
    }

    function formula_mst_edit(Request $request)
    {
        $data=formula_mst::find($request->id);

        $data_item=formula_mst_item::select("formula_mst_item.*","product.product_name","uom.uom_name")
            ->leftJoin("product","product.id","formula_mst_item.material")
            ->leftJoin("uom","uom.id","product.uom")
            ->orderBy("formula_mst_item.id","asc")
            ->where("formula_mst_item.formula_id",$data->id)
            ->get();
        //dd($data_item);
        $rawmaterial=product::where("status","raw material")
            ->orderBy("product_name","asc")->get();

        return view("admin.formula.edit",compact('data','rawmaterial','data_item'));
    }

    function formula_list(Request $request)
    {

        $data=formula_mst::with('product_item:id,product_name')
            ->search($request->search,['product_item.product_name', 'nos', 'size'])->paginate(session('records_per_page', 30));

        return view("admin.formula.list",compact('data'));
    }

    function formula_item_save(Request $request)
    {
        //dd($request->all());
        $request->validate([
           'nos'=>'required',
           'size'=>'required',
           'product'=>'required|unique:formula_mst',
           'required_qty'=>'required'
        ]);
        $formula_mst=new formula_mst();
        $formula_mst->nos=$request->nos;
        $formula_mst->size=$request->size;
        $formula_mst->required_qty=$request->required_qty;
        $formula_mst->product=$request->product;
        if($formula_mst->save())
        {
            if(isset($request->material))
            {
                $tot=count($request->material);
                for ($i=0;$i<$tot;$i++)
                {
                    if(empty($request->material[$i])){
                        continue;
                    }
                    $item=new formula_mst_item();
                    $item->formula_id=$formula_mst->id;
                    $item->nos=$request->nos;
                    $item->size=$request->size;
                    $item->required_qty=$request->required_qty;
                    $item->material=$request->material[$i];
                    //$item->percentage=$request->percentage[$i];
                    $item->qty=$request->percentage[$i];
                    $item->product=$request->product;
                    $item->save();
                }
            }


        }

        return redirect()->route("admin.production.formula_list")->with("message","formula create successfully");
    }

    function formula_add(Request $request)
    {
        $rawmaterial=product::where("status","raw material")
            ->orderBy("product_name","asc")->get();

        $product=product::orderBy("product_name","asc")->where("status","product")->get();
        $formula_material=formula_material::select("formula_material.*","raw_material_group.group_name","raw_material_group.uom")
            ->leftJoin("raw_material_group","raw_material_group.id","formula_material.raw_mat")
            ->orderBy('formula_material.id','asc')
            ->get();

        $raw_material_group=raw_material_group::all();
        return view("admin.formula.create",compact('rawmaterial','formula_material','raw_material_group',"product"));
    }
    function formula_material_save(Request $request)
    {
        //dd($request->all());
        if($request->action=="update")
        {
            formula_material::truncate();
        }
        $tot=count($request->raw_material);
        if($tot > 0)
        {
            for($i=0;$i<$tot;$i++)
            {
                $fm=new formula_material();
                $fm->raw_mat=$request->raw_material[$i];
                $fm->percentage=$request->required_qty_per[$i];
                $fm->user_id=Session::get('user_id');
                $fm->save();
            }
        }
        return back()->with("message","formula material update");
    }
}
