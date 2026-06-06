<?php

use App\Http\Controllers\Api\Auth\LoginController;
use App\Http\Controllers\Api\Panel\GatewayController;
use App\Http\Controllers\Api\Data\GatewayController as DataGatewayController;
use Illuminate\Support\Facades\Route;


//-----------------------------
//global routes (doesnt need authenticate)
//-----------------------------
Route::get('/data/gateways', DataGatewayController::class);
Route::prefix('payment')->group(function () {

});

//base login Routes
Route::post('/auth/login', LoginController::class)->middleware('guest');

//need authenticate routing
Route::prefix('panel')->middleware('auth:sanctum')->group(function () {
    Route::apiResource('gateways', GatewayController::class)->except('show');
});

