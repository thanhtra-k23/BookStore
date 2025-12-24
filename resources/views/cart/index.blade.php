@extends('layouts.pure-blade')

@section('title', 'Giỏ hàng - BookStore')

@section('content')
<div class="container py-4">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Trang chủ</a></li>
            <li class="breadcrumb-item active">Giỏ hàng</li>
        </ol>
    </nav>

    <div class="row">
        <div class="col-12">
            <h2 class="fw-bold mb-4">
                <i class="fas fa-shopping-cart me-2"></i>
                Giỏ hàng của bạn
            </h2>
        </div>
    </div>

    <div class="row">
        <!-- Cart Items -->
        <div class="col-lg-8">
            <div class="card card-modern">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Sản phẩm trong giỏ</h5>
                    <button class="btn btn-outline-danger btn-sm" onclick="clearCart()">
                        <i class="fas fa-trash me-1"></i>
                        Xóa tất cả
                    </button>
                </div>
                <div class="card-body p-0">
                    <div id="cartItems">
                        <!-- Cart items will be loaded here -->
                        <div class="text-center py-5">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Coupon Section -->
            <div class="card card-modern mt-4">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-tags me-2"></i>
                        Mã giảm giá
                    </h5>
                </div>
                <div class="card-body">
                    <form id="couponForm" class="row g-3">
                        <div class="col-md-8">
                            <input type="text" class="form-control" id="couponCode" 
                                   placeholder="Nhập mã giảm giá">
                        </div>
                        <div class="col-md-4">
                            <button type="submit" class="btn btn-outline-primary w-100">
                                <i class="fas fa-check me-1"></i>
                                Áp dụng
                            </button>
                        </div>
                    </form>
                    <div id="couponResult" class="mt-3"></div>
                </div>
            </div>
        </div>

        <!-- Order Summary -->
        <div class="col-lg-4">
            <div class="card card-modern sticky-top" style="top: 20px;">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-receipt me-2"></i>
                        Tóm tắt đơn hàng
                    </h5>
                </div>
                <div class="card-body">
                    <div id="orderSummary">
                        <!-- Order summary will be loaded here -->
                        <div class="text-center py-3">
                            <div class="spinner-border spinner-border-sm text-primary" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="d-grid gap-2">
                        <button class="btn btn-primary btn-lg" onclick="proceedToCheckout()" disabled id="checkoutBtn">
                            <i class="fas fa-credit-card me-2"></i>
                            Thanh toán
                        </button>
                        <a href="{{ route('search') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left me-2"></i>
                            Tiếp tục mua sắm
                        </a>
                    </div>
                </div>
            </div>

            <!-- Shipping Info -->
            <div class="card card-modern mt-4">
                <div class="card-header">
                    <h6 class="mb-0">
                        <i class="fas fa-truck me-2"></i>
                        Thông tin giao hàng
                    </h6>
                </div>
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <i class="fas fa-shipping-fast text-success me-3"></i>
                        <div>
                            <div class="fw-semibold">Giao hàng miễn phí</div>
                            <small class="text-muted">Đơn hàng từ 200.000đ</small>
                        </div>
                    </div>
                    <div class="d-flex align-items-center mb-3">
                        <i class="fas fa-clock text-info me-3"></i>
                        <div>
                            <div class="fw-semibold">Giao hàng nhanh</div>
                            <small class="text-muted">2-3 ngày làm việc</small>
                        </div>
                    </div>
                    <div class="d-flex align-items-center">
                        <i class="fas fa-shield-alt text-warning me-3"></i>
                        <div>
                            <div class="fw-semibold">Đảm bảo chất lượng</div>
                            <small class="text-muted">Hoàn tiền 100%</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recently Viewed -->
    <div class="row mt-5">
        <div class="col-12">
            <h4 class="fw-bold mb-4">
                <i class="fas fa-history me-2"></i>
                Sách đã xem gần đây
            </h4>
            <div class="row" id="recentlyViewed">
                <!-- Recently viewed books will be loaded here -->
            </div>
        </div>
    </div>
</div>

<!-- Remove Item Modal -->
<div class="modal fade" id="removeItemModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Xác nhận xóa</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Bạn có chắc chắn muốn xóa sản phẩm này khỏi giỏ hàng?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                <button type="button" class="btn btn-danger" id="confirmRemove">Xóa</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    let cartData = [];
    let appliedCoupon = null;

    // Load cart on page load
    document.addEventListener('DOMContentLoaded', function() {
        loadCart();
        loadRecentlyViewed();
    });

    // Load cart items
    function loadCart() {
        fetch('/api/cart/items')
            .then(response => response.json())
            .then(data => {
                cartData = data.items || [];
                renderCartItems();
                updateOrderSummary();
            })
            .catch(error => {
                console.error('Error loading cart:', error);
                document.getElementById('cartItems').innerHTML = `
                    <div class="text-center py-5">
                        <i class="fas fa-exclamation-triangle text-warning fs-1 mb-3"></i>
                        <p class="text-muted">Có lỗi xảy ra khi tải giỏ hàng</p>
                    </div>
                `;
            });
    }

    // Render cart items
    function renderCartItems() {
        const container = document.getElementById('cartItems');
        
        if (cartData.length === 0) {
            container.innerHTML = `
                <div class="text-center py-5">
                    <i class="fas fa-shopping-cart text-muted fs-1 mb-3"></i>
                    <h5 class="text-muted mb-3">Giỏ hàng trống</h5>
                    <p class="text-muted mb-4">Hãy thêm một số sách vào giỏ hàng của bạn</p>
                    <a href="{{ route('search') }}" class="btn btn-primary">
                        <i class="fas fa-book me-2"></i>
                        Khám phá sách
                    </a>
                </div>
            `;
            return;
        }

        let html = '';
        cartData.forEach(item => {
            const price = item.gia_khuyen_mai || item.gia_ban;
            const originalPrice = item.gia_ban;
            const total = price * item.so_luong;

            html += `
                <div class="cart-item border-bottom p-3" data-id="${item.sach_id}">
                    <div class="row align-items-center">
                        <div class="col-md-2 text-center mb-3 mb-md-0">
                            <img src="${item.anh_bia_url || '/images/no-image.png'}" 
                                 alt="${item.ten_sach}" 
                                 class="img-fluid rounded" 
                                 style="max-height: 80px; object-fit: cover;">
                        </div>
                        <div class="col-md-4 mb-3 mb-md-0">
                            <h6 class="fw-semibold mb-1">${item.ten_sach}</h6>
                            <small class="text-muted">
                                ${item.tac_gia ? 'Tác giả: ' + item.tac_gia : ''}
                            </small>
                        </div>
                        <div class="col-md-2 text-center mb-3 mb-md-0">
                            <div class="fw-semibold text-primary">
                                ${formatPrice(price)}đ
                            </div>
                            ${price < originalPrice ? `
                                <small class="text-muted text-decoration-line-through">
                                    ${formatPrice(originalPrice)}đ
                                </small>
                            ` : ''}
                        </div>
                        <div class="col-md-2 text-center mb-3 mb-md-0">
                            <div class="input-group input-group-sm" style="max-width: 120px; margin: 0 auto;">
                                <button class="btn btn-outline-secondary" type="button" 
                                        onclick="updateQuantity(${item.sach_id}, ${item.so_luong - 1})">
                                    <i class="fas fa-minus"></i>
                                </button>
                                <input type="number" class="form-control text-center" 
                                       value="${item.so_luong}" min="1" 
                                       onchange="updateQuantity(${item.sach_id}, this.value)">
                                <button class="btn btn-outline-secondary" type="button" 
                                        onclick="updateQuantity(${item.sach_id}, ${item.so_luong + 1})">
                                    <i class="fas fa-plus"></i>
                                </button>
                            </div>
                        </div>
                        <div class="col-md-1 text-center mb-3 mb-md-0">
                            <div class="fw-bold">${formatPrice(total)}đ</div>
                        </div>
                        <div class="col-md-1 text-center">
                            <button class="btn btn-outline-danger btn-sm" 
                                    onclick="removeItem(${item.sach_id})">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                </div>
            `;
        });

        container.innerHTML = html;
    }

    // Update quantity
    function updateQuantity(sachId, newQuantity) {
        if (newQuantity < 1) {
            removeItem(sachId);
            return;
        }

        showLoading();
        
        fetch('/api/cart/update', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                sach_id: sachId,
                so_luong: parseInt(newQuantity)
            })
        })
        .then(response => response.json())
        .then(data => {
            hideLoading();
            if (data.success) {
                loadCart(); // Reload cart
                showToast('Đã cập nhật số lượng', 'success');
            } else {
                showToast(data.message || 'Có lỗi xảy ra', 'danger');
            }
        })
        .catch(error => {
            hideLoading();
            showToast('Có lỗi xảy ra', 'danger');
        });
    }

    // Remove item
    function removeItem(sachId) {
        const modal = new bootstrap.Modal(document.getElementById('removeItemModal'));
        modal.show();
        
        document.getElementById('confirmRemove').onclick = function() {
            showLoading();
            
            fetch('/api/cart/remove', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    sach_id: sachId
                })
            })
            .then(response => response.json())
            .then(data => {
                hideLoading();
                modal.hide();
                if (data.success) {
                    loadCart(); // Reload cart
                    showToast('Đã xóa sản phẩm khỏi giỏ hàng', 'success');
                } else {
                    showToast(data.message || 'Có lỗi xảy ra', 'danger');
                }
            })
            .catch(error => {
                hideLoading();
                modal.hide();
                showToast('Có lỗi xảy ra', 'danger');
            });
        };
    }

    // Clear cart
    function clearCart() {
        if (!confirm('Bạn có chắc chắn muốn xóa tất cả sản phẩm trong giỏ hàng?')) {
            return;
        }

        showLoading();
        
        fetch('/api/cart/clear', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            hideLoading();
            if (data.success) {
                loadCart(); // Reload cart
                showToast('Đã xóa tất cả sản phẩm', 'success');
            } else {
                showToast(data.message || 'Có lỗi xảy ra', 'danger');
            }
        })
        .catch(error => {
            hideLoading();
            showToast('Có lỗi xảy ra', 'danger');
        });
    }

    // Update order summary
    function updateOrderSummary() {
        const container = document.getElementById('orderSummary');
        
        if (cartData.length === 0) {
            container.innerHTML = `
                <div class="text-center py-3">
                    <p class="text-muted mb-0">Chưa có sản phẩm nào</p>
                </div>
            `;
            document.getElementById('checkoutBtn').disabled = true;
            return;
        }

        let subtotal = 0;
        let itemCount = 0;

        cartData.forEach(item => {
            const price = item.gia_khuyen_mai || item.gia_ban;
            subtotal += price * item.so_luong;
            itemCount += item.so_luong;
        });

        const shipping = subtotal >= 200000 ? 0 : 30000;
        let discount = 0;
        
        if (appliedCoupon) {
            if (appliedCoupon.loai_giam_gia === 'phan_tram') {
                discount = (subtotal * appliedCoupon.gia_tri_giam) / 100;
                if (appliedCoupon.gia_tri_giam_toi_da && discount > appliedCoupon.gia_tri_giam_toi_da) {
                    discount = appliedCoupon.gia_tri_giam_toi_da;
                }
            } else {
                discount = Math.min(appliedCoupon.gia_tri_giam, subtotal);
            }
        }

        const total = subtotal + shipping - discount;

        let html = `
            <div class="d-flex justify-content-between mb-2">
                <span>Tạm tính (${itemCount} sản phẩm):</span>
                <span>${formatPrice(subtotal)}đ</span>
            </div>
            <div class="d-flex justify-content-between mb-2">
                <span>Phí vận chuyển:</span>
                <span class="${shipping === 0 ? 'text-success' : ''}">
                    ${shipping === 0 ? 'Miễn phí' : formatPrice(shipping) + 'đ'}
                </span>
            </div>
        `;

        if (appliedCoupon) {
            html += `
                <div class="d-flex justify-content-between mb-2 text-success">
                    <span>Giảm giá (${appliedCoupon.ma_code}):</span>
                    <span>-${formatPrice(discount)}đ</span>
                </div>
            `;
        }

        html += `
            <hr>
            <div class="d-flex justify-content-between fw-bold fs-5">
                <span>Tổng cộng:</span>
                <span class="text-primary">${formatPrice(total)}đ</span>
            </div>
        `;

        container.innerHTML = html;
        document.getElementById('checkoutBtn').disabled = false;
    }

    // Apply coupon
    document.getElementById('couponForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const code = document.getElementById('couponCode').value.trim();
        if (!code) {
            showToast('Vui lòng nhập mã giảm giá', 'warning');
            return;
        }

        const subtotal = cartData.reduce((sum, item) => {
            const price = item.gia_khuyen_mai || item.gia_ban;
            return sum + (price * item.so_luong);
        }, 0);

        showLoading();
        
        fetch('/api/discount/validate', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                ma_code: code,
                tong_tien: subtotal
            })
        })
        .then(response => response.json())
        .then(data => {
            hideLoading();
            const resultDiv = document.getElementById('couponResult');
            
            if (data.success) {
                appliedCoupon = data.discount_info;
                appliedCoupon.ma_code = code;
                appliedCoupon.loai_giam_gia = data.discount_info.loai_giam_gia || 'so_tien';
                appliedCoupon.gia_tri_giam = data.discount;
                
                resultDiv.innerHTML = `
                    <div class="alert alert-success d-flex justify-content-between align-items-center">
                        <span><i class="fas fa-check-circle me-2"></i>${data.message}</span>
                        <button class="btn btn-sm btn-outline-success" onclick="removeCoupon()">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                `;
                updateOrderSummary();
                showToast('Áp dụng mã giảm giá thành công!', 'success');
            } else {
                resultDiv.innerHTML = `
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-circle me-2"></i>${data.message}
                    </div>
                `;
                showToast(data.message, 'danger');
            }
        })
        .catch(error => {
            hideLoading();
            showToast('Có lỗi xảy ra', 'danger');
        });
    });

    // Remove coupon
    function removeCoupon() {
        appliedCoupon = null;
        document.getElementById('couponCode').value = '';
        document.getElementById('couponResult').innerHTML = '';
        updateOrderSummary();
        showToast('Đã hủy mã giảm giá', 'info');
    }

    // Proceed to checkout
    function proceedToCheckout() {
        if (cartData.length === 0) {
            showToast('Giỏ hàng trống', 'warning');
            return;
        }

        // Store coupon in session if applied
        if (appliedCoupon) {
            sessionStorage.setItem('appliedCoupon', JSON.stringify(appliedCoupon));
        }

        window.location.href = '/checkout';
    }

    // Load recently viewed books
    function loadRecentlyViewed() {
        fetch('/api/books/recently-viewed')
            .then(response => response.json())
            .then(data => {
                const container = document.getElementById('recentlyViewed');
                if (data.books && data.books.length > 0) {
                    let html = '';
                    data.books.forEach(book => {
                        html += `
                            <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                                <div class="card card-modern h-100">
                                    <div class="card-body text-center">
                                        <img src="${book.anh_bia_url || '/images/no-image.png'}" 
                                             alt="${book.ten_sach}" 
                                             class="img-fluid mb-3 rounded" 
                                             style="max-height: 150px; object-fit: cover;">
                                        <h6 class="card-title">${book.ten_sach}</h6>
                                        <p class="text-primary fw-bold">
                                            ${formatPrice(book.gia_khuyen_mai || book.gia_ban)}đ
                                        </p>
                                        <a href="/books/${book.sach_id}" class="btn btn-outline-primary btn-sm">
                                            Xem chi tiết
                                        </a>
                                    </div>
                                </div>
                            </div>
                        `;
                    });
                    container.innerHTML = html;
                } else {
                    container.innerHTML = `
                        <div class="col-12 text-center py-4">
                            <p class="text-muted">Chưa có sách nào được xem gần đây</p>
                        </div>
                    `;
                }
            })
            .catch(error => {
                console.error('Error loading recently viewed:', error);
            });
    }

    // Format price
    function formatPrice(price) {
        return new Intl.NumberFormat('vi-VN').format(price);
    }
</script>
@endpush