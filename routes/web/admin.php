<?php

use App\Domain\Users\Enums\Roles;
use App\Http\Controllers\Web\Admin\Export\ExportController;
use App\Http\Controllers\Web\Admin\Import\ImportController;
use App\Http\Controllers\Web\Admin\Product\ProductController;
use App\Http\Controllers\Web\Admin\Product\ProductExportController;
use App\Http\Controllers\Web\Admin\Product\ProductImportController;
use App\Http\Controllers\Web\Admin\Profile\ProfileController;
use App\Http\Controllers\Web\Admin\User\UserController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['auth', 'verified', 'active', 'role:' . Roles::ADMINISTRATOR->value]], function () {
    Route::prefix('admin')->group(function () {
        Route::name('admin.')->group(function () {
            Route::get('dashboard', function () {
                return view('admin.dashboard');
            })->name('dashboard');

            Route::get('profile', [ProfileController::class, 'show'])->name('profile.show');
            Route::patch('profile', [ProfileController::class, 'update'])->name('profile.update');
            Route::patch('profile/password', [ProfileController::class, 'updatePassword'])->name('profile.update.password');

            Route::resource('users', UserController::class)->only(['index', 'edit', 'update']);

            Route::get('products/export', ProductExportController::class)->name('products.export');

            Route::post('products/import', ProductImportController::class)->name('products.import');

            Route::resource('products', ProductController::class);

            Route::get('exports', [ExportController::class, 'index'])->name('exports.index');

            Route::resource('imports', ImportController::class)->only(['index', 'show']);
        });
    });
});
