<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\v1\management\configuration\ConfigurationController;
use App\Http\Controllers\v1\management\configuration\menu\MenuController;
use App\Http\Controllers\v1\management\configuration\geography\CountriesController;
use App\Http\Controllers\v1\management\configuration\geography\StatesController;
use App\Http\Controllers\v1\management\configuration\geography\MunicipalitiesController;
use App\Http\Controllers\v1\management\configuration\geography\DistrictsController;
use App\Http\Controllers\v1\management\configuration\branches\BranchesController;
use App\Http\Controllers\v1\management\configuration\clients\DocumentTypesController;
use App\Http\Controllers\v1\management\configuration\clients\GenderController;
use App\Http\Controllers\v1\management\configuration\clients\ClientTypeController;
use App\Http\Controllers\v1\management\configuration\clients\MaritalStatusController;
use App\Http\Controllers\v1\management\configuration\clients\PhoneTypeController;
use App\Http\Controllers\v1\management\configuration\clients\KinshipController;
use App\Http\Controllers\v1\management\configuration\clients\ContractsStatusController;
use App\Http\Controllers\v1\management\configuration\infrastructure\EquipmentStatusController;

Route::prefix('v1/configuration')
    ->middleware(['auth:sanctum'])
    ->group(function () {
        //      MENU
        Route::group(['prefix' => 'menu'], function () {
            Route::get('/', [ConfigurationController::class, 'getMenu']);
            Route::post('/', [MenuController::class, 'store']);
            Route::post('data', [MenuController::class, 'data']);
            Route::post('edit', [MenuController::class, 'edit']);
            Route::put('{id}', [MenuController::class, 'update']);
            Route::get('parents', [MenuController::class, 'getParents']);
        });

        //      Branches
        Route::group(['prefix' => 'branches'], function () {
            Route::post('/', [BranchesController::class, 'store']);
            Route::post('data', [BranchesController::class, 'data']);
            Route::post('edit', [BranchesController::class, 'edit']);
            Route::put('{id}', [BranchesController::class, 'update']);
        });

        //      Countries, States, Cities
        Route::group(['prefix' => 'geography'], function () {

            //      Countries
            Route::group(['prefix' => 'countries'], function () {
                Route::post('/', [CountriesController::class, 'create']);
                Route::post('data', [CountriesController::class, 'data']);
                Route::post('edit', [CountriesController::class, 'edit']);
                Route::put('{id}', [CountriesController::class, 'update']);
            });

            //      States
            Route::group(['prefix' => 'states'], function () {
                Route::post('/', [StatesController::class, 'create']);
                Route::post('data', [StatesController::class, 'data']);
                Route::post('edit', [StatesController::class, 'edit']);
                Route::put('{id}', [StatesController::class, 'update']);
            });

            //      Municipalities
            Route::group(['prefix' => 'municipalities'], function () {
                Route::post('/', [MunicipalitiesController::class, 'store']);
                Route::post('data', [MunicipalitiesController::class, 'data']);
                Route::post('edit', [MunicipalitiesController::class, 'edit']);
                Route::put('{id}', [MunicipalitiesController::class, 'update']);
            });

            //      Districts
            Route::group(['prefix' => 'districts'], function () {
                Route::post('/', [DistrictsController::class, 'store']);
                Route::post('data', [DistrictsController::class, 'data']);
                Route::post('edit', [DistrictsController::class, 'edit']);
                Route::put('{id}', [DistrictsController::class, 'update']);
            });
        });

        //      Clients
        Route::group(['prefix' => 'clients'], function () {

            //      Document Types
            Route::group(['prefix' => 'documents'], function () {
                Route::post('/', [DocumentTypesController::class, 'store']);
                Route::post('data', [DocumentTypesController::class, 'dataViewer']);
                Route::post('edit', [DocumentTypesController::class, 'edit']);
                Route::put('{id}', [DocumentTypesController::class, 'update']);
            });

            //      Genders
            Route::group(['prefix' => 'genders'], function () {
                Route::post('/', [GenderController::class, 'store']);
                Route::post('data', [GenderController::class, 'dataViewer']);
                Route::post('edit', [GenderController::class, 'edit']);
                Route::put('{id}', [GenderController::class, 'update']);
            });

            //     Marital Status
            Route::group(['prefix' => 'marital'], function () {
                Route::post('/', [MaritalStatusController::class, 'store']);
                Route::post('data', [MaritalStatusController::class, 'data']);
                Route::post('edit', [MaritalStatusController::class, 'edit']);
                Route::put('{id}', [MaritalStatusController::class, 'update']);
            });

            //      Types
            Route::group(['prefix' => 'types'], function () {
                Route::post('/', [ClientTypeController::class, 'store']);
                Route::post('data', [ClientTypeController::class, 'dataviewer']);
                Route::post('edit', [ClientTypeController::class, 'edit']);
                Route::put('{id}', [ClientTypeController::class, 'update']);
            });

            //      Phone Types
            Route::group(['prefix' => 'phones'], function () {
                Route::post('/', [PhoneTypeController::class, 'store']);
                Route::post('data', [PhoneTypeController::class, 'data']);
                Route::post('edit', [PhoneTypeController::class, 'edit']);
                Route::put('{id}', [PhoneTypeController::class, 'update']);
            });

            //      Kinship
            Route::group(['prefix' => 'kinship'], function () {
                Route::post('/', [KinshipController::class, 'store']);
                Route::post('data', [KinshipController::class, 'data']);
                Route::post('edit', [KinshipController::class, 'edit']);
                Route::put('{id}', [KinshipController::class, 'update']);
            });

            //      Contracts
            Route::group(['prefix' => 'contracts'], function () {
                Route::post('/', [ContractsStatusController::class, 'store']);
                Route::post('data', [ContractsStatusController::class, 'data']);
                Route::post('edit', [ContractsStatusController::class, 'edit']);
                Route::put('{id}', [ContractsStatusController::class, 'update']);
            });
        });

        //      Infrastructure
        Route::group(['prefix' => 'infrastructure'], function () {

            //      Equipments
            Route::group(['prefix' => 'equipments'], function () {

                //      Status
                Route::group(['prefix' => 'status'], function () {
                    Route::post('/', [EquipmentStatusController::class, 'store']);
                    Route::post('data', [EquipmentStatusController::class, 'data']);
                    Route::post('edit', [EquipmentStatusController::class, 'edit']);
                    Route::put('{id}', [EquipmentStatusController::class, 'update']);
                });
            });
        });
    });
