<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'BookStore - Nhà sách online')</title>
    
    <style>
        /* ========== CSS RESET & VARIABLES ========== */
        * { margin: 0; padding: 0; box-sizing: border-box; }
        
        :root {
            --primary: #2563eb;
            --primary-dark: #1d4ed8;
            --secondary: #64748b;
            --success: #16a34a;
            --danger: #dc2626;
            --warning: #f59e0b;
            --dark: #1e293b;
            --light: #f8fafc;
            --gradient-primary: linear-gradient(135deg, #2563eb 0%, #7c3aed 100%);
            --gradient-orange: linear-gradient(135deg, #f59e0b 0%, #ef4444 100%);
            --shadow-sm: 0 2px 8px rgba(0,0,0,0.08);
            --shadow-md: 0 4px 15px rgba(0,0,0,0.1);
            --shadow-lg: 0 10px 40px rgba(0,0,0,0.15);
        }

        body {
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
            line-height: 1.6;
            color: #334155;
            background: #f1f5f9;
            min-height: 100vh;
        }

        .container { max-width: 1400px; margin: 0 auto; padding: 0 15px; }

        /* ========== SINGLE-LINE HEADER (Tiki/Fahasa Style) ========== */
        .header-unified {
            background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
            position: sticky;
            top: 0;
            z-index: 1000;
            box-shadow: 0 2px 20px rgba(0,0,0,0.3);
        }

        .header-main-row {
            display: flex;
            align-items: center;
            padding: 0.75rem 0;
            gap: 1.5rem;
        }

        /* Logo */
        .logo-unified {
            display: flex;
            align-items: center;
            gap: 0.6rem;
            text-decoration: none;
            flex-shrink: 0;
        }

        .logo-icon-box {
            width: 42px;
            height: 42px;
            background: var(--gradient-orange);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.4rem;
            box-shadow: 0 3px 10px rgba(245, 158, 11, 0.3);
        }

        .logo-text-box .brand-name {
            font-size: 1.35rem;
            font-weight: 800;
            background: linear-gradient(135deg, #60a5fa, #a78bfa);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            line-height: 1.1;
        }

        .logo-text-box .brand-tagline {
            font-size: 0.65rem;
            color: #94a3b8;
            letter-spacing: 0.5px;
        }

        /* Search Bar - Center & Large */
        .search-unified {
            flex: 1;
            max-width: 600px;
        }

        .search-unified form {
            display: flex;
            background: rgba(255,255,255,0.1);
            border-radius: 8px;
            border: 2px solid rgba(255,255,255,0.1);
            overflow: hidden;
            transition: all 0.3s;
        }

        .search-unified form:focus-within {
            background: rgba(255,255,255,0.15);
            border-color: rgba(96, 165, 250, 0.5);
        }

        .search-unified input {
            flex: 1;
            padding: 0.7rem 1rem;
            border: none;
            background: transparent;
            color: white;
            font-size: 0.95rem;
            outline: none;
        }

        .search-unified input::placeholder { color: #94a3b8; }

        .search-unified button {
            padding: 0.7rem 1.25rem;
            background: var(--gradient-primary);
            border: none;
            color: white;
            font-weight: 600;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 0.4rem;
            transition: all 0.3s;
        }

        .search-unified button:hover {
            filter: brightness(1.1);
        }

        /* Header Actions - Right Side */
        .header-actions-unified {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .header-btn {
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 0.4rem 0.75rem;
            border-radius: 8px;
            text-decoration: none;
            color: #cbd5e1;
            transition: all 0.3s;
            position: relative;
            min-width: 55px;
        }

        .header-btn:hover {
            background: rgba(255,255,255,0.1);
            color: white;
        }

        .header-btn .icon { font-size: 1.3rem; }
        .header-btn .label { font-size: 0.65rem; margin-top: 2px; }

        .header-btn .badge-num {
            position: absolute;
            top: 0;
            right: 0.3rem;
            background: var(--danger);
            color: white;
            font-size: 0.6rem;
            font-weight: 700;
            padding: 1px 5px;
            border-radius: 10px;
            min-width: 16px;
            text-align: center;
        }

        .header-btn.cart-highlight {
            background: var(--gradient-orange);
            color: white;
            flex-direction: row;
            gap: 0.4rem;
            padding: 0.5rem 1rem;
            border-radius: 20px;
        }

        .header-btn.cart-highlight:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 20px rgba(245, 158, 11, 0.3);
        }

        .header-btn.cart-highlight .label { margin-top: 0; font-size: 0.8rem; font-weight: 600; }

        /* User Dropdown */
        .user-dropdown {
            position: relative;
        }

        .user-dropdown-btn {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.4rem 0.75rem;
            background: rgba(255,255,255,0.1);
            border: none;
            border-radius: 20px;
            color: white;
            cursor: pointer;
            transition: all 0.3s;
        }

        .user-dropdown-btn:hover { background: rgba(255,255,255,0.2); }

        .user-avatar-small {
            width: 30px;
            height: 30px;
            background: var(--gradient-primary);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.9rem;
        }

        .user-dropdown-menu {
            display: none;
            position: absolute;
            top: calc(100% + 8px);
            right: 0;
            background: white;
            border-radius: 12px;
            box-shadow: var(--shadow-lg);
            min-width: 200px;
            padding: 0.5rem;
            z-index: 1000;
        }

        .user-dropdown-menu.show { display: block; animation: fadeIn 0.2s ease; }

        .user-dropdown-menu a,
        .user-dropdown-menu button {
            display: flex;
            align-items: center;
            gap: 0.6rem;
            padding: 0.6rem 0.75rem;
            color: #334155;
            text-decoration: none;
            border-radius: 8px;
            transition: all 0.2s;
            font-size: 0.9rem;
            background: none;
            border: none;
            width: 100%;
            text-align: left;
            cursor: pointer;
        }

        .user-dropdown-menu a:hover,
        .user-dropdown-menu button:hover {
            background: #f1f5f9;
            color: var(--primary);
        }

        .user-dropdown-menu .divider {
            height: 1px;
            background: #e2e8f0;
            margin: 0.4rem 0;
        }

        .user-dropdown-menu .logout-link { color: var(--danger); }
        .user-dropdown-menu .logout-link:hover { background: #fee2e2; }

        /* Navigation Bar - Below Header */
        .nav-bar-unified {
            background: rgba(255,255,255,0.05);
            border-top: 1px solid rgba(255,255,255,0.1);
        }

        .nav-menu-unified {
            display: flex;
            justify-content: center;
            list-style: none;
            gap: 0;
        }

        .nav-menu-unified > li { position: relative; }

        .nav-menu-unified > li > a {
            display: flex;
            align-items: center;
            gap: 0.4rem;
            padding: 0.75rem 1rem;
            color: #cbd5e1;
            text-decoration: none;
            font-weight: 500;
            font-size: 0.9rem;
            transition: all 0.3s;
            border-bottom: 2px solid transparent;
        }

        .nav-menu-unified > li > a:hover,
        .nav-menu-unified > li > a.active {
            color: white;
            background: rgba(255,255,255,0.1);
            border-bottom-color: #60a5fa;
        }

        .nav-menu-unified > li > a .nav-icon { font-size: 1rem; }

        /* Dropdown in Nav */
        .nav-dropdown-box {
            display: none;
            position: absolute;
            top: 100%;
            left: 0;
            background: white;
            border-radius: 12px;
            box-shadow: var(--shadow-lg);
            min-width: 220px;
            padding: 0.5rem;
            z-index: 1000;
        }

        .nav-menu-unified > li:hover .nav-dropdown-box {
            display: block;
            animation: fadeIn 0.2s ease;
        }

        .nav-dropdown-box a {
            display: flex;
            align-items: center;
            gap: 0.6rem;
            padding: 0.6rem 0.75rem;
            color: #334155;
            text-decoration: none;
            border-radius: 8px;
            transition: all 0.2s;
            font-size: 0.9rem;
        }

        .nav-dropdown-box a:hover {
            background: #f0f9ff;
            color: var(--primary);
            transform: translateX(3px);
        }

        .nav-dropdown-box .dropdown-icon {
            width: 32px;
            height: 32px;
            background: #f1f5f9;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* Promo Strip - Optional thin bar */
        .promo-strip {
            background: linear-gradient(90deg, #2563eb, #7c3aed);
            padding: 0.4rem 0;
            text-align: center;
            color: white;
            font-size: 0.8rem;
            font-weight: 500;
        }

        .promo-strip span { animation: pulse 2s infinite; }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-5px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.7; }
        }

        /* Mobile Responsive */
        .mobile-menu-btn {
            display: none;
            background: rgba(255,255,255,0.1);
            border: none;
            color: white;
            padding: 0.5rem;
            border-radius: 8px;
            font-size: 1.25rem;
            cursor: pointer;
        }

        @media (max-width: 1024px) {
            .search-unified { max-width: 400px; }
            .header-btn .label { display: none; }
            .header-btn { min-width: auto; padding: 0.5rem; }
            .header-btn.cart-highlight .label { display: inline; }
        }

        @media (max-width: 768px) {
            .mobile-menu-btn { display: block; }
            .nav-bar-unified { display: none; }
            .nav-bar-unified.show { display: block; }
            .nav-menu-unified { flex-direction: column; }
            .nav-dropdown-box { position: static; box-shadow: none; background: rgba(255,255,255,0.05); }
            .nav-dropdown-box a { color: #cbd5e1; }
            .search-unified { order: 3; max-width: 100%; width: 100%; margin-top: 0.75rem; }
            .header-main-row { flex-wrap: wrap; }
            .logo-text-box .brand-tagline { display: none; }
        }

        /* ========== MAIN CONTENT ========== */
        .main-content {
            min-height: calc(100vh - 200px);
            padding: 1rem 0;
        }

        /* ========== COMMON COMPONENTS ========== */
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.4rem;
            padding: 0.6rem 1.25rem;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            font-size: 0.9rem;
            cursor: pointer;
            transition: all 0.3s;
            text-decoration: none;
        }

        .btn:hover { transform: translateY(-2px); }
        .btn-primary { background: var(--gradient-primary); color: white; }
        .btn-success { background: linear-gradient(135deg, #16a34a, #22c55e); color: white; }
        .btn-danger { background: linear-gradient(135deg, #dc2626, #ef4444); color: white; }
        .btn-secondary { background: #64748b; color: white; }
        .btn-outline { background: transparent; border: 2px solid #e2e8f0; color: #64748b; }
        .btn-outline:hover { border-color: var(--primary); color: var(--primary); }

        /* Cards */
        .card {
            background: white;
            border-radius: 12px;
            box-shadow: var(--shadow-sm);
            padding: 1.25rem;
            margin-bottom: 1rem;
        }

        /* Forms */
        .form-control {
            width: 100%;
            padding: 0.7rem 1rem;
            border: 2px solid #e2e8f0;
            border-radius: 8px;
            font-size: 0.95rem;
            transition: all 0.3s;
        }

        .form-control:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
        }

        /* Tables */
        .table {
            width: 100%;
            border-collapse: collapse;
            border-radius: 10px;
            overflow: hidden;
        }

        .table th, .table td {
            padding: 0.75rem;
            text-align: left;
            border-bottom: 1px solid #e2e8f0;
        }

        .table th {
            background: var(--dark);
            color: white;
            font-weight: 600;
            font-size: 0.85rem;
        }

        .table tr:hover { background: #f8fafc; }

        /* Alerts */
        .alert {
            padding: 0.875rem 1rem;
            border-radius: 10px;
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 0.6rem;
            font-weight: 500;
        }

        .alert-success { background: #d1fae5; color: #065f46; border: 1px solid #a7f3d0; }
        .alert-danger { background: #fee2e2; color: #991b1b; border: 1px solid #fca5a5; }
        .alert-warning { background: #fef3c7; color: #92400e; border: 1px solid #fcd34d; }
        .alert-info { background: #dbeafe; color: #1e40af; border: 1px solid #93c5fd; }

        /* Pagination */
        .pagination {
            display: flex;
            justify-content: center;
            gap: 0.4rem;
            margin-top: 1.5rem;
            flex-wrap: wrap;
        }

        .pagination a, .pagination span {
            padding: 0.5rem 0.875rem;
            border-radius: 6px;
            text-decoration: none;
            font-weight: 500;
            font-size: 0.9rem;
            transition: all 0.3s;
        }

        .pagination a {
            background: white;
            color: var(--dark);
            border: 1px solid #e2e8f0;
        }

        .pagination a:hover {
            background: var(--primary);
            color: white;
            border-color: var(--primary);
        }

        .pagination .active {
            background: var(--gradient-primary);
            color: white;
            border: none;
        }

        /* Grid */
        .row { display: flex; flex-wrap: wrap; margin: 0 -0.5rem; }
        .col { flex: 1; padding: 0 0.5rem; }
        .col-6 { flex: 0 0 50%; padding: 0 0.5rem; }
        .col-4 { flex: 0 0 33.333%; padding: 0 0.5rem; }
        .col-3 { flex: 0 0 25%; padding: 0 0.5rem; }

        @media (max-width: 768px) {
            .col-6, .col-4, .col-3 { flex: 0 0 100%; }
        }

        /* Utilities */
        .text-center { text-align: center; }
        .mb-1 { margin-bottom: 0.5rem; }
        .mb-2 { margin-bottom: 1rem; }
        .mb-3 { margin-bottom: 1.5rem; }
        .mt-1 { margin-top: 0.5rem; }
        .mt-2 { margin-top: 1rem; }
        .d-flex { display: flex; }
        .justify-between { justify-content: space-between; }
        .align-center { align-items: center; }
        .gap-1 { gap: 0.5rem; }
        .gap-2 { gap: 1rem; }

        /* ========== MODERN FOOTER ========== */
        .footer-modern {
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
            color: #94a3b8;
            padding: 2.5rem 0 0;
            margin-top: 2rem;
        }

        .footer-modern::before {
            content: '';
            display: block;
            height: 3px;
            background: linear-gradient(90deg, #2563eb, #7c3aed, #ec4899, #f59e0b);
            margin-bottom: 2.5rem;
        }

        .footer-grid {
            display: grid;
            grid-template-columns: 1.5fr 1fr 1fr 1.2fr;
            gap: 2rem;
        }

        .footer-logo {
            display: flex;
            align-items: center;
            gap: 0.6rem;
            margin-bottom: 1rem;
        }

        .footer-logo-icon {
            width: 45px;
            height: 45px;
            background: var(--gradient-orange);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
        }

        .footer-brand {
            font-size: 1.4rem;
            font-weight: 800;
            background: linear-gradient(135deg, #60a5fa, #a78bfa);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .footer-desc {
            font-size: 0.9rem;
            line-height: 1.7;
            margin-bottom: 1rem;
        }

        .footer-contact-item {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin-bottom: 0.5rem;
            font-size: 0.85rem;
        }

        .footer-title {
            color: white;
            font-size: 1rem;
            font-weight: 700;
            margin-bottom: 1rem;
            padding-bottom: 0.5rem;
            border-bottom: 2px solid rgba(255,255,255,0.1);
        }

        .footer-links {
            list-style: none;
        }

        .footer-links li { margin-bottom: 0.5rem; }

        .footer-links a {
            color: #94a3b8;
            text-decoration: none;
            font-size: 0.9rem;
            transition: all 0.3s;
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
        }

        .footer-links a:hover {
            color: white;
            transform: translateX(3px);
        }

        .newsletter-form {
            display: flex;
            gap: 0.4rem;
            margin-bottom: 1rem;
        }

        .newsletter-form input {
            flex: 1;
            padding: 0.6rem 0.875rem;
            border: 2px solid rgba(255,255,255,0.1);
            border-radius: 8px;
            background: rgba(255,255,255,0.05);
            color: white;
            font-size: 0.85rem;
        }

        .newsletter-form input::placeholder { color: #64748b; }

        .newsletter-form button {
            padding: 0.6rem 1rem;
            background: var(--gradient-primary);
            border: none;
            border-radius: 8px;
            color: white;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
        }

        .newsletter-form button:hover { transform: translateY(-2px); }

        .social-links {
            display: flex;
            gap: 0.5rem;
        }

        .social-link {
            width: 38px;
            height: 38px;
            background: rgba(255,255,255,0.1);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.1rem;
            text-decoration: none;
            transition: all 0.3s;
        }

        .social-link:hover {
            transform: translateY(-3px);
            background: var(--primary);
        }

        /* Trust Badges */
        .trust-badges {
            display: flex;
            flex-wrap: wrap;
            gap: 0.75rem;
            margin-top: 1rem;
        }

        .trust-badge {
            display: flex;
            align-items: center;
            gap: 0.4rem;
            padding: 0.4rem 0.75rem;
            background: rgba(255,255,255,0.05);
            border-radius: 6px;
            font-size: 0.75rem;
            color: #cbd5e1;
        }

        .trust-badge .badge-icon { font-size: 1rem; }

        .footer-bottom {
            margin-top: 2rem;
            padding: 1rem 0;
            border-top: 1px solid rgba(255,255,255,0.1);
            text-align: center;
        }

        .footer-bottom p {
            font-size: 0.85rem;
            color: #64748b;
            margin: 0.25rem 0;
        }

        @media (max-width: 1024px) {
            .footer-grid { grid-template-columns: 1fr 1fr; }
        }

        @media (max-width: 768px) {
            .footer-grid { grid-template-columns: 1fr; text-align: center; }
            .footer-logo { justify-content: center; }
            .footer-contact-item { justify-content: center; }
            .social-links { justify-content: center; }
            .trust-badges { justify-content: center; }
            .newsletter-form { flex-direction: column; }
        }

        /* ========== BOOK CARD MODERN ========== */
        .book-card-v2 {
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: var(--shadow-sm);
            transition: all 0.3s ease;
            border: 1px solid #f1f5f9;
        }

        .book-card-v2:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(0,0,0,0.1);
        }

        .book-card-v2 .book-img-wrap {
            position: relative;
            height: 200px;
            background: linear-gradient(135deg, #f8fafc, #e2e8f0);
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }

        .book-card-v2 .book-img-wrap img {
            max-width: 65%;
            max-height: 85%;
            object-fit: contain;
            border-radius: 4px;
            box-shadow: 0 3px 15px rgba(0,0,0,0.15);
            transition: transform 0.3s;
        }

        .book-card-v2:hover .book-img-wrap img { transform: scale(1.05); }

        .book-card-v2 .sale-badge {
            position: absolute;
            top: 8px;
            left: 8px;
            background: linear-gradient(135deg, #ef4444, #dc2626);
            color: white;
            padding: 3px 10px;
            border-radius: 15px;
            font-size: 0.7rem;
            font-weight: 700;
        }

        .book-card-v2 .wishlist-btn {
            position: absolute;
            top: 8px;
            right: 8px;
            width: 32px;
            height: 32px;
            background: rgba(255,255,255,0.9);
            border: none;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s;
            font-size: 1rem;
            opacity: 0;
        }

        .book-card-v2:hover .wishlist-btn { opacity: 1; }
        .book-card-v2 .wishlist-btn:hover { background: #fee2e2; transform: scale(1.1); }

        .book-card-v2 .book-body { padding: 0.875rem; }

        .book-card-v2 .book-title {
            font-size: 0.9rem;
            font-weight: 600;
            color: #1e293b;
            margin-bottom: 0.3rem;
            line-height: 1.35;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            min-height: 2.4em;
        }

        .book-card-v2 .book-title a { color: inherit; text-decoration: none; }
        .book-card-v2 .book-title a:hover { color: var(--primary); }

        .book-card-v2 .book-author {
            font-size: 0.8rem;
            color: #64748b;
            margin-bottom: 0.4rem;
        }

        .book-card-v2 .book-rating {
            display: flex;
            align-items: center;
            gap: 4px;
            margin-bottom: 0.5rem;
            font-size: 0.75rem;
        }

        .book-card-v2 .book-rating .stars { color: #f59e0b; }
        .book-card-v2 .book-rating .count { color: #94a3b8; }

        .book-card-v2 .book-price-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 8px;
        }

        .book-card-v2 .price-current {
            font-size: 1.1rem;
            font-weight: 700;
            color: #dc2626;
        }

        .book-card-v2 .price-old {
            font-size: 0.75rem;
            color: #94a3b8;
            text-decoration: line-through;
        }

        .book-card-v2 .btn-add-cart {
            width: 36px;
            height: 36px;
            background: var(--gradient-primary);
            border: none;
            border-radius: 50%;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s;
            font-size: 1rem;
        }

        .book-card-v2 .btn-add-cart:hover {
            transform: scale(1.1);
            box-shadow: 0 5px 15px rgba(37, 99, 235, 0.3);
        }

        /* Book Grid */
        .books-grid-v2 {
            display: grid;
            grid-template-columns: repeat(6, 1fr);
            gap: 1rem;
        }

        @media (max-width: 1400px) { .books-grid-v2 { grid-template-columns: repeat(5, 1fr); } }
        @media (max-width: 1200px) { .books-grid-v2 { grid-template-columns: repeat(4, 1fr); } }
        @media (max-width: 900px) { .books-grid-v2 { grid-template-columns: repeat(3, 1fr); } }
        @media (max-width: 600px) { .books-grid-v2 { grid-template-columns: repeat(2, 1fr); } }

        /* Section Styles */
        .section-box {
            background: white;
            border-radius: 16px;
            padding: 1.25rem;
            margin-bottom: 1.25rem;
            box-shadow: var(--shadow-sm);
        }

        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
            padding-bottom: 0.75rem;
            border-bottom: 2px solid #f1f5f9;
        }

        .section-title {
            font-size: 1.2rem;
            font-weight: 700;
            color: #1e293b;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .section-link {
            color: var(--primary);
            text-decoration: none;
            font-weight: 600;
            font-size: 0.85rem;
            display: flex;
            align-items: center;
            gap: 4px;
            transition: all 0.3s;
        }

        .section-link:hover { gap: 8px; }

        /* Category Cards */
        .categories-grid-v2 {
            display: grid;
            grid-template-columns: repeat(6, 1fr);
            gap: 0.75rem;
        }

        @media (max-width: 1200px) { .categories-grid-v2 { grid-template-columns: repeat(4, 1fr); } }
        @media (max-width: 768px) { .categories-grid-v2 { grid-template-columns: repeat(3, 1fr); } }
        @media (max-width: 480px) { .categories-grid-v2 { grid-template-columns: repeat(2, 1fr); } }

        .category-card-v2 {
            background: white;
            border-radius: 12px;
            padding: 1rem 0.75rem;
            text-align: center;
            box-shadow: var(--shadow-sm);
            transition: all 0.3s;
            border: 2px solid transparent;
            text-decoration: none;
            color: inherit;
        }

        .category-card-v2:hover {
            transform: translateY(-3px);
            border-color: var(--primary);
            box-shadow: 0 8px 25px rgba(37, 99, 235, 0.12);
        }

        .category-card-v2 .cat-icon { font-size: 2rem; margin-bottom: 0.4rem; }
        .category-card-v2 .cat-name { font-weight: 600; font-size: 0.9rem; color: #1e293b; }
        .category-card-v2 .cat-count { font-size: 0.75rem; color: #64748b; }

        /* Breadcrumb */
        .breadcrumb {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.75rem 0;
            font-size: 0.9rem;
            color: #64748b;
        }

        .breadcrumb a { color: var(--primary); text-decoration: none; }
        .breadcrumb a:hover { text-decoration: underline; }
        .breadcrumb span { color: #94a3b8; }
    </style>

    @stack('styles')
</head>
<body>
    <!-- Promo Strip -->
    <div class="promo-strip">
        <span>🔥</span> Miễn phí vận chuyển cho đơn hàng từ 300.000đ | Hotline: <strong>0787 905 089</strong>
    </div>

    <!-- ========== UNIFIED HEADER ========== -->
    <header class="header-unified">
        <div class="container">
            <div class="header-main-row">
                <!-- Logo -->
                <a href="{{ route('home') }}" class="logo-unified">
                    <div class="logo-icon-box">📚</div>
                    <div class="logo-text-box">
                        <span class="brand-name">BookStore</span>
                        <span class="brand-tagline">SÁCH HAY MỖI NGÀY</span>
                    </div>
                </a>

                <!-- Search Bar -->
                <div class="search-unified">
                    <form action="{{ route('search') }}" method="GET">
                        <input type="text" name="q" placeholder="Tìm sách, tác giả, thể loại..." value="{{ request('q') }}" autocomplete="off">
                        <button type="submit">
                            <span>🔍</span>
                            <span class="search-text">Tìm kiếm</span>
                        </button>
                    </form>
                </div>

                <!-- Header Actions -->
                <div class="header-actions-unified">
                    <!-- Wishlist -->
                    <a href="{{ route('wishlist.index') }}" class="header-btn" title="Yêu thích">
                        <span class="icon">❤️</span>
                        <span class="label">Yêu thích</span>
                        <span class="badge-num wishlist-count" style="display: none;">0</span>
                    </a>

                    <!-- Cart -->
                    <a href="{{ route('cart.index') }}" class="header-btn cart-highlight" title="Giỏ hàng">
                        <span class="icon">🛒</span>
                        <span class="label">Giỏ hàng</span>
                        <span class="badge-num cart-count" style="display: none;">0</span>
                    </a>

                    <!-- User -->
                    @auth
                        <div class="user-dropdown">
                            <button class="user-dropdown-btn" onclick="toggleUserMenu(event)">
                                <div class="user-avatar-small">👤</div>
                                <span style="max-width: 80px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">{{ auth()->user()->ho_ten }}</span>
                                <span>▼</span>
                            </button>
                            <div class="user-dropdown-menu" id="userDropdownMenu">
                                <a href="{{ route('profile') }}">👤 Tài khoản</a>
                                <a href="{{ route('orders') }}">📦 Đơn hàng</a>
                                <a href="{{ route('wishlist.index') }}">❤️ Yêu thích</a>
                                @if(auth()->user()->vai_tro === 'admin')
                                    <div class="divider"></div>
                                    <a href="{{ route('admin.dashboard') }}">⚙️ Quản trị Admin</a>
                                @endif
                                <div class="divider"></div>
                                <form action="{{ route('logout') }}" method="POST" style="margin: 0;">
                                    @csrf
                                    <button type="submit" class="logout-link">🚪 Đăng xuất</button>
                                </form>
                            </div>
                        </div>
                    @else
                        <a href="{{ route('login') }}" class="header-btn" title="Đăng nhập">
                            <span class="icon">👤</span>
                            <span class="label">Đăng nhập</span>
                        </a>
                    @endauth

                    <!-- Mobile Menu Toggle -->
                    <button class="mobile-menu-btn" onclick="toggleMobileNav()">☰</button>
                </div>
            </div>
        </div>

        <!-- Navigation Bar -->
        <nav class="nav-bar-unified" id="navBar">
            <div class="container">
                <ul class="nav-menu-unified">
                    <li>
                        <a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'active' : '' }}">
                            <span class="nav-icon">🏠</span> Trang chủ
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('categories') }}">
                            <span class="nav-icon">📂</span> Danh mục <span>▼</span>
                        </a>
                        <div class="nav-dropdown-box">
                            @php $theLoais = \App\Models\TheLoai::take(8)->get(); @endphp
                            @foreach($theLoais as $tl)
                                <a href="{{ route('category', $tl->duong_dan ?? $tl->ma_the_loai) }}">
                                    <span class="dropdown-icon">📖</span> {{ $tl->ten_the_loai }}
                                </a>
                            @endforeach
                            <a href="{{ route('categories') }}" style="color: var(--primary); font-weight: 600;">
                                <span class="dropdown-icon">📚</span> Xem tất cả →
                            </a>
                        </div>
                    </li>
                    <li><a href="{{ route('authors') }}"><span class="nav-icon">✍️</span> Tác giả</a></li>
                    <li><a href="{{ route('new-books') }}"><span class="nav-icon">🆕</span> Sách mới</a></li>
                    <li><a href="{{ route('bestsellers') }}"><span class="nav-icon">🔥</span> Bán chạy</a></li>
                    <li><a href="{{ route('sale') }}"><span class="nav-icon">💰</span> Khuyến mãi</a></li>
                </ul>
            </div>
        </nav>
    </header>

    <!-- Main Content -->
    <main class="main-content">
        <div class="container">
            @if(session('success'))
                <div class="alert alert-success">✓ {{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger">✗ {{ session('error') }}</div>
            @endif
            @if(session('warning'))
                <div class="alert alert-warning">⚠ {{ session('warning') }}</div>
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

    <!-- ========== MODERN FOOTER ========== -->
    <footer class="footer-modern">
        <div class="container">
            <div class="footer-grid">
                <!-- Column 1: About -->
                <div class="footer-col">
                    <div class="footer-logo">
                        <div class="footer-logo-icon">📚</div>
                        <span class="footer-brand">BookStore</span>
                    </div>
                    <p class="footer-desc">Nhà sách trực tuyến hàng đầu với hàng ngàn đầu sách chất lượng, giao hàng nhanh và dịch vụ tận tâm.</p>
                    <div class="footer-contact-item">📍 Khóm 9, Phường Nguyệt Hóa, Tỉnh Vĩnh Long</div>
                    <div class="footer-contact-item">📞 0787 905 089</div>
                    <div class="footer-contact-item">✉️ contact@bookstore.vn</div>
                    
                    <!-- Trust Badges -->
                    <div class="trust-badges">
                        <div class="trust-badge"><span class="badge-icon">🚚</span> Giao hàng nhanh</div>
                        <div class="trust-badge"><span class="badge-icon">↩️</span> Đổi trả 30 ngày</div>
                        <div class="trust-badge"><span class="badge-icon">✅</span> Sách chính hãng</div>
                        <div class="trust-badge"><span class="badge-icon">🔒</span> Thanh toán an toàn</div>
                    </div>
                </div>

                <!-- Column 2: Categories -->
                <div class="footer-col">
                    <h4 class="footer-title">📂 Danh mục sách</h4>
                    <ul class="footer-links">
                        @php $footerCategories = \App\Models\TheLoai::orderBy('ten_the_loai')->take(5)->get(); @endphp
                        @foreach($footerCategories as $cat)
                            <li><a href="{{ route('category', $cat->duong_dan ?? $cat->ma_the_loai) }}">📖 {{ $cat->ten_the_loai }}</a></li>
                        @endforeach
                        <li><a href="{{ route('categories') }}">📚 Xem tất cả →</a></li>
                    </ul>
                </div>

                <!-- Column 3: Support -->
                <div class="footer-col">
                    <h4 class="footer-title">🛟 Hỗ trợ</h4>
                    <ul class="footer-links">
                        <li><a href="{{ route('about') }}">ℹ️ Giới thiệu</a></li>
                        <li><a href="{{ route('contact') }}">📞 Liên hệ</a></li>
                        <li><a href="{{ route('faq') }}">❓ Câu hỏi thường gặp</a></li>
                        <li><a href="{{ route('shipping-policy') }}">🚚 Chính sách vận chuyển</a></li>
                        <li><a href="{{ route('return-policy') }}">↩️ Chính sách đổi trả</a></li>
                        <li><a href="{{ route('privacy-policy') }}">🔒 Chính sách bảo mật</a></li>
                    </ul>
                </div>

                <!-- Column 4: Newsletter -->
                <div class="footer-col">
                    <h4 class="footer-title">📬 Đăng ký nhận tin</h4>
                    <p style="font-size: 0.85rem; margin-bottom: 0.75rem;">Nhận thông tin sách mới và khuyến mãi!</p>
                    <form class="newsletter-form" onsubmit="event.preventDefault(); showToast('🎉 Đăng ký thành công!', 'success');">
                        <input type="email" placeholder="Email của bạn..." required>
                        <button type="submit">Gửi</button>
                    </form>
                    
                    <h4 class="footer-title" style="margin-top: 1rem;">🌐 Kết nối</h4>
                    <div class="social-links">
                        <a href="#" class="social-link" title="Facebook">📘</a>
                        <a href="#" class="social-link" title="Instagram">📷</a>
                        <a href="#" class="social-link" title="YouTube">📺</a>
                        <a href="#" class="social-link" title="TikTok">🎵</a>
                    </div>

                    <h4 class="footer-title" style="margin-top: 1rem;">💳 Thanh toán</h4>
                    <div class="trust-badges">
                        <div class="trust-badge">💵 COD</div>
                        <div class="trust-badge">🏦 Chuyển khoản</div>
                        <div class="trust-badge">📱 MoMo</div>
                        <div class="trust-badge">💳 VNPAY</div>
                    </div>
                </div>
            </div>

            <div class="footer-bottom">
                <p>© {{ date('Y') }} BookStore. Phát triển với ❤️ bằng Laravel & MySQL</p>
                <p>Đồ án tốt nghiệp - Ngô Thanh Trà - MSSV: 110123189 - GVHD: Trịnh Quốc Việt</p>
            </div>
        </div>
    </footer>

    <!-- JavaScript -->
    <script>
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        // Toggle mobile nav
        function toggleMobileNav() {
            document.getElementById('navBar').classList.toggle('show');
        }

        // Toggle user dropdown
        function toggleUserMenu(event) {
            event.stopPropagation();
            document.getElementById('userDropdownMenu').classList.toggle('show');
        }

        // Close dropdowns on outside click
        document.addEventListener('click', function(e) {
            const userMenu = document.getElementById('userDropdownMenu');
            if (userMenu && !e.target.closest('.user-dropdown')) {
                userMenu.classList.remove('show');
            }
        });

        // Toast notification
        function showToast(message, type = 'success') {
            const existing = document.querySelector('.toast-notification');
            if (existing) existing.remove();

            const toast = document.createElement('div');
            toast.className = 'toast-notification';
            toast.innerHTML = message;
            toast.style.cssText = `
                position: fixed;
                bottom: 30px;
                right: 30px;
                padding: 14px 24px;
                border-radius: 10px;
                color: white;
                font-weight: 600;
                z-index: 9999;
                animation: slideIn 0.3s ease;
                box-shadow: 0 10px 30px rgba(0,0,0,0.2);
                background: ${type === 'success' ? 'linear-gradient(135deg, #10b981, #059669)' : 
                             type === 'error' ? 'linear-gradient(135deg, #ef4444, #dc2626)' : 
                             'linear-gradient(135deg, #3b82f6, #2563eb)'};
            `;
            document.body.appendChild(toast);
            setTimeout(() => { toast.style.animation = 'slideOut 0.3s ease'; setTimeout(() => toast.remove(), 300); }, 3000);
        }

        // Login modal
        function showLoginModal(message = 'Vui lòng đăng nhập để tiếp tục') {
            const existing = document.querySelector('.login-modal-overlay');
            if (existing) existing.remove();

            const modal = document.createElement('div');
            modal.className = 'login-modal-overlay';
            modal.innerHTML = `
                <div class="login-modal-box">
                    <div style="font-size: 3.5rem; margin-bottom: 1rem;">🔐</div>
                    <h3 style="font-size: 1.3rem; color: #1e293b; margin-bottom: 0.5rem;">Yêu cầu đăng nhập</h3>
                    <p style="color: #64748b; margin-bottom: 1.5rem;">${message}</p>
                    <a href="{{ route('login') }}" class="btn btn-primary" style="width: 100%; margin-bottom: 0.5rem;">🔑 Đăng nhập ngay</a>
                    <a href="{{ route('register') }}" class="btn btn-outline" style="width: 100%;">📝 Đăng ký tài khoản</a>
                    <button onclick="this.closest('.login-modal-overlay').remove()" style="position: absolute; top: 1rem; right: 1rem; background: #f1f5f9; border: none; width: 32px; height: 32px; border-radius: 50%; cursor: pointer; font-size: 1rem;">✕</button>
                </div>
            `;
            modal.style.cssText = `position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.6); backdrop-filter: blur(5px); display: flex; align-items: center; justify-content: center; z-index: 10000;`;
            modal.querySelector('.login-modal-box').style.cssText = `background: white; border-radius: 20px; padding: 2rem; max-width: 380px; width: 90%; text-align: center; position: relative; animation: fadeIn 0.3s ease;`;
            modal.onclick = (e) => { if (e.target === modal) modal.remove(); };
            document.body.appendChild(modal);
        }

        // Add to cart
        function addToCartQuick(bookId) {
            fetch('{{ route("cart.add") }}', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' },
                body: JSON.stringify({ ma_sach: bookId, so_luong: 1 })
            })
            .then(r => {
                if (r.status === 401) { showLoginModal('Bạn cần đăng nhập để thêm vào giỏ hàng'); throw new Error('Unauthorized'); }
                return r.json();
            })
            .then(data => {
                if (data.success) { showToast('✓ Đã thêm vào giỏ hàng!', 'success'); updateCartCount(); }
                else { showToast(data.message || 'Có lỗi xảy ra!', 'error'); }
            })
            .catch(e => { if (e.message !== 'Unauthorized') showToast('Có lỗi xảy ra!', 'error'); });
        }

        // Toggle wishlist
        function toggleWishlist(bookId) {
            fetch('{{ route("wishlist.toggle") }}', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' },
                body: JSON.stringify({ ma_sach: bookId })
            })
            .then(r => {
                if (r.status === 401) { showLoginModal('Bạn cần đăng nhập để thêm vào yêu thích'); throw new Error('Unauthorized'); }
                return r.json();
            })
            .then(data => {
                if (data.success) { showToast(data.added ? '❤️ Đã thêm vào yêu thích!' : '💔 Đã xóa khỏi yêu thích!', 'success'); updateCartCount(); }
                else { showToast(data.message || 'Có lỗi xảy ra!', 'error'); }
            })
            .catch(e => { if (e.message !== 'Unauthorized') showToast('Có lỗi xảy ra!', 'error'); });
        }

        // Update cart/wishlist count
        function updateCartCount() {
            fetch('/api/cart/count').then(r => r.json()).then(data => {
                document.querySelectorAll('.cart-count').forEach(el => {
                    el.textContent = data.count || 0;
                    el.style.display = data.count > 0 ? 'inline-block' : 'none';
                });
            }).catch(() => {});
            
            fetch('/api/wishlist/count').then(r => r.json()).then(data => {
                document.querySelectorAll('.wishlist-count').forEach(el => {
                    el.textContent = data.count || 0;
                    el.style.display = data.count > 0 ? 'inline-block' : 'none';
                });
            }).catch(() => {});
        }

        // Init
        document.addEventListener('DOMContentLoaded', function() {
            updateCartCount();
        });

        // CSS animations
        const style = document.createElement('style');
        style.textContent = `
            @keyframes slideIn { from { transform: translateX(100%); opacity: 0; } to { transform: translateX(0); opacity: 1; } }
            @keyframes slideOut { from { transform: translateX(0); opacity: 1; } to { transform: translateX(100%); opacity: 0; } }
        `;
        document.head.appendChild(style);
    </script>

    @stack('scripts')
</body>
</html>
