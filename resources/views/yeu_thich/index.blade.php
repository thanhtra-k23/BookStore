@extends('layouts.pure-blade')

@section('title', $title ?? 'Danh sách yêu thích - BookStore')

@section('content')
<div class="container" style="padding-top: 2rem;">
    <!-- Breadcrumb -->
    <div class="breadcrumb">
        <a href="{{ route('home') }}">Trang chủ</a> › <span class="active">Danh sách yêu thích</span>
    </div>

    <div class="card">
        <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
            <h2 class="card-title">❤️ Danh sách yêu thích</h2>
            @if(isset($yeuThich) && $yeuThich->count() > 0)
            <div style="display: flex; gap: 0.5rem;">
                <button onclick="addAllToCart()" class="btn btn-success">🛒 Thêm tất cả vào giỏ</button>
                <button onclick="clearWishlist()" class="btn btn-danger">🗑️ Xóa tất cả</button>
            </div>
            @endif
        </div>
        <div style="padding: 1.5rem;">
            @if(isset($yeuThich) && $yeuThich->count() > 0)
            <div class="book-grid">
                @foreach($yeuThich as $item)
                <div class="book-card hover-lift" id="wishlist-item-{{ $item->sach->ma_sach ?? $item->sach_id }}">
                    <div style="position: relative; overflow: hidden;">
                        <a href="{{ route('book.detail', ['id' => $item->sach->ma_sach ?? $item->sach_id, 'slug' => $item->sach->slug ?? '']) }}">
                            <img src="{{ $item->sach->anh_bia_url ?? '/images/no-image.png' }}" 
                                 alt="{{ $item->sach->ten_sach ?? 'Sách' }}" 
                                 class="book-image">
                        </a>
                        
                        <!-- Remove button -->
                        <button onclick="removeFromWishlist({{ $item->sach->ma_sach ?? $item->sach_id }})" 
                                style="position: absolute; top: 10px; right: 10px; background: white; border: none; border-radius: 50%; width: 36px; height: 36px; cursor: pointer; box-shadow: var(--shadow-md); display: flex; align-items: center; justify-content: center; font-size: 1.2rem; transition: all 0.3s;"
                                title="Xóa khỏi yêu thích">
                            ❌
                        </button>

                        <!-- Discount badge -->
                        @if($item->sach->gia_khuyen_mai && $item->sach->gia_khuyen_mai < $item->sach->gia_ban)
                        @php
                            $discount = round((($item->sach->gia_ban - $item->sach->gia_khuyen_mai) / $item->sach->gia_ban) * 100);
                        @endphp
                        <div style="position: absolute; top: 10px; left: 10px; background: var(--danger-color); color: white; padding: 0.25rem 0.5rem; border-radius: 4px; font-size: 0.8rem; font-weight: 600;">
                            -{{ $discount }}%
                        </div>
                        @endif

                        <!-- Stock status -->
                        @if(($item->sach->so_luong_ton ?? 0) <= 0)
                        <div style="position: absolute; bottom: 0; left: 0; right: 0; background: rgba(0,0,0,0.7); color: white; text-align: center; padding: 0.5rem; font-size: 0.85rem;">
                            Hết hàng
                        </div>
                        @endif
                    </div>
                    
                    <div class="book-info">
                        <h3 class="book-title">
                            <a href="{{ route('book.detail', ['id' => $item->sach->ma_sach ?? $item->sach_id, 'slug' => $item->sach->slug ?? '']) }}">
                                {{ $item->sach->ten_sach ?? 'Sách' }}
                            </a>
                        </h3>
                        <p class="book-author">{{ $item->sach->tacGia->ten_tac_gia ?? 'Chưa rõ tác giả' }}</p>
                        
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
                            <div>
                                @if($item->sach->gia_khuyen_mai && $item->sach->gia_khuyen_mai < $item->sach->gia_ban)
                                    <span class="book-price">{{ number_format($item->sach->gia_khuyen_mai) }}đ</span>
                                    <span class="book-price-old">{{ number_format($item->sach->gia_ban) }}đ</span>
                                @else
                                    <span class="book-price">{{ number_format($item->sach->gia_ban ?? 0) }}đ</span>
                                @endif
                            </div>
                        </div>

                        <!-- Action buttons -->
                        <div style="display: flex; gap: 0.5rem;">
                            @if(($item->sach->so_luong_ton ?? 0) > 0)
                            <button onclick="addToCart({{ $item->sach->ma_sach ?? $item->sach_id }})" 
                                    class="btn btn-primary" style="flex: 1; padding: 0.6rem;">
                                🛒 Thêm vào giỏ
                            </button>
                            @else
                            <button disabled class="btn btn-secondary" style="flex: 1; padding: 0.6rem; opacity: 0.6;">
                                Hết hàng
                            </button>
                            @endif
                            <a href="{{ route('book.detail', ['id' => $item->sach->ma_sach ?? $item->sach_id, 'slug' => $item->sach->slug ?? '']) }}" 
                               class="btn btn-secondary" style="padding: 0.6rem;">
                                👁️
                            </a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <!-- Pagination -->
            @if($yeuThich->hasPages())
            <div class="pagination" style="margin-top: 2rem;">
                {{ $yeuThich->links() }}
            </div>
            @endif
            @else
            <div style="text-align: center; padding: 4rem 2rem;">
                <div style="font-size: 5rem; margin-bottom: 1.5rem;">❤️</div>
                <h3 style="font-size: 1.5rem; margin-bottom: 1rem; color: var(--dark-color);">Danh sách yêu thích trống</h3>
                <p style="color: var(--secondary-color); margin-bottom: 2rem; max-width: 400px; margin-left: auto; margin-right: auto;">
                    Hãy thêm những cuốn sách yêu thích vào danh sách để dễ dàng theo dõi và mua sắm sau này!
                </p>
                <a href="{{ route('search') }}" class="btn btn-primary" style="padding: 1rem 2rem;">
                    🔍 Khám phá sách ngay
                </a>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function removeFromWishlist(bookId) {
    fetch('/wishlist/toggle', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({ sach_id: bookId })
    }).then(r => r.json()).then(data => {
        const item = document.getElementById('wishlist-item-' + bookId);
        if (item) {
            item.style.transition = 'all 0.3s';
            item.style.opacity = '0';
            item.style.transform = 'scale(0.8)';
            setTimeout(() => {
                item.remove();
                // Check if list is empty
                if (document.querySelectorAll('.book-card').length === 0) {
                    location.reload();
                }
            }, 300);
        }
        showToast('Đã xóa khỏi danh sách yêu thích!', 'success');
    });
}

function addToCart(bookId) {
    fetch('/cart/add', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({ ma_sach: bookId, so_luong: 1 })
    }).then(r => r.json()).then(data => {
        if (data.success) {
            showToast('Đã thêm vào giỏ hàng!', 'success');
            if (typeof updateCartCount === 'function') updateCartCount();
        } else {
            showToast(data.message || 'Có lỗi xảy ra!', 'danger');
        }
    });
}

function addAllToCart() {
    const bookCards = document.querySelectorAll('.book-card');
    const bookIds = Array.from(bookCards).map(card => {
        const id = card.id.replace('wishlist-item-', '');
        return parseInt(id);
    });
    
    if (bookIds.length === 0) return;
    
    fetch('/wishlist/add-to-cart', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({ sach_ids: bookIds })
    }).then(r => r.json()).then(data => {
        showToast('Đã thêm tất cả vào giỏ hàng!', 'success');
        if (typeof updateCartCount === 'function') updateCartCount();
    }).catch(() => {
        // Fallback: add one by one
        bookIds.forEach(id => addToCart(id));
    });
}

function clearWishlist() {
    if (confirm('Bạn có chắc chắn muốn xóa tất cả khỏi danh sách yêu thích?')) {
        fetch('/wishlist/clear', {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        }).then(() => {
            showToast('Đã xóa tất cả khỏi danh sách yêu thích!', 'success');
            location.reload();
        });
    }
}
</script>
@endpush
