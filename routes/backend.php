<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\backend\DashboardController;
use App\Http\Controllers\backend\ProductController;
use App\Http\Controllers\backend\CategoryController;
use App\Http\Controllers\backend\AttributeController;

Route::get('dashboard',[DashboardController::class,'dashboard'])->name('dashboard');

//Product
Route::resource('products',ProductController::class);
Route::resource('attributes',AttributeController::class);
Route::post('/attributes-value-store/{attribute}/values',[AttributeController::class, 'storeValue'])->name('attributes.values.store');
Route::delete('/attributes-value-delete/values/{value}',[AttributeController::class, 'destroyValue'])->name('attributes.values.destroy');

//Categories
Route::post('categories/reorder', [CategoryController::class, 'reorder'])->name('categories.reorder');
Route::resource('categories',CategoryController::class);
Route::get('categories/root-data', [CategoryController::class, 'rootData']);
Route::get('categories/{category}/children', [CategoryController::class, 'children'])->name('categories.children');
