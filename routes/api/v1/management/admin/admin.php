<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\v1\management\Admin\UsersController;
use App\Http\Controllers\v1\management\Admin\Users\RolesController;
use App\Http\Controllers\v1\management\Admin\Users\PermissionsController;
use App\Http\Controllers\v1\management\Admin\Profiles\InternetController;
use App\Http\Controllers\v1\management\Admin\Users\TechniciansController;

Route::prefix('v1/management')
    ->middleware(['auth:sanctum'])
    ->group(function () {
        //      USERS
        Route::group(['prefix' => 'users'], function () {
            Route::post('/', [UsersController::class, 'store']);
            Route::post('data', [UsersController::class, 'data']);
            Route::post('edit', [UsersController::class, 'edit']);
            Route::put('{id}', [UsersController::class, 'update']);
        });

        //      Roles
        Route::group(['prefix' => 'roles'], function () {
            Route::post('/', [RolesController::class, 'store']);
            Route::post('data', [RolesController::class, 'data']);
            Route::post('edit', [RolesController::class, 'edit']);
            Route::put('{id}', [RolesController::class, 'update']);
        });

        //      Permissions
        Route::group(['prefix' => 'permissions'], function () {
            Route::post('/', [PermissionsController::class, 'store']);
            Route::post('data', [PermissionsController::class, 'data']);
            Route::post('edit', [PermissionsController::class, 'edit']);
            Route::put('{id}', [PermissionsController::class, 'update']);
        });

        //      Technicians
        Route::group(['prefix' => 'technicians'], function () {
            Route::post('/', [TechniciansController::class, 'store']);
            Route::post('data', [TechniciansController::class, 'data']);
            Route::post('edit', [TechniciansController::class, 'edit']);
            Route::put('{id}', [TechniciansController::class, 'update']);
        });

        //      PROFILES
        Route::group(['prefix' => 'profiles'], function () {
            //      INTERNET
            Route::group(['prefix' => 'internet'], function () {
                Route::post('/', [InternetController::class, 'store']);
                Route::post('data', [InternetController::class, 'data']);
                Route::post('edit', [InternetController::class, 'edit']);
                Route::put('{id}', [InternetController::class, 'update']);
            });
        });
    });
