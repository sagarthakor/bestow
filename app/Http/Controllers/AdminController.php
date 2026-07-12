<?php

namespace App\Http\Controllers;

use App\attribute;
use App\brand;
use App\Exports\InvoiceExport;
use App\Exports\quotationExport;
use App\importer;
use App\manufacturer;
use App\packer;
use App\payment_terms;
use App\product_attribute;
use App\salesman;
use App\subcategory;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use App\product;
use App\customers;
use App\quotation_item;
use App\quotation;
use App\company;
use Illuminate\Support\Facades\Auth;
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
use Excel;
use Illuminate\Support\Facades\Redirect;
use App\finacial_year;
use App\quot_followup;
use App\purchase;
use App\delivery_challan;
use App\invoice;
use App\raw_material_group;
use App\Imports\ProductImport;

class AdminController extends Controller
{
    function ajax_search_product(Request $request)
    {
        $product = product::where("bar_code", "=", $request->itemname)->get();
        //$option="<option value=''>select product</option>";
        foreach ($product as $product) {
            $option[] = "<option value='$product->id'>$product->product_name</option>";
        }
        return $option;
    }

    function product_import_save(Request $request)
    {
        $import = new ProductImport();
        $data = \Excel::import($import, request()->file('import_file'));
        return back()->with("success", "File Import Successfully");
    }

    function product_import(Request $request)
    {
        return view("admin.product_import")->with(["uniqueProduct" => "", "duplicateProduct" => ""]);
    }

    function subcategory_edit(Request $request)
    {

        $subcategory = subcategory::find($request->id);

        $category = ['' => "select category"] + category::orderBy("category_name", "asc")->get()->pluck("category_name", "id")->toArray();

        return view("admin.subcategory_edit", compact("category", "subcategory"));
    }

    function subcategory_delete(Request $request)
    {
        $category = subcategory::find($request->id);
        ////LogActivity::addToLog($category->subcategory_name.' SubCategory Delete');
        if ($category->delete()) {
            //  //LogActivity::addToLog($request->subcategory_name.' SubCategory Delete');

            return redirect()->route('admin.subcategory.list')->with('message', 'Subcategory Delete successfully');
        } else {
            return back();
        }
    }

    function subcategory_update(Request $request)
    {
        $request->validate([
            'subcategory_name' => 'required|max:255',
        ]);

        $category = subcategory::find($request->id);
        $category->category = $request->category;
        $category->subcategory_name = $request->subcategory_name;


        if ($request->hasFile('subcategory_image')) {
            $image = $request->file('subcategory_image');
            $name = time() . '.' . $image->getClientOriginalExtension();
            $destinationPath = '/subcategory/';
            $image->move(public_path($destinationPath), $name);
            $category->subcategory_image = $name;
        }

        if ($category->save()) {
            //  //LogActivity::addToLog($request->subcategory_name.' SubCategory Update');

            return redirect()->route('admin.subcategory.list')->with('message', 'Subcategory Update successfully');
        } else {
            return back();
        }
    }

    function subcategory_save(Request $request)
    {
        $request->validate([
            'subcategory_name' => 'required|max:255',
        ]);

        $category = new subcategory();
        $category->category = $request->category;
        $category->subcategory_name = $request->subcategory_name;


        if ($request->hasFile('subcategory_image')) {
            $image = $request->file('subcategory_image');
            $name = time() . '.' . $image->getClientOriginalExtension();
            $destinationPath = '/subcategory/';
            $image->move(public_path($destinationPath), $name);
            $category->subcategory_image = $name;
        }

        if ($category->save()) {
            //  //LogActivity::addToLog($request->subcategory_name.' SubCategory Create');

            return redirect()->route('admin.subcategory.list')->with('message', 'Subcategory save successfully');
        } else {
            return back();
        }
    }

    function subcategory_add()
    {
        $category = ['' => "select category"] + category::orderBy("category_name", "asc")->get()->pluck("category_name", "id")->toArray();
        return view("admin.subcategory_add", compact("category"));
    }

    function quotation_followup_list(Request $request)
    {
        $data = new quot_followup();
        $data = $data->select("quot_followup.*");
        $data = $data->orderBy("id", "desc");
        $data = $data->paginate(10);

        $company_name = company::select('company_name')->first();

        return view("admin.quotation.followup_list")
            ->with(['list' => $data, 'company' => $company_name]);
    }

    function payment_terms_delete(Request $request)
    {
        $pay = payment_terms::find($request->id);
        if ($pay->delete()) {
            return \redirect()->route("admin.payment_terms.list")->with("message", "Payment terms Delete successfully");
        }
    }

    function payment_terms_update(Request $request)
    {
        $request->validate([
            'terms_name' => 'required',
            'days' => 'required'
        ]);

        $pay = payment_terms::find($request->id);
        $pay->terms_name = $request->terms_name;
        $pay->days = $request->days;
        if ($pay->save()) {
            return \redirect()->route("admin.payment_terms.list")->with("message", "Payment terms Update successfully");
        }
    }

    function payment_terms_edit(Request $request)
    {
        $data = payment_terms::find($request->id);
        return view("admin.terms.payment.edit", compact("data"));
    }

    function payment_terms_save(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'terms_name' => 'required',
            'days' => 'required'
        ]);

        $pay = new payment_terms();
        $pay->terms_name = $request->terms_name;
        $pay->days = $request->days;
        if ($pay->save()) {
            return \redirect()->route("admin.payment_terms.list")->with("message", "Payment terms save successfully");
        }
    }

    function payment_terms(Request $request)
    {
        $data = payment_terms::orderBy("id", "desc")->paginate(10);

        return view("admin.terms.payment.index", compact("data"));
    }

    function quot_stage_change(Request $request)
    {
        DB::table('quotation')
            ->where('id', $request->quot_id)
            ->update(['quot_stage' => $request->stage]);

        return $request->stage;
    }

    function get_vendor_contact(Request $request)
    {
        $vcontact = vendor_contact::where('vendor', $request->vendor)
            ->get();
        $str = "<option value=''>select contact</option>";

        foreach ($vcontact as $vclist) {
            $str .= '<option value="' . $vclist->id . '">' . $vclist->contact_name . '</option>';
        }
        return $str;
    }

    function product_quot_list(Request $request)
    {
        $quotitem = quotation_item::select('quot_item.*', 'product.product_name', 'customers.customer_name')
            ->leftJoin('product', 'product.id', 'quot_item.product')
            ->leftJoin('customers', 'customers.id', 'quot_item.customer')
            ->where('quot_item.product', $request->product)
            ->get();

        return view("admin/product_quot_list", compact('quotitem'));

    }

    function quot_normal_view(Request $request)
    {
        $quot = quotation::where('id', $request->id)
            ->first();

        $quotitem = quotation_item::select('quot_item.*', 'product.product_name', 'product.make', 'product.model')
            ->leftJoin('product', 'product.id', 'quot_item.product')
            ->where('quot_item.quot_no', $quot->quot_no)
            ->get();


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


        return view("admin.quotation.preview")->with(['data' => $quot, 'quotitem' => $quotitem, 'product' => $product, 'service' => $service, 'module' => $module]);
    }

    function quot_view(Request $request)
    {
        $quot = quotation::select("quotation.*", "contact.contact_name as contactname")
            ->where('quotation.id', $request->id)
            ->leftJoin("contact", "contact.id", "quotation.contact_name")
            ->first();

        $quotitem = quotation_item::select('quot_item.*', 'product.product_name', 'product.make', 'product.model', "product.product_image", "uom.uom_name")
            ->leftJoin('product', 'product.id', 'quot_item.product')
            ->leftJoin('uom', 'uom.id', 'product.uom')
            ->where('quot_item.quot_no', $quot->quot_no)
            ->get();


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


        return view("admin.quotation_preview")->with(['data' => $quot, 'quotitem' => $quotitem, 'product' => $product, 'service' => $service, 'module' => $module]);
    }

    function material_delete(Request $request)
    {
        $material = material::find($request->id);

        if ($material->delete()) {
            return redirect()->route('admin.material.list')->with('message', 'Material delete successfully');
        }
    }

    function material_update(Request $request)
    {
        $material = material::find($request->id);
        $material->material_name = $request->material_name;
        $material->hsn_code = $request->hsn_code;
        $material->website_id = Session::get('website_id');
        $material->user_id = Session::get('user_id');
        if ($material->save()) {
            return redirect()->route('admin.material.list')->with('message', 'Material update successfully');
        }
    }

    function material_edit(Request $request)
    {
        $data = material::find($request->id);
        return view("admin/material_edit")->with(['data' => $data]);
    }

    function material_save(Request $request)
    {
        $request->validate([
            'material_name' => 'required|max:255|unique:material',
        ]);


        date_default_timezone_set('Asia/Kolkata');
        $material = new material();
        $material->material_name = $request->material_name;
        $material->hsn_code = $request->hsn_code;
        $material->website_id = Session::get('website_id');
        $material->user_id = Session::get('user_id');
        $material->created_time = date('Y-m-d h:i:s A');
        if ($material->save()) {
            return redirect()->route('admin.material.list')->with('message', 'Material Save successfully');
        }
    }

    function material_list(Request $request)
    {
        $mat = new material();
        $mat = $mat;
        if (isset($request->material_name)) {
            $mat = $mat->where('material_name', 'like', '%' . $request->material_name . '%');

        }
        if (isset($request->hsn_code)) {
            $mat = $mat->where('hsn_code', 'like', '%' . $request->hsn_code . '%');

        }
        $mat = $mat->orderBy("material_name", "asc");
        $result = $mat->paginate(10);

        return view("admin/material_list")->with(['data' => $result]);
    }

    function contact_delete(Request $request)
    {
        $save = contact::find($request->id);
        if ($save->delete()) {
            return redirect()->route('client/contact_list')->with('message', 'Contact Delete successfully');
        } else {
            return back()->with('message', 'error in save');
        }
    }

    function vendor_contact_delete(Request $request)
    {
        $save = vendor_contact::find($request->id);
        if ($save->delete()) {
            return redirect()->route('client/vendor_contact_list')->with('message', 'Vendor Contact Delete successfully');
        } else {
            return back()->with('message', 'error in save');
        }
    }

    function contact_update(Request $request)
    {
        date_default_timezone_set('Asia/Kolkata');
        $save = contact::find($request->id);

        $request->validate([
            'contact_name' => 'required|max:255',
            'primary_email' => 'email:rfc,dns',
            'primary_phone' => 'required',
            'customer' => 'required',
        ]);
        // / dd($request->all());
        $save->customer = $request->customer;
        $save->contact_name = $request->contact_name;

        $save->primary_phone = $request->primary_phone;
        $save->secondary_phone = $request->secondary_phone;
        $save->primary_email = $request->primary_email;
        $save->secondary_email = $request->secondary_email;

        $save->description = $request->description;
        $save->created_time = date('Y-m-d h:i:s A');
        $save->user_id = Session::get('user_id');
        $save->website_id = Session::get('website_id');
        $save->office_phone = $request->office_phone;
        $save->office_email = $request->office_email;
        $save->department = $request->department;
        $save->designation = $request->designation;
        if ($request->hasFile('photo')) {

            $image = $request->file('photo');
            $name = $request->primary_phone . '.' . time() . '.' . $image->getClientOriginalExtension();
            $destinationPath = '/contact_image/';
            $image->move($destinationPath, $name);

            $save->photo = $name;
        }


        if ($save->save()) {
            //  //LogActivity::addToLog($request->contact_name.' Customer Contact Update ');

            return redirect()->route('admin.customer.contact.list')->with('message', 'Contact Update successfully');
        } else {
            return back()->with('message', 'error in save');
        }
    }

    function vendor_contact_update(Request $request)
    {
        date_default_timezone_set('Asia/Kolkata');
        $save = vendor_contact::find($request->id);
        $request->validate([
            'contact_name' => 'required|max:255',
            'primary_email' => 'email:rfc,dns',
            'primary_phone' => 'required',
            'vendor' => 'required',
        ]);
        // / dd($request->all());
        $save->vendor = $request->vendor;
        $save->contact_name = $request->contact_name;

        $save->primary_phone = $request->primary_phone;
        $save->secondary_phone = $request->secondary_phone;
        $save->primary_email = $request->primary_email;
        $save->secondary_email = $request->secondary_email;

        $save->description = $request->description;
        $save->created_time = date('Y-m-d h:i:s A');
        $save->user_id = Session::get('user_id');
        $save->website_id = Session::get('website_id');
        $save->office_phone = $request->office_phone;
        $save->office_email = $request->office_email;
        $save->department = $request->department;
        $save->designation = $request->designation;
        if ($request->hasFile('photo')) {

            $image = $request->file('photo');
            $name = $request->primary_phone . '.' . time() . '.' . $image->getClientOriginalExtension();
            $destinationPath = '/contact_image/';
            $image->move($destinationPath, $name);

            $save->photo = $name;
        }

        if ($save->save()) {
            //   //LogActivity::addToLog($request->contact_name.' Vendor Contact Update ');

            return redirect()->route('admin.vendor.contact.list')->with('message', 'Contact Update successfully');
        } else {
            return back()->with('message', 'error in save');
        }
    }

    function contact_edit(Request $request)
    {
        $save = contact::find($request->id);
        $country = ['' => 'select country'] + country::orderBy('country_name', 'asc')
                ->get()->pluck('country_name', 'id')->toArray();

        $state = ['' => 'select state'] + state::orderBy('state_name', 'asc')
                ->get()->pluck('state_name', 'id')->toArray();

        $city = ['' => 'select city'] + city::orderBy('city_name', 'asc')
                ->get()->pluck('city_name', 'id')->toArray();

        $customer = ['' => 'select customer'] + customers::query()
                ->orderBy('customer_name', 'asc')
                ->get()
                ->pluck('customer_name', 'id')
                ->toArray();

        return view("admin/contact_edit")->with(['customer' => $customer, 'country' => $country, 'state' => $state, 'city' => $city, 'data' => $save]);
    }

    function vendor_contact_edit(Request $request)
    {
        $save = vendor_contact::find($request->id);

        $vendor = ['' => 'select vendor'] + vendor::query()
                ->orderBy('vendor_name', 'asc')
                ->get()
                ->pluck('vendor_name', 'id')
                ->toArray();

        return view("admin/vendor_contact_edit")->with(['vendor' => $vendor, 'data' => $save]);
    }

    function contact_view(Request $request)
    {
        $save = contact::select('contact.*', 'customers.customer_name')
            ->leftJoin('customers', 'customers.id', 'contact.customer')
            ->where('contact.id', $request->id)
            ->first();

        $country = ['' => 'select country'] + country::orderBy('country_name', 'asc')
                ->get()->pluck('country_name', 'id')->toArray();

        $state = ['' => 'select state'] + state::orderBy('state_name', 'asc')
                ->get()->pluck('state_name', 'id')->toArray();

        $city = ['' => 'select city'] + city::orderBy('city_name', 'asc')
                ->get()->pluck('city_name', 'id')->toArray();

        $customer = ['' => 'select customer'] + customers::query()
                ->orderBy('customer_name', 'asc')
                ->get()
                ->pluck('customer_name', 'id')
                ->toArray();

        return view("admin/contact_view")->with(['customer' => $customer, 'country' => $country, 'state' => $state, 'city' => $city, 'data' => $save]);
    }

    function vendor_contact_view(Request $request)
    {
        $save = vendor_contact::select('vendor_contact.*', 'vendor.vendor_name')
            ->leftJoin('vendor', 'vendor.id', 'vendor_contact.vendor')
            ->where('vendor_contact.id', $request->id)
            ->first();

        return view("admin/vendor_contact_view")->with(['data' => $save]);
    }

    function contact_save(Request $request)
    {
        date_default_timezone_set('Asia/Kolkata');
        date_default_timezone_set('Asia/Kolkata');
        $save = new contact();

        $con = contact::where('customer', $request->customer)
            ->where('contact_name', $request->contact_name)
            ->first();

        if (empty($con)) {
            $request->validate([
                'contact_name' => 'required',
                'primary_email' => 'email:rfc,dns',
                'primary_phone' => 'required',
                'customer' => 'required',
            ]);
        } else {
            return back()->with('message', 'This Contact Already exist in this customer');
        }

        // / dd($request->all());
        $save->customer = $request->customer;
        $save->contact_name = $request->contact_name;

        $save->primary_phone = $request->primary_phone;
        $save->secondary_phone = $request->secondary_phone;
        $save->primary_email = $request->primary_email;
        $save->secondary_email = $request->secondary_email;

        $save->description = $request->description;
        $save->created_time = date('Y-m-d h:i:s A');
        $save->user_id = Session::get('user_id');
        $save->website_id = Session::get('website_id');
        $save->office_phone = $request->office_phone;
        $save->office_email = $request->office_email;
        $save->department = $request->department;
        $save->designation = $request->designation;

        if ($request->hasFile('photo')) {

            $image = $request->file('photo');
            $name = $request->primary_phone . '.' . time() . '.' . $image->getClientOriginalExtension();
            $destinationPath = '/contact_image/';
            $image->move($destinationPath, $name);

            $save->photo = $name;
        }

        if ($save->save()) {
            //  //LogActivity::addToLog($request->contact_name.' Customer Contact Create ');

            return redirect()->route('client/contact_list')->with('message', 'Contact save successfully');
        } else {
            return back()->with('message', 'error in save');
        }
    }

    function vendor_contact_save(Request $request)
    {
        date_default_timezone_set('Asia/Kolkata');
        date_default_timezone_set('Asia/Kolkata');
        $save = new vendor_contact();


        $con = contact::where('customer', $request->customer)
            ->where('contact_name', $request->contact_name)
            ->first();

        if (empty($con)) {
            $request->validate([
                'contact_name' => 'required',
                'primary_email' => 'email:rfc,dns',
                'primary_phone' => 'required',
                'vendor' => 'required',
            ]);
        } else {
            return back()->with('message', 'This Contact Already exist in this customer');
        }


        // / dd($request->all());
        $save->vendor = $request->vendor;
        $save->contact_name = $request->contact_name;

        $save->primary_phone = $request->primary_phone;
        $save->secondary_phone = $request->secondary_phone;
        $save->primary_email = $request->primary_email;
        $save->secondary_email = $request->secondary_email;

        $save->description = $request->description;
        $save->created_time = date('Y-m-d h:i:s A');
        $save->user_id = Session::get('user_id');
        $save->website_id = Session::get('website_id');
        $save->office_phone = $request->office_phone;
        $save->office_email = $request->office_email;
        $save->department = $request->department;
        $save->designation = $request->designation;

        if ($request->hasFile('photo')) {

            $image = $request->file('photo');
            $name = $request->primary_phone . '.' . time() . '.' . $image->getClientOriginalExtension();
            $destinationPath = '/contact_image/';
            $image->move($destinationPath, $name);

            $save->photo = $name;
        }

        if ($save->save()) {
            //  //LogActivity::addToLog($request->contact_name.' Vendor Contact Create ');

            return redirect()->route('admin.vendor.contact.list')->with('message', 'Contact save successfully');
        } else {
            return back()->with('message', 'error in save');
        }
    }

    function contact_add()
    {
        $country = ['' => 'select country'] + country::orderBy('country_name', 'asc')
                ->get()->pluck('country_name', 'id')->toArray();

        $state = ['' => 'select state'] + state::orderBy('state_name', 'asc')
                ->get()->pluck('state_name', 'id')->toArray();

        $city = ['' => 'select city'] + city::orderBy('city_name', 'asc')
                ->get()->pluck('city_name', 'id')->toArray();

        $customer = ['' => 'select customer'] + customers::query()
                ->orderBy('customer_name', 'asc')
                ->get()
                ->pluck('customer_name', 'id')
                ->toArray();

        return view("admin/contact_add")->with(['customer' => $customer, 'country' => $country, 'state' => $state, 'city' => $city]);
    }

    function contact_add1(Request $request)
    {
        $country = ['' => 'select country'] + country::orderBy('country_name', 'asc')
                ->get()->pluck('country_name', 'id')->toArray();

        $state = ['' => 'select state'] + state::orderBy('state_name', 'asc')
                ->get()->pluck('state_name', 'id')->toArray();

        $city = ['' => 'select city'] + city::orderBy('city_name', 'asc')
                ->get()->pluck('city_name', 'id')->toArray();

        $custom = customers::where('id', $request->cust_id)->first();

        $customer = [$custom->id => $custom->customer_name] + customers::query()
                ->orderBy('customer_name', 'asc')
                ->get()
                ->pluck('customer_name', 'id')
                ->toArray();

        return view("admin/contact_add")->with(['customer' => $customer, 'country' => $country, 'state' => $state, 'city' => $city]);
    }

    function vendor_contact_add(Request $request)
    {


        $vendor = ['' => 'select vendor'] + vendor::query()
                ->orderBy('vendor_name', 'asc')
                ->get()
                ->pluck('vendor_name', 'id')
                ->toArray();

        return view("admin/vendor_contact_add")->with(['vendor' => $vendor]);
    }

    function vendor_contact_add1(Request $request)
    {
        $vn = vendor::where('id', $request->vendor_id)->first();
        $vendor = [$vn->id => $vn->vendor_name] + vendor::query()
                ->orderBy('vendor_name', 'asc')
                ->get()
                ->pluck('vendor_name', 'id')
                ->toArray();

        return view("admin/vendor_contact_add")->with(['vendor' => $vendor]);
    }

    function contact_list(Request $request)
    {
        $contact = new contact();
        $contact = $contact->select('contact.*', 'customers.customer_name');
        $contact = $contact->leftJoin('customers', 'customers.id', 'contact.customer');
        $contact = $contact->orderBy('customers.customer_name', 'asc');
        if (isset($request->contact_name)) {
            $contact = $contact->where('contact_name', 'like', '%' . $request->contact_name . '%');
        }
        if (isset($request->customer_name)) {
            $contact = $contact->where('customers.customer_name', 'like', '%' . $request->customer_name . '%');
        }

        if (isset($request->primary_phone)) {
            $contact = $contact->where('contact.primary_phone', 'like', '%' . $request->primary_phone . '%');
        }

        if (isset($request->primary_email)) {
            $contact = $contact->where('contact.primary_email', 'like', '%' . $request->primary_email . '%');
        }

        if (isset($request->department)) {
            $contact = $contact->where('contact.department', 'like', '%' . $request->department . '%');
        }
        if (isset($request->designation)) {
            $contact = $contact->where('contact.designation', 'like', '%' . $request->designation . '%');
        }
        $result = $contact->paginate(10);

        return view("admin/contact_list")->with(['cdata' => $result]);
    }

    function vendor_contact_list(Request $request)
    {
        $contact = new vendor_contact();
        $contact = $contact->select('vendor_contact.*', 'vendor.vendor_name');
        $contact = $contact->leftJoin('vendor', 'vendor.id', 'vendor_contact.vendor');
        $contact = $contact->orderBy('vendor.vendor_name', 'asc');
        if (isset($request->contact_name)) {
            $contact = $contact->where('vendor_contact.contact_name', 'like', '%' . $request->contact_name . '%');
        }
        if (isset($request->customer_name)) {
            $contact = $contact->where('vendor_contact.vendor_name', 'like', '%' . $request->customer_name . '%');
        }
        if (isset($request->department)) {
            $contact = $contact->where('vendor_contact.department', 'like', '%' . $request->department . '%');
        }
        if (isset($request->designation)) {
            $contact = $contact->where('vendor_contact.designation', 'like', '%' . $request->designation . '%');
        }
        $result = $contact->paginate(10);

        return view("admin/vendor_contact_list")->with(['cdata' => $result]);
    }

    function contact_list_preview(Request $request)
    {
        $contact = new contact();
        $contact = $contact->select('contact.*', 'customers.customer_name');
        $contact = $contact->leftJoin('customers', 'customers.id', 'contact.customer');
        $contact = $contact->orderBy('customers.customer_name', 'asc');
        if (isset($request->contact_name)) {
            $contact = $contact->where('contact_name', 'like', '%' . $request->contact_name . '%');
        }
        if (isset($request->customer_name)) {
            $contact = $contact->where('customers.customer_name', 'like', '%' . $request->customer_name . '%');
        }

        if (isset($request->primary_phone)) {
            $contact = $contact->where('contact.primary_phone', 'like', '%' . $request->primary_phone . '%');
        }
        if (isset($request->primary_email)) {
            $contact = $contact->where('contact.primary_email', 'like', '%' . $request->primary_email . '%');
        }

        if (isset($request->department)) {
            $contact = $contact->where('contact.department', 'like', '%' . $request->department . '%');
        }
        if (isset($request->designation)) {
            $contact = $contact->where('contact.designation', 'like', '%' . $request->designation . '%');
        }
        $contact = $contact->where('contact.customer', $request->id);
        $result = $contact->paginate(10);

        return view("admin/contact_list_preview")->with(['cdata' => $result]);
    }


    function vendor_list_preview(Request $request)
    {

        $contact = new vendor_contact();

        $contact = $contact->select('vendor_contact.*', 'vendor.vendor_name');
        $contact = $contact->leftJoin('vendor', 'vendor.id', 'vendor_contact.vendor');
        $contact = $contact->orderBy('vendor.vendor_name', 'asc');
        if (isset($request->contact_name)) {
            $contact = $contact->where('vendor_contact.contact_name', 'like', '%' . $request->contact_name . '%');
        }
        if (isset($request->primary_phone)) {
            $contact = $contact->where('vendor_contact.primary_phone', 'like', '%' . $request->primary_phone . '%');
        }
        if (isset($request->primary_email)) {
            $contact = $contact->where('vendor_contact.primary_email', 'like', '%' . $request->primary_email . '%');
        }
        if (isset($request->customer_name)) {
            $contact = $contact->where('vendor.vendor_name', 'like', '%' . $request->customer_name . '%');
        }
        if (isset($request->department)) {
            $contact = $contact->where('vendor_contact.department', 'like', '%' . $request->department . '%');
        }
        if (isset($request->designation)) {
            $contact = $contact->where('vendor_contact.designation', 'like', '%' . $request->designation . '%');
        }

        $contact = $contact->where('vendor_contact.vendor', $request->id);

        $result = $contact->paginate(10);

        return view("admin/vendor_list_preview")->with(['cdata' => $result]);
    }

    function action_product(Request $request)
    {

        if ($request->quotation == "quotation") {
            $customer = ['' => 'select customer'] + customers::query()
                    ->orderBy('customer_name', 'asc')
                    ->get()
                    ->pluck('customer_name', 'id')
                    ->toArray();

            $count = count($request->product_id);
            $count;

            $str = '';
            $srno = 0;
            $product1 = product::select('product.*', 'gst.gst_per', 'uom.uom_name')
                ->leftJoin('gst', 'gst.id', 'product.gst')
                ->leftJoin('uom', 'uom.id', 'product.uom')
                ->where('product.status', 'product')
                ->get();

            for ($i = 0; $i < $count; $i++) {
                $srno++;
                $p = product::select('product.*', 'gst.gst_per', 'uom.uom_name')
                    ->leftJoin('gst', 'gst.id', 'product.gst')
                    ->leftJoin('uom', 'uom.id', 'product.uom')
                    ->where('product.status', 'product')
                    ->Where('product.id', $request->product_id[$i])
                    ->first();

                $str .= '<tr class="gradeX" id="row' . $srno . '">
                                                <td style="width: 30%">
                                                    <div class="input-group">';
                $str .= '<select class="form-control" onchange="get_product(this.value,' . $srno . ')" name="product[]" id="product' . $srno . '">
                                                        <option value="' . $p->id . '">' . $p->product_name . '</option>';
                foreach ($product1 as $prod) {
                    if ($p->id == $prod->id) {
                    } else {
                        $str .= '<option value="' . $prod->id . '">' . $prod->product_name . '</option>';
                    }
                }
                $str .= '</select>';
                $str .= '<div class="input-group-btn">
      <a class="btn btn-default" id="product_btn' . $srno . '" onclick="product_search(' . $srno . ')">
        <img src="' . asset('public/product_icon.png') . '" style="height:20px ">
      </a>
    </div>
  </div>

                                                    <div class="form-group">
                                                    <label>  </label>
                                                        <textarea id="description' . $srno . '" name="description[]" class="form-control">' . $p->description . '</textarea>

                                                </div>
                                                </td>
                                               <td style="vertical-align: top !important;">
                                                   <input type="text" class="form-control" name="inner_diamitter[]" id="inner_diamitter' . $srno . '" value="' . $p->inner_diameter . '">
                                               </td>
                                               <td style="vertical-align: top !important;">
                                                   <input type="text" class="form-control" name="outer_diamitter[]" id="outer_diamitter' . $srno . '" value="' . $p->outer_diameter . '">
                                               </td>
                                               <td style="vertical-align: top !important;">
                                                   <input type="text" class="form-control" name="thikness[]" id="thikness1" value="' . $p->thikness . '">
                                               </td>
                                               <td style="vertical-align: top !important;">
                                                   <input type="text" class="form-control" name="hsn[]" id="hsn' . $srno . '" value="' . $p->hsn . '">
                                               </td>
                                                <td style="vertical-align: top !important;">
                                                    <input type="text" name="qty[]" onkeyup="cal(this)" class="qty form-control" id="qty' . $srno . '" >
                                                </td>
                                                <td style="vertical-align: top !important;">
                                                    <input type="text" name="price[]" class="price form-control" id="price' . $srno . '" onkeyup="cal(this)">
                                                </td>

                                                <td style="vertical-align: top !important;">
                                                    <input type="text" name="total_amount[]" class="total form-control" id="total_amount' . $srno . '">
                                                </td>
                                                 <td style="vertical-align: top !important;">
                                                    <input type="text" name="discount_per[]" class="discount_per form-control" id="discount_per' . $srno . '">
                                                </td>
                                                <td style="vertical-align: top !important;">
                                                    <input type="text" name="discount_amount[]" class="discount_amount form-control" id="discount_amount' . $srno . '">
                                                </td>
                                                <td style="vertical-align: top !important;">
                                                    <input type="text" name="gst_per[]" class="gst_per form-control" id="gst_per' . $srno . '" value="' . $p->gst . '">
                                                </td>
                                                <td style="vertical-align: top !important;">
                                                    <input type="text" name="gst_amount[]" class="gst_amount form-control" id="gst_amount' . $srno . '">
                                                </td>
                                                 <td style="vertical-align: top !important;">
                                                    <input type="text" name="net_price[]" class="netprice form-control" id="net_price' . $srno . '">
                                                </td>
                                                <td class="actions" style="vertical-align: top !important;">
                                                    <a onclick="remove_row(this)" style="cursor: pointer;" class="on-editing save-row" title="save"><i class="fa fa-trash" style="font-size: 22px"></i></a>
                                                   <!--  <a href="#" class="on-editing cancel-row"><i class="fa fa-times"></i></a> -->
                                                   <!--  <a href="#" class="on-default edit-row"><i class="fa fa-pencil"></i></a>
                                                    <a href="#" class="on-default remove-row"><i class="fa fa-trash-o"></i></a> -->
                                                </td>
                                            </tr>';

            }


            $term = ['' => 'select module'] + module_rights::select("module_rights.*", 'module.module_name', 'module.id as moduleid')
                    ->leftJoin("module", "module.id", "module_rights.module_id")
                    ->where("module_rights.user_id", Session::get('user_id'))
                    ->get()->pluck('module_name', 'moduleid')->toArray();

            $service = product::select('product.*', 'gst.gst_per', 'uom.uom_name')
                ->leftJoin('gst', 'gst.id', 'product.gst')
                ->leftJoin('uom', 'uom.id', 'product.uom')
                ->where('product.status', 'service')
                ->orderBy('product.product_name', 'asc')
                ->get();

            return view("admin.quotation_add1")->with(['customer' => $customer, 'product' => $product1, 'service' => $service, 'terms' => $term, 'str' => $str]);
        }
        if ($request->salesorder == "salesorder") {

        }

    }

    function filter_product(Request $request)
    {
        $product = new product();
        $product_name = $material = $category = $inner_from = $inner_to = $outer_from = $outer_to = $thikness = "";
        if ($request->product_name != '') {
            $product_name = $request->product_name;
            $product = $product->Where('product_name', 'like', '%' . $request->product_name . '%');
        }

        if ($request->material != '') {
            $material = $request->material;
            $product = $product->Where('material_name', 'like', '%' . $request->material . '%');
        }

        if ($request->category != '') {
            $category = $request->category;
            $product = $product->Where('category_name', 'like', '%' . $request->category . '%');
        }

        if ($request->inner_from != '' and $request->inner_to == '') {
            $inner_from = $request->inner_from;
            $product = $product->where('inner_diameter', '>=', $request->inner_from);
        }

        if ($request->inner_from == '' and $request->inner_to != '') {
            $inner_to = $request->inner_to;
            $product = $product->where('inner_diameter', '<=', $request->inner_to);
        }

        if ($request->inner_from != '' and $request->inner_to != '') {
            $inner_from = $request->inner_from;
            $inner_to = $request->inner_to;

            $product = $product->whereBetween('inner_diameter', array($request->inner_from, $request->inner_to));
        }

        if ($request->outer_from != '' and $request->outer_to == '') {
            $outer_from = $request->outer_from;

            $product = $product->where('outer_diameter', '>=', $request->outer_from);
        }

        if ($request->outer_from == '' and $request->outer_to != '') {
            $outer_to = $request->outer_to;
            $product = $product->where('outer_diameter', '<=', $request->outer_to);
        }

        if ($request->outer_from != '' and $request->outer_to != '') {
            $outer_from = $request->outer_from;
            $outer_to = $request->outer_to;
            $product = $product->whereBetween('outer_diameter', array($request->outer_from, $request->outer_to));
        }

        if ($request->thik != '') {
            $thikness = $request->thik;
            $product = $product->Where('thikness', 'like', '%' . $request->thik . '%');
        }
        $product = $product;
        $product = $product->where('status', 'product');
        //echo print_r($request->all());
        $result = $product->paginate(10);
        //dd($result);

        return view("admin/search_product")
            ->with(['data' => $result, 'product_name' => $product_name, 'material' => $material, 'inner_from' => $inner_from, 'inner_to' => $inner_to, 'outer_from' => $outer_from, 'outer_to' => $outer_to, 'category' => $category, 'thikness' => $thikness]);
    }

    function search_product(Request $request)
    {
        // $pagesize = $request->pagesize;
        $data = new product();
        $data = $data->select("product.id", "product.product_name", "product.hsn", "product.price", "gst.gst_per", "product.product_image", "product.item_code");
        $data = $data->leftJoin("gst", "gst.id", "product.gst");
        if (isset($request->product_name)) {

            $data = $data->orwhere("product.product_name", "LIKE", '%' . $request->product_name . '%');

        }
        if (isset($request->item_code)) {

            $data = $data->orwhere("product.item_code", "LIKE", '%' . $request->item_code . '%');

        }

        if (isset($request->prices)) {
            $data = $data->orwhere("product.price", "LIKE", '%' . $request->prices . '%');
        }
        if (isset($request->gst)) {
            $data = $data->orwhere("gst.gst_per", "LIKE", '%' . $request->gst . '%');
        }
        $data = $data->where("product.status", "product");
        $data = $data->paginate(25);
        // $data=$data->paginate(is_null($pagesize) ? 1 : $pagesize);
        //dd($data);

        $service = product::select('product.*', 'gst.gst_per', 'uom.uom_name', 'category.category_image', 'category.category_name as catname', 'material.material_name as matname')
            ->leftJoin('gst', 'gst.id', 'product.gst')
            ->leftJoin('category', 'category.id', 'product.category')
            ->leftJoin('material', 'material.id', 'product.material')
            ->leftJoin('uom', 'uom.id', 'product.uom')
            ->where('product.status', 'service')
            ->orderBy('product.product_name', 'asc')
            ->get();

        $customer = customers::orderBy('customer_name', 'asc')->get();

        return view("admin/search_product")->with(['data' => $data, 'customer' => $customer, 'service' => $service]);
    }

    function terms_save(Request $request)
    {
        $terms = new terms();
        $terms->module = $request->module;
        $terms->description = $request->description;
        $terms->website_id = Session::get('website_id');
        $terms->user_id = Session::get('user_id');
        if ($terms->save()) {
            return redirect()->route("admin.term.list")->with("message", "terms & condition save successfully");
        }
    }

    function terms_add()
    {
        $module = ['' => 'select module'] + module_rights::select("module_rights.*", 'module.module_name', 'module.id as moduleid')
                ->leftJoin("module", "module.id", "module_rights.module_id")
                ->where("module_rights.user_id", Session::get('user_id'))
                ->get()->pluck('module_name', 'moduleid')->toArray();

        return view("admin/terms_add")->with(['module' => $module]);
    }

    function type_delete(Request $request)
    {
        $industry = type::find($request->id);
        if ($industry->delete()) {
            return redirect()->route("admin.type.list")->with("message", "type delete successfully");
        }
    }

    function industry_delete(Request $request)
    {
        $industry = industry::find($request->id);
        if ($industry->delete()) {
            return redirect()->route("admin.industry.list")->with("message", "industry delete successfully");
        }
    }

    function type_list()
    {
        $list = type::orderBy("type_name", "asc")->get();
        return view("admin/type_list")->with(['data' => $list]);
    }

    function type_update(Request $request)
    {
        $industry = type::find($request->id);
        $industry->type_name = $request->type_name;
        $industry->website_id = Session::get('website_id');
        if ($industry->save()) {
            return redirect()->route("admin.type.list")->with("message", "type update successfully");
        }

    }

    function type_edit(Request $request)
    {
        $industry = type::find($request->id);

        return view("admin/type_edit")->with(['data' => $industry]);
    }

    function type_save(Request $request)
    {
        $industry = new type();
        $industry->type_name = $request->type_name;
        $industry->website_id = Session::get('website_id');
        if ($industry->save()) {
            return redirect()->route("admin.type.list")->with("message", "type save successfully");
        }
    }

    function type_add()
    {
        return view("admin/type_add");
    }

    function industry_update(Request $request)
    {
        $industry = industry::find($request->id);
        $industry->industry_name = $request->industry_name;
        $industry->website_id = Session::get('website_id');
        if ($industry->save()) {
            return redirect()->route("admin.industry.list")->with("message", "industry update successfully");
        }

    }

    function industry_edit(Request $request)
    {
        $industry = industry::find($request->id);

        return view("admin/industry_edit")->with(['data' => $industry]);
    }

    function industry_save(Request $request)
    {
        $industry = new industry();
        $industry->industry_name = $request->industry_name;
        $industry->website_id = Session::get('website_id');
        if ($industry->save()) {
            return redirect()->route("admin.industry.list")->with("message", "industry save successfully");
        }
    }

    function industry_add()
    {
        return view("admin/industry_add");
    }

    function industry_list()
    {
        $list = industry::orderBy("industry_name", "asc")->paginate(10);
        return view("admin/industry_list")->with(['data_list' => $list]);
    }


    function quot_mail(Request $request)
    {
        //dd($request->all());


        $quot = quotation::select('quotation.*', 'customers.*', 'contact.contact_name', 'contact.primary_phone', 'website_user.first_name', 'website_user.last_name')
            ->leftJoin('customers', 'customers.id', 'quotation.customer')
            ->leftJoin('contact', 'contact.id', 'quotation.contact_name')
            ->leftJoin('website_user', 'website_user.id', 'quotation.user_id')
            ->where('quotation.id', $request->qid)
            ->first();

        $quotitem = quotation_item::select('quot_item.*', 'product.product_name', 'product.make', 'product.model', 'product.product_image', 'product.material_name', 'category.category_image')
            ->leftJoin('product', 'product.id', 'quot_item.product')
            ->leftJoin('category', 'category.id', 'product.category')
            ->where('quot_item.quot_no', $quot->quot_no)
            ->get();

        $discsum = quotation_item::select('quot_item.*', 'product.product_name', 'product.make', 'product.model', 'product.product_image', 'product.material_name', 'category.category_image')
            ->leftJoin('product', 'product.id', 'quot_item.product')
            ->leftJoin('category', 'category.id', 'product.category')
            ->where('quot_item.quot_no', $quot->quot_no)
            ->sum('quot_item.discount_amount');

        //dd($quotitem);
        $company = company::query()->first();

        $terms = terms::query()->first();

        $filename = $quot->customer_name;

        $filename .= '.pdf';

        $data = array('body' => $request->to_body);


        $pdf = PDF::loadView('admin.quotation_print', compact('quot', 'quotitem', 'company', 'terms', 'discsum'));

        //$data=array('customer_name',$quot->customer_name);
        $array = explode(',', $request->to_email);
        $tocc = explode(',', $request->to_cc);
        //$body=$request
        //dd($content);
        $subject = $request->to_subject;
        $to = "saggyt19@gmail.com" ?? 'erp@nowtowow.co.in';
        Mail::send('emails.quotation', $data, function ($message) use ($pdf, $filename, $to, $subject, $array, $tocc) {
            $message->from('erp@nowtowow.co.in', 'Quotation');
            foreach ($array as $val) {
                $message->to($val)->subject($subject);
            }
            if (empty($tocc)) {
            } else {
                foreach ($tocc as $val1) {
                    if (empty($val1)) {
                    } else {
                        $message->cc($val1)->subject($subject);
                    }

                }
            }

            $message->attachData($pdf->output(), $filename);
        });

        return back()->with("message", "mail send successfully");
    }


    function quot_normal_mail(Request $request)
    {
        // dd($request->all());

        $quot = quotation::select('quotation.*', 'customers.*', 'contact.contact_name', 'contact.primary_phone', 'website_user.user_name')
            ->leftJoin('customers', 'customers.id', 'quotation.customer')
            ->leftJoin('contact', 'contact.id', 'quotation.contact_name')
            ->leftJoin('website_user', 'website_user.id', 'quotation.user_id')
            ->where('quotation.id', $request->qid)
            ->first();

        $quotitem = quotation_item::select('quot_item.*', 'product.product_name', 'product.make', 'product.model', 'product.product_image', 'product.material_name', 'category.category_image')
            ->leftJoin('product', 'product.id', 'quot_item.product')
            ->leftJoin('category', 'category.id', 'product.category')
            ->where('quot_item.quot_no', $quot->quot_no)
            ->get();

        $discsum = quotation_item::select('quot_item.*', 'product.product_name', 'product.make', 'product.model', 'product.product_image', 'product.material_name', 'category.category_image')
            ->leftJoin('product', 'product.id', 'quot_item.product')
            ->leftJoin('category', 'category.id', 'product.category')
            ->where('quot_item.quot_no', $quot->quot_no)
            ->sum('quot_item.discount_amount');

        //dd($quotitem);
        $company = company::query()->first();

        $terms = terms::query()->first();

        $filename = $quot->customer_name;

        $filename .= '.pdf';

        $data = array('body' => $request->to_body);


        $pdf = PDF::loadView('admin.quot_normal_print', compact('quot', 'quotitem', 'company', 'terms', 'discsum'));

        //$data=array('customer_name',$quot->customer_name);
        $array = explode(',', $request->to_email);
        $tocc = explode(',', $request->to_cc);
        //$body=$request
        //dd($content);
        $subject = $request->to_subject;
        $to = "saggyt19@gmail.com" ?? 'erp@nowtowow.co.in';
        Mail::send('emails.quotation', $data, function ($message) use ($pdf, $filename, $to, $subject, $array, $tocc) {
            $message->from('erp@nowtowow.co.in', 'Quotation');
            foreach ($array as $val) {
                $message->to($val)->subject($subject);
            }
            if (empty($tocc)) {
            } else {
                foreach ($tocc as $val1) {
                    if (empty($val1)) {
                    } else {
                        $message->cc($val1)->subject($subject);
                    }

                }
            }

            $message->attachData($pdf->output(), $filename);
        });

        return back()->with("message", "mail send successfully");
    }

    function service_renewal_delete(Request $request)
    {
        $sr = service_renewal::find($request->id);

        $book = new service_renewal_book();
        $book->service_renewal_id = $sr->id;
        $book->service = $request->service;
        $book->category = $request->category;
        $book->customer = $request->customer;
        $book->usage_unit = $request->usage_unit;
        $book->sales_start_date = date('Y-m-d', strtotime($request->sales_start_date));
        $book->sales_end_date = date('Y-m-d', strtotime($request->sales_end_date));
        $book->support_start_date = date('Y-m-d', strtotime($request->support_start_date));
        $book->support_expiry_date = date('Y-m-d', strtotime($request->support_expiry_date));
        $book->price = $request->price;
        $book->purchase_cost = $request->purchase_cost;
        $book->website_id = Session::get('website_id');
        $book->user_id = Session::get('user_id');
        $book->particular = 'Service Renewal Delete By ' . Session::get('user_name') . '';
        $book->save();
        return redirect()->route("client/service_renew")->with("message", "service renewal update successfully");

    }

    function service_renewal_update(Request $request)
    {
        $sr = service_renewal::find($request->id);
        $sr->service = $request->service;
        $sr->category = $request->category;
        $sr->customer = $request->customer;
        $sr->usage_unit = $request->usage_unit;
        $sr->sales_start_date = date('Y-m-d', strtotime($request->sales_start_date));
        $sr->sales_end_date = date('Y-m-d', strtotime($request->sales_end_date));
        $sr->support_start_date = date('Y-m-d', strtotime($request->support_start_date));
        $sr->support_expiry_date = date('Y-m-d', strtotime($request->support_expiry_date));
        $sr->price = $request->price;
        $sr->purchase_cost = $request->purchase_cost;
        $sr->website_id = Session::get('website_id');
        $sr->user_id = Session::get('user_id');
        if ($sr->save()) {
            $book = new service_renewal_book();
            $book->service_renewal_id = $sr->id;
            $book->service = $request->service;
            $book->category = $request->category;
            $book->customer = $request->customer;
            $book->usage_unit = $request->usage_unit;
            $book->sales_start_date = date('Y-m-d', strtotime($request->sales_start_date));
            $book->sales_end_date = date('Y-m-d', strtotime($request->sales_end_date));
            $book->support_start_date = date('Y-m-d', strtotime($request->support_start_date));
            $book->support_expiry_date = date('Y-m-d', strtotime($request->support_expiry_date));
            $book->price = $request->price;
            $book->purchase_cost = $request->purchase_cost;
            $book->website_id = Session::get('website_id');
            $book->user_id = Session::get('user_id');
            $book->particular = 'Service Renewal Update By ' . Session::get('user_name') . '';
            $book->save();
            return redirect()->route("client/service_renew")->with("message", "service renewal update successfully");
        }
    }

    function service_renewal_edit(Request $request)
    {
        $data = service_renewal::find($request->id);

        $customer = ['' => 'select customer'] + customers::query()
                ->orderBy('customer_name', 'asc')
                ->get()->pluck('customer_name', 'id')->toArray();

        $service = ['' => 'select service'] + product::query()
                ->where('status', 'service')
                ->orderBy('product_name', 'asc')
                ->get()->pluck('product_name', 'id')->toArray();

        $category = ['' => 'select category'] + category::query()
                ->orderBy('category_name', 'asc')
                ->get()->pluck('category_name', 'id')->toArray();

        $usage_unit = ['' => 'select uom'] + uom::query()
                ->orderBy('uom_name', 'asc')
                ->get()->pluck('uom_name', 'id')->toArray();

        return view("admin/renew_edit")->with(['data' => $data, 'customer' => $customer, 'service' => $service, 'category' => $category, 'usage_unit' => $usage_unit]);
    }

    function service_renewal_save(Request $request)
    {
        $sr = new service_renewal();
        $sr->service = $request->service;
        $sr->category = $request->category;
        $sr->customer = $request->customer;
        $sr->usage_unit = $request->usage_unit;
        $sr->sales_start_date = date('Y-m-d', strtotime($request->sales_start_date));
        $sr->sales_end_date = date('Y-m-d', strtotime($request->sales_end_date));
        $sr->support_start_date = date('Y-m-d', strtotime($request->support_start_date));
        $sr->support_expiry_date = date('Y-m-d', strtotime($request->support_expiry_date));
        $sr->price = $request->price;
        $sr->purchase_cost = $request->purchase_cost;
        $sr->website_id = Session::get('website_id');
        $sr->user_id = Session::get('user_id');
        if ($sr->save()) {
            $book = new service_renewal_book();
            $book->service_renewal_id = $sr->id;
            $book->service = $request->service;
            $book->category = $request->category;
            $book->customer = $request->customer;
            $book->usage_unit = $request->usage_unit;
            $book->sales_start_date = date('Y-m-d', strtotime($request->sales_start_date));
            $book->sales_end_date = date('Y-m-d', strtotime($request->sales_end_date));
            $book->support_start_date = date('Y-m-d', strtotime($request->support_start_date));
            $book->support_expiry_date = date('Y-m-d', strtotime($request->support_expiry_date));
            $book->price = $request->price;
            $book->purchase_cost = $request->purchase_cost;
            $book->website_id = Session::get('website_id');
            $book->user_id = Session::get('user_id');
            $book->particular = 'Service Renewal Add By ' . Session::get('user_name') . '';
            $book->save();
            return redirect()->route("client/service_renew")->with("message", "service renewal add successfully");
        }
    }

    function renew_add(Request $request)
    {
        $customer = ['' => 'select customer'] + customers::query()
                ->orderBy('customer_name', 'asc')
                ->get()->pluck('customer_name', 'id')->toArray();

        $service = ['' => 'select service'] + product::query()
                ->where('status', 'service')
                ->orderBy('product_name', 'asc')
                ->get()->pluck('product_name', 'id')->toArray();

        $category = ['' => 'select category'] + category::query()
                ->orderBy('category_name', 'asc')
                ->get()->pluck('category_name', 'id')->toArray();

        $usage_unit = ['' => 'select uom'] + uom::query()
                ->orderBy('uom_name', 'asc')
                ->get()->pluck('uom_name', 'id')->toArray();

        return view("admin/renew_add")->with(['customer' => $customer, 'service' => $service, 'category' => $category, 'usage_unit' => $usage_unit]);
    }

    function service_renew()
    {
        $data = service_renewal::select('service_renewal.*', 'customers.customer_name', 'uom.uom_name', 'category.category_name', 'product.product_name')
            ->leftJoin('customers', 'customers.id', 'service_renewal.customer')
            ->leftJoin('uom', 'uom.id', 'service_renewal.usage_unit')
            ->leftJoin('category', 'category.id', 'service_renewal.category')
            ->leftJoin('product', 'product.id', 'service_renewal.service')
            ->orderBy('service_renewal.support_expiry_date', 'desc')
            ->get();
        //dd($data);
        return view("admin/service_renew")->with(['data' => $data]);
    }

    function quot_update(Request $request)
    {
        $now = date('Y-m-d', strtotime($request->quot_date));
        $finacial_year = finacial_year::where('start_date', '<=', $now)->where('end_date', '>=', $now)
            ->first();
        //dd($request->all());
        $request->validate([
            'quot_stage' => 'required',
            'valid_until' => 'required',
            'customer' => 'required',
            'quot_date' => 'required',
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

        $customer_name = customers::find($request->customer);

        $quotation = quotation::find($request->id);
        $quotation->quot_date = date('Y-m-d', strtotime($request->quot_date));
        $quotation->subject = $request->subject;
        $quotation->customer = $request->customer;
        $quotation->customer_name = $customer_name->customer_name;
        $quotation->contact_name = $request->contact_name;
        $quotation->quot_stage = $request->quot_stage;
        $quotation->valid_until = date('Y-m-d', strtotime($request->valid_until));
        $quotation->billing_address = $request->billing_address;
        $quotation->shipping_address = $request->shipping_address;
        $quotation->billing_pobox = $request->billing_pobox;
        $quotation->shipping_pobox = $request->shipping_pobox;
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
        $quotation->cgsttotal = $request->cgsttotal;
        $quotation->sgsttotal = $request->sgsttotal;
        $quotation->adjustment = $request->adjustment;
        $quotation->net_amount = $request->item_total;
        $quotation->discount_total = $request->discount_total;
        $quotation->grand_total = $request->grand_total;
        $quotation->module = $request->module;
        $quotation->remark = $request->remark;
        $quotation->invoice_no = $request->invoice_no;
        $quotation->finacial_year = $finacial_year->id;
        $quotation->payment_terms = $request->payment_terms;
        $quotation->salesman_id = $request->salesman_id;
        $quotation->updated_by = Auth::user()->id;
        if ($quotation->save()) {
            //   //LogActivity::addToLog($request->quotation_no.' Quotation Update');

            $q = quotation::find($quotation->id);
            if (empty($request->product)) {
            } else {
                quotation_item::where('quotation_no', $q->quotation_no)->delete();
                $totproduct = count($request->product);
                for ($i = 0; $i < $totproduct; $i++) {
                    $item = new quotation_item();
                    $item->quot_no = $q->quot_no;
                    $item->product = $request->product[$i];
                    $item->description = $request->description[$i];
                    $item->qty = $request->qty[$i];
                    $item->customer = $request->customer;
                    $item->quotation_no = $request->quotation_no;
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
                    $item->amount = $request->total_amount[$i];
                    $item->cgst_per = $request->cgst_per[$i];
                    $item->cgst_amount = $request->cgst_amount[$i];
                    $item->sgst_per = $request->sgst_per[$i];
                    $item->sgst_amount = $request->sgst_amount[$i];
                    $item->gst_per = $request->gst_per[$i];
                    $item->gst_amount = $request->gst_amount[$i];

                    $item->discount_per = $request->discount_per[$i];
                    $item->discount_amount = $request->discount_amount[$i];

                    $item->grand_total = $request->net_price[$i];
                    $item->save();

                    ////LogActivity::addToLog($request->product[$i].' Product Add in '. $request->quotation_no);

                }
            }


            return redirect()->route("admin.quotation.list")->with('message', 'quotation update successfully');
        } else {
            return back();
        }

    }

    function quot_normal_update(Request $request)
    {
        $request->validate([
            'quot_stage' => 'required',
            'valid_until' => 'required',
            'customer' => 'required',
            'quot_date' => 'required',
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

        $customer_name = customers::find($request->customer);

        $quotation = quotation::find($request->id);
        $quotation->quot_date = date('Y-m-d', strtotime($request->quot_date));
        $quotation->subject = $request->subject;
        $quotation->customer = $request->customer;
        $quotation->customer_name = $customer_name->customer_name;
        $quotation->contact_name = $request->contact_name;
        $quotation->quot_stage = $request->quot_stage;
        $quotation->valid_until = date('Y-m-d', strtotime($request->valid_until));
        $quotation->billing_address = $request->billing_address;
        $quotation->shipping_address = $request->shipping_address;
        $quotation->billing_pobox = $request->billing_pobox;
        $quotation->shipping_pobox = $request->shipping_pobox;
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
        $quotation->module = $request->module;
        $quotation->remark = $request->remark;
        $quotation->invoice_no = $request->invoice_no;

        if ($quotation->save()) {
            $q = quotation::find($quotation->id);
            if (empty($request->product)) {
            } else {
                quotation_item::where('quot_no', $q->quot_no)->delete();
                $totproduct = count($request->product);
                for ($i = 0; $i < $totproduct; $i++) {
                    $item = new quotation_item();
                    $item->quot_no = $q->quot_no;
                    $item->product = $request->product[$i];
                    $item->description = $request->description[$i];
                    $item->qty = $request->qty[$i];
                    $item->customer = $request->customer;
                    $item->quotation_no = $request->quotation_no;
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
                    $item->amount = $request->total_amount[$i];
                    $item->cgst_per = $request->cgst_per[$i];
                    $item->cgst_amount = $request->cgst_amount[$i];
                    $item->sgst_per = $request->sgst_per[$i];
                    $item->sgst_amount = $request->sgst_amount[$i];
                    $item->gst_per = $request->gst_per[$i];
                    $item->gst_amount = $request->gst_amount[$i];

                    $item->discount_per = $request->discount_per[$i];
                    $item->discount_amount = $request->discount_amount[$i];

                    $item->grand_total = $request->net_price[$i];
                    $item->save();
                }
            }
            return redirect()->route("quotation_normal_list")->with('message', 'quotation update successfully');
        } else {
            return back();
        }

    }

    function quot_duplicate(Request $request)
    {

        $now = date('Y-m-d', strtotime($request->quot_date));
        //die;
        $finacial_year = finacial_year::where('start_date', '<=', $now)->where('end_date', '>=', $now)
            ->first();
        //dd($finacial_year);
        $qno = quotation::where("finacial_year", $finacial_year->id)
            ->max('quot_no');

        //dd($qno);

        date_default_timezone_set('Asia/Kolkata');

        $customer_name = customers::find($request->customer);

        $request->validate([
            'quot_stage' => 'required',
            'valid_until' => 'required',
            'customer' => 'required',
            'quot_date' => 'required',
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
        //$qno=quotation::max('quot_no');

        if (empty($qno)) {
            $year = date("y", strtotime($request->quot_date));
            $nextyear = $year + 1;

            $n2 = str_pad($qno + 1, 4, 0, STR_PAD_LEFT);
            $qno = 0;
            $n2 .= '/';
            $n2 .= $year;
            $n2 .= '-';
            $n2 .= $nextyear;
        } else {
            $year = date("y", strtotime($request->quot_date));
            $nextyear = $year + 1;
            $n2 = str_pad($qno + 1, 4, 0, STR_PAD_LEFT);
            $n2 .= '/';
            $n2 .= $year;
            $n2 .= '-';
            $n2 .= $nextyear;
        }
        // dd($n2);
        $customer_name = customers::find($request->customer);

        $quotation = new quotation();
        $quotation->quot_no = $qno + 1;
        $quotation->quotation_no = $n2;
        $quotation->quot_date = date('Y-m-d', strtotime($request->quot_date));
        $quotation->subject = $request->subject;
        $quotation->customer = $request->customer;
        $quotation->contact_name = $request->contact_name;
        $quotation->customer_name = $customer_name->customer_name;
        $quotation->quot_stage = $request->quot_stage;
        $quotation->valid_until = date('Y-m-d', strtotime($request->valid_until));
        $quotation->billing_address = $request->billing_address;
        $quotation->shipping_address = $request->shipping_address;
        $quotation->billing_pobox = $request->billing_pobox;
        $quotation->shipping_pobox = $request->shipping_pobox;
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
        $quotation->gst_amount = $request->igsttotal;
        $quotation->cgsttotal = $request->cgsttotal;
        $quotation->sgsttotal = $request->sgsttotal;

        $quotation->adjustment = $request->adjustment;
        $quotation->discount_total = $request->discount_total;
        $quotation->net_amount = $request->item_total;
        $quotation->grand_total = $request->grand_total;
        $quotation->module = $request->module;
        $quotation->remark = $request->remark;
        $quotation->datetime = date('Y-m-d h:i:s a');
        $quotation->payment_terms = $request->payment_terms;
        $quotation->finacial_year = $finacial_year->id;

        if ($quotation->save()) {
            if (empty($request->product)) {
            } else {
                $totproduct = count($request->product);
                for ($i = 0; $i < $totproduct; $i++) {

                    $item = new quotation_item();
                    $item->quot_no = $qno + 1;
                    $item->quotation_no = $n2;
                    $item->product = $request->product[$i];
                    $item->description = $request->description[$i];
                    $item->qty = $request->qty[$i];
                    $item->customer = $request->customer;
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
                    $item->amount = $request->total_amount[$i];

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

            if (session()->has('cart')) {
                $cart = session()->get('cart');
                foreach (session('cart') as $id => $details) {
                    unset($cart[$id]);
                    session()->put('cart', $cart);
                }
            }


            //     //LogActivity::addToLog($n2.' Quotation Create');

            return redirect()->route("quotation_list")->with('message', 'quotation create successfully');
        } else {
            return back();
        }


    }

    function quot_save(Request $request)
    {

        $now = date('Y-m-d', strtotime($request->quot_date));
        //die;
        $finacial_year = finacial_year::where('start_date', '<=', $now)->where('end_date', '>=', $now)
            ->first();
        $start = date("y", strtotime($finacial_year->start_date));
        $end = date("y", strtotime($finacial_year->end_date));
        //dd($finacial_year);
        $qno = quotation::where("finacial_year", $finacial_year->id)
            ->max('quot_no');

        //dd($qno);

        date_default_timezone_set('Asia/Kolkata');

        $customer_name = customers::find($request->customer);

        $request->validate([
            'quot_stage' => 'required',
            'valid_until' => 'required',
            'customer' => 'required',
            'quot_date' => 'required',
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
        //$qno=quotation::max('quot_no');

        if (empty($qno)) {
            $year = date("y", strtotime($request->quot_date));
            $nextyear = $year + 1;

            $n2 = str_pad($qno + 1, 4, 0, STR_PAD_LEFT);
            $qno = 0;
            $n2 .= '/';
            $n2 .= $start;
            $n2 .= '-';
            $n2 .= $end;
        } else {
            $year = date("y", strtotime($request->quot_date));
            $nextyear = $year + 1;
            $n2 = str_pad($qno + 1, 4, 0, STR_PAD_LEFT);
            $n2 .= '/';
            $n2 .= $start;
            $n2 .= '-';
            $n2 .= $end;
        }
        // dd($n2);
        $customer_name = customers::find($request->customer);

        $quotation = new quotation();
        $quotation->quot_no = $qno + 1;
        $quotation->quotation_no = $n2;
        $quotation->quot_date = date('Y-m-d', strtotime($request->quot_date));
        $quotation->subject = $request->subject;
        $quotation->customer = $request->customer;
        $quotation->contact_name = $request->contact_name;
        $quotation->customer_name = $customer_name->customer_name;
        $quotation->quot_stage = $request->quot_stage;
        $quotation->valid_until = date('Y-m-d', strtotime($request->valid_until));
        $quotation->billing_address = $request->billing_address;
        $quotation->shipping_address = $request->shipping_address;
        $quotation->billing_pobox = $request->billing_pobox;
        $quotation->shipping_pobox = $request->shipping_pobox;
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
        $quotation->gst_amount = $request->igsttotal;
        $quotation->cgsttotal = $request->cgsttotal;
        $quotation->sgsttotal = $request->sgsttotal;

        $quotation->adjustment = $request->adjustment;
        $quotation->discount_total = $request->discount_total;
        $quotation->net_amount = $request->item_total;
        $quotation->grand_total = $request->grand_total;
        $quotation->module = $request->module;
        $quotation->remark = $request->remark;
        $quotation->datetime = date('Y-m-d h:i:s a');
        $quotation->payment_terms = $request->payment_terms;
        $quotation->finacial_year = $finacial_year->id;
        $quotation->salesman_id = $request->salesman_id;
        $quotation->created_by = Auth::user()->id;
        if ($quotation->save()) {
            if (empty($request->product)) {
            } else {
                $totproduct = count($request->product);
                for ($i = 0; $i < $totproduct; $i++) {

                    $item = new quotation_item();
                    $item->quot_no = $qno + 1;
                    $item->quotation_no = $n2;
                    $item->product = $request->product[$i];
                    $item->description = $request->description[$i];
                    $item->qty = $request->qty[$i];
                    $item->customer = $request->customer;
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
                    $item->amount = $request->total_amount[$i];

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

            if (session()->has('quot_cart')) {
                $cart = session()->get('quot_cart');
                foreach (session('quot_cart') as $id => $details) {
                    unset($cart[$id]);
                    session()->put('quot_cart', $cart);
                }
            }


            //LogActivity::addToLog($n2.' Quotation Create');

            return redirect()->route("admin.quotation.list")->with('message', 'quotation create successfully');
        } else {
            return back();
        }


    }


    function quot_normal_save(Request $request)
    {
        date_default_timezone_set('Asia/Kolkata');

        $customer_name = customers::find($request->customer);

        $request->validate([
            'quot_stage' => 'required',
            'valid_until' => 'required',
            'customer' => 'required',
            'quot_date' => 'required',
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
        $qno = quotation::max('quot_no');

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
        $customer_name = customers::find($request->customer);

        $quotation = new quotation();
        $quotation->quot_no = $qno + 1;
        $quotation->quotation_no = $n2;
        $quotation->quot_date = date('Y-m-d', strtotime($request->quot_date));
        $quotation->subject = $request->subject;
        $quotation->customer = $request->customer;
        $quotation->contact_name = $request->contact_name;
        $quotation->customer_name = $customer_name->customer_name;
        $quotation->quot_stage = $request->quot_stage;
        $quotation->valid_until = date('Y-m-d', strtotime($request->valid_until));
        $quotation->billing_address = $request->billing_address;
        $quotation->shipping_address = $request->shipping_address;
        $quotation->billing_pobox = $request->billing_pobox;
        $quotation->shipping_pobox = $request->shipping_pobox;
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
        $quotation->module = $request->module;
        $quotation->remark = $request->remark;
        $quotation->datetime = date('Y-m-d h:i:s a');
        if ($quotation->save()) {
            if (empty($request->product)) {
            } else {
                $totproduct = count($request->product);
                for ($i = 0; $i < $totproduct; $i++) {

                    $item = new quotation_item();
                    $item->quot_no = $qno + 1;
                    $item->quotation_no = $n2;
                    $item->product = $request->product[$i];
                    $item->description = $request->description[$i];
                    $item->qty = $request->qty[$i];
                    $item->customer = $request->customer;
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
                    $item->amount = $request->total_amount[$i];

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

            if (session()->has('cart')) {
                $cart = session()->get('cart');
                foreach (session('cart') as $id => $details) {
                    unset($cart[$id]);
                    session()->put('cart', $cart);
                }
            }
            return redirect()->route("quotation_normal_list")->with('message', 'quotation create successfully');

        } else {
            return back();
        }
    }

    function vendor_delete(Request $request)
    {
        $vendor = vendor::find($request->id);
        if ($vendor->delete()) {
            return redirect()->route("client/vendor_list")->with("message", "vendor delete successfully");
        }

    }

    function vendor_update(Request $request)
    {
        date_default_timezone_set('Asia/Kolkata');
        //dd($request->all());
        $save = vendor::find($request->id);
        $request->validate([
            'vendor_name' => 'required|max:255',
            'primary_email' => 'email:rfc,dns',
            'primary_phone' => 'required',
            'owner_name' => 'required|max:255',
            'owner_mobile' => 'required|max:255',
            'owner_email' => 'email:rfc,dns',
            'billing_address' => 'required',
            'shipping_address' => 'required',
            'billing_city' => 'required',
            'shipping_city' => 'required',
            'billing_postalcode' => 'required',
            'shipping_postalcode' => 'required',
            'shipping_country' => 'required',
            'alternate_no' => 'required',
            'work_email' => 'required',
            'billing_country' => 'required',
            'billing_state' => 'required',
            'shipping_state' => 'required',
            'department' => 'required',
            'designation' => 'required',
        ]);
        // / dd($request->all());
        $save->vendor_name = $request->vendor_name;
        $save->website = $request->website;
        $save->primary_phone = $request->primary_phone;
        $save->secondary_phone = $request->secondary_phone;
        $save->primary_email = $request->primary_email;
        $save->secondary_email = $request->secondary_email;
        $save->owner_name = $request->owner_name;
        $save->owner_mobile = $request->owner_mobile;
        $save->owner_email = $request->owner_email;
        $save->work_email = $request->work_email;
        $save->alternate_no = $request->alternate_no;
        $save->owner_gst = $request->owner_gst;
        $save->owner_pan = $request->owner_pan;
        $save->billing_address = $request->billing_address;
        $save->shipping_address = $request->shipping_address;
        $save->billing_pobox = $request->billing_pobox;
        $save->shipping_pobox = $request->shipping_pobox;
        $save->billing_city = $request->billing_city;
        $save->shipping_city = $request->shipping_city;
        $save->billing_state = $request->billing_state;
        $save->shipping_state = $request->shipping_state;
        $save->billing_postalcode = $request->billing_postalcode;
        $save->shipping_postalcode = $request->shipping_postalcode;
        $save->billing_country = $request->billing_country;
        $save->shipping_country = $request->shipping_country;
        $save->description = $request->description;
        $save->created_time = date('Y-m-d h:i:s A');
        $save->user_id = Session::get('user_id');
        $save->website_id = Session::get('website_id');
        $save->industry = $request->industry;
        $save->type = $request->type;
        $save->department = $request->department;
        $save->designation = $request->designation;
        $save->tax_preference = $request->tax_preference;
        $save->payment_terms = $request->payment_terms;

        if ($save->save()) {
            //LogActivity::addToLog($request->vendor_name.' Vendor Update ');

            return redirect()->route('admin.vendor.list')->with('message', 'Vendor save successfully');
        } else {
            return back()->with('message', 'error in save');
        }
    }

    function vendor_edit(Request $request)
    {
        $data = vendor::find($request->id);

        $industry = ['' => 'select industry'] + industry::query()
                ->orderBy('industry_name')->get()->pluck('industry_name', 'id')->toArray();
        $type = ['' => 'select type'] + type::query()
                ->orderBy('type_name')->get()->pluck('type_name', 'id')->toArray();

        $country = ['' => 'select country'] + country::orderBy('country_name', 'asc')
                ->get()->pluck('country_name', 'id')->toArray();

        $state = ['' => 'select state'] + state::orderBy('state_name', 'asc')
                ->get()->pluck('state_name', 'id')->toArray();

        $city = ['' => 'select city'] + city::orderBy('city_name', 'asc')
                ->get()->pluck('city_name', 'id')->toArray();

        $payment_terms = ['' => 'select'] + payment_terms::orderBy("id", "desc")
                ->get()->pluck("terms_name", "days")->toArray();

        return view("admin/vendor_edit")->with(['payment_terms' => $payment_terms, 'industry' => $industry, 'type' => $type, 'country' => $country, 'state' => $state, 'city' => $city, 'data' => $data]);
    }

    function vendor_preview(Request $request)
    {
        $data = vendor::select('vendor.*', 'industry.industry_name', 'country.country_name', 'state.state_name', 'city.city_name')
            ->LeftJoin('industry', 'industry.id', 'vendor.industry')
            ->LeftJoin('country', 'country.id', 'vendor.billing_country')
            ->LeftJoin('state', 'state.id', 'vendor.billing_state')
            ->LeftJoin('city', 'city.id', 'vendor.billing_city')
            ->where('vendor.id', $request->id)
            ->first();
        if (empty($data->shipping_country)) {
            $shipping_country = "";
        } else {
            $shipping_country1 = country::find($data->shipping_country);
            $shipping_country = $shipping_country1->country_name;
        }

        if (empty($data->shipping_state)) {
            $shipping_state = "";
        } else {
            $shipping_state1 = state::find($data->shipping_state);
            $shipping_state = $shipping_state1->state_name;
        }

        if (empty($data->shipping_city)) {
            $shipping_city = "";
        } else {
            $shipping_city1 = city::find($data->shipping_city);
            $shipping_city = $shipping_city1->city_name;
        }


        return view("admin/vendor_preview")->with(['data' => $data, 'shipping_state1' => $shipping_state, 'shipping_city1' => $shipping_city, 'shipping_country1' => $shipping_country]);
    }

    function vendor_save(Request $request)
    {
        //dd($request->all());
        date_default_timezone_set('Asia/Kolkata');
        $save = new vendor();
        $request->validate([
            'vendor_name' => 'required|max:255|unique:vendor',
            'primary_email' => 'email:rfc,dns',
            'primary_phone' => 'required',
            'owner_name' => 'required|max:255',
            'owner_mobile' => 'required|max:255',
            'owner_email' => 'email:rfc,dns',
            'billing_address' => 'required',
            'shipping_address' => 'required',
            'billing_city' => 'required',
            'shipping_city' => 'required',
            'billing_postalcode' => 'required',
            'shipping_postalcode' => 'required',
            'shipping_country' => 'required',
            'alternate_no' => 'required',
            'work_email' => 'required',
            'billing_country' => 'required',
            'billing_state' => 'required',
            'shipping_state' => 'required',
            'department' => 'required',
            'designation' => 'required',
        ]);
        // / dd($request->all());
        $save->vendor_name = $request->vendor_name;
        $save->website = $request->website;
        $save->primary_phone = $request->primary_phone;
        $save->secondary_phone = $request->secondary_phone;
        $save->primary_email = $request->primary_email;
        $save->secondary_email = $request->secondary_email;
        $save->owner_name = $request->owner_name;
        $save->owner_mobile = $request->owner_mobile;
        $save->owner_email = $request->owner_email;
        $save->work_email = $request->work_email;
        $save->alternate_no = $request->alternate_no;
        $save->owner_gst = $request->owner_gst;
        $save->owner_pan = $request->owner_pan;
        $save->billing_address = $request->billing_address;
        $save->shipping_address = $request->shipping_address;
        $save->billing_pobox = $request->billing_pobox;
        $save->shipping_pobox = $request->shipping_pobox;
        $save->billing_city = $request->billing_city;
        $save->shipping_city = $request->shipping_city;
        $save->billing_state = $request->billing_state;
        $save->shipping_state = $request->shipping_state;
        $save->billing_postalcode = $request->billing_postalcode;
        $save->shipping_postalcode = $request->shipping_postalcode;
        $save->billing_country = $request->billing_country;
        $save->shipping_country = $request->shipping_country;
        $save->description = $request->description;
        $save->created_time = date('Y-m-d h:i:s A');
        $save->user_id = Session::get('user_id');
        $save->website_id = Session::get('website_id');
        $save->industry = $request->industry;
        $save->type = $request->type;
        $save->department = $request->department;
        $save->designation = $request->designation;
        $save->tax_preference = $request->tax_preference;
        $save->payment_terms = $request->payment_terms;
        if ($save->save()) {
            $save1 = new vendor_contact();
            $save1->vendor = $save->id;
            $save1->contact_name = $request->owner_name;
            $save1->primary_phone = $request->owner_mobile;
            $save1->secondary_phone = $request->alternate_no;
            $save1->primary_email = $request->owner_email;
            $save1->secondary_email = $request->work_email;
            $save1->created_time = date('Y-m-d h:i:s A');
            $save1->user_id = Session::get('user_id');
            $save1->website_id = Session::get('website_id');
            $save1->department = $request->department;
            $save1->designation = $request->designation;
            $save1->save();

            //LogActivity::addToLog($request->vendor_name.' Vendor Create ');

            return redirect()->route('admin.vendor.list')->with('message', 'Vendor save successfully');
        } else {
            return back()->with('message', 'error in save');
        }

    }

    function vendor_add(Request $request)
    {
        $industry = ['' => 'select industry'] + industry::query()
                ->orderBy('industry_name')->get()->pluck('industry_name', 'id')->toArray();
        $type = ['' => 'select type'] + type::query()
                ->orderBy('type_name')->get()->pluck('type_name', 'id')->toArray();

        $country = ['' => 'select country'] + country::orderBy('country_name', 'asc')
                ->get()->pluck('country_name', 'id')->toArray();

        $state = ['' => 'select state'] + state::orderBy('state_name', 'asc')
                ->get()->pluck('state_name', 'id')->toArray();

        $city = ['' => 'select city'] + city::orderBy('city_name', 'asc')
                ->get()->pluck('city_name', 'id')->toArray();

        $payment_terms = ['' => 'select'] + payment_terms::orderBy("id", "desc")
                ->get()->pluck("terms_name", "days")->toArray();

        return view("admin/vendor_add")->with(['payment_terms' => $payment_terms, 'industry' => $industry, 'type' => $type, 'country' => $country, 'state' => $state, 'city' => $city]);

    }


    function vendor_list(Request $request)
    {
        $data = new vendor();
        $data = $data->select('vendor.*', 'city.city_name');
        $data = $data->leftJoin('city', 'city.id', 'vendor.billing_city');
        $data = $data;

        if ($request->vendor_name != '') {
            $data = $data->Where('vendor.vendor_name', 'like', '%' . $request->vendor_name . '%');
        }
        if ($request->primary_phone != '') {
            $data = $data->Where('vendor.primary_phone', 'like', '%' . $request->primary_phone . '%');
        }
        if ($request->primary_email != '') {
            $data = $data->Where('vendor.primary_email', 'like', '%' . $request->primary_email . '%');
        }
        if ($request->owner_name != '') {
            $data = $data->Where('vendor.owner_name', 'like', '%' . $request->owner_name . '%');
        }
        if ($request->owner_mobile != '') {
            $data = $data->Where('vendor.owner_mobile', 'like', '%' . $request->owner_mobile . '%');
        }
        if ($request->owner_email != '') {
            $data = $data->Where('vendor.owner_email', 'like', '%' . $request->owner_email . '%');
        }

        if (isset($request->vname_asc)) {
            $data = $data->orderBy('vendor.vendor_name', 'asc');
        }
        if (isset($request->vname_desc)) {
            $data = $data->orderBy('vendor.vendor_name', 'desc');
        }

        if (isset($request->pphone_asc)) {
            $data = $data->orderBy('vendor.primary_phone', 'asc');
        }
        if (isset($request->pphone_desc)) {
            $data = $data->orderBy('vendor.primary_phone', 'desc');
        }

        if (isset($request->pemail_asc)) {
            $data = $data->orderBy('vendor.primary_email', 'asc');
        }
        if (isset($request->pemail_desc)) {
            $data = $data->orderBy('vendor.primary_email', 'desc');
        }

        if (isset($request->cname_asc)) {
            $data = $data->orderBy('vendor.owner_name', 'asc');
        }
        if (isset($request->cname_desc)) {
            $data = $data->orderBy('vendor.owner_name', 'desc');
        }

        $data = $data->orderBy("vendor.vendor_name", 'asc');
        //echo print_r($request->all());
        $result = $data->paginate(10);

        return view("admin/vendor_list")->with(['cdata' => $result]);
    }

    function service_add(Request $request)
    {
        $category = ['' => 'select category'] + category::query()
                ->orderBy('category_name', 'asc')->get()->pluck('category_name', 'id')->toArray();

        $gst = ['' => 'select gst'] + gst::query()->orderBy('gst_per', 'asc')->get()->pluck('gst_per', 'id')->toArray();


        $uom = ['' => 'select uom'] + uom::query()->orderBy('uom_name', 'asc')->get()->pluck('uom_name', 'id')->toArray();

        $vendor = ['' => 'select vendor'] + vendor::query()->orderBy('vendor_name', 'asc')->get()->pluck('vendor_name', 'id')->toArray();

        return view("admin/service_add")->with(['category' => $category, 'uom' => $uom, 'gst' => $gst, 'vendor' => $vendor]);

    }

    function gst_delete(Request $request)
    {
        $gst = gst::find($request->id);

        if ($gst->delete()) {
            return redirect()->route('admin.gst.list')->with('message', 'gst delete successfully');
        } else {
            return back();
        }
    }

    function gst_update(Request $request)
    {
        $gst = gst::find($request->id);
        $gst->gst_per = $request->gst_per;
        $gst->website_id = Session::get('website_id');
        $gst->user_id = Session::get('user_id');
        if ($gst->save()) {
            return redirect()->route('admin.gst.list')->with('message', 'gst update successfully');
        } else {
            return back();
        }
    }

    function gst_edit(Request $request)
    {
        $gst = gst::find($request->id);

        return view("admin/gst_edit")->with(['data' => $gst]);
    }

    public function gst_save(Request $request)
    {
        // Validate Unique GST %
        $request->validate([
            'gst_per' => 'required|numeric|unique:gst,gst_per'
        ], [
            'gst_per.unique' => 'This GST percentage already exists.'
        ]);

        $gst = new gst();
        $gst->gst_per = $request->gst_per;
        $gst->website_id = Session::get('website_id');
        $gst->user_id = Session::get('user_id');

        if ($gst->save()) {
            return redirect()->route('admin.gst.list')->with('message', 'GST saved successfully');
        } else {
            return back();
        }
    }


    function gst_list(Request $request)
    {
        $gst = new gst();
        $gst1 = new gst();

        if (isset($request->gst_per)) {
            $gst = $gst->where('gst_per', 'like', '%' . $request->gst_per . '%');
            $gst1 = $gst1->where('gst_per', $request->gst_per);
        }

        if ($request->gst_per != "") {
            $gst1 = $gst1->count();
            $gst = $gst->paginate($gst1);
        } else {

            $gst = $gst->paginate(10);
        }

        return view("admin/gst_list")->with(['cdata' => $gst]);
    }

    function uom_delete(Request $request)
    {
        $uom = uom::find($request->id);

        if ($uom->delete()) {
            return redirect()->route('admin.uom.list')->with('message', 'uom delete successfully');
        } else {
            return back();
        }
    }

    function uom_update(Request $request)
    {
        $uom = uom::find($request->id);
        $uom->uom_name = $request->uom_name;
        if ($uom->save()) {
            return redirect()->route('admin.uom.list')->with('message', 'uom update successfully');
        } else {
            return back();
        }
    }

    function uom_edit(Request $request)
    {
        $uom = uom::find($request->id);

        return view("admin/uom_edit")->with(['data' => $uom]);
    }

    function uom_save(Request $request)
    {
        $uom = new uom();
        $uom->uom_name = $request->uom_name;
        $uom->website_id = Session::get('website_id');
        $uom->user_id = Session::get('user_id');
        if ($uom->save()) {
            return redirect()->route('admin.uom.list')->with('message', 'uom save successfully');
        } else {
            return back();
        }
    }

    function uom_list(Request $request)
    {
        $uom = new uom();
        $uom = $uom;
        if (isset($request->usage_unit)) {
            $uom = $uom->where('uom_name', 'like', '%' . $request->usage_unit . '%');

        }

        $data = $uom->paginate(10);

        return view("admin/uom_list")->with(['data' => $data]);

    }

    function product_add(Request $request)
    {
        $category = ['' => 'select category'] + category::query()
                ->orderBy('category_name', 'asc')
                ->get()
                ->pluck('category_name', 'id')
                ->toArray();

        $material = ['' => 'select material'] + material::query()
                ->orderBy('material_name', 'asc')
                ->get()
                ->pluck('material_name', 'id')
                ->toArray();

        $gst = ['' => 'select gst'] + gst::query()
                ->orderBy('gst_per', 'asc')
                ->get()->pluck('gst_per', 'id')->toArray();


        $uom = ['' => 'select uom'] + uom::query()
                ->orderBy('uom_name', 'asc')
                ->get()
                ->pluck('uom_name', 'id')
                ->toArray();

        $vendor = ['' => 'select vendor'] + vendor::query()
                ->orderBy('vendor_name', 'asc')
                ->get()
                ->pluck('vendor_name', 'id')
                ->toArray();

        $country = ['' => 'select country'] + country::orderBy('country_name', 'asc')
                ->get()->pluck('country_name', 'id')->toArray();

        $state = ['' => 'select state'] + state::orderBy('state_name', 'asc')
                ->get()->pluck('state_name', 'id')->toArray();

        $city = ['' => 'select city'] + city::orderBy('city_name', 'asc')
                ->get()->pluck('city_name', 'id')->toArray();


        return view("admin/product_add")->with(['category' => $category, 'uom' => $uom, 'gst' => $gst, 'vendor' => $vendor, 'country' => $country, 'state' => $state, 'city' => $city, 'material' => $material]);
    }

    function product_normal_add(Request $request)
    {
        $category = ['' => 'select category'] + category::query()
                ->orderBy('category_name', 'asc')
                ->get()
                ->pluck('category_name', 'id')
                ->toArray();

        $material = ['' => 'select material'] + material::query()
                ->orderBy('material_name', 'asc')
                ->get()
                ->pluck('material_name', 'id')
                ->toArray();

        $gst = ['' => 'select gst'] + gst::query()
                ->orderBy('gst_per', 'asc')
                ->get()->pluck('gst_per', 'id')->toArray();


        $uom = ['' => 'select uom'] + uom::query()
                ->orderBy('uom_name', 'asc')
                ->get()
                ->pluck('uom_name', 'id')
                ->toArray();

        $vendor = ['' => 'select vendor'] + vendor::query()
                ->orderBy('vendor_name', 'asc')
                ->get()
                ->pluck('vendor_name', 'id')
                ->toArray();

        $country = ['' => 'select country'] + country::orderBy('country_name', 'asc')
                ->get()->pluck('country_name', 'id')->toArray();

        $state = ['' => 'select state'] + state::orderBy('state_name', 'asc')
                ->get()->pluck('state_name', 'id')->toArray();

        $city = ['' => 'select city'] + city::orderBy('city_name', 'asc')
                ->get()->pluck('city_name', 'id')->toArray();

        return view("admin/product_normal_add")->with(['category' => $category, 'uom' => $uom, 'gst' => $gst, 'vendor' => $vendor, 'country' => $country, 'state' => $state, 'city' => $city, 'material' => $material]);
    }

    function category_delete(Request $request)
    {
        $data = category::find($request->id);
        if ($data->delete()) {
            return redirect()->route('admin.category.list')->with('message', 'category delete successfully');
        } else {
            return back();
        }
    }

    function category_update(Request $request)
    {
        $category = category::find($request->id);
        $category->category_name = $request->category_name;

        if ($request->hasFile('category_image')) {
            $image = $request->file('category_image');
            $name = time() . '.' . $image->getClientOriginalExtension();
            $destinationPath = '/product_category/';
            $image->move(public_path($destinationPath), $name);
            $category->category_image = $name;
        }

        if ($category->save()) {
            //LogActivity::addToLog($request->category_name.' Category Update');
            return redirect()->route('admin.category.list')->with('message', 'category update successfully');
        } else {
            return back();
        }
    }

    function category_edit(Request $request)
    {
        $data = category::find($request->id);
        return view("admin/category_edit")->with(['data' => $data]);
    }

    function category_save(Request $request)
    {
        $request->validate([
            'category_name' => 'required|max:255|unique:category',
        ]);

        $category = new category();
        $category->category_name = $request->category_name;
        $category->website_id = Session::get('website_id');
        $category->user_id = Session::get('user_id');

        if ($request->hasFile('category_image')) {
            $image = $request->file('category_image');
            $name = time() . '.' . $image->getClientOriginalExtension();
            $destinationPath = '/product_category/';
            $image->move(public_path($destinationPath), $name);
            $category->category_image = $name;
        }

        if ($category->save()) {
            //LogActivity::addToLog($request->category_name.' Category Create');

            return redirect()->route('admin.category.list')->with('message', 'category save successfully');
        } else {
            return back();
        }
    }

    function category_list(Request $request)
    {
        $category = new category();
        $category = $category->select('category.*');
        $category = $category;
        if (isset($request->category_name)) {
            $category = $category->where('category_name', 'like', '%' . $request->category_name . '%');
        }
        $result = $category->paginate(10);

        return view("admin/category_list")->with(['data' => $result]);
    }

    function subcategory_list(Request $request)
    {

        $subcategory = new subcategory();
        $subcategory = $subcategory->select('subcategory.*', 'category.category_name');
        $subcategory = $subcategory->leftJoin('category', 'category.id', 'subcategory.category');
        if (isset($request->subcategory_name)) {
            $subcategory = $subcategory->where('subcategory_name', 'like', '%' . $request->subcategory_name . '%');
        }
        if (isset($request->category)) {
            $subcategory = $subcategory->where('subcategory.category_name', 'like', '%' . $request->category_name . '%');
        }
        $result = $subcategory->paginate(10);

        return view("admin/subcategory/index")->with(['subcategory' => $result]);
    }

    function state_delete(Request $request)
    {
        $data = state::find($request->id);
        //LogActivity::addToLog($data->state_name.' State Delete');
        if ($data->delete()) {
            //LogActivity::addToLog($request->category_name.' Category Create');

            return redirect()->route('admin.state.list')->with('message', 'state delete successfully');
        }
    }

    function state_update(Request $request)
    {
        $state = state::find($request->id);
        $state->state_name = $request->state_name;
        $state->state_code = $request->state_code;
        $state->country = $request->country;
        if ($state->save()) {
            //LogActivity::addToLog($request->state_name.' State Update');
            return redirect()->route('admin.state.list')->with('message', 'state update successfully');
        }
    }

    function city_update(Request $request)
    {
        $city = city::find($request->id);
        $city->city_name = $request->city_name;
        $city->state = $request->state;
        $city->website_id = Session::get('website_id');
        $city->user_id = Session::get('user_id');
        if ($city->save()) {
            //LogActivity::addToLog($request->city_name.' City Update');
            return redirect()->route('admin.city.list')->with('message', 'city update successfully');
        }
    }

    function state_edit(Request $request)
    {
        $country = ['' => 'select country'] + country::orderBy('country_name', 'asc')
                ->get()
                ->pluck('country_name', 'id')
                ->toArray();

        $state = state::find($request->id);

        return view("admin/state_edit")->with(['country' => $country, 'data' => $state]);
    }

    function city_edit(Request $request)
    {
        $state = ['' => 'select state'] + state::query()
                ->orderBy('state_name', 'asc')
                ->get()
                ->pluck('state_name', 'id')
                ->toArray();

        $data = city::find($request->id);

        return view("admin/city_edit")->with(['state' => $state, 'data' => $data]);
    }

    public function city_delete(Request $request)
    {
        $data = city::find($request->id);
        if($data->delete()){
            return redirect()->route('admin.city.list')->with('message', 'city deleted successfully');
        }
    }

    function state_save(Request $request)
    {
        $state = new state();
        $state->state_name = $request->state_name;
        $state->country = $request->country;
        $state->state_code = $request->state_code;
        $state->website_id = Session::get('website_id');
        $state->user_id = Session::get('user_id');

        if ($state->save()) {
            //LogActivity::addToLog($request->state_name.' State Create');

            return redirect()->route('admin.state.list')->with('message', 'state save successfully');
        }

    }


    function city_save(Request $request)
    {
        $state = new city();
        $state->city_name = $request->city_name;
        $state->state = $request->state;
        $state->website_id = Session::get('website_id');
        $state->user_id = Session::get('user_id');
        if ($state->save()) {
            //LogActivity::addToLog($request->city_name.' City Create');

            return redirect()->route('admin.city.list')->with('message', 'city save successfully');
        }

    }


    function state_add()
    {
        $country = ['' => 'select country'] + country::orderBy('country_name', 'asc')
                ->get()
                ->pluck('country_name', 'id')
                ->toArray();

        return view("admin/state_add")->with(['country' => $country]);
    }

    function city_add()
    {
        $state = ['' => 'select state'] + state::orderBy('state_name', 'asc')
                ->get()
                ->pluck('state_name', 'id')
                ->toArray();

        return view("admin/city_add")->with(['state' => $state]);
    }

    function state_list(Request $request)
    {
        $state = new state();
        $state = $state->select('state.*', 'country.country_name');
        $state = $state->leftJoin('country', 'country.id', 'state.country');
        if (isset($request->country)) {
            $state = $state->where('country.country_name', 'like', '%' . $request->country . '%');
        }
        if (isset($request->state)) {
            $state = $state->where('state.state_name', 'like', '%' . $request->state . '%');
        }
        $data = $state->paginate(10);

        return view("admin/state_list")->with(['data' => $data]);
    }

    function city_list(Request $request)
    {
        $city = new city();
        $city = $city->select('city.*', 'state.state_name');
        $city = $city->leftJoin('state', 'state.id', 'city.state');
        if (isset($request->state)) {
            $city = $city->where('state.state_name', 'like', '%' . $request->state . '%');
        }
        if (isset($request->city)) {
            $city = $city->where('city.city_name', 'like', '%' . $request->city . '%');

        }

        $data = $city->paginate(10);
        // /dd($data);
        return view("admin/city_list")->with(['data' => $data]);
    }

    function country_delete(Request $request)
    {
        $data = country::find($request->id);
        //LogActivity::addToLog($data->country_name.' Country Delete');
        if ($data->delete()) {

            return redirect()->route('admin.country.list')->with('message', 'country delete successfully');
        }
    }

    function country_update(Request $request)
    {
        $country = country::find($request->id);
        $country->country_name = $request->country_name;
        if ($country->save()) {
            //LogActivity::addToLog($request->country_name.' Country Update');

            return redirect()->route('admin.country.list')->with('message', 'country update successfully');
        }
    }

    function country_edit(Request $request)
    {
        $data = country::find($request->id);
        return view("admin/country_edit")->with(['data' => $data]);
    }

    function country_save(Request $request)
    {
        $country = new country();
        $country->country_name = $request->country_name;
        $country->user_id = Session::get('user_id');
        $country->website_id = Session::get('website_id');
        if ($country->save()) {
            //LogActivity::addToLog($request->country_name.' Country Create');

            return redirect()->route('admin.country.list')->with('message', 'country save successfully');
        }
    }

    function country_list(Request $request)
    {
        $country = new country();
        if (isset($request->country)) {
            $country = $country->where('country_name', 'like', '%' . $request->country . '%');
        }
        $data = $country->paginate(10);
        // $data=country::orderBy('country_name','asc')->get();
        return view('admin/country_list')->with(['data' => $data]);
    }

    function services_delete(Request $request)
    {
        $services = product::find($request->id);

        //LogActivity::addToLog($services->product_name.' Service Delete');

        if ($services->delete()) {
            return redirect()->route("services-list")->with('message', 'service delete successfully');
        }
    }

    function service_update(Request $request)
    {
        $save = product::find($request->id);

        $request->validate([
            'product_name' => 'required',
            'price' => 'required',
        ]);

        $save->product_name = $request->product_name;
        $save->make = $request->make;
        $save->model = $request->model;
        $save->price = $request->price;
        $save->gst = $request->gst;
        $save->uom = $request->uom;
        $save->category = $request->category;
        $save->sales_start_date = date('Y-m-d', strtotime($request->sales_start_date));
        $save->sales_end_date = date('Y-m-d', strtotime($request->sales_end_date));
        $save->user_id = Session::get('user_id');
        $save->website_id = Session::get('website_id');
        $save->description = $request->description;
        $save->vendor = $request->vendor;
        $save->outer_diameter = $request->outer_diameter;
        $save->inner_diameter = $request->inner_diameter;
        $save->thikness = $request->thikness;
        $save->hsn = $request->hsn;
        $save->status = "service";
        if ($request->hasFile('product_image')) {

            $image = $request->file('product_image');
            $name = time() . '.' . $image->getClientOriginalExtension();
            $destinationPath = '/product_image/';
            $image->move($destinationPath, $name);

            $save->product_image = $name;
        }


        if ($save->save()) {
            //LogActivity::addToLog($request->product_name.' Service Update');

            return redirect()->route('services-list')->with('message', 'services Update successfully');
        } else {
            return back()->with('message', 'error in save');
        }
    }

    function services_edit(Request $request)
    {
        $data = product::find($request->id);


        $category = ['' => 'select category'] + category::query()->orderBy('category_name', 'asc')->get()->pluck('category_name', 'id')->toArray();

        $gst = ['' => 'select gst'] + gst::query()->orderBy('gst_per', 'asc')->get()->pluck('gst_per', 'id')->toArray();


        $uom = ['' => 'select uom'] + uom::query()->orderBy('uom_name', 'asc')->get()->pluck('uom_name', 'id')->toArray();

        $vendor = ['' => 'select vendor'] + vendor::query()->orderBy('vendor_name', 'asc')->get()->pluck('vendor_name', 'id')->toArray();


        return view("admin/services_edit")->with(['category' => $category, 'uom' => $uom, 'gst' => $gst, 'data' => $data, 'vendor' => $vendor]);
    }

    function services_save(Request $request)
    {
        $request->validate([
            'product_name' => 'required|max:255',
            'price' => 'required',
        ]);

        $save = new product();
        $save->product_name = $request->product_name;
        $save->make = $request->make;
        $save->model = $request->model;
        $save->price = $request->price;
        $save->gst = $request->gst;
        $save->uom = $request->uom;
        $save->category = $request->category;
        $save->sales_start_date = date('Y-m-d', strtotime($request->sales_start_date));
        $save->sales_end_date = date('Y-m-d', strtotime($request->sales_end_date));
        $save->user_id = Session::get('user_id');
        $save->website_id = Session::get('website_id');
        $save->description = $request->description;
        $save->vendor = $request->vendor;
        $save->outer_diameter = $request->outer_diameter;
        $save->inner_diameter = $request->inner_diameter;
        $save->thikness = $request->thikness;
        $save->hsn = $request->hsn;
        $save->status = "service";

        if ($request->hasFile('product_image')) {

            $image = $request->file('product_image');
            $name = time() . '.' . $image->getClientOriginalExtension();
            $destinationPath = '/product_image/';
            $image->move($destinationPath, $name);

            $save->product_image = $name;
        }


        if ($save->save()) {
            //LogActivity::addToLog($request->product_name.' Service Save');

            return redirect()->route('services-list')->with('message', 'services save successfully');
        } else {
            return back()->with('message', 'error in save');
        }
    }

    function services_list(Request $request)
    {

        $product = DB::table('product');
        $product = $product->select('product.*', 'gst.gst_per', 'uom.uom_name', 'category.category_name as catname', 'material.material_name as matname');
        $product = $product->leftJoin('gst', 'gst.id', 'product.gst');
        $product = $product->leftJoin('uom', 'uom.id', 'product.uom');
        $product = $product->leftJoin('category', 'category.id', 'product.category');
        $product = $product->leftJoin('material', 'material.id', 'product.material');
        $product = $product->where('product.status', 'service');
        $product = $product;


        if (isset($request->product_name)) {
            $product = $product->where('product.product_name', 'like', '%' . $request->product_name . '%');
        }

        if (isset($request->category)) {
            $product = $product->where('product.category_name', 'like', '%' . $request->category . '%');
        }

        if (isset($request->material)) {
            $product = $product->where('product.material_name', 'like', '%' . $request->material . '%');

        }

        if (isset($request->usage_unit)) {
            $product = $product->where('uom.uom_name', 'like', '%' . $request->usage_unit . '%');

        }

        if (isset($request->price)) {
            $product = $product->where('product.price', 'like', '%' . $request->price . '%');

        }

        if (isset($request->gst)) {
            $product = $product->where('gst.gst_per', 'like', '%' . $request->gst . '%');

        }

        $product = $product->paginate(10);

        return view('admin/service_list')->with(['data' => $product]);
    }

    function quotation_delete(Request $request)
    {
        $data = quotation::find($request->quot_no);


        if ($data->delete()) {
            quotation_item::where('quot_no', $data->quot_no)->delete();
            return back()->with('message', 'quotation delete successfully');

        }

    }

    function dashboard(Request $request)
    {
        Session::put('website_id', 1);
        $totcustomer = customers::all()->count();
        $pendingQuotation = quotation::where("finacial_year", Session::get('finacial_year_id'))
            ->whereNull('so_status')
            ->count();
        $totquotation = quotation::where("finacial_year", Session::get('finacial_year_id'))->count();
        $totproduct = product::all()->count();

        $totsales = salesorder::where("finacial_year", Session::get('finacial_year_id'))
            ->count();

        $totpurchase = purchase::where("finacial_year", Session::get('finacial_year_id'))
            ->count();

        $totdelivery = delivery_challan::where("finacial_year", Session::get('finacial_year_id'))
            ->count();

        $totinvoice = invoice::where("finacial_year", Session::get('finacial_year_id'))
            ->count();

        $service_renewal = service_renewal::select('service_renewal.*', 'customers.customer_name', 'uom.uom_name', 'category.category_name', 'product.product_name')
            ->leftJoin('customers', 'customers.id', 'service_renewal.customer')
            ->leftJoin('uom', 'uom.id', 'service_renewal.usage_unit')
            ->leftJoin('category', 'category.id', 'service_renewal.category')
            ->leftJoin('product', 'product.id', 'service_renewal.service')
            ->orderBy('service_renewal.support_expiry_date', 'desc')
            ->get();

        $start = date('Y-m-d', strtotime('-10 days'));
        $end = date('Y-m-d', strtotime('-5 days'));

        $product = new quotation();


        $product = $product->select('quotation.*', 'customers.customer_name', 'customers.primary_email', 'customers.secondary_email');
        $product = $product->leftJoin('customers', 'customers.id', 'quotation.customer');
        $product = $product->whereBetween('quotation.quot_date', [$start, $end]);
        $product = $product->orderBy('id', 'desc');
        $result = $product->get();

        $company = \App\company::first();
        \Session::put('finacial_year_id', $company->finacial_year_id);
        \Session::put('favicon', $company->favicon);

        return view('admin/index')->with(['totinvoice' => $totinvoice, 'totdelivery' => $totdelivery, 'totpurchase' => $totpurchase, 'totsales' => $totsales, 'totcustomer' => $totcustomer, 'totquotation' => $totquotation, 'totproduct' => $totproduct, 'service_renewal' => $service_renewal, 'quot' => $result, 'pendingQuotation' => $pendingQuotation]);
    }

    function terms_update(Request $request)
    {
        // dd($request->all());
        $data = terms::find($request->id);
        $data->description = $request->description;
        $data->module = $request->module;
        $data->website_id = Session::get('website_id');
        $data->user_id = Session::get('user_id');
        if ($data->save()) {
            return redirect()->route('admin.term.list')->with('message', 'terms & condition update successfully');
        }
    }

    function terms_delete(Request $request)
    {
        $data = terms::find($request->id);
        if ($data->delete()) {
            return redirect()->route('admin.term.list')->with('message', 'terms & condition delete successfully');
        }
    }

    function terms_edit(Request $request)
    {
        $module = ['' => 'select module'] + module_rights::select("module_rights.*", 'module.module_name', 'module.id as moduleid')
                ->leftJoin("module", "module.id", "module_rights.module_id")
                ->where("module_rights.user_id", Session::get('user_id'))
                ->get()->pluck('module_name', 'moduleid')->toArray();

        $data = terms::find($request->id);
        return view('admin.terms_edit')->with(['data' => $data, 'module' => $module]);
    }

    function terms_list(Request $request)
    {
        $data = terms::query()
            ->get();
        return view('admin.terms_list')->with(['data' => $data]);
    }

    function company_update(Request $request)
    {
        $data = company::find($request->id);


        if (isset($request->logo)) {
            $request->validate([
                'logo' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            ]);

            $string = preg_replace('/\s+/', '', $request->company_name);
            $imageName = $string . '.' . $request->logo->extension();

            $request->logo->move(public_path('company_logo'), $imageName);

            $data->logo = $imageName;
        }

        if (isset($request->signature_image)) {
            $request->validate([
                'signature_image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            ]);
            $string = preg_replace('/\s+/', '', $request->file('signature_image')->getClientOriginalName());
            $imageName = $string;
            $request->signature_image->move(public_path('company_logo'), $imageName);
            $data->signature_image = $imageName;
        }

        $data->email = $request->email;
        $data->gst = $request->gst;
        $data->company_name = $request->company_name;
        $data->address = $request->address;
        $data->address1 = $request->address1;
        $data->address2 = $request->address2;
        $data->phone = $request->phone;
        $data->mobile = $request->mobile;
        $data->owner_name = $request->owner_name;
        $data->owner_mobile = $request->owner_mobile;
        $data->alternate_no = $request->alternate_no;
        $data->personal_email = $request->personal_email;
        $data->work_email = $request->work_email;
        $data->user_id = Session::get('user_id');
        $data->website_id = Session::get('website_id');

        $data->account_type = $request->account_type;
        $data->bank_name = $request->bank_name;
        $data->account_no = $request->account_no;
        $data->bank_branch = $request->bank_branch;
        $data->ifsc_code = $request->ifsc_code;
        $data->micr_code = $request->micr_code;
        $data->country = $request->country;
        $data->state = $request->state;
        $data->city = $request->city;
        $data->pincode = $request->pincode;
        $data->pan_no = $request->pan_no;
        if ($data->save()) {
            return redirect()->route('admin.company.list')->with('message', 'company details update successfully');
        } else {
            return back();
        }
    }

    function company_edit(Request $request)
    {
        $data = company::find($request->id);

        $country = ['' => 'select country'] + country::orderBy('country_name', 'asc')
                ->get()->pluck('country_name', 'id')->toArray();

        $state = ['' => 'select state'] + state::orderBy('state_name', 'asc')
                ->get()->pluck('state_name', 'id')->toArray();

        $city = ['' => 'select city'] + city::orderBy('city_name', 'asc')
                ->get()->pluck('city_name', 'id')->toArray();

        return view('admin/company_edit', compact("data", "country", "state", "city"));
    }

    function company_list(Request $request)
    {
        $data = company::query()
            ->orderBy('company_name', 'asc')->get();

        return view('admin.company_list')->with(['data' => $data]);
    }

    public function quot_preview(Request $request)
    {
        // Quotation details with related customer, contact, and user info
        $quot = quotation::select(
            'quotation.*',
            'customers.customer_name',
            'customers.owner_gst',
            'customers.primary_phone',
            'customers.primary_email',
            'contact.contact_name as contactname',
            'contact.primary_phone',
            'website_user.user_name',
            'website_user.last_name',
            'website_user.first_name',
            'customers.tax_preference'
        )
            ->leftJoin('customers', 'customers.id', '=', 'quotation.customer')
            ->leftJoin('contact', 'contact.id', '=', 'quotation.contact_name')
            ->leftJoin('website_user', 'website_user.id', '=', 'quotation.user_id')
            ->where('quotation.id', $request->quot_no)
            ->firstOrFail(); // agar quotation nahi mila to 404

        // State info
        $state = state::where('state_name', $quot->billing_state)->first();
        $stateId = $state->id ?? 0;

        // Quotation items with product, category, uom details
        $quotitem = quotation_item::select(
            'quot_item.*',
            'product.product_name',
            'product.item_code',
            'product.make',
            'product.model',
            'product.material_name',
            'category.category_name as catname',
            'category.category_image',
            'product.product_image',
            'uom.uom_name'
        )
            ->leftJoin('product', 'product.id', '=', 'quot_item.product')
            ->leftJoin('category', 'category.id', '=', 'product.category')
            ->leftJoin('uom', 'uom.id', '=', 'product.uom')
            ->where('quot_item.quotation_no', $quot->quotation_no)
            ->get();

        // Discount sum
        $discsum = $quotitem->sum('discount_amount');

        // Company info
        $company = company::first();

        // Terms
        $terms = terms::first();

        // Return view for preview
        return view('admin.quotation_print_old', [
            'quot' => $quot,
            'quotitem' => $quotitem,
            'company' => $company,
            'terms' => $terms,
            'discsum' => $discsum,
            'stateId' => $stateId,
            'preiview' => true
        ]);
    }


    function quot_normal_preview(Request $request)
    {
        $quot = quotation::select('quotation.*', 'customers.*', 'contact.contact_name', 'contact.primary_phone', 'website_user.user_name', 'website_user.last_name', 'website_user.first_name')
            ->leftJoin('customers', 'customers.id', 'quotation.customer')
            ->leftJoin('contact', 'contact.id', 'quotation.contact_name')
            ->leftJoin('website_user', 'website_user.id', 'quotation.user_id')
            ->where('quotation.id', $request->quot_no)
            ->first();

        $quotitem = quotation_item::select('quot_item.*', 'product.product_name', 'product.make', 'product.model', 'product.material_name', 'category.category_image', 'product.product_image', 'category.category_name as catname', 'uom.uom_name')
            ->leftJoin('product', 'product.id', 'quot_item.product')
            ->leftJoin('category', 'category.id', 'product.category')
            ->leftJoin('uom', 'uom.id', 'product.uom')
            ->where('quot_item.quot_no', $quot->quot_no)
            ->get();

        $discsum = quotation_item::select('quot_item.*', 'product.product_name', 'product.make', 'product.model', 'product.product_image', 'product.material_name', 'category.category_image')
            ->leftJoin('product', 'product.id', 'quot_item.product')
            ->leftJoin('category', 'category.id', 'product.category')
            ->where('quot_item.quot_no', $quot->quot_no)
            ->sum('quot_item.discount_amount');

        //dd($discsum);

        $company = company::select("company.*", "state.state_name")
            ->leftJoin("state", "state.id", "company.state")
            ->first();

        $terms = terms::query()->first();

        $filename = $quot->customer_name;
        $filename .= '.pdf';

        $pdf = PDF::loadView('admin.quotation.print', compact('quot', 'quotitem', 'company', 'terms', 'discsum'));
        $content = $pdf->download()->getOriginalContent();

        unlink(storage_path('app/public/quot/pdf/' . $file));

        Storage::put('public/quot/pdf/' . $filename, $content);

        $url = url('/storage/app/public/quot/pdf/' . $filename);
        return Redirect::to($url);
        // return view("ViewerJS.index")->with(['filename'=>$filename]);

        //return view('admin.quotation_print')->with(['quot'=>$quot,'quotitem'=>$quotitem,'company'=>$company]);

        // return view('admin.quotation.print_preview')->with(['quot'=>$quot,'quotitem'=>$quotitem,'company'=>$company,'discsum'=>$discsum]);

    }


    function quot_print(Request $request)
    {

        // $quots=quotation::where('quot_no',$request->quot_no)->first();
        // $quots->customer=$request->customer;
        // $quots->quot_date=date('Y-m-d',strtotime($request->date));
        // $quots->save();

        $quot = quotation::select('quotation.*', 'customers.customer_name', "customers.owner_gst", 'customers.primary_phone', 'customers.primary_email', 'contact.contact_name as contactname', 'contact.primary_phone', 'website_user.user_name', 'website_user.last_name', 'website_user.first_name', 'customers.tax_preference')
            ->leftJoin('customers', 'customers.id', 'quotation.customer')
            ->leftJoin('state', 'state.id', 'customers.state')
            ->leftJoin('contact', 'contact.id', 'quotation.contact_name')
            ->leftJoin('website_user', 'website_user.id', 'quotation.user_id')
            ->where('quotation.id', $request->quot_no)
            ->first();

        $state = state::where('state_name', $quot->billing_state)->first();
        $stateId = $state->id ?? 0;

        $quotitem = quotation_item::select('quot_item.*', 'product.product_name', 'product.make', 'product.model', 'product.material_name', 'category.category_image', 'product.product_image', "product.hsn", "product.item_code", 'category.category_name as catname', 'uom.uom_name')
            ->leftJoin('product', 'product.id', 'quot_item.product')
            ->leftJoin('category', 'category.id', 'product.category')
            ->leftJoin('uom', 'uom.id', 'product.uom')
            ->where('quot_item.quotation_no', $quot->quotation_no)
            ->get();
        //dd($quotitem);

        $discsum = quotation_item::select('quot_item.*', 'product.product_name', 'product.make', 'product.model', 'product.product_image', 'product.material_name', 'category.category_image')
            ->leftJoin('product', 'product.id', 'quot_item.product')
            ->leftJoin('category', 'category.id', 'product.category')
            ->where('quot_item.quotation_no', $quot->quotation_no)
            ->sum('quot_item.discount_amount');

        //dd($discsum);

        $company = company::select("company.*", "state.state_name")
            ->leftJoin("state", "state.id", "company.state")
            ->first();

        $terms = terms::query()->first();

        $filename = Carbon::now()->format('ymdhis') . '_' . $quot->quotation_no;

        $filename .= "_" . $quot->customer_name;
        $filename .= '.pdf';

        //LogActivity::addToLog($quot->quotation_no.' Quotation Print');
        $isInternalPrint = $request->is_internal;
        $pdf = PDF::loadView('admin.quotation_print_old', compact('stateId', 'quot', 'quotitem', 'company', 'terms', 'discsum', 'isInternalPrint'));
        //return view('admin.quotation_print_old', compact('quot','quotitem','company','terms','discsum'));

//        $pdf = PDF::loadView('admin.quotation_print', compact('quot','quotitem','company','terms','discsum'));

        return $pdf->download($filename);

        //return view('admin.quotation_print')->with(['quot'=>$quot,'quotitem'=>$quotitem,'company'=>$company]);

    }

    function quot_normal_print(Request $request)
    {
        // $quots=quotation::where('quot_no',$request->quot_no)->first();
        // $quots->customer=$request->customer;
        // $quots->quot_date=date('Y-m-d',strtotime($request->date));
        // $quots->save();

        $quot = quotation::select('quotation.*', 'customers.*', 'contact.contact_name', 'contact.primary_phone', 'website_user.user_name', 'website_user.last_name', 'website_user.first_name', 'customers.tax_preference')
            ->leftJoin('customers', 'customers.id', 'quotation.customer')
            ->leftJoin('contact', 'contact.id', 'quotation.contact_name')
            ->leftJoin('website_user', 'website_user.id', 'quotation.user_id')
            ->where('quotation.id', $request->quot_no)
            ->first();

        $quotitem = quotation_item::select('quot_item.*', 'product.product_name', 'product.make', 'product.model', 'product.material_name', 'category.category_image', 'product.product_image', 'category.category_name as catname', 'uom.uom_name')
            ->leftJoin('product', 'product.id', 'quot_item.product')
            ->leftJoin('category', 'category.id', 'product.category')
            ->leftJoin('uom', 'uom.id', 'product.uom')
            ->where('quot_item.quot_no', $quot->quot_no)
            ->get();

        $discsum = quotation_item::select('quot_item.*', 'product.product_name', 'product.make', 'product.model', 'product.product_image', 'product.material_name', 'category.category_image')
            ->leftJoin('product', 'product.id', 'quot_item.product')
            ->leftJoin('category', 'category.id', 'product.category')
            ->where('quot_item.quot_no', $quot->quot_no)
            ->sum('quot_item.discount_amount');

        //dd($discsum);

        $company = company::select("company.*", "state.state_name")
            ->leftJoin("state", "state.id", "company.state")
            ->first();

        $terms = terms::query()->first();

        $filename = $quot->quotation_no . '_';
        $filename .= $quot->customer_name;
        $filename .= '.pdf';

        $pdf = PDF::loadView('admin.quotation.print', compact('quot', 'quotitem', 'company', 'terms', 'discsum'));

        return $pdf->download($filename);

        //return view('admin.quotation_print')->with(['quot'=>$quot,'quotitem'=>$quotitem,'company'=>$company]);

    }

    function quotation_duplicate(Request $request)
    {
        $quot = quotation::where('id', $request->id)
            ->first();
        //dd($quot);
        $quotitem = quotation_item::select('quot_item.*', 'product.product_name', 'product.make', 'product.model')
            ->leftJoin('product', 'product.id', 'quot_item.product')
            ->where('quot_item.quotation_no', $quot->quotation_no)
            ->get();

        //dd($quotitem);

        $customer = ['' => 'select customer'] + customers::query()->orderBy('customer_name', 'asc')
                ->get()
                ->pluck('customer_name', 'id')
                ->toArray();

        if (empty($quot->contact_name)) {
            $contact_name = ['' => 'select contact'] + contact::query()
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


        $pterms = "";
        $duedate = date('d-m-Y');

        if (empty($quot->payment_terms)) {
            if (empty($customer_terms->payment_terms)) {
                $payment_terms = payment_terms::orderBy("terms_name", "asc")
                    ->get();
                foreach ($payment_terms as $pt) {
                    $pterms .= "<option value='" . $pt->days . "'>" . $pt->terms_name . "</option>";
                }

            } else {

                $payment_terms = payment_terms::where("id", $customer_terms->payment_terms)
                    ->first();
                //dd($payment_terms);
                $pterms .= "<option value='" . $payment_terms->days . "'>" . $payment_terms->terms_name . "</option>";

                $duedate = Date('d-m-Y', strtotime('+' . $payment_terms->days . ' days'));
                $payment_terms1 = payment_terms::orderBy("terms_name", "asc")
                    ->get();
                foreach ($payment_terms1 as $pt) {
                    $pterms .= "<option value='" . $pt->days . "'>" . $pt->terms_name . "</option>";
                }
                //$payment_terms=[$payment_terms->id=>$payment_terms->terms_name];
            }

        } else {
            $payment_terms1 = payment_terms::where("days", $quot->payment_terms)
                ->first();

            $duedate = Date('d-m-Y', strtotime('+' . $payment_terms1->days . ' days'));
            //dd($payment_terms);
            $pterms .= "<option value='" . $payment_terms1->days . "'>" . $payment_terms1->terms_name . "</option>";
            // dd($pterms);
            $payment_terms = payment_terms::orderBy("terms_name", "asc")
                ->get();

            //dd($payment_terms);
            foreach ($payment_terms as $pt) {
                $pterms .= "<option value='" . $pt->days . "'>" . $pt->terms_name . "</option>";
            }


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

        $bom = product::select('product.*', 'gst.gst_per', 'uom.uom_name')
            ->leftJoin('gst', 'gst.id', 'product.gst')
            ->leftJoin('uom', 'uom.id', 'product.uom')
            ->where('product.status', 'bom')
            ->orderBy('product.product_name', 'asc')
            ->get();

        //$term=terms::where('website_id',Session::get('website_id'))->first();

        $module = terms::query()
            ->get()
            ->pluck('module', 'id')
            ->toArray();

        // dd($pterms);

        return view("admin.quotation_duplicate")->with(["payment_terms" => $pterms, 'duedate' => $duedate, 'data' => $quot, 'quotitem' => $quotitem, 'customer' => $customer, 'product' => $product, 'service' => $service, 'module' => $module, 'contact_name' => $contact_name, 'bom' => $bom]);
        //

    }

    function quotation_edit(Request $request)
    {
        $quot = quotation::where('id', $request->id)
            ->first();
        //dd($quot);
        $quotitem = quotation_item::select('quot_item.*', "uom.uom_name", "product.item_code", 'product.product_name', 'product.make', 'product.model', "product.product_image", "product.bar_code")
            ->leftJoin('product', 'product.id', 'quot_item.product')
            ->leftJoin("uom", "uom.id", "product.uom")
            ->where('quot_item.quotation_no', $quot->quotation_no)
            ->get();

        //dd($quotitem);

        $customer = ['' => 'select customer'] + customers::query()->orderBy('customer_name', 'asc')
                ->get()
                ->pluck('customer_name', 'id')
                ->toArray();

        if (empty($quot->contact_name)) {
            $contact_name = ['' => 'select contact'] + contact::query()
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


        $pterms = "";
        $duedate = date('d-m-Y');

        if (empty($quot->payment_terms)) {
            if (empty($customer_terms->payment_terms)) {
                $payment_terms = payment_terms::orderBy("terms_name", "asc")
                    ->get();
                foreach ($payment_terms as $pt) {
                    $pterms .= "<option value='" . $pt->days . "'>" . $pt->terms_name . "</option>";
                }

            } else {

                $payment_terms = payment_terms::where("id", $customer_terms->payment_terms)
                    ->first();
                //dd($payment_terms);
                $pterms .= "<option value='" . $payment_terms->days . "'>" . $payment_terms->terms_name . "</option>";

                $duedate = Date('d-m-Y', strtotime('+' . $payment_terms->days . ' days'));
                $payment_terms1 = payment_terms::orderBy("terms_name", "asc")
                    ->get();
                foreach ($payment_terms1 as $pt) {
                    $pterms .= "<option value='" . $pt->days . "'>" . $pt->terms_name . "</option>";
                }
                //$payment_terms=[$payment_terms->id=>$payment_terms->terms_name];
            }

        } else {
            $payment_terms1 = payment_terms::where("days", $quot->payment_terms)
                ->first();

            $duedate = Date('d-m-Y', strtotime('+' . $payment_terms1->days . ' days'));
            //dd($payment_terms);
            $pterms .= "<option value='" . $payment_terms1->days . "'>" . $payment_terms1->terms_name . "</option>";
            // dd($pterms);
            $payment_terms = payment_terms::orderBy("terms_name", "asc")
                ->get();

            //dd($payment_terms);
            foreach ($payment_terms as $pt) {
                $pterms .= "<option value='" . $pt->days . "'>" . $pt->terms_name . "</option>";
            }


        }

        $product = product::select('product.*', 'gst.gst_per', 'uom.uom_name')
            ->leftJoin('gst', 'gst.id', 'product.gst')
            ->leftJoin('uom', 'uom.id', 'product.uom')
            ->where('product.status', 'product')
            ->orderBy('product.product_name', 'asc')
            ->get();
        //dd($product);

        $service = product::select('product.*', 'gst.gst_per', 'uom.uom_name')
            ->leftJoin('gst', 'gst.id', 'product.gst')
            ->leftJoin('uom', 'uom.id', 'product.uom')
            ->where('product.status', 'service')
            ->orderBy('product.product_name', 'asc')
            ->get();

        $bom = product::select('product.*', 'gst.gst_per', 'uom.uom_name')
            ->leftJoin('gst', 'gst.id', 'product.gst')
            ->leftJoin('uom', 'uom.id', 'product.uom')
            ->where('product.status', 'bom')
            ->orderBy('product.product_name', 'asc')
            ->get();

        //$term=terms::where('website_id',Session::get('website_id'))->first();

        $module = terms::query()
            ->get()
            ->pluck('module', 'id')
            ->toArray();

        $module = terms::get()->pluck('module', 'id')->toArray();
        $salesMan = salesman::get()->pluck('salesman_name', 'id')->toArray();
        // dd($pterms);

        return view("admin.quotation_edit")->with(["payment_terms" => $pterms, 'duedate' => $duedate, 'data' => $quot, 'quotitem' => $quotitem, 'customer' => $customer, 'product' => $product, 'service' => $service, 'module' => $module, 'contact_name' => $contact_name, 'bom' => $bom, 'salesMan' => $salesMan]);
        //

    }

    function quotation_normal_edit(Request $request)
    {
        $quot = quotation::where('id', $request->id)
            ->first();

        $quotitem = quotation_item::select('quot_item.*', 'product.product_name', 'product.make', 'product.model')
            ->leftJoin('product', 'product.id', 'quot_item.product')
            ->where('quot_item.quot_no', $quot->quot_no)
            ->get();


        $customer = ['' => 'select customer'] + customers::query()->orderBy('customer_name', 'asc')
                ->get()
                ->pluck('customer_name', 'id')
                ->toArray();

        if (empty($quot->contact_name)) {
            $contact_name = ['' => 'select contact'] + contact::query()
                    ->orderBy('contact_name', 'asc')
                    ->get()
                    ->pluck('contact_name', 'id')
                    ->toArray();
        } else {
            $cname = contact::select('id', 'contact_name')->where('id', $quot->contact_name)->first();

            $contact_name = [$cname->id => $cname->contact_name] + contact::query()
                    ->orderBy('contact_name', 'asc')
                    ->get()
                    ->pluck('contact_name', 'id')
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


        return view("admin.quotation.edit")->with(['data' => $quot, 'quotitem' => $quotitem, 'customer' => $customer, 'product' => $product, 'service' => $service, 'module' => $module, 'contact_name' => $contact_name]);
        //

    }

    function quot_product_update(Request $request)
    {
        $qitem = quotation_item::find($request->itemid);
        $qitem->product = $request->product;
        $qitem->qty = $request->qty;
        $qitem->price = $request->rate;
        $amount = $request->qty * $request->rate;
        $qitem->amount = round($amount);
        $qitem->gst_per = $request->gst_per;
        $qitem->gst_amount = $request->gst_amount;
        $qitem->grand_total = $request->total_amount;

        $qitem->save();

        $quotitem = quotation_item::select('quot_item.*', 'product.product_name', 'product.make', 'product.model')
            ->leftJoin('product', 'product.id', 'quot_item.product')
            ->where('quot_item.quot_no', $qitem->quot_no)
            ->get();

        $product = product::orderBy('product_name', 'asc')->get();
        $str = '<input type="hidden" value="' . $qitem->quot_no . '" id="quot_no">';
        $str .= '<table class="table table-striped add-edit-table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>Product</th>
                                                <th>Make</th>
                                                <th>Model</th>
                                                <th>Rate</th>
                                                <th>Qty</th>
                                                <th>Amount</th>
                                                <th>GST %</th>
                                                <th>GST Amount</th>
                                                <th>Total Amount</th>
                                                <th></th>
                                            </tr>
                                        </thead>';
        $str .= '<tbody>';
        $srno = 2;
        $grand_total = 0;
        foreach ($quotitem as $qi) {
            $grand_total = $grand_total + $qi->grand_total;
            $srno++;
            $str .= '<tr class="gradeX">
                                                <td>
                                                    <select class="form-control" onchange="get_product(this.value,' . $srno . ')" name="product[]" id="product' . $srno . '">
                                                        <option value="' . $qi->product . '">' . $qi->product_name . '</option>';

            foreach ($product as $prod) {
                $str .= '<option value="' . $prod->id . '">' . $prod->product_name . '</option>';
            }

            $str .= '</select>
                                                </td>
                                                <td>
                                                    <input type="text" name="make[]" value="' . $qi->make . '" class="form-control" id="make' . $srno . '">
                                                </td>
                                                <td>
                                                    <input type="text" name="model[]" value="' . $qi->model . '" class="form-control" id="model' . $srno . '">
                                                </td>
                                                 <td>
                                                    <input type="text" name="rate[]" value="' . $qi->price . '" class="form-control" id="rate' . $srno . '">
                                                </td>
                                                <td>
                                                    <input type="text" name="qty[]" oninput="cal(this.value,' . $srno . ')" class="form-control" id="qty' . $srno . '" value="' . $qi->qty . '">
                                                </td>
                                                <td>
                                                    <input type="text" name="amount[]" class="form-control" id="amount' . $srno . '" value="' . $qi->amount . '">
                                                </td>
                                               <td>
                                                    <input type="text" name="gst_per[]" class="form-control" id="gst_per' . $srno . '" value="' . $qi->gst_per . '">
                                                </td>
                                                <td>
                                                    <input type="text" name="gst_amount[]" class="form-control" id="gst_amount' . $srno . '" value="' . $qi->gst_amount . '">
                                                </td>
                                                <td>
                                                    <input type="text" name="total_amount[]" class="form-control" id="total_amount' . $srno . '" value="' . $qi->grand_total . '">
                                                </td>
                                                <td class="actions">
                                                    <a href="#" onclick="update_quot(' . $srno . ',' . $qi->id . ')" class="on-editing save-row" title="save"><i class="fa fa-save" style="font-size: 22px"></i></a>
                                                   <!--  <a href="#" class="on-editing cancel-row"><i class="fa fa-times"></i></a> -->
                                                   <!--  <a href="#" class="on-default edit-row"><i class="fa fa-pencil"></i></a>
                                                    <a href="#" class="on-default remove-row"><i class="fa fa-trash-o"></i></a> -->
                                                </td>
                                            </tr>';

        }

        $str .= ' <tr class="gradeX">
                                                <td>
                                                    <select class="form-control" onchange="get_product(this.value,1)" name="product[]" id="product1">
                                                        <option value="">select</option>';

        foreach ($product as $prod) {
            $str .= '<option value="' . $prod->id . '">' . $prod->product_name . '</option>';
        }

        $str .= '</select>
                                                </td>
                                                <td>
                                                    <input type="text" name="make[]" class="form-control" id="make1">
                                                </td>
                                                <td>
                                                    <input type="text" name="model[]" class="form-control" id="model1">
                                                </td>
                                                 <td>
                                                    <input type="text" name="rate[]" class="form-control" id="rate1">
                                                </td>
                                                <td>
                                                    <input type="text" name="qty[]" oninput="cal(this.value,1)" class="form-control" id="qty1">
                                                </td>
                                                <td>
                                                    <input type="text" name="amount[]" class="form-control" id="amount1">
                                                </td>
                                               <td>
                                                    <input type="text" name="gst_per[]" class="form-control" id="gst_per1">
                                                </td>
                                                <td>
                                                    <input type="text" name="gst_amount[]" class="form-control" id="gst_amount1">
                                                </td>
                                                <td>
                                                    <input type="text" name="total_amount[]" class="form-control" id="total_amount1">
                                                </td>
                                                <td class="actions">
                                                    <a href="#" onclick="save_quot(1)" class="on-editing save-row" title="save"><i class="fa fa-save" style="font-size: 22px"></i></a>
                                                   <!--  <a href="#" class="on-editing cancel-row"><i class="fa fa-times"></i></a> -->
                                                   <!--  <a href="#" class="on-default edit-row"><i class="fa fa-pencil"></i></a>
                                                    <a href="#" class="on-default remove-row"><i class="fa fa-trash-o"></i></a> -->
                                                </td>
                                            </tr>';

        $str .= '<tr><td colspan="8" style="text-align:right">Total</td><td>' . $grand_total . '</td></tr>';
        $str .= '</tbody>';


        $str .= '</table>';

        $gt = quotation::where('quot_no', $qitem->quot_no)->first();
        $gt->grand_total = $grand_total;
        $gt->quot_date = date('Y-m-d', strtotime($request->quot_date));
        $gt->customer = $request->customer;
        $gt->save();

        return $str;
    }

    function quot_product_save(Request $request)
    {
        $quot = quotation::where('quot_no', $request->quot_no)->first();
        if (empty($quot)) {
            $qsave = new quotation();
            $qno = quotation::max('quot_no');
            if (empty($qno)) {
                $qno = 1;
            } else {
                $qno = $qno + 1;
            }
            // dd($qno);
            $qsave->quot_no = $qno;
        } else {
            $qsave = quotation::where('quot_no', $request->quot_no)->first();
            $qno = $quot->quot_no;
            $qsave->quot_no = $qno;

        }

        $qsave->quot_date = date('Y-m-d', strtotime($request->quot_date));
        $qsave->customer = $request->customer;
        $qsave->user_id = Session::get('user_id');
        $qsave->website_id = Session::get('website_id');

        if ($qsave->save()) {
            $qitem = new quotation_item();
            $qitem->quot_no = $qno;
            $qitem->product = $request->product;
            $qitem->qty = $request->qty;
            $qitem->price = $request->rate;
            $amount = $request->qty * $request->rate;
            $qitem->amount = round($amount);
            $qitem->gst_per = $request->gst_per;
            $qitem->gst_amount = $request->gst_amount;
            $qitem->grand_total = $request->total_amount;

            $qitem->save();
        }


        $quotitem = quotation_item::select('quot_item.*', 'product.product_name', 'product.make', 'product.model')
            ->leftJoin('product', 'product.id', 'quot_item.product')
            ->where('quot_item.quot_no', $qno)
            ->get();

        $product = product::orderBy('product_name', 'asc')->get();
        $str = '<input type="hidden" value="' . $qno . '" id="quot_no">';
        $str .= '<table class="table table-striped add-edit-table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>Product</th>
                                                <th>Make</th>
                                                <th>Model</th>
                                                <th>Rate</th>
                                                <th>Qty</th>
                                                <th>Amount</th>
                                                <th>GST %</th>
                                                <th>GST Amount</th>
                                                <th>Total Amount</th>
                                                <th></th>
                                            </tr>
                                        </thead>';
        $str .= '<tbody>';
        $srno = 2;
        $grand_total = 0;
        foreach ($quotitem as $qi) {
            $grand_total = $grand_total + $qi->grand_total;
            $srno++;
            $str .= '<tr class="gradeX">
                                                <td>
                                                    <select class="form-control" onchange="get_product(this.value,' . $srno . ')" name="product[]" id="product' . $srno . '">
                                                        <option value="' . $qi->product . '">' . $qi->product_name . '</option>';

            foreach ($product as $prod) {
                $str .= '<option value="' . $prod->id . '">' . $prod->product_name . '</option>';
            }

            $str .= '</select>
                                                </td>
                                                <td>
                                                    <input type="text" name="make[]" value="' . $qi->make . '" class="form-control" id="make' . $srno . '">
                                                </td>
                                                <td>
                                                    <input type="text" name="model[]" value="' . $qi->model . '" class="form-control" id="model' . $srno . '">
                                                </td>
                                                 <td>
                                                    <input type="text" name="rate[]" value="' . $qi->price . '" class="form-control" id="rate' . $srno . '">
                                                </td>
                                                <td>
                                                    <input type="text" name="qty[]" oninput="cal(this.value,' . $srno . ')" class="form-control" id="qty' . $srno . '" value="' . $qi->qty . '">
                                                </td>
                                                <td>
                                                    <input type="text" name="amount[]" class="form-control" id="amount' . $srno . '" value="' . $qi->amount . '">
                                                </td>
                                               <td>
                                                    <input type="text" name="gst_per[]" class="form-control" id="gst_per' . $srno . '" value="' . $qi->gst_per . '">
                                                </td>
                                                <td>
                                                    <input type="text" name="gst_amount[]" class="form-control" id="gst_amount' . $srno . '" value="' . $qi->gst_amount . '">
                                                </td>
                                                <td>
                                                    <input type="text" name="total_amount[]" class="form-control" id="total_amount' . $srno . '" value="' . $qi->grand_total . '">
                                                </td>
                                                <td class="actions">
                                                    <a href="#" onclick="update_quot(' . $srno . ',' . $qi->id . ')" class="on-editing save-row" title="save"><i class="fa fa-save" style="font-size: 22px"></i></a>
                                                   <!--  <a href="#" class="on-editing cancel-row"><i class="fa fa-times"></i></a> -->
                                                   <!--  <a href="#" class="on-default edit-row"><i class="fa fa-pencil"></i></a>
                                                    <a href="#" class="on-default remove-row"><i class="fa fa-trash-o"></i></a> -->
                                                </td>
                                            </tr>';

        }

        $str .= ' <tr class="gradeX">
                                                <td>
                                                    <select class="form-control" onchange="get_product(this.value,1)" name="product[]" id="product1">
                                                        <option value="">select</option>';

        foreach ($product as $prod) {
            $str .= '<option value="' . $prod->id . '">' . $prod->product_name . '</option>';
        }

        $str .= '</select>
                                                </td>
                                                <td>
                                                    <input type="text" name="make[]" class="form-control" id="make1">
                                                </td>
                                                <td>
                                                    <input type="text" name="model[]" class="form-control" id="model1">
                                                </td>
                                                 <td>
                                                    <input type="text" name="rate[]" class="form-control" id="rate1">
                                                </td>
                                                <td>
                                                    <input type="text" name="qty[]" oninput="cal(this.value,1)" class="form-control" id="qty1">
                                                </td>
                                                <td>
                                                    <input type="text" name="amount[]" class="form-control" id="amount1">
                                                </td>
                                               <td>
                                                    <input type="text" name="gst_per[]" class="form-control" id="gst_per1">
                                                </td>
                                                <td>
                                                    <input type="text" name="gst_amount[]" class="form-control" id="gst_amount1">
                                                </td>
                                                <td>
                                                    <input type="text" name="total_amount[]" class="form-control" id="total_amount1">
                                                </td>
                                                <td class="actions">
                                                    <a href="#" onclick="save_quot(1)" class="on-editing save-row" title="save"><i class="fa fa-save" style="font-size: 22px"></i></a>
                                                   <!--  <a href="#" class="on-editing cancel-row"><i class="fa fa-times"></i></a> -->
                                                   <!--  <a href="#" class="on-default edit-row"><i class="fa fa-pencil"></i></a>
                                                    <a href="#" class="on-default remove-row"><i class="fa fa-trash-o"></i></a> -->
                                                </td>
                                            </tr>';

        $str .= '<tr><td colspan="8" style="text-align:right">Total</td><td>' . $grand_total . '</td></tr>';
        $str .= '</tbody>';


        $str .= '</table>';

        $gt = quotation::where('quot_no', $qno)->first();
        $gt->grand_total = $grand_total;
        $gt->save();

        return $str;
    }

    function get_po_product(Request $request)
    {

        $data = product::select('product.*', 'gst.gst_per', 'uom.uom_name')
            ->leftJoin('gst', 'gst.id', 'product.gst')
            ->leftJoin('uom', 'uom.id', 'product.uom')
            ->orderBy('product_name', 'asc')
            ->where('product.id', $request->product)
            ->first();


        if (empty($data->material)) {
            $hsn = "";
        } else {
            $material = material::where('id', $data->material)->first();
            //dd($material);
            $hsn = $material->hsn_code ?? '';

        }


        $productprice = $data->price;

        //dd($productprice);
        //dd($data);
        if (empty($data)) {
            $user[] = "";
        } else {
            $user[] = array('product_name' => $data->product_name, 'price' => $productprice, 'gst' => $data->gst_per, 'uom' => $data->uom_name, 'description' => $data->description, 'product_image' => $data->product_image, 'outer_diameter' => $data->outer_diameter, 'inner_diameter' => $data->inner_diameter, 'thikness' => $data->thikness, 'hsn' => $hsn);
        }
        //dd($user);
        return json_encode($user);
    }

    function get_product(Request $request)
    {

        $data = product::select('product.*', 'gst.gst_per', 'uom.uom_name', "stock_status.qty as stockqty")
            ->leftJoin('gst', 'gst.id', 'product.gst')
            ->leftJoin('uom', 'uom.id', 'product.uom')
            ->leftJoin('stock_status', 'stock_status.product', 'product.id')
            ->orderBy('product_name', 'asc')
            ->where('product.id', $request->product)
            ->first();

        $sub_product = bom_sub_product::select('product.product_name')
            ->leftJoin('product', 'product.id', 'bom_sub_product.product')
            ->where('bom_sub_product.bom_id', $data->id)
            ->get();

        $str = $data->description;

        // dd(htmlentities($str));
        $quotation = quotation::where('customer', $request->customer)
            ->orderBy('quotation_no', 'desc')
            ->first();


        $hsn = $data->hsn ?? '';


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
        //dd($customer);
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


        $pathImage = '/product_image/' . $data->product_image;


        //dd($productprice);
        //dd($data);
        if (empty($data)) {
            $user[] = "";
        } else {
            $user[] = array("stockqty" => $data->stockqty ?? 0, 'product_name' => $data->product_name, 'price' => $productprice, 'gst' => $igst, 'sgst' => $sgstper, 'cgst' => $cgstper, 'uom' => $data->uom_name, 'description' => $str, 'product_image' => $pathImage, 'outer_diameter' => $data->outer_diameter, 'inner_diameter' => $data->inner_diameter, 'thikness' => $data->thikness, 'hsn' => $hsn, 'discper' => $discper);
        }
        //dd($user);
        return json_encode($user);
    }

    function get_service(Request $request)
    {

        $data = product::select('product.*', 'gst.gst_per', 'uom.uom_name')
            ->leftJoin('gst', 'gst.id', 'product.gst')
            ->leftJoin('uom', 'uom.id', 'product.uom')
            ->where('product.id', $request->service)
            ->where('product.status', 'service')
            ->first();

        //dd($data);
        if (empty($data)) {
            $user[] = "";
        } else {
            $user[] = array('product_name' => $data->product_name, 'price' => $data->price, 'gst' => $data->gst_per, 'uom' => $data->uom_name, 'description' => $data->description, 'product_image' => $data->product_image, 'outer_diameter' => $data->outer_diameter, 'inner_diameter' => $data->inner_diameter, 'thikness' => $data->thikness, 'hsn' => $data->hsn);
        }
        //dd($user);
        return json_encode($user);
    }

    function get_customer(Request $request)
    {

        $data = customers::find($request->customer);
        if (!isset($data)) {
            return json_encode([]);
        }
        $bcityname = $bstatename = $bcountryname = $scityname = $sstatename = $scountryname = '';
        if ($data->billing_city != '') {
            $bcity = city::find($data->billing_city);
            $bcityname = $bcity->city_name;
        }
        if ($data->billing_state != '') {
            $bstate = state::find($data->billing_state);
            $bstatename = $bstate->state_name;
        }
        if ($data->billing_country != '') {
            $bcountry = country::find($data->billing_country);
            $bcountryname = $bcountry->country_name;
        }

        if ($data->shipping_city != '') {
            $scity = city::find($data->shipping_city);
            $scityname = $scity->city_name;
        }
        if ($data->shipping_state != '') {
            $sstate = state::find($data->shipping_state);
            $sstatename = $sstate->state_name;
        }
        if ($data->shipping_country != '') {
            $scountry = country::find($data->shipping_country);
            $scountryname = $scountry->country_name;
        }

        $pterms = "";
        //dd($data->payment_terms);
        if (empty($data->payment_terms)) {
            $payment_terms = payment_terms::orderBy("terms_name", "asc")
                ->get();
            foreach ($payment_terms as $pt) {
                $pterms .= "<option value='" . $pt->days . "'>" . $pt->terms_name . "</option>";
            }

            $duedate = Date('d-m-Y', strtotime('+ 15 days'));
        } else {
            $payment_terms = payment_terms::where("days", $data->payment_terms)
                ->first();

            $payment_terms1 = payment_terms::orderBy("terms_name", "asc")
                ->get();

            // dd($payment_terms);
            $pterms .= "<option value='$payment_terms->days'>$payment_terms->terms_name</option>";

            foreach ($payment_terms1 as $pt) {
                if ($pt->days == $payment_terms->days) {
                } else {
                    $pterms .= "<option value='$pt->days'>$pt->terms_name</option>";
                }

            }

            $duedate = Date('d-m-Y', strtotime('+ ' . $payment_terms->days . ' days'));
        }

        if (empty($data)) {
            $user[] = "";
        } else {
            $user[] = array("pterms" => $pterms, 'duedate' => $duedate, 'name' => $data->customer_name, 'primary_phone' => $data->primary_phone, 'secondary_phone' => $data->secondary_phone, 'primary_email' => $data->primary_email, 'secondary_email' => $data->secondary_email, 'owner_name' => $data->owner_name, 'owner_mobile' => $data->owner_mobile, 'owner_email' => $data->owner_email, 'owner_gst' => $data->owner_gst, 'owner_pan' => $data->owner_pan, 'billing_address' => $data->billing_address, 'shipping_address' => $data->shipping_address, 'billing_pobox' => $data->billing_pobox, 'shipping_pobox' => $data->shipping_pobox, 'billing_city' => $bcityname, 'shipping_city' => $scityname, 'billing_state' => $bstatename, 'shipping_state' => $sstatename, 'billing_postalcode' => $data->billing_postalcode, 'shipping_postalcode' => $data->shipping_postalcode, 'billing_country' => $bcountryname, 'shipping_country' => $scountryname, 'description' => $data->description);
        }


        return json_encode($user);
    }

    function get_contact(Request $request)
    {
        $contact = contact::where('customer', $request->customer)->get();
        $str = "";
        foreach ($contact as $value) {
            $str .= '<option value="' . $value->id . '">' . $value->contact_name . '</option>';
        }
        return $str;
    }

    function get_vendor(Request $request)
    {

        $data = vendor::find($request->customer);
        $bcityname = $bstatename = $bcountryname = $scityname = $sstatename = $scountryname = '';
        if ($data->billing_city != '') {
            $bcity = city::find($data->billing_city);
            $bcityname = $bcity->city_name;
        }
        if ($data->billing_state != '') {
            $bstate = state::find($data->billing_state);
            $bstatename = $bstate->state_name;
        }
        if ($data->billing_country != '') {
            $bcountry = country::find($data->billing_country);
            $bcountryname = $bcountry->country_name;
        }

        if ($data->shipping_city != '') {
            $scity = city::find($data->shipping_city);
            $scityname = $scity->city_name;
        }
        if ($data->shipping_state != '') {
            $sstate = state::find($data->shipping_state);
            $sstatename = $sstate->state_name;
        }
        if ($data->shipping_country != '') {
            $scountry = country::find($data->shipping_country);
            $scountryname = $scountry->country_name;
        }


        $pterms = "";
        //dd($data->payment_terms);
        if (empty($data->payment_terms)) {
            $payment_terms = payment_terms::orderBy("terms_name", "asc")
                ->get();
            foreach ($payment_terms as $pt) {
                $pterms .= "<option value='" . $pt->days . "'>" . $pt->terms_name . "</option>";
            }

            $duedate = Date('d-m-Y', strtotime('+ 15 days'));
        } else {
            $payment_terms = payment_terms::where("days", $data->payment_terms)
                ->first();
            //dd($payment_terms);
            $payment_terms1 = payment_terms::orderBy("terms_name", "asc")
                ->get();

            // dd($payment_terms);
            $pterms .= "<option value='$payment_terms->days'>$payment_terms->terms_name</option>";

            foreach ($payment_terms1 as $pt) {
                if ($pt->days == $payment_terms->days) {
                } else {
                    $pterms .= "<option value='$pt->days'>$pt->terms_name</option>";
                }

            }

            $duedate = Date('d-m-Y', strtotime('+ ' . $payment_terms->days . ' days'));
        }

        if (empty($data)) {
            $user[] = "";
        } else {
            $user[] = array('pterms' => $pterms, 'duedate' => $duedate, 'name' => $data->vendor_name, 'primary_phone' => $data->primary_phone, 'secondary_phone' => $data->secondary_phone, 'primary_email' => $data->primary_email, 'secondary_email' => $data->secondary_email, 'owner_name' => $data->owner_name, 'owner_mobile' => $data->owner_mobile, 'owner_email' => $data->owner_email, 'owner_gst' => $data->owner_gst, 'owner_pan' => $data->owner_pan, 'billing_address' => $data->billing_address, 'shipping_address' => $data->shipping_address, 'billing_pobox' => $data->billing_pobox, 'shipping_pobox' => $data->shipping_pobox, 'billing_city' => $bcityname, 'shipping_city' => $scityname, 'billing_state' => $bstatename, 'shipping_state' => $sstatename, 'billing_postalcode' => $data->billing_postalcode, 'shipping_postalcode' => $data->shipping_postalcode, 'billing_country' => $bcountryname, 'shipping_country' => $scountryname, 'description' => $data->description);
        }


        return json_encode($user);
    }

    function quotation_add(Request $request)
    {
        $customer = ['' => 'select customer'] + customers::orderBy('customer_name', 'asc')
                ->get()
                ->pluck('customer_name', 'id')
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

        $bom = product::select('product.*', 'gst.gst_per', 'uom.uom_name')
            ->leftJoin('gst', 'gst.id', 'product.gst')
            ->leftJoin('uom', 'uom.id', 'product.uom')
            ->where('product.status', 'bom')
            ->orderBy('product.product_name', 'asc')
            ->get();

        $term = ['' => 'select terms'] + terms::query()
                ->get()->pluck('module', 'id')->toArray();

        $pterms = "";

        $payment_terms = payment_terms::orderBy("terms_name", "asc")
            ->get();
        foreach ($payment_terms as $pt) {
            $pterms .= "<option value='" . $pt->days . "'>" . $pt->terms_name . "</option>";
        }

        $duedate = Date('d-m-Y', strtotime('+ 15 days'));

        $salesMan = ['' => 'select sales person'] + salesman::orderBy('salesman_name', 'asc')
                ->get()
                ->pluck('salesman_name', 'id')
                ->toArray();

        return view("admin.quotation_add")
            ->with(['payment_terms' => $pterms, 'due_date' => $duedate, 'customer' => $customer, 'salesMan' => $salesMan, 'product' => $product, 'service' => $service, 'bom' => $bom, 'module' => $term]);
    }

    function quotation_add1(Request $request)
    {
        $custom = customers::where('id', $request->cust_id)->first();

        $customer = [$custom->id => $custom->customer_name] + customers::orderBy('customer_name', 'asc')
                ->get()
                ->pluck('customer_name', 'id')
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


        $bom = product::select('product.*', 'gst.gst_per', 'uom.uom_name')
            ->leftJoin('gst', 'gst.id', 'product.gst')
            ->leftJoin('uom', 'uom.id', 'product.uom')
            ->where('product.status', 'bom')
            ->orderBy('product.product_name', 'asc')
            ->get();

        $term = ['' => 'select terms'] + terms::query()
                ->get()->pluck('module', 'id')->toArray();

        $module = ['' => 'select terms'] + terms::query()
                ->get()->pluck('module', 'id')->toArray();

        return view("admin.quotation_add")->with(['bom' => $bom, 'customer' => $customer, 'product' => $product, 'service' => $service, 'terms' => $term,
            "module" => $module]);
    }


    function quotation_normal_add(Request $request)
    {
        $customer = ['' => 'select customer'] + customers::orderBy('customer_name', 'asc')
                ->get()
                ->pluck('customer_name', 'id')
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

        $term = ['' => 'select terms'] + terms::query()
                ->get()->pluck('module', 'id')->toArray();

        return view("admin.quotation.create")->with(['customer' => $customer, 'product' => $product, 'service' => $service, 'terms' => $term]);
    }

    function quotation_normal_add1(Request $request)
    {
        $custom = customers::where('id', $request->cust_id)->first();
        $customer = [$custom->id => $custom->customer_name] + customers::orderBy('customer_name', 'asc')
                ->get()
                ->pluck('customer_name', 'id')
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

        $term = ['' => 'select terms'] + terms::query()
                ->get()->pluck('module', 'id')->toArray();

        return view("admin.quotation_normal_add")->with(['customer' => $customer, 'product' => $product, 'service' => $service, 'terms' => $term]);
    }

    function quotation_list(Request $request)
    {
        //dd(Session::get('finacial_year_id'));
        $product = new quotation();

        $quot_no = $client_name = $quot_date = $subject = $amount = $quot_stage = "";

        $product = $product->select('quotation.*', 'customers.customer_name', 'customers.primary_email', 'customers.secondary_email')
            ->when(isset($request->status) && $request->status == 'Y', function ($query) {
                $query->whereNull('quotation.so_status');
            });
        $product = $product->leftJoin('customers', 'customers.id', 'quotation.customer');
        // $product=$product->where('quotation.finacial_year',Session::get('finacial_year_id'));

        if ($request->quot_no != '') {
            $quot_no = $request->quot_no;
            $product = $product->Where('quotation.quotation_no', 'like', '%' . $request->quot_no . '%');
        }
        if ($request->client_name != '') {
            $client_name = $request->client_name;
            $product = $product->Where('quotation.customer_name', 'like', '%' . $request->client_name . '%');
        }
        if (isset($request->from_date) and isset($request->end_date)) {
            $from = date('Y-m-d', strtotime($request->from_date));
            $to = date('Y-m-d', strtotime($request->end_date));
            $product = $product->whereBetween('quot_date', [$from, $to]);
        }
        if ($request->subject != '') {
            $subject = $request->subject;
            $product = $product->Where('quotation.subject', 'like', '%' . $request->subject . '%');
        }
        if ($request->amount != '') {
            $amount = $request->amount;
            $product = $product->Where('quotation.grand_total', 'like', '%' . $request->amount . '%');
        }
        if ($request->quot_stage != '') {
            $quot_stage = $request->quot_stage;
            $product = $product->Where('quotation.quot_stage', 'like', '%' . $request->quot_stage . '%');
        }

        if (isset($request->quotno_asc)) {
            $product = $product->orderBy('quotation.quot_no', 'asc');
        }
        if (isset($request->quotno_desc)) {
            $product = $product->orderBy('quotation.quot_no', 'desc');
        }

        if (isset($request->quot_date_asc)) {
            $product = $product->orderBy('quotation.quot_date', 'asc');
        }
        if (isset($request->quot_date_desc)) {
            $product = $product->orderBy('quotation.quot_date', 'desc');
        }

        if (isset($request->client_asc)) {
            $product = $product->orderBy('quotation.customer_name', 'asc');
        }
        if (isset($request->client_desc)) {
            $product = $product->orderBy('quotation.customer_name', 'desc');
        }

        if (isset($request->subject_asc)) {
            $product = $product->orderBy('quotation.subject', 'asc');
        }
        if (isset($request->subject_desc)) {
            $product = $product->orderBy('quotation.subject', 'desc');
        }

        if (isset($request->amount_asc)) {
            $product = $product->orderBy('quotation.grand_total', 'asc');
        }
        if (isset($request->amount_desc)) {
            $product = $product->orderBy('quotation.grand_total', 'desc');
        }
        if (isset($request->stage_asc)) {
            $product = $product->orderBy('quotation.quot_stage', 'asc');
        }
        if (isset($request->stage_desc)) {
            $product = $product->orderBy('quotation.quot_stage', 'desc');
        }
        //echo print_r($request->all());

        $product = $product->orderBy('quotation.id', 'desc');
        $result = $product->paginate(10);

        $company_name = company::select('company_name')->first();


        $stage = array('Created' => 'Created', 'Sent' => 'Sent', 'Reviewing' => 'Reviewing', 'QuoteRivision' => 'QuoteRivision', 'Accepted' => 'Accepted', 'Invoiced' => 'Invoiced', 'Canceled' => 'Canceled');

        if (isset($request->tally_quotation)) {

            return Excel::download(new InvoiceExport($request), 'quotation_tally.xlsx');
        }

        if (isset($request->export_excel)) {
            return Excel::download(new quotationExport($request), 'QuotationReport.xlsx');
        }

        return view('admin.quotation_list')
            ->with(['list' => $result, 'company' => $company_name->company_name, 'stage' => $stage]);


    }


    function quotation_normal_list(Request $request)
    {
        $product = new quotation();
        $quot_no = $client_name = $quot_date = $subject = $amount = $quot_stage = "";

        $product = $product->select('quotation.*', 'customers.customer_name', 'customers.primary_email', 'customers.secondary_email');
        $product = $product->leftJoin('customers', 'customers.id', 'quotation.customer');
        $product = $product;

        if ($request->quot_no != '') {
            $quot_no = $request->quot_no;
            $product = $product->Where('quotation.quotation_no', 'like', '%' . $request->quot_no . '%');
        }
        if ($request->client_name != '') {
            $client_name = $request->client_name;
            $product = $product->Where('quotation.customer_name', 'like', '%' . $request->client_name . '%');
        }
        if ($request->quot_date != '') {
            $quot_date = date('Y-m-d', strtotime($request->quot_date));
            $product = $product->Where('quotation.quot_date', 'like', '%' . $quot_date . '%');
        }
        if ($request->subject != '') {
            $subject = $request->subject;
            $product = $product->Where('quotation.subject', 'like', '%' . $request->subject . '%');
        }
        if ($request->amount != '') {
            $amount = $request->amount;
            $product = $product->Where('quotation.net_amount', 'like', '%' . $request->amount . '%');
        }
        if ($request->quot_stage != '') {
            $quot_stage = $request->quot_stage;
            $product = $product->Where('quotation.quot_stage', 'like', '%' . $request->quot_stage . '%');
        }

        if (isset($request->quotno_asc)) {
            $product = $product->orderBy('quotation.quot_no', 'asc');
        }
        if (isset($request->quotno_desc)) {
            $product = $product->orderBy('quotation.quot_no', 'desc');
        }

        if (isset($request->quot_date_asc)) {
            $product = $product->orderBy('quotation.quot_date', 'asc');
        }
        if (isset($request->quot_date_desc)) {
            $product = $product->orderBy('quotation.quot_date', 'desc');
        }

        if (isset($request->client_asc)) {
            $product = $product->orderBy('quotation.customer_name', 'asc');
        }
        if (isset($request->client_desc)) {
            $product = $product->orderBy('quotation.customer_name', 'desc');
        }

        if (isset($request->subject_asc)) {
            $product = $product->orderBy('quotation.subject', 'asc');
        }
        if (isset($request->subject_desc)) {
            $product = $product->orderBy('quotation.subject', 'desc');
        }

        if (isset($request->amount_asc)) {
            $product = $product->orderBy('quotation.grand_total', 'asc');
        }
        if (isset($request->amount_desc)) {
            $product = $product->orderBy('quotation.grand_total', 'desc');
        }
        if (isset($request->stage_asc)) {
            $product = $product->orderBy('quotation.quot_stage', 'asc');
        }
        if (isset($request->stage_desc)) {
            $product = $product->orderBy('quotation.quot_stage', 'desc');
        }
        //echo print_r($request->all());
        $product = $product->orderBy('quotation.id', 'desc');
        $result = $product->paginate(10);

        $company_name = company::select('company_name')->first();

        return view('admin.quotation.index')->with(['list' => $result, 'company' => $company_name->company_name]);
    }

    function quot_list_preview(Request $request)
    {
        $product = new quotation();
        $quot_no = $client_name = $quot_date = $subject = $amount = $quot_stage = "";

        $product = $product->select('quotation.*', 'customers.customer_name', 'customers.primary_email', 'customers.secondary_email');
        $product = $product->leftJoin('customers', 'customers.id', 'quotation.customer');
        $product = $product;

        if ($request->quot_no != '') {
            $quot_no = $request->quot_no;
            $product = $product->Where('quotation.quotation_no', 'like', '%' . $request->quot_no . '%');
        }
        if ($request->client_name != '') {
            $client_name = $request->client_name;
            $product = $product->Where('quotation.customer_name', 'like', '%' . $request->client_name . '%');
        }
        if ($request->quot_date != '') {
            $quot_date = date('Y-m-d', strtotime($request->quot_date));
            $product = $product->Where('quotation.quot_date', 'like', '%' . $quot_date . '%');
        }
        if ($request->subject != '') {
            $subject = $request->subject;
            $product = $product->Where('quotation.subject', 'like', '%' . $request->subject . '%');
        }
        if ($request->amount != '') {
            $amount = $request->amount;
            $product = $product->Where('quotation.net_amount', 'like', '%' . $request->amount . '%');
        }
        if ($request->quot_stage != '') {
            $quot_stage = $request->quot_stage;
            $product = $product->Where('quotation.quot_stage', 'like', '%' . $request->quot_stage . '%');
        }
        $product = $product->Where('quotation.customer', $request->id);

        $product = $product->orderBy('quotation.id', 'desc');

        //echo print_r($request->all());
        $result = $product->paginate(10);

        return view('admin.quot_list_preview')->with(['list' => $result]);
    }

    function quot_normal_list_preview(Request $request)
    {
        $product = new quotation();
        $quot_no = $client_name = $quot_date = $subject = $amount = $quot_stage = "";

        $product = $product->select('quotation.*', 'customers.customer_name', 'customers.primary_email', 'customers.secondary_email');
        $product = $product->leftJoin('customers', 'customers.id', 'quotation.customer');
        $product = $product;

        if ($request->quot_no != '') {
            $quot_no = $request->quot_no;
            $product = $product->Where('quotation.quotation_no', 'like', '%' . $request->quot_no . '%');
        }
        if ($request->client_name != '') {
            $client_name = $request->client_name;
            $product = $product->Where('quotation.customer_name', 'like', '%' . $request->client_name . '%');
        }
        if ($request->quot_date != '') {
            $quot_date = date('Y-m-d', strtotime($request->quot_date));
            $product = $product->Where('quotation.quot_date', 'like', '%' . $quot_date . '%');
        }
        if ($request->subject != '') {
            $subject = $request->subject;
            $product = $product->Where('quotation.subject', 'like', '%' . $request->subject . '%');
        }
        if ($request->amount != '') {
            $amount = $request->amount;
            $product = $product->Where('quotation.net_amount', 'like', '%' . $request->amount . '%');
        }
        if ($request->quot_stage != '') {
            $quot_stage = $request->quot_stage;
            $product = $product->Where('quotation.quot_stage', 'like', '%' . $request->quot_stage . '%');
        }
        $product = $product->Where('quotation.customer', $request->id);

        $product = $product->orderBy('quotation.id', 'desc');

        //echo print_r($request->all());
        $result = $product->paginate(10);

        $company_name = company::select('company_name')->first();

        return view('admin.quot_normal_list_preview')->with(['list' => $result, 'company' => $company_name->company_name]);
    }

    function customer_delete(Request $request)
    {
        $save = customers::find($request->id);
        if ($save->delete()) {
            return redirect()->route('customer-list')->with('message', 'customer delete successfully');
        } else {
            return back()->with('message', 'error in save');
        }
    }

    function customer_update(Request $request)
    {

        $save = customers::find($request->id);
        //  dd($request->all());

        $request->validate([
            'customer_name' => 'required',
            'primary_email' => 'email',
            'owner_name' => 'required|max:255',
            'owner_mobile' => 'required|max:255',
            'owner_email' => 'email',
            'billing_address' => 'required',
            'shipping_address' => 'required',
            'billing_city' => 'required',
            'shipping_city' => 'required',
            'billing_postalcode' => 'required',
            'shipping_postalcode' => 'required',
            'shipping_country' => 'required',
            'work_email' => 'required',
            'primary_phone' => 'required',
            'alternate_no' => 'required',
            'billing_country' => 'required',
            'billing_state' => 'required',
            'shipping_state' => 'required',
            'department' => 'required',
            'designation' => 'required',
        ]);
        // / dd($request->all());
        $save->customer_name = $request->customer_name;
        $save->website = $request->website;
        $save->primary_phone = $request->primary_phone;
        $save->secondary_phone = $request->secondary_phone;
        $save->primary_email = $request->primary_email;
        $save->secondary_email = $request->secondary_email;
        $save->owner_name = $request->owner_name;
        $save->owner_mobile = $request->owner_mobile;
        $save->owner_email = $request->owner_email;
        $save->work_email = $request->work_email;
        $save->alternate_no = $request->alternate_no;
        $save->owner_gst = $request->owner_gst;
        $save->owner_pan = $request->owner_pan;
        $save->billing_address = $request->billing_address;
        $save->shipping_address = $request->shipping_address;
        $save->billing_pobox = $request->billing_pobox;
        $save->shipping_pobox = $request->shipping_pobox;
        $save->billing_city = $request->billing_city;
        $save->shipping_city = $request->shipping_city;
        $save->billing_state = $request->billing_state;
        $save->shipping_state = $request->shipping_state;
        $save->billing_postalcode = $request->billing_postalcode;
        $save->shipping_postalcode = $request->shipping_postalcode;
        $save->billing_country = $request->billing_country;
        $save->shipping_country = $request->shipping_country;
        $save->description = $request->description;
        $save->tax_preference = $request->tax_preference;
        $save->payment_terms = $request->payment_terms;
        $save->user_id = Session::get('user_id');
        $save->website_id = Session::get('website_id');
        $save->industry = $request->industry;
        $save->type = $request->type;
        $save->department = $request->department;
        $save->designation = $request->designation;
        if ($save->save()) {
            //LogActivity::addToLog($request->customer_name.' Customer Update ');

            return redirect()->route('admin.customers.list')->with('message', 'Customer Update successfully');
        } else {
            return back()->with('message', 'error in save');
        }
    }

    function customer_edit(Request $request)
    {
        $industry = ['' => 'select industry'] + industry::query()
                ->orderBy('industry_name')->get()->pluck('industry_name', 'id')->toArray();
        $type = ['' => 'select type'] + type::query()
                ->orderBy('type_name')->get()->pluck('type_name', 'id')->toArray();

        $country = ['' => 'select country'] + country::orderBy('country_name', 'asc')
                ->get()->pluck('country_name', 'id')->toArray();

        $state = ['' => 'select state'] + state::orderBy('state_name', 'asc')
                ->get()->pluck('state_name', 'id')->toArray();

        $city = ['' => 'select city'] + city::orderBy('city_name', 'asc')
                ->get()->pluck('city_name', 'id')->toArray();

        $payment_terms = ['' => 'select'] + payment_terms::orderBy("id", "desc")
                ->get()->pluck("terms_name", "days")->toArray();

        $data = customers::find($request->id);
        return view('admin.customer.edit')->with(['payment_terms' => $payment_terms, 'data' => $data, 'industry' => $industry, 'type' => $type, 'country' => $country, 'state' => $state, 'city' => $city]);
    }

    function customer_preview(Request $request)
    {
        $industry = ['' => 'select industry'] + industry::query()
                ->orderBy('industry_name')->get()->pluck('industry_name', 'id')->toArray();
        $type = ['' => 'select type'] + type::query()
                ->orderBy('type_name')->get()->pluck('type_name', 'id')->toArray();

        $country = ['' => 'select country'] + country::orderBy('country_name', 'asc')
                ->get()->pluck('country_name', 'id')->toArray();

        $state = ['' => 'select state'] + state::orderBy('state_name', 'asc')
                ->get()->pluck('state_name', 'id')->toArray();

        $city = ['' => 'select city'] + city::orderBy('city_name', 'asc')
                ->get()->pluck('city_name', 'id')->toArray();


        $data = customers::select('customers.*', 'industry.industry_name', 'type.type_name', 'country.country_name', 'state.state_name', 'city.city_name')
            ->leftJoin('industry', 'industry.id', 'customers.industry')
            ->leftJoin('type', 'type.id', 'customers.type')
            ->leftJoin('country', 'country.id', 'customers.billing_country')
            ->leftJoin('state', 'state.id', 'customers.billing_state')
            ->leftJoin('city', 'city.id', 'customers.billing_city')
            ->where('customers.id', $request->id)
            ->first();

        if (empty($data->shipping_country)) {
            $shipping_country = "";
        } else {
            $shipping_country1 = country::find($data->shipping_country);
            $shipping_country = $shipping_country1->country_name;
        }

        if (empty($data->shipping_state)) {
            $shipping_state = "";
        } else {
            $shipping_state1 = state::find($data->shipping_state);
            $shipping_state = $shipping_state1->state_name;
        }

        if (empty($data->shipping_city)) {
            $shipping_city = "";
        } else {
            $shipping_city1 = city::find($data->shipping_city);
            $shipping_city = $shipping_city1->city_name;
        }

        $contact = contact::select('contact.*', 'customers.customer_name')
            ->leftJoin('customers', 'customers.id', 'contact.customer')
            ->where('contact.customer', $request->id)
            ->get();

        return view('admin.customer_preview')->with(['data' => $data, 'industry' => $industry, 'type' => $type, 'country' => $country, 'state' => $state, 'city' => $city, 'contact' => $contact, 'shipping_city' => $shipping_city, 'shipping_state' => $shipping_state, 'shipping_country' => $shipping_country]);
    }

    function customer_save(Request $request)
    {
        date_default_timezone_set('Asia/Kolkata');
        $save = new customers();


        $request->validate([
            'customer_name' => 'required|max:255|unique:customers',
            'primary_email' => 'email:rfc,dns',
            'owner_name' => 'required|max:255',
            'owner_mobile' => 'required|max:255',
            'owner_email' => 'email:rfc,dns',
            'billing_address' => 'required',
            'shipping_address' => 'required',
            'billing_city' => 'required',
            'shipping_city' => 'required',
            'billing_postalcode' => 'required',
            'shipping_postalcode' => 'required',
            'shipping_country' => 'required',
            'work_email' => 'required',
            'primary_phone' => 'required',
            'alternate_no' => 'required',
            'billing_country' => 'required',
            'billing_state' => 'required',
            'shipping_state' => 'required',
            'department' => 'required',
            'designation' => 'required',
        ]);
        // / dd($request->all());
        $save->customer_name = $request->customer_name;
        $save->website = $request->website;
        $save->primary_phone = $request->primary_phone;
        $save->secondary_phone = $request->secondary_phone;
        $save->primary_email = $request->primary_email;
        $save->secondary_email = $request->secondary_email;
        $save->owner_name = $request->owner_name;
        $save->owner_mobile = $request->owner_mobile;
        $save->owner_email = $request->owner_email;
        $save->work_email = $request->work_email;
        $save->alternate_no = $request->alternate_no;
        $save->owner_gst = $request->owner_gst;
        $save->owner_pan = $request->owner_pan;
        $save->billing_address = $request->billing_address;
        $save->shipping_address = $request->shipping_address;
        $save->billing_pobox = $request->billing_pobox;
        $save->shipping_pobox = $request->shipping_pobox;
        $save->billing_city = $request->billing_city;
        $save->shipping_city = $request->shipping_city;
        $save->billing_state = $request->billing_state;
        $save->shipping_state = $request->shipping_state;
        $save->billing_postalcode = $request->billing_postalcode;
        $save->shipping_postalcode = $request->shipping_postalcode;
        $save->billing_country = $request->billing_country;
        $save->shipping_country = $request->shipping_country;
        $save->description = $request->description;
        $save->created_time = date('Y-m-d h:i:s A');
        $save->user_id = Session::get('user_id');
        $save->website_id = Session::get('website_id');
        $save->industry = $request->industry;
        $save->type = $request->type;
        $save->department = $request->department;
        $save->designation = $request->designation;
        $save->tax_preference = $request->tax_preference;
        $save->payment_terms = $request->payment_terms;
        if ($save->save()) {
            $save1 = new contact();
            $save1->customer = $save->id;
            $save1->contact_name = $request->owner_name;

            $save1->primary_phone = $request->owner_mobile;
            $save1->secondary_phone = $request->alternate_no;
            $save1->primary_email = $request->owner_email;
            $save1->secondary_email = $request->work_email;

            $save1->created_time = date('Y-m-d h:i:s A');
            $save1->user_id = Session::get('user_id');
            $save1->website_id = Session::get('website_id');

            $save1->department = $request->department;
            $save1->designation = $request->designation;

            $save1->save();

            //LogActivity::addToLog($request->customer_name.' Customer Create ');

            return redirect()->route('admin.customers.list')->with('message', 'Customer save successfully');
        } else {
            return back()->with('message', 'error in save');
        }
    }

    function customer_add()
    {
        $industry = ['' => 'select industry'] + industry::query()
                ->orderBy('industry_name')->get()->pluck('industry_name', 'id')->toArray();
        $type = ['' => 'select type'] + type::query()
                ->orderBy('type_name')->get()->pluck('type_name', 'id')->toArray();

        $country = ['' => 'select country'] + country::orderBy('country_name', 'asc')
                ->get()->pluck('country_name', 'id')->toArray();

        $state = ['' => 'select state'] + state::orderBy('state_name', 'asc')
                ->get()->pluck('state_name', 'id')->toArray();

        $city = ['' => 'select city'] + city::orderBy('city_name', 'asc')
                ->get()->pluck('city_name', 'id')->toArray();

        $payment_terms = ['' => 'select'] + payment_terms::orderBy("id", "desc")
                ->get()->pluck("terms_name", "days")->toArray();
        return view("admin/customer.create")->with(['payment_terms' => $payment_terms, 'industry' => $industry, 'type' => $type, 'country' => $country, 'state' => $state, 'city' => $city]);
    }

    function customer_list(Request $request)
    {
        $data = new customers();
        $data = $data->select('customers.*', 'city.city_name');
        $data = $data->leftJoin('city', 'city.id', 'customers.billing_city');


        if ($request->customer_name != '') {
            $data = $data->Where('customers.customer_name', 'like', '%' . $request->customer_name . '%');
        }
        if ($request->primary_phone != '') {
            $data = $data->Where('customers.primary_phone', 'like', '%' . $request->primary_phone . '%');
        }
        if ($request->primary_email != '') {
            $data = $data->Where('customers.primary_email', 'like', '%' . $request->primary_email . '%');
        }
        if ($request->owner_name != '') {
            $data = $data->Where('customers.owner_name', 'like', '%' . $request->owner_name . '%');
        }
        if ($request->owner_mobile != '') {
            $data = $data->Where('customers.owner_mobile', 'like', '%' . $request->owner_mobile . '%');
        }
        if ($request->owner_email != '') {
            $data = $data->Where('customers.owner_email', 'like', '%' . $request->owner_email . '%');
        }
        if (isset($request->cname_asc)) {
            $data = $data->orderBy('customers.customer_name', 'asc');
        }
        if (isset($request->cname_desc)) {
            $data = $data->orderBy('customers.customer_name', 'desc');
        }
        if (isset($request->office_phone_asc)) {
            $data = $data->orderBy('customers.primary_phone', 'asc');
        }
        if (isset($request->office_phone_desc)) {
            $data = $data->orderBy('customers.primary_phone', 'desc');
        }
        if (isset($request->office_email_asc)) {
            $data = $data->orderBy('customers.primary_email', 'asc');
        }
        if (isset($request->office_email_desc)) {
            $data = $data->orderBy('customers.owner_name', 'desc');
        }

        if (isset($request->owner_name_asc)) {
            $data = $data->orderBy('customers.owner_name', 'asc');
        }
        if (isset($request->owner_name_desc)) {
            $data = $data->orderBy('customers.owner_name', 'desc');
        }
        if (isset($request->owner_phone_asc)) {
            $data = $data->orderBy('customers.owner_mobile', 'asc');
        }
        if (isset($request->owner_phone_desc)) {
            $data = $data->orderBy('customers.owner_mobile', 'desc');
        }

        if (isset($request->owner_email_asc)) {
            $data = $data->orderBy('customers.owner_email', 'asc');
        }
        if (isset($request->owner_email_desc)) {
            $data = $data->orderBy('customers.owner_email', 'desc');
        }
        //echo print_r($request->all());
        $data = $data->orderBy('customers.customer_name');
        $result = $data->paginate(10);

        return view("admin/customer_list")->with(['cdata' => $result]);
    }

    function product_list(Request $request)
    {
        $product = DB::table('product');
        $product = $product->select('product.*', 'gst.gst_per', 'uom.uom_name', 'category.category_name as catname', 'material.material_name as matname');
        $product = $product->leftJoin('gst', 'gst.id', 'product.gst');
        $product = $product->leftJoin('uom', 'uom.id', 'product.uom');
        $product = $product->leftJoin('category', 'category.id', 'product.category');
        $product = $product->leftJoin('material', 'material.id', 'product.material');
        $product = $product->where('product.status', 'product');
        $product = $product;


        if (isset($request->product_name)) {
            $product = $product->where('product.product_name', 'like', '%' . $request->product_name . '%');
        }

        if (isset($request->item_code)) {
            $product = $product->where('product.item_code', 'like', '%' . $request->item_code . '%');
        }

        if (isset($request->category)) {
            $product = $product->where('product.category_name', 'like', '%' . $request->category . '%');
        }

        if (isset($request->material)) {
            $product = $product->where('product.material_name', 'like', '%' . $request->material . '%');

        }

        if (isset($request->usage_unit)) {
            $product = $product->where('uom.uom_name', 'like', '%' . $request->usage_unit . '%');

        }

        if (isset($request->price)) {
            $product = $product->where('product.price', 'like', '%' . $request->price . '%');

        }

        if (isset($request->gst)) {
            $product = $product->where('gst.gst_per', 'like', '%' . $request->gst . '%');

        }
        $product = $product->orderBy("id", "desc");
        $product = $product->paginate(10);


        // dd($product);
        return view("admin/product-list")->with(['data' => $product]);
    }

    function product_normal_list(Request $request)
    {
        $product = DB::table('product');
        $product = $product->select('product.*', 'gst.gst_per', 'uom.uom_name', 'category.category_name as catname', 'material.material_name as matname');
        $product = $product->leftJoin('gst', 'gst.id', 'product.gst');
        $product = $product->leftJoin('uom', 'uom.id', 'product.uom');
        $product = $product->leftJoin('category', 'category.id', 'product.category');
        $product = $product->leftJoin('material', 'material.id', 'product.material');


        if (isset($request->product_name)) {
            $product = $product->where('product.product_name', 'like', '%' . $request->product_name . '%');
        }

        if (isset($request->category)) {
            $product = $product->where('product.category_name', 'like', '%' . $request->category . '%');

        }

        if (isset($request->material)) {
            $product = $product->where('product.material_name', 'like', '%' . $request->material . '%');

        }

        if (isset($request->usage_unit)) {
            $product = $product->where('uom.uom_name', 'like', '%' . $request->usage_unit . '%');

        }

        if (isset($request->price)) {
            $product = $product->where('product.price', 'like', '%' . $request->price . '%');

        }

        if (isset($request->gst)) {
            $product = $product->where('gst.gst_per', 'like', '%' . $request->gst . '%');

        }

        $product = $product->where('product.status', 'product');
        $product = $product->paginate(10);


        // dd($product);
        return view("admin/product_normal_list")->with(['data' => $product]);
    }

    function product_save(Request $request)
    {
        //dd($request->all());
        date_default_timezone_set("Asia/Kolkata");

        if (empty($request->category)) {
            $category_name = "";
        } else {
            $categoryname = category::find($request->category);
            $category_name = $categoryname->category_name;
            //dd($category_name);
            if ($category_name == "AS PER DRAWING") {
                $request->validate([
                    'product_image' => 'required',
                    'price' => 'required',
                    'product_name' => 'required|unique:product|max:255',
                ]);
            }
        }

        $request->validate([
            'product_name' => 'required|unique:product|max:255',
            'price' => 'required',
        ]);

        if (empty($request->category)) {
            $category_name = "";
        } else {
            $categoryname = category::find($request->category);
            $category_name = $categoryname->category_name;
        }

        if (empty($request->material)) {
            $material_name = "";
        } else {
            $materialname = material::find($request->material);
            $material_name = $materialname->material_name;
        }


        $save = new product();
        $save->product_name = $request->product_name;
        $save->make = $request->make;
        $save->model = $request->model;
        $save->price = $request->price;
        $save->gst = $request->gst;
        $save->uom = $request->uom;
        $save->category = $request->category;
        $save->material = $request->material;

        $save->category_name = $category_name;
        $save->material_name = $material_name;

        $save->sales_start_date = date('Y-m-d', strtotime($request->sales_start_date));
        $save->sales_end_date = date('Y-m-d', strtotime($request->sales_end_date));
        $save->user_id = Session::get('user_id');
        $save->website_id = Session::get('website_id');
        $save->description = $request->description;
        $save->vendor = $request->vendor;
        $save->outer_diameter = $request->outer_diameter;
        $save->inner_diameter = $request->inner_diameter;
        $save->thikness = $request->thikness;
        $save->hsn = $request->hsn;
        $save->status = 'product';
        $save->created_time = date('Y-m-d h:i:s A');

        if ($request->hasFile('product_image')) {

            $image = $request->file('product_image');
            $name = time() . '.' . $image->getClientOriginalExtension();
            $destinationPath = '/product_image/';
            $image->move($destinationPath, $name);

            $save->product_image = $name;
        }


        $save->remark = $request->remark;
        if ($save->save()) {
            return redirect()->route('product-list')->with('message', 'product save successfully');
        } else {
            return back()->with('message', 'error in save');
        }
    }


    function product_normal_save(Request $request)
    {
        date_default_timezone_set("Asia/Kolkata");
        $request->validate([
            'product_name' => 'required|max:255|unique:product',
            'price' => 'required',
        ]);

        if (empty($request->category)) {
            $category_name = "";
        } else {
            $categoryname = category::find($request->category);
            $category_name = $categoryname->category_name;
        }

        if (empty($request->material)) {
            $material_name = "";
        } else {
            $materialname = material::find($request->material);
            $material_name = $materialname->material_name;
        }

        $save = new product();
        $save->product_name = $request->product_name;
        $save->make = $request->make;
        $save->model = $request->model;
        $save->price = $request->price;
        $save->gst = $request->gst;
        $save->uom = $request->uom;
        $save->category = $request->category;
        $save->material = $request->material;

        $save->category_name = $category_name;
        $save->material_name = $material_name;

        $save->sales_start_date = date('Y-m-d', strtotime($request->sales_start_date));
        $save->sales_end_date = date('Y-m-d', strtotime($request->sales_end_date));
        $save->user_id = Session::get('user_id');
        $save->website_id = Session::get('website_id');
        $save->description = $request->description;
        $save->vendor = $request->vendor;
        $save->outer_diameter = $request->outer_diameter;
        $save->inner_diameter = $request->inner_diameter;
        $save->thikness = $request->thikness;
        $save->hsn = $request->hsn;
        $save->status = 'product';
        $save->created_time = date('Y-m-d h:i:s A');
        if ($request->hasFile('product_image')) {

            $image = $request->file('product_image');
            $name = time() . '.' . $image->getClientOriginalExtension();
            $destinationPath = '/product_image/';
            $image->move($destinationPath, $name);

            $save->product_image = $name;
        }

        if ($save->save()) {
            return redirect()->route('client/product/normal/list')->with('message', 'product save successfully');
        } else {
            return back()->with('message', 'error in save');
        }
    }

    function raw_material_edit(Request $request)
    {
        $save = product::find($request->id);

        $category = ['' => 'select category'] + category::query()
                ->orderBy('category_name', 'asc')->get()->pluck('category_name', 'id')->toArray();
        $subcategory = ['' => 'select subcategory'] + subcategory::orderBy('subcategory_name', 'asc')->get()->pluck('subcategory_name', 'id')->toArray();

        $manufacturer = ['' => 'select manufacturer'] + manufacturer::orderBy('manufacturer_name', 'asc')->get()->pluck('manufacturer_name', 'id')->toArray();

        $importer = ['' => 'select Importer'] + importer::orderBy('manufacturer_name', 'asc')
                ->get()->pluck('manufacturer_name', 'id')->toArray();

        $packer = ['' => 'select Packer'] + packer::orderBy('manufacturer_name', 'asc')
                ->get()->pluck('manufacturer_name', 'id')->toArray();

        $brand = ['' => 'select brand'] + brand::orderBy('brand_name', 'asc')->get()->pluck('brand_name', 'id')->toArray();

        $material = ['' => 'select material'] + material::query()
                ->orderBy('material_name', 'asc')
                ->get()
                ->pluck('material_name', 'id')
                ->toArray();
        $attribute = attribute::orderBy('attribute_name', 'asc')->get();

        $product_attribute = product_attribute::select("product_attribute.*", "attribute.attribute_name", "variation.variation_name")
            ->leftJoin("attribute", "attribute.id", "product_attribute.attribute_id")
            ->leftJoin("variation", "variation.id", "product_attribute.option_id")
            ->where("product_attribute.group_id", $request->id)
            ->get();
        $gst = ['' => 'select gst'] + gst::query()
                ->orderBy('gst_per', 'asc')->get()->pluck('gst_per', 'id')->toArray();

        $uom = ['' => 'select uom'] + uom::query()
                ->orderBy('uom_name', 'asc')->get()->pluck('uom_name', 'id')->toArray();

        $vendor = ['' => 'select vendor'] + vendor::query()
                ->orderBy('vendor_name', 'asc')->get()->pluck('vendor_name', 'id')->toArray();

        $raw_material_group = ['' => 'select Raw Material Group'] + raw_material_group::orderBy('group_name', 'asc')
                ->get()->pluck('group_name', 'group_name')->toArray();

        return view("admin/raw_material_edit")->with(["raw_material_group" => $raw_material_group, "importer" => $importer, "packer" => $packer, "attribute" => $attribute, "brand" => $brand, "manufacturer" => $manufacturer, "subcategory" => $subcategory, 'category' => $category, 'uom' => $uom, 'gst' => $gst, 'data' => $save, 'vendor' => $vendor, 'material' => $material]);

    }

    function product_edit(Request $request)
    {
        $save = product::find($request->id);

        $product_multi_image = \App\product_multi_images::where("item_code", $save->item_code)->first();

        $category = ['' => 'select category'] + category::orderBy('category_name', 'asc')
                ->get()
                ->pluck('category_name', 'id')
                ->toArray();

        $subcategory = ['' => 'select subcategory'] + subcategory::orderBy('subcategory_name', 'asc')->get()->pluck('subcategory_name', 'id')->toArray();

        $manufacturer = ['' => 'select manufacturer'] + manufacturer::orderBy('manufacturer_name', 'asc')->get()->pluck('manufacturer_name', 'id')->toArray();

        $importer = ['' => 'select Importer'] + importer::orderBy('manufacturer_name', 'asc')
                ->get()->pluck('manufacturer_name', 'id')->toArray();

        $packer = ['' => 'select Packer'] + packer::orderBy('manufacturer_name', 'asc')
                ->get()->pluck('manufacturer_name', 'id')->toArray();

        $brand = ['' => 'select brand'] + brand::orderBy('brand_name', 'asc')->get()->pluck('brand_name', 'id')->toArray();

        $material = ['' => 'select material'] + material::query()
                ->orderBy('material_name', 'asc')
                ->get()
                ->pluck('material_name', 'id')
                ->toArray();
        $attribute = attribute::orderBy('attribute_name', 'asc')->get();

        $product_attribute = product_attribute::select("product_attribute.*", "attribute.attribute_name", "variation.variation_name")
            ->leftJoin("attribute", "attribute.id", "product_attribute.attribute_id")
            ->leftJoin("variation", "variation.id", "product_attribute.option_id")
            ->where("product_attribute.group_id", $request->id)
            ->get();
        $gst = ['' => 'select gst'] + gst::query()
                ->orderBy('gst_per', 'asc')->get()->pluck('gst_per', 'id')->toArray();

        $uom = ['' => 'select uom'] + uom::query()
                ->orderBy('uom_name', 'asc')->get()->pluck('uom_name', 'id')->toArray();

        $vendor = ['' => 'select vendor'] + vendor::query()
                ->orderBy('vendor_name', 'asc')->get()->pluck('vendor_name', 'id')->toArray();

        $cotton = ['' => 'select cotton'] + product::orderBy('product_name', 'asc')->where("raw_material_group", "=", "Cotton")
                ->get()->pluck('product_name', 'product_name')->toArray();
        //dd($cotton);
        $spendex = ['' => 'select spendex'] + product::orderBy('product_name', 'asc')->where("raw_material_group", "=", "Spendex")
                ->get()->pluck('product_name', 'product_name')->toArray();

        $elastics = ['' => 'select elstics'] + product::orderBy('product_name', 'asc')->where("raw_material_group", "=", "Elastics")
                ->get()->pluck('product_name', 'product_name')->toArray();

        $nylon = ['' => 'select nylon'] + product::orderBy('product_name', 'asc')->where("raw_material_group", "=", "Nylon")
                ->get()->pluck('product_name', 'product_name')->toArray();

        $polyester = ['' => 'select Polyester'] + product::orderBy('product_name', 'asc')->where("raw_material_group", "=", "Polyester")
                ->get()->pluck('product_name', 'product_name')->toArray();

        $P_P_Yarn = ['' => 'select P.P Yarn'] + product::orderBy('product_name', 'asc')->where("raw_material_group", "=", "P_P_Yarn")
                ->get()->pluck('product_name', 'product_name')->toArray();

        return view("admin/product_edit")->with(["product_multi_image" => $product_multi_image, "nylon" => $nylon, "cotton" => $cotton, "spendex" => $spendex, "elastics" => $elastics, "importer" => $importer, "packer" => $packer, "attribute" => $attribute, "brand" => $brand, "manufacturer" => $manufacturer, "subcategory" => $subcategory, 'category' => $category, 'uom' => $uom, 'gst' => $gst, 'data' => $save, 'vendor' => $vendor, 'material' => $material, 'polyester' => $polyester, 'P_P_Yarn' => $P_P_Yarn]);

    }

    function product_normal_edit(Request $request)
    {
        $save = product::find($request->id);

        $category = ['' => 'select category'] + category::query()
                ->orderBy('category_name', 'asc')->get()->pluck('category_name', 'id')->toArray();

        $material = ['' => 'select material'] + material::query()
                ->orderBy('material_name', 'asc')
                ->get()
                ->pluck('material_name', 'id')
                ->toArray();

        $gst = ['' => 'select gst'] + gst::query()
                ->orderBy('gst_per', 'asc')->get()->pluck('gst_per', 'id')->toArray();

        $uom = ['' => 'select uom'] + uom::query()
                ->orderBy('uom_name', 'asc')->get()->pluck('uom_name', 'id')->toArray();

        $vendor = ['' => 'select vendor'] + vendor::query()
                ->orderBy('vendor_name', 'asc')->get()->pluck('vendor_name', 'id')->toArray();

        return view("admin/product_normal_edit")->with(['category' => $category, 'uom' => $uom, 'gst' => $gst, 'data' => $save, 'vendor' => $vendor, 'material' => $material]);

    }

    function rawmaterial(Request $request)
    {
        //dd($request->id);
        $save = product::select('product.*', 'category.category_image', "gst.gst_per", "manufacturer.manufacturer_name", "importer.manufacturer_name as iname", "packer.manufacturer_name as pname", 'category.category_name', "uom.uom_name", "subcategory.subcategory_name", "brand.brand_name", "material.material_name")
            ->leftJoin('category', 'category.id', 'product.category')
            ->leftJoin('subcategory', 'subcategory.id', 'product.subcategory')
            ->leftJoin('material', 'material.id', 'product.material')
            ->leftJoin('manufacturer', 'manufacturer.id', 'product.manufacturer')
            ->leftJoin('importer', 'importer.id', 'product.importer')
            ->leftJoin('packer', 'packer.id', 'product.packer')
            ->leftJoin('brand', 'brand.id', 'product.brand')
            ->leftJoin('uom', 'uom.id', 'product.uom')
            ->leftJoin('gst', 'gst.id', 'product.gst')
            ->where('product.id', $request->id)
            ->first();
        //dd($save);

        return view("admin/rawmaterial_preview")->with(['data' => $save]);
    }

    function product_preview(Request $request)
    {
        //dd($request->id);
        $data = product::select('product.*', 'category.category_image', "gst.gst_per", "manufacturer.manufacturer_name", "importer.manufacturer_name as iname", "packer.manufacturer_name as pname", 'category.category_name', "uom.uom_name", "subcategory.subcategory_name", "brand.brand_name", "material.material_name")
            ->leftJoin('category', 'category.id', 'product.category')
            ->leftJoin('subcategory', 'subcategory.id', 'product.subcategory')
            ->leftJoin('material', 'material.id', 'product.material')
            ->leftJoin('manufacturer', 'manufacturer.id', 'product.manufacturer')
            ->leftJoin('importer', 'importer.id', 'product.importer')
            ->leftJoin('packer', 'packer.id', 'product.packer')
            ->leftJoin('brand', 'brand.id', 'product.brand')
            ->leftJoin('uom', 'uom.id', 'product.uom')
            ->leftJoin('gst', 'gst.id', 'product.gst')
            ->where('product.id', $request->id)
            ->first();
        //dd($save);
        $product_multi_image = \App\product_multi_images::where("item_code", $data->item_code)->first();

        return view("admin/product_preview")->with(['data' => $data, "product_multi_image" => $product_multi_image]);
    }

    function product_normal_preview(Request $request)
    {
        $save = product::select('product.*', 'category.category_image')
            ->leftJoin('category', 'category.id', 'product.category')
            ->where('product.id', $request->id)
            ->first();

        if (empty($save->gst)) {
            $gstper = "";
        } else {
            $gst = gst::where('id', $save->gst)->first();
            $gstper = $gst->gst_per;
        }

        if (empty($save->uom)) {
            $uompar = "";
        } else {
            $uom = uom::where('id', $save->uom)->first();
            $uompar = $uom->uom_name;
        }

        if (empty($save->vendor)) {
            $vendor_name = "";
        } else {
            $vendor = vendor::where('id', $save->vendor)->first();

            $vendor_name = $vendor->vendor_name;
        }


        return view("admin/product_normal_preview")->with(['uompar' => $uompar, 'gstper' => $gstper, 'data' => $save, 'vendor_name' => $vendor_name]);
    }


    function raw_material_delete(Request $request)
    {
        $save = product::find($request->id);
        if ($save->delete()) {
            return back()->with('message', 'Raw Material delete successfully');
        } else {
            return back()->with('message', 'error in save');
        }
    }

    function product_delete(Request $request)
    {
        $save = product::find($request->id);
        if ($save->delete()) {
            return back()->with('message', 'product delete successfully');
        } else {
            return back()->with('message', 'error in save');
        }
    }

    function service_delete(Request $request)
    {
        $save = product::find($request->id);
        if ($save->delete()) {
            return back()->with('message', 'service delete successfully');
        } else {
            return back()->with('message', 'error in save');
        }
    }

    function product_normal_delete(Request $request)
    {
        $save = product::find($request->id);
        if ($save->delete()) {
            return back()->with('message', 'product delete successfully');
        } else {
            return back()->with('message', 'error in save');
        }
    }

    function product_update(Request $request)
    {
        $save = product::find($request->id);
        $request->validate([
            'product_name' => 'required',
            'price' => 'required',
        ]);

        if (empty($request->category)) {
            $category_name = "";
        } else {
            $categoryname = category::find($request->category);
            $category_name = $categoryname->category_name;
        }

        if (empty($request->material)) {
            $material_name = "";
        } else {
            $materialname = material::find($request->material);
            $material_name = $materialname->material_name;
        }


        $save->product_name = $request->product_name;
        $save->make = $request->make;
        $save->model = $request->model;
        $save->price = $request->price;
        $save->gst = $request->gst;
        $save->uom = $request->uom;
        $save->category = $request->category;
        $save->material = $request->material;

        $save->category_name = $category_name;
        $save->material_name = $material_name;

        $save->sales_start_date = date('Y-m-d', strtotime($request->sales_start_date));
        $save->sales_end_date = date('Y-m-d', strtotime($request->sales_end_date));
        $save->user_id = Session::get('user_id');
        $save->website_id = Session::get('website_id');
        $save->description = $request->description;
        $save->vendor = $request->vendor;
        $save->outer_diameter = $request->outer_diameter;
        $save->inner_diameter = $request->inner_diameter;
        $save->thikness = $request->thikness;
        $save->hsn = $request->hsn;
        $save->status = 'product';
        $save->remark = $request->remark;
        if ($request->hasFile('product_image')) {
            $this->validate($request, [
                'product_image' => 'required|image|mimes:jpeg,png,jpg,bmp,gif,svg|max:2048',
            ]);
            $image = $request->file('product_image');
            $name = time() . '.' . $image->getClientOriginalExtension();
            $destinationPath = '/product_image/';
            $image->move($destinationPath, $name);

            $save->product_image = $name;
        }


        if ($save->save()) {
            return redirect()->route('product-list')->with('message', 'product update successfully');
        } else {
            return back()->with('message', 'error in save');
        }
    }


    function product_normal_update(Request $request)
    {
        $save = product::find($request->id);
        $request->validate([
            'product_name' => 'required',
            'price' => 'required',
        ]);

        if (empty($request->category)) {
            $category_name = "";
        } else {
            $categoryname = category::find($request->category);
            $category_name = $categoryname->category_name;
        }

        if (empty($request->material)) {
            $material_name = "";
        } else {
            $materialname = material::find($request->material);
            $material_name = $materialname->material_name;
        }


        $save->product_name = $request->product_name;
        $save->make = $request->make;
        $save->model = $request->model;
        $save->price = $request->price;
        $save->gst = $request->gst;
        $save->uom = $request->uom;
        $save->category = $request->category;
        $save->material = $request->material;

        $save->category_name = $category_name;
        $save->material_name = $material_name;

        $save->sales_start_date = date('Y-m-d', strtotime($request->sales_start_date));
        $save->sales_end_date = date('Y-m-d', strtotime($request->sales_end_date));
        $save->user_id = Session::get('user_id');
        $save->website_id = Session::get('website_id');
        $save->description = $request->description;
        $save->vendor = $request->vendor;
        $save->outer_diameter = $request->outer_diameter;
        $save->inner_diameter = $request->inner_diameter;
        $save->thikness = $request->thikness;
        $save->hsn = $request->hsn;
        $save->status = 'product';

        if ($request->hasFile('product_image')) {
            $this->validate($request, [
                'product_image' => 'required|image|mimes:jpeg,png,jpg,bmp,gif,svg|max:2048',
            ]);
            $image = $request->file('product_image');
            $name = time() . '.' . $image->getClientOriginalExtension();
            $destinationPath = '/product_image/';
            $image->move($destinationPath, $name);

            $save->product_image = $name;
        }


        if ($save->save()) {
            return redirect()->route('client/product/normal/list')->with('message', 'product update successfully');
        } else {
            return back()->with('message', 'error in save');
        }
    }


}
