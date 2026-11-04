<?php

use App\Http\Controllers\v1\management\Auth\AuthController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => '/v1/auth'], function () {
    Route::post('login', [AuthController::class, 'authenticate']);
    Route::post('logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
});
