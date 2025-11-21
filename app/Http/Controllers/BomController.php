<?php

namespace App\Http\Controllers;

use App\brand;
use App\manufacturer;
use App\subcategory;
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
use App\bom;
use App\bom_sub_product;
use App\attribute;

class BomController extends Controller
{
    //
    function get_product(Request $request)
    {

        $data=product::select('product.*','gst.gst_per','uom.uom_name',"stock_status.qty as stockqty")
            ->leftJoin('gst','gst.id','product.gst')
            ->leftJoin('uom','uom.id','product.uom')
            ->leftJoin("stock_status","stock_status.product","product.id")
            ->orderBy('product_name','asc')
            ->where('product.id',$request->product)
            ->where('product.website_id',Session::get('website_id'))
            ->first();
       // dd($data);

        $sub_product=bom_sub_product::select('product.product_name',"stock_status.qty")
            ->leftJoin('product','product.id','bom_sub_product.product')
            ->leftJoin("stock_status","stock_status.product","product.id")
            ->where('bom_sub_product.bom_id',$data->id)
            ->get();
        $row="";
        foreach ($sub_product as $sprod)
        {
            $row .=$sprod->product_name.'stock on hand'.$sprod->qty;
        }
        //dd($row);
        $str=$data->description;

        // dd(htmlentities($str));

        if(empty($data->hsn))
        {
            $hsn="";
        }else{
            $material=material::where('id',$data->material)->first();
            //dd($material);
            $hsn=$material->hsn_code ?? '';
            $hsn=$data->hsn;
        }


            $productprice=$data->price;
            $discper=0;



            $igst=$data->gst_per;
            $cgstper=$data->gst_per/2;
            $sgstper=$data->gst_per/2;
        //dd($productprice);
       // dd($data);
        if(empty($data))
        {
            $user[]="";
        }else{
            $user[]=array('stockqty'=>$data->stockqty ?? 0,'product_name'=>$data->product_name,'price'=>$productprice,'gst'=>$igst,'sgst'=>$sgstper,'cgst'=>$cgstper,'uom'=>$data->uom_name,'description'=>$str,'product_image'=>$data->product_image,'outer_diameter'=>$data->outer_diameter,'inner_diameter'=>$data->inner_diameter,'thikness'=>$data->thikness,'hsn'=>$hsn,'discper'=>$discper);
        }
        //dd($user);
        return json_encode($user);
    }

    function bom_invoice_add(Request $request)
    {
    	$bom=product::find($request->id);

    	$customer=customers::orderBy('customer_name','asc')
    	->get();

    	return view("admin/bom/bom_invoice_add")->with(['bom'=>$bom,'customer'=>$customer]);
    }

    function bom_delete(Request $request)
    {
    	$bom=product::find($request->id);
    	if($bom->delete())
    	{
    		bom_sub_product::where('bom_id',$request->id)->delete();

    		return redirect()->route("bom/list")->with('message','BOM Delete Successfully');

    	}
    }
    function bom_update(Request $request)
    {
       
    	date_default_timezone_set('Asia/Kolkata');

    	$request->validate([

                'product_name' => 'required',
                'price'=>'required',
            ]);

        $bom=product::find($request->id);
        $bom->bar_code=$request->bar_code;
        $bom->product_name=$request->product_name;
        $bom->created_time=date('d-m-Y h:i:s a');
        $bom->item_total=$request->item_total;
        $bom->category=$request->category;
        $bom->subcategory=$request->subcategory;
        //dd($bom);
        $bom->material=$request->material;
        $bom->inner_diameter=$request->inner_diameter ?? "";
        $bom->outer_diameter=$request->outer_diameter ?? "";
        $bom->thikness=$request->thikness ?? "";
        $bom->hsn=$request->hsn;
        $bom->price=$request->price;
        $bom->gst=$request->gst;
        $bom->uom=$request->uom;
        $bom->description=$request->description;
        $bom->status='bom';
        $bom->attribute1=$request->attribute1 ?? "";
        $bom->value1=$request->value1 ?? "";
        $bom->attribute2=$request->attribute2 ?? "";
        $bom->value2=$request->value2 ?? "";
        $bom->sku=$request->sku;

        if(empty($request->manufacturer)){}else{
            $manufacturername=manufacturer::find($request->manufacturer);
            $bom->manufacturer_name=$manufacturername->manufacturer_name;
            $bom->manufacturer=$request->manufacturer;
        }
        if(empty($request->brand)){}else{
            $brandname=brand::find($request->brand);
            $bom->brand_name=$brandname->brand_name;
            $bom->brand=$request->brand;
        }

        if ($request->hasFile('product_image')) {

            $image = $request->file('product_image');
            $name = time().'.'.$image->getClientOriginalExtension();
            $destinationPath = public_path('/product_image/');
            $image->move($destinationPath, $name);

            $bom->product_image=$name;
        }

        if(isset($request->show_hide))
        {
            $bom->show_hide=$request->show_hide;
        }else{
            $bom->show_hide="";
        }

        if(isset($request->show_hide))
        {
            $bom->show_hide=$request->show_hide;
        }else{
            $bom->show_hide="hide";
        }

        if(isset($request->price_show_hide))
        {
            $bom->price_show_hide=$request->price_show_hide;
        }else{
            $bom->price_show_hide="hide";
        }

    	if($bom->save())
    	{
    		bom_sub_product::where('bom_id',$request->id)->delete();

    		$totprod=count($request->product);
    		for($i=0;$i<$totprod;$i++)
    		{
    			$bom_sub_product=new bom_sub_product();
    			$bom_sub_product->bom_id=$request->id;
    			$bom_sub_product->product=$request->product[$i];
    			$bom_sub_product->description=$request->p_description[$i];
    			$bom_sub_product->inner_daimitter=$request->p_inner_diamitter[$i] ?? "";
    			$bom_sub_product->outer_daimitter=$request->p_outer_diamitter[$i] ?? "";
    			$bom_sub_product->thikness=$request->p_thikness[$i] ?? "";
    			$bom_sub_product->hsn=$request->p_hsn[$i];
    			$bom_sub_product->uom=$request->p_uom[$i];
    			$bom_sub_product->qty=$request->p_qty[$i];
    			$bom_sub_product->price=$request->p_price[$i];
    			$bom_sub_product->amount=$request->p_amount[$i];
    			$bom_sub_product->save();
    		}

    		return redirect()->route("bom/list")->with('message','BOM Update Successfully');
    	}
    }
    function bom_edit(Request $request)
    {
    	$bom=product::find($request->id);

    	$bom_item=bom_sub_product::select('bom_sub_product.*','product.product_name','stock_status.qty as stockqty')
    	->Join('product','product.id','bom_sub_product.product')
        ->join("stock_status","stock_status.product","bom_sub_product.product")
    	->where('bom_id',$request->id)
    	->get();

    	$product=product::orderBy('product_name','asc')->get();
        
        $category=[''=>'select category']+category::where('website_id',Session::get('website_id'))
                ->orderBy('category_name','asc')
                ->get()
                ->pluck('category_name','id')
                ->toArray();

        $subcategory=[''=>'select category']+subcategory::orderBy('subcategory_name','asc')
                ->get()
                ->pluck('subcategory_name','id')
                ->toArray();

        $material=[''=>'select material']+material::where('website_id',Session::get('website_id'))
                ->orderBy('material_name','asc')
                ->get()
                ->pluck('material_name','id')
                ->toArray();

        $gst=[''=>'select gst']+gst::where('website_id',Session::get('website_id'))
                ->orderBy('gst_per','asc')
                ->get()->pluck('gst_per','id')->toArray();


        $uom=[''=>'select uom']+uom::where('website_id',Session::get('website_id'))
                ->orderBy('uom_name','asc')
                ->get()
                ->pluck('uom_name','id')
                ->toArray();

        $vendor=[''=>'select vendor']+vendor::where('website_id',Session::get('website_id'))
                ->orderBy('vendor_name','asc')
                ->get()
                ->pluck('vendor_name','id')
                ->toArray();

        $product=product::select("product.id","product.product_name","stock_status.qty as stockqty")
            ->orderBy('product.product_name','asc')
            ->leftJoin("stock_status","stock_status.product","product.id")
            ->get();

        $manufacturer=[''=>'select Manufacturer']+manufacturer::orderBy('manufacturer_name','asc')
                ->get()->pluck('manufacturer_name','id')->toArray();

        $brand=[''=>'select Brand']+brand::orderBy('brand_name','asc')
                ->get()->pluck('brand_name','id')->toArray();
        
         $attribute=attribute::orderBy('attribute_name', 'asc')->get();
         
    	return view("admin/bom/bom_edit")
            ->with(["attribute"=>$attribute,'subcategory'=>$subcategory,'brand'=>$brand,'manufacturer'=>$manufacturer,'bom'=>$bom,'item'=>$bom_item,'product'=>$product,'category'=>$category,'material'=>$material,'gst'=>$gst,'uom'=>$uom,'vendor'=>$vendor]);

    }

    function bom_preview(Request $request)
    {
        $bom=product::select('product.*','category.category_name','material.material_name','uom.uom_name','gst.gst_per')
            ->leftJoin('category','category.id','product.category')
            ->leftJoin('material','material.id','product.material')
            ->leftJoin('uom','uom.id','product.uom')
            ->leftJoin('gst','gst.id','product.gst')

            ->where('product.id',$request->id)
            ->first();

        $bom_item=bom_sub_product::select('bom_sub_product.*','product.product_name',"product.hsn","product.product_image")
            ->leftJoin('product','product.id','bom_sub_product.product')
            ->where('bom_id',$request->id)
            ->get();



        $category=[''=>'select category']+category::where('website_id',Session::get('website_id'))
                ->orderBy('category_name','asc')
                ->get()
                ->pluck('category_name','id')
                ->toArray();

        $material=[''=>'select material']+material::where('website_id',Session::get('website_id'))
                ->orderBy('material_name','asc')
                ->get()
                ->pluck('material_name','id')
                ->toArray();

        $gst=[''=>'select gst']+gst::where('website_id',Session::get('website_id'))
                ->orderBy('gst_per','asc')
                ->get()->pluck('gst_per','id')->toArray();


        $uom=[''=>'select uom']+uom::where('website_id',Session::get('website_id'))
                ->orderBy('uom_name','asc')
                ->get()
                ->pluck('uom_name','id')
                ->toArray();

        $vendor=[''=>'select vendor']+vendor::where('website_id',Session::get('website_id'))
                ->orderBy('vendor_name','asc')
                ->get()
                ->pluck('vendor_name','id')
                ->toArray();



    	return view("admin/bom/bom_preview")->with(['bom'=>$bom,'item'=>$bom_item,'category'=>$category,'material'=>$material,'uom'=>$uom,'gst'=>$gst,'vendor'=>$vendor]);
    }

    function bom_list(Request $request)
    {
    	$bom=new product();

    	if(isset($request->bom_name))
    	{
    		$bom=$bom->where('product_name','like','%'.$request->bom_name.'%');
    	}
    	if(isset($request->price))
    	{
    		$bom=$bom->where('item_total','like','%'.$request->price.'%');
    	}
    	$bom=$bom->where('status','bom');
    	$bom=$bom->orderBy('id','desc');
    	$bom=$bom->paginate(10);

    	return view("admin/bom/bom_list",compact('bom'));
    }

    function bom_save(Request $request)
    {
    	date_default_timezone_set('Asia/Kolkata');

    	//dd($request->all());
    	$request->validate([
            'bom_name' => 'required',
        ]);

    	$bom=new product();
    	$bom->bar_code=$request->bar_code;
    	$bom->product_name=$request->bom_name;
    	$bom->created_time=date('d-m-Y h:i:s a');
    	$bom->item_total=$request->item_total;
    	$bom->category=$request->bom_category;
        $bom->subcategory=$request->subcategory;
    	$bom->material=$request->bom_material ?? "";
    	$bom->inner_diameter=$request->bom_inner_diameter ?? "";
    	$bom->outer_diameter=$request->bom_outer_diameter ?? "";
    	$bom->thikness=$request->bom_thikness ?? "";
    	$bom->hsn=$request->bom_hsn;
    	$bom->price=$request->bom_price;
    	$bom->gst=$request->bom_gst;
    	$bom->uom=$request->bom_uom;
    	$bom->description=$request->bom_description;
        $bom->product_description=$request->product_description;
    	$bom->website_id=Session::get('website_id');
    	$bom->user_id=Session::get('user_id');
        $bom->status='bom';
        $bom->attribute1=$request->attribute1 ?? "";
        $bom->value1=$request->value1 ?? "";
        $bom->attribute2=$request->attribute2 ?? "";
        $bom->value2=$request->value2 ?? "";
        if(isset($request->show_hide))
        {
            $bom->show_hide=$request->show_hide;
        }else{
            $bom->show_hide="";
        }
        $bom->sku=$request->sku;

        if(empty($request->manufacturer)){}else{
            $manufacturername=manufacturer::find($request->manufacturer);
            $bom->manufacturer_name=$manufacturername->manufacturer_name;
            $bom->manufacturer=$request->manufacturer;
        }
        if(empty($request->brand)){}else{
            $brandname=brand::find($request->brand);
            $bom->brand_name=$brandname->brand_name;
            $bom->brand=$request->brand;
        }

        if ($request->hasFile('product_image')) {

            $image = $request->file('product_image');
            $name = time().'.'.$image->getClientOriginalExtension();
            $destinationPath = public_path('/product_image/');
            $image->move($destinationPath, $name);

            $bom->product_image=$name;
        }

        if(isset($request->show_hide))
        {
            $bom->show_hide=$request->show_hide;
        }else{
            $bom->show_hide="hide";
        }

        if(isset($request->price_show_hide))
        {
            $bom->price_show_hide=$request->price_show_hide;
        }else{
            $bom->price_show_hide="hide";
        }


    	if($bom->save())
    	{
    		$totprod=count($request->product);
    		for($i=0;$i<$totprod;$i++)
    		{
    			$bom_sub_product=new bom_sub_product();
    			$bom_sub_product->bom_id=$bom->id;
    			$bom_sub_product->product=$request->product[$i];
    			$bom_sub_product->description=$request->description[$i] ?? "";
    			$bom_sub_product->inner_daimitter=$request->inner_diamitter[$i] ?? "";
    			$bom_sub_product->outer_daimitter=$request->outer_diamitter[$i] ?? "";
    			$bom_sub_product->thikness=$request->thikness[$i] ?? "";
    			$bom_sub_product->hsn=$request->hsn[$i];
    			$bom_sub_product->uom=$request->uom[$i];
    			$bom_sub_product->qty=$request->qty[$i];
    			$bom_sub_product->price=$request->price[$i];
    			$bom_sub_product->amount=$request->amount[$i];
    			$bom_sub_product->save();
    		}
    		return redirect()->route("bom/list")->with('message','BOM Add Successfully');
    	}
    }

    function bom_add()
    {
        $category=[''=>'select category']+category::where('website_id',Session::get('website_id'))
                ->orderBy('category_name','asc')
                ->get()
                ->pluck('category_name','id')
                ->toArray();

        $material=[''=>'select material']+material::where('website_id',Session::get('website_id'))
                ->orderBy('material_name','asc')
                ->get()
                ->pluck('material_name','id')
                ->toArray();

        $gst=[''=>'select gst']+gst::where('website_id',Session::get('website_id'))
                ->orderBy('gst_per','asc')
                ->get()->pluck('gst_per','id')->toArray();


        $uom=[''=>'select uom']+uom::where('website_id',Session::get('website_id'))
                ->orderBy('uom_name','asc')
                ->get()
                ->pluck('uom_name','id')
                ->toArray();

        $vendor=[''=>'select vendor']+vendor::where('website_id',Session::get('website_id'))
                ->orderBy('vendor_name','asc')
                ->get()
                ->pluck('vendor_name','id')
                ->toArray();
                
    	$product=product::select("product.id","product.product_name","stock_status.qty as stockqty")
            ->orderBy('product.product_name','asc')
            ->where("product.status","product")
            ->leftJoin("stock_status","stock_status.product","product.id")
            ->get();

        $manufacturer=[''=>'select Manufacturer']+manufacturer::orderBy('manufacturer_name','asc')
                ->get()->pluck('manufacturer_name','id')->toArray();

        $brand=[''=>'select Brand']+brand::orderBy('brand_name','asc')
                ->get()->pluck('brand_name','id')->toArray();
                
        $attribute=attribute::orderBy('attribute_name', 'asc')->get();
        
    	return view("admin/bom/bom_add",compact('manufacturer','brand','product','category','material','gst','uom','vendor',"attribute"));
    }
}
