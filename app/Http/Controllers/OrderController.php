<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Http\Requests\StoreOrderRequest;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::with('items')->latest()->paginate(10);
        return view('orders.index', compact('orders'));
    }

    public function create()
    {
        return view('orders.create');
    }

    public function store(StoreOrderRequest $request)
    {
        $order = Order::create([
            'customer_name' => $request->customer_name,
            'total_amount'  => 0,           // sẽ tính sau
            'status'        => 'pending'
        ]);

        $totalAmount = 0;

        foreach ($request->items as $item) {
            $subtotal = $item['price'] * $item['quantity'];
            $totalAmount += $subtotal;

            OrderItem::create([
                'order_id'     => $order->id,
                'product_name' => $item['product_name'],
                'price'        => $item['price'],
                'quantity'     => $item['quantity'],
                'subtotal'     => $subtotal,
            ]);
        }

        // Cập nhật tổng tiền
        $order->update(['total_amount' => $totalAmount]);

        return redirect()->route('orders.index')
            ->with('success', 'Tạo đơn hàng thành công!');
    }

    public function show(Order $order)
    {
        $order->load('items');
        return view('orders.show', compact('order'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:pending,processing,completed'
        ]);

        $order->update(['status' => $request->status]);

        return redirect()->route('orders.show', $order)
            ->with('success', 'Cập nhật trạng thái đơn hàng thành công!');
    }

    public function destroy(Order $order)
    {
        $order->delete();
        return redirect()->route('orders.index')
            ->with('success', 'Xóa đơn hàng thành công!');
    }
}
