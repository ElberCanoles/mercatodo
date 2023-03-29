<?php

use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['auth', 'verified', 'role:administrator']], function () {

    Route::prefix('admin')->group(function () {

        Route::name('admin.')->group(function () {

            Route::get('/dashboard', function () {
                return view('admin.dashboard');
            })->name('dashboard');

            Route::get('/profile', [App\Http\Controllers\Admin\ProfileController::class, 'show'])->name('profile.show');
            Route::post('/profile', [App\Http\Controllers\Admin\ProfileController::class, 'update'])->name('profile.update');
            Route::post('/profile/password', [App\Http\Controllers\Admin\ProfileController::class, 'updatePassword'])->name('profile.update.password');

            Route::resource('users', \App\Http\Controllers\Admin\User\UserController::class)->only(['index', 'edit', 'update']);
        });
    });
});
