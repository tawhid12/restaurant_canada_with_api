<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthenticationController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\DashboardController;
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
/*=== Customer Registration */
Route::post('/reg-customer',[AuthenticationController::class,'signUpStore']);
/*=== Owner Registration */
Route::post('/reg-owner',[AuthenticationController::class,'signUpregistrationStore']);
/*=== Delivery Registration */
Route::post('/reg-delivery-boy',[AuthenticationController::class,'signUp_delivery_boy_Store']);
/* Login For Customer | Owner | Delivery Boy  */
Route::post('/login',[AuthenticationController::class,'signIn']);
Route::get('/profile/{token}/{id}', [UserController::class,'userProfile']);
Route::post('/changePer/{token}/{id}',  [UserController::class,'changePer'])->name('owner.changePer');
/* == Owner Dashboard== */
Route::get('owner/dashboard/{token}', [DashboardController::class,'ownerDashboard']);
Route::get('owner/order-list/pending/{token}', [DashboardController::class,'pendingOrder']);
Route::get('owner/order-list/processing/{token}', [DashboardController::class,'processingOrder']);
Route::get('owner/order-list/complete/{token}', [DashboardController::class,'completeOrder']);

/* == Order Ready | Process | Cancel  == */
Route::get('owner/order/ready/{token}/{id}', [DashboardController::class,'orderReady']);
Route::get('owner/order/processing/{token}/{id}', [DashboardController::class,'orderProcessing']);
Route::get('owner/order/cancel/{token}/{id}', [DashboardController::class,'orderCancel']);

/* Cusotmer single Order */
Route::get('customer/order/{token}/{id}', [DashboardController::class,'orderbyId']);

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

