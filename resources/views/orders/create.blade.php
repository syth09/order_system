@extends('layouts.app')

@section('content')
    <h1>Tạo Đơn hàng Mới</h1>

    <form action="{{ route('orders.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label class="form-label">Tên khách hàng</label>
            <input type="text" name="customer_name" class="form-control @error('customer_name') is-invalid @enderror"
                value="{{ old('customer_name') }}" required>
            @error('customer_name')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <h5>Sản phẩm</h5>
        <div id="products-container">
            <div class="product-row mb-3 border p-3 rounded" id="product-row-0">
                <div class="row">
                    <div class="col-md-5">
                        <input type="text" name="products[0][name]" placeholder="Tên sản phẩm" class="form-control"
                            required>
                    </div>
                    <div class="col-md-2">
                        <input type="number" name="products[0][quantity]" placeholder="Số lượng" class="form-control"
                            min="1" value="1" required>
                    </div>
                    <div class="col-md-3">
                        <input type="number" name="products[0][price]" placeholder="Giá" step="1000"
                            class="form-control" min="0" required>
                    </div>
                    <div class="col-md-2">
                        <button type="button" class="btn btn-danger btn-sm remove-row">Xóa</button>
                    </div>
                </div>
            </div>
        </div>

        <button type="button" id="add-product" class="btn btn-success mb-3">+ Thêm sản phẩm</button>

        <button type="submit" class="btn btn-primary">Tạo đơn hàng</button>
    </form>

    <script>
        let rowIndex = 1;

        document.getElementById('add-product').addEventListener('click', function() {
            const container = document.getElementById('products-container');
            const newRow = document.createElement('div');
            newRow.className = 'product-row mb-3 border p-3 rounded';
            newRow.id = `product-row-${rowIndex}`;
            newRow.innerHTML = `
        <div class="row">
            <div class="col-md-5">
                <input type="text" name="products[${rowIndex}][name]" placeholder="Tên sản phẩm" class="form-control" required>
            </div>
            <div class="col-md-2">
                <input type="number" name="products[${rowIndex}][quantity]" placeholder="Số lượng" class="form-control" min="1" value="1" required>
            </div>
            <div class="col-md-3">
                <input type="number" name="products[${rowIndex}][price]" placeholder="Giá" step="1000" class="form-control" min="0" required>
            </div>
            <div class="col-md-2">
                <button type="button" class="btn btn-danger btn-sm remove-row">Xóa</button>
            </div>
        </div>
    `;
            container.appendChild(newRow);
            rowIndex++;
        });

        document.addEventListener('click', function(e) {
            if (e.target.classList.contains('remove-row')) {
                if (document.querySelectorAll('.product-row').length > 1) {
                    e.target.closest('.product-row').remove();
                } else {
                    alert('Đơn hàng phải có ít nhất 1 sản phẩm!');
                }
            }
        });
    </script>
@endsection
