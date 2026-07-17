<?php

namespace App\Http\Controllers;

use App\bom_sub_product;
use App\company;
use App\contact;
use App\customers;
use App\delivery_challan;
use App\delivery_challan_item;
use App\invoice;
use App\invoice_item;
use App\material;
use App\payment_terms;
use App\product;
use App\quotation;
use App\quotation_item;
use App\salesman;
use App\salesorder;
use App\salesorder_item;
use App\stock_book;
use App\stock_status;
use App\terms;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Session;
use PDF;
use Storage;
use App\finacial_year;

class InvoiceController extends Controller
{
    //
    function invoice_list_preview(Request $request)
    {

        $quot_no = $client_name = $quot_date = $subject = $amount = $quot_stage = "";

        $product = new invoice();

        $quot_no = $client_name = $quot_date = $subject = $amount = $quot_stage = "";

        $product = $product->select('invoice.*', 'customers.customer_name', 'customers.primary_email', 'customers.secondary_email');
        $product = $product->leftJoin('customers', 'customers.id', 'invoice.customer');

        if ($request->invoice_no != '') {
            $quot_no = $request->quot_no;
            $product = $product->Where('invoice.invoice_number', 'like', '%' . $request->invoice_no . '%');
        }
        if ($request->client_name != '') {
            $client_name = $request->client_name;
            $product = $product->Where('customers.customer_name', 'like', '%' . $request->client_name . '%');
        }
        if (isset($request->from_date) and isset($request->end_date)) {
            $from = date('Y-m-d', strtotime($request->from_date));
            $to = date('Y-m-d', strtotime($request->end_date));
            $product = $product->whereBetween('invoice.invoice_date', [$from, $to]);
        }
        if ($request->subject != '') {
            $subject = $request->subject;
            $product = $product->Where('invoice.subject', 'like', '%' . $request->subject . '%');
        }
        if ($request->amount != '') {
            $amount = $request->amount;
            $product = $product->Where('invoice.grand_total', 'like', '%' . $request->amount . '%');
        }
        if ($request->status != '') {
            $quot_stage = $request->status;
            $product = $product->Where('invoice.status', 'like', '%' . $request->status . '%');
        }


        //echo print_r($request->all());
        $product = $product->where('invoice.customer', $request->id);
        $product = $product->orderBy('invoice.id', 'desc');
        $result = $product->paginate(session('records_per_page', 30));

        $company_name = company::select('company_name')->first();


        $stage = array('Created' => 'Created', 'Sent' => 'Sent', 'Reviewing' => 'Reviewing', 'QuoteRivision' => 'QuoteRivision', 'Accepted' => 'Accepted', 'Invoiced' => 'Invoiced', 'Canceled' => 'Canceled');


        return view("admin.invoice.invoice_list_preview")->with(['list' => $result, 'company' => $company_name->company_name, 'stage' => $stage]);
    }


    function invoice_print(Request $request)
    {
        $so = invoice::select("invoice.*", 'customers.*', 'customers.primary_phone as primphone', 'contact.primary_phone as contact_phone', 'website_user.*', 'customers.tax_preference')
            ->leftJoin('contact', 'contact.id', 'invoice.contact_name')
            ->leftJoin('website_user', 'website_user.id', 'invoice.user_id')
            ->leftJoin("customers", "customers.id", "invoice.customer")
            ->where('invoice.id', $request->id)
            ->first();

        $user = '';
        if (isset($so->created_by)) {
            $user = \app\User::whereId($so->created_by)->first();
        }


        $contact = contact::find($so->contact_name);
        //dd($contact);
        $soitem = invoice_item::select('invoice_item.*', 'product.product_name', 'product.make', 'product.model', 'uom.uom_name', 'product.product_image', 'product.material_name', 'category.category_image', 'product.hsn')
            ->leftJoin('product', 'product.id', 'invoice_item.product')
            ->leftJoin('category', 'category.id', 'product.category')
            ->leftJoin("uom", "uom.id", "product.uom")
            ->where('invoice_item.invoice_no', $so->invoice_number)
            ->get();

        //dd($soitem);

        $discsum = invoice_item::select('invoice_item.*', 'product.product_name', 'product.make', 'product.model')
            ->leftJoin('product', 'product.id', 'invoice_item.product')
            ->where('invoice_item.invoice_no', $so->invoice_number)
            ->sum('invoice_item.discount_amount');

        // dd($discsum);

        $customer = customers::select("customers.*", 'state.state_name')
            ->leftJoin("state", "state.id", "customers.billing_state")
//            ->where('customers.website_id',Session::get('website_id'))
            ->where('customers.id', $so->customer)
            ->first();

        $company = company::select("company.*", "state.state_name")
            ->leftJoin("state", "state.id", "company.state")
            ->first();

        $filename = date('ymdhis') . '_' . $so->invoice_number . '_';
        $filename .= $customer->customer_name;
        $filename .= '.pdf';

        $quot = $so;
        $quotitem = $soitem;

        if ($customer->tax_preference == "true") {
            $pdf = PDF::loadView('admin.invoice.invoice_print', compact("discsum", "quot", "company", "quotitem", "customer", "contact", 'user'));

            return $pdf->download($filename);
        }
        if ($customer->tax_preference == "false") {

            $pdf = PDF::loadView('admin.invoice.invoice_print', compact("discsum", "quot", "company", "quotitem", "customer", "contact", 'user'));

            return $pdf->download($filename);
        }
        return back()->with("message", "Please select Customer Tax Preference");
    }


    function invoice_preview(Request $request)
    {
        $so = invoice::select("invoice.*", 'customers.*', 'customers.primary_phone as primphone', 'website_user.*')
            ->leftJoin('contact', 'contact.id', 'invoice.contact_name')
            ->leftJoin('website_user', 'website_user.id', 'invoice.user_id')
            ->leftJoin("customers", "customers.id", "invoice.customer")
            ->where('invoice.id', $request->id)
            ->first();

        $contact = contact::find($so->contact_name);
        //dd($contact);
        $soitem = invoice_item::select('invoice_item.*', 'product.product_name', 'product.make', 'product.model', 'uom.uom_name', 'product.product_image', 'product.material_name', 'category.category_image', 'product.hsn')
            ->leftJoin('product', 'product.id', 'invoice_item.product')
            ->leftJoin('category', 'category.id', 'product.category')
            ->leftJoin("uom", "uom.id", "product.uom")
            ->where('invoice_item.invoice_no', $so->invoice_number)
            ->get();

        //dd($soitem);

        $discsum = invoice_item::select('invoice_item.*', 'product.product_name', "product.product_image", 'product.make', 'product.model')
            ->leftJoin('product', 'product.id', 'invoice_item.product')
            ->where('invoice_item.invoice_no', $so->invoice_number)
            ->sum('invoice_item.discount_amount');

        // dd($discsum);

        $customer = customers::select("customers.*", 'state.state_name')
            ->leftJoin("state", "state.id", "customers.billing_state")
            ->where('customers.id', $so->customer)
            ->first();

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

        $module = terms::where("website_id")->get()
            ->pluck('module', 'id')
            ->toArray();

        $company = company::select("company.*", "state.state_name")
            ->leftJoin("state", "state.id", "company.state")
            ->first();

        $filename = date("ymdhis") . '_' . $so->invoice_number . '_';
        $filename .= $customer->customer_name;
        $filename .= '.pdf';


        $quot = $so;
        $quotitem = $soitem;

        if ($customer->tax_preference == "true") {
            $pdf = PDF::loadView('admin.invoice.invoice_print_old', compact("discsum", "quot", "company", "quotitem", "customer", "contact"));

            $content = $pdf->download()->getOriginalContent();

            Storage::put('public/invoice/pdf/' . $filename, $content);

            $url = url('/storage/app/public/invoice/pdf/' . $filename);
            return Redirect::to($url);
        }
        if ($customer->tax_preference == "false") {

            $pdf = PDF::loadView('admin.invoice.invoice_print_old', compact("discsum", "quot", "company", "quotitem", "customer", "contact"));


            $content = $pdf->download()->getOriginalContent();

            Storage::put('public/invoice/pdf/' . $filename, $content);

            $url = url('/storage/app/public/invoice/pdf/' . $filename);
            return Redirect::to($url);
        }
        return back()->with("message", "Please select Customer Tax Preference");
    }

    function invoice_view(Request $request)
    {
        $quot = invoice::select("invoice.*", "customers.customer_name")
            ->leftJoin("customers", "customers.id", "invoice.customer")
            ->where("invoice.id", $request->id)
            ->first();

        $quotitem = invoice_item::select('invoice_item.*', 'product.product_name', 'uom.uom_name', 'stock_status.qty as stockqty')
            ->leftJoin('product', 'product.id', 'invoice_item.product')
            ->leftJoin("uom", "uom.id", "product.uom")
            ->leftJoin("stock_status", "stock_status.product", "invoice_item.product")
            ->where('invoice_item.invoice_no', $quot->invoice_number)
            ->get();

        return view("admin/invoice/invoice_view")
            ->with(['data' => $quot, 'item' => $quotitem]);
    }

    function so_invoice_update(Request $request)
    {
        //dd($request->all());

        date_default_timezone_set('Asia/Kolkata');

        $customer_name = customers::find($request->customer);

        $request->validate([

            'customer' => 'required',
            'invoice_date' => 'required',
            'product' => 'required',
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
            'term_condition' => 'required',

        ]);

        $invoice = invoice::find($request->id);

        $invoice->customer = $request->customer;
        $invoice->contact_name = $request->contact_name;
        $invoice->subject = $request->subject;
        $invoice->salaesorder_no = $request->salaesorder_no;
        if (isset($request->challan_number)) {
            $invoice->challan_number = $request->challan_number;
        }
        $invoice->invoice_date = date('Y-m-d', strtotime($request->invoice_date));
        $invoice->payment_terms = $request->payment_terms;
        $invoice->invoice_duedate = date('Y-m-d', strtotime($request->invoice_duedate));
        $invoice->status = $request->status;
        $invoice->remark = $request->remark;
        $invoice->item_total = $request->item_total;
        $invoice->discount_total = $request->discount_total;
        $invoice->cgsttotal = $request->cgsttotal;
        $invoice->sgsttotal = $request->sgsttotal;
        $invoice->igsttotal = $request->igsttotal;
        $invoice->adjustment = $request->adjustment;
        $invoice->grand_total = $request->grand_total;

        $invoice->billing_address = $request->billing_address;
        $invoice->shipping_address = $request->shipping_address;
        $invoice->billing_city = $request->billing_city;
        $invoice->shipping_city = $request->shipping_city;
        $invoice->billing_state = $request->billing_state;
        $invoice->shipping_state = $request->shipping_state;
        $invoice->billing_postalcode = $request->billing_postalcode;
        $invoice->shipping_postalcode = $request->shipping_postalcode;
        $invoice->billing_country = $request->billing_country;
        $invoice->shipping_country = $request->shipping_country;
        $invoice->term_condition = $request->term_condition;
        $invoice->module = $request->module;
        $invoice->user_id = Session::get("user_id");
        $invoice->website_id = Session::get("website_id");

        $invoice->purchase_order = $request->purchase_order;
        $invoice->purchase_order_date = $request->purchase_order_date;
        $invoice->quot_date = $request->quot_date;
        $invoice->quotation_no = $request->quotation_no;
        $invoice->salaesorder_date = $request->salaesorder_date;
        $invoice->delivery_date = $request->delivery_date;
        $invoice->eway_billno = $request->eway_billno;
        $invoice->eway_billdate = $request->eway_billdate;
        $invoice->challan_date = $request->challan_date;
        $invoice->updated_by = Auth::user()->id;

        if ($invoice->save()) {
            invoice_item::where("invid", $invoice->id)->delete();

            if (empty($request->product)) {
            } else {

                $totproduct = count($request->product);
                for ($i = 0; $i < $totproduct; $i++) {
                    $item = new invoice_item();
                    $item->invid = $invoice->id;
                    $item->invoice_no = $invoice->invoice_number;
                    $item->product = $request->product[$i];
                    $item->description = $request->description[$i];
                    $item->inner_diamitter = $request->inner_diamitter[$i] ?? "";
                    $item->outer_diamitter = $request->outer_diamitter[$i] ?? "";
                    $item->thikness = $request->thikness[$i] ?? "";
                    $item->hsn = $request->hsn[$i];
                    $item->qty = $request->qty[$i];
                    $item->price = $request->price[$i];
                    $item->total_amount = $request->total_amount[$i];
                    $item->discount_per = $request->discount_per[$i];
                    $item->discount_amount = $request->discount_amount[$i];
                    $item->cgst_per = $request->cgst_per[$i];
                    $item->cgst_amount = $request->cgst_amount[$i];
                    $item->sgst_per = $request->sgst_per[$i];
                    $item->sgst_amount = $request->sgst_amount[$i];
                    $item->igst_per = $request->gst_per[$i];
                    $item->igst_amount = $request->gst_amount[$i];
                    $item->net_price = $request->net_price[$i];

                    if ($item->save()) {

                    }


                }
            }

            if (empty($request->salaesorder_no)) {
            } else {
                $so = salesorder::where('salaesorder_no', $request->salaesorder_no)->first();
                $so->invoice_status = "Y";
                $so->save();
            }


            return redirect()->route("admin.invoice.list")->with("message", "Invoice Update Successfully");
        }
    }

    function invoice_edit(Request $request)
    {
        $quot = invoice::where("id", $request->id)
            ->first();

        if (empty($quot->contact_name)) {
            $contact_name = ['' => 'select contact'] + contact::query()
                    ->where('customer', $quot->customer)
                    ->orderBy('contact_name', 'asc')
                    ->get()
                    ->pluck('contact_name', 'id')
                    ->toArray();
        } else {
            $cname = contact::select('id', 'contact_name')->where('id', $quot->contact_name)->first();

            $contact_name = [$cname->id => $cname->contact_name] + contact::query()
                    ->where('customer', $quot->customer)
                    ->orderBy('contact_name', 'asc')
                    ->get()
                    ->pluck('contact_name', 'id')
                    ->toArray();
        }

        $quotitem = invoice_item::select('invoice_item.*', 'product.product_name', "product.product_image", 'uom.uom_name', 'stock_status.qty as stockqty', "product.bar_code")
            ->leftJoin('product', 'product.id', 'invoice_item.product')
            ->leftJoin("uom", "uom.id", "product.uom")
            ->leftJoin("stock_status", "stock_status.product", "invoice_item.product")
            ->where('invoice_item.invoice_no', $quot->invoice_number)
            ->get();

        $customer = customers::where('id', $quot->customer)
            ->pluck("customer_name", "id")
            ->toArray();


        // Product/service/BOM picking on this page uses the select2 AJAX
        // search endpoint (product_search_options) now, so the full
        // product-table dump that used to be passed to the view is gone.

        //$term=terms::where('website_id',Session::get('website_id'))->first();

        $term = terms::get();
        $customer_terms = customers::where('id', $quot->customer)
            ->first();
        //dd($customer_terms);
        $pterms = "";
        $duedate = date('d-m-Y');
        if (empty($customer_terms->payment_terms)) {
            $payment_terms = payment_terms::orderBy("terms_name", "asc")
                ->get();
            foreach ($payment_terms as $pt) {
                $pterms .= "<option value='" . $pt->days . "'>" . $pt->terms_name . "</option>";
            }

        } else {

            $payment_terms = payment_terms::where("days", $customer_terms->payment_terms)
                ->first();

            $pterms .= "<option value='" . $payment_terms->days . "'>" . $payment_terms->terms_name . "</option>";

            $duedate = Date('d-m-Y', strtotime('+' . $payment_terms->days . ' days'));
            $payment_terms1 = payment_terms::orderBy("terms_name", "asc")
                ->get();
            foreach ($payment_terms1 as $pt) {
                $pterms .= "<option value='" . $pt->days . "'>" . $pt->terms_name . "</option>";
            }
            //$payment_terms=[$payment_terms->id=>$payment_terms->terms_name];
        }

        $stockstatus = stock_status::get();

        $module = ['' => 'select terms'] + terms::query()
                ->get()
                ->pluck('module', 'id')
                ->toArray();

        return view("admin.invoice.edit")
            ->with(['contact_name' => $contact_name, 'module' => $module, 'stockstatus' => $stockstatus, 'duedate' => $duedate, 'payment_terms' => $pterms, 'data' => $quot, 'quotitem' => $quotitem, 'customer' => $customer, 'term' => $term]);

    }

    function invoice_list(Request $request)
    {

        $quot_no = $client_name = $quot_date = $subject = $amount = $quot_stage = "";

        $product = new invoice();

        $quot_no = $client_name = $quot_date = $subject = $amount = $quot_stage = "";

        $product = $product->select('invoice.*', 'customers.customer_name', 'customers.primary_email', 'customers.secondary_email');
        $product = $product->leftJoin('customers', 'customers.id', 'invoice.customer');

        //  dd($product->get());
        if ($request->invoice_no != '') {
            $quot_no = $request->quot_no;
            $product = $product->Where('invoice.invoice_number', 'like', '%' . $request->invoice_no . '%');
        }

        if ($request->client_name != '') {
            $client_name = $request->client_name;
            $product = $product->Where('customers.customer_name', 'like', '%' . $request->client_name . '%');
        }

        if (isset($request->from_date) and isset($request->end_date)) {
            $from = date('Y-m-d', strtotime($request->from_date));
            $to = date('Y-m-d', strtotime($request->end_date));
            $product = $product->whereBetween('invoice.invoice_date', [$from, $to]);
        }

        if ($request->subject != '') {
            $subject = $request->subject;
            $product = $product->Where('invoice.subject', 'like', '%' . $request->subject . '%');
        }

        if ($request->amount != '') {
            $amount = $request->amount;
            $product = $product->Where('invoice.grand_total', 'like', '%' . $request->amount . '%');
        }

        if ($request->status != '') {
            $quot_stage = $request->status;
            $product = $product->Where('invoice.status', 'like', '%' . $request->status . '%');
        }


        //echo print_r($request->all());

        $product = $product->orderBy('invoice.id', 'desc');
        $result = $product->paginate(session('records_per_page', 30));

        $company_name = company::select('company_name')->first();


        $stage = array('Created' => 'Created', 'Sent' => 'Sent', 'Reviewing' => 'Reviewing', 'QuoteRivision' => 'QuoteRivision', 'Accepted' => 'Accepted', 'Invoiced' => 'Invoiced', 'Canceled' => 'Canceled');


        return view("admin.invoice.invoice_list")->with(['list' => $result, 'company' => $company_name->company_name, 'stage' => $stage]);
    }

    function get_products(Request $request)
    {

        $data = product::select('product.*', 'gst.gst_per', 'uom.uom_name', 'stock_status.qty as stockqty', "category.category_name as catname", "category.category_image")
            ->leftJoin('gst', 'gst.id', 'product.gst')
            ->leftJoin('uom', 'uom.id', 'product.uom')
            ->leftJoin('category', 'category.id', 'product.category')
            ->leftJoin("stock_status", "stock_status.product", "product.id")
            ->orderBy('product_name', 'asc')
            ->where('product.id', $request->product)
            ->first();

        $stockqty = $data->stockqty ?? 0;

        $sub_product = bom_sub_product::select('product.product_name', "stock_status.qty")
            ->leftJoin('product', 'product.id', 'bom_sub_product.product')
            ->leftJoin("stock_status", "stock_status.product", "product.id")
            ->where('bom_sub_product.bom_id', $data->id)
            ->get();

        $row = "";

        foreach ($sub_product as $sprod) {
            $row .= $sprod->product_name . 'stock on hand' . $sprod->qty;
        }
        //dd($row);
        $str = $data->description;

        // dd(htmlentities($str));
        $quotation = quotation::where('customer', $request->customer)
            ->orderBy('quotation_no', 'desc')
            ->first();


        $hsn = $data->hsn;


        $qitem = quotation_item::where('customer', $request->customer)
            ->where('product', $request->product)
            ->orderBy('quotation_no', 'desc')
            ->first();
        if (empty($qitem)) {
            $productprice = $data->price;
            $discper = 0;
        } else {
            $productprice = $qitem->price;
            $discper = $qitem->discount_per;
        }

        $company = company::select('company.*', 'state.state_name', 'state.state_code')
            ->leftJoin("state", "state.id", "company.state")
            ->first();

        $customer = customers::select('customers.*', 'state.state_name', 'state.state_code')
            ->leftJoin("state", "state.id", "customers.billing_state")
            ->where("customers.id", $request->customer)
            ->first();

        $igst = $cgstper = $sgstper = 0;

        //dd($productprice);
        //dd($data);


        if (empty($data->product_image)) {
        } else {
            $pathImage = asset('public/product_image/' . $data->product_image);
        }

        if (empty($data)) {
            $user[] = "";
        } else {
            $user[] = array('stockqty' => $stockqty, 'product_name' => $data->product_name, 'price' => $productprice, 'gst' => $igst, 'sgst' => $sgstper, 'cgst' => $cgstper, 'uom' => $data->uom_name, 'description' => $str, 'product_image' => $pathImage, 'outer_diameter' => $data->outer_diameter, 'inner_diameter' => $data->inner_diameter, 'thikness' => $data->thikness, 'hsn' => $hsn, 'discper' => $discper);
        }
        //dd($user);
        return json_encode($user);
    }

    function get_product(Request $request)
    {

        $data = product::select('product.*', 'gst.gst_per', 'uom.uom_name', 'stock_status.qty as stockqty')
            ->leftJoin('gst', 'gst.id', 'product.gst')
            ->leftJoin('uom', 'uom.id', 'product.uom')
            ->leftJoin("stock_status", "stock_status.product", "product.id")
            ->orderBy('product_name', 'asc')
            ->where('product.id', $request->product)
            ->first();
        $stockqty = $data->stockqty ?? 0;
        $sub_product = bom_sub_product::select('product.product_name', "stock_status.qty")
            ->leftJoin('product', 'product.id', 'bom_sub_product.product')
            ->leftJoin("stock_status", "stock_status.product", "product.id")
            ->where('bom_sub_product.bom_id', $data->id)
            ->get();
        $row = "";
        foreach ($sub_product as $sprod) {
            $row .= $sprod->product_name . 'stock on hand' . $sprod->qty;
        }
        //dd($row);
        $str = $data->description;

        // dd(htmlentities($str));
        $quotation = quotation::where('customer', $request->customer)
            ->orderBy('quotation_no', 'desc')
            ->first();


        $hsn = $data->hsn;


        $qitem = quotation_item::where('customer', $request->customer)
            ->where('product', $request->product)
            ->orderBy('quotation_no', 'desc')
            ->first();
        if (empty($qitem)) {
            $productprice = $data->price;
            $discper = 0;
        } else {
            $productprice = $qitem->price;
            $discper = $qitem->discount_per;
        }

        $company = company::select('company.*', 'state.state_name', 'state.state_code')
            ->leftJoin("state", "state.id", "company.state")
            ->first();

        $customer = customers::select('customers.*', 'state.state_name', 'state.state_code')
            ->leftJoin("state", "state.id", "customers.billing_state")
            ->where("customers.id", $request->customer)
            ->first();

        $igst = $cgstper = $sgstper = 0;
        if ($customer->tax_preference == "true") {
            if ($company->state_code == $customer->state_code) {
                $igst = 0;
                $cgstper = $data->gst_per / 2;
                $sgstper = $data->gst_per / 2;
            } else {
                $igst = $data->gst_per;
                $cgstper = 0;
                $sgstper = 0;
            }
        }
        //dd($productprice);
        //dd($data);
        if (empty($data)) {
            $user[] = "";
        } else {
            $user[] = array('stockqty' => $stockqty, 'product_name' => $data->product_name, 'price' => $productprice, 'gst' => $igst, 'sgst' => $sgstper, 'cgst' => $cgstper, 'uom' => $data->uom_name, 'description' => $str, 'product_image' => $data->product_image, 'outer_diameter' => $data->outer_diameter, 'inner_diameter' => $data->inner_diameter, 'thikness' => $data->thikness, 'hsn' => $hsn, 'discper' => $discper);
        }
        //dd($user);
        return json_encode($user);
    }

    function so_invoice(Request $request)
    {

        date_default_timezone_set('Asia/Kolkata');

        $customer_name = customers::find($request->customer);

        $request->validate([

            'customer' => 'required',
            'invoice_date' => 'required',
            'product' => 'required',
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
            'term_condition' => 'required',

        ]);
        $now = date('Y-m-d', strtotime($request->invoice_date));

        $finacial_year = finacial_year::where('start_date', '<=', $now)->where('end_date', '>=', $now)
            ->first();
        $start = date("y", strtotime($finacial_year->start_date));
        $end = date("y", strtotime($finacial_year->end_date));

        $qno = invoice::where("finacial_year", $finacial_year->id)->max('invoice_no');

        if (empty($qno)) {
            $year = date("Y");
            $nextyear = $year + 1;

            $n2 = "INV-";
            $n2 .= str_pad($qno + 1, 5, 0, STR_PAD_LEFT);
            $qno = 0;
            $n2 .= '/';
            $n2 .= $start;
            $n2 .= '-';
            $n2 .= $end;
        } else {
            $year = date("Y");
            $nextyear = $year + 1;
            $n2 = "INV-";
            $n2 .= str_pad($qno + 1, 5, 0, STR_PAD_LEFT);
            $n2 .= '/';
            $n2 .= $start;
            $n2 .= '-';
            $n2 .= $end;
        }

        $invoice = new invoice();
        $invoice->invoice_no = $qno + 1;
        $invoice->invoice_number = $n2;
        $invoice->customer = $request->customer;
        $invoice->contact_name = $request->contact_name;
        $invoice->subject = $request->subject;
        $invoice->salaesorder_no = $request->salaesorder_no;
        if (isset($request->challan_number)) {

            $invoice->challan_number = $request->challan_number;
//
//            $dc=delivery_challan::where("challan_number",$request->challan_number)
//                ->first();
//            dd($dc);
//            $dc->invoice="Y";
//            $dc->save;
            DB::table('delivery_challan')
                ->where('challan_number', $request->challan_number)
                ->update(['invoice' => "Y"]);
        }
        $invoice->invoice_date = date('Y-m-d', strtotime($request->invoice_date));
        $invoice->payment_terms = $request->payment_terms;
        $invoice->invoice_duedate = date('Y-m-d', strtotime($request->invoice_duedate));
        $invoice->status = $request->status;
        $invoice->remark = $request->remark;
        $invoice->item_total = $request->item_total;
        $invoice->discount_total = $request->discount_total;
        $invoice->cgsttotal = $request->cgsttotal;
        $invoice->sgsttotal = $request->sgsttotal;
        $invoice->igsttotal = $request->igsttotal;
        $invoice->adjustment = $request->adjustment;
        $invoice->grand_total = $request->item_total;

        $invoice->billing_address = $request->billing_address;
        $invoice->shipping_address = $request->shipping_address;
        $invoice->billing_city = $request->billing_city;
        $invoice->shipping_city = $request->shipping_city;
        $invoice->billing_state = $request->billing_state;
        $invoice->shipping_state = $request->shipping_state;
        $invoice->billing_postalcode = $request->billing_postalcode;
        $invoice->shipping_postalcode = $request->shipping_postalcode;
        $invoice->billing_country = $request->billing_country;
        $invoice->shipping_country = $request->shipping_country;
        $invoice->term_condition = $request->term_condition;
        $invoice->module = $request->module;
        $invoice->user_id = Session::get("user_id");
        $invoice->website_id = Session::get("website_id");
        $invoice->finacial_year = $finacial_year->id;

        $invoice->purchase_order = $request->purchase_order;
        $invoice->purchase_order_date = $request->purchase_order_date;
        $invoice->quot_date = $request->quot_date;
        $invoice->quotation_no = $request->quotation_no;
        $invoice->salaesorder_date = $request->salaesorder_date;
        $invoice->delivery_date = $request->delivery_date;
        $invoice->eway_billno = $request->eway_billno;
        $invoice->eway_billdate = $request->eway_billdate;
        $invoice->challan_date = $request->challan_date;
        $invoice->salesman_id = $request->salesman_id;
        $invoice->created_by = Auth::user()->id;

        if ($invoice->save()) {
            if (empty($request->product)) {
            } else {
                $totproduct = count($request->product);
                for ($i = 0; $i < $totproduct; $i++) {
                    $item = new invoice_item();
                    $item->invid = $invoice->id;
                    $item->invoice_no = $n2;
                    $item->product = $request->product[$i];
                    $item->description = $request->description[$i];
                    $item->inner_diamitter = $request->inner_diamitter[$i] ?? "";
                    $item->outer_diamitter = $request->outer_diamitter[$i] ?? "";
                    $item->thikness = $request->thikness[$i] ?? "";
                    $item->hsn = $request->hsn[$i];
                    $item->qty = $request->qty[$i];
                    $item->price = $request->price[$i];
                    $item->total_amount = $request->total_amount[$i];
                    $item->discount_per = $request->discount_per[$i];
                    $item->discount_amount = $request->discount_amount[$i];
                    $item->cgst_per = $request->cgst_per[$i];
                    $item->cgst_amount = $request->cgst_amount[$i];
                    $item->sgst_per = $request->sgst_per[$i];
                    $item->sgst_amount = $request->sgst_amount[$i];
                    $item->igst_per = $request->gst_per[$i];
                    $item->igst_amount = $request->gst_amount[$i];
                    $item->net_price = $request->net_price[$i];

                    if ($item->save()) {

                    }


                }
            }

            if (empty($request->salaesorder_no)) {
            } else {
                $so = salesorder::where('salaesorder_no', $request->salaesorder_no)->first();
                $so->invoice_status = "Y";
                $so->save();
            }
            return redirect()->route("admin.invoice.list")->with("message", "Invoice Create Successfully");
        }
    }

    function getinvoiceduedate(Request $request)
    {

        $date = strtotime("+" . $request->days . " days", strtotime($request->salaesorder_date));
        return date("d-m-Y", $date);

    }

    function invoice_add(Request $request)
    {
        $contact_name = array();
        if ($request->id == 0) {
            $quot = "";
            $quotitem = array();
            $pterms = "";
            $customer = ['' => 'select customer'] + customers::orderBy('customer_name', "asc")
                    ->pluck("customer_name", "id")
                    ->toArray();
            $payment_terms = payment_terms::orderBy("terms_name", "asc")
                ->get();
            foreach ($payment_terms as $pt) {
                $pterms .= "<option value='" . $pt->days . "'>" . $pt->terms_name . "</option>";
            }

            $duedate = Date('d-m-Y', strtotime('+ 15 days'));

            $salesMan = ['' => 'select salesman'] + salesman::orderBy('salesman_name', "asc")
                    ->pluck("salesman_name", "id")
                    ->toArray();

        } else {
            $quot = salesorder::where('id', $request->id)
                ->first();

            if (empty($quot->contact_name)) {
                $contact_name = ['' => 'select contact'] + contact::query()
                        ->orderBy('contact_name', 'asc')
                        ->where('customer', $quot->customer)
                        ->get()
                        ->pluck('contact_name', 'id')
                        ->toArray();
            } else {

                $cname = contact::select('id', 'contact_name')->where('id', $quot->contact_name)->first();

                $contact_name = [$cname->id => $cname->contact_name] + contact::query()
                        ->where('customer', $quot->customer)
                        ->orderBy('contact_name', 'asc')
                        ->get()
                        ->pluck('contact_name', 'id')
                        ->toArray();
                //dd($contact_name);
            }

            $quotitem = salesorder_item::select('salesorder_item.*', 'product.product_name', 'uom.uom_name', 'stock_status.qty as stockqty', "product.bar_code")
                ->leftJoin('product', 'product.id', 'salesorder_item.product')
                ->leftJoin("uom", "uom.id", "product.uom")
                ->leftJoin("stock_status", "stock_status.product", "salesorder_item.product")
                ->where('salesorder_item.soid', $quot->sono)
                ->get();
            $customer = customers::where('id', $quot->customer)
                ->pluck("customer_name", "id")
                ->toArray();

            $customer_terms = customers::where('id', $quot->customer)
                ->first();

            $salesMan = salesman::where('id', $quot->salesman_id)
                ->first();
            //dd($customer_terms);
            $pterms = "";
            $duedate = date('d-m-Y');
            if (empty($customer_terms->payment_terms)) {
                $payment_terms = payment_terms::orderBy("terms_name", "asc")
                    ->get();
                foreach ($payment_terms as $pt) {
                    $pterms .= "<option value='" . $pt->days . "'>" . $pt->terms_name . "</option>";
                }

            } else {

                $payment_terms = payment_terms::where("id", $customer_terms->payment_terms)
                    ->first();

                $pterms .= "<option value='" . $payment_terms->days . "'>" . $payment_terms->terms_name . "</option>";

                $duedate = Date('d-m-Y', strtotime('+' . $payment_terms->days . ' days'));
                $payment_terms1 = payment_terms::orderBy("terms_name", "asc")
                    ->get();
                foreach ($payment_terms1 as $pt) {
                    $pterms .= "<option value='" . $pt->days . "'>" . $pt->terms_name . "</option>";
                }
                //$payment_terms=[$payment_terms->id=>$payment_terms->terms_name];
            }
        }


        // Product/service/BOM picking on this page uses the select2 AJAX
        // search endpoint (product_search_options) now, so the full
        // product-table dump that used to be passed to the view is gone.

        //$term=terms::where('website_id',Session::get('website_id'))->first();

        $term = terms::all();

        $stockstatus = stock_status::get();

        return view("admin.invoice.create")->with(['contact_name' => $contact_name, 'stockstatus' => $stockstatus, 'duedate' => $duedate, 'payment_terms' => $pterms, 'data' => $quot, 'quotitem' => $quotitem, 'customer' => $customer, 'term' => $term, 'salesMan' => $salesMan]);

    }

    function invoice_delete(Request $request)
    {
        invoice::where('id', $request->id)->delete();
        return back()->with("message","invoice delete successfully");
    }
}
