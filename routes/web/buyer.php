<?php

use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['auth', 'verified', 'active', 'role:role.buyer']], function () {
    Route::prefix('buyer')->group(function () {
        Route::name('buyer.')->group(function () {

            Route::get('/dashboard', function () {
                return view('buyer.dashboard');
            })->name('dashboard');

            Route::get('/profile', [App\Http\Controllers\Buyer\Profile\ProfileController::class, 'show'])->name('profile.show');
            Route::patch('/profile', [App\Http\Controllers\Buyer\Profile\ProfileController::class, 'update'])->name('profile.update');
            Route::patch('/profile/password', [App\Http\Controllers\Buyer\Profile\ProfileController::class, 'updatePassword'])->name('profile.update.password');

            Route::post('products/add/{product}/carts', [App\Http\Controllers\Buyer\Product\ProductCartController::class, 'add'])->name('products.add.to.cart');
            Route::post('products/less/{product}/carts', [App\Http\Controllers\Buyer\Product\ProductCartController::class, 'less'])->name('products.less.to.cart');
            Route::delete('products/{product}/carts', [App\Http\Controllers\Buyer\Product\ProductCartController::class, 'destroy'])->name('products.carts.destroy');

            Route::get('cart', [App\Http\Controllers\Buyer\Cart\CartController::class, 'index'])->name('cart.index');

            Route::get('checkout', [App\Http\Controllers\Buyer\Checkout\CheckoutController::class, 'create'])->name('checkout.create');

            Route::post('checkout', [App\Http\Controllers\Buyer\Checkout\CheckoutController::class, 'store'])->name('checkout.store');

            Route::get('checkout/result/transaction', App\Http\Controllers\Buyer\Checkout\CheckoutResultController::class)->name('checkout.result');

            Route::get('cart/pay', [App\Http\Controllers\Buyer\Cart\CartController::class, 'pay'])->name('cart.pay');

            Route::get('placetopay/payment/response', [App\Http\Controllers\Buyer\Payment\PlaceToPayController::class, 'processResponse'])->name('placetopay.payment.response');

            Route::get('placetopay/payment/cancelled', [App\Http\Controllers\Buyer\Payment\PlaceToPayController::class, 'abortSession'])->name('placetopay.payment.cancelled');

        });
    });
});
