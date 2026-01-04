@extends('layouts.pure-blade')

@section('title', 'Trang chủ - BookStore')

@push('styles')
<style>
    /* Hero Section */
    .hero-section {
        background: linear-gradient(135deg, #1e3a5f 0%, #2d5a87 50%, #1e3a5f 100%);
        color: white;
        padding: 2.5rem 1.5rem;
        border-radius: 16px;
        margin-bottom: 1.5rem;
        text-align: center;
        position: relative;
        overflow: hidden;
    }
    
    .hero-section::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url('https://images.unsplash.com/photo-1507842217343-583bb7270b66?w=1200') center/cover;
        opacity: 0.2;
    }
    
    .hero-content {
        position: relative;
        z-index: 1;
    }
    
    .hero-title {
        font-size: 2rem;
        font-weight: 800;
        margin-bottom: 0.75rem;
        text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
    }
    
    .hero-subtitle {
        font-size: 1rem;
        opacity: 0.9;
        margin-bottom: 1.5rem;
    }
    
    /* Search Box */
    .search-box {
        max-width: 550px;
        margin: 0 auto;
        display: flex;
        gap: 8px;
        background: white;
        padding: 6px;
        border-radius: 50px;
        box-shadow: 0 10px 40px rgba(0,0,0,0.2);
    }
    
    .search-box input {
        flex: 1;
        border: none;
        padding: 10px 18px;
        font-size: 0.95rem;
        border-radius: 50px;
        outline: none;
    }
    
    .search-box button {
        padding: 10px 25px;
        border-radius: 50px;
        border: none;
        background: linear-gradient(135deg, #2563eb, #3b82f6);
        color: white;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s;
    }
    
    .search-box button:hover {
        transform: scale(1.05);
        box-shadow: 0 5px 20px rgba(37, 99, 235, 0.4);
    }

    /* Section Title */
    .section-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1rem;
        padding-bottom: 0.75rem;
        border-bottom: 2px solid #e2e8f0;
    }
    
    .section-title {
        font-size: 1.25rem;
        font-weight: 700;
        color: #1e293b;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    
    .section-link {
        color: #2563eb;
        text-decoration: none;
        font-weight: 600;
        font-size: 0.9rem;
        display: flex;
        align-items: center;
        gap: 5px;
        transition: all 0.3s;
    }
    
    .section-link:hover {
        color: #1d4ed8;
        gap: 10px;
    }

    /* Book Grid - Full Width */
    .books-grid {
        display: grid;
        grid-template-columns: repeat(6, 1fr);
        gap: 1rem;
    }
    
    @media (max-width: 1400px) {
        .books-grid { grid-template-columns: repeat(5, 1fr); }
    }
    
    @media (max-width: 1200px) {
        .books-grid { grid-template-columns: repeat(4, 1fr); }
    }
    
    @media (max-width: 900px) {
        .books-grid { grid-template-columns: repeat(3, 1fr); }
    }
    
    @media (max-width: 600px) {
        .books-grid { grid-template-columns: repeat(2, 1fr); }
    }

    /* Book Card - Compact */
    .book-card-modern {
        background: white;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 2px 10px rgba(0,0,0,0.06);
        transition: all 0.3s ease;
        border: 1px solid #f1f5f9;
    }
    
    .book-card-modern:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 30px rgba(0,0,0,0.12);
    }
    
    .book-card-image {
        position: relative;
        height: 200px;
        background: linear-gradient(135deg, #f8fafc, #e2e8f0);
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
    }
    
    .book-card-image img {
        max-width: 65%;
        max-height: 85%;
        object-fit: contain;
        border-radius: 4px;
        box-shadow: 0 3px 15px rgba(0,0,0,0.15);
        transition: transform 0.3s;
    }
    
    .book-card-modern:hover .book-card-image img {
        transform: scale(1.05);
    }
    
    .book-badge {
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
    
    .book-card-body {
        padding: 0.875rem;
    }
    
    .book-category {
        display: inline-block;
        padding: 2px 8px;
        background: #eff6ff;
        color: #2563eb;
        border-radius: 12px;
        font-size: 0.7rem;
        font-weight: 600;
        margin-bottom: 0.4rem;
    }
    
    .book-card-title {
        font-size: 0.9rem;
        font-weight: 600;
        color: #1e293b;
        margin-bottom: 0.35rem;
        line-height: 1.35;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        min-height: 2.4em;
    }
    
    .book-card-title a {
        color: inherit;
        text-decoration: none;
        transition: color 0.3s;
    }
    
    .book-card-title a:hover {
        color: #2563eb;
    }
    
    .book-author {
        color: #64748b;
        font-size: 0.8rem;
        margin-bottom: 0.5rem;
    }
    
    .book-price-row {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 0.75rem;
    }
    
    .book-price-current {
        font-size: 1.1rem;
        font-weight: 700;
        color: #dc2626;
    }
    
    .book-price-old {
        font-size: 0.8rem;
        color: #94a3b8;
        text-decoration: line-through;
    }
    
    .book-actions {
        display: flex;
        gap: 6px;
    }
    
    .btn-cart {
        flex: 1;
        padding: 8px 12px;
        border: none;
        border-radius: 8px;
        font-weight: 600;
        font-size: 0.8rem;
        cursor: pointer;
        transition: all 0.3s;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 4px;
    }
    
    .btn-cart-primary {
        background: linear-gradient(135deg, #2563eb, #3b82f6);
        color: white;
    }
    
    .btn-cart-primary:hover {
        background: linear-gradient(135deg, #1d4ed8, #2563eb);
        transform: translateY(-2px);
    }
    
    .btn-cart-secondary {
        background: #f1f5f9;
        color: #475569;
        padding: 8px;
        flex: 0;
    }
    
    .btn-cart-secondary:hover {
        background: #fee2e2;
        color: #dc2626;
    }

    /* Categories Grid - Compact */
    .categories-grid {
        display: grid;
        grid-template-columns: repeat(6, 1fr);
        gap: 0.75rem;
    }
    
    @media (max-width: 1200px) {
        .categories-grid { grid-template-columns: repeat(4, 1fr); }
    }
    
    @media (max-width: 768px) {
        .categories-grid { grid-template-columns: repeat(3, 1fr); }
    }
    
    @media (max-width: 480px) {
        .categories-grid { grid-template-columns: repeat(2, 1fr); }
    }
    
    .category-card {
        background: white;
        border-radius: 12px;
        padding: 1rem 0.75rem;
        text-align: center;
        box-shadow: 0 2px 10px rgba(0,0,0,0.04);
        transition: all 0.3s;
        border: 2px solid transparent;
        text-decoration: none;
        color: inherit;
    }
    
    .category-card:hover {
        transform: translateY(-3px);
        border-color: #2563eb;
        box-shadow: 0 8px 25px rgba(37, 99, 235, 0.12);
    }
    
    .category-icon {
        font-size: 2rem;
        margin-bottom: 0.5rem;
    }
    
    .category-name {
        font-weight: 600;
        font-size: 0.9rem;
        color: #1e293b;
        margin-bottom: 0.2rem;
    }
    
    .category-count {
        font-size: 0.75rem;
        color: #64748b;
    }

    /* Stats Section - Compact */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 1rem;
    }
    
    @media (max-width: 768px) {
        .stats-grid { grid-template-columns: repeat(2, 1fr); }
    }
    
    .stat-card {
        background: white;
        border-radius: 12px;
        padding: 1.25rem;
        text-align: center;
        box-shadow: 0 2px 10px rgba(0,0,0,0.04);
        border-left: 4px solid;
    }
    
    .stat-card.books { border-color: #2563eb; }
    .stat-card.authors { border-color: #16a34a; }
    .stat-card.categories { border-color: #d97706; }
    .stat-card.users { border-color: #dc2626; }
    
    .stat-number {
        font-size: 2rem;
        font-weight: 800;
        margin-bottom: 0.2rem;
    }
    
    .stat-card.books .stat-number { color: #2563eb; }
    .stat-card.authors .stat-number { color: #16a34a; }
    .stat-card.categories .stat-number { color: #d97706; }
    .stat-card.users .stat-number { color: #dc2626; }
    
    .stat-label {
        color: #64748b;
        font-weight: 500;
        font-size: 0.9rem;
    }

    /* Content Section - Compact */
    .content-section {
        background: white;
        border-radius: 16px;
        padding: 1.25rem;
        margin-bottom: 1.25rem;
        box-shadow: 0 2px 15px rgba(0,0,0,0.04);
    }
</style>
@endpush

@section('content')
<!-- Hero Section -->
<div class="hero-section">
    <div class="hero-content">
        <h1 class="hero-title">📚 Chào mừng đến với BookStore</h1>
        <p class="hero-subtitle">Khám phá thế giới sách với hàng ngàn đầu sách chất lượng</p>
        
        <form action="{{ route('search') }}" method="GET" class="search-box">
            <input type="text" name="q" placeholder="Tìm kiếm sách, tác giả, thể loại..." value="{{ request('q') }}">
            <button type="submit">🔍 Tìm kiếm</button>
        </form>
    </div>
</div>

<!-- Featured Books -->
<div class="content-section">
    <div class="section-header">
        <h2 class="section-title">🔥 Sách nổi bật</h2>
        <a href="{{ route('search') }}" class="section-link">Xem tất cả →</a>
    </div>
    
    <div class="books-grid">
        @forelse($sachNoiBat as $sach)
            @include('partials.book-card-new', ['book' => $sach])
        @empty
            <p class="text-center" style="grid-column: 1/-1; color: #64748b;">Chưa có sách nổi bật nào.</p>
        @endforelse
    </div>
</div>

<!-- New Books -->
<div class="content-section">
    <div class="section-header">
        <h2 class="section-title">🆕 Sách mới nhất</h2>
        <a href="{{ route('search') }}?sort=newest" class="section-link">Xem tất cả →</a>
    </div>
    
    <div class="books-grid">
        @forelse($sachMoi as $sach)
            @include('partials.book-card-new', ['book' => $sach])
        @empty
            <p class="text-center" style="grid-column: 1/-1; color: #64748b;">Chưa có sách mới nào.</p>
        @endforelse
    </div>
</div>

<!-- Categories -->
<div class="content-section">
    <div class="section-header">
        <h2 class="section-title">📂 Danh mục sách</h2>
        <a href="{{ route('categories') }}" class="section-link">Xem tất cả →</a>
    </div>
    
    <div class="categories-grid">
        @php
            $categoryIcons = [
                'Văn học' => '📖',
                'Kinh tế' => '💼',
                'Kỹ năng sống' => '🎯',
                'Khoa học' => '🔬',
                'Thiếu nhi' => '🧒',
                'Tâm lý' => '🧠',
                'Lịch sử' => '🏛️',
                'Công nghệ' => '💻',
            ];
        @endphp
        
        @forelse($theLoais as $theLoai)
            <a href="{{ route('category', $theLoai->duong_dan) }}" class="category-card">
                <div class="category-icon">{{ $categoryIcons[$theLoai->ten_the_loai] ?? '📚' }}</div>
                <div class="category-name">{{ $theLoai->ten_the_loai }}</div>
                <div class="category-count">{{ $theLoai->sach_count ?? 0 }} cuốn</div>
            </a>
        @empty
            <p class="text-center" style="grid-column: 1/-1; color: #64748b;">Chưa có danh mục nào.</p>
        @endforelse
    </div>
</div>

<!-- Toast Container -->
<div class="toast-container">
    <div id="toast" class="toast"></div>
</div>
@endsection

@push('scripts')
<script>
    function showToast(message, type = 'success') {
        const toast = document.getElementById('toast');
        toast.textContent = (type === 'success' ? '✓ ' : '✗ ') + message;
        toast.className = 'toast ' + type + ' show';
        setTimeout(() => toast.classList.remove('show'), 3000);
    }

    function addToCart(bookId) {
        fetch('{{ route("cart.add") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: JSON.stringify({ ma_sach: bookId, so_luong: 1 })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showToast('Đã thêm vào giỏ hàng!', 'success');
            } else {
                showToast(data.message || 'Có lỗi xảy ra!', 'error');
            }
        })
        .catch(() => showToast('Có lỗi xảy ra!', 'error'));
    }
</script>
@endpush
