<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\v1\management\imports\ImportController;


Route::prefix('v1/imports')
    ->middleware(['auth:sanctum'])
    ->group(function () {
        
    });
