@extends('layouts.pure-blade')

@section('title', $title)

@section('content')
<div class="login-page">
    <div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-5 col-md-7">
            <div class="card card-modern shadow-lg">
                <div class="card-body p-5">
                    <!-- Logo -->
                    <div class="text-center mb-4">
                        <div class="auth-logo mb-3">
                            <i class="fas fa-book-open text-primary" style="font-size: 3rem;"></i>
                        </div>
                        <h2 class="fw-bold text-primary mb-2">BookStore</h2>
                        <p class="text-muted">Đăng nhập vào tài khoản của bạn</p>
                    </div>

                    <!-- Login Form -->
                    <form method="POST" action="{{ route('login') }}" id="loginForm">
                        @csrf
                        
                        <!-- Email -->
                        <div class="mb-3">
                            <label for="email" class="form-label">
                                <i class="fas fa-envelope me-2"></i>Email
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
                        </div>

                        <!-- Password -->
                        <div class="mb-3">
                            <label for="password" class="form-label">
                                <i class="fas fa-lock me-2"></i>Mật khẩu
                            </label>
                            <div class="input-group">
                                <input type="password" 
                                       class="form-control form-control-lg @error('password') is-invalid @enderror" 
                                       id="password" 
                                       name="password" 
                                       placeholder="Nhập mật khẩu"
                                       required>
                                <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                    <i class="fas fa-eye"></i>
                                </button>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Remember Me & Forgot Password -->
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="remember" name="remember" 
                                       {{ old('remember') ? 'checked' : '' }}>
                                <label class="form-check-label" for="remember">
                                    Ghi nhớ đăng nhập
                                </label>
                            </div>
                            <a href="{{ route('forgot-password') }}" class="text-decoration-none">
                                Quên mật khẩu?
                            </a>
                        </div>

                        <!-- Login Button -->
                        <div class="d-grid mb-3">
                            <button type="submit" class="btn btn-primary btn-lg" id="loginBtn">
                                <i class="fas fa-sign-in-alt me-2"></i>
                                Đăng nhập
                            </button>
                        </div>

                        <!-- Divider -->
                        <div class="text-center mb-3">
                            <span class="text-muted">hoặc</span>
                        </div>

                        <!-- Social Login -->
                        <div class="row g-2 mb-4">
                            <div class="col-6">
                                <button type="button" class="btn btn-outline-danger w-100" onclick="loginWithGoogle()">
                                    <i class="fab fa-google me-2"></i>Google
                                </button>
                            </div>
                            <div class="col-6">
                                <button type="button" class="btn btn-outline-primary w-100" onclick="loginWithFacebook()">
                                    <i class="fab fa-facebook-f me-2"></i>Facebook
                                </button>
                            </div>
                        </div>

                        <!-- Register Link -->
                        <div class="text-center">
                            <p class="mb-0">
                                Chưa có tài khoản? 
                                <a href="{{ route('register') }}" class="text-decoration-none fw-semibold">
                                    Đăng ký ngay
                                </a>
                            </p>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Quick Access -->
            <div class="text-center mt-4">
                <p class="text-muted mb-2">Truy cập nhanh:</p>
                <div class="btn-group">
                    <a href="{{ route('home') }}" class="btn btn-outline-secondary btn-sm">
                        <i class="fas fa-home me-1"></i>Trang chủ
                    </a>
                    <a href="{{ route('search') }}" class="btn btn-outline-secondary btn-sm">
                        <i class="fas fa-search me-1"></i>Tìm sách
                    </a>
                    <a href="{{ route('categories') }}" class="btn btn-outline-secondary btn-sm">
                        <i class="fas fa-list me-1"></i>Thể loại
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
        animation: pulse 2s infinite;
    }
    
    @keyframes pulse {
        0% {
            transform: scale(1);
        }
        50% {
            transform: scale(1.05);
        }
        100% {
            transform: scale(1);
        }
    }
    
    .card-modern {
        border: none;
        border-radius: 20px;
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
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
    
    .input-group .btn {
        border-radius: 0 12px 12px 0;
    }
    
    .form-check-input:checked {
        background-color: #007bff;
        border-color: #007bff;
    }
    
    /* Login page specific background */
    .login-page {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        min-height: 100vh;
        padding-top: 2rem;
    }
    
    .btn-outline-danger:hover {
        background-color: #dc3545;
        border-color: #dc3545;
    }
    
    .btn-outline-primary:hover {
        background-color: #0d6efd;
        border-color: #0d6efd;
    }
</style>
@endpush

@push('scripts')
<script>
    // Toggle password visibility
    document.getElementById('togglePassword').addEventListener('click', function() {
        const passwordInput = document.getElementById('password');
        const icon = this.querySelector('i');
        
        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        } else {
            passwordInput.type = 'password';
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        }
    });

    // Form submission with loading state
    document.getElementById('loginForm').addEventListener('submit', function() {
        const loginBtn = document.getElementById('loginBtn');
        loginBtn.disabled = true;
        loginBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Đang đăng nhập...';
    });

    // Social login functions
    function loginWithGoogle() {
        showToast('Tính năng đăng nhập Google đang phát triển', 'info');
    }

    function loginWithFacebook() {
        showToast('Tính năng đăng nhập Facebook đang phát triển', 'info');
    }

    // Auto-focus on email field
    document.addEventListener('DOMContentLoaded', function() {
        const emailInput = document.getElementById('email');
        if (emailInput && !emailInput.value) {
            emailInput.focus();
        }
    });

    // Enter key handling
    document.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            const form = document.getElementById('loginForm');
            if (form) {
                form.submit();
            }
        }
    });
</script>
@endpush