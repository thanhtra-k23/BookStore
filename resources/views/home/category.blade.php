@extends('layouts.pure-blade')

@section('title', $title)

@push('styles')
<style>
    /* Hero Section cho Danh mục */
    .category-hero {
        background: linear-gradient(135deg, #2563eb 0%, #7c3aed 100%);
        color: white;
        padding: 3rem 0;
        margin: -1.5rem -15px 0;
        width: calc(100% + 30px);
    }

    .category-hero-content {
        text-align: center;
    }

    .category-icon-wrapper {
        width: 80px;
        height: 80px;
        background: rgba(255, 255, 255, 0.2);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1rem;
        font-size: 2.5rem;
    }

    .category-title {
        font-size: 2.5rem;
        font-weight: 800;
        margin-bottom: 0.5rem;
    }

    .category-desc {
        font-size: 1.1rem;
        opacity: 0.9;
        max-width: 600px;
        margin: 0 auto;
    }

    /* Filter Bar */
    .filter-bar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 1rem 0;
        margin-bottom: 1.5rem;
        flex-wrap: wrap;
        gap: 1rem;
    }

    .filter-bar .result-count {
        color: #64748b;
        font-size: 0.95rem;
    }

    .filter-bar .result-count span {
        font-weight: 700;
        color: #2563eb;
    }

    .sort-select {
        padding: 0.6rem 1rem;
        border: 2px solid #e2e8f0;
        border-radius: 10px;
        font-size: 0.9rem;
        background: white;
        cursor: pointer;
        transition: all 0.3s;
        min-width: 180px;
    }

    .sort-select:focus {
        outline: none;
        border-color: #2563eb;
        box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
    }

    /* Book Grid - Full Width */
    .books-grid-category {
        display: grid;
        grid-template-columns: repeat(6, 1fr);
        gap: 1rem;
    }

    @media (max-width: 1400px) {
        .books-grid-category { grid-template-columns: repeat(5, 1fr); }
    }

    @media (max-width: 1200px) {
        .books-grid-category { grid-template-columns: repeat(4, 1fr); }
    }

    @media (max-width: 900px) {
        .books-grid-category { grid-template-columns: repeat(3, 1fr); }
    }

    @media (max-width: 600px) {
        .books-grid-category { grid-template-columns: repeat(2, 1fr); }
        .category-title { font-size: 1.75rem; }
        .category-hero { padding: 2rem 0; }
    }

    /* Book Card Modern */
    .book-card-cat {
        background: white;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 2px 10px rgba(0,0,0,0.06);
        transition: all 0.3s ease;
        border: 1px solid #f1f5f9;
    }

    .book-card-cat:hover {
        transform: translateY(-5px) scale(1.02);
        box-shadow: 0 15px 35px rgba(0,0,0,0.12);
    }

    .book-card-cat .book-image-wrapper {
        position: relative;
        height: 220px;
        background: linear-gradient(135deg, #f8fafc, #e2e8f0);
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
    }

    .book-card-cat .book-image-wrapper img {
        max-width: 70%;
        max-height: 90%;
        object-fit: contain;
        border-radius: 4px;
        box-shadow: 0 3px 15px rgba(0,0,0,0.15);
        transition: transform 0.3s;
    }

    .book-card-cat:hover .book-image-wrapper img {
        transform: scale(1.05);
    }

    .book-card-cat .discount-badge {
        position: absolute;
        top: 8px;
        left: 8px;
        background: linear-gradient(135deg, #ef4444, #dc2626);
        color: white;
        padding: 4px 10px;
        border-radius: 15px;
        font-size: 0.7rem;
        font-weight: 700;
        z-index: 5;
    }

    .book-card-cat .wishlist-btn {
        position: absolute;
        top: 8px;
        right: 8px;
        width: 32px;
        height: 32px;
        background: rgba(255, 255, 255, 0.9);
        border: none;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.3s;
        z-index: 5;
        font-size: 1rem;
    }

    .book-card-cat .wishlist-btn:hover {
        background: #fee2e2;
        transform: scale(1.1);
    }

    .book-card-cat .book-body {
        padding: 0.875rem;
    }

    .book-card-cat .book-title {
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

    .book-card-cat .book-title a {
        color: inherit;
        text-decoration: none;
    }

    .book-card-cat .book-title a:hover {
        color: #2563eb;
    }

    .book-card-cat .book-rating {
        display: flex;
        align-items: center;
        gap: 4px;
        margin-bottom: 0.5rem;
        font-size: 0.75rem;
    }

    .book-card-cat .book-rating .stars {
        color: #f59e0b;
    }

    .book-card-cat .book-rating .count {
        color: #94a3b8;
    }

    .book-card-cat .book-price-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 8px;
    }

    .book-card-cat .price-current {
        font-size: 1.1rem;
        font-weight: 700;
        color: #dc2626;
    }

    .book-card-cat .price-old {
        font-size: 0.75rem;
        color: #94a3b8;
        text-decoration: line-through;
    }

    .book-card-cat .btn-add-cart {
        width: 36px;
        height: 36px;
        background: linear-gradient(135deg, #2563eb, #3b82f6);
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

    .book-card-cat .btn-add-cart:hover {
        background: linear-gradient(135deg, #1d4ed8, #2563eb);
        transform: scale(1.1);
    }

    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 4rem 2rem;
    }

    .empty-state .icon {
        font-size: 5rem;
        margin-bottom: 1rem;
    }

    .empty-state h3 {
        color: #64748b;
        margin-bottom: 0.5rem;
    }

    .empty-state p {
        color: #94a3b8;
        margin-bottom: 1.5rem;
    }

    /* Pagination */
    .pagination-wrapper {
        margin-top: 2rem;
        display: flex;
        justify-content: center;
    }
</style>
@endpush

@section('content')
<!-- Hero Section cho Danh mục -->
<div class="category-hero">
    <div class="container">
        <div class="category-hero-content">
            <div class="category-icon-wrapper">
                @php
                    $icons = [
                        'Văn học' => '📖',
                        'Kinh tế' => '💼',
                        'Kỹ năng sống' => '🎯',
                        'Khoa học' => '🔬',
                        'Thiếu nhi' => '🧒',
                        'Tâm lý' => '🧠',
                        'Lịch sử' => '🏛️',
                        'Công nghệ' => '💻',
                        'Ngoại ngữ' => '🌍',
                        'Y học' => '⚕️',
                    ];
                    $icon = $icons[$category->ten_the_loai] ?? '📚';
                @endphp
                {{ $icon }}
            </div>
            <h1 class="category-title">{{ $category->ten_the_loai }}</h1>
            <p class="category-desc">
                {{ $category->mo_ta ?? 'Khám phá bộ sưu tập sách ' . $category->ten_the_loai . ' với những đầu sách hay nhất' }}
            </p>
        </div>
    </div>
</div>

<div class="container" style="padding-top: 1.5rem;">
    <!-- Filter Bar -->
    <div class="filter-bar">
        <p class="result-count">
            Tìm thấy <span>{{ $books->total() }}</span> cuốn sách
        </p>
        
        <form method="GET" action="{{ route('category', $category->duong_dan) }}">
            <select name="sort" class="sort-select" onchange="this.form.submit()">
                <option value="newest" {{ request('sort', 'newest') == 'newest' ? 'selected' : '' }}>Mới nhất</option>
                <option value="popular" {{ request('sort') == 'popular' ? 'selected' : '' }}>Bán chạy</option>
                <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Giá thấp → cao</option>
                <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Giá cao → thấp</option>
                <option value="rating" {{ request('sort') == 'rating' ? 'selected' : '' }}>Đánh giá cao</option>
            </select>
        </form>
    </div>

    @if($books->count() > 0)
        <!-- Books Grid -->
        <div class="books-grid-category">
            @foreach($books as $book)
                <div class="book-card-cat">
                    <div class="book-image-wrapper">
                        @php
                            $discount = ($book->gia_khuyen_mai && $book->gia_ban > 0) 
                                ? round((($book->gia_ban - $book->gia_khuyen_mai) / $book->gia_ban) * 100) 
                                : 0;
                        @endphp
                        
                        @if($discount > 0)
                            <div class="discount-badge">-{{ $discount }}%</div>
                        @endif
                        
                        <button class="wishlist-btn" onclick="toggleWishlist({{ $book->ma_sach }})" title="Yêu thích">
                            ❤️
                        </button>
                        
                        <a href="{{ route('book.detail', ['id' => $book->ma_sach, 'slug' => $book->slug ?? Str::slug($book->ten_sach)]) }}">
                            <img src="{{ $book->anh_bia_url ?? '/images/no-image.png' }}" 
                                 alt="{{ $book->ten_sach }}"
                                 onerror="this.src='/images/no-image.png'">
                        </a>
                    </div>
                    
                    <div class="book-body">
                        <h3 class="book-title">
                            <a href="{{ route('book.detail', ['id' => $book->ma_sach, 'slug' => $book->slug ?? Str::slug($book->ten_sach)]) }}">
                                {{ $book->ten_sach }}
                            </a>
                        </h3>
                        
                        <div class="book-rating">
                            <span class="stars">★★★★☆</span>
                            <span class="count">({{ rand(10, 200) }})</span>
                        </div>
                        
                        <div class="book-price-row">
                            <div>
                                @if($book->gia_khuyen_mai && $book->gia_khuyen_mai < $book->gia_ban)
                                    <div class="price-current">{{ number_format($book->gia_khuyen_mai) }}₫</div>
                                    <div class="price-old">{{ number_format($book->gia_ban) }}₫</div>
                                @else
                                    <div class="price-current">{{ number_format($book->gia_ban) }}₫</div>
                                @endif
                            </div>
                            
                            <button class="btn-add-cart" onclick="addToCartQuick({{ $book->ma_sach }})" title="Thêm vào giỏ">
                                🛒
                            </button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="pagination-wrapper">
            {{ $books->appends(request()->query())->links() }}
        </div>
    @else
        <!-- Empty State -->
        <div class="empty-state">
            <div class="icon">📚</div>
            <h3>Chưa có sách nào</h3>
            <p>Thể loại "{{ $category->ten_the_loai }}" hiện chưa có sách nào.</p>
            <div>
                <a href="{{ route('categories') }}" class="btn btn-primary" style="margin-right: 0.5rem;">
                    📂 Xem thể loại khác
                </a>
                <a href="{{ route('home') }}" class="btn btn-secondary">
                    🏠 Về trang chủ
                </a>
            </div>
        </div>
    @endif
</div>
@endsection
