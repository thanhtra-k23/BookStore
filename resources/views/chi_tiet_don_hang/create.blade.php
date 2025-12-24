@extends('layouts.app')

@section('title', $title)

@section('content')
<div class="container mt-4 mb-5">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('admin.chitietdonhang.index') }}" class="text-decoration-none">
                    <i class="fas fa-list-alt me-1"></i>
                    Chi tiết đơn hàng
                </a>
            </li>
            <li class="breadcrumb-item active">Thêm sản phẩm</li>
        </ol>
    </nav>

    <div class="row">
        <div class="col-lg-8">
            <div class="card card-modern">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-plus me-2"></i>
                        Thêm sản phẩm vào đơn hàng
                    </h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.chitietdonhang.store') }}">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label class="form-label">Đơn hàng <span class="text-danger">*</span></label>
                                <select name="don_hang_id" class="form-select @error('don_hang_id') is-invalid @enderror" 
                                        required onchange="loadOrderInfo(this.value)">
                                    <option value="">Chọn đơn hàng</option>
                                    @foreach($donHangs as $order)
                                        <option value="{{ $order->id }}" 
                                                {{ (old('don_hang_id', $selectedOrder?->id) == $order->id) ? 'selected' : '' }}
                                                data-customer="{{ $order->nguoiDung->ho_ten }}"
                                                data-status="{{ $order->trang_thai_text }}"
                                                data-total="{{ number_format($order->tong_tien, 0, ',', '.') }}">
                                            {{ $order->ma_don }} - {{ $order->nguoiDung->ho_ten }} 
                                            ({{ number_format($order->tong_tien, 0, ',', '.') }}đ)
                                        </option>
                                    @endforeach
                                </select>
                                @error('don_hang_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-12 mb-3">
                                <label class="form-label">Sách <span class="text-danger">*</span></label>
                                <select name="sach_id" class="form-select @error('sach_id') is-invalid @enderror" 
                                        required onchange="loadBookInfo(this.value)">
                                    <option value="">Chọn sách</option>
                                    @foreach($sachs as $book)
                                        <option value="{{ $book->id }}" 
                                                {{ old('sach_id') == $book->id ? 'selected' : '' }}
                                                data-price="{{ $book->gia_hien_tai }}"
                                                data-stock="{{ $book->so_luong_ton }}"
                                                data-author="{{ $book->tacGia->ten_tac_gia }}"
                                                data-category="{{ $book->theLoai->ten_the_loai }}">
                                            {{ $book->ten_sach }} - {{ $book->tacGia->ten_tac_gia }} 
                                            ({{ number_format($book->gia_hien_tai, 0, ',', '.') }}đ - Còn {{ $book->so_luong_ton }} cuốn)
                                        </option>
                                    @endforeach
                                </select>
                                @error('sach_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Số lượng <span class="text-danger">*</span></label>
                                <input type="number" name="so_luong" class="form-control @error('so_luong') is-invalid @enderror" 
                                       value="{{ old('so_luong', 1) }}" min="1" required onchange="calculateTotal()">
                                @error('so_luong')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">
                                    Số lượng tồn kho: <span id="stock-info">-</span>
                                </div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Giá bán tại thời điểm <span class="text-danger">*</span></label>
                                <input type="number" name="gia_ban_tai_thoi_diem" class="form-control @error('gia_ban_tai_thoi_diem') is-invalid @enderror" 
                                       value="{{ old('gia_ban_tai_thoi_diem') }}" min="0" step="1000" onchange="calculateTotal()">
                                @error('gia_ban_tai_thoi_diem')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">
                                    Để trống để sử dụng giá hiện tại
                                </div>
                            </div>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>
                                Thêm vào đơn hàng
                            </button>
                            <a href="{{ route('admin.chitietdonhang.index') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-times me-2"></i>
                                Hủy bỏ
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <!-- Order Info -->
            <div class="card card-modern mb-4">
                <div class="card-header">
                    <h6 class="mb-0">
                        <i class="fas fa-info-circle me-2"></i>
                        Thông tin đơn hàng
                    </h6>
                </div>
                <div class="card-body">
                    <div id="order-info" class="d-none">
                        <div class="mb-2">
                            <strong>Khách hàng:</strong>
                            <div id="customer-name" class="text-muted"></div>
                        </div>
                        <div class="mb-2">
                            <strong>Trạng thái:</strong>
                            <div id="order-status"></div>
                        </div>
                        <div class="mb-2">
                            <strong>Tổng tiền hiện tại:</strong>
                            <div id="current-total" class="text-success fw-bold"></div>
                        </div>
                    </div>
                    <div id="no-order-selected" class="text-muted text-center py-3">
                        <i class="fas fa-shopping-cart fa-2x mb-2"></i>
                        <div>Chọn đơn hàng để xem thông tin</div>
                    </div>
                </div>
            </div>

            <!-- Book Info -->
            <div class="card card-modern mb-4">
                <div class="card-header">
                    <h6 class="mb-0">
                        <i class="fas fa-book me-2"></i>
                        Thông tin sách
                    </h6>
                </div>
                <div class="card-body">
                    <div id="book-info" class="d-none">
                        <div class="text-center mb-3">
                            <img id="book-image" src="" alt="" class="img-fluid rounded" style="max-height: 150px;">
                        </div>
                        <div class="mb-2">
                            <strong>Tác giả:</strong>
                            <div id="book-author" class="text-muted"></div>
                        </div>
                        <div class="mb-2">
                            <strong>Thể loại:</strong>
                            <div id="book-category" class="text-muted"></div>
                        </div>
                        <div class="mb-2">
                            <strong>Giá hiện tại:</strong>
                            <div id="book-price" class="text-primary fw-bold"></div>
                        </div>
                        <div class="mb-2">
                            <strong>Tồn kho:</strong>
                            <div id="book-stock" class="text-info"></div>
                        </div>
                    </div>
                    <div id="no-book-selected" class="text-muted text-center py-3">
                        <i class="fas fa-book fa-2x mb-2"></i>
                        <div>Chọn sách để xem thông tin</div>
                    </div>
                </div>
            </div>

            <!-- Calculation -->
            <div class="card card-modern">
                <div class="card-header">
                    <h6 class="mb-0">
                        <i class="fas fa-calculator me-2"></i>
                        Tính toán
                    </h6>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-2">
                        <span>Số lượng:</span>
                        <span id="calc-quantity">0</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Giá bán:</span>
                        <span id="calc-price">0đ</span>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between">
                        <span class="fw-bold">Thành tiền:</span>
                        <span id="calc-total" class="fw-bold text-success">0đ</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function loadOrderInfo(orderId) {
        const orderSelect = document.querySelector('select[name="don_hang_id"]');
        const selectedOption = orderSelect.querySelector(`option[value="${orderId}"]`);
        
        if (selectedOption && orderId) {
            document.getElementById('customer-name').textContent = selectedOption.dataset.customer;
            document.getElementById('order-status').innerHTML = `<span class="badge bg-info">${selectedOption.dataset.status}</span>`;
            document.getElementById('current-total').textContent = selectedOption.dataset.total + 'đ';
            
            document.getElementById('order-info').classList.remove('d-none');
            document.getElementById('no-order-selected').classList.add('d-none');
        } else {
            document.getElementById('order-info').classList.add('d-none');
            document.getElementById('no-order-selected').classList.remove('d-none');
        }
    }

    function loadBookInfo(bookId) {
        const bookSelect = document.querySelector('select[name="sach_id"]');
        const selectedOption = bookSelect.querySelector(`option[value="${bookId}"]`);
        
        if (selectedOption && bookId) {
            const price = parseFloat(selectedOption.dataset.price);
            const stock = parseInt(selectedOption.dataset.stock);
            
            document.getElementById('book-author').textContent = selectedOption.dataset.author;
            document.getElementById('book-category').textContent = selectedOption.dataset.category;
            document.getElementById('book-price').textContent = new Intl.NumberFormat('vi-VN').format(price) + 'đ';
            document.getElementById('book-stock').textContent = stock + ' cuốn';
            document.getElementById('stock-info').textContent = stock + ' cuốn';
            
            // Set price input
            document.querySelector('input[name="gia_ban_tai_thoi_diem"]').value = price;
            
            // Update quantity max
            document.querySelector('input[name="so_luong"]').max = stock;
            
            document.getElementById('book-info').classList.remove('d-none');
            document.getElementById('no-book-selected').classList.add('d-none');
            
            calculateTotal();
        } else {
            document.getElementById('book-info').classList.add('d-none');
            document.getElementById('no-book-selected').classList.remove('d-none');
            
            document.getElementById('stock-info').textContent = '-';
            calculateTotal();
        }
    }

    function calculateTotal() {
        const quantity = parseInt(document.querySelector('input[name="so_luong"]').value) || 0;
        const price = parseFloat(document.querySelector('input[name="gia_ban_tai_thoi_diem"]').value) || 0;
        const total = quantity * price;
        
        document.getElementById('calc-quantity').textContent = quantity;
        document.getElementById('calc-price').textContent = new Intl.NumberFormat('vi-VN').format(price) + 'đ';
        document.getElementById('calc-total').textContent = new Intl.NumberFormat('vi-VN').format(total) + 'đ';
    }

    // Initialize on page load
    document.addEventListener('DOMContentLoaded', function() {
        const orderSelect = document.querySelector('select[name="don_hang_id"]');
        const bookSelect = document.querySelector('select[name="sach_id"]');
        
        if (orderSelect.value) {
            loadOrderInfo(orderSelect.value);
        }
        
        if (bookSelect.value) {
            loadBookInfo(bookSelect.value);
        }
        
        calculateTotal();
    });

    // Validate quantity against stock
    document.querySelector('input[name="so_luong"]').addEventListener('input', function() {
        const bookSelect = document.querySelector('select[name="sach_id"]');
        const selectedOption = bookSelect.querySelector(`option[value="${bookSelect.value}"]`);
        
        if (selectedOption) {
            const stock = parseInt(selectedOption.dataset.stock);
            const quantity = parseInt(this.value);
            
            if (quantity > stock) {
                this.setCustomValidity(`Số lượng không được vượt quá ${stock} cuốn`);
            } else {
                this.setCustomValidity('');
            }
        }
        
        calculateTotal();
    });
</script>
@endpush