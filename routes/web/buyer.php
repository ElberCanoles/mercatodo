<?php

use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['auth', 'verified', 'active', 'role:role.buyer']], function () {

    Route::prefix('buyer')->group(function () {

        Route::name('buyer.')->group(function () {

            Route::get('/dashboard', function () {
                return view('buyer.dashboard');
            })->name('dashboard');

            Route::get('/profile', [App\Http\Controllers\Buyer\ProfileController::class, 'show'])->name('profile.show');
            Route::patch('/profile', [App\Http\Controllers\Buyer\ProfileController::class, 'update'])->name('profile.update');
            Route::patch('/profile/password', [App\Http\Controllers\Buyer\ProfileController::class, 'updatePassword'])->name('profile.update.password');
        });
    });
});
