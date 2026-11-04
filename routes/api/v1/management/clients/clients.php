<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\v1\management\clients\ClientsController;
use App\Http\Controllers\v1\management\clients\DocumentsController;
use App\Http\Controllers\v1\management\clients\PhonesController;
use App\Http\Controllers\v1\management\clients\EmailsController;
use App\Http\Controllers\v1\management\clients\AddressesController;
use App\Http\Controllers\v1\management\clients\ReferencesController;
use App\Http\Controllers\v1\management\clients\FinancialInformationController;
use App\Http\Controllers\v1\management\clients\ContractsController;

Route::prefix('v1/clients')
    ->middleware(['auth:sanctum'])
    ->group(function () {

        Route::post('/', [ClientsController::class, 'store']);
        Route::post('data', [ClientsController::class, 'data']);
        Route::post('edit', [ClientsController::class, 'edit']);
        Route::put('{id}', [ClientsController::class, 'update']);
        Route::post('search', [ClientsController::class, 'searchByName']);
        Route::get('{id}/branch', [ClientsController::class, 'clientBranch']);
        Route::get('search/{id}', [ClientsController::class, 'searchById']);

        //      PERSONAL DOCUMENTS
        Route::group(['prefix' => 'documents'], function () {
            Route::post('/', [DocumentsController::class, 'store']);
            Route::post('data', [DocumentsController::class, 'data']);
            Route::post('edit', [DocumentsController::class, 'edit']);
            Route::put('{id}', [DocumentsController::class, 'update']);
            Route::post('verify', [DocumentsController::class, 'verify']);
        });

        //      PHONES
        Route::group(['prefix' => 'phones'], function () {
            Route::post('/', [PhonesController::class, 'store']);
            Route::post('data', [PhonesController::class, 'data']);
            Route::post('edit', [PhonesController::class, 'edit']);
            Route::put('{id}', [PhonesController::class, 'update']);
        });

        //      EMAILS
        Route::group(['prefix' => 'emails'], function () {
            Route::post('/', [EmailsController::class, 'store']);
            Route::post('data', [EmailsController::class, 'data']);
            Route::post('edit', [EmailsController::class, 'edit']);
            Route::put('{id}', [EmailsController::class, 'update']);
        });

        //      ADDRESSES
        Route::group(['prefix' => 'addresses'], function () {
            Route::post('/', [AddressesController::class, 'store']);
            Route::post('data', [AddressesController::class, 'data']);
            Route::post('edit', [AddressesController::class, 'edit']);
            Route::put('{id}', [AddressesController::class, 'update']);
        });

        //      REFERENCES
        Route::group(['prefix' => 'references'], function () {
            Route::post('/', [ReferencesController::class, 'store']);
            Route::post('data', [ReferencesController::class, 'data']);
            Route::post('edit', [ReferencesController::class, 'edit']);
            Route::put('{id}', [ReferencesController::class, 'update']);
        });

        //      Financial Information
        Route::group(['prefix' => 'financial/information'], function () {
            Route::post('/', [FinancialInformationController::class, 'store']);
            Route::post('data', [FinancialInformationController::class, 'data']);
            Route::post('edit', [FinancialInformationController::class, 'edit']);
            Route::put('{id}', [FinancialInformationController::class, 'update']);
        });

        //      Contracts
        Route::group(['prefix' => 'contracts'], function () {
            Route::post('/', [ContractsController::class, 'store']);
            Route::post('data', [ContractsController::class, 'data']);
            Route::post('edit', [ContractsController::class, 'edit']);
            Route::put('{id}', [ContractsController::class, 'update']);
            Route::get('print/{id}', [ContractsController::class, 'print']);
        });

        Route::get('import', [ClientsController::class, 'importClients']);
    });
