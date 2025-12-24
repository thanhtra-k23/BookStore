@extends('layouts.pure-blade')

@section('title', 'Trang chủ - BookStore')

@section('content')
<div class="card">
    <div class="card-header">
        <h1 class="card-title">Chào mừng đến với BookStore</h1>
        <p>Khám phá thế giới sách với hàng ngàn đầu sách chất lượng</p>
    </div>
</div>

<!-- Search Section -->
<div class="card">
    <form action="{{ route('search') }}" method="GET" class="d-flex">
        <input type="text" name="q" class="form-control" placeholder="Tìm kiếm sách, tác giả..." 
               value="{{ request('q') }}" style="margin-right: 1rem;">
        <button type="submit" class="btn btn-primary">Tìm kiếm</button>
    </form>
</div>

<!-- Featured Books -->
<div class="card">
    <div class="card-header">
        <h2 class="card-title">📚 Sách nổi bật</h2>
    </div>
    
    <div class="book-grid">
        @forelse($sachNoiBat as $sach)
            <div class="book-card">
                <img src="{{ $sach->anh_bia_url }}" alt="{{ $sach->ten_sach }}" class="book-image">
                <div class="book-info">
                    <h3 class="book-title">
                        <a href="{{ route('book.detail', ['id' => $sach->ma_sach, 'slug' => $sach->duong_dan]) }}" 
                           style="text-decoration: none; color: inherit;">
                            {{ $sach->ten_sach }}
                        </a>
                    </h3>
                    <p class="book-author">
                        Tác giả: {{ $sach->tacGia->ten_tac_gia ?? 'Chưa xác định' }}
                    </p>
                    <p class="book-author">
                        Thể loại: {{ $sach->theLoai->ten_the_loai ?? 'Chưa phân loại' }}
                    </p>
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            @if($sach->isOnSale())
                                <span class="book-price">{{ number_format($sach->gia_khuyen_mai) }}đ</span>
                                <span class="book-price-old">{{ number_format($sach->gia_ban) }}đ</span>
                            @else
                                <span class="book-price">{{ number_format($sach->gia_ban) }}đ</span>
                            @endif
                        </div>
                        <div>
                            @if($sach->canOrder())
                                <button onclick="addToCart({{ $sach->ma_sach }})" class="btn btn-primary">
                                    Thêm vào giỏ
                                </button>
                            @else
                                <span style="color: #dc2626; font-weight: bold;">Hết hàng</span>
                            @endif
                        </div>
                    </div>
                    <div class="mt-2">
                        <small style="color: #6b7280;">
                            ⭐ {{ $sach->diem_trung_binh ?? 0 }}/5 
                            ({{ $sach->so_luot_danh_gia ?? 0 }} đánh giá)
                        </small>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="alert alert-info text-center">
                    Chưa có sách nổi bật nào.
                </div>
            </div>
        @endforelse
    </div>
</div>

<!-- New Books -->
<div class="card">
    <div class="card-header">
        <h2 class="card-title">🆕 Sách mới nhất</h2>
    </div>
    
    <div class="book-grid">
        @forelse($sachMoi as $sach)
            <div class="book-card">
                <img src="{{ $sach->anh_bia_url }}" alt="{{ $sach->ten_sach }}" class="book-image">
                <div class="book-info">
                    <h3 class="book-title">
                        <a href="{{ route('book.detail', ['id' => $sach->ma_sach, 'slug' => $sach->duong_dan]) }}" 
                           style="text-decoration: none; color: inherit;">
                            {{ $sach->ten_sach }}
                        </a>
                    </h3>
                    <p class="book-author">
                        Tác giả: {{ $sach->tacGia->ten_tac_gia ?? 'Chưa xác định' }}
                    </p>
                    <p class="book-author">
                        Thể loại: {{ $sach->theLoai->ten_the_loai ?? 'Chưa phân loại' }}
                    </p>
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            @if($sach->isOnSale())
                                <span class="book-price">{{ number_format($sach->gia_khuyen_mai) }}đ</span>
                                <span class="book-price-old">{{ number_format($sach->gia_ban) }}đ</span>
                            @else
                                <span class="book-price">{{ number_format($sach->gia_ban) }}đ</span>
                            @endif
                        </div>
                        <div>
                            @if($sach->canOrder())
                                <button onclick="addToCart({{ $sach->ma_sach }})" class="btn btn-primary">
                                    Thêm vào giỏ
                                </button>
                            @else
                                <span style="color: #dc2626; font-weight: bold;">Hết hàng</span>
                            @endif
                        </div>
                    </div>
                    <div class="mt-2">
                        <small style="color: #6b7280;">
                            Ngày thêm: {{ $sach->created_at->format('d/m/Y') }}
                        </small>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="alert alert-info text-center">
                    Chưa có sách mới nào.
                </div>
            </div>
        @endforelse
    </div>
</div>

<!-- Categories -->
<div class="card">
    <div class="card-header">
        <h2 class="card-title">📂 Danh mục sách</h2>
    </div>
    
    <div class="row">
        @forelse($theLoais as $theLoai)
            <div class="col-4 mb-3">
                <div class="card">
                    <div class="text-center" style="padding: 1rem;">
                        <h4>{{ $theLoai->ten_the_loai }}</h4>
                        <p style="color: #6b7280;">{{ $theLoai->sach_count ?? 0 }} cuốn sách</p>
                        <a href="{{ route('category', $theLoai->duong_dan) }}" class="btn btn-primary">
                            Xem thêm
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="alert alert-info text-center">
                    Chưa có danh mục nào.
                </div>
            </div>
        @endforelse
    </div>
</div>

<!-- Statistics -->
<div class="card">
    <div class="card-header">
        <h2 class="card-title">📊 Thống kê</h2>
    </div>
    
    <div class="row text-center">
        <div class="col-3">
            <div class="card">
                <h3 style="color: #2563eb; font-size: 2rem;">{{ $tongSach }}</h3>
                <p>Tổng số sách</p>
            </div>
        </div>
        <div class="col-3">
            <div class="card">
                <h3 style="color: #16a34a; font-size: 2rem;">{{ $tongTacGia }}</h3>
                <p>Tác giả</p>
            </div>
        </div>
        <div class="col-3">
            <div class="card">
                <h3 style="color: #d97706; font-size: 2rem;">{{ $tongTheLoai }}</h3>
                <p>Thể loại</p>
            </div>
        </div>
        <div class="col-3">
            <div class="card">
                <h3 style="color: #dc2626; font-size: 2rem;">{{ $tongNguoiDung }}</h3>
                <p>Người dùng</p>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Add to cart function using pure JavaScript and Laravel Eloquent
    function addToCart(sachId) {
        if (!sachId) {
            showAlert('Có lỗi xảy ra, vui lòng thử lại!', 'danger');
            return;
        }

        // Show loading
        const button = event.target;
        const originalText = button.textContent;
        button.textContent = 'Đang thêm...';
        button.disabled = true;

        // Make AJAX request
        ajaxRequest('/cart/add', 'POST', {
            ma_sach: sachId,
            so_luong: 1
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showAlert(data.message, 'success');
                // Update cart count if needed
                updateCartCount();
            } else {
                showAlert(data.message || 'Có lỗi xảy ra!', 'danger');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showAlert('Có lỗi xảy ra, vui lòng thử lại!', 'danger');
        })
        .finally(() => {
            // Restore button
            button.textContent = originalText;
            button.disabled = false;
        });
    }

    // Update cart count
    function updateCartCount() {
        ajaxRequest('/api/cart/count')
        .then(response => response.json())
        .then(data => {
            // Update cart count in navbar if exists
            const cartCountElements = document.querySelectorAll('.cart-count');
            cartCountElements.forEach(element => {
                element.textContent = data.count || 0;
            });
        })
        .catch(error => {
            console.error('Error updating cart count:', error);
        });
    }

    // Load cart count on page load
    document.addEventListener('DOMContentLoaded', function() {
        updateCartCount();
    });
</script>
@endpush