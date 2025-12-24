@extends('layouts.app')

@section('title', $title)

@section('content')
<div class="container mt-4 mb-5">
    <!-- Header Section -->
    <div class="card card-modern mb-4">
        <div class="card-header">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <h4 class="mb-1 fw-bold">
                        <i class="fas fa-shopping-cart me-2"></i>
                        Giỏ hàng của bạn
                    </h4>
                    <p class="mb-0 text-muted">
                        Quản lý các sản phẩm bạn muốn mua
                    </p>
                </div>
                <div class="col-md-6 text-md-end">
                    @if($gioHang->count() > 0)
                        <span class="badge bg-primary fs-6 me-3">
                            {{ $tongSoLuong }} sản phẩm
                        </span>
                        <button class="btn btn-outline-danger" onclick="clearCart()">
                            <i class="fas fa-trash me-1"></i>
                            Xóa tất cả
                        </button>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card card-modern">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-shopping-cart me-2"></i>
                        Giỏ hàng của bạn
                    </h5>
                    @if($gioHang->count() > 0)
                        <button class="btn btn-outline-danger btn-sm" onclick="clearCart()">
                            <i class="fas fa-trash me-1"></i>
                            Xóa tất cả
                        </button>
                    @endif
                </div>
                <div class="card-body">
                    @if($gioHang->count() > 0)
                        <div id="cart-items">
                            @foreach($gioHang as $item)
                                <div class="cart-item border-bottom pb-3 mb-3" data-item-id="{{ $item->id }}">
                                    <div class="row align-items-center">
                                        <div class="col-md-2">
                                            <img src="{{ $item->sach->anh_bia_url }}" 
                                                 alt="{{ $item->sach->ten_sach }}"
                                                 class="img-fluid rounded"
                                                 style="height: 80px; object-fit: cover;">
                                        </div>
                                        <div class="col-md-4">
                                            <h6 class="mb-1">
                                                <a href="{{ route('book.detail', [$item->sach->id, $item->sach->duong_dan]) }}" 
                                                   class="text-decoration-none text-dark">
                                                    {{ $item->sach->ten_sach }}
                                                </a>
                                            </h6>
                                            <small class="text-muted">
                                                <i class="fas fa-user me-1"></i>
                                                {{ $item->sach->tacGia->ten_tac_gia }}
                                            </small>
                                            <br>
                                            <small class="text-muted">
                                                <i class="fas fa-tag me-1"></i>
                                                {{ $item->sach->theLoai->ten_the_loai }}
                                            </small>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="fw-bold">
                                                {{ number_format($item->sach->gia_hien_tai, 0, ',', '.') }}đ
                                            </div>
                                            @if($item->sach->isOnSale())
                                                <small class="text-decoration-line-through text-muted">
                                                    {{ number_format($item->sach->gia_ban, 0, ',', '.') }}đ
                                                </small>
                                            @endif
                                        </div>
                                        <div class="col-md-2">
                                            <div class="input-group input-group-sm">
                                                <button class="btn btn-outline-secondary" type="button" 
                                                        onclick="updateQuantity({{ $item->id }}, {{ $item->so_luong - 1 }})">
                                                    <i class="fas fa-minus"></i>
                                                </button>
                                                <input type="number" class="form-control text-center" 
                                                       value="{{ $item->so_luong }}" 
                                                       min="1" max="{{ $item->sach->so_luong_ton }}"
                                                       onchange="updateQuantity({{ $item->id }}, this.value)">
                                                <button class="btn btn-outline-secondary" type="button"
                                                        onclick="updateQuantity({{ $item->id }}, {{ $item->so_luong + 1 }})">
                                                    <i class="fas fa-plus"></i>
                                                </button>
                                            </div>
                                            <small class="text-muted">
                                                Còn {{ $item->sach->so_luong_ton }} cuốn
                                            </small>
                                        </div>
                                        <div class="col-md-2 text-end">
                                            <div class="fw-bold text-primary mb-2">
                                                {{ number_format($item->thanh_tien, 0, ',', '.') }}đ
                                            </div>
                                            <button class="btn btn-outline-danger btn-sm" 
                                                    onclick="removeItem({{ $item->id }})">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Continue Shopping -->
                        <div class="text-center mt-4">
                            <a href="{{ route('home') }}" class="btn btn-outline-primary">
                                <i class="fas fa-arrow-left me-2"></i>
                                Tiếp tục mua sắm
                            </a>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-shopping-cart fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">Giỏ hàng trống</h5>
                            <p class="text-muted">Hãy thêm sách vào giỏ hàng để tiếp tục mua sắm</p>
                            <a href="{{ route('home') }}" class="btn btn-primary">
                                <i class="fas fa-book me-2"></i>
                                Khám phá sách
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        @if($gioHang->count() > 0)
            <div class="col-lg-4">
                <!-- Order Summary -->
                <div class="card card-modern">
                    <div class="card-header">
                        <h6 class="mb-0">
                            <i class="fas fa-receipt me-2"></i>
                            Tóm tắt đơn hàng
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between mb-2">
                            <span>Tổng số lượng:</span>
                            <span class="fw-bold" id="total-quantity">{{ $tongSoLuong }} cuốn</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Tạm tính:</span>
                            <span id="subtotal">{{ number_format($tongTien, 0, ',', '.') }}đ</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Phí vận chuyển:</span>
                            <span class="text-success">Miễn phí</span>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between mb-3">
                            <span class="fw-bold">Tổng cộng:</span>
                            <span class="fw-bold text-primary fs-5" id="total-amount">
                                {{ number_format($tongTien, 0, ',', '.') }}đ
                            </span>
                        </div>

                        <!-- Discount Code -->
                        <div class="mb-3">
                            <label class="form-label">Mã giảm giá</label>
                            <div class="input-group">
                                <input type="text" id="discount-code" class="form-control" 
                                       placeholder="Nhập mã giảm giá">
                                <button class="btn btn-outline-primary" type="button" onclick="applyDiscount()">
                                    Áp dụng
                                </button>
                            </div>
                            <div id="discount-message" class="form-text"></div>
                        </div>

                        <div class="d-grid">
                            <a href="{{ route('checkout') }}" class="btn btn-primary btn-lg">
                                <i class="fas fa-credit-card me-2"></i>
                                Thanh toán
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Recommended Books -->
                <div class="card card-modern mt-4">
                    <div class="card-header">
                        <h6 class="mb-0">
                            <i class="fas fa-thumbs-up me-2"></i>
                            Có thể bạn quan tâm
                        </h6>
                    </div>
                    <div class="card-body">
                        <!-- Add recommended books here -->
                        <p class="text-muted text-center">
                            Đang tải gợi ý sách...
                        </p>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Update quantity
    function updateQuantity(itemId, newQuantity) {
        if (newQuantity < 1) {
            removeItem(itemId);
            return;
        }

        showLoading();
        
        $.ajax({
            url: `/cart/update/${itemId}`,
            method: 'PUT',
            data: {
                so_luong: newQuantity
            },
            success: function(response) {
                location.reload();
            },
            error: function(xhr) {
                const response = xhr.responseJSON;
                showToast(response.message || 'Có lỗi xảy ra', 'danger');
            },
            complete: function() {
                hideLoading();
            }
        });
    }

    // Remove item
    function removeItem(itemId) {
        if (!confirm('Bạn có chắc chắn muốn xóa sản phẩm này khỏi giỏ hàng?')) {
            return;
        }

        $.ajax({
            url: `/cart/remove/${itemId}`,
            method: 'DELETE',
            success: function(response) {
                if (response.success) {
                    $(`.cart-item[data-item-id="${itemId}"]`).fadeOut(300, function() {
                        $(this).remove();
                        updateCartSummary();
                    });
                    showToast(response.message, 'success');
                    updateCounts();
                }
            },
            error: function(xhr) {
                const response = xhr.responseJSON;
                showToast(response.message || 'Có lỗi xảy ra', 'danger');
            }
        });
    }

    // Clear cart
    function clearCart() {
        if (!confirm('Bạn có chắc chắn muốn xóa tất cả sản phẩm khỏi giỏ hàng?')) {
            return;
        }

        $.ajax({
            url: '/cart/clear',
            method: 'DELETE',
            success: function(response) {
                location.reload();
            },
            error: function(xhr) {
                const response = xhr.responseJSON;
                showToast(response.message || 'Có lỗi xảy ra', 'danger');
            }
        });
    }

    // Apply discount code
    function applyDiscount() {
        const code = document.getElementById('discount-code').value.trim();
        const messageDiv = document.getElementById('discount-message');
        
        if (!code) {
            messageDiv.innerHTML = '<span class="text-danger">Vui lòng nhập mã giảm giá</span>';
            return;
        }

        const totalAmount = {{ $tongTien }};
        
        $.ajax({
            url: '/discount/validate',
            method: 'POST',
            data: {
                ma_code: code,
                tong_tien: totalAmount
            },
            success: function(response) {
                if (response.success) {
                    messageDiv.innerHTML = `<span class="text-success">${response.message}</span>`;
                    // Update total amount display
                    document.getElementById('total-amount').textContent = 
                        new Intl.NumberFormat('vi-VN').format(response.final_total) + 'đ';
                } else {
                    messageDiv.innerHTML = `<span class="text-danger">${response.message}</span>`;
                }
            },
            error: function(xhr) {
                const response = xhr.responseJSON;
                messageDiv.innerHTML = `<span class="text-danger">${response.message || 'Có lỗi xảy ra'}</span>`;
            }
        });
    }

    // Update cart summary
    function updateCartSummary() {
        const items = document.querySelectorAll('.cart-item');
        if (items.length === 0) {
            location.reload();
        }
    }
</script>
@endpush