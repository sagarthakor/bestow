<?php

namespace App\Http\Controllers;

use App\customers;
use App\inward;

use App\inward_item;
use App\purchase;
use App\purchase_item;
use App\stock_book;
use App\stock_status;
use App\vendor;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\purchase_receive;
use App\purchase_receive_item;
use Illuminate\Support\Facades\Redirect;
use Session;
use App\bom_sub_product;
use App\product;

class InwardController extends Controller
{
    //
    function bominward(Request $request){
          $subproduct=bom_sub_product::where("bom_id",$request->product)->get();
          $str="<table class='table table-bordered' id='subproduct'>";
          $str .="<thread><tr><th>Sr.No</th><th>Product Name</th><th>Qty</th><th>Stock</th></tr></thread>";
          $str .="<tbody>";
          $srno=0;
          $sumqty=0;
          $sumrequirestock=0;
          $finalsum=0;
          $negativemark=0;
          foreach($subproduct as $sub){
              $srno++;
              $product=product::select("product.id","product.product_name","stock_status.qty as stockqty","uom.uom_name")
              ->leftJoin("uom","uom.id","product.uom")
              ->leftJoin("stock_status","stock_status.product","product.id")
              ->where("product.id",$sub->product)
              ->first();
              $bomsub=bom_sub_product::where("product",$sub->product)
              ->where("id",$sub->id)->first();
              $stock=$product->stockqty ?? 0;
              $requiredStock=$request->bom_qty*$bomsub->qty;
              $sumqty=$sumqty+$product->stockqty;
              $sumrequirestock=$sumrequirestock+$requiredStock;
              $finalstock=$stock-$requiredStock;
              $finalsum=$finalsum+$finalstock;
              if ($finalstock < 0)
              {
                  $negativemark++;
                  $str .="<tr style='border:2px solid red'>";
              }else{
                  $str .="<tr>";
              }
              $uomname=$product->uom_name;
              $str .="<td style='text-align:center'>$srno</td>";
              $str .="<td>$product->product_name</td>";
              $str .="<td style='text-align:center'>$requiredStock $uomname</td>";
              $str .="<td style='text-align:center'>$stock $uomname</td>";
            //   $str .="<td>$finalstock</td>";
              $str .="</tr>";
          }
          if($negativemark>0){
              $str .="<tr style='border:2px solid red'><td colspan='5' style='text-align:right;color:red'><span class='substock' style='display:none'>$negativemark</span>$negativemark Products are not in Stock So You Can't add This BOM in Stock</td></tr>";
          }else{
              $str .="<tr style='border:2px solid green'><td colspan='5' style='text-align:right;color:green'><span class='substock' style='display:none'>$negativemark</span>Sub Products are in Stock So You Can add This BOM in Stock</td></tr>";
          }

          $str .="</tbody>";
          $str .="</table>";
          return $str;
    }

    function inward_update(Request $request)
    {
        $checkproduct = stock_status::where("product", $request->product)
            ->first();

        $inward_item = inward_item::find($request->id);

        $checkproduct = stock_status::where("product", $request->product)
            ->first();
        $totqty = $checkproduct->qty - $inward_item->received_qty;
        $newqty=$totqty+$request->received;
        $inward_item->customer = $request->customer;
        $inward_item->inward_type = $request->inward_type;
        $inward_item->inward_from = $request->inward_from;
        $inward_item->product = $request->product;
        $inward_item->received_qty = $request->received ?? '0';
        $inward_item->created_time = date('d-m-Y h:i:s a');
        $inward_item->inward_date = date('Y-m-d', strtotime($request->inward_date));
        $inward_item->save();




        $checkproduct->qty = $newqty;
        $checkproduct->inward_date = date('Y-m-d');
        $checkproduct->particular = "New Stock Receive Update";
        $checkproduct->created_time = date('d-m-Y h:i:s a');
        $checkproduct->save();

        $book = new stock_book();
        $book->purchase_no = $request->purchase_no;
        $book->product = $request->product;
        $book->inward_date = date('Y-m-d');
        $book->inward_qty = $request->received;
        $book->remaining_qty = $request->received;
        $book->particular = "New Stock Receive Update";
        $book->created_time = date('d-m-Y h:i:s a');
        $book->user_id = Session::get("user_id");
        $book->save();

        return redirect()->route("normal/inward")->with("message", "inward save successfully");

    }

    function inward_edit(Request $request)
    {

        $item = "";

        $vendor = ['' => 'select vendor'] + vendor::orderBy('vendor_name', 'asc')
                ->get()->pluck('vendor_name', 'id')->toArray();

        $customer = ['' => 'select customer'] + customers::orderBy('customer_name', 'asc')
                ->get()->pluck('customer_name', 'id')->toArray();


        $item = inward_item::select('inward_item.*', 'product.product_name')
            ->leftJoin('product', 'product.id', 'inward_item.product')
            ->where("inward_item.id", $request->id)
            ->first();
        //dd($item);

        return view("admin.inward.inward_edit")->with(['item' => $item, 'customer' => $customer]);
    }

    function inward_save(Request $request)
    {
        //dd($request->all());
        date_default_timezone_set('Asia/Kolkata');
        if(empty($request->product)){}else{
           $totinward = count($request->product);
        for ($i = 0; $i < $totinward; $i++) {
            if (empty($request->product[$i])) {
            } else {
                $inward_item = new inward_item();
                $inward_item->customer = $request->customer;
                $inward_item->inward_type = $request->inward_type;
                $inward_item->inward_from = $request->inward_from;
                $inward_item->product = $request->product[$i];
                $inward_item->received_qty = $request->received[$i] ?? '0';
                $inward_item->created_time = date('d-m-Y h:i:s a');
                $inward_item->inward_date = date('Y-m-d', strtotime($request->inward_date));
                $inward_item->save();

                $checkproduct = stock_status::where("product", $request->product[$i])
                    ->first();
                if (empty($checkproduct)) {
                    $status = new stock_status();
                    $status->product = $request->product[$i];
                    $status->qty = $request->received[$i];
                    $status->particular =$request->inward_from;
                    $status->inward_date = date('Y-m-d');
                    $status->user_id = Session::get("user_id");
                    $status->created_time = date('d-m-Y h:i:s a');
                    $status->save();
                } else {
                    $totqty = $request->received[$i] + $checkproduct->qty;
                    $checkproduct->qty = $totqty;
                    $checkproduct->inward_date = date('Y-m-d');
                    $checkproduct->particular =$request->inward_from;
                    $checkproduct->created_time = date('d-m-Y h:i:s a');
                    $checkproduct->save();
                }

                $book = new stock_book();
                $book->purchase_no = $request->purchase_no;
                $book->product = $request->product[$i];
                $book->inward_date = date('Y-m-d');
                $book->inward_qty = $request->received[$i];
                $book->remaining_qty = $request->received[$i];
                $book->particular =$request->inward_from;
                $book->created_time = date('d-m-Y h:i:s a');
                $book->user_id = Session::get("user_id");
                $book->save();
            }

        }
        }


        if(empty($request->bom)){}else{
        $totbom = count($request->bom);
        for ($i = 0; $i < $totbom; $i++) {
            if (empty($request->bom[$i])) {
            } else {
                $inward_item = new inward_item();
                $inward_item->customer = $request->customer;
                $inward_item->inward_type = $request->inward_type;
                $inward_item->inward_from ="Production (BOM)";
                $inward_item->product = $request->bom[$i];
                $inward_item->received_qty = $request->bom_qty[$i] ?? '0';
                $inward_item->created_time = date('d-m-Y h:i:s a');
                $inward_item->inward_date = date('Y-m-d', strtotime($request->inward_date));
                $inward_item->save();



                $checkproduct = stock_status::where("product", $request->bom[$i])
                    ->first();
                if (empty($checkproduct)) {
                    $status = new stock_status();
                    $status->product = $request->bom[$i];
                    $status->qty = $request->bom_qty[$i];
                    $status->particular ="Production (BOM)";
                    $status->inward_date = date('Y-m-d');
                    $status->user_id = Session::get("user_id");
                    $status->created_time = date('d-m-Y h:i:s a');
                    $status->save();
                } else {
                    $totqty = $request->bom_qty[$i] + $checkproduct->qty;
                    $checkproduct->qty = $totqty;
                    $checkproduct->inward_date = date('Y-m-d');
                    $checkproduct->particular ="Production (BOM)";
                    $checkproduct->created_time = date('d-m-Y h:i:s a');
                    $checkproduct->save();
                }

                $book = new stock_book();
                $book->purchase_no = $request->purchase_no;
                $book->product = $request->bom[$i];
                $book->inward_date = date('Y-m-d');
                $book->inward_qty = $request->bom_qty[$i];
                $book->remaining_qty = $request->bom_qty[$i];
                $book->particular ="Production (BOM)";
                $book->created_time = date('d-m-Y h:i:s a');
                $book->user_id = Session::get("user_id");
                $book->save();


                $bom=bom_sub_product::where("bom_id",$request->bom[$i])->get();
                    foreach($bom as $b){
                        $checkproduct = stock_status::where("product", $b->product)
                        ->first();
                        $bomsubqty=$b->qty*$request->bom_qty[$i];
                            $totqty =$checkproduct->qty-$bomsubqty;
                            $checkproduct->qty = $totqty;
                            $checkproduct->inward_date = date('Y-m-d');
                            $checkproduct->particular ="Production (Product)";
                            $checkproduct->created_time = date('d-m-Y h:i:s a');
                            $checkproduct->save();

                            $book = new stock_book();
                            $book->product = $b->product;
                            $book->inward_date = date('Y-m-d');
                            $book->outward_qty = $bomsubqty;
                            $book->remaining_qty = $totqty;
                            $book->particular ="Production (Product)";
                            $book->created_time = date('d-m-Y h:i:s a');
                            $book->user_id = Session::get("user_id");
                            $book->save();
                    }

            }

        }
        }
        return redirect()->route("admin.inward.list")->with("message", "inward save successfully");

    }


    function stock_status(Request $request)
    {
        $status = new stock_status();
        $status = $status->select('stock_status.*', 'product.product_name');
        $status = $status->rightJoin('product', 'product.id', 'stock_status.product');
        if (isset($request->product_name)) {
            $status = $status->where('product.product_name', 'like', '%' . $request->product_name . '%');
        }
        if (isset($request->qty)) {
            $status = $status->where('stock_status.qty', $request->qty);
        }

        if ($request->filled('created_time')) {
            $date = Carbon::parse($request->created_time)->format('d-m-Y');
            $status = $status->where('stock_status.created_time', 'like', $date . '%');
        }

        $status = $status->orderBy('stock_status.created_time', 'desc');
        $status = $status->paginate(10);

        return view("admin.stock.stock_status")
            ->with(['data' => $status]);
    }

    function received_save(Request $request)
    {
        date_default_timezone_set("Asia/Kolkata");
        $pr = new purchase_receive();
        $pr->purchase_no = $request->purchase_no;
        $pr->receive_date = date('Y-m-d', strtotime($request->received_date));
        $pr->user_id = Session::get("user_id");
        $pr->note = $request->note;
        $pr->create_time = date('Y-m-d h:i:s');
        if ($pr->save()) {
            if (empty($request->item)) {
            } else {
                $totitem = count($request->item);
                for ($i = 0; $i < $totitem; $i++) {
                    if ($request->qty_received[$i] == 0 || $request->qty_received[$i] == "") {
                    } else {
                        $pitem = purchase_item::where("pono", $request->purchase_no)
                            ->where("product", $request->item[$i])
                            ->first();
                        $piqty = $pitem->received_qty ?? 0;
                        $totpoitem = $piqty + $request->qty_received[$i];
                        $pitem->received_qty = $totpoitem;
                        $pitem->remain_qty = $pitem->qty - $totpoitem;
                        $pitem->save();

                        $preceive = new purchase_receive_item();
                        $preceive->purchase_receive_id = $pr->id;
                        $preceive->item = $request->item[$i];
                        $preceive->order_qty = $request->order[$i];
                        $preceive->total_received_qty = $request->received[$i];
                        $preceive->remain_qty = $request->remain_qty[$i];
                        $preceive->qty_received = $request->qty_received[$i];
                        $preceive->purchase_no = $request->purchase_no;
                        if ($preceive->save()) {
                            $checkproduct = stock_status::where("product", $request->item[$i])
                                ->first();
                            if (empty($checkproduct)) {
                                $status = new stock_status();
                                $status->product = $request->item[$i];
                                $status->qty = $request->qty_received[$i];
                                $status->particular = "Purchase Receive";
                                $status->inward_date = date('Y-m-d');
                                $status->user_id = Session::get("user_id");
                                $status->created_time = date('d-m-Y h:i:s a');
                                $status->save();
                            } else {
                                $totqty = $request->qty_received[$i] + $checkproduct->qty;
                                $checkproduct->qty = $totqty;
                                $checkproduct->inward_date = date('Y-m-d');
                                $checkproduct->particular = "Purchase Receive";
                                $checkproduct->created_time = date('d-m-Y h:i:s a');
                                $checkproduct->save();
                            }

                            $book = new stock_book();
                            $book->purchase_no = $request->purchase_no;
                            $book->product = $request->item[$i];
                            $book->inward_date = date('Y-m-d');
                            $book->inward_qty = $request->qty_received[$i];
                            $book->remaining_qty = $request->qty_received[$i];
                            $book->particular = "Purchase Receive";
                            $book->created_time = date('d-m-Y h:i:s a');
                            $book->user_id = Session::get("user_id");
                            $book->save();
                        }
                    }
                }
            }
        }
        $poid = purchase::where("purchase_no", $request->purchase_no)->first();

        return redirect()->route("admin.purchase.list");
    }

    function inward_add(Request $request)
    {
        $podata = purchase::find($request->po_id);

        $poitem = purchase_item::select('purchase_item.*', 'product.product_name', 'product.make', 'product.model','uom.uom_name','product.status as product_status')
            ->leftJoin('product', 'product.id', 'purchase_item.product')
            ->leftJoin("uom","uom.id","product.uom")
            ->where('purchase_item.pono', $podata->purchase_no)
            ->get();

        //dd($podata->purchase_no);
        $poreceive = purchase_receive::where("purchase_no", $podata->purchase_no)->get();
        //dd($poreceive);

        $row = "";
        foreach ($poreceive as $precv) {
//            $row .="<tr><td colspan='3'><br></td></tr>";
            $row .= "<tr style='background-color: #c3e6cb;'>";
            $row .= "<th>$precv->purchase_no</th>";
            $row .= "<th>" . date('d-m-Y', strtotime($precv->receive_date)) . "</th>";
            $row .= "<th>$precv->note</th>";
            $row .= "</tr>";
            $received = purchase_receive_item::select("purchase_receive_item.*", "product.product_name")
                ->leftJoin('product', 'product.id', 'purchase_receive_item.item')
                ->where("purchase_receive_item.purchase_receive_id", $precv->id)
                ->get();

            $row .= '<tr style="background-color: #b8daff;"><th>Product Name</th><th>Order Qty</th><th>Receive Qty</th></tr>';

            foreach ($received as $precitem) {
                $row .= "<tr style='background-color: #ffeeba;'>";
                $row .= "<td>$precitem->product_name</td>";
                $row .= "<td>$precitem->order_qty</td>";
                $row .= "<td>$precitem->qty_received</td>";
                $row .= "</tr>";
//                $row .="<tr><td colspan='3'><br></td></tr>";
            }
            $row .= "<tr><td colspan='3'><br></td></tr>";
        }

        return view("admin.inward.received_add")->with(['received_item' => $row, 'data' => $podata, 'poitem' => $poitem]);
    }
}
