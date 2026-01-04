<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Quên mật khẩu - BookStore</title>
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
            justify-content: center;
            align-items: center;
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 50%, #b45309 100%);
            background-size: 400% 400%;
            animation: gradientBG 15s ease infinite;
            padding: 2rem;
        }

        @keyframes gradientBG {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        .forgot-container {
            width: 100%;
            max-width: 450px;
        }

        .forgot-card {
            background: white;
            border-radius: 24px;
            box-shadow: 0 25px 50px rgba(0,0,0,0.15);
            overflow: hidden;
        }

        .card-header {
            background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
            padding: 2.5rem 2rem;
            text-align: center;
        }

        .header-icon {
            font-size: 4rem;
            margin-bottom: 1rem;
            animation: shake 2s ease-in-out infinite;
        }

        @keyframes shake {
            0%, 100% { transform: rotate(0deg); }
            25% { transform: rotate(10deg); }
            75% { transform: rotate(-10deg); }
        }

        .card-header h1 {
            font-size: 1.75rem;
            color: #92400e;
            margin-bottom: 0.5rem;
        }

        .card-header p {
            color: #a16207;
            font-size: 0.95rem;
        }

        .card-body {
            padding: 2rem;
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
            border-color: #f59e0b;
            background: white;
            box-shadow: 0 0 0 4px rgba(245, 158, 11, 0.1);
        }

        .form-hint {
            margin-top: 0.5rem;
            font-size: 0.85rem;
            color: #64748b;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn-submit {
            width: 100%;
            padding: 1rem;
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
            color: white;
            border: none;
            border-radius: 12px;
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-bottom: 1.5rem;
        }

        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 30px rgba(245, 158, 11, 0.4);
        }

        .btn-submit:disabled {
            opacity: 0.7;
            cursor: not-allowed;
            transform: none;
        }

        .back-link {
            text-align: center;
        }

        .back-link a {
            color: #64748b;
            text-decoration: none;
            font-size: 0.95rem;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            transition: color 0.3s;
        }

        .back-link a:hover {
            color: #f59e0b;
        }

        .help-card {
            background: white;
            border-radius: 16px;
            padding: 1.5rem;
            margin-top: 1.5rem;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }

        .help-card h3 {
            font-size: 1rem;
            color: #374151;
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .help-item {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.5rem 0;
            font-size: 0.9rem;
            color: #64748b;
        }

        .help-item a {
            color: #f59e0b;
            text-decoration: none;
        }

        .help-item a:hover {
            text-decoration: underline;
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

        .quick-links {
            text-align: center;
            margin-top: 1.5rem;
        }

        .quick-links a {
            display: inline-block;
            padding: 0.5rem 1rem;
            margin: 0.25rem;
            background: rgba(255,255,255,0.2);
            color: white;
            text-decoration: none;
            border-radius: 8px;
            font-size: 0.9rem;
            transition: all 0.3s;
        }

        .quick-links a:hover {
            background: rgba(255,255,255,0.3);
        }

        @media (max-width: 480px) {
            body {
                padding: 1rem;
            }

            .card-header {
                padding: 2rem 1.5rem;
            }

            .card-body {
                padding: 1.5rem;
            }

            .header-icon {
                font-size: 3rem;
            }

            .card-header h1 {
                font-size: 1.5rem;
            }
        }
    </style>
</head>
<body>
    <div class="forgot-container">
        <div class="forgot-card">
            <div class="card-header">
                <div class="header-icon">🔑</div>
                <h1>Quên mật khẩu?</h1>
                <p>Đừng lo, chúng tôi sẽ giúp bạn!</p>
            </div>

            <div class="card-body">
                @if(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
                @endif

                @if(session('success') || session('tb_success'))
                <div class="alert alert-success">{{ session('success') ?? session('tb_success') }}</div>
                @endif

                @if($errors->any())
                <div class="alert alert-danger">
                    @foreach($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
                @endif

                <form method="POST" action="{{ route('forgot-password') }}" id="forgotForm">
                    @csrf
                    
                    <div class="form-group">
                        <label class="form-label">📧 Email đã đăng ký</label>
                        <input type="email" 
                               name="email" 
                               class="form-input" 
                               value="{{ old('email') }}"
                               placeholder="Nhập email của bạn"
                               required 
                               autofocus>
                        <div class="form-hint">
                            ℹ️ Chúng tôi sẽ gửi link đặt lại mật khẩu đến email này
                        </div>
                    </div>

                    <button type="submit" class="btn-submit" id="submitBtn">
                        📨 Gửi hướng dẫn
                    </button>
                </form>

                <div class="back-link">
                    <a href="{{ route('login') }}">← Quay lại đăng nhập</a>
                </div>
            </div>
        </div>

        <div class="help-card">
            <h3>❓ Cần hỗ trợ?</h3>
            <div class="help-item">
                ⏰ Không nhận được email? Kiểm tra thư mục spam
            </div>
            <div class="help-item">
                📧 Email: <a href="mailto:support@bookstore.vn">support@bookstore.vn</a>
            </div>
            <div class="help-item">
                📞 Hotline: <a href="tel:0787905089">0787 905 089</a>
            </div>
        </div>

        <div class="quick-links">
            <a href="{{ route('register') }}">📝 Tạo tài khoản mới</a>
            <a href="{{ route('home') }}">🏠 Về trang chủ</a>
        </div>
    </div>

    <script>
        document.getElementById('forgotForm').addEventListener('submit', function() {
            const btn = document.getElementById('submitBtn');
            btn.disabled = true;
            btn.textContent = '⏳ Đang gửi...';
        });
    </script>
</body>
</html>
