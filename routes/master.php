<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Master Routes
|--------------------------------------------------------------------------
|
| Routes related to master admin functionality, including module, submodule,
| user, and website management.
|
*/

Route::get('master', "MasterController@index")->name('master/index');
Route::get("master/logout", "LoginController@master_logout");
Route::post("master_login", ['as' => 'master.login_master', 'uses' => 'LoginController@login_master']);
Route::get("masteradmin", "LoginController@master_login");

Route::get("master/website-list", "MasterController@website_list")->name('master/website-list');
Route::get("master/website_add", "MasterController@website_add");
Route::post("master/website_save", ['as' => 'post.website_save', 'uses' => 'MasterController@website_save']);
Route::get("master/website_edit/{id?}", "MasterController@website_edit");
Route::post("master/website_update", ['as' => 'post.website_update', 'uses' => 'MasterController@website_update']);

Route::get("master/module-list", "MasterController@module_list")->name('master/module_list');
Route::get("master/module_add", "MasterController@module_add");
Route::post("master/module_save", ['as' => 'post.module_save', 'uses' => "MasterController@module_save"]);
Route::get("master/module_edit/{id?}", "MasterController@module_edit");
Route::get("master/module_delete/{id?}", "MasterController@module_delete");
Route::post("master/module_update", ['as' => 'post.module_update', 'uses' => 'MasterController@module_update']);

Route::get("master/submodule/list", "MasterController@submodule_list")->name("master/submodule");
Route::get("master/sub/module/add", "MasterController@submodule_add");
Route::post("master/sub/module/save", ['as' => 'post.sub_module_save', 'uses' => "MasterController@sub_module_save"]);
Route::get("master/submodule_edit/{id?}", "MasterController@submodule_edit");
Route::post("master/submodule/update", ['as' => 'post.sub_module_update', 'uses' => 'MasterController@submodule_update']);
Route::get("master/submodule/delete/{id?}", "MasterController@submodule_delete");

Route::get("master/user-list", "MasterController@user_list")->name('master/user_list');
Route::get("master/user_add", "MasterController@user_add");
Route::post("master/user_save", ['as' => 'post.user_save', 'uses' => 'MasterController@user_save']);
Route::get("master/user_edit/{id?}", "MasterController@user_edit");
Route::post("master/user_update", ['as' => 'post.user_update', 'uses' => "MasterController@user_update"]);

Route::get("master/main/user", "MasterController@main_user_list")->name('master/main_user_list');
Route::post("master/main/user/save", ['as' => 'post.main_user_save', 'uses' => 'MasterController@main_user_save']);
Route::get("master/master_user_add", "MasterController@master_user_add");
Route::post("master/master_user_save", ['as' => 'post.master_user_save', 'uses' => 'MasterController@master_user_save']);
Route::get("master/master_user_edit/{id?}", "MasterController@master_user_edit");
Route::post("master/master_user_update", ['as' => 'post.master_user_update', 'uses' => "MasterController@master_user_update"]);
Route::get("master/master_user_delete/{id?}", "MasterController@master_user_delete");

Route::get("master/master-module-list", "MasterController@master_module_list")->name('master/master_module_list');
Route::get("master/master_module_add", "MasterController@master_module_add");
Route::post("master/master_module_save", ['as' => 'post.master_module_save', 'uses' => "MasterController@master_module_save"]);
Route::get("master/master_module_edit/{id?}", "MasterController@master_module_edit");
Route::get("master/master_module_delete/{id?}", "MasterController@master_module_delete");
Route::post("master/master_module_update", ['as' => 'post.master_module_update', 'uses' => 'MasterController@master_module_update']);

?>
