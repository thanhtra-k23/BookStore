@extends('layouts.admin')

@section('title', $title)

@section('content')
    <!-- Header Section -->
    <div class="card mb-4">
        <div class="card-header">
            <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
                <div>
                    <h4 style="margin: 0; font-weight: 700; display: flex; align-items: center; gap: 0.5rem;">
                        <i class="fas fa-book"></i>
                        Chi tiết sách
                    </h4>
                    <p class="mb-0 text-muted">
                        Thông tin chi tiết về sách
                    </p>
                </div>
                <div class="col-md-6 text-md-end">
                    <a href="{{ route('admin.sach.index') }}" class="btn btn-outline-secondary me-2">
                        <i class="fas fa-arrow-left me-2"></i>
                        Quay lại danh sách
                    </a>
                    <a href="{{ route('admin.sach.edit', $sach->ma_sach) }}" class="btn btn-primary">
                        <i class="fas fa-edit me-2"></i>
                        Chỉnh sửa
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Left Column - Book Image -->
        <div class="col-md-4">
            <div class="card card-modern">
                <div class="card-body text-center">
                    @if($sach->anh_bia)
                        <img src="{{ $sach->anh_bia_url }}" alt="{{ $sach->ten_sach }}" 
                             class="img-fluid rounded shadow mb-3" style="max-height: 400px;">
                    @else
                        <div class="bg-light rounded d-flex align-items-center justify-content-center mb-3" 
                             style="height: 400px;">
                            <i class="fas fa-image fa-4x text-muted"></i>
                        </div>
                    @endif
                    
                    <!-- Status Badge -->
                    <div class="mb-3">
                        <span class="badge {{ $sach->trang_thai === 'active' ? 'bg-success' : 'bg-secondary' }} fs-6">
                            {{ $sach->trang_thai === 'active' ? 'Đang bán' : 'Ngừng bán' }}
                        </span>
                    </div>

                    <!-- Stock Status -->
                    <div class="mb-3">
                        @if($sach->so_luong_ton > 0)
                            <span class="badge bg-info fs-6">
                                <i class="fas fa-boxes me-1"></i>
                                Còn {{ $sach->so_luong_ton }} cuốn
                            </span>
                        @else
                            <span class="badge bg-danger fs-6">
                                <i class="fas fa-exclamation-triangle me-1"></i>
                                Hết hàng
                            </span>
                        @endif
                    </div>

                    <!-- Views -->
                    <div class="text-muted">
                        <i class="fas fa-eye me-1"></i>
                        {{ number_format($sach->luot_xem) }} lượt xem
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column - Book Details -->
        <div class="col-md-8">
            <!-- Basic Information -->
            <div class="card card-modern mb-4">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-info-circle me-2"></i>
                        Thông tin cơ bản
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <td class="fw-bold" width="40%">Tên sách:</td>
                                    <td>{{ $sach->ten_sach }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Tác giả:</td>
                                    <td>
                                        @if($sach->tacGia)
                                            <a href="{{ route('admin.tacgia.show', $sach->tacGia->ma_tac_gia) }}" 
                                               class="text-decoration-none">
                                                {{ $sach->tacGia->ten_tac_gia }}
                                            </a>
                                        @else
                                            <span class="text-muted">Chưa có tác giả</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Thể loại:</td>
                                    <td>
                                        @if($sach->theLoai)
                                            <a href="{{ route('admin.theloai.show', $sach->theLoai->ma_the_loai) }}" 
                                               class="text-decoration-none">
                                                {{ $sach->theLoai->ten_the_loai }}
                                            </a>
                                        @else
                                            <span class="text-muted">Chưa có thể loại</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Nhà xuất bản:</td>
                                    <td>
                                        @if($sach->nhaXuatBan)
                                            <a href="{{ route('admin.nhaxuatban.show', $sach->nhaXuatBan->ma_nxb) }}" 
                                               class="text-decoration-none">
                                                {{ $sach->nhaXuatBan->ten_nxb }}
                                            </a>
                                        @else
                                            <span class="text-muted">Chưa có nhà xuất bản</span>
                                        @endif
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <td class="fw-bold" width="40%">Năm xuất bản:</td>
                                    <td>{{ $sach->nam_xuat_ban }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Ngày thêm:</td>
                                    <td>{{ $sach->created_at->format('d/m/Y H:i') }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Cập nhật cuối:</td>
                                    <td>{{ $sach->updated_at->format('d/m/Y H:i') }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Đường dẫn:</td>
                                    <td>
                                        <code>{{ $sach->duong_dan }}</code>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pricing Information -->
            <div class="card card-modern mb-4">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-dollar-sign me-2"></i>
                        Thông tin giá bán
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="text-center p-3 bg-light rounded">
                                <h6 class="text-muted mb-1">Giá bán</h6>
                                <h4 class="fw-bold text-primary mb-0">
                                    {{ number_format($sach->gia_ban, 0, ',', '.') }}đ
                                </h4>
                            </div>
                        </div>
                        @if($sach->gia_khuyen_mai)
                            <div class="col-md-4">
                                <div class="text-center p-3 bg-light rounded">
                                    <h6 class="text-muted mb-1">Giá khuyến mãi</h6>
                                    <h4 class="fw-bold text-danger mb-0">
                                        {{ number_format($sach->gia_khuyen_mai, 0, ',', '.') }}đ
                                    </h4>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="text-center p-3 bg-light rounded">
                                    <h6 class="text-muted mb-1">Tiết kiệm</h6>
                                    <h4 class="fw-bold text-success mb-0">
                                        {{ number_format($sach->gia_ban - $sach->gia_khuyen_mai, 0, ',', '.') }}đ
                                    </h4>
                                    <small class="text-muted">
                                        ({{ round((($sach->gia_ban - $sach->gia_khuyen_mai) / $sach->gia_ban) * 100) }}%)
                                    </small>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Description -->
            <div class="card card-modern mb-4">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-align-left me-2"></i>
                        Mô tả sách
                    </h5>
                </div>
                <div class="card-body">
                    <div class="text-justify">
                        {!! nl2br(e($sach->mo_ta)) !!}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Row -->
    <div class="row mt-4">
        <!-- Sales Statistics -->
        <div class="col-md-6">
            <div class="card card-modern">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-chart-bar me-2"></i>
                        Thống kê bán hàng
                    </h5>
                </div>
                <div class="card-body">
                    @php
                        $totalSold = $sach->chiTietDonHangs ? $sach->chiTietDonHangs->sum('so_luong') : 0;
                        $totalRevenue = $sach->chiTietDonHangs ? $sach->chiTietDonHangs->sum('thanh_tien') : 0;
                        $totalOrders = $sach->chiTietDonHangs ? $sach->chiTietDonHangs->count() : 0;
                    @endphp
                    
                    <div class="row text-center">
                        <div class="col-4">
                            <div class="p-2">
                                <h4 class="fw-bold text-primary mb-1">{{ $totalSold }}</h4>
                                <small class="text-muted">Đã bán</small>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="p-2">
                                <h4 class="fw-bold text-success mb-1">{{ $totalOrders }}</h4>
                                <small class="text-muted">Đơn hàng</small>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="p-2">
                                <h4 class="fw-bold text-info mb-1">
                                    {{ number_format($totalRevenue / 1000000, 1) }}M
                                </h4>
                                <small class="text-muted">Doanh thu</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Reviews Summary -->
        <div class="col-md-6">
            <div class="card card-modern">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-star me-2"></i>
                        Đánh giá khách hàng
                    </h5>
                </div>
                <div class="card-body">
                    @php
                        $reviews = $sach->danhGias;
                        $avgRating = $reviews->avg('diem_danh_gia') ?? 0;
                        $totalReviews = $reviews->count();
                    @endphp
                    
                    <div class="row text-center">
                        <div class="col-6">
                            <div class="p-2">
                                <h4 class="fw-bold text-warning mb-1">
                                    {{ number_format($avgRating, 1) }}/5
                                </h4>
                                <div class="mb-1">
                                    @for($i = 1; $i <= 5; $i++)
                                        <i class="fas fa-star {{ $i <= $avgRating ? 'text-warning' : 'text-muted' }}"></i>
                                    @endfor
                                </div>
                                <small class="text-muted">Điểm trung bình</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="p-2">
                                <h4 class="fw-bold text-info mb-1">{{ $totalReviews }}</h4>
                                <small class="text-muted">Lượt đánh giá</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card card-modern">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-0">Thao tác nhanh</h6>
                            <small class="text-muted">Các hành động có thể thực hiện với sách này</small>
                        </div>
                        <div>
                            <a href="{{ route('admin.sach.edit', $sach->ma_sach) }}" class="btn btn-primary me-2">
                                <i class="fas fa-edit me-1"></i>
                                Chỉnh sửa
                            </a>
                            
                            @if($sach->trang_thai === 'active')
                                <button type="button" class="btn btn-warning me-2" 
                                        onclick="toggleStatus({{ $sach->ma_sach }})">
                                    <i class="fas fa-pause me-1"></i>
                                    Ngừng bán
                                </button>
                            @else
                                <button type="button" class="btn btn-success me-2" 
                                        onclick="toggleStatus({{ $sach->ma_sach }})">
                                    <i class="fas fa-play me-1"></i>
                                    Kích hoạt
                                </button>
                            @endif
                            
                            <button type="button" class="btn btn-danger" 
                                    onclick="confirmDelete('{{ route('admin.sach.destroy', $sach->ma_sach) }}')">
                                <i class="fas fa-trash me-1"></i>
                                Xóa sách
                            </button>
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
    function toggleStatus(bookId) {
        if (confirm('Bạn có chắc chắn muốn thay đổi trạng thái sách này?')) {
            window.location.href = `/admin/sach/${bookId}/toggle-status`;
        }
    }

    function confirmDelete(url) {
        if (confirm('Bạn có chắc chắn muốn xóa sách này? Hành động này không thể hoàn tác!')) {
            // Create form and submit
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = url;
            
            const csrfToken = document.createElement('input');
            csrfToken.type = 'hidden';
            csrfToken.name = '_token';
            csrfToken.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            
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