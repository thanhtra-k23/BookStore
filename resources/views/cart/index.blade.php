@extends('layouts.pure-blade')

@section('title', 'Giỏ hàng - BookStore')

@push('styles')
<style>
    .cart-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 2rem 1rem;
    }

    .cart-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2rem;
        flex-wrap: wrap;
        gap: 1rem;
    }

    .cart-title {
        font-size: 1.75rem;
        font-weight: 700;
        color: #1e293b;
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .cart-title .icon {
        font-size: 2rem;
    }

    .cart-count-badge {
        background: linear-gradient(135deg, #2563eb, #3b82f6);
        color: white;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 0.9rem;
    }

    .cart-grid {
        display: grid;
        grid-template-columns: 1fr 380px;
        gap: 2rem;
    }

    .cart-items-section {
        background: white;
        border-radius: 20px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        overflow: hidden;
    }

    .cart-items-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 1.25rem 1.5rem;
        background: linear-gradient(135deg, #f8fafc, #f1f5f9);
        border-bottom: 1px solid #e2e8f0;
    }

    .cart-items-header h3 {
        font-size: 1.1rem;
        font-weight: 600;
        color: #1e293b;
        margin: 0;
    }

    .btn-clear-cart {
        background: transparent;
        border: 1px solid #ef4444;
        color: #ef4444;
        padding: 8px 16px;
        border-radius: 8px;
        font-size: 0.85rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s;
    }

    .btn-clear-cart:hover {
        background: #ef4444;
        color: white;
    }

    .cart-item {
        display: grid;
        grid-template-columns: 100px 1fr auto auto auto;
        gap: 1.5rem;
        align-items: center;
        padding: 1.5rem;
        border-bottom: 1px solid #f1f5f9;
        transition: background 0.3s;
    }

    .cart-item:hover {
        background: #fafafa;
    }

    .cart-item:last-child {
        border-bottom: none;
    }

    .cart-item-image {
        width: 100px;
        height: 130px;
        object-fit: cover;
        border-radius: 12px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    }

    .cart-item-info h4 {
        font-size: 1rem;
        font-weight: 600;
        color: #1e293b;
        margin-bottom: 6px;
        line-height: 1.4;
    }

    .cart-item-info h4 a {
        color: inherit;
        text-decoration: none;
        transition: color 0.3s;
    }

    .cart-item-info h4 a:hover {
        color: #2563eb;
    }

    .cart-item-author {
        color: #64748b;
        font-size: 0.9rem;
        margin-bottom: 8px;
    }

    .cart-item-price {
        text-align: center;
    }

    .price-current {
        font-size: 1.1rem;
        font-weight: 700;
        color: #dc2626;
    }

    .price-original {
        font-size: 0.85rem;
        color: #94a3b8;
        text-decoration: line-through;
    }

    .quantity-controls {
        display: flex;
        align-items: center;
        border: 2px solid #e2e8f0;
        border-radius: 10px;
        overflow: hidden;
    }

    .qty-btn {
        width: 36px;
        height: 36px;
        border: none;
        background: #f8fafc;
        font-size: 1.1rem;
        cursor: pointer;
        transition: all 0.3s;
    }

    .qty-btn:hover {
        background: #2563eb;
        color: white;
    }

    .qty-input {
        width: 50px;
        height: 36px;
        border: none;
        text-align: center;
        font-weight: 600;
        font-size: 1rem;
    }

    .qty-input:focus {
        outline: none;
    }

    .cart-item-total {
        font-size: 1.15rem;
        font-weight: 700;
        color: #1e293b;
        min-width: 120px;
        text-align: right;
    }

    .btn-remove {
        width: 40px;
        height: 40px;
        border: none;
        background: #fee2e2;
        color: #dc2626;
        border-radius: 10px;
        font-size: 1.1rem;
        cursor: pointer;
        transition: all 0.3s;
    }

    .btn-remove:hover {
        background: #dc2626;
        color: white;
        transform: scale(1.1);
    }

    .cart-empty {
        text-align: center;
        padding: 4rem 2rem;
    }

    .cart-empty-icon {
        font-size: 5rem;
        margin-bottom: 1.5rem;
        opacity: 0.3;
    }

    .cart-empty h3 {
        font-size: 1.5rem;
        color: #64748b;
        margin-bottom: 1rem;
    }

    .cart-empty p {
        color: #94a3b8;
        margin-bottom: 2rem;
    }

    /* Summary Section */
    .cart-summary {
        position: sticky;
        top: 100px;
    }

    .summary-card {
        background: white;
        border-radius: 20px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        overflow: hidden;
    }

    .summary-header {
        padding: 1.25rem 1.5rem;
        background: linear-gradient(135deg, #1e293b, #334155);
        color: white;
    }

    .summary-header h3 {
        font-size: 1.1rem;
        font-weight: 600;
        margin: 0;
    }

    .summary-body {
        padding: 1.5rem;
    }

    .summary-row {
        display: flex;
        justify-content: space-between;
        margin-bottom: 1rem;
        font-size: 0.95rem;
    }

    .summary-row.total {
        font-size: 1.25rem;
        font-weight: 700;
        padding-top: 1rem;
        border-top: 2px solid #e2e8f0;
        margin-top: 1rem;
    }

    .summary-row.total .value {
        color: #dc2626;
    }

    .summary-row .label {
        color: #64748b;
    }

    .summary-row .value {
        font-weight: 600;
        color: #1e293b;
    }

    .summary-row .value.free {
        color: #10b981;
    }

    .summary-row .value.discount {
        color: #10b981;
    }

    .summary-footer {
        padding: 1.5rem;
        border-top: 1px solid #f1f5f9;
    }

    .btn-checkout {
        width: 100%;
        padding: 16px;
        background: linear-gradient(135deg, #f59e0b, #fbbf24);
        color: white;
        border: none;
        border-radius: 12px;
        font-size: 1.1rem;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.3s;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        margin-bottom: 1rem;
    }

    .btn-checkout:hover {
        background: linear-gradient(135deg, #d97706, #f59e0b);
        transform: translateY(-2px);
        box-shadow: 0 10px 30px rgba(245, 158, 11, 0.4);
    }

    .btn-checkout:disabled {
        background: #cbd5e1;
        cursor: not-allowed;
        transform: none;
        box-shadow: none;
    }

    .btn-continue {
        width: 100%;
        padding: 14px;
        background: transparent;
        color: #2563eb;
        border: 2px solid #2563eb;
        border-radius: 12px;
        font-size: 1rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s;
        text-decoration: none;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
    }

    .btn-continue:hover {
        background: #2563eb;
        color: white;
    }
</style>
@endpush

@section('content')
<div class="cart-container">
    <!-- Header -->
    <div class="cart-header">
        <h1 class="cart-title">
            <span class="icon">🛒</span>
            Giỏ hàng của bạn
            <span class="cart-count-badge" id="cartCountBadge">0</span>
        </h1>
    </div>

    <div class="cart-grid">
        <!-- Cart Items -->
        <div class="cart-items-section">
            <div class="cart-items-header">
                <h3>Sản phẩm trong giỏ</h3>
                <button class="btn-clear-cart" onclick="clearCart()">🗑️ Xóa tất cả</button>
            </div>
            <div id="cartItems">
                <!-- Loading -->
                <div class="cart-empty">
                    <div class="cart-empty-icon">⏳</div>
                    <h3>Đang tải...</h3>
                </div>
            </div>
        </div>

        <!-- Summary -->
        <div class="cart-summary">
            <div class="summary-card">
                <div class="summary-header">
                    <h3>📋 Tóm tắt đơn hàng</h3>
                </div>
                <div class="summary-body" id="orderSummary">
                    <div class="summary-row">
                        <span class="label">Tạm tính:</span>
                        <span class="value">0đ</span>
                    </div>
                </div>
                <div class="summary-footer">
                    <button class="btn-checkout" id="checkoutBtn" onclick="proceedToCheckout()" disabled>
                        <span>⚡</span>
                        <span>Tiến hành thanh toán</span>
                    </button>
                    <a href="{{ route('search') }}" class="btn-continue">
                        <span>←</span>
                        <span>Tiếp tục mua sắm</span>
                    </a>
                </div>
            </div>

            <!-- Coupon Section -->
            <div class="summary-card" style="margin-top: 1.5rem;">
                <div class="summary-header" style="background: linear-gradient(135deg, #7c3aed, #8b5cf6);">
                    <h3>🎫 Mã giảm giá</h3>
                </div>
                <div class="summary-body">
                    <form id="couponForm" style="display: flex; gap: 10px;">
                        <input type="text" id="couponCode" placeholder="Nhập mã giảm giá" 
                               style="flex: 1; padding: 12px; border: 2px solid #e2e8f0; border-radius: 10px; font-size: 0.95rem;">
                        <button type="submit" style="padding: 12px 20px; background: linear-gradient(135deg, #7c3aed, #8b5cf6); color: white; border: none; border-radius: 10px; font-weight: 600; cursor: pointer;">
                            Áp dụng
                        </button>
                    </form>
                    <div id="couponResult" style="margin-top: 1rem;"></div>
                </div>
            </div>

            <!-- Shipping Info -->
            <div class="summary-card" style="margin-top: 1.5rem;">
                <div class="summary-body">
                    <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 1rem;">
                        <span style="font-size: 1.5rem;">🚚</span>
                        <div>
                            <div style="font-weight: 600; color: #10b981;">Miễn phí vận chuyển</div>
                            <small style="color: #64748b;">Đơn hàng từ 200.000đ</small>
                        </div>
                    </div>
                    <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 1rem;">
                        <span style="font-size: 1.5rem;">⏰</span>
                        <div>
                            <div style="font-weight: 600;">Giao hàng nhanh</div>
                            <small style="color: #64748b;">2-3 ngày làm việc</small>
                        </div>
                    </div>
                    <div style="display: flex; align-items: center; gap: 12px;">
                        <span style="font-size: 1.5rem;">🛡️</span>
                        <div>
                            <div style="font-weight: 600;">Đảm bảo chất lượng</div>
                            <small style="color: #64748b;">Hoàn tiền 100%</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    let cartData = [];
    let appliedCoupon = null;

    document.addEventListener('DOMContentLoaded', function() {
        loadCart();
    });

    function loadCart() {
        // Get cart from session/database
        @if(Auth::check())
            // For logged-in users, fetch from server
            fetch('/api/cart/items')
                .then(response => response.json())
                .then(data => {
                    cartData = data.items || [];
                    renderCart();
                })
                .catch(() => {
                    // Fallback to session cart
                    cartData = @json(session('cart', []));
                    if (typeof cartData === 'object' && !Array.isArray(cartData)) {
                        cartData = Object.values(cartData);
                    }
                    renderCart();
                });
        @else
            // For guests, use session cart
            cartData = @json(session('cart', []));
            if (typeof cartData === 'object' && !Array.isArray(cartData)) {
                cartData = Object.values(cartData);
            }
            renderCart();
        @endif
    }

    function renderCart() {
        const container = document.getElementById('cartItems');
        const countBadge = document.getElementById('cartCountBadge');
        
        if (!cartData || cartData.length === 0) {
            container.innerHTML = `
                <div class="cart-empty">
                    <div class="cart-empty-icon">🛒</div>
                    <h3>Giỏ hàng trống</h3>
                    <p>Hãy thêm một số sách vào giỏ hàng của bạn</p>
                    <a href="{{ route('search') }}" class="btn btn-primary" style="padding: 12px 24px; border-radius: 10px; text-decoration: none;">
                        📚 Khám phá sách
                    </a>
                </div>
            `;
            countBadge.textContent = '0';
            updateSummary(0, 0);
            return;
        }

        let html = '';
        let totalItems = 0;
        let subtotal = 0;

        cartData.forEach((item, index) => {
            const price = item.gia_khuyen_mai || item.gia_ban;
            const originalPrice = item.gia_ban;
            const itemTotal = price * item.so_luong;
            const sachId = item.ma_sach || item.sach_id;
            
            totalItems += item.so_luong;
            subtotal += itemTotal;

            html += `
                <div class="cart-item" data-id="${sachId}">
                    <img src="${item.hinh_anh || item.anh_bia_url || 'https://via.placeholder.com/100x130?text=No+Image'}" 
                         alt="${item.ten_sach}" 
                         class="cart-item-image"
                         onerror="this.src='https://via.placeholder.com/100x130?text=No+Image'">
                    
                    <div class="cart-item-info">
                        <h4><a href="/book/${sachId}">${item.ten_sach}</a></h4>
                        <div class="cart-item-author">✍️ ${item.tac_gia || 'Chưa cập nhật'}</div>
                    </div>
                    
                    <div class="cart-item-price">
                        <div class="price-current">${formatPrice(price)}đ</div>
                        ${price < originalPrice ? `<div class="price-original">${formatPrice(originalPrice)}đ</div>` : ''}
                    </div>
                    
                    <div class="quantity-controls">
                        <button class="qty-btn" onclick="updateQty('${sachId}', ${item.so_luong - 1})">−</button>
                        <input type="number" class="qty-input" value="${item.so_luong}" min="1" 
                               onchange="updateQty('${sachId}', this.value)">
                        <button class="qty-btn" onclick="updateQty('${sachId}', ${item.so_luong + 1})">+</button>
                    </div>
                    
                    <div class="cart-item-total">${formatPrice(itemTotal)}đ</div>
                    
                    <button class="btn-remove" onclick="removeItem('${sachId}')">🗑️</button>
                </div>
            `;
        });

        container.innerHTML = html;
        countBadge.textContent = totalItems;
        updateSummary(subtotal, totalItems);
    }

    function updateSummary(subtotal, itemCount) {
        const shipping = subtotal >= 200000 ? 0 : 30000;
        let discount = 0;
        
        if (appliedCoupon) {
            discount = appliedCoupon.discount || 0;
        }

        const total = subtotal + shipping - discount;

        let html = `
            <div class="summary-row">
                <span class="label">Tạm tính (${itemCount} sản phẩm):</span>
                <span class="value">${formatPrice(subtotal)}đ</span>
            </div>
            <div class="summary-row">
                <span class="label">Phí vận chuyển:</span>
                <span class="value ${shipping === 0 ? 'free' : ''}">${shipping === 0 ? 'Miễn phí' : formatPrice(shipping) + 'đ'}</span>
            </div>
        `;

        if (discount > 0) {
            html += `
                <div class="summary-row">
                    <span class="label">Giảm giá:</span>
                    <span class="value discount">-${formatPrice(discount)}đ</span>
                </div>
            `;
        }

        html += `
            <div class="summary-row total">
                <span class="label">Tổng cộng:</span>
                <span class="value">${formatPrice(total)}đ</span>
            </div>
        `;

        document.getElementById('orderSummary').innerHTML = html;
        document.getElementById('checkoutBtn').disabled = itemCount === 0;
    }

    function updateQty(sachId, newQty) {
        if (newQty < 1) {
            removeItem(sachId);
            return;
        }

        fetch('{{ route("cart.add") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                ma_sach: sachId,
                so_luong: parseInt(newQty),
                update: true
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Update local cart data
                const item = cartData.find(i => (i.ma_sach || i.sach_id) == sachId);
                if (item) {
                    item.so_luong = parseInt(newQty);
                }
                renderCart();
                showToast('✓ Đã cập nhật số lượng', 'success');
            } else {
                showToast(data.message || 'Có lỗi xảy ra', 'error');
            }
        })
        .catch(() => {
            showToast('Có lỗi xảy ra', 'error');
        });
    }

    function removeItem(sachId) {
        if (!confirm('Bạn có chắc muốn xóa sản phẩm này?')) return;

        fetch('{{ route("cart.remove", "") }}/' + sachId, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                cartData = cartData.filter(i => (i.ma_sach || i.sach_id) != sachId);
                renderCart();
                showToast('✓ Đã xóa sản phẩm', 'success');
                updateCartCount();
            } else {
                showToast(data.message || 'Có lỗi xảy ra', 'error');
            }
        })
        .catch(() => {
            // Remove from local anyway
            cartData = cartData.filter(i => (i.ma_sach || i.sach_id) != sachId);
            renderCart();
            showToast('✓ Đã xóa sản phẩm', 'success');
        });
    }

    function clearCart() {
        if (!confirm('Bạn có chắc muốn xóa tất cả sản phẩm?')) return;

        fetch('{{ route("cart.clear") }}', {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            cartData = [];
            renderCart();
            showToast('✓ Đã xóa tất cả sản phẩm', 'success');
            updateCartCount();
        })
        .catch(() => {
            cartData = [];
            renderCart();
        });
    }

    function proceedToCheckout() {
        if (cartData.length === 0) {
            showToast('Giỏ hàng trống', 'error');
            return;
        }
        window.location.href = '{{ route("checkout") }}';
    }

    // Coupon form
    document.getElementById('couponForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const code = document.getElementById('couponCode').value.trim();
        if (!code) {
            showToast('Vui lòng nhập mã giảm giá', 'error');
            return;
        }

        fetch('/api/discount/validate', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ ma_code: code })
        })
        .then(response => response.json())
        .then(data => {
            const resultDiv = document.getElementById('couponResult');
            if (data.success) {
                appliedCoupon = { code: code, discount: data.discount };
                resultDiv.innerHTML = `<div style="padding: 10px; background: #d1fae5; color: #059669; border-radius: 8px;">✓ ${data.message}</div>`;
                renderCart();
                showToast('✓ Áp dụng mã thành công!', 'success');
            } else {
                resultDiv.innerHTML = `<div style="padding: 10px; background: #fee2e2; color: #dc2626; border-radius: 8px;">✗ ${data.message}</div>`;
            }
        })
        .catch(() => {
            showToast('Có lỗi xảy ra', 'error');
        });
    });

    function formatPrice(price) {
        return new Intl.NumberFormat('vi-VN').format(price);
    }
</script>
@endpush
