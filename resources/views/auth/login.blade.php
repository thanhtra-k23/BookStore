<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Đăng nhập - BookStore</title>
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
            background: linear-gradient(135deg, #667eea 0%, #764ba2 50%, #f093fb 100%);
            background-size: 400% 400%;
            animation: gradientBG 15s ease infinite;
        }

        @keyframes gradientBG {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        /* Left Side - Branding */
        .login-branding {
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 3rem;
            color: white;
            position: relative;
            overflow: hidden;
        }

        .login-branding::before {
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
            font-size: 5rem;
            margin-bottom: 1.5rem;
            animation: float 3s ease-in-out infinite;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-15px); }
        }

        .brand-title {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 1rem;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.2);
        }

        .brand-subtitle {
            font-size: 1.1rem;
            opacity: 0.9;
            max-width: 350px;
            line-height: 1.6;
        }

        .brand-features {
            margin-top: 3rem;
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .feature-item {
            display: flex;
            align-items: center;
            gap: 1rem;
            background: rgba(255,255,255,0.15);
            padding: 1rem 1.5rem;
            border-radius: 12px;
            backdrop-filter: blur(10px);
        }

        .feature-icon {
            font-size: 1.5rem;
        }

        /* Right Side - Login Form */
        .login-form-container {
            flex: 1;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 2rem;
            background: white;
            min-height: 100vh;
        }

        .login-form-wrapper {
            width: 100%;
            max-width: 420px;
        }

        .form-header {
            text-align: center;
            margin-bottom: 2.5rem;
        }

        .form-header h1 {
            font-size: 2rem;
            color: #1e293b;
            margin-bottom: 0.5rem;
        }

        .form-header p {
            color: #64748b;
            font-size: 1rem;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 600;
            color: #374151;
            font-size: 0.95rem;
        }

        .form-input {
            width: 100%;
            padding: 1rem 1.25rem;
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            font-size: 1rem;
            transition: all 0.3s ease;
            background: #f8fafc;
        }

        .form-input:focus {
            outline: none;
            border-color: #667eea;
            background: white;
            box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1);
        }

        .form-input.error {
            border-color: #ef4444;
        }

        .input-group {
            position: relative;
        }

        .input-group .form-input {
            padding-right: 3.5rem;
        }

        .toggle-password {
            position: absolute;
            right: 1rem;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: #64748b;
            cursor: pointer;
            font-size: 1.2rem;
            padding: 0.5rem;
        }

        .toggle-password:hover {
            color: #667eea;
        }

        .form-options {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
        }

        .checkbox-group {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .checkbox-group input {
            width: 18px;
            height: 18px;
            accent-color: #667eea;
        }

        .checkbox-group label {
            color: #64748b;
            font-size: 0.9rem;
            cursor: pointer;
        }

        .forgot-link {
            color: #667eea;
            text-decoration: none;
            font-size: 0.9rem;
            font-weight: 500;
        }

        .forgot-link:hover {
            text-decoration: underline;
        }

        .btn-login {
            width: 100%;
            padding: 1rem;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 12px;
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-bottom: 1.5rem;
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 30px rgba(102, 126, 234, 0.4);
        }

        .btn-login:disabled {
            opacity: 0.7;
            cursor: not-allowed;
            transform: none;
        }

        .divider {
            display: flex;
            align-items: center;
            margin: 1.5rem 0;
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
            font-size: 0.9rem;
        }

        .social-buttons {
            display: flex;
            gap: 1rem;
            margin-bottom: 2rem;
        }

        .btn-social {
            flex: 1;
            padding: 0.875rem;
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            background: white;
            cursor: pointer;
            font-size: 1rem;
            font-weight: 500;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            transition: all 0.3s ease;
        }

        .btn-social:hover {
            border-color: #667eea;
            background: #f8fafc;
        }

        .btn-google { color: #ea4335; }
        .btn-facebook { color: #1877f2; }

        .register-link {
            text-align: center;
            color: #64748b;
        }

        .register-link a {
            color: #667eea;
            text-decoration: none;
            font-weight: 600;
        }

        .register-link a:hover {
            text-decoration: underline;
        }

        .error-message {
            color: #ef4444;
            font-size: 0.85rem;
            margin-top: 0.5rem;
        }

        .alert {
            padding: 1rem;
            border-radius: 12px;
            margin-bottom: 1.5rem;
            font-size: 0.95rem;
        }

        .alert-danger {
            background: #fef2f2;
            color: #dc2626;
            border: 1px solid #fecaca;
        }

        .alert-success {
            background: #f0fdf4;
            color: #16a34a;
            border: 1px solid #bbf7d0;
        }

        /* Responsive */
        @media (max-width: 968px) {
            body {
                flex-direction: column;
            }

            .login-branding {
                padding: 2rem;
                min-height: auto;
            }

            .brand-logo { font-size: 3rem; }
            .brand-title { font-size: 1.75rem; }
            .brand-features { display: none; }

            .login-form-container {
                min-height: auto;
                padding: 2rem 1.5rem;
            }
        }

        @media (max-width: 480px) {
            .login-branding {
                padding: 1.5rem;
            }

            .brand-subtitle { display: none; }

            .form-options {
                flex-direction: column;
                gap: 1rem;
                align-items: flex-start;
            }

            .social-buttons {
                flex-direction: column;
            }
        }
    </style>
</head>
<body>
    <!-- Left Side - Branding -->
    <div class="login-branding">
        <div class="branding-content">
            <div class="brand-logo">📚</div>
            <h1 class="brand-title">BookStore</h1>
            <p class="brand-subtitle">Khám phá thế giới tri thức qua hàng ngàn đầu sách chất lượng</p>
            
            <div class="brand-features">
                <div class="feature-item">
                    <span class="feature-icon">🚚</span>
                    <span>Giao hàng miễn phí từ 200.000đ</span>
                </div>
                <div class="feature-item">
                    <span class="feature-icon">💯</span>
                    <span>Sách chính hãng 100%</span>
                </div>
                <div class="feature-item">
                    <span class="feature-icon">🔄</span>
                    <span>Đổi trả trong 7 ngày</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Right Side - Login Form -->
    <div class="login-form-container">
        <div class="login-form-wrapper">
            <div class="form-header">
                <h1>Chào mừng trở lại! 👋</h1>
                <p>Đăng nhập để tiếp tục mua sắm</p>
            </div>

            @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            @if($errors->any())
            <div class="alert alert-danger">
                @foreach($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
            @endif

            <form method="POST" action="{{ route('login') }}" id="loginForm">
                @csrf
                
                <div class="form-group">
                    <label class="form-label">📧 Email</label>
                    <input type="email" 
                           name="email" 
                           class="form-input {{ $errors->has('email') ? 'error' : '' }}" 
                           value="{{ old('email') }}"
                           placeholder="Nhập email của bạn"
                           required 
                           autofocus>
                </div>

                <div class="form-group">
                    <label class="form-label">🔒 Mật khẩu</label>
                    <div class="input-group">
                        <input type="password" 
                               name="password" 
                               id="password"
                               class="form-input {{ $errors->has('password') ? 'error' : '' }}" 
                               placeholder="Nhập mật khẩu"
                               required>
                        <button type="button" class="toggle-password" onclick="togglePassword()">👁️</button>
                    </div>
                </div>

                <div class="form-options">
                    <div class="checkbox-group">
                        <input type="checkbox" id="remember" name="remember" {{ old('remember') ? 'checked' : '' }}>
                        <label for="remember">Ghi nhớ đăng nhập</label>
                    </div>
                    <a href="{{ route('forgot-password') }}" class="forgot-link">Quên mật khẩu?</a>
                </div>

                <button type="submit" class="btn-login" id="loginBtn">
                    🔐 Đăng nhập
                </button>
            </form>

            <div class="divider">
                <span>hoặc đăng nhập với</span>
            </div>

            <div class="social-buttons">
                <button type="button" class="btn-social btn-google" onclick="socialLogin('google')">
                    <span>G</span> Google
                </button>
                <button type="button" class="btn-social btn-facebook" onclick="socialLogin('facebook')">
                    <span>f</span> Facebook
                </button>
            </div>

            <p class="register-link">
                Chưa có tài khoản? <a href="{{ route('register') }}">Đăng ký ngay</a>
            </p>

            <div style="text-align: center; margin-top: 2rem;">
                <a href="{{ route('home') }}" style="color: #64748b; text-decoration: none; font-size: 0.9rem;">
                    ← Quay lại trang chủ
                </a>
            </div>
        </div>
    </div>

    <script>
        function togglePassword() {
            const input = document.getElementById('password');
            const btn = document.querySelector('.toggle-password');
            if (input.type === 'password') {
                input.type = 'text';
                btn.textContent = '🙈';
            } else {
                input.type = 'password';
                btn.textContent = '👁️';
            }
        }

        function socialLogin(provider) {
            alert('Tính năng đăng nhập ' + provider + ' đang được phát triển!');
        }

        document.getElementById('loginForm').addEventListener('submit', function() {
            const btn = document.getElementById('loginBtn');
            btn.disabled = true;
            btn.textContent = '⏳ Đang đăng nhập...';
        });
    </script>
</body>
</html>
