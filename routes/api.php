<?php

use App\Http\Controllers\Api\Auth\LoginController;
use App\Http\Controllers\Api\Panel\GatewayController;
use App\Http\Controllers\Api\Data\GatewayController as DataGatewayController;
use App\Http\Controllers\Api\Payment\PayRequestController;
use Illuminate\Support\Facades\Route;


//-----------------------------
//global routes (doesnt need authenticate)
//-----------------------------
Route::get('/data/gateways', DataGatewayController::class);

Route::prefix('payment')->group(function () {
    Route::post('/pay-request',[PayRequestController::class,'store']);
    Route::get('/verify/{gateway}',[PayRequestController::class,'verify']);
});

//base login Routes
Route::post('/auth/login', LoginController::class)->middleware('guest');

//need authenticate routing
Route::prefix('panel')->middleware('auth:sanctum')->group(function () {
    Route::apiResource('gateways', GatewayController::class)->except('show');
});

