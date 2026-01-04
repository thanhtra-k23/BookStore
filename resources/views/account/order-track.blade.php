@extends('layouts.pure-blade')

@section('title', $title)

@section('content')
<div class="container" style="padding-top: 2rem;">
    <!-- Breadcrumb -->
    <div class="breadcrumb">
        <a href="{{ route('home') }}">Trang chủ</a> › 
        <a href="{{ route('profile') }}">Tài khoản</a> › 
        <span class="active">Theo dõi đơn hàng</span>
    </div>

    <div class="row">
        <div class="col-8">
            <div class="card">
                <div class="card-header">
                    <h2 class="card-title">🚚 Theo dõi đơn hàng #{{ $order->ma_don }}</h2>
                </div>
                <div style="padding: 2rem;">
                    <!-- Order Timeline -->
                    <div class="order-timeline" style="position: relative; padding-left: 40px;">
                        @php
                            $statusOrder = ['cho_xac_nhan', 'da_xac_nhan', 'dang_giao', 'da_giao'];
                            $currentIndex = array_search($order->trang_thai, $statusOrder);
                            if ($order->trang_thai == 'da_huy') $currentIndex = -1;
                        @endphp
                        
                        @foreach($timeline as $index => $step)
                        @php
                            $isCompleted = $index <= $currentIndex;
                            $isCurrent = $index == $currentIndex;
                        @endphp
                        <div class="timeline-item" style="position: relative; padding-bottom: 2rem; {{ $index == count($timeline) - 1 ? 'padding-bottom: 0;' : '' }}">
                            <!-- Line -->
                            @if($index < count($timeline) - 1)
                            <div style="position: absolute; left: -28px; top: 30px; width: 4px; height: calc(100% - 10px); background: {{ $isCompleted ? 'var(--success-color)' : '#e2e8f0' }};"></div>
                            @endif
                            
                            <!-- Circle -->
                            <div style="position: absolute; left: -40px; top: 0; width: 28px; height: 28px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 1rem;
                                {{ $isCompleted ? 'background: var(--success-color); color: white;' : 'background: #e2e8f0; color: var(--secondary-color);' }}
                                {{ $isCurrent ? 'box-shadow: 0 0 0 4px rgba(22, 163, 74, 0.2); animation: pulse 2s infinite;' : '' }}">
                                {{ $step['icon'] }}
                            </div>
                            
                            <!-- Content -->
                            <div>
                                <h4 style="font-size: 1.1rem; margin-bottom: 0.25rem; color: {{ $isCompleted ? 'var(--dark-color)' : 'var(--secondary-color)' }};">
                                    {{ $step['label'] }}
                                </h4>
                                @if($step['date'])
                                    <p style="color: var(--secondary-color); font-size: 0.9rem; margin: 0;">
                                        {{ $step['date']->format('H:i - d/m/Y') }}
                                    </p>
                                @elseif($isCurrent)
                                    <p style="color: var(--success-color); font-size: 0.9rem; margin: 0; font-weight: 500;">
                                        Đang xử lý...
                                    </p>
                                @endif
                            </div>
                        </div>
                        @endforeach

                        @if($order->trang_thai == 'da_huy')
                        <div class="timeline-item" style="position: relative;">
                            <div style="position: absolute; left: -40px; top: 0; width: 28px; height: 28px; border-radius: 50%; display: flex; align-items: center; justify-content: center; background: var(--danger-color); color: white;">
                                ❌
                            </div>
                            <div>
                                <h4 style="font-size: 1.1rem; margin-bottom: 0.25rem; color: var(--danger-color);">Đã hủy</h4>
                                <p style="color: var(--secondary-color); font-size: 0.9rem; margin: 0;">Đơn hàng đã bị hủy</p>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Order Items -->
            <div class="card" style="margin-top: 1.5rem;">
                <div class="card-header">
                    <h3 class="card-title">📦 Sản phẩm trong đơn hàng</h3>
                </div>
                <div>
                    @foreach($order->chiTiet as $item)
                    <div style="display: flex; align-items: center; padding: 1rem; border-bottom: 1px solid #e2e8f0;">
                        <img src="{{ $item->sach->anh_bia_url ?? '/images/no-image.png' }}" 
                             alt="{{ $item->sach->ten_sach }}" 
                             style="width: 60px; height: 80px; object-fit: cover; border-radius: 8px; margin-right: 1rem;">
                        <div style="flex: 1;">
                            <h4 style="font-size: 1rem; margin-bottom: 0.25rem;">{{ $item->sach->ten_sach }}</h4>
                            <p style="color: var(--secondary-color); font-size: 0.9rem; margin: 0;">
                                Số lượng: {{ $item->so_luong }} × {{ number_format($item->gia_ban) }}đ
                            </p>
                        </div>
                        <div style="font-weight: 700; color: var(--primary-color);">
                            {{ number_format($item->so_luong * $item->gia_ban) }}đ
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="col-4">
            <!-- Order Summary -->
            <div class="card" style="position: sticky; top: 100px;">
                <div class="card-header">
                    <h3 class="card-title">📋 Thông tin đơn hàng</h3>
                </div>
                <div style="padding: 1.5rem;">
                    <div style="margin-bottom: 1rem;">
                        <p style="color: var(--secondary-color); margin-bottom: 0.25rem; font-size: 0.9rem;">Mã đơn hàng</p>
                        <p style="font-weight: 600; margin: 0;">#{{ $order->ma_don }}</p>
                    </div>
                    <div style="margin-bottom: 1rem;">
                        <p style="color: var(--secondary-color); margin-bottom: 0.25rem; font-size: 0.9rem;">Ngày đặt</p>
                        <p style="font-weight: 600; margin: 0;">{{ $order->created_at->format('H:i - d/m/Y') }}</p>
                    </div>
                    <div style="margin-bottom: 1rem;">
                        <p style="color: var(--secondary-color); margin-bottom: 0.25rem; font-size: 0.9rem;">Địa chỉ giao hàng</p>
                        <p style="margin: 0;">{{ $order->dia_chi_giao }}</p>
                    </div>
                    <div style="margin-bottom: 1rem;">
                        <p style="color: var(--secondary-color); margin-bottom: 0.25rem; font-size: 0.9rem;">Số điện thoại</p>
                        <p style="margin: 0;">{{ $order->so_dien_thoai_giao }}</p>
                    </div>
                    
                    <hr style="margin: 1rem 0; border-color: #e2e8f0;">
                    
                    <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem;">
                        <span style="color: var(--secondary-color);">Tạm tính</span>
                        <span>{{ number_format($order->tong_tien_goc ?? $order->tong_tien) }}đ</span>
                    </div>
                    @if($order->so_tien_giam_gia > 0)
                    <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem;">
                        <span style="color: var(--success-color);">Giảm giá</span>
                        <span style="color: var(--success-color);">-{{ number_format($order->so_tien_giam_gia) }}đ</span>
                    </div>
                    @endif
                    <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem;">
                        <span style="color: var(--secondary-color);">Phí vận chuyển</span>
                        <span style="color: var(--success-color);">Miễn phí</span>
                    </div>
                    
                    <hr style="margin: 1rem 0; border-color: #e2e8f0;">
                    
                    <div style="display: flex; justify-content: space-between; font-size: 1.2rem; font-weight: 700;">
                        <span>Tổng cộng</span>
                        <span style="color: var(--danger-color);">{{ number_format($order->tong_tien) }}đ</span>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="card" style="margin-top: 1rem;">
                <div style="padding: 1rem;">
                    <a href="{{ route('profile') }}#orders" class="btn btn-secondary" style="width: 100%; margin-bottom: 0.5rem;">
                        ← Quay lại đơn hàng
                    </a>
                    @if($order->trang_thai == 'cho_xac_nhan')
                    <button onclick="cancelOrder({{ $order->id }})" class="btn btn-danger" style="width: 100%;">
                        ❌ Hủy đơn hàng
                    </button>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
@keyframes pulse {
    0%, 100% { transform: scale(1); }
    50% { transform: scale(1.1); }
}
</style>
@endpush

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
</script>
@endpush
