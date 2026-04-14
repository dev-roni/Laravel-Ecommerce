<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\backend\DashboardController;
use App\Http\Controllers\backend\ProductController;
use App\Http\Controllers\backend\CategoryController;

Route::get('dashboard',[DashboardController::class,'dashboard'])->name('dashboard');
Route::resource('products',ProductController::class);
Route::post('categories/reorder', [CategoryController::class, 'reorder'])->name('categories.reorder');
Route::resource('categories',CategoryController::class);
Route::get('categories/root-data', [CategoryController::class, 'rootData']);
Route::get('categories/{category}/children', [CategoryController::class, 'children'])->name('categories.children');
