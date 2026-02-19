<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\web\frontend\HomeController;

Route::get('/',[HomeController::class,'index'])->name('index');
