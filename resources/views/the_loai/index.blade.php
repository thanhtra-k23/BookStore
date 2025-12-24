@extends('layouts.app')

@section('title', 'Quản lý thể loại')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-tags me-2"></i>Quản lý thể loại
            </h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Trang chủ</a></li>
                    <li class="breadcrumb-item active">Thể loại</li>
                </ol>
            </nav>
        </div>
        <div>
            <a href="{{ route('admin.theloai.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-1"></i>Thêm thể loại
            </a>
            <button type="button" class="btn btn-success" onclick="exportData()">
                <i class="fas fa-file-export me-1"></i>Xuất Excel
            </button>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Tổng số thể loại
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['total'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-tags fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Đang hoạt động
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['active'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Thể loại cha
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['parent_categories'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-layer-group fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Thể loại con
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['child_categories'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-sitemap fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Bộ lọc tìm kiếm</h6>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('admin.theloai.index') }}" id="filterForm">
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <label for="search" class="form-label">Tìm kiếm</label>
                        <input type="text" class="form-control" id="search" name="search" 
                               value="{{ request('search') }}" placeholder="Tên thể loại, mô tả...">
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="parent_id" class="form-label">Thể loại cha</label>
                        <select class="form-select" id="parent_id" name="parent_id">
                            <option value="">Tất cả</option>
                            <option value="null" {{ request('parent_id') === 'null' ? 'selected' : '' }}>Thể loại gốc</option>
                            @foreach($parentCategories as $parent)
                                <option value="{{ $parent->ma_the_loai }}" 
                                        {{ request('parent_id') == $parent->ma_the_loai ? 'selected' : '' }}>
                                    {{ $parent->ten_the_loai }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2 mb-3">
                        <label for="status" class="form-label">Trạng thái</label>
                        <select class="form-select" id="status" name="status">
                            <option value="">Tất cả</option>
                            <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Hoạt động</option>
                            <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Không hoạt động</option>
                        </select>
                    </div>
                    <div class="col-md-2 mb-3">
                        <label for="sort_by" class="form-label">Sắp xếp theo</label>
                        <select class="form-select" id="sort_by" name="sort_by">
                            <option value="ten_the_loai" {{ request('sort_by') === 'ten_the_loai' ? 'selected' : '' }}>Tên</option>
                            <option value="created_at" {{ request('sort_by') === 'created_at' ? 'selected' : '' }}>Ngày tạo</option>
                            <option value="updated_at" {{ request('sort_by') === 'updated_at' ? 'selected' : '' }}>Ngày cập nhật</option>
                        </select>
                    </div>
                    <div class="col-md-2 mb-3">
                        <label for="sort_order" class="form-label">Thứ tự</label>
                        <select class="form-select" id="sort_order" name="sort_order">
                            <option value="asc" {{ request('sort_order') === 'asc' ? 'selected' : '' }}>Tăng dần</option>
                            <option value="desc" {{ request('sort_order') === 'desc' ? 'selected' : '' }}>Giảm dần</option>
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary me-2">
                            <i class="fas fa-search me-1"></i>Tìm kiếm
                        </button>
                        <a href="{{ route('admin.theloai.index') }}" class="btn btn-secondary">
                            <i class="fas fa-undo me-1"></i>Đặt lại
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Data Table -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Danh sách thể loại</h6>
            <div>
                <button type="button" class="btn btn-sm btn-danger" onclick="bulkAction('delete')" id="bulkDeleteBtn" style="display: none;">
                    <i class="fas fa-trash me-1"></i>Xóa đã chọn
                </button>
                <button type="button" class="btn btn-sm btn-success" onclick="bulkAction('activate')" id="bulkActivateBtn" style="display: none;">
                    <i class="fas fa-check me-1"></i>Kích hoạt
                </button>
                <button type="button" class="btn btn-sm btn-warning" onclick="bulkAction('deactivate')" id="bulkDeactivateBtn" style="display: none;">
                    <i class="fas fa-times me-1"></i>Vô hiệu hóa
                </button>
            </div>
        </div>
        <div class="card-body">
            @if($theLoai->count() > 0)
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="table-light">
                            <tr>
                                <th width="30">
                                    <input type="checkbox" id="selectAll" class="form-check-input">
                                </th>
                                <th width="60">Hình ảnh</th>
                                <th>Tên thể loại</th>
                                <th>Thể loại cha</th>
                                <th>Số sách</th>
                                <th width="100">Trạng thái</th>
                                <th width="120">Ngày tạo</th>
                                <th width="150">Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($theLoai as $item)
                                <tr>
                                    <td>
                                        <input type="checkbox" class="form-check-input row-checkbox" 
                                               value="{{ $item->ma_the_loai }}">
                                    </td>
                                    <td>
                                        @if($item->hinh_anh)
                                            <img src="{{ Storage::url($item->hinh_anh) }}" 
                                                 alt="{{ $item->ten_the_loai }}" 
                                                 class="img-thumbnail" style="width: 50px; height: 50px; object-fit: cover;">
                                        @else
                                            <div class="bg-light d-flex align-items-center justify-content-center" 
                                                 style="width: 50px; height: 50px;">
                                                <i class="fas fa-image text-muted"></i>
                                            </div>
                                        @endif
                                    </td>
                                    <td>
                                        <div>
                                            <strong>{{ $item->ten_the_loai }}</strong>
                                            @if($item->mo_ta)
                                                <br><small class="text-muted">{{ Str::limit($item->mo_ta, 50) }}</small>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        @if($item->theLoaiCha)
                                            <span class="badge bg-info">{{ $item->theLoaiCha->ten_the_loai }}</span>
                                        @else
                                            <span class="text-muted">Thể loại gốc</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge bg-primary">{{ $item->sach ? $item->sach->count() : 0 }}</span>
                                    </td>
                                    <td>
                                        <div class="form-check form-switch">
                                            <input class="form-check-input status-toggle" type="checkbox" 
                                                   data-id="{{ $item->ma_the_loai }}"
                                                   {{ $item->trang_thai ? 'checked' : '' }}>
                                        </div>
                                    </td>
                                    <td>
                                        <small>{{ $item->created_at->format('d/m/Y H:i') }}</small>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('admin.theloai.show', $item) }}" 
                                               class="btn btn-sm btn-info" title="Xem chi tiết">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.theloai.edit', $item) }}" 
                                               class="btn btn-sm btn-warning" title="Chỉnh sửa">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <button type="button" class="btn btn-sm btn-danger" 
                                                    onclick="deleteItem({{ $item->ma_the_loai }})" title="Xóa">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                {{ $theLoai->appends(request()->query())->links() }}
            @else
                <div class="text-center py-5">
                    <i class="fas fa-tags fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">Không có thể loại nào</h5>
                    <p class="text-muted">Hãy thêm thể loại đầu tiên cho hệ thống</p>
                    <a href="{{ route('admin.theloai.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-1"></i>Thêm thể loại
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Delete Form -->
<form id="deleteForm" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>

<!-- Bulk Action Form -->
<form id="bulkActionForm" method="POST" action="{{ route('admin.theloai.bulk-action') }}" style="display: none;">
    @csrf
    <input type="hidden" name="action" id="bulkAction">
    <input type="hidden" name="selected_ids" id="selectedIds">
</form>
@endsection

@push('styles')
<style>
.status-toggle {
    cursor: pointer;
}
.table th {
    border-top: none;
    font-weight: 600;
    background-color: #f8f9fc;
}
.btn-group .btn {
    margin-right: 2px;
}
.btn-group .btn:last-child {
    margin-right: 0;
}
</style>
@endpush

@push('scripts')
<script>
$(document).ready(function() {
    // Select all checkbox
    $('#selectAll').change(function() {
        $('.row-checkbox').prop('checked', $(this).prop('checked'));
        toggleBulkActions();
    });

    // Individual checkbox
    $('.row-checkbox').change(function() {
        toggleBulkActions();
        
        // Update select all checkbox
        const totalCheckboxes = $('.row-checkbox').length;
        const checkedCheckboxes = $('.row-checkbox:checked').length;
        $('#selectAll').prop('checked', totalCheckboxes === checkedCheckboxes);
    });

    // Status toggle
    $('.status-toggle').change(function() {
        const id = $(this).data('id');
        const status = $(this).prop('checked');
        
        $.ajax({
            url: `/admin/theloai/${id}/toggle-status`,
            method: 'GET',
            success: function(response) {
                if (response.success) {
                    showToast('success', response.message);
                } else {
                    showToast('error', response.message);
                    // Revert checkbox state
                    $(this).prop('checked', !status);
                }
            },
            error: function() {
                showToast('error', 'Có lỗi xảy ra khi cập nhật trạng thái');
                // Revert checkbox state
                $(this).prop('checked', !status);
            }
        });
    });
});

function toggleBulkActions() {
    const checkedCount = $('.row-checkbox:checked').length;
    
    if (checkedCount > 0) {
        $('#bulkDeleteBtn, #bulkActivateBtn, #bulkDeactivateBtn').show();
    } else {
        $('#bulkDeleteBtn, #bulkActivateBtn, #bulkDeactivateBtn').hide();
    }
}

function deleteItem(id) {
    Swal.fire({
        title: 'Xác nhận xóa',
        text: 'Bạn có chắc chắn muốn xóa thể loại này?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Xóa',
        cancelButtonText: 'Hủy'
    }).then((result) => {
        if (result.isConfirmed) {
            const form = $('#deleteForm');
            form.attr('action', `/admin/theloai/${id}`);
            form.submit();
        }
    });
}

function bulkAction(action) {
    const selectedIds = $('.row-checkbox:checked').map(function() {
        return $(this).val();
    }).get();

    if (selectedIds.length === 0) {
        showToast('warning', 'Vui lòng chọn ít nhất một thể loại');
        return;
    }

    let title, text, confirmButtonText;
    
    switch (action) {
        case 'delete':
            title = 'Xác nhận xóa';
            text = `Bạn có chắc chắn muốn xóa ${selectedIds.length} thể loại đã chọn?`;
            confirmButtonText = 'Xóa';
            break;
        case 'activate':
            title = 'Xác nhận kích hoạt';
            text = `Bạn có chắc chắn muốn kích hoạt ${selectedIds.length} thể loại đã chọn?`;
            confirmButtonText = 'Kích hoạt';
            break;
        case 'deactivate':
            title = 'Xác nhận vô hiệu hóa';
            text = `Bạn có chắc chắn muốn vô hiệu hóa ${selectedIds.length} thể loại đã chọn?`;
            confirmButtonText = 'Vô hiệu hóa';
            break;
    }

    Swal.fire({
        title: title,
        text: text,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: confirmButtonText,
        cancelButtonText: 'Hủy'
    }).then((result) => {
        if (result.isConfirmed) {
            $('#bulkAction').val(action);
            $('#selectedIds').val(JSON.stringify(selectedIds));
            $('#bulkActionForm').submit();
        }
    });
}

function exportData() {
    const params = new URLSearchParams(window.location.search);
    params.append('export', '1');
    window.location.href = `{{ route('admin.theloai.export') }}?${params.toString()}`;
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