@extends('layouts.admin')

@section('title', 'Chi tiết thể loại: ' . $theLoai->ten_the_loai)

@section('content')
    <!-- Header -->
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; flex-wrap: wrap; gap: 1rem;">
        <div>
            <h1 style="font-size: 1.5rem; margin: 0; color: #1e293b; display: flex; align-items: center; gap: 0.5rem;">
                <i class="fas fa-eye"></i>Chi tiết thể loại
            </h1>
            <nav style="margin-top: 0.5rem;">
                <span style="color: #64748b; font-size: 0.9rem;">
                    <a href="{{ route('home') }}" style="color: #3b82f6; text-decoration: none;">Trang chủ</a>
                    <li class="breadcrumb-item"><a href="{{ route('admin.theloai.index') }}">Thể loại</a></li>
                    <li class="breadcrumb-item active">{{ $theLoai->ten_the_loai }}</li>
                </ol>
            </nav>
        </div>
        <div>
            <a href="{{ route('theloai.edit', $theLoai) }}" class="btn btn-warning me-2">
                <i class="fas fa-edit me-1"></i>Chỉnh sửa
            </a>
            <button type="button" class="btn btn-danger me-2" onclick="deleteCategory()">
                <i class="fas fa-trash me-1"></i>Xóa
            </button>
            <a href="{{ route('admin.theloai.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-1"></i>Quay lại
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <!-- Category Information -->
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">Thông tin thể loại</h6>
                    <div>
                        <span class="badge {{ $theLoai->trang_thai ? 'bg-success' : 'bg-danger' }}">
                            {{ $theLoai->trang_thai ? 'Hoạt động' : 'Không hoạt động' }}
                        </span>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        @if($theLoai->hinh_anh)
                            <div class="col-md-3 mb-3">
                                <img src="{{ Storage::url($theLoai->hinh_anh) }}" 
                                     alt="{{ $theLoai->ten_the_loai }}" 
                                     class="img-fluid rounded shadow-sm">
                            </div>
                            <div class="col-md-9">
                        @else
                            <div class="col-md-12">
                        @endif
                            <table class="table table-borderless">
                                <tr>
                                    <td width="150" class="fw-bold text-muted">Tên thể loại:</td>
                                    <td>{{ $theLoai->ten_the_loai }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-bold text-muted">Đường dẫn:</td>
                                    <td><code>{{ $theLoai->duong_dan }}</code></td>
                                </tr>
                                <tr>
                                    <td class="fw-bold text-muted">Thể loại cha:</td>
                                    <td>
                                        @if($theLoai->parent)
                                            <a href="{{ route('theloai.show', $theLoai->parent) }}" 
                                               class="badge bg-info text-decoration-none">
                                                {{ $theLoai->parent->ten_the_loai }}
                                            </a>
                                        @else
                                            <span class="text-muted">Thể loại gốc</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td class="fw-bold text-muted">Thứ tự hiển thị:</td>
                                    <td>{{ $theLoai->thu_tu_hien_thi ?? 0 }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-bold text-muted">Ngày tạo:</td>
                                    <td>{{ $stats['created_date'] }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-bold text-muted">Cập nhật cuối:</td>
                                    <td>{{ $stats['updated_date'] }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    @if($theLoai->mo_ta)
                        <div class="mt-3">
                            <h6 class="fw-bold text-muted">Mô tả:</h6>
                            <p class="text-justify">{{ $theLoai->mo_ta }}</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Child Categories -->
            @if($theLoai->children->count() > 0)
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-success">
                            Thể loại con ({{ $theLoai->children->count() }})
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            @foreach($theLoai->children as $child)
                                <div class="col-md-6 mb-3">
                                    <div class="card border-left-success">
                                        <div class="card-body py-2">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div>
                                                    <h6 class="mb-1">
                                                        <a href="{{ route('theloai.show', $child) }}" 
                                                           class="text-decoration-none">
                                                            {{ $child->ten_the_loai }}
                                                        </a>
                                                    </h6>
                                                    <small class="text-muted">
                                                        {{ $child->sach->count() }} sách
                                                    </small>
                                                </div>
                                                <div>
                                                    <span class="badge {{ $child->trang_thai ? 'bg-success' : 'bg-secondary' }}">
                                                        {{ $child->trang_thai ? 'Hoạt động' : 'Tạm dừng' }}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif

            <!-- Books in Category -->
            @if($theLoai->sach->count() > 0)
                <div class="card shadow mb-4">
                    <div class="card-header py-3 d-flex justify-content-between align-items-center">
                        <h6 class="m-0 font-weight-bold text-info">
                            Sách trong thể loại ({{ $stats['total_books'] }})
                        </h6>
                        <a href="{{ route('sach.index', ['the_loai' => $theLoai->ma_the_loai]) }}" 
                           class="btn btn-sm btn-primary">
                            Xem tất cả
                        </a>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            @foreach($theLoai->sach->take(6) as $sach)
                                <div class="col-md-4 mb-3">
                                    <div class="card h-100">
                                        @if($sach->hinh_anh)
                                            <img src="{{ Storage::url($sach->hinh_anh) }}" 
                                                 class="card-img-top" alt="{{ $sach->ten_sach }}"
                                                 style="height: 200px; object-fit: cover;">
                                        @else
                                            <div class="card-img-top bg-light d-flex align-items-center justify-content-center" 
                                                 style="height: 200px;">
                                                <i class="fas fa-book fa-3x text-muted"></i>
                                            </div>
                                        @endif
                                        <div class="card-body">
                                            <h6 class="card-title">
                                                <a href="{{ route('sach.show', $sach) }}" 
                                                   class="text-decoration-none">
                                                    {{ Str::limit($sach->ten_sach, 50) }}
                                                </a>
                                            </h6>
                                            <p class="card-text">
                                                <small class="text-muted">
                                                    {{ $sach->tacGia->ten_tac_gia ?? 'Chưa có tác giả' }}
                                                </small>
                                            </p>
                                            <div class="d-flex justify-content-between align-items-center">
                                                <span class="text-primary fw-bold">
                                                    {{ number_format($sach->gia_ban) }}đ
                                                </span>
                                                <span class="badge {{ $sach->trang_thai ? 'bg-success' : 'bg-secondary' }}">
                                                    {{ $sach->trang_thai ? 'Có sẵn' : 'Hết hàng' }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif
        </div>

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
                                <h3 class="text-primary mb-0">{{ $stats['total_books'] }}</h3>
                                <small class="text-muted">Tổng số sách</small>
                            </div>
                        </div>
                        <div class="col-6 mb-3">
                            <h3 class="text-success mb-0">{{ $stats['active_books'] }}</h3>
                            <small class="text-muted">Sách đang bán</small>
                        </div>
                        <div class="col-6">
                            <div class="border-end">
                                <h3 class="text-info mb-0">{{ $stats['total_children'] }}</h3>
                                <small class="text-muted">Thể loại con</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <h3 class="text-warning mb-0">{{ $theLoai->thu_tu_hien_thi ?? 0 }}</h3>
                            <small class="text-muted">Thứ tự hiển thị</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- SEO Information -->
            @if($theLoai->meta_title || $theLoai->meta_description || $theLoai->meta_keywords)
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-success">Thông tin SEO</h6>
                    </div>
                    <div class="card-body">
                        @if($theLoai->meta_title)
                            <div class="mb-3">
                                <strong class="text-muted">Meta Title:</strong>
                                <p class="mb-0">{{ $theLoai->meta_title }}</p>
                            </div>
                        @endif

                        @if($theLoai->meta_description)
                            <div class="mb-3">
                                <strong class="text-muted">Meta Description:</strong>
                                <p class="mb-0">{{ $theLoai->meta_description }}</p>
                            </div>
                        @endif

                        @if($theLoai->meta_keywords)
                            <div class="mb-0">
                                <strong class="text-muted">Meta Keywords:</strong>
                                <p class="mb-0">
                                    @foreach(explode(',', $theLoai->meta_keywords) as $keyword)
                                        <span class="badge bg-light text-dark me-1">{{ trim($keyword) }}</span>
                                    @endforeach
                                </p>
                            </div>
                        @endif
                    </div>
                </div>
            @endif

            <!-- Quick Actions -->
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-warning">Thao tác nhanh</h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('theloai.edit', $theLoai) }}" class="btn btn-warning">
                            <i class="fas fa-edit me-2"></i>Chỉnh sửa thể loại
                        </a>
                        
                        <a href="{{ route('admin.theloai.create') }}?parent={{ $theLoai->ma_the_loai }}" 
                           class="btn btn-info">
                            <i class="fas fa-plus me-2"></i>Thêm thể loại con
                        </a>
                        
                        <a href="{{ route('admin.sach.create') }}?category={{ $theLoai->ma_the_loai }}" 
                           class="btn btn-success">
                            <i class="fas fa-book me-2"></i>Thêm sách vào thể loại
                        </a>
                        
                        <button type="button" class="btn btn-outline-primary" onclick="toggleStatus()">
                            <i class="fas fa-toggle-{{ $theLoai->trang_thai ? 'on' : 'off' }} me-2"></i>
                            {{ $theLoai->trang_thai ? 'Vô hiệu hóa' : 'Kích hoạt' }}
                        </button>
                        
                        <hr>
                        
                        <button type="button" class="btn btn-danger" onclick="deleteCategory()">
                            <i class="fas fa-trash me-2"></i>Xóa thể loại
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Form -->
<form id="deleteForm" method="POST" action="{{ route('theloai.destroy', $theLoai) }}" style="display: none;">
    @csrf
    @method('DELETE')
</form>
@endsection

@push('styles')
<style>
.border-left-success {
    border-left: 0.25rem solid #1cc88a !important;
}

.border-end {
    border-right: 1px solid #e3e6f0 !important;
}

.card-img-top {
    transition: transform 0.3s ease;
}

.card:hover .card-img-top {
    transform: scale(1.05);
}

.table-borderless td {
    border: none;
    padding: 0.5rem 0;
}

.badge {
    font-size: 0.75em;
}
</style>
@endpush

@push('scripts')
<script>
function deleteCategory() {
    Swal.fire({
        title: 'Xác nhận xóa',
        text: 'Bạn có chắc chắn muốn xóa thể loại "{{ $theLoai->ten_the_loai }}"?',
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

function toggleStatus() {
    const currentStatus = {{ $theLoai->trang_thai ? 'true' : 'false' }};
    const action = currentStatus ? 'vô hiệu hóa' : 'kích hoạt';
    
    Swal.fire({
        title: `Xác nhận ${action}`,
        text: `Bạn có chắc chắn muốn ${action} thể loại này?`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#6c757d',
        confirmButtonText: action.charAt(0).toUpperCase() + action.slice(1),
        cancelButtonText: 'Hủy'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: `/admin/theloai/{{ $theLoai->ma_the_loai }}/toggle-status`,
                method: 'GET',
                success: function(response) {
                    if (response.success) {
                        showToast('success', response.message);
                        setTimeout(() => {
                            location.reload();
                        }, 1500);
                    } else {
                        showToast('error', response.message);
                    }
                },
                error: function() {
                    showToast('error', 'Có lỗi xảy ra khi cập nhật trạng thái');
                }
            });
        }
    });
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
</script>
@endpush