@extends('layouts.pure-blade')

@section('title', 'Danh sách yêu thích')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-heart me-2 text-danger"></i>Danh sách yêu thích
            </h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Trang chủ</a></li>
                    <li class="breadcrumb-item active">Yêu thích</li>
                </ol>
            </nav>
        </div>
        <div>
            @if(isset($yeuThich) && $yeuThich->count() > 0)
                <button type="button" class="btn btn-success me-2" onclick="addAllToCart()">
                    <i class="fas fa-cart-plus me-1"></i>Thêm tất cả vào giỏ hàng
                </button>
                <button type="button" class="btn btn-danger" onclick="clearWishlist()">
                    <i class="fas fa-trash me-1"></i>Xóa tất cả
                </button>
            @endif
        </div>
    </div>

    @if(isset($yeuThich) && $yeuThich->count() > 0)
        <!-- Wishlist Items -->
        <div class="row">
            @foreach($yeuThich as $item)
                <div class="col-lg-3 col-md-4 col-sm-6 mb-4" id="wishlist-item-{{ $item->ma_yeu_thich }}">
                    <div class="card h-100 shadow-sm">
                        <!-- Book Image -->
                        <div class="position-relative">
                            @if($item->sach->hinh_anh)
                                <img src="{{ Storage::url($item->sach->hinh_anh) }}" 
                                     class="card-img-top" alt="{{ $item->sach->ten_sach }}"
                                     style="height: 250px; object-fit: cover;">
                            @else
                                <div class="card-img-top bg-light d-flex align-items-center justify-content-center" 
                                     style="height: 250px;">
                                    <i class="fas fa-book fa-3x text-muted"></i>
                                </div>
                            @endif
                            
                            <!-- Remove from wishlist button -->
                            <button type="button" class="btn btn-danger btn-sm position-absolute top-0 end-0 m-2" 
                                    onclick="removeFromWishlist({{ $item->ma_yeu_thich }})" 
                                    title="Xóa khỏi danh sách yêu thích">
                                <i class="fas fa-times"></i>
                            </button>
                            
                            <!-- Status badge -->
                            @if(!$item->sach->trang_thai)
                                <span class="badge bg-secondary position-absolute bottom-0 start-0 m-2">
                                    Hết hàng
                                </span>
                            @elseif($item->sach->so_luong_ton <= 0)
                                <span class="badge bg-warning position-absolute bottom-0 start-0 m-2">
                                    Tạm hết
                                </span>
                            @endif
                        </div>
                        
                        <!-- Book Info -->
                        <div class="card-body d-flex flex-column">
                            <h6 class="card-title">
                                <a href="{{ route('book.detail', ['id' => $item->sach->ma_sach, 'slug' => $item->sach->duong_dan]) }}" 
                                   class="text-decoration-none text-dark">
                                    {{ Str::limit($item->sach->ten_sach, 60) }}
                                </a>
                            </h6>
                            
                            <p class="card-text text-muted small mb-2">
                                <i class="fas fa-user me-1"></i>
                                {{ $item->sach->tacGia->ten_tac_gia ?? 'Chưa có tác giả' }}
                            </p>
                            
                            <p class="card-text text-muted small mb-2">
                                <i class="fas fa-building me-1"></i>
                                {{ $item->sach->nhaXuatBan->ten_nxb ?? 'Chưa có NXB' }}
                            </p>
                            
                            @if($item->sach->theLoai)
                                <p class="card-text mb-2">
                                    <span class="badge bg-info">{{ $item->sach->theLoai->ten_the_loai }}</span>
                                </p>
                            @endif
                            
                            <!-- Price -->
                            <div class="mt-auto">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <div>
                                        @if($item->sach->gia_khuyen_mai && $item->sach->gia_khuyen_mai < $item->sach->gia_ban)
                                            <span class="text-decoration-line-through text-muted small">
                                                {{ number_format($item->sach->gia_ban) }}đ
                                            </span>
                                            <br>
                                            <span class="text-danger fw-bold">
                                                {{ number_format($item->sach->gia_khuyen_mai) }}đ
                                            </span>
                                        @else
                                            <span class="text-primary fw-bold">
                                                {{ number_format($item->sach->gia_ban) }}đ
                                            </span>
                                        @endif
                                    </div>
                                    <small class="text-muted">
                                        Còn: {{ $item->sach->so_luong_ton }}
                                    </small>
                                </div>
                                
                                <!-- Action Buttons -->
                                <div class="d-grid gap-2">
                                    @if($item->sach->trang_thai && $item->sach->so_luong_ton > 0)
                                        <button type="button" class="btn btn-primary btn-sm" 
                                                onclick="addToCart({{ $item->sach->ma_sach }})">
                                            <i class="fas fa-cart-plus me-1"></i>Thêm vào giỏ hàng
                                        </button>
                                    @else
                                        <button type="button" class="btn btn-secondary btn-sm" disabled>
                                            <i class="fas fa-ban me-1"></i>Không có sẵn
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                        
                        <!-- Added date -->
                        <div class="card-footer bg-transparent">
                            <small class="text-muted">
                                <i class="fas fa-clock me-1"></i>
                                Thêm vào: {{ $item->created_at->format('d/m/Y H:i') }}
                            </small>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        
        <!-- Pagination -->
        @if(method_exists($yeuThich, 'links'))
            {{ $yeuThich->appends(request()->query())->links() }}
        @endif
    @else
        <!-- Empty Wishlist -->
        <div class="text-center py-5">
            <div class="mb-4">
                <i class="fas fa-heart fa-5x text-muted"></i>
            </div>
            <h3 class="text-muted mb-3">Danh sách yêu thích trống</h3>
            <p class="text-muted mb-4">
                Bạn chưa có sách nào trong danh sách yêu thích.<br>
                Hãy khám phá và thêm những cuốn sách yêu thích của bạn!
            </p>
            <div>
                <a href="{{ route('home') }}" class="btn btn-primary me-2">
                    <i class="fas fa-home me-1"></i>Về trang chủ
                </a>
                <a href="{{ route('categories') }}" class="btn btn-outline-primary">
                    <i class="fas fa-tags me-1"></i>Xem thể loại
                </a>
            </div>
        </div>
    @endif
</div>

<!-- Loading Spinner -->
<div id="loadingSpinner" class="position-fixed top-50 start-50 translate-middle" style="display: none; z-index: 9999;">
    <div class="spinner-border text-primary" role="status">
        <span class="visually-hidden">Loading...</span>
    </div>
</div>
@endsection

@push('styles')
<style>
.card {
    transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
}

.card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.15) !important;
}

.card-img-top {
    transition: transform 0.3s ease;
}

.card:hover .card-img-top {
    transform: scale(1.05);
}

.btn-sm {
    font-size: 0.875rem;
}

.badge {
    font-size: 0.75em;
}

.position-relative {
    overflow: hidden;
}

.card-footer {
    border-top: 1px solid rgba(0,0,0,.125);
}

@media (max-width: 768px) {
    .col-sm-6 {
        margin-bottom: 1rem;
    }
}
</style>
@endpush

@push('scripts')
<script>
function removeFromWishlist(wishlistId) {
    Swal.fire({
        title: 'Xác nhận xóa',
        text: 'Bạn có chắc chắn muốn xóa sách này khỏi danh sách yêu thích?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Xóa',
        cancelButtonText: 'Hủy'
    }).then((result) => {
        if (result.isConfirmed) {
            showLoading();
            
            $.ajax({
                url: `/wishlist/remove/${wishlistId}`,
                method: 'DELETE',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    hideLoading();
                    
                    if (response.success) {
                        // Remove item from DOM with animation
                        $(`#wishlist-item-${wishlistId}`).fadeOut(300, function() {
                            $(this).remove();
                            
                            // Check if wishlist is empty
                            if ($('[id^="wishlist-item-"]').length === 0) {
                                location.reload();
                            }
                        });
                        
                        showToast('success', response.message);
                        updateWishlistCount();
                    } else {
                        showToast('error', response.message || 'Có lỗi xảy ra');
                    }
                },
                error: function(xhr) {
                    hideLoading();
                    showToast('error', 'Có lỗi xảy ra khi xóa sách khỏi danh sách yêu thích');
                }
            });
        }
    });
}

function addToCart(bookId) {
    showLoading();
    
    $.ajax({
        url: '/cart/add',
        method: 'POST',
        data: {
            _token: '{{ csrf_token() }}',
            ma_sach: bookId,
            so_luong: 1
        },
        success: function(response) {
            hideLoading();
            
            if (response.success) {
                showToast('success', response.message);
                updateCartCount();
            } else {
                showToast('error', response.message || 'Có lỗi xảy ra');
            }
        },
        error: function(xhr) {
            hideLoading();
            
            if (xhr.status === 401) {
                showToast('warning', 'Vui lòng đăng nhập để thêm sách vào giỏ hàng');
                setTimeout(() => {
                    window.location.href = '/login';
                }, 2000);
            } else {
                showToast('error', 'Có lỗi xảy ra khi thêm sách vào giỏ hàng');
            }
        }
    });
}

function addAllToCart() {
    const availableBooks = [];
    
    // Get all available books
    $('[id^="wishlist-item-"]').each(function() {
        const addToCartBtn = $(this).find('.btn-primary:contains("Thêm vào giỏ hàng")');
        if (addToCartBtn.length > 0) {
            const bookId = addToCartBtn.attr('onclick').match(/addToCart\((\d+)\)/)[1];
            availableBooks.push(bookId);
        }
    });
    
    if (availableBooks.length === 0) {
        showToast('warning', 'Không có sách nào có sẵn để thêm vào giỏ hàng');
        return;
    }
    
    Swal.fire({
        title: 'Xác nhận thêm tất cả',
        text: `Bạn có chắc chắn muốn thêm ${availableBooks.length} sách vào giỏ hàng?`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#28a745',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Thêm tất cả',
        cancelButtonText: 'Hủy'
    }).then((result) => {
        if (result.isConfirmed) {
            showLoading();
            
            $.ajax({
                url: '/wishlist/add-to-cart',
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    book_ids: availableBooks
                },
                success: function(response) {
                    hideLoading();
                    
                    if (response.success) {
                        showToast('success', response.message);
                        updateCartCount();
                    } else {
                        showToast('error', response.message || 'Có lỗi xảy ra');
                    }
                },
                error: function(xhr) {
                    hideLoading();
                    showToast('error', 'Có lỗi xảy ra khi thêm sách vào giỏ hàng');
                }
            });
        }
    });
}

function clearWishlist() {
    Swal.fire({
        title: 'Xác nhận xóa tất cả',
        text: 'Bạn có chắc chắn muốn xóa tất cả sách khỏi danh sách yêu thích?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Xóa tất cả',
        cancelButtonText: 'Hủy'
    }).then((result) => {
        if (result.isConfirmed) {
            showLoading();
            
            $.ajax({
                url: '/wishlist/clear',
                method: 'DELETE',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    hideLoading();
                    
                    if (response.success) {
                        showToast('success', response.message);
                        setTimeout(() => {
                            location.reload();
                        }, 1500);
                    } else {
                        showToast('error', response.message || 'Có lỗi xảy ra');
                    }
                },
                error: function(xhr) {
                    hideLoading();
                    showToast('error', 'Có lỗi xảy ra khi xóa danh sách yêu thích');
                }
            });
        }
    });
}

function updateWishlistCount() {
    $.get('/api/wishlist/count', function(data) {
        $('.wishlist-count').text(data.count);
        if (data.count === 0) {
            $('.wishlist-count').hide();
        }
    });
}

function updateCartCount() {
    $.get('/api/cart/count', function(data) {
        $('.cart-count').text(data.count);
        if (data.count > 0) {
            $('.cart-count').show();
        }
    });
}

function showLoading() {
    $('#loadingSpinner').show();
}

function hideLoading() {
    $('#loadingSpinner').hide();
}

function showToast(type, message) {
    const Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true,
        didOpen: (toast) => {
            toast.addEventListener('mouseenter', Swal.stopTimer)
            toast.addEventListener('mouseleave', Swal.resumeTimer)
        }
    });

    Toast.fire({
        icon: type,
        title: message
    });
}

// Initialize tooltips
$(document).ready(function() {
    $('[title]').tooltip();
});
</script>
@endpush