@extends('layouts.app')

@section('content')
    <div class="container mt-4">

        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="fw-bold text-white bg-primary px-4 py-3 rounded"
                    style="background: linear-gradient(135deg, #0d6efd, #0b5ed7);">
                    Quản Lý Đơn Hàng
                </h2>
            </div>
        </div>

        <!-- Toolbar -->
        <div class="row mb-3 align-items-center">
            <div class="col-md-6">
                <a href="{{ route('orders.create') }}" class="btn btn-primary btn-lg shadow-sm">
                    <i class="bi bi-plus-lg"></i> Thêm đơn hàng mới
                </a>
            </div>

            <div class="col-md-6">
                <div class="input-group">
                    <input type="text" id="searchInput" class="form-control" placeholder="Tìm theo tên khách hàng...">
                    <button class="btn btn-primary" type="button" onclick="searchTable()">
                        <i class="bi bi-search"></i> Tìm
                    </button>
                </div>
            </div>
        </div>

        <!-- Bảng dữ liệu -->
        <div class="card shadow">
            <div class="card-body p-0">
                <table class="table table-hover align-middle mb-0" id="ordersTable">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 80px;">ID</th>
                            <th>Họ tên khách hàng</th>
                            <th>Trạng thái</th>
                            <th class="text-end">Tổng tiền</th>
                            <th>Ngày tạo</th>
                            <th class="text-center" style="width: 220px;">Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($orders as $order)
                            <tr>
                                <td class="fw-bold">{{ $order->id }}</td>
                                <td>{{ $order->customer_name }}</td>
                                <td>
                                    @php
                                        $statusClass = match ($order->status) {
                                            'pending' => 'bg-warning text-dark',
                                            'processing' => 'bg-info text-white',
                                            'completed' => 'bg-success text-white',
                                            default => 'bg-secondary text-white',
                                        };
                                    @endphp
                                    <span class="badge {{ $statusClass }} px-3 py-2 fs-6">
                                        {{ ucfirst($order->status) }}
                                    </span>
                                </td>
                                <td class="text-end fw-bold">
                                    {{ number_format($order->total_amount) }} ₫
                                </td>
                                <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
                                <td class="text-center">
                                    <a href="{{ route('orders.show', $order) }}"
                                        class="btn btn-info btn-sm text-white px-3">
                                        Chi tiết
                                    </a>

                                    <button onclick="updateStatus({{ $order->id }})" class="btn btn-warning btn-sm px-3">
                                        Sửa
                                    </button>

                                    <form action="{{ route('orders.destroy', $order) }}" method="POST" class="d-inline"
                                        onsubmit="return confirm('Bạn có chắc muốn xóa đơn hàng này?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm px-3">Xóa</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-5 text-muted">
                                    Chưa có đơn hàng nào. Hãy tạo đơn hàng mới!
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Phân trang -->
        <div class="d-flex justify-content-end mt-3">
            {{ $orders->links() }}
        </div>

    </div>

    <!-- Modal cập nhật trạng thái nhanh -->
    <div class="modal fade" id="statusModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Cập nhật trạng thái đơn hàng</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="statusForm" method="POST">
                    @csrf
                    @method('PATCH')
                    <div class="modal-body">
                        <input type="hidden" name="order_id" id="modal_order_id">
                        <select name="status" id="modal_status" class="form-select form-select-lg">
                            <option value="pending">Pending</option>
                            <option value="processing">Processing</option>
                            <option value="completed">Completed</option>
                        </select>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                        <button type="submit" class="btn btn-primary">Cập nhật</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function searchTable() {
            let input = document.getElementById('searchInput').value.toLowerCase();
            let rows = document.querySelectorAll('#ordersTable tbody tr');

            rows.forEach(row => {
                let name = row.cells[1].textContent.toLowerCase();
                row.style.display = name.includes(input) ? '' : 'none';
            });
        }

        function updateStatus(orderId) {
            document.getElementById('modal_order_id').value = orderId;
            document.getElementById('statusForm').action = `/orders/${orderId}/status`;
            new bootstrap.Modal(document.getElementById('statusModal')).show();
        }
    </script>
@endpush
