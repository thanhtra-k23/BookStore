@extends('layouts.pure-blade')

@section('title', $book->ten_sach . ' - BookStore')

@section('content')
<div class="container py-4">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Trang chủ</a></li>
            <li class="breadcrumb-item"><a href="{{ route('categories') }}">Thể loại</a></li>
            @if($book->theLoai)
                <li class="breadcrumb-item">
                    <a href="{{ route('category', $book->theLoai->duong_dan) }}">
                        {{ $book->theLoai->ten_the_loai }}
                    </a>
                </li>
            @endif
            <li class="breadcrumb-item active">{{ $book->ten_sach }}</li>
        </ol>
    </nav>

    <div class="row">
        <!-- Book Image -->
        <div class="col-lg-5 mb-4">
            <div class="card card-modern">
                <div class="card-body text-center">
                    @if($book->anh_bia)
                        <img src="{{ $book->anh_bia_url }}" 
                             alt="{{ $book->ten_sach }}" 
                             class="img-fluid rounded shadow-lg"
                             style="max-height: 500px; object-fit: cover;">
                    @else
                        <div class="bg-light rounded d-flex align-items-center justify-content-center" 
                             style="height: 500px;">
                            <i class="fas fa-book text-muted" style="font-size: 5rem;"></i>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="mt-3 d-grid gap-2">
                <button class="btn btn-primary btn-lg" onclick="addToCart({{ $book->sach_id }})">
                    <i class="fas fa-shopping-cart me-2"></i>
                    Thêm vào giỏ hàng
                </button>
                <button class="btn btn-outline-danger" onclick="toggleWishlist({{ $book->sach_id }})">
                    <i class="fas fa-heart me-2"></i>
                    Thêm vào yêu thích
                </button>
            </div>
        </div>

        <!-- Book Details -->
        <div class="col-lg-7">
            <div class="card card-modern">
                <div class="card-body">
                    <h1 class="card-title fw-bold mb-3">{{ $book->ten_sach }}</h1>
                    
                    <!-- Rating -->
                    <div class="d-flex align-items-center mb-3">
                        <div class="text-warning me-2">
                            @for($i = 1; $i <= 5; $i++)
                                @if($i <= $book->rating_average)
                                    <i class="fas fa-star"></i>
                                @else
                                    <i class="far fa-star"></i>
                                @endif
                            @endfor
                        </div>
                        <span class="text-muted">
                            ({{ $book->rating_average }}/5 - {{ $book->reviews_count }} đánh giá)
                        </span>
                    </div>

                    <!-- Price -->
                    <div class="mb-4">
                        @if($book->gia_khuyen_mai && $book->gia_khuyen_mai < $book->gia_ban)
                            <div class="d-flex align-items-center gap-3">
                                <span class="h3 text-danger fw-bold mb-0">
                                    {{ number_format($book->gia_khuyen_mai, 0, ',', '.') }}đ
                                </span>
                                <span class="text-muted text-decoration-line-through">
                                    {{ number_format($book->gia_ban, 0, ',', '.') }}đ
                                </span>
                                <span class="badge bg-danger">
                                    -{{ round((($book->gia_ban - $book->gia_khuyen_mai) / $book->gia_ban) * 100) }}%
                                </span>
                            </div>
                        @else
                            <span class="h3 text-primary fw-bold">
                                {{ number_format($book->gia_ban, 0, ',', '.') }}đ
                            </span>
                        @endif
                    </div>

                    <!-- Book Info -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <td class="fw-semibold">Tác giả:</td>
                                    <td>
                                        @if($book->tacGia)
                                            <a href="{{ route('author', $book->tacGia->duong_dan) }}" 
                                               class="text-decoration-none">
                                                {{ $book->tacGia->ten_tac_gia }}
                                            </a>
                                        @else
                                            <span class="text-muted">Chưa cập nhật</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td class="fw-semibold">Thể loại:</td>
                                    <td>
                                        @if($book->theLoai)
                                            <a href="{{ route('category', $book->theLoai->duong_dan) }}" 
                                               class="text-decoration-none">
                                                {{ $book->theLoai->ten_the_loai }}
                                            </a>
                                        @else
                                            <span class="text-muted">Chưa phân loại</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td class="fw-semibold">Nhà xuất bản:</td>
                                    <td>
                                        @if($book->nhaXuatBan)
                                            {{ $book->nhaXuatBan->ten_nxb }}
                                        @else
                                            <span class="text-muted">Chưa cập nhật</span>
                                        @endif
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <td class="fw-semibold">Số trang:</td>
                                    <td>{{ $book->so_trang ?? 'Chưa cập nhật' }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-semibold">Năm xuất bản:</td>
                                    <td>{{ $book->nam_xuat_ban ?? 'Chưa cập nhật' }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-semibold">Tình trạng:</td>
                                    <td>
                                        @if($book->so_luong_ton_kho > 0)
                                            <span class="badge bg-success">Còn hàng ({{ $book->so_luong_ton_kho }})</span>
                                        @else
                                            <span class="badge bg-danger">Hết hàng</span>
                                        @endif
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <!-- Description -->
                    @if($book->mo_ta)
                        <div class="mb-4">
                            <h5 class="fw-semibold mb-3">Mô tả sách</h5>
                            <div class="text-muted">
                                {!! nl2br(e($book->mo_ta)) !!}
                            </div>
                        </div>
                    @endif

                    <!-- Tags -->
                    @if($book->tags)
                        <div class="mb-4">
                            <h6 class="fw-semibold mb-2">Từ khóa:</h6>
                            @foreach(explode(',', $book->tags) as $tag)
                                <span class="badge bg-light text-dark me-1 mb-1">#{{ trim($tag) }}</span>
                            @endforeach
                        </div>
                    @endif

                    <!-- Share -->
                    <div class="border-top pt-3">
                        <h6 class="fw-semibold mb-2">Chia sẻ:</h6>
                        <div class="d-flex gap-2">
                            <a href="#" class="btn btn-outline-primary btn-sm">
                                <i class="fab fa-facebook-f"></i>
                            </a>
                            <a href="#" class="btn btn-outline-info btn-sm">
                                <i class="fab fa-twitter"></i>
                            </a>
                            <a href="#" class="btn btn-outline-success btn-sm">
                                <i class="fab fa-whatsapp"></i>
                            </a>
                            <button class="btn btn-outline-secondary btn-sm" onclick="copyLink()">
                                <i class="fas fa-link"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Reviews Section -->
    <div class="row mt-5">
        <div class="col-12">
            <div class="card card-modern">
                <div class="card-header">
                    <h4 class="mb-0">
                        <i class="fas fa-comments me-2"></i>
                        Đánh giá từ khách hàng
                    </h4>
                </div>
                <div class="card-body">
                    <!-- Review Summary -->
                    <div class="row mb-4">
                        <div class="col-md-4 text-center">
                            <div class="display-4 fw-bold text-warning">{{ $book->rating_average }}</div>
                            <div class="text-warning mb-2">
                                @for($i = 1; $i <= 5; $i++)
                                    @if($i <= $book->rating_average)
                                        <i class="fas fa-star"></i>
                                    @else
                                        <i class="far fa-star"></i>
                                    @endif
                                @endfor
                            </div>
                            <div class="text-muted">{{ $book->reviews_count }} đánh giá</div>
                        </div>
                        <div class="col-md-8">
                            @for($i = 5; $i >= 1; $i--)
                                <div class="d-flex align-items-center mb-2">
                                    <span class="me-2">{{ $i }} sao</span>
                                    <div class="progress flex-grow-1 me-2" style="height: 8px;">
                                        <div class="progress-bar bg-warning" 
                                             style="width: {{ $book->reviews_count > 0 ? ($book->rating_distribution[$i] ?? 0) / $book->reviews_count * 100 : 0 }}%">
                                        </div>
                                    </div>
                                    <span class="text-muted">{{ $book->rating_distribution[$i] ?? 0 }}</span>
                                </div>
                            @endfor
                        </div>
                    </div>

                    <!-- Add Review Form -->
                    <div class="border-top pt-4 mb-4">
                        <h5 class="mb-3">Viết đánh giá của bạn</h5>
                        <form id="reviewForm">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Họ tên</label>
                                    <input type="text" class="form-control" name="ho_ten" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Email</label>
                                    <input type="email" class="form-control" name="email" required>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Đánh giá</label>
                                <div class="rating-input">
                                    @for($i = 1; $i <= 5; $i++)
                                        <i class="far fa-star rating-star" data-rating="{{ $i }}"></i>
                                    @endfor
                                </div>
                                <input type="hidden" name="rating" id="rating" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Nhận xét</label>
                                <textarea class="form-control" name="noi_dung" rows="4" required></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-paper-plane me-2"></i>
                                Gửi đánh giá
                            </button>
                        </form>
                    </div>

                    <!-- Reviews List -->
                    <div class="reviews-list">
                        @forelse($book->danhGias()->latest()->take(5)->get() as $review)
                            <div class="border-bottom pb-3 mb-3">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <div>
                                        <h6 class="mb-1">{{ $review->ho_ten }}</h6>
                                        <div class="text-warning">
                                            @for($i = 1; $i <= 5; $i++)
                                                @if($i <= $review->so_sao)
                                                    <i class="fas fa-star"></i>
                                                @else
                                                    <i class="far fa-star"></i>
                                                @endif
                                            @endfor
                                        </div>
                                    </div>
                                    <small class="text-muted">{{ $review->created_at->diffForHumans() }}</small>
                                </div>
                                <p class="text-muted mb-0">{{ $review->noi_dung }}</p>
                            </div>
                        @empty
                            <div class="text-center py-4">
                                <i class="fas fa-comments text-muted fs-1 mb-3"></i>
                                <p class="text-muted">Chưa có đánh giá nào cho sách này.</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Related Books -->
    @if($relatedBooks->count() > 0)
        <div class="row mt-5">
            <div class="col-12">
                <h4 class="fw-bold mb-4">
                    <i class="fas fa-book-open me-2"></i>
                    Sách liên quan
                </h4>
                <div class="row">
                    @foreach($relatedBooks as $relatedBook)
                        <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                            @include('partials.book-card', ['book' => $relatedBook])
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif
</div>
@endsection

@push('styles')
<style>
    .rating-input .rating-star {
        font-size: 1.5rem;
        color: #ddd;
        cursor: pointer;
        transition: color 0.2s;
    }
    
    .rating-input .rating-star:hover,
    .rating-input .rating-star.active {
        color: #ffc107;
    }
</style>
@endpush

@push('scripts')
<script>
    // Rating input
    document.querySelectorAll('.rating-star').forEach(star => {
        star.addEventListener('click', function() {
            const rating = this.dataset.rating;
            document.getElementById('rating').value = rating;
            
            document.querySelectorAll('.rating-star').forEach((s, index) => {
                if (index < rating) {
                    s.classList.remove('far');
                    s.classList.add('fas', 'active');
                } else {
                    s.classList.remove('fas', 'active');
                    s.classList.add('far');
                }
            });
        });
    });

    // Add to cart
    function addToCart(bookId) {
        showLoading();
        
        fetch('/api/cart/add', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                sach_id: bookId,
                so_luong: 1
            })
        })
        .then(response => response.json())
        .then(data => {
            hideLoading();
            if (data.success) {
                showToast('Đã thêm sách vào giỏ hàng!', 'success');
                updateCartCount();
            } else {
                showToast(data.message || 'Có lỗi xảy ra!', 'danger');
            }
        })
        .catch(error => {
            hideLoading();
            showToast('Có lỗi xảy ra!', 'danger');
        });
    }

    // Toggle wishlist
    function toggleWishlist(bookId) {
        showLoading();
        
        fetch('/api/wishlist/toggle', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                sach_id: bookId
            })
        })
        .then(response => response.json())
        .then(data => {
            hideLoading();
            if (data.success) {
                showToast(data.message, 'success');
                updateWishlistCount();
            } else {
                showToast(data.message || 'Có lỗi xảy ra!', 'danger');
            }
        })
        .catch(error => {
            hideLoading();
            showToast('Có lỗi xảy ra!', 'danger');
        });
    }

    // Copy link
    function copyLink() {
        navigator.clipboard.writeText(window.location.href).then(() => {
            showToast('Đã sao chép liên kết!', 'success');
        });
    }

    // Submit review
    document.getElementById('reviewForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        const data = Object.fromEntries(formData);
        data.sach_id = {{ $book->sach_id }};
        
        showLoading();
        
        fetch('/api/reviews/add', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify(data)
        })
        .then(response => response.json())
        .then(data => {
            hideLoading();
            if (data.success) {
                showToast('Cảm ơn bạn đã đánh giá!', 'success');
                this.reset();
                document.getElementById('rating').value = '';
                document.querySelectorAll('.rating-star').forEach(s => {
                    s.classList.remove('fas', 'active');
                    s.classList.add('far');
                });
                // Reload reviews section
                setTimeout(() => location.reload(), 2000);
            } else {
                showToast(data.message || 'Có lỗi xảy ra!', 'danger');
            }
        })
        .catch(error => {
            hideLoading();
            showToast('Có lỗi xảy ra!', 'danger');
        });
    });

    // Update cart count
    function updateCartCount() {
        fetch('/api/cart/count')
            .then(response => response.json())
            .then(data => {
                const cartBadge = document.querySelector('.cart-count');
                if (cartBadge) {
                    cartBadge.textContent = data.count;
                }
            });
    }

    // Update wishlist count
    function updateWishlistCount() {
        fetch('/api/wishlist/count')
            .then(response => response.json())
            .then(data => {
                const wishlistBadge = document.querySelector('.wishlist-count');
                if (wishlistBadge) {
                    wishlistBadge.textContent = data.count;
                }
            });
    }
</script>
@endpush