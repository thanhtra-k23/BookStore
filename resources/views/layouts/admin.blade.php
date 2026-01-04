<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin - BookStore')</title>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    
    <!-- SweetAlert2 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            --sidebar-width: 260px;
            --header-height: 65px;
            --primary: #2563eb;
            --primary-dark: #1d4ed8;
            --success: #16a34a;
            --danger: #dc2626;
            --warning: #d97706;
            --info: #0891b2;
            --dark: #1e293b;
            --light: #f8fafc;
            --gray: #64748b;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #f1f5f9 0%, #e2e8f0 100%);
            min-height: 100vh;
        }

        /* Sidebar */
        .admin-sidebar {
            position: fixed;
            left: 0;
            top: 0;
            width: var(--sidebar-width);
            height: 100vh;
            background: linear-gradient(180deg, #1e293b 0%, #0f172a 100%);
            color: white;
            z-index: 1000;
            overflow-y: auto;
            transition: transform 0.3s ease;
        }

        .sidebar-header {
            padding: 1.5rem;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .sidebar-logo {
            font-size: 1.5rem;
        }

        .sidebar-brand {
            font-size: 1.25rem;
            font-weight: 700;
            background: linear-gradient(135deg, #60a5fa, #a78bfa);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .sidebar-nav {
            padding: 1rem 0;
        }

        .nav-section {
            padding: 0.5rem 1.5rem;
            font-size: 0.75rem;
            text-transform: uppercase;
            color: #64748b;
            letter-spacing: 0.5px;
            margin-top: 1rem;
        }

        .nav-item {
            display: block;
            padding: 0.75rem 1.5rem;
            color: #94a3b8;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            transition: all 0.2s ease;
            border-left: 3px solid transparent;
        }

        .nav-item:hover {
            background: rgba(255,255,255,0.05);
            color: white;
            border-left-color: var(--primary);
        }

        .nav-item.active {
            background: rgba(37, 99, 235, 0.2);
            color: white;
            border-left-color: var(--primary);
        }

        .nav-item i {
            width: 20px;
            text-align: center;
        }

        .nav-item .badge {
            margin-left: auto;
            background: var(--danger);
            padding: 0.2rem 0.5rem;
            border-radius: 10px;
            font-size: 0.7rem;
        }

        /* Header */
        .admin-header {
            position: fixed;
            top: 0;
            left: var(--sidebar-width);
            right: 0;
            height: var(--header-height);
            background: white;
            box-shadow: 0 2px 10px rgba(0,0,0,0.08);
            z-index: 999;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 1.5rem;
        }

        .header-left {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .menu-toggle {
            display: none;
            background: none;
            border: none;
            font-size: 1.25rem;
            color: var(--dark);
            cursor: pointer;
        }

        .header-search {
            position: relative;
        }

        .header-search input {
            padding: 0.5rem 1rem 0.5rem 2.5rem;
            border: 2px solid #e2e8f0;
            border-radius: 10px;
            width: 300px;
            font-size: 0.9rem;
            transition: all 0.3s;
        }

        .header-search input:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
        }

        .header-search i {
            position: absolute;
            left: 0.75rem;
            top: 50%;
            transform: translateY(-50%);
            color: var(--gray);
        }

        .header-right {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .header-datetime {
            text-align: right;
            padding: 0.5rem 1rem;
            background: linear-gradient(135deg, #f8fafc, #f1f5f9);
            border-radius: 10px;
        }

        .header-time {
            font-size: 1.1rem;
            font-weight: 700;
            color: var(--dark);
        }

        .header-date {
            font-size: 0.8rem;
            color: var(--gray);
        }

        .header-btn {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            border: none;
            background: #f1f5f9;
            color: var(--gray);
            cursor: pointer;
            position: relative;
            transition: all 0.2s;
        }

        .header-btn:hover {
            background: var(--primary);
            color: white;
        }

        .header-btn .badge {
            position: absolute;
            top: -5px;
            right: -5px;
            background: var(--danger);
            color: white;
            font-size: 0.65rem;
            padding: 0.15rem 0.4rem;
            border-radius: 10px;
        }

        .header-user {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.5rem;
            border-radius: 10px;
            cursor: pointer;
            transition: background 0.2s;
        }

        .header-user:hover {
            background: #f1f5f9;
        }

        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            background: linear-gradient(135deg, var(--primary), #7c3aed);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
        }

        .user-info {
            text-align: left;
        }

        .user-name {
            font-weight: 600;
            color: var(--dark);
            font-size: 0.9rem;
        }

        .user-role {
            font-size: 0.75rem;
            color: var(--gray);
        }

        /* Main Content */
        .admin-main {
            margin-left: var(--sidebar-width);
            margin-top: var(--header-height);
            padding: 1.5rem;
            min-height: calc(100vh - var(--header-height));
        }

        /* Quick Stats Bar */
        .quick-stats {
            display: flex;
            gap: 1rem;
            margin-bottom: 1.5rem;
            flex-wrap: wrap;
        }

        .quick-stat {
            flex: 1;
            min-width: 200px;
            background: white;
            border-radius: 12px;
            padding: 1rem 1.25rem;
            display: flex;
            align-items: center;
            gap: 1rem;
            box-shadow: 0 2px 8px rgba(0,0,0,0.06);
            transition: all 0.3s;
        }

        .quick-stat:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(0,0,0,0.1);
        }

        .stat-icon {
            width: 50px;
            height: 50px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
            color: white;
        }

        .stat-icon.primary { background: linear-gradient(135deg, #2563eb, #3b82f6); }
        .stat-icon.success { background: linear-gradient(135deg, #16a34a, #22c55e); }
        .stat-icon.warning { background: linear-gradient(135deg, #d97706, #f59e0b); }
        .stat-icon.danger { background: linear-gradient(135deg, #dc2626, #ef4444); }

        .stat-info h4 {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--dark);
            margin: 0;
        }

        .stat-info p {
            font-size: 0.85rem;
            color: var(--gray);
            margin: 0;
        }

        /* Cards */
        .card {
            background: white;
            border-radius: 16px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.06);
            margin-bottom: 1.5rem;
        }

        .card-header {
            padding: 1.25rem;
            border-bottom: 1px solid #e2e8f0;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .card-title {
            font-size: 1.1rem;
            font-weight: 600;
            color: var(--dark);
            margin: 0;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .card-body {
            padding: 1.25rem;
        }

        /* Buttons */
        .btn {
            padding: 0.6rem 1.25rem;
            border-radius: 10px;
            border: none;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            text-decoration: none;
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.15);
        }

        .btn-primary { background: linear-gradient(135deg, #2563eb, #3b82f6); color: white; }
        .btn-success { background: linear-gradient(135deg, #16a34a, #22c55e); color: white; }
        .btn-danger { background: linear-gradient(135deg, #dc2626, #ef4444); color: white; }
        .btn-warning { background: linear-gradient(135deg, #d97706, #f59e0b); color: white; }
        .btn-secondary { background: #64748b; color: white; }
        .btn-sm { padding: 0.4rem 0.8rem; font-size: 0.85rem; }

        /* Tables */
        .table {
            width: 100%;
            border-collapse: collapse;
        }

        .table th {
            background: linear-gradient(135deg, #f8fafc, #f1f5f9);
            padding: 1rem;
            text-align: left;
            font-weight: 600;
            color: var(--dark);
            font-size: 0.85rem;
            text-transform: uppercase;
        }

        .table td {
            padding: 1rem;
            border-bottom: 1px solid #f1f5f9;
            color: var(--dark);
        }

        .table tr:hover td {
            background: #f8fafc;
        }

        /* Badge */
        .badge {
            padding: 0.35rem 0.75rem;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
        }

        .badge-success, .bg-success { background: #dcfce7 !important; color: #16a34a !important; }
        .badge-warning, .bg-warning { background: #fef3c7 !important; color: #d97706 !important; }
        .badge-danger, .bg-danger { background: #fee2e2 !important; color: #dc2626 !important; }
        .badge-info, .bg-info { background: #e0f2fe !important; color: #0891b2 !important; }
        .badge-primary, .bg-primary { background: #dbeafe !important; color: #2563eb !important; }
        .badge-secondary, .bg-secondary { background: #f1f5f9 !important; color: #64748b !important; }

        /* Form Controls - Modern Style */
        .form-label {
            font-weight: 600;
            font-size: 0.85rem;
            color: var(--dark);
            margin-bottom: 0.5rem;
            display: block;
        }

        .form-control, .form-select {
            padding: 0.65rem 1rem;
            border: 2px solid #e2e8f0;
            border-radius: 10px;
            font-size: 0.9rem;
            transition: all 0.2s ease;
            background-color: #fff;
            width: 100%;
        }

        .form-control:focus, .form-select:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.1);
        }

        .form-control::placeholder {
            color: #94a3b8;
        }

        .form-select {
            cursor: pointer;
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3e%3c/svg%3e");
            background-position: right 0.75rem center;
            background-repeat: no-repeat;
            background-size: 1.25em 1.25em;
            padding-right: 2.5rem;
            -webkit-appearance: none;
            -moz-appearance: none;
            appearance: none;
        }

        .form-check-input {
            width: 1.1rem;
            height: 1.1rem;
            border: 2px solid #cbd5e1;
            border-radius: 4px;
            cursor: pointer;
            transition: all 0.2s;
        }

        .form-check-input:checked {
            background-color: var(--primary);
            border-color: var(--primary);
        }

        .form-check-input:focus {
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.2);
        }

        /* Form Switch */
        .form-switch .form-check-input {
            width: 2.5rem;
            height: 1.25rem;
            border-radius: 1rem;
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='-4 -4 8 8'%3e%3ccircle r='3' fill='%23fff'/%3e%3c/svg%3e");
            background-position: left center;
            transition: all 0.2s ease;
        }

        .form-switch .form-check-input:checked {
            background-position: right center;
            background-color: var(--success);
            border-color: var(--success);
        }

        /* Filter Card */
        .filter-card {
            background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
            border: 1px solid #e2e8f0;
            border-radius: 16px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
        }

        .filter-card .filter-title {
            font-size: 0.9rem;
            font-weight: 700;
            color: var(--dark);
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .filter-card .filter-title i {
            color: var(--primary);
        }

        /* Row and Grid */
        .row {
            display: flex;
            flex-wrap: wrap;
            margin: -0.5rem;
        }

        .row > * {
            padding: 0.5rem;
        }

        .col-md-2 { flex: 0 0 16.666667%; max-width: 16.666667%; }
        .col-md-3 { flex: 0 0 25%; max-width: 25%; }
        .col-md-4 { flex: 0 0 33.333333%; max-width: 33.333333%; }
        .col-md-6 { flex: 0 0 50%; max-width: 50%; }
        .col-md-12, .col-12 { flex: 0 0 100%; max-width: 100%; }

        .g-3 { gap: 1rem; }
        .mb-3 { margin-bottom: 1rem; }
        .mb-4 { margin-bottom: 1.5rem; }
        .me-1 { margin-right: 0.25rem; }
        .me-2 { margin-right: 0.5rem; }
        .ms-1 { margin-left: 0.25rem; }
        .mt-2 { margin-top: 0.5rem; }
        .py-2 { padding-top: 0.5rem; padding-bottom: 0.5rem; }
        .py-3 { padding-top: 1rem; padding-bottom: 1rem; }
        .py-5 { padding-top: 3rem; padding-bottom: 3rem; }

        .d-flex { display: flex; }
        .d-block { display: block; }
        .d-none { display: none; }
        .align-items-center { align-items: center; }
        .justify-content-between { justify-content: space-between; }
        .justify-content-center { justify-content: center; }
        .flex-wrap { flex-wrap: wrap; }
        .gap-2 { gap: 0.5rem; }
        .gap-3 { gap: 1rem; }

        .text-center { text-align: center; }
        .text-muted { color: #64748b !important; }
        .text-primary { color: var(--primary) !important; }
        .text-success { color: var(--success) !important; }
        .text-danger { color: var(--danger) !important; }
        .text-warning { color: var(--warning) !important; }
        .fw-bold { font-weight: 700; }
        .small { font-size: 0.85rem; }

        .text-decoration-line-through { text-decoration: line-through; }

        /* Button Group */
        .btn-group {
            display: inline-flex;
            border-radius: 10px;
            overflow: hidden;
        }

        .btn-group .btn {
            border-radius: 0;
            margin: 0;
        }

        .btn-group .btn:first-child {
            border-radius: 10px 0 0 10px;
        }

        .btn-group .btn:last-child {
            border-radius: 0 10px 10px 0;
        }

        .btn-group-sm .btn {
            padding: 0.35rem 0.6rem;
            font-size: 0.8rem;
        }

        .btn-outline-primary { background: transparent; border: 2px solid var(--primary); color: var(--primary); }
        .btn-outline-primary:hover { background: var(--primary); color: white; }
        .btn-outline-secondary { background: transparent; border: 2px solid #64748b; color: #64748b; }
        .btn-outline-secondary:hover { background: #64748b; color: white; }
        .btn-outline-info { background: transparent; border: 2px solid var(--info); color: var(--info); }
        .btn-outline-info:hover { background: var(--info); color: white; }
        .btn-outline-warning { background: transparent; border: 2px solid var(--warning); color: var(--warning); }
        .btn-outline-warning:hover { background: var(--warning); color: white; }
        .btn-outline-danger { background: transparent; border: 2px solid var(--danger); color: var(--danger); }
        .btn-outline-danger:hover { background: var(--danger); color: white; }

        /* Table Improvements */
        .table-responsive {
            overflow-x: auto;
            border-radius: 12px;
        }

        .table-hover tbody tr:hover {
            background-color: #f8fafc;
        }

        .table .align-middle {
            vertical-align: middle;
        }

        .img-thumbnail {
            border: 2px solid #e2e8f0;
            border-radius: 8px;
            padding: 2px;
        }

        /* Pagination */
        .pagination {
            display: flex;
            list-style: none;
            padding: 0;
            margin: 1.5rem 0 0;
            gap: 0.25rem;
            justify-content: center;
        }

        .pagination li a,
        .pagination li span {
            display: flex;
            align-items: center;
            justify-content: center;
            min-width: 36px;
            height: 36px;
            padding: 0 0.75rem;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 500;
            font-size: 0.9rem;
            transition: all 0.2s;
            background: white;
            color: var(--dark);
            border: 1px solid #e2e8f0;
        }

        .pagination li a:hover {
            background: var(--primary);
            color: white;
            border-color: var(--primary);
        }

        .pagination li.active span {
            background: var(--primary);
            color: white;
            border-color: var(--primary);
        }

        .pagination li.disabled span {
            color: #cbd5e1;
            cursor: not-allowed;
        }

        /* Statistics Cards */
        .border-left-primary { border-left: 4px solid var(--primary) !important; }
        .border-left-success { border-left: 4px solid var(--success) !important; }
        .border-left-warning { border-left: 4px solid var(--warning) !important; }
        .border-left-danger { border-left: 4px solid var(--danger) !important; }
        .border-left-info { border-left: 4px solid var(--info) !important; }

        .shadow { box-shadow: 0 4px 15px rgba(0,0,0,0.08) !important; }
        .h-100 { height: 100%; }
        .no-gutters { margin: 0; }
        .no-gutters > * { padding: 0; }
        .mr-2 { margin-right: 0.5rem; }
        .col { flex: 1; }
        .col-auto { flex: 0 0 auto; }
        .text-xs { font-size: 0.75rem; }
        .font-weight-bold { font-weight: 700; }
        .text-uppercase { text-transform: uppercase; }
        .text-gray-300 { color: #cbd5e1; }
        .text-gray-800 { color: #1e293b; }
        .h5 { font-size: 1.25rem; }
        .fa-2x { font-size: 2rem; }
        .m-0 { margin: 0; }

        .col-xl-3 { flex: 0 0 25%; max-width: 25%; }
        .col-md-6 { flex: 0 0 50%; max-width: 50%; }

        @media (max-width: 1200px) {
            .col-xl-3 { flex: 0 0 50%; max-width: 50%; }
        }

        @media (max-width: 768px) {
            .col-md-2, .col-md-3, .col-md-4, .col-md-6, .col-xl-3 {
                flex: 0 0 100%;
                max-width: 100%;
            }
        }

        /* Dropdown */
        .dropdown {
            position: relative;
        }

        .dropdown-menu {
            position: absolute;
            top: 100%;
            right: 0;
            background: white;
            border-radius: 12px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.15);
            min-width: 200px;
            padding: 0.5rem;
            display: none;
            z-index: 1001;
        }

        .dropdown-menu.show {
            display: block;
            animation: fadeIn 0.2s ease;
        }

        .dropdown-item {
            display: block;
            padding: 0.6rem 1rem;
            color: var(--dark);
            text-decoration: none;
            border-radius: 8px;
            transition: all 0.2s;
        }

        .dropdown-item:hover {
            background: #f1f5f9;
            color: var(--primary);
        }

        .dropdown-divider {
            height: 1px;
            background: #e2e8f0;
            margin: 0.5rem 0;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* Responsive */
        @media (max-width: 1024px) {
            .admin-sidebar {
                transform: translateX(-100%);
            }

            .admin-sidebar.show {
                transform: translateX(0);
            }

            .admin-header {
                left: 0;
            }

            .admin-main {
                margin-left: 0;
            }

            .menu-toggle {
                display: block;
            }

            .header-search input {
                width: 200px;
            }
        }

        @media (max-width: 768px) {
            .header-datetime {
                display: none;
            }

            .user-info {
                display: none;
            }

            .quick-stats {
                flex-direction: column;
            }

            .quick-stat {
                min-width: 100%;
            }
        }

        /* Scrollbar */
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

        .admin-sidebar::-webkit-scrollbar-thumb {
            background: rgba(255,255,255,0.2);
        }

        /* Toast */
        .toast-container {
            position: fixed;
            top: 80px;
            right: 20px;
            z-index: 9999;
        }

        .toast {
            background: white;
            border-radius: 12px;
            padding: 1rem 1.5rem;
            box-shadow: 0 10px 30px rgba(0,0,0,0.15);
            margin-bottom: 0.5rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            animation: slideIn 0.3s ease;
        }

        .toast.success { border-left: 4px solid var(--success); }
        .toast.danger { border-left: 4px solid var(--danger); }
        .toast.warning { border-left: 4px solid var(--warning); }
        .toast.info { border-left: 4px solid var(--info); }

        @keyframes slideIn {
            from { transform: translateX(100%); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }
    </style>
    @stack('styles')
</head>
<body>

    <!-- Sidebar -->
    <aside class="admin-sidebar" id="sidebar">
        <div class="sidebar-header">
            <span class="sidebar-logo">📚</span>
            <span class="sidebar-brand">BookStore Admin</span>
        </div>
        
        <nav class="sidebar-nav">
            <a href="{{ route('admin.dashboard') }}" class="nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <i class="fas fa-tachometer-alt"></i>
                <span>Dashboard</span>
            </a>
            
            <div class="nav-section">Quản lý Kho sách</div>
            <a href="{{ route('admin.sach.index') }}" class="nav-item {{ request()->routeIs('admin.sach.*') ? 'active' : '' }}">
                <i class="fas fa-book"></i>
                <span>Sách</span>
            </a>
            <a href="{{ route('admin.theloai.index') }}" class="nav-item {{ request()->routeIs('admin.theloai.*') ? 'active' : '' }}">
                <i class="fas fa-folder"></i>
                <span>Thể loại</span>
            </a>
            <a href="{{ route('admin.tacgia.index') }}" class="nav-item {{ request()->routeIs('admin.tacgia.*') ? 'active' : '' }}">
                <i class="fas fa-user-edit"></i>
                <span>Tác giả</span>
            </a>
            <a href="{{ route('admin.nhaxuatban.index') }}" class="nav-item {{ request()->routeIs('admin.nhaxuatban.*') ? 'active' : '' }}">
                <i class="fas fa-building"></i>
                <span>Nhà xuất bản</span>
            </a>
            
            <div class="nav-section">Đơn hàng & Khách hàng</div>
            <a href="{{ route('admin.donhang.index') }}" class="nav-item {{ request()->routeIs('admin.donhang.*') ? 'active' : '' }}">
                <i class="fas fa-shopping-cart"></i>
                <span>Đơn hàng</span>
                <span class="badge" id="pendingOrders">0</span>
            </a>
            <a href="{{ route('admin.nguoidung.index') }}" class="nav-item {{ request()->routeIs('admin.nguoidung.*') ? 'active' : '' }}">
                <i class="fas fa-users"></i>
                <span>Người dùng</span>
            </a>
            
            <div class="nav-section">Marketing</div>
            <a href="{{ route('admin.magiamgia.index') }}" class="nav-item {{ request()->routeIs('admin.magiamgia.*') ? 'active' : '' }}">
                <i class="fas fa-tags"></i>
                <span>Mã giảm giá</span>
            </a>
            
            <div class="nav-section">Hệ thống</div>
            <a href="{{ route('home') }}" class="nav-item" target="_blank">
                <i class="fas fa-external-link-alt"></i>
                <span>Xem Website</span>
            </a>
            <a href="#" class="nav-item" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                <i class="fas fa-sign-out-alt"></i>
                <span>Đăng xuất</span>
            </a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">@csrf</form>
        </nav>
    </aside>

    <!-- Header -->
    <header class="admin-header">
        <div class="header-left">
            <button class="menu-toggle" onclick="toggleSidebar()">
                <i class="fas fa-bars"></i>
            </button>
            <div class="header-search">
                <i class="fas fa-search"></i>
                <input type="text" placeholder="Tìm kiếm..." id="globalSearch">
            </div>
        </div>
        
        <div class="header-right">
            <div class="header-datetime">
                <div class="header-time" id="currentTime">--:--:--</div>
                <div class="header-date" id="currentDate">--/--/----</div>
            </div>
            
            <a href="{{ route('cart.index') }}" class="header-btn" title="Giỏ hàng" style="text-decoration: none;">
                <i class="fas fa-shopping-cart"></i>
            </a>
            
            <div class="dropdown">
                <button class="header-btn" onclick="toggleDropdown('notifDropdown')" title="Thông báo">
                    <i class="fas fa-bell"></i>
                    <span class="badge" id="notifCount">0</span>
                </button>
                <div class="dropdown-menu" id="notifDropdown">
                    <div style="padding: 0.75rem; font-weight: 600; border-bottom: 1px solid #e2e8f0;">
                        Thông báo
                    </div>
                    <div id="notifList" style="max-height: 300px; overflow-y: auto;">
                        <div style="padding: 1rem; text-align: center; color: #64748b;">
                            Không có thông báo mới
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="dropdown">
                <div class="header-user" onclick="toggleDropdown('userDropdown')">
                    <div class="user-avatar">
                        {{ substr(auth()->user()->ho_ten ?? 'A', 0, 1) }}
                    </div>
                    <div class="user-info">
                        <div class="user-name">{{ auth()->user()->ho_ten ?? 'Admin' }}</div>
                        <div class="user-role">Quản trị viên</div>
                    </div>
                    <i class="fas fa-chevron-down" style="color: #64748b; font-size: 0.75rem;"></i>
                </div>
                <div class="dropdown-menu" id="userDropdown">
                    <a href="{{ route('profile') }}" class="dropdown-item">
                        <i class="fas fa-user me-2"></i> Hồ sơ cá nhân
                    </a>
                    <a href="#" class="dropdown-item">
                        <i class="fas fa-cog me-2"></i> Cài đặt
                    </a>
                    <div class="dropdown-divider"></div>
                    <a href="#" class="dropdown-item" style="color: #dc2626;" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="fas fa-sign-out-alt me-2"></i> Đăng xuất
                    </a>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="admin-main">
        <!-- Quick Stats -->
        <div class="quick-stats">
            <div class="quick-stat">
                <div class="stat-icon primary"><i class="fas fa-shopping-cart"></i></div>
                <div class="stat-info">
                    <h4 id="statOrders">0</h4>
                    <p>Đơn hàng hôm nay</p>
                </div>
            </div>
            <div class="quick-stat">
                <div class="stat-icon success"><i class="fas fa-dollar-sign"></i></div>
                <div class="stat-info">
                    <h4 id="statRevenue">0đ</h4>
                    <p>Doanh thu hôm nay</p>
                </div>
            </div>
            <div class="quick-stat">
                <div class="stat-icon warning"><i class="fas fa-users"></i></div>
                <div class="stat-info">
                    <h4 id="statUsers">0</h4>
                    <p>Khách hàng mới</p>
                </div>
            </div>
            <div class="quick-stat">
                <div class="stat-icon danger"><i class="fas fa-exclamation-triangle"></i></div>
                <div class="stat-info">
                    <h4 id="statLowStock">0</h4>
                    <p>Sách sắp hết</p>
                </div>
            </div>
        </div>

        @yield('content')
    </main>

    <!-- Toast Container -->
    <div class="toast-container" id="toastContainer">
        @if(session('tb_success'))
        <div class="toast success">
            <i class="fas fa-check-circle" style="color: var(--success);"></i>
            <span>{{ session('tb_success') }}</span>
        </div>
        @endif
        @if(session('tb_danger'))
        <div class="toast danger">
            <i class="fas fa-exclamation-circle" style="color: var(--danger);"></i>
            <span>{{ session('tb_danger') }}</span>
        </div>
        @endif
    </div>

    <!-- Overlay for mobile -->
    <div id="sidebarOverlay" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 999;" onclick="toggleSidebar()"></div>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        // Update time
        function updateDateTime() {
            const now = new Date();
            const timeStr = now.toLocaleTimeString('vi-VN', { hour: '2-digit', minute: '2-digit', second: '2-digit' });
            const dateStr = now.toLocaleDateString('vi-VN', { weekday: 'long', day: '2-digit', month: '2-digit', year: 'numeric' });
            
            document.getElementById('currentTime').textContent = timeStr;
            document.getElementById('currentDate').textContent = dateStr;
        }
        
        setInterval(updateDateTime, 1000);
        updateDateTime();

        // Toggle sidebar
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebarOverlay');
            sidebar.classList.toggle('show');
            overlay.style.display = sidebar.classList.contains('show') ? 'block' : 'none';
        }

        // Toggle dropdown
        function toggleDropdown(id) {
            const dropdown = document.getElementById(id);
            document.querySelectorAll('.dropdown-menu').forEach(d => {
                if (d.id !== id) d.classList.remove('show');
            });
            dropdown.classList.toggle('show');
        }

        // Close dropdowns when clicking outside
        document.addEventListener('click', function(e) {
            if (!e.target.closest('.dropdown')) {
                document.querySelectorAll('.dropdown-menu').forEach(d => d.classList.remove('show'));
            }
        });

        // Refresh data
        function refreshData() {
            loadStats();
            showToast('Đã làm mới dữ liệu!', 'success');
        }

        // Load stats from API
        function loadStats() {
            fetch('/admin/stats')
                .then(r => r.json())
                .then(data => {
                    document.getElementById('statOrders').textContent = data.orders_today || 0;
                    document.getElementById('statRevenue').textContent = formatCurrency(data.revenue_today || 0);
                    document.getElementById('statUsers').textContent = data.new_users || 0;
                    document.getElementById('statLowStock').textContent = data.low_stock || 0;
                    document.getElementById('pendingOrders').textContent = data.pending_orders || 0;
                    
                    if (data.pending_orders > 0) {
                        document.getElementById('pendingOrders').style.display = 'inline';
                    } else {
                        document.getElementById('pendingOrders').style.display = 'none';
                    }
                })
                .catch(err => console.log('Stats load failed'));
        }

        // Format currency
        function formatCurrency(amount) {
            return new Intl.NumberFormat('vi-VN').format(amount) + 'đ';
        }

        // Show toast
        function showToast(message, type = 'success') {
            const container = document.getElementById('toastContainer');
            const toast = document.createElement('div');
            toast.className = 'toast ' + type;
            toast.innerHTML = `
                <i class="fas fa-${type === 'success' ? 'check' : type === 'danger' ? 'exclamation' : 'info'}-circle" style="color: var(--${type});"></i>
                <span>${message}</span>
            `;
            container.appendChild(toast);
            
            setTimeout(() => {
                toast.style.animation = 'slideIn 0.3s ease reverse';
                setTimeout(() => toast.remove(), 300);
            }, 3000);
        }

        // Auto-hide toasts
        document.querySelectorAll('.toast').forEach(toast => {
            setTimeout(() => {
                toast.style.animation = 'slideIn 0.3s ease reverse';
                setTimeout(() => toast.remove(), 300);
            }, 5000);
        });

        // Global search
        document.getElementById('globalSearch').addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                const query = this.value.trim();
                if (query) {
                    window.location.href = '/admin/search?q=' + encodeURIComponent(query);
                }
            }
        });

        // Load stats on page load
        document.addEventListener('DOMContentLoaded', function() {
            loadStats();
            // Refresh stats every 60 seconds
            setInterval(loadStats, 60000);
        });
    </script>
    @stack('scripts')
</body>
</html>
