<?php
namespace App\Http\Controllers;
use App\bom_sub_product;
use App\payment_terms;
use App\purchase_receive_item;
use App\purchase_required_material;
use App\purchase_requirement;
use Illuminate\Http\Request;
use App\product;
use App\customers;
use App\quotation_item;
use App\quotation;
use App\company;
use Illuminate\Support\Facades\Redirect;
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
use App\purchase;
use App\purchase_item;
use App\inward;
use App\inward_item;
use App\stock_status;
use App\stock_book;
use App\purchase_receive;
use App\finacial_year;

class PurchaseController extends Controller
{
    //

    function search_product(Request $request)
    {
        // $pagesize = $request->pagesize;
        $data=new product();
        $data=$data->select("product.id","product.product_name", "product.value1", "product.value2","product.hsn","product.price","gst.gst_per","product.product_image","product.item_code");
        $data=$data->leftJoin("gst","gst.id","product.gst");
        if(isset($request->product_name))
        {

            $data=$data->orwhere("product.product_name","LIKE",'%'.$request->product_name.'%');

        }
        if(isset($request->item_code))
        {

            $data=$data->orwhere("product.item_code","LIKE",'%'.$request->item_code.'%');

        }

        if(isset($request->prices))
        {
            $data=$data->orwhere("product.price","LIKE",'%'.$request->prices.'%');
        }
        if(isset($request->gst))
        {
            $data=$data->orwhere("gst.gst_per","LIKE",'%'.$request->gst.'%');
        }

        $data=$data->paginate(session('records_per_page', 30));
        // $data=$data->paginate(is_null($pagesize) ? 1 : $pagesize);
        //dd($data);

        $service=product::select('product.*','gst.gst_per','uom.uom_name','category.category_image','category.category_name as catname','material.material_name as matname')
            ->leftJoin('gst','gst.id','product.gst')
            ->leftJoin('category','category.id','product.category')
            ->leftJoin('material','material.id','product.material')
            ->leftJoin('uom','uom.id','product.uom')
            ->where('product.status','service')
            ->orderBy('product.product_name','asc')
            ->get();

        $customer=vendor::orderBy('vendor_name','asc')->get();

        return view("admin/purchase/search_product")->with(['data'=>$data,'vendor'=>$customer,'service'=>$service]);
    }

     function po_delete(Request $request)
    {
        date_default_timezone_set("Asia/Kolkata");

        $sales =purchase::find($request->id);
        $sales->delete_status=1;
        $sales->delete_user=Session::get('user_id');
        $sales->delete_datetime=date("d-m-Y h:i:s a");
        if($sales->save())
        {
            return back()->with("message","Purchase delete successfully");
        }
    }
    function po_invoice_view(Request $request)
    {
        $purchase=purchase::find($request->id);

        return view("admin.purchase.invoice_view")->with(['po_data'=>$purchase]);
    }

    function po_receive(Request $request)
    {
            $purchase=purchase::find($request->id);
            //dd($request->id);
            $po_receive=purchase_receive::where("purchase_no",$purchase->purchase_no)
            ->get();

            $po_receive_item=purchase_receive_item::select("purchase_receive_item.*","product.product_name", "product.value1", "product.value2")
            ->leftJoin("product","product.id","purchase_receive_item.item")
            ->where("purchase_receive_item.purchase_no",$purchase->purchase_no)
            ->get();
            //dd($po_receive_item);

            return view("admin.purchase.po_receive_view")->with(['purchase'=>$purchase,'po_receive'=>$po_receive,'po_receive_item'=>$po_receive_item]);

    }
     function po_invoice_save(Request $request)
    {
        $purchase=purchase::find($request->id);
        $purchase->invoice_no=$request->invoice_no;
        $purchase->invoice_date=date('Y-m-d',strtotime($request->invoice_date));

        if($request->hasFile('invoice_file')) {
            $image = $request->file('invoice_file');
            $name =$request->file('invoice_file')->getClientOriginalName();
            $destinationPath = public_path('/po/invoice_file/');
            $image->move($destinationPath, $name);
            $purchase->invoice_file=$name;
        }
        if($purchase->save())
        {
            $po=purchase::find($purchase->id);
            $po->receive="Y";
            if($po->save())
            {
                $preceive=purchase_receive::where("purchase_no",$purchase->purchase_no)->first();
                $preceive->invoice_no=$request->invoice_no;
                $preceive->invoice_date=date('Y-m-d',strtotime($request->invoice_date));
                if($request->hasFile('invoice_file')) {
                    $image = $request->file('invoice_file');
                    $name =$request->file('invoice_file')->getClientOriginalName();
                    $preceive->invoice_file=$name;
                }
                $preceive->save();
            }

            return redirect()->route("admin.purchase.list")->with("message","Invoice Details save successfully");
        }else{
            return back();
        }


    }

    function po_invoice(Request $request)
    {
        $data=purchase::find($request->id);
        $preceive=purchase_receive::where("purchase_no",$data->purchase_no)->first();

        return view("admin.purchase.invoice_details")
        ->with(['data'=>$data,'preceive' => $preceive]);
    }
    function requirement_add(Request $request)
    {
        // Post/Redirect/Get. The purchase order form on this screen posts to
        // po_save, whose validation sends the user back() on failure - and back()
        // is a GET to whatever URL rendered the form. Rendering only from a GET
        // with the requirement and vendor in the query string gives that redirect
        // somewhere real to land, so a rejected purchase order comes back to its
        // own screen with the errors instead of to a bare, argument-less URL.
        if ($request->isMethod('post')) {
            return redirect()->route('admin.requirement.add', [
                'id' => $request->id,
                'vendor' => $request->vendor,
            ]);
        }

        // Everything below is built around one requirement, so it is looked up
        // first: without it the page used to fall over on a null further down,
        // which is what a refresh or a stale link produced.
        $list = purchase_requirement::select("purchase_requirement.*", "website_user.first_name", "website_user.last_name")
            ->leftJoin("website_user", "website_user.id", "purchase_requirement.user_id")
            ->where("purchase_requirement.id", $request->id)
            ->first();

        if (empty($list)) {
            return redirect()->route('admin.requirement.list')
                ->with('error', 'Pick a purchase requirement to raise a purchase order from.');
        }

        // The whole tax split below is computed against the chosen vendor, so it
        // has to exist. The form marks it required; arriving any other way (a
        // refresh, a copied link) used to reach a null and fall over.
        if (empty($request->vendor) || !vendor::where('id', $request->vendor)->exists()) {
            return redirect()->route('admin.requirement.view', ['id' => $list->id])
                ->with('error', 'Select a vendor to raise the purchase order against.');
        }

        $product = product::select('product.*', 'gst.gst_per', 'uom.uom_name',"product.product_image")
            ->leftJoin('gst', 'gst.id', 'product.gst')
            ->leftJoin('uom', 'uom.id', 'product.uom')
            ->where('product.status', 'product')
            ->orwhere('product.status', 'raw material')
            ->orderBy('product.product_name', 'asc')
            ->get();

        $service = product::select('product.*', 'gst.gst_per', 'uom.uom_name')
            ->leftJoin('gst', 'gst.id', 'product.gst')
            ->leftJoin('uom', 'uom.id', 'product.uom')
            ->where('product.status', 'service')
            ->orderBy('product.product_name', 'asc')
            ->get();

        $bom=product::select('product.*','gst.gst_per','uom.uom_name')
            ->leftJoin('gst','gst.id','product.gst')
            ->leftJoin('uom','uom.id','product.uom')
            ->where('product.status','bom')
            ->orderBy('product.product_name','asc')
            ->get();

        $solist = ['' => 'select salaesorder'] + salesorder::orderBy('salaesorder_no', 'desc')->get()->pluck('salaesorder_no', 'salaesorder_no')->toArray();
        $term = ['' => 'select terms'] + terms::query()
                ->get()->pluck('module', 'id')->toArray();
        $payment_terms = payment_terms::orderBy("terms_name", "asc")
            ->get();
        $pterms="";
        foreach ($payment_terms as $pt) {
            $pterms .= "<option value='" . $pt->days . "'>" . $pt->terms_name . "</option>";
        }

        $duedate = Date('d-m-Y', strtotime('+ 15 days'));

        $item=purchase_required_material::select("purchase_required_material.*","product.product_image","product.product_name", "product.value1", "product.value2","product.purchase_price","uom.uom_name","product.purchase_price","product.id as pid")
            ->leftJoin("product","product.id","purchase_required_material.raw_material")
            ->leftJoin('uom','uom.id','product.uom')
            ->where("purchase_required_material.order_id",$list->id)
            ->get();
        //dd($item);

        $srno=$allgst=0;
        $str="";
    foreach($item as $details)
    {
      $srno++;
            //echo $values;
      $item=product::select('product.*','gst.gst_per','uom.uom_name',"stock_status.qty as stockqty")
      ->leftJoin('gst','gst.id','product.gst')
      ->leftJoin('uom','uom.id','product.uom')
      ->leftJoin('stock_status','stock_status.product','product.id')
      ->where('product.id',$details->raw_material)
      ->first();
        $discper=0;
        $discamount=0;
        $qty=$details->qty;
        $productprice=$item->purchase_price*$qty;
        $price=$item->purchase_price;
        $company=company::select('company.*','state.state_name','state.state_code')
            ->leftJoin("state","state.id","company.state")
            ->first();

        $customer=vendor::select('vendor.*','state.state_name','state.state_code')
            ->leftJoin("state","state.id","vendor.billing_state")
            ->where("vendor.id",$request->vendor)
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
      <option value="'.$item->id.'">'.\App\product::nameWithVariantInline($item->product_name, $item->value1 ?? null, $item->value2 ?? null).'</option>';
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
                                                <input oninput="cal(this)" name="price[]" value="'.$item->purchase_price.'" type="text" data-rule-required="true" data-rule-positive="true" class="price listPrice smallInputBox inputElement" data-is-price-changed="false" list-info="" data-base-currency-id="" aria-required="true" autocomplete="off" aria-invalid="false">&nbsp;<span class="priceBookPopup cursorPointer" data-popup="Popup" title="Price Books" data-module-name="PriceBooks" style="float:left">
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


        // The vendor was already chosen on the requirement screen, and the
        // purchase order below is priced and taxed against it - so it is handed
        // to the form as the selected one rather than asked for a second time.
        $selectedVendor = vendor::find($request->vendor);

        return view("admin.purchase/po_add")
            ->with(["str"=>$str,'requirement_details'=>$list,'item'=>$item,'payment_terms'=>$pterms,'duedate'=>$duedate,'bom'=>$bom,'selectedVendor' => $selectedVendor, 'product' => $product, 'service' => $service, 'terms' => $term, 'solist' => $solist]);

    }
    function requirement_view(Request $request)
    {
        $list=purchase_requirement::select("purchase_requirement.*","website_user.first_name","website_user.last_name")
            ->leftJoin("website_user","website_user.id","purchase_requirement.user_id")
            ->orderBy("purchase_requirement.id","desc")
            ->where("purchase_requirement.id",$request->id)
            ->first();

        $item=purchase_required_material::select("purchase_required_material.*","product.product_name", "product.value1", "product.value2","uom.uom_name")
            ->leftJoin("product","product.id","purchase_required_material.raw_material")
            ->leftJoin('uom','uom.id','product.uom')
            ->where("purchase_required_material.order_id",$list->id)
            ->get();

        $vendor=[''=>'select vendor']+vendor::orderBy("vendor_name",'asc')
        ->get()
        ->pluck("vendor_name","id")
        ->toArray();

       $count=$list->po_no ?? 0;
       $status=0;
       if($count)
       {
        $status=1;
       }

        return view("admin.purchase.requirement_view",compact("list","item",'status',"vendor"));
    }
    function requirement_list(Request $request)
    {
        $list=new purchase_requirement();
        $list=$list->select("purchase_requirement.*","website_user.first_name","website_user.last_name");
        $list=$list->leftJoin("website_user","website_user.id","purchase_requirement.user_id");
        if(isset($request->request_no))
        {
            $list=$list->where("order_no",$request->request_no);
        }
        if(isset($request->from_date) && isset($request->to_date))
        {
            $from_date=date('Y-m-d',strtotime($request->from_date));
            $to_date=date('Y-m-d',strtotime($request->to_date));

            $list = $list->whereBetween('date',[$from_date,$to_date]);
        }
        if(isset($request->user))
        {
            $list=$list->where("website_user.first_name",'like','%'.$request->user.'%');
            $list=$list->where("website_user.last_name",'like','%'.$request->user.'%');
        }
        if(isset($request->purchase_no))
        {
            $list=$list->where("purchase_requirement.po_no",'like','%'.$request->purchase_no.'%');
        }
        // Socks and belt production both raise requirements into this one list.
        if($request->module != '')
        {
            $list=$list->where("purchase_requirement.module",$request->module);
        }
        $list=$list->orderBy("purchase_requirement.id","desc");
        $list=$list->get();

        return view("admin.purchase.requirement_list",compact("list"));
    }
    function get_product(Request $request)
    {

        $data=product::select('product.*','gst.gst_per','uom.uom_name',"stock_status.qty as stockqty")
            ->leftJoin('gst','gst.id','product.gst')
            ->leftJoin('uom','uom.id','product.uom')
            ->leftJoin('stock_status','stock_status.product','product.id')
            ->orderBy('product_name','asc')
            ->where('product.id',$request->product)
            ->first();

        $sub_product=bom_sub_product::select('product.product_name', 'product.value1', 'product.value2')
            ->leftJoin('product','product.id','bom_sub_product.product')
            ->where('bom_sub_product.bom_id',$data->id)
            ->get();

        $str=$data->description;

        // dd(htmlentities($str));
        $quotation=quotation::where('customer',$request->customer)
            ->orderBy('quotation_no','desc')
            ->first();


            $hsn=$data->hsn ?? '';

            $productprice=$data->purchase_price;
            $discper=0;


        $company=company::select('company.*','state.state_name','state.state_code')
            ->leftJoin("state","state.id","company.state")
            ->first();
        //dd($company);
        $customer=vendor::select('vendor.*','state.state_name','state.state_code')
            ->leftJoin("state","state.id","vendor.billing_state")
            ->where("vendor.id",$request->customer)
            ->first();
        //dd($customer);
        $igst=$cgstper=$sgstper=0;
        if($customer->tax_preference=="true")
        {
            if($company->state_code == $customer->state_code)
            {
                $igst=0;
                $cgstper=$data->gst_per/2;
                $sgstper=$data->gst_per/2;
            }else{
                $igst=$data->gst_per;
                $cgstper=0;
                $sgstper=0;
            }
        }
        //dd($productprice);
        //dd($data);
        if(empty($data))
        {
            $user[]="";
        }else{
            $productImage = asset('/product_image/'. $data->product_image);
            $user[]=array("stockqty"=>$data->stockqty ?? 0,'product_name'=>$data->product_name,'price'=>$productprice,'gst'=>$igst,'sgst'=>$sgstper,'cgst'=>$cgstper,'uom'=>$data->uom_name,'description'=>$str,'product_image'=>$productImage,'outer_diameter'=>$data->outer_diameter,'inner_diameter'=>$data->inner_diameter,'thikness'=>$data->thikness,'hsn'=>$hsn,'discper'=>$discper);
        }
        //dd($user);
        return json_encode($user);
    }
    public function stock_book(Request $request)
    {
        $query = stock_book::query()
            ->select('stock_book.*', 'product.product_name', 'product.value1', 'product.value2', 'customers.customer_name', 'vendor.vendor_name')
            ->leftJoin('product', 'product.id', '=', 'stock_book.product')
            ->leftJoin('customers', 'customers.id', '=', 'stock_book.customer')
            ->leftJoin('vendor', 'vendor.id', '=', 'stock_book.vendor');

        // 🔹 Dynamic filters
        $filters = [
            'inward_from'   => 'stock_book.inward_from',
            'inward_no'     => 'stock_book.inward_no',
            'vendor_name'   => 'vendor.vendor_name',
            'inward_type'   => 'stock_book.inward_type',
            'customer_name' => 'customers.customer_name',
            'product_name'  => 'product.product_name',
            'inward_qty'    => 'stock_book.inward_qty',
            'outward_qty'   => 'stock_book.outward_qty',
            'remaining_qty' => 'stock_book.remaining_qty',
            'particular' => 'stock_book.particular',
        ];

        foreach ($filters as $input => $column) {
            if ($request->filled($input)) {
                if (in_array($input, ['remaining_qty', 'inward_qty', 'outward_qty'])) {
                    $query->where($column, $request->$input);
                } else {
                    if($input == 'particular'){
                        $query->whereRaw("REPLACE(stock_book.particular, CHAR(10), '') LIKE ?", ["%{$request->particular}%"]);
                    } else {
                        $query->where($column, 'like', '%' . $request->$input . '%');
                    }
                }
            }
        }


        // 🔹 Date filter (from_date and to_date)
        if ($request->filled('from_date') && $request->filled('to_date')) {
            $from = date('Y-m-d', strtotime($request->from_date));
            $to   = date('Y-m-d', strtotime($request->to_date));
            $query->whereBetween('stock_book.inward_date', [$from, $to]);
        } elseif ($request->filled('from_date')) {
            $from = date('Y-m-d', strtotime($request->from_date));
            $query->whereDate('stock_book.inward_date', '>=', $from);
        } elseif ($request->filled('to_date')) {
            $to = date('Y-m-d', strtotime($request->to_date));
            $query->whereDate('stock_book.inward_date', '<=', $to);
        }

        $status = $query->orderByDesc('stock_book.id')->paginate(session('records_per_page', 30));

        return view("admin.stock_book", ['data' => $status]);
    }


    function inward_delete(Request $request)
    {
        $inwarditem=inward_item::find($request->id);

        $received_qty=$inwarditem->received_qty;

        $stockstatus=stock_status::select('qty')
            ->where('product',$request->product)
            ->first();

        $stockqty=$stockstatus->qty-$received_qty;

        DB::table('stock_status')
            ->where('product',$request->product)
            ->update(['qty'=>$stockqty]);

        if($inwarditem->delete())
        {
            return back()->with('message','Inward delete successfully');
        }

    }

    function stock_status(Request $request)
    {
        $status=new stock_status();
        $status=$status->select('stock_status.*','product.product_name','product.value1','product.value2');
        $status=$status->leftJoin('product','product.id','stock_status.product');
        if(isset($request->product_name))
        {
            $status=$status->where('product.product_name','like','%'.$request->product_name.'%');
        }
        if(isset($request->qty))
        {
            $status=$status->where('stock_status.qty',$request->qty);
        }
         $status=$status->orderBy('product.product_name','asc');
         $status=$status->paginate(session('records_per_page', 30));

        return view("admin.stock_status")
            ->with(['data'=>$status]);
    }

    function inward_normal_update(Request $request)
    {
            //dd($request->all());
            $inwarditem=inward_item::find($request->id);
            $total_qty=$inwarditem->total_qty;
            $received_qty=$inwarditem->received_qty;
            $remaining_qty=$inwarditem->remaining_qty;

            $inwarditem->product=$request->product;
            $inwarditem->total_qty=$request->qty;
            $inwarditem->received_qty=$request->received;
            $inwarditem->remaining_qty=$request->remaining;
            $inwarditem->vendor=$request->vendor;
            $inwarditem->customer=$request->customer;

            if($inwarditem->save())
            {

                $inward=inward::where('inward_no',$inwarditem->inward_no)->first();
                $inward->vendor=$request->vendor;
                $inward->customer=$request->customer;
                $inward->inward_type=$request->inward_type;
                $inward->inward_from=$request->inward_from;
                $inward->inward_date=date('Y-m-d',strtotime($request->inward_date));
                $inward->purchase=$request->purchase;
                $inward->subject=$request->subject;
                $inward->remark=$request->remark;
                $inward->created_time=date('d-m-Y h:i:s a');
                $inward->user_id=Session::get('user_id');
                $inward->website_id=Session::get('website_id');
                $inward->save();

                $stockstatus=stock_status::where('product',$request->product)
                    ->first();
                $stockqty=$stockstatus->qty-$received_qty;
                $newqty=$stockqty+$request->received;


                DB::table('stock_status')
                    ->where('product',$request->product)
                    ->update(['qty'=>$newqty]);

                $stock_book=new stock_book();
                $stock_book->inward_id=$inwarditem->inward_id;
                $stock_book->inward_no=$inwarditem->inward_no;
                $stock_book->vendor=$request->vendor;
                $stock_book->customer=$request->customer;
                $stock_book->inward_type=$request->inward_type;
                $stock_book->inward_from=$request->inward_from;
                $stock_book->inward_date=date('Y-m-d',strtotime($request->inward_date));
                $stock_book->subject=$request->subject;
                $stock_book->remark=$request->remark;
                $stock_book->product=$request->product;
                $stock_book->inward_qty=$request->received ?? '0';
                $stock_book->remaining_qty=$request->remaining ?? '0';
                $stock_book->particular="Inward Stock Update";
                $stock_book->created_time=date('d-m-Y h:i:s a');
                $stock_book->purchase_no=$request->purchase;
                $stock_book->user_id=Session::get('user_id');
                $stock_book->website_id=Session::get('website_id');
                $stock_book->save();

                return redirect()->route("normal/inward")->with("message","inward update successfully");
            }


    }
    function inward_edit(Request $request)
    {

        $item="";

        $vendor=[''=>'select vendor']+vendor::orderBy('vendor_name','asc')
                ->get()->pluck('vendor_name','id')->toArray();

        $customer=[''=>'select customer']+customers::orderBy('customer_name','asc')
                ->get()->pluck('customer_name','id')->toArray();


        $item=inward_item::select('inward_item.*','product.product_name', 'product.value1', 'product.value2')
                ->leftJoin('product','product.id','inward_item.product')
                ->where("inward_item.id",$request->id)
                ->first();
            //dd($item);

        $data=inward::where('inward_number',$item->inward_no)->first();

        if(empty($data->purchase))
        {
            return view("admin.inward_normal_edit_1")->with(['data'=>$data,'item'=>$item,'vendor'=>$vendor,'customer'=>$customer]);
        }else{
            return view("admin.inward_normal_edit")->with(['data'=>$data,'item'=>$item,'vendor'=>$vendor,'customer'=>$customer]);

        }
    }

    function inward_list(Request $request)
    {
        $inward=new inward_item();
        $inward=$inward->select('inward_item.*','product.product_name', 'product.value1', 'product.value2','customers.customer_name','vendor.vendor_name');
        $inward=$inward->leftJoin('product','product.id','inward_item.product');
        $inward=$inward->leftJoin('customers','customers.id','inward_item.customer');
        $inward=$inward->leftJoin('vendor','vendor.id','inward_item.vendor');

        if(isset($request->inward_from))
        {
            $inward=$inward->WHERE('inward_item.inward_from','like','%'.$request->inward_from.'%');
        }
        if(isset($request->product_name))
        {
            $inward=$inward->WHERE('product.product_name','like','%'.$request->product_name.'%');
        }
        if(isset($request->inward_no))
        {
            $inward=$inward->WHERE('inward_item.inward_no','like','%'.$request->inward_no.'%');
        }
        if(isset($request->customer_name))
        {
            $inward=$inward->WHERE('customers.customer_name','like','%'.$request->customer_name.'%');
        }
        if(isset($request->vendor_name))
        {
            $inward=$inward->WHERE('vendor.vendor_name','like','%'.$request->vendor_name.'%');
        }
        if(isset($request->inward_type))
        {
            $inward=$inward->WHERE('inward_item.inward_type','like','%'.$request->inward_type.'%');
        }

        $inward=$inward->orderBy('inward_item.id','desc');

        $result=$inward->paginate(session('records_per_page', 30));
        return view("admin/inward_list")->with(['data'=>$result]);
    }

    function inward_save(Request $request)
    {
        date_default_timezone_set('Asia/Kolkata');

        $qno = inward::query()
            ->max('inward_no');

        if (empty($qno)) {
            $year = date("Y");
            $nextyear = $year + 1;

            $n2 = str_pad($qno + 1, 4, 0, STR_PAD_LEFT);
            $qno = 0;
            $n2 .= '/';
            $n2 .= $year;
            $n2 .= '-';
            $n2 .= $nextyear;
        } else {
            $year = date("Y");
            $nextyear = $year + 1;
            $n2 = str_pad($qno + 1, 4, 0, STR_PAD_LEFT);
            $n2 .= '/';
            $n2 .= $year;
            $n2 .= '-';
            $n2 .= $nextyear;
        }
        $vendor_name = vendor::find($request->vendor);
        $inward=new inward();
        $inward->inward_no = $qno + 1;
        $inward->inward_number = $n2;
        $inward->vendor=$request->vendor;
        $inward->customer=$request->customer;
        $inward->inward_type=$request->inward_type;
        $inward->inward_from=$request->inward_from;
        $inward->inward_date=date('Y-m-d',strtotime($request->inward_date));
        $inward->purchase=$request->purchase;

        $inward->subject=$request->subject;
        $inward->remark=$request->remark;
        $inward->created_time=date('d-m-Y h:i:s a');
        $inward->user_id=Session::get('user_id');
        $inward->website_id=Session::get('website_id');
        if($inward->save()) {

//            DB::table('purchase')
//                ->where('purchase_no',$request->purchase)
//                ->update(['inward_status'=>1]);

            $totinward=count($request->product);
            for($i=0;$i<$totinward;$i++)
            {
                if(empty($request->product[$i]))
                {}else{
                    $inward_item=new inward_item();
                    $inward_item->inward_id=$qno + 1;
                    $inward_item->inward_no=$n2;
                    $inward_item->vendor=$request->vendor;
                    $inward_item->customer=$request->customer;
                    $inward_item->inward_type=$request->inward_type;
                    $inward_item->inward_from=$request->inward_from;
                    $inward_item->product=$request->product[$i];
                    $inward_item->total_qty=$request->qty[$i] ?? '0';
                    $inward_item->received_qty=$request->received[$i] ?? '0';
                    $inward_item->remaining_qty=$request->remaining[$i] ?? '0';
                    $inward_item->created_time=date('d-m-Y h:i:s a');
                    $inward_item->save();
                }

            }

            for($i=0;$i<$totinward;$i++)
            {
                if(empty($request->product[$i]))
                {}else{
                    $checkproduct=stock_status::WHERE('product',$request->product[$i])->first();
                    if(empty($checkproduct))
                    {
                        $stock_status=new stock_status();
                        $stock_status->inward_id=$qno + 1;
                        $stock_status->inward_no=$n2;
                        $stock_status->vendor=$request->vendor;
                        $stock_status->customer=$request->customer;
                        $stock_status->inward_type=$request->inward_type;
                        $stock_status->inward_from=$request->inward_from;
                        $stock_status->inward_date=date('Y-m-d',strtotime($request->inward_date));
                        $stock_status->subject=$request->subject;
                        $stock_status->remark=$request->remark;
                        $stock_status->product=$request->product[$i];
                        $stock_status->qty=$request->received[$i];
                        $stock_status->particular="Inward Stock";
                        $stock_status->created_time=date('d-m-Y h:i:s a');
                        $stock_status->purchase_no=$request->purchase;
                        $stock_status->user_id=Session::get('user_id');
                        $stock_status->website_id=Session::get('website_id');
                        $stock_status->save();
                    }else{
                        $qty=$checkproduct->qty+$request->received[$i];
                        DB::table('stock_status')
                            ->WHERE('product',$request->product[$i])
                            ->UPDATE(['qty'=>$qty,'created_time'=>date('d-m-Y h:i:s a')]);
                    }

                    $stock_book=new stock_book();
                    $stock_book->inward_id=$qno + 1;
                    $stock_book->inward_no=$n2;
                    $stock_book->vendor=$request->vendor;
                    $stock_book->customer=$request->customer;
                    $stock_book->inward_type=$request->inward_type;
                    $stock_book->inward_from=$request->inward_from;
                    $stock_book->inward_date=date('Y-m-d',strtotime($request->inward_date));
                    $stock_book->subject=$request->subject;
                    $stock_book->remark=$request->remark;
                    $stock_book->product=$request->product[$i];
                    $stock_book->inward_qty=$request->received[$i] ?? '0';
                    $stock_book->remaining_qty=$request->remaining[$i] ?? '0';
                    $stock_book->particular="Inward Stock";
                    $stock_book->created_time=date('d-m-Y h:i:s a');
                    $stock_book->purchase_no=$request->purchase;
                    $stock_book->user_id=Session::get('user_id');
                    $stock_book->website_id=Session::get('website_id');
                    $stock_book->save();
                }


            }
            return redirect()->route("normal/inward")->with("message","inward save successfully");
        }
    }
    function get_po_item(Request $request)
    {
        $poitem = purchase_item::SELECT('purchase_item.*', 'product.product_name', 'product.value1', 'product.value2','product.product_image', 'product.material_name', 'category.category_image')
            ->leftJoin('product', 'product.id', 'purchase_item.product')
            ->leftJoin('category', 'category.id', 'product.category')
            ->where('purchase_item.pono', $request->purchase_no)
            ->get();

        $str='<div class="tabledata">
                                     <table class="table table-striped add-edit-table table-bordered" id="caltable">
                                        <thead>
                                            <tr>
                                                 <th style="text-align: center;">Product Name</th>
                                                 <th style="text-align: center;">Total Qty</th>
                                                 <th style="text-align: center;">Received Qty</th>
                                                 <th style="text-align: center;">Remaining Qty</th>

                                            </tr>
                                        </thead>
                                        <tbody>';

                                            $srno=$total=$gsttotal=$grand=$discount_total=0;

                                            foreach($poitem as $item) {
                                                $srno++;

                                                $total = $total + $item->amount;
                                                $gsttotal = $gsttotal + $item->gst_amount;
                                                $grand = $grand + $item->grand_total;
                                                $discount_total = $discount_total + $item->discount_amount;

                                                $str .= '<tr id="row' . $srno . '">
                                                <td style="vertical-align: top !important;width: 50%">

     <select class="form-control" onchange="get_product(this.value,' . $srno . ')" name="product[]" id="product' . $srno . '">
                                                        <option value="' . $item->product . '">' . \App\product::nameWithVariantInline($item->product_name, $item->value1 ?? null, $item->value2 ?? null) . '</option>';

                                                $str .= '</select>


  <div class="form-group">
       <label>  </label>
       <textarea id="description' . $srno . '" name="description[]" class="form-control">
       ' . $item->description . '
       </textarea>
  </div>
 </td>
<td style="vertical-align: top !important;text-align: center;">
   <input type="text" readonly name="qty[]" onkeyup="cal(this)" value="' . $item->qty . '" class="qty form-control" id="qty' . $srno . '">
</td>
<td style="vertical-align: top !important;text-align: center;">
   <input type="text" name="received[]" onkeyup="cal(this)" value="0" class="received form-control" id="qty' . $srno . '">
</td>
<td style="vertical-align: top !important;text-align: center;">
   <input type="text" name="remaining[]" onkeyup="cal(this)" value="' . $item->qty . '" class="remaining form-control" id="qty' . $srno . '">
</td>
</tr>
                                      ';
                                            }


                                    $str .='</tbody>

                                    </table>

                                </div>';
                                            return $str;
    }

    function get_po(Request  $request)
    {
        $po=purchase::SELECT('purchase_no')->WHERE('vendor',$request->vendor)
            ->orderBy('purchase_no','desc')
            ->get();
        $str='<option value="">select po</option>';
        foreach ($po as $pono)
        {
            $str .='<option value="'.$pono->purchase_no.'">'.$pono->purchase_no.'</option>';
        }
        return $str;
    }
    function inward_add(Request $request)
    {
        $vendor=[''=>'select vendor']+vendor::orderBy('vendor_name','asc')
            ->get()->pluck('vendor_name','id')->toArray();

        $customer=[''=>'select customer']+customers::orderBy('customer_name','asc')
                ->get()->pluck('customer_name','id')->toArray();

        $product1=product::orderBy('product_name','asc')
        ->get();

        $bom=product::orderBy('product_name','asc')
        ->where("status","bom")
        ->get();

        return view("admin/inward/inward_add",compact('vendor','product1','customer','bom'));
    }
    function po_normal_mail_send(Request $request)
    {
        $po = purchase::select('website_user.user_name','purchase.*', 'vendor.*','vendor_contact.contact_name','vendor_contact.primary_phone')
            ->leftJoin('vendor', 'vendor.id', 'purchase.vendor')
            ->leftJoin('vendor_contact', 'vendor_contact.id', 'purchase.contact_name')
            ->leftJoin('website_user', 'website_user.id', 'purchase.user_id')
            ->where('purchase.id', $request->poid)
            ->first();
        $vendor=vendor::WHERE('id',$po->vendor)->first();

        $poitem = purchase_item::SELECT('purchase_item.*', 'product.product_name', 'product.value1', 'product.value2','product.product_image', 'product.material_name', 'category.category_image')
            ->leftJoin('product', 'product.id', 'purchase_item.product')
            ->leftJoin('category', 'category.id', 'product.category')
            ->where('purchase_item.poid', $po->pono)
            ->get();

        $discsum = purchase_item::SELECT('purchase_item.*', 'product.product_name', 'product.value1', 'product.value2', 'product.product_image', 'product.material_name', 'category.category_image')
            ->leftJoin('product', 'product.id', 'purchase_item.product')
            ->leftJoin('category', 'category.id', 'product.category')
            ->where('purchase_item.poid', $po->pono)
            ->sum('purchase_item.discount_amount');
        //dd($quotitem);
        $company = company::query()->first();

        $terms = terms::query()->first();

        $filename = $po->vendor_name;
        $filename .= '.pdf';

        $data = array('body'=>$request->to_body);


        $pdf = PDF::loadView('admin.po_normal_print', compact('po','poitem','company','discsum','vendor'));

        $array=explode(',',$request->to_email);
        $tocc=explode(',',$request->to_cc);
        //$body=$request
        //dd($content);
        $subject=$request->to_subject;

        Mail::send('emails.purchase', $data, function ($message) use ($pdf,$filename,$subject,$array,$tocc) {
            $message->from('erp@nowtowow.co.in', 'Purchase');
            foreach($array as $val)
            {
                $message->to($val)->subject($subject);
            }
            if(empty($tocc))
            {}else{
                foreach($tocc as $val1)
                {
                    if(empty($val1))
                    {}else{
                        $message->cc($val1)->subject($subject);
                    }

                }
            }

            $message->attachData($pdf->output(),$filename);
        });

        return back()->with("message","Mail Send Successfully");
    }
    function po_n_pdf(Request $request)
    {
        $po = purchase::select('website_user.user_name','purchase.*', 'vendor.*','vendor_contact.contact_name','vendor_contact.primary_phone')
            ->leftJoin('vendor', 'vendor.id', 'purchase.vendor')
            ->leftJoin('vendor_contact', 'vendor_contact.id', 'purchase.contact_name')
            ->leftJoin('website_user', 'website_user.id', 'purchase.user_id')
            ->where('purchase.id', $request->id)
            ->first();

        $vendor=vendor::where('id',$po->vendor)->first();

        $poitem = purchase_item::select('purchase_item.*', 'product.product_name', 'product.value1', 'product.value2','product.product_image', 'product.material_name', 'category.category_image')
            ->leftJoin('product', 'product.id', 'purchase_item.product')
            ->leftJoin('category', 'category.id', 'product.category')
            ->where('purchase_item.poid', $po->id)
            ->get();

        $discsum = purchase_item::select('purchase_item.*', 'product.product_name', 'product.value1', 'product.value2', 'product.product_image', 'product.material_name', 'category.category_image')
            ->leftJoin('product', 'product.id', 'purchase_item.product')
            ->leftJoin('category', 'category.id', 'product.category')
            ->where('purchase_item.poid', $po->pono)
            ->sum('purchase_item.discount_amount');
        //dd($quotitem);
        $company = company::query()->first();

        $terms = terms::query()->first();

        $filename = $po->vendor_name;
        $filename .= '.pdf';

         $pdf = PDF::loadView('admin.po_normal_print', compact('po','poitem','company','discsum','vendor'));

         return $pdf->download($filename);

//        return view('admin.po_normal_print')->with(['po' => $po, 'poitem' => $poitem, 'company' => $company, 'discsum' => $discsum,'vendor'=>$vendor]);

    }

    function po_pdf(Request $request)
    {
        $po = purchase::select('website_user.user_name','purchase.*', 'vendor.*','vendor_contact.contact_name','vendor_contact.primary_phone as vprimephone')
            ->leftJoin('vendor', 'vendor.id', 'purchase.vendor')
            ->leftJoin('vendor_contact', 'vendor_contact.id', 'purchase.contact_name')
            ->leftJoin('website_user', 'website_user.id', 'purchase.user_id')
            ->where('purchase.id', $request->id)
            ->first();

        $vendor=vendor::where('id',$po->vendor)->first();

        $poitem = purchase_item::select('purchase_item.*', 'product.product_name','product.product_image', 'product.material_name', 'product.value1', 'product.value2', 'category.category_image',"uom.uom_name")
            ->leftJoin('product', 'product.id', 'purchase_item.product')
            ->leftJoin('uom','uom.id','product.uom')
            ->leftJoin('category', 'category.id', 'product.category')
            ->where('purchase_item.pono', $po->purchase_no)
            ->get();

        $discsum = purchase_item::select('purchase_item.*', 'product.product_name', 'product.value1', 'product.value2', 'product.product_image', 'product.material_name', 'category.category_image')
            ->leftJoin('product', 'product.id', 'purchase_item.product')
            ->leftJoin('category', 'category.id', 'product.category')
            ->where('purchase_item.pono', $po->purchase_no)
            ->sum('purchase_item.discount_amount');
        //dd($quotitem);
        $company = company::query()->first();

        $terms = terms::query()->first();

        $filename = $po->vendor_name;
        $filename .=date('ymdhis');
        $filename .= '.pdf';
        $quot=$po;
        $quotitem=$poitem;
        $pdf = PDF::loadView('admin.po_print', compact('quot','quotitem','company','discsum','vendor'));

        return $pdf->download($filename);

//        return view('admin.po_print')->with(['po' => $po, 'poitem' => $poitem, 'company' => $company, 'discsum' => $discsum,'vendor'=>$vendor]);

    }

    function po_n_preview(Request $request)
    {
        $po = purchase::select('website_user.user_name','purchase.*', 'vendor.*','vendor_contact.contact_name','vendor_contact.primary_phone')
            ->leftJoin('vendor', 'vendor.id', 'purchase.vendor')
            ->leftJoin('vendor_contact', 'vendor_contact.id', 'purchase.contact_name')
            ->leftJoin('website_user', 'website_user.id', 'purchase.user_id')
            ->where('purchase.id', $request->id)
            ->first();

        $vendor=vendor::where('id',$po->vendor)->first();

        $poitem = purchase_item::select('purchase_item.*', 'product.product_name','product.product_image', 'product.material_name', 'product.value1', 'product.value2', 'category.category_image')
            ->leftJoin('product', 'product.id', 'purchase_item.product')
            ->leftJoin('category', 'category.id', 'product.category')
            ->where('purchase_item.pono', $po->purchase_no)
            ->get();

        $discsum = purchase_item::select('purchase_item.*', 'product.product_name', 'product.value1', 'product.value2', 'product.product_image', 'product.material_name', 'category.category_image')
            ->leftJoin('product', 'product.id', 'purchase_item.product')
            ->leftJoin('category', 'category.id', 'product.category')
            ->where('purchase_item.pono', $po->purchase_no)
            ->sum('purchase_item.discount_amount');
        //dd($quotitem);
        $company = company::query()->first();

        $terms = terms::query()->first();

        $filename = $po->vendor_name;
        $filename .= '.pdf';
        $quot=$po;
        $quotitem=$poitem;
        $pdf = PDF::loadView('admin.po_print', compact('quot','quotitem','company','discsum','vendor'));

        $content = $pdf->download()->getOriginalContent();
        Storage::put('public/purchase/pdf/'.$filename,$content) ;

        $url=url('/storage/app/public/purchase/pdf/'.$filename);
        return Redirect::to($url);
       // return $pdf->download($filename);

    }

    function po_preview(Request $request)
    {
        $po = purchase::select('website_user.user_name','purchase.*', 'vendor.*','vendor_contact.contact_name as contactname','vendor_contact.primary_phone as vprimephone',"vendor.owner_gst")
            ->leftJoin('vendor', 'vendor.id', 'purchase.vendor')
            ->leftJoin('vendor_contact', 'vendor_contact.id', 'purchase.contact_name')
            ->leftJoin('website_user', 'website_user.id', 'purchase.user_id')
            ->where('purchase.id', $request->id)
            ->first();

        $vendor=vendor::where('id',$po->vendor)->first();

        $poitem = purchase_item::select('purchase_item.*', 'product.product_name','product.product_image', 'product.material_name', 'product.value1', 'product.value2', 'category.category_image',"uom.uom_name")
            ->leftJoin('product', 'product.id', 'purchase_item.product')
            ->leftJoin('uom','uom.id','product.uom')
            ->leftJoin('category', 'category.id', 'product.category')
            ->where('purchase_item.pono', $po->purchase_no)
            ->get();

        $discsum = purchase_item::select('purchase_item.*', 'product.product_name', 'product.value1', 'product.value2', 'product.product_image', 'product.material_name', 'category.category_image')
            ->leftJoin('product', 'product.id', 'purchase_item.product')
            ->leftJoin('category', 'category.id', 'product.category')
             ->where('purchase_item.pono', $po->purchase_no)
            ->sum('purchase_item.discount_amount');
        //dd($quotitem);
        $company = company::query()->first();

        $terms = terms::query()->first();

        $filename = $po->vendor_name;
        $filename .=date('ymdhis');
        $filename .= '.pdf';
        $quot=$po;
        $quotitem=$poitem;
        $pdf = PDF::loadView('admin.po_print', compact('quot','quotitem','company','discsum','vendor'));

        $content = $pdf->download()->getOriginalContent();
        Storage::put('public/purchase/pdf/'.$filename,$content) ;

        $url=url('/storage/app/public/purchase/pdf/'.$filename);
        return Redirect::to($url);
    }

    function po_view(Request $request)
    {
        $po = purchase::select('purchase.*','website_user.user_name','vendor_contact.contact_name as contactname','vendor_contact.primary_phone',"vendor.owner_gst")
            ->leftJoin('vendor', 'vendor.id', 'purchase.vendor')
            ->leftJoin('vendor_contact', 'vendor_contact.id', 'purchase.contact_name')
            ->leftJoin('website_user', 'website_user.id', 'purchase.user_id')
            ->where('purchase.id', $request->id)
            ->first();

        $vendor=vendor::where('id',$po->vendor)->first();

        $poitem = purchase_item::select('purchase_item.*', 'product.product_name','product.product_image', 'product.material_name', 'product.value1', 'product.value2', 'category.category_image',"uom.uom_name")
            ->leftJoin('product', 'product.id', 'purchase_item.product')
            ->leftJoin('uom', 'uom.id', 'product.uom')
            ->leftJoin('category', 'category.id', 'product.category')
            ->where('purchase_item.pono', $po->purchase_no)
            ->get();

        $discsum = purchase_item::select('purchase_item.*', 'product.product_name', 'product.value1', 'product.value2', 'product.product_image', 'product.material_name', 'category.category_image')
            ->leftJoin('product', 'product.id', 'purchase_item.product')
            ->leftJoin('category', 'category.id', 'product.category')
             ->where('purchase_item.pono', $po->purchase_no)
            ->sum('purchase_item.discount_amount');
        //dd($quotitem);
        $company = company::query()->first();

        $terms = terms::query()->first();

        $filename = $po->customer_name;
        $filename .= '.pdf';

        // $pdf = PDF::loadView('admin.quotation_print', compact('quot','quotitem','company','terms'));

        // return $pdf->download($filename);

        return view('admin.po_view')->with(['po' => $po, 'poitem' => $poitem, 'company' => $company, 'discsum' => $discsum,'vendor'=>$vendor]);

    }

    function purchase_normal_update(Request $request)
    {
        $request->validate([
            'status' => 'required',
            'due_date' => 'required',
            'vendor' => 'required',
            'po_date' => 'required',
            'billing_address' => 'required',
            'shipping_address' => 'required',
            'billing_city' => 'required',
            'shipping_city' => 'required',
            'billing_state' => 'required',
            'shipping_state' => 'required',
            'billing_postalcode' => 'required',
            'shipping_postalcode' => 'required',
            'billing_country' => 'required',
            'shipping_country' => 'required',

        ]);

        $vendor_name = vendor::find($request->vendor);

        $quotation = purchase::find($request->id);

        $quotation->salesorder_no = $request->salesorder_no;
        $quotation->po_date = date('Y-m-d', strtotime($request->po_date));
        $quotation->subject = $request->subject;
        $quotation->vendor = $request->vendor;
        $quotation->vendor_name = $vendor_name->vendor_name;
        $quotation->status = $request->status;
        $quotation->due_date = date('Y-m-d', strtotime($request->due_date));
        $quotation->billing_address = $request->billing_address;
        $quotation->shipping_address = $request->shipping_address;
        $quotation->billing_postalcode = $request->billing_postalcode;

        $quotation->billing_city = $request->billing_city;
        $quotation->shipping_city = $request->shipping_city;
        $quotation->billing_state = $request->billing_state;
        $quotation->shipping_state = $request->shipping_state;
        $quotation->billing_postalcode = $request->billing_postalcode;
        $quotation->shipping_postalcode = $request->shipping_postalcode;
        $quotation->billing_country = $request->billing_country;
        $quotation->shipping_country = $request->shipping_country;
        $quotation->term_condition = $request->term_condition;
        $quotation->website_id = Session::get('website_id');
        $quotation->user_id = Session::get('user_id');
        $quotation->gst_amount = $request->gsttotal;
        $quotation->net_amount = $request->item_total;
        $quotation->discount_total = $request->discount_total;
        $quotation->grand_total = $request->grand_total;
        // $quotation->module=$request->module;
        $quotation->remark = $request->remark;
        if ($quotation->save()) {
            purchase_item::where('pono', $request->purchase_no)->delete();
            if (empty($request->product)) {
            } else {
                $totproduct = count($request->product);
                for ($i = 0; $i < $totproduct; $i++) {
                    $item = new purchase_item();
                    $item->poid = $request->pono;
                    $item->pono = $request->purchase_no;
                    $item->product = $request->product[$i];
                    $item->description = $request->description[$i];
                    $item->qty = $request->qty[$i];
                    $item->vendor = $request->vendor;
                    if (empty($request->inner_diamitter[$i])) {
                    } else {
                        $item->inner_diameter = $request->inner_diamitter[$i];
                    }
                    if (empty($request->outer_diamitter[$i])) {
                    } else {
                        $item->outer_diameter = $request->outer_diamitter[$i];
                    }
                    if (empty($request->thikness[$i])) {
                    } else {
                        $item->thikness = $request->thikness[$i];
                    }
                    if (empty($request->hsn[$i])) {
                    } else {
                        $item->hsn = $request->hsn[$i];
                    }
                    $item->price = $request->price[$i];
                    $item->total = $request->total_amount[$i];

                    $item->discount_per = $request->discount_per[$i];
                    $item->discount_amount = $request->discount_amount[$i];

                    $item->gst_per = $request->gst_per[$i];
                    $item->gst_amount = $request->gst_amount[$i];
                    $item->grand_total = $request->net_price[$i];
                    $item->save();
                }
            }


            return redirect()->route("purchase_list")->with('message', 'Purchase Update successfully');
        } else {
            return back();
        }

    }

    function po_update(Request $request)
    {
        $request->validate([
            'status' => 'required',
            'due_date' => 'required',
            'vendor' => 'required',
            'po_date' => 'required',
            'billing_address' => 'required',
            'shipping_address' => 'required',
            'billing_city' => 'required',
            'shipping_city' => 'required',
            'billing_state' => 'required',
            'shipping_state' => 'required',
            'billing_postalcode' => 'required',
            'shipping_postalcode' => 'required',
            'billing_country' => 'required',
            'shipping_country' => 'required',

        ]);

        $vendor_name = vendor::find($request->vendor);

        $quotation = purchase::find($request->id);

        $quotation->salesorder_no = $request->salesorder_no;
        $quotation->po_date = date('Y-m-d', strtotime($request->po_date));
        $quotation->subject = $request->subject;
        $quotation->vendor = $request->vendor;
        $quotation->vendor_name = $vendor_name->vendor_name;
        $quotation->contact_name = $request->contact_name;
        $quotation->status = $request->status;
        $quotation->due_date = date('Y-m-d', strtotime($request->due_date));
        $quotation->billing_address = $request->billing_address;
        $quotation->shipping_address = $request->shipping_address;
        $quotation->billing_postalcode = $request->billing_postalcode;

        $quotation->billing_city = $request->billing_city;
        $quotation->shipping_city = $request->shipping_city;
        $quotation->billing_state = $request->billing_state;
        $quotation->shipping_state = $request->shipping_state;
        $quotation->billing_postalcode = $request->billing_postalcode;
        $quotation->shipping_postalcode = $request->shipping_postalcode;
        $quotation->billing_country = $request->billing_country;
        $quotation->shipping_country = $request->shipping_country;
        $quotation->term_condition = $request->term_condition;
        $quotation->website_id = Session::get('website_id');
        $quotation->user_id = Session::get('user_id');
        $quotation->cgsttotal = $request->cgsttotal;
        $quotation->sgsttotal = $request->sgsttotal;
        $quotation->igsttotal = $request->igsttotal;
        $quotation->net_amount = $request->item_total;
        $quotation->discount_total = $request->discount_total;
        $quotation->grand_total = $request->grand_total;
        // $quotation->module=$request->module;
        $quotation->remark = $request->remark;
        $quotation->adjustment=$request->adjustment;
        if ($quotation->save()) {
            purchase_item::where('pono', $request->purchase_no)->delete();
            if (empty($request->product)) {
            } else {
                $totproduct = count($request->product);
                for ($i = 0; $i < $totproduct; $i++) {
//                    $item=purchase_item::where('poid',$quotation->pono)
//                        ->where("pono",$quotation->purchase_no)
//                        ->first();
//                    echo $quotation->purchase_no."<br>";
//                    echo $request->product[$i]."<br>";
                    $poreceive=purchase_receive_item::where('purchase_no',$quotation->purchase_no)
                        ->where("item",$request->product[$i])
                       ->sum("qty_received");
                   // dd($poreceive);
                    $remain=$request->qty[$i]-$poreceive;
                    $item = new purchase_item();
                    $item->poid = $quotation->pono;
                    $item->pono = $quotation->purchase_no;
                    $item->remain_qty=$remain;
                    $item->product = $request->product[$i];
                    $item->description = $request->description[$i];
                    $item->qty = $request->qty[$i];
                    $item->vendor = $request->vendor;
                    if (empty($request->inner_diamitter[$i])) {
                    } else {
                        $item->inner_diameter = $request->inner_diamitter[$i];
                    }
                    if (empty($request->outer_diamitter[$i])) {
                    } else {
                        $item->outer_diameter = $request->outer_diamitter[$i];
                    }
                    if (empty($request->thikness[$i])) {
                    } else {
                        $item->thikness = $request->thikness[$i];
                    }
                    if (empty($request->hsn[$i])) {
                    } else {
                        $item->hsn = $request->hsn[$i];
                    }
                    $item->price = $request->price[$i];
                    $item->total = $request->total_amount[$i];

                    $item->discount_per = $request->discount_per[$i];
                    $item->discount_amount = $request->discount_amount[$i];
                    $item->cgst_per = $request->cgst_per[$i];
                    $item->cgst_amount = $request->cgst_amount[$i];
                    $item->sgst_per = $request->sgst_per[$i];
                    $item->sgst_amount = $request->sgst_amount[$i];
                    $item->gst_per = $request->gst_per[$i];
                    $item->gst_amount = $request->gst_amount[$i];
                    $item->grand_total = $request->net_price[$i];
                    $item->save();
                }
            }
            return redirect()->route("admin.purchase.list")->with('message', 'Purchase Update successfully');
        } else {
            return back();
        }

    }

    function purchase_edit(Request $request)
    {
        $quot = purchase::where('id', $request->id)
            ->first();
        //dd($quot);
        $quotitem = purchase_item::select('purchase_item.*', 'product.product_name', 'product.value1', 'product.value2',"product.product_image", 'product.make', 'product.model')
            ->leftJoin('product', 'product.id', 'purchase_item.product')
            ->where('purchase_item.poid', $quot->pono)
            ->get();

        //dd($quotitem);

        $vendor = ['' => 'select customer'] + vendor::query()->orderBy('vendor_name', 'asc')
                ->get()
                ->pluck('vendor_name', 'id')
                ->toArray();

        if(empty($quot->contact_name))
        {
            $contact_name=[''=>'select contact']+vendor_contact::query()
                    ->where('vendor',$quot->vendor)
                    ->orderBy('contact_name','asc')
                    ->get()
                    ->pluck('contact_name','id')
                    ->toArray();
        }else{
            $cname=vendor_contact::select('id','contact_name')->where('id',$quot->contact_name)->first();

            $contact_name=[$cname->id=>$cname->contact_name]+vendor_contact::query()
                    ->where('vendor',$quot->vendor)
                    ->orderBy('contact_name','asc')
                    ->get()
                    ->pluck('contact_name','id')
                    ->toArray();
        }

        $product = product::select('product.*', 'gst.gst_per', 'uom.uom_name')
            ->leftJoin('gst', 'gst.id', 'product.gst')
            ->leftJoin('uom', 'uom.id', 'product.uom')
            ->where('product.status', 'product')
            ->orderBy('product.product_name', 'asc')
            ->get();

        $service = product::select('product.*', 'gst.gst_per', 'uom.uom_name')
            ->leftJoin('gst', 'gst.id', 'product.gst')
            ->leftJoin('uom', 'uom.id', 'product.uom')
            ->where('product.status', 'service')
            ->orderBy('product.product_name', 'asc')
            ->get();

        //$term=terms::where('website_id',Session::get('website_id'))->first();

        $module = terms::query()
            ->get()
            ->pluck('module', 'id')
            ->toArray();

            $bom = product::select('product.*', 'gst.gst_per', 'uom.uom_name')
            ->leftJoin('gst', 'gst.id', 'product.gst')
            ->leftJoin('uom', 'uom.id', 'product.uom')
            ->where('product.status', 'bom')
            ->orderBy('product.product_name', 'asc')
            ->get();

        $solist = ['' => 'select salaesorder'] + salesorder::orderBy('salaesorder_no', 'desc')->get()->pluck('salaesorder_no', 'salaesorder_no')->toArray();

        return view("admin.purchase_normal_edit")->with(['bom' => $bom,'contact_name'=>$contact_name,'data' => $quot, 'quotitem' => $quotitem, 'vendor' => $vendor, 'product' => $product, 'service' => $service, 'module' => $module, 'solist' => $solist]);
        //
    }

    function po_edit(Request $request)
    {
        $quot = purchase::where('id', $request->id)
            ->first();
        //dd($quot);
        $quotitem = purchase_item::select('purchase_item.*',"uom.uom_name","product.product_image", 'product.product_name', 'product.make', 'product.model', 'product.item_code', 'product.value1', 'product.value2')
            ->leftJoin('product', 'product.id', 'purchase_item.product')
            ->leftJoin('uom', 'uom.id', 'product.uom')
            ->where('purchase_item.pono', $quot->purchase_no)
            ->get();

        //dd($quotitem);

        $vendor = ['' => 'select customer'] + vendor::query()->orderBy('vendor_name', 'asc')
                ->get()
                ->pluck('vendor_name', 'id')
                ->toArray();

        if(empty($quot->contact_name))
        {
            $contact_name=[''=>'select contact']+vendor_contact::query()
                    ->orderBy('contact_name','asc')
                    ->get()
                    ->pluck('contact_name','id')
                    ->toArray();
        }else{
            $cname=vendor_contact::select('id','contact_name')
                ->where('id',$quot->contact_name)->first();

            $contact_name=[$cname->id=>$cname->contact_name]+vendor_contact::query()
                    ->orderBy('contact_name','asc')
                    ->get()
                    ->pluck('contact_name','id')
                    ->toArray();
        }

        $product = product::select('product.*', 'gst.gst_per', 'uom.uom_name')
            ->leftJoin('gst', 'gst.id', 'product.gst')
            ->leftJoin('uom', 'uom.id', 'product.uom')
            ->where('product.status', 'product')
            ->orderBy('product.product_name', 'asc')
            ->get();

        $service = product::select('product.*', 'gst.gst_per', 'uom.uom_name')
            ->leftJoin('gst', 'gst.id', 'product.gst')
            ->leftJoin('uom', 'uom.id', 'product.uom')
            ->where('product.status', 'service')
            ->orderBy('product.product_name', 'asc')
            ->get();

        //$term=terms::where('website_id',Session::get('website_id'))->first();

        $module = terms::query()
            ->get()
            ->pluck('module', 'id')
            ->toArray();

        $solist = ['' => 'select salaesorder'] + salesorder::orderBy('salaesorder_no', 'desc')->get()->pluck('salaesorder_no', 'salaesorder_no')->toArray();

        $bom = product::select('product.*', 'gst.gst_per', 'uom.uom_name')
            ->leftJoin('gst', 'gst.id', 'product.gst')
            ->leftJoin('uom', 'uom.id', 'product.uom')
            ->where('product.status', 'bom')
            ->orderBy('product.product_name', 'asc')
            ->get();

        // The vendor picker searches over ajax, so the form renders the saved
        // vendor as its one option and needs the name to show in it.
        $vendorName = vendor::where('id', $quot->vendor)->value('vendor_name');

        return view("admin.purchase.po_edit")
            ->with(['bom' => $bom,'contact_name'=>$contact_name,'data' => $quot, 'quotitem' => $quotitem, 'vendor' => $vendor, 'vendorName' => $vendorName, 'product' => $product, 'service' => $service, 'module' => $module, 'solist' => $solist]);
        //
    }

    function purchase_list(Request $request)
    {

        $product = new purchase();

        $quot_no = $client_name = $quot_date = $subject = $amount = $quot_stage = "";

        $product = $product->select('purchase.*', 'vendor.vendor_name', 'vendor.primary_email', 'vendor.secondary_email');
        $product = $product->leftJoin('vendor', 'vendor.id', 'purchase.vendor');
        $product = $product;

        if ($request->purchase_no != '') {
            $product = $product->Where('purchase.purchase_no', 'like', '%' . $request->purchase_no . '%');
        }
        if ($request->client_name != '') {
            $client_name = $request->client_name;
            $product = $product->Where('purchase.vendor_name', 'like', '%' . $request->client_name . '%');
        }
        if ($request->po_date != '') {
            $po_date = date('Y-m-d', strtotime($request->po_date));
            $product = $product->Where('purchase.po_date', 'like', '%' . $quot_date . '%');
        }
        if ($request->subject != '') {
            $subject = $request->subject;
            $product = $product->Where('purchase.subject', 'like', '%' . $request->subject . '%');
        }
        if ($request->amount != '') {
            $amount = $request->amount;
            $product = $product->Where('purchase.grand_total', 'like', '%' . $request->amount . '%');
        }
        if ($request->status != '') {
            $quot_stage = $request->status;
            $product = $product->Where('purchase.status', 'like', '%' . $request->status . '%');
        }

        $product = $product->orderBy('purchase.id','desc');
        //echo print_r($request->all());
        $result = $product->paginate(session('records_per_page', 30));

        $company_name = company::select('company_name')->first();
        return view('admin.purchase/purchase_list')
            ->with(['list' => $result, 'company' => $company_name->company_name]);
    }

    function po_list(Request $request)
    {

        $product = new purchase();

        $quot_no = $client_name = $quot_date = $subject = $amount = $quot_stage = "";

        $product = $product->select('purchase.*', 'vendor.vendor_name', 'vendor.primary_email', 'vendor.secondary_email');
        $product = $product->leftJoin('vendor', 'vendor.id', 'purchase.vendor');
       // $product = $product->where('purchase.finacial_year', Session::get('finacial_year_id'));

        if ($request->purchase_no != '') {
            $product = $product->Where('purchase.purchase_no', 'like', '%' . $request->purchase_no . '%');
        }
        if ($request->client_name != '') {
            $client_name = $request->client_name;
            $product = $product->Where('purchase.vendor_name', 'like', '%' . $request->client_name . '%');
        }
       if(isset($request->from_date) and isset($request->end_date))
        {
            $from=date('Y-m-d',strtotime($request->from_date));
            $to=date('Y-m-d',strtotime($request->end_date));
            $product = $product->whereBetween('purchase.po_date',[$from,$to]);
        }
        if ($request->subject != '') {
            $subject = $request->subject;
            $product = $product->Where('purchase.subject', 'like', '%' . $request->subject . '%');
        }
        if ($request->amount != '') {
            $amount = $request->amount;
            $product = $product->Where('purchase.grand_total', 'like', '%' . $request->amount . '%');
        }
        if ($request->status != '') {
            $quot_stage = $request->status;
            $product = $product->Where('purchase.status', 'like', '%' . $request->status . '%');
        }

        $product = $product->orderBy('purchase.id','desc');
        $product = $product->whereNull('purchase.delete_status');
        //echo print_r($request->all());
        $result = $product->paginate(session('records_per_page', 30));
        //dd($result)
        $receive_detail=purchase_receive::orderBy("id","desc")->get();

        $receive_item=purchase_receive_item::select("purchase_receive_item.*","product.product_name", "product.value1", "product.value2")
        ->leftJoin("product","product.id","purchase_receive_item.item")
        ->orderBy("purchase_receive_item.id","desc")
        ->get();

        $company_name = company::select('company_name')->first();
        return view('admin.purchase.po_list')
            ->with(['list' => $result, 'company' => $company_name->company_name,'receive_item'=>$receive_item,'receive_detail'=>$receive_detail]);
    }

    function purchase_normal_save(Request $request)
    {

        $request->validate([
            'status' => 'required',
            'due_date' => 'required',
            'vendor' => 'required',
            'po_date' => 'required',
            'billing_address' => 'required',
            'shipping_address' => 'required',
            'billing_city' => 'required',
            'shipping_city' => 'required',
            'billing_state' => 'required',
            'shipping_state' => 'required',
            'billing_postalcode' => 'required',
            'shipping_postalcode' => 'required',
            'billing_country' => 'required',
            'shipping_country' => 'required',
            "product"=> "required|array|min:1",
        ]);

        $qno = purchase::query()
            ->max('pono');

        if (empty($qno)) {
            $year = date("Y");
            $nextyear = $year + 1;

            $n2 = str_pad($qno + 1, 4, 0, STR_PAD_LEFT);
            $qno = 0;
            $n2 .= '/';
            $n2 .= $year;
            $n2 .= '-';
            $n2 .= $nextyear;
        } else {
            $year = date("Y");
            $nextyear = $year + 1;
            $n2 = str_pad($qno + 1, 4, 0, STR_PAD_LEFT);
            $n2 .= '/';
            $n2 .= $year;
            $n2 .= '-';
            $n2 .= $nextyear;
        }
        $vendor_name = vendor::find($request->vendor);

        $quotation = new purchase();
        $quotation->pono = $qno + 1;
        $quotation->purchase_no = $n2;
        $quotation->salesorder_no = $request->salesorder_no;
        $quotation->po_date = date('Y-m-d', strtotime($request->po_date));
        $quotation->subject = $request->subject;
        $quotation->vendor = $request->vendor;
        $quotation->vendor_name = $vendor_name->vendor_name;
        $quotation->status = $request->status;
        $quotation->due_date = date('Y-m-d', strtotime($request->due_date));
        $quotation->billing_address = $request->billing_address;
        $quotation->shipping_address = $request->shipping_address;
        $quotation->billing_postalcode = $request->billing_postalcode;

        $quotation->billing_city = $request->billing_city;
        $quotation->shipping_city = $request->shipping_city;
        $quotation->billing_state = $request->billing_state;
        $quotation->shipping_state = $request->shipping_state;
        $quotation->billing_postalcode = $request->billing_postalcode;
        $quotation->shipping_postalcode = $request->shipping_postalcode;
        $quotation->billing_country = $request->billing_country;
        $quotation->shipping_country = $request->shipping_country;
        $quotation->term_condition = $request->term_condition;
        $quotation->website_id = Session::get('website_id');
        $quotation->user_id = Session::get('user_id');
        $quotation->gst_amount = $request->gsttotal;
        $quotation->net_amount = $request->item_total;
        $quotation->grand_total = $request->grand_total;
        $quotation->discount_total = $request->discount_total;
        // $quotation->module=$request->module;
        $quotation->remark = $request->remark;
        if ($quotation->save()) {
            if (empty($request->product)) {
            } else {
                $totproduct = count($request->product);
                for ($i = 0; $i < $totproduct; $i++) {
                    $item = new purchase_item();
                    $item->poid = $qno + 1;
                    $item->pono = $n2;
                    $item->product = $request->product[$i];
                    $item->description = $request->description[$i];
                    $item->qty = $request->qty[$i];
                    $item->vendor = $request->vendor;
                    if (empty($request->inner_diamitter[$i])) {
                    } else {
                        $item->inner_diameter = $request->inner_diamitter[$i];
                    }
                    if (empty($request->outer_diamitter[$i])) {
                    } else {
                        $item->outer_diameter = $request->outer_diamitter[$i];
                    }
                    if (empty($request->thikness[$i])) {
                    } else {
                        $item->thikness = $request->thikness[$i];
                    }
                    if (empty($request->hsn[$i])) {
                    } else {
                        $item->hsn = $request->hsn[$i];
                    }
                    $item->price = $request->price[$i];
                    $item->total = $request->total_amount[$i];

                    $item->discount_per = $request->discount_per[$i];
                    $item->discount_amount = $request->discount_amount[$i];

                    $item->gst_per = $request->gst_per[$i];
                    $item->gst_amount = $request->gst_amount[$i];
                    $item->grand_total = $request->net_price[$i];
                    $item->save();
                }
            }


            return redirect()->route("purchase_list")->with('message', 'Purchase create successfully');
        } else {
            return back();
        }

    }

    function po_save(Request $request)
    {

        $request->validate([
            'status' => 'required',
            'due_date' => 'required',
            'vendor' => 'required',
            'po_date' => 'required',
            'billing_address' => 'required',
            'shipping_address' => 'required',
            'billing_city' => 'required',
            'shipping_city' => 'required',
            'billing_state' => 'required',
            'shipping_state' => 'required',
            'billing_postalcode' => 'required',
            'shipping_postalcode' => 'required',
            'billing_country' => 'required',
            'shipping_country' => 'required',
            "product"=> "required|array|min:1",
        ]);

         $now = date('Y-m-d',strtotime($request->po_date));


        $finacial_year= finacial_year::whereDate('start_date', '<=', $now)
       ->orderBy('id','desc')->first();

       $start=date("y",strtotime($finacial_year->start_date));
       $end=date("y",strtotime($finacial_year->end_date));
        $qno=purchase::where("finacial_year",$finacial_year->id)
       ->max('pono');

        // $qno = purchase::where('website_id', Session::get('website_id'))
        //     ->max('pono');

        if (empty($qno)) {
            $year = date("y",strtotime($start));
            $nextyear = $year + 1;
            $n2="PO-";
            $n2 .= str_pad($qno + 1, 4, 0, STR_PAD_LEFT);
            $qno = 0;
            $n2 .= '/';
            $n2 .= $start;
            $n2 .= '-';
            $n2 .= $end;
        } else {
            $year = date("y",strtotime($start));
            $nextyear = $year + 1;
            $n2="PO-";
            $n2 .= str_pad($qno + 1, 4, 0, STR_PAD_LEFT);
            $n2 .= '/';
            $n2 .= $start;
            $n2 .= '-';
            $n2 .= $end;
        }

        $vendor_name = vendor::find($request->vendor);

        $quotation = new purchase();
        $quotation->pono = $qno + 1;
        $quotation->purchase_no = $n2;
        $quotation->salesorder_no = $request->salesorder_no;
        $quotation->po_date = date('Y-m-d', strtotime($request->po_date));
        $quotation->subject = $request->subject;
        $quotation->vendor = $request->vendor;
        $quotation->vendor_name = $vendor_name->vendor_name;
        $quotation->contact_name = $request->contact_name;
        $quotation->status = $request->status;
        $quotation->due_date = date('Y-m-d', strtotime($request->due_date));
        $quotation->billing_address = $request->billing_address;
        $quotation->shipping_address = $request->shipping_address;
        $quotation->billing_postalcode = $request->billing_postalcode;

        $quotation->billing_city = $request->billing_city;
        $quotation->shipping_city = $request->shipping_city;
        $quotation->billing_state = $request->billing_state;
        $quotation->shipping_state = $request->shipping_state;
        $quotation->billing_postalcode = $request->billing_postalcode;
        $quotation->shipping_postalcode = $request->shipping_postalcode;
        $quotation->billing_country = $request->billing_country;
        $quotation->shipping_country = $request->shipping_country;
        $quotation->term_condition = $request->term_condition;
        $quotation->website_id = Session::get('website_id');
        $quotation->user_id = Session::get('user_id');
        $quotation->igsttotal = $request->igsttotal;
        $quotation->cgsttotal = $request->cgsttotal;
        $quotation->sgsttotal = $request->sgsttotal;
        $quotation->net_amount = $request->item_total;
        $quotation->grand_total = $request->grand_total;
        $quotation->discount_total = $request->discount_total;
        $quotation->adjustment=$request->adjustment;
        // $quotation->module=$request->module;
        $quotation->remark = $request->remark;
        $quotation->payment_terms=$request->payment_terms;
        $quotation->finacial_year=$finacial_year->id;
        if ($quotation->save()) {
            if(isset($request->order_id))
            {
                $pr=purchase_requirement::find($request->order_id);
                $pr->po_no=$n2;
                $pr->save();
            }

            if (empty($request->product)) {
            } else {
                $totproduct = count($request->product);
                for ($i = 0; $i < $totproduct; $i++) {
                    $item = new purchase_item();
                    $item->poid = $qno + 1;
                    $item->pono = $n2;
                    $item->product = $request->product[$i];
                    $item->description = $request->description[$i];
                    $item->qty = $request->qty[$i];
                    $item->vendor = $request->vendor;
                    if (empty($request->inner_diamitter[$i])) {
                    } else {
                        $item->inner_diameter = $request->inner_diamitter[$i];
                    }
                    if (empty($request->outer_diamitter[$i])) {
                    } else {
                        $item->outer_diameter = $request->outer_diamitter[$i];
                    }
                    if (empty($request->thikness[$i])) {
                    } else {
                        $item->thikness = $request->thikness[$i];
                    }
                    if (empty($request->hsn[$i])) {
                    } else {
                        $item->hsn = $request->hsn[$i];
                    }
                    $item->price = $request->price[$i];
                    $item->total = $request->total_amount[$i];

                    $item->discount_per = $request->discount_per[$i];
                    $item->discount_amount = $request->discount_amount[$i];
                    $item->cgst_per = $request->cgst_per[$i];
                    $item->cgst_amount = $request->cgst_amount[$i];
                    $item->sgst_per = $request->sgst_per[$i];
                    $item->sgst_amount = $request->sgst_amount[$i];
                    $item->gst_per = $request->gst_per[$i];
                    $item->gst_amount = $request->gst_amount[$i];
                    $item->grand_total = $request->net_price[$i];
                    $item->save();
                }
            }


            return redirect()->route("admin.purchase.list")->with('message', 'Purchase create successfully');
        } else {
            return back();
        }

    }

    function purchase_add(Request $request)
    {
        $vendor = ['' => 'select vendor'] + vendor::orderBy('vendor_name', 'asc')
                ->get()
                ->pluck('vendor_name', 'id')
                ->toArray();



        $product = product::select('product.*', 'gst.gst_per', 'uom.uom_name')
            ->leftJoin('gst', 'gst.id', 'product.gst')
            ->leftJoin('uom', 'uom.id', 'product.uom')
            ->where('product.status', 'product')
            ->orderBy('product.product_name', 'asc')
            ->get();

        $service = product::select('product.*', 'gst.gst_per', 'uom.uom_name')
            ->leftJoin('gst', 'gst.id', 'product.gst')
            ->leftJoin('uom', 'uom.id', 'product.uom')
            ->where('product.status', 'service')
            ->orderBy('product.product_name', 'asc')
            ->get();

        $solist = ['' => 'select salaesorder'] + salesorder::orderBy('salaesorder_no', 'desc')->get()->pluck('salaesorder_no', 'salaesorder_no')->toArray();
        $term = ['' => 'select terms'] + terms::get()->pluck('module', 'id')->toArray();

        return view("admin.purchase_normal_add")->with(['vendor' => $vendor, 'product' => $product, 'service' => $service, 'terms' => $term, 'solist' => $solist]);
    }

    function po_add(Request $request)
    {
        // Product/service/BOM picking on this page uses the select2 AJAX
        // search endpoint (product_search_options) now, so the full
        // product-table dump that used to be passed to the view is gone.

        $solist = ['' => 'select salaesorder'] + salesorder::orderBy('salaesorder_no', 'desc')->get()->pluck('salaesorder_no', 'salaesorder_no')->toArray();
        $term = ['' => 'select terms'] + terms::query()
                ->get()->pluck('module', 'id')->toArray();
        $payment_terms = payment_terms::orderBy("terms_name", "asc")
            ->get();
        $pterms="";
        foreach ($payment_terms as $pt) {
            $pterms .= "<option value='" . $pt->days . "'>" . $pt->terms_name . "</option>";
        }

        $duedate = Date('d-m-Y', strtotime('+ 15 days'));

        return view("admin.purchase/po_add")
            ->with(['payment_terms'=>$pterms,'duedate'=>$duedate,'terms' => $term, 'solist' => $solist]);
    }
}
