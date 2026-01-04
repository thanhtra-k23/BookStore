@extends('layouts.pure-blade')

@section('title', $title)

@push('styles')
<style>
    /* Modern Search Page 2025 */
    .search-hero {
        background: linear-gradient(135deg, #1e3a5f 0%, #2d5a87 50%, #3b7cb8 100%);
        padding: 3rem 0;
        margin: -2.5rem -20px 0;
        position: relative;
        overflow: hidden;
    }
    
    .search-hero::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.05'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
    }
    
    .search-hero-content {
        position: relative;
        z-index: 1;
        text-align: center;
        color: white;
    }
    
    .search-hero h1 {
        font-size: 2rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
    }
    
    .search-hero p {
        opacity: 0.9;
        font-size: 1rem;
        margin-bottom: 1.5rem;
    }
</style>
@endpush

@section('content')
<!-- Search Hero -->
<div class="search-hero">
    <div class="container">
        <div class="search-hero-content">
            <h1>🔍 Tìm kiếm sách</h1>
            @if($keyword)
                <p>Kết quả cho: <strong>"{{ $keyword }}"</strong></p>
            @else
                <p>Khám phá kho sách phong phú với hàng nghìn đầu sách</p>
            @endif
        </div>
    </div>
</div>

<!-- Main Search Container -->
<div class="search-container">
    <!-- Sidebar Filters -->
    <aside class="search-sidebar">
        <form method="GET" action="{{ route('search') }}" id="filterForm">
            <!-- Search Input -->
            <div class="filter-section">
                <h3 class="filter-title">
                    <span class="filter-icon">🔍</span>
                    Từ khóa tìm kiếm
                </h3>
                <div class="search-input-wrapper">
                    <input type="text" 
                           name="q" 
                           class="search-input"
                           placeholder="Nhập tên sách, tác giả..." 
                           value="{{ $keyword }}">
                    <button type="submit" class="search-btn">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="11" cy="11" r="8"></circle>
                            <path d="m21 21-4.35-4.35"></path>
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Category Filter -->
            <div class="filter-section">
                <h3 class="filter-title">
                    <span class="filter-icon">📂</span>
                    Thể loại
                </h3>
                <select name="the_loai_id" class="filter-select" onchange="this.form.submit()">
                    <option value="">Tất cả thể loại</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->ma_the_loai }}" 
                                {{ request('the_loai_id') == $category->ma_the_loai ? 'selected' : '' }}>
                            {{ $category->ten_the_loai }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Author Filter -->
            <div class="filter-section">
                <h3 class="filter-title">
                    <span class="filter-icon">✍️</span>
                    Tác giả
                </h3>
                <select name="tac_gia_id" class="filter-select" onchange="this.form.submit()">
                    <option value="">Tất cả tác giả</option>
                    @foreach($authors as $author)
                        <option value="{{ $author->ma_tac_gia }}" 
                                {{ request('tac_gia_id') == $author->ma_tac_gia ? 'selected' : '' }}>
                            {{ $author->ten_tac_gia }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Price Range Filter -->
            <div class="filter-section">
                <h3 class="filter-title">
                    <span class="filter-icon">💰</span>
                    Khoảng giá
                </h3>
                <div class="price-range">
                    <div class="price-inputs">
                        <input type="number" 
                               name="gia_min" 
                               class="price-input"
                               placeholder="Từ" 
                               value="{{ request('gia_min') }}">
                        <span class="price-separator">-</span>
                        <input type="number" 
                               name="gia_max" 
                               class="price-input"
                               placeholder="Đến" 
                               value="{{ request('gia_max') }}">
                    </div>
                    <div class="price-presets">
                        <button type="button" class="price-preset" onclick="setPriceRange(0, 50000)">Dưới 50k</button>
                        <button type="button" class="price-preset" onclick="setPriceRange(50000, 100000)">50k - 100k</button>
                        <button type="button" class="price-preset" onclick="setPriceRange(100000, 200000)">100k - 200k</button>
                        <button type="button" class="price-preset" onclick="setPriceRange(200000, 0)">Trên 200k</button>
                    </div>
                </div>
            </div>

            <!-- Sort Options -->
            <div class="filter-section">
                <h3 class="filter-title">
                    <span class="filter-icon">📊</span>
                    Sắp xếp theo
                </h3>
                <select name="sort" class="filter-select" onchange="this.form.submit()">
                    <option value="relevance" {{ request('sort') == 'relevance' ? 'selected' : '' }}>Liên quan nhất</option>
                    <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Mới nhất</option>
                    <option value="popular" {{ request('sort') == 'popular' ? 'selected' : '' }}>Phổ biến nhất</option>
                    <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Giá: Thấp → Cao</option>
                    <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Giá: Cao → Thấp</option>
                </select>
            </div>

            <!-- Action Buttons -->
            <div class="filter-actions">
                <button type="submit" class="btn-apply">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <polyline points="20 6 9 17 4 12"></polyline>
                    </svg>
                    Áp dụng
                </button>
                <a href="{{ route('search') }}" class="btn-reset">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"></path>
                        <path d="M3 3v5h5"></path>
                    </svg>
                    Đặt lại
                </a>
            </div>
        </form>
    </aside>

    <!-- Main Content -->
    <main class="search-main">
        <!-- Results Header -->
        <div class="results-header">
            <div class="results-info">
                @if($sach->count() > 0)
                    <span class="results-count">
                        Tìm thấy <strong>{{ $totalResults ?? $sach->total() }}</strong> kết quả
                    </span>
                    @if($keyword)
                        <span class="results-keyword">cho "{{ $keyword }}"</span>
                    @endif
                @endif
            </div>
            
            <div class="view-toggle">
                <button type="button" class="view-btn active" data-view="grid" onclick="setView('grid')">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <rect x="3" y="3" width="7" height="7"></rect>
                        <rect x="14" y="3" width="7" height="7"></rect>
                        <rect x="14" y="14" width="7" height="7"></rect>
                        <rect x="3" y="14" width="7" height="7"></rect>
                    </svg>
                </button>
                <button type="button" class="view-btn" data-view="list" onclick="setView('list')">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <line x1="8" y1="6" x2="21" y2="6"></line>
                        <line x1="8" y1="12" x2="21" y2="12"></line>
                        <line x1="8" y1="18" x2="21" y2="18"></line>
                        <line x1="3" y1="6" x2="3.01" y2="6"></line>
                        <line x1="3" y1="12" x2="3.01" y2="12"></line>
                        <line x1="3" y1="18" x2="3.01" y2="18"></line>
                    </svg>
                </button>
            </div>
        </div>

        @if($sach->count() > 0)
            <!-- Book Grid -->
            <div class="book-grid" id="bookGrid">
                @foreach($sach as $book)
                    @include('partials.book-card-pure', ['book' => $book])
                @endforeach
            </div>

            <!-- Pagination -->
            @if($sach->hasPages())
                <div class="pagination-wrapper">
                    <div class="pagination-modern">
                        @if($sach->onFirstPage())
                            <span class="page-btn disabled">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <polyline points="15 18 9 12 15 6"></polyline>
                                </svg>
                            </span>
                        @else
                            <a href="{{ $sach->previousPageUrl() }}" class="page-btn">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <polyline points="15 18 9 12 15 6"></polyline>
                                </svg>
                            </a>
                        @endif
                        
                        @php
                            $currentPage = $sach->currentPage();
                            $lastPage = $sach->lastPage();
                            $start = max(1, $currentPage - 2);
                            $end = min($lastPage, $currentPage + 2);
                        @endphp
                        
                        @if($start > 1)
                            <a href="{{ $sach->url(1) }}" class="page-btn">1</a>
                            @if($start > 2)
                                <span class="page-dots">...</span>
                            @endif
                        @endif
                        
                        @for($page = $start; $page <= $end; $page++)
                            @if($page == $currentPage)
                                <span class="page-btn active">{{ $page }}</span>
                            @else
                                <a href="{{ $sach->url($page) }}" class="page-btn">{{ $page }}</a>
                            @endif
                        @endfor
                        
                        @if($end < $lastPage)
                            @if($end < $lastPage - 1)
                                <span class="page-dots">...</span>
                            @endif
                            <a href="{{ $sach->url($lastPage) }}" class="page-btn">{{ $lastPage }}</a>
                        @endif
                        
                        @if($sach->hasMorePages())
                            <a href="{{ $sach->nextPageUrl() }}" class="page-btn">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <polyline points="9 18 15 12 9 6"></polyline>
                                </svg>
                            </a>
                        @else
                            <span class="page-btn disabled">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <polyline points="9 18 15 12 9 6"></polyline>
                                </svg>
                            </span>
                        @endif
                    </div>
                </div>
            @endif

        @else
            <!-- Empty State -->
            <div class="empty-state">
                <div class="empty-illustration">
                    <svg width="120" height="120" viewBox="0 0 24 24" fill="none" stroke="#cbd5e1" stroke-width="1">
                        <circle cx="11" cy="11" r="8"></circle>
                        <path d="m21 21-4.35-4.35"></path>
                        <path d="M8 8l6 6M14 8l-6 6" stroke="#94a3b8"></path>
                    </svg>
                </div>
                <h3 class="empty-title">Không tìm thấy kết quả</h3>
                @if($keyword)
                    <p class="empty-desc">Không có sách nào phù hợp với từ khóa "<strong>{{ $keyword }}</strong>"</p>
                    <div class="empty-suggestions">
                        <p>Gợi ý:</p>
                        <ul>
                            <li>Kiểm tra lại chính tả từ khóa</li>
                            <li>Thử tìm kiếm với từ khóa khác</li>
                            <li>Sử dụng từ khóa ngắn gọn hơn</li>
                        </ul>
                    </div>
                @else
                    <p class="empty-desc">Vui lòng nhập từ khóa để bắt đầu tìm kiếm</p>
                @endif
                <div class="empty-actions">
                    <a href="{{ route('home') }}" class="btn-home">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                            <polyline points="9 22 9 12 15 12 15 22"></polyline>
                        </svg>
                        Về trang chủ
                    </a>
                    <a href="{{ route('categories') }}" class="btn-browse">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"></path>
                        </svg>
                        Xem thể loại
                    </a>
                </div>
            </div>
        @endif
    </main>
</div>

<!-- Mobile Filter Toggle -->
<button class="mobile-filter-toggle" onclick="toggleMobileFilter()">
    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
        <line x1="4" y1="21" x2="4" y2="14"></line>
        <line x1="4" y1="10" x2="4" y2="3"></line>
        <line x1="12" y1="21" x2="12" y2="12"></line>
        <line x1="12" y1="8" x2="12" y2="3"></line>
        <line x1="20" y1="21" x2="20" y2="16"></line>
        <line x1="20" y1="12" x2="20" y2="3"></line>
        <line x1="1" y1="14" x2="7" y2="14"></line>
        <line x1="9" y1="8" x2="15" y2="8"></line>
        <line x1="17" y1="16" x2="23" y2="16"></line>
    </svg>
    Bộ lọc
</button>

<!-- Mobile Filter Overlay -->
<div class="mobile-filter-overlay" id="mobileFilterOverlay" onclick="toggleMobileFilter()"></div>
@endsection

@push('styles')
<style>
    /* Search Container Layout */
    .search-container {
        display: grid;
        grid-template-columns: 280px 1fr;
        gap: 2rem;
        margin-top: 2rem;
        padding-bottom: 3rem;
    }
    
    /* Sidebar Styles */
    .search-sidebar {
        background: white;
        border-radius: 16px;
        padding: 1.5rem;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        height: fit-content;
        position: sticky;
        top: 100px;
    }
    
    .filter-section {
        margin-bottom: 1.5rem;
        padding-bottom: 1.5rem;
        border-bottom: 1px solid #e2e8f0;
    }
    
    .filter-section:last-of-type {
        border-bottom: none;
        margin-bottom: 1rem;
    }
    
    .filter-title {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.95rem;
        font-weight: 600;
        color: #1e293b;
        margin-bottom: 1rem;
    }
    
    .filter-icon {
        font-size: 1.1rem;
    }
    
    /* Search Input */
    .search-input-wrapper {
        display: flex;
        gap: 0.5rem;
    }
    
    .search-input {
        flex: 1;
        padding: 0.75rem 1rem;
        border: 2px solid #e2e8f0;
        border-radius: 10px;
        font-size: 0.95rem;
        transition: all 0.3s;
    }
    
    .search-input:focus {
        outline: none;
        border-color: #3b82f6;
        box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.1);
    }
    
    .search-btn {
        padding: 0.75rem;
        background: linear-gradient(135deg, #3b82f6, #2563eb);
        border: none;
        border-radius: 10px;
        color: white;
        cursor: pointer;
        transition: all 0.3s;
    }
    
    .search-btn:hover {
        transform: scale(1.05);
        box-shadow: 0 4px 15px rgba(59, 130, 246, 0.4);
    }
    
    /* Filter Select */
    .filter-select {
        width: 100%;
        padding: 0.75rem 1rem;
        border: 2px solid #e2e8f0;
        border-radius: 10px;
        font-size: 0.95rem;
        background: white;
        cursor: pointer;
        transition: all 0.3s;
    }
    
    .filter-select:focus {
        outline: none;
        border-color: #3b82f6;
    }
    
    /* Price Range */
    .price-inputs {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        margin-bottom: 0.75rem;
    }
    
    .price-input {
        flex: 1;
        padding: 0.6rem 0.75rem;
        border: 2px solid #e2e8f0;
        border-radius: 8px;
        font-size: 0.9rem;
        text-align: center;
    }
    
    .price-input:focus {
        outline: none;
        border-color: #3b82f6;
    }
    
    .price-separator {
        color: #94a3b8;
        font-weight: 500;
    }
    
    .price-presets {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 0.5rem;
    }
    
    .price-preset {
        padding: 0.5rem;
        background: #f1f5f9;
        border: 1px solid #e2e8f0;
        border-radius: 6px;
        font-size: 0.8rem;
        color: #475569;
        cursor: pointer;
        transition: all 0.2s;
    }
    
    .price-preset:hover {
        background: #e0f2fe;
        border-color: #3b82f6;
        color: #2563eb;
    }
    
    /* Filter Actions */
    .filter-actions {
        display: flex;
        gap: 0.75rem;
    }
    
    .btn-apply {
        flex: 1;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        padding: 0.75rem;
        background: linear-gradient(135deg, #3b82f6, #2563eb);
        border: none;
        border-radius: 10px;
        color: white;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s;
    }
    
    .btn-apply:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(59, 130, 246, 0.4);
    }
    
    .btn-reset {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        padding: 0.75rem 1rem;
        background: #f1f5f9;
        border: none;
        border-radius: 10px;
        color: #64748b;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.3s;
    }
    
    .btn-reset:hover {
        background: #e2e8f0;
        color: #475569;
    }
</style>
@endpush

@push('styles')
<style>
    /* Results Header */
    .results-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.5rem;
        padding: 1rem 1.25rem;
        background: white;
        border-radius: 12px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
    }
    
    .results-info {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        flex-wrap: wrap;
    }
    
    .results-count {
        color: #475569;
        font-size: 0.95rem;
    }
    
    .results-count strong {
        color: #2563eb;
        font-weight: 700;
    }
    
    .results-keyword {
        color: #64748b;
        font-size: 0.9rem;
    }
    
    /* View Toggle */
    .view-toggle {
        display: flex;
        gap: 0.25rem;
        background: #f1f5f9;
        padding: 0.25rem;
        border-radius: 8px;
    }
    
    .view-btn {
        padding: 0.5rem 0.75rem;
        background: transparent;
        border: none;
        border-radius: 6px;
        color: #64748b;
        cursor: pointer;
        transition: all 0.2s;
    }
    
    .view-btn:hover {
        color: #2563eb;
    }
    
    .view-btn.active {
        background: white;
        color: #2563eb;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    }
    
    /* Book Grid - Override for search page */
    .search-main .book-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(240px, 1fr));
        gap: 1.5rem;
    }
    
    .search-main .book-grid.list-view {
        grid-template-columns: 1fr;
    }
    
    .search-main .book-grid.list-view .book-card {
        display: grid;
        grid-template-columns: 180px 1fr;
        max-width: 100%;
    }
    
    .search-main .book-grid.list-view .book-cover-wrapper {
        height: 240px;
    }
    
    .search-main .book-grid.list-view .book-details {
        display: flex;
        flex-direction: column;
        justify-content: center;
    }
    
    /* Pagination Modern */
    .pagination-wrapper {
        display: flex;
        justify-content: center;
        margin-top: 2.5rem;
    }
    
    .pagination-modern {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        background: white;
        padding: 0.5rem;
        border-radius: 12px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
    }
    
    .page-btn {
        display: flex;
        align-items: center;
        justify-content: center;
        min-width: 40px;
        height: 40px;
        padding: 0 0.75rem;
        background: transparent;
        border: none;
        border-radius: 8px;
        color: #475569;
        font-weight: 500;
        text-decoration: none;
        transition: all 0.2s;
    }
    
    .page-btn:hover:not(.disabled):not(.active) {
        background: #f1f5f9;
        color: #2563eb;
    }
    
    .page-btn.active {
        background: linear-gradient(135deg, #3b82f6, #2563eb);
        color: white;
    }
    
    .page-btn.disabled {
        color: #cbd5e1;
        cursor: not-allowed;
    }
    
    .page-dots {
        color: #94a3b8;
        padding: 0 0.25rem;
    }
</style>
@endpush

@push('styles')
<style>
    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 4rem 2rem;
        background: white;
        border-radius: 20px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    }
    
    .empty-illustration {
        margin-bottom: 1.5rem;
    }
    
    .empty-title {
        font-size: 1.5rem;
        font-weight: 700;
        color: #1e293b;
        margin-bottom: 0.75rem;
    }
    
    .empty-desc {
        color: #64748b;
        margin-bottom: 1.5rem;
        font-size: 1rem;
    }
    
    .empty-suggestions {
        background: #f8fafc;
        border-radius: 12px;
        padding: 1.25rem;
        margin-bottom: 1.5rem;
        text-align: left;
        max-width: 400px;
        margin-left: auto;
        margin-right: auto;
    }
    
    .empty-suggestions p {
        font-weight: 600;
        color: #475569;
        margin-bottom: 0.5rem;
    }
    
    .empty-suggestions ul {
        list-style: none;
        padding: 0;
        margin: 0;
    }
    
    .empty-suggestions li {
        color: #64748b;
        padding: 0.35rem 0;
        padding-left: 1.25rem;
        position: relative;
    }
    
    .empty-suggestions li::before {
        content: '•';
        position: absolute;
        left: 0;
        color: #3b82f6;
    }
    
    .empty-actions {
        display: flex;
        gap: 1rem;
        justify-content: center;
        flex-wrap: wrap;
    }
    
    .btn-home, .btn-browse {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.85rem 1.5rem;
        border-radius: 10px;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.3s;
    }
    
    .btn-home {
        background: linear-gradient(135deg, #3b82f6, #2563eb);
        color: white;
    }
    
    .btn-home:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(59, 130, 246, 0.4);
    }
    
    .btn-browse {
        background: #f1f5f9;
        color: #475569;
    }
    
    .btn-browse:hover {
        background: #e2e8f0;
    }
    
    /* Mobile Filter */
    .mobile-filter-toggle {
        display: none;
        position: fixed;
        bottom: 20px;
        right: 20px;
        padding: 1rem 1.25rem;
        background: linear-gradient(135deg, #3b82f6, #2563eb);
        border: none;
        border-radius: 50px;
        color: white;
        font-weight: 600;
        cursor: pointer;
        box-shadow: 0 4px 20px rgba(59, 130, 246, 0.4);
        z-index: 100;
        gap: 0.5rem;
        align-items: center;
    }
    
    .mobile-filter-overlay {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.5);
        z-index: 998;
    }
    
    .mobile-filter-overlay.show {
        display: block;
    }
    
    /* Responsive */
    @media (max-width: 992px) {
        .search-container {
            grid-template-columns: 1fr;
        }
        
        .search-sidebar {
            position: fixed;
            top: 0;
            left: -100%;
            width: 300px;
            height: 100vh;
            border-radius: 0;
            z-index: 999;
            overflow-y: auto;
            transition: left 0.3s ease;
        }
        
        .search-sidebar.show {
            left: 0;
        }
        
        .mobile-filter-toggle {
            display: flex;
        }
    }
    
    @media (max-width: 576px) {
        .search-hero h1 {
            font-size: 1.5rem;
        }
        
        .results-header {
            flex-direction: column;
            gap: 1rem;
            align-items: flex-start;
        }
        
        .search-main .book-grid {
            grid-template-columns: repeat(auto-fill, minmax(160px, 1fr));
            gap: 1rem;
        }
        
        .empty-actions {
            flex-direction: column;
        }
        
        .btn-home, .btn-browse {
            width: 100%;
            justify-content: center;
        }
    }
</style>
@endpush

@push('scripts')
<script>
    // Price Range Presets
    function setPriceRange(min, max) {
        document.querySelector('input[name="gia_min"]').value = min || '';
        document.querySelector('input[name="gia_max"]').value = max || '';
    }
    
    // View Toggle (Grid/List)
    function setView(view) {
        const grid = document.getElementById('bookGrid');
        const buttons = document.querySelectorAll('.view-btn');
        
        buttons.forEach(btn => {
            btn.classList.remove('active');
            if (btn.dataset.view === view) {
                btn.classList.add('active');
            }
        });
        
        if (view === 'list') {
            grid.classList.add('list-view');
        } else {
            grid.classList.remove('list-view');
        }
        
        // Save preference
        localStorage.setItem('searchViewMode', view);
    }
    
    // Mobile Filter Toggle
    function toggleMobileFilter() {
        const sidebar = document.querySelector('.search-sidebar');
        const overlay = document.getElementById('mobileFilterOverlay');
        
        sidebar.classList.toggle('show');
        overlay.classList.toggle('show');
        document.body.style.overflow = sidebar.classList.contains('show') ? 'hidden' : '';
    }
    
    // Initialize on page load
    document.addEventListener('DOMContentLoaded', function() {
        // Restore view preference
        const savedView = localStorage.getItem('searchViewMode');
        if (savedView) {
            setView(savedView);
        }
        
        // Auto-submit on filter change (optional - already handled by onchange)
        // Close mobile filter when clicking outside
        document.addEventListener('click', function(e) {
            const sidebar = document.querySelector('.search-sidebar');
            const toggle = document.querySelector('.mobile-filter-toggle');
            
            if (sidebar.classList.contains('show') && 
                !sidebar.contains(e.target) && 
                !toggle.contains(e.target)) {
                toggleMobileFilter();
            }
        });
    });
</script>
@endpush
