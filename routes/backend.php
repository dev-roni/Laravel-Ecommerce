<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\backend\DashboardController;
use App\Http\Controllers\backend\ProductController;
use App\Http\Controllers\backend\ProductVariantController;
use App\Http\Controllers\backend\CategoryController;
use App\Http\Controllers\backend\AttributeController;
use App\Http\Controllers\backend\OrderController;
use App\Http\Controllers\backend\ProductReviewController;
use App\Http\Controllers\backend\CouponController;
use App\Http\Controllers\backend\UserController;

// ── Auth Routes (Breeze দেয়) ──
require __DIR__ . '/auth.php';

Route::middleware(['auth','admin'])->group(function(){
    //dashboard
    Route::get('dashboard',[DashboardController::class,'dashboard'])->name('dashboard');

    //Product
    Route::resource('products',ProductController::class);
    Route::delete('product-images/{image}',[ProductController::class, 'destroyImage'])->name('products.images.destroy');
    Route::post('product-images/{image}/primary',[ProductController::class, 'setPrimaryImage'])->name('products.images.primary');
    Route::post('products/{product}/toggle',[ProductController::class, 'toggleActive'])->name('products.toggle');

    //product varient manage
    Route::put('products/{product}/variants/{variant}',[ProductVariantController::class, 'update'])->name('products.variants.update');
    Route::delete('products/{product}/variants/{variant}',[ProductVariantController::class, 'destroy'])->name('products.variants.destroy');
    Route::post('products/{product}/variants',[ProductVariantController::class, 'store'])->name('products.variants.store');
    Route::post('products/{product}/variants/{variant}/image',[ProductVariantController::class, 'updateImage'])->name('products.variants.image');
    Route::delete('products/{product}/variants/{variant}/image',[ProductVariantController::class, 'destroyImage'])->name('products.variants.image.destroy');

    //attribute/product varient value like color
    Route::resource('attributes',AttributeController::class);
    Route::post('/attributes-value-store/{attribute}/values',[AttributeController::class, 'storeValue'])->name('attributes.values.store');
    Route::delete('/attributes-value-delete/values/{value}',[AttributeController::class, 'destroyValue'])->name('attributes.values.destroy');

    //Categories
    Route::post('categories/reorder', [CategoryController::class, 'reorder'])->name('categories.reorder');
    Route::resource('categories',CategoryController::class);
    Route::get('categories/root-data', [CategoryController::class, 'rootData']);
    Route::get('categories/{category}/children', [CategoryController::class, 'children'])->name('categories.children');

    // Orders
    Route::resource('orders',OrderController::class);
    Route::post('orders/{order}/status', [OrderController::class, 'updateStatus'])->name('orders.status');
    Route::post('orders/{order}/payment', [OrderController::class, 'updatePayment'])->name('orders.payment');

    //prduct review / comment
    Route::get('reviews', [ProductReviewController::class, 'index'])->name('reviews.index');
    Route::post('reviews/{review}/approve',[ProductReviewController::class, 'approve'])->name('reviews.approve');
    Route::delete('reviews/{review}',[ProductReviewController::class, 'destroy'])->name('reviews.destroy');

    //coupon
    Route::resource('coupons', CouponController::class)->except(['show']);
    Route::post('coupons/{coupon}/toggle', [CouponController::class, 'toggleActive'])->name('coupons.toggle');

    //user management
    Route::get('users', [Admin\UserController::class, 'index'])->name('users.index');
    Route::get('users/{user}', [Admin\UserController::class, 'show'])->name('users.show');
    Route::post('users/{user}/ban', [Admin\UserController::class, 'toggleBan'])->name('users.ban');
    Route::delete('users/{user}', [Admin\UserController::class, 'destroy'])->name('users.destroy');

    // Admin management
    Route::get('admins', [Admin\UserController::class, 'admins'])->name('users.admins');
    Route::get('admins/create', [Admin\UserController::class, 'createAdmin'])->name('users.admins.create');
    Route::post('admins', [Admin\UserController::class, 'storeAdmin'])->name('users.admins.store');

});