@extends('layouts.admin')

@section('title', 'Chi tiết nhà xuất bản')

@section('content')
    <!-- Header -->
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; flex-wrap: wrap; gap: 1rem;">
        <div>
            <h1 style="font-size: 1.5rem; margin: 0; color: #1e293b; display: flex; align-items: center; gap: 0.5rem;">
                <i class="fas fa-eye"></i>Chi tiết nhà xuất bản
            </h1>
            <nav style="margin-top: 0.5rem;">
                <span style="color: #64748b; font-size: 0.9rem;">
                    <a href="{{ route('home') }}" style="color: #3b82f6; text-decoration: none;">Trang chủ</a>
                    <li class="breadcrumb-item"><a href="{{ route('admin.nhaxuatban.index') }}">Nhà xuất bản</a></li>
                    <li class="breadcrumb-item active">{{ $nhaXuatBan->ten_nxb }}</li>
                </ol>
            </nav>
        </div>
        <div>
            <a href="{{ route('admin.nhaxuatban.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-1"></i>Quay lại
            </a>
            <a href="/admin/nhaxuatban/{{ $nhaXuatBan->ma_nxb }}/edit" class="btn btn-warning">
                <i class="fas fa-edit me-1"></i>Chỉnh sửa
            </a>
            <button type="button" class="btn btn-danger" onclick="deletePublisher()">
                <i class="fas fa-trash me-1"></i>Xóa
            </button>
        </div>
    </div>

    <div class="row">
        <!-- Publisher Information -->
        <div class="col-lg-8">
            <!-- Basic Info -->
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">Thông tin cơ bản</h6>
                    <div>
                        @if($nhaXuatBan->trang_thai)
                            <span class="badge bg-success">Đang hoạt động</span>
                        @else
                            <span class="badge bg-secondary">Không hoạt động</span>
                        @endif
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3 text-center mb-3">
                            @if($nhaXuatBan->logo)
                                <img src="{{ Storage::url($nhaXuatBan->logo) }}" 
                                     alt="{{ $nhaXuatBan->ten_nxb }}" 
                                     class="img-fluid rounded shadow" style="max-width: 150px;">
                            @else
                                <div class="bg-light d-flex align-items-center justify-content-center rounded shadow" 
                                     style="width: 150px; height: 150px; margin: 0 auto;">
                                    <i class="fas fa-building fa-3x text-muted"></i>
                                </div>
                            @endif
                        </div>
                        <div class="col-md-9">
                            <table class="table table-borderless">
                                <tr>
                                    <td width="150"><strong>Tên nhà xuất bản:</strong></td>
                                    <td>{{ $nhaXuatBan->ten_nxb }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Mã NXB:</strong></td>
                                    <td><code>{{ $nhaXuatBan->ma_nxb }}</code></td>
                                </tr>
                                <tr>
                                    <td><strong>Đường dẫn:</strong></td>
                                    <td><code>{{ $nhaXuatBan->duong_dan }}</code></td>
                                </tr>
                                @if($nhaXuatBan->nam_thanh_lap)
                                <tr>
                                    <td><strong>Năm thành lập:</strong></td>
                                    <td>{{ $nhaXuatBan->nam_thanh_lap }}</td>
                                </tr>
                                @endif
                                @if($nhaXuatBan->quoc_gia)
                                <tr>
                                    <td><strong>Quốc gia:</strong></td>
                                    <td>
                                        <span class="badge bg-info">{{ $nhaXuatBan->quoc_gia }}</span>
                                    </td>
                                </tr>
                                @endif
                            </table>
                        </div>
                    </div>
                    
                    @if($nhaXuatBan->mo_ta)
                    <div class="mt-3">
                        <h6 class="font-weight-bold">Mô tả:</h6>
                        <p class="text-muted">{{ $nhaXuatBan->mo_ta }}</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Contact Information -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Thông tin liên hệ</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            @if($nhaXuatBan->dia_chi)
                            <div class="mb-3">
                                <h6 class="font-weight-bold">
                                    <i class="fas fa-map-marker-alt text-danger me-2"></i>Địa chỉ
                                </h6>
                                <p class="text-muted mb-0">{{ $nhaXuatBan->dia_chi }}</p>
                            </div>
                            @endif

                            @if($nhaXuatBan->so_dien_thoai)
                            <div class="mb-3">
                                <h6 class="font-weight-bold">
                                    <i class="fas fa-phone text-success me-2"></i>Số điện thoại
                                </h6>
                                <p class="text-muted mb-0">{{ $nhaXuatBan->so_dien_thoai }}</p>
                            </div>
                            @endif
                        </div>
                        <div class="col-md-6">
                            @if($nhaXuatBan->email)
                            <div class="mb-3">
                                <h6 class="font-weight-bold">
                                    <i class="fas fa-envelope text-primary me-2"></i>Email
                                </h6>
                                <p class="text-muted mb-0">{{ $nhaXuatBan->email }}</p>
                            </div>
                            @endif

                            @if($nhaXuatBan->website)
                            <div class="mb-3">
                                <h6 class="font-weight-bold">
                                    <i class="fas fa-globe text-info me-2"></i>Website
                                </h6>
                                <p class="text-muted mb-0">
                                    <a href="{{ $nhaXuatBan->website }}" target="_blank" class="text-decoration-none">
                                        {{ $nhaXuatBan->website }}
                                        <i class="fas fa-external-link-alt ms-1"></i>
                                    </a>
                                </p>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Statistics -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Thống kê</h6>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-6 mb-3">
                            <div class="border-end">
                                <h4 class="text-primary">{{ $stats['total_books'] }}</h4>
                                <small class="text-muted">Tổng sách</small>
                            </div>
                        </div>
                        <div class="col-6 mb-3">
                            <h4 class="text-success">{{ $stats['active_books'] }}</h4>
                            <small class="text-muted">Sách hoạt động</small>
                        </div>
                        <div class="col-6">
                            <div class="border-end">
                                <h4 class="text-info">{{ number_format($stats['avg_price']) }}đ</h4>
                                <small class="text-muted">Giá trung bình</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <h4 class="text-warning">{{ number_format($stats['total_revenue']) }}đ</h4>
                            <small class="text-muted">Tổng giá trị</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- System Information -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-info">Thông tin hệ thống</h6>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled mb-0">
                        <li class="mb-2">
                            <strong>Ngày tạo:</strong><br>
                            <small class="text-muted">{{ $stats['created_date'] }}</small>
                        </li>
                        <li class="mb-2">
                            <strong>Cập nhật lần cuối:</strong><br>
                            <small class="text-muted">{{ $stats['updated_date'] }}</small>
                        </li>
                        <li class="mb-2">
                            <strong>Trạng thái:</strong><br>
                            @if($nhaXuatBan->trang_thai)
                                <span class="badge bg-success">Đang hoạt động</span>
                            @else
                                <span class="badge bg-secondary">Không hoạt động</span>
                            @endif
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-success">Thao tác nhanh</h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="/admin/nhaxuatban/{{ $nhaXuatBan->ma_nxb }}/edit" class="btn btn-warning">
                            <i class="fas fa-edit me-2"></i>Chỉnh sửa thông tin
                        </a>
                        <button type="button" class="btn btn-danger" onclick="deletePublisher()">
                            <i class="fas fa-trash me-2"></i>Xóa nhà xuất bản
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Form -->
<form id="deleteForm" method="POST" action="/admin/nhaxuatban/{{ $nhaXuatBan->ma_nxb }}" style="display: none;">
    @csrf
    @method('DELETE')
</form>
@endsection

@push('styles')
<style>
.card-header {
    background-color: #f8f9fc;
    border-bottom: 1px solid #e3e6f0;
}

.table th {
    border-top: none;
    font-weight: 600;
    background-color: #f8f9fc;
}

.border-end {
    border-right: 1px solid #e3e6f0 !important;
}
</style>
@endpush

@push('scripts')
<script>
function deletePublisher() {
    Swal.fire({
        title: 'Xác nhận xóa',
        text: 'Bạn có chắc chắn muốn xóa nhà xuất bản này? Hành động này không thể hoàn tác!',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Xóa',
        cancelButtonText: 'Hủy'
    }).then((result) => {
        if (result.isConfirmed) {
            $('#deleteForm').submit();
        }
    });
}
</script>
@endpush