<div class="card card-modern h-100 book-card">
    <div class="position-relative">
        <img src="{{ $book->anh_bia_url }}" 
             class="card-img-top" 
             alt="{{ $book->ten_sach }}"
             style="height: 250px; object-fit: cover;">
        
        @if($book->isOnSale())
            <span class="position-absolute top-0 start-0 badge bg-danger m-2">
                -{{ $book->phan_tram_giam_gia }}%
            </span>
        @endif

        @if(!$book->isInStock())
            <span class="position-absolute top-0 end-0 badge bg-secondary m-2">
                Hết hàng
            </span>
        @endif

        <!-- Wishlist Button -->
        @auth
            <button class="btn btn-sm btn-light position-absolute top-0 end-0 m-2 wishlist-btn" 
                    data-book-id="{{ $book->ma_sach }}"
                    style="border-radius: 50%; width: 35px; height: 35px;">
                <i class="fas fa-heart"></i>
            </button>
        @endauth
    </div>

    <div class="card-body d-flex flex-column">
        <h6 class="card-title mb-2">
            <a href="{{ route('book.detail', [$book->ma_sach, $book->duong_dan]) }}" 
               class="text-decoration-none text-dark">
                {{ Str::limit($book->ten_sach, 50) }}
            </a>
        </h6>

        <p class="text-muted small mb-2">
            <i class="fas fa-user me-1"></i>
            <a href="{{ route('author', $book->tacGia->duong_dan) }}" 
               class="text-decoration-none text-muted">
                {{ $book->tacGia->ten_tac_gia }}
            </a>
        </p>

        <p class="text-muted small mb-2">
            <i class="fas fa-tag me-1"></i>
            <a href="{{ route('category', $book->theLoai->duong_dan) }}" 
               class="text-decoration-none text-muted">
                {{ $book->theLoai->ten_the_loai }}
            </a>
        </p>

        @if($book->diem_danh_gia)
            <div class="mb-2">
                <div class="d-flex align-items-center">
                    <div class="text-warning me-2">
                        @for($i = 1; $i <= 5; $i++)
                            @if($i <= $book->diem_danh_gia)
                                <i class="fas fa-star"></i>
                            @else
                                <i class="far fa-star"></i>
                            @endif
                        @endfor
                    </div>
                    <small class="text-muted">({{ $book->so_luong_danh_gia }})</small>
                </div>
            </div>
        @endif

        <div class="mt-auto">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <div>
                    @if($book->isOnSale())
                        <span class="text-decoration-line-through text-muted small">
                            {{ number_format($book->gia_ban, 0, ',', '.') }}đ
                        </span>
                        <br>
                        <span class="fw-bold text-danger">
                            {{ number_format($book->gia_khuyen_mai, 0, ',', '.') }}đ
                        </span>
                    @else
                        <span class="fw-bold text-primary">
                            {{ number_format($book->gia_ban, 0, ',', '.') }}đ
                        </span>
                    @endif
                </div>
                <small class="text-muted">
                    <i class="fas fa-eye me-1"></i>
                    {{ number_format($book->luot_xem) }}
                </small>
            </div>

            <div class="d-grid gap-2">
                @if($book->canOrder())
                    @auth
                        <button class="btn btn-primary btn-sm add-to-cart-btn" 
                                data-book-id="{{ $book->ma_sach }}">
                            <i class="fas fa-cart-plus me-1"></i>
                            Thêm vào giỏ
                        </button>
                    @else
                        <a href="{{ route('login') }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-sign-in-alt me-1"></i>
                            Đăng nhập để mua
                        </a>
                    @endauth
                @else
                    <button class="btn btn-secondary btn-sm" disabled>
                        <i class="fas fa-times me-1"></i>
                        Không có sẵn
                    </button>
                @endif

                <a href="{{ route('book.detail', [$book->ma_sach, $book->duong_dan]) }}" 
                   class="btn btn-outline-primary btn-sm">
                    <i class="fas fa-eye me-1"></i>
                    Xem chi tiết
                </a>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Add to cart functionality
    $(document).on('click', '.add-to-cart-btn', function() {
        const bookId = $(this).data('book-id');
        const button = $(this);
        
        button.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-1"></i>Đang thêm...');
        
        $.ajax({
            url: '/cart/add',
            method: 'POST',
            data: {
                sach_id: bookId,
                so_luong: 1
            },
            success: function(response) {
                if (response.success) {
                    showToast(response.message, 'success');
                    updateCounts();
                } else {
                    showToast(response.message, 'danger');
                }
            },
            error: function(xhr) {
                const response = xhr.responseJSON;
                showToast(response.message || 'Có lỗi xảy ra', 'danger');
            },
            complete: function() {
                button.prop('disabled', false).html('<i class="fas fa-cart-plus me-1"></i>Thêm vào giỏ');
            }
        });
    });

    // Wishlist functionality
    $(document).on('click', '.wishlist-btn', function() {
        const bookId = $(this).data('book-id');
        const button = $(this);
        const icon = button.find('i');
        
        $.ajax({
            url: '/wishlist/toggle',
            method: 'POST',
            data: {
                sach_id: bookId
            },
            success: function(response) {
                if (response.success) {
                    if (response.is_favorite) {
                        icon.removeClass('far').addClass('fas').addClass('text-danger');
                    } else {
                        icon.removeClass('fas text-danger').addClass('far');
                    }
                    showToast(response.message, 'success');
                    updateCounts();
                }
            },
            error: function(xhr) {
                const response = xhr.responseJSON;
                showToast(response.message || 'Có lỗi xảy ra', 'danger');
            }
        });
    });
</script>
@endpush