<?php

use App\Http\Controllers\Admin\Export\ExportController;
use App\Http\Controllers\Admin\Product\ProductController;
use App\Http\Controllers\Admin\Product\ProductExportController;
use App\Http\Controllers\Admin\Profile\ProfileController;
use App\Http\Controllers\Admin\User\UserController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['auth', 'verified', 'active', 'role:role.administrator']], function () {
    Route::prefix('admin')->group(function () {
        Route::name('admin.')->group(function () {

            Route::get('dashboard', function () {
                return view('admin.dashboard');
            })->name('dashboard');

            Route::get('profile', [ProfileController::class, 'show'])->name('profile.show');
            Route::patch('profile', [ProfileController::class, 'update'])->name('profile.update');
            Route::patch('profile/password', [ProfileController::class, 'updatePassword'])->name('profile.update.password');

            Route::resource('users', UserController::class)->only(['index', 'edit', 'update']);

            Route::get('products/export', ProductExportController::class);

            Route::resource('products', ProductController::class);

            Route::get('exports', [ExportController::class, 'index'])->name('exports.index');
        });
    });
});
