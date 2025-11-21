<?php

namespace App\Http\Controllers;

use App\attribute;
use App\bom_sub_product;
use App\salesorder;
use App\salesorder_item;
use App\stock_status;
use App\subcategory;
use App\variation;
use Illuminate\Http\Request;
use App\vendor;
use App\customers;
use App\product;
use App\services;
use Session;
use App\terms;
use App\state;
use App\city;
use App\material;
use App\quotation_item;
use App\quotation;
use Illuminate\Support\Str;
use App\company;

class AjaxController extends Controller
{

    function get_variation(Request $request)
    {
        $attri=attribute::where("attribute_name",$request->attribute)->first();
        $variation=variation::where("attribute",$attri->id)->get();

        $row="<option value=''>select option</option>";
        foreach ($variation as $value)
        {
            $row .="<option value='$value->variation_name'>$value->variation_name</option>";
        }
        return $row;
    }
    function generate_url(Request $request)
    {
        $slug = Str::slug($request->product_name);
        return $slug;
    }

    function delete_product_variation_image(Request $request)
    {
        $product_image_variation=product_image_variation::where("id",$request->product_image_variation_id)
            ->first();
        if($product_image_variation->delete())
        {
            return "success";
        }else{
            return "error";
        }
    }

    function getvariationvalue(Request $request)
    {
        $attribute=attribute::where('id', $request->colourid)->where('image_upload',1)->count();
        if($attribute > 0)
        {
            $variation=variation::where('attribute',$request->colourid)
                ->get();
            $row="<div class='row'>
            <table class='table'>";
            foreach($variation as $var)
            {
                $row .="<tr>";
                $row .="<td><div class='col-md-6'><input type='hidden' name='image_attribute_id[]' class='form-control' value='$var->attribute'><input type='text' name='image_variation_name[]' class='form-control' value='$var->variation_name'></div></td>";
                $row .="<td><div class='col-md-6'><input type='file' class='form-control' name='image_variation_file[]'></div></td>";
                $row .='<td><a onclick="remove_row(this)" style="cursor: pointer;" class="on-editing save-row" title="save"><i class="fa fa-trash" style="font-size: 22px"></i></a></td>';
                $row .="</tr>";
            }
            $row .='</table>';
            $row .='</div>';
            return $row;
        }

    }

    function getvariation(Request $request)
    {
        $row="<tr>";
        $srno=0;

        foreach(array_unique($request->attribute) as $attributelist)
        {
            $srno++;
            $attribute=attribute::where('id',$attributelist)->first();
            $variation=variation::where("attribute",$attributelist)
                ->get();

            $row .="<td>
            <input type='hidden' name='attribute".$srno."[]' id='attribute".$srno."' value='$attribute->attribute_name'>
            <select class='form-control options' name='value".$srno."[]' id='value".$srno."'>
            <option>select $attribute->attribute_name variation</option>";
            foreach($variation as $var)
            {
                $row .="<option value='$var->variation_name'>$var->variation_name</option>";
            }
            $row .="</select></td>";

        }
        $row .="<td><input type='text' placeholder='Item Name' class='form-control itemname' name='product_name[]'></td>";
        $row .="<td><input type='text' placeholder='SKU' class='form-control sku' name='sku[]'></td>";
        $row .='<td><input type="text" placeholder="Selling Price" name="selling_price[]" class="form-control selling_price"></td>';
        $row .='<td><input type="text" placeholder="Purchase Price" name="purchase_price[]" class="form-control purchase_price"></td>';
//        $row .='<td><input type="text" placeholder="Opening Stock"  name="opening_stock[]" class="form-control opening_stock"></td>';
        $row .='<td><input type="file" name="images'.$srno.'[]" class="form-control images"></td>';

        $row .='<td><a onclick="remove_row(this)" style="cursor: pointer;" class="on-editing save-row" title="save"><i class="fa fa-trash" style="font-size: 22px"></i></a></td>';
        $row .="</tr>";
        return $row;

    }

    function getsubcategory(Request $request)
    {
        $sub=subcategory::where('category',$request->category)->get();
        $option="<option value=''>select subcategory</option>";
        foreach($sub as $list)
        {
            $option .='<option value="'.$list->id.'">'.$list->subcategory_name.'</option>';
        }
        return $option;
    }

    function get_quot_item(Request $request)
    {
        $soitem=quotation_item::select('quot_item.*','product.product_name','material.material_name','product.outer_diameter','product.inner_diameter','product.thikness')
            ->leftJoin('product','product.id','quot_item.product')
            ->leftJoin('material','material.id','product.material')
            ->where('quot_item.quotation_no',$request->quot)
            ->get();

        $str='<table id="datatable" class="table table-striped table-bordered">';
        $str .='<tr>';
        $str .='<th>Sr.</th>';
        $str .='<th>Product Name</th>';
        $str .='<th>ID</th>';
        $str .='<th>OD</th>';
        $str .='<th>Thk</th>';
        $str .='<th>Material</th>';
        $str .='<th>so/quot QTY</th>';
        $str .='<th>Outward QTY</th>';
        $str .='<th>Remaining Qty</th>';
        $str .='</tr>';
        $srno=0;
        foreach ($soitem as $sitem)
        {
            $prod=product::where('id',$sitem->product)
                ->where('status','bom')
                ->first();
            if(empty($prod))
            {
                $stock=stock_status::where('product',$sitem->product)
                    ->first();
                $totstock=$stock->qty ?? 0;

                if($totstock >= $sitem->qty) {
                    $srno++;
                    $str .= '<tr>';
                    $str .= '<td style="width: 5%">' . $srno . '</td>';
                    $str .= '<td style="width: 35%">' . $sitem->product_name . ' <input type="hidden" name="product[]" value="'.$sitem->product.'" ></td>';
                    $str .= '<td style="width: 5%;text-align: center">' . $sitem->inner_diameter . ' <input type="hidden" name="inner_diameter[]" value="'.$sitem->inner_diameter.'" ></td>';
                    $str .= '<td style="width: 5%;text-align: center">' . $sitem->outer_diameter . ' <input type="hidden" name="outer_diameter[]" value="'.$sitem->outer_diameter.'" ></td>';
                    $str .= '<td style="width: 5%;text-align: center">' . $sitem->thikness . ' <input type="hidden" name="thikness[]" value="'.$sitem->thikness.'" ></td>';
                    $str .= '<td style="width: 10%;text-align: center">' . $sitem->material_name . ' <input type="hidden" name="material_name[]" value="'.$sitem->material_name.'" ></td>';
                    $str .= '<td style="width: 15%;"><input style="text-align: right" type="text" class="qty form-control" name="qty[]" value="' . $sitem->qty . '" onkeyup="cal(this)"><br> <span style="color: blue">Avalible stock : '.$totstock.' Qty</span></td>';
                    $str .= '<td style="width: 10%;"><input style="text-align: right" type="text" class="outward_qty form-control" name="outward_qty[]" onkeyup="cal(this)"><p class="msg" style="color: red;display: none">Do not enter more qty</p></td>';
                    $str .= '<td style="width: 10%;"><input style="text-align: right" type="text" class="remaining_qty form-control" name="remaining_qty[]" onkeyup="cal(this)"></td>';

                    $str .= "</tr>";
                }else{
                    $srno++;
                    $str .= '<tr style="color: red">';
                    $str .= '<td style="width: 5%">' . $srno . '</td>';
                    $str .= '<td style="width: 35%">' . $sitem->product_name . '<br>out of stock</td>';
                    $str .= '<td style="width: 5%;text-align: center">' . $sitem->inner_diameter . '</td>';
                    $str .= '<td style="width: 5%;text-align: center">' . $sitem->outer_diameter . '</td>';
                    $str .= '<td style="width: 5%;text-align: center">' . $sitem->thikness . '</td>';
                    $str .= '<td style="width: 10%;text-align: center">' . $sitem->material_name . '</td>';
                    $str .= '<td style="width: 15%;">' . $sitem->qty . ' <br> Avalible stock : '.$totstock.' Qty</td>';
                    $str .= '<td style="width: 10%;"></td>';
                    $str .= '<td style="width: 10%;"></td>';

                    $str .= "</tr>";
                }
            }else{
                $bomproduct=bom_sub_product::select('bom_sub_product.*','product.product_name','material.material_name','product.outer_diameter','product.inner_diameter','product.thikness')
                    ->leftJoin('product','product.id','bom_sub_product.product')
                    ->leftJoin('material','material.id','product.material')
                    ->where('bom_sub_product.bom_id',$prod->id)
                    ->get();
                foreach($bomproduct as $bomp)
                {
                    $stock=stock_status::where('product',$bomp->product)
                        ->first();
                    $totstock=$stock->qty ?? 0;

                    $stock=stock_status::where('product',$sitem->product)
                        ->first();
                    $totstock=$stock->qty ?? 0;

                    if($totstock >= $sitem->qty) {
                        $srno++;
                        $str .= '<tr style="color: blue">';
                        $str .= '<td style="width: 5%">' . $srno . '</td>';
                        $str .= '<td style="width: 35%">' . $bomp->product_name . ' <input type="hidden" name="product[]" value="'.$bomp->product.'" ></td>';
                        $str .= '<td style="width: 5%;text-align: center">' . $bomp->inner_diameter . ' <input type="hidden" name="inner_diameter[]" value="'.$bomp->inner_diameter.'" ></td>';
                        $str .= '<td style="width: 5%;text-align: center">' . $bomp->outer_diameter . ' <input type="hidden" name="outer_diameter[]" value="'.$bomp->outer_diameter.'" ></td>';
                        $str .= '<td style="width: 5%;text-align: center">' . $bomp->thikness . ' <input type="hidden" name="thikness[]" value="'.$bomp->thikness.'" ></td>';
                        $str .= '<td style="width: 10%;text-align: center">' . $bomp->material_name . ' <input type="hidden" name="material_name[]" value="'.$bomp->material_name.'" ></td>';
                        $str .= '<td style="width: 15%;"><input style="text-align: right" type="text" class="qty form-control" name="qty[]" value="' . $bomp->qty . '" onkeyup="cal(this)"><br> <span style="color: blue">Avalible stock : ' . $totstock . ' Qty</span></td>';
                        $str .= '<td style="width: 10%;"><input style="text-align: right" type="text" class="outward_qty form-control" name="outward_qty[]" onkeyup="cal(this)"><p class="msg" style="color: red;display: none">Do not enter more qty</p></td>';
                        $str .= '<td style="width: 10%;"><input style="text-align: right" type="text" class="remaining_qty form-control" name="remaining_qty[]" onkeyup="cal(this)"></td>';

                        $str .= "</tr>";
                    }else{
                        $srno++;
                        $str .= '<tr style="color: red">';
                        $str .= '<td style="width: 5%">' . $srno . '</td>';
                        $str .= '<td style="width: 35%">' . $prod->product_name . ' - ' . $bomp->product_name . '</td>';
                        $str .= '<td style="width: 5%;text-align: center">' . $bomp->inner_diameter . '</td>';
                        $str .= '<td style="width: 5%;text-align: center">' . $bomp->outer_diameter . '</td>';
                        $str .= '<td style="width: 5%;text-align: center">' . $bomp->thikness . '</td>';
                        $str .= '<td style="width: 10%;text-align: center">' . $bomp->material_name . '</td>';
                        $str .= '<td style="width: 15%;">' . $bomp->qty . '<br> Avalible stock : ' . $totstock . ' Qty</td>';
                        $str .= '<td style="width: 10%;"></td>';
                        $str .= '<td style="width: 10%;"></td>';

                        $str .= "</tr>";
                    }
                }
            }


        }
        $str .='</table>';
        return $str;
    }

    function get_sales_item(Request $request)
    {
        $soitem=salesorder_item::select('salesorder_item.*','product.product_name','material.material_name','product.outer_diameter','product.inner_diameter','product.thikness')
            ->leftJoin('product','product.id','salesorder_item.product')
            ->leftJoin('material','material.id','product.material')
            ->where('salesorder_item.sono',$request->sono)
            ->get();

        $str='<table id="datatable" class="table table-striped table-bordered">';
        $str .='<tr>';
        $str .='<th>Sr.</th>';
        $str .='<th>Product Name</th>';
        $str .='<th>ID</th>';
        $str .='<th>OD</th>';
        $str .='<th>Thk</th>';
        $str .='<th>Material</th>';
        $str .='<th>so/quot QTY</th>';
        $str .='<th>Outward QTY</th>';
        $str .='<th>Remaining Qty</th>';
        $str .='</tr>';
        $srno=0;
        foreach ($soitem as $sitem)
        {
            $prod=product::where('id',$sitem->product)
                ->where('status','bom')
                ->first();
            if(empty($prod))
            {
                $stock=stock_status::where('product',$sitem->product)
                    ->first();
                $totstock=$stock->qty ?? 0;

                if($totstock >= $sitem->qty) {
                    $srno++;
                    $str .= '<tr>';
                    $str .= '<td style="width: 5%">' . $srno . '</td>';
                    $str .= '<td style="width: 35%">' . $sitem->product_name . ' <input type="hidden" name="product[]" value="'.$sitem->product.'" ></td>';
                    $str .= '<td style="width: 5%;text-align: center">' . $sitem->inner_diameter . ' <input type="hidden" name="inner_diameter[]" value="'.$sitem->inner_diameter.'" ></td>';
                    $str .= '<td style="width: 5%;text-align: center">' . $sitem->outer_diameter . ' <input type="hidden" name="outer_diameter[]" value="'.$sitem->outer_diameter.'" ></td>';
                    $str .= '<td style="width: 5%;text-align: center">' . $sitem->thikness . ' <input type="hidden" name="thikness[]" value="'.$sitem->thikness.'" ></td>';
                    $str .= '<td style="width: 10%;text-align: center">' . $sitem->material_name . ' <input type="hidden" name="material_name[]" value="'.$sitem->material_name.'" ></td>';
                    $str .= '<td style="width: 15%;"><input style="text-align: right" type="text" class="qty form-control" name="qty[]" value="' . $sitem->qty . '" onkeyup="cal(this)"><br> <span style="color: blue">Avalible stock : '.$totstock.' Qty</span></td>';
                    $str .= '<td style="width: 10%;"><input style="text-align: right" type="text" class="outward_qty form-control" name="outward_qty[]" onkeyup="cal(this)"><p class="msg" style="color: red;display: none">Do not enter more qty</p></td>';
                    $str .= '<td style="width: 10%;"><input style="text-align: right" type="text" class="remaining_qty form-control" name="remaining_qty[]" onkeyup="cal(this)"></td>';

                    $str .= "</tr>";
                }else{
                    $srno++;
                    $str .= '<tr style="color: red">';
                    $str .= '<td style="width: 5%">' . $srno . '</td>';
                    $str .= '<td style="width: 35%">' . $sitem->product_name . '<br>out of stock</td>';
                    $str .= '<td style="width: 5%;text-align: center">' . $sitem->inner_diameter . '</td>';
                    $str .= '<td style="width: 5%;text-align: center">' . $sitem->outer_diameter . '</td>';
                    $str .= '<td style="width: 5%;text-align: center">' . $sitem->thikness . '</td>';
                    $str .= '<td style="width: 10%;text-align: center">' . $sitem->material_name . '</td>';
                    $str .= '<td style="width: 15%;">' . $sitem->qty . ' <br> Avalible stock : '.$totstock.' Qty</td>';
                    $str .= '<td style="width: 10%;"></td>';
                    $str .= '<td style="width: 10%;"></td>';

                    $str .= "</tr>";
                }
            }else{
                $bomproduct=bom_sub_product::select('bom_sub_product.*','product.product_name','material.material_name','product.outer_diameter','product.inner_diameter','product.thikness')
                    ->leftJoin('product','product.id','bom_sub_product.product')
                    ->leftJoin('material','material.id','product.material')
                    ->where('bom_sub_product.bom_id',$prod->id)
                    ->get();
                foreach($bomproduct as $bomp)
                {
                    $stock=stock_status::where('product',$bomp->product)
                        ->first();
                    $totstock=$stock->qty ?? 0;

                    $stock=stock_status::where('product',$sitem->product)
                        ->first();
                    $totstock=$stock->qty ?? 0;

                    if($totstock >= $sitem->qty) {
                        $srno++;
                        $str .= '<tr style="color: blue">';
                        $str .= '<td style="width: 5%">' . $srno . '</td>';
                        $str .= '<td style="width: 35%">' . $bomp->product_name . ' <input type="hidden" name="product[]" value="'.$bomp->product.'" ></td>';
                        $str .= '<td style="width: 5%;text-align: center">' . $bomp->inner_diameter . ' <input type="hidden" name="inner_diameter[]" value="'.$bomp->inner_diameter.'" ></td>';
                        $str .= '<td style="width: 5%;text-align: center">' . $bomp->outer_diameter . ' <input type="hidden" name="outer_diameter[]" value="'.$bomp->outer_diameter.'" ></td>';
                        $str .= '<td style="width: 5%;text-align: center">' . $bomp->thikness . ' <input type="hidden" name="thikness[]" value="'.$bomp->thikness.'" ></td>';
                        $str .= '<td style="width: 10%;text-align: center">' . $bomp->material_name . ' <input type="hidden" name="material_name[]" value="'.$bomp->material_name.'" ></td>';
                        $str .= '<td style="width: 15%;"><input style="text-align: right" type="text" class="qty form-control" name="qty[]" value="' . $bomp->qty . '" onkeyup="cal(this)"><br> <span style="color: blue">Avalible stock : ' . $totstock . ' Qty</span></td>';
                        $str .= '<td style="width: 10%;"><input style="text-align: right" type="text" class="outward_qty form-control" name="outward_qty[]" onkeyup="cal(this)"><p class="msg" style="color: red;display: none">Do not enter more qty</p></td>';
                        $str .= '<td style="width: 10%;"><input style="text-align: right" type="text" class="remaining_qty form-control" name="remaining_qty[]" onkeyup="cal(this)"></td>';

                        $str .= "</tr>";
                    }else{
                        $srno++;
                        $str .= '<tr style="color: red">';
                        $str .= '<td style="width: 5%">' . $srno . '</td>';
                        $str .= '<td style="width: 35%">' . $prod->product_name . ' - ' . $bomp->product_name . '</td>';
                        $str .= '<td style="width: 5%;text-align: center">' . $bomp->inner_diameter . '</td>';
                        $str .= '<td style="width: 5%;text-align: center">' . $bomp->outer_diameter . '</td>';
                        $str .= '<td style="width: 5%;text-align: center">' . $bomp->thikness . '</td>';
                        $str .= '<td style="width: 10%;text-align: center">' . $bomp->material_name . '</td>';
                        $str .= '<td style="width: 15%;">' . $bomp->qty . '<br> Avalible stock : ' . $totstock . ' Qty</td>';
                        $str .= '<td style="width: 10%;"></td>';
                        $str .= '<td style="width: 10%;"></td>';

                        $str .= "</tr>";
                    }
                }
            }


        }
        $str .='</table>';
        return $str;
    }

    function get_quot(Request $request)
    {
        $so=quotation::where('customer',$request->customer)
            ->get();
        $str="<option value=''>select quot</option>";
        foreach ($so as $solist)
        {
            $str .="<option value='".$solist->quotation_no."'>".$solist->quotation_no."</option>";
        }
        return $str;
    }

    function get_so(Request $request)
    {
        $so=salesorder::where('customer',$request->customer)
            ->get();
        $str="<option value=''>select so</option>";
        foreach ($so as $solist)
        {
            $str .="<option value='".$solist->salaesorder_no."'>".$solist->salaesorder_no."</option>";
        }
        return $str;
    }
  function get_customer_list(Request $request)
  {
    $customer=customers::orderBy('customer_name','asc')->where('id',$request->custid)->get();
    $str="";
    foreach ($customer as  $value) {
      $str .='<option value="'.$value->id.'">'.$value->customer_name.'</option>';
    }
    return $str;
  }

  function ajax_get_hsn(Request $request)
  {
    $data=material::where('id',$request->material)->first();
    if(empty($data))
    {
      return "";
    }else{
      return $data->hsn_code;
    }
  }
   function checkout_quot(Request $request)
  {
      
    // foreach(session('cart') as $id => $details)
    // {
    //     echo implode(",",$arr);
    // }

    // die;
    // $product1=explode(',',$request->product_id);
    $str='';

    $quot_cart=count((array) session('quot_cart'));
    if($quot_cart==0)
    {
        return back();
    }
    
    $srno=$allgst=0;
    foreach(session('quot_cart') as $id => $details)
    {
      $srno++;
            //echo $values;
      $item=product::select('product.*','gst.gst_per','uom.uom_name',"stock_status.qty as stockqty")
      ->leftJoin('gst','gst.id','product.gst')
      ->leftJoin('uom','uom.id','product.uom')
          ->leftJoin('stock_status','stock_status.product','product.id')
      ->where('product.id',$details['product_id'])
      ->first();

        
        
        $discper=0;
        $discamount=0;
        $qty=$details["quantity"];
        $productprice=$item->price*$qty;
        $price=$item->price;
        $company=company::select('company.*','state.state_name','state.state_code')
            ->leftJoin("state","state.id","company.state")
            ->first();

        $customer=customers::select('customers.*','state.state_name','state.state_code')
            ->leftJoin("state","state.id","customers.billing_state")
            ->where("customers.id",$request->customer)
            ->first();
        // dd($company);
        $igstper=$cgstper=$sgstper=$igstamt=$cgstamt=$sgstamt=$grandtotal=0;
        if($customer->tax_preference=="true")
        {
            if($company->state_code == $customer->state_code)
            {
                $igstper=0;
                $igstamt=0;
                $cgstper=$item->gst_per/2;
                $sgstper=$item->gst_per/2;
                $cgstamt=$productprice*$cgstper/100;
                $sgstamt=$productprice*$sgstper/100;
            }else{
                $igstper=$item->gst_per;
                $cgstper=$cgstamt=0;
                $sgstper=$sgstamt=0;

                $igstamt=$productprice*$igstper/100;
            }
        }
        $allgst=$cgstamt+$sgstamt+$igstamt;
        $itemtotal=$productprice;
        $grandtotal=$productprice+$cgstamt+$sgstamt+$igstamt;


      $str .='<tr id="row'.$srno.'">
      <td style="vertical-align: top !important;width: 20%">
      <div class="form-group">
      <select class="form-control product js-example-basic-single" onchange="get_product(this)" name="product[]" id="product'.$srno.'">
      <option value="'.$item->id.'">'.$item->product_name.'</option>';
      $str .='</select>
      </div>
      <div class="form-group">
      <label></label>
      <textarea style="width:100%" id="description'.$srno.'" name="description[]" class="description">'.$item->description.'</textarea>

      </div>
      </td>';
      if($item->product_image=="")
      {
          $product_image=asset('public/no-img.png');
      }else{
          $product_image=asset("public/product_image/".$item->product_image);
      }
      $stockQty=$item->stockqty ?? 0;
        $str .='<td style="vertical-align: top !important;text-align:center">
        <img style="height: 80px;width: 80px;" src="'.$product_image.'" class="photo img-responsive">
        </td>
        <td style="vertical-align: top !important;text-align: center;">
          <span class="hsnSpan">'.$item->hsn.'</span>
          <input type="hidden" class="hsn smallInputBox inputElement" name="hsn[]" value="'.$item->hsn.'" id="">
        </td>
                                        <td style="vertical-align: top !important;text-align: center;width: 10%">
                                            <input type="text" name="qty[]" onkeyup="cal(this)" value="'.$qty.'" class="qty smallInputBox inputElement" id="">
                                            <label class="stockQty">Stock : '.$stockQty.'</label>
                                        </td>
                                        <td style="vertical-align: top !important;text-align: center">
                                            <span class="uomSpan"></span><input type="hidden" class="uom smallInputBox inputElement" name="uom[]" value="0" id="">
                                        </td>

                                        <td style="vertical-align: top !important;width: 20% !important;">
                                            <div>
                                                <input oninput="cal(this)" name="price[]" value="'.$item->price.'" type="text" data-rule-required="true" data-rule-positive="true" class="price listPrice smallInputBox inputElement" data-is-price-changed="false" list-info="" data-base-currency-id="" aria-required="true" autocomplete="off" aria-invalid="false">&nbsp;<span class="priceBookPopup cursorPointer" data-popup="Popup" title="Price Books" data-module-name="PriceBooks" style="float:left">
                                                    <i class="vicon-pricebooks" title="Price Books"></i>
                                                </span>
                                            </div>
                                            <div style="clear:both"></div>
                                            <div>
                                                <span>(-)&nbsp;<strong>
                                                         <a style="cursor: pointer" onclick="disDiv(this)">Discount</a>
    (<span class="discountPerc">0</span>%)
                                                         :
                                                        <div class="discountDiv" style="display: none">
                                                            <div class="form-group" style="width: 50%;display: inline;float: left;">
                                                                <label>Disc %</label>
                                                                <input oninput="cal(this)" name="discount_per[]" value="0" type="text" data-rule-required="true" data-rule-positive="true" class="discount_per listPrice smallInputBox inputElement" data-is-price-changed="false" list-info="" data-base-currency-id="" aria-required="true" autocomplete="off" aria-invalid="false" style="width: 80%;">

                                                            </div>
                                                            <div class="form-group" style="width: 50%;float: left;">
                                                                <label>Disc Amt</label>
                                                                <input oninput="discmatcal(this)" name="discount_amount[]" value="0" type="text" class="discount_amount inputElement" style="width: 80%;">

                                                            </div>

                                                        </div>
                                                    </strong>
                                                </span>
                                            </div>

                                            <div style="width:150px;">
                                                <strong>Total After Discount :</strong>
                                            </div>
                                            <div class="individualTaxContainer">(+)&nbsp;
                                                <strong>
                                                    <a style="cursor: pointer" onclick="taxDiv(this)">Tax </a> (<span class="taxTotal">'.$item->gst_per.'</span>%):
                                                    </strong><div style="display:none;" class="taxdiv"><strong>
                                                        <div class="form-group" style="width: 34%;display:inline;float: left;">
                                                            <label>CGST %</label>
                                                            <input style="width: 35px;" oninput="cal(this)" name="cgst_per[]" value="'.$cgstper.'" type="text" data-rule-required="true" data-rule-positive="true" class="cgst_per listPrice smallInputBox inputElement" data-is-price-changed="false" list-info="" data-base-currency-id="" aria-required="true" autocomplete="off" aria-invalid="false">

                                                        </div>
                                                        <div class="form-group" style="width: 34%;display:inline;float: left;">
                                                            <label>SGST %</label>
                                                            <input style="width: 35px;" oninput="cal(this)" name="sgst_per[]" value="'.$sgstper.'" type="text" data-rule-required="true" data-rule-positive="true" class="sgst_per listPrice smallInputBox inputElement" data-is-price-changed="false" list-info="" data-base-currency-id="" aria-required="true" autocomplete="off" aria-invalid="false">

                                                        </div>
                                                        <div class="form-group" style="width: 32%;float: left;">
                                                            <div class="form-group">
                                                                <label>IGST %</label>
                                                                <input style="width: 35px;" oninput="cal(this)" name="igst_per[]" value="'.$igstper.'" type="text" data-rule-required="true" data-rule-positive="true" class="igst_per listPrice smallInputBox inputElement" data-is-price-changed="false" list-info="" data-base-currency-id="" aria-required="true" autocomplete="off" aria-invalid="false">

                                                            </div>

                                                        </div>
                                                </strong>
                                            </div>
                                            <span class="taxDivContainer">
                                                <div class="taxUI hide" id="tax_div1">
                                                    <p class="popover_title hide">Set Tax for : <span class="variable"></span>
                                                    </p>
                                                </div>
                                            </span>

                                        </div></td>

                                        <td style="vertical-align: top !important;">
                                            <div class="productTotal" align="right">'.$itemtotal.'</div>
                                            <div class="discountTotal" align="right">
            0.00
                                            </div>
                                            <div class="totalAfterDiscount" align="right">
            '.$productprice.'
                                            </div>
                                            <div id="taxTotal1" class="productTaxTotal" align="right">'.$allgst.'</div>
                                        </td>


                                        <td style="display:none;vertical-align: top !important;text-align: center;">
                                            <input type="text" name="cgst_per[]" value="'.$cgstper.'" class="cgst_per form-control" id="" oninput="cal(this)">
                                        </td>
                                        <td style="display:none;vertical-align: top !important;text-align: center;">
                                            <input type="text" name="cgst_amount[]" value="'.$cgstamt.'" class="cgst_amount form-control" id="">
                                        </td>
                                        <td style="display:none;vertical-align: top !important;text-align: center;">
                                            <input type="text" name="sgst_per[]" value="'.$sgstper.'" class="sgst_per form-control" id="" oninput="cal(this)">
                                        </td>
                                        <td style="display:none;vertical-align: top !important;text-align: center;">
                                            <input type="text" name="sgst_amount[]" value="'.$sgstamt.'" class="sgst_amount form-control" id="">
                                        </td>
                                        <td style="display:none;vertical-align: top !important;text-align: center;">
                                            <input type="text" name="gst_per[]" value="'.$igstper.'" class="gst_per form-control" id="" oninput="cal(this)">
                                        </td>
                                        <td style="display:none;vertical-align: top !important;text-align: center;">
                                            <input type="text" name="gst_amount[]" value="'.$igstamt.'" class="gst_amount form-control" id="">
                                        </td>
                                        <td style="vertical-align: top !important;text-align: center;">

                                           <input type="hidden" class="total" name="total_amount[]" value="'.$productprice.'"><input type="text" name="net_price[]" value="'.$grandtotal.'" class="netprice smallInputBox inputElement" id="">
                                        </td><td class="actions" style="vertical-align: top !important;text-align:center"><a style="customer:pointer" onclick="remove_row(this)" class="rowremove"><i class="fa fa-trash" style="font-size: 22px"></i></a></td></tr>';


    }
    //dd($str);
    if(isset($request->customer))
    {
      $cust=customers::where('id',$request->customer)->first();

      $customer=[$cust->id=>$cust->customer_name]+customers::orderBy('customer_name','asc')
      ->get()
      ->pluck('customer_name','id')
      ->toArray();
    }else{
    $customer=[''=>'select customer']+customers::orderBy('customer_name','asc')
    ->get()
    ->pluck('customer_name','id')
    ->toArray();
}
    $service=product::select('product.*','gst.gst_per','uom.uom_name','category.category_image')
    ->leftJoin('gst','gst.id','product.gst')
    ->leftJoin('uom','uom.id','product.uom')
    ->leftJoin('category','category.id','product.category')
    ->where('product.website_id',Session::get('website_id'))
    ->where('product.status','service')
    ->orderBy('product.product_name','asc')
    ->get();
    
    $product=product::select('product.*','gst.gst_per','uom.uom_name','category.category_image')
    ->leftJoin('gst','gst.id','product.gst')
    ->leftJoin('uom','uom.id','product.uom')
    ->leftJoin('category','category.id','product.category')
    ->where('product.website_id',Session::get('website_id'))
    ->where('product.status','product')
    ->orderBy('product.product_name','asc')
    ->get();
    
    $bom=product::select('product.*','gst.gst_per','uom.uom_name','category.category_image')
    ->leftJoin('gst','gst.id','product.gst')
    ->leftJoin('uom','uom.id','product.uom')
    ->leftJoin('category','category.id','product.category')
    ->where('product.website_id',Session::get('website_id'))
    ->where('product.status','bom')
    ->orderBy('product.product_name','asc')
    ->get();



    $term=[''=>'select terms']+terms::where("website_id",Session::get('website_id'))
    ->get()->pluck('module','id')->toArray();

      $module=[''=>'select terms']+terms::where("website_id",Session::get('website_id'))
              ->get()->pluck('module','id')->toArray();


    return view("admin.quotation_add")->with(["product"=>$product,"bom"=>$bom,'master_serach_product'=>'master_serach_product','totproduct'=>$srno,'customer'=>$customer,'service'=>$service,'terms'=>$term,'str'=>$str,"module"=>$module]);
  }
  function product_search(Request $request)
  {
   $product = new product();
   $product_name=$material=$category=$inner_from=$inner_to=$outer_from=$outer_to=$thikness="";
   $product=$product->select('product.*','gst.gst_per','uom.uom_name','category.category_image','category.category_name as catname','material.material_name as matname');
   $product=$product->leftJoin('gst','gst.id','product.gst');
   $product=$product->leftJoin('material','material.id','product.material');
   $product=$product->leftJoin('uom','uom.id','product.uom');
   $product=$product->leftJoin('category','category.id','product.category');

   if($request->product_name != '')
   {
    $product_name=$request->product_name;
    $product = $product->Where('product.product_name','like','%'.$request->product_name.'%');

  }
        //dd($product);
  if($request->material != '')
  {
    $material=$request->material;
    $product = $product->Where('material.material_name','like','%'.$request->material.'%');
  }

  if($request->category != '')
  {
   $category=$request->category;
   $product = $product->Where('category.category_name','like','%'.$request->category.'%');
 }

 if($request->inner_from !='' and $request->inner_to =='')
 {
    $inner_from=$request->inner_from;

    $product = $product->where('product.inner_diameter','=',$request->inner_from);
}

  if($request->inner_from =='' and $request->inner_to !='')
  {
   $inner_to=$request->inner_to;
   $product = $product->where('product.inner_diameter','=',$request->inner_to);
  }

if($request->inner_from !='' and $request->inner_to !='')
{
  $inner_from=$request->inner_from;
  $inner_to=$request->inner_to;

  $product = $product->whereBetween('product.inner_diameter', array($request->inner_from, $request->inner_to));

  $product=$product->orderBy('product.inner_diameter','asc');
}

if($request->outer_from !='' and $request->outer_to =='')
{
  $outer_from=$request->outer_from;

  $product = $product->where('product.outer_diameter','=',$request->outer_from);
}

if($request->outer_from =='' and $request->outer_to !='')
{
 $outer_to=$request->outer_to;
 $product = $product->where('product.outer_diameter','=',$request->outer_to);
}

if($request->outer_from !='' and $request->outer_to !='')
{
  $outer_from=$request->outer_from;
  $outer_to=$request->outer_to;
  $product = $product->whereBetween('product.outer_diameter', array($request->outer_from, $request->outer_to));

  $product=$product->orderBy('product.outer_diameter','asc');
}

if($request->thik != '')
{
  $thikness=$request->thik;
  $product = $product->Where('product.thikness','=',$request->thik);
}


// if(isset($request->inner_from) and !isset($request->inner_to) and isset($request->outer_from) and !isset($request->outer_to))
// {
//    $product = $product->where('inner_diameter','>=',$request->inner_from);
//   $product = $product->where('outer_diameter','<=',$request->outer_from);
// }
// if($request->inner_from !='' and $request->inner_to ==""  and $request->outer_from !='' $request->outer_to =='')
// {

// }

$product=$product->where('product.website_id',Session::get('website_id'));
$product=$product->where('product.status','product');
        //echo print_r($request->all());
$product = $product->get();
//dd($product);

$quotation_item=new quotation_item();
$quotation_item=$quotation_item->select('product.product_name','quot_item.price as qprice','customers.customer_name','category.category_name as catname','material.material_name as matname','product.inner_diameter as pinner','product.outer_diameter as pouter','product.thikness as pthik','product.id as pid','quot_item.description as qdesc','quot_item.quotation_no');
$quotation_item=$quotation_item->leftJoin('customers','customers.id','quot_item.customer');
$quotation_item=$quotation_item->leftJoin('product','product.id','quot_item.product');
$quotation_item=$quotation_item->leftJoin('category','category.id','product.category');
$quotation_item=$quotation_item->leftJoin('material','material.id','product.material');


if(isset($request->product_name))
{
   $quotation_item = $quotation_item->Where('product.product_name','like','%'.$request->product_name.'%');

}
if(isset($request->customer))
{
  $quotation_item=$quotation_item->where('quot_item.customer',$request->customer);
   $quotation_item = $quotation_item->Where('product.status','product');
  $quotation_item=$quotation_item->orderBy('quot_item.id','desc');
  $quotation_item=$quotation_item->get();
}

//dd($quotation_item);

$str='<table class="table table-striped table-bordered">
<thead>
<tr>
<th>Sr</th>
<th>Product Name</th>
<th>Category</th>
<th>Material</th>
<th>ID</th>
<th>OD</th>
<th>Thik</th>
<th>Price</th>
<th>Select</th>
<th></th>
</tr>
</thead>

<tbody>';

$srno=0;
foreach ($quotation_item as $value) {
    $srno++;
  $str .='<tr>
  <td style="color:#0c0a96;font-weight: 600;">'.$srno.'</td>
  <td style="color: #0c0a96;font-weight: 600;">'.$value->product_name.'<br>Quot No : '.$value->quotation_no.'</td>
  <td style="color: #0c0a96;font-weight: 600;">'.$value->catname.'</td>
  <td style="color: #0c0a96;font-weight: 600;">'.$value->matname.'</td>
  <td style="color: #0c0a96;font-weight: 600;">'.$value->pinner.'</td>
  <td style="color: #0c0a96;font-weight: 600;">'.$value->pouter.'</td>
  <td style="color: #0c0a96;font-weight: 600;">'.$value->pthik.'</td>
  <td style="color: #0c0a96;font-weight: 600;">'.$value->qprice.'</td>
  <td width="10%" style="text-align:center">
  <button onclick="add_to_cart('.$value->pid.','.$value->qprice.')" class="btn btn-danger my-cart-btn" data-id="'.$value->pid.'" data-name="'.$value->product_name.'" data-summary="'.$value->qdesc.'" data-price="'.$value->qprice.'" data-quantity="1" data-image="'.asset('public/product_category/'.$value->category_image).'">Add to Cart</button>
  </td>
  <td class="actions" style="width: 5%">
  <a href="'.url('client/product/edit/'.$value->pid).'" class="on-default edit-row"><i class="fa fa-pencil"></i></a>
  <a href="'.url('product_delete/'.$value->pid).'" class="on-default remove-row"><i class="fa fa-trash-o"></i></a>
  </td>';
}
foreach($product as $list)
{
  $srno++;
  $str .='<tr>
  <td>'.$srno.'</td>
  <td>'.$list->product_name.'</td>
  <td>'.$list->catname.'</td>
  <td>'.$list->matname.'</td>
  <td>'.$list->inner_diameter.'</td>
  <td>'.$list->outer_diameter.'</td>
  <td>'.$list->thikness.'</td>
  <td>'.$list->price.'</td>
  <td width="10%" style="text-align:center">
  <button onclick="add_to_cart('.$list->id.','.$list->price.')" class="btn btn-danger my-cart-btn" data-id="'.$list->id.'" data-name="'.$list->product_name.'" data-summary="'.$list->description.'" data-price="'.$list->price.'" data-quantity="1" data-image="'.asset('public/product_category/'.$list->category_image).'">Add to Cart</button>
  </td>
  <td class="actions" style="width: 5%">
  <a href="'.url('client/product/edit/'.$list->id).'" class="on-default edit-row"><i class="fa fa-pencil"></i></a>
  <a href="'.url('product_delete/'.$list->id).'" class="on-default remove-row"><i class="fa fa-trash-o"></i></a>
  </td>
  </tr>';
}
$str .='</tbody></table>';


return $str;
}


function service_search(Request $request)
  {

   $product = new product();
   $product_name=$material=$category=$inner_from=$inner_to=$outer_from=$outer_to=$thikness="";
   $product=$product->select('product.*','gst.gst_per','uom.uom_name','category.category_image','category.category_name as catname','material.material_name as matname');
   $product=$product->leftJoin('gst','gst.id','product.gst');
   $product=$product->leftJoin('material','material.id','product.material');
   $product=$product->leftJoin('uom','uom.id','product.uom');
   $product=$product->leftJoin('category','category.id','product.category');

   if($request->product_name != '')
   {
    $product_name=$request->product_name;
    $product = $product->Where('product.product_name','like','%'.$request->product_name.'%');

  }
        //dd($product);
  if($request->material != '')
  {
    $material=$request->material;
    $product = $product->Where('material.material_name','like','%'.$request->material.'%');
  }

  if($request->category != '')
  {
   $category=$request->category;
   $product = $product->Where('category.category_name','like','%'.$request->category.'%');
 }

 if($request->inner_from !='' and $request->inner_to =='')
 {
    $inner_from=$request->inner_from;

    $product = $product->where('product.inner_diameter','=',$request->inner_from);
}

  if($request->inner_from =='' and $request->inner_to !='')
  {
   $inner_to=$request->inner_to;
   $product = $product->where('product.inner_diameter','=',$request->inner_to);
  }

if($request->inner_from !='' and $request->inner_to !='')
{
  $inner_from=$request->inner_from;
  $inner_to=$request->inner_to;

  $product = $product->whereBetween('product.inner_diameter', array($request->inner_from, $request->inner_to));

  $product=$product->orderBy('product.inner_diameter','asc');
}

if($request->outer_from !='' and $request->outer_to =='')
{
  $outer_from=$request->outer_from;

  $product = $product->where('product.outer_diameter','=',$request->outer_from);
}

if($request->outer_from =='' and $request->outer_to !='')
{
 $outer_to=$request->outer_to;
 $product = $product->where('product.outer_diameter','=',$request->outer_to);
}

if($request->outer_from !='' and $request->outer_to !='')
{
  $outer_from=$request->outer_from;
  $outer_to=$request->outer_to;
  $product = $product->whereBetween('product.outer_diameter', array($request->outer_from, $request->outer_to));

  $product=$product->orderBy('product.outer_diameter','asc');
}

if($request->thik != '')
{
  $thikness=$request->thik;
  $product = $product->Where('product.thikness','=',$request->thik);
}


// if(isset($request->inner_from) and !isset($request->inner_to) and isset($request->outer_from) and !isset($request->outer_to))
// {
//    $product = $product->where('inner_diameter','>=',$request->inner_from);
//   $product = $product->where('outer_diameter','<=',$request->outer_from);
// }
// if($request->inner_from !='' and $request->inner_to ==""  and $request->outer_from !='' $request->outer_to =='')
// {

// }

$product=$product->where('product.website_id',Session::get('website_id'));
$product=$product->where('product.status','service');
        //echo print_r($request->all());
$product = $product->get();
//dd($product);

$quotation_item=new quotation_item();
$quotation_item=$quotation_item->select('product.product_name','quot_item.price as qprice','customers.customer_name','category.category_name as catname','material.material_name as matname','product.inner_diameter as pinner','product.outer_diameter as pouter','product.thikness as pthik','product.id as pid','quot_item.description as qdesc','quot_item.quotation_no');
$quotation_item=$quotation_item->leftJoin('customers','customers.id','quot_item.customer');
$quotation_item=$quotation_item->leftJoin('product','product.id','quot_item.product');
$quotation_item=$quotation_item->leftJoin('category','category.id','product.category');
$quotation_item=$quotation_item->leftJoin('material','material.id','product.material');


if(isset($request->product_name))
{
   $quotation_item = $quotation_item->Where('product.product_name','like','%'.$request->product_name.'%');

}
if(isset($request->customer))
{
  $quotation_item=$quotation_item->where('quot_item.customer',$request->customer);
  $quotation_item = $quotation_item->Where('product.status','service');
  $quotation_item=$quotation_item->orderBy('quot_item.id','desc');
  $quotation_item=$quotation_item->get();
}

//dd($quotation_item);

$str='<table class="table table-striped table-bordered">
<thead>
<tr>
<th>Sr</th>
<th>Product Name</th>
<th>Category</th>
<th>Material</th>

<th>Price</th>
<th>Select</th>
<th></th>
</tr>
</thead>

<tbody>';

$srno=0;
foreach ($quotation_item as $value) {
    $srno++;
  $str .='<tr>
  <td style="color:#0c0a96;font-weight: 600;">'.$srno.'</td>
  <td style="color: #0c0a96;font-weight: 600;">'.$value->product_name.'<br>Quot No : '.$value->quotation_no.'</td>
  <td style="color: #0c0a96;font-weight: 600;">'.$value->catname.'</td>
  <td style="color: #0c0a96;font-weight: 600;">'.$value->matname.'</td>

  <td style="color: #0c0a96;font-weight: 600;">'.$value->qprice.'</td>
  <td width="10%" style="text-align:center">
  <button class="btn btn-danger my-cart-btn" data-id="'.$value->pid.'" data-name="'.$value->product_name.'" data-summary="'.$value->qdesc.'" data-price="'.$value->qprice.'" data-quantity="1" data-image="'.asset('public/product_category/'.$value->category_image).'">Add to Cart</button>
  </td>
  <td class="actions" style="width: 5%">
  <a href="'.url('client/product/edit/'.$value->pid).'" class="on-default edit-row"><i class="fa fa-pencil"></i></a>
  <a href="'.url('product_delete/'.$value->pid).'" class="on-default remove-row"><i class="fa fa-trash-o"></i></a>
  </td>';
}
foreach($product as $list)
{
  $srno++;
  $str .='<tr>
  <td>'.$srno.'</td>
  <td>'.$list->product_name.'</td>
  <td>'.$list->catname.'</td>
  <td>'.$list->matname.'</td>

  <td>'.$list->price.'</td>
  <td width="10%" style="text-align:center">
  <button class="btn btn-danger my-cart-btn" data-id="'.$list->id.'" data-name="'.$list->product_name.'" data-summary="'.$list->description.'" data-price="'.$list->price.'" data-quantity="1" data-image="'.asset('public/product_category/'.$list->category_image).'">Add to Cart</button>
  </td>
  <td class="actions" style="width: 5%">
  <a href="'.url('client/product/edit/'.$list->id).'" class="on-default edit-row"><i class="fa fa-pencil"></i></a>
  <a href="'.url('product_delete/'.$list->id).'" class="on-default remove-row"><i class="fa fa-trash-o"></i></a>
  </td>
  </tr>';
}
$str .='</tbody></table>';


return $str;
}
function get_city(Request $request)
{
  $state=city::where('state',$request->state)->get();
  $str='<option value="">select city</option>';
  foreach($state as $s)
  {
    $str .="<option value='".$s->id."'>".$s->city_name."</option>";
  }
  return $str;
}
function get_state(Request $request)
{
  $state=state::where('country',$request->country)->get();
  $str='<option value="">select state</option>';
  foreach($state as $s)
  {
    $str .="<option value='".$s->id."'>".$s->state_name."</option>";
  }
  return $str;
}

function get_terms(Request $request)
{
  $data=terms::where('id',$request->module)->where('website_id',Session::get('website_id'))->first();
  if(empty($data))
  {
    $str='<div class="form-group"> <label>Terms & Conditions</label><textarea id="term_condition" class="form-control" name="term_condition">Not Found</textarea></div>';
  }else{
    $str='<div class="form-group"><label>Terms & Conditions</label><textarea id="term_condition" class="form-control" name="term_condition">'.$data->description.'</textarea></div>';
  }
  return $str;
}
function ajax_service_save(Request $request)
{
  $service=new product();
  $service->product_name=$request->model_service_name;
  $service->price=$request->model_service_price;
  $service->website_id=Session::get('website_id');
  $service->user_id=Session::get('user_id');
  $service->status='service';
  if($service->save())
  {
    $slist=product::orderBy('product_name','asc')->get();
    $str='<option value="'.$service->id.'">'.$request->model_service_name.'</option>';
    foreach($slist as $list)
    {
     if($service->id==$list->id)
     {
      $str .='<option value="'.$list->id.'">'.$list->product_name.'</option>';
    }
  }

  return $str;
}else{
 return "1";
}
}

function ajax_customer_save(Request $request)
{
 $customers=new customers();
 $customers->customer_name=$request->organization_name;
 $customers->website=$request->website;
 $customers->primary_phone=$request->primary_phone;
 $customers->website_id=Session::get('website_id');
 $customers->user_id=Session::get('user_id');
 $customers->save();

 $cust=customers::orderBy('customer_name','asc')->get();
 $str='<option value="'.$customers->id.'">'.$request->organization_name.'</option>';
 foreach($cust as $c)
 {
  $str .='<option value="'.$c->id.'">'.$c->customer_name.'</option>';
}
$user[]=array('organization_name'=>$request->organization_name,'website'=>$request->website,'primary_phone'=>$request->primary_phone,'str'=>$str);
return json_encode($user);
}

function ajax_customer_save1(Request $request)
{
 $customers=new customers();
 $customers->customer_name=$request->organization_name;
 $customers->website=$request->website;
 $customers->primary_phone=$request->primary_phone;
 $customers->website_id=Session::get('website_id');
 $customers->user_id=Session::get('user_id');

 $customers->save();

 $cust=customers::orderBy('customer_name','asc')->get();
 $str='<option value="'.$customers->id.'">'.$request->organization_name.'</option>';

 foreach($cust as $c)
 {
  $str .='<option value="'.$c->id.'">'.$c->customer_name.'</option>';
}

return $str;
}

function ajax_vendor_save(Request $request)
{
 $vendor=new vendor();
 $vendor->vendor_name=$request->vendor_name;
 $vendor->primary_email=$request->primary_email;
 $vendor->primary_phone=$request->primary_phone;
 $vendor->website_id=Session::get('website_id');
 $vendor->user_id=Session::get('user_id');
 if($vendor->save())
 {
  $vendor_list=vendor::orderBy('vendor_name','asc')->get();
  $str='<option value="'.$vendor->id.'">'.$request->vendor_name.'</option>';
  foreach($vendor_list as $list)
  {
   $str .='<option value="'.$list->id.'">'.$list->vendor_name.'</option>';
 }

 return $str;
}else{
  return "1";
}
}
function ajax_getproduct(Request $request)
{
 $product=product::find($request->product);
 $str='<option value="'.$product->id.'">'.$product->product_name.'</option>';
 $product_list=product::orderBy('product_name','asc')->where('status','product')->get();

 foreach($product_list as $list)
 {
  if($product->id==$list->id)
   {}else{
     $str .='<option value="'.$list->id.'">'.$list->product_name.'</option>';
   }
 }
 return $str;
}

function ajax_getservice(Request $request)
{
 $product=product::find($request->product);
 $str='<option value="'.$product->id.'">'.$product->product_name.'</option>';
 $product_list=product::orderBy('product_name','asc')->where('status','service')->get();

 foreach($product_list as $list)
 {
  if($product->id==$list->id)
   {}else{
     $str .='<option value="'.$list->id.'">'.$list->product_name.'</option>';
   }
 }
 return $str;
}

    function ajax_getbom(Request $request)
    {
        $product=product::find($request->product);
        $str='<option value="'.$product->id.'">'.$product->product_name.'</option>';
        $product_list=product::orderBy('product_name','asc')
            ->where('status','bom')->get();

        foreach($product_list as $list)
        {
            if($product->id==$list->id)
            {}else{
                $str .='<option value="'.$list->id.'">'.$list->product_name.'</option>';
            }
        }
        return $str;
    }

}
