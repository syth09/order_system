@extends('layouts.app')

@section('content')
    <div class="container">

        <div class="bg-primary text-white p-4 rounded-3 shadow-sm mb-4">
            <h1 class="mb-0 fs-3 fw-bold">Tạo Đơn Hàng Mới</h1>
        </div>

        <div class="card shadow-sm">
            <div class="card-body">

                <form method="POST" action="{{ route('orders.store') }}" id="orderForm">
                    @csrf

                    <div class="mb-4">
                        <label class="form-label fw-bold">Tên khách hàng</label>
                        <input type="text" name="customer_name" class="form-control" value="{{ old('customer_name') }}"
                            required>
                        @error('customer_name')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Danh sách sản phẩm động -->
                    <h5 class="mb-3">Danh sách sản phẩm</h5>
                    <div id="items-container">
                        <!-- Sản phẩm mẫu đầu tiên -->
                        <div class="item-row mb-3 border p-3 rounded">
                            <div class="row">
                                <div class="col-md-5">
                                    <input type="text" name="items[0][product_name]" class="form-control"
                                        placeholder="Tên sản phẩm" required>
                                </div>
                                <div class="col-md-2">
                                    <input type="number" step="0.01" name="items[0][price]" class="form-control"
                                        placeholder="Giá" required>
                                </div>
                                <div class="col-md-2">
                                    <input type="number" name="items[0][quantity]" class="form-control"
                                        placeholder="Số lượng" min="1" required>
                                </div>
                                <div class="col-md-2">
                                    <button type="button" class="btn btn-danger btn-sm w-100 remove-item">Xóa</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <button type="button" id="add-item" class="btn btn-secondary mb-4">
                        + Thêm sản phẩm
                    </button>

                    <div class="mt-4">
                        <button type="submit" class="btn btn-success px-4">Tạo đơn hàng</button>
                        <a href="{{ route('orders.index') }}" class="btn btn-secondary px-4">Hủy</a>
                    </div>
                </form>

            </div>
        </div>
    </div>

    <script>
        document.getElementById('add-item').addEventListener('click', function() {
            let container = document.getElementById('items-container');
            let index = container.children.length;

            let newRow = `
        <div class="item-row mb-3 border p-3 rounded">
            <div class="row">
                <div class="col-md-5">
                    <input type="text" name="items[${index}][product_name]" class="form-control"
                           placeholder="Tên sản phẩm" required>
                </div>
                <div class="col-md-2">
                    <input type="number" step="0.01" name="items[${index}][price]" class="form-control"
                           placeholder="Giá" required>
                </div>
                <div class="col-md-2">
                    <input type="number" name="items[${index}][quantity]" class="form-control"
                           placeholder="Số lượng" min="1" required>
                </div>
                <div class="col-md-2">
                    <button type="button" class="btn btn-danger btn-sm w-100 remove-item">Xóa</button>
                </div>
            </div>
        </div>`;

            container.insertAdjacentHTML('beforeend', newRow);
        });

        // Xóa dòng sản phẩm
        document.addEventListener('click', function(e) {
            if (e.target.classList.contains('remove-item')) {
                if (document.querySelectorAll('.item-row').length > 1) {
                    e.target.closest('.item-row').remove();
                } else {
                    alert("Đơn hàng phải có ít nhất 1 sản phẩm!");
                }
            }
        });
    </script>
@endsection
