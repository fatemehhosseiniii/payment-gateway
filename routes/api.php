<?php

use App\Http\Controllers\Api\Auth\LoginController;
use App\Http\Controllers\Api\Panel\GatewayController;
use Illuminate\Support\Facades\Route;


//base login Routes
Route::post('/auth/login', LoginController::class)->middleware('guest');

//need authenticate routing
Route::prefix('panel')->middleware('auth:sanctum')->group(function () {
    Route::apiResource('gateways', GatewayController::class)->except('show');
});

