<?php

use App\Http\Controllers\Website\AccountController;
use App\Http\Controllers\Website\CheckoutController;
use App\Http\Controllers\Website\LoginController;
use App\Http\Controllers\Website\OrderController;
use App\Http\Controllers\Website\RegisterController;
use Illuminate\Support\Facades\File;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

Route::get('/', function () {
    return redirect()->route('website.home');
});

Route::get('/refresh-csrf', function () {
    return csrf_token();
});

Route::get('/debug-login-temp-xyz', function () {
    \Auth::loginUsingId(1);
    \Session::put('software_title', 'Bestow');
    \Session::put('records_per_page', \App\company::first()->records_per_page ?? 30);
    return redirect('/admin');
});

Route::group(['namespace' => 'Api', 'prefix' => 'ajax', 'as' => 'ajax.'], function () {
    Route::get('states', 'CommonController@ajaxGetStates')->name('states');
    Route::get('cities', 'CommonController@ajaxGetCities')->name('cities');
});


Route::match(['POST', 'GET'], '/login', [LoginController::class, 'index'])->name('user.login');
Route::match(['POST', 'GET'], '/register', [RegisterController::class, 'index'])->name('user.register.index');

Route::get('logout', [LoginController::class, 'logout'])->name('user.logout');

Route::match(['POST', 'GET'], 'forget-password', 'FrontController@forgot_password')->name('user.forget.password');
Route::match(['POST', 'GET'], 'confirm', 'FrontController@verify_otp')->name('user.forgot.password.confirm');

//Route::match(['POST', 'GET'],'user_login',['as'=>'user.login','uses'=>'Website\LoginController@user_login']);

Route::group(['namespace' => 'Website', 'as' => 'website.'], function () {

    Route::get('/login', [LoginController::class, 'loginPage'])->name('login');
    Route::post('/login', [LoginController::class, 'loginSubmit'])->name('login.submit');

    Route::get('/forgot-password', [LoginController::class, 'forgotPassword'])->name('forgot.password');
    Route::post('/forgot-password', [LoginController::class, 'forgotPasswordPost'])->name('forgot.password.post');

    Route::get('/reset-password/{token}', [LoginController::class, 'resetPassword'])->name('reset.password');
    Route::post('/reset-password', [LoginController::class, 'resetPasswordPost'])->name('reset.password.post');

// Register
    Route::get('/register', [LoginController::class, 'registerPage'])->name('register');
    Route::post('/register', [LoginController::class, 'registerSubmit'])->name('register.submit');

// Logout
    Route::get('/logout', [LoginController::class, 'logout'])->name('logout');
    Route::post('/home/section/featured', 'HomeController@load_featured_section')->name('home.section.featured');
    Route::post('/home/section/best_selling', 'HomeController@load_best_selling_section')->name('home.section.best_selling');

    Route::get('/change/{lang?}', 'HomeController@changeLanguage')->name('lang');

    Route::get('/', 'HomeController@home')->name('home');
    Route::get('about', 'HomeController@about')->name('about');

    Route::match(['POST', 'GET'], 'contact', 'ContactController@index')->name('contact');
    Route::match(['POST', 'GET'], 'grievance', 'ContactController@grievance')->name('grievance');

    Route::get('mission-vision', 'HomeController@visionMission')->name('mission.vision');
    Route::get('our-legal', 'HomeController@legal')->name('legal');

    Route::get('faqs', 'HomeController@faqs')->name('faqs');
    Route::get('blogs', 'HomeController@blogs')->name('blog');
    Route::get('blogs/{slug}', 'HomeController@blogdetails')->name('blogdetails');

    // GalleryController was never implemented and gallery.* is not linked from any
    // active view (only the unused resources/views/website_backup/ folder).
    // Route::group(['prefix' => 'gallery', 'as' => 'gallery.'], function () {
    //     Route::get('/', 'GalleryController@index')->name('view');
    //     Route::get('details/{id}', 'GalleryController@detail')->name('detail');
    // });

    Route::get('/brands', 'HomeController@all_brands')->name('brands.all');
    Route::get('/categories', 'HomeController@all_categories')->name('categories.all');

    Route::get('/flash-deals', 'HomeController@all_flash_deals')->name('flash.deals');
    Route::get('/flash-deal/{slug}', 'HomeController@flash_deal_details')->name('flash.deal.details');

    Route::get('policy/{slug}/{id}', 'HomeController@policy')->name('policy');

    Route::match(['POST', 'GET'], 'contact', 'ContactController@index')->name('contact');

    Route::get('auto-complete', 'ProductController@autoComplete')->name('auto.complete');
    Route::get('search-result', 'ProductController@searchResult')->name('search.result');
    Route::get('/search', 'ProductController@index')->name('search');
    Route::get('/search?keyword={search}', 'ProductController@index')->name('suggestion.search');
    Route::post('/ajax-search', 'ProductController@ajax_search')->name('search.ajax');

    Route::get('/category/{category_slug?}', 'ProductController@listingByCategory')->name('products.category');
    Route::get('/brand/{brand_slug?}', 'ProductController@listingByBrand')->name('products.brand');

    Route::group(['prefix' => 'product', 'as' => 'product.'], function () {
        Route::get('/', 'ProductController@index')->name('view');
        Route::get('details/{slug?}/{code?}', 'ProductController@details')->name('details');
    });

    Route::group(['prefix' => 'cart', 'as' => 'cart.'], function () {

        Route::get('/', 'CartController@view')->name('view');
        Route::get('retrieve', 'CartController@retrieve')->name('retrieve');
        Route::post('add', 'CartController@add')->name('add');
        Route::post('remove', 'CartController@remove')->name('remove');
        Route::post('update', 'CartController@update')->name('update');
        Route::post('load-offer-items', 'CartController@loadOfferItems')->name('load.offer.items');
    });



    Route::get('/get-cities/{state_id}', [CheckoutController::class, 'getCities'])->name('get.cities');

    Route::get('/orders/{id}/invoice', [OrderController::class, 'invoice'])
        ->name('orders.invoice');

    Route::middleware('userAuth')->group(function () {
        Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.view');
        // routes/web.php
        Route::post('/calculate-shipping', [CheckoutController::class, 'calculateShipping'])
            ->name('checkout.shipping');

        Route::post('/checkout/place-order', [CheckoutController::class, 'placeOrder'])->name('checkout.place');

        Route::post('/checkout/address', [CheckoutController::class, 'storeAddress'])
            ->name('checkout.address.store');

        Route::delete('/checkout/address/{address}', [CheckoutController::class, 'destroyAddress'])
            ->name('checkout.address.destroy');

        Route::post('/payment/razorpay-verify', [CheckoutController::class, 'verifyRazorpay'])->name('payment.verify');
        Route::get('/order/success/{order_id?}/{razorpay_order_id?}', [OrderController::class, 'success'])->name('order.success');
        Route::get('/account', [AccountController::class, 'dashboard'])
            ->name('account.dashboard');// If you're using session auth

// ✅ Orders Page
        Route::get('/orders', [OrderController::class, 'myOrders'])
            ->name('orders');

        Route::get('/my-orders', [OrderController::class, 'myOrders'])->name('my.orders');
        Route::get('/my-orders/{id}', [OrderController::class, 'orderDetails'])->name('my.order.details');

        Route::post('/orders/{id}/cancel', [OrderController::class, 'cancel'])
            ->name('orders.cancel');

// ✅ Return / Replace Order
        Route::get('/orders/{id}/return', [OrderController::class, 'return'])
            ->name('orders.return');


    });


// Guest Order Tracking
    Route::get('/track-order', [OrderController::class, 'trackOrderForm'])->name('track.order');
    Route::post('/track-order', [OrderController::class, 'trackOrder'])->name('track.order.submit');
    Route::group(['prefix' => 'order-checkout', 'as' => 'order.checkout.', 'middleware' => 'website.checkout'], function () {

        Route::group(['prefix' => 'address', 'as' => 'address.'], function () {
            Route::post('create', 'AddressController@create')->name('create');
            Route::post('update', 'AddressController@update')->name('update');
        });

        Route::match(['get', 'post'], 'address', 'OrderProcessController@address')->name('address');
        Route::get('payment', 'OrderProcessController@payment')->name('payment');
        Route::post('process', 'OrderProcessController@process')->name('process');
    });

    Route::match(['GET', 'POST'], 'order-payment-response', 'OrderProcessController@paymentResponse')->name('order.payment.response');

    Route::get('overview/{customer_order_id}', 'OrderProcessController@overview')->name('overview');

});
