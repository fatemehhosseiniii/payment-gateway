<?php

use App\Http\Controllers\Api\Auth\LoginController;
use App\Http\Controllers\Api\Panel\GatewayController;
use App\Http\Controllers\Api\Panel\PayRequestController;
use App\Http\Controllers\Api\Data\GatewayController as DataGatewayController;
use App\Http\Controllers\Api\Panel\PayRequestStatusController;
use App\Http\Controllers\Api\Payment\PayRequestController as PaymentPayRequestController;
use Illuminate\Support\Facades\Route;


//-----------------------------
//global routes (doesnt need authenticate)
//-----------------------------
Route::get('/data/gateways', DataGatewayController::class);
Route::prefix('payment')->group(function () {
    Route::post('/pay-request', [PaymentPayRequestController::class, 'store']);
    Route::get('/verify/{gateway}', [PaymentPayRequestController::class, 'verify']);
});


//base login Routes
Route::post('/auth/login', LoginController::class)->middleware('guest');


//-----------------------------
//Panel routes (need authenticate routing)
//-----------------------------
Route::prefix('panel')->middleware('auth:sanctum')->group(function () {
    Route::apiResource('gateways', GatewayController::class)->except('show');

    Route::get('/pay-request-statuses', PayRequestStatusController::class);
    Route::apiResource('pay-requests', PayRequestController::class)->only('index', 'show');
});

