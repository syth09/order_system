<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::with('items')
            ->latest()
            ->paginate(10);

        return view('orders.index', compact('orders'));
    }

    public function create()
    {
        return view('orders.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_name' => 'required|string|max:255',
            'products' => 'required|array|min:1',
            'products.*.name' => 'required|string|max:255',
            'products.*.quantity' => 'required|integer|min:1',
            'products.*.price' => 'required|numeric|min:0',
        ], [
            'products.min' => 'Đơn hàng phải có ít nhất 1 sản phẩm.'
        ]);

        DB::beginTransaction();
        try {
            $order = Order::create([
                'customer_name' => $validated['customer_name'],
                'status' => 'pending',
                'total_amount' => 0,
            ]);

            $total = 0;
            foreach ($validated['products'] as $item) {
                $subtotal = $item['quantity'] * $item['price'];
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_name' => $item['name'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'subtotal' => $subtotal,
                ]);
                $total += $subtotal;
            }

            $order->total_amount = $total;
            $order->save();

            DB::commit();

            return redirect()->route('orders.index')
                ->with('success', 'Tạo đơn hàng thành công!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    public function show(Order $order)
    {
        $order->load('items');
        return view('orders.show', compact('order'));
    }

    public function updateStatus(Order $order, Request $request)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,processing,completed',
        ]);

        $order->update(['status' => $validated['status']]);

        return redirect()->route('orders.show', $order)
            ->with('success', 'Cập nhật trạng thái thành công!');
    }

    public function destroy(Order $order)
    {
        $order->delete();
        return redirect()->route('orders.index')
            ->with('success', 'Đơn hàng đã được xóa thành công!');
    }
}
