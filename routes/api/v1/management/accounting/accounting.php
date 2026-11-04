<?php

use App\Http\Controllers\v1\management\Accounting\AccountingController;
use App\Http\Controllers\v1\management\Accounting\DTE\DTEController;
use App\Http\Controllers\v1\management\Accounting\DTE\Options\EventController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1/accounting')
    ->middleware(['auth:sanctum'])
    ->group(function () {

        Route::post('/client/invoices', [AccountingController::class, 'clientInvoices']);

        //  DTEs
        Route::group(['prefix' => 'dte'], function () {
            Route::post('data', [DTEController::class, 'data']);
            Route::post('create/{documentId}', [DTEController::class, 'store']);
            Route::get('print/{dteId}', [DTEController::class, 'printDTE']);
            Route::post('search', [DTEController::class, 'search']);
            Route::post('resend/mail', [DTEController::class, 'resendMail']);
            Route::post('refund', [DTEController::class, 'refund']);
        });

        //  Options
        Route::group(['prefix' => 'options'], function () {

            //  Events
            Route::group(['prefix' => 'events'], function () {
                Route::post('data', [EventController::class, 'data']);
                Route::post('/', [EventController::class, 'store']);
                Route::post('edit', [EventController::class, 'edit']);
                Route::put('update', [EventController::class, 'update']);
            });
        });
    });
