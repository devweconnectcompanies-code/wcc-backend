<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\v1\management\operations\OperationsController;

Route::prefix('v1/operations')
    ->middleware(['auth:sanctum'])
    ->group(function () {

        //      Technicals
        Route::group(['prefix' => 'technical'], function () {
            Route::post('data', [OperationsController::class, 'data']);
            Route::post('edit', [OperationsController::class, 'edit']);
            Route::put('{id}', [OperationsController::class, 'processSupport']);
        });
    });
