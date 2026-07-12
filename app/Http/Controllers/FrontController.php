<?php

namespace App\Http\Controllers;

use App\attribute;
use App\bom_sub_product;
use App\category;
use App\city;
use App\company;
use App\contact;
use App\country;
use App\customer_order;
use App\customer_order_item;
use App\customers;
use App\industry;
use App\item_group;
use App\payment_terms;
use App\product;
use App\product_attribute;
use App\product_image_variation;
use App\product_options;
use App\product_variation;
use App\quotation;
use App\salesman;
use App\state;
use App\subcategory;
use App\type;
use App\variation;
use App\website_user;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Validator;
use Session;
use DB;
class FrontController extends Controller
{
    //
    function get_product_detail(Request $request){

        $product=product::select("product.*","gst.gst_per")
        ->leftJoin("gst","gst.id","product.gst")
        ->where('item_code','=',$request->item_code)->where('value2','=',$request->size)->get();

        $color = product::select('value1','id')->where('item_code','=',$request->item_code)->where('value2','=',$request->size)->get();

        return response()->json(['status' => true, 'product' => $product, 'color' => $color]);
    }

    public function get_product_detail_image(Request $request)
    {
        $product = product::select('product_image','price','id')->where("item_code",'=',$request->item_code)->where("value1","=",$request->color)->where("value2",'=',$request->size)->first();

        return response()->json(['status' => true, 'image' => $product->product_image, 'price' => $product->price, 'product_id' => $product->id]);
    }

    function products(Request $request)
    {

        $product = new product();
        $product = $product->select(DB::raw('product.*,category.category_name,subcategory.subcategory_name'));
        $product = $product->leftJoin("category", "category.id", "product.category");
        $product = $product->leftJoin("subcategory", "subcategory.id", "product.subcategory");
        if (isset($request->subcategory)) {
                $product = $product->where("subcategory.subcategory_name", "=", $request->subcategory);
            }

        if (isset($request->category)) {

            $product = $product->where("category.category_name", "=",$request->category);
        }

        $product = $product->where("product.show_hide", '=', "show");
        $product=$product->groupBy("product.item_code");
        $product = $product->get();

        $subcategory = subcategory::orderBy("subcategory_name", "asc")->get();
        $category = category::orderBy("id", "asc")->get();

        return view("front.products",compact('product','category','subcategory'));
    }

    function product_filter(Request $request)
    {
        // dd($request->all());
        $product = new product();
        $product = $product->select("product.*", "category.category_name", "subcategory.subcategory_name");
        $product = $product->leftJoin("category", "category.id", "product.category");
        $product = $product->leftJoin("subcategory", "subcategory.id", "product.subcategory");
        if ($request->has("subcategory")) {
            foreach ($request->subcategory as $subcat) {
                $product = $product->orwhere("subcategory.subcategory_name", "like", '%' . $subcat . '%');
            }
            //$subcategoryFilterData = implode("','", $request->subcategory);
            //echo $subcategoryFilterData;
            //$product=$product->where("subcategory.subcategory_name","like",'%'.$subcategoryFilterData.'%');
        }
        $product = $product->where("product.show_hide", '=', "show");
        $product = $product->where("product.show_hide", '!=', "");
        $product = $product->get();
        //dd($product);
        $row = "";

        foreach ($product as $val) {
            if ($val->show_hide == "show") {
                $row .= '<div class="col-md-4">
                                             <li>
                                                <div class="product-grid">
                                                <div class="product-image">
                                                   <a href="' . url('product-details/' . $val->category_name . '/' . $val->subcategory_name.'/'.$val->item_code) . '">
                                                   <img class="pic-1" src="/product_image/'.$val->product_image.'" alt="Product image">
                                                   </a>
                                                </div>
                                                <h3 class="title">' . $val->product_name . '</h3>
                                                <div class="product-content">
                                                   <div class="price">
                                                      <span></span>
                                                   </div>
                                                   <a class="add-to-cart" href="' . url('product-details/' . $val->category_name . '/' . $val->subcategory_name.'/'.$val->item_code) . '">View Details</a>
                                                </div>
                                                </div>
                                             </li>
                                          </div>';
            }

        }
        return $row ?? "No Record Found";
    }

    function logout()
    {
        Session::forget('customer_session');

        return redirect()->route("front/index");

    }

    function filters(Request $request)
    {

        $subcategories = array();

        $product = new item_group();
        $product = $product->select("item_group.*", "category.id", "category.category_name", "subcategory.id", "subcategory.subcategory_name");
        $product = $product->leftJoin("category", "category.id", "item_group.category");
        $product = $product->leftJoin("subcategory", "subcategory.id", "item_group.subcategory");
        if (isset($request->category)) {
            $product = $product->where("category.category_name", $request->category);
            //dd($request->category);
            $category = category::where("category_name", $request->category)
                ->first();

            $subcategories = subcategory::select("subcategory.*", "category.category_name")
                ->where("subcategory.category", $category->id)
                ->leftJoin("category", "category.id", "subcategory.category")
                ->orderBy("subcategory.subcategory_name", "asc")
                ->get();

            //dd($subcategories);
        }
        if (isset($request->subcategory)) {
            $product = $product->where("subcategory.subcategory_name", $request->subcategory);
        }
        $product = $product->paginate(30);

        //dd($product);
        $subcategory = subcategory::orderBy("subcategory_name", "asc")->get();
        $category = category::orderBy("id", "asc")->get();


        return view("front.socks", compact('category', 'subcategories', 'product', 'subcategory'));

        // return view()
    }

    function register_save(Request $request)
    {
        //dd($request->all());
        date_default_timezone_set('Asia/Kolkata');
        $save = new customers();
        $request->validate([
            'customer_name' => 'required|max:255',
            'primary_email' => 'email:rfc,dns|unique:customers',
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
            'password' => 'required',
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
        $save->password = $request->password;

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
            return redirect()->route("customer_login")->with("register_message", "Your Registration has been done successfully");
            //return view("front.customer_login")->with("register_message","Your Registration has been done successfully");
        } else {
            return back()->with('message', 'error in save');
        }
    }

    function register(Request $request)
    {
        $industry = ['' => 'select industry'] + industry::orderBy('industry_name')->get()->pluck('industry_name', 'id')->toArray();
        $type = ['' => 'select type'] + type::orderBy('type_name')->get()->pluck('type_name', 'id')->toArray();

        $country = ['' => 'select country'] + country::orderBy('country_name', 'asc')
                ->get()->pluck('country_name', 'id')->toArray();

        $state = ['' => 'select state'] + state::orderBy('state_name', 'asc')
                ->get()->pluck('state_name', 'id')->toArray();

        $city = ['' => 'select city'] + city::orderBy('city_name', 'asc')
                ->get()->pluck('city_name', 'id')->toArray();

        $payment_terms = ['' => 'select'] + payment_terms::orderBy("id", "desc")
                ->get()->pluck("terms_name", "days")->toArray();

        return view("front.register")
        ->with(['payment_terms' => $payment_terms, 'industry' => $industry, 'type' => $type, 'country' => $country, 'state' => $state, 'city' => $city]);

    }

    function customer_logout(Request $request)
    {
        Session::forget("customer_session");
        Session::forget("customer_name");
        if(!Session::has('customer_session'))
        {
            return redirect()->route("index");
        }
    }

    function salesman_logout(Request $request)
    {
        Session::forget("salesman_session");
        Session::forget("salesman_name");
        Session::forget("salesman_code");
        if(!Session::has('salesman_session'))
        {
            return redirect()->route("index");
        }
    }

    function salesman_new_password(Request $request)
    {
        $find = salesman::where('salesman_email', $request->email)->first();
        $find->salesman_password = $request->new_password;
        if ($find->save()) {
            return redirect()->route("salesman/login")->with("message","New Password Update Successfully");
        }
    }

    function new_password(Request $request)
    {
        $find = customers::where('primary_email', $request->email)->first();
        $find->password = $request->new_password;
        if ($find->save()) {
            return view("front.customer_login")->with("message","New Password Update Successfully");
        }
    }

    function salesman_verify_otp(Request $request)
    {
        $find = salesman::where('salesman_email', $request->email)
            ->where("otp", $request->otp)
            ->first();
        if (empty($find)) {
            return back()->with('message', 'OTP does not match');
        } else {
            return view("front.salesman.new_password")
                ->with(['email' => $request->email]);
        }
    }

    function verify_otp(Request $request)
    {
        $find = customers::where('primary_email', $request->email)
            ->where("otp", $request->otp)
            ->first();
        if (empty($find)) {
            return back()->with('message', 'OTP does not match');
        } else {
            return view("front.new_password")
                ->with(['email' => $request->email]);
        }
    }

    function sales_forgot_password(Request $request)
    {

        $find = salesman::where('salesman_email', $request->email)->first();
        if (empty($find)) {
            return back()->with('message', 'Email id not found');
        } else {
            $digits = 4;
            $hashed_random_password = rand(pow(10, $digits - 1), pow(10, $digits) - 1);
            //$hashed_random_password = rand();
            $data = array('password' => $hashed_random_password);
            $to = $request->email;
            Mail::send(['text' => 'emails.otp'], $data, function ($message) use ($to) {
                $message->to($to, 'Socks')->subject
                ('OTP');
                $message->from('erp@nowtowow.co.in', 'OTP');
            });

            $find->otp = $hashed_random_password;


            if ($find->save()) {
                return view("front.salesman.otp_verify")
                    ->with(['otp' => $hashed_random_password, 'email' => $request->email]);
            }
        }
    }


     function forgot_password1(Request $request)
    {

        $find = customers::where('id', $request->id)->first();
        if (empty($find)) {
            return back()->with('message', 'Email id not found');
        } else {
            $digits = 4;
            $hashed_random_password = rand(pow(10, $digits - 1), pow(10, $digits) - 1);
            //$hashed_random_password = rand();
            $data = array('password' => $hashed_random_password);
            $to = $find->primary_email;
            Mail::send(['text' => 'emails.otp'], $data, function ($message) use ($to) {
                $message->to($to, 'Socks')->subject
                ('OTP');
                $message->from('erp@nowtowow.co.in', 'OTP');
            });

            $find->otp = $hashed_random_password;


            if ($find->save()) {
                return view("front.otp_verify")
                    ->with(['otp' => $hashed_random_password, 'email' => $find->primary_email]);
            }
        }
    }

    function forgot_password(Request $request)
    {
        $find = customers::where('primary_email', $request->email)->first();
        if (empty($find)) {
            return back()->with('message', 'Email id not found');
        } else {
            $digits = 4;
            $hashed_random_password = rand(pow(10, $digits - 1), pow(10, $digits) - 1);
            //$hashed_random_password = rand();
            $data = array('password' => $hashed_random_password);
            $to = $request->email;
            Mail::send(['text' => 'emails.otp'], $data, function ($message) use ($to) {
                $message->to($to, 'Socks')->subject
                ('OTP');
                $message->from('erp@nowtowow.co.in', 'OTP');
            });

            $find->otp = $hashed_random_password;


            if ($find->save()) {
                return view("front.otp_verify")
                    ->with(['otp' => $hashed_random_password, 'email' => $request->email]);
            }
        }
    }

    function check_password(Request $request)
    {
         //dd($request->all());
        $check = customers::where("primary_email", $request->email)
            ->where("password", $request->password)
            ->first();
        if (empty($check)) {
            return view("front.customer_password")->with(['email' => $request->email, "message" => 'invalid password',"customer_id"=>$request->custid]);
        } else {



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
                    ->get()->pluck("terms_name", "id")->toArray();

            Session::put("customer_session", $check->id);
            Session::put("customer_name", $check->customer_name);

            return redirect()->route("index");

            return view("front.cart.checkout")->with(['data' => $check, "industry" => $industry, "type" => $type, "country" => $country, "state" => $state, "city" => $city, "payment_terms" => $payment_terms]);
        }
    }

    function check_email(Request $request)
    {
        $check = customers::where("primary_email", $request->email)->first();
        if (empty($check)) {
            return back()->with("message", "Email id is invalid");
        } else {
            return view("front.customer_password")->with(['email' => $request->email,"customer_id"=>$check->id]);
        }

        //dd($request->all());
    }


     function inquiry_placeorder(Request $request)
    {
       // dd($request->all());
        $tot=count((array) session('inquiry'));
        if($tot==0)
        {
            return back()->with("message","add item in inquiry cart");
        }
        date_default_timezone_set('Asia/Kolkata');


        $save = customers::find(session()->get('customer_session'));
        //  dd($request->all());

        $request->validate([
            'customer_name' => 'required',
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
        $save->tax_preference = $request->tax_preference;
        $save->payment_terms = $request->payment_terms;
        $save->industry = $request->industry;
        $save->type = $request->type;
        $save->department = $request->department;
        $save->designation = $request->designation;
        //dd($save);
        $save->save();


        $customer_name = customers::find(session()->get('customer_session'));

        $billingcity = $billingstate = $billingcountry = $shppingcity = $shppingstate = $shippingcountry = "";
        if (isset($request->billing_city)) {
            $bcity = city::find($request->billing_city);
            $billingcity = $bcity->city_name;
        }
        if (isset($request->shipping_city)) {
            $scity = city::find($request->shipping_city);
            $shppingcity = $scity->city_name;
        }
        if (isset($request->billing_state)) {
            $bstate = state::find($request->billing_state);
            $billingstate = $bstate->state_name;
        }

        if (isset($request->shipping_state)) {
            $sstate = state::find($request->shipping_state);
            $shppingstate = $sstate->state_name;
        }

        if (isset($request->billing_country)) {
            $bcountry = country::find($request->billing_country);
            $billingcountry = $bcountry->country_name;
        }

        if (isset($request->shipping_country)) {
            $scountry = country::find($request->shipping_country);
            $shippingcountry = $scountry->country_name;
        }

        $customerorder = new customer_order();
        $qno = customer_order::max('order_no');

        if (empty($qno)) {
            $year = date("y");
            $nextyear = $year + 1;
            $n2 = "OR-";
            $n2 .= str_pad($qno + 1, 4, 0, STR_PAD_LEFT);
            $qno = 0;
            $n2 .= '/';
            $n2 .= $year;
            $n2 .= '-';
            $n2 .= $nextyear;
        } else {
            $year = date("y");
            $nextyear = $year + 1;
            $n2 = "OR-";
            $n2 .= str_pad($qno + 1, 4, 0, STR_PAD_LEFT);
            $n2 .= '/';
            $n2 .= $year;
            $n2 .= '-';
            $n2 .= $nextyear;
        }
        $customerorder->order_no = $qno + 1;
        $customerorder->order_number = $n2;
        $customerorder->customer = session()->get('customer_session');
        $customerorder->customer_name = $customer_name->customer_name;
        $customerorder->order_date = date('Y-m-d');
        $customerorder->datetime = date('Y-m-d h:i:s');

        $customerorder->billing_address = $request->billing_address;
        $customerorder->shipping_address = $request->shipping_address;

        $customerorder->billing_city = $billingcity;
        $customerorder->shipping_city = $shppingcity;
        $customerorder->billing_state = $billingstate;
        $customerorder->shipping_state = $shppingstate;
        $customerorder->billing_postalcode = $request->billing_postalcode;
        $customerorder->shipping_postalcode = $request->shipping_postalcode;
        $customerorder->billing_country = $billingcountry;
        $customerorder->shipping_country = $shippingcountry;
        $customerorder->payment_terms = $request->payment_terms;
        $customerorder->status = "Created";
        $customerorder->payment = "inquiry";
        $customerorder->salesman=$request->salesman;

        if (isset($request->payment_terms)) {
            $days = $request->payment_terms;
            $duedate = Date('Y-m-d', strtotime('+ ' . $days . ' days'));
            $customerorder->order_duedate = $duedate;
        }

        if ($customerorder->save()) {
            $tamt = $tcgst = $tsgst = $tgst = $tgrand = 0;
            foreach (session('inquiry') as $id => $details) {
                $item = product::where('product_name', $details['name'])->first();

                $subitem = product::select("product.*", "gst.gst_per")
                    ->leftJoin("gst","gst.id","product.gst")
                    ->where("product.product_name", $details['name'])
                    ->first();
                // dd($subitem);
                $customer_order_item = new customer_order_item();
                $customer_order_item->order_no = $n2;
                $customer_order_item->order_id = $customerorder->id;
                $customer_order_item->product = $subitem->id;
                $customer_order_item->custom_description=$details['custom_description'];
//                $customer_order_item->attribute1 = $details['attribute1'];
//                $customer_order_item->value1 = $details['value1'];
//                $customer_order_item->attribute2 = $details['attribute2'];
//                $customer_order_item->value2 = $details['value2'];
                $customer_order_item->qty = $details['quantity'];
                $customer_order_item->group_id = $item->id;
                $customer_order_item->price = $subitem->price;


                $company = company::select('company.*', 'state.state_name', 'state.state_code')
                    ->leftJoin("state", "state.id", "company.state")
                    ->first();

                $customer = customers::select('customers.*', 'state.state_name', 'state.state_code')
                    ->leftJoin("state", "state.id", "customers.billing_state")
                    ->where("customers.id", session()->get('customer_session'))
                    ->first();

                $igst = $cgstper = $sgstper = $cgstamt = $sgstamt = $igstamt = 0;

                $total_amount = $subitem->price * $details['quantity'];
                $tamt = $tamt + $total_amount;
                if ($customer->tax_preference == "true") {
                    if ($company->state_code == $customer->state_code) {
                        $igst = 0;
                        $cgstper = $subitem->gst_per / 2;
                        $cgstamt = $total_amount * $cgstper / 100;
                        $sgstper = $subitem->gst_per / 2;
                        $sgstamt = $total_amount * $sgstper / 100;
                        $tcgst = $tcgst + $cgstamt;
                        $tsgst = $tsgst + $sgstamt;
                        $tgst = 0;
                    } else {
                        $igst = $subitem->gst_per;
                        $igstamt = $total_amount * $igst / 100;
                        $cgstper = 0;
                        $sgstper = 0;
                        $cgstamt = 0;
                        $sgstamt = 0;
                        $tcgst = 0;
                        $tsgst = 0;
                        $tgst = $tgst + $igstamt;
                    }
                }

                $customer_order_item->total_amount = $total_amount;
                $customer_order_item->discount_per = 0;
                $customer_order_item->discount_amount = 0;
                $customer_order_item->cgst_per = $cgstper;
                $customer_order_item->cgst_amount = $cgstamt;
                $customer_order_item->sgst_per = $sgstper;
                $customer_order_item->sgst_amount = $sgstamt;
                $customer_order_item->gst_per = $igst;
                $customer_order_item->gst_amount = $igstamt;
                $customer_order_item->net_price = $total_amount + $cgstamt + $sgstamt + $igstamt;

                //dd($customer_order_item);

                $customer_order_item->save();
                $tgrand = $tgrand + $total_amount + $cgstamt + $sgstamt + $igstamt;
            }

            $corder = customer_order::find($customerorder->id);
            $corder->net_amount = $tamt;
            $corder->cgsttotal = $tcgst;
            $corder->sgsttotal = $tsgst;
            $corder->gst_amount = $tgst;
            $corder->grand_total = $tgrand;
            $corder->save();
        }
        Session::forget('inquiry');
        $order=customer_order::where("order_number",$n2)->first();
        $orderitem=customer_order_item::select("customer_order_item.*","product.product_name","product.product_image","category.category_name","subcategory.subcategory_name")
            ->leftJoin("product","product.id","customer_order_item.product")
            ->leftJoin("category","category.id","product.category")
            ->leftJoin("subcategory","subcategory.id","product.subcategory")
            ->where("customer_order_item.order_no",$n2)
            ->get();

        $customer_email=customers::where("id",$order->customer)->first();
        $c_email=company::first();
        $email=$customer_email->primary_email;
        $company_email=$c_email->email;
        Mail::send('emails.order',['order'=>$order,'order_item'=>$orderitem], function($message) use ($order,$orderitem,$company_email) {
            $message->to($company_email);
            $message->from($company_email, 'Order');
            $message->subject('Your Order Request');
        });

        Mail::send('emails.order',['order'=>$order,'order_item'=>$orderitem], function($message) use ($order,$orderitem,$email,$company_email) {
            $message->to($email);
            $message->from($company_email, 'Order');
            $message->subject('Your Order Request');

        });

        return view("front.order_success")
            ->with(["order_no" => $n2,"order"=>$order,'order_item'=>$orderitem]);
    }

    function placeorder(Request $request)
    {
       // dd($request->all());
        $tot=count((array) session('cart'));
        if($tot==0)
        {
            return back()->with("message","add item in cart");
        }
        date_default_timezone_set('Asia/Kolkata');


        $save = customers::find(session()->get('customer_session'));
        //  dd($request->all());

        $request->validate([
            'customer_name' => 'required',
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
        $save->tax_preference = $request->tax_preference;
        $save->payment_terms = $request->payment_terms;
        $save->industry = $request->industry;
        $save->type = $request->type;
        $save->department = $request->department;
        $save->designation = $request->designation;
        //dd($save);
        $save->save();


        $customer_name = customers::find(session()->get('customer_session'));

        $billingcity = $billingstate = $billingcountry = $shppingcity = $shppingstate = $shippingcountry = "";
        if (isset($request->billing_city)) {
            $bcity = city::find($request->billing_city);
            $billingcity = $bcity->city_name;
        }
        if (isset($request->shipping_city)) {
            $scity = city::find($request->shipping_city);
            $shppingcity = $scity->city_name;
        }
        if (isset($request->billing_state)) {
            $bstate = state::find($request->billing_state);
            $billingstate = $bstate->state_name;
        }

        if (isset($request->shipping_state)) {
            $sstate = state::find($request->shipping_state);
            $shppingstate = $sstate->state_name;
        }

        if (isset($request->billing_country)) {
            $bcountry = country::find($request->billing_country);
            $billingcountry = $bcountry->country_name;
        }

        if (isset($request->shipping_country)) {
            $scountry = country::find($request->shipping_country);
            $shippingcountry = $scountry->country_name;
        }

        $customerorder = new customer_order();
        $qno = customer_order::max('order_no');

        if (empty($qno)) {
            $year = date("y");
            $nextyear = $year + 1;
            $n2 = "OR-";
            $n2 .= str_pad($qno + 1, 4, 0, STR_PAD_LEFT);
            $qno = 0;
            $n2 .= '/';
            $n2 .= $year;
            $n2 .= '-';
            $n2 .= $nextyear;
        } else {
            $year = date("y");
            $nextyear = $year + 1;
            $n2 = "OR-";
            $n2 .= str_pad($qno + 1, 4, 0, STR_PAD_LEFT);
            $n2 .= '/';
            $n2 .= $year;
            $n2 .= '-';
            $n2 .= $nextyear;
        }
        $customerorder->order_no = $qno + 1;
        $customerorder->order_number = $n2;
        $customerorder->customer = session()->get('customer_session');
        $customerorder->customer_name = $customer_name->customer_name;
        $customerorder->order_date = date('Y-m-d');
        $customerorder->datetime = date('Y-m-d h:i:s');

        $customerorder->billing_address = $request->billing_address;
        $customerorder->shipping_address = $request->shipping_address;

        $customerorder->billing_city = $billingcity;
        $customerorder->shipping_city = $shppingcity;
        $customerorder->billing_state = $billingstate;
        $customerorder->shipping_state = $shppingstate;
        $customerorder->billing_postalcode = $request->billing_postalcode;
        $customerorder->shipping_postalcode = $request->shipping_postalcode;
        $customerorder->billing_country = $billingcountry;
        $customerorder->shipping_country = $shippingcountry;
        $customerorder->payment_terms = $request->payment_terms;
        $customerorder->status = "Created";
        $customerorder->salesman=$request->salesman;

        if (isset($request->payment_terms)) {
            $days = $request->payment_terms;
            $duedate = Date('Y-m-d', strtotime('+ ' . $days . ' days'));
            $customerorder->order_duedate = $duedate;
        }

        if ($customerorder->save()) {
            $tamt = $tcgst = $tsgst = $tgst = $tgrand = 0;
            foreach (session('cart') as $id => $details) {
                $item = product::where('product_name', $details['name'])->first();

                $subitem = product::select("product.*", "gst.gst_per")
                    ->leftJoin("gst","gst.id","product.gst")
                    ->where("product.product_name", $details['name'])
                    ->first();
                // dd($subitem);
                $customer_order_item = new customer_order_item();
                $customer_order_item->order_no = $n2;
                $customer_order_item->order_id = $customerorder->id;
                $customer_order_item->product = $subitem->id;
                $customer_order_item->custom_description=$details['custom_description'];
//                $customer_order_item->attribute1 = $details['attribute1'];
//                $customer_order_item->value1 = $details['value1'];
//                $customer_order_item->attribute2 = $details['attribute2'];
//                $customer_order_item->value2 = $details['value2'];
                $customer_order_item->qty = $details['quantity'];
                $customer_order_item->group_id = $item->id;
                $customer_order_item->price = $subitem->price;


                $company = company::select('company.*', 'state.state_name', 'state.state_code')
                    ->leftJoin("state", "state.id", "company.state")
                    ->first();

                $customer = customers::select('customers.*', 'state.state_name', 'state.state_code')
                    ->leftJoin("state", "state.id", "customers.billing_state")
                    ->where("customers.id", session()->get('customer_session'))
                    ->first();

                $igst = $cgstper = $sgstper = $cgstamt = $sgstamt = $igstamt = 0;

                $total_amount = $subitem->price * $details['quantity'];
                $tamt = $tamt + $total_amount;
                if ($customer->tax_preference == "true") {
                    if ($company->state_code == $customer->state_code) {
                        $igst = 0;
                        $cgstper = $subitem->gst_per / 2;
                        $cgstamt = $total_amount * $cgstper / 100;
                        $sgstper = $subitem->gst_per / 2;
                        $sgstamt = $total_amount * $sgstper / 100;
                        $tcgst = $tcgst + $cgstamt;
                        $tsgst = $tsgst + $sgstamt;
                        $tgst = 0;
                    } else {
                        $igst = $subitem->gst_per;
                        $igstamt = $total_amount * $igst / 100;
                        $cgstper = 0;
                        $sgstper = 0;
                        $cgstamt = 0;
                        $sgstamt = 0;
                        $tcgst = 0;
                        $tsgst = 0;
                        $tgst = $tgst + $igstamt;
                    }
                }

                $customer_order_item->total_amount = $total_amount;
                $customer_order_item->discount_per = 0;
                $customer_order_item->discount_amount = 0;
                $customer_order_item->cgst_per = $cgstper;
                $customer_order_item->cgst_amount = $cgstamt;
                $customer_order_item->sgst_per = $sgstper;
                $customer_order_item->sgst_amount = $sgstamt;
                $customer_order_item->gst_per = $igst;
                $customer_order_item->gst_amount = $igstamt;
                $customer_order_item->net_price = $total_amount + $cgstamt + $sgstamt + $igstamt;

                //dd($customer_order_item);

                $customer_order_item->save();
                $tgrand = $tgrand + $total_amount + $cgstamt + $sgstamt + $igstamt;
            }

            $corder = customer_order::find($customerorder->id);
            $corder->net_amount = $tamt;
            $corder->cgsttotal = $tcgst;
            $corder->sgsttotal = $tsgst;
            $corder->gst_amount = $tgst;
            $corder->grand_total = $tgrand;
            $corder->save();
        }
        Session::forget('cart');
        $order=customer_order::where("order_number",$n2)->first();
        $orderitem=customer_order_item::select("customer_order_item.*","product.product_name","product.product_image","category.category_name","subcategory.subcategory_name")
            ->leftJoin("product","product.id","customer_order_item.product")
            ->leftJoin("category","category.id","product.category")
            ->leftJoin("subcategory","subcategory.id","product.subcategory")
            ->where("customer_order_item.order_no",$n2)
            ->get();


        $customer_email=customers::where("id",$order->customer)->first();
        $c_email=company::first();
        $email=$customer_email->primary_email;
        $company_email=$c_email->email;
        Mail::send('emails.order',['order'=>$order,'order_item'=>$orderitem], function($message) use ($order,$orderitem,$email,$company_email) {
            $message->to($email);
            $message->from($company_email, 'Order');
            $message->subject('Your Order Request');

        });

        Mail::send('emails.order',['order'=>$order,'order_item'=>$orderitem], function($message) use ($order,$orderitem,$email,$company_email) {
            $message->to($email);
            $message->from($company_email, 'Order');
            $message->subject('Your Order Request');

        });

        return view("front.order_success")
            ->with(["order_no" => $n2,"order"=>$order,'order_item'=>$orderitem]);
    }

    function checkemail(Request $request)
    {
        $emailErr = "";
        $email = $request->primary_email;
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $emailErr = "email";
        }
        echo $emailErr;

//        $messages = array('email.regex' => 'Your email id is not valid.');
//
//        $validated = $request->validate([
//            'primary_email' => 'email:rfc,dns',
//        ]);
//
//        return $messages;

        $check = customers::where("primary_email", $request->primary_email)->first();
        if (empty($check)) {
            return false;
        } else {
            return true;
        }
        //dd($request->all());
    }

    function subcategory(Request $request)
    {
        $categories = category::orderBy("category_name", "asc")->get();

        $subcategories = subcategory::orderBy("subcategory_name", "asc")->get();

        $subcate = subcategory::where("id", $request->subid)->get();

        $product_attribute = item_group::select("item_group.subcategory", "item_group.id", "product_attribute.*")
            ->leftJoin("product_attribute", "product_attribute.group_id", "item_group.id")
            ->where("item_group.subcategory", $request->subid)
            ->get();
//        dd($group_id);
//        $product_attribute=product_attribute::where("group_id",$group_id->id)
//        ->get();
//        dd($product_attribute);
        $variation = variation::get();

        $product = item_group::where("subcategory", $request->subid)
            ->paginate(30);

        return view("front.subcategory_product", compact('subcate', 'categories', 'subcategories', 'product_attribute', 'variation', 'product'));
    }

    function product_details(Request $request)
    {
            $product =product::select("product.*","brand.brand_name","gst.gst_per","manufacturer.manufacturer_name as mname","importer.manufacturer_name as iname","packer.manufacturer_name as pname")
            ->leftJoin("brand","brand.id","product.brand")
            ->leftjoin("manufacturer","manufacturer.id","product.manufacturer")
            ->leftjoin("importer","importer.id","product.importer")
            ->leftjoin("packer","packer.id","product.packer")
            ->leftJoin("gst","gst.id","product.gst")
            ->where("product.item_code", $request->product_name)
            ->first();


            $product_multi_image=\App\product::select('product_image')->where("item_code",$product->item_code ?? "")->get();



            $product1 =product::where("item_code","=",$request->product_name)
            ->get();

            $product1 = collect($product1)->filter(function ($product){
               return $product;
            })->unique('value2');

            $subproduct=array();
            $attribute1=array();
            $value1=array();
            $attribute2=array();
            $value2=array();

        if(isset($product->status) && $product->status="bom")
        {
            $subproduct=bom_sub_product::select("product.product_image","product.product_name","product.attribute1 as a1","product.value1 as v1","product.attribute2 as a2","product.value2 as v2")
                ->where("bom_sub_product.bom_id",$product->id)
                ->leftJoin("product","product.id","bom_sub_product.product")
                ->get();

            $attribute1 = DB::table('bom_sub_product')
                ->leftJoin("product","product.id","bom_sub_product.product")
                ->select('product.attribute1', DB::raw('count(*) as total'))
                ->where("bom_sub_product.bom_id",$product->id)
                ->groupBy('product.attribute1')
                ->get();

            $value1 = DB::table('bom_sub_product')
                ->leftJoin("product","product.id","bom_sub_product.product")
                ->select('product.value1', DB::raw('count(*) as total'))
                ->where("bom_sub_product.bom_id",$product->id)
                ->groupBy('product.value1')
                ->get();

            $attribute2 = DB::table('bom_sub_product')
                ->leftJoin("product","product.id","bom_sub_product.product")
                ->select('product.attribute2', DB::raw('count(*) as total'))
                ->where("bom_sub_product.bom_id",$product->id)
                ->groupBy('product.attribute2')
                ->get();

            $value2 = DB::table('bom_sub_product')
                ->leftJoin("product","product.id","bom_sub_product.product")
                ->select('product.value2', DB::raw('count(*) as total'))
                ->where("bom_sub_product.bom_id",$product->id)
                ->groupBy('product.value2')
                ->get();
        }
        //dd($subproduct);

        $product_attribute=product_attribute::where("group_id",$product->id)
            ->get();

        return view("front.product_details")
            ->with(["product_multi_image"=>$product_multi_image,"product1"=>$product1,"attribute1"=>$attribute1,"value1"=>$value1,"attribute2"=>$attribute2,"value2"=>$value2,"subproduct"=>$subproduct,"product" => $product,"product_attribute"=>$product_attribute]);
        //->with(["product_image_variation"=>$product_image_variation,"data"=>$data,'colours1'=>$colours1,'colours2'=>$colours2,'colours3'=>$colours3,'colours4'=>$colours4,'colours5'=>$colours5,'colours6'=>$colours6,'colours7'=>$colours7,'colours8'=>$colours8,'colours9'=>$colours9,'colours10'=>$colours10,'size1'=>$size1,'size2'=>$size2,'size3'=>$size3,'size4'=>$size4,'size5'=>$size5,'size6'=>$size6,'size7'=>$size7,'size8'=>$size8,'size9'=>$size9,'size10'=>$size10]);
    }

    public function index()
    {
        # code...
        $categories = category::orderBy('id', "asc")->get();
        $subcategories = subcategory::orderBy('category', 'asc')->get();
        $group = item_group::orderBy("id", "desc")->get();
        $product = product::orderBy("id", "desc")->whereNull("group_id")->get();

        return view("front.index", compact("group", "product", "categories", "subcategories"));
    }

    public static function menus()
    {
        $categories = category::orderBy('id', "asc")->get();
        $subcategories = subcategory::orderBy('category', 'asc')->get();

        $menu = "";
        foreach ($categories as $clist) {

            $menu .= '<li class="level dropdown">
           <span class="opener plus"></span>
            <a href="' . url('sub/' . $clist->category_name . '/' . $clist->id) . '" class="page-scroll">' . $clist->category_name . '</a>
           <div class="megamenu mobile-sub-menu">
                <div class="megamenu-inner-top">
                    <ul class="sub-menu-level1">
                        <li class="level2 ">
                            <ul class="sub-menu-level2">
           ';

            foreach ($subcategories as $slist)
                if ($clist->id == $slist->category) {
                    $menu .= '<li class="level3"><a href="' . url('sub/' . $slist->subcategory_name . '/' . $slist->id) . '">
                        ' . $slist->subcategory_name . '</a></li>';
                }
            // else
            // {
            //     $menu .='<li class="level3"><a href="#">Not Found</a></li>';
            // }

            $menu .= '</ul></div></div></li>';
        }
        return $menu;

    }
}
