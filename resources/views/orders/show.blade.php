@extends('layouts.app')

@section('content')
    <h1>Chi tiết Đơn hàng #{{ $order->id }}</h1>

    <p><strong>Khách hàng:</strong> {{ $order->customer_name }}</p>
    <p><strong>Trạng thái:</strong>
        <span
            class="badge
        @if ($order->status == 'pending') bg-warning
        @elseif($order->status == 'processing') bg-info
        @else bg-success @endif">
            {{ ucfirst($order->status) }}
        </span>
    </p>
    <p><strong>Tổng tiền:</strong> {{ number_format($order->total_amount) }} VNĐ</p>

    <h5>Danh sách sản phẩm</h5>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Sản phẩm</th>
                <th>Số lượng</th>
                <th>Đơn giá</th>
                <th>Thành tiền</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($order->items as $item)
                <tr>
                    <td>{{ $item->product_name }}</td>
                    <td>{{ $item->quantity }}</td>
                    <td>{{ number_format($item->price) }}</td>
                    <td>{{ number_format($item->subtotal) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <hr>

    <h5>Cập nhật trạng thái</h5>
    <form action="{{ route('orders.status', $order) }}" method="POST">
        @csrf
        @method('PATCH')
        <select name="status" class="form-select w-25 d-inline">
            <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Pending</option>
            <option value="processing" {{ $order->status == 'processing' ? 'selected' : '' }}>Processing</option>
            <option value="completed" {{ $order->status == 'completed' ? 'selected' : '' }}>Completed</option>
        </select>
        <button type="submit" class="btn btn-warning">Cập nhật</button>
    </form>

    <a href="{{ route('orders.index') }}" class="btn btn-secondary mt-3">Quay lại danh sách</a>
@endsection
