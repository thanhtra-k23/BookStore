@extends('layouts.app')

@section('title', $title)

@section('content')
<div class="container mt-4 mb-5">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('admin.chitietdonhang.index') }}" class="text-decoration-none">
                    <i class="fas fa-list-alt me-1"></i>
                    Chi tiết đơn hàng
                </a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('chitietdonhang.show', $chiTiet->id) }}" class="text-decoration-none">
                    Chi tiết #{{ $chiTiet->id }}
                </a>
            </li>
            <li class="breadcrumb-item active">Chỉnh sửa</li>
        </ol>
    </nav>

    <div class="row">
        <div class="col-lg-8">
            <div class="card card-modern">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-edit me-2"></i>
                        Chỉnh sửa chi tiết đơn hàng
                    </h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('chitietdonhang.update', $chiTiet->id) }}">
                        @csrf
                        @method('PUT')
                        
                        <!-- Order and Book Info (Read-only) -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label class="form-label">Đơn hàng</label>
                                <div class="form-control-plaintext border rounded p-2 bg-light">
                                    <strong>{{ $chiTiet->donHang->ma_don }}</strong>
                                    <br>
                                    <small class="text-muted">{{ $chiTiet->donHang->nguoiDung->ho_ten }}</small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Sách</label>
                                <div class="form-control-plaintext border rounded p-2 bg-light">
                                    <strong>{{ $chiTiet->sach->ten_sach }}</strong>
                                    <br>
                                    <small class="text-muted">{{ $chiTiet->sach->tacGia->ten_tac_gia }}</small>
                                </div>
                            </div>
                        </div>

                        <!-- Editable Fields -->
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Số lượng <span class="text-danger">*</span></label>
                                <input type="number" name="so_luong" class="form-control @error('so_luong') is-invalid @enderror" 
                                       value="{{ old('so_luong', $chiTiet->so_luong) }}" 
                                       min="1" max="{{ $chiTiet->sach->so_luong_ton + $chiTiet->so_luong }}" 
                                       required onchange="calculateTotal()">
                                @error('so_luong')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">
                                    Tồn kho hiện tại: {{ $chiTiet->sach->so_luong_ton }} cuốn
                                    <br>
                                    Số lượng cũ: {{ $chiTiet->so_luong }} cuốn
                                    <br>
                                    Có thể đặt tối đa: {{ $chiTiet->sach->so_luong_ton + $chiTiet->so_luong }} cuốn
                                </div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Giá bán tại thời điểm <span class="text-danger">*</span></label>
                                <input type="number" name="gia_ban_tai_thoi_diem" class="form-control @error('gia_ban_tai_thoi_diem') is-invalid @enderror" 
                                       value="{{ old('gia_ban_tai_thoi_diem', $chiTiet->gia_ban_tai_thoi_diem) }}" 
                                       min="0" step="1000" required onchange="calculateTotal()">
                                @error('gia_ban_tai_thoi_diem')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">
                                    Giá cũ: {{ number_format($chiTiet->gia_ban_tai_thoi_diem, 0, ',', '.') }}đ
                                    <br>
                                    Giá hiện tại: {{ number_format($chiTiet->sach->gia_hien_tai, 0, ',', '.') }}đ
                                </div>
                            </div>
                        </div>

                        <!-- Warning Alert -->
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            <strong>Lưu ý:</strong>
                            <ul class="mb-0 mt-2">
                                <li>Thay đổi số lượng sẽ ảnh hưởng đến tồn kho sách</li>
                                <li>Thay đổi giá bán sẽ ảnh hưởng đến tổng tiền đơn hàng</li>
                                <li>Các thay đổi sẽ được áp dụng ngay lập tức</li>
                            </ul>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>
                                Cập nhật
                            </button>
                            <a href="{{ route('chitietdonhang.show', $chiTiet->id) }}" class="btn btn-outline-secondary">
                                <i class="fas fa-times me-2"></i>
                                Hủy bỏ
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <!-- Current Info -->
            <div class="card card-modern mb-4">
                <div class="card-header">
                    <h6 class="mb-0">
                        <i class="fas fa-info-circle me-2"></i>
                        Thông tin hiện tại
                    </h6>
                </div>
                <div class="card-body">
                    <table class="table table-borderless table-sm">
                        <tr>
                            <td class="fw-bold">Số lượng:</td>
                            <td>{{ $chiTiet->so_luong }} cuốn</td>
                        </tr>
                        <tr>
                            <td class="fw-bold">Giá bán:</td>
                            <td>{{ number_format($chiTiet->gia_ban_tai_thoi_diem, 0, ',', '.') }}đ</td>
                        </tr>
                        <tr>
                            <td class="fw-bold">Thành tiền:</td>
                            <td class="fw-bold text-success">
                                {{ number_format($chiTiet->thanh_tien, 0, ',', '.') }}đ
                            </td>
                        </tr>
                    </table>
                </div>
            </div>

            <!-- Book Info -->
            <div class="card card-modern mb-4">
                <div class="card-header">
                    <h6 class="mb-0">
                        <i class="fas fa-book me-2"></i>
                        Thông tin sách
                    </h6>
                </div>
                <div class="card-body">
                    <div class="text-center mb-3">
                        <img src="{{ $chiTiet->sach->anh_bia_url }}" 
                             alt="{{ $chiTiet->sach->ten_sach }}"
                             class="img-fluid rounded" style="max-height: 120px;">
                    </div>
                    <h6 class="text-center mb-3">{{ $chiTiet->sach->ten_sach }}</h6>
                    
                    <table class="table table-borderless table-sm">
                        <tr>
                            <td class="fw-bold">Tác giả:</td>
                            <td>{{ $chiTiet->sach->tacGia->ten_tac_gia }}</td>
                        </tr>
                        <tr>
                            <td class="fw-bold">Thể loại:</td>
                            <td>{{ $chiTiet->sach->theLoai->ten_the_loai }}</td>
                        </tr>
                        <tr>
                            <td class="fw-bold">Giá hiện tại:</td>
                            <td>
                                @if($chiTiet->sach->isOnSale())
                                    <span class="text-decoration-line-through text-muted small">
                                        {{ number_format($chiTiet->sach->gia_ban, 0, ',', '.') }}đ
                                    </span>
                                    <br>
                                    <span class="fw-bold text-danger">
                                        {{ number_format($chiTiet->sach->gia_khuyen_mai, 0, ',', '.') }}đ
                                    </span>
                                @else
                                    <span class="fw-bold">
                                        {{ number_format($chiTiet->sach->gia_ban, 0, ',', '.') }}đ
                                    </span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td class="fw-bold">Tồn kho:</td>
                            <td>
                                <span class="badge {{ $chiTiet->sach->so_luong_ton > 0 ? 'bg-success' : 'bg-danger' }}">
                                    {{ $chiTiet->sach->so_luong_ton }} cuốn
                                </span>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>

            <!-- Calculation Preview -->
            <div class="card card-modern">
                <div class="card-header">
                    <h6 class="mb-0">
                        <i class="fas fa-calculator me-2"></i>
                        Tính toán mới
                    </h6>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-2">
                        <span>Số lượng:</span>
                        <span id="new-quantity">{{ $chiTiet->so_luong }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Giá bán:</span>
                        <span id="new-price">{{ number_format($chiTiet->gia_ban_tai_thoi_diem, 0, ',', '.') }}đ</span>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between">
                        <span class="fw-bold">Thành tiền mới:</span>
                        <span id="new-total" class="fw-bold text-primary">
                            {{ number_format($chiTiet->thanh_tien, 0, ',', '.') }}đ
                        </span>
                    </div>
                    
                    <div class="mt-3 pt-3 border-top">
                        <div class="d-flex justify-content-between text-muted small">
                            <span>Chênh lệch:</span>
                            <span id="difference">0đ</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    const originalQuantity = {{ $chiTiet->so_luong }};
    const originalPrice = {{ $chiTiet->gia_ban_tai_thoi_diem }};
    const originalTotal = {{ $chiTiet->thanh_tien }};

    function calculateTotal() {
        const quantity = parseInt(document.querySelector('input[name="so_luong"]').value) || 0;
        const price = parseFloat(document.querySelector('input[name="gia_ban_tai_thoi_diem"]').value) || 0;
        const total = quantity * price;
        const difference = total - originalTotal;
        
        document.getElementById('new-quantity').textContent = quantity;
        document.getElementById('new-price').textContent = new Intl.NumberFormat('vi-VN').format(price) + 'đ';
        document.getElementById('new-total').textContent = new Intl.NumberFormat('vi-VN').format(total) + 'đ';
        
        const differenceElement = document.getElementById('difference');
        if (difference > 0) {
            differenceElement.textContent = '+' + new Intl.NumberFormat('vi-VN').format(difference) + 'đ';
            differenceElement.className = 'text-success';
        } else if (difference < 0) {
            differenceElement.textContent = new Intl.NumberFormat('vi-VN').format(difference) + 'đ';
            differenceElement.className = 'text-danger';
        } else {
            differenceElement.textContent = '0đ';
            differenceElement.className = 'text-muted';
        }
    }

    // Initialize calculation on page load
    document.addEventListener('DOMContentLoaded', function() {
        calculateTotal();
    });

    // Validate quantity against available stock
    document.querySelector('input[name="so_luong"]').addEventListener('input', function() {
        const maxQuantity = {{ $chiTiet->sach->so_luong_ton + $chiTiet->so_luong }};
        const quantity = parseInt(this.value);
        
        if (quantity > maxQuantity) {
            this.setCustomValidity(`Số lượng không được vượt quá ${maxQuantity} cuốn`);
        } else if (quantity < 1) {
            this.setCustomValidity('Số lượng phải lớn hơn 0');
        } else {
            this.setCustomValidity('');
        }
        
        calculateTotal();
    });

    // Update calculation when price changes
    document.querySelector('input[name="gia_ban_tai_thoi_diem"]').addEventListener('input', function() {
        calculateTotal();
    });
</script>
@endpush