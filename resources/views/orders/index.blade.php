@extends('layouts.app')

@section('content')
    <div class="container">

        <!-- Tiêu đề -->
        <div class="bg-primary text-white p-4 rounded-3 shadow-sm mb-4">
            <h1 class="mb-0 fs-3 fw-bold">Hệ Thống Đơn Hàng</h1>
        </div>

        <a href="{{ route('orders.create') }}" class="btn btn-success mb-3">
            + Tạo đơn hàng mới
        </a>

        <div class="card shadow-sm">
            <div class="card-body p-0">
                <table class="table table-hover mb-0">
                    <thead class="table-light border-bottom">
                        <tr>
                            <th>ID</th>
                            <th>Khách hàng</th>
                            <th>Tổng tiền</th>
                            <th>Trạng thái</th>
                            <th>Ngày tạo</th>
                            <th class="text-center">Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($orders as $order)
                            <tr>
                                <td><strong>#{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}</strong></td>
                                <td>{{ $order->customer_name }}</td>
                                <td class="fw-bold text-end">{{ number_format($order->total_amount) }} ₫</td>
                                <td>
                                    @if ($order->status == 'pending')
                                        <span class="badge bg-warning">Pending</span>
                                    @elseif($order->status == 'processing')
                                        <span class="badge bg-info">Processing</span>
                                    @elseif($order->status == 'completed')
                                        <span class="badge bg-success">Completed</span>
                                    @endif
                                </td>
                                <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
                                <td class="text-center">
                                    <a href="{{ route('orders.show', $order) }}"
                                        class="btn btn-info btn-sm rounded-pill">Chi tiết</a>
                                    <form action="{{ route('orders.destroy', $order) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm rounded-pill"
                                            onclick="return confirm('Xác nhận xóa đơn hàng này?')">
                                            Xóa
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-4">Chưa có đơn hàng nào.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="mt-3">
            {{ $orders->links() }}
        </div>

    </div>
@endsection
