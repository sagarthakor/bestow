<?php

namespace App\Http\Controllers;

use App\category;
use App\country;
use App\formula_material;
use App\formula_mst;
use App\formula_mst_item;
use App\product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Session;

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

        $data_item=formula_mst_item::select("formula_mst_item.*","product.product_name","product.value1","product.value2","uom.uom_name")
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

        $existingItems = formula_mst_item::where("formula_id", $data->id)->get()->keyBy('material');

        // Fixed raw-material categories (matches ProductionController::getproduct_image,
        // the same set that used to live on the product form before the redesign).
        $categories = [
            "Cotton" => "Cotton",
            "Spendex" => "Spendex",
            "Elastics" => "Elastics",
            "Nylon" => "Nylon",
            "Polyester" => "Polyester",
            "P_P_Yarn" => "P.P Yarn",
        ];

        $categoryRows = [];
        foreach ($categories as $group => $label) {
            $materials = DB::table('product')
                ->leftJoin('uom', 'uom.id', 'product.uom')
                ->select('product.id', 'product.product_name', 'product.value1', 'product.value2', 'uom.uom_name')
                ->where('product.raw_material_group', $group)
                ->orderBy('product.product_name', 'asc')
                ->get();

            $selected = null;
            foreach ($materials as $material) {
                if ($existingItems->has($material->id)) {
                    $selected = $existingItems->get($material->id);
                    break;
                }
            }

            $categoryRows[] = [
                'group' => $group,
                'label' => $label,
                'materials' => $materials,
                'selected_material' => $selected->material ?? '',
                'selected_qty' => $selected->qty ?? '',
            ];
        }

        return view("admin.formula.edit",compact('data','categoryRows'));
    }

    function formula_list(Request $request)
    {
        $query = formula_mst::with('product_item:id,product_name,value1,value2');

        // Eloquence's ->search() scored relevance against a threshold rather
        // than requiring every typed word to match, so a query like "navy
        // socks white patti" matched on "socks" alone was enough to pull in
        // every sock formula. Word-by-word AND, same rule as the product
        // picker (product_search_options), is what the search box actually
        // needs to narrow down to one formula.
        $words = preg_split('/\s+/', trim((string) $request->search), -1, PREG_SPLIT_NO_EMPTY) ?: [];
        foreach ($words as $word) {
            $like = '%' . str_replace(['%', '_'], ['\%', '\_'], $word) . '%';
            $query->where(function ($q) use ($like) {
                $q->where('nos', 'like', $like)
                    ->orWhere('size', 'like', $like)
                    ->orWhereHas('product_item', function ($p) use ($like) {
                        $p->where('product_name', 'like', $like)
                            ->orWhere('value1', 'like', $like)
                            ->orWhere('value2', 'like', $like);
                    });
            });
        }

        $data = $query->paginate(session('records_per_page', 30))->appends($request->all());

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
                    $item->qty=$request->qty[$i];
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

        $socksCategoryId = category::where('category_name', 'Socks')->value('id');
        $product=product::orderBy("product_name","asc")
            ->where("status","product")
            ->where("category", $socksCategoryId)
            ->get();

        return view("admin.formula.create",compact('rawmaterial',"product"));
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
