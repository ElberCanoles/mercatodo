<?php

use App\Http\Controllers\Buyer\Cart\CartController;
use App\Http\Controllers\Buyer\Checkout\CheckoutController;
use App\Http\Controllers\Buyer\Checkout\CheckoutResultController;
use App\Http\Controllers\Buyer\Order\OrderController;
use App\Http\Controllers\Buyer\Order\OrderRetryPaymentController;
use App\Http\Controllers\Buyer\Payment\PlaceToPayController;
use App\Http\Controllers\Buyer\Product\ProductCartController;
use App\Http\Controllers\Buyer\Profile\ProfileController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['auth', 'verified', 'active', 'role:role.buyer']], function () {
    Route::prefix('buyer')->group(function () {
        Route::name('buyer.')->group(function () {

            Route::get('dashboard', function () {
                return view('buyer.dashboard');
            })->name('dashboard');

            Route::get('profile', [ProfileController::class, 'show'])->name('profile.show');
            Route::patch('profile', [ProfileController::class, 'update'])->name('profile.update');
            Route::patch('profile/password', [ProfileController::class, 'updatePassword'])->name('profile.update.password');

            Route::post('products/add/{product}/carts', [ProductCartController::class, 'add'])->name('products.add.to.cart');
            Route::post('products/less/{product}/carts', [ProductCartController::class, 'less'])->name('products.less.to.cart');
            Route::delete('products/{product}/carts', [ProductCartController::class, 'destroy'])->name('products.carts.destroy');

            Route::resource('orders', OrderController::class)->only(['index', 'show']);

            Route::get('orders/{order}/retry-payment', OrderRetryPaymentController::class)->name('orders.retry.payment');

            Route::get('cart', [CartController::class, 'index'])->name('cart.index');

            Route::get('checkout', [CheckoutController::class, 'create'])->name('checkout.create');

            Route::post('checkout', [CheckoutController::class, 'store'])->name('checkout.store');

            Route::get('checkout/result/transaction', CheckoutResultController::class)->name('checkout.result')->middleware('signed');;

            Route::get('cart/pay', [CartController::class, 'pay'])->name('cart.pay');

            Route::get('placetopay/payment/response', [PlaceToPayController::class, 'processResponse'])->name('placetopay.payment.response')->middleware('signed');;

            Route::get('placetopay/payment/cancelled', [PlaceToPayController::class, 'abortSession'])->name('placetopay.payment.cancelled')->middleware('signed');;
        });
    });
});
