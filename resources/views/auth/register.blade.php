<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Đăng ký - BookStore</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            min-height: 100vh;
            display: flex;
            background: linear-gradient(135deg, #10b981 0%, #059669 50%, #047857 100%);
            background-size: 400% 400%;
            animation: gradientBG 15s ease infinite;
        }

        @keyframes gradientBG {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        /* Left Side - Branding */
        .register-branding {
            flex: 0 0 40%;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 3rem;
            color: white;
            position: relative;
            overflow: hidden;
        }

        .register-branding::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
            animation: rotate 30s linear infinite;
        }

        @keyframes rotate {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }

        .branding-content {
            position: relative;
            z-index: 1;
            text-align: center;
        }

        .brand-logo {
            font-size: 4rem;
            margin-bottom: 1.5rem;
            animation: bounce 2s ease-in-out infinite;
        }

        @keyframes bounce {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-10px); }
        }

        .brand-title {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 1rem;
        }

        .brand-subtitle {
            font-size: 1rem;
            opacity: 0.9;
            max-width: 300px;
            line-height: 1.6;
        }

        .benefits-list {
            margin-top: 2.5rem;
            text-align: left;
        }

        .benefit-item {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 0.75rem 0;
            font-size: 0.95rem;
        }

        .benefit-icon {
            width: 40px;
            height: 40px;
            background: rgba(255,255,255,0.2);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
        }

        /* Right Side - Register Form */
        .register-form-container {
            flex: 1;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 2rem;
            background: white;
            min-height: 100vh;
            overflow-y: auto;
        }

        .register-form-wrapper {
            width: 100%;
            max-width: 500px;
            padding: 1rem 0;
        }

        .form-header {
            text-align: center;
            margin-bottom: 2rem;
        }

        .form-header h1 {
            font-size: 1.75rem;
            color: #1e293b;
            margin-bottom: 0.5rem;
        }

        .form-header p {
            color: #64748b;
            font-size: 0.95rem;
        }

        .form-row {
            display: flex;
            gap: 1rem;
        }

        .form-row .form-group {
            flex: 1;
        }

        .form-group {
            margin-bottom: 1.25rem;
        }

        .form-label {
            display: block;
            margin-bottom: 0.4rem;
            font-weight: 600;
            color: #374151;
            font-size: 0.9rem;
        }

        .form-label .required {
            color: #ef4444;
        }

        .form-input {
            width: 100%;
            padding: 0.875rem 1rem;
            border: 2px solid #e2e8f0;
            border-radius: 10px;
            font-size: 0.95rem;
            transition: all 0.3s ease;
            background: #f8fafc;
        }

        .form-input:focus {
            outline: none;
            border-color: #10b981;
            background: white;
            box-shadow: 0 0 0 4px rgba(16, 185, 129, 0.1);
        }

        .form-input.error {
            border-color: #ef4444;
        }

        textarea.form-input {
            resize: vertical;
            min-height: 70px;
        }

        .input-group {
            position: relative;
        }

        .input-group .form-input {
            padding-right: 3rem;
        }

        .toggle-password {
            position: absolute;
            right: 0.75rem;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: #64748b;
            cursor: pointer;
            font-size: 1.1rem;
            padding: 0.25rem;
        }

        .password-strength {
            margin-top: 0.5rem;
        }

        .strength-bar {
            height: 4px;
            background: #e2e8f0;
            border-radius: 2px;
            overflow: hidden;
        }

        .strength-fill {
            height: 100%;
            width: 0%;
            transition: all 0.3s ease;
            border-radius: 2px;
        }

        .strength-text {
            font-size: 0.8rem;
            color: #64748b;
            margin-top: 0.25rem;
        }

        .checkbox-group {
            display: flex;
            align-items: flex-start;
            gap: 0.5rem;
            margin-bottom: 1rem;
        }

        .checkbox-group input {
            width: 18px;
            height: 18px;
            margin-top: 2px;
            accent-color: #10b981;
        }

        .checkbox-group label {
            color: #64748b;
            font-size: 0.85rem;
            line-height: 1.4;
        }

        .checkbox-group a {
            color: #10b981;
            text-decoration: none;
        }

        .checkbox-group a:hover {
            text-decoration: underline;
        }

        .btn-register {
            width: 100%;
            padding: 1rem;
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 1.05rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-bottom: 1.25rem;
        }

        .btn-register:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 30px rgba(16, 185, 129, 0.4);
        }

        .btn-register:disabled {
            opacity: 0.7;
            cursor: not-allowed;
            transform: none;
        }

        .divider {
            display: flex;
            align-items: center;
            margin: 1.25rem 0;
            color: #94a3b8;
        }

        .divider::before,
        .divider::after {
            content: '';
            flex: 1;
            height: 1px;
            background: #e2e8f0;
        }

        .divider span {
            padding: 0 1rem;
            font-size: 0.85rem;
        }

        .social-buttons {
            display: flex;
            gap: 1rem;
            margin-bottom: 1.5rem;
        }

        .btn-social {
            flex: 1;
            padding: 0.75rem;
            border: 2px solid #e2e8f0;
            border-radius: 10px;
            background: white;
            cursor: pointer;
            font-size: 0.9rem;
            font-weight: 500;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            transition: all 0.3s ease;
        }

        .btn-social:hover {
            border-color: #10b981;
            background: #f8fafc;
        }

        .btn-google { color: #ea4335; }
        .btn-facebook { color: #1877f2; }

        .login-link {
            text-align: center;
            color: #64748b;
            font-size: 0.95rem;
        }

        .login-link a {
            color: #10b981;
            text-decoration: none;
            font-weight: 600;
        }

        .login-link a:hover {
            text-decoration: underline;
        }

        .alert {
            padding: 0.875rem 1rem;
            border-radius: 10px;
            margin-bottom: 1.25rem;
            font-size: 0.9rem;
        }

        .alert-danger {
            background: #fef2f2;
            color: #dc2626;
            border: 1px solid #fecaca;
        }

        /* Responsive */
        @media (max-width: 968px) {
            body {
                flex-direction: column;
            }

            .register-branding {
                flex: none;
                padding: 2rem;
                min-height: auto;
            }

            .brand-logo { font-size: 3rem; }
            .brand-title { font-size: 1.5rem; }
            .benefits-list { display: none; }

            .register-form-container {
                min-height: auto;
                padding: 2rem 1.5rem;
            }
        }

        @media (max-width: 480px) {
            .register-branding {
                padding: 1.5rem;
            }

            .brand-subtitle { display: none; }

            .form-row {
                flex-direction: column;
                gap: 0;
            }

            .social-buttons {
                flex-direction: column;
            }
        }
    </style>
</head>
<body>
    <!-- Left Side - Branding -->
    <div class="register-branding">
        <div class="branding-content">
            <div class="brand-logo">📖</div>
            <h1 class="brand-title">Tham gia BookStore</h1>
            <p class="brand-subtitle">Tạo tài khoản để trải nghiệm mua sắm sách tuyệt vời</p>
            
            <div class="benefits-list">
                <div class="benefit-item">
                    <div class="benefit-icon">🎁</div>
                    <span>Giảm 10% cho đơn hàng đầu tiên</span>
                </div>
                <div class="benefit-item">
                    <div class="benefit-icon">❤️</div>
                    <span>Lưu sách yêu thích để mua sau</span>
                </div>
                <div class="benefit-item">
                    <div class="benefit-icon">📦</div>
                    <span>Theo dõi đơn hàng dễ dàng</span>
                </div>
                <div class="benefit-item">
                    <div class="benefit-icon">⭐</div>
                    <span>Đánh giá và nhận xét sách</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Right Side - Register Form -->
    <div class="register-form-container">
        <div class="register-form-wrapper">
            <div class="form-header">
                <h1>Tạo tài khoản mới ✨</h1>
                <p>Điền thông tin bên dưới để bắt đầu</p>
            </div>

            @if($errors->any())
            <div class="alert alert-danger">
                @foreach($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
            @endif

            <form method="POST" action="{{ route('register') }}" id="registerForm">
                @csrf
                
                <div class="form-group">
                    <label class="form-label">👤 Họ và tên <span class="required">*</span></label>
                    <input type="text" 
                           name="name" 
                           class="form-input {{ $errors->has('name') ? 'error' : '' }}" 
                           value="{{ old('name') }}"
                           placeholder="Nhập họ và tên của bạn"
                           required>
                </div>

                <div class="form-group">
                    <label class="form-label">📧 Email <span class="required">*</span></label>
                    <input type="email" 
                           name="email" 
                           class="form-input {{ $errors->has('email') ? 'error' : '' }}" 
                           value="{{ old('email') }}"
                           placeholder="Nhập email của bạn"
                           required>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">📱 Số điện thoại</label>
                        <input type="tel" 
                               name="so_dien_thoai" 
                               class="form-input" 
                               value="{{ old('so_dien_thoai') }}"
                               placeholder="VD: 0901234567"
                               maxlength="10">
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">📍 Địa chỉ</label>
                    <textarea name="dia_chi" 
                              class="form-input" 
                              placeholder="Nhập địa chỉ giao hàng (tùy chọn)">{{ old('dia_chi') }}</textarea>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">🔒 Mật khẩu <span class="required">*</span></label>
                        <div class="input-group">
                            <input type="password" 
                                   name="password" 
                                   id="password"
                                   class="form-input" 
                                   placeholder="Tối thiểu 6 ký tự"
                                   required
                                   minlength="6">
                            <button type="button" class="toggle-password" onclick="togglePassword('password')">👁️</button>
                        </div>
                        <div class="password-strength">
                            <div class="strength-bar">
                                <div class="strength-fill" id="strengthFill"></div>
                            </div>
                            <div class="strength-text" id="strengthText">Độ mạnh mật khẩu</div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">🔒 Xác nhận <span class="required">*</span></label>
                        <div class="input-group">
                            <input type="password" 
                                   name="password_confirmation" 
                                   id="password_confirmation"
                                   class="form-input" 
                                   placeholder="Nhập lại mật khẩu"
                                   required>
                            <button type="button" class="toggle-password" onclick="togglePassword('password_confirmation')">👁️</button>
                        </div>
                    </div>
                </div>

                <div class="checkbox-group">
                    <input type="checkbox" id="terms" name="terms" required>
                    <label for="terms">
                        Tôi đồng ý với <a href="/terms">Điều khoản sử dụng</a> và <a href="/privacy">Chính sách bảo mật</a>
                    </label>
                </div>

                <div class="checkbox-group">
                    <input type="checkbox" id="newsletter" name="newsletter" checked>
                    <label for="newsletter">
                        Nhận thông báo về sách mới và khuyến mãi qua email
                    </label>
                </div>

                <button type="submit" class="btn-register" id="registerBtn">
                    ✨ Tạo tài khoản
                </button>
            </form>

            <div class="divider">
                <span>hoặc đăng ký với</span>
            </div>

            <div class="social-buttons">
                <button type="button" class="btn-social btn-google" onclick="socialRegister('google')">
                    <span>G</span> Google
                </button>
                <button type="button" class="btn-social btn-facebook" onclick="socialRegister('facebook')">
                    <span>f</span> Facebook
                </button>
            </div>

            <p class="login-link">
                Đã có tài khoản? <a href="{{ route('login') }}">Đăng nhập ngay</a>
            </p>

            <div style="text-align: center; margin-top: 1.5rem;">
                <a href="{{ route('home') }}" style="color: #64748b; text-decoration: none; font-size: 0.9rem;">
                    ← Quay lại trang chủ
                </a>
            </div>
        </div>
    </div>

    <script>
        function togglePassword(inputId) {
            const input = document.getElementById(inputId);
            const btn = input.nextElementSibling;
            if (input.type === 'password') {
                input.type = 'text';
                btn.textContent = '🙈';
            } else {
                input.type = 'password';
                btn.textContent = '👁️';
            }
        }

        // Password strength checker
        document.getElementById('password').addEventListener('input', function() {
            const password = this.value;
            const fill = document.getElementById('strengthFill');
            const text = document.getElementById('strengthText');
            
            let strength = 0;
            let label = 'Rất yếu';
            let color = '#ef4444';
            
            if (password.length >= 6) strength++;
            if (password.match(/[a-z]/)) strength++;
            if (password.match(/[A-Z]/)) strength++;
            if (password.match(/[0-9]/)) strength++;
            if (password.match(/[^a-zA-Z0-9]/)) strength++;
            
            switch(strength) {
                case 0: case 1: label = 'Rất yếu'; color = '#ef4444'; break;
                case 2: label = 'Yếu'; color = '#f59e0b'; break;
                case 3: label = 'Trung bình'; color = '#eab308'; break;
                case 4: label = 'Mạnh'; color = '#22c55e'; break;
                case 5: label = 'Rất mạnh'; color = '#10b981'; break;
            }
            
            fill.style.width = (strength * 20) + '%';
            fill.style.background = color;
            text.textContent = label;
            text.style.color = color;
        });

        // Password confirmation check
        document.getElementById('password_confirmation').addEventListener('input', function() {
            const password = document.getElementById('password').value;
            if (this.value && this.value !== password) {
                this.style.borderColor = '#ef4444';
            } else {
                this.style.borderColor = '#e2e8f0';
            }
        });

        function socialRegister(provider) {
            alert('Tính năng đăng ký ' + provider + ' đang được phát triển!');
        }

        document.getElementById('registerForm').addEventListener('submit', function(e) {
            const password = document.getElementById('password').value;
            const confirm = document.getElementById('password_confirmation').value;
            const terms = document.getElementById('terms').checked;
            
            if (password !== confirm) {
                e.preventDefault();
                alert('Mật khẩu xác nhận không khớp!');
                return;
            }
            
            if (!terms) {
                e.preventDefault();
                alert('Vui lòng đồng ý với điều khoản sử dụng!');
                return;
            }
            
            const btn = document.getElementById('registerBtn');
            btn.disabled = true;
            btn.textContent = '⏳ Đang tạo tài khoản...';
        });

        // Phone number formatting
        document.querySelector('input[name="so_dien_thoai"]').addEventListener('input', function() {
            this.value = this.value.replace(/\D/g, '').substring(0, 10);
        });
    </script>
</body>
</html>
