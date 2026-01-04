<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>@yield('title', 'BookStore - Nhà sách online')</title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('css/admin-style.css') }}">
    <style>
        :root {
            --primary-color: #2563eb;
            --primary-dark: #1d4ed8;
            --primary-light: #3b82f6;
            --secondary-color: #64748b;
            --success-color: #16a34a;
            --danger-color: #dc2626;
            --warning-color: #d97706;
            --info-color: #0891b2;
            --light-color: #f8fafc;
            --dark-color: #1e293b;
            --gradient-primary: linear-gradient(135deg, #2563eb 0%, #7c3aed 100%);
            --gradient-secondary: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --gradient-success: linear-gradient(135deg, #16a34a 0%, #22c55e 100%);
            --gradient-danger: linear-gradient(135deg, #dc2626 0%, #ef4444 100%);
            --gradient-warning: linear-gradient(135deg, #d97706 0%, #f59e0b 100%);
            --gradient-info: linear-gradient(135deg, #0891b2 0%, #06b6d4 100%);
            --shadow-sm: 0 2px 8px rgba(15, 23, 42, 0.08);
            --shadow-md: 0 4px 15px rgba(15, 23, 42, 0.12);
            --shadow-lg: 0 10px 30px rgba(15, 23, 42, 0.15);
        }

        body {
            background: linear-gradient(135deg, #f5f7fb 0%, #e8f0fe 50%, #fdfbff 100%);
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        /* Enhanced Navbar */
        .navbar {
            background: linear-gradient(135deg, var(--dark-color) 0%, #334155 100%) !important;
            box-shadow: var(--shadow-lg);
            backdrop-filter: blur(10px);
        }

        .navbar-brand {
            font-weight: 700;
            font-size: 1.5rem;
            background: linear-gradient(135deg, #60a5fa, #a78bfa);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .navbar .nav-link {
            transition: all 0.3s ease;
            border-radius: 8px;
            margin: 0 2px;
        }

        .navbar .nav-link:hover {
            background: rgba(255, 255, 255, 0.1);
            transform: translateY(-2px);
        }

        /* Enhanced Card Styling */
        .card-modern {
            border-radius: 20px;
            border: none;
            box-shadow: var(--shadow-md);
            background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
            transition: all 0.3s ease;
        }

        .card-modern:hover {
            transform: translateY(-4px);
            box-shadow: var(--shadow-lg);
        }

        .card-modern .card-header {
            border-bottom: 2px solid #e2e8f0;
            background: linear-gradient(135deg, #f8fafc, #ffffff);
            border-radius: 20px 20px 0 0 !important;
            padding: 1.5rem;
        }

        .card-modern .card-body {
            background: white;
            border-radius: 0 0 20px 20px !important;
            padding: 1.5rem;
        }

        /* Enhanced Table Styling */
        .table-responsive {
            border-radius: 16px;
            overflow: hidden;
            box-shadow: var(--shadow-sm);
        }

        .table {
            margin-bottom: 0;
        }

        .table thead {
            background: linear-gradient(135deg, var(--dark-color), #334155);
        }

        .table thead th {
            border: none;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.85rem;
            letter-spacing: 0.5px;
            color: white;
            padding: 1rem 0.85rem;
            position: sticky;
            top: 0;
            z-index: 10;
        }

        .table tbody tr {
            transition: all 0.2s ease;
            border-bottom: 1px solid #f1f5f9;
        }

        .table tbody tr:hover {
            background: linear-gradient(135deg, #f8fafc, #ffffff);
            transform: translateX(4px);
            box-shadow: var(--shadow-sm);
        }

        .table tbody td {
            padding: 1rem 0.85rem;
            vertical-align: middle;
        }

        .table tbody tr:last-child td {
            border-bottom: none;
        }

        /* Enhanced Button Styling */
        .btn {
            border-radius: 10px;
            font-weight: 600;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
            transition: left 0.5s;
        }

        .btn:hover::before {
            left: 100%;
        }

        .btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.2);
        }

        .btn-primary {
            background: var(--gradient-primary);
            border: none;
        }

        .btn-success {
            background: var(--gradient-success);
            border: none;
        }

        .btn-danger {
            background: var(--gradient-danger);
            border: none;
        }

        .btn-warning {
            background: var(--gradient-warning);
            border: none;
            color: white;
        }

        .btn-info {
            background: var(--gradient-info);
            border: none;
            color: white;
        }

        .btn-outline-primary {
            border: 2px solid var(--primary-color);
            color: var(--primary-color);
        }

        .btn-outline-primary:hover {
            background: var(--gradient-primary);
            border-color: transparent;
            color: white;
        }

        .btn-outline-success {
            border: 2px solid var(--success-color);
            color: var(--success-color);
        }

        .btn-outline-success:hover {
            background: var(--gradient-success);
            border-color: transparent;
            color: white;
        }

        .btn-outline-danger {
            border: 2px solid var(--danger-color);
            color: var(--danger-color);
        }

        .btn-outline-danger:hover {
            background: var(--gradient-danger);
            border-color: transparent;
            color: white;
        }

        .btn-outline-warning {
            border: 2px solid var(--warning-color);
            color: var(--warning-color);
        }

        .btn-outline-warning:hover {
            background: var(--gradient-warning);
            border-color: transparent;
            color: white;
        }

        .btn-outline-info {
            border: 2px solid var(--info-color);
            color: var(--info-color);
        }

        .btn-outline-info:hover {
            background: var(--gradient-info);
            border-color: transparent;
            color: white;
        }

        .action-btn i {
            cursor: pointer;
            font-size: 16px;
            margin-right: 8px;
            transition: all 0.15s ease;
            padding: 4px;
            border-radius: 4px;
        }

        .action-btn i:hover {
            transform: translateY(-1px) scale(1.1);
        }

        /* Enhanced Form Controls */
        .form-control, .form-select {
            border-radius: 10px;
            border: 2px solid #e2e8f0;
            transition: all 0.3s ease;
            padding: 0.75rem 1rem;
            background: linear-gradient(135deg, #ffffff, #f8fafc);
        }

        .form-control:focus, .form-select:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.15);
            background: white;
        }

        .form-label {
            font-weight: 600;
            color: #374151;
            margin-bottom: 0.5rem;
        }

        /* Enhanced Modal */
        .modal-content {
            border-radius: 20px;
            border: none;
            box-shadow: 0 25px 80px rgba(15, 23, 42, 0.25);
        }

        .modal-header {
            border-bottom: 2px solid #e2e8f0;
            border-radius: 20px 20px 0 0;
            background: linear-gradient(135deg, #f8fafc, #ffffff);
        }

        .modal-footer {
            border-top: 2px solid #e2e8f0;
            border-radius: 0 0 20px 20px;
            background: linear-gradient(135deg, #f8fafc, #ffffff);
        }

        /* Enhanced Badge */
        .badge {
            font-weight: 600;
            padding: 0.5em 0.85em;
            border-radius: 8px;
            font-size: 0.8rem;
        }

        .bg-primary {
            background: var(--gradient-primary) !important;
        }

        .bg-success {
            background: var(--gradient-success) !important;
        }

        .bg-danger {
            background: var(--gradient-danger) !important;
        }

        .bg-warning {
            background: var(--gradient-warning) !important;
            color: white !important;
        }

        .bg-info {
            background: var(--gradient-info) !important;
        }

        .bg-secondary {
            background: linear-gradient(135deg, #475569, #64748b) !important;
        }

        /* Enhanced Stats Cards */
        .stats-card {
            background: var(--gradient-secondary);
            color: white;
            border-radius: 16px;
            padding: 1.5rem;
            margin-bottom: 1rem;
            box-shadow: var(--shadow-md);
            transition: all 0.3s ease;
        }

        .stats-card:hover {
            transform: translateY(-4px);
            box-shadow: var(--shadow-lg);
        }

        .stats-card .stats-number {
            font-size: 2.25rem;
            font-weight: 700;
        }

        .stats-card .stats-label {
            opacity: 0.9;
            font-size: 0.9rem;
        }

        /* Statistics Card with Border */
        .border-left-primary {
            border-left: 5px solid var(--primary-color) !important;
            background: linear-gradient(135deg, #ffffff, #eff6ff);
            border-radius: 12px;
            transition: all 0.3s ease;
        }

        .border-left-primary:hover {
            transform: translateY(-4px);
            box-shadow: var(--shadow-md);
        }

        .border-left-success {
            border-left: 5px solid var(--success-color) !important;
            background: linear-gradient(135deg, #ffffff, #f0fdf4);
            border-radius: 12px;
            transition: all 0.3s ease;
        }

        .border-left-success:hover {
            transform: translateY(-4px);
            box-shadow: var(--shadow-md);
        }

        .border-left-warning {
            border-left: 5px solid var(--warning-color) !important;
            background: linear-gradient(135deg, #ffffff, #fffbeb);
            border-radius: 12px;
            transition: all 0.3s ease;
        }

        .border-left-warning:hover {
            transform: translateY(-4px);
            box-shadow: var(--shadow-md);
        }

        .border-left-info {
            border-left: 5px solid var(--info-color) !important;
            background: linear-gradient(135deg, #ffffff, #f0f9ff);
            border-radius: 12px;
            transition: all 0.3s ease;
        }

        .border-left-info:hover {
            transform: translateY(-4px);
            box-shadow: var(--shadow-md);
        }

        /* Page Header */
        .page-header {
            background: var(--gradient-primary);
            color: white;
            padding: 2rem;
            border-radius: 20px;
            margin-bottom: 2rem;
            box-shadow: var(--shadow-lg);
        }

        .page-header h1 {
            font-weight: 700;
            margin-bottom: 0.5rem;
        }

        .page-header p {
            opacity: 0.9;
            margin-bottom: 0;
        }

        /* Enhanced Toast Notifications */
        .toast-container {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 9999;
        }

        .toast {
            border-radius: 16px;
            border: none;
            box-shadow: var(--shadow-lg);
            animation: slideInRight 0.3s ease;
        }

        @keyframes slideInRight {
            from {
                opacity: 0;
                transform: translateX(50px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        .toast-success {
            background: var(--gradient-success);
            color: white;
        }

        .toast-danger {
            background: var(--gradient-danger);
            color: white;
        }

        .toast-info {
            background: var(--gradient-info);
            color: white;
        }

        .toast-warning {
            background: var(--gradient-warning);
            color: white;
        }

        /* Loading Spinner */
        .spinner-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 0.8);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 9998;
        }

        .spinner-border-custom {
            width: 3rem;
            height: 3rem;
            border-width: 0.3em;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .card-modern .card-header {
                padding: 1rem;
            }
            
            .card-modern .card-body {
                padding: 1rem;
            }
            
            .table-responsive {
                font-size: 0.875rem;
            }
        }

        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
        }

        ::-webkit-scrollbar-track {
            background: #f1f5f9;
        }

        ::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }

        /* Enhanced Dropdown Menu */
        .dropdown-menu {
            border-radius: 16px;
            border: none;
            box-shadow: var(--shadow-lg);
            padding: 0.75rem;
            background: linear-gradient(135deg, #ffffff, #f8fafc);
            animation: fadeInDown 0.2s ease;
        }

        @keyframes fadeInDown {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .dropdown-item {
            border-radius: 10px;
            padding: 0.65rem 1rem;
            transition: all 0.2s ease;
            font-weight: 500;
        }

        .dropdown-item:hover {
            background: linear-gradient(135deg, #f1f5f9, #e2e8f0);
            transform: translateX(6px);
            color: var(--primary-color);
        }

        .dropdown-item i {
            width: 20px;
            text-align: center;
        }

        .dropdown-divider {
            margin: 0.5rem 0;
            border-color: #e2e8f0;
        }

        /* Enhanced Pagination */
        .pagination {
            gap: 0.5rem;
        }

        .page-item .page-link {
            border-radius: 10px;
            border: none;
            padding: 0.6rem 1rem;
            font-weight: 500;
            transition: all 0.3s ease;
            background: white;
            color: var(--dark-color);
            box-shadow: var(--shadow-sm);
        }

        .page-item .page-link:hover {
            background: var(--primary-color);
            color: white;
            transform: translateY(-2px);
            box-shadow: var(--shadow-md);
        }

        .page-item.active .page-link {
            background: var(--gradient-primary);
            border: none;
            box-shadow: 0 4px 15px rgba(37, 99, 235, 0.4);
        }

        .page-item.disabled .page-link {
            background: #f1f5f9;
            color: #9ca3af;
        }

        /* Action Buttons */
        .action-btn i {
            cursor: pointer;
            font-size: 16px;
            margin-right: 8px;
            transition: all 0.2s ease;
            padding: 6px;
            border-radius: 6px;
        }

        .action-btn i:hover {
            transform: translateY(-2px) scale(1.15);
        }

        /* Button Group */
        .btn-group .btn {
            border-radius: 8px !important;
            margin: 0 2px;
        }

        .btn-group-sm > .btn {
            padding: 0.4rem 0.7rem;
            font-size: 0.85rem;
        }

        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 10px;
        }

        ::-webkit-scrollbar-track {
            background: #f1f5f9;
            border-radius: 5px;
        }

        ::-webkit-scrollbar-thumb {
            background: linear-gradient(135deg, #94a3b8, #64748b);
            border-radius: 5px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: linear-gradient(135deg, #64748b, #475569);
        }

        /* Breadcrumb */
        .breadcrumb {
            background: transparent;
            padding: 0;
            margin-bottom: 0;
            font-size: 0.9rem;
        }

        .breadcrumb-item + .breadcrumb-item::before {
            content: "›";
            color: #94a3b8;
            font-weight: bold;
        }

        .breadcrumb-item a {
            color: var(--primary-color);
            text-decoration: none;
            transition: color 0.2s;
        }

        .breadcrumb-item a:hover {
            color: var(--primary-dark);
            text-decoration: underline;
        }

        .breadcrumb-item.active {
            color: #64748b;
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 4rem 2rem;
        }

        .empty-state i {
            font-size: 4rem;
            color: #cbd5e1;
            margin-bottom: 1.5rem;
        }

        .empty-state h5 {
            color: var(--secondary-color);
            margin-bottom: 0.5rem;
        }

        .empty-state p {
            color: #9ca3af;
            margin-bottom: 1.5rem;
        }

        /* Loading Spinner */
        .spinner-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(4px);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 9998;
        }

        .spinner-border-custom {
            width: 3.5rem;
            height: 3.5rem;
            border-width: 0.3em;
            color: var(--primary-color);
        }

        /* Responsive */
        @media (max-width: 768px) {
            .card-modern .card-header {
                padding: 1rem;
            }
            
            .card-modern .card-body {
                padding: 1rem;
            }
            
            .table-responsive {
                font-size: 0.85rem;
            }

            .btn-group .btn {
                padding: 0.3rem 0.5rem;
            }
        }

        /* Animation Classes */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .fade-in-up {
            animation: fadeInUp 0.5s ease-out;
        }

        /* Hover Effects */
        .hover-lift {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .hover-lift:hover {
            transform: translateY(-6px);
            box-shadow: var(--shadow-lg);
        }

        /* Text Gradient */
        .text-gradient {
            background: var(--gradient-primary);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        /* Card Shadow */
        .shadow {
            box-shadow: var(--shadow-md) !important;
        }

        .shadow-lg {
            box-shadow: var(--shadow-lg) !important;
        }
    </style>

    @stack('styles')
</head>
<body>
    <!-- Navigation -->
    @include('layouts.partials.navbar')

    <!-- Main Content -->
    <main>
        @yield('content')
    </main>

    <!-- Footer -->
    @include('layouts.partials.footer')

    <!-- Loading Spinner -->
    <div id="loadingSpinner" class="spinner-overlay d-none">
        <div class="spinner-border spinner-border-custom text-primary" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
    </div>

    <!-- Toast Container -->
    <div class="toast-container">
        @if(session('tb_success'))
            <div class="toast toast-success show" role="alert">
                <div class="toast-body d-flex align-items-center">
                    <i class="fas fa-check-circle me-2"></i>
                    {{ session('tb_success') }}
                    <button type="button" class="btn-close btn-close-white ms-auto" data-bs-dismiss="toast"></button>
                </div>
            </div>
        @endif

        @if(session('tb_danger'))
            <div class="toast toast-danger show" role="alert">
                <div class="toast-body d-flex align-items-center">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    {{ session('tb_danger') }}
                    <button type="button" class="btn-close btn-close-white ms-auto" data-bs-dismiss="toast"></button>
                </div>
            </div>
        @endif

        @if(session('tb_info'))
            <div class="toast toast-info show" role="alert">
                <div class="toast-body d-flex align-items-center">
                    <i class="fas fa-info-circle me-2"></i>
                    {{ session('tb_info') }}
                    <button type="button" class="btn-close btn-close-white ms-auto" data-bs-dismiss="toast"></button>
                </div>
            </div>
        @endif

        @if($errors->any())
            <div class="toast toast-danger show" role="alert">
                <div class="toast-body">
                    <div class="d-flex align-items-center mb-2">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <strong>Vui lòng kiểm tra lại:</strong>
                    </div>
                    <ul class="mb-0 ps-3">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close btn-close-white position-absolute top-0 end-0 m-2" data-bs-dismiss="toast"></button>
                </div>
            </div>
        @endif
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    
    <script>
        // Global CSRF token setup for AJAX
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        // Auto-hide toasts after 5 seconds
        document.addEventListener('DOMContentLoaded', function() {
            const toasts = document.querySelectorAll('.toast');
            toasts.forEach(function(toast) {
                setTimeout(function() {
                    const bsToast = new bootstrap.Toast(toast);
                    bsToast.hide();
                }, 5000);
            });
        });

        // Loading spinner functions
        function showLoading() {
            document.getElementById('loadingSpinner').classList.remove('d-none');
        }

        function hideLoading() {
            document.getElementById('loadingSpinner').classList.add('d-none');
        }

        // Confirm delete function
        function confirmDelete(url, message = 'Bạn có chắc chắn muốn xóa?') {
            if (confirm(message)) {
                window.location.href = url;
            }
        }

        // Show toast notification
        function showToast(message, type = 'success') {
            const toastContainer = document.querySelector('.toast-container');
            const toastHtml = `
                <div class="toast toast-${type} show" role="alert">
                    <div class="toast-body d-flex align-items-center">
                        <i class="fas fa-${type === 'success' ? 'check' : type === 'danger' ? 'exclamation' : 'info'}-circle me-2"></i>
                        ${message}
                        <button type="button" class="btn-close btn-close-white ms-auto" data-bs-dismiss="toast"></button>
                    </div>
                </div>
            `;
            toastContainer.insertAdjacentHTML('beforeend', toastHtml);
            
            // Auto-hide after 5 seconds
            setTimeout(function() {
                const toast = toastContainer.lastElementChild;
                const bsToast = new bootstrap.Toast(toast);
                bsToast.hide();
            }, 5000);
        }
    </script>

    @stack('scripts')
</body>
</html>