<?php

use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['auth', 'verified', 'role:administrator']], function () {

    Route::prefix('admin')->group(function () {

        Route::name('admin.')->group(function () {

            Route::get('/dashboard', function () {
                return view('dashboard');
            })->name('dashboard');

            Route::get('/profile', [App\Http\Controllers\Admin\ProfileController::class, 'profile'])->name('profile');
        });
    });
});
