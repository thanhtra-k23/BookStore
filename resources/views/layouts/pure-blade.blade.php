<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'BookStore - Nhà sách online')</title>
    
    <style>
        /* CSS thuần không sử dụng framework */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f8f9fa;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }

        /* Header */
        .header {
            background: #fff;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        .navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem 0;
        }

        .logo {
            font-size: 1.5rem;
            font-weight: bold;
            color: #2563eb;
            text-decoration: none;
        }

        .nav-menu {
            display: flex;
            list-style: none;
            gap: 2rem;
        }

        .nav-menu a {
            text-decoration: none;
            color: #333;
            font-weight: 500;
            transition: color 0.3s;
        }

        .nav-menu a:hover {
            color: #2563eb;
        }

        /* Main Content */
        .main-content {
            min-height: calc(100vh - 200px);
            padding: 2rem 0;
        }

        /* Cards */
        .card {
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            padding: 1.5rem;
            margin-bottom: 1.5rem;
        }

        .card-header {
            border-bottom: 1px solid #e5e7eb;
            padding-bottom: 1rem;
            margin-bottom: 1rem;
        }

        .card-title {
            font-size: 1.25rem;
            font-weight: 600;
            color: #1f2937;
        }

        /* Buttons */
        .btn {
            display: inline-block;
            padding: 0.75rem 1.5rem;
            border: none;
            border-radius: 6px;
            text-decoration: none;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s;
        }

        .btn-primary {
            background: #2563eb;
            color: white;
        }

        .btn-primary:hover {
            background: #1d4ed8;
        }

        .btn-success {
            background: #16a34a;
            color: white;
        }

        .btn-danger {
            background: #dc2626;
            color: white;
        }

        .btn-secondary {
            background: #6b7280;
            color: white;
        }

        /* Forms */
        .form-group {
            margin-bottom: 1rem;
        }

        .form-label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 500;
            color: #374151;
        }

        .form-control {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid #d1d5db;
            border-radius: 6px;
            font-size: 1rem;
        }

        .form-control:focus {
            outline: none;
            border-color: #2563eb;
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
        }

        /* Tables */
        .table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 1rem;
        }

        .table th,
        .table td {
            padding: 0.75rem;
            text-align: left;
            border-bottom: 1px solid #e5e7eb;
        }

        .table th {
            background: #f9fafb;
            font-weight: 600;
            color: #374151;
        }

        .table tr:hover {
            background: #f9fafb;
        }

        /* Grid System */
        .row {
            display: flex;
            flex-wrap: wrap;
            margin: 0 -0.75rem;
        }

        .col {
            flex: 1;
            padding: 0 0.75rem;
        }

        .col-2 { flex: 0 0 16.666667%; }
        .col-3 { flex: 0 0 25%; }
        .col-4 { flex: 0 0 33.333333%; }
        .col-6 { flex: 0 0 50%; }
        .col-8 { flex: 0 0 66.666667%; }
        .col-9 { flex: 0 0 75%; }
        .col-12 { flex: 0 0 100%; }

        /* Breadcrumb */
        .breadcrumb {
            background: transparent;
            padding: 0.75rem 0;
            margin-bottom: 1.5rem;
            border-radius: 6px;
            font-size: 0.9rem;
        }

        .breadcrumb-item + .breadcrumb-item::before {
            content: "›";
            color: #6b7280;
            font-weight: bold;
        }

        .breadcrumb-item a {
            color: #2563eb;
            text-decoration: none;
        }

        .breadcrumb-item a:hover {
            color: #1d4ed8;
            text-decoration: underline;
        }

        .breadcrumb-item.active {
            color: #6b7280;
        }

        /* Loading States */
        .loading {
            position: relative;
            pointer-events: none;
            opacity: 0.6;
        }

        .loading::after {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 20px;
            height: 20px;
            margin: -10px 0 0 -10px;
            border: 2px solid #f3f3f3;
            border-top: 2px solid #2563eb;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        /* Error States */
        .error-state {
            text-align: center;
            padding: 2rem;
            color: #dc2626;
        }

        .error-state .error-icon {
            font-size: 3rem;
            margin-bottom: 1rem;
        }

        /* Success States */
        .success-state {
            text-align: center;
            padding: 2rem;
            color: #16a34a;
        }

        .success-state .success-icon {
            font-size: 3rem;
            margin-bottom: 1rem;
        }
        /* Utilities */
        .text-right { text-align: right; }
        .mb-1 { margin-bottom: 0.25rem; }
        .mb-2 { margin-bottom: 0.5rem; }
        .mb-3 { margin-bottom: 1rem; }
        .mb-4 { margin-bottom: 1.5rem; }
        .mt-1 { margin-top: 0.25rem; }
        .mt-2 { margin-top: 0.5rem; }
        .mt-3 { margin-top: 1rem; }
        .mt-4 { margin-top: 1.5rem; }

        .d-flex { display: flex; }
        .justify-content-between { justify-content: space-between; }
        .align-items-center { align-items: center; }

        /* Footer */
        .footer {
            background: #1f2937;
            color: #d1d5db;
            padding: 2rem 0;
            margin-top: 3rem;
        }

        .footer-content {
            display: flex;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 2rem;
        }

        .footer-section h3 {
            color: #fff;
            margin-bottom: 1rem;
        }

        .footer-section ul {
            list-style: none;
        }

        .footer-section ul li {
            margin-bottom: 0.5rem;
        }

        .footer-section ul li a {
            color: #d1d5db;
            text-decoration: none;
        }

        .footer-section ul li a:hover {
            color: #fff;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .navbar {
                flex-direction: column;
                gap: 1rem;
            }

            .nav-menu {
                flex-direction: column;
                gap: 1rem;
                text-align: center;
            }

            .row {
                flex-direction: column;
            }

            .col,
            .col-2,
            .col-3,
            .col-4,
            .col-6,
            .col-8,
            .col-9 {
                flex: 0 0 100%;
            }

            .footer-content {
                flex-direction: column;
            }
        }

        /* Book specific styles */
        .book-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 1.5rem;
            margin-top: 2rem;
        }

        .book-card {
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            overflow: hidden;
            transition: transform 0.3s;
        }

        .book-card:hover {
            transform: translateY(-4px);
        }

        .book-image {
            width: 100%;
            height: 300px;
            object-fit: cover;
        }

        .book-info {
            padding: 1rem;
        }

        .book-title {
            font-size: 1.1rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
            color: #1f2937;
        }

        .book-author {
            color: #6b7280;
            margin-bottom: 0.5rem;
        }

        .book-price {
            font-size: 1.2rem;
            font-weight: bold;
            color: #dc2626;
        }

        .book-price-old {
            text-decoration: line-through;
            color: #9ca3af;
            font-size: 1rem;
            margin-left: 0.5rem;
        }

        /* Alert messages */
        .alert {
            padding: 1rem;
            border-radius: 6px;
            margin-bottom: 1rem;
        }

        .alert-success {
            background: #d1fae5;
            color: #065f46;
            border: 1px solid #a7f3d0;
        }

        .alert-danger {
            background: #fee2e2;
            color: #991b1b;
            border: 1px solid #fca5a5;
        }

        .alert-warning {
            background: #fef3c7;
            color: #92400e;
            border: 1px solid #fcd34d;
        }

        .alert-info {
            background: #dbeafe;
            color: #1e40af;
            border: 1px solid #93c5fd;
        }
    </style>

    @stack('styles')
</head>
<body>
    <!-- Header -->
    <header class="header">
        <div class="container">
            <nav class="navbar">
                <a href="{{ route('home') }}" class="logo">
                    📚 BookStore
                </a>
                
                <ul class="nav-menu">
                    <li><a href="{{ route('home') }}">Trang chủ</a></li>
                    <li><a href="{{ route('categories') }}">Danh mục</a></li>
                    <li><a href="{{ route('authors') }}">Tác giả</a></li>
                    <li><a href="{{ route('search') }}">Tìm kiếm</a></li>
                    
                    @auth
                        <li><a href="{{ route('cart.index') }}">Giỏ hàng</a></li>
                        <li><a href="{{ route('wishlist.index') }}">Yêu thích</a></li>
                        <li><a href="{{ route('profile') }}">Tài khoản</a></li>
                        @if(auth()->user()->vai_tro === 'admin')
                            <li><a href="{{ route('admin.dashboard') }}">Admin</a></li>
                        @endif
                        <li>
                            <form action="{{ route('logout') }}" method="POST" style="display: inline;">
                                @csrf
                                <button type="submit" class="btn btn-secondary">Đăng xuất</button>
                            </form>
                        </li>
                    @else
                        <li><a href="{{ route('login') }}">Đăng nhập</a></li>
                        <li><a href="{{ route('register') }}">Đăng ký</a></li>
                    @endauth
                </ul>
            </nav>
        </div>
    </header>

    <!-- Main Content -->
    <main class="main-content">
        <div class="container">
            <!-- Flash Messages -->
            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif

            @if(session('warning'))
                <div class="alert alert-warning">
                    {{ session('warning') }}
                </div>
            @endif

            @if(isset($errors) && $errors->any())
                <div class="alert alert-danger">
                    <ul style="margin: 0; padding-left: 1rem;">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @yield('content')
        </div>
    </main>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="footer-content">
                <div class="footer-section">
                    <h3>BookStore</h3>
                    <p>Nhà sách trực tuyến hàng đầu Việt Nam</p>
                    <p>📍 Khóm 9, Phường Nguyệt Hóa, Tỉnh Vĩnh Long</p>
                    <p>📞 0787905089</p>
                </div>
                
                <div class="footer-section">
                    <h3>Danh mục</h3>
                    <ul>
                        <li><a href="{{ route('categories') }}">Tất cả danh mục</a></li>
                        <li><a href="{{ route('authors') }}">Tác giả</a></li>
                        <li><a href="{{ route('search') }}">Tìm kiếm</a></li>
                    </ul>
                </div>
                
                <div class="footer-section">
                    <h3>Hỗ trợ</h3>
                    <ul>
                        <li><a href="{{ route('contact') }}">Liên hệ</a></li>
                        <li><a href="{{ route('about') }}">Giới thiệu</a></li>
                    </ul>
                </div>
            </div>
            
            <div class="text-center mt-4" style="border-top: 1px solid #374151; padding-top: 1rem;">
                <p>&copy; {{ date('Y') }} BookStore. Được phát triển với ❤️ bằng Laravel & MySQL</p>
            </div>
        </div>
    </footer>

    <!-- JavaScript -->
    <script>
        // CSRF Token setup
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        
        // Simple AJAX helper
        function ajaxRequest(url, method = 'GET', data = null) {
            return fetch(url, {
                method: method,
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                body: data ? JSON.stringify(data) : null
            });
        }

        // Show alert messages
        function showAlert(message, type = 'success') {
            const alertDiv = document.createElement('div');
            alertDiv.className = `alert alert-${type}`;
            alertDiv.textContent = message;
            
            const container = document.querySelector('.container');
            container.insertBefore(alertDiv, container.firstChild);
            
            setTimeout(() => {
                alertDiv.remove();
            }, 5000);
        }

        // Form validation helper
        function validateForm(formId) {
            const form = document.getElementById(formId);
            const inputs = form.querySelectorAll('input[required], select[required], textarea[required]');
            let isValid = true;
            
            inputs.forEach(input => {
                if (!input.value.trim()) {
                    input.style.borderColor = '#dc2626';
                    isValid = false;
                } else {
                    input.style.borderColor = '#d1d5db';
                }
            });
            
            return isValid;
        }
    </script>

    @stack('scripts')
</body>
</html>