<?php

use App\Http\Controllers\OrderController;
use Illuminate\Support\Facades\Route;

Route::get('/orders', function () {
    return view('orders.index');
});

Route::resource('orders', OrderController::class);

Route::resource('orders', OrderController::class)->except(['edit', 'update', 'destroy']);

Route::patch('/orders/{order}/status', [OrderController::class, 'updateStatus'])
    ->name('orders.status');
