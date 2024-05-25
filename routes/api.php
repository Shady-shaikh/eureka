<?php

use App\Http\Controllers\backend\AccountController;
use App\Http\Controllers\backend\ApiController;
use App\Http\Controllers\backend\OutletController;
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

// admin.outlet
Route::middleware('throttle:1000,1')->group(function () {

    //outlet module
    Route::get('/outlets', [ApiController::class, 'outlets'])->name('admin.outlets');
    Route::post('/update_outlet', [ApiController::class, 'update_outlet'])->name('admin.update_outlet');
    Route::get('/view_outlet', [ApiController::class, 'view_outlet'])->name('admin.view_outlet');
    Route::any('/delete_outlet', [ApiController::class, 'delete_outlet'])->name('admin.delete_outlet');
    Route::get('/get_area_route_beat', [ApiController::class, 'get_area_route_beat'])->name('admin.get_area_route_beat');
    Route::get('/get_country_state_district', [ApiController::class, 'get_country_state_district'])->name('admin.get_country_state_district');


    Route::get('/get_companies', [ApiController::class, 'get_companies'])->name('admin.get_companies');
    Route::any('/login', [ApiController::class, 'login'])->name('admin.login');
    Route::post('/updatePassword', [ApiController::class, 'updatePassword'])->name('admin.updatePassword');
    Route::get('/get_dashboard_data', [ApiController::class, 'get_dashboard_data'])->name('admin.get_dashboard_data');
    Route::any('/get_user_data', [ApiController::class, 'get_user_data'])->name('admin.get_user_data');
    Route::any('/get_daily_beats', [ApiController::class, 'get_daily_beats'])->name('admin.get_daily_beats');
    Route::any('/get_outlets', [ApiController::class, 'get_outlets'])->name('admin.get_outlets');
    Route::post('/update_days_plan', [ApiController::class, 'update_days_plan'])->name('admin.update_days_plan');
    Route::post('/update_outlet_Selection', [ApiController::class, 'update_outlet_Selection'])->name('admin.update_outlet_Selection');
    Route::any('/get_orders', [ApiController::class, 'get_orders'])->name('admin.get_orders');
    Route::any('/get_products', [ApiController::class, 'get_products'])->name('admin.get_products');
    Route::any('/update_order', [ApiController::class, 'update_order'])->name('admin.update_order');
    Route::any('/update_order_temp', [ApiController::class, 'update_order_temp'])->name('admin.update_order_temp');
    Route::any('/view_order', [ApiController::class, 'view_order'])->name('admin.view_order');
    Route::any('/view_order_temp', [ApiController::class, 'view_order_temp'])->name('admin.view_order_temp');
    Route::any('/get_gst', [ApiController::class, 'get_gst'])->name('admin.get_gst');
    Route::any('/get_item_auto', [ApiController::class, 'get_item_auto'])->name('admin.get_item_auto');
    Route::any('/get_margin_scheme', [ApiController::class, 'get_margin_scheme'])->name('admin.get_margin_scheme');
    Route::any('/update_so_items', [ApiController::class, 'update_so_items'])->name('admin.update_so_items');
    Route::any('/update_so_items_temp', [ApiController::class, 'update_so_items_temp'])->name('admin.update_so_items_temp');
    Route::any('/view_order_item', [ApiController::class, 'view_order_item'])->name('admin.view_order_item');
    Route::any('/view_order_item_temp', [ApiController::class, 'view_order_item_temp'])->name('admin.view_order_item_temp');
    Route::any('/delete_order', [ApiController::class, 'delete_order'])->name('admin.delete_order');
    Route::any('/delete_order_temp', [ApiController::class, 'delete_order_temp'])->name('admin.delete_order_temp');
    Route::any('/delete_order_item', [ApiController::class, 'delete_order_item'])->name('admin.delete_order_item');
    Route::any('/delete_order_item_temp', [ApiController::class, 'delete_order_item_temp'])->name('admin.delete_order_item_temp');
    Route::any('/save_order', [ApiController::class, 'save_order'])->name('admin.save_order');
    Route::post('/update_outstanding', [ApiController::class, 'update_outstanding'])->name('admin.update_outstanding');
    Route::post('/update_visibility', [ApiController::class, 'update_visibility'])->name('admin.update_visibility');
    Route::post('/save_comments', [ApiController::class, 'save_comments'])->name('admin.save_comments');
    Route::any('/get_previous_comments', [ApiController::class, 'get_previous_comments'])->name('admin.get_previous_comments');
    Route::any('/get_previous_soh', [ApiController::class, 'get_previous_soh'])->name('admin.get_previous_soh');
    Route::post('/save_soh', [ApiController::class, 'save_soh'])->name('admin.save_soh');
    Route::post('/update_outlet_image', [ApiController::class, 'update_outlet_image'])->name('admin.update_outlet_image');
});

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
