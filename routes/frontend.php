<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\frontend\HomeController;

Route::get('/',[HomeController::class,'index'])->name('index');
Route::get('products/{product:slug}', [HomeController::class, 'show'])
     ->name('products.show');
