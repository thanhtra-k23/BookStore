@extends('layouts.pure-blade')

@section('title', $title)

@push('styles')
<style>
    /* Hero Banner với Background Sách */
    .hero-banner {
        background: linear-gradient(135deg, rgba(30, 41, 59, 0.95) 0%, rgba(51, 65, 85, 0.9) 100%),
                    url('https://images.unsplash.com/photo-1507842217343-583bb7270b66?w=1920&q=80') center/cover no-repeat;
        color: white;
        padding: 5rem 0;
        margin: -2.5rem -20px 3rem;
        text-align: center;
        position: relative;
        overflow: hidden;
    }
    
    .hero-banner::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><circle cx="50" cy="50" r="1" fill="rgba(255,255,255,0.1)"/></svg>');
        background-size: 50px 50px;
        animation: sparkle 20s linear infinite;
    }
    
    @keyframes sparkle {
        0% { transform: translateY(0); }
        100% { transform: translateY(-50px); }
    }
    
    .hero-content {
        position: relative;
        z-index: 2;
        max-width: 800px;
        margin: 0 auto;
        padding: 0 20px;
    }
    
    .hero-badge {
        display: inline-block;
        background: rgba(255, 255, 255, 0.15);
        backdrop-filter: blur(10px);
        padding: 8px 20px;
        border-radius: 50px;
        font-size: 0.9rem;
        margin-bottom: 1.5rem;
        border: 1px solid rgba(255, 255, 255, 0.2);
    }

    .hero-banner h1 {
        font-size: 3rem;
        font-weight: 800;
        margin-bottom: 1.25rem;
        text-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
        line-height: 1.2;
    }
    
    .hero-banner h1 .highlight {
        background: linear-gradient(135deg, #fbbf24, #f59e0b);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }
    
    .hero-banner p {
        font-size: 1.2rem;
        opacity: 0.9;
        margin-bottom: 2rem;
        max-width: 600px;
        margin-left: auto;
        margin-right: auto;
        line-height: 1.7;
    }
    
    /* Hero Search Box */
    .hero-search {
        background: white;
        border-radius: 60px;
        padding: 8px;
        display: flex;
        max-width: 550px;
        margin: 0 auto 2rem;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
    }
    
    .hero-search input {
        flex: 1;
        border: none;
        padding: 1rem 1.5rem;
        font-size: 1rem;
        border-radius: 50px;
        outline: none;
        color: #1e293b;
    }
    
    .hero-search input::placeholder {
        color: #94a3b8;
    }
    
    .hero-search button {
        background: linear-gradient(135deg, #2563eb, #7c3aed);
        border: none;
        padding: 1rem 2rem;
        border-radius: 50px;
        color: white;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.3s;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    
    .hero-search button:hover {
        transform: scale(1.05);
        box-shadow: 0 10px 30px rgba(37, 99, 235, 0.4);
    }
    
    .hero-buttons {
        display: flex;
        gap: 1rem;
        justify-content: center;
        flex-wrap: wrap;
    }
    
    .hero-buttons .btn {
        padding: 1rem 2rem;
        font-size: 1.05rem;
        border-radius: 50px;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    
    .btn-light {
        background: white;
        color: #1e293b;
        font-weight: 600;
    }
    
    .btn-light:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 30px rgba(255, 255, 255, 0.3);
    }
    
    .btn-outline-light {
        background: transparent;
        border: 2px solid rgba(255, 255, 255, 0.5);
        color: white;
        font-weight: 600;
    }
    
    .btn-outline-light:hover {
        background: white;
        color: #1e293b;
        border-color: white;
    }

    /* Stats Section */
    .stats-section {
        background: white;
        border-radius: 24px;
        padding: 2.5rem;
        margin-bottom: 4rem;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.08);
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 2rem;
        text-align: center;
        position: relative;
        margin-top: -60px;
        z-index: 10;
    }
    
    .stat-item {
        padding: 1.5rem;
        border-radius: 16px;
        transition: all 0.3s ease;
    }
    
    .stat-item:hover {
        background: linear-gradient(135deg, #f8fafc, #f1f5f9);
        transform: translateY(-5px);
    }
    
    .stat-icon {
        width: 70px;
        height: 70px;
        border-radius: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1rem;
        font-size: 2rem;
    }
    
    .stat-icon.books { 
        background: linear-gradient(135deg, #dbeafe, #eff6ff);
        color: #2563eb;
    }
    .stat-icon.authors { 
        background: linear-gradient(135deg, #d1fae5, #ecfdf5);
        color: #059669;
    }
    .stat-icon.categories { 
        background: linear-gradient(135deg, #e0e7ff, #eef2ff);
        color: #4f46e5;
    }
    .stat-icon.orders { 
        background: linear-gradient(135deg, #fef3c7, #fffbeb);
        color: #d97706;
    }
    
    .stat-number {
        font-size: 2.25rem;
        font-weight: 800;
        color: #1e293b;
        margin-bottom: 0.25rem;
    }
    
    .stat-label {
        color: #64748b;
        font-size: 0.95rem;
        font-weight: 500;
    }
    
    /* Section Styling */
    .section {
        margin-bottom: 5rem;
    }
    
    .section-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2.5rem;
        flex-wrap: wrap;
        gap: 1rem;
    }
    
    .section-title {
        font-size: 1.85rem;
        font-weight: 800;
        color: #1e293b;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }
    
    .section-title .icon {
        width: 45px;
        height: 45px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.3rem;
    }
    
    .section-title .icon.star { 
        background: linear-gradient(135deg, #fef3c7, #fde68a);
        color: #d97706;
    }
    .section-title .icon.new { 
        background: linear-gradient(135deg, #d1fae5, #a7f3d0);
        color: #059669;
    }
    .section-title .icon.sale { 
        background: linear-gradient(135deg, #fee2e2, #fecaca);
        color: #dc2626;
    }
    .section-title .icon.category { 
        background: linear-gradient(135deg, #e0e7ff, #c7d2fe);
        color: #4f46e5;
    }
    
    .section-subtitle {
        color: #64748b;
        margin-top: 0.5rem;
        font-size: 1rem;
    }

    /* View All Buttons */
    .btn-view-all {
        background: transparent;
        border: 2px solid #e2e8f0;
        color: #64748b;
        padding: 0.75rem 1.5rem;
        border-radius: 50px;
        font-weight: 600;
        transition: all 0.3s;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    
    .btn-view-all:hover {
        border-color: #2563eb;
        color: #2563eb;
        transform: translateX(5px);
    }
    
    .btn-view-all.primary:hover {
        background: #2563eb;
        color: white;
    }
    
    .btn-view-all.success:hover {
        background: #059669;
        border-color: #059669;
        color: white;
    }
    
    .btn-view-all.danger:hover {
        background: #dc2626;
        border-color: #dc2626;
        color: white;
    }
    
    /* Book Grid - Responsive */
    .book-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 2rem;
    }
    
    /* Category Cards */
    .categories-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        gap: 1.5rem;
    }
    
    .category-item {
        background: white;
        border-radius: 20px;
        padding: 2rem;
        text-align: center;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.06);
        transition: all 0.4s ease;
        border: 1px solid #f1f5f9;
        position: relative;
        overflow: hidden;
    }
    
    .category-item::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(135deg, #2563eb, #7c3aed);
        transform: scaleX(0);
        transition: transform 0.3s;
    }
    
    .category-item:hover::before {
        transform: scaleX(1);
    }
    
    .category-item:hover {
        transform: translateY(-8px);
        box-shadow: 0 20px 40px rgba(37, 99, 235, 0.15);
    }
    
    .category-icon {
        width: 80px;
        height: 80px;
        border-radius: 20px;
        background: linear-gradient(135deg, #2563eb, #7c3aed);
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1.25rem;
        font-size: 2rem;
        color: white;
        transition: transform 0.3s;
    }
    
    .category-item:hover .category-icon {
        transform: scale(1.1) rotate(5deg);
    }
    
    .category-name {
        font-size: 1.25rem;
        font-weight: 700;
        color: #1e293b;
        margin-bottom: 0.5rem;
    }
    
    .category-count {
        color: #64748b;
        margin-bottom: 1.25rem;
        font-size: 0.95rem;
    }
    
    .category-item .btn {
        border-radius: 50px;
    }

    /* Newsletter Section */
    .newsletter-section {
        background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
        color: white;
        padding: 5rem 2rem;
        border-radius: 30px;
        text-align: center;
        margin-top: 4rem;
        position: relative;
        overflow: hidden;
    }
    
    .newsletter-section::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -20%;
        width: 400px;
        height: 400px;
        background: linear-gradient(135deg, rgba(37, 99, 235, 0.3), rgba(124, 58, 237, 0.3));
        border-radius: 50%;
        filter: blur(60px);
    }
    
    .newsletter-section::after {
        content: '';
        position: absolute;
        bottom: -30%;
        left: -10%;
        width: 300px;
        height: 300px;
        background: linear-gradient(135deg, rgba(245, 158, 11, 0.2), rgba(239, 68, 68, 0.2));
        border-radius: 50%;
        filter: blur(60px);
    }
    
    .newsletter-content {
        position: relative;
        z-index: 2;
    }
    
    .newsletter-section h3 {
        font-size: 2rem;
        font-weight: 800;
        margin-bottom: 1rem;
    }
    
    .newsletter-section p {
        opacity: 0.8;
        margin-bottom: 2rem;
        max-width: 500px;
        margin-left: auto;
        margin-right: auto;
        font-size: 1.05rem;
    }
    
    .newsletter-form {
        display: flex;
        gap: 0.75rem;
        max-width: 480px;
        margin: 0 auto;
    }
    
    .newsletter-form input {
        flex: 1;
        padding: 1.1rem 1.5rem;
        border: none;
        border-radius: 50px;
        font-size: 1rem;
        background: rgba(255, 255, 255, 0.1);
        backdrop-filter: blur(10px);
        color: white;
        border: 1px solid rgba(255, 255, 255, 0.2);
    }
    
    .newsletter-form input::placeholder {
        color: rgba(255, 255, 255, 0.6);
    }
    
    .newsletter-form input:focus {
        outline: none;
        background: rgba(255, 255, 255, 0.15);
        border-color: rgba(255, 255, 255, 0.4);
    }
    
    .newsletter-form button {
        padding: 1.1rem 2rem;
        border: none;
        border-radius: 50px;
        background: linear-gradient(135deg, #f59e0b, #fbbf24);
        color: #1e293b;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.3s;
    }
    
    .newsletter-form button:hover {
        transform: scale(1.05);
        box-shadow: 0 10px 30px rgba(245, 158, 11, 0.4);
    }
    
    .text-center { text-align: center; }
    .mt-3 { margin-top: 1.5rem; }
    
    /* Responsive */
    @media (max-width: 1200px) {
        .book-grid {
            grid-template-columns: repeat(3, 1fr);
        }
    }
    
    @media (max-width: 992px) {
        .book-grid {
            grid-template-columns: repeat(2, 1fr);
        }
        
        .stats-section {
            grid-template-columns: repeat(2, 1fr);
            gap: 1.5rem;
        }
    }
    
    @media (max-width: 768px) {
        .hero-banner {
            padding: 3rem 0 4rem;
        }
        
        .hero-banner h1 {
            font-size: 2rem;
        }
        
        .hero-banner p {
            font-size: 1rem;
        }
        
        .hero-search {
            flex-direction: column;
            border-radius: 20px;
            padding: 15px;
        }
        
        .hero-search input,
        .hero-search button {
            width: 100%;
            border-radius: 12px;
        }
        
        .section-header {
            flex-direction: column;
            text-align: center;
        }
        
        .newsletter-form {
            flex-direction: column;
        }
        
        .book-grid {
            grid-template-columns: repeat(2, 1fr);
            gap: 1rem;
        }
    }
    
    @media (max-width: 480px) {
        .book-grid {
            grid-template-columns: 1fr;
        }
        
        .stats-section {
            grid-template-columns: 1fr;
            margin-top: -40px;
        }
    }
</style>
@endpush

@section('content')
<!-- Hero Section -->
<div class="hero-banner">
    <div class="hero-content">
        <div class="hero-badge">📚 Hơn {{ number_format($stats['total_books']) }} đầu sách đang chờ bạn</div>
        <h1>Khám phá <span class="highlight">Thế giới Sách</span><br>Mở rộng Tri thức</h1>
        <p>
            Từ văn học kinh điển đến khoa học hiện đại, từ kỹ năng sống đến kinh tế. 
            Tất cả đều có tại BookStore với giá ưu đãi nhất.
        </p>
        
        <form class="hero-search" action="{{ route('search') }}" method="GET">
            <input type="text" name="q" id="hero-search-input" placeholder="Tìm kiếm sách, tác giả, thể loại...">
            <button type="submit">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="11" cy="11" r="8"></circle>
                    <path d="m21 21-4.35-4.35"></path>
                </svg>
                Tìm kiếm
            </button>
        </form>
        
        <div class="hero-buttons">
            <a href="{{ route('categories') }}" class="btn btn-light">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path>
                    <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path>
                </svg>
                Xem thể loại
            </a>
            <a href="{{ route('search', ['on_sale' => 1]) }}" class="btn btn-outline-light">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"></path>
                    <line x1="7" y1="7" x2="7.01" y2="7"></line>
                </svg>
                Khuyến mãi hot
            </a>
        </div>
    </div>
</div>

<!-- Stats Section -->
<div class="container">
    <div class="stats-section fade-in-up">
        <div class="stat-item">
            <div class="stat-icon books">📚</div>
            <div class="stat-number">{{ number_format($stats['total_books']) }}</div>
            <div class="stat-label">Đầu sách</div>
        </div>
        <div class="stat-item">
            <div class="stat-icon authors">✍️</div>
            <div class="stat-number">{{ number_format($stats['total_authors']) }}</div>
            <div class="stat-label">Tác giả</div>
        </div>
        <div class="stat-item">
            <div class="stat-icon categories">📂</div>
            <div class="stat-number">{{ number_format($stats['total_categories']) }}</div>
            <div class="stat-label">Thể loại</div>
        </div>
        <div class="stat-item">
            <div class="stat-icon orders">🛒</div>
            <div class="stat-number">{{ number_format($stats['total_orders']) }}</div>
            <div class="stat-label">Đơn hàng</div>
        </div>
    </div>

    <!-- Featured Books -->
    @if($featuredBooks->count() > 0)
    <section class="section">
        <div class="section-header">
            <div>
                <h2 class="section-title">
                    <span class="icon star">⭐</span>
                    Sách nổi bật
                </h2>
                <p class="section-subtitle">Những cuốn sách được yêu thích nhất</p>
            </div>
            <a href="{{ route('search', ['sort' => 'popular']) }}" class="btn-view-all primary">
                Xem tất cả
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M5 12h14M12 5l7 7-7 7"/>
                </svg>
            </a>
        </div>
        <div class="book-grid">
            @foreach($featuredBooks as $book)
                @include('partials.book-card-pure', ['book' => $book])
            @endforeach
        </div>
    </section>
    @endif

    <!-- New Arrivals -->
    @if($newBooks->count() > 0)
    <section class="section">
        <div class="section-header">
            <div>
                <h2 class="section-title">
                    <span class="icon new">🆕</span>
                    Sách mới
                </h2>
                <p class="section-subtitle">Những cuốn sách vừa được cập nhật</p>
            </div>
            <a href="{{ route('search', ['sort' => 'newest']) }}" class="btn-view-all success">
                Xem tất cả
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M5 12h14M12 5l7 7-7 7"/>
                </svg>
            </a>
        </div>
        <div class="book-grid">
            @foreach($newBooks as $book)
                @include('partials.book-card-pure', ['book' => $book])
            @endforeach
        </div>
    </section>
    @endif

    <!-- Sale Books -->
    @if($saleBooks->count() > 0)
    <section class="section">
        <div class="section-header">
            <div>
                <h2 class="section-title">
                    <span class="icon sale">🏷️</span>
                    Sách khuyến mãi
                </h2>
                <p class="section-subtitle">Những cuốn sách đang có giá ưu đãi</p>
            </div>
            <a href="{{ route('search', ['on_sale' => 1]) }}" class="btn-view-all danger">
                Xem tất cả
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M5 12h14M12 5l7 7-7 7"/>
                </svg>
            </a>
        </div>
        <div class="book-grid">
            @foreach($saleBooks as $book)
                @include('partials.book-card-pure', ['book' => $book])
            @endforeach
        </div>
    </section>
    @endif

    <!-- Popular Categories -->
    @if($popularCategories->count() > 0)
    <section class="section">
        <div class="section-header">
            <div>
                <h2 class="section-title">
                    <span class="icon category">📁</span>
                    Thể loại phổ biến
                </h2>
                <p class="section-subtitle">Các thể loại sách được quan tâm nhiều nhất</p>
            </div>
            <a href="{{ route('categories') }}" class="btn-view-all primary">
                Xem tất cả
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M5 12h14M12 5l7 7-7 7"/>
                </svg>
            </a>
        </div>
        <div class="categories-grid">
            @foreach($popularCategories as $category)
                <div class="category-item">
                    <div class="category-icon">📖</div>
                    <h3 class="category-name">{{ $category->ten_the_loai }}</h3>
                    <p class="category-count">{{ $category->sach_count }} cuốn sách</p>
                    <a href="{{ route('category', $category->duong_dan) }}" class="btn btn-primary">
                        Xem sách
                    </a>
                </div>
            @endforeach
        </div>
    </section>
    @endif

    <!-- Newsletter Section -->
    <div class="newsletter-section">
        <div class="newsletter-content">
            <h3>📬 Đăng ký nhận tin khuyến mãi</h3>
            <p>
                Nhận thông báo về sách mới, khuyến mãi độc quyền và các sự kiện đặc biệt từ BookStore
            </p>
            <form class="newsletter-form" onsubmit="return handleNewsletter(event)">
                <input type="email" name="newsletter_email" id="newsletter-email" placeholder="Nhập email của bạn" required>
                <button type="submit">Đăng ký ngay</button>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function handleNewsletter(e) {
        e.preventDefault();
        const email = e.target.querySelector('input[type="email"]').value;
        if (email) {
            showAlert('Cảm ơn bạn đã đăng ký nhận tin!', 'success');
            e.target.reset();
        }
        return false;
    }
    
    // Add to cart quick function
    function addToCartQuick(bookId) {
        fetch('/cart/add', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({ ma_sach: bookId, so_luong: 1 })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showAlert('Đã thêm sách vào giỏ hàng!', 'success');
                // Update cart count if exists
                const cartCount = document.querySelector('.cart-count');
                if (cartCount && data.cart_count) {
                    cartCount.textContent = data.cart_count;
                }
            } else {
                showAlert(data.message || 'Có lỗi xảy ra', 'error');
            }
        })
        .catch(() => {
            showAlert('Có lỗi xảy ra, vui lòng thử lại', 'error');
        });
    }
    
    // Toggle wishlist function
    function toggleWishlist(bookId) {
        fetch('/wishlist/toggle', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({ ma_sach: bookId })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showAlert(data.message || 'Đã cập nhật danh sách yêu thích!', 'success');
            } else {
                showAlert(data.message || 'Vui lòng đăng nhập để sử dụng tính năng này', 'warning');
            }
        })
        .catch(() => {
            showAlert('Vui lòng đăng nhập để sử dụng tính năng này', 'warning');
        });
    }
    
    // Notify when available function
    function notifyWhenAvailable(bookId) {
        showAlert('Chúng tôi sẽ thông báo khi sách có hàng!', 'info');
    }
</script>
@endpush
