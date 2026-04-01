@extends('layouts.app')

@section('content')
    <div class="container">

        <div class="bg-info text-white p-4 rounded-3 shadow-sm mb-4">
            <h1 class="mb-0 fs-3 fw-bold">Chi Tiết Đơn Hàng #{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}</h1>
        </div>

        <div class="row">
            <!-- Thông tin đơn hàng -->
            <div class="col-md-4">
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-light">
                        <strong>Thông tin đơn hàng</strong>
                    </div>
                    <div class="card-body">
                        <p><strong>Khách hàng:</strong> {{ $order->customer_name }}</p>
                        <p><strong>Tổng tiền:</strong> <span
                                class="fw-bold text-success">{{ number_format($order->total_amount) }} ₫</span></p>
                        <p><strong>Ngày tạo:</strong> {{ $order->created_at->format('d/m/Y H:i') }}</p>

                        <div class="mt-3">
                            <strong>Trạng thái hiện tại:</strong><br>
                            @if ($order->status == 'pending')
                                <span class="badge bg-warning fs-6">Pending</span>
                            @elseif($order->status == 'processing')
                                <span class="badge bg-info fs-6">Processing</span>
                            @else
                                <span class="badge bg-success fs-6">Completed</span>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Cập nhật trạng thái -->
                <div class="card shadow-sm">
                    <div class="card-header bg-light">
                        <strong>Cập nhật trạng thái</strong>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('orders.updateStatus', $order) }}">
                            @csrf
                            @method('PATCH')
                            <select name="status" class="form-select mb-3">
                                <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="processing" {{ $order->status == 'processing' ? 'selected' : '' }}>Processing
                                </option>
                                <option value="completed" {{ $order->status == 'completed' ? 'selected' : '' }}>Completed
                                </option>
                            </select>
                            <button type="submit" class="btn btn-primary w-100">Cập nhật trạng thái</button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Danh sách sản phẩm trong đơn -->
            <div class="col-md-8">
                <div class="card shadow-sm">
                    <div class="card-header bg-light">
                        <strong>Danh sách sản phẩm ({{ $order->items->count() }} món)</strong>
                    </div>
                    <div class="card-body p-0">
                        <table class="table mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Tên sản phẩm</th>
                                    <th class="text-end">Giá</th>
                                    <th class="text-center">Số lượng</th>
                                    <th class="text-end">Thành tiền</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($order->items as $item)
                                    <tr>
                                        <td>{{ $item->product_name }}</td>
                                        <td class="text-end">{{ number_format($item->price) }} ₫</td>
                                        <td class="text-center">{{ $item->quantity }}</td>
                                        <td class="text-end fw-bold">{{ number_format($item->subtotal) }} ₫</td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="table-light">
                                <tr>
                                    <th colspan="3" class="text-end">Tổng cộng:</th>
                                    <th class="text-end fw-bold">{{ number_format($order->total_amount) }} ₫</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-4">
            <a href="{{ route('orders.index') }}" class="btn btn-secondary">Quay lại danh sách đơn hàng</a>
        </div>

    </div>
@endsection
