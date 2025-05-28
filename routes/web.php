<?php

use Illuminate\Support\Facades\Route;


Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/{id}', [App\Http\Controllers\HomeController::class, 'detail'])->name('detail-product');
Route::post('/payment', [App\Http\Controllers\HomeController::class, 'payment'])->name('payment');
