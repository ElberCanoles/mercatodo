<?php

use Illuminate\Support\Facades\Route;

Route::get('products', [\App\Http\Controllers\Guest\Product\ProductController::class, 'index'])->name('products.index');
Route::get('products/{slug}', [\App\Http\Controllers\Guest\Product\ProductController::class, 'show'])->name('products.show');

