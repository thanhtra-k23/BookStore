@extends('layouts.app')

@section('title', $title)

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <!-- Header Card -->
            <div class="card card-modern mb-4">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col-md-6">
                            <h4 class="mb-1 fw-bold">
                                <i class="fas fa-tag me-2"></i>
                                Chi tiết mã giảm giá
                            </h4>
                            <p class="mb-0 text-muted">
                                Thông tin chi tiết về mã giảm giá
                            </p>
                        </div>
                        <div class="col-md-6 text-md-end">
                            <a href="{{ route('admin.magiamgia.index') }}" class="btn btn-outline-secondary me-2">
                                <i class="fas fa-arrow-left me-2"></i>
                                Quay lại danh sách
                            </a>
                            <a href="{{ route('admin.magiamgia.edit', $maGiamGia->ma_giam_gia) }}" class="btn btn-primary">
                                <i class="fas fa-edit me-2"></i>
                                Chỉnh sửa
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <!-- Left Column - Main Info -->
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
                                            <td class="fw-bold" width="40%">Tên mã giảm giá:</td>
                                            <td>{{ $maGiamGia->ten_ma_giam_gia }}</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold">Mã code:</td>
                                            <td>
                                                <code class="fs-5 text-primary">{{ $maGiamGia->ma_code }}</code>
                                                <button class="btn btn-sm btn-outline-secondary ms-2" onclick="copyToClipboard('{{ $maGiamGia->ma_code }}')">
                                                    <i class="fas fa-copy"></i>
                                                </button>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold">Loại giảm giá:</td>
                                            <td>
                                                <span class="badge {{ $maGiamGia->loai_giam_gia == 'phan_tram' ? 'bg-info' : 'bg-success' }} fs-6">
                                                    {{ $maGiamGia->loai_giam_gia_text }}
                                                </span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold">Giá trị giảm:</td>
                                            <td class="text-danger fw-bold fs-5">{{ $maGiamGia->gia_tri_giam_text }}</td>
                                        </tr>
                                    </table>
                                </div>
                                <div class="col-md-6">
                                    <table class="table table-borderless">
                                        <tr>
                                            <td class="fw-bold" width="40%">Đơn hàng tối thiểu:</td>
                                            <td>{{ number_format($maGiamGia->gia_tri_don_hang_toi_thieu, 0, ',', '.') }}đ</td>
                                        </tr>
                                        @if($maGiamGia->gia_tri_giam_toi_da)
                                        <tr>
                                            <td class="fw-bold">Giảm tối đa:</td>
                                            <td>{{ number_format($maGiamGia->gia_tri_giam_toi_da, 0, ',', '.') }}đ</td>
                                        </tr>
                                        @endif
                                        <tr>
                                            <td class="fw-bold">Số lượng:</td>
                                            <td>
                                                @if($maGiamGia->so_luong)
                                                    {{ $maGiamGia->da_su_dung }}/{{ $maGiamGia->so_luong }}
                                                    <small class="text-muted">(còn {{ $maGiamGia->so_luong_con_lai }})</small>
                                                @else
                                                    <span class="text-success">Không giới hạn</span>
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold">Giới hạn mỗi user:</td>
                                            <td>{{ $maGiamGia->gioi_han_su_dung_moi_user ?? 1 }} lần</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>

                            @if($maGiamGia->mo_ta)
                            <div class="mt-3">
                                <h6 class="fw-bold">Mô tả:</h6>
                                <p class="text-muted">{{ $maGiamGia->mo_ta }}</p>
                            </div>
                            @endif
                        </div>
                    </div>

                    <!-- Time Information -->
                    <div class="card card-modern mb-4">
                        <div class="card-header">
                            <h5 class="mb-0">
                                <i class="fas fa-clock me-2"></i>
                                Thời gian hiệu lực
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="text-center p-3 bg-light rounded">
                                        <h6 class="text-muted mb-1">Ngày bắt đầu</h6>
                                        <h5 class="fw-bold text-success mb-0">
                                            {{ $maGiamGia->ngay_bat_dau->format('d/m/Y H:i') }}
                                        </h5>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="text-center p-3 bg-light rounded">
                                        <h6 class="text-muted mb-1">Ngày kết thúc</h6>
                                        <h5 class="fw-bold text-danger mb-0">
                                            {{ $maGiamGia->ngay_ket_thuc->format('d/m/Y H:i') }}
                                        </h5>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-3 text-center">
                                @if($maGiamGia->isNotStarted())
                                    <span class="badge bg-warning fs-6">
                                        <i class="fas fa-clock me-1"></i>
                                        Chưa bắt đầu
                                    </span>
                                @elseif($maGiamGia->isExpired())
                                    <span class="badge bg-danger fs-6">
                                        <i class="fas fa-times me-1"></i>
                                        Đã hết hạn
                                    </span>
                                @else
                                    <span class="badge bg-success fs-6">
                                        <i class="fas fa-check me-1"></i>
                                        Đang hiệu lực
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Usage History -->
                    @if($maGiamGia->donHangs->count() > 0)
                    <div class="card card-modern">
                        <div class="card-header">
                            <h5 class="mb-0">
                                <i class="fas fa-history me-2"></i>
                                Lịch sử sử dụng
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-sm">
                                    <thead>
                                        <tr>
                                            <th>Đơn hàng</th>
                                            <th>Khách hàng</th>
                                            <th>Giá trị đơn</th>
                                            <th>Giảm giá</th>
                                            <th>Ngày sử dụng</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($maGiamGia->donHangs->take(10) as $donHang)
                                        <tr>
                                            <td>
                                                <a href="{{ route('admin.donhang.show', $donHang->ma_don_hang) }}">
                                                    #{{ $donHang->ma_don_hang }}
                                                </a>
                                            </td>
                                            <td>{{ $donHang->ten_nguoi_nhan }}</td>
                                            <td>{{ number_format($donHang->tong_tien, 0, ',', '.') }}đ</td>
                                            <td class="text-success">
                                                -{{ number_format($donHang->giam_gia, 0, ',', '.') }}đ
                                            </td>
                                            <td>{{ $donHang->created_at->format('d/m/Y H:i') }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            
                            @if($maGiamGia->donHangs->count() > 10)
                            <div class="text-center mt-3">
                                <small class="text-muted">
                                    Hiển thị 10/{{ $maGiamGia->donHangs->count() }} đơn hàng gần nhất
                                </small>
                            </div>
                            @endif
                        </div>
                    </div>
                    @endif
                </div>

                <!-- Right Column - Status & Actions -->
                <div class="col-md-4">
                    <!-- Status Card -->
                    <div class="card card-modern mb-4">
                        <div class="card-header">
                            <h5 class="mb-0">
                                <i class="fas fa-info-circle me-2"></i>
                                Trạng thái
                            </h5>
                        </div>
                        <div class="card-body text-center">
                            <div class="mb-3">
                                <span class="badge {{ $maGiamGia->trang_thai ? 'bg-success' : 'bg-secondary' }} fs-6">
                                    {{ $maGiamGia->trang_thai ? 'Đang hoạt động' : 'Không hoạt động' }}
                                </span>
                            </div>

                            <div class="mb-3">
                                <h6 class="text-muted mb-1">Trạng thái hiện tại</h6>
                                <h5 class="fw-bold">{{ $maGiamGia->getStatusText() }}</h5>
                            </div>

                            @if($maGiamGia->so_luong)
                            <div class="progress mb-3">
                                @php
                                    $percentage = ($maGiamGia->da_su_dung / $maGiamGia->so_luong) * 100;
                                @endphp
                                <div class="progress-bar" style="width: {{ $percentage }}%"></div>
                            </div>
                            <small class="text-muted">
                                Đã sử dụng {{ number_format($percentage, 1) }}%
                            </small>
                            @endif
                        </div>
                    </div>

                    <!-- Statistics -->
                    <div class="card card-modern mb-4">
                        <div class="card-header">
                            <h5 class="mb-0">
                                <i class="fas fa-chart-bar me-2"></i>
                                Thống kê
                            </h5>
                        </div>
                        <div class="card-body">
                            @php
                                $totalRevenue = $maGiamGia->donHangs->sum('tong_tien');
                                $totalDiscount = $maGiamGia->donHangs->sum('giam_gia');
                                $avgOrderValue = $maGiamGia->donHangs->count() > 0 ? $totalRevenue / $maGiamGia->donHangs->count() : 0;
                            @endphp
                            
                            <div class="row text-center">
                                <div class="col-12 mb-3">
                                    <h6 class="text-muted mb-1">Tổng đơn hàng</h6>
                                    <h4 class="fw-bold text-primary">{{ $maGiamGia->da_su_dung }}</h4>
                                </div>
                                <div class="col-12 mb-3">
                                    <h6 class="text-muted mb-1">Doanh thu</h6>
                                    <h4 class="fw-bold text-success">
                                        {{ number_format($totalRevenue / 1000000, 1) }}M
                                    </h4>
                                </div>
                                <div class="col-12 mb-3">
                                    <h6 class="text-muted mb-1">Tổng giảm giá</h6>
                                    <h4 class="fw-bold text-danger">
                                        {{ number_format($totalDiscount / 1000000, 1) }}M
                                    </h4>
                                </div>
                                <div class="col-12">
                                    <h6 class="text-muted mb-1">Đơn hàng TB</h6>
                                    <h4 class="fw-bold text-info">
                                        {{ number_format($avgOrderValue / 1000, 0) }}K
                                    </h4>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="card card-modern">
                        <div class="card-header">
                            <h5 class="mb-0">
                                <i class="fas fa-cogs me-2"></i>
                                Thao tác
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="d-grid gap-2">
                                <a href="{{ route('admin.magiamgia.edit', $maGiamGia->ma_giam_gia) }}" class="btn btn-primary">
                                    <i class="fas fa-edit me-1"></i>
                                    Chỉnh sửa
                                </a>
                                
                                <a href="{{ route('admin.magiamgia.toggle-status', $maGiamGia->ma_giam_gia) }}" 
                                   class="btn {{ $maGiamGia->trang_thai ? 'btn-warning' : 'btn-success' }}">
                                    <i class="fas {{ $maGiamGia->trang_thai ? 'fa-pause' : 'fa-play' }} me-1"></i>
                                    {{ $maGiamGia->trang_thai ? 'Vô hiệu hóa' : 'Kích hoạt' }}
                                </a>
                                
                                @if($maGiamGia->donHangs->count() == 0)
                                <form action="{{ route('admin.magiamgia.destroy', $maGiamGia->ma_giam_gia) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger w-100" 
                                            onclick="return confirm('Bạn có chắc chắn muốn xóa mã giảm giá này?')">
                                        <i class="fas fa-trash me-1"></i>
                                        Xóa mã giảm giá
                                    </button>
                                </form>
                                @else
                                <button class="btn btn-danger w-100" disabled title="Không thể xóa mã đã được sử dụng">
                                    <i class="fas fa-trash me-1"></i>
                                    Không thể xóa
                                </button>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(function() {
        // Show success message
        const toast = document.createElement('div');
        toast.className = 'toast align-items-center text-white bg-success border-0 position-fixed top-0 end-0 m-3';
        toast.style.zIndex = '9999';
        toast.innerHTML = `
            <div class="d-flex">
                <div class="toast-body">
                    Đã sao chép mã: ${text}
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" onclick="this.parentElement.parentElement.remove()"></button>
            </div>
        `;
        document.body.appendChild(toast);
        
        setTimeout(() => {
            if (toast.parentElement) {
                toast.remove();
            }
        }, 3000);
    });
}
</script>
@endsection