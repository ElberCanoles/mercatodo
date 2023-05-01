<?php

use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['auth', 'verified', 'active', 'role:role.buyer']], function () {
    Route::prefix('buyer')->group(function () {
        Route::name('buyer.')->group(function () {
            Route::get('/dashboard', function () {
                return view('buyer.dashboard');
            })->name('dashboard');

            Route::get('/profile', [\App\Http\Controllers\Buyer\Profile\ProfileController::class, 'show'])->name('profile.show');
            Route::patch('/profile', [\App\Http\Controllers\Buyer\Profile\ProfileController::class, 'update'])->name('profile.update');
            Route::patch('/profile/password', [\App\Http\Controllers\Buyer\Profile\ProfileController::class, 'updatePassword'])->name('profile.update.password');

            Route::get('products', [\App\Http\Controllers\Buyer\Product\ProductController::class, 'index'])->name('products.index');
            Route::get('products/{slug}', [\App\Http\Controllers\Buyer\Product\ProductController::class, 'show'])->name('products.show');
        });
    });
});
