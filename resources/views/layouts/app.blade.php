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
            --secondary-color: #64748b;
            --success-color: #16a34a;
            --danger-color: #dc2626;
            --warning-color: #d97706;
            --info-color: #0891b2;
            --light-color: #f8fafc;
            --dark-color: #1e293b;
        }

        body {
            background: linear-gradient(135deg, #f5f7fb 0%, #e3ecff 45%, #fdfbff 100%);
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .navbar {
            box-shadow: 0 2px 8px rgba(15, 23, 42, 0.15);
            backdrop-filter: blur(10px);
        }

        .navbar-brand {
            font-weight: 700;
            font-size: 1.5rem;
        }

        .card-modern {
            border-radius: 16px;
            border: none;
            box-shadow: 0 10px 30px rgba(15, 23, 42, 0.08);
            background: white;
        }

        .card-modern .card-header {
            border-bottom: 1px solid #edf2ff;
            background: white;
            border-radius: 16px 16px 0 0 !important;
            padding: 1.5rem;
        }

        .card-modern .card-body {
            background: white;
            border-radius: 0 0 16px 16px !important;
            padding: 1.5rem;
        }

        .table thead {
            background: var(--dark-color);
            color: white;
        }

        .table thead th {
            border: none;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.875rem;
            letter-spacing: 0.5px;
        }

        .table tbody tr {
            transition: all 0.2s ease;
        }

        .table tbody tr:hover {
            background-color: #f8fafc;
            transform: translateY(-1px);
        }

        .btn {
            border-radius: 8px;
            font-weight: 500;
            transition: all 0.2s ease;
        }

        .btn:hover {
            transform: translateY(-1px);
        }

        .btn-primary {
            background: var(--primary-color);
            border-color: var(--primary-color);
        }

        .btn-success {
            background: var(--success-color);
            border-color: var(--success-color);
        }

        .btn-danger {
            background: var(--danger-color);
            border-color: var(--danger-color);
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

        .form-control, .form-select {
            border-radius: 8px;
            border: 1px solid #e2e8f0;
            transition: all 0.2s ease;
        }

        .form-control:focus, .form-select:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.2rem rgba(37, 99, 235, 0.25);
        }

        .modal-content {
            border-radius: 16px;
            border: none;
            box-shadow: 0 20px 60px rgba(15, 23, 42, 0.2);
        }

        .modal-header {
            border-bottom: 1px solid #e2e8f0;
            border-radius: 16px 16px 0 0;
        }

        .modal-footer {
            border-top: 1px solid #e2e8f0;
            border-radius: 0 0 16px 16px;
        }

        .badge {
            font-weight: 500;
            padding: 0.5em 0.75em;
        }

        .stats-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 12px;
            padding: 1.5rem;
            margin-bottom: 1rem;
        }

        .stats-card .stats-number {
            font-size: 2rem;
            font-weight: 700;
        }

        .stats-card .stats-label {
            opacity: 0.9;
            font-size: 0.875rem;
        }

        /* Toast Notifications */
        .toast-container {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 9999;
        }

        .toast {
            border-radius: 12px;
            border: none;
            box-shadow: 0 10px 30px rgba(15, 23, 42, 0.2);
        }

        .toast-success {
            background: linear-gradient(135deg, #16a34a, #22c55e);
            color: white;
        }

        .toast-danger {
            background: linear-gradient(135deg, #dc2626, #ef4444);
            color: white;
        }

        .toast-info {
            background: linear-gradient(135deg, #0891b2, #06b6d4);
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

        /* Enhanced Table Styling */
        .table-responsive {
            border-radius: 12px;
            overflow: hidden;
        }

        .table {
            margin-bottom: 0;
        }

        .table thead th {
            position: sticky;
            top: 0;
            z-index: 10;
            background: linear-gradient(135deg, #1e293b, #334155);
            color: white;
            padding: 1rem 0.75rem;
            vertical-align: middle;
        }

        .table tbody td {
            padding: 1rem 0.75rem;
            vertical-align: middle;
        }

        .table tbody tr:last-child td {
            border-bottom: none;
        }

        /* Card Enhancements */
        .card-header {
            background: linear-gradient(135deg, #f8fafc, #ffffff);
            border-bottom: 2px solid #e2e8f0;
        }

        /* Badge Improvements */
        .badge {
            border-radius: 6px;
            font-size: 0.8125rem;
        }

        /* Button Group Improvements */
        .btn-group-sm > .btn {
            padding: 0.375rem 0.625rem;
            font-size: 0.8125rem;
        }

        /* Form Control Improvements */
        .form-label {
            font-weight: 500;
            color: #475569;
            margin-bottom: 0.5rem;
        }

        /* Statistics Card Improvements */
        .border-left-primary {
            border-left: 4px solid var(--primary-color) !important;
        }

        .border-left-success {
            border-left: 4px solid var(--success-color) !important;
        }

        .border-left-warning {
            border-left: 4px solid var(--warning-color) !important;
        }

        .border-left-info {
            border-left: 4px solid var(--info-color) !important;
        }

        /* Breadcrumb Styling */
        .breadcrumb {
            background: transparent;
            padding: 0;
            margin-bottom: 0;
            font-size: 0.875rem;
        }

        .breadcrumb-item + .breadcrumb-item::before {
            content: "›";
            color: #94a3b8;
        }

        .breadcrumb-item a {
            color: var(--primary-color);
            text-decoration: none;
        }

        .breadcrumb-item a:hover {
            text-decoration: underline;
        }

        .breadcrumb-item.active {
            color: #64748b;
        }

        /* Empty State Styling */
        .text-center.py-5 {
            padding: 3rem 1rem !important;
        }

        .text-center.py-5 i {
            opacity: 0.5;
        }

        /* Dropdown Menu Improvements */
        .dropdown-menu {
            border-radius: 12px;
            border: none;
            box-shadow: 0 10px 30px rgba(15, 23, 42, 0.15);
            padding: 0.5rem;
        }

        .dropdown-item {
            border-radius: 8px;
            padding: 0.5rem 1rem;
            transition: all 0.2s ease;
        }

        .dropdown-item:hover {
            background-color: #f1f5f9;
            transform: translateX(4px);
        }

        .dropdown-divider {
            margin: 0.5rem 0;
            border-color: #e2e8f0;
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