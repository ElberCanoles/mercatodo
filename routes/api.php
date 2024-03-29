<?php

use App\Http\Controllers\Api\Auth\LoginController;
use App\Http\Controllers\Api\Product\ProductController;
use Illuminate\Support\Facades\Route;

Route::name('api.')->group(function () {
    Route::post('login', LoginController::class)->name('login');

    Route::middleware('auth:sanctum')->prefix('v1')->group(function () {
        Route::get('user', function () {
            return response()->json([
                'user' => request()->user()
            ]);
        });

        Route::apiResource('products', ProductController::class);
    });
});
