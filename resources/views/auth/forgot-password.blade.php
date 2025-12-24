@extends('layouts.app')

@section('title', $title)

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-5 col-md-7">
            <div class="card card-modern shadow-lg">
                <div class="card-body p-5">
                    <!-- Logo -->
                    <div class="text-center mb-4">
                        <div class="auth-logo mb-3">
                            <i class="fas fa-key text-warning" style="font-size: 3rem;"></i>
                        </div>
                        <h2 class="fw-bold text-warning mb-2">Quên mật khẩu?</h2>
                        <p class="text-muted">Nhập email để nhận hướng dẫn đặt lại mật khẩu</p>
                    </div>

                    <!-- Forgot Password Form -->
                    <form method="POST" action="{{ route('forgot-password') }}" id="forgotPasswordForm">
                        @csrf
                        
                        <!-- Email -->
                        <div class="mb-4">
                            <label for="email" class="form-label">
                                <i class="fas fa-envelope me-2"></i>Email đã đăng ký
                            </label>
                            <input type="email" 
                                   class="form-control form-control-lg @error('email') is-invalid @enderror" 
                                   id="email" 
                                   name="email" 
                                   value="{{ old('email') }}" 
                                   placeholder="Nhập email của bạn"
                                   required 
                                   autofocus>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">
                                <small class="text-muted">
                                    <i class="fas fa-info-circle me-1"></i>
                                    Chúng tôi sẽ gửi link đặt lại mật khẩu đến email này
                                </small>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="d-grid mb-4">
                            <button type="submit" class="btn btn-warning btn-lg" id="submitBtn">
                                <i class="fas fa-paper-plane me-2"></i>
                                Gửi hướng dẫn
                            </button>
                        </div>

                        <!-- Back to Login -->
                        <div class="text-center">
                            <p class="mb-0">
                                <a href="{{ route('login') }}" class="text-decoration-none">
                                    <i class="fas fa-arrow-left me-2"></i>
                                    Quay lại đăng nhập
                                </a>
                            </p>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Help Section -->
            <div class="card card-modern mt-4">
                <div class="card-body p-4">
                    <h6 class="fw-semibold mb-3">
                        <i class="fas fa-question-circle me-2"></i>
                        Cần hỗ trợ?
                    </h6>
                    <div class="row">
                        <div class="col-12 mb-2">
                            <small class="text-muted">
                                <i class="fas fa-clock me-2"></i>
                                Không nhận được email? Kiểm tra thư mục spam hoặc chờ 5-10 phút
                            </small>
                        </div>
                        <div class="col-12 mb-2">
                            <small class="text-muted">
                                <i class="fas fa-envelope me-2"></i>
                                Email hỗ trợ: <a href="mailto:support@bookstore.com">support@bookstore.com</a>
                            </small>
                        </div>
                        <div class="col-12">
                            <small class="text-muted">
                                <i class="fas fa-phone me-2"></i>
                                Hotline: <a href="tel:1900-1234">1900-1234</a>
                            </small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Access -->
            <div class="text-center mt-4">
                <p class="text-muted mb-2">Hoặc:</p>
                <div class="btn-group">
                    <a href="{{ route('register') }}" class="btn btn-outline-success btn-sm">
                        <i class="fas fa-user-plus me-1"></i>Tạo tài khoản mới
                    </a>
                    <a href="{{ route('home') }}" class="btn btn-outline-secondary btn-sm">
                        <i class="fas fa-home me-1"></i>Về trang chủ
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .auth-logo {
        animation: swing 2s ease-in-out infinite;
    }
    
    @keyframes swing {
        0%, 100% {
            transform: rotate(0deg);
        }
        20% {
            transform: rotate(15deg);
        }
        40% {
            transform: rotate(-10deg);
        }
        60% {
            transform: rotate(5deg);
        }
        80% {
            transform: rotate(-5deg);
        }
    }
    
    .card-modern {
        border: none;
        border-radius: 20px;
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
    }
    
    .form-control-lg {
        border-radius: 12px;
        padding: 0.75rem 1rem;
    }
    
    .btn-lg {
        border-radius: 12px;
        padding: 0.75rem 1.5rem;
        font-weight: 600;
    }
    
    body {
        background: linear-gradient(135deg, #f59e0b 0%, #f97316 100%);
        min-height: 100vh;
    }
    
    .btn-warning {
        color: #000;
    }
    
    .btn-warning:hover {
        color: #000;
        background-color: #fbbf24;
        border-color: #fbbf24;
    }
</style>
@endpush

@push('scripts')
<script>
    // Form submission with loading state
    document.getElementById('forgotPasswordForm').addEventListener('submit', function() {
        const submitBtn = document.getElementById('submitBtn');
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Đang gửi...';
    });

    // Email validation
    document.getElementById('email').addEventListener('blur', function() {
        const email = this.value;
        if (email) {
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(email)) {
                this.setCustomValidity('Email không hợp lệ');
                this.classList.add('is-invalid');
            } else {
                this.setCustomValidity('');
                this.classList.remove('is-invalid');
            }
        }
    });

    // Auto-focus on email field
    document.addEventListener('DOMContentLoaded', function() {
        const emailInput = document.getElementById('email');
        if (emailInput) {
            emailInput.focus();
        }
    });

    // Show success message after form submission
    @if(session('tb_success'))
        setTimeout(function() {
            showToast('{{ session('tb_success') }}', 'success');
        }, 500);
    @endif
</script>
@endpush