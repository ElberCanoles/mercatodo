<?php

use Illuminate\Support\Facades\Route;

Route::get('products', [\App\Http\Controllers\Guest\Product\ProductController::class, 'index'])->name('products.index');
Route::get('products/{slug}', [\App\Http\Controllers\Guest\Product\ProductController::class, 'show'])->name('products.show');

Route::post('products/add/{product}/carts', [\App\Http\Controllers\Guest\Product\ProductCartController::class, 'add'])->name('products.add.to.cart');
Route::post('products/less/{product}/carts', [\App\Http\Controllers\Guest\Product\ProductCartController::class, 'less'])->name('products.less.to.cart');
Route::delete('products/{product}/carts/{cart}', [\App\Http\Controllers\Guest\Product\ProductCartController::class, 'destroy'])->name('products.carts.destroy');

Route::get('cart', function (){})->name('cart.index');
