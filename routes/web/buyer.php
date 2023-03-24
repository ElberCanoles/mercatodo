<?php

use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['auth', 'verified', 'role:buyer']], function () {

    Route::prefix('buyer')->group(function () {

        Route::name('buyer.')->group(function () {

            Route::get('/dashboard', function () {
                return view('dashboard');
            })->name('dashboard');

            Route::get('/profile', [App\Http\Controllers\Buyer\ProfileController::class, 'profile'])->name('profile');
        });
    });
});
