<?php

use App\Http\Controllers\Api\Auth\LoginController;
use App\Http\Controllers\Api\Supplier\ItemController;
use App\Http\Controllers\Api\Supplier\PublicController;
use Illuminate\Support\Facades\Route;

Route::post('login', [LoginController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('logout', [LoginController::class, 'logout']);
    Route::apiResources([
        'items' => ItemController::class
    ]);

    Route::get('categories', [PublicController::class, 'categories']);
});
