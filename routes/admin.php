<?php

use App\Http\Controllers\Admin\BannerController;
use App\Http\Controllers\Admin\BeltCostingController;
use App\Http\Controllers\Admin\BukkalCodeController;
use App\Http\Controllers\Admin\NiwarCodeController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\ShippingChargeController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\LoginController;
use App\variation;
use Illuminate\Support\Facades\Route;



/*
|--------------------------------------------------------------------------
| ✅ LOGIN & AUTHENTICATION ROUTES
|--------------------------------------------------------------------------
*/

Auth::routes();
/*
|--------------------------------------------------------------------------
| ✅ PROTECTED ROUTES (LOGIN REQUIRED)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {

    // v2 design-system preview only — renders master_v2/table_master_v2 with
    // sample content so the new layout can be reviewed with a real session.
    // Not linked from any menu; safe to remove once Phase 3 migration starts.
    Route::get('/v2-preview', function () {
        return view('admin.layout.preview_v2');
    })->name('admin.v2.preview');

    // v2 dashboard preview only — see AdminController::dashboardV2().
    // The live '/' 'admin.dashboard' route below is untouched.
    Route::get('/v2-dashboard', 'AdminController@dashboardV2')->name('admin.v2.dashboard');

    // v2 table/form redesign sample migrations only (Category + Customer).
    // Live routes further below (admin.category.*, admin.customers.*,
    // admin.customer.*) are completely untouched. Forms below submit to
    // those SAME existing save/update routes, so no backend logic changed.
     Route::prefix('v2/category')->name('admin.v2.category.')->group(function () {
        Route::get('/list', 'AdminController@category_list_v2')->name('list');
        Route::get('/add', 'AdminController@category_add_v2')->name('add');
        Route::get('/edit/{id}', 'AdminController@category_edit_v2')->name('edit');
    });
    Route::prefix('v2/customer')->name('admin.v2.customer.')->group(function () {
        Route::get('/list', 'AdminController@customer_list_v2')->name('list');
        Route::get('/add', 'AdminController@customer_add_v2')->name('add');
    });

    Route::prefix('bukkal')->name('admin.bukkal.')->group(function () {
        Route::get('/list', [BukkalCodeController::class, 'index'])->name('list');
        Route::get('/add', [BukkalCodeController::class, 'create'])->name('add');
        Route::post('/store', [BukkalCodeController::class, 'store'])->name('store');
        Route::get('/edit/{id}', [BukkalCodeController::class, 'edit'])->name('edit');
        Route::post('/update/{id}', [BukkalCodeController::class, 'update'])->name('update');
        Route::get('/delete/{id}', [BukkalCodeController::class, 'destroy'])->name('delete');
    });

    Route::get('get-bukkal-data/{id}', [BukkalCodeController::class, 'getBukkalData']);
    Route::get('get-niwar-data/{id}', [NiwarCodeController::class, 'getNiwarData']);

// Niwar Codes CRUD
    Route::prefix('niwar')->name('admin.niwar.')->group(function () {
        Route::get('/list', [NiwarCodeController::class, 'index'])->name('list');
        Route::get('/add', [NiwarCodeController::class, 'create'])->name('add');
        Route::post('/store', [NiwarCodeController::class, 'store'])->name('store');
        Route::get('/edit/{id}', [NiwarCodeController::class, 'edit'])->name('edit');
        Route::post('/update/{id}', [NiwarCodeController::class, 'update'])->name('update');
        Route::get('/delete/{id}', [NiwarCodeController::class, 'destroy'])->name('delete');
    });

// Belt Costing CRUD
    Route::prefix('belt-costing')->name('admin.belt.')->group(function () {
        Route::get('/list', [BeltCostingController::class, 'index'])->name('list');
        Route::get('/add', [BeltCostingController::class, 'create'])->name('add');
        Route::post('/store', [BeltCostingController::class, 'store'])->name('store');
        Route::get('/edit/{id}', [BeltCostingController::class, 'edit'])->name('edit');
        Route::post('/update/{id}', [BeltCostingController::class, 'update'])->name('update');
        Route::get('/delete/{id}', [BeltCostingController::class, 'destroy'])->name('delete');
    });

// Belt Production (separate module from the sock Production flow)
    Route::prefix('belt-production')->name('admin.belt_production.')->group(function () {
        Route::get('/list', 'BeltProductionController@belt_production_list')->name('list');
        Route::get('/add', 'BeltProductionController@belt_production_add')->name('add');
        Route::get('/check-material', 'BeltProductionController@belt_production_check_material')->name('check_material');
        Route::post('/store', 'BeltProductionController@belt_production_store')->name('store');
        Route::post('/complete', 'BeltProductionController@belt_production_complete')->name('complete');
        Route::get('/wastage-material', 'BeltProductionController@belt_production_wastage_material')->name('wastage_material');
    });

    // ✅ Admin Dashboard
    Route::get('dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('index', [AdminController::class, 'dashboard']);
    Route::get('/home', [AdminController::class, 'dashboard']);
    Route::get('/', [AdminController::class, 'dashboard']);

    // ✅ Example: Change Company Settings (will expand in next parts)
    Route::post('company_update', [AdminController::class, 'company_update'])->name('admin.company.update');

    // ✅ ✅ (Next modules like Users, Salesman, Product...) will be added in PART 2, 3, 4, 5

    Route::get('/users', 'UserController@index')->name('admin.user.list');
    Route::get('/user/add', 'UserController@create')->name('admin.user.add');
    Route::post('/user/store', 'UserController@store')->name('admin.user.store');
    Route::get('/user/edit/{id}', 'UserController@edit')->name('admin.user.edit');
    Route::post('/user/update', 'UserController@update')->name('admin.user.update');
    Route::get('/user/delete/{id}', 'UserController@destroy')->name('admin.user.destroy');


    Route::group([
        'prefix' => 'company',
        'as' => 'admin.company.',
    ], function () {
        Route::get('/list', "AdminController@company_list")->name('list');
        Route::get('/edit/{id?}', "AdminController@company_edit")->name('edit');
        Route::post('/update', "AdminController@company_update")->name('update');
    });

    /*
|--------------------------------------------------------------------------
| ✅ SALESMAN MANAGEMENT ROUTES
|--------------------------------------------------------------------------
*/
    Route::get('salesman/list', 'SalesmanController@index')->name('admin.salesman.list');
    Route::get('salesman/create', 'SalesmanController@create')->name('admin.salesman.create');
    Route::post('salesman/save', 'SalesmanController@salesman_save')->name('admin.salesman.save');
    Route::get('salesman/edit/{id}', 'SalesmanController@edit')->name('admin.salesman.edit');
    Route::post('salesman/update', 'SalesmanController@salesman_update')->name('admin.salesman.update');
    Route::get('salesman/delete/{id}', 'SalesmanController@delete')->name('admin.salesman.delete');


    /*
    |--------------------------------------------------------------------------
    | ✅ FRONT CUSTOMER / USER MANAGEMENT
    |--------------------------------------------------------------------------
    */
    Route::get('customers', 'AdminController@customer_list')->name('admin.customers.list');
    Route::get('customer/add', 'AdminController@customer_add')->name('admin.customer.add');
    Route::post('customer/save', 'AdminController@customer_save')->name('admin.customer.save');
    Route::get('customer/edit/{id}', 'AdminController@customer_edit')->name('admin.customer.edit');
    Route::post('customer/update', 'AdminController@customer_update')->name('admin.customer.update');
    Route::get('customer/delete/{id}', 'AdminController@customer_delete')->name('admin.customer.delete');
    Route::get('customer/preview/{id}', 'AdminController@customer_preview')->name('admin.customer.preview');


    Route::get('vendor/list',"AdminController@vendor_list")->name("admin.vendor.list");

    Route::get('vendor/add',"AdminController@vendor_add")->name("admin.vendor.add");
    Route::post("vendor/save",'AdminController@vendor_save')->name("admin.vendor.save");
    Route::get("vendor/edit/{id?}","AdminController@vendor_edit")->name("admin.vendor.edit");
    Route::post("vendor/update","AdminController@vendor_update")->name("admin.vendor.update");
    Route::get("vendor/delete/{id?}","AdminController@vendor_delete")->name("admin.vendor.delete");
    Route::get("customer/contact","AdminController@contact_list")->name("admin.customer.contact.list");
    Route::get("vendor/contact","AdminController@vendor_contact_list")->name("admin.vendor.contact.list");
    Route::get("vendor/preview/{id?}","AdminController@vendor_preview")->name('admin.vendor.preview');
    Route::get("contact/add","AdminController@contact_add")->name('admin.contact.add');
    Route::get("contact/view/{id?}","AdminController@contact_view")->name('admin.contact.view');
    Route::get("contact/edit/{id?}","AdminController@contact_edit")->name('admin.contact.edit');

    Route::post("contact/save",'AdminController@contact_save')->name('admin.contact.save');
    Route::post("vendor/contact/save",'AdminController@vendor_contact_save')->name('admin.vendor.contact.save');
    Route::get("vendor/contact/view/{id?}","AdminController@vendor_contact_view")->name('admin.vendor.contact.view');
    Route::get("vendor/contact/edit/{id?}","AdminController@vendor_contact_edit")->name('admin.vendor.contact.edit');
    Route::get("vendor/contact/delete/{id?}","AdminController@vendor_contact_delete")->name('admin.vendor.contact.delete');

    Route::post("contact/update",'AdminController@contact_update')->name('admin.contact.update');

    Route::post("vendor_contact/update",'AdminController@vendor_contact_update')->name('admin.vendor.contact.update');

    /*
    |--------------------------------------------------------------------------
    | ✅ FRONT LOGIN / LOGOUT ROUTES (Customer & Salesman)
    |--------------------------------------------------------------------------
    */
    Route::get('customer/logout', 'FrontController@customer_logout')->name('front.customer.logout');
    Route::get('salesman/logout', 'FrontController@salesman_logout')->name('front.salesman.logout');

    Route::post('front/user/update', 'UserController@user_update')->name('front.customer.update');
    Route::post('front/salesman/update', 'SalesmanController@front_salesman_update')->name('front.salesman.update');

    /*
    |--------------------------------------------------------------------------
    | ✅ PASSWORD RESET / OTP FOR LOGIN (CUSTOMER & SALESMAN)
    |--------------------------------------------------------------------------
    */
    Route::post('salesman/login/email', 'UserController@sales_email')->name('sales.login.email.check');
    Route::post('salesman/verify/otp', 'FrontController@salesman_verify_otp')->name('salesman.login.verify.otp');
    Route::post('salesman/newpassword', 'FrontController@salesman_new_password')->name('salesman.login.new.password');

    Route::post('login/verify/otp', 'FrontController@verify_otp')->name('login.verify.otp');
    Route::post('login/new/password', 'FrontController@new_password')->name('login.new.password');

    /*
|--------------------------------------------------------------------------
| ✅ CATEGORY & SUBCATEGORY MANAGEMENT
|--------------------------------------------------------------------------
*/
    Route::get('category/list', 'AdminController@category_list')->name('admin.category.list');
    Route::get('category/add', function () {
        return view('admin.category_add');
    })->name('admin.category.add');
    Route::post("category/save",['as'=>'post.category_save','uses'=>'AdminController@category_save']);
    Route::get('category/edit/{id}', 'AdminController@category_edit')->name('admin.category.edit');
    Route::post('category/update', 'AdminController@category_update')->name('admin.category.update');
    Route::get('category/delete/{id}', 'AdminController@category_delete')->name('admin.category.delete');

    /* Subcategory Routes */
    Route::get('subcategory/list', 'AdminController@subcategory_list')->name('admin.subcategory.list');
    Route::get('subcategory/add', 'AdminController@subcategory_add')->name('admin.subcategory.add');
    Route::post('subcategory/save', 'AdminController@subcategory_save')->name('admin.subcategory.save');
    Route::get('subcategory/edit/{id}', 'AdminController@subcategory_edit')->name('admin.subcategory.edit');
    Route::post('subcategory/update', 'AdminController@subcategory_update')->name('admin.subcategory.update');
    Route::get('subcategory/delete/{id}', 'AdminController@subcategory_delete')->name('admin.subcategory.delete');


    /*
    |--------------------------------------------------------------------------
    | ✅ PRODUCT LIST / ADD / EDIT / DELETE
    |--------------------------------------------------------------------------
    */
    Route::get('product/list', 'AdminController@product_list')->name('admin.product.list');
    //Route::get('product/add', 'AdminController@product_normal_add')->name('admin.product.add');
    Route::post('product/save', 'AdminController@product_normal_save')->name('admin.product.save');
    Route::get('product/edit/{id}', 'AdminController@product_edit')->name('admin.product.edit');
    //Route::post('product/update', 'AdminController@product_normal_update')->name('admin.product.update');
    Route::post('product_update',['as'=>'post.product_update','uses'=>'ProductController@product_update']);
    Route::get('product/delete/{id}', 'AdminController@product_delete')->name('admin.product.delete');
    Route::get("product/add/","ProductController@product_add")->name('admin.product.add');
    Route::post('product_save',['as'=>'post.product_save','uses'=>'ProductController@product_save']);
    Route::get("getsubcategory","AjaxController@getsubcategory");
    Route::get("get_variation","AjaxController@get_variation");

    /* Product Image Delete */
    Route::get('product/image/delete/{item_code}/{image}', 'ProductController@product_img_delete')->name('admin.product.image.delete');


    Route::get("material/list","AdminController@material_list")->name("admin.material.list");

    Route::get("material/add",function(){
        return view("admin/material_add");
    })->name('admin.material.add');

    Route::post("material/save",['as'=>'post.material_save','uses'=>'AdminController@material_save']);

    Route::get("material/edit/{id?}","AdminController@material_edit")->name('admin.material_edit');

    Route::post("material/update",['as'=>'post.material_update','uses'=>'AdminController@material_update']);

    Route::get("material/delete/{id?}","AdminController@material_delete")->name('admin.material.delete');


    /*
    |--------------------------------------------------------------------------
    | ✅ BRAND MANAGEMENT
    |--------------------------------------------------------------------------
    */
    Route::get('brand/list', 'ProductController@brand_list')->name('admin.brand.list');
    Route::get('brand/add', function () {
        return view('admin.items.brand_add');
    })->name('admin.brand.add');
    Route::post('brand/save', 'ProductController@brand_save')->name('admin.brand.save');
    Route::post('brand/update', 'ProductController@brand_update')->name('admin.brand.update');
    Route::get('brand/edit/{id}', 'ProductController@brand_edit')->name('admin.brand.edit');
    Route::get('brand/delete/{id}', 'ProductController@brand_delete')->name('admin.brand.delete');


    /*
    |--------------------------------------------------------------------------
    | ✅ COLOR MANAGEMENT
    |--------------------------------------------------------------------------
    */
    Route::get('color/list', 'ProductController@colour_list')->name('admin.color.list');
    Route::get('color/add', function () {
        return view('admin.items.colour_add');
    })->name('admin.color.add');
    Route::post('color/save', 'ProductController@colour_save')->name('admin.color.save');
    Route::post('color/update', 'ProductController@colour_update')->name('admin.color.update');


    /*
    |--------------------------------------------------------------------------
    | ✅ ATTRIBUTE & VARIATION
    |--------------------------------------------------------------------------
    */
    Route::get('attribute/list', 'AttributeController@list')->name('admin.attribute.list');
    Route::post('attribute/save', 'AttributeController@attribute_save')->name('admin.attribute.save');
    Route::post('attribute/update', 'AttributeController@attribute_update')->name('admin.attribute.update');
    Route::get('attribute/edit/{id}', 'AttributeController@edit')->name('admin.attribute.edit');
    Route::get('attribute/delete/{id}', 'AttributeController@attribute_delete')->name('admin.attribute.delete');
    Route::get('attribute/add', function () {
        return view('admin.attribute.create');
    })->name('admin.attribute.add');

    /* Variations */
    Route::get('variation/list', "VariationController@list")->name("admin.variation.list");
    Route::get('variation/add', 'VariationController@add')->name('admin.variation.add');
    Route::post('variation/save', 'VariationController@insert')->name('admin.variation.save');
    Route::post('variation/update', 'VariationController@update')->name('admin.variation.update');
    Route::get('variation/delete/{id}', 'VariationController@delete')->name('admin.variation.delete');
    Route::get('variation/edit/{id}', function ($id) {

        $attribute=[''=>'select attribute']+\App\attribute::orderBy('attribute_name', 'asc')->get()
                ->pluck("attribute_name","id")->toArray();
        $data=variation::find($id);
        return view("admin/variation/edit",compact("data","attribute"));
    })->name('admin.variation.edit');
    Route::get("raw-material/list","ProductController@rawmaterial_list")
        ->name("admin.raw.material.list");
    Route::get("raw_material/add/","ProductController@raw_add")->name("admin.raw.material.add");
    Route::get("raw_material/edit/{id?}","AdminController@raw_material_edit")->name("admin.raw.material.edit");
    Route::get("raw_material_delete/{id?}","AdminController@raw_material_delete")->name("admin.raw.material.delete");
    Route::post('raw_material_save',['as'=>'post.raw_material_save','uses'=>'ProductController@raw_material_save']);
    Route::post('raw_material_update',['as'=>'post.raw_material_update','uses'=>'ProductController@raw_material_update']);

    /*
    |--------------------------------------------------------------------------
    | ✅ GST & UOM MANAGEMENT
    |--------------------------------------------------------------------------
    */
    Route::group([
        'prefix' => 'gst',
        'as' => 'admin.gst.',
    ], function () {

        Route::get('/list', 'AdminController@gst_list')->name('list');

        Route::get('/add', function () {
            return view('admin.gst_add');
        })->name('add');
        Route::post('/save', 'AdminController@gst_save')->name('save');
        Route::get("/edit/{id?}","AdminController@gst_edit")->name('edit');
        Route::post('/update', 'AdminController@gst_update')->name('update');
        Route::get("/delete/{id?}","AdminController@gst_delete")->name('delete');

    });

    Route::group([
        'prefix' => '/payment/terms',
        'as' => 'admin.payment_terms.',
    ], function () {

        Route::get('/list', "AdminController@payment_terms")->name('list');
        Route::get('/add', function () {
            return view("admin.terms.payment.create");
        })->name('add');
        Route::post('/save', "AdminController@payment_terms_save")->name('save');
        Route::get('/edit/{id?}', "AdminController@payment_terms_edit")->name('edit');
        Route::post('/update', "AdminController@payment_terms_update")->name('update');
        Route::get('/delete/{id?}', "AdminController@payment_terms_delete")->name('delete');

    });

    Route::group([
        'prefix' => 'industry',
        'as' => 'admin.industry.',
    ], function () {

        Route::get('/list', "AdminController@industry_list")->name('list');
        Route::get('/add', "AdminController@industry_add")->name('add');
        Route::post('/save', "AdminController@industry_save")->name('save');
        Route::get('/edit/{id?}', "AdminController@industry_edit")->name('edit');
        Route::post('/update', "AdminController@industry_update")->name('update');
        Route::get("/delete/{id?}","AdminController@industry_delete")->name('delete');

    });


    Route::group([
        'prefix' => 'type',
        'as' => 'admin.type.',
    ], function () {

        Route::get('/list', "AdminController@type_list")->name('list');
        Route::get('/add', "AdminController@type_add")->name('add');
        Route::post('/save', "AdminController@type_save")->name('save');
        Route::get('/edit/{id?}', "AdminController@type_edit")->name('edit');
        Route::post('/update', "AdminController@type_update")->name('update');
        Route::get('/delete/{id?}', "AdminController@type_delete")->name('delete');

    });



    Route::group([
        'prefix' => 'country',
        'as' => 'admin.country.',
    ], function () {

        Route::get('/list', "AdminController@country_list")->name('list');
        Route::get('/add', function () {
            return view("admin/country_add");
        })->name('add');

        Route::post('/save', "AdminController@country_save")->name('save');
        Route::get('/edit/{id?}', "AdminController@country_edit")->name('edit');
        Route::get('/delete/{id?}', "AdminController@country_delete")->name('delete');
        Route::post('/update', "AdminController@country_update")->name('update');

    });


    Route::group([
        'prefix' => 'state',
        'as' => 'admin.state.',
        'middleware' => ['auth']
    ], function () {

        Route::get('/list', "AdminController@state_list")->name('list');
        Route::get('/add', "AdminController@state_add")->name('add');
        Route::post('/save', "AdminController@state_save")->name('save');

        Route::get('/edit/{id?}', "AdminController@state_edit")->name('edit');
        Route::get('/delete/{id?}', "AdminController@state_delete")->name('delete');
        Route::post('/update', "AdminController@state_update")->name('update');

    });


    Route::group([
        'prefix' => 'city',
        'as' => 'admin.city.',
    ], function () {

        Route::get('/list', "AdminController@city_list")->name('list');
        Route::get('/add', "AdminController@city_add")->name('add');
        Route::post('/save', "AdminController@city_save")->name('save');

        Route::get('/edit/{id?}', "AdminController@city_edit")->name('edit');
        Route::get('/delete/{id?}', "AdminController@city_delete")->name('delete');
        Route::post('/update', "AdminController@city_update")->name('update');

    });

    Route::group([
        'prefix' => 'term',
        'as' => 'admin.term.',
    ], function () {
       Route::get('/list', "AdminController@terms_list")->name('list');
        Route::get("/add","AdminController@terms_add")->name('add');
        Route::post("/save","AdminController@terms_save")->name('save');
        Route::get("/edit/{id?}","AdminController@terms_edit")->name('edit');
        Route::post("/update",'AdminController@terms_update')->name('update');
        Route::get("/delete/{id?}","AdminController@terms_delete")->name('delete');
    });

    Route::get('uom/list', 'AdminController@uom_list')->name('admin.uom.list');
    Route::get('uom/add', function () {
        return view('admin.uom_add');
    })->name('admin.uom.add');
    Route::post('uom/save', 'AdminController@uom_save')->name('admin.uom.save');
    Route::post('uom/update', 'AdminController@uom_update')->name('admin.uom.update');
    Route::get("uom/edit/{id?}","AdminController@uom_edit")->name('admin.uom.edit');

    Route::get("uom/delete/{id?}","AdminController@uom_delete")->name('admin.uom.delete');

    Route::get('/roles', 'RoleController@index')->name('admin.roles.list');
    Route::get('/roles/show', 'RoleController@index')->name('admin.roles.show');
    Route::get('/role/add', 'RoleController@create')->name('admin.role.add');
    Route::post('/role/store', 'RoleController@store')->name('admin.roles.store');
    Route::get('/role/edit/{id}', 'RoleController@edit')->name('admin.roles.edit');
    Route::post('/role/update', 'RoleController@update')->name('admin.roles.update');
    Route::get('/role/delete/{id}', 'RoleController@destroy')->name('admin.roles.destroy');

    /*
|--------------------------------------------------------------------------
| ✅ QUOTATIONS (Normal + Proforma + Revision)
|--------------------------------------------------------------------------
*/

    Route::get("get_customer","AdminController@get_customer");
    Route::get("get_contact","AdminController@get_contact");
    Route::get("invoice/get_product","InvoiceController@get_product")->name('admin.invoice.get_product');
    Route::get("deliverychallan/duplicate/{id?}","DeliveryChallanController@deliverychallan_duplicate");
    Route::get("bom/get_product","BomController@get_product");
    Route::get("get_product","AdminController@get_product");
    Route::get("purchase/get_product","PurchaseController@get_product");
    Route::get("get_terms","AjaxController@get_terms");
    Route::get("get_vendor","AdminController@get_vendor");
    Route::get("get_vendor_contact","AdminController@get_vendor_contact");
    Route::get("product/search-options","AdminController@product_search_options")->name('admin.product.search_options');
    Route::get("vendor/search-options","AdminController@vendor_search_options")->name('admin.vendor.search_options');


    Route::get('quotation/list/{status?}', 'AdminController@quotation_list')->name('admin.quotation.list');
    Route::get('quotation/add', 'AdminController@quotation_add')->name('admin.quotation.add');
    Route::get('quotation/add/{customer_id}', 'AdminController@quotation_add1');
    Route::post('quotation/save', 'AdminController@quot_save')->name('admin.quotation.save');
    Route::get('quotation/edit/{id}', 'AdminController@quotation_edit')->name('admin.quotation.edit');
    Route::post('quotation/update', 'AdminController@quot_update')->name('admin.quotation.update');
    // post.quot_update: the same handler under the name expected by the leftover
    // Form::model(...) wrapper in quotation_preview / invoice_view / deliverychallan_view.
    // Those forms have no real submit button (their "Save" buttons trigger separate
    // JS/AJAX calls) but the route name still needs to resolve for the page to render.
    Route::post('quotation/update-legacy', 'AdminController@quot_update')->name('post.quot_update');
    Route::get('quotation/delete/{id}', 'AdminController@quotation_delete')->name('admin.quotation.delete');

    Route::get('quotation/duplicate/{id}', 'AdminController@quotation_duplicate')->name('admin.quotation.duplicate');
    Route::post('quotation/duplicate/save', 'AdminController@quot_duplicate')->name('admin.quotation.duplicate.save');

    Route::get('quotation/view/{id}', 'AdminController@quot_view')->name('admin.quotation.view');
    Route::get('quotation/print/{quot_no}/{is_internal?}', 'AdminController@quot_print')->name('admin.quotation.print');
    Route::get('quotation/proforma/{id}', 'QuotationController@quot_proforma')->name('admin.quotation.proforma');
    Route::get('quotation/quot_preview/{quot_no?}',"AdminController@quot_preview")->name('admin.quotation.preview');
    /* Revision Quotations */
    Route::get('quotation/revise/{id}', 'QuotationController@quot_revise')->name('admin.quotation.revise');
    Route::post('quotation/revise/save', 'QuotationController@revise_quot_save')->name('admin.quotation.revise.save');

    Route::get('/orders', [OrderController::class, 'index'])->name('admin.orders.index');
    Route::post('/orders/{id}/update-status', [OrderController::class, 'updateStatus'])->name('admin.orders.updateStatus');
    Route::get('/order/view/{id?}', [OrderController::class, 'view'])->name('admin.orders.view');
    /*
    |--------------------------------------------------------------------------
    | ✅ SALES ORDER (SO)
    |--------------------------------------------------------------------------
    */
    Route::prefix('sales-order')->name('admin.sales.')->group(function () {
        Route::get('/', 'SalesController@salesorder_list')->name('list');
        Route::get('/create/{customer?}', 'SalesController@create')->name('create');
        Route::post('/store', 'SalesController@store')->name('save');
        Route::get('/edit/{id}', 'SalesController@so_edit')->name('edit');
        Route::post('/update', 'SalesController@salesorder_update1')->name('update');
        Route::get('/delete/{id}', 'SalesController@so_delete')->name('delete');
        Route::get('/view/{id}', 'SalesController@sales_view')->name('view');
        Route::get('/print/{id}', 'SalesController@so_print')->name('print');
        Route::get("delivery-challan/add/{id?}","DeliveryChallanController@challan_add")->name('delivery.add');
        Route::get("delivery-challan/add/{id?}/{customer?}","DeliveryChallanController@challan_add")->name('delivery.add');
        Route::get("duplicate/{id?}","SalesController@so_duplicate");
        Route::get("sales_order_1/create/{id?}","SalesController@create")->name('quot.add');
        Route::post("so/deliverychallan","DeliveryChallanController@so_deliverychallan")
            ->name("delivery_challan");
    });

    Route::prefix('banner')->name('admin.banners.')->group(function () {
        Route::get('/', [BannerController::class, 'index'])->name('index');

        Route::get('create', [BannerController::class, 'create'])->name('create');

        Route::post('store', [BannerController::class, 'store'])->name('store');

        Route::get('edit/{id}', [BannerController::class, 'edit'])->name('edit');

        Route::put('update/{id}', [BannerController::class, 'update'])->name('update');
        // OR if you want PUT/PATCH:
        // Route::put('update/{id}', [BannerController::class, 'update'])->name('banners.update');

        Route::get('delete/{id}', [BannerController::class, 'destroy'])->name('destroy');
    });

    /*
    |--------------------------------------------------------------------------
    | ✅ DELIVERY CHALLAN
    |--------------------------------------------------------------------------
    */
    //Route::get('challan/create/{id?}', 'DeliveryChallanController@challan_add')->name('admin.challan.add');
    Route::post('challan/save', 'ChallanController@challan_save')->name('admin.challan.save');
    Route::get('challan/list', 'DeliveryChallanController@deliverychallan_list')->name('admin.challan.list');
    Route::get('challan/edit/{id}', 'DeliveryChallanController@deliverychallan_edit')->name('admin.challan.edit');
    Route::post('challan/update', 'ChallanController@challan_update')->name('admin.challan.update');
    Route::get('challan/print/{id}', 'DeliveryChallanController@deliverychallan_print')->name('admin.challan.print');
    Route::get('challan/view/{id}', 'DeliveryChallanController@deliverychallan_view')->name('admin.challan.view');
    Route::get('challan/delete/{id}', 'DeliveryChallanController@delivery_delete')->name('admin.challan.delete');
    Route::get('challan/invoice/create/{id?}', 'DeliveryChallanController@invoice_add')->name('admin.challan.invoice.add');
    Route::get("challan/preview/{id?}","DeliveryChallanController@deliverychallan_preview")->name('admin.challan.preview');
    Route::post("so/deliverychallan/update","DeliveryChallanController@so_deliverychallan_update")
        ->name("post.so_deliverychallan_update");
    Route::get("sales/deliverychallan/add/{id?}","DeliveryChallanController@challan_add")->name('admin.deliverychallan.add');
    Route::get("sales/deliverychallan/add/{id?}/{customer?}","DeliveryChallanController@challan_add")->name('admin.deliverychallan.add');

    /*
    |--------------------------------------------------------------------------
    | ✅ INVOICES (Billing)
    |--------------------------------------------------------------------------
    */
    Route::get('invoice/list', 'InvoiceController@invoice_list')->name('admin.invoice.list');
    Route::get('invoice/create/{id?}', 'InvoiceController@invoice_add')->name('admin.invoice.add');
    Route::post('invoice/save', 'InvoiceController@so_invoice')->name('admin.invoice.save');
    Route::get('invoice/edit/{id}', 'InvoiceController@invoice_edit')->name('admin.invoice.edit');
    Route::post('invoice/update', 'InvoiceController@so_invoice_update')->name('admin.invoice.update');
    Route::get('invoice/view/{id}', 'InvoiceController@invoice_view')->name('admin.invoice.view');
    Route::get('invoice/print/{id}', 'InvoiceController@invoice_print')->name('admin.invoice.print');
    Route::get('invoice/preview/{id}', 'InvoiceController@invoice_preview')->name('admin.invoice.preview');
    Route::get('invoice/delete/{id}', 'InvoiceController@invoice_delete')->name('admin.invoice.delete');
    Route::get("sales/invoice/add/{id?}","InvoiceController@invoice_add")->name('sales.invoice.add');
    /*
    |--------------------------------------------------------------------------
    | ✅ PURCHASE ORDER (PO)
    |--------------------------------------------------------------------------
    */
    Route::get('purchase/list', 'PurchaseController@po_list')->name('admin.purchase.list');
    Route::get('purchase/add', 'PurchaseController@po_add')->name('admin.purchase.add');
    Route::post('purchase/save', 'PurchaseController@po_save')->name('admin.purchase.save');
    Route::get('purchase/edit/{id}', 'PurchaseController@po_edit')->name('admin.purchase.edit');
    Route::post('purchase/update', 'PurchaseController@po_update')->name('admin.purchase.update');
    Route::get('purchase/view/{id}', 'PurchaseController@po_view')->name('admin.purchase.view');
    Route::get('purchase/preview/{id}', 'PurchaseController@po_preview')->name('admin.purchase.preview');
    Route::get('purchase/print/{id}', 'PurchaseController@po_pdf')->name('admin.purchase.print');
    Route::get('purchase/delete/{id}', 'PurchaseController@po_delete')->name('admin.purchase.delete');
    Route::get("purchase/requirement/list","PurchaseController@requirement_list")->name('admin.purchase.requirement.list');
    Route::post("received/save","InwardController@received_save")->name("post.received_purchase");
    Route::post("po/invoice/save","PurchaseController@po_invoice_save")->name("post.po_invoice_details_save");
    /*
    |--------------------------------------------------------------------------
    | ✅ INWARD STOCK ENTRY
    |--------------------------------------------------------------------------
    */
    Route::get('inward/list', 'PurchaseController@inward_list')->name('admin.inward.list');
    Route::get('inward/add/{po_id?}', 'InwardController@inward_add')->name('admin.inward.add');
    Route::post('inward/save', 'InwardController@inward_save')->name('admin.inward.save');
    Route::get('inward/edit/{id}', 'InwardController@inward_edit')->name('admin.inward.edit');
    Route::post('inward/update', 'InwardController@inward_update')->name('admin.inward.update');
    Route::get('inward/view/{id}', 'PurchaseController@po_receive')->name('admin.inward.view');
    Route::get('inward/delete/{id}/{product?}', 'PurchaseController@inward_delete')->name('admin.inward.delete');
    Route::get("po/invoice/{id?}","PurchaseController@po_invoice")->name('admin.po.invoice');
    /*
|--------------------------------------------------------------------------
| ✅ PRODUCTION FLOW (Washing, Stitching, Packing, Pressing)
|--------------------------------------------------------------------------
*/
    Route::group([
        'prefix' => 'production',
        'as' => 'admin.production.',
    ], function () {

        Route::get("details/{batch_no?}","ProductionController@production_details")->name('details');
        Route::get("batch/machine/{id?}","ProductionController@add_batch")->name('add_batch');
        Route::get("machine/complete/batch/{id?}","ProductionController@production_complete_batch")->name('machine.complete.batch');

        //Formula
        Route::get('/formula/add', "FormulaController@formula_add")
            ->name('formula_add');

        Route::get('/formula/edit/{id?}', "FormulaController@formula_mst_edit")
            ->name('formula_edit');

        Route::get('/formula/list', "FormulaController@formula_list")
            ->name('formula_list');

        Route::post('/formula/update', "FormulaController@formula_update")
            ->name('formula_update');

        Route::post('/formula/item/save', "FormulaController@formula_item_save")
            ->name('formula_item_save');

        Route::post('/formula/material/save', "FormulaController@formula_material_save")
            ->name('formula_material_save');

        Route::get('/formula/view/{id?}', "FormulaController@formula_view")
            ->name('formula_view');

        Route::get('/formula/delete/{id?}', "FormulaController@formula_delete")
            ->name('formula_delete');

        // Buckle Formula (Belt raw-material formula - separate from the sock Formula Master above)
        Route::get('/buckle-formula/list', "BuckleFormulaController@buckle_formula_list")
            ->name('buckle_formula_list');

        Route::get('/buckle-formula/add', "BuckleFormulaController@buckle_formula_add")
            ->name('buckle_formula_add');

        Route::post('/buckle-formula/store', "BuckleFormulaController@buckle_formula_store")
            ->name('buckle_formula_store');

        Route::get('/buckle-formula/edit/{id}', "BuckleFormulaController@buckle_formula_edit")
            ->name('buckle_formula_edit');

        Route::post('/buckle-formula/update', "BuckleFormulaController@buckle_formula_update")
            ->name('buckle_formula_update');

        Route::get('/buckle-formula/delete/{id}', "BuckleFormulaController@buckle_formula_delete")
            ->name('buckle_formula_delete');

        // Dashboard
        Route::get('/dashboard', "ProductionController@production_dashboard")
            ->name('dashboard');

        // All Processes
        Route::get('/process', "ProductionController@production_process")
            ->name('process');

        Route::get('/allocate/machine', "ProductionController@allocate_machine")
            ->name('allocate_machines');

        Route::get('/allprocess', "ProductionController@all_production")
            ->name('all');

        // Main machine batch
        Route::get('/machine/batch/{id?}', "ProductionController@production_batch")
            ->name('machine.batch');

        Route::get('/machine/batch/delete/{id?}', "ProductionController@production_batch_delete")
            ->name('machine.batch.delete');

        Route::get("complete","ProductionController@production_complete")->name('complete');

        // Save production record
        Route::post('/record/save', "ProductionController@production_record")
            ->name('record.save');


        // ==========================
        //        WASHING
        // ==========================
        Route::get('/move-to-washing/{batch_no?}', "ProductionController@move_to_washing")
            ->name('washing.move');

        Route::post('/washing/record/save', "ProductionController@production_washing_record")
            ->name('washing.record.save');

        Route::get('/washing/pending', "ProductionController@washing_pending")
            ->name('washing.pending');

        Route::get('/washing/complete', "ProductionController@washing_complete")
            ->name('washing.complete');

        Route::get('/washing/all', "ProductionController@washing_all")
            ->name('washing.all');

        Route::get('/washing/machine/batch/{id?}', "ProductionController@washing_pending_batch")
            ->name('washing.machine.batch');

        Route::get('/washing/machine/complete/{id?}', "ProductionController@washing_complete_batch")
            ->name('washing.machine.complete');
        Route::get("washing-machine/list","MachineController@washing_machine_list")->name("washing_machinelist");

        Route::get('/washing/delete/{id?}', "ProductionController@washing_delete")
            ->name('washing.delete');

        Route::get('/washing/dashboard', "ProductionController@washing_dashboard")
            ->name('washing.dashboard');
        Route::get("/machine/list","ProductionController@machine_list")->name('machine.list');
        Route::post("machine/allocate","ProductionController@machine_allocate")
            ->name("allow_machine_production");
        Route::post("getproduction_mat","ProductionController@getproduction_mat");
        Route::get("getproduct_stock","ProductionController@getproduct_stock");
        Route::get("getproduct_image","ProductionController@getproduct_image");

        // ==========================
        //        PRESSING
        // ==========================
        Route::get('/move-to-pressing/{batch_no?}', "ProductionController@move_to_pressing")
            ->name('pressing.move');

        Route::post('/pressing/record/save', "ProductionController@production_pressing_record")
            ->name('pressing.record.save');

        Route::get('/pressing/pending', "ProductionController@pressing_pending")
            ->name('pressing.pending');

        Route::get('/pressing/complete', "ProductionController@pressing_complete")
            ->name('pressing.complete');

        Route::get('/pressing/all', "ProductionController@pressing_all")
            ->name('pressing.all');

        Route::get('/pressing/machine/batch/{id?}', "ProductionController@pressing_pending_batch")
            ->name('pressing.machine.batch');

        Route::get('/pressing/machine/complete/{id?}', "ProductionController@pressing_complete_batch")
            ->name('pressing.machine.complete');

        Route::get('/pressing/delete/{id?}', "ProductionController@pressing_delete")
            ->name('pressing.delete');

        Route::get('/pressing/dashboard', "ProductionController@pressing_dashboard")
            ->name('pressing.dashboard');


        // ==========================
        //        STITCHING
        // ==========================
        Route::get('/move-to-stitching/{batch_no?}', "ProductionController@move_to_stitching")
            ->name('stitching.move');

        Route::post('/stitching/record/save', "ProductionController@production_stitching_record")
            ->name('stitching.record.save');

        Route::get('/stitching/pending', "ProductionController@stiching_pending")
            ->name('stitching.pending');

        Route::get('/stitching/complete', "ProductionController@stitching_complete")
            ->name('stitching.complete');

        Route::get('/stitching/all', "ProductionController@stitching_all")
            ->name('stitching.all');

        Route::get('/stitching/machine/batch/{id?}', "ProductionController@stitching_pending_batch")
            ->name('stitching.machine.batch');

        Route::get('/stitching/machine/complete/{id?}', "ProductionController@stitching_complete_batch")
            ->name('stitching.machine.complete');

        Route::get('/stitching/delete/{id?}', "ProductionController@stitching_delete")
            ->name('stitching.delete');

        Route::get('/stitching/dashboard', "ProductionController@stitching_dashboard")
            ->name('stitching.dashboard');


        // ==========================
        //        PACKAGING
        // ==========================
        Route::get('/move-to-packaging/{batch_no?}', "ProductionController@move_to_packaging")
            ->name('packaging.move');

        Route::post('/packaging/record/save', "ProductionController@production_packaging_record")
            ->name('packaging.record.save');

        Route::get('/packaging/pending', "ProductionController@packaging_pending")
            ->name('packaging.pending');

        Route::get('/packaging/complete', "ProductionController@packaging_complete")
            ->name('packaging.complete');

        Route::get('/packaging/all', "ProductionController@packaging_all")
            ->name('packaging.all');

        Route::get('/packaging/machine/batch/{id?}', "ProductionController@packaging_pending_batch")
            ->name('packaging.machine.batch');

        Route::get('/packaging/machine/complete/{id?}', "ProductionController@packaging_complete_batch")
            ->name('packaging.machine.complete');

        Route::get('/packaging/delete/{id?}', "ProductionController@packag_delete")
            ->name('packaging.delete');

        Route::get('/packaging/dashboard', "ProductionController@packaging_dashboard")
            ->name('packaging.dashboard');

    });


    /*
    |--------------------------------------------------------------------------
    | ✅ MACHINE MANAGEMENT (Washing, Stitching, etc.)
    |--------------------------------------------------------------------------
    */
    Route::prefix('machine')->group(function () {
        Route::get('list', 'MachineController@machine_list')->name('admin.machine.list');
        Route::get('add', 'MachineController@machine_add')->name('admin.machine.add');
        Route::post('save', 'MachineController@machine_save')->name('admin.machine.save');
        Route::get('edit/{id}', 'MachineController@machine_edit')->name('admin.machine.edit');
        Route::post('update', 'MachineController@machine_update')->name('admin.machine.update');
        Route::get('delete/{id}', 'MachineController@machine_delete')->name('admin.machine.delete');
    });

    /*
    |--------------------------------------------------------------------------
    | ✅ GRN (Goods Received Note) & STOCK
    |--------------------------------------------------------------------------
    */
    Route::prefix('grn')->group(function () {
        Route::get('list', 'GRNController@grn_list')->name('admin.grn.list');
    });
    Route::prefix('stock')->group(function () {
        Route::get('status', 'InwardController@stock_status')->name('admin.stock.status');
        Route::get('book', 'PurchaseController@stock_book')->name('admin.stock.book');
        Route::get('inward-add',"PurchaseController@inward_add")->name('admin.stock.inward.add');
        Route::post("inward/save",['as'=>'post.stock_insert','uses'=>"InwardController@inward_save"]);

    });

    /*
    |--------------------------------------------------------------------------
    | ✅ REQUIREMENT (Purchase Requirement)
    |--------------------------------------------------------------------------
    */
    Route::prefix('requirement')->group(function () {
        Route::get('view/{id}', 'PurchaseController@requirement_view')->name('admin.requirement.view');
        Route::post('add', 'PurchaseController@requirement_add')->name('admin.requirement.add');
        Route::get('list', 'PurchaseController@requirement_list')->name('admin.requirement.list');
    });

    /*
    |--------------------------------------------------------------------------
    | ✅ BILL OF MATERIAL (BOM)
    |--------------------------------------------------------------------------
    */
    Route::prefix('bom')->group(function () {
        Route::get('add', 'BomController@bom_add')->name('admin.bom.add');
        Route::post('save', 'BomController@bom_save')->name('admin.bom.save');
        Route::get('list', 'BomController@bom_list')->name('admin.bom.list');
        Route::get('edit/{id}', 'BomController@bom_edit')->name('admin.bom.edit');
        Route::post('update', 'BomController@bom_update')->name('admin.bom.update');
        Route::get('delete/{id}', 'BomController@bom_delete')->name('admin.bom.delete');
        Route::get('preview/{id}', 'BomController@bom_preview')->name('admin.bom.preview');
    });

    /*
    |--------------------------------------------------------------------------
    | ✅ REPORTS
    |--------------------------------------------------------------------------
    */
    Route::prefix('reports')->group(function () {
        Route::get('quotation', 'ReportsController@quotation')->name('admin.reports.quotation');
        Route::get('sales', 'ReportsController@sales')->name('admin.reports.sales');
        Route::get('sales-out-of-stock-item', 'ReportsController@salesOutOfStockItems')->name('admin.reports.sales.out_of_stock_items');
        Route::get('challan', 'ReportsController@challan')->name('admin.reports.challan');
        Route::get('invoice', 'ReportsController@invoice')->name('admin.reports.invoice');
        Route::get('sales-summary', 'ReportsController@salesSummary')->name('admin.reports.sales_summary');
        Route::get('product-wise-sales', 'ReportsController@productWiseSales')->name('admin.reports.product_wise_sales');
        Route::get('salesman-wise-sales', 'ReportsController@salesmanWiseSales')->name('admin.reports.salesman_wise_sales');
        Route::get('stock-available', 'ReportsController@stockAvailable')->name('admin.reports.stock_available');
        Route::get('raw-material-pending', 'ReportsController@rawMaterialPending')->name('admin.reports.raw_material_pending');
        Route::get('production-pending', 'ReportsController@productionPending')->name('admin.reports.production_pending');
        Route::get('stitching-pending', 'ReportsController@stitchingPending')->name('admin.reports.stitching_pending');
        Route::get('pressing-pending', 'ReportsController@pressingPending')->name('admin.reports.pressing_pending');
        Route::get('packaging-pending', 'ReportsController@packagingPending')->name('admin.reports.packaging_pending');
        Route::get('belt-production', 'ReportsController@beltProduction')->name('admin.reports.belt_production');
    });



    Route::get('/shipping-charges', [ShippingChargeController::class, 'index'])->name('admin.shipping.index');
    Route::post('/shipping-charges/store', [ShippingChargeController::class, 'store'])->name('admin.shipping.store');
    Route::post('/shipping-charges/update/{id}', [ShippingChargeController::class, 'update'])->name('admin.shipping.update');
    Route::delete('/shipping-charges/delete/{id}', [ShippingChargeController::class, 'destroy'])->name('admin.shipping.delete');
    /*
    |--------------------------------------------------------------------------
    | ✅ LOGOUT
    |--------------------------------------------------------------------------
    */
    Route::get('logout', 'MasterController@logout')->name('admin.logout');

});

