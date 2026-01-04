@push('styles')
<style>
    .book-card-modern {
        background: white; border-radius: 12px; overflow: hidden;
        box-shadow: 0 2px 10px rgba(0,0,0,0.06); transition: all 0.3s ease; border: 1px solid #f1f5f9;
    }
    .book-card-modern:hover { transform: translateY(-5px) scale(1.02); box-shadow: 0 15px 35px rgba(0,0,0,0.12); }
    .book-card-modern .book-image-wrapper {
        position: relative; height: 220px;
        background: linear-gradient(135deg, #f8fafc, #e2e8f0);
        display: flex; align-items: center; justify-content: center; overflow: hidden;
    }
    .book-card-modern .book-image-wrapper img {
        max-width: 70%; max-height: 90%; object-fit: contain;
        border-radius: 4px; box-shadow: 0 3px 15px rgba(0,0,0,0.15); transition: transform 0.3s;
    }
    .book-card-modern:hover .book-image-wrapper img { transform: scale(1.05); }
    .book-card-modern .discount-badge {
        position: absolute; top: 8px; left: 8px;
        background: linear-gradient(135deg, #ef4444, #dc2626);
        color: white; padding: 4px 10px; border-radius: 15px;
        font-size: 0.7rem; font-weight: 700; z-index: 5;
    }
    .book-card-modern .wishlist-btn {
        position: absolute; top: 8px; right: 8px; width: 32px; height: 32px;
        background: rgba(255, 255, 255, 0.9); border: none; border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        cursor: pointer; transition: all 0.3s; z-index: 5; font-size: 1rem;
    }
    .book-card-modern .wishlist-btn:hover { background: #fee2e2; transform: scale(1.1); }
    .book-card-modern .book-body { padding: 0.875rem; }
    .book-card-modern .book-title {
        font-size: 0.9rem; font-weight: 600; color: #1e293b; margin-bottom: 0.35rem;
        line-height: 1.35; display: -webkit-box; -webkit-line-clamp: 2;
        -webkit-box-orient: vertical; overflow: hidden; min-height: 2.4em;
    }
    .book-card-modern .book-title a { color: inherit; text-decoration: none; }
    .book-card-modern .book-title a:hover { color: #8b5cf6; }
    .book-card-modern .book-rating { display: flex; align-items: center; gap: 4px; margin-bottom: 0.5rem; font-size: 0.75rem; }
    .book-card-modern .book-rating .stars { color: #f59e0b; }
    .book-card-modern .book-rating .count { color: #94a3b8; }
    .book-card-modern .book-price-row { display: flex; align-items: center; justify-content: space-between; gap: 8px; }
    .book-card-modern .price-current { font-size: 1.1rem; font-weight: 700; color: #dc2626; }
    .book-card-modern .price-old { font-size: 0.75rem; color: #94a3b8; text-decoration: line-through; }
    .book-card-modern .btn-add-cart {
        width: 36px; height: 36px;
        background: linear-gradient(135deg, #8b5cf6, #6366f1);
        border: none; border-radius: 50%; color: white;
        display: flex; align-items: center; justify-content: center;
        cursor: pointer; transition: all 0.3s; font-size: 1rem;
    }
    .book-card-modern .btn-add-cart:hover { background: linear-gradient(135deg, #7c3aed, #4f46e5); transform: scale(1.1); }
    .empty-state { text-align: center; padding: 4rem 2rem; }
    .empty-state .icon { font-size: 5rem; margin-bottom: 1rem; }
    .pagination-wrapper { margin-top: 2rem; display: flex; justify-content: center; }
</style>
@endpush

<div class="container" style="padding-top: 1.5rem;">
    <div class="filter-bar">
        <p class="result-count">Tìm thấy <span>{{ $books->total() }}</span> cuốn sách</p>
        <form method="GET" action="{{ route('author', $author->duong_dan) }}">
            <select name="sort" class="sort-select" onchange="this.form.submit()">
                <option value="newest" {{ request('sort', 'newest') == 'newest' ? 'selected' : '' }}>Mới nhất</option>
                <option value="popular" {{ request('sort') == 'popular' ? 'selected' : '' }}>Phổ biến</option>
                <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Giá thấp → cao</option>
                <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Giá cao → thấp</option>
            </select>
        </form>
    </div>

    @if($books->count() > 0)
        <div class="books-grid-modern">
            @foreach($books as $book)
                <div class="book-card-modern">
                    <div class="book-image-wrapper">
                        @php $discount = ($book->gia_khuyen_mai && $book->gia_ban > 0) ? round((($book->gia_ban - $book->gia_khuyen_mai) / $book->gia_ban) * 100) : 0; @endphp
                        @if($discount > 0)<div class="discount-badge">-{{ $discount }}%</div>@endif
                        <button class="wishlist-btn" onclick="toggleWishlist({{ $book->ma_sach }})" title="Yêu thích">❤️</button>
                        <a href="{{ route('book.detail', ['id' => $book->ma_sach, 'slug' => $book->slug ?? Str::slug($book->ten_sach)]) }}">
                            <img src="{{ $book->anh_bia_url ?? '/images/no-image.png' }}" alt="{{ $book->ten_sach }}" onerror="this.src='/images/no-image.png'">
                        </a>
                    </div>
                    <div class="book-body">
                        <h3 class="book-title">
                            <a href="{{ route('book.detail', ['id' => $book->ma_sach, 'slug' => $book->slug ?? Str::slug($book->ten_sach)]) }}">{{ $book->ten_sach }}</a>
                        </h3>
                        <div class="book-rating"><span class="stars">★★★★☆</span><span class="count">({{ rand(10, 200) }})</span></div>
                        <div class="book-price-row">
                            <div>
                                @if($book->gia_khuyen_mai && $book->gia_khuyen_mai < $book->gia_ban)
                                    <div class="price-current">{{ number_format($book->gia_khuyen_mai) }}₫</div>
                                    <div class="price-old">{{ number_format($book->gia_ban) }}₫</div>
                                @else
                                    <div class="price-current">{{ number_format($book->gia_ban) }}₫</div>
                                @endif
                            </div>
                            <button class="btn-add-cart" onclick="addToCartQuick({{ $book->ma_sach }})" title="Thêm vào giỏ">🛒</button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="pagination-wrapper">{{ $books->appends(request()->query())->links() }}</div>
    @else
        <div class="empty-state">
            <div class="icon">📚</div>
            <h3>Chưa có sách nào</h3>
            <p style="color: #94a3b8;">Tác giả "{{ $author->ten_tac_gia }}" hiện chưa có sách trong hệ thống.</p>
            <div style="display: flex; gap: 0.5rem; justify-content: center;">
                <a href="{{ route('authors') }}" class="btn btn-primary">👥 Xem tác giả khác</a>
                <a href="{{ route('home') }}" class="btn btn-secondary">🏠 Về trang chủ</a>
            </div>
        </div>
    @endif
</div>
