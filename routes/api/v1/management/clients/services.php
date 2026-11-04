<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\v1\management\services\ServicesController;
use App\Http\Controllers\v1\management\services\ServiceInternetsController;
use App\Http\Controllers\v1\management\services\ServiceEquipmentController;
use App\Http\Controllers\v1\management\services\ServiceIPTVEquipmentController;

Route::prefix('v1/services')
    ->middleware(['auth:sanctum'])
    ->group(function () {
        //  General Data
        Route::post('/', [ServicesController::class, 'store']);
        Route::post('data', [ServicesController::class, 'data']);
        Route::post('/edit', [ServicesController::class, 'read']);
        Route::put('{id}', [ServicesController::class, 'update']);
        Route::post('/client', [ServicesController::class, 'clientServices']);
        Route::get('client/{id}', [ServicesController::class, 'servicesList']);
        Route::get('{id}/address', [ServicesController::class, 'serviceAddress']);

        //      Services Internet
        Route::group(['prefix' => 'internet'], function () {
            Route::post('/', [ServiceInternetsController::class, 'store']);
            Route::post('read', [ServiceInternetsController::class, 'read']);
            Route::put('{id}', [ServiceInternetsController::class, 'update']);
        });

        //      Service Equipment
        Route::group(['prefix' => 'equipment'], function () {

            //  Service Internet Equipment
            Route::group(['prefix' => 'internet'], function () {
                Route::post('/', [ServiceEquipmentController::class, 'store']);
                Route::post('list', [ServiceEquipmentController::class, 'list']);
                Route::delete('/', [ServiceEquipmentController::class, 'remove']);
            });

            //      Service IPTV Equipment
            Route::group(['prefix' => 'iptv'], function () {
                Route::post('/', [ServiceIptvEquipmentController::class, 'store']);
                Route::post('list', [ServiceIPTVEquipmentController::class, 'data']);
                Route::post('edit', [ServiceIPTVEquipmentController::class, 'edit']);
                Route::put('{id}', [ServiceIPTVEquipmentController::class, 'update']);
                Route::delete('/', [ServiceIPTVEquipmentController::class, 'remove']);
                Route::post('suggested-email', [ServiceIPTVEquipmentController::class, 'suggestedEmail']);
            });
        });

    });
