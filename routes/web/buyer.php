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

            Route::post('products/add/{product}/carts', [\App\Http\Controllers\Buyer\Product\ProductCartController::class, 'add'])->name('products.add.to.cart');
            Route::post('products/less/{product}/carts', [\App\Http\Controllers\Buyer\Product\ProductCartController::class, 'less'])->name('products.less.to.cart');
            Route::delete('products/{product}/carts', [\App\Http\Controllers\Buyer\Product\ProductCartController::class, 'destroy'])->name('products.carts.destroy');

            Route::get('cart', [\App\Http\Controllers\Buyer\Cart\CartController::class, 'index'])->name('cart.index');

            Route::get('checkout', [\App\Http\Controllers\Buyer\Checkout\CheckoutController::class, 'create'])->name('checkout.create');

            Route::post('checkout', [\App\Http\Controllers\Buyer\Checkout\CheckoutController::class, 'store'])->name('checkout.store');

            Route::get('cart/pay', [\App\Http\Controllers\Buyer\Cart\CartController::class, 'pay'])->name('cart.pay');

            Route::get('payment/response', function (){return "Payment response";})->name('payment.response');

            Route::get('payment/cancelled', function (){return "Payment cancelled";})->name('payment.cancelled');

        });
    });
});
