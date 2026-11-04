<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\v1\management\dashboard\DashboardController;

Route::prefix('v1/dashboard')
    ->middleware(['auth:sanctum'])
    ->group(function () {
        Route::get('client-types', [DashboardController::class, 'clientsByType']);
        Route::get('resources', [DashboardController::class, 'systemResources']);
        Route::get('top-profiles', [DashboardController::class, 'topInternetProfiles']);
        Route::get('supports-data', [DashboardController::class, 'supportsByDay']);
        Route::get('invoices-stats', [DashboardController::class, 'invoiceStatusChart']);
        Route::group(['prefix' => 'stats'], function () {
            Route::get('active-clients', [DashboardController::class, 'statsActiveClients']);
            Route::get('active-services', [DashboardController::class, 'statsActiveServices']);
            Route::get('incomes', [DashboardController::class, 'statsMonthlyIncomes']);
            Route::get('pending-incomes', [DashboardController::class, 'statsMonthlyPendingIncomes']);
            Route::get('daily-payments', [DashboardController::class, 'dailyPayments']);
        });

        Route::get('interfaces/traffic', [DashboardController::class, 'interfaceTraffic']);
        Route::get('interfaces/list', [DashboardController::class, 'interfacesList']);
        Route::get('active/sessions', [DashboardController::class, 'activeSessions']);
    });
