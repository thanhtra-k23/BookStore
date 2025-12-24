@extends('layouts.pure-blade')

@section('title', 'Thanh toán - BookStore')

@section('content')
<div class="container py-5">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Trang chủ</a></li>
            <li class="breadcrumb-item"><a href="{{ route('cart.index') }}">Giỏ hàng</a></li>
            <li class="breadcrumb-item active">Thanh toán</li>
        </ol>
    </nav>

    <div class="row">
        <div class="col-lg-8">
            <!-- Checkout Form -->
            <div class="card mb-4">
                <div class="card-header">
                    <h4 class="mb-0">
                        <i class="fas fa-credit-card me-2"></i>
                        Thông tin thanh toán
                    </h4>
                </div>
                <div class="card-body">
                    <form id="checkoutForm" method="POST" action="{{ route('checkout.process') }}">
                        @csrf
                        
                        <!-- Customer Information -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h5 class="border-bottom pb-2 mb-3">Thông tin khách hàng</h5>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="ho_ten" class="form-label">Họ và tên <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="ho_ten" name="ho_ten" 
                                       value="{{ auth()->user()->ho_ten ?? '' }}" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                                <input type="email" class="form-control" id="email" name="email" 
                                       value="{{ auth()->user()->email ?? '' }}" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="so_dien_thoai" class="form-label">Số điện thoại <span class="text-danger">*</span></label>
                                <input type="tel" class="form-control" id="so_dien_thoai" name="so_dien_thoai" 
                                       value="{{ auth()->user()->so_dien_thoai ?? '' }}" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="dia_chi" class="form-label">Địa chỉ giao hàng <span class="text-danger">*</span></label>
                                <textarea class="form-control" id="dia_chi" name="dia_chi" rows="2" required>{{ auth()->user()->dia_chi ?? '' }}</textarea>
                            </div>
                        </div>

                        <!-- Payment Method -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h5 class="border-bottom pb-2 mb-3">Phương thức thanh toán</h5>
                            </div>
                            <div class="col-12">
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="radio" name="payment_method" id="cod" value="cod" checked>
                                    <label class="form-check-label" for="cod">
                                        <i class="fas fa-money-bill-wave me-2"></i>
                                        Thanh toán khi nhận hàng (COD)
                                    </label>
                                </div>
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="radio" name="payment_method" id="bank_transfer" value="bank_transfer">
                                    <label class="form-check-label" for="bank_transfer">
                                        <i class="fas fa-university me-2"></i>
                                        Chuyển khoản ngân hàng
                                    </label>
                                </div>
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="radio" name="payment_method" id="momo" value="momo">
                                    <label class="form-check-label" for="momo">
                                        <i class="fas fa-mobile-alt me-2"></i>
                                        Ví MoMo
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- Order Notes -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <label for="ghi_chu" class="form-label">Ghi chú đơn hàng</label>
                                <textarea class="form-control" id="ghi_chu" name="ghi_chu" rows="3" 
                                          placeholder="Ghi chú về đơn hàng, ví dụ: thời gian giao hàng..."></textarea>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <!-- Order Summary -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-receipt me-2"></i>
                        Tóm tắt đơn hàng
                    </h5>
                </div>
                <div class="card-body">
                    <!-- Cart Items -->
                    <div class="mb-3">
                        <h6 class="border-bottom pb-2">Sản phẩm</h6>
                        <div id="checkout-items">
                            <!-- Items will be loaded here -->
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <div>
                                    <small class="text-muted">Đang tải...</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Order Total -->
                    <div class="border-top pt-3">
                        <div class="d-flex justify-content-between mb-2">
                            <span>Tạm tính:</span>
                            <span id="subtotal">0 ₫</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Phí vận chuyển:</span>
                            <span id="shipping">30,000 ₫</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Giảm giá:</span>
                            <span id="discount" class="text-success">-0 ₫</span>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between fw-bold fs-5">
                            <span>Tổng cộng:</span>
                            <span id="total" class="text-primary">0 ₫</span>
                        </div>
                    </div>

                    <!-- Discount Code -->
                    <div class="mt-3">
                        <div class="input-group">
                            <input type="text" class="form-control" placeholder="Mã giảm giá" id="discount_code">
                            <button class="btn btn-outline-secondary" type="button" onclick="applyDiscount()">
                                Áp dụng
                            </button>
                        </div>
                    </div>

                    <!-- Place Order Button -->
                    <div class="d-grid mt-4">
                        <button type="submit" form="checkoutForm" class="btn btn-primary btn-lg">
                            <i class="fas fa-check me-2"></i>
                            Đặt hàng
                        </button>
                    </div>

                    <!-- Security Info -->
                    <div class="text-center mt-3">
                        <small class="text-muted">
                            <i class="fas fa-shield-alt me-1"></i>
                            Thông tin của bạn được bảo mật
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .card {
        border: none;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        border-radius: 10px;
    }
    
    .card-header {
        background: linear-gradient(135deg, #007bff, #0056b3);
        color: white;
        border-radius: 10px 10px 0 0 !important;
    }
    
    .form-control:focus {
        border-color: #007bff;
        box-shadow: 0 0 0 0.2rem rgba(0,123,255,.25);
    }
    
    .form-check-input:checked {
        background-color: #007bff;
        border-color: #007bff;
    }
    
    .btn-primary {
        background: linear-gradient(135deg, #007bff, #0056b3);
        border: none;
        border-radius: 8px;
        padding: 12px 24px;
        font-weight: 600;
    }
    
    .btn-primary:hover {
        background: linear-gradient(135deg, #0056b3, #004085);
        transform: translateY(-1px);
    }
</style>
@endpush

@push('scripts')
<script>
    // Load cart items for checkout
    document.addEventListener('DOMContentLoaded', function() {
        loadCheckoutItems();
    });

    function loadCheckoutItems() {
        // Simulate loading cart items
        const checkoutItems = document.getElementById('checkout-items');
        
        // This would normally fetch from API
        const items = [
            { name: 'Truyện Kiều', price: 150000, quantity: 1 },
            { name: 'Chí Phèo', price: 80000, quantity: 2 }
        ];
        
        let html = '';
        let subtotal = 0;
        
        items.forEach(item => {
            const itemTotal = item.price * item.quantity;
            subtotal += itemTotal;
            
            html += `
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <div>
                        <div class="fw-semibold">${item.name}</div>
                        <small class="text-muted">Số lượng: ${item.quantity}</small>
                    </div>
                    <div class="text-end">
                        <div>${formatCurrency(itemTotal)}</div>
                    </div>
                </div>
            `;
        });
        
        checkoutItems.innerHTML = html;
        
        // Update totals
        const shipping = 30000;
        const discount = 0;
        const total = subtotal + shipping - discount;
        
        document.getElementById('subtotal').textContent = formatCurrency(subtotal);
        document.getElementById('total').textContent = formatCurrency(total);
    }

    function formatCurrency(amount) {
        return new Intl.NumberFormat('vi-VN', {
            style: 'currency',
            currency: 'VND'
        }).format(amount);
    }

    function applyDiscount() {
        const code = document.getElementById('discount_code').value;
        if (code) {
            // Simulate discount application
            alert('Tính năng mã giảm giá đang được phát triển');
        }
    }

    // Form validation
    document.getElementById('checkoutForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        // Basic validation
        const requiredFields = ['ho_ten', 'email', 'so_dien_thoai', 'dia_chi'];
        let isValid = true;
        
        requiredFields.forEach(field => {
            const input = document.getElementById(field);
            if (!input.value.trim()) {
                input.classList.add('is-invalid');
                isValid = false;
            } else {
                input.classList.remove('is-invalid');
            }
        });
        
        if (isValid) {
            // Simulate order processing
            alert('Đơn hàng đã được đặt thành công! Chức năng thanh toán đang được phát triển.');
            // window.location.href = '/checkout/success';
        }
    });
</script>
@endpush