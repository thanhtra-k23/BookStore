@extends('layouts.pure-blade')

@section('title', $title)

@section('content')
<div class="register-page">
    <div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-6 col-md-8">
            <div class="card card-modern shadow-lg">
                <div class="card-body p-5">
                    <!-- Logo -->
                    <div class="text-center mb-4">
                        <div class="auth-logo mb-3">
                            <i class="fas fa-user-plus text-success" style="font-size: 3rem;"></i>
                        </div>
                        <h2 class="fw-bold text-success mb-2">Tạo tài khoản</h2>
                        <p class="text-muted">Tham gia cộng đồng yêu sách BookStore</p>
                    </div>

                    <!-- Register Form -->
                    <form method="POST" action="{{ route('register') }}" id="registerForm">
                        @csrf
                        
                        <!-- Name -->
                        <div class="mb-3">
                            <label for="name" class="form-label">
                                <i class="fas fa-user me-2"></i>Họ và tên <span class="text-danger">*</span>
                            </label>
                            <input type="text" 
                                   class="form-control form-control-lg @error('name') is-invalid @enderror" 
                                   id="name" 
                                   name="name" 
                                   value="{{ old('name') }}" 
                                   placeholder="Nhập họ và tên của bạn"
                                   required 
                                   autofocus>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Email -->
                        <div class="mb-3">
                            <label for="email" class="form-label">
                                <i class="fas fa-envelope me-2"></i>Email <span class="text-danger">*</span>
                            </label>
                            <input type="email" 
                                   class="form-control form-control-lg @error('email') is-invalid @enderror" 
                                   id="email" 
                                   name="email" 
                                   value="{{ old('email') }}" 
                                   placeholder="Nhập email của bạn"
                                   required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Phone -->
                        <div class="mb-3">
                            <label for="so_dien_thoai" class="form-label">
                                <i class="fas fa-phone me-2"></i>Số điện thoại
                            </label>
                            <input type="tel" 
                                   class="form-control form-control-lg @error('so_dien_thoai') is-invalid @enderror" 
                                   id="so_dien_thoai" 
                                   name="so_dien_thoai" 
                                   value="{{ old('so_dien_thoai') }}" 
                                   placeholder="Nhập số điện thoại (tùy chọn)">
                            @error('so_dien_thoai')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Address -->
                        <div class="mb-3">
                            <label for="dia_chi" class="form-label">
                                <i class="fas fa-map-marker-alt me-2"></i>Địa chỉ
                            </label>
                            <textarea class="form-control @error('dia_chi') is-invalid @enderror" 
                                      id="dia_chi" 
                                      name="dia_chi" 
                                      rows="2" 
                                      placeholder="Nhập địa chỉ của bạn (tùy chọn)">{{ old('dia_chi') }}</textarea>
                            @error('dia_chi')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Password -->
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="password" class="form-label">
                                    <i class="fas fa-lock me-2"></i>Mật khẩu <span class="text-danger">*</span>
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
                                <div class="form-text">
                                    <small class="text-muted">Ít nhất 6 ký tự</small>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="password_confirmation" class="form-label">
                                    <i class="fas fa-lock me-2"></i>Xác nhận mật khẩu <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <input type="password" 
                                           class="form-control form-control-lg" 
                                           id="password_confirmation" 
                                           name="password_confirmation" 
                                           placeholder="Nhập lại mật khẩu"
                                           required>
                                    <button class="btn btn-outline-secondary" type="button" id="togglePasswordConfirm">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Password Strength Indicator -->
                        <div class="mb-3">
                            <div class="password-strength">
                                <div class="progress" style="height: 5px;">
                                    <div class="progress-bar" id="passwordStrengthBar" style="width: 0%"></div>
                                </div>
                                <small class="text-muted" id="passwordStrengthText">Độ mạnh mật khẩu</small>
                            </div>
                        </div>

                        <!-- Terms & Conditions -->
                        <div class="mb-4">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="terms" name="terms" required>
                                <label class="form-check-label" for="terms">
                                    Tôi đồng ý với 
                                    <a href="/terms" target="_blank" class="text-decoration-none">Điều khoản sử dụng</a> 
                                    và 
                                    <a href="/privacy" target="_blank" class="text-decoration-none">Chính sách bảo mật</a>
                                </label>
                            </div>
                        </div>

                        <!-- Newsletter -->
                        <div class="mb-4">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="newsletter" name="newsletter" checked>
                                <label class="form-check-label" for="newsletter">
                                    Nhận thông báo về sách mới và khuyến mãi qua email
                                </label>
                            </div>
                        </div>

                        <!-- Register Button -->
                        <div class="d-grid mb-3">
                            <button type="submit" class="btn btn-success btn-lg" id="registerBtn">
                                <i class="fas fa-user-plus me-2"></i>
                                Tạo tài khoản
                            </button>
                        </div>

                        <!-- Divider -->
                        <div class="text-center mb-3">
                            <span class="text-muted">hoặc</span>
                        </div>

                        <!-- Social Register -->
                        <div class="row g-2 mb-4">
                            <div class="col-6">
                                <button type="button" class="btn btn-outline-danger w-100" onclick="registerWithGoogle()">
                                    <i class="fab fa-google me-2"></i>Google
                                </button>
                            </div>
                            <div class="col-6">
                                <button type="button" class="btn btn-outline-primary w-100" onclick="registerWithFacebook()">
                                    <i class="fab fa-facebook-f me-2"></i>Facebook
                                </button>
                            </div>
                        </div>

                        <!-- Login Link -->
                        <div class="text-center">
                            <p class="mb-0">
                                Đã có tài khoản? 
                                <a href="{{ route('login') }}" class="text-decoration-none fw-semibold">
                                    Đăng nhập ngay
                                </a>
                            </p>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Benefits -->
            <div class="row mt-4">
                <div class="col-md-4 text-center mb-3">
                    <div class="benefit-item">
                        <i class="fas fa-shipping-fast text-primary fs-2 mb-2"></i>
                        <h6 class="fw-semibold">Giao hàng miễn phí</h6>
                        <small class="text-muted">Đơn hàng từ 200.000đ</small>
                    </div>
                </div>
                <div class="col-md-4 text-center mb-3">
                    <div class="benefit-item">
                        <i class="fas fa-heart text-danger fs-2 mb-2"></i>
                        <h6 class="fw-semibold">Danh sách yêu thích</h6>
                        <small class="text-muted">Lưu sách yêu thích</small>
                    </div>
                </div>
                <div class="col-md-4 text-center mb-3">
                    <div class="benefit-item">
                        <i class="fas fa-star text-warning fs-2 mb-2"></i>
                        <h6 class="fw-semibold">Đánh giá & Nhận xét</h6>
                        <small class="text-muted">Chia sẻ cảm nhận</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .auth-logo {
        animation: bounceIn 1s ease-in-out;
    }
    
    @keyframes bounceIn {
        0% {
            transform: scale(0.3);
            opacity: 0;
        }
        50% {
            transform: scale(1.05);
        }
        70% {
            transform: scale(0.9);
        }
        100% {
            transform: scale(1);
            opacity: 1;
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
    
    .input-group .btn {
        border-radius: 0 12px 12px 0;
    }
    
    .form-check-input:checked {
        background-color: var(--success-color);
        border-color: var(--success-color);
    }
    
    /* Register page specific background */
    .register-page {
        background: linear-gradient(135deg, #16a34a 0%, #22c55e 100%);
        min-height: 100vh;
        padding-top: 2rem;
    }
    
    .password-strength .progress {
        border-radius: 10px;
    }
    
    .password-strength .progress-bar {
        transition: all 0.3s ease;
        border-radius: 10px;
    }
    
    .benefit-item {
        padding: 1rem;
        border-radius: 12px;
        background: rgba(255, 255, 255, 0.1);
        backdrop-filter: blur(10px);
        color: white;
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

    document.getElementById('togglePasswordConfirm').addEventListener('click', function() {
        const passwordInput = document.getElementById('password_confirmation');
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

    // Password strength checker
    document.getElementById('password').addEventListener('input', function() {
        const password = this.value;
        const strengthBar = document.getElementById('passwordStrengthBar');
        const strengthText = document.getElementById('passwordStrengthText');
        
        let strength = 0;
        let text = 'Rất yếu';
        let color = 'bg-danger';
        
        if (password.length >= 6) strength += 1;
        if (password.match(/[a-z]/)) strength += 1;
        if (password.match(/[A-Z]/)) strength += 1;
        if (password.match(/[0-9]/)) strength += 1;
        if (password.match(/[^a-zA-Z0-9]/)) strength += 1;
        
        switch(strength) {
            case 0:
            case 1:
                text = 'Rất yếu';
                color = 'bg-danger';
                break;
            case 2:
                text = 'Yếu';
                color = 'bg-warning';
                break;
            case 3:
                text = 'Trung bình';
                color = 'bg-info';
                break;
            case 4:
                text = 'Mạnh';
                color = 'bg-success';
                break;
            case 5:
                text = 'Rất mạnh';
                color = 'bg-success';
                break;
        }
        
        strengthBar.style.width = (strength * 20) + '%';
        strengthBar.className = 'progress-bar ' + color;
        strengthText.textContent = text;
    });

    // Password confirmation validation
    document.getElementById('password_confirmation').addEventListener('input', function() {
        const password = document.getElementById('password').value;
        const confirmPassword = this.value;
        
        if (confirmPassword && password !== confirmPassword) {
            this.setCustomValidity('Mật khẩu xác nhận không khớp');
            this.classList.add('is-invalid');
        } else {
            this.setCustomValidity('');
            this.classList.remove('is-invalid');
        }
    });

    // Form submission with loading state
    document.getElementById('registerForm').addEventListener('submit', function(e) {
        const password = document.getElementById('password').value;
        const confirmPassword = document.getElementById('password_confirmation').value;
        const terms = document.getElementById('terms').checked;
        
        if (password !== confirmPassword) {
            e.preventDefault();
            showToast('Mật khẩu xác nhận không khớp', 'danger');
            return;
        }
        
        if (!terms) {
            e.preventDefault();
            showToast('Vui lòng đồng ý với điều khoản sử dụng', 'warning');
            return;
        }
        
        const registerBtn = document.getElementById('registerBtn');
        registerBtn.disabled = true;
        registerBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Đang tạo tài khoản...';
    });

    // Social register functions
    function registerWithGoogle() {
        showToast('Tính năng đăng ký Google đang phát triển', 'info');
    }

    function registerWithFacebook() {
        showToast('Tính năng đăng ký Facebook đang phát triển', 'info');
    }

    // Email validation
    document.getElementById('email').addEventListener('blur', function() {
        const email = this.value;
        if (email) {
            // Simple email validation
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

    // Phone number formatting
    document.getElementById('so_dien_thoai').addEventListener('input', function() {
        let value = this.value.replace(/\D/g, '');
        if (value.length > 10) {
            value = value.substring(0, 10);
        }
        this.value = value;
    });
</script>
@endpush