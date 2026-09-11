<?php

namespace App\Http\Controllers;

use App\attribute;
use App\category;
use App\city;
use App\contact;
use App\country;
use App\customers;
use App\gst;
use App\importer;
use App\industry;
use App\item_group;
use App\Library\ImageUpload;
use App\material;
use App\packer;
use App\payment_terms;
use App\product;
use App\product_attribute;
use App\product_options;
use App\ProductVariant;
use App\state;
use App\stock_book;
use App\stock_status;
use App\subcategory;
use App\type;
use App\uom;
use App\variation;
use App\vendor;
use Illuminate\Http\Request;
use Session;
use App\manufacturer;
use App\brand;
use DB;
use App\raw_material_group;
use App\product_multi_images;
use File;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function productUpload()
    {
        //   "Item_Code": 151,
        //   "Product_Name": "Body Black plain cotton",
        //   "Category": "Shocks",
        //   "Sub_Category": "Uniform Socks",
        //   "Brand": "Benzer",
        //   "Material": "P.V Cotton",
        //   "Cotton": "Black P.V Cotton",
        //   "P.P": "Nil",
        //   "Spendex": "Black-spendex",
        //   "Elastic": "Black-elastic",
        //   "Polyster": "Black",
        //   "Manufacturer": "Shree Yogi Traders",
        //   "Importer": "Shree Yogi Traders",
        //   "Packer": "Shree Yogi Traders",
        //   "Purchase_Price": "Nil",
        //   "Selling1_Price_per_piece": 28,
        //   "HSN": 6115,
        //   "Usage_Unit": "Nos",
        //   "Color": "Black",
        //   "Size": 1,
        //   "Price_per_box": 336,
        //   "Image": "BODY BLACK PLAIN COTTON",
        //   "Cover_Image": "BENZER"
         set_time_limit(0);
        $data = \File::get(public_path('product_file/products.json'));
        $records = json_decode($data);


        foreach (array_chunk($records->Sheet1, 5000) as $responseChunk)
        {


            foreach($responseChunk as $value) {


                 if(isset($value->Item_Code) || !empty($value->Item_Code)){


                 $product = new Product();
                 $product->price = $value->Price_per_box;
                 $product->item_code = $value->Item_Code;
                 $product->product_name = $value->Product_Name;
                 $product->category = 1;
                 $product->subcategory = 3;
                 $product->attribute1 = 'Colour';
                 $product->value1 = $value->Color;
                 $product->attribute2 = 'Size';
                 $product->value2 = $value->Size;
                 $product->hsn = $value->HSN;
                 $product->uom = 2;
                 $product->price = $value->Selling1_Price_per_piece;
                 $product->importer = 1;
                 $product->cotton = $value->Cotton;
                 $product->spendex = $value->Spendex;
                 $product->elastics = $value->Elastic;
                 $product->nylon = $value->Polyster;
                 $product->brand = 3;
                 $product->brand_name = 'BENZER';
                 $product->gst = 18;
                 $product->save();

                 }


            }
        }

       // dd($record);
    }
    //
    function product_img_delete(Request $request)
    {
        $images=product_multi_images::where("item_code","=",$request->item_code)->first();
        $images1=json_decode($images->product_image);

        $images1 = json_decode($images->product_image, true);
        $mimage=array();
        foreach ($images1 as $value) {
            if($value == $request->image){
                $image_path =public_path()."/product_image/$value";  // Value is not URL but directory file path
                if(File::exists($image_path)) {
                    File::delete($image_path);
                }
            }else{
                $mimage[]=$value;
            }
        }

        $images->product_image=json_encode($mimage);
        $images->save();
        return back();
    }

    function raw_group_save(Request $request)
    {
        $data=new raw_material_group();
        $data->group_name=$request->group_name;
        if($data->save())
        {
            return redirect()->route("raw_group")->with("message","raw material group save");
        }
    }
    function raw_material_add()
    {
        return view("admin.items.raw_group_add");
    }
    function raw_group()
    {
        $data=raw_material_group::paginate(10);
        return view("admin.items.raw_group_list",compact("data"));
    }
    function  rawmaterial_list(Request $request)
    {
        $product=DB::table('product');
        $product=$product->select('product.*','gst.gst_per','uom.uom_name','category.category_name as catname','material.material_name as matname');
        $product=$product->leftJoin('gst','gst.id','product.gst');
        $product=$product->leftJoin('uom','uom.id','product.uom');
        $product=$product->leftJoin('category','category.id','product.category');
        $product=$product->leftJoin('material','material.id','product.material');
        $product=$product->where('product.status','raw material');
        $product=$product;


        if(isset($request->product_name))
        {
            $product=$product->where('product.product_name','like','%'.$request->product_name.'%');
        }
        if(isset($request->item_code))
        {
            $product=$product->where('product.item_code','like','%'.$request->item_code.'%');
        }
        if(isset($request->raw_material_group))
        {
            $product=$product->where('product.raw_material_group','like','%'.$request->raw_material_group.'%');
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
        $product=$product->paginate(session('records_per_page', 30));


        // dd($product);
        return view("admin/raw_material_list")->with(['data'=>$product]);
    }

    function product_option_delete(Request $request)
    {
        $data=product_options::find($request->optionid);
        if($data->delete())
        {
            return "success";
        }else{
            return "error";
        }
    }

    function attribute_delete(Request $request)
    {
        $data=product_attribute::find($request->attribute_id);
        if($data->delete())
        {
            return "success";
        }else{
            return "error";
        }
    }
    function group_update(Request $request)
    {
        date_default_timezone_set("Asia/Kolkata");
        $item_group=item_group::find($request->id);
        $item_group->group_name=$request->group_name;
        $item_group->category=$request->category;
        $item_group->subcategory=$request->subcategory;
        $item_group->product_url=$request->product_url;
        $item_group->description=$request->description;
        $item_group->product_description=$request->product_description;
        $item_group->created_time=date('d-m-Y h:i:s');

        $item_group->hsn=$request->hsn;
        $item_group->gst=$request->gst;
        $item_group->uom=$request->uom;
        $item_group->group_price=$request->group_price;

        if(empty($request->material)){}else{
            $materialname=material::find($request->material);
            $material_name=$materialname->material_name;
            $item_group->material_name=$material_name;
            $item_group->material=$request->material;
        }

        if(empty($request->manufacturer)){}else{
            $manufacturername=manufacturer::find($request->manufacturer);
            $item_group->manufacturer_name=$manufacturername->manufacturer_name;
            $item_group->manufacturer=$request->manufacturer;
        }
        if(empty($request->brand)){}else{
            $brandname=brand::find($request->brand);
            $item_group->brand_name=$brandname->brand_name;
            $item_group->brand=$request->brand;
        }

        if(empty($request->primary_image))
        {}else{
            $name = rand().'_'.$request->primary_image->getClientOriginalName();

            $destinationPath = public_path('/product_image/');
            $request->primary_image->move($destinationPath, $name);
            $item_group->primary_image=$name;
        }

        //dd($request->all());

        if($item_group->save())
        {
            $d=product_attribute::where("group_id",$item_group->id)
                ->delete();

            $tot_product_attribute=count($request->product_attribute);
            for($a=0;$a<$tot_product_attribute;$a++)
            {
                if(empty($request->product_attribute[$a]))
                {}else{
                    $option=new product_attribute();
                    $attname=attribute::where('id',$request->product_attribute[$a])->first();
                    $option->attribute_id=$request->product_attribute[$a];
                    $option->attribute_name=$attname->attribute_name ?? "";
                    $option->group_id=$item_group->id;
                    $option->save();

                }
            }

            if(empty($request->old_product_name))
            {}else{
                $totproduct=count($request->old_product_name);
                for($i=0;$i<$totproduct;$i++)
                {
                    $product=product::where("group_id",$item_group->id)
                        ->where("id",$request->old_product_id[$i])
                        ->first();

                    $product->created_time=date('Y-m-d h:i:s');
                    $product->user_id=Session::get("user_id");
                    $product->website_id=Session::get("website_id");
                    $product->group_id=$item_group->id;
                    $product->product_name=$request->old_product_name[$i];
                    $product->sku=$request->old_sku[$i];
                    $product->price=$request->old_selling_price[$i];
                    $product->purchase_price=$request->old_purchase_price[$i];
                    $product->hsn=$request->hsn;
                    $product->gst=$request->gst;
                    $product->uom=$request->uom;
                    $product->status="product";
                    $product->description=$request->description;
                    $product->product_url=$request->product_url;
                    $product->product_description=$request->product_description;
                    if(empty($request->category)){}else{
                        $categoryname=category::find($request->category);
                        $category_name=$categoryname->category_name;
                        $product->category_name=$category_name;
                        $product->category=$request->category;
                    }
                    if(empty($request->subcategory)){}else{
                        $subcategory=subcategory::find($request->subcategory);
                        $subcategory_name=$subcategory->subcategory_name;
                        $product->subcategory_name=$subcategory_name;
                        $product->subcategory=$request->subcategory;
                    }

                    if(empty($request->material)){}else{
                        $materialname=material::find($request->material);
                        $material_name=$materialname->material_name;
                        $product->material_name=$material_name;
                        $product->material=$request->material;
                    }

                    if(empty($request->manufacturer)){}else{
                        $manufacturername=manufacturer::find($request->manufacturer);
                        $product->manufacturer_name=$manufacturername->manufacturer_name;
                        $product->manufacturer=$request->manufacturer;
                    }
                    if(empty($request->brand)){}else{
                        $brandname=brand::find($request->brand);
                        $product->brand_name=$brandname->brand_name;
                        $product->brand=$request->brand;
                    }

                    if(empty($request->old_images2[$i]))
                    {}else{
                        $name = $i.'_'.$request->old_images2[$i]->getClientOriginalName();

                        $destinationPath = public_path('/product_image/');
                        $request->old_images2[$i]->move($destinationPath, $name);
                        $product->product_image=$name;
                    }

                    if(empty($request->old_attribute1[$i])){}else{
                        $product->attribute1=$request->old_attribute1[$i];
                        $product->value1=$request->old_value1[$i];
                    }
                    if(empty($request->old_attribute2[$i])){}else{
                        $product->attribute2=$request->old_attribute2[$i];
                        $product->value2=$request->old_value2[$i];
                    }
                    if(empty($request->old_attribute3[$i])){}else{
                        $product->attribute3=$request->old_attribute3[$i];
                        $product->value3=$request->old_value3[$i];
                    }
                    if(empty($request->old_attribute4[$i])){}else{
                        $product->attribute4=$request->old_attribute4[$i];
                        $product->value4=$request->old_value4[$i];
                    }
                    if(empty($request->old_attribute5[$i])){}else{
                        $product->attribute5=$request->old_attribute5[$i];
                        $product->value5=$request->old_value5[$i];
                    }
                    if(empty($request->old_attribute6[$i])){}else{
                        $product->attribute6=$request->old_attribute6[$i];
                        $product->value6=$request->old_value6[$i];
                    }

                    if($product->save()){
                        $j=0;
                        $attribute=product_options::where("group_id",$item_group->id)
                            ->where("product",$request->old_product_id[$i])
                            ->first();

                        //$attribute=new product_options();
                        $attribute->group_id=$item_group->id;
                        $attribute->product=$product->id;

                        if(empty($request->old_attribute1[$i]))
                        {}else{
                            echo $request->old_attribute1[$i];
                        }
                        if(empty($request->old_attribute1[$i])){}else{
                            $attribute->attribute1=$request->old_attribute1[$i];
                            $attribute->value1=$request->old_value1[$i];
                        }
                        if(empty($request->old_attribute2[$i])){}else{
                            $attribute->attribute2=$request->old_attribute2[$i];
                            $attribute->value2=$request->old_value2[$i];
                        }
                        if(empty($request->old_attribute3[$i])){}else{
                            $attribute->attribute3=$request->old_attribute3[$i];
                            $attribute->value3=$request->old_value3[$i];
                        }
                        if(empty($request->old_attribute4[$i])){}else{
                            $attribute->attribute4=$request->old_attribute4[$i];
                            $attribute->value4=$request->old_value4[$i];
                        }
                        if(empty($request->old_attribute5[$i])){}else{
                            $attribute->attribute5=$request->old_attribute5[$i];
                            $attribute->value5=$request->old_value5[$i];
                        }
                        if(empty($request->old_attribute6[$i])){}else{
                            $attribute->attribute6=$request->old_attribute6[$i];
                            $attribute->value6=$request->old_value6[$i];
                        }
                        $attribute->save();
                    }


                }
                // dd($i);
            }

            if(empty($request->product_name))
            {}else{
                $totproduct=count($request->product_name);
                for($i=0;$i<$totproduct;$i++)
                {
                    $product=new product();
                    $product->created_time=date('Y-m-d h:i:s');
                    $product->user_id=Session::get("user_id");
                    $product->website_id=Session::get("website_id");
                    $product->group_id=$item_group->id;
                    $product->product_name=$request->product_name[$i];
                    $product->sku=$request->sku[$i];
                    $product->price=$request->selling_price[$i];
                    $product->purchase_price=$request->purchase_price[$i];
                    $product->hsn=$request->hsn;
                    $product->gst=$request->gst;
                    $product->uom=$request->uom;
                    $product->status="product";
                    $product->description=$request->description;
                    $product->product_url=$request->product_url;
                    $product->product_description=$request->product_description;
                    if(empty($request->category)){}else{
                        $categoryname=category::find($request->category);
                        $category_name=$categoryname->category_name;
                        $product->category_name=$category_name;
                        $product->category=$request->category;
                    }
                    if(empty($request->subcategory)){}else{
                        $subcategory=subcategory::find($request->subcategory);
                        $subcategory_name=$subcategory->subcategory_name;
                        $product->subcategory_name=$subcategory_name;
                        $product->subcategory=$request->subcategory;
                    }

                    if(empty($request->material)){}else{
                        $materialname=material::find($request->material);
                        $material_name=$materialname->material_name;
                        $product->material_name=$material_name;
                        $product->material=$request->material;
                    }

                    if(empty($request->manufacturer)){}else{
                        $manufacturername=manufacturer::find($request->manufacturer);
                        $product->manufacturer_name=$manufacturername->manufacturer_name;
                        $product->manufacturer=$request->manufacturer;
                    }
                    if(empty($request->brand)){}else{
                        $brandname=brand::find($request->brand);
                        $product->brand_name=$brandname->brand_name;
                        $product->brand=$request->brand;
                    }

                    if(empty($request->images2[$i]))
                    {}else{
                        $name = $i.'_'.$request->images2[$i]->getClientOriginalName();

                        $destinationPath = public_path('/product_image/');
                        $request->images2[$i]->move($destinationPath, $name);
                        $product->product_image=$name;
                    }

                    if(empty($request->attribute1[$i])){}else{
                        $product->attribute1=$request->attribute1[$i];
                        $product->value1=$request->value1[$i];
                    }
                    if(empty($request->attribute2[$i])){}else{
                        $product->attribute2=$request->attribute2[$i];
                        $product->value2=$request->value2[$i];
                    }
                    if(empty($request->attribute3[$i])){}else{
                        $product->attribute3=$request->attribute3[$i];
                        $product->value3=$request->value3[$i];
                    }
                    if(empty($request->attribute4[$i])){}else{
                        $product->attribute4=$request->attribute4[$i];
                        $product->value4=$request->value4[$i];
                    }
                    if(empty($request->attribute5[$i])){}else{
                        $product->attribute5=$request->attribute5[$i];
                        $product->value5=$request->value5[$i];
                    }
                    if(empty($request->attribute6[$i])){}else{
                        $product->attribute6=$request->attribute6[$i];
                        $product->value6=$request->value6[$i];
                    }

                    if($product->save()){
                        $j=0;
                        $attribute=new product_options();
                        $attribute->group_id=$item_group->id;
                        $attribute->product=$product->id;

                        if(empty($request->attribute1[$i]))
                        {}else{
                            echo $request->attribute1[$i];
                        }
                        if(empty($request->attribute1[$i])){}else{
                            $attribute->attribute1=$request->attribute1[$i];
                            $attribute->value1=$request->value1[$i];
                        }
                        if(empty($request->attribute2[$i])){}else{
                            $attribute->attribute2=$request->attribute2[$i];
                            $attribute->value2=$request->value2[$i];
                        }
                        if(empty($request->attribute3[$i])){}else{
                            $attribute->attribute3=$request->attribute3[$i];
                            $attribute->value3=$request->value3[$i];
                        }
                        if(empty($request->attribute4[$i])){}else{
                            $attribute->attribute4=$request->attribute4[$i];
                            $attribute->value4=$request->value4[$i];
                        }
                        if(empty($request->attribute5[$i])){}else{
                            $attribute->attribute5=$request->attribute5[$i];
                            $attribute->value5=$request->value5[$i];
                        }
                        if(empty($request->attribute6[$i])){}else{
                            $attribute->attribute6=$request->attribute6[$i];
                            $attribute->value6=$request->value6[$i];
                        }
                        $attribute->save();
                    }


                }
                // dd($i);
            }
        }
        return redirect()->route("item_group_list")
            ->with("message","Group Update successfully");
    }
    function group_edit(Request $request)
    {
        $category=[''=>'select category']+category::query()
                ->orderBy('category_name','asc')
                ->get()
                ->pluck('category_name','id')
                ->toArray();

        $subcategory=[''=>'select subcategory']+subcategory::orderBy('subcategory_name','asc')
                ->get()
                ->pluck('subcategory_name','id')
                ->toArray();

        $material=[''=>'select material']+material::query()
                ->orderBy('material_name','asc')
                ->get()
                ->pluck('material_name','id')
                ->toArray();

        $gst=[''=>'select gst']+gst::query()
                ->orderBy('gst_per','asc')
                ->get()->pluck('gst_per','id')->toArray();


        $uom=[''=>'select uom']+uom::query()
                ->orderBy('uom_name','asc')
                ->get()
                ->pluck('uom_name','id')
                ->toArray();

        $vendor=[''=>'select vendor']+vendor::query()
                ->orderBy('vendor_name','asc')
                ->get()
                ->pluck('vendor_name','id')
                ->toArray();

        $country=[''=>'select country']+country::orderBy('country_name','asc')
                ->get()->pluck('country_name','id')->toArray();

        $state=[''=>'select state']+state::orderBy('state_name','asc')
                ->get()->pluck('state_name','id')->toArray();

        $city=[''=>'select city']+city::orderBy('city_name','asc')
                ->get()->pluck('city_name','id')->toArray();

        $manufacturer=[''=>'select Manufacturer']+manufacturer::orderBy('manufacturer_name','asc')
                ->get()->pluck('manufacturer_name','id')->toArray();

        $brand=[''=>'select Brand']+brand::orderBy('brand_name','asc')
                ->get()->pluck('brand_name','id')->toArray();

        $attribute=attribute::orderBy('attribute_name', 'asc')->get();

        $data=item_group::find($request->id);

        $product_attribute=product_attribute::where("group_id",$request->id)->get();

        $product_options=product_options::select("product_options.*","product.id as pid","product.product_name", "product.value1", "product.value2","product.price","product.purchase_price","product.sku","product.product_image")
            ->leftJoin("product","product.id","product_options.product")
            ->where("product_options.group_id",$request->id)
            ->get();
        // dd($product_options);

        $variation=variation::select("variation.*","attribute.attribute_name")
            ->leftJoin("attribute","attribute.id","variation.attribute")
            ->get();
        return view("admin/items/group_edit")
            ->with(['subcategory'=>$subcategory,'variation'=>$variation,'product_options'=>$product_options,'product_attribute'=>$product_attribute,'data'=>$data,'attribute'=>$attribute,'brand'=>$brand,'manufacturer'=>$manufacturer,'category'=>$category,'uom'=>$uom,'gst'=>$gst,'vendor'=>$vendor,'country'=>$country,'state'=>$state,'city'=>$city,'material'=>$material]);

    }

    function item_group_list(Request $request)
    {
        $group=item_group::orderBy("id","desc")->paginate(session('records_per_page', 30));
        return view("admin.items.group_list")->with(['data'=>$group]);
    }

    function group_save(Request $request)
    {
        //  dd($request->all());
        date_default_timezone_set("Asia/Kolkata");
        $item_group=new item_group();
        $item_group->group_name=$request->group_name;
        $item_group->category=$request->category;
        $item_group->subcategory=$request->subcategory;
        $item_group->product_url=$request->product_url;
        $item_group->description=$request->description;
        $item_group->product_description=$request->product_description;
        $item_group->created_time=date('d-m-Y h:i:s');

        $item_group->hsn=$request->hsn;
        $item_group->gst=$request->gst;
        $item_group->uom=$request->uom;
        $item_group->group_price=$request->group_price;

        if(empty($request->material)){}else{
            $materialname=material::find($request->material);
            $material_name=$materialname->material_name;
            $item_group->material_name=$material_name;
            $item_group->material=$request->material;
        }

        if(empty($request->manufacturer)){}else{
            $manufacturername=manufacturer::find($request->manufacturer);
            $item_group->manufacturer_name=$manufacturername->manufacturer_name;
            $item_group->manufacturer=$request->manufacturer;
        }
        if(empty($request->brand)){}else{
            $brandname=brand::find($request->brand);
            $item_group->brand_name=$brandname->brand_name;
            $item_group->brand=$request->brand;
        }

        if(empty($request->primary_image))
        {}else{
            $name = rand().'_'.$request->primary_image->getClientOriginalName();

            $destinationPath = public_path('/product_image/');
            $request->primary_image->move($destinationPath, $name);
            $item_group->primary_image=$name;
        }

        //dd($request->all());

        if($item_group->save())
        {
            $tot_product_attribute=count($request->product_attribute);
            for($a=0;$a<$tot_product_attribute;$a++)
            {
                if(empty($request->product_attribute[$a]))
                {}else{
                    $option=new product_attribute();
                    $attname=attribute::where('id',$request->product_attribute[$a])->first();
                    $option->attribute_id=$request->product_attribute[$a];
                    $option->attribute_name=$attname->attribute_name ?? "";
                    $option->group_id=$item_group->id;
                    $option->save();

                }
            }

            if(empty($request->product_name))
            {}else{
                $totproduct=count($request->product_name);
                for($i=0;$i<$totproduct;$i++)
                {
                    $product=new product();
                    $product->created_time=date('Y-m-d h:i:s');
                    $product->user_id=Session::get("user_id");
                    $product->website_id=Session::get("website_id");
                    $product->group_id=$item_group->id;
                    $product->product_name=$request->product_name[$i];
                    $product->sku=$request->sku[$i];
                    $product->price=$request->selling_price[$i];
                    $product->purchase_price=$request->purchase_price[$i];
                    $product->hsn=$request->hsn;
                    $product->gst=$request->gst;
                    $product->uom=$request->uom;
                    $product->status="product";
                    $product->description=$request->description;
                    $product->product_url=$request->product_url;
                    $product->product_description=$request->product_description;
                    if(empty($request->category)){}else{
                        $categoryname=category::find($request->category);
                        $category_name=$categoryname->category_name;
                        $product->category_name=$category_name;
                        $product->category=$request->category;
                    }
                    if(empty($request->subcategory)){}else{
                        $subcategory=subcategory::find($request->subcategory);
                        $subcategory_name=$subcategory->subcategory_name;
                        $product->subcategory_name=$subcategory_name;
                        $product->subcategory=$request->subcategory;
                    }

                    if(empty($request->material)){}else{
                        $materialname=material::find($request->material);
                        $material_name=$materialname->material_name;
                        $product->material_name=$material_name;
                        $product->material=$request->material;
                    }

                    if(empty($request->manufacturer)){}else{
                        $manufacturername=manufacturer::find($request->manufacturer);
                        $product->manufacturer_name=$manufacturername->manufacturer_name;
                        $product->manufacturer=$request->manufacturer;
                    }
                    if(empty($request->brand)){}else{
                        $brandname=brand::find($request->brand);
                        $product->brand_name=$brandname->brand_name;
                        $product->brand=$request->brand;
                    }

                    if(empty($request->images2[$i]))
                    {}else{
                        $name = $i.'_'.$request->images2[$i]->getClientOriginalName();

                        $destinationPath = public_path('/product_image/');
                        $request->images2[$i]->move($destinationPath, $name);
                        $product->product_image=$name;
                    }

                    if(empty($request->attribute1[$i])){}else{
                        $product->attribute1=$request->attribute1[$i];
                        $product->value1=$request->value1[$i];
                    }
                    if(empty($request->attribute2[$i])){}else{
                        $product->attribute2=$request->attribute2[$i];
                        $product->value2=$request->value2[$i];
                    }
                    if(empty($request->attribute3[$i])){}else{
                        $product->attribute3=$request->attribute3[$i];
                        $product->value3=$request->value3[$i];
                    }
                    if(empty($request->attribute4[$i])){}else{
                        $product->attribute4=$request->attribute4[$i];
                        $product->value4=$request->value4[$i];
                    }
                    if(empty($request->attribute5[$i])){}else{
                        $product->attribute5=$request->attribute5[$i];
                        $product->value5=$request->value5[$i];
                    }
                    if(empty($request->attribute6[$i])){}else{
                        $product->attribute6=$request->attribute6[$i];
                        $product->value6=$request->value6[$i];
                    }

                    if($product->save())
                    {
                        $j=0;

                        $attribute=new product_options();
                        $attribute->group_id=$item_group->id;
                        $attribute->product=$product->id;

                        if(empty($request->attribute1[$i]))
                        {}else{
                            echo $request->attribute1[$i];
                        }


                        if(empty($request->attribute1[$i])){}else{
                            $attribute->attribute1=$request->attribute1[$i];
                            $attribute->value1=$request->value1[$i];
                        }
                        if(empty($request->attribute2[$i])){}else{
                            $attribute->attribute2=$request->attribute2[$i];
                            $attribute->value2=$request->value2[$i];
                        }
                        if(empty($request->attribute3[$i])){}else{
                            $attribute->attribute3=$request->attribute3[$i];
                            $attribute->value3=$request->value3[$i];
                        }
                        if(empty($request->attribute4[$i])){}else{
                            $attribute->attribute4=$request->attribute4[$i];
                            $attribute->value4=$request->value4[$i];
                        }
                        if(empty($request->attribute5[$i])){}else{
                            $attribute->attribute5=$request->attribute5[$i];
                            $attribute->value5=$request->value5[$i];
                        }
                        if(empty($request->attribute6[$i])){}else{
                            $attribute->attribute6=$request->attribute6[$i];
                            $attribute->value6=$request->value6[$i];
                        }

//                            if(empty($request->images2[$i]))
//                            {}else{
//                               echo $name = $i.'_'.$request->images2[$i]->getClientOriginalName();
//                               echo "<br>";
//                                $destinationPath = public_path('/product_image/');
//                                $request->images2[$i]->move($destinationPath, $name);
//                                $attribute->image1=$name;
//                            }

                        $attribute->save();


                    }


                }
                // dd($i);
            }

        }

        return redirect()->route("item_group_list")
            ->with("message","Group create successfully");
    }
    function brand_delete(Request $request)
    {
        $b=brand::find($request->id);
        if($b->delete())
        {
            return redirect()->route("admin.brand.list")->with("message","Brand Delete success");
        }
    }
    function brand_update(Request $request)
    {
        $request->validate([
            'brand_name' => 'required',
        ]);
        $b=brand::find($request->id);
        $b->brand_name=$request->brand_name;
        if($b->save())
        {
            return redirect()->route("admin.brand.list")->with("message","Brand Update success");
        }
    }
    function brand_edit(Request $request)
    {
        $m=brand::find($request->id);
        return view("admin/items/brand_edit")->with(['data'=>$m]);
    }

    /** v2 sample migration only — identical to brand_edit() above, different view. */
    function brand_edit_v2(Request $request)
    {
        $m=brand::find($request->id);
        return view("admin.items.brand_edit_v2")->with(['data'=>$m]);
    }
    function brand_list(Request $request)
    {
        $data=brand::orderBy("id","desc")->paginate(session('records_per_page', 30));
        return view("admin/items/brand_list")
            ->with(['data'=>$data]);
    }

    /** v2 sample migration only — identical query to brand_list() above, different view. */
    function brand_list_v2(Request $request)
    {
        $data=brand::orderBy("id","desc")->paginate(session('records_per_page', 30));
        return view("admin.items.brand_list_v2")
            ->with(['data'=>$data]);
    }
    function brand_save(Request $request)
    {
        $request->validate([
            'brand_name' => 'required',
        ]);
        $b=new brand();
        $b->brand_name=$request->brand_name;
        if($b->save())
        {
            return redirect()->route("admin.brand.list")->with("message","Brand Save success");
        }
    }
    function manufacturer_delete(Request $request)
    {
        $m=manufacturer::find($request->id);
        if($m->delete())
        {
            return redirect()->route("manufacturer")->with("message","Manufacturer Delete success");
        }
    }
    function manufactured_update(Request $request)
    {
        $save=manufacturer::find($request->id);
        $request->validate([
            'manufacturer_name' => 'required',
        ]);

        date_default_timezone_set('Asia/Kolkata');


        $request->validate([
            'manufacturer_name' => 'required|max:255',
            'primary_email'=>'email:rfc,dns',
            'owner_name' => 'required|max:255',
            'owner_mobile' => 'required|max:255',
            'owner_email' =>'email:rfc,dns',
            'billing_address' => 'required',
            'shipping_address' => 'required',
            'billing_city' => 'required',
            'shipping_city' => 'required',
            'billing_postalcode' => 'required',
            'shipping_postalcode' => 'required',
            'shipping_country' => 'required',
            'work_email'=>'required',
            'primary_phone'=>'required',
            'alternate_no'=>'required',
            'billing_country'=>'required',
            'billing_state'=>'required',
            'shipping_state'=>'required',
            'department'=>'required',
            'designation'=>'required',
        ]);
        // / dd($request->all());
        $save->manufacturer_name=$request->manufacturer_name;
        $save->website=$request->website;
        $save->primary_phone=$request->primary_phone;
        $save->secondary_phone=$request->secondary_phone;
        $save->primary_email=$request->primary_email;
        $save->secondary_email=$request->secondary_email;
        $save->owner_name=$request->owner_name;
        $save->owner_mobile=$request->owner_mobile;
        $save->owner_email=$request->owner_email;
        $save->work_email=$request->work_email;
        $save->alternate_no=$request->alternate_no;
        $save->owner_gst=$request->owner_gst;
        $save->owner_pan=$request->owner_pan;
        $save->billing_address=$request->billing_address;
        $save->shipping_address=$request->shipping_address;
        $save->billing_pobox=$request->billing_pobox;
        $save->shipping_pobox=$request->shipping_pobox;
        $save->billing_city=$request->billing_city;
        $save->shipping_city=$request->shipping_city;
        $save->billing_state=$request->billing_state;
        $save->shipping_state=$request->shipping_state;
        $save->billing_postalcode=$request->billing_postalcode;
        $save->shipping_postalcode=$request->shipping_postalcode;
        $save->billing_country=$request->billing_country;
        $save->shipping_country=$request->shipping_country;
        $save->description=$request->description;
        $save->created_time=date('Y-m-d h:i:s A');
        $save->user_id=Session::get('user_id');
        $save->website_id=Session::get('website_id');
        $save->industry=$request->industry;
        $save->type=$request->type;
        $save->department=$request->department;
        $save->designation=$request->designation;
        $save->tax_preference=$request->tax_preference;
        $save->payment_terms=$request->payment_terms;
        if($save->save())
        {

            \LogActivity::addToLog($request->manufacturer_name.' Manufacturer Create ');

            return redirect()->route("manufacturer")->with("message","Manufacturer save success");
        }else{
            return back()->with('message','error in save');
        }
    }

    function packer_delete(Request $request)
    {
        $save=packer::find($request->id);
        \LogActivity::addToLog($save->manufacturer_name.' Packer Delete');
        if($save->delete())
        {
            return redirect()->route("packer")->with("message","Packer Delete success");

        }
    }

    function importer_delete(Request $request)
    {
        $save=importer::find($request->id);
        \LogActivity::addToLog($save->manufacturer_name.' Importer Delete');
        if($save->delete())
        {
            return redirect()->route("importer")->with("message","Importer Delete success");

        }
    }
    function importer_update(Request $request)
    {
        $save=importer::find($request->id);
        $request->validate([
            'manufacturer_name' => 'required',
        ]);

        date_default_timezone_set('Asia/Kolkata');


        $request->validate([
            'manufacturer_name' => 'required|max:255',
            'primary_email'=>'email:rfc,dns',
            'owner_name' => 'required|max:255',
            'owner_mobile' => 'required|max:255',
            'owner_email' =>'email:rfc,dns',
            'billing_address' => 'required',
            'shipping_address' => 'required',
            'billing_city' => 'required',
            'shipping_city' => 'required',
            'billing_postalcode' => 'required',
            'shipping_postalcode' => 'required',
            'shipping_country' => 'required',
            'work_email'=>'required',
            'primary_phone'=>'required',
            'alternate_no'=>'required',
            'billing_country'=>'required',
            'billing_state'=>'required',
            'shipping_state'=>'required',
            'department'=>'required',
            'designation'=>'required',
        ]);
        // / dd($request->all());
        $save->manufacturer_name=$request->manufacturer_name;
        $save->website=$request->website;
        $save->primary_phone=$request->primary_phone;
        $save->secondary_phone=$request->secondary_phone;
        $save->primary_email=$request->primary_email;
        $save->secondary_email=$request->secondary_email;
        $save->owner_name=$request->owner_name;
        $save->owner_mobile=$request->owner_mobile;
        $save->owner_email=$request->owner_email;
        $save->work_email=$request->work_email;
        $save->alternate_no=$request->alternate_no;
        $save->owner_gst=$request->owner_gst;
        $save->owner_pan=$request->owner_pan;
        $save->billing_address=$request->billing_address;
        $save->shipping_address=$request->shipping_address;
        $save->billing_pobox=$request->billing_pobox;
        $save->shipping_pobox=$request->shipping_pobox;
        $save->billing_city=$request->billing_city;
        $save->shipping_city=$request->shipping_city;
        $save->billing_state=$request->billing_state;
        $save->shipping_state=$request->shipping_state;
        $save->billing_postalcode=$request->billing_postalcode;
        $save->shipping_postalcode=$request->shipping_postalcode;
        $save->billing_country=$request->billing_country;
        $save->shipping_country=$request->shipping_country;
        $save->description=$request->description;
        $save->created_time=date('Y-m-d h:i:s A');
        $save->user_id=Session::get('user_id');
        $save->website_id=Session::get('website_id');
        $save->industry=$request->industry;
        $save->type=$request->type;
        $save->department=$request->department;
        $save->designation=$request->designation;
        $save->tax_preference=$request->tax_preference;
        $save->payment_terms=$request->payment_terms;
        if($save->save())
        {

            \LogActivity::addToLog($request->manufacturer_name.' Importer Create ');

            return redirect()->route("importer")->with("message","Importer save success");
        }else{
            return back()->with('message','error in save');
        }
    }

    function manufacturer_edit(Request $request)
    {
        $data=manufacturer::find($request->id);
        $industry=[''=>'select industry']+industry::query()
                ->orderBy('industry_name')->get()->pluck('industry_name','id')->toArray();
        $type=[''=>'select type']+type::query()
                ->orderBy('type_name')->get()->pluck('type_name','id')->toArray();

        $country=[''=>'select country']+country::orderBy('country_name','asc')
                ->get()->pluck('country_name','id')->toArray();

        $state=[''=>'select state']+state::orderBy('state_name','asc')
                ->get()->pluck('state_name','id')->toArray();

        $city=[''=>'select city']+city::orderBy('city_name','asc')
                ->get()->pluck('city_name','id')->toArray();

        $payment_terms=[''=>'select']+payment_terms::orderBy("id","desc")
                ->get()->pluck("terms_name","days")->toArray();
        return view("admin/items/manufacturer_edit")->with(['data'=>$data,'payment_terms'=>$payment_terms,'industry'=>$industry,'type'=>$type,'country'=>$country,'state'=>$state,'city'=>$city]);

//        return view("admin/items/manufacturer_edit")->with(['data'=>$data]);
    }
    function importer_edit(Request $request)
    {
        $data=importer::find($request->id);
        $industry=[''=>'select industry']+industry::query()
                ->orderBy('industry_name')->get()->pluck('industry_name','id')->toArray();
        $type=[''=>'select type']+type::query()
                ->orderBy('type_name')->get()->pluck('type_name','id')->toArray();

        $country=[''=>'select country']+country::orderBy('country_name','asc')
                ->get()->pluck('country_name','id')->toArray();

        $state=[''=>'select state']+state::orderBy('state_name','asc')
                ->get()->pluck('state_name','id')->toArray();

        $city=[''=>'select city']+city::orderBy('city_name','asc')
                ->get()->pluck('city_name','id')->toArray();

        $payment_terms=[''=>'select']+payment_terms::orderBy("id","desc")
                ->get()->pluck("terms_name","days")->toArray();
        return view("admin/items/importer_edit")->with(['data'=>$data,'payment_terms'=>$payment_terms,'industry'=>$industry,'type'=>$type,'country'=>$country,'state'=>$state,'city'=>$city]);

//        return view("admin/items/manufacturer_edit")->with(['data'=>$data]);
    }

    function packer_edit(Request $request)
    {
        $data=packer::find($request->id);
        $industry=[''=>'select industry']+industry::query()
                ->orderBy('industry_name')->get()->pluck('industry_name','id')->toArray();
        $type=[''=>'select type']+type::query()
                ->orderBy('type_name')->get()->pluck('type_name','id')->toArray();

        $country=[''=>'select country']+country::orderBy('country_name','asc')
                ->get()->pluck('country_name','id')->toArray();

        $state=[''=>'select state']+state::orderBy('state_name','asc')
                ->get()->pluck('state_name','id')->toArray();

        $city=[''=>'select city']+city::orderBy('city_name','asc')
                ->get()->pluck('city_name','id')->toArray();

        $payment_terms=[''=>'select']+payment_terms::orderBy("id","desc")
                ->get()->pluck("terms_name","days")->toArray();
        return view("admin/items/packer_edit")
            ->with(['data'=>$data,'payment_terms'=>$payment_terms,'industry'=>$industry,'type'=>$type,'country'=>$country,'state'=>$state,'city'=>$city]);

//        return view("admin/items/manufacturer_edit")->with(['data'=>$data]);
    }

    function manufacturer_list(Request $request)
    {
        $data=manufacturer::orderBy("id","desc")->paginate(session('records_per_page', 30));
        return view("admin/items/manufacturer_list")
            ->with(['data'=>$data]);
    }

    function packer_list(Request $request)
    {
        $data=packer::orderBy("id","desc")->paginate(session('records_per_page', 30));
        return view("admin/items/packer_list")
            ->with(['data'=>$data]);
    }

    function importer_list(Request $request)
    {
        $data=importer::orderBy("id","desc")->paginate(session('records_per_page', 30));
        return view("admin/items/importer_list")
            ->with(['data'=>$data]);
    }


    function importer_save(Request $request)
    {
        $request->validate([
            'manufacturer_name' => 'required',
        ]);

        date_default_timezone_set('Asia/Kolkata');
        $save=new importer();


        $request->validate([
            'manufacturer_name' => 'required|max:255|unique:importer',
            'primary_email'=>'email:rfc,dns',
            'owner_name' => 'required|max:255',
            'owner_mobile' => 'required|max:255',
            'owner_email' =>'email:rfc,dns',
            'billing_address' => 'required',
            'shipping_address' => 'required',
            'billing_city' => 'required',
            'shipping_city' => 'required',
            'billing_postalcode' => 'required',
            'shipping_postalcode' => 'required',
            'shipping_country' => 'required',
            'work_email'=>'required',
            'primary_phone'=>'required',
            'alternate_no'=>'required',
            'billing_country'=>'required',
            'billing_state'=>'required',
            'shipping_state'=>'required',
            'department'=>'required',
            'designation'=>'required',
        ]);
        // / dd($request->all());
        $save->manufacturer_name=$request->manufacturer_name;
        $save->website=$request->website;
        $save->primary_phone=$request->primary_phone;
        $save->secondary_phone=$request->secondary_phone;
        $save->primary_email=$request->primary_email;
        $save->secondary_email=$request->secondary_email;
        $save->owner_name=$request->owner_name;
        $save->owner_mobile=$request->owner_mobile;
        $save->owner_email=$request->owner_email;
        $save->work_email=$request->work_email;
        $save->alternate_no=$request->alternate_no;
        $save->owner_gst=$request->owner_gst;
        $save->owner_pan=$request->owner_pan;
        $save->billing_address=$request->billing_address;
        $save->shipping_address=$request->shipping_address;
        $save->billing_pobox=$request->billing_pobox;
        $save->shipping_pobox=$request->shipping_pobox;
        $save->billing_city=$request->billing_city;
        $save->shipping_city=$request->shipping_city;
        $save->billing_state=$request->billing_state;
        $save->shipping_state=$request->shipping_state;
        $save->billing_postalcode=$request->billing_postalcode;
        $save->shipping_postalcode=$request->shipping_postalcode;
        $save->billing_country=$request->billing_country;
        $save->shipping_country=$request->shipping_country;
        $save->description=$request->description;
        $save->created_time=date('Y-m-d h:i:s A');
        $save->user_id=Session::get('user_id');
        $save->website_id=Session::get('website_id');
        $save->industry=$request->industry;
        $save->type=$request->type;
        $save->department=$request->department;
        $save->designation=$request->designation;
        $save->tax_preference=$request->tax_preference;
        $save->payment_terms=$request->payment_terms;
        if($save->save())
        {

            \LogActivity::addToLog($request->manufacturer_name.' Importer Create ');

            return redirect()->route("importer")->with("message","Importer save success");
        }else{
            return back()->with('message','error in save');
        }

    }

    function manufactured_save(Request $request)
    {
        $request->validate([
            'manufacturer_name' => 'required',
        ]);

        date_default_timezone_set('Asia/Kolkata');
        $save=new manufacturer();


        $request->validate([
            'manufacturer_name' => 'required|max:255|unique:manufacturer',
            'primary_email'=>'email:rfc,dns',
            'owner_name' => 'required|max:255',
            'owner_mobile' => 'required|max:255',
            'owner_email' =>'email:rfc,dns',
            'billing_address' => 'required',
            'shipping_address' => 'required',
            'billing_city' => 'required',
            'shipping_city' => 'required',
            'billing_postalcode' => 'required',
            'shipping_postalcode' => 'required',
            'shipping_country' => 'required',
            'work_email'=>'required',
            'primary_phone'=>'required',
            'alternate_no'=>'required',
            'billing_country'=>'required',
            'billing_state'=>'required',
            'shipping_state'=>'required',
            'department'=>'required',
            'designation'=>'required',
        ]);
        // / dd($request->all());
        $save->manufacturer_name=$request->manufacturer_name;
        $save->website=$request->website;
        $save->primary_phone=$request->primary_phone;
        $save->secondary_phone=$request->secondary_phone;
        $save->primary_email=$request->primary_email;
        $save->secondary_email=$request->secondary_email;
        $save->owner_name=$request->owner_name;
        $save->owner_mobile=$request->owner_mobile;
        $save->owner_email=$request->owner_email;
        $save->work_email=$request->work_email;
        $save->alternate_no=$request->alternate_no;
        $save->owner_gst=$request->owner_gst;
        $save->owner_pan=$request->owner_pan;
        $save->billing_address=$request->billing_address;
        $save->shipping_address=$request->shipping_address;
        $save->billing_pobox=$request->billing_pobox;
        $save->shipping_pobox=$request->shipping_pobox;
        $save->billing_city=$request->billing_city;
        $save->shipping_city=$request->shipping_city;
        $save->billing_state=$request->billing_state;
        $save->shipping_state=$request->shipping_state;
        $save->billing_postalcode=$request->billing_postalcode;
        $save->shipping_postalcode=$request->shipping_postalcode;
        $save->billing_country=$request->billing_country;
        $save->shipping_country=$request->shipping_country;
        $save->description=$request->description;
        $save->created_time=date('Y-m-d h:i:s A');
        $save->user_id=Session::get('user_id');
        $save->website_id=Session::get('website_id');
        $save->industry=$request->industry;
        $save->type=$request->type;
        $save->department=$request->department;
        $save->designation=$request->designation;
        $save->tax_preference=$request->tax_preference;
        $save->payment_terms=$request->payment_terms;
        if($save->save())
        {

            \LogActivity::addToLog($request->manufacturer_name.' Manufacturer Create ');

            return redirect()->route("manufacturer")->with("message","Manufacturer save success");
        }else{
            return back()->with('message','error in save');
        }

    }

    function packer_update(Request $request)
    {
        $request->validate([
            'manufacturer_name' => 'required',
        ]);

        date_default_timezone_set('Asia/Kolkata');
        $save=packer::find($request->id);
        $request->validate([
            'manufacturer_name' => 'required|max:255',
            'primary_email'=>'email:rfc,dns',
            'owner_name' => 'required|max:255',
            'owner_mobile' => 'required|max:255',
            'owner_email' =>'email:rfc,dns',
            'billing_address' => 'required',
            'shipping_address' => 'required',
            'billing_city' => 'required',
            'shipping_city' => 'required',
            'billing_postalcode' => 'required',
            'shipping_postalcode' => 'required',
            'shipping_country' => 'required',
            'work_email'=>'required',
            'primary_phone'=>'required',
            'alternate_no'=>'required',
            'billing_country'=>'required',
            'billing_state'=>'required',
            'shipping_state'=>'required',
            'department'=>'required',
            'designation'=>'required',
        ]);
        // / dd($request->all());
        $save->manufacturer_name=$request->manufacturer_name;
        $save->website=$request->website;
        $save->primary_phone=$request->primary_phone;
        $save->secondary_phone=$request->secondary_phone;
        $save->primary_email=$request->primary_email;
        $save->secondary_email=$request->secondary_email;
        $save->owner_name=$request->owner_name;
        $save->owner_mobile=$request->owner_mobile;
        $save->owner_email=$request->owner_email;
        $save->work_email=$request->work_email;
        $save->alternate_no=$request->alternate_no;
        $save->owner_gst=$request->owner_gst;
        $save->owner_pan=$request->owner_pan;
        $save->billing_address=$request->billing_address;
        $save->shipping_address=$request->shipping_address;
        $save->billing_pobox=$request->billing_pobox;
        $save->shipping_pobox=$request->shipping_pobox;
        $save->billing_city=$request->billing_city;
        $save->shipping_city=$request->shipping_city;
        $save->billing_state=$request->billing_state;
        $save->shipping_state=$request->shipping_state;
        $save->billing_postalcode=$request->billing_postalcode;
        $save->shipping_postalcode=$request->shipping_postalcode;
        $save->billing_country=$request->billing_country;
        $save->shipping_country=$request->shipping_country;
        $save->description=$request->description;
        $save->created_time=date('Y-m-d h:i:s A');
        $save->user_id=Session::get('user_id');
        $save->website_id=Session::get('website_id');
        $save->industry=$request->industry;
        $save->type=$request->type;
        $save->department=$request->department;
        $save->designation=$request->designation;
        $save->tax_preference=$request->tax_preference;
        $save->payment_terms=$request->payment_terms;
        if($save->save())
        {
            \LogActivity::addToLog($request->manufacturer_name.' Packer Update ');

            return redirect()->route("packer")->with("message","Packer Update success");
        }else{
            return back()->with('message','error in save');
        }

    }

    function packer_save(Request $request)
    {
        $request->validate([
            'manufacturer_name' => 'required',
        ]);

        date_default_timezone_set('Asia/Kolkata');
        $save=new packer();


        $request->validate([
            'manufacturer_name' => 'required|max:255|unique:packer',
            'primary_email'=>'email:rfc,dns',
            'owner_name' => 'required|max:255',
            'owner_mobile' => 'required|max:255',
            'owner_email' =>'email:rfc,dns',
            'billing_address' => 'required',
            'shipping_address' => 'required',
            'billing_city' => 'required',
            'shipping_city' => 'required',
            'billing_postalcode' => 'required',
            'shipping_postalcode' => 'required',
            'shipping_country' => 'required',
            'work_email'=>'required',
            'primary_phone'=>'required',
            'alternate_no'=>'required',
            'billing_country'=>'required',
            'billing_state'=>'required',
            'shipping_state'=>'required',
            'department'=>'required',
            'designation'=>'required',
        ]);
        // / dd($request->all());
        $save->manufacturer_name=$request->manufacturer_name;
        $save->website=$request->website;
        $save->primary_phone=$request->primary_phone;
        $save->secondary_phone=$request->secondary_phone;
        $save->primary_email=$request->primary_email;
        $save->secondary_email=$request->secondary_email;
        $save->owner_name=$request->owner_name;
        $save->owner_mobile=$request->owner_mobile;
        $save->owner_email=$request->owner_email;
        $save->work_email=$request->work_email;
        $save->alternate_no=$request->alternate_no;
        $save->owner_gst=$request->owner_gst;
        $save->owner_pan=$request->owner_pan;
        $save->billing_address=$request->billing_address;
        $save->shipping_address=$request->shipping_address;
        $save->billing_pobox=$request->billing_pobox;
        $save->shipping_pobox=$request->shipping_pobox;
        $save->billing_city=$request->billing_city;
        $save->shipping_city=$request->shipping_city;
        $save->billing_state=$request->billing_state;
        $save->shipping_state=$request->shipping_state;
        $save->billing_postalcode=$request->billing_postalcode;
        $save->shipping_postalcode=$request->shipping_postalcode;
        $save->billing_country=$request->billing_country;
        $save->shipping_country=$request->shipping_country;
        $save->description=$request->description;
        $save->created_time=date('Y-m-d h:i:s A');
        $save->user_id=Session::get('user_id');
        $save->website_id=Session::get('website_id');
        $save->industry=$request->industry;
        $save->type=$request->type;
        $save->department=$request->department;
        $save->designation=$request->designation;
        $save->tax_preference=$request->tax_preference;
        $save->payment_terms=$request->payment_terms;
        if($save->save())
        {
            \LogActivity::addToLog($request->manufacturer_name.' Packer Create ');

            return redirect()->route("packer")->with("message","Packer save success");
        }else{
            return back()->with('message','error in save');
        }

    }

    function packer(Request $request)
    {
        $industry=[''=>'select industry']+industry::query()
                ->orderBy('industry_name')->get()->pluck('industry_name','id')->toArray();
        $type=[''=>'select type']+type::query()
                ->orderBy('type_name')->get()->pluck('type_name','id')->toArray();

        $country=[''=>'select country']+country::orderBy('country_name','asc')
                ->get()->pluck('country_name','id')->toArray();

        $state=[''=>'select state']+state::orderBy('state_name','asc')
                ->get()->pluck('state_name','id')->toArray();

        $city=[''=>'select city']+city::orderBy('city_name','asc')
                ->get()->pluck('city_name','id')->toArray();

        $payment_terms=[''=>'select']+payment_terms::orderBy("id","desc")
                ->get()->pluck("terms_name","days")->toArray();
        return view("admin/items/packer_add")->with(['payment_terms'=>$payment_terms,'industry'=>$industry,'type'=>$type,'country'=>$country,'state'=>$state,'city'=>$city]);

    }

    function importer(Request $request)
    {
        $industry=[''=>'select industry']+industry::query()
                ->orderBy('industry_name')->get()->pluck('industry_name','id')->toArray();
        $type=[''=>'select type']+type::query()
                ->orderBy('type_name')->get()->pluck('type_name','id')->toArray();

        $country=[''=>'select country']+country::orderBy('country_name','asc')
                ->get()->pluck('country_name','id')->toArray();

        $state=[''=>'select state']+state::orderBy('state_name','asc')
                ->get()->pluck('state_name','id')->toArray();

        $city=[''=>'select city']+city::orderBy('city_name','asc')
                ->get()->pluck('city_name','id')->toArray();

        $payment_terms=[''=>'select']+payment_terms::orderBy("id","desc")
                ->get()->pluck("terms_name","days")->toArray();
        return view("admin/items/importer_add")->with(['payment_terms'=>$payment_terms,'industry'=>$industry,'type'=>$type,'country'=>$country,'state'=>$state,'city'=>$city]);

    }

    function manufacturer(Request $request)
    {
        $industry=[''=>'select industry']+industry::query()
                ->orderBy('industry_name')->get()->pluck('industry_name','id')->toArray();
        $type=[''=>'select type']+type::query()
                ->orderBy('type_name')->get()->pluck('type_name','id')->toArray();

        $country=[''=>'select country']+country::orderBy('country_name','asc')
                ->get()->pluck('country_name','id')->toArray();

        $state=[''=>'select state']+state::orderBy('state_name','asc')
                ->get()->pluck('state_name','id')->toArray();

        $city=[''=>'select city']+city::orderBy('city_name','asc')
                ->get()->pluck('city_name','id')->toArray();

        $payment_terms=[''=>'select']+payment_terms::orderBy("id","desc")
                ->get()->pluck("terms_name","days")->toArray();
        return view("admin/items/manufacturer_add")->with(['payment_terms'=>$payment_terms,'industry'=>$industry,'type'=>$type,'country'=>$country,'state'=>$state,'city'=>$city]);

    }
    function item_group(Request $request)
    {
        $category=[''=>'select category']+category::query()
                ->orderBy('category_name','asc')
                ->get()
                ->pluck('category_name','id')
                ->toArray();

        $material=[''=>'select material']+material::query()
                ->orderBy('material_name','asc')
                ->get()
                ->pluck('material_name','id')
                ->toArray();

        $gst=[''=>'select gst']+gst::query()
                ->orderBy('gst_per','asc')
                ->get()->pluck('gst_per','id')->toArray();


        $uom=[''=>'select uom']+uom::query()
                ->orderBy('uom_name','asc')
                ->get()
                ->pluck('uom_name','id')
                ->toArray();

        $vendor=[''=>'select vendor']+vendor::query()
                ->orderBy('vendor_name','asc')
                ->get()
                ->pluck('vendor_name','id')
                ->toArray();

        $country=[''=>'select country']+country::orderBy('country_name','asc')
                ->get()->pluck('country_name','id')->toArray();

        $state=[''=>'select state']+state::orderBy('state_name','asc')
                ->get()->pluck('state_name','id')->toArray();

        $city=[''=>'select city']+city::orderBy('city_name','asc')
                ->get()->pluck('city_name','id')->toArray();

        $manufacturer=[''=>'select Manufacturer']+manufacturer::orderBy('manufacturer_name','asc')
                ->get()->pluck('manufacturer_name','id')->toArray();

        $brand=[''=>'select Brand']+brand::orderBy('brand_name','asc')
                ->get()->pluck('brand_name','id')->toArray();

        $attribute=attribute::orderBy('attribute_name', 'asc')->get();

        return view("admin/items/group_create")->with(['attribute'=>$attribute,'brand'=>$brand,'manufacturer'=>$manufacturer,'category'=>$category,'uom'=>$uom,'gst'=>$gst,'vendor'=>$vendor,'country'=>$country,'state'=>$state,'city'=>$city,'material'=>$material]);


    }

    function raw_add(Request $request)
    {
        $category=[''=>'select category']+category::query()
                ->orderBy('category_name','asc')
                ->get()
                ->pluck('category_name','id')
                ->toArray();

        $material=[''=>'select material']+material::query()
                ->orderBy('material_name','asc')
                ->get()
                ->pluck('material_name','id')
                ->toArray();

        $gst=[''=>'select gst']+gst::query()
                ->orderBy('gst_per','asc')
                ->get()->pluck('gst_per','id')->toArray();


        $uom=[''=>'select uom']+uom::query()
                ->orderBy('uom_name','asc')
                ->get()
                ->pluck('uom_name','id')
                ->toArray();

        $vendor=[''=>'select vendor']+vendor::query()
                ->orderBy('vendor_name','asc')
                ->get()
                ->pluck('vendor_name','id')
                ->toArray();

        $country=[''=>'select country']+country::orderBy('country_name','asc')
                ->get()->pluck('country_name','id')->toArray();

        $state=[''=>'select state']+state::orderBy('state_name','asc')
                ->get()->pluck('state_name','id')->toArray();

        $city=[''=>'select city']+city::orderBy('city_name','asc')
                ->get()->pluck('city_name','id')->toArray();

        $manufacturer=[''=>'select Manufacturer']+manufacturer::orderBy('manufacturer_name','asc')
                ->get()->pluck('manufacturer_name','id')->toArray();

        $importer=[''=>'select Importer']+importer::orderBy('manufacturer_name','asc')
                ->get()->pluck('manufacturer_name','id')->toArray();

        $packer=[''=>'select Packer']+packer::orderBy('manufacturer_name','asc')
                ->get()->pluck('manufacturer_name','id')->toArray();

        $brand=[''=>'select Brand']+brand::orderBy('brand_name','asc')
                ->get()->pluck('brand_name','id')->toArray();

        $attribute=attribute::orderBy('attribute_name', 'asc')->get();

        $variation=variation::select("variation.*","attribute.attribute_name")
            ->leftJoin("attribute","attribute.id","variation.attribute")
            ->get();

        $raw_material_group=[''=>'select Raw Material Group']+raw_material_group::orderBy('group_name','asc')
                ->get()->pluck('group_name','group_name')->toArray();

        return view("admin/raw_material_add")
            ->with(["raw_material_group"=>$raw_material_group,"importer"=>$importer,"packer"=>$packer,'variation'=>$variation,'attribute'=>$attribute,'brand'=>$brand,'manufacturer'=>$manufacturer,'category'=>$category,'uom'=>$uom,'gst'=>$gst,'vendor'=>$vendor,'country'=>$country,'state'=>$state,'city'=>$city,'material'=>$material]);
    }

    /**
     * The photos of one product, for screens that pick a product or a size and
     * then show what it actually looks like - belt cutting, the formulas and
     * production. A variant carries its own images; product_image is only the
     * cover, and rows created before per-variant images have nothing else.
     */
    function product_photo(Request $request)
    {
        $item = product::find($request->product);

        if (empty($item)) {
            return response()->json(['found' => false, 'images' => [], 'name' => '']);
        }

        $files = $item->variant_images ?: [];
        if (empty($files) && !empty($item->product_image)) {
            $files = [$item->product_image];
        }

        // Served straight off the document root - a "public/" in the path 404s on
        // this deployment, the same way the production screens link them.
        $images = collect($files)
            ->filter(fn ($file) => trim((string) $file) !== '')
            ->map(fn ($file) => asset('product_image/' . $file))
            ->values();

        return response()->json([
            'found' => true,
            'id' => $item->id,
            'name' => product::nameWithVariantInline($item->product_name, $item->value1 ?? null, $item->value2 ?? null),
            'item_code' => $item->item_code,
            'size' => $item->value2,
            'colour' => $item->value1,
            'images' => $images,
            'image' => $images->first(),
        ]);
    }

    function product_add(Request $request)
    {
        $category=[''=>'select category']+category::query()
                ->orderBy('category_name','asc')
                ->get()
                ->pluck('category_name','id')
                ->toArray();

        $material=[''=>'select material']+material::query()
                ->orderBy('material_name','asc')
                ->get()
                ->pluck('material_name','id')
                ->toArray();

        $gst=[''=>'select gst']+gst::query()
                ->orderBy('gst_per','asc')
                ->get()->pluck('gst_per','id')->toArray();


        $uom=[''=>'select uom']+uom::query()
                ->orderBy('uom_name','asc')
                ->get()
                ->pluck('uom_name','id')
                ->toArray();

        $vendor=[''=>'select vendor']+vendor::query()
                ->orderBy('vendor_name','asc')
                ->get()
                ->pluck('vendor_name','id')
                ->toArray();

        $country=[''=>'select country']+country::orderBy('country_name','asc')
                ->get()->pluck('country_name','id')->toArray();

        $state=[''=>'select state']+state::orderBy('state_name','asc')
                ->get()->pluck('state_name','id')->toArray();

        $city=[''=>'select city']+city::orderBy('city_name','asc')
                ->get()->pluck('city_name','id')->toArray();

        $brand=[''=>'select Brand']+brand::orderBy('brand_name','asc')
                ->get()->pluck('brand_name','id')->toArray();

        $attribute=attribute::orderBy('attribute_name', 'asc')->get();

        $color=attribute::where("attribute_name","=","Colour")->first();
        $color_value=variation::Where("attribute",$color->id ?? '')->get();

        $size=attribute::where("attribute_name","=","Size")->first();
        $size_value=variation::Where("attribute",$size->id ?? '')->get();

        $variation=variation::select("variation.*","attribute.attribute_name")
            ->leftJoin("attribute","attribute.id","variation.attribute")
            ->get();

        return view("admin/product_add")
            ->with(["color_value"=>$color_value,"size_value"=>$size_value,'variation'=>$variation,'attribute'=>$attribute,'brand'=>$brand,'category'=>$category,'uom'=>$uom,'gst'=>$gst,'vendor'=>$vendor,'country'=>$country,'state'=>$state,'city'=>$city,'material'=>$material]);
    }


    function raw_material_update(Request $request)
    {
        //dd($request->all());
        date_default_timezone_set("Asia/Kolkata");
        $request->validate([
            'product_name' => 'required|max:255',
            'purchase_price'=>'required',
        ]);
        $save=product::find($request->id);
        $save->item_code=$request->item_code;
        $save->product_name=$request->product_name;
        $save->purchase_price=$request->purchase_price;
        $save->gst=$request->gst;
        $save->uom=$request->uom;
        $save->material=$request->material;
        $save->user_id=Session::get('user_id');
        $save->website_id=Session::get('website_id');
        $save->description=$request->description;
        $save->hsn=$request->hsn;
        $save->status='raw material';
        $save->created_time=date('Y-m-d h:i:s A');
        $save->raw_material_group=$request->raw_material_group;

        if ($request->hasFile('product_image')) {
            $image = $request->file('product_image');
            $name = time().'.'.$image->getClientOriginalExtension();
            $destinationPath = public_path('/product_image/');
            $image->move($destinationPath, $name);

            $save->product_image=$name;
        }

        if(isset($request->show_hide))
        {
            $save->show_hide=$request->show_hide;
        }else{
            $save->show_hide="hide";
        }

        if(isset($request->price_show_hide))
        {
            $save->price_show_hide=$request->price_show_hide;
        }else{
            $save->price_show_hide="hide";
        }
        $save->remark=$request->remark;
        if($save->save())
        {
            return redirect()->route('admin.raw.material.list')
                ->with('message','Raw Material save successfully');
            // return redirect()->route('product-list')->with('message','product save successfully');
        }else{
            return back()->with('message','error in save');
        }
    }
    function raw_material_save(Request $request)
    {
        //dd($request->all());
        date_default_timezone_set("Asia/Kolkata");
        $request->validate([
            'product_name' => 'required|unique:product|max:255',
            'purchase_price'=>'required',
        ]);
        $save=new product();
        $save->item_code=$request->item_code;
        $save->product_name=$request->product_name;
        $save->purchase_price=$request->purchase_price;
        $save->gst=$request->gst;
        $save->uom=$request->uom;
        $save->material=$request->material;
        $save->user_id=Session::get('user_id');
        $save->website_id=Session::get('website_id');
        $save->description=$request->description;
        $save->hsn=$request->hsn;
        $save->status='raw material';
        $save->created_time=date('Y-m-d h:i:s A');
        $save->raw_material_group=$request->raw_material_group;

        if ($request->hasFile('product_image')) {
            $image = $request->file('product_image');
            $name = time().'.'.$image->getClientOriginalExtension();
            $destinationPath = public_path('/product_image/');
            $image->move($destinationPath, $name);

            $save->product_image=$name;
        }

        if(isset($request->show_hide))
        {
            $save->show_hide=$request->show_hide;
        }else{
            $save->show_hide="hide";
        }

        if(isset($request->price_show_hide))
        {
            $save->price_show_hide=$request->price_show_hide;
        }else{
            $save->price_show_hide="hide";
        }
        $save->remark=$request->remark;
        if($save->save())
        {
            return redirect()->route('admin.raw.material.list')
                ->with('message','Raw Material save successfully');
            // return redirect()->route('product-list')->with('message','product save successfully');
        }else{
            return back()->with('message','error in save');
        }
    }

    function product_save(Request $request)
    {
        //dd($request->all());
        $coverImage = Str::slug($request->product_name, '-');

        $request->validate([
            'product_name' => 'required|max:255',
        ]);

        // Same item_code + product_name can be reused to add more colour/size
        // variants later, but the exact same variant cannot be added twice.
        $duplicateVariations=[];
        $tot=count($request->size);
        for($i=0;$i<$tot;$i++)
        {
            $exists=product::where('item_code',$request->item_code)
                ->where('product_name',$request->product_name)
                ->where('value1',$request->color[$i])
                ->where('value2',$request->size[$i])
                ->exists();

            if($exists)
            {
                $duplicateVariations[]='Color: '.$request->color[$i].', Size: '.$request->size[$i];
            }
        }

        if(!empty($duplicateVariations))
        {
            return back()->withInput()->withErrors([
                'variation' => 'This variation already exists for Item Code "'.$request->item_code.'" / Product "'.$request->product_name.'" - '.implode('; ', $duplicateVariations)
            ]);
        }

        $tot=count($request->size);
        for($i=0;$i<$tot;$i++)
        {
            date_default_timezone_set("Asia/Kolkata");
            $save=new product();
            $save->item_code=$request->item_code;
            $save->bar_code=$request->bar_code;
            $save->product_name=$request->product_name;
            $save->make=$request->make;
            $save->model=$request->model;
            $save->price=$request->price[$i];
            $save->mrp=$request->mrp[$i] ?? null;
            $save->gst=$request->gst;
            $save->uom=$request->uom;
            $save->category=$request->category;
            $save->material=$request->material;
            $save->user_id=Session::get('user_id');
            $save->website_id=Session::get('website_id');
            $save->description=$request->description;
            $save->vendor=$request->vendor;
            $save->thikness=$request->thikness;
            $save->hsn=$request->hsn;
            $save->status='product';
            $save->created_time=date('Y-m-d h:i:s A');
            $save->sku=$request->sku;
            $save->subcategory=$request->subcategory;
            if(isset($request->brand)){
                $brandname=brand::find($request->brand);
                $save->brand_name=$brandname->brand_name;
                $save->brand=$request->brand;
            }

            if ($request->hasFile("attribute_image.$i")) {
                $variantImages=[];
                foreach ($request->file("attribute_image.$i") as $file) {
                    $imageName = time().'_'.uniqid().'.'.$file->extension();
                    $file->move(public_path('product_image'), $imageName);
                    $variantImages[]=$imageName;
                }
                $save->product_image=$variantImages[0] ?? "";
                $save->variant_images=$variantImages;
            }

            $save->opening_stock=$request->opening_stock ?? 0;
            $save->show_hide=$request->show_hide ?? "hide";
            $save->price_show_hide=$request->price_show_hide ?? "hide";
            $save->slug=$coverImage;
            $save->attribute1="Colour";
            $save->value1=$request->color[$i];
            $save->attribute2="Size";
            $save->value2=$request->size[$i];
            $save->product_description=$request->product_description;
            $save->remark=$request->remark;
            $save->save();

        }

        return redirect()->route('admin.product.list')->with('message','product save successfully');
    }

    function product_update(Request $request)
    {

        $image_uploader = (new ImageUpload());

        $save=product::find($request->id);

        $request->validate([
            'product_name' => 'required',
            'price'=>'required',
            'item_code'=>'required'
        ]);


        if(empty($request->category))
        {
            $category_name="";
        }else{
            $categoryname=category::find($request->category);
            $category_name=$categoryname->category_name;
        }

        if(empty($request->material))
        {
            $material_name="";
        }else{
            $materialname=material::find($request->material);
            $material_name=$materialname->material_name;
        }

        $save->product_name=$request->product_name;
        $save->item_code=$request->item_code;
        $newSlug = Str::slug($request->product_name, '-');
        $save->slug = $newSlug;
        $save->bar_code=$request->bar_code;
        $save->make=$request->make;
        $save->model=$request->model;
        $save->price=$request->price;
        $save->mrp=$request->mrp;
        $save->purchase_price=$request->purchase_price;
        $save->gst=$request->gst;
        $save->uom=$request->uom;
        $save->category=$request->category;
        $save->subcategory=$request->subcategory;
        //$save->subcategory=$request->subcategory_name;
        $save->material=$request->material;
        $save->category_name=$category_name;
        $save->material_name=$material_name;

        $save->user_id=Session::get('user_id');
        $save->website_id=Session::get('website_id');
        $save->description=$request->description;
        $save->hsn=$request->hsn;
        $save->status='product';
        $save->remark=$request->remark;
        $save->product_description=$request->product_description;

        $save->attribute1=$request->attribute1 ?? "";
        $save->value1=$request->value1 ?? "";
        $save->attribute2=$request->attribute2 ?? "";
        $save->value2=$request->value2 ?? "";
        if(isset($request->show_hide))
        {
            $save->show_hide=$request->show_hide;
        }else{
            $save->show_hide="hide";
        }

        if(isset($request->price_show_hide))
        {
            $save->price_show_hide=$request->price_show_hide;
        }else{
            $save->price_show_hide="hide";
        }

        if(isset($request->opening_stock))
        {
            $save->opening_stock=$request->opening_stock;
        }

        if ($request->hasFile('attribute_image')) {
            $this->validate($request, [
                'attribute_image.*' => 'image|mimes:jpeg,png,jpg,bmp,gif,svg|max:2024',
            ]);

            $existingVariantImages=$save->variant_images ?: ($save->product_image ? [$save->product_image] : []);
            $newVariantImages=[];
            foreach ($request->file('attribute_image') as $file) {
                $imageName = time().'_'.uniqid().'.'.$file->extension();
                $file->move(public_path('product_image'), $imageName);
                $newVariantImages[]=$imageName;
            }
            $save->variant_images=array_merge($existingVariantImages, $newVariantImages);
            if (empty($save->product_image)) {
                $save->product_image=$newVariantImages[0];
            }
        }

        if(empty($request->brand)){}else{
            $brandname=brand::find($request->brand);
            $save->brand_name=$brandname->brand_name;
            $save->brand=$request->brand;
        }

        $save->save();

        // Sync slug across all variants of same item_code
        product::where('item_code', $save->item_code)
            ->where('id', '!=', $save->id)
            ->update(['slug' => $newSlug]);

        $mimage=array();
        $mimage1=array();

        $images=product_multi_images::where("item_code","=",$save->item_code)->first();
        if($images){
            $images1 = json_decode($images->product_image, true);
            foreach ($images1 as $value) {
                $mimage[]=$value;
            }
            $k=0;
            if($request->hasfile('product_multi_image')){
                foreach($request->file('product_multi_image') as $file){
                    $imageNamemulti = time().'_'.++$k.'.'.$file->extension();
                    $file->move(public_path('product_image'), $imageNamemulti);
                    $mimage1[]=$imageNamemulti;
                }
            }
            $merge=array_merge($mimage,$mimage1);
            $images->product_image=json_encode($merge);
            $images->item_code=$request->item_code;
            $images->save();
        }else{
            $k=0;
            if($request->hasfile('product_multi_image')){
                foreach($request->file('product_multi_image') as $file){
                    $imageNamemulti = time().'_'.++$k.'.'.$file->extension();
                    $file->move(public_path('product_image'), $imageNamemulti);
                    $mimage[]=$imageNamemulti;
                }
            }
            $multiimage=new \App\product_multi_images();
            $multiimage->product_image=json_encode($mimage);
            $multiimage->item_code=$request->item_code;
            $multiimage->save();
        }

        return redirect()->route('admin.product.list')->with('message','product update successfully');
    }

    function editor_image_upload(Request $request)
    {
        $request->validate([
            'upload' => 'required|image',
        ]);

        $file = $request->file('upload');
        $name = time().'_'.uniqid().'.'.$file->extension();
        $file->move(public_path('product_image'), $name);

        return response()->json([
            'uploaded' => 1,
            'fileName' => $name,
            'url' => asset('product_image/'.$name),
        ]);
    }
}
