<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\frontend\ProfileController;
use App\Http\Controllers\frontend\ShopController;
use App\Http\Controllers\frontend\ProductReviewController;
use App\Http\Controllers\frontend\CartController;
use App\Http\Controllers\frontend\CheckoutController;
use App\Http\Controllers\frontend\orderController;
use App\Http\Controllers\frontend\PaymentController;

// ── Auth Routes (Breeze দেয়) ──
require __DIR__ . '/auth.php';

// ── Authenticated Routes ──
Route::middleware('auth')->group(function () {
    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::patch('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');

        // Cart
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
    Route::patch('/cart/{cartItem}', [CartController::class, 'update'])->name('cart.update');
    Route::delete('/cart/{cartItem}', [CartController::class, 'remove'])->name('cart.remove');

    // Checkout
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');

    //coupon apply/remove
    Route::post('cart/coupon/apply',  [CartController::class, 'applyCoupon'])->name('cart.coupon.apply');
    Route::post('cart/coupon/remove', [CartController::class, 'removeCoupon'])->name('cart.coupon.remove');

    //payment
     Route::get('payment/pending/{order}',
        [PaymentController::class, 'pending'])
        ->name('payment.pending');
    Route::post('payment/initiate/{order}',[PaymentController::class, 'initiate'])->name('payment.initiate');

    // Order success
    Route::get('/orders/{order}/success', [OrderController::class, 'success'])->name('orders.success');

    // Customer order history
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
    Route::post('/orders/{order}/cancel', [OrderController::class, 'cancel'])->name('orders.cancel');

    //product reviews
    Route::post('/products/{product}/reviews',[ProductReviewController::class, 'store'])->name('reviews.store');
    Route::delete('/reviews/{review}', [ProductReviewController::class, 'destroy'])->name('reviews.destroy');

    //invoice generate
    Route::get('orders/{order}/invoice',[OrderController::class, 'invoice'])->name('orders.invoice');

}); 

// routes — Public routes
Route::get('/', [ShopController::class, 'index'])->name('shop.index');
Route::get('/search', [ShopController::class, 'search'])->name('shop.search');
Route::get('/category/{slug}', [ShopController::class, 'category'])->name('shop.category');
Route::get('/product/{slug}', [ShopController::class, 'product'])->name('shop.product');

// SSLCommerz callback — auth middleware ছাড়া
Route::post('payment/success',  [PaymentController::class, 'success'])->name('sslc.success');
Route::post('payment/fail',     [PaymentController::class, 'fail'])->name('sslc.failure');
Route::post('payment/cancel',   [PaymentController::class, 'cancel'])->name('sslc.cancel');
Route::post('payment/ipn',      [PaymentController::class, 'ipn'])->name('sslc.ipn');
