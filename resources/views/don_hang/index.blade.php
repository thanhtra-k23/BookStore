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
                        <i class="fas fa-shopping-cart me-2"></i>
                        Quản lý đơn hàng
                    </h4>
                    <p class="mb-0 text-muted">
                        Theo dõi và xử lý các đơn hàng của khách hàng
                    </p>
                </div>
                <div class="col-md-6 text-md-end">
                    <a href="{{ route('admin.donhang.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>
                        Tạo đơn hàng mới
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-md-2 col-6 mb-3">
            <div class="card card-modern text-center">
                <div class="card-body">
                    <i class="fas fa-list-alt text-primary fs-2 mb-2"></i>
                    <h5 class="fw-bold">{{ number_format($stats['total']) }}</h5>
                    <small class="text-muted">Tổng đơn</small>
                </div>
            </div>
        </div>
        <div class="col-md-2 col-6 mb-3">
            <div class="card card-modern text-center">
                <div class="card-body">
                    <i class="fas fa-clock text-warning fs-2 mb-2"></i>
                    <h5 class="fw-bold">{{ number_format($stats['pending']) }}</h5>
                    <small class="text-muted">Chờ xác nhận</small>
                </div>
            </div>
        </div>
        <div class="col-md-2 col-6 mb-3">
            <div class="card card-modern text-center">
                <div class="card-body">
                    <i class="fas fa-check text-info fs-2 mb-2"></i>
                    <h5 class="fw-bold">{{ number_format($stats['confirmed']) }}</h5>
                    <small class="text-muted">Đã xác nhận</small>
                </div>
            </div>
        </div>
        <div class="col-md-2 col-6 mb-3">
            <div class="card card-modern text-center">
                <div class="card-body">
                    <i class="fas fa-truck text-primary fs-2 mb-2"></i>
                    <h5 class="fw-bold">{{ number_format($stats['shipping']) }}</h5>
                    <small class="text-muted">Đang giao</small>
                </div>
            </div>
        </div>
        <div class="col-md-2 col-6 mb-3">
            <div class="card card-modern text-center">
                <div class="card-body">
                    <i class="fas fa-check-circle text-success fs-2 mb-2"></i>
                    <h5 class="fw-bold">{{ number_format($stats['completed']) }}</h5>
                    <small class="text-muted">Hoàn thành</small>
                </div>
            </div>
        </div>
        <div class="col-md-2 col-6 mb-3">
            <div class="card card-modern text-center">
                <div class="card-body">
                    <i class="fas fa-times-circle text-danger fs-2 mb-2"></i>
                    <h5 class="fw-bold">{{ number_format($stats['cancelled']) }}</h5>
                    <small class="text-muted">Đã hủy</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters Section -->
    <div class="card card-modern mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.donhang.index') }}">
                <div class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label">Tìm kiếm</label>
                        <input type="text" name="search" class="form-control" 
                               placeholder="Mã đơn, tên khách hàng..." 
                               value="{{ request('search') }}">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Trạng thái</label>
                        <select name="trang_thai" class="form-select">
                            <option value="">Tất cả</option>
                            <option value="cho_xac_nhan" {{ request('trang_thai') == 'cho_xac_nhan' ? 'selected' : '' }}>
                                Chờ xác nhận
                            </option>
                            <option value="da_xac_nhan" {{ request('trang_thai') == 'da_xac_nhan' ? 'selected' : '' }}>
                                Đã xác nhận
                            </option>
                            <option value="dang_giao" {{ request('trang_thai') == 'dang_giao' ? 'selected' : '' }}>
                                Đang giao
                            </option>
                            <option value="da_giao" {{ request('trang_thai') == 'da_giao' ? 'selected' : '' }}>
                                Đã giao
                            </option>
                            <option value="da_huy" {{ request('trang_thai') == 'da_huy' ? 'selected' : '' }}>
                                Đã hủy
                            </option>
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
                    <div class="col-md-3">
                        <label class="form-label">&nbsp;</label>
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-search me-1"></i>
                                Tìm kiếm
                            </button>
                            <a href="{{ route('admin.donhang.index') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-refresh me-1"></i>
                                Reset
                            </a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Orders Table -->
    <div class="card card-modern">
        <div class="card-header">
            <h6 class="mb-0">
                Danh sách đơn hàng ({{ $donHang->total() }} kết quả)
            </h6>
        </div>
        <div class="card-body">
            @if($donHang->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead>
                            <tr>
                                <th>Mã đơn</th>
                                <th>Khách hàng</th>
                                <th>Sản phẩm</th>
                                <th>Tổng tiền</th>
                                <th>Trạng thái</th>
                                <th>Ngày tạo</th>
                                <th width="150">Hành động</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($donHang as $order)
                                <tr>
                                    <td>
                                        <div class="fw-bold">{{ $order->ma_don }}</div>
                                        @if($order->maGiamGia)
                                            <small class="text-success">
                                                <i class="fas fa-tag me-1"></i>
                                                {{ $order->maGiamGia->ma_code }}
                                            </small>
                                        @endif
                                    </td>
                                    <td>
                                        <div>
                                            <div class="fw-bold">{{ $order->nguoiDung->ho_ten }}</div>
                                            <small class="text-muted">{{ $order->nguoiDung->email }}</small>
                                        </div>
                                    </td>
                                    <td>
                                        <div>
                                            <span class="badge bg-info">{{ $order->getTotalQuantity() }} sản phẩm</span>
                                            <div class="mt-1">
                                                @foreach($order->chiTiet->take(2) as $item)
                                                    <small class="text-muted d-block">
                                                        {{ Str::limit($item->sach->ten_sach, 30) }}
                                                    </small>
                                                @endforeach
                                                @if($order->chiTiet->count() > 2)
                                                    <small class="text-muted">
                                                        và {{ $order->chiTiet->count() - 2 }} sản phẩm khác...
                                                    </small>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div>
                                            @if($order->so_tien_giam_gia > 0)
                                                <div class="text-decoration-line-through text-muted small">
                                                    {{ number_format($order->tong_tien_goc, 0, ',', '.') }}đ
                                                </div>
                                                <div class="fw-bold text-success">
                                                    {{ number_format($order->tong_tien, 0, ',', '.') }}đ
                                                </div>
                                                <small class="text-success">
                                                    Tiết kiệm {{ number_format($order->so_tien_giam_gia, 0, ',', '.') }}đ
                                                </small>
                                            @else
                                                <div class="fw-bold">
                                                    {{ number_format($order->tong_tien, 0, ',', '.') }}đ
                                                </div>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        @php
                                            $statusColors = [
                                                'cho_xac_nhan' => 'warning',
                                                'da_xac_nhan' => 'info',
                                                'dang_giao' => 'primary',
                                                'da_giao' => 'success',
                                                'da_huy' => 'danger'
                                            ];
                                        @endphp
                                        <span class="badge bg-{{ $statusColors[$order->trang_thai] ?? 'secondary' }}">
                                            {{ $order->trang_thai_text }}
                                        </span>
                                        <div class="mt-1">
                                            <small class="text-muted">
                                                {{ $order->phuong_thuc_thanh_toan_text }}
                                            </small>
                                        </div>
                                    </td>
                                    <td>
                                        <div>{{ $order->created_at->format('d/m/Y') }}</div>
                                        <small class="text-muted">{{ $order->created_at->format('H:i') }}</small>
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <a href="{{ route('donhang.show', $order->id) }}" 
                                               class="btn btn-outline-info" title="Xem chi tiết">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            @if($order->canCancel())
                                                <a href="{{ route('donhang.edit', $order->id) }}" 
                                                   class="btn btn-outline-primary" title="Chỉnh sửa">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                            @endif
                                            <div class="btn-group btn-group-sm">
                                                <button type="button" class="btn btn-outline-secondary dropdown-toggle" 
                                                        data-bs-toggle="dropdown" title="Thay đổi trạng thái">
                                                    <i class="fas fa-cog"></i>
                                                </button>
                                                <ul class="dropdown-menu">
                                                    @if($order->trang_thai !== 'da_xac_nhan' && $order->trang_thai !== 'da_giao' && $order->trang_thai !== 'da_huy')
                                                        <li>
                                                            <a class="dropdown-item" 
                                                               href="javascript:void(0)" 
                                                               onclick="updateStatus({{ $order->id }}, 'da_xac_nhan')">
                                                                <i class="fas fa-check text-info me-2"></i>
                                                                Xác nhận
                                                            </a>
                                                        </li>
                                                    @endif
                                                    @if($order->trang_thai === 'da_xac_nhan')
                                                        <li>
                                                            <a class="dropdown-item" 
                                                               href="javascript:void(0)" 
                                                               onclick="updateStatus({{ $order->id }}, 'dang_giao')">
                                                                <i class="fas fa-truck text-primary me-2"></i>
                                                                Bắt đầu giao hàng
                                                            </a>
                                                        </li>
                                                    @endif
                                                    @if($order->trang_thai === 'dang_giao')
                                                        <li>
                                                            <a class="dropdown-item" 
                                                               href="javascript:void(0)" 
                                                               onclick="updateStatus({{ $order->id }}, 'da_giao')">
                                                                <i class="fas fa-check-circle text-success me-2"></i>
                                                                Hoàn thành
                                                            </a>
                                                        </li>
                                                    @endif
                                                    @if($order->canCancel())
                                                        <li><hr class="dropdown-divider"></li>
                                                        <li>
                                                            <a class="dropdown-item text-danger" 
                                                               href="javascript:void(0)" 
                                                               onclick="updateStatus({{ $order->id }}, 'da_huy')">
                                                                <i class="fas fa-times-circle me-2"></i>
                                                                Hủy đơn hàng
                                                            </a>
                                                        </li>
                                                    @endif
                                                </ul>
                                            </div>
                                            <a href="{{ route('donhang.print', $order->id) }}" 
                                               class="btn btn-outline-success" title="In đơn hàng" target="_blank">
                                                <i class="fas fa-print"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                {{ $donHang->appends(request()->query())->links() }}
            @else
                <div class="text-center py-5">
                    <i class="fas fa-shopping-cart fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">Không có đơn hàng nào</h5>
                    <p class="text-muted">Chưa có đơn hàng nào được tạo</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Update order status
    function updateStatus(orderId, newStatus) {
        const statusTexts = {
            'da_xac_nhan': 'xác nhận',
            'dang_giao': 'chuyển sang đang giao',
            'da_giao': 'hoàn thành',
            'da_huy': 'hủy'
        };

        const message = `Bạn có chắc chắn muốn ${statusTexts[newStatus]} đơn hàng này?`;
        
        if (confirm(message)) {
            showLoading();
            
            $.ajax({
                url: `/admin/donhang/${orderId}/update-status`,
                method: 'POST',
                data: {
                    trang_thai: newStatus
                },
                success: function(response) {
                    location.reload();
                },
                error: function(xhr) {
                    const response = xhr.responseJSON;
                    showToast(response.message || 'Có lỗi xảy ra', 'danger');
                },
                complete: function() {
                    hideLoading();
                }
            });
        }
    }
</script>
@endpush