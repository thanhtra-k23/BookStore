@extends('layouts.app')

@section('title', $title)

@section('content')
<div class="container mt-4 mb-5">
    <!-- Header Section -->
    <div class="card card-modern mb-4">
        <div class="card-header">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <h4 class="mb-1 fw-bold">
                        <i class="fas fa-list-alt me-2"></i>
                        Quản lý chi tiết đơn hàng
                    </h4>
                    <p class="mb-0 text-muted">
                        Theo dõi và quản lý từng sản phẩm trong các đơn hàng
                    </p>
                </div>
                <div class="col-md-6 text-md-end">
                    <div class="btn-group">
                        <a href="{{ route('admin.chitietdonhang.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus me-2"></i>
                            Thêm sản phẩm
                        </a>
                        <a href="{{ route('admin.chitietdonhang.export', request()->query()) }}" class="btn btn-success">
                            <i class="fas fa-download me-2"></i>
                            Xuất Excel
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-md-3 col-6 mb-3">
            <div class="card card-modern text-center">
                <div class="card-body">
                    <i class="fas fa-shopping-bag text-primary fs-2 mb-2"></i>
                    <h5 class="fw-bold">{{ number_format($stats['total_items']) }}</h5>
                    <small class="text-muted">Tổng chi tiết</small>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-6 mb-3">
            <div class="card card-modern text-center">
                <div class="card-body">
                    <i class="fas fa-cubes text-success fs-2 mb-2"></i>
                    <h5 class="fw-bold">{{ number_format($stats['total_quantity']) }}</h5>
                    <small class="text-muted">Tổng số lượng</small>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-6 mb-3">
            <div class="card card-modern text-center">
                <div class="card-body">
                    <i class="fas fa-money-bill-wave text-warning fs-2 mb-2"></i>
                    <h5 class="fw-bold">{{ number_format($stats['total_revenue'], 0, ',', '.') }}đ</h5>
                    <small class="text-muted">Tổng doanh thu</small>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-6 mb-3">
            <div class="card card-modern text-center">
                <div class="card-body">
                    <i class="fas fa-book text-info fs-2 mb-2"></i>
                    <h5 class="fw-bold">{{ number_format($stats['unique_books']) }}</h5>
                    <small class="text-muted">Sách khác nhau</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters Section -->
    <div class="card card-modern mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.chitietdonhang.index') }}">
                <div class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label">Tìm kiếm</label>
                        <input type="text" name="search" class="form-control" 
                               placeholder="Mã đơn, tên sách..." 
                               value="{{ request('search') }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Đơn hàng</label>
                        <select name="don_hang_id" class="form-select">
                            <option value="">Tất cả đơn hàng</option>
                            @foreach($donHangs as $order)
                                <option value="{{ $order->id }}" 
                                        {{ request('don_hang_id') == $order->id ? 'selected' : '' }}>
                                    {{ $order->ma_don }} - {{ $order->nguoiDung->ho_ten }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Từ ngày</label>
                        <input type="date" name="tu_ngay" class="form-control" 
                               value="{{ request('tu_ngay') }}">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Đến ngày</label>
                        <input type="date" name="den_ngay" class="form-control" 
                               value="{{ request('den_ngay') }}">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">&nbsp;</label>
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-search me-1"></i>
                                Tìm
                            </button>
                            <a href="{{ route('admin.chitietdonhang.index') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-refresh me-1"></i>
                                Reset
                            </a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Order Details Table -->
    <div class="card card-modern">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h6 class="mb-0">
                Danh sách chi tiết đơn hàng ({{ $chiTietDonHang->total() }} kết quả)
            </h6>
            <button class="btn btn-sm btn-danger" onclick="bulkDelete()">
                <i class="fas fa-trash me-1"></i>
                Xóa đã chọn
            </button>
        </div>
        <div class="card-body">
            @if($chiTietDonHang->count() > 0)
                <form id="bulkForm" method="POST" action="{{ route('chitietdonhang.bulk-delete') }}">
                    @csrf
                    
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead>
                                <tr>
                                    <th width="50">
                                        <input type="checkbox" id="selectAll" class="form-check-input">
                                    </th>
                                    <th>Đơn hàng</th>
                                    <th>Sản phẩm</th>
                                    <th>Số lượng</th>
                                    <th>Giá bán</th>
                                    <th>Thành tiền</th>
                                    <th>Ngày tạo</th>
                                    <th width="120">Hành động</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($chiTietDonHang as $item)
                                    <tr>
                                        <td>
                                            <input type="checkbox" name="ids[]" value="{{ $item->id }}" 
                                                   class="form-check-input item-checkbox">
                                        </td>
                                        <td>
                                            <div>
                                                <div class="fw-bold">
                                                    <a href="{{ route('donhang.show', $item->donHang->id) }}" 
                                                       class="text-decoration-none">
                                                        {{ $item->donHang->ma_don }}
                                                    </a>
                                                </div>
                                                <small class="text-muted">
                                                    {{ $item->donHang->nguoiDung->ho_ten }}
                                                </small>
                                                <br>
                                                @php
                                                    $statusColors = [
                                                        'cho_xac_nhan' => 'warning',
                                                        'da_xac_nhan' => 'info',
                                                        'dang_giao' => 'primary',
                                                        'da_giao' => 'success',
                                                        'da_huy' => 'danger'
                                                    ];
                                                @endphp
                                                <span class="badge bg-{{ $statusColors[$item->donHang->trang_thai] ?? 'secondary' }} mt-1">
                                                    {{ $item->donHang->trang_thai_text }}
                                                </span>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <img src="{{ $item->sach->anh_bia_url }}" 
                                                     alt="{{ $item->sach->ten_sach }}"
                                                     class="rounded me-3"
                                                     style="width: 40px; height: 50px; object-fit: cover;">
                                                <div>
                                                    <div class="fw-bold">
                                                        <a href="{{ route('book.detail', [$item->sach->id, $item->sach->duong_dan]) }}" 
                                                           class="text-decoration-none text-dark">
                                                            {{ Str::limit($item->sach->ten_sach, 40) }}
                                                        </a>
                                                    </div>
                                                    <small class="text-muted">
                                                        <i class="fas fa-user me-1"></i>
                                                        {{ $item->sach->tacGia->ten_tac_gia }}
                                                    </small>
                                                    <br>
                                                    <small class="text-muted">
                                                        <i class="fas fa-tag me-1"></i>
                                                        {{ $item->sach->theLoai->ten_the_loai }}
                                                    </small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge bg-primary fs-6">
                                                {{ $item->so_luong }} cuốn
                                            </span>
                                        </td>
                                        <td>
                                            <div class="fw-bold">
                                                {{ number_format($item->gia_ban_tai_thoi_diem, 0, ',', '.') }}đ
                                            </div>
                                            @if($item->gia_ban_tai_thoi_diem != $item->sach->gia_hien_tai)
                                                <small class="text-muted">
                                                    Hiện tại: {{ number_format($item->sach->gia_hien_tai, 0, ',', '.') }}đ
                                                </small>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="fw-bold text-success">
                                                {{ number_format($item->thanh_tien, 0, ',', '.') }}đ
                                            </div>
                                        </td>
                                        <td>
                                            <div>{{ $item->created_at->format('d/m/Y') }}</div>
                                            <small class="text-muted">{{ $item->created_at->format('H:i') }}</small>
                                        </td>
                                        <td>
                                            <div class="btn-group btn-group-sm">
                                                <a href="{{ route('chitietdonhang.show', $item->id) }}" 
                                                   class="btn btn-outline-info" title="Xem chi tiết">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                @if($item->donHang->canCancel())
                                                    <a href="{{ route('chitietdonhang.edit', $item->id) }}" 
                                                       class="btn btn-outline-primary" title="Chỉnh sửa">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <button type="button" class="btn btn-outline-danger" 
                                                            onclick="confirmDelete('{{ route('chitietdonhang.destroy', $item->id) }}')" 
                                                            title="Xóa">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                @else
                                                    <button type="button" class="btn btn-outline-secondary" disabled title="Không thể chỉnh sửa">
                                                        <i class="fas fa-lock"></i>
                                                    </button>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </form>

                <!-- Pagination -->
                {{ $chiTietDonHang->appends(request()->query())->links() }}
            @else
                <div class="text-center py-5">
                    <i class="fas fa-list-alt fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">Không có chi tiết đơn hàng nào</h5>
                    <p class="text-muted">Chưa có chi tiết đơn hàng nào được tạo</p>
                    <a href="{{ route('admin.chitietdonhang.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>
                        Thêm chi tiết đầu tiên
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Select all checkbox
    document.getElementById('selectAll').addEventListener('change', function() {
        const checkboxes = document.querySelectorAll('.item-checkbox');
        checkboxes.forEach(checkbox => {
            checkbox.checked = this.checked;
        });
    });

    // Bulk delete
    function bulkDelete() {
        const checkedBoxes = document.querySelectorAll('.item-checkbox:checked');
        if (checkedBoxes.length === 0) {
            alert('Vui lòng chọn ít nhất một chi tiết đơn hàng');
            return;
        }

        if (confirm('Bạn có chắc chắn muốn xóa các chi tiết đơn hàng đã chọn? Hành động này không thể hoàn tác!')) {
            document.getElementById('bulkForm').submit();
        }
    }

    // Confirm delete single item
    function confirmDelete(url) {
        if (confirm('Bạn có chắc chắn muốn xóa chi tiết đơn hàng này?')) {
            // Create a form and submit
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