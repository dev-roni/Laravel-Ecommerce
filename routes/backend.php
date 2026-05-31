<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\backend\DashboardController;
use App\Http\Controllers\backend\ProductController;
use App\Http\Controllers\backend\ProductVariantController;
use App\Http\Controllers\backend\CategoryController;
use App\Http\Controllers\backend\AttributeController;
use App\Http\Controllers\backend\OrderController;

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

    // Admin group-এর ভেতরে
    Route::resource('orders',OrderController::class);
    Route::post('orders/{order}/status', [OrderController::class, 'updateStatus'])->name('orders.status');
    Route::post('orders/{order}/payment', [OrderController::class, 'updatePayment'])->name('orders.payment');
});