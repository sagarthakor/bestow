<?php

namespace App\Http\Controllers;

use App\bom_sub_product;
use App\contact;
use App\customers;
use App\delivery_challan;
use App\delivery_challan_item;
use App\product;
use App\quotation;
use App\salesorder;
use App\salesorder_item;
use App\stock_book;
use App\stock_status;
use App\sub_module;
use App\terms;
use Session;
use Illuminate\Http\Request;
use DB;
class ChallanController extends Controller
{
    //
    
    function challan_update(Request $request)
    {
        $challan=delivery_challan::find($request->id);

        $challan->challan_number=$request->challan_number;
        $challan->customer=$request->customer;
        $challan->challan_date=date('Y-m-d',strtotime($request->challan_date));
        $challan->contact_name=$request->contact_name;
        $challan->valid_date=date('Y-m-d',strtotime($request->valid_date));
        $challan->subject=$request->subject;
        $challan->challan_stage=$request->challan_stage;
        $challan->sales_order=$request->sales_order;
        $challan->quotation=$request->quotation;
        $challan->remark=$request->remark;
        $challan->billing_address=$request->billing_address;
        $challan->billing_country=$request->billing_country;
        $challan->billing_city=$request->billing_city;
        $challan->billing_state=$request->billing_state;
        $challan->billing_postalcode=$request->billing_postalcode;

        $challan->shipping_address=$request->shipping_address;
        $challan->shipping_country=$request->shipping_country;
        $challan->shipping_state=$request->shipping_state;
        $challan->shipping_city=$request->shipping_city;
        $challan->shipping_postalcode=$request->shipping_postalcode;

        $challan->term_condition=$request->term_condition;
        $challan->module=$request->module;
        $challan->user_id=Session::get('user_id');
        $challan->website_id=Session::get('website_id');
        if($challan->save())
        {
            $totproduct=count($request->product);
            for($i=0;$i<$totproduct;$i++)
            {

                $status=stock_status::where('product',$request->product[$i])
                    ->first();
                if(empty($status))
                {}else{

                    $ditem=delivery_challan_item::where('product',$request->product[$i])
                        ->where("challan_no",$request->challan_number)
                        ->first();

                    if(empty($ditem))
                    {}else{
                        $stock_qty=$status->qty;
                        $oldqty=$ditem->outward_qty;
                        $told=$stock_qty+$oldqty;

                        DB::table("stock_status")
                            ->where('product',$request->product[$i])
                            ->update(['qty'=>$told]);

                        DB::table('delivery_challan_item')
                            ->where('product',$request->product[$i])
                            ->where("challan_no",$request->challan_number)
                            ->update(['olditem'=>'Y']);
                    }

                    $challan_item=new delivery_challan_item();
                    $challan_item->challan_id=$challan->id;
                    $challan_item->challan_no=$request->challan_number;
                    $challan_item->product=$request->product[$i];
                    $challan_item->inner_diameter=$request->inner_diameter[$i];
                    $challan_item->outer_diameter=$request->outer_diameter[$i];
                    $challan_item->thikness=$request->thikness[$i];
                    $challan_item->material_name=$request->material_name[$i];
                    $challan_item->so_quot_qty=$request->qty[$i];
                    $challan_item->outward_qty=$request->outward_qty[$i];
                    $challan_item->remaining_qty=$request->remaining_qty[$i];

                    if($challan_item->save())
                    {
                        $stock_qty=$status->qty;
                        $outward_qty=$request->outward_qty[$i];
                        $avalible_qty=$stock_qty-$outward_qty;

                        DB::table("stock_status")
                            ->where('product',$request->product[$i])
                            ->update(['qty'=>$avalible_qty]);

                        $book=new stock_book();
                        $book->date_time=date('Y-m-d h:i:s');
                        $book->challan_no=$request->challan_number;
                        $book->salesorder_no=$request->sales_order;
                        $book->quotation=$request->quotation;
                        $book->product=$request->product[$i];
                        $book->outward_qty=$request->outward_qty[$i];
                        $book->particular="Delivery Challan";
                        $book->created_time=date('d-m-Y h:i:s a');
                        $book->user_id=Session::get('user_id');
                        $book->website_id=Session::get('website_id');
                        $book->customer=$request->customer;
                        $book->remaining_qty=$request->remaining_qty[$i];
                        $book->inward_qty=$request->qty[$i];
                        $book->save();


                    }
                }

            }

            delivery_challan_item::where('challan_no',$request->challan_number)
                ->where('olditem','=','Y')
                ->delete();

            return redirect()->route("challan")->with('message','challan update sucessfully');
        }
    }

    function challan_edit(Request $request)
    {
        $data=delivery_challan::find($request->id);
        $contact=contact::where('id',$data->contact_name)->first();
        $customer=[''=>'select customer']+customers::orderBy('customer_name','asc')
                ->get()
                ->pluck('customer_name','id')
                ->toArray();

        $sales_order=[$data->sales_order ?? ''=>$data->sales_order ?? ''];
        $quotation=[$data->quotation ?? '' =>$data->quotation ?? ''];
        $contact_name=[$contact->id ?? '' =>$contact->contact_name ?? ''];
        $terms=[''=>'select terms']+terms::where("website_id",Session::get('website_id'))
                ->get()->pluck('module','id')->toArray();

        $challan_item=delivery_challan_item::select("delivery_challan_item.*",'product.product_name')
            ->leftJoin('product','product.id','delivery_challan_item.product')
            ->where('delivery_challan_item.challan_no',$data->challan_number)
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

        foreach($challan_item as $sitem)
        {
            $extraproduct[]=$sitem->product;
            $prod=product::where('id',$sitem->product)
                ->where('status','bom')
                ->first();
            if(empty($prod))
            {
                $stock=stock_status::where('product',$sitem->product)
                    ->first();
                $totstock=$stock->qty ?? 0;

                if($totstock+$sitem->so_quot_qty >= $sitem->qty) {
                    $srno++;
                    $str .= '<tr>';
                    $str .= '<td style="width: 5%">' . $srno . '</td>';
                    $str .= '<td style="width: 35%">' . $sitem->product_name . ' <input type="hidden" name="product[]" value="'.$sitem->product.'" ></td>';
                    $str .= '<td style="width: 5%;text-align: center">' . $sitem->inner_diameter . ' <input type="hidden" name="inner_diameter[]" value="'.$sitem->inner_diameter.'" ></td>';
                    $str .= '<td style="width: 5%;text-align: center">' . $sitem->outer_diameter . ' <input type="hidden" name="outer_diameter[]" value="'.$sitem->outer_diameter.'" ></td>';
                    $str .= '<td style="width: 5%;text-align: center">' . $sitem->thikness . ' <input type="hidden" name="thikness[]" value="'.$sitem->thikness.'" ></td>';
                    $str .= '<td style="width: 10%;text-align: center">' . $sitem->material_name . ' <input type="hidden" name="material_name[]" value="'.$sitem->material_name.'" ></td>';
                    $str .= '<td style="width: 15%;"><input style="text-align: right" type="text" class="qty form-control" name="qty[]" value="' . $sitem->so_quot_qty . '" onkeyup="cal(this)"><br> <span style="color: blue">Avalible stock : '.$totstock.' Qty</span></td>';
                    $str .= '<td style="width: 10%;"><input style="text-align: right" type="text" class="outward_qty form-control" value="'.$sitem->outward_qty.'" name="outward_qty[]" onkeyup="cal(this)"><p class="msg" style="color: red;display: none">Do not enter more qty</p></td>';
                    $str .= '<td style="width: 10%;"><input style="text-align: right" type="text" class="remaining_qty form-control" value="'.$sitem->remaining_qty.'" name="remaining_qty[]" onkeyup="cal(this)"></td>';

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

                    if($totstock+$sitem->so_quot_qty >= $sitem->qty) {
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


        $soitem=salesorder_item::select('salesorder_item.*','product.product_name','material.material_name','product.outer_diameter','product.inner_diameter','product.thikness')
            ->leftJoin('product','product.id','salesorder_item.product')
            ->leftJoin('material','material.id','product.material')
            ->where('salesorder_item.sono',$data->sales_order)
            ->whereNotIn("salesorder_item.product",$extraproduct)
            ->get();



        foreach($soitem as $sitem)
        {
            $extraproduct[]=$sitem->product;
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
                    $str .= '<td style="width: 10%;"><input style="text-align: right" type="text" class="outward_qty form-control"  name="outward_qty[]" value="0" onkeyup="cal(this)"><p class="msg" style="color: red;display: none">Do not enter more qty</p></td>';
                    $str .= '<td style="width: 10%;"><input style="text-align: right" type="text" class="remaining_qty form-control"  name="remaining_qty[]" value="' . $sitem->qty . '" onkeyup="cal(this)"></td>';

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
        return view("admin/challan/challan_edit",compact('customer','terms','data','sales_order','quotation','contact_name','str'));

    }

    function challan_list(Request $request)
    {
        $challan=new delivery_challan();
        $challan=$challan->select('delivery_challan.*','customers.customer_name');
        $challan=$challan->leftJoin('customers','customers.id','delivery_challan.customer');
        $challan=$challan->orderBy('id','desc');
        $challan=$challan->paginate(10);

        return view("admin/challan/challan_list",compact('challan'));
    }
    function challan_save(Request $request)
    {
        date_default_timezone_set('Asia/Kolkata');
        //dd($request->all());

        $qno=delivery_challan::where('website_id',Session::get('website_id'))
            ->max('challan_no');

        if(empty($qno))
        {
            $year = date("Y");
            $nextyear = $year +1;

            $n2=str_pad($qno + 1, 4, 0, STR_PAD_LEFT);
            $qno=0;
            $n2 .='/';
            $n2 .=$year;
            $n2 .='-';
            $n2 .=$nextyear;
        }else{
            $year = date("Y");
            $nextyear = $year +1;
            $n2 = str_pad($qno + 1, 4, 0, STR_PAD_LEFT);
            $n2 .='/';
            $n2 .=$year;
            $n2 .='-';
            $n2 .=$nextyear;
        }

        $challan=new delivery_challan();

        $challan->challan_no=$qno+1;
        $challan->challan_number=$n2;
        $challan->customer=$request->customer;
        $challan->challan_date=date('Y-m-d',strtotime($request->challan_date));
        $challan->contact_name=$request->contact_name;
        $challan->valid_date=date('Y-m-d',strtotime($request->valid_date));
        $challan->subject=$request->subject;
        $challan->challan_stage=$request->challan_stage;
        $challan->sales_order=$request->sales_order;
        $challan->quotation=$request->quotation;
        $challan->remark=$request->remark;
        $challan->billing_address=$request->billing_address;
        $challan->billing_country=$request->billing_country;
        $challan->billing_city=$request->billing_city;
        $challan->billing_state=$request->billing_state;
        $challan->billing_postalcode=$request->billing_postalcode;

        $challan->shipping_address=$request->shipping_address;
        $challan->shipping_country=$request->shipping_country;
        $challan->shipping_state=$request->shipping_state;
        $challan->shipping_city=$request->shipping_city;
        $challan->shipping_postalcode=$request->shipping_postalcode;

        $challan->term_condition=$request->term_condition;
        $challan->module=$request->module;
        $challan->user_id=Session::get('user_id');
        $challan->website_id=Session::get('website_id');
        if($challan->save())
        {
            $totproduct=count($request->product);
            for($i=0;$i<$totproduct;$i++)
            {
                $status=stock_status::where('product',$request->product[$i])
                    ->first();
                if(empty($status))
                {}else{
                    $challan_item=new delivery_challan_item();
                    $challan_item->challan_id=$challan->id;
                    $challan_item->challan_no=$n2;
                    $challan_item->product=$request->product[$i];
                    $challan_item->inner_diameter=$request->inner_diameter[$i];
                    $challan_item->outer_diameter=$request->outer_diameter[$i];
                    $challan_item->thikness=$request->thikness[$i];
                    $challan_item->material_name=$request->material_name[$i];
                    $challan_item->so_quot_qty=$request->qty[$i];
                    $challan_item->outward_qty=$request->outward_qty[$i];
                    $challan_item->remaining_qty=$request->remaining_qty[$i];

                    if($challan_item->save())
                    {
                        $stock_qty=$status->qty;
                        $outward_qty=$request->outward_qty[$i];
                        $avalible_qty=$stock_qty-$outward_qty;

                        DB::table("stock_status")
                            ->where('product',$request->product[$i])
                            ->update(['qty'=>$avalible_qty]);

                        $book=new stock_book();
                        $book->date_time=date('Y-m-d h:i:s');
                        $book->challan_no=$n2;
                        $book->salesorder_no=$request->sales_order;
                        $book->quotation=$request->quotation;
                        $book->product=$request->product[$i];
                        $book->outward_qty=$request->outward_qty[$i];
                        $book->particular="Delivery Challan";
                        $book->created_time=date('d-m-Y h:i:s a');
                        $book->user_id=Session::get('user_id');
                        $book->website_id=Session::get('website_id');
                        $book->customer=$request->customer;
                        $book->remaining_qty=$request->remaining_qty[$i];
                        $book->inward_qty=$request->qty[$i];
                        $book->save();


                    }
                }

            }

            return redirect()->route("challan")->with('message','challan save sucessfully');
        }
    }

    function challan_add()
    {
        $customer=[''=>'select customer']+customers::orderBy('customer_name','asc')
                ->get()
                ->pluck('customer_name','id')
                ->toArray();

        $terms=[''=>'select terms']+terms::where("website_id",Session::get('website_id'))
                ->get()->pluck('module','id')->toArray();

        return view("admin/challan/challan_add",compact('customer','terms'));
    }
}
