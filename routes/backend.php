<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\backend\DashboardController;
use App\Http\Controllers\backend\ProductController;

Route::get('dashboard',[DashboardController::class,'dashboard'])->name('dashboard');
Route::resource('products',ProductController::class);
