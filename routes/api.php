<?php

use App\Http\Controllers\Website\CheckoutController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Use App\quotation;

Route::get('quotation', function() {
    // If the Content-Type and Accept headers are set to 'application/json',
    // this will return a JSON structure. This will be cleaned up later.
    return quotation::all();
});

Route::get("get_product_detail","FrontController@get_product_detail");
Route::get("get_product_detail_image","FrontController@get_product_detail_image");

Route::get('update-inquiry', 'CartController@inquiry_update');

Route::get("get_product","AdminController@ajax_search_product");
