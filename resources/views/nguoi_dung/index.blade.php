@extends('layouts.admin')

@section('title', 'Quản lý người dùng')

@section('content')
    <!-- Header -->
    <div class="card mb-4">
        <div class="card-header">
            <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
                <div>
                    <h4 style="margin: 0; font-weight: 700; display: flex; align-items: center; gap: 0.5rem;">
                        <i class="fas fa-users"></i>
                        Quản lý người dùng
                    </h4>
                    <p style="margin: 0.25rem 0 0; color: #64748b; font-size: 0.9rem;">
                        Quản lý tài khoản người dùng trong hệ thống
                    </p>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('admin.nguoidung.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Thêm người dùng
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="quick-stat">
                <div class="stat-icon primary"><i class="fas fa-users"></i></div>
                <div class="stat-info">
                    <h4>{{ $stats['total'] ?? $nguoiDung->total() }}</h4>
                    <p>Tổng người dùng</p>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="quick-stat">
                <div class="stat-icon success"><i class="fas fa-user-check"></i></div>
                <div class="stat-info">
                    <h4>{{ $stats['verified'] ?? 0 }}</h4>
                    <p>Đã xác thực</p>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="quick-stat">
                <div class="stat-icon warning"><i class="fas fa-user-shield"></i></div>
                <div class="stat-info">
                    <h4>{{ $stats['admins'] ?? 0 }}</h4>
                    <p>Quản trị viên</p>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="quick-stat">
                <div class="stat-icon danger"><i class="fas fa-user-clock"></i></div>
                <div class="stat-info">
                    <h4>{{ $stats['unverified'] ?? 0 }}</h4>
                    <p>Chưa xác thực</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="filter-card">
        <div class="filter-title">
            <i class="fas fa-filter"></i> Bộ lọc tìm kiếm
        </div>
        <form method="GET" action="{{ route('admin.nguoidung.index') }}">
            <div class="row">
                <div class="col-md-3 mb-3">
                    <label class="form-label">Tìm kiếm</label>
                    <input type="text" class="form-control" name="search" 
                           value="{{ request('search') }}" placeholder="Tên, email, SĐT...">
                </div>
                <div class="col-md-2 mb-3">
                    <label class="form-label">Vai trò</label>
                    <select class="form-select" name="vai_tro">
                        <option value="">Tất cả</option>
                        <option value="quan_tri" {{ request('vai_tro') === 'quan_tri' ? 'selected' : '' }}>Quản trị viên</option>
                        <option value="khach_hang" {{ request('vai_tro') === 'khach_hang' ? 'selected' : '' }}>Khách hàng</option>
                    </select>
                </div>
                <div class="col-md-2 mb-3">
                    <label class="form-label">Xác thực</label>
                    <select class="form-select" name="verified">
                        <option value="">Tất cả</option>
                        <option value="1" {{ request('verified') === '1' ? 'selected' : '' }}>Đã xác thực</option>
                        <option value="0" {{ request('verified') === '0' ? 'selected' : '' }}>Chưa xác thực</option>
                    </select>
                </div>
                <div class="col-md-2 mb-3">
                    <label class="form-label">Sắp xếp theo</label>
                    <select class="form-select" name="sort_by">
                        <option value="created_at" {{ request('sort_by') === 'created_at' ? 'selected' : '' }}>Ngày tạo</option>
                        <option value="ho_ten" {{ request('sort_by') === 'ho_ten' ? 'selected' : '' }}>Họ tên</option>
                        <option value="email" {{ request('sort_by') === 'email' ? 'selected' : '' }}>Email</option>
                    </select>
                </div>
                <div class="col-md-3 mb-3">
                    <label class="form-label">&nbsp;</label>
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-search"></i> Tìm kiếm
                        </button>
                        <a href="{{ route('admin.nguoidung.index') }}" class="btn btn-outline-secondary">
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
                <i class="fas fa-list me-2"></i>Danh sách người dùng ({{ $nguoiDung->total() }} kết quả)
            </h6>
            <div class="d-flex gap-2">
                <button type="button" class="btn btn-sm btn-danger" onclick="bulkAction('delete')" id="bulkDeleteBtn" style="display: none;">
                    <i class="fas fa-trash"></i> Xóa đã chọn
                </button>
            </div>
        </div>
        <div class="card-body">
            @if($nguoiDung->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead>
                            <tr>
                                <th width="40">
                                    <input type="checkbox" id="selectAll" class="form-check-input">
                                </th>
                                <th width="50">Ảnh</th>
                                <th>Họ tên</th>
                                <th>Email</th>
                                <th>Số điện thoại</th>
                                <th>Vai trò</th>
                                <th>Xác thực</th>
                                <th>Ngày tạo</th>
                                <th width="140">Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($nguoiDung as $item)
                                <tr>
                                    <td>
                                        <input type="checkbox" class="form-check-input row-checkbox" 
                                               value="{{ $item->id }}">
                                    </td>
                                    <td>
                                        @if($item->anh_dai_dien)
                                            <img src="{{ Storage::url($item->anh_dai_dien) }}" 
                                                 alt="{{ $item->ho_ten }}" 
                                                 style="width: 40px; height: 40px; object-fit: cover; border-radius: 50%;">
                                        @else
                                            <div style="width: 40px; height: 40px; background: linear-gradient(135deg, #667eea, #764ba2); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-weight: 600; font-size: 0.9rem;">
                                                {{ strtoupper(substr($item->ho_ten ?? 'U', 0, 1)) }}
                                            </div>
                                        @endif
                                    </td>
                                    <td>
                                        <strong>{{ $item->ho_ten }}</strong>
                                        @if($item->dia_chi)
                                            <br><small class="text-muted"><i class="fas fa-map-marker-alt me-1"></i>{{ Str::limit($item->dia_chi, 30) }}</small>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="mailto:{{ $item->email }}" class="text-decoration-none">{{ $item->email }}</a>
                                    </td>
                                    <td>
                                        @if($item->so_dien_thoai)
                                            {{ $item->so_dien_thoai }}
                                        @else
                                            <span class="text-muted">—</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($item->vai_tro == 'quan_tri')
                                            <span class="badge" style="background: #fef3c7; color: #d97706;">
                                                <i class="fas fa-shield-alt me-1"></i>Quản trị
                                            </span>
                                        @else
                                            <span class="badge" style="background: #dbeafe; color: #2563eb;">
                                                <i class="fas fa-user me-1"></i>Khách hàng
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($item->xac_minh_email_luc)
                                            <span class="badge" style="background: #dcfce7; color: #16a34a;">
                                                <i class="fas fa-check-circle me-1"></i>Đã xác thực
                                            </span>
                                        @else
                                            <span class="badge" style="background: #fee2e2; color: #dc2626;">
                                                <i class="fas fa-clock me-1"></i>Chưa xác thực
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        <small class="text-muted">{{ $item->created_at->format('d/m/Y') }}</small>
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <a href="{{ route('admin.nguoidung.show', $item) }}" 
                                               class="btn btn-outline-info" title="Xem">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.nguoidung.edit', $item) }}" 
                                               class="btn btn-outline-primary" title="Sửa">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            @if($item->id !== auth()->id())
                                            <button type="button" class="btn btn-outline-danger" 
                                                    onclick="deleteItem({{ $item->id }})" title="Xóa">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                {{ $nguoiDung->appends(request()->query())->links() }}
            @else
                <div class="text-center py-5">
                    <i class="fas fa-users fa-3x text-muted mb-3" style="display: block;"></i>
                    <h5 class="text-muted">Không có người dùng nào</h5>
                    <p class="text-muted">Hãy thêm người dùng đầu tiên cho hệ thống</p>
                    <a href="{{ route('admin.nguoidung.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-1"></i>Thêm người dùng
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
});

function toggleBulkActions() {
    const checkedCount = $('.row-checkbox:checked').length;
    if (checkedCount > 0) {
        $('#bulkDeleteBtn').show();
    } else {
        $('#bulkDeleteBtn').hide();
    }
}

function deleteItem(id) {
    Swal.fire({
        title: 'Xác nhận xóa',
        text: 'Bạn có chắc chắn muốn xóa người dùng này?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Xóa',
        cancelButtonText: 'Hủy'
    }).then((result) => {
        if (result.isConfirmed) {
            const form = $('#deleteForm');
            form.attr('action', `/admin/nguoidung/${id}`);
            form.submit();
        }
    });
}

function bulkAction(action) {
    const selectedIds = $('.row-checkbox:checked').map(function() {
        return $(this).val();
    }).get();

    if (selectedIds.length === 0) {
        Swal.fire('Thông báo', 'Vui lòng chọn ít nhất một người dùng', 'warning');
        return;
    }

    Swal.fire({
        title: 'Xác nhận xóa',
        text: `Bạn có chắc chắn muốn xóa ${selectedIds.length} người dùng đã chọn?`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Xóa',
        cancelButtonText: 'Hủy'
    }).then((result) => {
        if (result.isConfirmed) {
            // Submit bulk delete
            $.ajax({
                url: '{{ route("admin.nguoidung.bulk-action") }}',
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    action: action,
                    selected_ids: selectedIds
                },
                success: function(response) {
                    location.reload();
                },
                error: function() {
                    Swal.fire('Lỗi', 'Có lỗi xảy ra', 'error');
                }
            });
        }
    });
}
</script>
@endpush
