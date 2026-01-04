@extends('layouts.pure-blade')

@section('title', $title)

@section('content')
<div class="container" style="padding-top: 2rem;">
    <!-- Breadcrumb -->
    <div class="breadcrumb">
        <a href="{{ route('home') }}">Trang chủ</a> › 
        <a href="{{ route('profile') }}">Tài khoản</a> › 
        <span class="active">Chi tiết đơn hàng</span>
    </div>

    <div class="row">
        <div class="col-8">
            <!-- Order Status -->
            <div class="card">
                <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
                    <h2 class="card-title">📦 Đơn hàng #{{ $order->ma_don }}</h2>
                    @php
                        $statusColors = [
                            'cho_xac_nhan' => 'warning',
                            'da_xac_nhan' => 'info',
                            'dang_giao' => 'primary',
                            'da_giao' => 'success',
                            'da_huy' => 'danger'
                        ];
                        $statusTexts = [
                            'cho_xac_nhan' => 'Chờ xác nhận',
                            'da_xac_nhan' => 'Đã xác nhận',
                            'dang_giao' => 'Đang giao',
                            'da_giao' => 'Hoàn thành',
                            'da_huy' => 'Đã hủy'
                        ];
                    @endphp
                    <span class="badge badge-{{ $statusColors[$order->trang_thai] ?? 'secondary' }}" style="font-size: 1rem; padding: 0.5rem 1rem;">
                        {{ $statusTexts[$order->trang_thai] ?? $order->trang_thai }}
                    </span>
                </div>
                <div style="padding: 1.5rem;">
                    <div class="row">
                        <div class="col-6">
                            <p style="color: var(--secondary-color); margin-bottom: 0.25rem;">Ngày đặt hàng</p>
                            <p style="font-weight: 600;">{{ $order->created_at->format('H:i - d/m/Y') }}</p>
                        </div>
                        <div class="col-6">
                            <p style="color: var(--secondary-color); margin-bottom: 0.25rem;">Phương thức thanh toán</p>
                            <p style="font-weight: 600;">
                                @switch($order->phuong_thuc_thanh_toan)
                                    @case('cod') Thanh toán khi nhận hàng @break
                                    @case('chuyen_khoan') Chuyển khoản ngân hàng @break
                                    @case('the_tin_dung') Thẻ tín dụng @break
                                    @default {{ $order->phuong_thuc_thanh_toan }}
                                @endswitch
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Order Items -->
            <div class="card" style="margin-top: 1.5rem;">
                <div class="card-header">
                    <h3 class="card-title">🛒 Sản phẩm đã đặt</h3>
                </div>
                <div>
                    @foreach($order->chiTiet as $item)
                    <div style="display: flex; align-items: center; padding: 1rem; border-bottom: 1px solid #e2e8f0;">
                        <a href="{{ route('book.detail', ['id' => $item->sach->ma_sach, 'slug' => $item->sach->slug ?? '']) }}">
                            <img src="{{ $item->sach->anh_bia_url ?? '/images/no-image.png' }}" 
                                 alt="{{ $item->sach->ten_sach }}" 
                                 style="width: 80px; height: 100px; object-fit: cover; border-radius: 8px; margin-right: 1rem;">
                        </a>
                        <div style="flex: 1;">
                            <h4 style="font-size: 1rem; margin-bottom: 0.25rem;">
                                <a href="{{ route('book.detail', ['id' => $item->sach->ma_sach, 'slug' => $item->sach->slug ?? '']) }}" style="text-decoration: none; color: var(--dark-color);">
                                    {{ $item->sach->ten_sach }}
                                </a>
                            </h4>
                            <p style="color: var(--secondary-color); font-size: 0.9rem; margin: 0;">
                                {{ $item->sach->tacGia->ten_tac_gia ?? 'Chưa rõ tác giả' }}
                            </p>
                            <p style="color: var(--secondary-color); font-size: 0.9rem; margin: 0.25rem 0 0;">
                                Số lượng: {{ $item->so_luong }} × {{ number_format($item->gia_ban) }}đ
                            </p>
                        </div>
                        <div style="text-align: right;">
                            <div style="font-weight: 700; color: var(--primary-color); font-size: 1.1rem;">
                                {{ number_format($item->so_luong * $item->gia_ban) }}đ
                            </div>
                            @if($order->trang_thai == 'da_giao')
                            <a href="{{ route('book.detail', ['id' => $item->sach->ma_sach, 'slug' => $item->sach->slug ?? '']) }}#reviews" 
                               class="btn btn-warning" style="padding: 0.3rem 0.6rem; font-size: 0.8rem; margin-top: 0.5rem;">
                                ⭐ Đánh giá
                            </a>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Shipping Info -->
            <div class="card" style="margin-top: 1.5rem;">
                <div class="card-header">
                    <h3 class="card-title">📍 Thông tin giao hàng</h3>
                </div>
                <div style="padding: 1.5rem;">
                    <div class="row">
                        <div class="col-6">
                            <p style="color: var(--secondary-color); margin-bottom: 0.25rem;">Người nhận</p>
                            <p style="font-weight: 600;">{{ $order->nguoiDung->ho_ten ?? 'N/A' }}</p>
                        </div>
                        <div class="col-6">
                            <p style="color: var(--secondary-color); margin-bottom: 0.25rem;">Số điện thoại</p>
                            <p style="font-weight: 600;">{{ $order->so_dien_thoai_giao }}</p>
                        </div>
                    </div>
                    <div style="margin-top: 1rem;">
                        <p style="color: var(--secondary-color); margin-bottom: 0.25rem;">Địa chỉ giao hàng</p>
                        <p style="font-weight: 600;">{{ $order->dia_chi_giao }}</p>
                    </div>
                    @if($order->ghi_chu)
                    <div style="margin-top: 1rem;">
                        <p style="color: var(--secondary-color); margin-bottom: 0.25rem;">Ghi chú</p>
                        <p>{{ $order->ghi_chu }}</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-4">
            <!-- Order Summary -->
            <div class="card" style="position: sticky; top: 100px;">
                <div class="card-header">
                    <h3 class="card-title">💰 Tổng thanh toán</h3>
                </div>
                <div style="padding: 1.5rem;">
                    <div style="display: flex; justify-content: space-between; margin-bottom: 0.75rem;">
                        <span style="color: var(--secondary-color);">Tạm tính ({{ $order->chiTiet->sum('so_luong') }} sản phẩm)</span>
                        <span>{{ number_format($order->tong_tien_goc ?? $order->tong_tien) }}đ</span>
                    </div>
                    
                    @if($order->so_tien_giam_gia > 0)
                    <div style="display: flex; justify-content: space-between; margin-bottom: 0.75rem;">
                        <span style="color: var(--success-color);">
                            Giảm giá
                            @if($order->maGiamGia)
                                <span style="font-size: 0.8rem;">({{ $order->maGiamGia->ma_code }})</span>
                            @endif
                        </span>
                        <span style="color: var(--success-color);">-{{ number_format($order->so_tien_giam_gia) }}đ</span>
                    </div>
                    @endif
                    
                    <div style="display: flex; justify-content: space-between; margin-bottom: 0.75rem;">
                        <span style="color: var(--secondary-color);">Phí vận chuyển</span>
                        <span style="color: var(--success-color);">Miễn phí</span>
                    </div>
                    
                    <hr style="margin: 1rem 0; border-color: #e2e8f0;">
                    
                    <div style="display: flex; justify-content: space-between; font-size: 1.25rem; font-weight: 700;">
                        <span>Tổng cộng</span>
                        <span style="color: var(--danger-color);">{{ number_format($order->tong_tien) }}đ</span>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="card" style="margin-top: 1rem;">
                <div style="padding: 1rem;">
                    @if($order->trang_thai == 'dang_giao')
                    <a href="{{ route('orders.track', $order->id) }}" class="btn btn-info" style="width: 100%; margin-bottom: 0.5rem;">
                        🚚 Theo dõi đơn hàng
                    </a>
                    @endif
                    
                    @if($order->trang_thai == 'cho_xac_nhan')
                    <button onclick="cancelOrder({{ $order->id }})" class="btn btn-danger" style="width: 100%; margin-bottom: 0.5rem;">
                        ❌ Hủy đơn hàng
                    </button>
                    @endif
                    
                    @if($order->trang_thai == 'da_giao')
                    <a href="{{ route('orders.review', $order->id) }}" class="btn btn-warning" style="width: 100%; margin-bottom: 0.5rem;">
                        ⭐ Đánh giá sản phẩm
                    </a>
                    <button onclick="reorder({{ $order->id }})" class="btn btn-success" style="width: 100%; margin-bottom: 0.5rem;">
                        🔄 Mua lại
                    </button>
                    @endif
                    
                    <a href="{{ route('profile') }}#orders" class="btn btn-secondary" style="width: 100%;">
                        ← Quay lại đơn hàng
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function cancelOrder(orderId) {
    if (confirm('Bạn có chắc chắn muốn hủy đơn hàng này?')) {
        fetch(`/orders/${orderId}/cancel`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        }).then(r => r.json()).then(data => {
            if (data.success) {
                showToast('Đã hủy đơn hàng thành công!', 'success');
                setTimeout(() => location.reload(), 1000);
            } else {
                showToast(data.message || 'Có lỗi xảy ra!', 'danger');
            }
        });
    }
}

function reorder(orderId) {
    fetch(`/orders/${orderId}/reorder`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        }
    }).then(r => r.json()).then(data => {
        if (data.success) {
            showToast('Đã thêm sản phẩm vào giỏ hàng!', 'success');
            updateCartCount();
        } else {
            showToast(data.message || 'Có lỗi xảy ra!', 'danger');
        }
    });
}
</script>
@endpush
