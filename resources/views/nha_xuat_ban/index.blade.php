@extends('layouts.admin')

@section('title', 'Quản lý nhà xuất bản')

@section('content')
    <!-- Header -->
    <div class="card mb-4">
        <div class="card-header">
            <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
                <div>
                    <h4 style="margin: 0; font-weight: 700; display: flex; align-items: center; gap: 0.5rem;">
                        <i class="fas fa-building"></i>
                        Quản lý nhà xuất bản
                    </h4>
                    <p style="margin: 0.25rem 0 0; color: #64748b; font-size: 0.9rem;">
                        Quản lý thông tin nhà xuất bản trong hệ thống
                    </p>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('admin.nhaxuatban.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Thêm NXB
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
                <div class="stat-icon primary"><i class="fas fa-building"></i></div>
                <div class="stat-info">
                    <h4>{{ $stats['total'] }}</h4>
                    <p>Tổng NXB</p>
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
                <div class="stat-icon warning"><i class="fas fa-book"></i></div>
                <div class="stat-info">
                    <h4>{{ $stats['with_books'] }}</h4>
                    <p>Có sách</p>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="quick-stat">
                <div class="stat-icon danger"><i class="fas fa-exclamation-triangle"></i></div>
                <div class="stat-info">
                    <h4>{{ $stats['without_books'] }}</h4>
                    <p>Chưa có sách</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="filter-card">
        <div class="filter-title">
            <i class="fas fa-filter"></i> Bộ lọc tìm kiếm
        </div>
        <form method="GET" action="{{ route('admin.nhaxuatban.index') }}" id="filterForm">
            <div class="row">
                <div class="col-md-3 mb-3">
                    <label class="form-label">Tìm kiếm</label>
                    <input type="text" class="form-control" name="search" 
                           value="{{ request('search') }}" placeholder="Tên NXB, địa chỉ, email...">
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
                    <label class="form-label">Quốc gia</label>
                    <input type="text" class="form-control" name="quoc_gia" 
                           value="{{ request('quoc_gia') }}" placeholder="Quốc gia">
                </div>
                <div class="col-md-2 mb-3">
                    <label class="form-label">Sắp xếp theo</label>
                    <select class="form-select" name="sort_by">
                        <option value="ten_nxb" {{ request('sort_by') === 'ten_nxb' ? 'selected' : '' }}>Tên NXB</option>
                        <option value="created_at" {{ request('sort_by') === 'created_at' ? 'selected' : '' }}>Ngày tạo</option>
                        <option value="nam_thanh_lap" {{ request('sort_by') === 'nam_thanh_lap' ? 'selected' : '' }}>Năm thành lập</option>
                    </select>
                </div>
                <div class="col-md-3 mb-3">
                    <label class="form-label">&nbsp;</label>
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-search"></i> Tìm kiếm
                        </button>
                        <a href="{{ route('admin.nhaxuatban.index') }}" class="btn btn-outline-secondary">
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
                <i class="fas fa-list me-2"></i>Danh sách NXB ({{ $nhaXuatBan->total() }} kết quả)
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
            @if($nhaXuatBan->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead>
                            <tr>
                                <th width="40">
                                    <input type="checkbox" id="selectAll" class="form-check-input">
                                </th>
                                <th width="60">Logo</th>
                                <th>Tên nhà xuất bản</th>
                                <th>Thông tin liên hệ</th>
                                <th>Quốc gia</th>
                                <th>Số sách</th>
                                <th width="100">Trạng thái</th>
                                <th width="140">Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($nhaXuatBan as $item)
                                <tr>
                                    <td>
                                        <input type="checkbox" class="form-check-input row-checkbox" 
                                               value="{{ $item->ma_nxb }}">
                                    </td>
                                    <td>
                                        @if($item->logo)
                                            <img src="{{ Storage::url($item->logo) }}" 
                                                 alt="{{ $item->ten_nxb }}" 
                                                 class="img-thumbnail" style="width: 45px; height: 45px; object-fit: cover;">
                                        @else
                                            <div style="width: 45px; height: 45px; background: linear-gradient(135deg, #3b82f6, #1d4ed8); border-radius: 8px; display: flex; align-items: center; justify-content: center; color: white;">
                                                <i class="fas fa-building"></i>
                                            </div>
                                        @endif
                                    </td>
                                    <td>
                                        <div>
                                            <strong>{{ $item->ten_nxb }}</strong>
                                            @if($item->nam_thanh_lap)
                                                <br><small class="text-muted">Thành lập: {{ $item->nam_thanh_lap }}</small>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        <div class="small text-muted">
                                            @if($item->so_dien_thoai)
                                                <div><i class="fas fa-phone me-1"></i>{{ $item->so_dien_thoai }}</div>
                                            @endif
                                            @if($item->email)
                                                <div><i class="fas fa-envelope me-1"></i>{{ $item->email }}</div>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        @if($item->quoc_gia)
                                            <span class="badge" style="background: #e0f2fe; color: #0891b2;">{{ $item->quoc_gia }}</span>
                                        @else
                                            <span class="text-muted">—</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge" style="background: #dbeafe; color: #2563eb;">{{ $item->sach->count() }} sách</span>
                                    </td>
                                    <td>
                                        <div class="form-check form-switch">
                                            <input class="form-check-input status-toggle" type="checkbox" 
                                                   data-id="{{ $item->ma_nxb }}"
                                                   {{ $item->trang_thai ? 'checked' : '' }}>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <a href="{{ route('admin.nhaxuatban.show', $item) }}" 
                                               class="btn btn-outline-info" title="Xem">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.nhaxuatban.edit', $item) }}" 
                                               class="btn btn-outline-primary" title="Sửa">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <button type="button" class="btn btn-outline-danger" 
                                                    onclick="deleteItem({{ $item->ma_nxb }})" title="Xóa">
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
                {{ $nhaXuatBan->appends(request()->query())->links() }}
            @else
                <div class="text-center py-5">
                    <i class="fas fa-building fa-3x text-muted mb-3" style="display: block;"></i>
                    <h5 class="text-muted">Không có nhà xuất bản nào</h5>
                    <p class="text-muted">Hãy thêm nhà xuất bản đầu tiên cho hệ thống</p>
                    <a href="{{ route('admin.nhaxuatban.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-1"></i>Thêm nhà xuất bản
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
<form id="bulkActionForm" method="POST" action="{{ route('admin.nhaxuatban.bulk-action') }}" style="display: none;">
    @csrf
    <input type="hidden" name="action" id="bulkAction">
    <input type="hidden" name="selected_ids" id="selectedIds">
</form>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    $('#selectAll').change(function() {
        $('.row-checkbox').prop('checked', $(this).prop('checked'));
        toggleBulkActions();
    });

    $('.row-checkbox').change(function() {
        toggleBulkActions();
        const totalCheckboxes = $('.row-checkbox').length;
        const checkedCheckboxes = $('.row-checkbox:checked').length;
        $('#selectAll').prop('checked', totalCheckboxes === checkedCheckboxes);
    });

    $('.status-toggle').change(function() {
        const id = $(this).data('id');
        $.ajax({
            url: `/admin/nhaxuatban/${id}/toggle-status`,
            method: 'GET',
            success: function(response) {
                if (response.success) {
                    showToast('success', response.message);
                }
            },
            error: function() {
                showToast('error', 'Có lỗi xảy ra');
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
        text: 'Bạn có chắc chắn muốn xóa nhà xuất bản này?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Xóa',
        cancelButtonText: 'Hủy'
    }).then((result) => {
        if (result.isConfirmed) {
            const form = $('#deleteForm');
            form.attr('action', `/admin/nhaxuatban/${id}`);
            form.submit();
        }
    });
}

function bulkAction(action) {
    const selectedIds = $('.row-checkbox:checked').map(function() {
        return $(this).val();
    }).get();

    if (selectedIds.length === 0) {
        showToast('warning', 'Vui lòng chọn ít nhất một nhà xuất bản');
        return;
    }

    Swal.fire({
        title: 'Xác nhận',
        text: `Bạn có chắc chắn muốn thực hiện hành động này với ${selectedIds.length} mục đã chọn?`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Xác nhận',
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
    window.location.href = `{{ route('admin.nhaxuatban.export') }}?${params.toString()}`;
}

function showToast(type, message) {
    Swal.fire({
        toast: true,
        position: 'top-end',
        icon: type,
        title: message,
        showConfirmButton: false,
        timer: 3000
    });
}
</script>
@endpush
