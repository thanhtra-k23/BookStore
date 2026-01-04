<div class="book-card">
    <!-- Book Cover với 3D Effect -->
    <div class="book-cover-wrapper">
        <a href="{{ route('book.detail', [$book->ma_sach, $book->duong_dan]) }}" class="book-cover-link">
            <div class="book-cover-3d">
                <img src="{{ $book->anh_bia_url }}" 
                     class="book-cover-image" 
                     alt="{{ $book->ten_sach }}"
                     loading="lazy"
                     onerror="this.src='https://images.unsplash.com/photo-1544947950-fa07a98d237f?w=300&h=400&fit=crop'">
                <div class="book-spine"></div>
            </div>
        </a>
        
        <!-- Sale Badge - Ribbon Style -->
        @if($book->isOnSale())
            <div class="sale-ribbon">
                <span>-{{ $book->phan_tram_giam_gia }}%</span>
            </div>
        @endif
        
        <!-- Out of Stock Badge -->
        @if(!$book->isInStock())
            <div class="out-of-stock-badge">
                <span>Hết hàng</span>
            </div>
        @endif

        <!-- Quick Actions - Floating Buttons -->
        <div class="quick-actions">
            <button class="action-btn cart-btn" onclick="addToCartQuick({{ $book->ma_sach }})" title="Thêm vào giỏ">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="9" cy="21" r="1"></circle>
                    <circle cx="20" cy="21" r="1"></circle>
                    <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
                </svg>
            </button>
            <button class="action-btn wishlist-btn" onclick="toggleWishlist({{ $book->ma_sach }})" title="Yêu thích">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path>
                </svg>
            </button>
            <a href="{{ route('book.detail', [$book->ma_sach, $book->duong_dan]) }}" class="action-btn view-btn" title="Xem chi tiết">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                    <circle cx="12" cy="12" r="3"></circle>
                </svg>
            </a>
        </div>
    </div>

    <!-- Book Info -->
    <div class="book-details">
        <!-- Category Tag -->
        <div class="category-tag">
            <a href="{{ route('category', $book->theLoai->duong_dan ?? 'uncategorized') }}">
                {{ $book->theLoai->ten_the_loai ?? 'Chưa phân loại' }}
            </a>
        </div>

        <!-- Book Title -->
        <h3 class="book-title">
            <a href="{{ route('book.detail', [$book->ma_sach, $book->duong_dan]) }}">
                {{ Str::limit($book->ten_sach, 45) }}
            </a>
        </h3>

        <!-- Author -->
        <p class="book-author">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                <circle cx="12" cy="7" r="4"></circle>
            </svg>
            <a href="{{ route('author', $book->tacGia->duong_dan ?? 'unknown') }}">
                {{ $book->tacGia->ten_tac_gia ?? 'Chưa cập nhật' }}
            </a>
        </p>

        <!-- Rating Stars -->
        <div class="rating-wrapper">
            <div class="stars">
                @php $rating = $book->diem_danh_gia ?? 0; @endphp
                @for($i = 1; $i <= 5; $i++)
                    <svg class="star {{ $i <= $rating ? 'filled' : '' }}" width="14" height="14" viewBox="0 0 24 24" fill="{{ $i <= $rating ? '#fbbf24' : 'none' }}" stroke="#fbbf24" stroke-width="2">
                        <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon>
                    </svg>
                @endfor
            </div>
            <span class="rating-count">({{ $book->so_luong_danh_gia ?? 0 }})</span>
        </div>

        <!-- Price Section -->
        <div class="price-section">
            <div class="price-info">
                @if($book->isOnSale())
                    <span class="current-price">{{ number_format($book->gia_khuyen_mai, 0, ',', '.') }}đ</span>
                    <span class="original-price">{{ number_format($book->gia_ban, 0, ',', '.') }}đ</span>
                @else
                    <span class="current-price">{{ number_format($book->gia_ban, 0, ',', '.') }}đ</span>
                @endif
            </div>
            <span class="view-count">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                    <circle cx="12" cy="12" r="3"></circle>
                </svg>
                {{ number_format($book->luot_xem ?? 0) }}
            </span>
        </div>

        <!-- Stock Status -->
        <div class="stock-indicator {{ $book->isInStock() ? 'in-stock' : 'out-of-stock' }}">
            @if($book->isInStock())
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <polyline points="20 6 9 17 4 12"></polyline>
                </svg>
                Còn {{ $book->so_luong_ton ?? 0 }} sản phẩm
            @else
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <line x1="18" y1="6" x2="6" y2="18"></line>
                    <line x1="6" y1="6" x2="18" y2="18"></line>
                </svg>
                Hết hàng
            @endif
        </div>

        <!-- Action Buttons -->
        <div class="card-actions">
            @if($book->canOrder())
                <button class="btn-cart" onclick="addToCartQuick({{ $book->ma_sach }})">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="9" cy="21" r="1"></circle>
                        <circle cx="20" cy="21" r="1"></circle>
                        <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
                    </svg>
                    Thêm giỏ
                </button>
                <a href="{{ route('book.detail', [$book->ma_sach, $book->duong_dan]) }}" class="btn-buy">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"></polygon>
                    </svg>
                    Mua ngay
                </a>
            @else
                <button class="btn-notify" onclick="notifyWhenAvailable({{ $book->ma_sach }})">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path>
                        <path d="M13.73 21a2 2 0 0 1-3.46 0"></path>
                    </svg>
                    Thông báo khi có hàng
                </button>
            @endif
        </div>
    </div>
</div>

<style>
/* Modern Book Card Styles */
.book-card {
    background: #ffffff;
    border-radius: 20px;
    overflow: hidden;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    border: 1px solid rgba(226, 232, 240, 0.6);
    position: relative;
}

.book-card:hover {
    transform: translateY(-12px);
    box-shadow: 0 25px 50px rgba(37, 99, 235, 0.2);
}

/* Book Cover Wrapper */
.book-cover-wrapper {
    position: relative;
    overflow: hidden;
    height: 300px;
    background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 20px;
}

.book-cover-link {
    display: block;
    perspective: 1000px;
}

/* 3D Book Effect */
.book-cover-3d {
    position: relative;
    transform-style: preserve-3d;
    transition: transform 0.5s ease;
    width: 180px;
    height: 260px;
}

.book-card:hover .book-cover-3d {
    transform: rotateY(-15deg) translateX(10px);
}

.book-cover-image {
    width: 100%;
    height: 100%;
    object-fit: cover;
    border-radius: 4px 12px 12px 4px;
    box-shadow: 
        5px 5px 20px rgba(0, 0, 0, 0.3),
        0 0 3px rgba(0, 0, 0, 0.2);
    transition: all 0.4s ease;
}

.book-spine {
    position: absolute;
    left: 0;
    top: 0;
    width: 15px;
    height: 100%;
    background: linear-gradient(90deg, #1e293b 0%, #334155 50%, #475569 100%);
    transform: rotateY(90deg) translateX(-7.5px);
    transform-origin: left;
    border-radius: 2px 0 0 2px;
}

/* Sale Ribbon */
.sale-ribbon {
    position: absolute;
    top: 15px;
    left: -35px;
    background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
    color: white;
    padding: 8px 40px;
    font-size: 0.85rem;
    font-weight: 700;
    transform: rotate(-45deg);
    box-shadow: 0 4px 15px rgba(239, 68, 68, 0.4);
    z-index: 10;
}

.sale-ribbon::before,
.sale-ribbon::after {
    content: '';
    position: absolute;
    bottom: -6px;
    border: 6px solid transparent;
    border-top-color: #991b1b;
}

.sale-ribbon::before {
    left: 0;
    border-left-color: #991b1b;
}

.sale-ribbon::after {
    right: 0;
    border-right-color: #991b1b;
}

/* Out of Stock Badge */
.out-of-stock-badge {
    position: absolute;
    top: 15px;
    right: 15px;
    background: linear-gradient(135deg, #6b7280 0%, #4b5563 100%);
    color: white;
    padding: 6px 14px;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 600;
    z-index: 10;
}

/* Quick Actions */
.quick-actions {
    position: absolute;
    right: 15px;
    top: 50%;
    transform: translateY(-50%) translateX(60px);
    display: flex;
    flex-direction: column;
    gap: 10px;
    opacity: 0;
    transition: all 0.4s ease;
    z-index: 15;
}

.book-card:hover .quick-actions {
    transform: translateY(-50%) translateX(0);
    opacity: 1;
}

.action-btn {
    width: 44px;
    height: 44px;
    border-radius: 50%;
    border: none;
    background: white;
    color: #64748b;
    cursor: pointer;
    transition: all 0.3s ease;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.15);
    display: flex;
    align-items: center;
    justify-content: center;
    text-decoration: none;
}

.action-btn:hover {
    transform: scale(1.15);
}

.cart-btn:hover {
    background: linear-gradient(135deg, #2563eb, #3b82f6);
    color: white;
}

.wishlist-btn:hover {
    background: linear-gradient(135deg, #ef4444, #f87171);
    color: white;
}

.view-btn:hover {
    background: linear-gradient(135deg, #10b981, #34d399);
    color: white;
}

/* Book Details */
.book-details {
    padding: 1.25rem;
}

/* Category Tag */
.category-tag {
    margin-bottom: 0.75rem;
}

.category-tag a {
    display: inline-block;
    padding: 5px 14px;
    background: linear-gradient(135deg, #eff6ff, #dbeafe);
    color: #2563eb;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 600;
    text-decoration: none;
    transition: all 0.3s;
}

.category-tag a:hover {
    background: linear-gradient(135deg, #2563eb, #3b82f6);
    color: white;
    transform: translateY(-2px);
}

/* Book Title */
.book-title {
    font-size: 1.05rem;
    font-weight: 700;
    line-height: 1.4;
    margin-bottom: 0.5rem;
    height: 2.9em;
    overflow: hidden;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
}

.book-title a {
    color: #1e293b;
    text-decoration: none;
    transition: color 0.3s;
}

.book-title a:hover {
    color: #2563eb;
}

/* Author */
.book-author {
    display: flex;
    align-items: center;
    gap: 6px;
    margin-bottom: 0.75rem;
    font-size: 0.9rem;
    color: #64748b;
}

.book-author svg {
    flex-shrink: 0;
}

.book-author a {
    color: #64748b;
    text-decoration: none;
    transition: color 0.3s;
}

.book-author a:hover {
    color: #2563eb;
}

/* Rating */
.rating-wrapper {
    display: flex;
    align-items: center;
    gap: 8px;
    margin-bottom: 0.75rem;
}

.stars {
    display: flex;
    gap: 2px;
}

.star {
    transition: transform 0.2s;
}

.star:hover {
    transform: scale(1.2);
}

.rating-count {
    color: #94a3b8;
    font-size: 0.8rem;
}

/* Price Section */
.price-section {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 0.75rem;
}

.price-info {
    display: flex;
    flex-direction: column;
    gap: 2px;
}

.current-price {
    font-size: 1.35rem;
    font-weight: 800;
    background: linear-gradient(135deg, #dc2626, #ef4444);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

.original-price {
    font-size: 0.85rem;
    color: #94a3b8;
    text-decoration: line-through;
}

.view-count {
    display: flex;
    align-items: center;
    gap: 4px;
    color: #94a3b8;
    font-size: 0.8rem;
}

/* Stock Indicator */
.stock-indicator {
    display: flex;
    align-items: center;
    gap: 6px;
    padding: 8px 14px;
    border-radius: 10px;
    font-size: 0.8rem;
    font-weight: 600;
    margin-bottom: 1rem;
}

.stock-indicator.in-stock {
    background: linear-gradient(135deg, #d1fae5, #ecfdf5);
    color: #059669;
}

.stock-indicator.out-of-stock {
    background: linear-gradient(135deg, #fee2e2, #fef2f2);
    color: #dc2626;
}

/* Card Actions */
.card-actions {
    display: flex;
    gap: 10px;
}

.btn-cart, .btn-buy, .btn-notify {
    flex: 1;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    padding: 12px 10px;
    border: none;
    border-radius: 12px;
    font-size: 0.9rem;
    font-weight: 700;
    cursor: pointer;
    transition: all 0.3s ease;
    text-decoration: none;
}

.btn-cart {
    background: linear-gradient(135deg, #2563eb, #3b82f6);
    color: white;
}

.btn-cart:hover {
    background: linear-gradient(135deg, #1d4ed8, #2563eb);
    transform: translateY(-3px);
    box-shadow: 0 8px 25px rgba(37, 99, 235, 0.4);
}

.btn-buy {
    background: linear-gradient(135deg, #f59e0b, #fbbf24);
    color: white;
}

.btn-buy:hover {
    background: linear-gradient(135deg, #d97706, #f59e0b);
    transform: translateY(-3px);
    box-shadow: 0 8px 25px rgba(245, 158, 11, 0.4);
}

.btn-notify {
    background: linear-gradient(135deg, #6b7280, #9ca3af);
    color: white;
    width: 100%;
}

.btn-notify:hover {
    background: linear-gradient(135deg, #4b5563, #6b7280);
    transform: translateY(-3px);
}

/* Responsive */
@media (max-width: 768px) {
    .book-cover-wrapper {
        height: 250px;
        padding: 15px;
    }
    
    .book-cover-3d {
        width: 150px;
        height: 220px;
    }
    
    .card-actions {
        flex-direction: column;
    }
    
    .btn-cart, .btn-buy {
        width: 100%;
    }
    
    .quick-actions {
        transform: translateY(-50%) translateX(0);
        opacity: 1;
        right: 10px;
    }
    
    .action-btn {
        width: 36px;
        height: 36px;
    }
}
</style>
