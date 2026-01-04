@extends('layouts.pure-blade')

@section('title', $book->ten_sach . ' - BookStore')

@push('styles')
<style>
    .book-detail-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 2rem 1rem;
    }

    .breadcrumb-nav {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 2rem;
        font-size: 0.9rem;
        flex-wrap: wrap;
    }

    .breadcrumb-nav a {
        color: #64748b;
        text-decoration: none;
        transition: color 0.3s;
    }

    .breadcrumb-nav a:hover {
        color: #2563eb;
    }

    .breadcrumb-nav .separator {
        color: #cbd5e1;
    }

    .breadcrumb-nav .current {
        color: #1e293b;
        font-weight: 600;
    }

    .book-detail-grid {
        display: grid;
        grid-template-columns: 1fr 1.5fr;
        gap: 3rem;
        margin-bottom: 3rem;
    }

    /* Image Section */
    .book-image-section {
        position: sticky;
        top: 100px;
    }

    .main-image-container {
        background: linear-gradient(145deg, #ffffff, #f1f5f9);
        border-radius: 24px;
        padding: 2rem;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
        position: relative;
        overflow: hidden;
    }

    .main-image-container::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, #2563eb, #7c3aed, #ec4899);
    }

    .book-main-image {
        width: 100%;
        max-height: 500px;
        object-fit: contain;
        border-radius: 16px;
        transition: transform 0.5s ease;
    }

    .book-main-image:hover {
        transform: scale(1.05);
    }

    .image-badges {
        position: absolute;
        top: 2.5rem;
        left: 2.5rem;
        display: flex;
        flex-direction: column;
        gap: 8px;
    }

    .badge-discount {
        background: linear-gradient(135deg, #ef4444, #dc2626);
        color: white;
        padding: 8px 16px;
        border-radius: 25px;
        font-weight: 700;
        font-size: 0.9rem;
        box-shadow: 0 4px 15px rgba(239, 68, 68, 0.4);
    }

    .badge-new {
        background: linear-gradient(135deg, #10b981, #059669);
        color: white;
        padding: 8px 16px;
        border-radius: 25px;
        font-weight: 700;
        font-size: 0.85rem;
    }

    /* Info Section */
    .book-info-section {
        padding: 1rem 0;
    }

    .book-category-link {
        display: inline-block;
        padding: 6px 16px;
        background: linear-gradient(135deg, #eff6ff, #dbeafe);
        color: #2563eb;
        border-radius: 25px;
        font-size: 0.85rem;
        font-weight: 600;
        text-decoration: none;
        margin-bottom: 1rem;
        transition: all 0.3s;
    }

    .book-category-link:hover {
        background: linear-gradient(135deg, #2563eb, #3b82f6);
        color: white;
        transform: translateY(-2px);
    }

    .book-title-main {
        font-size: 2rem;
        font-weight: 800;
        color: #1e293b;
        line-height: 1.3;
        margin-bottom: 1rem;
    }

    .book-author-link {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 1.5rem;
        font-size: 1.1rem;
    }

    .book-author-link a {
        color: #64748b;
        text-decoration: none;
        transition: color 0.3s;
    }

    .book-author-link a:hover {
        color: #2563eb;
    }
</style>
@endpush

@section('content')
<div class="book-detail-container">
    <!-- Breadcrumb -->
    <nav class="breadcrumb-nav">
        <a href="{{ route('home') }}">🏠 Trang chủ</a>
        <span class="separator">›</span>
        <a href="{{ route('categories') }}">Thể loại</a>
        @if($book->theLoai)
            <span class="separator">›</span>
            <a href="{{ route('category', $book->theLoai->duong_dan) }}">{{ $book->theLoai->ten_the_loai }}</a>
        @endif
        <span class="separator">›</span>
        <span class="current">{{ Str::limit($book->ten_sach, 40) }}</span>
    </nav>

    <div class="book-detail-grid">
        <!-- Image Section -->
        <div class="book-image-section">
            <div class="main-image-container">
                <div class="image-badges">
                    @if($book->gia_khuyen_mai && $book->gia_khuyen_mai < $book->gia_ban)
                        <span class="badge-discount">-{{ round((($book->gia_ban - $book->gia_khuyen_mai) / $book->gia_ban) * 100) }}%</span>
                    @endif
                </div>
                <img src="{{ $book->anh_bia_url }}" 
                     alt="{{ $book->ten_sach }}" 
                     class="book-main-image"
                     onerror="this.src='https://via.placeholder.com/400x500?text=No+Image'">
            </div>
        </div>

        <!-- Info Section -->
        <div class="book-info-section">
            @if($book->theLoai)
                <a href="{{ route('category', $book->theLoai->duong_dan) }}" class="book-category-link">
                    📂 {{ $book->theLoai->ten_the_loai }}
                </a>
            @endif

            <h1 class="book-title-main">{{ $book->ten_sach }}</h1>

            <div class="book-author-link">
                <span>✍️</span>
                @if($book->tacGia)
                    <a href="{{ route('author', $book->tacGia->duong_dan) }}">{{ $book->tacGia->ten_tac_gia }}</a>
                @else
                    <span>Chưa cập nhật</span>
                @endif
            </div>

            <!-- Rating -->
            <div class="book-rating-section">
                <div class="rating-stars">
                    @php $rating = $book->diem_danh_gia ?? 0; @endphp
                    @for($i = 1; $i <= 5; $i++)
                        <span class="star {{ $i <= $rating ? 'filled' : '' }}">★</span>
                    @endfor
                </div>
                <span class="rating-text">{{ number_format($rating, 1) }}/5</span>
                <span class="rating-count">({{ $book->so_luong_danh_gia ?? 0 }} đánh giá)</span>
                <span class="view-count">👁️ {{ number_format($book->luot_xem ?? 0) }} lượt xem</span>
            </div>

            <!-- Price Section -->
            <div class="price-section">
                @if($book->gia_khuyen_mai && $book->gia_khuyen_mai < $book->gia_ban)
                    <div class="price-current">{{ number_format($book->gia_khuyen_mai, 0, ',', '.') }}đ</div>
                    <div class="price-original">{{ number_format($book->gia_ban, 0, ',', '.') }}đ</div>
                    <div class="price-save">Tiết kiệm {{ number_format($book->gia_ban - $book->gia_khuyen_mai, 0, ',', '.') }}đ</div>
                @else
                    <div class="price-current">{{ number_format($book->gia_ban, 0, ',', '.') }}đ</div>
                @endif
            </div>

            <!-- Stock Status -->
            <div class="stock-section">
                @if(($book->so_luong_ton ?? 0) > 0)
                    <div class="stock-available">
                        <span class="stock-icon">✓</span>
                        <span>Còn hàng ({{ $book->so_luong_ton }} sản phẩm)</span>
                    </div>
                @else
                    <div class="stock-unavailable">
                        <span class="stock-icon">✗</span>
                        <span>Hết hàng</span>
                    </div>
                @endif
            </div>

            <!-- Quantity Selector -->
            <div class="quantity-section">
                <label>Số lượng:</label>
                <div class="quantity-controls">
                    <button class="qty-btn" onclick="decreaseQty()">−</button>
                    <input type="number" id="quantity" value="1" min="1" max="{{ $book->so_luong_ton ?? 1 }}" class="qty-input">
                    <button class="qty-btn" onclick="increaseQty()">+</button>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="action-buttons">
                @if(($book->so_luong_ton ?? 0) > 0)
                    <button class="btn-add-to-cart" onclick="addToCart({{ $book->ma_sach }})">
                        <span class="btn-icon">🛒</span>
                        <span>Thêm vào giỏ hàng</span>
                    </button>
                    <button class="btn-buy-now" onclick="buyNow({{ $book->ma_sach }})">
                        <span class="btn-icon">⚡</span>
                        <span>Mua ngay</span>
                    </button>
                @else
                    <button class="btn-notify-stock" onclick="notifyStock({{ $book->ma_sach }})">
                        <span class="btn-icon">🔔</span>
                        <span>Thông báo khi có hàng</span>
                    </button>
                @endif
                <button class="btn-wishlist" onclick="toggleWishlist({{ $book->ma_sach }})">
                    <span class="btn-icon">❤️</span>
                </button>
            </div>

            <!-- Book Details Table -->
            <div class="book-details-table">
                <h3>📋 Thông tin chi tiết</h3>
                <table>
                    <tr>
                        <td>Tác giả</td>
                        <td>{{ $book->tacGia->ten_tac_gia ?? 'Chưa cập nhật' }}</td>
                    </tr>
                    <tr>
                        <td>Thể loại</td>
                        <td>{{ $book->theLoai->ten_the_loai ?? 'Chưa phân loại' }}</td>
                    </tr>
                    <tr>
                        <td>Nhà xuất bản</td>
                        <td>{{ $book->nhaXuatBan->ten_nxb ?? 'Chưa cập nhật' }}</td>
                    </tr>
                    <tr>
                        <td>Năm xuất bản</td>
                        <td>{{ $book->nam_xuat_ban ?? 'Chưa cập nhật' }}</td>
                    </tr>
                    <tr>
                        <td>Số trang</td>
                        <td>{{ $book->so_trang ?? 'Chưa cập nhật' }}</td>
                    </tr>
                    <tr>
                        <td>Mã sách</td>
                        <td>{{ $book->ma_sach }}</td>
                    </tr>
                </table>
            </div>

            <!-- Share Section -->
            <div class="share-section">
                <span>Chia sẻ:</span>
                <div class="share-buttons">
                    <button class="share-btn facebook" onclick="shareOnFacebook()">📘</button>
                    <button class="share-btn twitter" onclick="shareOnTwitter()">🐦</button>
                    <button class="share-btn copy" onclick="copyLink()">🔗</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Description Section -->
    <div class="description-section">
        <h2>📖 Mô tả sách</h2>
        <div class="description-content">
            {!! nl2br(e($book->mo_ta ?? 'Chưa có mô tả cho sách này.')) !!}
        </div>
    </div>

    <!-- Related Books -->
    @if(isset($relatedBooks) && $relatedBooks->count() > 0)
    <div class="related-section">
        <h2>📚 Sách liên quan</h2>
        <div class="book-grid">
            @foreach($relatedBooks as $relatedBook)
                @include('partials.book-card-pure', ['book' => $relatedBook])
            @endforeach
        </div>
    </div>
    @endif
</div>

<!-- Toast Notification -->
<div id="toast" class="toast-notification"></div>
@endsection

@push('styles')
<style>
    .book-rating-section {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 1.5rem;
        flex-wrap: wrap;
    }

    .rating-stars {
        display: flex;
        gap: 2px;
    }

    .rating-stars .star {
        font-size: 1.3rem;
        color: #e2e8f0;
    }

    .rating-stars .star.filled {
        color: #f59e0b;
    }

    .rating-text {
        font-weight: 700;
        color: #1e293b;
    }

    .rating-count, .view-count {
        color: #64748b;
        font-size: 0.9rem;
    }

    .price-section {
        background: linear-gradient(135deg, #fef3c7, #fef9c3);
        padding: 1.5rem;
        border-radius: 16px;
        margin-bottom: 1.5rem;
        border: 2px solid #fcd34d;
    }

    .price-current {
        font-size: 2.5rem;
        font-weight: 800;
        background: linear-gradient(135deg, #dc2626, #ef4444);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    .price-original {
        font-size: 1.2rem;
        color: #94a3b8;
        text-decoration: line-through;
        margin-top: 4px;
    }

    .price-save {
        display: inline-block;
        margin-top: 8px;
        padding: 6px 14px;
        background: linear-gradient(135deg, #10b981, #059669);
        color: white;
        border-radius: 20px;
        font-size: 0.85rem;
        font-weight: 600;
    }

    .stock-section {
        margin-bottom: 1.5rem;
    }

    .stock-available, .stock-unavailable {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 10px 20px;
        border-radius: 25px;
        font-weight: 600;
    }

    .stock-available {
        background: linear-gradient(135deg, #d1fae5, #ecfdf5);
        color: #059669;
    }

    .stock-unavailable {
        background: linear-gradient(135deg, #fee2e2, #fef2f2);
        color: #dc2626;
    }

    .quantity-section {
        display: flex;
        align-items: center;
        gap: 15px;
        margin-bottom: 1.5rem;
    }

    .quantity-section label {
        font-weight: 600;
        color: #374151;
    }

    .quantity-controls {
        display: flex;
        align-items: center;
        border: 2px solid #e2e8f0;
        border-radius: 12px;
        overflow: hidden;
    }

    .qty-btn {
        width: 45px;
        height: 45px;
        border: none;
        background: #f8fafc;
        font-size: 1.3rem;
        cursor: pointer;
        transition: all 0.3s;
    }

    .qty-btn:hover {
        background: #2563eb;
        color: white;
    }

    .qty-input {
        width: 60px;
        height: 45px;
        border: none;
        text-align: center;
        font-size: 1.1rem;
        font-weight: 600;
    }

    .qty-input:focus {
        outline: none;
    }

    .action-buttons {
        display: flex;
        gap: 12px;
        margin-bottom: 2rem;
        flex-wrap: wrap;
    }

    .btn-add-to-cart, .btn-buy-now, .btn-notify-stock {
        flex: 1;
        min-width: 180px;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        padding: 16px 24px;
        border: none;
        border-radius: 14px;
        font-size: 1.05rem;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .btn-add-to-cart {
        background: linear-gradient(135deg, #2563eb, #3b82f6);
        color: white;
    }

    .btn-add-to-cart:hover {
        background: linear-gradient(135deg, #1d4ed8, #2563eb);
        transform: translateY(-3px);
        box-shadow: 0 10px 30px rgba(37, 99, 235, 0.4);
    }

    .btn-buy-now {
        background: linear-gradient(135deg, #f59e0b, #fbbf24);
        color: white;
    }

    .btn-buy-now:hover {
        background: linear-gradient(135deg, #d97706, #f59e0b);
        transform: translateY(-3px);
        box-shadow: 0 10px 30px rgba(245, 158, 11, 0.4);
    }

    .btn-notify-stock {
        background: linear-gradient(135deg, #6b7280, #9ca3af);
        color: white;
    }

    .btn-wishlist {
        width: 56px;
        height: 56px;
        border: 2px solid #fca5a5;
        background: white;
        border-radius: 14px;
        font-size: 1.5rem;
        cursor: pointer;
        transition: all 0.3s;
    }

    .btn-wishlist:hover {
        background: linear-gradient(135deg, #ef4444, #f87171);
        border-color: transparent;
        transform: scale(1.1);
    }

    .book-details-table {
        background: linear-gradient(145deg, #ffffff, #f8fafc);
        border-radius: 16px;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
        border: 1px solid #e2e8f0;
    }

    .book-details-table h3 {
        margin-bottom: 1rem;
        color: #1e293b;
        font-size: 1.1rem;
    }

    .book-details-table table {
        width: 100%;
    }

    .book-details-table td {
        padding: 10px 0;
        border-bottom: 1px solid #f1f5f9;
    }

    .book-details-table td:first-child {
        color: #64748b;
        width: 40%;
    }

    .book-details-table td:last-child {
        color: #1e293b;
        font-weight: 500;
    }

    .share-section {
        display: flex;
        align-items: center;
        gap: 15px;
    }

    .share-section span {
        color: #64748b;
        font-weight: 500;
    }

    .share-buttons {
        display: flex;
        gap: 10px;
    }

    .share-btn {
        width: 42px;
        height: 42px;
        border: none;
        border-radius: 50%;
        font-size: 1.2rem;
        cursor: pointer;
        transition: all 0.3s;
    }

    .share-btn.facebook { background: #1877f2; color: white; }
    .share-btn.twitter { background: #1da1f2; color: white; }
    .share-btn.copy { background: #64748b; color: white; }

    .share-btn:hover {
        transform: scale(1.15);
    }

    .description-section {
        background: white;
        border-radius: 20px;
        padding: 2rem;
        margin-bottom: 3rem;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    }

    .description-section h2 {
        color: #1e293b;
        margin-bottom: 1.5rem;
        font-size: 1.5rem;
    }

    .description-content {
        color: #475569;
        line-height: 1.8;
    }

    .related-section {
        margin-top: 3rem;
    }

    .related-section h2 {
        color: #1e293b;
        margin-bottom: 1.5rem;
        font-size: 1.5rem;
    }

    .toast-notification {
        position: fixed;
        bottom: 30px;
        right: 30px;
        padding: 16px 24px;
        border-radius: 12px;
        color: white;
        font-weight: 600;
        z-index: 9999;
        transform: translateX(150%);
        transition: transform 0.4s ease;
    }

    .toast-notification.show {
        transform: translateX(0);
    }

    .toast-notification.success {
        background: linear-gradient(135deg, #10b981, #059669);
    }

    .toast-notification.error {
        background: linear-gradient(135deg, #ef4444, #dc2626);
    }

    @media (max-width: 768px) {
        .book-detail-grid {
            grid-template-columns: 1fr;
            gap: 2rem;
        }

        .book-image-section {
            position: static;
        }

        .book-title-main {
            font-size: 1.5rem;
        }

        .price-current {
            font-size: 2rem;
        }

        .action-buttons {
            flex-direction: column;
        }

        .btn-add-to-cart, .btn-buy-now {
            width: 100%;
        }
    }
</style>
@endpush

@push('scripts')
<script>
    // Quantity controls
    function decreaseQty() {
        const input = document.getElementById('quantity');
        if (parseInt(input.value) > 1) {
            input.value = parseInt(input.value) - 1;
        }
    }

    function increaseQty() {
        const input = document.getElementById('quantity');
        const max = parseInt(input.max);
        if (parseInt(input.value) < max) {
            input.value = parseInt(input.value) + 1;
        }
    }

    // Toast notification
    function showToast(message, type = 'success') {
        const toast = document.getElementById('toast');
        toast.textContent = message;
        toast.className = 'toast-notification ' + type + ' show';
        setTimeout(() => {
            toast.classList.remove('show');
        }, 3000);
    }

    // Add to cart
    function addToCart(bookId) {
        const quantity = document.getElementById('quantity').value;
        
        fetch('{{ route("cart.add") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                ma_sach: bookId,
                so_luong: parseInt(quantity)
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showToast('✓ Đã thêm vào giỏ hàng!', 'success');
                updateCartCount();
            } else {
                showToast(data.message || 'Có lỗi xảy ra!', 'error');
            }
        })
        .catch(error => {
            showToast('Có lỗi xảy ra!', 'error');
        });
    }

    // Quick add to cart (from book cards)
    function addToCartQuick(bookId) {
        fetch('{{ route("cart.add") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                ma_sach: bookId,
                so_luong: 1
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showToast('✓ Đã thêm vào giỏ hàng!', 'success');
                updateCartCount();
            } else {
                showToast(data.message || 'Có lỗi xảy ra!', 'error');
            }
        })
        .catch(error => {
            showToast('Có lỗi xảy ra!', 'error');
        });
    }

    // Buy now
    function buyNow(bookId) {
        const quantity = document.getElementById('quantity').value;
        
        fetch('{{ route("cart.add") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                ma_sach: bookId,
                so_luong: parseInt(quantity)
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                window.location.href = '{{ route("checkout") }}';
            } else {
                showToast(data.message || 'Có lỗi xảy ra!', 'error');
            }
        })
        .catch(error => {
            showToast('Có lỗi xảy ra!', 'error');
        });
    }

    // Toggle wishlist
    function toggleWishlist(bookId) {
        fetch('{{ route("wishlist.toggle") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                ma_sach: bookId
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showToast(data.added ? '❤️ Đã thêm vào yêu thích!' : '💔 Đã xóa khỏi yêu thích!', 'success');
            } else {
                showToast(data.message || 'Có lỗi xảy ra!', 'error');
            }
        })
        .catch(error => {
            showToast('Có lỗi xảy ra!', 'error');
        });
    }

    // Notify when in stock
    function notifyStock(bookId) {
        showToast('🔔 Bạn sẽ được thông báo khi sách có hàng!', 'success');
    }

    function notifyWhenAvailable(bookId) {
        showToast('🔔 Bạn sẽ được thông báo khi sách có hàng!', 'success');
    }

    // Update cart count in header
    function updateCartCount() {
        fetch('/api/cart/count')
            .then(response => response.json())
            .then(data => {
                const cartBadge = document.querySelector('.cart-count');
                if (cartBadge) {
                    cartBadge.textContent = data.count || 0;
                }
            })
            .catch(() => {});
    }

    // Share functions
    function shareOnFacebook() {
        window.open('https://www.facebook.com/sharer/sharer.php?u=' + encodeURIComponent(window.location.href), '_blank');
    }

    function shareOnTwitter() {
        window.open('https://twitter.com/intent/tweet?url=' + encodeURIComponent(window.location.href) + '&text=' + encodeURIComponent('{{ $book->ten_sach }}'), '_blank');
    }

    function copyLink() {
        navigator.clipboard.writeText(window.location.href).then(() => {
            showToast('🔗 Đã sao chép liên kết!', 'success');
        });
    }
</script>
@endpush
