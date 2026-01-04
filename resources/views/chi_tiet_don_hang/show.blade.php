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
            <li class="breadcrumb-item active">Chi tiết #{{ $chiTiet->id }}</li>
        </ol>
    </nav>

    <div class="row">
        <div class="col-lg-8">
            <!-- Order Detail Info -->
            <div class="card card-modern mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-info-circle me-2"></i>
                        Thông tin chi tiết đơn hàng
                    </h5>
                    <div class="btn-group">
                        @if($chiTiet->donHang->canCancel())
                            <a href="{{ route('chitietdonhang.edit', $chiTiet->id) }}" class="btn btn-primary btn-sm">
                                <i class="fas fa-edit me-1"></i>
                                Chỉnh sửa
                            </a>
                        @endif
                        <a href="{{ route('admin.chitietdonhang.index') }}" class="btn btn-outline-secondary btn-sm">
                            <i class="fas fa-arrow-left me-1"></i>
                            Quay lại
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <td class="fw-bold" width="150">ID Chi tiết:</td>
                                    <td>#{{ $chiTiet->id }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Số lượng:</td>
                                    <td>
                                        <span class="badge bg-primary fs-6">{{ $chiTiet->so_luong }} cuốn</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Giá bán:</td>
                                    <td>
                                        <span class="fw-bold text-success">
                                            {{ number_format($chiTiet->gia_ban_tai_thoi_diem, 0, ',', '.') }}đ
                                        </span>
                                        @if($chiTiet->gia_ban_tai_thoi_diem != $chiTiet->sach->gia_hien_tai)
                                            <br>
                                            <small class="text-muted">
                                                Giá hiện tại: {{ number_format($chiTiet->sach->gia_hien_tai, 0, ',', '.') }}đ
                                            </small>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Thành tiền:</td>
                                    <td>
                                        <span class="fw-bold text-success fs-5">
                                            {{ number_format($chiTiet->thanh_tien, 0, ',', '.') }}đ
                                        </span>
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <td class="fw-bold" width="150">Ngày tạo:</td>
                                    <td>{{ $chiTiet->created_at->format('d/m/Y H:i:s') }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Cập nhật:</td>
                                    <td>{{ $chiTiet->updated_at->format('d/m/Y H:i:s') }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Trạng thái:</td>
                                    <td>
                                        @if($chiTiet->donHang->canCancel())
                                            <span class="badge bg-warning">Có thể chỉnh sửa</span>
                                        @else
                                            <span class="badge bg-secondary">Không thể chỉnh sửa</span>
                                        @endif
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Book Information -->
            <div class="card card-modern mb-4">
                <div class="card-header">
                    <h6 class="mb-0">
                        <i class="fas fa-book me-2"></i>
                        Thông tin sách
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <img src="{{ $chiTiet->sach->anh_bia_url }}" 
                                 alt="{{ $chiTiet->sach->ten_sach }}"
                                 class="img-fluid rounded shadow-sm">
                        </div>
                        <div class="col-md-9">
                            <h5 class="mb-3">
                                <a href="{{ route('book.detail', [$chiTiet->sach->id, $chiTiet->sach->duong_dan]) }}" 
                                   class="text-decoration-none text-dark">
                                    {{ $chiTiet->sach->ten_sach }}
                                </a>
                            </h5>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <table class="table table-borderless table-sm">
                                        <tr>
                                            <td class="fw-bold" width="100">Tác giả:</td>
                                            <td>
                                                <a href="{{ route('author', $chiTiet->sach->tacGia->duong_dan) }}" 
                                                   class="text-decoration-none">
                                                    {{ $chiTiet->sach->tacGia->ten_tac_gia }}
                                                </a>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold">Thể loại:</td>
                                            <td>
                                                <a href="{{ route('category', $chiTiet->sach->theLoai->duong_dan) }}" 
                                                   class="text-decoration-none">
                                                    {{ $chiTiet->sach->theLoai->ten_the_loai }}
                                                </a>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold">NXB:</td>
                                            <td>{{ $chiTiet->sach->nhaXuatBan->ten_nxb }}</td>
                                        </tr>
                                    </table>
                                </div>
                                <div class="col-md-6">
                                    <table class="table table-borderless table-sm">
                                        <tr>
                                            <td class="fw-bold" width="120">Giá hiện tại:</td>
                                            <td>
                                                @if($chiTiet->sach->isOnSale())
                                                    <span class="text-decoration-line-through text-muted">
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
                                        <tr>
                                            <td class="fw-bold">Trạng thái:</td>
                                            <td>
                                                <span class="badge {{ $chiTiet->sach->trang_thai === 'active' ? 'bg-success' : 'bg-secondary' }}">
                                                    {{ $chiTiet->sach->trang_thai_text }}
                                                </span>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>

                            @if($chiTiet->sach->diem_danh_gia)
                                <div class="mt-3">
                                    <div class="d-flex align-items-center">
                                        <span class="me-2">Đánh giá:</span>
                                        <div class="text-warning me-2">
                                            @for($i = 1; $i <= 5; $i++)
                                                @if($i <= $chiTiet->sach->diem_danh_gia)
                                                    <i class="fas fa-star"></i>
                                                @else
                                                    <i class="far fa-star"></i>
                                                @endif
                                            @endfor
                                        </div>
                                        <span class="text-muted">
                                            ({{ $chiTiet->sach->diem_danh_gia }}/5 - {{ $chiTiet->sach->so_luong_danh_gia }} đánh giá)
                                        </span>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <!-- Order Information -->
            <div class="card card-modern mb-4">
                <div class="card-header">
                    <h6 class="mb-0">
                        <i class="fas fa-shopping-cart me-2"></i>
                        Thông tin đơn hàng
                    </h6>
                </div>
                <div class="card-body">
                    <div class="text-center mb-3">
                        <h5 class="fw-bold">
                            <a href="{{ route('admin.donhang.show', $chiTiet->donHang->id) }}" 
                               class="text-decoration-none">
                                {{ $chiTiet->donHang->ma_don }}
                            </a>
                        </h5>
                        @php
                            $statusColors = [
                                'cho_xac_nhan' => 'warning',
                                'da_xac_nhan' => 'info',
                                'dang_giao' => 'primary',
                                'da_giao' => 'success',
                                'da_huy' => 'danger'
                            ];
                        @endphp
                        <span class="badge bg-{{ $statusColors[$chiTiet->donHang->trang_thai] ?? 'secondary' }} fs-6">
                            {{ $chiTiet->donHang->trang_thai_text }}
                        </span>
                    </div>

                    <table class="table table-borderless table-sm">
                        <tr>
                            <td class="fw-bold">Khách hàng:</td>
                            <td>{{ $chiTiet->donHang->nguoiDung->ho_ten }}</td>
                        </tr>
                        <tr>
                            <td class="fw-bold">Email:</td>
                            <td>{{ $chiTiet->donHang->nguoiDung->email }}</td>
                        </tr>
                        <tr>
                            <td class="fw-bold">Tổng sản phẩm:</td>
                            <td>{{ $chiTiet->donHang->getTotalQuantity() }} cuốn</td>
                        </tr>
                        <tr>
                            <td class="fw-bold">Tổng tiền:</td>
                            <td class="fw-bold text-success">
                                {{ number_format($chiTiet->donHang->tong_tien, 0, ',', '.') }}đ
                            </td>
                        </tr>
                        @if($chiTiet->donHang->so_tien_giam_gia > 0)
                            <tr>
                                <td class="fw-bold">Giảm giá:</td>
                                <td class="text-success">
                                    -{{ number_format($chiTiet->donHang->so_tien_giam_gia, 0, ',', '.') }}đ
                                </td>
                            </tr>
                        @endif
                        <tr>
                            <td class="fw-bold">Thanh toán:</td>
                            <td>{{ $chiTiet->donHang->phuong_thuc_thanh_toan_text }}</td>
                        </tr>
                        <tr>
                            <td class="fw-bold">Ngày tạo:</td>
                            <td>{{ $chiTiet->donHang->created_at->format('d/m/Y H:i') }}</td>
                        </tr>
                    </table>

                    <div class="d-grid">
                        <a href="{{ route('admin.donhang.show', $chiTiet->donHang->id) }}" class="btn btn-outline-primary">
                            <i class="fas fa-eye me-2"></i>
                            Xem đơn hàng đầy đủ
                        </a>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            @if($chiTiet->donHang->canCancel())
                <div class="card card-modern">
                    <div class="card-header">
                        <h6 class="mb-0">
                            <i class="fas fa-cogs me-2"></i>
                            Hành động
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <a href="{{ route('chitietdonhang.edit', $chiTiet->id) }}" class="btn btn-primary">
                                <i class="fas fa-edit me-2"></i>
                                Chỉnh sửa chi tiết
                            </a>
                            <button type="button" class="btn btn-danger" onclick="confirmDelete()">
                                <i class="fas fa-trash me-2"></i>
                                Xóa khỏi đơn hàng
                            </button>
                        </div>
                    </div>
                </div>
            @else
                <div class="card card-modern">
                    <div class="card-body text-center">
                        <i class="fas fa-lock fa-2x text-muted mb-3"></i>
                        <h6 class="text-muted">Không thể chỉnh sửa</h6>
                        <p class="text-muted small mb-0">
                            Chi tiết đơn hàng này không thể chỉnh sửa do trạng thái đơn hàng hiện tại.
                        </p>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function confirmDelete() {
        if (confirm('Bạn có chắc chắn muốn xóa sản phẩm này khỏi đơn hàng?\n\nHành động này sẽ:\n- Xóa sản phẩm khỏi đơn hàng\n- Hoàn lại số lượng vào kho\n- Cập nhật lại tổng tiền đơn hàng')) {
            // Create form and submit
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '{{ route("chitietdonhang.destroy", $chiTiet->id) }}';
            
            const csrfToken = document.createElement('input');
            csrfToken.type = 'hidden';
            csrfToken.name = '_token';
            csrfToken.value = '{{ csrf_token() }}';
            
            const methodField = document.createElement('input');
            methodField.type = 'hidden';
            methodField.name = '_method';
            methodField.value = 'DELETE';
            
            form.appendChild(csrfToken);
            form.appendChild(methodField);
            document.body.appendChild(form);
            form.submit();
        }
    }
</script>
@endpush
