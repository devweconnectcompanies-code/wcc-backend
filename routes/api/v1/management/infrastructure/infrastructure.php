<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\v1\management\infrastructure\network\AuthServersController;
use App\Http\Controllers\v1\management\infrastructure\network\NodesController;
use App\Http\Controllers\v1\management\infrastructure\network\NodeContactController;
use App\Http\Controllers\v1\management\infrastructure\equipments\TypesController;
use App\Http\Controllers\v1\management\infrastructure\equipments\BrandsController;
use App\Http\Controllers\v1\management\infrastructure\equipments\ModelsController;
use App\Http\Controllers\v1\management\infrastructure\network\EquipmentController;
use App\Http\Controllers\v1\management\infrastructure\equipments\InventoryController;

Route::prefix('v1/infrastructure')
    ->middleware(['auth:sanctum'])
    ->group(function () {

        //      Network
        Route::group(['prefix' => 'network'], function () {
            //      Auth Server
            Route::group(['prefix' => 'servers'], function () {
                Route::post('/', [AuthServersController::class, 'store']);
                Route::post('data', [AuthServersController::class, 'data']);
                Route::post('edit', [AuthServersController::class, 'edit']);
                Route::put('{id}', [AuthServersController::class, 'update']);
            });

            //      Nodes
            Route::group(['prefix' => 'nodes'], function () {
                Route::post('/', [NodesController::class, 'store']);
                Route::post('data', [NodesController::class, 'data']);
                Route::post('edit', [NodesController::class, 'edit']);
                Route::put('{id}', [NodesController::class, 'update']);

                //      Contacts
                Route::group(['prefix' => 'contacts'], function () {
                    Route::post('/', [NodeContactController::class, 'store']);
                    Route::post('data', [NodeContactController::class, 'data']);
                    Route::post('edit', [NodeContactController::class, 'edit']);
                    Route::put('{id}', [NodeContactController::class, 'update']);
                });
            });
        });

        //      Equipments
        Route::group(['prefix' => 'equipment'], function () {
            Route::post('/', [EquipmentController::class, 'store']);
            Route::post('data', [EquipmentController::class, 'data']);
            Route::post('edit', [EquipmentController::class, 'edit']);
            Route::put('{id}', [EquipmentController::class, 'update']);

            //      Types
            Route::group(['prefix' => 'types'], function () {
                Route::post('/', [TypesController::class, 'store']);
                Route::post('data', [TypesController::class, 'data']);
                Route::post('edit', [TypesController::class, 'edit']);
                Route::put('{id}', [TypesController::class, 'update']);
            });

            //      Brands
            Route::group(['prefix' => 'brands'], function () {
                Route::post('/', [BrandsController::class, 'store']);
                Route::post('data', [BrandsController::class, 'data']);
                Route::post('edit', [BrandsController::class, 'edit']);
                Route::put('{id}', [BrandsController::class, 'update']);
            });

            //      Models
            Route::group(['prefix' => 'models'], function () {
                Route::post('/', [ModelsController::class, 'store']);
                Route::post('data', [ModelsController::class, 'data']);
                Route::post('edit', [ModelsController::class, 'edit']);
                Route::put('{id}', [ModelsController::class, 'update']);
            });

            //      Inventory
            Route::group(['prefix' => 'inventory'], function () {
                Route::post('/', [InventoryController::class, 'store']);
                Route::post('data', [InventoryController::class, 'data']);
                Route::post('edit', [InventoryController::class, 'read']);
                Route::put('{id}', [InventoryController::class, 'update']);
                Route::post('logs', [InventoryController::class, 'logs']);
                Route::post('internet/search', [InventoryController::class, 'internet_search']);
                Route::post('iptv/search', [InventoryController::class, 'iptv_search']);
                Route::post('search', [InventoryController::class, 'sales_search']);
                Route::post('sale', [InventoryController::class, 'sell_device']);
            });
        });
    });
