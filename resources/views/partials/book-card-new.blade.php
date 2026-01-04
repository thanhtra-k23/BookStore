<div class="book-card-modern">
    <div class="book-card-image">
        @if($book->isOnSale())
            <span class="book-badge">-{{ $book->phan_tram_giam_gia }}%</span>
        @endif
        <a href="{{ route('book.detail', [$book->ma_sach, $book->duong_dan]) }}">
            <img src="{{ $book->anh_bia_url }}" 
                 alt="{{ $book->ten_sach }}"
                 onerror="this.onerror=null; this.src='https://placehold.co/200x280/e2e8f0/64748b?text={{ urlencode(Str::limit($book->ten_sach, 15)) }}';">
        </a>
    </div>
    
    <div class="book-card-body">
        <span class="book-category">{{ $book->theLoai->ten_the_loai ?? 'Chưa phân loại' }}</span>
        
        <h3 class="book-card-title">
            <a href="{{ route('book.detail', [$book->ma_sach, $book->duong_dan]) }}">
                {{ $book->ten_sach }}
            </a>
        </h3>
        
        <p class="book-author">
            ✍️ {{ $book->tacGia->ten_tac_gia ?? 'Chưa xác định' }}
        </p>
        
        <div class="book-rating">
            <span class="stars">
                @for($i = 1; $i <= 5; $i++)
                    {{ $i <= ($book->diem_trung_binh ?? 0) ? '★' : '☆' }}
                @endfor
            </span>
            <span>({{ $book->so_luot_danh_gia ?? 0 }})</span>
        </div>
        
        <div class="book-price-row">
            @if($book->isOnSale())
                <span class="book-price-current">{{ number_format($book->gia_khuyen_mai, 0, ',', '.') }}đ</span>
                <span class="book-price-old">{{ number_format($book->gia_ban, 0, ',', '.') }}đ</span>
            @else
                <span class="book-price-current">{{ number_format($book->gia_ban, 0, ',', '.') }}đ</span>
            @endif
        </div>
        
        <div class="book-actions">
            @if($book->canOrder())
                <button class="btn-cart btn-cart-primary" onclick="addToCart({{ $book->ma_sach }})">
                    🛒 Thêm giỏ
                </button>
                <a href="{{ route('book.detail', [$book->ma_sach, $book->duong_dan]) }}" class="btn-cart btn-cart-secondary">
                    👁️ Xem
                </a>
            @else
                <button class="btn-cart btn-out-of-stock" disabled>
                    Hết hàng
                </button>
            @endif
        </div>
    </div>
</div>
