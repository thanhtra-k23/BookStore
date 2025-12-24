@extends('layouts.pure-blade')

@section('title', 'Đặt hàng thành công - BookStore')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card text-center">
                <div class="card-body py-5">
                    <!-- Success Icon -->
                    <div class="mb-4">
                        <div class="success-icon">
                            <svg width="80" height="80" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <circle cx="12" cy="12" r="10" fill="#16a34a"/>
                                <path d="M9 12l2 2 4-4" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </div>
                    </div>

                    <!-- Success Message -->
                    <h2 class="text-success mb-3">Đặt hàng thành công!</h2>
                    <p class="text-muted mb-4">
                        Cảm ơn bạn đã đặt hàng tại BookStore. Chúng tôi sẽ liên hệ với bạn trong thời gian sớm nhất để xác nhận đơn hàng.
                    </p>

                    <!-- Order Info -->
                    <div class="order-info mb-4">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <div class="info-item">
                                    <strong>Mã đơn hàng:</strong>
                                    <div class="text-primary">#DH{{ str_pad(rand(1000, 9999), 4, '0', STR_PAD_LEFT) }}</div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="info-item">
                                    <strong>Thời gian đặt:</strong>
                                    <div>{{ date('d/m/Y H:i') }}</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Next Steps -->
                    <div class="next-steps mb-4">
                        <h5 class="mb-3">Các bước tiếp theo:</h5>
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <div class="step">
                                    <div class="step-number">1</div>
                                    <div class="step-text">Xác nhận đơn hàng</div>
                                    <small class="text-muted">Trong vòng 2 giờ</small>
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <div class="step">
                                    <div class="step-number">2</div>
                                    <div class="step-text">Chuẩn bị hàng</div>
                                    <small class="text-muted">1-2 ngày làm việc</small>
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <div class="step">
                                    <div class="step-number">3</div>
                                    <div class="step-text">Giao hàng</div>
                                    <small class="text-muted">2-3 ngày làm việc</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="action-buttons">
                        <a href="{{ route('home') }}" class="btn btn-primary me-3">
                            <i class="fas fa-home me-2"></i>
                            Về trang chủ
                        </a>
                        <a href="{{ route('categories') }}" class="btn btn-secondary">
                            <i class="fas fa-book me-2"></i>
                            Tiếp tục mua sắm
                        </a>
                    </div>

                    <!-- Contact Info -->
                    <div class="contact-info mt-4 pt-4 border-top">
                        <p class="mb-2">
                            <strong>Cần hỗ trợ?</strong>
                        </p>
                        <p class="text-muted mb-0">
                            Liên hệ: <a href="tel:0787905089">0787905089</a> | 
                            Email: <a href="mailto:support@bookstore.vn">support@bookstore.vn</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .success-icon {
        display: inline-block;
        animation: bounce 1s ease-in-out;
    }
    
    @keyframes bounce {
        0%, 20%, 50%, 80%, 100% {
            transform: translateY(0);
        }
        40% {
            transform: translateY(-10px);
        }
        60% {
            transform: translateY(-5px);
        }
    }
    
    .info-item {
        padding: 1rem;
        background: #f8f9fa;
        border-radius: 8px;
        height: 100%;
    }
    
    .step {
        text-align: center;
    }
    
    .step-number {
        width: 40px;
        height: 40px;
        background: #007bff;
        color: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 0.5rem;
        font-weight: bold;
    }
    
    .step-text {
        font-weight: 600;
        margin-bottom: 0.25rem;
    }
    
    .card {
        border: none;
        box-shadow: 0 4px 20px rgba(0,0,0,0.1);
        border-radius: 15px;
    }
    
    .btn {
        border-radius: 8px;
        padding: 12px 24px;
        font-weight: 600;
    }
    
    .btn-primary {
        background: linear-gradient(135deg, #007bff, #0056b3);
        border: none;
    }
    
    .btn-primary:hover {
        background: linear-gradient(135deg, #0056b3, #004085);
        transform: translateY(-1px);
    }
    
    .btn-secondary {
        background: #6c757d;
        border: none;
    }
    
    .btn-secondary:hover {
        background: #5a6268;
        transform: translateY(-1px);
    }
</style>
@endpush