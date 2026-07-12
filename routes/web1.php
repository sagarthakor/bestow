<?php

use App\city;
use App\country;
use App\industry;
use App\payment_terms;
use App\state;
use App\type;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\Checksession;

/*
|--------------------------------------------------------------------------
| Website Routes
|--------------------------------------------------------------------------
|
| Routes related to front-end website functionality, including product browsing,
| cart, checkout, and user authentication. Includes all 'front' routes.
|
*/

Route::get('/', "FrontController@index")->name("index");
Route::get('index', "FrontController@index")->name("index");
Route::get("product-details/{category?}/{subcategory?}/{product_name?}", "FrontController@product_details");
Route::get("product-details/{product_url?}/{product_id?}", "FrontController@product_details");
Route::get("products/{category?}/{subcategory?}", "FrontController@products");
Route::get("product-category/{category?}/{subcategory?}", "FrontController@filters");
Route::post("product_filters", "FrontController@product_filter");
Route::get("sub/{subcategory?}/{subid?}", "FrontController@subcategory");

Route::get("register", "FrontController@register");
Route::post("save/register", "FrontController@register_save")->name("post.register_save");
Route::get("customer_login", function () {
    return view("front.customer_login");
})->name("customer_login");
Route::post("checkemail", "FrontController@checkemail")->name("checkemail");
Route::post("check/email", "FrontController@check_email")->name("login.email.check");
Route::post("check/password", "FrontController@check_password")->name("login.password.check");
Route::post("newpassword", "FrontController@new_password")->name("login.new.password");
Route::post("verify/otp", "FrontController@verify_otp")->name("login.verify.otp");
Route::get("customer/forgotpassword/{id?}", "FrontController@forgot_password1");
Route::get("customer/logout", "FrontController@logout");
Route::get("user/logout", "FrontController@customer_logout");
Route::post("front/user/update", "UserController@user_update")->name("front.customer.update");
Route::get("user/order", "UserController@user_order");

Route::get("salesman/login", "UserController@salesman_login")->name("salesman/login");
Route::post("salesman/login/email", "UserController@sales_email")->name("sales.login.email.check");
Route::post("sales/password/check", "UserController@sales_check_password")->name("sales.check.password");
Route::post("salesman/verify/otp", "FrontController@salesman_verify_otp")->name("salesman.login.verify.otp");
Route::post("salesman/newpassword", "FrontController@salesman_new_password")->name("salesman.login.new.password");
Route::get("sales/forgotpassword/{email?}", "FrontController@sales_forgot_password");
Route::get("salesman/logout", "FrontController@salesman_logout");
Route::get("salesman/order/list", "SalesmanController@order_list")->name("salesman/order");
Route::post("front/salesman/update", "SalesmanController@front_salesman_update")->name("front.post.salesman_update");

Route::get("cart", function () {
    return view("front.cart.cart");
});
Route::get("inquiry", function () {
    return view("front.cart.inquiry");
});
Route::get("proceed_checkout", function () {
    return view("front.cart.proceed_checkout");
});
Route::get("checkout", function () {
    if (session()->has('customer_session')) {
        $customer = \App\customers::select("customers.*", "city.city_name")
            ->leftJoin("city", "city.id", "customers.billing_city")
            ->where("customers.id", session()->get('customer_session'))
            ->first();
        $industry = ['' => 'select industry'] + industry::where('website_id', Session::get('website_id'))
                ->orderBy('industry_name')->get()->pluck('industry_name', 'id')->toArray();
        $type = ['' => 'select type'] + type::where('website_id', Session::get('website_id'))
                ->orderBy('type_name')->get()->pluck('type_name', 'id')->toArray();
        $country = ['' => 'select country'] + country::orderBy('country_name', 'asc')
                ->get()->pluck('country_name', 'id')->toArray();
        $state = ['' => 'select state'] + state::orderBy('state_name', 'asc')
                ->get()->pluck('state_name', 'id')->toArray();
        $city = ['' => 'select city'] + city::orderBy('city_name', 'asc')
                ->get()->pluck('city_name', 'id')->toArray();
        $payment_terms = ['' => 'select'] + payment_terms::orderBy("id", "desc")
                ->get()->pluck("terms_name", "days")->toArray();
        $salesman = ['' => 'select salesman code'] + \App\salesman::orderBy("salesman_code", "desc")->get()
                ->pluck('salesman_code', 'salesman_code')->toArray();
        return view("front.cart.checkout")
            ->with(['salesman' => $salesman, 'data' => $customer, 'payment_terms' => $payment_terms, 'industry' => $industry, 'type' => $type, 'country' => $country, 'state' => $state, 'city' => $city]);
    } else {
        return view("front.customer_login");
    }
});
Route::get("inquiry_checkout", function () {
    if (session()->has('customer_session')) {
        $customer = \App\customers::select("customers.*", "city.city_name")
            ->leftJoin("city", "city.id", "customers.billing_city")
            ->where("customers.id", session()->get('customer_session'))
            ->first();
        $industry = ['' => 'select industry'] + industry::where('website_id', Session::get('website_id'))
                ->orderBy('industry_name')->get()->pluck('industry_name', 'id')->toArray();
        $type = ['' => 'select type'] + type::where('website_id', Session::get('website_id'))
                ->orderBy('type_name')->get()->pluck('type_name', 'id')->toArray();
        $country = ['' => 'select country'] + country::orderBy('country_name', 'asc')
                ->get()->pluck('country_name', 'id')->toArray();
        $state = ['' => 'select state'] + state::orderBy('state_name', 'asc')
                ->get()->pluck('state_name', 'id')->toArray();
        $city = ['' => 'select city'] + city::orderBy('city_name', 'asc')
                ->get()->pluck('city_name', 'id')->toArray();
        $payment_terms = ['' => 'select'] + payment_terms::orderBy("id", "desc")
                ->get()->pluck("terms_name", "days")->toArray();
        $salesman = ['' => 'select salesman code'] + \App\salesman::orderBy("salesman_code", "desc")->get()
                ->pluck('salesman_code', 'salesman_code')->toArray();
        return view("front.cart.inquiry_checkout")
            ->with(['salesman' => $salesman, 'data' => $customer, 'payment_terms' => $payment_terms, 'industry' => $industry, 'type' => $type, 'country' => $country, 'state' => $state, 'city' => $city]);
    } else {
        return view("front.customer_login");
    }
});
Route::post("placeorder", "FrontController@placeorder")->name("placeorder");
Route::post("inquiry_placeorder", "FrontController@inquiry_placeorder")->name("inquiry_placeorder");
Route::post("add_to_cart", "CartController@add_to_cart")->name("add_to_cart");
Route::get("quot_add_to_cart", "CartController@quot_add_to_cart");
Route::get("po_add_to_cart", "CartController@po_add_to_cart");
Route::post("cart_action", "CartController@cart_action")->name("cart_action");
Route::post("inquiry_action", "CartController@inquiry_action")->name("inquiry_action");
Route::post("inquiry_update", "CartController@update_inquiry")->name("inquiry_update");
Route::get("update-cart", "CartController@update");
Route::get("cart-update-cart", "CartController@cart_update_cart");
Route::get("inquiry-update-cart", "CartController@inquiry_update_cart");
Route::get("cart-remove-cart", "CartController@remove");
Route::post("remove-from-cart", "CartController@remove");
Route::get("inquiry-remove-cart", "CartController@inquiry_remove");
Route::get("getcart", "CartController@getcart");
Route::get("pogetcart", "CartController@pogetcart");
Route::get("cart_item_remove", "CartController@remove");
Route::get("quot_cart_item_remove", "CartController@quot_remove");
Route::get("po_cart_item_remove", "CartController@po_remove");
Route::get("get_cart_item", "CartController@get_cart_item");
Route::get("po_get_cart_item", "CartController@po_get_cart_item");
Route::get("checkout/po/{customer?}", "AjaxController@checkout_po");

Route::get("terms-condition", function () {
    return view("front.terms-condition");
});
Route::get("privacy-policy", function () {
    return view("front.privacy-policy");
});
Route::get("about_us", function () {
    return view("front.about_us");
});
Route::get("delivery-returns", function () {
    return view("front.delivery-returns");
});

Route::get("clients/get_state", "AjaxController@get_state");
Route::get("clients/get_city", "AjaxController@get_city");

Route::get('product-upload', "ProductController@productUpload");
Route::get('clear', function () {
    \Artisan::call('route:clear');
});

Route::any('ViewerJS/', function () {
    return view('ViewerJS.index');
});

Auth::routes();
Route::get('/home', 'HomeController@index')->name('home');

?>
