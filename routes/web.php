<?php

use App\Http\Controllers\OrderController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('orders.index');
});

Route::resource('orders', OrderController::class);

// Route cập nhật trạng thái đơn hàng
Route::patch('orders/{order}/status', [OrderController::class, 'updateStatus'])->name('orders.updateStatus');
