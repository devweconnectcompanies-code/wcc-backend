<?php

use App\Http\Controllers\v1\management\billing\BillingController;
use App\Http\Controllers\v1\management\billing\ExtensionController;
use App\Http\Controllers\v1\management\billing\options\ActivitiesController;
use App\Http\Controllers\v1\management\billing\options\DiscountController;
use App\Http\Controllers\v1\management\billing\options\DocumentController;
use App\Http\Controllers\v1\management\billing\options\PaymentMethodsController;
use App\Http\Controllers\v1\management\billing\options\StatusesController;
use App\Http\Controllers\v1\management\billing\PaymentController;
use App\Http\Controllers\v1\management\billing\PrepaymentController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1/billing')
    ->middleware(['auth:sanctum'])
    ->group(function () {

        Route::post('data', [BillingController::class, 'data']);

        Route::group(['prefix' => 'invoices'], function () {
            Route::post('client', [BillingController::class, 'clientInvoices']);
            Route::get('print/{id}', [BillingController::class, 'printInvoice']);
            Route::get('{id}/due-date', [BillingController::class, 'invoiceDueDate']);
            Route::get('client/{id}', [BillingController::class, 'getClientPendingInvoices']);
            Route::post('recalculate', [BillingController::class, 'recalculateInvoice']);
        });

        //      Options
        Route::group(['prefix' => 'options'], function () {

            //      Document Types
            Route::group(['prefix' => 'documents'], function () {
                Route::post('/', [DocumentController::class, 'store']);
                Route::post('/data', [DocumentController::class, 'data']);
                Route::post('/edit', [DocumentController::class, 'edit']);
                Route::put('{id}', [DocumentController::class, 'update']);
            });

            //      Activities
            Route::group(['prefix' => 'activities'], function () {
                Route::post('/', [ActivitiesController::class, 'store']);
                Route::post('data', [ActivitiesController::class, 'data']);
                Route::post('edit', [ActivitiesController::class, 'edit']);
                Route::put('{id}', [ActivitiesController::class, 'update']);
            });

            //      Statuses
            Route::group(['prefix' => 'statuses'], function () {
                Route::post('/', [StatusesController::class, 'store']);
                Route::post('data', [StatusesController::class, 'data']);
                Route::post('edit', [StatusesController::class, 'edit']);
                Route::put('{id}', [StatusesController::class, 'update']);
            });

            //      Discounts
            Route::group(['prefix' => 'discounts'], function () {
                Route::post('/', [DiscountController::class, 'store']);
                Route::post('data', [DiscountController::class, 'data']);
                Route::post('edit', [DiscountController::class, 'edit']);
                Route::put('{id}', [DiscountController::class, 'update']);
            });

            //      Payment Methods
            Route::group(['prefix' => 'payment'], function () {
                Route::group(['prefix' => 'methods'], function () {
                    Route::post('/', [PaymentMethodsController::class, 'store']);
                    Route::post('data', [PaymentMethodsController::class, 'data']);
                    Route::post('edit', [PaymentMethodsController::class, 'edit']);
                    Route::put('{id}', [PaymentMethodsController::class, 'update']);
                });
            });
        });

        //      Extensions
        Route::group(['prefix' => 'extensions'], function () {
            Route::post('/', [ExtensionController::class, 'store']);
            Route::post('data', [ExtensionController::class, 'invoiceExtensionList']);
            Route::get('{id}', [ExtensionController::class, 'read']);
            Route::put('{id}', [ExtensionController::class, 'update']);
        });

        //  Payments
        Route::group(['prefix' => 'payments'], function () {
            Route::post('/', [PaymentController::class, 'store']);
            Route::post('list', [PaymentController::class, 'paymentList']);
        });

        //  Prepayments
        Route::group(['prefix' => 'prepayments'], function () {
            Route::post('/', [PrepaymentController::class, 'store']);
            Route::post('list', [PrepaymentController::class, 'listByClient']);
            Route::post('data', [PrepaymentController::class, 'edit']);
            Route::put('{id}', [PrepaymentController::class, 'update']);
        });

    });
