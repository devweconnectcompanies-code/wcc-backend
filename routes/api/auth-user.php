<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\v1\management\Auth\AuthController;

Route::get('user', [AuthController::class, 'user'])->middleware('auth:sanctum');
