@extends('layouts.admin')

@section('title', 'Quản lý thể loại')

@section('content')
    <!-- Header -->
    <div class="card mb-4">
        <div class="card-header">
            <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
                <div>
                    <h4 style="margin: 0; font-weight: 700; display: flex; align-items: center; gap: 0.5rem;">
                        <i class="fas fa-tags"></i>
                        Quản lý thể loại
                    </h4>
                    <p style="margin: 0.25rem 0 0; color: #64748b; font-size: 0.9rem;">
                        Quản lý danh mục thể loại sách trong hệ thống
                    </p>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('admin.theloai.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Thêm thể loại
                    </a>
                    <button type="button" class="btn btn-success" onclick="exportData()">
                        <i class="fas fa-file-export"></i> Xuất Excel
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="quick-stat">
                <div class="stat-icon primary"><i class="fas fa-tags"></i></div>
                <div class="stat-info">
                    <h4>{{ $stats['total'] }}</h4>
                    <p>Tổng thể loại</p>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="quick-stat">
                <div class="stat-icon success"><i class="fas fa-check-circle"></i></div>
                <div class="stat-info">
                    <h4>{{ $stats['active'] }}</h4>
                    <p>Đang hoạt động</p>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="quick-stat">
                <div class="stat-icon warning"><i class="fas fa-layer-group"></i></div>
                <div class="stat-info">
                    <h4>{{ $stats['parent_categories'] }}</h4>
                    <p>Thể loại cha</p>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="quick-stat">
                <div class="stat-icon danger"><i class="fas fa-sitemap"></i></div>
                <div class="stat-info">
                    <h4>{{ $stats['child_categories'] }}</h4>
                    <p>Thể loại con</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="filter-card">
        <div class="filter-title">
            <i class="fas fa-filter"></i> Bộ lọc tìm kiếm
        </div>
        <form method="GET" action="{{ route('admin.theloai.index') }}" id="filterForm">
            <div class="row">
                <div class="col-md-3 mb-3">
                    <label class="form-label">Tìm kiếm</label>
                    <input type="text" class="form-control" name="search" 
                           value="{{ request('search') }}" placeholder="Tên thể loại, mô tả...">
                </div>
                <div class="col-md-2 mb-3">
                    <label class="form-label">Thể loại cha</label>
                    <select class="form-select" name="parent_id">
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
                    <label class="form-label">Trạng thái</label>
                    <select class="form-select" name="status">
                        <option value="">Tất cả</option>
                        <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Hoạt động</option>
                        <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Không hoạt động</option>
                    </select>
                </div>
                <div class="col-md-2 mb-3">
                    <label class="form-label">Sắp xếp theo</label>
                    <select class="form-select" name="sort_by">
                        <option value="ten_the_loai" {{ request('sort_by') === 'ten_the_loai' ? 'selected' : '' }}>Tên</option>
                        <option value="created_at" {{ request('sort_by') === 'created_at' ? 'selected' : '' }}>Ngày tạo</option>
                        <option value="updated_at" {{ request('sort_by') === 'updated_at' ? 'selected' : '' }}>Ngày cập nhật</option>
                    </select>
                </div>
                <div class="col-md-3 mb-3">
                    <label class="form-label">&nbsp;</label>
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-search"></i> Tìm kiếm
                        </button>
                        <a href="{{ route('admin.theloai.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-undo"></i> Đặt lại
                        </a>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <!-- Data Table -->
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h6 class="card-title m-0">
                <i class="fas fa-list me-2"></i>Danh sách thể loại ({{ $theLoai->total() }} kết quả)
            </h6>
            <div class="d-flex gap-2">
                <button type="button" class="btn btn-sm btn-danger" onclick="bulkAction('delete')" id="bulkDeleteBtn" style="display: none;">
                    <i class="fas fa-trash"></i> Xóa đã chọn
                </button>
                <button type="button" class="btn btn-sm btn-success" onclick="bulkAction('activate')" id="bulkActivateBtn" style="display: none;">
                    <i class="fas fa-check"></i> Kích hoạt
                </button>
                <button type="button" class="btn btn-sm btn-warning" onclick="bulkAction('deactivate')" id="bulkDeactivateBtn" style="display: none;">
                    <i class="fas fa-times"></i> Vô hiệu hóa
                </button>
            </div>
        </div>
        <div class="card-body">
            @if($theLoai->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead>
                            <tr>
                                <th width="40">
                                    <input type="checkbox" id="selectAll" class="form-check-input">
                                </th>
                                <th width="60">Ảnh</th>
                                <th>Tên thể loại</th>
                                <th>Thể loại cha</th>
                                <th>Số sách</th>
                                <th width="100">Trạng thái</th>
                                <th width="120">Ngày tạo</th>
                                <th width="140">Thao tác</th>
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
                                                 class="img-thumbnail" style="width: 45px; height: 45px; object-fit: cover;">
                                        @else
                                            <div style="width: 45px; height: 45px; background: #f1f5f9; border-radius: 8px; display: flex; align-items: center; justify-content: center;">
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
                                            <span class="badge" style="background: #e0f2fe; color: #0891b2;">{{ $item->theLoaiCha->ten_the_loai }}</span>
                                        @else
                                            <span class="text-muted">—</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge" style="background: #dbeafe; color: #2563eb;">{{ $item->sach ? $item->sach->count() : 0 }} sách</span>
                                    </td>
                                    <td>
                                        <div class="form-check form-switch">
                                            <input class="form-check-input status-toggle" type="checkbox" 
                                                   data-id="{{ $item->ma_the_loai }}"
                                                   {{ $item->trang_thai ? 'checked' : '' }}>
                                        </div>
                                    </td>
                                    <td>
                                        <small class="text-muted">{{ $item->created_at->format('d/m/Y') }}</small>
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <a href="{{ route('admin.theloai.show', $item) }}" 
                                               class="btn btn-outline-info" title="Xem">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.theloai.edit', $item) }}" 
                                               class="btn btn-outline-primary" title="Sửa">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <button type="button" class="btn btn-outline-danger" 
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
                    <i class="fas fa-tags fa-3x text-muted mb-3" style="display: block;"></i>
                    <h5 class="text-muted">Không có thể loại nào</h5>
                    <p class="text-muted">Hãy thêm thể loại đầu tiên cho hệ thống</p>
                    <a href="{{ route('admin.theloai.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-1"></i>Thêm thể loại
                    </a>
                </div>
            @endif
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