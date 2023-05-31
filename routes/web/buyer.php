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

            Route::get('checkout', [\App\Http\Controllers\Buyer\Checkout\CheckoutController::class, 'create'])->name('checkout.create');

            Route::post('checkout', [\App\Http\Controllers\Buyer\Checkout\CheckoutController::class, 'store'])->name('checkout.store');

            Route::get('cart/pay', [App\Http\Controllers\Guest\Cart\CartController::class, 'pay'])->name('cart.pay');

            Route::get('payment/response', function (){return "Payment response";})->name('payment.response');

            Route::get('payment/cancelled', function (){return "Payment cancelled";})->name('payment.cancelled');

        });
    });
});
